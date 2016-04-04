<?php
/**
 * Created by PhpStorm.
 * User: richa
 * Date: 06.10.2015
 * Time: 11:23
 */

namespace Instante\CMS\Editor;


use Nette\Application\BadRequestException;
use Nette\Application\IResponse;
use Nette\Application\Responses\JsonResponse;
use Nette\Application\Responses\TextResponse;
use NetteModule\MicroPresenter;

final class EditorApi
{
    /** @var IEditableFacade */
    private $editableFacade;

    private function __construct(IEditableFacade $editableFacade = NULL)
    {
        $this->editableFacade = $editableFacade;
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
            IEditableFacade $editableFacade,
            $action,
            $presenter
            // presenter arg type intentionally not present there, as this function's args are built with autowiring
            // and the presenter is automatically added as last argument. Specifying MicroPresenter type results
            // in conflict with autowiring mechanism.
        ) {
            /** @var MicroPresenter $presenter */
            $params = $presenter->getRequest()->getParameters();
            unset($params['callback']);
            unset($params['action']);
            $api = new self($editableFacade);
            return $api->dispatch($action, $params);
        };
    }

    private function dispatch($action, $params)
    {
        $actionMethod = 'action' . ($action === NULL ? '' : ucfirst($action));
        if (!method_exists($this, $actionMethod)) {
            throw new BadRequestException;
        }
        $response = $this->$actionMethod($params);
        if ($response instanceof IResponse) {
            return $response;
        }
        if (is_string($response)) {
            return new TextResponse($response);
        }
        if (is_array($response)) {
            return new JsonResponse($response);
        }
        throw new \Exception('unknown response type');
    }

    private function actionLoad($params)
    {
        return new TextResponse($this->editableFacade->getText($params['ident']));
    }

    private function actionSave($params)
    {
        return new JsonResponse($this->editableFacade->setText($params['ident'], $params['text']));
    }
}
