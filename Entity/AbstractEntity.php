<?php


namespace Newwebsouth\Abstraction\Entity;


use Nomess\Helpers\ArrayHelper;

abstract class AbstractEntity
{
    
    use ArrayHelper;
    
    /**
     * Add this instance to target
     *
     * @param object $object Object to notified
     * @param string $propertyName The property of object
     * @param string $propertyProvider The property provider (unused in case of ManyTo...)
     * @return $this
     */
    protected function addDependencyTo( object $object, string $propertyName, ?string $propertyProvider = NULL ): self
    {
        $reflectionProperty = $this->getReflectionProperty( $object, $propertyName );
        
        $value = NULL;
        
        if( $reflectionProperty->isInitialized( $object ) ) {
            $value = $reflectionProperty->getValue( $object );
        }
        
        if( $reflectionProperty->getType()->getName() === 'array' ) {
            $value[] = $this;
            $reflectionProperty->setValue( $object, $value );
        } else {
            
            $reflectionProperty->setValue( $object, $this );
            
            if( is_object( $value ) ) {
                $this->removeDependencyTo( $value, $propertyProvider );
            }
        }
        
        return $this;
    }
    
    
    /**
     * Remove this instance to target
     *
     * @param object $object Object to notified
     * @param string $propertyName The property of object
     * @return $this
     */
    protected function removeDependencyTo( object $object, string $propertyName ): self
    {
        $reflectionProperty = $this->getReflectionProperty( $object, $propertyName );
        
        if( $reflectionProperty->isInitialized() ) {
            $value = $reflectionProperty->getValue( $object );
            
            if( is_array( $value ) ) {
                if( $this->arrayContainsValue( $this, $value, TRUE ) ) {
                    unset( $value[$this->indexOf( $this, $value )] );
                }
            } else {
                $value = NULL;
            }
            
            $reflectionProperty->setValue( $object, $value );
        }
        
        return $this;
    }
    
    
    private function getReflectionProperty( object $object, string $propertyName ): \ReflectionProperty
    {
        $reflectionProperty = new \ReflectionProperty( get_class( $object ), $propertyName );
        
        if( !$reflectionProperty->isPublic() ) {
            $reflectionProperty->setAccessible( TRUE );
        }
        
        return $reflectionProperty;
    }
    
    abstract public function getId(): int;
}
