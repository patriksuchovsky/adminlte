<?php declare(strict_types=1);

namespace Patriksuchovsky\Adminlte;

use Nette\Utils\Html;

class Menu
{
    private array $menuItems;
    private $user;
    private $presenter;
    private $data;

    public function __construct(array $menu)
    {
        $this->menuItems = $menu;
        $this->user = null;
        $this->presenter = null;
        $this->data = null;
    }

    public function displayMenu($user, $presenter, array $data = null): Html
    {
        $this->user = $user;
        $this->presenter = $presenter;
        $this->data = $data;

        $menu = Html::el('ul')
            ->class('nav nav-pills nav-sidebar flex-column')
            ->setAttribute('data-widget', 'treeview')
            ->setAttribute('role', 'menu')
            ->setAttribute('data-accordion', 'false');

        $this->getMenuItem($menu, $this->menuItems);

        return $menu;
    }

    private function getMenuItem(Html &$menu, array $items)
    {
        foreach ($items as $i) {
            $type = $i['type'] ?? 'link';

            switch ($type) {
                case 'sub':
                    $liClass = 'nav-item has-treeview';
                    $aClass = 'nav-link';

                    foreach ($i['sub'] as $s) {
                        if ($this->presenter->isLinkCurrent($s['link']) || (isset($i['active']) && $this->presenter->isLinkActive($i['active']))) {
                            $liClass .= ' menu-open';
                            $aClass .= ' active';
                            break;
                        }
                    }

                    $submenu = Html::el('ul')->class('nav nav-treeview');

                    foreach ($i['sub'] as $s) {
                        if (!(isset($s['display']) && !$s['display']) && (isset($s['resource']) && $this->user->isAllowed($s['resource']) || !isset($s['resource'])))
                            $submenu->addHtml($this->getNavItem($s));
                    }

                    $menu->addHtml(
                        Html::el('li')
                            ->class($liClass)
                            ->addHtml(
                                Html::el('a')
                                    ->href('#')
                                    ->class($aClass)
                                    ->addHtml( Html::el('i')->class('nav-icon ' . $i['icon']) )
                                    ->addHtml(
                                        Html::el('p')
                                            ->addText($i['name'])
                                            ->addHtml( Html::el('i')->class('fa fa-angle-left right') )
                                    )

                            )
                            ->addHtml($submenu)
                    );
                    break;
                case 'header':
                    $menu->addHtml(
                        Html::el('li')
                            ->class('nav-header')
                            ->setText($i['name'])
                    );
                    break;
                default:
                    if (isset($i['resource']) && $this->user->isAllowed($i['resource']) || !isset($i['resource']))
                        $menu->addHtml($this->getNavItem($i));
            }
        }
    }

    private function getNavItem(array $item): Html
    {
        $aClass = 'nav-link';

        if ($this->presenter->isLinkCurrent($item['link']) || (isset($item['active']) && $this->presenter->isLinkActive($item['active'])))
            $aClass .= ' active';

        $p = Html::el('p')
            ->addText($item['name']);

        if (isset($item['badge'])) {
            if (isset($item['badge']['name']) && isset($item['badge']['color']) && isset($this->data[$item['badge']['name']])) {
                $p->addHtml(
                    Html::el('span')
                        ->class('right badge badge-' . $item['badge']['color'])
                        ->setText($this->data[$item['badge']['name']])
                );
            }
        }

        return Html::el('li')->class('nav-item')
            ->setHtml(
                Html::el('a')
                    ->href($this->presenter->link($item['link']))
                    ->class($aClass)
                    ->addHtml( Html::el('i')->class('nav-icon ' . $item['icon']) )
                    ->addHtml( $p )
            );
    }
}
