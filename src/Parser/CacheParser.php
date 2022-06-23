<?php

declare(strict_types=1);

namespace Janwebdev\ObjectMapper\Parser;

use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

class CacheParser implements ParserInterface
{
	private CacheInterface $cache;
	private ParserInterface $parser;

	public function __construct(CacheInterface $cache, ParserInterface $parser)
	{
		$this->cache = $cache;
		$this->parser = $parser;
	}

	public function __invoke(string $className)
    {
        $cached = $this->readCache($className);
        if (null !== $cached) {
            return $cached;
        }

        $schema = ($this->parser)($className);
        $this->saveCache($className, $schema);

        return $schema;
    }

    private function readCache(string $className): ?array
    {
        if (null === $this->cache) {
            return null;
        }
        try {
            return $this->cache->get($this->cacheKey($className));
        } catch (InvalidArgumentException $e) {
            throw new ParserException('Cache read error', $e->getCode(), $e);
        }
    }

    private function saveCache(string $className, array $schema): void
    {
        if (null !== $this->cache) {
            try {
                $this->cache->set($this->cacheKey($className), $schema);
            } catch (InvalidArgumentException $e) {
                throw new ParserException('Cache write error', $e->getCode(), $e);
            }
        }
    }

    private function cacheKey(string $className): string
    {
        return sha1($className);
    }
}
