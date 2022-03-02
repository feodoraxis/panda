<?php
if ( !defined('ABSPATH') ) die();

add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_style( 'all-css', get_template_directory_uri() . '/assets/css/all.min.css', Array(), '2.0', false );

    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'yandex-js', 'https://api-maps.yandex.ru/2.1/?lang=ru_RU', Array(), '2.1', false );
    wp_enqueue_script( 'libs-js', get_template_directory_uri() . '/assets/js/libs.min.js', Array('jquery' ), '3.0', false );    
    wp_enqueue_script( 'common-js', get_template_directory_uri() . '/assets/js/common.js', Array( 'jquery', 'libs-js'), time(), true );

});

add_action( 'admin_enqueue_scripts', function() {
    $link = explode( '?', $_SERVER['REQUEST_URI'] )['0'];

    if ( $link == '/wp-admin/post.php' ) {
        $post = intval( $_REQUEST['post'] );
        $post_type = get_post_type( $post );

        if ( $post_type == 'product' ) {
            wp_enqueue_script( 'product-script-feodoraxis', '/wp-content/themes/panda/assets/js/product-script.js', array(), '1', true );
        }
    }
});