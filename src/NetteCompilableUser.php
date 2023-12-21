<?php declare(strict_types = 1);

namespace PechOndra\Layout\Menu;

interface NetteCompilableUser
{
	public function isAllowed(string $resource, string $privilege): bool;
}
