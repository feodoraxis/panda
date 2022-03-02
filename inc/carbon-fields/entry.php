<?php
if ( !defined('ABSPATH') ) die();

add_action( 'carbon_fields_register_fields', 'crb_attach_theme_options_fields' );
function crb_attach_theme_options_fields() {

    include __DIR__ . "/theme_options.php";
    include __DIR__ . "/fields/entry.php";
    include __DIR__ . "/terms/entry.php";
    include __DIR__ . "/blocks/entry.php";
    
}

add_action( 'after_setup_theme', 'crb_load' );
function crb_load() {
    require_once( __DIR__ . '/../../vendor/autoload.php' );
    \Carbon_Fields\Carbon_Fields::boot();
}
