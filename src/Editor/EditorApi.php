<?php
/**
 * Created by PhpStorm.
 * User: richa
 * Date: 06.10.2015
 * Time: 11:23
 */

namespace Instante\CMS\Editor;


use Kdyby\Doctrine\EntityManager;
use Nette\Application\BadRequestException;
use Nette\Application\Responses\TextResponse;

final class EditorApi
{
    /** @var EntityManager */
    private $em;

    /**
     * EditorApi constructor.
     * @param EntityManager $em
     */
    private function __construct(EntityManager $em = NULL)
    {
        $this->em = $em;
    }

    /**
     * Creates function callback as a route handler for the extension.
     * Redirects action parameter from route to $this->action<Action> method, like Nette's presenters.
     *
     * @return \Closure
     */
    public static function getEntryPoint()
    {
        return function (
            EntityManager $em,
            $action,
            $presenter
        ) {
            $params = $presenter->getRequest()->getParameters();
            unset($params['callback']);
            unset($params['action']);
            $api = new self($em);
            return $api->dispatch($action, $params);
        };
    }

    private function dispatch($action, $params)
    {
        $actionMethod = 'action' . ($action === NULL ? '' : ucfirst($action));
        if (!method_exists($this, $actionMethod)) {
            throw new BadRequestException;
        }
        return $this->$actionMethod($params);
    }

    private function actionSave($params)
    {
        return new TextResponse('foo');
    }
}
