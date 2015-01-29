<?php

class EIC_Shortcode_Button {

    private $buttons_added = false;

    public function __construct()
    {
        add_action( 'media_buttons_context',  array( $this, 'add_shortcode_button' ) );
        add_action( 'admin_footer',  array( $this, 'add_modal_content' ) );
    }

    public function add_shortcode_button( $context )
    {
        $screen = get_current_screen();

        if( $screen->base == 'post' && !$this->buttons_added ) {
            $title = __( 'Add Image Collage', 'easy-image-collage' );

            $context .= '<a href="#" id="eic-button" class="button" data-editor="content" title="' . $title . '">' . $title . '</a>';

            // Prevent adding buttons to other TinyMCE instances on the same page
            $this->buttons_added = true;
        }

        return $context;
    }

    public function add_modal_content()
    {
        $screen = get_current_screen();

        if( $screen->base == 'post' ) {
            include( EasyImageCollage::get()->coreDir . '/helpers/modal.php' );

            $post = get_post();
            $grid_ids = $this->get_grids_in_content( $post->post_content );

            $grids = array();

            foreach( $grid_ids as $grid_id ) {
                $grid = get_post_meta( $grid_id, 'eic_grid_data', true );

                if( !isset( $grid['images'] ) ) {
                    $grid['images'] = array();
                }

                $grids[$grid_id] = $grid;
            }

            wp_localize_script( 'eic_admin', 'eic_admin_grids', $grids );
            wp_localize_script( 'eic_admin', 'eic_default_grid', array(
                'id' => 0,
                'layout' => 'square',
                'images' => array(),
                'properties' => array(
                    'width' => 500,
                    'ratio' => 1,
                    'borderWidth' => intval( EasyImageCollage::option( 'default_style_border_width', 4 ) ),
                    'borderColor' => EasyImageCollage::option( 'default_style_border_color', '#444444' ),
                ),
            ) );
        }
    }

    public function get_grids_in_content( $content )
    {
        preg_match_all("/\[easy-image-collage([^\]]*)/i", $content, $shortcodes);

        $grid_ids = array();
        foreach( $shortcodes[1] as $shortcode_options )
        {
            preg_match("/id=\"?'?(\d+)/i", $shortcode_options, $id);

            $grid_ids[] = intval( $id[1] );
        }

        return $grid_ids;
    }
}