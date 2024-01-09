<?php declare(strict_types = 1);

namespace Pleskin\Nette;

interface NetteCompilablePresenter
{
	public function getUser(): NetteCompilableUser;

	public function getAction(): string;

	public function getName(): string;

	public function getParameters(): array;
}
