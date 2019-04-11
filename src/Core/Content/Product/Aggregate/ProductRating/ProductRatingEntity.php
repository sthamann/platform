<?php declare(strict_types=1);

namespace Shopware\Core\Content\Product\Aggregate\ProductRating;

use Shopware\Core\Checkout\Customer\CustomerEntity;
use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use Shopware\Core\Framework\Language\LanguageEntity;
use Shopware\Core\System\SalesChannel\SalesChannelEntity;

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
     * @var SalesChannelEntity|null
     */
    private $sales_channel;
    private $language;
    private $customer;
    /**
     * @var ProductEntity|null
     */
    private $product;
    /**
     * @var string|null
     */
    private $content;
    /**
     * @var string|null
     */
    private $title;

    //properties#
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

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status): void
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
