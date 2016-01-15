<?php

namespace Instante\Tests\CMS\DI;

use Instante\CMS\Editor\EditorApi;
use Instante\CMS\Editor\IEditableFacade;
use Nette\Application\Responses\TextResponse;
use Nette\Configurator;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

class MockEditableFacade implements IEditableFacade
{
    /**
     * @param string $ident
     * @param string $text
     * @return void
     */
    public function setText($ident, $text)
    {
        // TODO: Implement setText() method.
    }

    /**
     * @param string $ident
     * @return string
     */
    public function getText($ident)
    {
        return '---' . $ident . '---';
    }
}

$callback = EditorApi::getEntryPoint();

class MockRequest
{
    public function getParameters()
    {
        return ['ident' => 'foo'];
    }
}

class MockPresenter
{
    public function getRequest()
    {
        return new MockRequest();
    }
}

/** @var TextResponse $response */
$response = $callback(new MockEditableFacade, 'load', new MockPresenter);
Assert::type(TextResponse::class, $response);
Assert::same('---foo---', $response->getSource());


