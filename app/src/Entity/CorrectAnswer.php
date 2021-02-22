<?php

namespace App\Entity;

use App\Repository\CorrectAnswerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CorrectAnswerRepository::class)
 */
class CorrectAnswer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $result;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $parameters;

    /**
     * @ORM\OneToOne(targetEntity=Question::class, mappedBy="correctAnswer", cascade={"persist", "remove"})
     */
    private $question;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getResult(): ?string
    {
        return $this->result;
    }

    public function setResult(string $result): self
    {
        $this->result = $result;

        return $this;
    }

    public function getParameters(): ?string
    {
        return $this->parameters;
    }

    public function setParameters(?string $parameters): self
    {
        $this->parameters = $parameters;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(Question $question): self
    {
        $this->question = $question;

        // set the owning side of the relation if necessary
        if ($question->getCorrectAnswer() !== $this) {
            $question->setCorrectAnswer($this);
        }

        return $this;
    }
}
