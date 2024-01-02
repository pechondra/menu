<?php declare(strict_types=1);

namespace PechOndra\Layout\Menu;

use PechOndra\Destination;

class NetteMenuCompiler
{

	public const DefaultPrivilege = 'view';
	public const DefaultAction = 'default';

	private NetteCompilableUser $user;

	private NetteCompilablePresenter $presenter;


	public function compile(
		MenuInterface $menu,
		NetteCompilablePresenter $presenter
	): MenuInterface
	{
		$this->presenter = $presenter;
		$this->user = $presenter->getUser();

		$this->compileItem($menu->getItems());

		return $menu;
	}


	private function compileItem(
		ItemCollection $items
	): void
	{

		if ($items->count() === 0) {
			return;
		}

		foreach ($items as $item) {
			$this->menuCurrentItemCompiler($item);
			$this->menuPrivilegesItemCompiler($item);

			if ($item->getItems()->count() !== 0) {
				$this->compileItem($item->getItems());
			}
		}
	}


	private function menuPrivilegesItemCompiler(ItemInterface $item): void
	{
		if ($item->getResource() === NULL) {
			return;
		}

		if ($this->user->isAllowed($item->getResource(), self::DefaultPrivilege) === false) {
			$this->removeItemRecursive($item);
		}
	}


	private function removeItemRecursive(ItemInterface $item): void
	{
		$parent = $item->getParent();

		if ($parent !== null) {
			$parent->getItems()->removeByCanonicalName($item->getCanonicalName());
		}

		if ($parent->getItems()->count() === 0 && $parent->getDestination() === NULL) {
			$this->removeItemRecursive($parent);
		}
	}


	private function menuCurrentItemCompiler(ItemInterface $item): void
	{

		// Set current/active for menu
		$currentPresenterDestination = self::getCurrentPresenterDestination(
			$this->presenter->getName(),
			$this->presenter->getAction(),
			$this->presenter->getParameters(),
		);

		if ($item->getDestination()->getDestination() === $currentPresenterDestination->getDestination()) {
			if ($item->getDestination()->getParams() === $currentPresenterDestination->getParams()) {
				$this->setCurrentRecursive($item);
			}
		}

	}

	private function setCurrentRecursive(ItemInterface|MenuInterface $item, bool $current = true): void
	{
		if ($item instanceof MenuInterface) {
			return;
		}

		$item->setCurrent($current);

		if ($item->getParent() !== null) {
			$this->setCurrentRecursive($item->getParent(), $current);
		}
	}


	private static function getCurrentPresenterDestination(string $presenter, string $action, array $params): Destination
	{
		if ($action === self::DefaultAction) {
			$action = '';
		}

		return new Destination(\sprintf(':%s:%s', $presenter, $action), $params);
	}

}
