<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Cocur\Slugify\Slugify;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookRepository")
 * @UniqueEntity("title")
 */
class Book
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Ne peut être vide")
     * @Assert\Length(min = 5, minMessage="Le titre doit faire minimum {{ limit }} caractères")
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Length(max = 4, maxMessage="4 chiffres maximum")
     * @Assert\Range(min = 1970, minMessage="Année minimal 1970")
     */
    private $yearBook;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="books", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Ne peut être vide")
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Ne peut être vide")
     */
    private $authorFirstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Ne peut être vide")
     */
    private $authorLastname;

    public function __construct()
    {
        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = ucfirst($title);

        return $this;
    }

    public function getSlug(): string
    {
        return (new Slugify())->slugify($this->title);
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getYearBook(): ?int
    {
        return $this->yearBook;
    }

    public function setYearBook(int $yearBook): self
    {
        $this->yearBook = $yearBook;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getAuthorFirstname(): ?string
    {
        return $this->authorFirstname;
    }

    public function setAuthorFirstname(string $authorFirstname): self
    {
        $this->authorFirstname = ucfirst($authorFirstname);

        return $this;
    }

    public function getAuthorLastname(): ?string
    {
        return $this->authorLastname;
    }

    public function setAuthorLastname(string $authorLastname): self
    {
        $this->authorLastname = ucfirst($authorLastname);

        return $this;
    }

    public function getAuthor(): string
    {
        return $author = $this->getAuthorLastname() . ' ' . $this->getAuthorFirstname();
    }
}
