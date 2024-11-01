<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Enum\InventoryStatusEnum;
use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Attribute\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product implements EntityInterface
{
    use TimestampableEntity;

    #[Groups('product:get')]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[Groups('product:get')]
    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $code;

    #[Groups('product:get')]
    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $name;

    #[Groups('product:get')]
    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $description;

    #[Groups('product:get')]
    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $image;

    #[Vich\UploadableField(mapping: 'products', fileNameProperty: 'image')]
    private ?File $imageFile = null;

    #[Groups('product:get')]
    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $category;

    #[Groups('product:get')]
    #[ORM\Column(type: Types::FLOAT, length: 255)]
    private float $price;

    #[Groups('product:get')]
    #[ORM\Column(type: Types::INTEGER, length: 255)]
    private int $quantity;

    #[Groups('product:get')]
    #[ORM\Column(type: Types::INTEGER, length: 255)]
    private string $internalReference;

    #[Groups('product:get')]
    #[ORM\Column(type: Types::INTEGER)]
    private int $shellId;

    #[Groups('product:get')]
    #[ORM\Column(type: Types::STRING, length: 255, enumType: InventoryStatusEnum::class)]
    private InventoryStatusEnum $inventoryStatus;

    #[Groups('product:get')]
    #[ORM\Column(type: Types::INTEGER)]
    private int $rating;

    public function getId(): int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if ($imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getInternalReference(): string
    {
        return $this->internalReference;
    }

    public function setInternalReference(string $internalReference): static
    {
        $this->internalReference = $internalReference;

        return $this;
    }

    public function getShellId(): int
    {
        return $this->shellId;
    }

    public function setShellId(int $shellId): static
    {
        $this->shellId = $shellId;

        return $this;
    }

    public function getInventoryStatus(): InventoryStatusEnum
    {
        return $this->inventoryStatus;
    }

    public function setInventoryStatus(InventoryStatusEnum $inventoryStatus): static
    {
        $this->inventoryStatus = $inventoryStatus;

        return $this;
    }

    public function getRating(): int
    {
        return $this->rating;
    }

    public function setRating(int $rating): static
    {
        $this->rating = $rating;

        return $this;
    }
}
