<?php

// Include site URL hash in HTML settings to update when site URL changes
$sitehash = base64_encode( admin_url() );

$admin_menu = array(
    'title' => 'Easy Image Collage ' . __('Settings', 'easy-image-collage'),
    'logo'  => EasyImageCollage::get()->coreUrl . '/img/logo.png',
    'menus' => array(
//=-=-=-=-=-=-= GENERAL =-=-=-=-=-=-=
        array(
            'title' => __('General', 'easy-image-collage'),
            'name' => 'general',
            'icon' => 'font-awesome:fa-cogs',
            'controls' => array(
                array(
                    'type' => 'toggle',
                    'name' => 'clickable_images',
                    'label' => __('Clickable Images', 'easy-image-collage'),
                    'description' => __( 'Best used in combination with a lightbox plugin.', 'easy-image-collage' ),
                    'default' => '0',
                ),
            ),
        ),
//=-=-=-=-=-=-= DEFAULT STYLE =-=-=-=-=-=-=
        array(
            'title' => __('Default Style', 'easy-image-collage'),
            'name' => 'default_style',
            'icon' => 'font-awesome:fa-picture-o',
            'controls' => array(
                array(
                    'type' => 'slider',
                    'name' => 'default_style_border_width',
                    'label' => __('Border Width', 'easy-image-collage'),
                    'min' => '1',
                    'max' => '20',
                    'step' => '1',
                    'default' => '4',
                ),
                array(
                    'type' => 'color',
                    'name' => 'default_style_border_color',
                    'label' => __('Border Color', 'easy-image-collage'),
                    'default' => '#444444',
                    'format' => 'hex',
                ),
            ),
        ),
//=-=-=-=-=-=-= CUSTOM CODE =-=-=-=-=-=-=
        array(
            'title' => __('Custom Code', 'easy-image-collage'),
            'name' => 'custom_code',
            'icon' => 'font-awesome:fa-code',
            'controls' => array(
                array(
                    'type' => 'codeeditor',
                    'name' => 'custom_code_public_css',
                    'label' => __('Public CSS', 'easy-image-collage'),
                    'theme' => 'github',
                    'mode' => 'css',
                ),
            ),
        ),
    ),
);