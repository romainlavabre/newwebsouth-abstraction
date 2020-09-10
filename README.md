For use AppController::getEntity, you must have an id in request parameters, if you want redirect your visitor if the entity is not found, add in datacenter :
> 'nws_abstraction_redirect' => 'route.name'

For crud interfaces, think to add in container configuration.


