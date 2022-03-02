<?php
if ( !defined('ABSPATH') ) die();

add_action( 'wp_ajax_recall_back_form', 'recall_back_form' );
add_action( 'wp_ajax_nopriv_recall_back_form', 'recall_back_form' ); 
function recall_back_form() {

    $result_error = Array(
        'success' => 0, 
        'title' => 'Ошибка', 
        'text' => 'Не удалось отправить сообщение'
    );

    if ( empty( $_POST['form'] ) ) {
        die( json_encode( $result_error ) );
    }

    $form_data = $_POST['form'];

    if ( !empty( trim( $form_data["name"] ) ) || !empty( trim( $form_data["phone"] ) ) ) { 
        die( json_encode( Array(
            'success' => 0, 
            'title' => 'Ошибка', 
            'text' => 'Необходимо указать имя и телефон'
        ) ) );
    }

    $mail_theme = "Сообщение из формы обратной связи";
    $email_to = carbon_get_theme_option("option-email-recall");

    $name = sanitize_text_field($form_data["gdfewd67ygdwr"]);
    $phone = sanitize_text_field($form_data["nyduwte6urydf"]);
    $email = sanitize_text_field($form_data["dneuwydfwyef7"]);

    $params = Array(
        'Тема' => $mail_theme,
        'Имя' => $name,
        'Телефон' => $phone,
        'Email' => $email,
        'UTM-метки' => $_POST['si_utm']
    );
    
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";
    $headers .= "From: boot@" . $_SERVER['HTTP_HOST'] . "\r\n";

    $multipart = create_message( $mail_theme, $params );

    if ( mail( $email_to, '=?UTF-8?B?' . base64_encode( $mail_theme ) . '?=', $multipart, $headers ) ) {
        die( json_encode( Array(
            'success' => 1, 
            'title' => "Спасибо!", 
            'message' => "Ожидайте нашего звонка" 
        ) ) );
    } else {
        die( json_encode( $result_error ) );
    }

    exit;

}

add_action( 'wp_ajax_call_order_product', 'call_order_product' );
add_action( 'wp_ajax_nopriv_call_order_product', 'call_order_product' ); 
function call_order_product() {

    $result_error = Array(
        'success' => 0, 
        'title' => 'Ошибка', 
        'text' => 'Не удалось отправить сообщение'
    );

    if ( empty( $_POST['form'] ) ) {
        die( json_encode( $result_error ) );
    }

    $form_data = $_POST['form'];

    if ( empty( trim( $form_data["name"] ) ) || empty( trim( $form_data["phone"] ) ) || empty( trim( $form_data["email"] ) ) ) { 
        die( json_encode( Array(
            'success' => 0, 
            'title' => 'Ошибка', 
            'text' => 'Необходимо указать имя и телефон'
        ) ) );
    }

    $mail_theme = "Заказ из формы обратной связи";
    $email_to = carbon_get_theme_option("option-email-recall");

    $name = sanitize_text_field( $form_data["name"] );
    $phone = sanitize_text_field( $form_data["phone"] );
    $email = sanitize_text_field( $form_data["email"] );

    $product = wc_get_product( intval( $form_data['product_id'] ) );

    $params = Array(
        'Тема' => $mail_theme,
        'Имя' => $name,
        'Телефон' => $phone,
        'Email' => $email,
        'Товар' => '<a href="' . get_permalink( $product->get_id() ) . '" target="_blank">' . $product->get_name() . '</a>',
        'UTM-метки' => $_POST['si_utm']
    );
    
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";
    $headers .= "From: boot@" . $_SERVER['HTTP_HOST'] . "\r\n";

    $multipart = create_message( $mail_theme, $params );

    if ( mail( $email_to, '=?UTF-8?B?' . base64_encode( $mail_theme ) . '?=', $multipart, $headers ) ) {
        die( json_encode( Array(
            'success' => 1, 
            'title' => "Спасибо!", 
            'message' => "Ожидайте нашего звонка"
        ) ) );
    } else {
        die( json_encode( $result_error ) );
    }

}

add_action( 'wp_ajax_custom_order_form', 'custom_order_form' );
add_action( 'wp_ajax_nopriv_custom_order_form', 'custom_order_form' ); 
function custom_order_form() {

    $result_error = Array(
        'success' => 0, 
        'title' => 'Ошибка', 
        'text' => 'Не удалось отправить сообщение'
    );

    if ( empty( $_POST['form'] ) ) {
        die( json_encode( $result_error ) );
    }

    $form_data = $_POST['form'];

    if ( empty( trim( $form_data["name"] ) ) || empty( trim( $form_data["email"] ) ) ) { 
        die( json_encode( Array(
            'success' => 0, 
            'title' => 'Ошибка', 
            'text' => 'Необходимо указать имя и email'
        ) ) );
    }

    $mail_theme = "Заказ из формы обратной связи гелиевых шаров";
    $email_to = carbon_get_theme_option( "option-email-recall" );

    $product_name  = sanitize_text_field( $form_data["product_name"] );
    $product_price = sanitize_text_field( $form_data["product_price"] );

    $name  = sanitize_text_field( $form_data["name"] );
    $phone = sanitize_text_field( $form_data["phone"] );
    $email = sanitize_text_field( $form_data["email"] );

    $product = wc_get_product( intval( $form_data['product_id'] ) );

    $params = Array(
        'Тема' => $mail_theme,
        'Имя' => $name,
        'Телефон' => $phone,
        'Email' => $email,

        'Название товара' => $product_name,
        'Стоимость товара' => $product_price,

        'Товар' => '<a href="' . get_permalink( $product->get_id() ) . '" target="_blank">' . $product->get_name() . '</a>',
        'UTM-метки' => $_POST['si_utm']
    );
    
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";
    $headers .= "From: boot@" . $_SERVER['HTTP_HOST'] . "\r\n";

    $multipart = create_message( $mail_theme, $params );

    if ( mail( $email_to, '=?UTF-8?B?' . base64_encode( $mail_theme ) . '?=', $multipart, $headers ) ) {
        die( json_encode( Array(
            'success' => 1, 
            'title' => "Спасибо!", 
            'message' => "Ожидайте нашего звонка"
        ) ) );
    } else {
        die( json_encode( $result_error ) );
    }

}

add_action( 'wp_ajax_upload_more', 'upload_more' );
add_action( 'wp_ajax_nopriv_upload_more', 'upload_more' ); 
function upload_more() {

    if ( empty( $_POST['form'] ) ) {
        return;
    }

    $form_data = $_POST['form'];

    $args = unserialize( stripslashes( $form_data['query'] ) );
    $args['paged'] = $form_data['page'] + 1;
    $args['post_status'] = 'publish';
 
    $query = new WP_Query( $args );

    if ( $query->have_posts() ) :
 
        switch ( $args['pagename'] ) {

            case 'promotions' :

                while ( $query->have_posts() ) : $query->the_post();
 
                    get_template_part( 'template-parts/content', get_post_type() );
        
                endwhile;
                
                break; 
            case 'product' : 

                while ( $query->have_posts() ) : $query->the_post();

                    echo '<div class="col-lg-3 col-md-6">';

                    /**
                     * Hook: woocommerce_shop_loop.
                     */
                    do_action( 'woocommerce_shop_loop' );
 
                    wc_get_template_part( 'content', 'product' );

                    echo '</div>';
        
                endwhile;
                break; 
        }

        if ( empty( trim( $args['pagename'] ) ) ) {
            switch ( $args['post_type'] ) {

                case 'product' : 
                    while ( $query->have_posts() ) : $query->the_post();
    
                        echo '<div class="col-lg-3 col-md-6">';
    
                        /**
                         * Hook: woocommerce_shop_loop.
                         */
                        do_action( 'woocommerce_shop_loop' );
     
                        wc_get_template_part( 'content', 'product' );
    
                        echo '</div>';
            
                    endwhile;
                    break; 
            }
        }

        if ( empty( trim( $args['pagename'] ) ) && empty( trim( $args['pagename'] ) ) ) {
            switch ( $args['taxonomy'] ) {

                case 'product_cat' : 

                    while( $query->have_posts() ) : $query->the_post();
    
                        echo '<div class="col-lg-3 col-md-6">';
    
                        /**
                         * Hook: woocommerce_shop_loop.
                         */
                        do_action( 'woocommerce_shop_loop' );
     
                        wc_get_template_part( 'content', 'product' );
    
                        echo '</div>';
            
                    endwhile;

                    break; 
            }
        }

    endif;

    wp_reset_postdata();
    
    die();
}

add_action( 'wp_ajax_add_to_cart_variation',        'add_to_cart_variation' );
add_action( 'wp_ajax_nopriv_add_to_cart_variation', 'add_to_cart_variation' ); 
function add_to_cart_variation() {

    global $woocommerce;

    $result = Array(
        "result" => false,
        "title" => "Произошла ошибка",
        "text" => "",
    );

    if ( empty( $_POST['form'] ) ) {
        die( json_encode( $result ) );
    }

    $form_data = $_POST['form'];

    if ( !is_array( $form_data ) ) {
        die( json_encode( $result ) );
    }

    $product_id       = intval( $form_data['product_id'] );
    $variation_id     = intval( $form_data['variation_id'] );
    $product_quantity = intval( $form_data['product_quantity'] );

    if ( $product_id < 2 || $variation_id < 2 ) {
        die(json_encode($result));
    }
    
    if ( $product_quantity < 1 ) {
        $product_quantity = 1;
    }

    if ( $woocommerce->cart->add_to_cart( $product_id, $product_quantity, $variation_id ) == false ) {
        die( json_encode( $result ) );
    }

    $result = Array(
        'result' => true,
        "title" => 'Товар добавлен в корзину',
        "text" => "Товаров в вашей корзине <span>" . $woocommerce->cart->get_cart_contents_count() . "</span>",
    );

    die( json_encode( $result ) );
    
}

add_action( 'wp_ajax_add_to_cart_simple', 'add_to_cart_simple' );
add_action( 'wp_ajax_nopriv_add_to_cart_simple', 'add_to_cart_simple' ); 
function add_to_cart_simple() {

    global $woocommerce;

    $result = Array(
        "result" => false,
        "title" => "Произошла ошибка",
        "text" => "",
    );

    if ( empty( $_POST['form'] ) ) {
        die( json_encode( $result ) );
    }

    $form_data = $_POST['form'];

    if ( !is_array( $form_data ) ) {
        die( json_encode( $result ) );
    }

    $product_id       = intval( $form_data['product_id'] );
    $product_quantity = intval( $form_data['product_quantity'] );

    if ( $product_id < 2 ) {
        die( json_encode( $result ) );
    }
    
    if ( $product_quantity < 1 ) {
        $product_quantity = 1;
    }

    if ( $woocommerce->cart->add_to_cart( $product_id, $product_quantity ) == false ){
        die(json_encode($result));
    }

    $result = Array(
        'result' => true,
        "title" => 'Товар добавлен в корзину',
        "text" => "Товаров в вашей корзине <span>" . $woocommerce->cart->get_cart_contents_count() . "</span>",
    );

    die( json_encode( $result ) );
    
}

add_action( 'wp_ajax_add_to_cart_preview', 'add_to_cart_preview' );
add_action( 'wp_ajax_nopriv_add_to_cart_preview', 'add_to_cart_preview' ); 
function add_to_cart_preview() {

    global $woocommerce;

    $result = Array(
        "success" => false,
        "title" => "Произошла ошибка",
        "text" => "",
    );

    if ( empty( $_POST['form'] ) ){
        die( json_encode( $result ) );
    }

    $form_data = $_POST['form'];

    if ( intval( $form_data['product_id'] ) < 2 ) {
        die( json_encode( $result ) );
    }

    $product_id = intval( $form_data['product_id'] );

    $product = wc_get_product( $product_id );

    //Простой товар
    if ( $product->get_type() == 'simple' && $woocommerce->cart->add_to_cart( $product_id, 1 ) == false ) {
        die( json_encode($result) );
    }

    //Далее -- только если вариативный
    if ( $product->get_type() == 'variable' ) {
     
        $variations_array = $product->get_available_variations();

        foreach ( $variations_array as $item ) {

            foreach ( $item['attributes'] as $k => $attr ) { 

                $kk = str_replace( 'attribute_', '', $k );

                if ( !is_array( $attributes[ $kk ] ) ) {
                    $attributes[ $kk ] = Array();
                }

                if ( !in_array( $attr, $attributes[ $kk ] ) ) {
                    $attributes[ $kk ][] = $attr;
                }

            }
        } 

        get_popup_variable_product( $product_id, $attributes, $variations_array );
        
    }

    exit;
}

add_action( 'wp_ajax_remove_product_mini', 'remove_product_mini' );
add_action( 'wp_ajax_nopriv_remove_product_mini', 'remove_product_mini' ); 
function remove_product_mini() {

    global $woocommerce;

    $result = Array(
        "result" => false,
        "title" => "Произошла ошибка",
    );

    if ( empty( $_POST['form'] ) ) {
        die( json_encode( $result ) );
    }

    $form_data = $_POST['form'];

    if ( !is_array( $form_data ) ) {
        die( json_encode( $result ) );
    }

    $product_item = sanitize_text_field( $form_data['product_item'] );
   
    if ( empty( trim( $product_item ) ) ) {
        die( json_encode( $result ) );
    }

    if ( $woocommerce->cart->remove_cart_item( $product_item ) == false ) {
        die( json_encode( $result ) );
    }

    echo $woocommerce->cart->get_total();

    exit;
}

add_action( 'wp_ajax_clear_cart_mini', 'clear_cart_mini' );
add_action( 'wp_ajax_nopriv_clear_cart_mini', 'clear_cart_mini' ); 
function clear_cart_mini() {

    $WC_Cart = new WC_Cart();
    $WC_Cart->empty_cart();

    exit;    
}

add_action( 'wp_ajax_admin_table_clone_feo', 'admin_table_clone_feo' );
add_action( 'wp_ajax_nopriv_admin_table_clone_feo', 'admin_table_clone_feo' ); 
function admin_table_clone_feo() {

    $html = $_POST['form']['html'];
    $key = intval( $_POST['form']['key'] );
   
    echo '<br><div class="b-table-item-feodoraxis" style="overflow-x: scroll;">' . stripslashes(str_replace('b-table-item-feodoraxis[' . $key . ']', 'b-table-item-feodoraxis[' . ($key+1) . ']', $html)) . '</div>';

    exit;
}