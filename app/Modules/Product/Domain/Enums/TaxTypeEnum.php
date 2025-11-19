<?php

namespace App\Modules\Product\Domain\Enums;

use App\Modules\Base\Domain\Traits\HasMetadata;

enum TaxTypeEnum: int
{
  use HasMetadata;
  case FIXED = 1;
  case PERCENTAGE = 2;

  public function getMetadata(): array
  {
    return match ($this) {
      self::FIXED => [
        'label' => 'Fixed',
        'value' => 1
      ],
      self::PERCENTAGE => [
        'label' => 'Percentage',
        'value' => 2
      ]
    };
  }


  public static function tryFromLabel(string $case): ?static
  {
    return match ($case) {
      'fixed' => self::FIXED,
      'percentage' => self::PERCENTAGE,
      default => null
    };
  }
}
