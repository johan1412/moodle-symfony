<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(
 *      fields={"email"},
 *      message="This email already exist."
 * )
 */
class User implements UserInterface
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
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;


    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;


    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="users")
     */
    private $comments;


    /**
     * @ORM\OneToOne(targetEntity=Subject::class, mappedBy="users", cascade={"persist"})
     */
    private $subject;

    /**
     * @ORM\ManyToMany(targetEntity=Group::class, inversedBy="groupProfessors")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $professorGroups;

    /**
     * @ORM\ManyToOne(targetEntity=Group::class, inversedBy="groupStudents")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $studentGroup;

    /**
     * @ORM\ManyToMany(targetEntity=Exercise::class, inversedBy="solvedBy")
     */
    private $solvedExercises;

    /**
     * @ORM\OneToMany (targetEntity=StudentAnswer::class, mappedBy="student")
     */
    private $studentAnswers;


    public function __construct()
    {
        $this->exercises = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->professorGroups = new ArrayCollection();
        $this->solvedExercises = new ArrayCollection();
    }




    public function getId(): ?int
    {
        return $this->id;
    }



    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = strtolower($email);

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
            $comment->setUsers($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getUsers() === $this) {
                $comment->setUsers(null);
            }
        }

        return $this;
    }

    public function getFullName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }



    public function getSubject(): ?Subject
    {
        return $this->subject;
    }

    public function setSubject(?Subject $subject): self
    {
        $this->subject = $subject;

        // set (or unset) the owning side of the relation if necessary
        $newUsers = null === $subject ? null : $this;
        if ($subject->getUsers() !== $newUsers) {
            $subject->setUsers($newUsers);
        }

        return $this;
    }

    /**
     * @return Collection|Group[]
     */
    public function getProfessorGroups(): Collection
    {
        return $this->professorGroups;
    }

    public function addProfessorGroup(Group $professorGroup): self
    {
        if (!$this->professorGroups->contains($professorGroup)) {
            $this->professorGroups[] = $professorGroup;
        }

        return $this;
    }

    public function removeProfessorGroup(Group $professorGroup): self
    {
        if ($this->professorGroups->contains($professorGroup)) {
            $this->professorGroups->removeElement($professorGroup);
        }

        return $this;
    }

    public function getStudentGroup(): ?Group
    {
        return $this->studentGroup;
    }

    public function setStudentGroup(?Group $studentGroup): self
    {
        $this->studentGroup = $studentGroup;

        return $this;
    }

    /**
     * @return Collection|Exercise[]
     */
    public function getSolvedExercises(): Collection
    {
        return $this->solvedExercises;
    }

    public function addSolvedExercise(Exercise $solvedExercise): self
    {
        if (!$this->solvedExercises->contains($solvedExercise)) {
            $this->solvedExercises[] = $solvedExercise;
        }

        return $this;
    }

    public function removeSolvedExercise(Exercise $solvedExercise): self
    {
        if ($this->solvedExercises->contains($solvedExercise)) {
            $this->solvedExercises->removeElement($solvedExercise);
        }

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
}
