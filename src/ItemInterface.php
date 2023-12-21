<?php declare(strict_types=1);

namespace PechOndra\Layout\Menu;

interface ItemInterface
{

	public function getCanonicalName(): string;

	public function getTitle(): string;

	public function getDestination(): string|null;

	public function getResource(): string|null;

	public function isCurrent(): bool;

	public function getSortPriority(): int;

	public function getItems(): ItemCollection;

	/** @internal */
	public function setCurrent(bool $current): void;

	public function getParent(): ItemInterface|MenuInterface|null;

	public function setParent(ItemInterface|MenuInterface|null $parent): void;

}
