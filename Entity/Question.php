<?php

namespace UJM\ExoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UJM\ExoBundle\Entity\ExerciseGrammar\Instruction;
use UJM\ExoBundle\Entity\ExerciseGrammar\Content;
use UJM\ExoBundle\Entity\ExerciseGrammar\ComplementaryInformation;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * UJM\ExoBundle\Entity\Question
 *
 * @ORM\Entity(repositoryClass="UJM\ExoBundle\Repository\QuestionRepository")
 * @ORM\Table(name="ujm_question")
 */
class Question
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
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var text $description
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var datetime $dateCreate
     *
     * @ORM\Column(name="date_create", type="datetime")
     */
    private $dateCreate;

    /**
     * @var datetime $dateModify
     *
     * @ORM\Column(name="date_modify", type="datetime", nullable=true)
     */
    private $dateModify;

    /**
     * @var boolean $locked
     *
     * @ORM\Column(name="locked", type="boolean", nullable=true)
     */
    private $locked;

    /**
     * @var boolean $model
     *
     * @ORM\Column(name="model", type="boolean", nullable=true)
     */
    private $model;

    /**
     * @ORM\ManyToOne(targetEntity="UJM\ExoBundle\Entity\Expertise")
     */
    private $expertise;

    /**
     * @ORM\ManyToMany(targetEntity="UJM\ExoBundle\Entity\Document")
     * @ORM\JoinTable(
     *     name="ujm_document_question",
     *     joinColumns={
     *         @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     *     },
     *     inverseJoinColumns={
     *         @ORM\JoinColumn(name="document_id", referencedColumnName="id")
     *     }
     * )
     */
    private $documents;

    /**
     * @ORM\ManyToOne(targetEntity="Claroline\CoreBundle\Entity\User")
     */
    private $user;

     /**
     * @ORM\ManyToOne(targetEntity="UJM\ExoBundle\Entity\Category")
     */
    private $category;
    
    /**
     * @ORM\OneToMany(targetEntity="UJM\ExoBundle\Entity\ExerciseGrammar\Instruction", mappedBy="question", cascade={"persist","remove"})
     */
    private $instructions;
    
    /**
     * @ORM\OneToMany(targetEntity="UJM\ExoBundle\Entity\ExerciseGrammar\Content", mappedBy="question", cascade={"persist","remove"})
     */
    private $contents;
    
    /**
     * @ORM\OneToMany(targetEntity="UJM\ExoBundle\Entity\ExerciseGrammar\ComplementaryInformation", mappedBy="question", cascade={"persist","remove"})
     */
    private $complementaryInformations;

    /**
     * Constructs a new instance of Expertises / Documents
     */
    public function __construct()
    {
        $this->documents = new \Doctrine\Common\Collections\ArrayCollection;
        $this->setLocked(false);
        $this->setModel(false);
        $this->instructions = new ArrayCollection();
        $this->contents = new ArrayCollection();
        $this->complementaryInformations = new ArrayCollection();
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

    /**
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param text $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return text
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set dateCreate
     *
     * @param datetime $dateCreate
     */
    public function setDateCreate($dateCreate)
    {
        $this->dateCreate = $dateCreate;
    }

    /**
     * Get dateCreate
     *
     * @return datetime
     */
    public function getDateCreate()
    {
        return $this->dateCreate;
    }

    /**
     * Set dateModify
     *
     * @param datetime $dateModify
     */
    public function setDateModify($dateModify)
    {
        $this->dateModify = $dateModify;
    }

    /**
     * Get dateModify
     *
     * @return datetime
     */
    public function getDateModify()
    {
        return $this->dateModify;
    }

    /**
     * Set locked
     *
     * @param boolean $locked
     */
    public function setLocked($locked)
    {
        $this->locked = $locked;
    }

    /**
     * Get locked
     */
    public function getLocked()
    {
        return $this->locked;
    }

    /**
     * Set model
     *
     * @param boolean $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * Get model
     */
    public function getModel()
    {
        return $this->model;
    }

    public function getExpertise()
    {
        return $this->expertise;
    }

    public function setExpertise(\UJM\ExoBundle\Entity\Expertise $expertise)
    {
        $this->expertise = $expertise;
    }

    /**
     * Gets an array of Documents.
     *
     * @return array An array of Documents objects
     */
    public function getDocuments()
    {
        return $this->documents;
    }

    /**
     * Add $Document
     *
     * @param UJM\ExoBundle\Entity\Document $Document
     */
    public function addDocument(\UJM\ExoBundle\Entity\Document $document)
    {
        $this->document[] = $document;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser(\Claroline\CoreBundle\Entity\User $user)
    {
        $this->user = $user;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory(\UJM\ExoBundle\Entity\Category $category)
    {
        $this->category = $category;
    }
    
    public function getInstructions()
    {
        return $this->instructions;
    }
    
    public function setInstructions(ArrayCollection $instructions)
    {
        foreach ($instructions as $instruction) {
            $this->addInstruction($instruction);
        }
        
        return $this;
    }
    
    public function addInstruction(Instruction $instruction)
    {
        if (!$this->instructions->contains($instruction)) {
            $this->instructions->add($instruction);
            $instruction->setQuestion($this);
        }
        
        return $this;
    }
    
    
    public function addInstructions(ArrayCollection $instructions)
    {
        foreach ($instructions as $instruction) {
            if (!$this->instructions->contains($instruction)) {
                $this->instructions->add($instruction);
                $instruction->setQuestion($this);
            }
        }
        
        return $this;
    }
    
    public function removeInstruction(Instruction $instruction)
    {
        if ($this->instructions->contains($instruction)) {
            $this->instructions->removeElement($instruction);
            $instruction->setQuestion(null);
        }
        
        return $this;
    }
    
    public function getContents()
    {
        return $this->contents;
    }
    
    public function setContents(ArrayCollection $contents)
    {
        foreach ($contents as $content) {
            $this->addContent($content);
        }
        
        return $this;
    }
    
    public function addContent(Content $content)
    {
        if (!$this->contents->contains($content)) {
            $this->contents->add($content);
            $content->setQuestion($this);
        }
        
        return $this;
    }
    
    
    public function addContents(ArrayCollection $contents)
    {
        foreach ($contents as $content) {
            if (!$this->contents->contains($content)) {
                $this->contents->add($content);
                $content->setQuestion($this);
            }
        }
        
        return $this;
    }
    
    public function removeContent(Content $content)
    {
        if ($this->contents->contains($content)) {
            $this->contents->removeElement($content);
            $content->setQuestion(null);
        }
        
        return $this;
    }
    
    public function getComplementaryInformations()
    {
        return $this->complementaryInformations;
    }
    
    public function setComplementaryInformations(ArrayCollection $complementaryInformations)
    {
        foreach ($complementaryInformations as $complementaryInformation) {
            $this->addComplementaryInformation($complementaryInformation);
        }
        
        return $this;
    }
    
    public function addComplementaryInformation(ComplementaryInformation $complementaryInformation)
    {
        if (!$this->complementaryInformations->contains($complementaryInformation)) {
            $this->complementaryInformations->add($complementaryInformation);
            $complementaryInformation->setQuestion($this);
        }
        
        return $this;
    }
    
    
    public function addComplementaryInformations(ArrayCollection $complementaryInformations)
    {
        foreach ($complementaryInformations as $complementaryInformation) {
            if (!$this->complementaryInformations->contains($complementaryInformation)) {
                $this->complementaryInformations->add($complementaryInformation);
                $complementaryInformation->setQuestion($this);
            }
        }
        
        return $this;
    }
    
    public function removeComplementaryInformation(ComplementaryInformation $complementaryInformation)
    {
        if ($this->complementaryInformations->contains($complementaryInformation)) {
            $this->complementaryInformations->removeElement($complementaryInformation);
            $complementaryInformation->setQuestion(null);
        }
        
        return $this;
    }
}
