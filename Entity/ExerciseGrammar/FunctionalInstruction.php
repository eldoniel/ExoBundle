<?php

namespace UJM\ExoBundle\Entity\ExerciseGrammar;

use Doctrine\ORM\Mapping as ORM;
use UJM\ExoBundle\Entity\Question;

/**
 * UJM\ExoBundle\Entity\ExerciseGrammar\FunctionalInstruction
 *
 * @ORM\Entity
 * @ORM\Table(name="ujm_functional_instruction")
 */
class FunctionalInstruction
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
    * Media UUID
    * @var string
    *
    * @ORM\Column(name="media", type="text")
    */
    private $media;
    
    /**
     *
     * Position of the functional instruction in the list
     * @var integer
     * 
     * @ORM\Column(name="position", type="integer")
     */
    private $position;
    
    /**
     * @ORM\ManyToOne(targetEntity="UJM\ExoBundle\Entity\Question", inversedBy="functionalInstructions")
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id", nullable=true)
     **/
    private $question;

    
    public function __construct()
    {
        $this->position = 1;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
    
    public function getQuestion()
    {
        return $this->question;
    }
    
    public function setQuestion($question) 
    {
        $this->question = $question;
        
        return $this;
    }
    
    public function getMedia()
    {
        return $this->media;
    }
    
    public function setMedia($media)
    {
        $this->media = $media;
        
        return $this;
    }
    
    public function getPosition()
    {
        return $this->position;
    }
    
    public function setPosition($position)
    {
        if ($position === null) {
            $this->position = 0;
        }
        else {
            $this->position = $position;
        }
        
        return $this;
    }
    
}
