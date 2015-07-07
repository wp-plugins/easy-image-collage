<?php

class EIC_Grid {

    private $post;
    private $data;

    public function __construct( $post )
    {
        // Get associated post
        if( is_object( $post ) && $post instanceof WP_Post ) {
            $this->post = $post;
        } else if( is_numeric( $post ) ) {
            $this->post = get_post( $post );
        } else {
            throw new InvalidArgumentException( 'Grids can only be instantiated with a Post object or Post ID.' );
        }

        // Get metadata
        $this->data = get_post_meta( $this->post->ID, 'eic_grid_data', true );
    }

    public function get_data()
    {
	    return $this->data;
    }

	public function update_data( $data )
	{
		$data['id'] = $this->ID();
		update_post_meta( $this->ID(), 'eic_grid_data', $data );
	}

	public function draw()
	{
		$layout = EasyImageCollage::get()->helper( 'layouts' )->get( $this->layout_name() );
		return EasyImageCollage::get()->helper( 'layouts' )->draw_layout( $layout, $this );
	}

	// Grid Fields
	public function align()
	{
		return isset( $this->data['properties']['align'] ) ? $this->data['properties']['align'] : 'center';
	}

	public function border_color()
	{
		return $this->data['properties']['borderColor'];
	}

	public function border_width()
	{
		return intval( $this->data['properties']['borderWidth'] );
	}

	public function divider_adjust( $id )
	{
		if( isset( $this->data['dividers'] ) && isset( $this->data['dividers'][$id] ) ) {
			return floatval( $this->data['dividers'][$id] );
		}
		return false;
	}

	public function height()
	{
		return intval( $this->width() / $this->ratio() );
	}

	public function ID()
	{
		return $this->post->ID;
	}

	public function image( $id )
	{
		$images = $this->images();
		return isset( $images[$id] ) ? $images[$id] : false;
	}

	public function images()
	{
		$images = is_array( $this->data['images'] ) ? $this->data['images'] : array();
		return $images;
	}

	public function layout_name()
	{
		return $this->data['layout'];
	}

	public function ratio()
	{
		$ratio = floatval( $this->data['properties']['ratio'] );
		$ratio = $ratio == 0 ? 1 : $ratio;
		return $ratio;
	}

	public function width()
	{
		return intval( $this->data['properties']['width'] );
	}
}