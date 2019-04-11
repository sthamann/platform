<?php declare(strict_types=1);

namespace Shopware\Core\Content\Product\Aggregate\ProductRating;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Checkout\Customer\CustomerEntity;
use Shopware\Core\System\SalesChannel\SalesChannelEntity;
use Shopware\Core\Framework\Language\LanguageEntity;

class productRatingEntity extends Entity
{
    use EntityIdTrait;
    
    #properties#
        public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }
    public function getProductId(): ?string
    {
        return $this->productId;
    }

    public function setProductId(?string $productId): void
    {
        $this->productId = $productId;
    }
    public function getCustomerId(): ?string
    {
        return $this->customerId;
    }

    public function setCustomerId(?string $customerId): void
    {
        $this->customerId = $customerId;
    }
    public function getSalesChannelId(): ?string
    {
        return $this->salesChannelId;
    }

    public function setSalesChannelId(?string $salesChannelId): void
    {
        $this->salesChannelId = $salesChannelId;
    }
    public function getLanguageId(): ?string
    {
        return $this->languageId;
    }

    public function setLanguageId(?string $languageId): void
    {
        $this->languageId = $languageId;
    }
    public function getExternalUser(): ?string
    {
        return $this->externalUser;
    }

    public function setExternalUser(?string $externalUser): void
    {
        $this->externalUser = $externalUser;
    }
    public function getExternalEmail(): ?string
    {
        return $this->externalEmail;
    }

    public function setExternalEmail(?string $externalEmail): void
    {
        $this->externalEmail = $externalEmail;
    }
    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }
    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): void
    {
        $this->content = $content;
    }
    public function getPositive(): ?int
    {
        return $this->positive;
    }

    public function setPositive(?int $positive): void
    {
        $this->positive = $positive;
    }
    public function getNegative(): ?int
    {
        return $this->negative;
    }

    public function setNegative(?int $negative): void
    {
        $this->negative = $negative;
    }
    public function getPoints(): ?float
    {
        return $this->points;
    }

    public function setPoints(?float $points): void
    {
        $this->points = $points;
    }
    public function getStatus(): ?boolean
    {
        return $this->status;
    }

    public function setStatus(?boolean $status): void
    {
        $this->status = $status;
    }
    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }
    public function getCommentCreatedAt(): ?\DateTime
    {
        return $this->commentCreatedAt;
    }

    public function setCommentCreatedAt(?\DateTime $commentCreatedAt): void
    {
        $this->commentCreatedAt = $commentCreatedAt;
    }
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
    public function getProduct(): ?ProductEntity
    {
        return $this->product;
    }

    public function setProduct(?ProductEntity $product): void
    {
        $this->product = $product;
    }
    public function getCustomer(): ?CustomerEntity
    {
        return $this->customer;
    }

    public function setCustomer(?CustomerEntity $customer): void
    {
        $this->customer = $customer;
    }
    public function getSales_channel(): ?SalesChannelEntity
    {
        return $this->sales_channel;
    }

    public function setSales_channel(?SalesChannelEntity $sales_channel): void
    {
        $this->sales_channel = $sales_channel;
    }
    public function getLanguage(): ?LanguageEntity
    {
        return $this->language;
    }

    public function setLanguage(?LanguageEntity $language): void
    {
        $this->language = $language;
    }
}