<?php

namespace Janwebdev\ObjectMapper\Converter\TypeCaster;

use Janwebdev\ObjectMapper\Converter\Exception\TypeCasterException;

class ArrayTypeCaster implements TypeCasterInterface
{
	private const TYPE = 'array';

	public function convert($values, string $type)
	{
		if (!\is_array($values)) {
			throw new TypeCasterException(get_class($this).' requires array as value');
		}

		$objects = [];
		foreach($values as $value) {
			$objects[] = json_decode(json_encode($value), false);
		}
		return $objects;
	}

	public function supports(string $type): bool
	{
		return self::TYPE === $type;
	}
}