<?php


namespace Newwebsouth\Abstraction\Crud;


use Nomess\Http\HttpRequest;

interface ServiceInterface
{
    public function service(HttpRequest $request, object $instance): bool;
}
