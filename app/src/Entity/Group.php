<?php

namespace App\Entity;

use App\Repository\GroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=GroupRepository::class)
 * @ORM\Table(name="`group`")
 * @UniqueEntity(
 *      fields={"name"},
 *      message="This name already exist."
 * )
 */
class Group
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
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="professorGroups")
     */
    private $groupProfessors;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="studentGroup")
     */
    private $groupStudents;

    /**
     * @ORM\ManyToMany(targetEntity=Exercise::class, inversedBy="groups")
     */
    private $exercises;

    public function __construct()
    {
        $this->groupProfessors = new ArrayCollection();
        $this->groupStudents = new ArrayCollection();
        $this->exercises = new ArrayCollection();
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

    /**
     * @return Collection|User[]
     */
    public function getGroupProfessors(): Collection
    {
        return $this->groupProfessors;
    }

    public function addGroupProfessor(User $groupProfessor): self
    {
        if (!$this->groupProfessors->contains($groupProfessor)) {
            $this->groupProfessors[] = $groupProfessor;
            $groupProfessor->addProfessorGroup($this);
        }

        return $this;
    }

    public function removeGroupProfessor(User $groupProfessor): self
    {
        if ($this->groupProfessors->contains($groupProfessor)) {
            $this->groupProfessors->removeElement($groupProfessor);
            $groupProfessor->removeProfessorGroup($this);
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getGroupStudents(): Collection
    {
        return $this->groupStudents;
    }

    public function addGroupStudent(User $groupStudent): self
    {
        if (!$this->groupStudents->contains($groupStudent)) {
            $this->groupStudents[] = $groupStudent;
            $groupStudent->setStudentGroup($this);
        }

        return $this;
    }

    public function removeGroupStudent(User $groupStudent): self
    {
        if ($this->groupStudents->contains($groupStudent)) {
            $this->groupStudents->removeElement($groupStudent);
            // set the owning side to null (unless already changed)
            if ($groupStudent->getStudentGroup() === $this) {
                $groupStudent->setStudentGroup(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Exercise[]
     */
    public function getExercises(): Collection
    {
        return $this->exercises;
    }

    public function addExercise(Exercise $exercise): self
    {
        if (!$this->exercises->contains($exercise)) {
            $this->exercises[] = $exercise;
        }

        return $this;
    }

    public function removeExercise(Exercise $exercise): self
    {
        if ($this->exercises->contains($exercise)) {
            $this->exercises->removeElement($exercise);
        }

        return $this;
    }
}
