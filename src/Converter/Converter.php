<?php

declare(strict_types=1);

namespace Janwebdev\ObjectMapper\Converter;

use Janwebdev\ObjectMapper\Resolver\Resolver;

class Converter implements ConverterInterface
{
	private Resolver $resolver;

    public function __construct(Resolver $resolver)
    {
    	$this->resolver = $resolver;
    }

    public function __invoke(array $row, array $schemas): array
    {
        $results = [];
        foreach ($row as $property => $value) {
            $schema = $schemas[$property];
            $typeCaster = $this->resolver->resolve($schema['type']);
            $results[$property] = $typeCaster->convert($value, $schema['type']);
        }
        return $results;
    }
}
