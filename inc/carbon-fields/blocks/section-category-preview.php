<?php
if ( !defined('ABSPATH') ) die();

use Carbon_Fields\Field;
use Carbon_Fields\Block;

Block::make( 'Category preview' )
    ->add_fields( Array(

        Field::make( 'association', 'b-cp-category', 'Выберите категорию' )
            ->set_types( Array(
                Array(
                    'type'      => 'term',
                    'post_type' => 'product_cat',
                )
            ) )
            ->set_min(1)
            ->set_max(1),
        Field::make( 'html', 'b-cp-html', 'Section Description' )
            ->set_html( "По техническим причинам в админке нет возможности ограничивать выбор продуктов категорией, которую вы выбрали выше. Поэтому в любом случае тут выводятся все товары из каталога и вам самостоятельно нужно следить за тем, чтобы они относились к категории, которую вы выбрали" ),
        Field::make( 'association', 'b-cp-product', 'Выбирите товары' ) 
            ->set_types( Array(
                Array(
                    'type'      => 'post',
                    'post_type' => 'product',
                )
            ) )
            ->set_min(4)
            ->set_max(4),

    ) )
    ->set_render_callback( function ( $fields, $attributes, $inner_blocks ) {

        // d($fields);

        if ( $fields['b-cp-category']['0']['id'] > 0 ) : 
        
            $term = get_term_by( 'id', $fields['b-cp-category']['0']['id'], $fields['b-cp-category']['0']['subtype'] );
            
            if ( is_array($fields['b-cp-product']) ) {
                foreach ( $fields['b-cp-product'] as $item ) {
                    $posts[] = $item['id'];
                }

                $query = new WP_Query([
                    'post__in' => $posts,
                    'post_type' => 'product',
                ]);
            }   
        ?>
            
            <section class="b-category-preview">
                <div class="b-category-preview-header">
                    <div class="b-category-preview-title">
                        <a href="<?php $term_link = get_term_link($term); echo $term_link; ?>"><?php echo $term->name; ?></a>
                    </div>
                </div>

                <?php if ( $query->have_posts() ) : ?>
                        
                    <div class="b-category-preview-body">
                        <div class="wrapper">
                            <div class="row">

                                <?php while ( $query->have_posts() ) : $query->the_post(); ?>

                                    <div class="col-lg-3 col-md-6">
                                        
                                        <?php wc_get_template_part( 'content', 'product' ); ?>

                                    </div>

                                <?php endwhile; ?>

                            </div>
                        </div>
                    </div>

                <?php endif; ?>

                <div class="b-category-preview-footer">
                    <a class="btn btn-light btn-big btn-icon" href="<?php echo $term_link; ?>">
                        <span class="icon">
                            
                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                            viewBox="0 0 302 273" style="enable-background:new 0 0 302 273;" xml:space="preserve">
                            <style type="text/css">
                            .st0{fill:#F50051;}
                            </style>
                            <rect x="24" y="43" class="st0" width="251" height="26"/>
                            <rect x="24" y="125" class="st0" width="251" height="26"/>
                            <rect x="24" y="207" class="st0" width="197.9" height="26"/>
                            </svg>

                        </span>
                        <span>Посмотреть ещё</span>
                    </a>
                </div>
            </section>
        
        <?php
        endif;
    } );