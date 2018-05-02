<?php declare(strict_types=1);

namespace Shopware\Api\Country\Event\CountryAreaTranslation;

use Shopware\Api\Country\Collection\CountryAreaTranslationDetailCollection;
use Shopware\Api\Country\Event\CountryArea\CountryAreaBasicLoadedEvent;
use Shopware\Api\Language\Event\Language\LanguageBasicLoadedEvent;
use Shopware\Context\Struct\ApplicationContext;
use Shopware\Framework\Event\NestedEvent;
use Shopware\Framework\Event\NestedEventCollection;

class CountryAreaTranslationDetailLoadedEvent extends NestedEvent
{
    public const NAME = 'country_area_translation.detail.loaded';

    /**
     * @var ApplicationContext
     */
    protected $context;

    /**
     * @var CountryAreaTranslationDetailCollection
     */
    protected $countryAreaTranslations;

    public function __construct(CountryAreaTranslationDetailCollection $countryAreaTranslations, ApplicationContext $context)
    {
        $this->context = $context;
        $this->countryAreaTranslations = $countryAreaTranslations;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getContext(): ApplicationContext
    {
        return $this->context;
    }

    public function getCountryAreaTranslations(): CountryAreaTranslationDetailCollection
    {
        return $this->countryAreaTranslations;
    }

    public function getEvents(): ?NestedEventCollection
    {
        $events = [];
        if ($this->countryAreaTranslations->getCountryAreas()->count() > 0) {
            $events[] = new CountryAreaBasicLoadedEvent($this->countryAreaTranslations->getCountryAreas(), $this->context);
        }
        if ($this->countryAreaTranslations->getLanguages()->count() > 0) {
            $events[] = new LanguageBasicLoadedEvent($this->countryAreaTranslations->getLanguages(), $this->context);
        }

        return new NestedEventCollection($events);
    }
}