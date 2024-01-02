<?php declare(strict_types = 1);

namespace PechOndra;

final class Destination
{
	/**
	 * @param array<string, string|int> $params
	 */
	private function __construct(
		private string $destination,
		private array $params = []
	) {}

	/**
	 * @param array<string, string|int> $params
	 */
	public static function create(
		string $destination,
		array $params = []
	): self
	{
		return new self($destination, $params);
	}

	public function getDestination(): string
	{
		return $this->destination;
	}

	/**
	 * @return array<string, string|int>
	 */
	public function getParams(): array
	{
		return $this->params;
	}
}
