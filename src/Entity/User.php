<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity('email')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'date')]
    private $birthday;

    #[ORM\Column(type: 'json', nullable: true)]
    private $address;

    #[ORM\Column(name: 'email', type: 'string', length: 255, unique: true)]
    #[Assert\Email]
    private $email;

    #[ORM\Column(type: 'string', length: 255)]
    private $password;

    #[ORM\Column(type: 'integer')]
    private $role;

    #[ORM\Column(type: 'text', nullable: true)]
    private $avatar;

    #[ORM\ManyToOne(targetEntity: Group::class, inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private $studyGroup;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserSubject::class, orphanRemoval: true)]
    private $marks;

    public function __construct()
    {
        $this->marks = new ArrayCollection();
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

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRole(): ?int
    {
        return $this->role;
    }

    public function setRole(int $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getStudyGroup(): ?Group
    {
        return $this->studyGroup;
    }

    public function setStudyGroup(?Group $studyGroup): self
    {
        $this->studyGroup = $studyGroup;

        return $this;
    }

    /**
     * @return Collection|UserSubject[]
     */
    public function getMarks(): Collection
    {
        return $this->marks;
    }

    public function addMark(UserSubject $mark): self
    {
        if (!$this->marks->contains($mark)) {
            $this->marks[] = $mark;
            $mark->setUser($this);
        }

        return $this;
    }

    public function removeMark(UserSubject $mark): self
    {
        if ($this->marks->removeElement($mark)) {
            // set the owning side to null (unless already changed)
            if ($mark->getUser() === $this) {
                $mark->setUser(null);
            }
        }

        return $this;
    }

    public function getAddress(): ?array
    {
        return $this->address;
    }

    public function setAddress(?array $address): self
    {
        $address['city'] = $this->mb_ucfirts($address['city']);
        $address['street'] = $this->mb_ucfirts($address['street']);

        $this->address = $address;

        return $this;
    }

    public function getFullAddress()
    {
        $address = $this->getAddress();

        return $address
            ? $address['city'] . ', ' . $address['street'] . ', ' . $address['house']
            : '';
    }


    private function mb_ucfirts($str, $charset = '')
    {
        if($charset == '') $charset = mb_internal_encoding();
        $letter = mb_strtoupper(mb_substr($str, 0, 1, $charset), $charset);
        $suffix = mb_substr($str, 1, mb_strlen($str, $charset) - 1, $charset);

        return $letter.$suffix;
    }
}
