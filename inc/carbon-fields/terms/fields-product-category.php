<?php
if ( !defined('ABSPATH') ) die();

use Carbon_Fields\Container;
use Carbon_Fields\Field;

Container::make( 'term_meta', 'Дополнительно' )
    ->where( 'term_taxonomy', '=', 'product_cat' )
    ->add_fields( Array(
        Field::make("text", 'product_cat-title', "Заголовок описания"),
        Field::make("textarea", 'product_cat-desc-1', 'Описание в колонке 1')->set_width(33),
        Field::make("textarea", 'product_cat-desc-2', 'Описание в колонке 2')->set_width(33),
        Field::make("textarea", 'product_cat-desc-3', 'Описание в колонке 3')->set_width(33),
    ) );