<?php

declare(strict_types=1);

namespace Janwebdev\ObjectMapper\Converter\TypeCaster;

interface TypeCasterInterface
{
    public function convert($value, string $type);

    public function supports(string $type): bool;
}
