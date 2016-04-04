<?php

namespace Instante\CMS\DI;

use Instante\CMS\Editor\EditorApi;
use Instante\CMS\Latte\EditorMacros;
use Kdyby\Doctrine\DI\IEntityProvider;
use Nette\Application\IRouter;
use Nette\Application\Routers\Route;
use Nette\Configurator;
use Nette\DI\Compiler;
use Nette\DI\CompilerExtension;

/**
 * Instante CMS content editor
 */
final class EditorExtension extends CompilerExtension implements IEntityProvider
{
    const DEFAULT_EXTENSION_NAME = 'instante.cms.editor';

    const EDITABLE_FACADE_SERVICE = 'editableFacade';

    public $defaultName = NULL;

    /**
     * Processes configuration data
     *
     * @return void
     */
    public function loadConfiguration()
    {
        $this->registerFacadeService();
        $this->registerLatteMacros();
        $this->registerJSModule();
    }

    /**
     * @param string url prefix for the route
     * @return IRouter
     */
    public static function createRoute($prefix = 'icms-api')
    {
        return new Route($prefix . '[/<action>]', EditorApi::getEntryPoint());
    }

    private function registerJSModule()
    {
        //TODO register js into js module container if js module container is present
    }

    private function registerFacadeService()
    {
        $this->getContainerBuilder()->addDefinition($this->prefix(self::EDITABLE_FACADE_SERVICE))
            ->setClass('Instante\\CMS\\Editor\\EditableFacade');
    }

    private function registerLatteMacros()
    {
        $this->getContainerBuilder()
            ->getDefinition('latte.latteFactory')
            ->addSetup('?->onCompile[] = function() use (?) { ' . EditorMacros::class
                . '::install(?,?); }', ['@self', '@self', '@self', $this->prefix('@ICMSAuthorizator')])
            ->addSetup('addFilter', [
                EditorMacros::EDITABLE_TEXT_RESOLVER_FILTER,
                [$this->prefix('@' . self::EDITABLE_FACADE_SERVICE), 'getText'],
            ])
            ->addSetup('addFilter', [
                EditorMacros::EDITABLE_TEXT_AUTHORIZATOR,
                [$this->prefix('@ICMSAuthorizator'), 'canWrite'],
            ]);
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
    public function getEntityMappings()
    {
        return ['Instante\\CMS\\Model' => __DIR__ . '/../Model'];
    }
}

