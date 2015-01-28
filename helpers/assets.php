<?php

class EIC_Assets {

    private $url;

    public function __construct()
    {
        $this->url = EasyImageCollage::get()->coreUrl;

        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_public' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin' ) );
        add_action( 'wp_head', array( $this, 'custom_css' ), 20 );

        add_filter( 'mce_external_plugins', array( $this, 'tinymce_plugin' ) );
    }

    public function enqueue_public()
    {
        wp_enqueue_style( 'eic_public', $this->url . '/css/public.css', array(), EIC_VERSION, 'screen' );
        wp_enqueue_script( 'eic_public', $this->url . '/js/public.js', array( 'jquery' ), EIC_VERSION, true );
    }

    public function enqueue_admin()
    {
        $screen = get_current_screen();

        if( $screen->base == 'post' ) {
            // Vendor assets
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_style( 'font-awesome', $this->url . '/vendor/font-awesome/css/font-awesome.min.css', array(), EIC_VERSION, 'screen' );
            wp_enqueue_style( 'simple-slider', $this->url . '/vendor/loopj-jquery-simple-slider/css/simple-slider.css', array(), EIC_VERSION, 'screen' );
            wp_enqueue_script( 'simple-slider', $this->url . '/vendor/loopj-jquery-simple-slider/js/simple-slider.min.js', array( 'jquery' ), EIC_VERSION, true );
            wp_enqueue_script( 'featherlight', $this->url . '/vendor/featherlight/featherlight.min.js', array( 'jquery' ), EIC_VERSION, true );

            // Plugin assets
            wp_enqueue_style( 'eic_admin', $this->url . '/css/admin.css', array(), EIC_VERSION, 'screen' );
            wp_enqueue_script( 'eic_admin', $this->url . '/js/admin.js', array( 'jquery', 'simple-slider', 'featherlight', 'wp-color-picker' ), EIC_VERSION, true );

            // Pass on data
            $data = array(
                'ajaxurl' => EasyImageCollage::get()->helper('ajax')->url(),
                'nonce' => wp_create_nonce( 'eic_image_collage' ),
                'shortcode_image' => $this->url . '/img/eic_shortcode.png',
            );
            wp_localize_script( 'eic_admin', 'eic_admin', $data );
        }
    }

    public function custom_css()
    {
        if( EasyImageCollage::option( 'custom_code_public_css', '' ) !== '' ) {
            echo '<style type="text/css">';
            echo EasyImageCollage::option( 'custom_code_public_css', '' );
            echo '</style>';
        }
    }

    public function tinymce_plugin( $plugin_array )
    {
        $plugin_array['easyimagecollage'] = $this->url . '/js/tinymce_shortcode.js';
        return $plugin_array;
    }
}