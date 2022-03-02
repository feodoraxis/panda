<?php
if ( !defined('ABSPATH') ) die();

use Carbon_Fields\Field;
use Carbon_Fields\Block;

Block::make( 'Prefers' )
    ->add_fields( Array(
        Field::make( 'complex', 'b-prefers-list', 'Преимущества' )
            ->add_fields(Array(
                Field::make( 'image', 'b-prefers-item-icon', 'Иконка SVG' )
                    ->set_width(30),
                Field::make( 'text', 'b-prefers-item-title', 'Заголовок' )
                    ->set_width(70),
                Field::make( 'text', 'b-prefers-item-subtitle', 'Подзаголовок' )

            ) )
            ->set_collapsed( true )
            ->set_max(4)
            ->setup_labels( Array(
                'plural_name' => 'преимщество',
                'singular_name' => 'преимщество',
            ) ),
    ) )
    ->set_render_callback( function ( $fields, $attributes, $inner_blocks ) {

        if ( is_array($fields['b-prefers-list']) ) : ?>
            
            <section class="b-prefers">
                <div class="wrapper">
                    <div class="row">

                        <?php foreach ( $fields['b-prefers-list'] as $item ) : ?>

                            <div class="col-md-3 col-6">
                                <div class="b-prefers-item">
                                    <div class="b-prefers-icon">
                                        <img src="<?php
                                        
                                            echo get_picture_by_id($item['b-prefers-item-icon']);
                                        
                                        ?>" alt="<?php echo $item['b-prefers-item-title']; ?>">
                                    </div>
                                    <b><?php echo $item['b-prefers-item-title']; ?></b>
                                    <p><?php echo $item['b-prefers-item-subtitle']; ?></p>
                                </div>
                            </div>

                        <?php endforeach; ?>

                    </div>
                </div>
            </section>
        
        <?php
        endif;
    } );