<?php
if ( !defined('ABSPATH') ) die();

use Carbon_Fields\Container;
use Carbon_Fields\Field;

Container::make( 'post_meta', "Дополнительно" )
    ->where( 'post_type', '=', 'page' )
    ->where( 'post_template', '=', 'page-ccatalog.php' )
    ->add_fields( Array(
        Field::make( 'complex', 'b-ccatalog-list', 'Каталог' ) 
            ->add_fields( Array(
                
                Field::make( 'text', 'b-ccatalog-item-title', 'Название' )
                    ->set_width(50),
                Field::make( 'text', 'b-ccatalog-item-price', 'Цена' )
                    ->set_width(50),
                Field::make( 'image', 'b-ccatalog-item-image', 'Изображение' )
                    ->set_width(17),
                Field::make( 'textarea', 'b-ccatalog-item-description', 'Описание' )
                    ->set_width(83),
            ) )
            ->set_collapsed( true )
            ->setup_labels( Array(
                'plural_name'   => 'товар',
                'singular_name' => 'товар',
            ) )
    ) );