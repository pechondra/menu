<?php declare(strict_types = 1);

namespace Pleskin\Nette;

interface NetteCompilableUser
{
	public function isAllowed(string $resource, string $privilege): bool;
}
