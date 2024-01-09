<?php declare(strict_types = 1);

namespace Pleskin;

use Pleskin\Exception\ItemAlreadyExistsException;
use Pleskin\Exception\ItemDoesNotExistsException;
use Pleskin\Infrastructure\ObjectIterator;

class ItemCollection extends ObjectIterator
{

	private ItemInterface|MenuInterface|null $parentItem;

	/**
	 * @param ItemInterface[] $data
	 */
	public function __construct(
		array $data,
		ItemInterface|MenuInterface|null $parentItem
	)
	{

		$this->parentItem = $parentItem;

		$collection = [];
		foreach ($data as $item) {
			$item->setParent($parentItem);
			$collection[$item->getCanonicalName()] = $item;
		}

		\uasort(
			$collection,
			static fn(
				ItemInterface $a,
				ItemInterface $b
			) => ($a->getSortPriority() <=> $b->getSortPriority()) * -1
		);

		parent::__construct($collection);
	}

	/**
	 * @throws ItemAlreadyExistsException
	 */
	public function add(ItemInterface $item): void
	{
		if (isset($this->data[$item->getCanonicalName()]) === TRUE) {
			throw ItemAlreadyExistsException::createWithCanonicalName($item->getCanonicalName());
		}

		$item->setParent($this->parentItem);
		$this->offsetSet($item->getCanonicalName(),$item);
	}

	public function removeByCanonicalName(string $canonicalName): void
	{
		if (isset($this->data[$canonicalName]) === FALSE) {
			throw ItemDoesNotExistsException::createWithCanonicalName($canonicalName);
		}

		$this->offsetUnset($canonicalName);
	}

	public function getByCanonicalName(string $canonicalName): ItemInterface
	{
		return $this->data[$canonicalName];
	}

}
