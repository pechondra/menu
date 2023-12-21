<?php declare(strict_types = 1);

namespace PechOndra\Layout\Menu\Exception;

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
