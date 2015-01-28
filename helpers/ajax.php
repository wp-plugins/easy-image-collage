<?php

class EIC_Ajax {

    public function __construct()
    {
        add_action( 'wp_ajax_image_collage', array( $this, 'ajax_image_collage' ) );
        add_action( 'wp_ajax_nopriv_image_collage', array( $this, 'ajax_image_collage' ) );
    }

    public function ajax_image_collage()
    {
        if( check_ajax_referer( 'eic_image_collage', 'security', false ) )
        {

            $grid =  $_POST['grid'];
            $grid_id = intval( $grid['id'] );

            // Create new or update grid
            if( $grid_id === 0 ) {
                global $user_ID;

                $post = array(
                    'post_status' => 'publish',
                    'post_date' => date('Y-m-d H:i:s'),
                    'post_author' => $user_ID,
                    'post_type' => 'eic_grid',
                    'post_content' => '',
                );

                $grid_id = wp_insert_post( $post );
            } else {
                $post = array(
                    'ID' => $grid_id,
                    'post_content' => ''
                );

                wp_update_post( $post );
            }

            $grid['id'] = $grid_id;
            update_post_meta( $grid_id, 'eic_grid_data', $grid );

            echo json_encode($grid_id);
        }

        die();
    }

    public function url()
    {
        $ajaxurl = admin_url( 'admin-ajax.php' );
        $ajaxurl .= '?eic_ajax=1';

        // WPML AJAX Localization Fix
        global $sitepress;
        if( isset( $sitepress) ) {
            $ajaxurl .= '&lang='.$sitepress->get_current_language();
        }

        return $ajaxurl;
    }
}