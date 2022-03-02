<?php
if ( !defined('ABSPATH') ) die();

use Carbon_Fields\Container;
use Carbon_Fields\Field;

Container::make( 'post_meta', 'Контент' )
    ->where( 'post_type', '=', 'page' )
    ->where( 'post_template', '=', 'page-contacts.php' )
    ->add_fields( Array(
        Field::make( 'text', 'b-contact-phone', 'Телефон' )
            ->set_width(25),
        Field::make( 'text', 'b-contact-company', 'Название' )
            ->set_width(25),
        Field::make( 'text', 'b-contact-address', 'Адрес' )
            ->set_width(25),
        Field::make( 'text', 'b-contact-email', 'E-mail' )
            ->set_width(25),

        Field::make( 'text', 'b-contacts-coords', 'Координаты карты' )
    ) ); 
