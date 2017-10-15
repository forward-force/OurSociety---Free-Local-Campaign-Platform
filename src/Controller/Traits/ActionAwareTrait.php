<?php
declare(strict_types=1);

namespace OurSociety\Controller\Traits;

use Cake\Controller\Exception\MissingActionException;
use Cake\Core\Configure;
use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\Utility\Inflector;
use OurSociety\Controller\Exception\MissingActionClassException;

trait ActionAwareTrait
{
    //public function invokeAction(): ?Response
    /** @noinspection ReturnTypeCanBeDeclaredInspection */
    public function invokeAction() // TODO: Type-hint temporarily dropped for compatibility with \Crud\Controller\ControllerTrait::invokeAction()
    {
        return $this->invokeActionMethodOrClass();
    }

    private function getActionClassName(): string
    {
        return str_replace('\\\\', '\\', sprintf(
            '\%s\Action\%s\%s\%sAction',
            $this->getApplicationNamespace(),
            Inflector::camelize($this->getRequestPrefixParam()),
            $this->getControllerName(false),
            ucfirst($this->getRequestActionParam())
        ));
    }

    private function getApplicationNamespace(): string
    {
        return Configure::read('App.namespace');
    }

    private function getControllerName(bool $withSuffix): string
    {
        if ($withSuffix === true) {
            return $this->name . 'Controller';
        }

        return $this->name;
    }

    private function getRequest(): ServerRequest
    {
        return $this->request;
    }

    private function getRequestActionParam(): string
    {
        return $this->getRequest()->getParam('action');
    }

    private function getRequestPrefixParam(): string
    {
        return $this->getRequest()->getParam('prefix', '');
    }

    private function getRequestPassParams(): array
    {
        return $this->getRequest()->getParam('pass');
    }

    private function invokeActionClass(): ?Response
    {
        $actionClassName = $this->getActionClassName();

        if (class_exists($actionClassName) === false) {
            $this->throwMissingActionException();
        }

        return (new $actionClassName($this))(...$this->getRequestPassParams());
    }

    private function invokeActionMethodOrClass(): ?Response
    {
        try {
            return $this->invokeActionMethod();
        } /** @noinspection BadExceptionsProcessingInspection */ catch (MissingActionException $exception) {
            return $this->invokeActionClass();
        }
    }

    private function invokeActionMethod(): ?Response
    {
        /** @noinspection PhpUndefinedClassInspection */
        return parent::invokeAction();
    }

    private function throwMissingActionException(): void
    {
        throw new MissingActionClassException($this->getActionClassName());
    }
}
