<?php

class EIC_Shortcode {

    public function __construct()
    {
        add_shortcode( 'easy-image-collage', array( $this, 'eic_shortcode' ) );
    }

    function eic_shortcode( $options )
    {
        $options = shortcode_atts( array(
            'id' => '0', // If no ID given, show a random recipe
        ), $options );

        $post = get_post( intval( $options['id'] ) );

        $output = '';

        if( !is_null( $post ) && $post->post_type == 'eic_grid' ) {
            $grid_data = get_post_meta( $post->ID, 'eic_grid_data', true );

            // Borders
            $border_color = $grid_data['properties']['borderColor'];
            $border_width = intval( $grid_data['properties']['borderWidth'] );

            // Frame size
            $width = intval( $grid_data['properties']['width'] );
            $ratio = intval( $grid_data['properties']['ratio'] );
            $ratio = $ratio == 0 ? 1 : $ratio;
            $height = $width / $ratio;

            // Styling
            $output .= '<style>';
            $output .= '.eic-frame-' . $grid_data['id'] . ' { width: ' . $width . 'px; height:' . $height . 'px; border: ' . $border_width . 'px solid ' . $border_color . '; }';
            $output .= '.eic-frame-' . $grid_data['id'] . ' .eic-image { border: ' . $border_width . 'px solid ' . $border_color . '; }';

            if( is_array( $grid_data['images'] ) ) {
                foreach( $grid_data['images'] as $id => $image ) {
                    if( $image ) {
                        $url = $image['attachment_url'];

                        $width = intval( $image['size_x'] );
                        $height = intval( $image['size_y'] );
                        $ratio = $width / $height;

                        $thumb = wp_get_attachment_image_src( $image['attachment_id'], array( $width, $height ) );

                        if( $thumb ) {
                            $thumb_url = $thumb[0];
                            $size = getimagesize( $thumb_url );

                            $thumb_width = $size[0];
                            $thumb_height = $size[1];
                            $thumb_ratio = $thumb_width / $thumb_height;

                            if( $thumb_ratio == $ratio ) {
                                $url = $thumb_url; // Only use the thumbnail if the ratios match
                            }
                        }

                        $output .= '.eic-frame-' . $grid_data['id'] . ' .eic-image-' . $id . ' {';
                        $output .= 'background-image: url("' . $url . '");';
                        $output .= 'background-size: ' . $width . 'px ' . $height . 'px;';
                        $output .= 'background-position-x: ' . $image['pos_x'] . 'px;';
                        $output .= 'background-position-y: ' . $image['pos_y'] . 'px;';
                        $output .= '}';
                    }
                }
            }

            $output .= '</style>';

            // Draw frame
            $output .= '<div class="eic-container">';
            $output .= EasyImageCollage::get()->helper( 'layouts' )->draw_layout( $grid_data );
            $output .= '</div>';
        }

        return $output;
    }
}