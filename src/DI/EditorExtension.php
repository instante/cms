<?php

namespace Instante\CMS\DI;

use Nette\Configurator;
use Nette\DI\Compiler;
use Symfony\Component\Console\Output\ConsoleOutput;
use Nette\DI\CompilerExtension;

/**
 * Instante CMS content editor
 */
class EditorExtension extends CompilerExtension
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
         * - register model facade
         * - register latte macros (for now, icms-text)
         * - add route to editor persistence presenter to router
         * - register js into js module container if js module container is present
         */
        $builder = $this->getContainerBuilder();

        $consoleOutput = $builder->addDefinition($this->prefix('consoleOutput'))
            ->setClass('Doctrine\DBAL\Migrations\OutputWriter')
            ->setFactory(get_called_class() . '::createConsoleOutput')
            ->setAutowired(FALSE);

        $configuration = $builder->addDefinition($this->prefix('configuration'))
            ->setClass('Doctrine\DBAL\Migrations\Configuration\Configuration', array(
                $consoleOutput,
            ))
            ->addSetup('setMigrationsTableName', array($config['table']))
            ->addSetup('setMigrationsDirectory', array($config['directory']))
            ->addSetup('setMigrationsNamespace', array($config['namespace']))
            ->addSetup('registerMigrationsFromDirectory', array($config['directory']));

        if (isset($config['console']) && $config['console']) {
            $this->processConsole($configuration);
        }
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
}

