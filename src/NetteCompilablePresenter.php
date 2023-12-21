<?php declare(strict_types = 1);

namespace PechOndra\Layout\Menu;

interface NetteCompilablePresenter
{
	public function getUser(): NetteCompilableUser;

	public function getAction(): string;

	public function getName(): string;
}
