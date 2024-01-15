<?php

declare(strict_types=1);

namespace App\Domain\Holiday;

use App\Entity\Holiday;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(name: 'app.holiday_provider_interface')]
interface HolidayProviderInterface
{
    /**
     * @return iterable<Holiday>
     */
    public function provide(int $year): iterable;
    public function getTypeId(): string;
}
