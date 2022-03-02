<?php
if ( !defined('ABSPATH') ) die();

use Carbon_Fields\Field;
use Carbon_Fields\Block;

Block::make( 'General slider' )
    ->add_fields( Array(
        Field::make( 'complex', 'b-general-slider', 'Слайдер' )
            ->add_fields(Array(
                Field::make( 'image', 'b-general-item-bg', 'Картинка')
                    ->set_width(30),
                Field::make( 'textarea', 'b-general-item-title', 'Заголовок' )
                    ->set_width(70),
                Field::make( 'textarea', 'b-general-item-subtitle', 'Подзаголовок' ),
                Field::make( 'text', 'b-general-item-button-text', 'Текст кнопки' ) 
                    ->set_width(50),
                Field::make( 'text', 'b-general-item-button-link', 'Ссылка кнопки' ) 
                    ->set_width(50),
                Field::make( 'textarea', 'b-general-item-list', 'Список' )
            ) )
            ->set_collapsed( true )
            ->setup_labels( Array(
                'plural_name' => 'слайд',
                'singular_name' => 'слайд',
            ) ),
    ) )
    ->set_render_callback( function ( $fields, $attributes, $inner_blocks ) {

        if ( is_array($fields['b-general-slider']) ) : ?>
            
            <section class="b-general-slider">
                <div class="owl-carousel" id="general-slider">

                    <?php foreach ( $fields['b-general-slider'] as $key => $item ) : ?>
                            
                        <div class="b-general-slider-item" style="background-image: url(<?php
                            
                                echo get_picture_by_id($item['b-general-item-bg']);
                            
                            ?>);">
                            <div class="wrapper">
                                <div class="b-general-slider-content">
                                    <div class="b-general-slider-nums">
                                        <span><?php
                                        
                                            if ( ($key+1) > 9 )
                                                echo $key+1;
                                            else
                                                echo '0' . ($key+1);
                                        
                                        ?></span>
                                        <span>/</span>
                                        <span><sup><?php

                                            $count = count($fields['b-general-slider']);
                                            
                                            if ( $count > 9 ) 
                                                echo $count;
                                            else
                                                echo '0' . $count;
                                        
                                        ?></sup></span>
                                    </div>

                                    <div class="b-general-slider-title">
                                        <p><?php
                                        
                                            echo nl2br($item['b-general-item-title']);
                                        
                                        ?></p>
                                    </div>

                                    <div class="b-general-slider-subtitle">
                                        <p><?php echo nl2br($item['b-general-item-subtitle']); ?></p>
                                    </div>

                                    <div class="b-general-slider-button">
                                        <button class="btn btn-rosa btn-big"><?php echo $item['b-general-item-button-text']; ?></button>
                                    </div>

                                    <?php 
                                    if ( !empty($item['b-general-item-list']) ) {
                                        $list = explode( '<br />', nl2br($item['b-general-item-list']) );

                                        if ( is_array($list) && !empty($list) ) {
                                            echo '<div class="b-general-slider-list"><ul>';

                                            foreach ( $list as $v ) 
                                                echo '<li><span>' . $v . '</span></li>';

                                            echo '</ul></div>';
                                        }

                                    }
                                        
                                    ?>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>

                </div>
                <div class="b-general-slider-scroll" id="b-general-slider-scroll">
                    <span>Scroll</span>
                </div>
            </section>
        
        <?php
        endif;
    } );