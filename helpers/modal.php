<div class="eic-modal">
    <div class="eic-layouts">
        <span class="eic-modal-title"><i class="fa fa-angle-double-down"></i> <?php _e( 'Choose layout', 'easy-image-collage' ); ?></span>
        <div class="eic-container">
            <?php echo EasyImageCollage::get()->helper( 'layouts' )->draw_layouts( true ); ?>
        </div>
    </div>

    <div class="eic-editing">
	    <a href="#" class="eic-modal-title" onclick="EasyImageCollage.btnChooseLayout()"><i class="fa fa-angle-double-left"></i> <?php _e( 'Change layout', 'easy-image-collage' ); ?></a>
	    <a href="#" class="eic-modal-title eic-modal-title-right" onclick="EasyImageCollage.btnFinish()"><?php _e( 'Finish', 'easy-image-collage' ); ?> <i class="fa fa-angle-double-right"></i></a>
	    <div class="eic-properties">
	        <span class="eic-property-header"><?php _e( 'Grid', 'easy-image-collage' ); ?></span>
	        <input type="text" id="grid-width" value="500">
	        <span id="grid-width-minus">-</span>
	        <span class="slider-value-container"><span id="grid-width-value">500</span> px</span>
	        <span id="grid-width-plus">+</span>
	        
	        <input type="text" id="grid-ratio" value="1">
	        <span class="slider-value-container"><span id="grid-ratio-value">1</span></span>
	        
	        <select id="grid-align">
		        <optgroup label="<?php _e( 'Text above and below', 'easy-image-collage' ); ?>">
			        <option value="left"><?php _e( 'Align', 'easy-image-collage' ); ?>: <?php _e( 'left', 'easy-image-collage' ); ?></option>
			        <option value="center"><?php _e( 'Align', 'easy-image-collage' ); ?>: <?php _e( 'center', 'easy-image-collage' ); ?></option>
			        <option value="right"><?php _e( 'Align', 'easy-image-collage' ); ?>: <?php _e( 'right', 'easy-image-collage' ); ?></option>
		        </optgroup>
	        </select>
	        
	        <span class="eic-property-header"><?php _e( 'Borders', 'easy-image-collage' ); ?></span>
            <input type="text" id="border-width" value="4">
	        <span class="slider-value-container"><span id="border-width-value">4</span> px</span>
            <input type="color" id="border-color" value="#444444"><br/>
	        <input type="checkbox" id="border-change"> <?php _e( 'Adjust Borders', 'easy-image-collage' ); ?>
	        <div class="eic-premium-only"><?php _e( 'This feature is only available in', 'easy-image-collage' ); ?> <a href="http://bootstrapped.ventures/easy-image-collage/" target="_blank">Easy Image Collage Premium</a></div>
        </div>
        <div class="eic-container">
        </div>
    </div>

	<div class="eic-manipulating">
		<?php
		if( EasyImageCollage::is_addon_active( 'image-manipulation' ) ) {
			require( EasyImageCollage::addon( 'image-manipulation' )->addonDir . '/modal.php' );
		} else {
		?>
		<a href="#" class="eic-modal-title" onclick="EasyImageCollage.setActivePage('editing')"><i class="fa fa-angle-double-left"></i> <?php _e( 'Cancel', 'easy-image-collage' ); ?></a>
		<div class="eic-premium-only"><?php _e( 'This feature is only available in', 'easy-image-collage' ); ?> <a href="http://bootstrapped.ventures/easy-image-collage/" target="_blank">Easy Image Collage Premium</a></div>
        <div class="manipulating-image-preview">
            <a href="http://bootstrapped.ventures/easy-image-collage/" target="_blank">
                <img src="<?php echo EasyImageCollage::get()->coreUrl; ?>/img/manipulate_image_preview.png" />
            </a>
        </div>
		<?php } ?>
	</div>
</div>