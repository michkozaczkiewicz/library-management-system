<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=BookRepository::class)
 */
class Book
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=13)
     */
    private $ISBN;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="date")
     */
    private $year;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Author", inversedBy="book")
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Genre", inversedBy="book")
     */
    private $genre;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Publisher", inversedBy="book")
     */
    private $publisher;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Issue", mappedBy="book")
     */
    private $issue;

    public function __construct()
    {
        $this->issue = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getISBN(): ?string
    {
        return $this->ISBN;
    }

    public function setISBN(string $ISBN): self
    {
        $this->ISBN = $ISBN;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getYear(): ?\DateTimeInterface
    {
        return $this->year;
    }

    public function setYear(\DateTimeInterface $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getGenre(): ?Genre
    {
        return $this->genre;
    }

    public function setGenre(?Genre $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getPublisher(): ?Publisher
    {
        return $this->publisher;
    }

    public function setPublisher(?Publisher $publisher): self
    {
        $this->publisher = $publisher;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return Collection|Issue[]
     */
    public function getIssue(): Collection
    {
        return $this->issue;
    }

    public function addIssue(Issue $issue): self
    {
        if (!$this->issue->contains($issue)) {
            $this->issue[] = $issue;
            $issue->setBook($this);
        }

        return $this;
    }

    public function removeIssue(Issue $issue): self
    {
        if ($this->issue->removeElement($issue)) {
            // set the owning side to null (unless already changed)
            if ($issue->getBook() === $this) {
                $issue->setBook(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return (string)$this->getTitle();
    }
}
