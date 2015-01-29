<?php

// Include site URL hash in HTML settings to update when site URL changes
$sitehash = base64_encode( admin_url() );

$admin_menu = array(
    'title' => 'Easy Image Collage ' . __('Settings', 'easy-image-collage'),
    'logo'  => EasyImageCollage::get()->coreUrl . '/img/logo.png',
    'menus' => array(
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
//=-=-=-=-=-=-= LIGHTBOX =-=-=-=-=-=-=
        array(
            'title' => __('Lightbox', 'easy-image-collage'),
            'name' => 'lightbox',
            'icon' => 'font-awesome:fa-camera',
            'controls' => array(
                array(
                    'type' => 'section',
                    'title' => __('General', 'easy-image-collage'),
                    'name' => 'lightbox_general',
                    'fields' => array(
                        array(
                            'type' => 'toggle',
                            'name' => 'clickable_images',
                            'label' => __('Clickable Images', 'easy-image-collage'),
                            'description' => __( 'Best used in combination with a lightbox plugin.', 'easy-image-collage' ),
                            'default' => '0',
                        ),
                    ),
                ),
                array(
                    'type' => 'section',
                    'title' => __('Advanced', 'easy-image-collage'),
                    'name' => 'lightbox_advanced',
                    'fields' => array(
                        array(
                            'type' => 'textbox',
                            'name' => 'lightbox_class',
                            'label' => __('Link class', 'easy-image-collage'),
                            'description' => __('Class to be added to the lightbox link.', 'easy-image-collage'),
                            'default' => '',
                        ),
                        array(
                            'type' => 'textbox',
                            'name' => 'lightbox_rel',
                            'label' => __('Link rel', 'easy-image-collage'),
                            'description' => __('Rel value of the lightbox link.', 'easy-image-collage'),
                            'default' => 'lightbox',
                        ),
                    ),
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