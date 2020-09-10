<?php


namespace Newwebsouth\Abstraction\Crud;


use Nomess\Http\HttpRequest;

interface ServiceInterface extends CrudInterface
{
    public function service(HttpRequest $request, object $instance): bool;
}
