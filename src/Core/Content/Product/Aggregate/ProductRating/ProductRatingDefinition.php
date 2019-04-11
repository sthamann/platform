<?php declare(strict_types=1);

namespace Shopware\Core\Content\Product\Aggregate\ProductRating;

use Shopware\Core\Checkout\Customer\CustomerDefinition;
use Shopware\Core\Content\Media\MediaDefinition;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Content\Property\Aggregate\PropertyGroupOption\PropertyGroupOptionDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\CreatedAtField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\DateField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
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

class ProductRatingDefinition extends MappingEntityDefinition
{
    public static function getEntityName(): string
    {
        return 'product_rating';
    }

    public static function getCollectionClass(): string
    {
        return ProductRatingCollection::class;
    }

    public static function getEntityClass(): string
    {
        return ProductRatingEntity::class;
    }

    /**
     * @return FieldCollection
     */
    protected static function defineFields(): FieldCollection
    {

        /**
         *  id	binary(16)
        product_id	binary(16)
        customer_id	binary(16) NULL
        sales_channel_id	binary(16)
        language_id	binary(16)
        external_user	varchar(255)
        external_email	varchar(255)
        title	varchar(255)
        content	text
        positive	int(11)
        negative	int(11)
        points	double
        status	tinyint(4)
        comment	text
        comment_created_at	datetime
        created_at	datetime
        updated_at	datetime

         */

        // Nullable
        // Textfield
        // ManyToOne Cascade Actions
        // Produkte laden Bewertungen Ja / Nein
        // DateTime Field ?
        // API Routen


        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
            new VersionField(),
            new FkField('product_id', 'productId', ProductDefinition::class),
            new FkField('customer_id', 'customerId', CustomerDefinition::class),
            new FkField('sales_channel_id', 'salesChannelId', SalesChannelDefinition::class),
            new FkField('language_id', 'languageId', LanguageDefinition::class),
            new StringField('external_user', 'externalUser'),
            new StringField('external_email', 'externalEmail'),
            new StringField('title', 'title'),
            new LongTextWithHtmlField('content', 'content'),
            new IntField('positive','positive'),
            new IntField('negative','negative'),
            new FloatField('points','points'),
            new BoolField('status','status'),
            new StringField('comment','comment'),
            new DateField('comment_created_at','commentCreatedAt'),
            new UpdatedAtField(),
            new CreatedAtField(),
            new ManyToOneAssociationField('product', 'product_id', ProductDefinition::class, 'id', false),
            new ManyToOneAssociationField('customer','customer_id',CustomerDefinition::class,'id',false),
            new ManyToOneAssociationField('sales_channel','sales_channel_id',SalesChannelDefinition::class,'id',false),
            new ManyToOneAssociationField('language','language_id',LanguageDefinition::class,'id',false),
            (new ReferenceVersionField(ProductDefinition::class))->addFlags(new Required()),
        ]);

       /* return new FieldCollection([
            (new FkField('product_id', 'productId', ProductDefinition::class))->addFlags(new PrimaryKey(), new Required()),
            (new ReferenceVersionField(ProductDefinition::class))->addFlags(new Required()),
            (new FkField('property_group_option_id', 'optionId', PropertyGroupOptionDefinition::class))->addFlags(new PrimaryKey(), new Required()),
            new ManyToOneAssociationField('product', 'product_id', ProductDefinition::class, 'id', false),
            new ManyToOneAssociationField('option', 'property_group_option_id', PropertyGroupOptionDefinition::class, 'id', false),
        ]);*/
    }
}
