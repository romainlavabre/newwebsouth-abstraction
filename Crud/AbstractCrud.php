<?php


namespace Newwebsouth\Abstraction\Crud;

use Nomess\Component\Orm\EntityManagerInterface;

abstract class AbstractCrud
{

    protected const ERROR           = 'error';
    protected const SUCCESS         = 'success';

    protected array $repository = [];

    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Get data of the repository
     *
     * @param string|null $index
     * @return mixed
     */
    public function getRepository(?string $index = NULL)
    {
        if($index === NULL){
            return $this->repository;
        }else{
            if(isset($this->repository[$index])){
                return $this->repository[$index];
            }

            return NULL;
        }
    }

    /**
     * Set data in repository
     * Please, use constant of AbstractCrud for index error or success
     *
     * @param string $index
     * @param mixed $data
     */
    protected function setRepository(string $index, $data): void
    {
        if(is_array($data)){
            foreach($data as $key => $value) {
                $this->repository[$index][$key] = $value;
            }
        }else{
            $this->repository[$index] = $data;
        }
    }


    /**
     * Purge the repository
     */
    protected function purgeRepository(): void
    {
        $this->repository = [];
    }
}
