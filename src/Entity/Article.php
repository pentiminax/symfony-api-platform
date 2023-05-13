<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Filter\ArticleQueryFilter;
use App\Repository\ArticleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\String\Slugger\SluggerInterface;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity('slug')]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => ['article:read']]),
        new GetCollection(normalizationContext: ['groups' => ['article:list']]),
        new Patch(normalizationContext: ['groups' => ['article:read']], denormalizationContext: ['groups' => ['article:write']], security: 'object.getUser() == user'),
        new Post(normalizationContext: ['groups' => ['article:read']], denormalizationContext: ['groups' => ['article:write']]),
    ]
)]
#[ApiFilter(ArticleQueryFilter::class, strategy: 'ipartial')]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['article:list', 'article:read', 'user:list', 'user:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['article:list', 'article:read', 'article:write', 'user:read'])]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Groups(['article:list', 'article:read', 'article:write', 'user:read'])]
    private ?string $description = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Groups(['article:list', 'article:read', 'user:read'])]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['article:list', 'article:read', 'article:write', 'user:read'])]
    private ?string $body = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['article:list', 'article:read'])]
    private ?User $user = null;

    #[ORM\Column]
    #[Groups(['article:list', 'article:read', 'user:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['article:list', 'article:read', 'user:read'])]
    private ?\DateTimeImmutable $editedAt = null;

    #[ORM\Column]
    #[Groups(['article:list', 'article:read', 'article:write', 'user:read'])]
    private ?\DateTimeImmutable $publishedAt = null;

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function computeSlug(SluggerInterface $slugger): void
    {
        if (!$this->slug || '-' === $this->slug) {
            $this->slug = $slugger->slug($this)->lower();
        }
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
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

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


    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getEditedAt(): ?\DateTimeImmutable
    {
        return $this->editedAt;
    }

    public function setEditedAt(?\DateTimeImmutable $editedAt): self
    {
        $this->editedAt = $editedAt;

        return $this;
    }

    public function __toString(): string
    {
        return $this->title;
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(\DateTimeImmutable $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }
}
