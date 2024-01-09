<?php declare(strict_types = 1);

namespace Pleskin;

class Item implements ItemInterface
{

	private string $canonicalName;

	private string $title;

	private ItemInterface|MenuInterface|null $parent = null;

	private Destination|null $destination;

	private string|null $resource;

	/** @internal */
	private bool $current = false;

	private int $sortPriority;

	private string|null $icon;

	private ItemCollection $items;

	public function __construct(
		string $canonicalName,
		string $title,
		Destination|null $destination = null,
		string|null $resource = null,
		int $sortPriority = 10,
		string $icon = null,
		ItemInterface ...$items
	)
	{
		$this->canonicalName = $canonicalName;
		$this->title = $title;
		$this->destination = $destination;
		$this->resource = $resource;
		$this->sortPriority = $sortPriority;
		$this->icon = $icon;

		$this->items = new ItemCollection($items, $this);
	}

	public function getCanonicalName(): string
	{
		return $this->canonicalName;
	}

	public function getTitle(): string
	{
		return $this->title;
	}

	public function getDestination(): Destination|null
	{
		return $this->destination;
	}

	public function getIcon(): string|null
	{
		return $this->icon;
	}

	public function getResource(): string|null
	{
		return $this->resource;
	}

	public function isCurrent(): bool
	{
		return $this->current;
	}

	public function getItems(): ItemCollection
	{
		return $this->items;
	}

	public function getParent(): ItemInterface|MenuInterface|null
	{
		return $this->parent;
	}

	/** @internal */
	public function setCurrent(bool $current): void
	{
		$this->current = $current;
	}


	/** @internal */
	public function setParent(ItemInterface|MenuInterface|null $parent): void
	{
		$this->parent = $parent;
	}


	public function getSortPriority(): int
	{
		return $this->sortPriority;
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

	public function addTitle(\Stringable|string $string): void
	{
		$this->title = $this->title . ' ' . $string;
	}

}
