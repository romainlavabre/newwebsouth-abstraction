<?php


namespace Newwebsouth\Abstraction\Controller;


use Newwebsouth\Abstraction\Crud\CreateInterface;
use Newwebsouth\Abstraction\Crud\DeleteInterface;
use Newwebsouth\Abstraction\Crud\ServiceInterface;
use Newwebsouth\Abstraction\Crud\UpdateInterface;
use Newwebsouth\Exception\UninitializedException;
use Nomess\Component\Orm\EntityManagerInterface;
use Nomess\Component\Parameter\ParameterStoreInterface;
use Nomess\Helpers\DataHelper;
use Nomess\Http\HttpRequest;
use Nomess\Http\HttpResponse;
use Nomess\Manager\Distributor;

abstract class AppController
{
    
    private const PARAM_ID                 = 'id';
    private const ERROR_MESSAGE            = 'error';
    private const CONF_ROUTE               = 'route';
    private const CONF_REQUEST_METHOD      = 'request_method';
    private array                     $configuration = [
        self::CONF_ROUTE          => NULL,
        self::CONF_REQUEST_METHOD => 'POST'
    ];
    
    
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
            } else {
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
