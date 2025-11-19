<?php

namespace App\Modules\Base\Domain\DTO;

interface BaseDTOInterface
{

    public static function fromArray(array $data): self;

    public function toArray(): array;

    public static function rollbackUploads(): void;
}
