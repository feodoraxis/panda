<?php
if ( !defined('ABSPATH') ) die();

use Carbon_Fields\Container;
use Carbon_Fields\Field;

Container::make( 'post_meta', 'Контент' )
    ->where( 'post_type', '=', 'post' )
    ->add_fields( Array(
        Field::make( "image", "b-stock-image", 'Внутреннее изображение' )
            ->set_width(20),
        Field::make( "textarea", "b-stock-preview-description", 'Превью-описание' )
            ->set_width(80),
    ) ); 
