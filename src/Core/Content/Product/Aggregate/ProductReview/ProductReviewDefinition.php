<?php declare(strict_types=1);

namespace Shopware\Core\Content\Product\Aggregate\ProductReview;

use Shopware\Core\Checkout\Customer\CustomerDefinition;
use Shopware\Core\Content\Media\MediaDefinition;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Content\Property\Aggregate\PropertyGroupOption\PropertyGroupOptionDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\AttributesField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\CreatedAtField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\DateField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\CascadeDelete;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\SearchRanking;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FloatField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\LongTextWithHtmlField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\VersionField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IntField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ReferenceVersionField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\UpdatedAtField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\MappingEntityDefinition;
use Shopware\Core\Framework\Language\LanguageDefinition;
use Shopware\Core\System\SalesChannel\SalesChannelDefinition;

class ProductReviewDefinition extends EntityDefinition
{
    public static function getEntityName(): string
    {
        return 'product_review';
    }

    public static function getCollectionClass(): string
    {
        return ProductReviewCollection::class;
    }

    public static function getEntityClass(): string
    {
        return ProductReviewEntity::class;
    }

    /**
     * @return FieldCollection
     */
    protected static function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
            new FkField('product_id', 'productId', ProductDefinition::class),
            new FkField('customer_id', 'customerId', CustomerDefinition::class),
            new FkField('sales_channel_id', 'salesChannelId', SalesChannelDefinition::class),
            new FkField('language_id', 'languageId', LanguageDefinition::class),
            (new StringField('external_user', 'externalUser'))->addFlags(new SearchRanking(SearchRanking::MIDDLE_SEARCH_RANKING)),
            (new StringField('external_email', 'externalEmail'))->addFlags(new SearchRanking(SearchRanking::MIDDLE_SEARCH_RANKING)),
            (new StringField('title', 'title'))->addFlags(new SearchRanking(SearchRanking::LOW_SEARCH_RAKING)),
            (new LongTextWithHtmlField('content', 'content'))->addFlags(new SearchRanking(SearchRanking::LOW_SEARCH_RAKING)),
            new IntField('positive','positive'),
            new IntField('negative','negative'),
            new FloatField('points','points'),
            new BoolField('status','status'),
            new StringField('comment','comment'),
            new DateField('comment_created_at','commentCreatedAt'),
            new AttributesField(),
            new UpdatedAtField(),
            new CreatedAtField(),
            (new ManyToOneAssociationField('product', 'product_id', ProductDefinition::class, 'id', false))->addFlags(new CascadeDelete()),
            (new ManyToOneAssociationField('customer','customer_id',CustomerDefinition::class,'id',false))->addFlags(new CascadeDelete(),new SearchRanking(SearchRanking::MIDDLE_SEARCH_RANKING)),
            (new ManyToOneAssociationField('sales_channel','sales_channel_id',SalesChannelDefinition::class,'id',false))->addFlags(new CascadeDelete()),
            (new ManyToOneAssociationField('language','language_id',LanguageDefinition::class,'id',false))->addFlags(new CascadeDelete()),
            (new ReferenceVersionField(ProductDefinition::class))->addFlags(new Required()),
        ]);
    }
}
