<?php
if ( !defined('ABSPATH') ) die();

use Carbon_Fields\Container;
use Carbon_Fields\Field;

Container::make( 'post_meta', 'Информация о скидках' )
    ->where( 'post_type', '=', 'product' )
    ->add_fields( Array(
        Field::make( 'text', 'b-product-discount-title', 'Заголовок' ),
        Field::make( 'textarea', 'b-product-discount-text', 'Информация' ),
        Field::make( "association", 'b-product-association', "С этим товаром покупают" )
            ->set_types( Array(
                Array(
                    'type'      => 'post',
                    'post_type' => 'product',
                )
            ) ) 
            ->set_max(4),
        Field::make( "checkbox", 'b-product-nocart', 'Заменить кнопку "В корзину" на форму обратной связи' )
            ->set_option_value( 'yes' ),
        Field::make( "checkbox", 'b-product-hidetabs', 'Скрыть вкладки "Описание" и "Характеристики"' )
            ->set_option_value( 'yes' ),
        Field::make( "checkbox", 'product_setup_image', 'Разрешить загрузку фото с заказу этого продукта' )
            ->set_option_value( 'yes' ),
    ) );