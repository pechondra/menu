# Konfigurovatelné menu

## Třída `\PechOndra\Layout\Menu\Menu`
- implementuje `\PechOndra\Layout\Menu\MenuInterface`
- obsahuje metodu `public function getItems(): \PechOndra\Layout\Menu\ItemCollection` která vrací položky menu

## Třída `\PechOndra\Layout\Menu\Item`
- implementuje `\PechOndra\Layout\Menu\ItemInterface`
- definuje property položky menu:
    - `canonicalName`: systémový název položky, musí být unikátní v dané struktuře
    - `title`: název položky *(většinou bude klíč pro překlad)*
    - `destination`: objekt s absolutní cestou odkazu např. *:Layout:Admin:Layout:* a query parametry, může být `null` (null = není sám o sobě odkazem ale může mít sub-itemy).
    - `resource`: zdroj pro ACL může být `null` (když je `null` tak se ACL neřeší = má přístup)
    - `parent`: doplňuje se automaticky při zařazení do struktury obsahuje `MenuInterface` nebo `ItemInterface` což je nadřazená položka této struktury
    - `items`: obsahuje podřazené položky v `\PechOndra\Layout\Menu\ItemCollection`
    - `current`: bool hodnota určující zda je položka aktivní *(funkční až po kompilaci menu pro konkrétní situaci do té doby `false`)*
    - `sortPriority`: priorita řazení v menu nejvyšší nahoře

## Třída `\PechOndra\Layout\Menu\NetteMenuCompiler`
- obsahuje metodu `NetteMenuCompiler::compile(\PechOndra\Layout\Menu\MenuInterface $menu)` která vrací objekt menu upravený pro konkrétní situaci.
- třída dostane potřebné závislosti (User a Presenter) a v metodě `compile` projde itemy pole:
    - nastaví `current = true` aktivním itemům rekurzivně
    - odebere itemy kam přihlášený uživatel nemá přístup

## Definice konkrétního menu:
```` php
class SomeMenu
{
    public static function provide(): \PechOndra\Layout\Menu\Menu
    {
        return new \PechOndra\Layout\Menu\Menu(
            new \PechOndra\Layout\Menu\Item(
                canonicalName: 'DemoLayout',
                title: 'Demo layout',
                destination: null,
                resource: null,
                sortPriority: 10,
                new \PechOndra\Layout\Menu\Item(
                    canonicalName: 'DemoLayoutSub1',
                    title: 'Demo layout Sub 1',
                    destination: null,
                    resource: null,
                    sortPriority: 20,
                    new \PechOndra\Layout\Menu\Item(
                        canonicalName: 'DemoLayoutSubSub1',
                        title: 'TAB Demo layout Sub Sub 1',
                        destination: null,
                        resource: null,
                    ),
                    new \PechOndra\Layout\Menu\Item(
                        canonicalName: 'DemoLayoutSubSub2',
                        title: 'TAB Demo layout Sub Sub 2',
                        destination: Destination::create(':Layout:Admin:Layout:', ['limit' => 10, 'category' => 'all'])']),
                        resource: null,
                        sortPriority: 10,
                    ),
                ),
            ),
        ),
    }
}
````

## Použití a kompilace menu:
`\App\Application\UI\Admin\Presenter\AdminPresenter` obsahuje metodu setAdminMenu() (jde přetížit):

```` php
	private function setAdminMenu(): void
	{
		$this->adminMenu = $this->menuCompiler->compile(
			$this->projectAdminMenuFactory->create(),
			$this
		);
	}
````

následně se `$this->adminMenu` předá v `beforeRender()` do šablony.

## Nejjednoduší použítí v šabloně
```` latte
	<h3>Main menu</h3>
	<ul n:block="subItems" n:inner-foreach="$adminMenu->getItems() as $subItem">
		<li>
			{if $subItem->getDestination() !== null}
				<a n:class="$subItem->isCurrent() === true ? text-danger" n:href="$subItem->getDestination() ?? this">{_$subItem->getTitle()}</a> <small class="text-muted">{$subItem->getResource()}</small>
			{else}
				<span n:class="$subItem->isCurrent() === true ? text-danger">{_$subItem->getTitle()}</span> <small class="text-muted">{$subItem->getResource()}</small>
			{/if}
		</li>
			{include subItems adminMenu: $subItem}
	</ul>
````

```` latte
{dump $mainMenu->getCurrentItem(\App\Layout\Menu\Menu::LayerTab)}
````

- zkompilované menu obsahuje ještě jednu podstatnou metodu `getCurrentItem(int $layer = 0): Item` která vrací aktivní item nebo `null`
    - `$layer` je vrstva kde má aktivní item hledat číslovaná od nuly (hlavní vrstava = 0, submenu = 1, ...)
    - první tři vrstvy jsou pojmenované konstanou:
        - `Menu::LayerMain` = 0
        - `Menu::LayerSub` = 1
        - `Menu::LayerTab` = 2
