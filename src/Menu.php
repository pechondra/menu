<?php declare(strict_types=1);

namespace PechOndra\Layout\Menu;

use PechOndra\Layout\Menu\Exception\ItemAlreadyExistsException;

class Menu implements MenuInterface
{

	public const LayerMain = 0;
	public const LayerSub = 1;
	public const LayerTab = 2;

	private ItemCollection $items;

	/**
	 * @throws ItemAlreadyExistsException
	 */
	public function __construct(
		ItemInterface ...$items
	)
	{
		$this->items = new ItemCollection($items, $this);
	}

	public function getItems(): ItemCollection
	{
		return $this->items;
	}

	public function getCurrentItem(
		int $layer
	): ItemInterface|null
	{
		$items = $this->getItems();
		$iterator = 0;

		if (self::hasSomeCurrentItem($this->getItems()) === false) {
			return null;
		}

		do {
			foreach ($items as $item) {
				if ($item->isCurrent() === TRUE) {
					if ($iterator === $layer) {

						return $item;
					}

					$iterator++;
					$items = $item->getItems();
				}
			}
		} while ($items->count() !== 0);

		return null;
	}

	public function addItem(Item $item): void
	{
		$this->getItems()->add($item);
	}

	public function removeItemByCanonicalName(string $canonicalName): void
	{
		$this->getItems()->removeByCanonicalName($canonicalName);
	}

	public function getItemByCanonicalName(string $canonicalName): ItemInterface
	{
		return $this->getItems()->getByCanonicalName($canonicalName);
	}

	private static function hasSomeCurrentItem(ItemCollection $items): bool
	{
		foreach ($items as $item) {
			if ($item->isCurrent() === TRUE) {

				return true;
			}
		}

		return false;
	}

}
