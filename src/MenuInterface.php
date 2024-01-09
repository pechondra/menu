<?php declare(strict_types=1);

namespace Pleskin;

interface MenuInterface
{

	public function getItems(): ItemCollection;

	public function getCurrentItem(int $layer): ItemInterface|null;

}
