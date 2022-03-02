<?php
if ( !defined('ABSPATH') ) die();

function d($arr) {
    echo '<pre>'; print_r($arr); echo"</pre>";
}
  
function debug($arr) {
    $f = fopen($_SERVER["DOCUMENT_ROOT"]."/debug.txt", "a+");
    fwrite($f, print_r(array($arr),true));
    fclose($f);
}
  
function plural_format_word( $number, $after ) {
    $cases = [2, 0, 1, 1, 1, 2];
    return $number . ' ' . $after[ ( $number % 100 > 4 && $number % 100 < 20 ) ? 2 : $cases[ min( $number % 10, 5 ) ] ];
}
  
function change_date_format( $date, $date_format ) {
    /** 
     * $date - use international format
     * $date_format - use needle format. You can use it like in function date()
    */
  
    if ( empty($date) || empty($date_format) ) {
        return false;
    }

    return date( $date_format, strtotime( $date ) );
}

function create_message($title, $data) {
    $time = date('d.m.Y в H:i');

    $message = "
            <!doctype html>
                <html>
                    <head>
                        <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
                        <title>$title</title>
                        <style>
                            div, p, span, strong, b, em, i, a, li, td {
                                -webkit-text-size-adjust: none;
                            }
                            td{vertical-align:middle}
                        </style>
                    </head>
                    
                    <body>
                        
                        <table width='500' cellspacing='0' cellpadding='5' border='1' bordercolor='1' style='border:solid 1px #000;border-collapse:collapse;'>
                            <caption align='center' bgcolor='#dededd' border='1' bordercolor='1' style='border:solid 1px #000;border-collapse:collapse;background:#dededd;padding:10px 0'><b>$title</b></caption>";

    foreach ($data as $key => $val) {
        if ($val != '')
            $message .= '<tr><td bgcolor="#efeeee" style="background:#efeeee">' . $key . ':</td><td>' . $val . '</td>';
    }

    $message .= "<tr><td bgcolor='#efeeee' style='background:#efeeee'>Дата:</td><td>$time</td></tr><tr><td bgcolor='#efeeee' style='background:#efeeee'>IP:</td><td>$_SERVER[REMOTE_ADDR]</td></tr>";

    $message .= "</table></body></html>";
    return $message;
}

function paginate_links_feodoraxis( $args = '' ) {
    global $wp_query, $wp_rewrite;

    // Setting up default values based on the current URL.
    $pagenum_link = html_entity_decode( get_pagenum_link() );
    $url_parts    = explode( '?', $pagenum_link );

    // Get max pages and current page out of the current query, if available.
    $total   = isset( $wp_query->max_num_pages ) ? $wp_query->max_num_pages : 1;
    $current = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;

    // Append the format placeholder to the base URL.
    $pagenum_link = trailingslashit( $url_parts[0] ) . '%_%';

    // URL base depends on permalink settings.
    $format  = $wp_rewrite->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
    $format .= $wp_rewrite->using_permalinks() ? user_trailingslashit( $wp_rewrite->pagination_base . '/%#%', 'paged' ) : '?paged=%#%';

    $defaults = array(
        'base'               => $pagenum_link, // http://example.com/all_posts.php%_% : %_% is replaced by format (below).
        'format'             => $format, // ?page=%#% : %#% is replaced by the page number.
        'total'              => $total,
        'current'            => $current,
        'aria_current'       => 'page',
        'show_all'           => false,
        'prev_next'          => true,
        'prev_text'          => __( '&laquo; Previous' ),
        'next_text'          => __( 'Next &raquo;' ),
        'end_size'           => 1,
        'mid_size'           => 2,
        'type'               => 'plain',
        'add_args'           => array(), // Array of query args to add.
        'add_fragment'       => '',
        'before_page_number' => '',
        'after_page_number'  => '',
    );

    $args = wp_parse_args( $args, $defaults );

    if ( ! is_array( $args['add_args'] ) ) {
        $args['add_args'] = array();
    }

    // Merge additional query vars found in the original URL into 'add_args' array.
    if ( isset( $url_parts[1] ) ) {
        // Find the format argument.
        $format       = explode( '?', str_replace( '%_%', $args['format'], $args['base'] ) );
        $format_query = isset( $format[1] ) ? $format[1] : '';
        wp_parse_str( $format_query, $format_args );

        // Find the query args of the requested URL.
        wp_parse_str( $url_parts[1], $url_query_args );

        // Remove the format argument from the array of query arguments, to avoid overwriting custom format.
        foreach ( $format_args as $format_arg => $format_arg_value ) {
            unset( $url_query_args[ $format_arg ] );
        }

        $args['add_args'] = array_merge( $args['add_args'], urlencode_deep( $url_query_args ) );
    }

    // Who knows what else people pass in $args.
    $total = (int) $args['total'];
    if ( $total < 2 ) {
        return;
    }
    $current  = (int) $args['current'];
    $end_size = (int) $args['end_size']; // Out of bounds? Make it the default.
    if ( $end_size < 1 ) {
        $end_size = 1;
    }
    $mid_size = (int) $args['mid_size'];
    if ( $mid_size < 0 ) {
        $mid_size = 2;
    }

    $add_args   = $args['add_args'];
    $r          = '';
    $page_links = array();
    $dots       = false;

    if ( $args['prev_next'] && $current && 1 < $current ) :
        $link = str_replace( '%_%', 2 == $current ? '' : $args['format'], $args['base'] );
        $link = str_replace( '%#%', $current - 1, $link );
        if ( $add_args ) {
            $link = add_query_arg( $add_args, $link );
        }
        $link .= $args['add_fragment'];

        $page_links[] = sprintf(
            '<li class="b-pagination-back"><a href="%s"><span>%s</span></a></li>',
            /**
             * Filters the paginated links for the given archive pages.
             *
             * @since 3.0.0
             *
             * @param string $link The paginated link URL.
             */
            esc_url( apply_filters( 'paginate_links', $link ) ),
            '<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"viewBox="0 0 185.3 185.3" style="enable-background:new 0 0 185.3 185.3;" xml:space="preserve"><style type="text/css">.st0{fill:#FFFFFF;}</style><g><g><path class="st0" d="M51.7,185.3c-2.7,0-5.5-1-7.6-3.1c-4.2-4.2-4.2-11,0-15.2l74.4-74.3L44.1,18.3c-4.2-4.2-4.2-11,0-15.2c4.2-4.2,11-4.2,15.2,0l81.9,81.9c4.2,4.2,4.2,11,0,15.2l-81.9,81.9C57.2,184.3,54.5,185.3,51.7,185.3z"/></g></g></svg>'
        );
    endif;

    for ( $n = 1; $n <= $total; $n++ ) :
        if ( $n == $current ) :
            $page_links[] = sprintf(
                '<li><span aria-current="%s" class="page-numbers current">%s</span></li>',
                esc_attr( $args['aria_current'] ),
                $args['before_page_number'] . number_format_i18n( $n ) . $args['after_page_number']
            );

            $dots = true;
        else :
            if ( $args['show_all'] || ( $n <= $end_size || ( $current && $n >= $current - $mid_size && $n <= $current + $mid_size ) || $n > $total - $end_size ) ) :
                $link = str_replace( '%_%', 1 == $n ? '' : $args['format'], $args['base'] );
                $link = str_replace( '%#%', $n, $link );
                if ( $add_args ) {
                    $link = add_query_arg( $add_args, $link );
                }
                $link .= $args['add_fragment'];

                $page_links[] = sprintf(
                    '<li><a class="page-numbers" href="%s"><span>%s</span></a></li>',
                    /** This filter is documented in wp-includes/general-template.php */
                    esc_url( apply_filters( 'paginate_links', $link ) ),
                    $args['before_page_number'] . number_format_i18n( $n ) . $args['after_page_number']
                );

                $dots = true;
            elseif ( $dots && ! $args['show_all'] ) :
                $page_links[] = '<li><span class="page-numbers dots">' . __( '&hellip;' ) . '</span></li>';

                $dots = false;
            endif;
        endif;
    endfor;

    if ( $args['prev_next'] && $current && $current < $total ) :
        $link = str_replace( '%_%', $args['format'], $args['base'] );
        $link = str_replace( '%#%', $current + 1, $link );
        if ( $add_args ) {
            $link = add_query_arg( $add_args, $link );
        }
        $link .= $args['add_fragment'];

        $page_links[] = sprintf(
            '<li class="b-pagination-next"><a href="%s"><span>%s</span></a></li>',
            /** This filter is documented in wp-includes/general-template.php */
            esc_url( apply_filters( 'paginate_links', $link ) ),
            '<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"viewBox="0 0 185.3 185.3" style="enable-background:new 0 0 185.3 185.3;" xml:space="preserve"><style type="text/css">.st0{fill:#FFFFFF;}</style><g><g><path class="st0" d="M51.7,185.3c-2.7,0-5.5-1-7.6-3.1c-4.2-4.2-4.2-11,0-15.2l74.4-74.3L44.1,18.3c-4.2-4.2-4.2-11,0-15.2c4.2-4.2,11-4.2,15.2,0l81.9,81.9c4.2,4.2,4.2,11,0,15.2l-81.9,81.9C57.2,184.3,54.5,185.3,51.7,185.3z"/></g></g></svg>'
        );
    endif;

    switch ( $args['type'] ) {
        case 'array':
            return $page_links;

        case 'list':
            $r .= "<ul class='page-numbers'>\n\t<li>";
            $r .= join( "</li>\n\t<li>", $page_links );
            $r .= "</li>\n</ul>\n";
            break;

        default:
            $r = join( "\n", $page_links );
            break;
    }

    return $r;
}

function wp_corenavi( $container ) {
    
    global $wp_query;
    
    $pages = '';
    $max = $wp_query->max_num_pages;
    
    if ( !$current = get_query_var('paged') ) {
        $current = 1;
    }
    
    $a['base'] = str_replace(999999999, '%#%', get_pagenum_link(999999999));
    $a['total'] = $max;
    $a['current'] = $current;
    $a['show_all'] = true;

    $total = 0;
    $a['mid_size'] = 2;
    $a['end_size'] = 2;
    $a['prev_text'] = false; 
    $a['next_text'] = false;

    if ( $max > 1 ) {
        echo '<ul>';
    }

    echo $pages . paginate_links_feodoraxis( $a );

    if ( $max > 1 ) {
        echo '</ul>';
    }

    if ( $wp_query->max_num_pages > 1 && get_query_var('paged') < $wp_query->max_num_pages ) : ?>
        
        <button class="btn btn-light btn-big btn-icon" 
            id="upload-more" 
            container='<?php 
            
                echo $container; 
                
            ?>' posts='<?php 
            
                echo serialize($wp_query->query_vars); 
                
            ?>' current-page='<?php 
            
                echo get_query_var('paged') ? get_query_var('paged') : 1; 
                
            ?>' max-pages='<?php 
            
                echo $wp_query->max_num_pages; 
                
            ?>'>

            <span class="icon">
                <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 302 273" style="enable-background:new 0 0 302 273;" xml:space="preserve"><style type="text/css">.st0{fill:#F50051;}</style><rect x="24" y="43" class="st0" width="251" height="26"/><rect x="24" y="125" class="st0" width="251" height="26"/><rect x="24" y="207" class="st0" width="197.9" height="26"/></svg>
            </span>
            <span class='text'>Посмотреть ещё</span>
        </button>

    <?php
    endif; 
}

function woocommerce_breadcrumb( $args = array() ) {
    $args = wp_parse_args(
        $args,
        apply_filters(
            'woocommerce_breadcrumb_defaults',
            array(
                'delimiter'   => '',
                'wrap_before' => '<div itemscope="" itemtype="http://schema.org/BreadcrumbList" id="breadcrumbs">',
                'wrap_after'  => '</div>',
                'before'      => '<span itemscope="" itemprop="itemListElement" itemtype="http://schema.org/ListItem">',
                'after'       => '</span>',
                'home'        => _x( 'Home', 'breadcrumb', 'woocommerce' ),
            )
        )
    );

    $breadcrumbs = new WC_Breadcrumb();

    if ( ! empty( $args['home'] ) ) {
        $breadcrumbs->add_crumb( $args['home'], apply_filters( 'woocommerce_breadcrumb_home_url', home_url() ) );
    }

    $args['breadcrumb'] = $breadcrumbs->generate();

    /**
     * WooCommerce Breadcrumb hook
     *
     * @hooked WC_Structured_Data::generate_breadcrumblist_data() - 10
     */
    do_action( 'woocommerce_breadcrumb', $breadcrumbs, $args );

    wc_get_template( 'global/breadcrumb.php', $args );
}

function get_attribure_type( $attr_slug ) {

    global $wpdb;

    if ( empty( $attr_slug ) && !strripos( 'a_', $attr_slug ) ) {
        return false;
    }

    $slug = substr( $attr_slug, 3 );

    $attribute_to_edit = $wpdb->get_row(
        $wpdb->prepare(
            "
            SELECT attribute_type, attribute_label, attribute_name, attribute_orderby, attribute_public
            FROM {$wpdb->prefix}woocommerce_attribute_taxonomies WHERE attribute_name = %s
            ",
            $slug
        )
    );

    return $attribute_to_edit->attribute_type;

}