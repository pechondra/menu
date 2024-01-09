<?php declare(strict_types = 1);

namespace Pleskin\Exception;

class ItemAlreadyExistsException extends \RuntimeException
{
	public static function createWithCanonicalName(string $canonicalName): self
	{
		return new self(
			\sprintf(
				'Item with canonical name %s already exists in this structure.',
				$canonicalName
			)
		);
	}
}
