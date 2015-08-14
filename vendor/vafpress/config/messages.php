<?php

return array(

	////////////////////////////////////////
	// Localized JS Message Configuration //
	////////////////////////////////////////

	/**
	 * Validation Messages
	 */
	'validation' => array(
		'alphabet'     => __('Value needs to be Alphabet', 'easy-image-collage'),
		'alphanumeric' => __('Value needs to be Alphanumeric', 'easy-image-collage'),
		'numeric'      => __('Value needs to be Numeric', 'easy-image-collage'),
		'email'        => __('Value needs to be Valid Email', 'easy-image-collage'),
		'url'          => __('Value needs to be Valid URL', 'easy-image-collage'),
		'maxlength'    => __('Length needs to be less than {0} characters', 'easy-image-collage'),
		'minlength'    => __('Length needs to be more than {0} characters', 'easy-image-collage'),
		'maxselected'  => __('Select no more than {0} items', 'easy-image-collage'),
		'minselected'  => __('Select at least {0} items', 'easy-image-collage'),
		'required'     => __('This is required', 'easy-image-collage'),
	),

	/**
	 * Import / Export Messages
	 */
	'util' => array(
		'import_success'    => __('Import succeed, option page will be refreshed..', 'easy-image-collage'),
		'import_failed'     => __('Import failed', 'easy-image-collage'),
		'export_success'    => __('Export succeed, copy the JSON formatted options', 'easy-image-collage'),
		'export_failed'     => __('Export failed', 'easy-image-collage'),
		'restore_success'   => __('Restoration succeed, option page will be refreshed..', 'easy-image-collage'),
		'restore_nochanges' => __('Options identical to default', 'easy-image-collage'),
		'restore_failed'    => __('Restoration failed', 'easy-image-collage'),
	),

	/**
	 * Control Fields String
	 */
	'control' => array(
		// select2 select box
		'select2_placeholder' => __('Select option(s)', 'easy-image-collage'),
		// fontawesome chooser
		'fac_placeholder'     => __('Select an Icon', 'easy-image-collage'),
	),

);

/**
 * EOF
 */