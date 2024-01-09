<?php declare(strict_types = 1);

namespace Pleskin\Exception;

class ItemDoesNotExistsException extends \RuntimeException
{
	public static function createWithCanonicalName(string $canonicalName): self
	{
		return new self(
			\sprintf(
				'Item with canonical name %s does not exist in this structure.',
				$canonicalName
			)
		);
	}
}
