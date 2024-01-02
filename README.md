# Configurable menu
- Czech documentation: [README_cs.md](README_cs.md)

## Class `\PechOndra\Layout\Menu\Menu`
- implements `\PechOndra\Layout\Menu\MenuInterface`
- contains a `public function getItems(): \PechOndra\Layout\Menu\ItemCollection` method that returns menu items

## Class `\PechOndra\Layout\Menu\Item`
- implements `\PechOndra\Layout\Menu\ItemInterface`
- defines the property of the menu item:
    - `canonicalName`: system name of the item, must be unique in the given structure
    - `title`: title of item *(mostly will be key for translation)*
    - `destination`: object with the absolute path of the link eg *:Layout:Admin:Layout:* and query params, can be `null` (null = not itself a link but can have sub-items).
    - `resource`: the resource for the ACL can be `null` (when it is `null`, the ACL is not resolved = it has access)
    - `parent`: is added automatically when included in the structure contains `MenuInterface` or `ItemInterface` which is the superior item of this structure
    - `items`: contains child items in `\PechOndra\Layout\Menu\ItemCollection`
    - `current`: bool value determining whether the item is active *(functional only after compiling the menu for a specific situation until then `false`)*
    - `sortPriority`: sorting priority in the highest menu at the top

## Class `\PechOndra\Layout\Menu\NetteMenuCompiler`
- contains the method `NetteMenuCompiler::compile(\PechOndra\Layout\Menu\MenuInterface $menu)` which returns a menu object modified for a specific situation.
- the class gets the necessary dependencies (User and Presenter) and passes the field items in the `compile` method:
    - sets `current = true` to active items recursively
    - removes items to which the logged-in user does not have access

## Definition of a specific menu:
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
                         destination: Destination::create(':Layout:Admin:Layout:', , ['limit' => 10, 'category' => 'all']),
                         resource: null,
                         sortPriority: 10,
                     ),
                 ),
             ),
         ),
     }
}
````

## Using and compiling menus:
`\App\Application\UI\Admin\Presenter\AdminPresenter` contains a method setAdminMenu() (can be overloaded):

```` php
private function setAdminMenu(): void
{
$this->adminMenu = $this->menuCompiler->compile(
$this->projectAdminMenuFactory->create(),
$this
);
}
````

subsequently, `$this->adminMenu` is passed in `beforeRender()` to the template.

## Easiest to use in a template
```` latte
<h3>Main menu</h3>
<ul n:block="subItems" n:inner-foreach="$adminMenu->getItems() as $subItem">
<li>
{if $subItem->getDestination() !== null}
<a n:class="$subItem->isCurrent() === true ? text-danger" n:href="$subItem->getDestination() ?? this">{_$subItem->getTitle()}< /a> <small class="text-muted">{$subItem->getResource()}</small>
{else}
<span n:class="$subItem->isCurrent() === true ? text-danger">{_$subItem->getTitle()}</span> <small class="text-muted">{$ subItem->getResource()}</small>
{/if}
</li>
{include subItems adminMenu: $subItem}
</ul>
````

```` latte
{dump $mainMenu->getCurrentItem(\App\Layout\Menu\Menu::LayerTab)}
````

- the compiled menu contains one more essential method `getCurrentItem(int $layer = 0): Item` which returns the active item or `null`
    - `$layer` is the layer where the active item should be searched, numbered from zero (main layer = 0, submenu = 1, ...)
    - the first three layers are named by a constant:
        - `Menu::LayerMain` = 0
        - `Menu::LayerSub` = 1
        - `Menu::LayerTab` = 2
