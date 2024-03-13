<?php declare(strict_types = 1);

namespace Pleskin\Infrastructure;

use ArrayAccess;
use Countable;
use IteratorAggregate;

/** @implements IteratorAggregate<int|string, mixed> */
abstract class ObjectIterator implements ArrayAccess, IteratorAggregate, Countable
{

	/**
	 * @var array<int|string, mixed>
	 */
	protected array $data;


	/**
	 * @param array<int|string, mixed> $data
	 */
	public function __construct(array $data = [])
	{
		$this->data = $data;
	}

	public function count(): int
	{
		return \count($this->data);
	}

	public function offsetExists(mixed $offset): bool
	{
		return isset($this->data[$offset]);
	}

	public function offsetGet(mixed $offset): mixed
	{
		return $this->data[$offset];
	}

	public function offsetSet(mixed $offset, mixed $value): void
	{
		$this->data[$offset] = $value;
	}

	public function offsetUnset(mixed $offset): void
	{
		unset($this->data[$offset]);
	}

	public function getIterator(): \Traversable
	{
		return new \ArrayIterator($this->data);
	}

}
