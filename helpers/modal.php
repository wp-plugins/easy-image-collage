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
            <input type="text" id="border-width" value="4">
            <input type="color" id="border-color" value="#444444">
        </div>
        <div class="eic-container">
        </div>
    </div>
</div>