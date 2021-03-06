<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity("email")
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
	 * @Assert\NotBlank(message="Ce champ ne peut pas être vide")
	 */
	private $surname;

	/**
	 * @ORM\Column(type="string", length=255)
	 * @Assert\NotBlank(message="Ce champ ne peut pas être vide")
	 */
	private $firstname;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
	 * @Assert\NotBlank(message="Ce champ ne peut pas être vide")
	 * @Assert\Email(message="Veuillez entrer une adresse mail valide")
     */
    private $email;

	/**
	 * @var string The hashed password
	 * @ORM\Column(type="string")
	 */
	private $password;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity=Task::class, mappedBy="user")
     */
    private $tasks;

    public function __construct()
	{
		$this->createdAt = new \DateTime();
  		$this->tasks = new ArrayCollection();
	}

	public function __toString()
	{
		return sprintf('%s', $this->surname);
	}

	public function getId(): ?int
                     	{
                     		return $this->id;
                     	}

	public function getSurname(): ?string
                     	{
                     		return $this->surname;
                     	}

	public function setSurname(string $surname): self
                     	{
                     		$this->surname = $surname;
                     
                     		return $this;
                     	}

	public function getFirstname(): ?string
                     	{
                     		return $this->firstname;
                     	}

	public function setFirstname(string $firstname): self
                     	{
                     		$this->firstname = $firstname;
                     
                     		return $this;
                     	}

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

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
        $roles[] = 'ROLE_USER';

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection|Task[]
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
            $task->setUser($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getUser() === $this) {
                $task->setUser(null);
            }
        }

        return $this;
    }

}
