<?php declare(strict_types=1);

namespace PechOndra\Layout\Menu;

interface MenuInterface
{

	public function getItems(): ItemCollection;

	public function getCurrentItem(int $layer): ItemInterface|null;

}
