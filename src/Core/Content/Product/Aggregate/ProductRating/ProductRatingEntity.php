<?php declare(strict_types=1);

namespace Shopware\Core\Content\Product\Aggregate\ProductRating;

use Shopware\Core\Content\Product\ProductCollection;
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

    /**
     * @return mixed
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * @param mixed $productId
     */
    public function setProductId($productId): void
    {
        $this->productId = $productId;
    }

    /**
     * @return mixed
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * @param mixed $customerId
     */
    public function setCustomerId($customerId): void
    {
        $this->customerId = $customerId;
    }

    /**
     * @return mixed
     */
    public function getSalesChannelId()
    {
        return $this->salesChannelId;
    }

    /**
     * @param mixed $salesChannelId
     */
    public function setSalesChannelId($salesChannelId): void
    {
        $this->salesChannelId = $salesChannelId;
    }

    /**
     * @return mixed
     */
    public function getLanguageId()
    {
        return $this->languageId;
    }

    /**
     * @param mixed $languageId
     */
    public function setLanguageId($languageId): void
    {
        $this->languageId = $languageId;
    }

    /**
     * @return mixed
     */
    public function getExternalUser()
    {
        return $this->externalUser;
    }

    /**
     * @param mixed $externalUser
     */
    public function setExternalUser($externalUser): void
    {
        $this->externalUser = $externalUser;
    }

    /**
     * @return mixed
     */
    public function getExternalEmail()
    {
        return $this->externalEmail;
    }

    /**
     * @param mixed $externalEmail
     */
    public function setExternalEmail($externalEmail): void
    {
        $this->externalEmail = $externalEmail;
    }

    /**
     * @return mixed
     */
    public function getPositive()
    {
        return $this->positive;
    }

    /**
     * @param mixed $positive
     */
    public function setPositive($positive): void
    {
        $this->positive = $positive;
    }

    /**
     * @return mixed
     */
    public function getNegative()
    {
        return $this->negative;
    }

    /**
     * @param mixed $negative
     */
    public function setNegative($negative): void
    {
        $this->negative = $negative;
    }

    /**
     * @return mixed
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * @param mixed $points
     */
    public function setPoints($points): void
    {
        $this->points = $points;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param mixed $comment
     */
    public function setComment($comment): void
    {
        $this->comment = $comment;
    }

    /**
     * @return mixed
     */
    public function getCommentCreatedAt()
    {
        return $this->commentCreatedAt;
    }

    /**
     * @param mixed $commentCreatedAt
     */
    public function setCommentCreatedAt($commentCreatedAt): void
    {
        $this->commentCreatedAt = $commentCreatedAt;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTimeInterface|null $updatedAt
     */
    public function setUpdatedAt(?\DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeInterface|null $createdAt
     */
    public function setCreatedAt(?\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

}