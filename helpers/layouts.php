<?php

class EIC_Layouts {

    private $layouts;

    public function __construct()
    {
        $this->layouts();
    }

    public function draw_layouts( $controls = false )
    {
        $output = '';
        foreach( $this->layouts as $name => $layout ) {
            $output .= $this->draw_layout( $name, $controls );
        }

        return $output;
    }

    public function draw_layout( $name_or_grid, $controls = false )
    {
        if( is_array( $name_or_grid ) ) {
            $grid = $name_or_grid;
            $name = $grid['layout'];
        } else {
            $name = $name_or_grid;
            $grid = false;
        }

        if( isset( $this->layouts[ $name ] ) ) {
            $layout = $this->layouts[ $name ];
            $grid_id = $grid ? $grid['id'] : 0;

            $output = '<div class="eic-frame eic-frame-' . $grid_id . ' eic-frame-' . $name . '" data-layout-name="' . $name . '"';
            if( $grid ) {
                $output .= ' data-orig-width="' . $grid['properties']['width'] . '"';
                $output .= ' data-orig-border="' . $grid['properties']['borderWidth'] . '"';
                $output .= ' data-ratio="' . $grid['properties']['ratio'] . '"';
            }
            $output .= '>';
            $output .= $this->draw_block( $layout, $grid, $controls );
            $output .= '</div>';
            return $output;
        } else {
            return '';
        }
    }

    private function draw_block( $block, $grid = false, $controls = false )
    {
        if( $block['type'] == 'img' ) {
            $output = '<div class="eic-image eic-image-' . $block['id'] . '"';
            if( $grid ) {
                $image = $grid['images'][$block['id']];

                if( $image ) {
                    $output .= ' data-size-x="' . $image['size_x'] . '"';
                    $output .= ' data-size-y="' . $image['size_y'] . '"';
                    $output .= ' data-pos-x="' . $image['pos_x'] . '"';
                    $output .= ' data-pos-y="' . $image['pos_y'] . '"';
                    $output .= '>';

                    if( EasyImageCollage::option( 'clickable_images', '0' ) == '1' ) {
                        $image_post = get_post($image['attachment_id']);
                        $class = EasyImageCollage::option( 'lightbox_class', '' );
                        $rel = EasyImageCollage::option( 'lightbox_rel', 'lightbox' );

                        $output .= '<a href="' . $image['attachment_url'] . '" rel="' . esc_attr( $rel ) . '" title="' . esc_attr( $image_post->post_title ) . '" class="eic-image-link ' . esc_attr( $class ) . '"></a>';
                    }

                } else {
                    $output .= '>';
                }
            } else {
                $output .= '>';
            }

            if( $controls ) {
                $output .= '<div class="eic-image-controls">';
                $output .= '<div class="eic-image-control eic-image-control-image" onclick="EasyImageCollage.btnImage(' . $block['id'] . ')"><i class="fa fa-picture-o"></i></div>';
                $output .= '</div>';
            }

            $output .= '</div>';

            return $output;
        } else {
            $percentage1 = str_replace( ',', '.', $block['pos'] * 100 );
            $percentage2 = str_replace( ',', '.', 100 - $percentage1 );

            if( $block['type'] == 'row' ) {
                $style1 = 'top: 0; left: 0; right: 0; bottom: ' . $percentage1 . '%; height: ' . $percentage1 . '%;';
                $style2 = 'bottom: 0; left: 0; right: 0; top: ' . $percentage1 . '%; height: ' . $percentage2 . '%;';
            } else {
                $style1 = 'top: 0; bottom: 0; left: 0; right: ' . $percentage1 . '%; width: ' . $percentage1 . '%;';
                $style2 = 'top: 0; bottom: 0; right: 0; left: ' . $percentage1 . '%; width: ' . $percentage2 . '%;';
            }

            $output = '<div class="eic-' . $block['type'] . 's">';
            $output .= '<div class="eic-' . $block['type'] . ' eic-child-1" style="' . $style1 . '">';
            $output .= $this->draw_block( $block['children'][0], $grid, $controls );
            $output .= '</div>';
            $output .= '<div class="eic-' . $block['type'] . ' eic-child-2" style="' . $style2 . '">';
            $output .= $this->draw_block( $block['children'][1], $grid, $controls );
            $output .= '</div>';
            $output .= '</div>';

            return $output;
        }
    }


    private function layouts()
    {
        $this->layouts = array(
            'square' => array(
                'type' => 'img',
                'id' => 0
            ),
            '4-squares' => array(
                'type' => 'col',
                'pos' => 0.5,
                'children' => array(
                    array(
                        'type' => 'row',
                        'pos' => 0.5,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 0
                            ),
                            array(
                                'type' => 'img',
                                'id' => 2
                            ),
                        )
                    ),
                    array(
                        'type' => 'row',
                        'pos' => 0.5,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 1
                            ),
                            array(
                                'type' => 'img',
                                'id' => 4
                            ),
                        )
                    ),
                ),
            ),
            '2-col' => array(
                'type' => 'col',
                'pos' => 0.5,
                'children' => array(
                    array(
                        'type' => 'img',
                        'id' => 0
                    ),
                    array(
                        'type' => 'img',
                        'id' => 1
                    ),
                ),
            ),
            '2-row' => array(
                'type' => 'row',
                'pos' => 0.5,
                'children' => array(
                    array(
                        'type' => 'img',
                        'id' => 0
                    ),
                    array(
                        'type' => 'img',
                        'id' => 1
                    ),
                ),
            ),
            '2-row-bottom-2-col' => array(
                'type' => 'row',
                'pos' => 0.5,
                'children' => array(
                    array(
                        'type' => 'img',
                        'id' => 0
                    ),
                    array(
                        'type' => 'col',
                        'pos' => 0.5,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 1
                            ),
                            array(
                                'type' => 'img',
                                'id' => 2
                            ),
                        ),
                    ),
                ),
            ),
            '2-row-top-2-col' => array(
                'type' => 'row',
                'pos' => 0.5,
                'children' => array(
                    array(
                        'type' => 'col',
                        'pos' => 0.5,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 0
                            ),
                            array(
                                'type' => 'img',
                                'id' => 1
                            ),
                        ),
                    ),
                    array(
                        'type' => 'img',
                        'id' => 2
                    ),
                ),
            ),
            '2-col-right-2-row' => array(
                'type' => 'col',
                'pos' => 0.5,
                'children' => array(
                    array(
                        'type' => 'img',
                        'id' => 0
                    ),
                    array(
                        'type' => 'row',
                        'pos' => 0.5,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 1
                            ),
                            array(
                                'type' => 'img',
                                'id' => 2
                            ),
                        ),
                    ),
                ),
            ),
            '2-col-left-2-row' => array(
                'type' => 'col',
                'pos' => 0.5,
                'children' => array(
                    array(
                        'type' => 'row',
                        'pos' => 0.5,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 0
                            ),
                            array(
                                'type' => 'img',
                                'id' => 1
                            ),
                        ),
                    ),
                    array(
                        'type' => 'img',
                        'id' => 2
                    ),
                ),
            ),
            '3-row' => array(
                'type' => 'row',
                'pos' => 0.33333,
                'children' => array(
                    array(
                        'type' => 'img',
                        'id' => 0
                    ),
                    array(
                        'type' => 'row',
                        'pos' => 0.5,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 1
                            ),
                            array(
                                'type' => 'img',
                                'id' => 2
                            ),
                        ),
                    ),
                ),
            ),
            '3-col' => array(
                'type' => 'col',
                'pos' => 0.33333,
                'children' => array(
                    array(
                        'type' => 'img',
                        'id' => 0
                    ),
                    array(
                        'type' => 'col',
                        'pos' => 0.5,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 1
                            ),
                            array(
                                'type' => 'img',
                                'id' => 2
                            ),
                        ),
                    ),
                ),
            ),
            '4-row' => array(
                'type' => 'row',
                'pos' => 0.25,
                'children' => array(
                    array(
                        'type' => 'img',
                        'id' => 0
                    ),
                    array(
                        'type' => 'row',
                        'pos' => 0.33333,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 1
                            ),
                            array(
                                'type' => 'row',
                                'pos' => 0.5,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 2
                                    ),
                                    array(
                                        'type' => 'img',
                                        'id' => 3
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            '4-col' => array(
                'type' => 'col',
                'pos' => 0.25,
                'children' => array(
                    array(
                        'type' => 'img',
                        'id' => 0
                    ),
                    array(
                        'type' => 'col',
                        'pos' => 0.33333,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 1
                            ),
                            array(
                                'type' => 'col',
                                'pos' => 0.5,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 2
                                    ),
                                    array(
                                        'type' => 'img',
                                        'id' => 3
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            '2-row-bottom-3-col' => array(
                'type' => 'row',
                'pos' => 0.5,
                'children' => array(
                    array(
                        'type' => 'img',
                        'id' => 0
                    ),
                    array(
                        'type' => 'col',
                        'pos' => 0.33333,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 1
                            ),
                            array(
                                'type' => 'col',
                                'pos' => 0.5,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 2
                                    ),
                                    array(
                                        'type' => 'img',
                                        'id' => 3
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            '2-row-top-3-col' => array(
                'type' => 'row',
                'pos' => 0.5,
                'children' => array(
                    array(
                        'type' => 'col',
                        'pos' => 0.33333,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 0
                            ),
                            array(
                                'type' => 'col',
                                'pos' => 0.5,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 1
                                    ),
                                    array(
                                        'type' => 'img',
                                        'id' => 2
                                    ),
                                ),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'img',
                        'id' => 3
                    ),
                ),
            ),
            '2-col-right-3-row' => array(
                'type' => 'col',
                'pos' => 0.5,
                'children' => array(
                    array(
                        'type' => 'img',
                        'id' => 0
                    ),
                    array(
                        'type' => 'row',
                        'pos' => 0.33333,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 1
                            ),
                            array(
                                'type' => 'row',
                                'pos' => 0.5,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 2
                                    ),
                                    array(
                                        'type' => 'img',
                                        'id' => 3
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            '2-col-left-3-row' => array(
                'type' => 'col',
                'pos' => 0.5,
                'children' => array(
                    array(
                        'type' => 'row',
                        'pos' => 0.33333,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 0
                            ),
                            array(
                                'type' => 'row',
                                'pos' => 0.5,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 1
                                    ),
                                    array(
                                        'type' => 'img',
                                        'id' => 2
                                    ),
                                ),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'img',
                        'id' => 3
                    ),
                ),
            ),
            '4-squares-odd-left' => array(
                'type' => 'row',
                'pos' => 0.5,
                'children' => array(
                    array(
                        'type' => 'col',
                        'pos' => 0.66666,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 0
                            ),
                            array(
                                'type' => 'img',
                                'id' => 2
                            ),
                        )
                    ),
                    array(
                        'type' => 'col',
                        'pos' => 0.33333,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 1
                            ),
                            array(
                                'type' => 'img',
                                'id' => 4
                            ),
                        )
                    ),
                ),
            ),
            '4-squares-odd-right' => array(
                'type' => 'row',
                'pos' => 0.5,
                'children' => array(
                    array(
                        'type' => 'col',
                        'pos' => 0.33333,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 0
                            ),
                            array(
                                'type' => 'img',
                                'id' => 2
                            ),
                        )
                    ),
                    array(
                        'type' => 'col',
                        'pos' => 0.66666,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 1
                            ),
                            array(
                                'type' => 'img',
                                'id' => 4
                            ),
                        )
                    ),
                ),
            ),
            '4-squares-odd-bottom' => array(
                'type' => 'col',
                'pos' => 0.5,
                'children' => array(
                    array(
                        'type' => 'row',
                        'pos' => 0.66666,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 0
                            ),
                            array(
                                'type' => 'img',
                                'id' => 2
                            ),
                        )
                    ),
                    array(
                        'type' => 'row',
                        'pos' => 0.33333,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 1
                            ),
                            array(
                                'type' => 'img',
                                'id' => 4
                            ),
                        )
                    ),
                ),
            ),
            '4-squares-odd-top' => array(
                'type' => 'col',
                'pos' => 0.5,
                'children' => array(
                    array(
                        'type' => 'row',
                        'pos' => 0.33333,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 0
                            ),
                            array(
                                'type' => 'img',
                                'id' => 2
                            ),
                        )
                    ),
                    array(
                        'type' => 'row',
                        'pos' => 0.66666,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 1
                            ),
                            array(
                                'type' => 'img',
                                'id' => 4
                            ),
                        )
                    ),
                ),
            ),
            '3-row-first-2-col' => array(
                'type' => 'row',
                'pos' => 0.33333,
                'children' => array(
                    array(
                        'type' => 'col',
                        'pos' => 0.5,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 0
                            ),
                            array(
                                'type' => 'img',
                                'id' => 1
                            ),
                        ),
                    ),
                    array(
                        'type' => 'row',
                        'pos' => 0.5,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 2
                            ),
                            array(
                                'type' => 'img',
                                'id' => 3
                            ),
                        ),
                    ),
                ),
            ),
            '3-row-second-2-col' => array(
                'type' => 'row',
                'pos' => 0.33333,
                'children' => array(
                    array(
                        'type' => 'img',
                        'id' => 0
                    ),
                    array(
                        'type' => 'row',
                        'pos' => 0.5,
                        'children' => array(
                            array(
                                'type' => 'col',
                                'pos' => 0.5,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 1
                                    ),
                                    array(
                                        'type' => 'img',
                                        'id' => 2
                                    ),
                                ),
                            ),
                            array(
                                'type' => 'img',
                                'id' => 3
                            ),
                        ),
                    ),
                ),
            ),
            '3-row-third-2-col' => array(
                'type' => 'row',
                'pos' => 0.33333,
                'children' => array(
                    array(
                        'type' => 'img',
                        'id' => 0
                    ),
                    array(
                        'type' => 'row',
                        'pos' => 0.5,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 1
                            ),
                            array(
                                'type' => 'col',
                                'pos' => 0.5,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 2
                                    ),
                                    array(
                                        'type' => 'img',
                                        'id' => 3
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            '3-col-first-2-row' => array(
                'type' => 'col',
                'pos' => 0.33333,
                'children' => array(
                    array(
                        'type' => 'row',
                        'pos' => 0.5,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 0
                            ),
                            array(
                                'type' => 'img',
                                'id' => 1
                            ),
                        ),
                    ),
                    array(
                        'type' => 'col',
                        'pos' => 0.5,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 2
                            ),
                            array(
                                'type' => 'img',
                                'id' => 3
                            ),
                        ),
                    ),
                ),
            ),
            '3-col-second-2-row' => array(
                'type' => 'col',
                'pos' => 0.33333,
                'children' => array(
                    array(
                        'type' => 'img',
                        'id' => 0
                    ),
                    array(
                        'type' => 'col',
                        'pos' => 0.5,
                        'children' => array(
                            array(
                                'type' => 'row',
                                'pos' => 0.5,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 1
                                    ),
                                    array(
                                        'type' => 'img',
                                        'id' => 2
                                    ),
                                ),
                            ),
                            array(
                                'type' => 'img',
                                'id' => 3
                            ),
                        ),
                    ),
                ),
            ),
            '3-col-third-2-row' => array(
                'type' => 'col',
                'pos' => 0.33333,
                'children' => array(
                    array(
                        'type' => 'img',
                        'id' => 0
                    ),
                    array(
                        'type' => 'col',
                        'pos' => 0.5,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 1
                            ),
                            array(
                                'type' => 'row',
                                'pos' => 0.5,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 2
                                    ),
                                    array(
                                        'type' => 'img',
                                        'id' => 3
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            '2-row-bottom-2-col-right-2-row' => array(
                'type' => 'row',
                'pos' => 0.33333,
                'children' => array(
                    array(
                        'type' => 'img',
                        'id' => 0
                    ),
                    array(
                        'type' => 'col',
                        'pos' => 0.5,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 1
                            ),
                            array(
                                'type' => 'row',
                                'pos' => 0.5,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 2
                                    ),
                                    array(
                                        'type' => 'img',
                                        'id' => 3
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            '2-row-top-2-col-right-2-row' => array(
                'type' => 'row',
                'pos' => 0.66666,
                'children' => array(
                    array(
                        'type' => 'col',
                        'pos' => 0.5,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 0
                            ),
                            array(
                                'type' => 'row',
                                'pos' => 0.5,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 1
                                    ),
                                    array(
                                        'type' => 'img',
                                        'id' => 2
                                    ),
                                ),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'img',
                        'id' => 3
                    ),
                ),
            ),
            '2-row-bottom-2-col-left-2-row' => array(
                'type' => 'row',
                'pos' => 0.33333,
                'children' => array(
                    array(
                        'type' => 'img',
                        'id' => 0
                    ),
                    array(
                        'type' => 'col',
                        'pos' => 0.5,
                        'children' => array(
                            array(
                                'type' => 'row',
                                'pos' => 0.5,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 1
                                    ),
                                    array(
                                        'type' => 'img',
                                        'id' => 2
                                    ),
                                ),
                            ),
                            array(
                                'type' => 'img',
                                'id' => 3
                            ),
                        ),
                    ),
                ),
            ),
            '2-row-top-2-col-left-2-row' => array(
                'type' => 'row',
                'pos' => 0.66666,
                'children' => array(
                    array(
                        'type' => 'col',
                        'pos' => 0.5,
                        'children' => array(
                            array(
                                'type' => 'row',
                                'pos' => 0.5,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 0
                                    ),
                                    array(
                                        'type' => 'img',
                                        'id' => 1
                                    ),
                                ),
                            ),
                            array(
                                'type' => 'img',
                                'id' => 2
                            ),
                        ),
                    ),
                    array(
                        'type' => 'img',
                        'id' => 3
                    ),
                ),
            ),
            '2-col-right-2-row-bottom-2-col' => array(
                'type' => 'col',
                'pos' => 0.33333,
                'children' => array(
                    array(
                        'type' => 'img',
                        'id' => 0
                    ),
                    array(
                        'type' => 'row',
                        'pos' => 0.5,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 1
                            ),
                            array(
                                'type' => 'col',
                                'pos' => 0.5,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 2
                                    ),
                                    array(
                                        'type' => 'img',
                                        'id' => 3
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            '2-col-left-2-row-bottom-2-col' => array(
                'type' => 'col',
                'pos' => 0.66666,
                'children' => array(
                    array(
                        'type' => 'row',
                        'pos' => 0.5,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 0
                            ),
                            array(
                                'type' => 'col',
                                'pos' => 0.5,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 1
                                    ),
                                    array(
                                        'type' => 'img',
                                        'id' => 2
                                    ),
                                ),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'img',
                        'id' => 3
                    ),
                ),
            ),
            '2-col-right-2-row-top-2-col' => array(
                'type' => 'col',
                'pos' => 0.33333,
                'children' => array(
                    array(
                        'type' => 'img',
                        'id' => 0
                    ),
                    array(
                        'type' => 'row',
                        'pos' => 0.5,
                        'children' => array(
                            array(
                                'type' => 'col',
                                'pos' => 0.5,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 1
                                    ),
                                    array(
                                        'type' => 'img',
                                        'id' => 2
                                    ),
                                ),
                            ),
                            array(
                                'type' => 'img',
                                'id' => 3
                            ),
                        ),
                    ),
                ),
            ),
            '2-col-left-2-row-top-2-col' => array(
                'type' => 'col',
                'pos' => 0.66666,
                'children' => array(
                    array(
                        'type' => 'row',
                        'pos' => 0.5,
                        'children' => array(
                            array(
                                'type' => 'col',
                                'pos' => 0.5,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 0
                                    ),
                                    array(
                                        'type' => 'img',
                                        'id' => 1
                                    ),
                                ),
                            ),
                            array(
                                'type' => 'img',
                                'id' => 2
                            ),
                        ),
                    ),
                    array(
                        'type' => 'img',
                        'id' => 3
                    ),
                ),
            ),
            '2-row-3-col' => array(
                'type' => 'row',
                'pos' => 0.5,
                'children' => array(
                    array(
                        'type' => 'col',
                        'pos' => 0.33333,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 0
                            ),
                            array(
                                'type' => 'col',
                                'pos' => 0.5,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 1
                                    ),
                                    array(
                                        'type' => 'img',
                                        'id' => 2
                                    ),
                                ),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'col',
                        'pos' => 0.33333,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 3
                            ),
                            array(
                                'type' => 'col',
                                'pos' => 0.5,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 4
                                    ),
                                    array(
                                        'type' => 'img',
                                        'id' => 5
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            '2-col-3-row' => array(
                'type' => 'col',
                'pos' => 0.5,
                'children' => array(
                    array(
                        'type' => 'row',
                        'pos' => 0.33333,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 0
                            ),
                            array(
                                'type' => 'row',
                                'pos' => 0.5,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 1
                                    ),
                                    array(
                                        'type' => 'img',
                                        'id' => 2
                                    ),
                                ),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'row',
                        'pos' => 0.33333,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 3
                            ),
                            array(
                                'type' => 'row',
                                'pos' => 0.5,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 4
                                    ),
                                    array(
                                        'type' => 'img',
                                        'id' => 5
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            '9-squares' => array(
                'type' => 'col',
                'pos' => 0.33333,
                'children' => array(
                    array(
                        'type' => 'row',
                        'pos' => 0.33333,
                        'children' => array(
                            array(
                                'type' => 'img',
                                'id' => 0
                            ),
                            array(
                                'type' => 'row',
                                'pos' => 0.5,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 1
                                    ),
                                    array(
                                        'type' => 'img',
                                        'id' => 2
                                    ),
                                ),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'col',
                        'pos' => 0.5,
                        'children' => array(
                            array(
                                'type' => 'row',
                                'pos' => 0.33333,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 3
                                    ),
                                    array(
                                        'type' => 'row',
                                        'pos' => 0.5,
                                        'children' => array(
                                            array(
                                                'type' => 'img',
                                                'id' => 4
                                            ),
                                            array(
                                                'type' => 'img',
                                                'id' => 5
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                            array(
                                'type' => 'row',
                                'pos' => 0.33333,
                                'children' => array(
                                    array(
                                        'type' => 'img',
                                        'id' => 6
                                    ),
                                    array(
                                        'type' => 'row',
                                        'pos' => 0.5,
                                        'children' => array(
                                            array(
                                                'type' => 'img',
                                                'id' => 7
                                            ),
                                            array(
                                                'type' => 'img',
                                                'id' => 8
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        );
    }
}