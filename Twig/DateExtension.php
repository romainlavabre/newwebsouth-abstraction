<?php


namespace Newwebsouth\Abstraction\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class DateExtension extends AbstractExtension
{
    
    public function getFunctions()
    {
        return [
            new TwigFunction( 'str_date', [ $this, 'date' ] )
        ];
    }
    
    
    public function date( ?string $date ): ?string
    {
        if( strpos( $date, date( 'Y-m-d' ) ) !== FALSE ) {
            return 'Aujourd\'hui à ' . ( new \DateTime( $date ) )->format( 'H\hi' );
        } elseif( strpos( $date, date( 'Y-m-d - 1' ) ) !== FALSE ) {
            return 'Hier à ' . ( new \DateTime( $date ) )->format( 'H\hi' );
        } elseif( !empty( $date ) ) {
            return 'Le ' . ( new \DateTime( $date ) )->format( 'd/m/Y \à H\hi' );
        }
        
        return NULL;
    }
}
