<?php declare(strict_types=1);

namespace Shopware\Core\System\Locale;

use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\RestrictDelete;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\SearchRanking;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\Language\LanguageDefinition;
use Shopware\Core\System\Locale\Aggregate\LocaleTranslation\LocaleTranslationDefinition;
use Shopware\Core\System\User\UserDefinition;

class LocaleDefinition extends EntityDefinition
{
    public function getEntityName(): string
    {
        return 'locale';
    }

    public function getCollectionClass(): string
    {
        return LocaleCollection::class;
    }

    public function getEntityClass(): string
    {
        return LocaleEntity::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
            (new StringField('code', 'code'))->addFlags(new Required(), new SearchRanking(SearchRanking::MIDDLE_SEARCH_RANKING)),
            (new TranslatedField('name'))->addFlags(new SearchRanking(SearchRanking::HIGH_SEARCH_RANKING)),
            new TranslatedField('territory'),
            new TranslatedField('customFields'),
            new OneToManyAssociationField('languages', LanguageDefinition::class, 'locale_id', 'id'),
            (new TranslationsAssociationField(LocaleTranslationDefinition::class, 'locale_id'))->addFlags(new Required()),
            (new OneToManyAssociationField('users', UserDefinition::class, 'locale_id', 'id'))->addFlags(new RestrictDelete()),
        ]);
    }
}
