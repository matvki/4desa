<?php

namespace App\Entity;

use App\Repository\AccountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use OpenApi\Attributes as OA;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use function PHPUnit\Framework\isEmpty;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
#[OA\Schema(
    properties: [
        new OA\Property(
            property: 'id',
            type: 'integer',
            description: 'The id of the account',
            readOnly: true
        ),
        new OA\Property(
            property: 'email',
            type: 'string',
            description: 'The email of the account',
        ),
        new OA\Property(
            property: 'roles',
            type: 'array',
            description: 'The roles of the account',
            items: new OA\Items(type: 'string')
        ),
        new OA\Property(
            property: 'password',
            type: 'string',
            description: 'The password of the account (hashed when saved)',
        ),
        new OA\Property(
            property: 'pseudo',
            type: 'string',
            description: 'The pseudo of the account',
        ),
        new OA\Property(
            property: 'description',
            type: 'string',
            description: 'The description of the account',
        ),
        new OA\Property(
            property: 'private',
            type: 'boolean',
            description: 'The private status of the account',
        ),
        new OA\Property(
            property: 'posts',
            type: 'array',
            description: 'The posts of the account',
            items: new OA\Items(ref: '#/components/schemas/Post')
        )
    ]
)]
class Account implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private ?string $email;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255, unique: true)]
    private string $pseudo;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private bool $private = true;

    #[ORM\OneToMany(mappedBy: 'belongsTo', targetEntity: Post::class, orphanRemoval: true)]
    #[Groups(["account_details"])]
    private Collection $posts;

    public function __construct()
    {
        $this->posts    = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        if (isEmpty($roles))
            $roles = ['ROLE_USER'];

        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription($description): static
    {
        $this->description = $description;

        return $this;
    }

    public function isPrivate(): bool
    {
        return $this->private;
    }

    public function setPrivate(bool $private): static
    {
        $this->private = $private;

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): static
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setBelongsTo($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getBelongsTo() === $this) {
                $post->setBelongsTo(null);
            }
        }

        return $this;
    }
}
