<?php

declare(strict_types=1);

namespace Janwebdev\ObjectMapper\Mapper;

interface MapperInterface
{
    public function __invoke(array $data, string $className);
}
