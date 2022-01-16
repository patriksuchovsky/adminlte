<?php declare(strict_types = 1);

namespace Patriksuchovsky\Adminlte\DI\Nette;

use Nette\DI\CompilerExtension;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Patriksuchovsky\Adminlte\Menu;

class AdminLteExtension extends CompilerExtension
{
    public function getConfigSchema(): Schema
    {
        return Expect::structure([
            'menu' => Expect::array()->required()
        ]);
    }

    public function loadConfiguration()
    {
        $builder = $this->getContainerBuilder();
        $config = $this->config;

        $builder->addDefinition($this->prefix('adminlte'))
            ->setType(Menu::class)
            ->setArgument('menu', $config->menu);

    }
}
