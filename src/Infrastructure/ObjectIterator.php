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

	/**
	 * @param mixed $offset
	 */
	public function offsetExists($offset): bool
	{
		return isset($this->data[$offset]);
	}

	/**
	 * @param mixed $offset
	 * @return mixed
	 */
	public function offsetGet($offset)
	{
		return $this->data[$offset];
	}

	/**
	 * @param mixed $offset
	 * @param mixed $value
	 * @return mixed
	 */
	public function offsetSet($offset, $value)
	{
		return $this->data[$offset] = $value;
	}

	/**
	 * @param mixed $offset
	 */
	public function offsetUnset($offset): void
	{
		unset($this->data[$offset]);
	}

	public function getIterator(): \Traversable
	{
		return new \ArrayIterator($this->data);
	}

}
