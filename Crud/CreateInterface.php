<?php


namespace Newwebsouth\Abstraction\Crud;


use Nomess\Http\HttpRequest;

interface CreateInterface extends CrudInterface
{
    public function create(HttpRequest $request, object $instance): bool;

}
