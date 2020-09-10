<?php


namespace Newwebsouth\Abstraction\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SortedExtension extends AbstractExtension
{
    
    public function getFunctions()
    {
        return [
            new TwigFunction( 'sorted_by_desc', [ $this, 'desc' ] ),
            new TwigFunction( 'sorted_by_asc', [ $this, 'asc' ] )
        ];
    }
    
    
    public function desc( ?array $array ): array
    {
        
        if( empty( $array ) ) {
            return [];
        }
        
        usort( $array, function ( object $a, object $b ) {
            return ( $a->getId() < $b->getId() ) ? 1 : -1;
        } );
        
        return $array;
    }
    
    
    public function asc( ?array $array ): array
    {
        $list = array();
        
        if( empty( $array ) ) {
            return $list;
        }
        
        usort( $array, function ( object $a, object $b ) {
            return ( $a->getId() < $b->getId() ) ? -1 : 1;
        } );
        
        return $list = $array;
    }
}
