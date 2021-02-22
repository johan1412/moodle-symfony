<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=QuestionRepository::class)
 * @UniqueEntity(
 *      fields={"name"},
 *      message="This name already exist."
 * )
 */
class Question
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $difficulty;

    /**
     * @ORM\ManyToOne(targetEntity=Exercise::class, inversedBy="questions")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $exercises;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=StudentAnswer::class, mappedBy="question")
     */
    private $studentAnswers;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $otherAnswers = [];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $correctAnswer;

    public function __construct()
    {
        $this->studentAnswers = new ArrayCollection();
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

    public function getDifficulty(): ?string
    {
        return $this->difficulty;
    }

    public function setDifficulty(?string $difficulty): self
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getExercises(): ?Exercise
    {
        return $this->exercises;
    }

    public function setExercises(?Exercise $exercises): self
    {
        $this->exercises = $exercises;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|StudentAnswer[]
     */
    public function getStudentAnswers(): Collection
    {
        return $this->studentAnswers;
    }

    public function addStudentAnswer(StudentAnswer $studentAnswer): self
    {
        if (!$this->studentAnswers->contains($studentAnswer)) {
            $this->studentAnswers[] = $studentAnswer;
            $studentAnswer->setQuestion($this);
        }

        return $this;
    }

    public function removeStudentAnswer(StudentAnswer $studentAnswer): self
    {
        if ($this->studentAnswers->contains($studentAnswer)) {
            $this->studentAnswers->removeElement($studentAnswer);
            // set the owning side to null (unless already changed)
            if ($studentAnswer->getQuestion() === $this) {
                $studentAnswer->setQuestion(null);
            }
        }

        return $this;
    }

    public function getCorrectAnswer(): ?string
    {
        return $this->correctAnswer;
    }

    public function setCorrectAnswer(string $correctAnswer): self
    {
        $this->correctAnswer = $correctAnswer;
        return $this;
    }

    public function getOtherAnswers(): ?array
    {
        return $this->otherAnswers;
    }

    public function setOtherAnswers(?array $otherAnswers): self
    {
        $this->otherAnswers = $otherAnswers;

        return $this;
    }

    public function addOtherAnswer(string $otherAnswer){
        $this->otherAnswers[] = $otherAnswer;
    }
}
