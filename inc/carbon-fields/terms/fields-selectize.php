<?php
if ( !defined('ABSPATH') ) die();

use Carbon_Fields\Container;
use Carbon_Fields\Field;

Container::make( 'term_meta', 'Дополнительно' )
    ->where( 'term_taxonomy', '=', 'pa_vyberite' )
    ->or_where( 'term_taxonomy', '=', 'pa_vid-kruzhki' )
    ->add_fields( Array(
        Field::make( 'image', 'pa_vyberite-thumb', 'Изображение' ),
    ) );