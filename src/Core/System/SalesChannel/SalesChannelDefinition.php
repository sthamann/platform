<?php declare(strict_types=1);

namespace Shopware\Core\System\SalesChannel;

use Shopware\Core\Checkout\Customer\Aggregate\CustomerGroup\CustomerGroupDefinition;
use Shopware\Core\Checkout\Customer\CustomerDefinition;
use Shopware\Core\Checkout\Order\OrderDefinition;
use Shopware\Core\Checkout\Payment\PaymentMethodDefinition;
use Shopware\Core\Checkout\Promotion\Aggregate\PromotionSalesChannel\PromotionSalesChannelDefinition;
use Shopware\Core\Checkout\Shipping\ShippingMethodDefinition;
use Shopware\Core\Content\MailTemplate\Aggregate\MailHeaderFooter\MailHeaderFooterDefinition;
use Shopware\Core\Content\MailTemplate\Aggregate\MailTemplateSalesChannel\MailTemplateSalesChannelDefinition;
use Shopware\Core\Content\MailTemplate\MailTemplateDefinition;
use Shopware\Core\Content\Navigation\NavigationDefinition;
use Shopware\Core\Content\NewsletterReceiver\NewsletterReceiverDefinition;
use Shopware\Core\Content\Product\Aggregate\ProductReview\ProductReviewDefinition;
use Shopware\Core\Content\Product\Aggregate\ProductVisibility\ProductVisibilityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\CreatedAtField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\CascadeDelete;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\JsonField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ReferenceVersionField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\UpdatedAtField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\Language\LanguageDefinition;
use Shopware\Core\System\Country\CountryDefinition;
use Shopware\Core\System\Currency\CurrencyDefinition;
use Shopware\Core\System\NumberRange\Aggregate\NumberRangeSalesChannel\NumberRangeSalesChannelDefinition;
use Shopware\Core\System\SalesChannel\Aggregate\SalesChannelCountry\SalesChannelCountryDefinition;
use Shopware\Core\System\SalesChannel\Aggregate\SalesChannelCurrency\SalesChannelCurrencyDefinition;
use Shopware\Core\System\SalesChannel\Aggregate\SalesChannelDomain\SalesChannelDomainDefinition;
use Shopware\Core\System\SalesChannel\Aggregate\SalesChannelLanguage\SalesChannelLanguageDefinition;
use Shopware\Core\System\SalesChannel\Aggregate\SalesChannelPaymentMethod\SalesChannelPaymentMethodDefinition;
use Shopware\Core\System\SalesChannel\Aggregate\SalesChannelShippingMethod\SalesChannelShippingMethodDefinition;
use Shopware\Core\System\SalesChannel\Aggregate\SalesChannelTranslation\SalesChannelTranslationDefinition;
use Shopware\Core\System\SalesChannel\Aggregate\SalesChannelType\SalesChannelTypeDefinition;
use Shopware\Core\System\SystemConfig\SystemConfigDefinition;

class SalesChannelDefinition extends EntityDefinition
{
    public static function getEntityName(): string
    {
        return 'sales_channel';
    }

    public static function getCollectionClass(): string
    {
        return SalesChannelCollection::class;
    }

    public static function getEntityClass(): string
    {
        return SalesChannelEntity::class;
    }

    protected static function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
            (new FkField('type_id', 'typeId', SalesChannelTypeDefinition::class))->addFlags(new Required()),
            (new FkField('language_id', 'languageId', LanguageDefinition::class))->addFlags(new Required()),
            (new FkField('customer_group_id', 'customerGroupId', CustomerGroupDefinition::class))->addFlags(new Required()),
            (new FkField('currency_id', 'currencyId', CurrencyDefinition::class))->addFlags(new Required()),
            (new FkField('payment_method_id', 'paymentMethodId', PaymentMethodDefinition::class))->addFlags(new Required()),
            (new FkField('shipping_method_id', 'shippingMethodId', ShippingMethodDefinition::class))->addFlags(new Required()),
            (new FkField('country_id', 'countryId', CountryDefinition::class))->addFlags(new Required()),
            new FkField('navigation_id', 'navigationId', NavigationDefinition::class),
            new ReferenceVersionField(NavigationDefinition::class, 'navigation_version_id'),
            (new FkField('mail_header_footer_id', 'mailHeaderFooterId', MailHeaderFooterDefinition::class)),
            (new StringField('type', 'type'))->addFlags(new Required()),
            new TranslatedField('name'),
            new StringField('short_name', 'shortName'),
            (new StringField('access_key', 'accessKey'))->addFlags(new Required()),
            new JsonField('configuration', 'configuration'),
            new BoolField('active', 'active'),
            new StringField('tax_calculation_type', 'taxCalculationType'),
            new TranslatedField('attributes'),
            new CreatedAtField(),
            new UpdatedAtField(),
            (new TranslationsAssociationField(SalesChannelTranslationDefinition::class, 'sales_channel_id'))->addFlags(new Required()),
            new ManyToManyAssociationField('currencies', CurrencyDefinition::class, SalesChannelCurrencyDefinition::class, 'sales_channel_id', 'currency_id'),
            new ManyToManyAssociationField('languages', LanguageDefinition::class, SalesChannelLanguageDefinition::class, 'sales_channel_id', 'language_id'),
            new ManyToManyAssociationField('countries', CountryDefinition::class, SalesChannelCountryDefinition::class, 'sales_channel_id', 'country_id'),
            new ManyToManyAssociationField('paymentMethods', PaymentMethodDefinition::class, SalesChannelPaymentMethodDefinition::class, 'sales_channel_id', 'payment_method_id'),
            new ManyToManyAssociationField('shippingMethods', ShippingMethodDefinition::class, SalesChannelShippingMethodDefinition::class, 'sales_channel_id', 'shipping_method_id'),
            new ManyToOneAssociationField('type', 'type_id', SalesChannelTypeDefinition::class, 'id', true),
            new ManyToOneAssociationField('language', 'language_id', LanguageDefinition::class, 'id', true),
            new ManyToOneAssociationField('customerGroup', 'customer_group_id', CustomerGroupDefinition::class, 'id', true),
            new ManyToOneAssociationField('currency', 'currency_id', CurrencyDefinition::class, 'id', true),
            new ManyToOneAssociationField('paymentMethod', 'payment_method_id', PaymentMethodDefinition::class, 'id', false),
            new ManyToOneAssociationField('shippingMethod', 'shipping_method_id', ShippingMethodDefinition::class, 'id', false),
            new ManyToOneAssociationField('country', 'country_id', CountryDefinition::class, 'id', false),
            new OneToManyAssociationField('orders', OrderDefinition::class, 'sales_channel_id', 'id'),
            new OneToManyAssociationField('customers', CustomerDefinition::class, 'sales_channel_id', 'id'),
            (new OneToManyAssociationField('domains', SalesChannelDomainDefinition::class, 'sales_channel_id', 'id'))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('systemConfigs', SystemConfigDefinition::class, 'sales_channel_id'))->addFlags(new CascadeDelete()),
            new ManyToOneAssociationField('navigation', 'navigation_id', NavigationDefinition::class, 'id', false),
            (new OneToManyAssociationField('productVisibilities', ProductVisibilityDefinition::class, 'sales_channel_id'))->addFlags(new CascadeDelete()),
            new ManyToOneAssociationField('mailHeaderFooter', 'mail_header_footer_id', MailHeaderFooterDefinition::class, 'id', true),
            new OneToManyAssociationField('newsletterReceivers', NewsletterReceiverDefinition::class, 'sales_channel_id', 'id'),
            new ManyToManyAssociationField('mailTemplates', MailTemplateDefinition::class, MailTemplateSalesChannelDefinition::class, 'sales_channel_id', 'mail_template_id'),
            new OneToManyAssociationField('numberRangeSalesChannels', NumberRangeSalesChannelDefinition::class, 'sales_channel_id'),
            new OneToManyAssociationField('promotionSalesChannels', PromotionSalesChannelDefinition::class, 'sales_channel_id', 'id'),
            new OneToManyAssociationField('ratings', ProductReviewDefinition::class, 'sales_channel_id', 'id'),

        ]);
    }
}
