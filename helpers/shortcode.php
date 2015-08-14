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

        if( !is_null( $post ) && $post->post_type == EIC_POST_TYPE ) {
	        $grid = new EIC_Grid( $post );

            // Styling
            $output .= '<style>';
            $output .= '.eic-frame-' . $grid->ID() . ' { width: ' . $grid->width() . 'px; height:' . $grid->height() . 'px; background-color: ' . $grid->border_color() . '; border: ' . $grid->border_width() . 'px solid ' . $grid->border_color() . '; }';
            $output .= '.eic-frame-' . $grid->ID() . ' .eic-image { border: ' . $grid->border_width() . 'px solid ' . $grid->border_color() . '; }';

            foreach( $grid->images() as $id => $image ) {
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

                        if( abs( $thumb_ratio - $ratio ) < 0.05 ) {
                            $url = $thumb_url; // Only use the thumbnail if the ratios match
                        }
                    }

                    $output .= '.eic-frame-' . $grid->ID() . ' .eic-image-' . $id . ' {';
                    $output .= 'background-image: url("' . $url . '");';
                    $output .= 'background-size: ' . $width . 'px ' . $height . 'px;';
                    $output .= 'background-position: ' . $image['pos_x'] . 'px ' . $image['pos_y'] . 'px;';
                    $output .= '}';
                }
            }


            $output .= '</style>';

	        switch( $grid->align() ) {
		        case 'left':
			        $container_style = ' style="text-align: left;"';
			        break;
		        case 'right':
			        $container_style = ' style="text-align: right;"';
			        break;
		        default:
			        $container_style = '';
	        }

            // Draw frame
            $output .= '<div class="eic-container"' . $container_style . '>';
            $output .= $grid->draw();
            $output .= '</div>';
        }

        return $output;
    }
}