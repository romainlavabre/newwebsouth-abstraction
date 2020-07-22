<?php


namespace Newwebsouth\Abstraction\Crud;


use Nomess\Http\HttpRequest;

interface UpdateInterface extends CrudInterface
{
    public function update(HttpRequest $request, object $instance): bool;
}
