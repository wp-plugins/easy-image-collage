var EasyImageCollage = EasyImageCollage || {};

/**
 * Variables
 */
EasyImageCollage.file_frame = undefined;
EasyImageCollage.editing_image = undefined;
EasyImageCollage.editing_grid = {};
EasyImageCollage.lightbox_settings = {
    namespace: 'eic-lightbox',
    closeOnClick: false,
    closeOnEsc: false,
    afterOpen: function() {
        var borderWidth = EasyImageCollage.editing_grid.properties.borderWidth;
        var borderColor = EasyImageCollage.editing_grid.properties.borderColor;

        // Border color - init and bind event
        jQuery('.eic-lightbox #border-color')
            .val(borderColor)
            .wpColorPicker({
                change: function () {
                    EasyImageCollage.editing_grid.properties.borderColor = jQuery(this).wpColorPicker('color');
                    EasyImageCollage.redrawBorders();
                }
            })
        ;

        // Border width - init and bind event
        jQuery('.eic-lightbox #border-width')
            .val(borderWidth)
            .simpleSlider({
                range: [1,20],
                step: 1,
                snap: true
            }).bind('slider:changed', function (event, data) {
                EasyImageCollage.editing_grid.properties.borderWidth = data.value;
                EasyImageCollage.redrawBorders();
            })
        ;
    }
};

/** Variables from PHP
 *
 */
EasyImageCollage.grids = {};
EasyImageCollage.default_grid = {};

/**
 * Front end events
 */
jQuery(document).ready(function($) {
    if(typeof eic_admin_grids !== 'undefined' && typeof eic_default_grid !== 'undefined') {
        EasyImageCollage.grids = eic_admin_grids;
        EasyImageCollage.default_grid = eic_default_grid;

        // Add new button
        $('#eic-button').featherlight($('.eic-modal'), EasyImageCollage.lightbox_settings);
        $('#eic-button').click(function() {
            EasyImageCollage.setActivePage('layouts');
            EasyImageCollage.newGrid();
        });

        // Choose layout
        $('.eic-layouts .eic-frame').click(function() {
            EasyImageCollage.btnPickLayout($(this).clone());
        });

        // Edit image controls
        $('.eic-editing').on('hover', '.eic-image', function(event) {
            if(event.type == 'mouseenter') {
                $(this).find('.eic-image-controls').show();
            } else {
                $(this).find('.eic-image-controls').hide();
            }
        });
    }
});

/**
 * Front end control buttons
 */
EasyImageCollage.btnEditGrid = function(id) {
    // Set editing grid
    EasyImageCollage.editing_grid = EasyImageCollage.grids[id];
    var grid = EasyImageCollage.editing_grid;

    // Open lightbox
    jQuery.featherlight(jQuery('.eic-modal'), EasyImageCollage.lightbox_settings);

    // Load grid layout
    var layout = jQuery('.eic-lightbox .eic-layouts .eic-frame-' + grid.layout).clone();
    jQuery('.eic-editing .eic-container').html(layout);

    // Load images in grid
    for(var i = 0; i < grid['images'].length; i++) {
        var image = grid['images'][i];

        EasyImageCollage.setImageFrontend(image);
    }

    // Go to edit grid page
    EasyImageCollage.setActivePage('editing');
};

EasyImageCollage.btnChooseLayout = function() {
    EasyImageCollage.setActivePage('layouts');
};

EasyImageCollage.btnPickLayout = function(layout_element) {
    jQuery('.eic-editing .eic-container').html(layout_element);
    EasyImageCollage.setActivePage('editing');

    var grid = EasyImageCollage.editing_grid;

    grid['layout'] = layout_element.data('layout-name');

    for(var i = 0; i < grid['images'].length; i++) {
        var image = grid['images'][i];

        var attachment = {
            id: image.attachment_id,
            url: image.attachment_url,
            width: image.attachment_width,
            height: image.attachment_height
        };
        EasyImageCollage.setImage(i, attachment);
    }
};

EasyImageCollage.btnImage = function(id) {
    EasyImageCollage.editing_image = id;
    EasyImageCollage.openMediaModal();
};

EasyImageCollage.btnFinish = function() {
    var grid = EasyImageCollage.editing_grid;

    var data = {
        action: 'image_collage',
        security: eic_admin.nonce,
        grid: grid
    };

    var new_grid = grid.id == 0 ? true : false;

    jQuery.post(eic_admin.ajaxurl, data, function(grid_id) {

        if(new_grid) {
            EasyImageCollage.addShortcodeToEditor(grid_id);
        }

        EasyImageCollage.grids[grid_id] = jQuery.extend(true, {}, grid);
        jQuery.featherlight.close();
    }, 'json');
};

/**
 * Other functions
 */
EasyImageCollage.newGrid = function() {
    EasyImageCollage.editing_grid = jQuery.extend(true, {}, EasyImageCollage.default_grid);
};

EasyImageCollage.openMediaModal = function() {

    // If the media frame already exists, reopen it.
    if ( EasyImageCollage.file_frame ) {
        EasyImageCollage.file_frame.open();
        return;
    }

    // Create the media frame.
    EasyImageCollage.file_frame = wp.media.frames.file_frame = wp.media({
        title: 'Choose Image',
        button: {
            text: 'Choose Image'
        },
        multiple: false
    });

    // When an image is selected, run a callback.
    EasyImageCollage.file_frame.on( 'select', function() {
        // We set multiple to false so only get one image from the uploader
        attachment = EasyImageCollage.file_frame.state().get('selection').first().toJSON();

        if( EasyImageCollage.editing_image !== undefined ) {
            EasyImageCollage.setImage(EasyImageCollage.editing_image, attachment);
            EasyImageCollage.editing_image = undefined;
        }
    });

    // Finally, open the modal
    EasyImageCollage.file_frame.open();
};

EasyImageCollage.setImage = function(id, attachment) {
    var image_element = jQuery('.eic-lightbox .eic-editing .eic-image-' + id);

    if(image_element.length !== 0) {
        var total_border_width = 4 * parseInt(EasyImageCollage.editing_grid['properties']['borderWidth']);

        // Calculate size and position
        var frame_width = image_element.innerWidth() + total_border_width;
        var frame_height = image_element.innerHeight() + total_border_width;
        var frame_ratio = frame_width / frame_height;
        var image_ratio = attachment.width / attachment.height;

        var bg_width = frame_width;
        var bg_height = frame_width / image_ratio;
        var bg_pos_x = 0;
        var bg_pos_y = -(bg_height - frame_height) / 2; // Center vertically

        if(frame_ratio < image_ratio) {
            bg_width = frame_height * image_ratio;
            bg_height = frame_height;
            bg_pos_x = -(bg_width - frame_width) / 2; // Center horizontally
            bg_pos_y = 0;
        }

        // Image object
        var image = {
            id: id,
            attachment_id: attachment.id,
            attachment_url: attachment.url,
            attachment_width: attachment.width,
            attachment_height: attachment.height,
            size_x: bg_width,
            size_y: bg_height,
            pos_x: bg_pos_x,
            pos_y: bg_pos_y
        };
        EasyImageCollage.editing_grid['images'][id] = image;

        EasyImageCollage.setImageFrontend(image);
    }
};

EasyImageCollage.setImageFrontend = function(image) {
    var image_element = jQuery('.eic-lightbox .eic-editing .eic-image-' + image.id);

    // Element styling
    image_element.addClass('has-image');
    image_element
        .css('background-image', 'url("'+image.attachment_url+'")')
        .css('background-size', '' + image.size_x + 'px ' + image.size_y + 'px')
        .css('background-position-x', '' + image.pos_x + 'px')
        .css('background-position-y', '' + image.pos_y + 'px')
    ;

    // Handle move
    EasyImageCollage.handleImageMove(image);
};

EasyImageCollage.handleImageMove = function(image) {
    var image_element = jQuery('.eic-lightbox .eic-editing .eic-image-' + image.id);

    image_element.on('mousedown touchstart', function(e) {
        if (e.target !== image_element[0]) return;
        e.preventDefault();

        if (e.originalEvent.touches) {
            EasyImageCollage.modifyEventForTouch(e);
        } else if (e.which !== 1) {
            return;
        }

        var x0 = e.clientX,
            y0 = e.clientY,
            size = image_element.css('background-size').match(/(-?\d+).*?\s(-?\d+)/),
            size_x = size[1],
            size_y = size[2],
            min_x = image_element.innerWidth() - size_x,
            min_y = image_element.innerHeight() - size_y,
            pos_x = parseInt(image_element.css('background-position-x')),
            pos_y = parseInt(image_element.css('background-position-y'));

        jQuery(window).on('mousemove touchmove', function(e) {
            e.preventDefault();

            if (e.originalEvent.touches) {
                EasyImageCollage.modifyEventForTouch(e);
            }

            var x = e.clientX,
                y = e.clientY;

            // New position
            pos_x = pos_x+x-x0;
            pos_y = pos_y+y-y0;

            // Check bounds
            pos_x = pos_x < min_x ? min_x : ( pos_x > 0 ? 0 : pos_x );
            pos_y = pos_y < min_y ? min_y : ( pos_y > 0 ? 0 : pos_y );

            // New starting point for drag
            x0 = x;
            y0 = y;

            image_element
                .css('background-position-x', '' + pos_x + 'px')
                .css('background-position-y', '' + pos_y + 'px')
        });

        jQuery(window).on('mouseup touchend mouseleave', function() {
            // Update new image position
            var pos_x = parseInt(image_element.css('background-position-x')),
                pos_y = parseInt(image_element.css('background-position-y'));

            image.pos_x = pos_x;
            image.pos_y = pos_y;

            // Remove event handlers
            jQuery(window).off('mousemove touchmove');
            jQuery(window).off('mouseup touchend mouseleave');
        });
    });
};

/**
 * Helper functions
 */
EasyImageCollage.redrawBorders = function() {
    var borderWidth = EasyImageCollage.editing_grid.properties.borderWidth;
    var borderColor = EasyImageCollage.editing_grid.properties.borderColor;

    jQuery('.eic-lightbox .eic-editing .eic-frame')
        .css('border', borderWidth + 'px solid ' + borderColor)
        .find('.eic-image')
        .css('border', borderWidth + 'px solid ' + borderColor);
};

EasyImageCollage.setActivePage = function(name) {
    var pages = ['layouts', 'editing'];

    pages.forEach(function(page) {
        if(page == name) {
            jQuery('.eic-' + page).show();
        } else {
            jQuery('.eic-' + page).hide();
        }
    });

    // Page specific
    if(name == 'editing') {
        EasyImageCollage.redrawBorders();
    }
};

EasyImageCollage.modifyEventForTouch = function(e) {
    e.clientX = e.originalEvent.touches[0].clientX;
    e.clientY = e.originalEvent.touches[0].clientY;
};

EasyImageCollage.addShortcodeToEditor = function(id) {
    var text = ' [easy-image-collage id='+id+'] ';

    if( !tinyMCE.activeEditor || tinyMCE.activeEditor.isHidden()) {
        var current = jQuery('textarea#content').val();
        jQuery('textarea#content').val(current + text);
    } else {
        tinyMCE.execCommand('mceInsertContent', false, text);
    }
};