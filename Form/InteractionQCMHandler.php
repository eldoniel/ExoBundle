<?php

namespace UJM\ExoBundle\Form;

use Doctrine\Common\Collections\ArrayCollection;

class InteractionQCMHandler extends \UJM\ExoBundle\Form\InteractionHandler
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
     * @param \UJM\ExoBundle\Entity\InteractionQCM $interQCM
     */
    protected function onSuccessAdd($interQCM)
    {

        // \ pour instancier un objet du namespace global et non pas de l'actuel
        $interQCM->getInteraction()->getQuestion()->setDateCreate(new \Datetime());
        $interQCM->getInteraction()->getQuestion()->setUser($this->user);
        $interQCM->getInteraction()->setType('InteractionQCM');

        $pointsWrong = str_replace(',', '.', $interQCM->getScoreFalseResponse());
        $pointsRight = str_replace(',', '.', $interQCM->getScoreRightResponse());

        $interQCM->setScoreFalseResponse($pointsWrong);
        $interQCM->setScoreRightResponse($pointsRight);

        $this->em->persist($interQCM);
        $this->em->persist($interQCM->getInteraction()->getQuestion());
        $this->em->persist($interQCM->getInteraction());

        // On persiste tous les choices de l'interaction QCM.
        $ord = 1;
        foreach ($interQCM->getChoices() as $choice) {
            $choice->setOrdre($ord);
            $choice->setInteractionQCM($interQCM);
            $this->em->persist($choice);
            $ord = $ord + 1;
        }

        $this->persistHints($interQCM);

        $this->em->flush();

        $this->addAnExercise($interQCM);

        $this->duplicateInter($interQCM);

    }

    /**
     * Implements the abstract method
     *
     * @access public
     *
     * @param \UJM\ExoBundle\Entity\InteractionQCM $originalInterQCM
     *
     * Return boolean
     */
    public function processUpdate($originalInterQCM)
    {
        $originalChoices = array();
        $originalHints = array();
        
        // Create an array of the current Choice objects in the database
        foreach ($originalInterQCM->getChoices() as $choice) {
            $originalChoices[] = $choice;
        }
        foreach ($originalInterQCM->getInteraction()->getHints() as $hint) {
            $originalHints[] = $hint;
        }

        if ( $this->request->getMethod() == 'POST' ) {
            $this->form->handleRequest($this->request);

            if ( $this->form->isValid() ) {
                $this->onSuccessUpdate($this->form->getData(), $originalChoices, $originalHints);

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
        $interQCM = $arg_list[0];
        $originalChoices = $arg_list[1];
        $originalHints = $arg_list[2];
  
        // the following allows to save instructions/contents/complementary informations
        
        $instructions = new ArrayCollection();
        $contents = new ArrayCollection();
        $complementaryInformations = new ArrayCollection();
        $functionalInstructions = new ArrayCollection();
        $question = $interQCM->getInteraction()->getQuestion();
        
        foreach ($question->getInstructions() as $instruction) {
            $instructions->add($instruction);
        }
        foreach ($question->getContents() as $content) {
            $contents->add($content);
        }
        foreach ($question->getComplementaryInformations() as $complementaryInformation) {
            $complementaryInformations->add($complementaryInformation);
        }
        foreach ($question->getFunctionalInstructions() as $functionalInstruction) {
            $functionalInstructions->add($functionalInstruction);
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
        for ($i=0; $i<count($functionalInstructions); $i++) {
            $functionalInstructions->get($i)->setQuestion($question);
        }

        $question->setInstructions($instructions);
        $question->setContents($contents);
        $question->setComplementaryInformations($complementaryInformations);
        $question->setFunctionalInstructions($functionalInstructions);
        
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
        foreach ($question->getFunctionalInstructions() as $functionalInstruction) {
            if ($functionalInstruction->getMedia() === null || $functionalInstruction->getMedia() === "") {
                $question->removeFunctionalInstruction($functionalInstruction);
            }
        }
        
        $interQCM->getInteraction()->setQuestion($question);

        // filter $originalChoices to contain choice no longer present
        foreach ($interQCM->getChoices() as $choice) {
            foreach ($originalChoices as $key => $toDel) {
                if ($toDel->getId() == $choice->getId()) {
                    unset($originalChoices[$key]);
                }
            }
        }

        // remove the relationship between the choice and the interactionqcm
        foreach ($originalChoices as $choice) {
            // remove the choice from the interactionqcm
            $interQCM->getChoices()->removeElement($choice);

            // if you wanted to delete the Choice entirely, you can also do that
            $this->em->remove($choice);
        }

        $this->modifyHints($interQCM, $originalHints);

        $pointsWrong = str_replace(',', '.', $interQCM->getScoreFalseResponse());
        $pointsRight = str_replace(',', '.', $interQCM->getScoreRightResponse());

        $interQCM->setScoreFalseResponse($pointsWrong);
        $interQCM->setScoreRightResponse($pointsRight);

        $this->em->persist($interQCM);
        $this->em->persist($interQCM->getInteraction()->getQuestion());
        $this->em->persist($interQCM->getInteraction());

        // On persiste tous les choices de l'interaction QCM.
        foreach ($interQCM->getChoices() as $choice) {
            $interQCM->addChoice($choice);
            $this->em->persist($choice);
        }

        $this->em->flush();

    }
}
