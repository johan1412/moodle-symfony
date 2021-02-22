<?php

namespace App\Entity;

use App\Repository\StudentAnswerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StudentAnswerRepository::class)
 */
class StudentAnswer
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
     * @ORM\ManyToOne(targetEntity=Question::class, inversedBy="studentAnswers")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $question;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="studentAnswers")
     */
    private $student;

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

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getStudent(): ?User
    {
        return $this->student;
    }

    public function setStudent(?User $student): self
    {
        $this->student = $student;

        return $this;
    }
}
