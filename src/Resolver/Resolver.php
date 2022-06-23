<?php

declare(strict_types=1);

namespace Janwebdev\ObjectMapper\Resolver;

use Janwebdev\ObjectMapper\Converter\Exception\TypeCasterException;
use Janwebdev\ObjectMapper\Converter\TypeCaster\TypeCasterInterface;

class Resolver
{
    /**
     * @var TypeCasterInterface[]
     */
    private array $typeCasters = [];

    public function addTypeCaster(TypeCasterInterface $typeCaster): void
    {
        $this->typeCasters[] = $typeCaster;
    }

    public function resolve(string $type): TypeCasterInterface
    {
        foreach ($this->typeCasters as $typeCaster) {
            if ($typeCaster->supports($type)) {
                return $typeCaster;
            }
        }
        throw new TypeCasterException('converter not supported '.$type);
    }
}
