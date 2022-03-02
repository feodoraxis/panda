<?php

if ( !defined('ABSPATH') ) die();

use Carbon_Fields\Container;
use Carbon_Fields\Field;

Container::make( 'theme_options', __( 'Theme Options' ) )

    ->add_tab( 'Общие настройки', array(
        Field::make( 'image', 'option-logo', 'Логотип' )
            ->set_width(15),
        Field::make( 'image', 'option-logo-light', 'Логотип light' )
            ->set_width(15),
        Field::make( 'text', 'option-phone', 'Телефон' )
            ->set_width(23),
        Field::make( 'text', 'option-email', 'E-mail' )
            ->set_width(23),
        Field::make( 'text', 'option-email-recall', 'E-mail для формы' )
            ->set_width(23),
        Field::make( 'text', 'option-company', 'Компания')
            ->set_width(33),
        Field::make( 'text', 'option-inn', 'ИНН')
            ->set_width(33),
        Field::make( 'text', 'option-address', 'Адрес')
            ->set_width(33),
        Field::make( 'textarea', 'option-copyright', 'Copyright' ),
    ) );