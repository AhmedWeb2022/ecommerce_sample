<?php

namespace App\Modules\Base\Domain\Traits;

trait HasMetadata
{
    abstract public function getMetadata(): array;

    public function label(): string
    {
        return $this->getMetadata()['label'];
    }

    public function color(): string
    {
        return $this->getMetadata()['color'];
    }

    public function icon(): string
    {
        return $this->getMetadata()['icon'];
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function get(): array
    {
        return array_map(fn($case) => (object) [
            'id' => $case->value,
            'title' => $case->label(),
        ], self::cases());
    }
}
