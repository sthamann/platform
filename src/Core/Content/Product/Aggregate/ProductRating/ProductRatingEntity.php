<?php declare(strict_types=1);

namespace Shopware\Core\Content\Product\Aggregate\ProductRating;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class ProductRatingEntity extends Entity
{
    use EntityIdTrait;
    /**
     *  (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
    new VersionField(),
    new FkField('product_id', 'productId', ProductDefinition::class),
    new FkField('customer_id', 'customerId', CustomerDefinition::class),
    new FkField('sales_channel_id', 'salesChannelId', SalesChannelDefinition::class),
    new FkField('language_id', 'languageId', LanguageDefinition::class),
    new StringField('external_user', 'external_user'),
    new StringField('external_email', 'external_email'),
    new StringField('title', 'title'),
    new LongTextWithHtmlField('content', 'content'),
    new IntField('positive','positive'),
    new IntField('negative','negative'),
    new FloatField('points','points'),
    new BoolField('status','status'),
    new StringField('comment','comment'),
    new DateField('comment_created_at','comment_created_at'),
    new UpdatedAtField(),
    new CreatedAtField(),
    new ManyToOneAssociationField('product', 'product_id', ProductDefinition::class, 'id', false),
    new ManyToOneAssociationField('customer','customer_id',CustomerDefinition::class,'id',false),
    new ManyToOneAssociationField('sales_channel','sales_channel_id',SalesChannelDefinition::class,'id',false),
    new ManyToOneAssociationField('language','language_id',LanguageDefinition::class,'id',false),
    (new ReferenceVersionField(ProductDefinition::class))->addFlags(new Required()),
     */
    protected $productId;

    protected $customerId;

    protected $salesChannelId;

    protected $languageId;

    protected $externalUser;

    protected $externalEmail;

    protected $positive;

    protected $negative;

    protected $points;

    protected $status;

    protected $comment;

    protected $commentCreatedAt;

    /**
     * @var \DateTimeInterface|null
     */
    protected $updatedAt;

    /**
     * @var \DateTimeInterface|null
     */
    protected $createdAt;

    public function getProductId()
    {
        return $this->productId;
    }

    public function setProductId($productId): void
    {
        $this->productId = $productId;
    }

    public function getCustomerId()
    {
        return $this->customerId;
    }

    public function setCustomerId($customerId): void
    {
        $this->customerId = $customerId;
    }

    public function getSalesChannelId()
    {
        return $this->salesChannelId;
    }

    public function setSalesChannelId($salesChannelId): void
    {
        $this->salesChannelId = $salesChannelId;
    }

    public function getLanguageId()
    {
        return $this->languageId;
    }

    public function setLanguageId($languageId): void
    {
        $this->languageId = $languageId;
    }

    public function getExternalUser()
    {
        return $this->externalUser;
    }

    public function setExternalUser($externalUser): void
    {
        $this->externalUser = $externalUser;
    }

    public function getExternalEmail()
    {
        return $this->externalEmail;
    }

    public function setExternalEmail($externalEmail): void
    {
        $this->externalEmail = $externalEmail;
    }

    public function getPositive()
    {
        return $this->positive;
    }

    public function setPositive($positive): void
    {
        $this->positive = $positive;
    }

    public function getNegative()
    {
        return $this->negative;
    }

    public function setNegative($negative): void
    {
        $this->negative = $negative;
    }

    public function getPoints()
    {
        return $this->points;
    }

    public function setPoints($points): void
    {
        $this->points = $points;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status): void
    {
        $this->status = $status;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function setComment($comment): void
    {
        $this->comment = $comment;
    }

    public function getCommentCreatedAt()
    {
        return $this->commentCreatedAt;
    }

    public function setCommentCreatedAt($commentCreatedAt): void
    {
        $this->commentCreatedAt = $commentCreatedAt;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
