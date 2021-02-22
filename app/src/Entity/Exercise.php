<?php

namespace App\Entity;

use App\Repository\ExerciseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ExerciseRepository::class)
 * @UniqueEntity(
 *      fields={"name"},
 *      message="This name already exist."
 * )
 */
class Exercise
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Question::class, mappedBy="exercises")
     */
    private $questions;


    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="exercises", cascade={"persist", "remove"})
     */
    private $comments;

    /**
     * @ORM\ManyToOne(targetEntity=Subject::class, inversedBy="exercises")
     * @ORM\JoinColumn(nullable=false)
     */
    private $subject;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="solvedExercises")
     */
    private $solvedBy;

    /**
     * @ORM\ManyToMany(targetEntity=Group::class, mappedBy="exercises")
     */
    private $groups;


    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->solvedBy = new ArrayCollection();
        $this->groups = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }


    /**
     * @return Collection|Question[]
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setExercises($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->contains($question)) {
            $this->questions->removeElement($question);
            // set the owning side to null (unless already changed)
            if ($question->getExercises() === $this) {
                $question->setExercises(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setExercises($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getExercises() === $this) {
                $comment->setExercises(null);
            }
        }

        return $this;
    }

    public function getSubject(): ?Subject
    {
        return $this->subject;
    }

    public function setSubject(?Subject $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getSolvedBy(): Collection
    {
        return $this->solvedBy;
    }

    public function addSolvedBy(User $solvedBy): self
    {
        if (!$this->solvedBy->contains($solvedBy)) {
            $this->solvedBy[] = $solvedBy;
            $solvedBy->addSolvedExercise($this);
        }
        return $this;
    }
    
    /*
     * @return Collection|Group[]
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroup(Group $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups[] = $group;
            $group->addExercise($this);
        }

        return $this;
    }

    public function removeSolvedBy(User $solvedBy): self
    {
        if ($this->solvedBy->contains($solvedBy)) {
            $this->solvedBy->removeElement($solvedBy);
            $solvedBy->removeSolvedExercise($this);
        }
        return $this;
    }


    public function removeGroup(Group $group): self
    {
        if ($this->groups->contains($group)) {
            $this->groups->removeElement($group);
            $group->removeExercise($this);
        }

        return $this;
    }
}
