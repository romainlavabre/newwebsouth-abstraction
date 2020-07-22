<?php


namespace Newwebsouth\Abstraction\Crud;


use Nomess\Http\HttpRequest;

interface DeleteInterface extends CrudInterface
{
    public function delete(HttpRequest $request, object $instance): bool;
}
