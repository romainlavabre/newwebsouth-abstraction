<?php


namespace Newwebsouth\Abstraction\Crud;


interface CrudInterface
{

    /**
     * @param string|null $index
     * @return mixed
     */
    public function getRepository(?string $index = null);
}
