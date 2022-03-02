<?php
if ( !defined('ABSPATH') ) die();

add_action( 'wp_nav_menu_item_custom_fields', function( $item_id, $item, $depth, $args, $id ) {

    wp_enqueue_script('add-one-media-js', get_template_directory_uri() . '/assets/js/add-one-media.js', Array('jquery'), time());
 
    if ( $depth  == 1 ) {

        $start_2_block = get_post_meta( $item_id, '_menu_start_2_block', true );
        $end_2_block = get_post_meta( $item_id, '_menu_end_2_block', true );

        echo '<p class="description">
            <label>
                <input type="checkbox" ' . checked( 'yes', $start_2_block, false ) . ' name="menu-start-2block[' . $item_id . ']">
                Начало колонки
            </label>
        </p>
        <p class="description">
            <label>
                <input type="checkbox" ' . checked( 'yes', $end_2_block, false ) . ' name="menu-end-2block[' . $item_id . ']">
                Конец колонки
            </label>
        </p>
        <p class="js-add-wrap js-add-wrap-' . $item_id . '">
            <label>
                <button type="button" name="menu-media[' . $item_id . ']" class="button button-primary button-large js-add-file" item-id="' . $item_id . '">Добавить файл</button>
            </label>
        </p>
        ';
    }

}, 10, 5 );

add_action( 'wp_update_nav_menu_item', function( $menu_id, $menu_item_db_id ) {

    $start = isset( $_POST[ 'menu-start-2block' ][ $menu_item_db_id ] ) && 'on' == $_POST[ 'menu-start-2block' ][ $menu_item_db_id ] ? 'yes' : 'no';
    $end   = isset( $_POST[ 'menu-end-2block' ][ $menu_item_db_id ] ) && 'on'   == $_POST[ 'menu-end-2block' ][ $menu_item_db_id ]   ? 'yes' : 'no';

    if ( isset($_POST[ 'add_file_id' ][ $menu_item_db_id ]) ) {
        $add_file_id = intval($_POST[ 'add_file_id' ][ $menu_item_db_id ]);
        update_post_meta( $menu_item_db_id, '_add_file_id', $add_file_id );
    }

    update_post_meta( $menu_item_db_id, '_menu_start_2_block', $start );
    update_post_meta( $menu_item_db_id, '_menu_end_2_block', $end );
    
}, 10, 2 );


add_filter('navigation_markup_template', function() {
    
    return '<nav class="navigation %1$s" role="navigation">
                <div class="nav-links">%3$s</div>
            </nav>';

}, 10, 2 );

add_filter( 'woocommerce_account_menu_items', 'change_account_links' );

function change_account_links( $items ) {
    
    if ( isset( $items['downloads'] ) )
        unset( $items['downloads'] );

    if ( isset( $items['edit-address'] ) )
        unset( $items['edit-address'] );

    return $items;
}

remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );

remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );

remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );

remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );

remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );


add_filter('product_attributes_type_selector', function( $array ) {

    if ( !strripos($_SERVER['REQUEST_URI'], 'p-admin') || ($_REQUEST['post_type'] == 'product' && $_REQUEST['page'] == 'product_attributes') ) {
        
        $array['image'] = 'Изображение';
        $array['color'] = 'Цвет';
        
    }
    
    return $array;

});

add_filter( 'woocommerce_checkout_fields', 'custom_woocommerce_checkout_fields');
function custom_woocommerce_checkout_fields ( $fields ) {

    unset($fields['billing']['billing_country']);
    unset($fields['billing']['billing_state']);
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_address_2']);

    $fields['billing']['billing_city']['label'] = 'Город';
    $fields['billing']['billing_phone']['required'] = true;

    $fields['billing']['billing_phone']['priority'] = 30;
    $fields['billing']['billing_email']['priority'] = 40;
    $fields['billing']['billing_city']['priority'] = 50;
    $fields['billing']['billing_postcode']['priority'] = 60;
    $fields['billing']['billing_address_1']['priority'] = 70;

    unset($fields['shipping']);

    return $fields;
}

add_action('wp_footer', function() {
    $link = explode('?', $_SERVER['REQUEST_URI'])['0'];
    
    if ( $link == '/' || strripos( $link, 'product/' ) || strripos( $link, 'catalog/' ) || strripos( $link, 'product-category/' ) ) : ?>
    
        <div class="m-modal m-modal-add-to-cart" id="add-to-cart-result">
            <div class="m-modal-background">

                <div class="m-modal-window m-modal-add-to-cart-window">
                    <span class="close"></span>
                    <div class="m-modal-window-header">
                        <b>Товар добавлен в корзину</b>
                        <p>Товаров в Вашей корзине: <span>2</span></p>

                        <div class="m-modal-add-to-cart-actions">

                            <div class="m-modal-add-to-cart-actions-item">
                                <a href="/cart/" class="btn btn-rosa">Перейти в корзину</a>
                            </div>

                            <div class="m-modal-add-to-cart-actions-item">
                                <button class="btn btn-light close-pop">Вернуться к сайту</button>
                            </div>

                        </div>                
                    </div>

                    <div class="m-modal-add-to-cart-buy-more">
                        <h3>Вместе с этим часто покупают:</h3>

                        <div class="owl-carousel m-modal-add-to-cart-buy-more-slider" id='add-to-cart-carousel'></div>

                    </div>

                </div>

            </div>
        </div>
    
    <?php
    endif; 

}, 1000);

add_action( 'save_post_product', function() {
    if ( isset( $_POST['b-table-item-feodoraxis'] ) && !empty( $_POST['b-table-item-feodoraxis'] ) ) {
        $post_id = intval( $_REQUEST['post_ID'] );
        $product_table_item_feodoraxis = $_POST['b-table-item-feodoraxis'];
        update_post_meta( $post_id, 'product_table_item_feodoraxis', $product_table_item_feodoraxis );
    }
});



$redaktor = get_role( 'editor' );
$redaktor->add_cap( 'edit_products' );
$redaktor->add_cap( 'edit_published_products' );
$redaktor->add_cap( 'publish_products' );
$redaktor->add_cap( 'edit_others_products' );
$redaktor->add_cap( 'manage_links' );
$redaktor->add_cap( 'edit_published_products' );
$redaktor->add_cap( 'manage_product_terms' );
$redaktor->add_cap( 'edit_product_terms' );
$redaktor->add_cap( 'assign_product_terms' );