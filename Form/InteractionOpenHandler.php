<?php

namespace UJM\ExoBundle\Form;

use Doctrine\Common\Collections\ArrayCollection;

class InteractionOpenHandler extends \UJM\ExoBundle\Form\InteractionHandler
{

     /**
     * Implements the abstract method
     *
     * @access public
     *
     */
    public function processAdd()
    {
        if ( $this->request->getMethod() == 'POST' ) {
            $this->form->handleRequest($this->request);
            //Uses the default category if no category selected
            $this->checkCategory();
            $this->checkTitle();
            if($this->validateNbClone() === FALSE) {
                    return 'infoDuplicateQuestion';
            }
            if ( $this->form->isValid() ) {
                    $this->onSuccessAdd($this->form->getData());
                    return true;
            }
        }

        return false;
    }

    /**
     * Implements the abstract method
     *
     * @access protected
     *
     * @param \UJM\ExoBundle\Entity\InteractionOpen $interOpen
     */
    protected function onSuccessAdd($interOpen)
    {
        $interOpen->getInteraction()->getQuestion()->setDateCreate(new \Datetime());
        $interOpen->getInteraction()->getQuestion()->setUser($this->user);
        $interOpen->getInteraction()->setType('InteractionOpen');

        $this->em->persist($interOpen);
        $this->em->persist($interOpen->getInteraction()->getQuestion());
        $this->em->persist($interOpen->getInteraction());

        foreach ($interOpen->getWordResponses() as $wr) {
            $wr->setInteractionOpen($interOpen);
            $this->em->persist($wr);
        }

        $this->persistHints($interOpen);

        $this->em->flush();

        $this->addAnExercise($interOpen);

        $this->duplicateInter($interOpen);

    }

    /**
     * Implements the abstract method
     *
     * @access public
     *
     * @param \UJM\ExoBundle\Entity\InteractionOpen $originalInterOpen
     *
     * Return boolean
     */
    public function processUpdate($originalInterOpen)
    {
        $originalWrs = array();
        $originalHints = array();

        foreach ($originalInterOpen->getWordResponses() as $wr) {
            $originalWrs[] = $wr;
        }
        foreach ($originalInterOpen->getInteraction()->getHints() as $hint) {
            $originalHints[] = $hint;
        }

        if ( $this->request->getMethod() == 'POST' ) {
            $this->form->handleRequest($this->request);

            if ( $this->form->isValid() ) {
                $this->onSuccessUpdate($this->form->getData(), $originalWrs, $originalHints);

                return true;
            }
        }

        return false;
    }

    /**
     * Implements the abstract method
     *
     * @access protected
     *
     */
    protected function onSuccessUpdate()
    {
        $arg_list = func_get_args();
        $interOpen = $arg_list[0];
        $originalWrs = $arg_list[1];
        $originalHints = $arg_list[2];
        
        // the following allows to save instructions/contents/complementary informations
        
        $instructions = new ArrayCollection();
        $contents = new ArrayCollection();
        $complementaryInformations = new ArrayCollection();
        $question = $interOpen->getInteraction()->getQuestion();
        
        foreach ($question->getInstructions() as $instruction) {
            $instructions->add($instruction);
        }
        foreach ($question->getContents() as $content) {
            $contents->add($content);
        }
        foreach ($question->getComplementaryInformations() as $complementaryInformation) {
            $complementaryInformations->add($complementaryInformation);
        }
        
        for ($i=0; $i<count($instructions); $i++) {
            $instructions->get($i)->setQuestion($question);
        }
        for ($i=0; $i<count($contents); $i++) {
            $contents->get($i)->setQuestion($question);
        }
        for ($i=0; $i<count($complementaryInformations); $i++) {
            $complementaryInformations->get($i)->setQuestion($question);
        }

        $question->setInstructions($instructions);
        $question->setContents($contents);
        $question->setComplementaryInformations($complementaryInformations);
        
        foreach ($question->getInstructions() as $instruction) {
            if ($instruction->getMedia() === null || $instruction->getMedia() === "") {
                $question->removeInstruction($instruction);
            }
        }
        foreach ($question->getContents() as $content) {
            if ($content->getMedia() === null || $content->getMedia() === "") {
                $question->removeContent($content);
            }
        }
        foreach ($question->getComplementaryInformations() as $complementaryInformation) {
            if ($complementaryInformation->getMedia() === null || $complementaryInformation->getMedia() === "") {
                $question->removeComplementaryInformation($complementaryInformation);
            }
        }
        
        $interOpen->getInteraction()->setQuestion($question);

        foreach ($interOpen->getWordResponses() as $wr) {
            foreach ($originalWrs as $key => $toDel) {
                if ($toDel->getId() == $wr->getId()) {
                    unset($originalWrs[$key]);
                }
            }
        }

        foreach ($originalWrs as $wr) {
            $interOpen->getWordResponses()->removeElement($wr);
            $this->em->remove($wr);
        }

        $this->modifyHints($interOpen, $originalHints);

        $this->em->persist($interOpen);
        $this->em->persist($interOpen->getInteraction()->getQuestion());
        $this->em->persist($interOpen->getInteraction());

        foreach ($interOpen->getWordResponses() as $wr) {
            $interOpen->addWordResponse($wr);
            $this->em->persist($wr);
        }

        $this->em->flush();

    }
}
