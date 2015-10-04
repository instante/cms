<?php

namespace Instante\CMS\DI;

use Instante\CMS\Latte\EditorMacros;
use Kdyby\Doctrine\DI\IEntityProvider;
use Nette\Configurator;
use Nette\DI\Compiler;
use Symfony\Component\Console\Output\ConsoleOutput;
use Nette\DI\CompilerExtension;

/**
 * Instante CMS content editor
 */
class EditorExtension extends CompilerExtension implements IEntityProvider
{
    const DEFAULT_EXTENSION_NAME = 'instante.cms.editor';

    public $defaultName = NULL;

    /**
     * Processes configuration data
     *
     * @return void
     */
    public function loadConfiguration()
    {
        /*
         * TODO
         * - add route to editor persistence presenter to router
         * - register js into js module container if js module container is present
         */
        $this->registerFacadeService();
        $this->registerLatteMacros();
    }

    private function registerFacadeService() {
        $this->getContainerBuilder()->addDefinition($this->prefix('editableFacade'))
            ->setClass('Instante\\CMS\\Editor\\EditableFacade');
    }

    private function registerLatteMacros()
    {
        $this->getContainerBuilder()
            ->getDefinition('latte.latteFactory')
            ->addSetup('?->onCompile[] = function() use (?) { ' . EditorMacros::class
                . '::install(?->getCompiler()); }', array('@self', '@self', '@self'));
    }

    /**
     * Register extension to compiler.
     *
     * @param \Nette\Configurator
     * @param string
     */
    public static function register(Configurator $configurator, $name = self::DEFAULT_EXTENSION_NAME)
    {
        $class = get_called_class();
        $configurator->onCompile[] = function (Configurator $configurator, Compiler $compiler) use ($class, $name) {
            $compiler->addExtension($name, new $class);
        };
    }

    /**
     * Returns associative array of Namespace => mapping definition
     *
     * @return array
     */
    function getEntityMappings()
    {
        return ['Instante\\CMS\\Model' => __DIR__ . '/../Model'];
    }
}

