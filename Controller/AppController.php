<?php


namespace Newwebsouth\Abstraction\Controller;


use Newwebsouth\Abstraction\Crud\CreateInterface;
use Newwebsouth\Abstraction\Crud\DeleteInterface;
use Newwebsouth\Abstraction\Crud\ServiceInterface;
use Newwebsouth\Abstraction\Crud\UpdateInterface;
use Nomess\Components\EntityManager\EntityManagerInterface;
use Nomess\Http\HttpRequest;
use Nomess\Manager\Distributor;
use Nomess\Helpers\DataHelper;

abstract class AppController extends Distributor
{

    use DataHelper;
    
    private const PARAM_ID            = 'id';

    private const ERROR_MESSAGE       = 'error';

    private const CONF_ROUTE          = 'route';

    private const CONF_REQUEST_METHOD = 'request_method';
    
    private const DC_KEY_REDIRECT_TO_ROUTE = 'nws_abstraction_redirect';

    protected EntityManagerInterface $entityManager;
    private array                    $configuration = [
        self::CONF_ROUTE          => NULL,
        self::CONF_REQUEST_METHOD => 'POST'
    ];


    public function __construct( EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    
    /**
     * Search the id request parameter, if found return the entity associate, if not found, redirect to
     * ressource unavailable route ("nws_abstraction_redirect") if data found in data center, else return null
     *
     * @param string $classname
     * @param HttpRequest $request
     * @return object|null
     */
    protected function getEntity( string $classname, HttpRequest $request ): ?object
    {
        $id     = (int)$request->getParameter( self::PARAM_ID );
        $entity = $this->entityManager->find( $classname, $id );

        if($entity !== NULL && !is_null($this->get(self::DC_KEY_REDIRECT_TO_ROUTE))){
            $this->redirectToLocal($this->get(self::DC_KEY_REDIRECT_TO_ROUTE));
        }
        
        return $entity;
    }
    
    
    /**
     * Valid that request method is good (POST by default) and if token is valid, and call your service.
     * If your service return false, the error will be added to request by the repository of service with "error"
     * key (constant)
     *
     * @param HttpRequest $request
     * @param CreateInterface $create
     * @param object $instance
     * @return bool
     */
    protected function manageCreate( HttpRequest $request, CreateInterface $create, object $instance ): bool
    {
        return $this->kernelManager( $request, $create, 'create', $instance );
    }


    private function kernelManager( HttpRequest $request, $interface, string $methodName, $instance ): bool
    {
        if( $request->isRequestMethod( $this->configuration[self::CONF_REQUEST_METHOD] ) ) {
            if( $request->isValidToken() ) {
                if( $interface->$methodName( $request, $instance ) ) {
                    return TRUE;
                }
            }
            else {
                $request->setError( 'Le formulaire n\'a pas pu être transmis' );

                return FALSE;
            }

            $request->setError( $interface->getRepository( self::ERROR_MESSAGE ) );
        }

        return FALSE;
    }
    
    
    /**
     * Valid that request method is good (POST by default) and if token is valid, and call your service.
     * If your service return false, the error will be added to request by the repository of service with "error"
     * key (constant)
     *
     * @param HttpRequest $request
     * @param UpdateInterface $update
     * @param object $instance
     * @return bool
     */
    protected function manageUpdate( HttpRequest $request, UpdateInterface $update, object $instance ): bool
    {
        return $this->kernelManager( $request, $update, 'update', $instance );
    }
    
    
    /**
     * Valid that request method is good (POST by default) and if token is valid, and call your service.
     * If your service return false, the error will be added to request by the repository of service with "error"
     * key (constant)
     *
     * @param HttpRequest $request
     * @param DeleteInterface $delete
     * @param object $instance
     * @return bool
     */
    protected function manageDelete( HttpRequest $request, DeleteInterface $delete, object $instance ): bool
    {
        return $this->kernelManager( $request, $delete, 'delete', $instance );
    }
    
    
    /**
     * Valid that request method is good (POST by default) and if token is valid, and call your service.
     * If your service return false, the error will be added to request by the repository of service with "error"
     * key (constant)
     *
     * @param HttpRequest $request
     * @param ServiceInterface $service
     * @param object $instance
     * @return bool
     */
    protected function manageService( HttpRequest $request, ServiceInterface $service, object $instance )
    {
        return $this->kernelManager( $request, $service, 'service', $instance );
    }
    
    
    /**
     * Update the default method of request to search
     *
     * @param string $requestMethod
     * @return $this
     */
    protected function setSearchMethod( string $requestMethod ): self
    {
        $this->configuration[self::CONF_REQUEST_METHOD] = $requestMethod;

        return $this;
    }
}
