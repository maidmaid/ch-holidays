<?php

declare(strict_types=1);

namespace App\Domain\Holiday;

use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

class HolidayProviderRegistry
{
    public function __construct(
        #[TaggedIterator(tag: 'app.holiday_provider_interface')]
        private iterable $providers,
    ) {
    }

    /**
     * @return iterable<HolidayProviderInterface>
     */
    public function provide(int $year): iterable
    {
        foreach ($this->providers as $provider) {
            yield from $provider->provide($year);
        }
    }

    /**
     * @return iterable<string>
     */
    public function getTypesIds(): iterable
    {
        foreach ($this->providers as $provider) {
            yield $provider->getTypeId();
        }
    }
}
