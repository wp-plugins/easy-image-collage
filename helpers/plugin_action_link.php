<?php

class EIC_Plugin_Action_Link {

    public function __construct()
    {
        add_filter( 'plugin_action_links_easy-image-collage/easy-image-collage.php', array( $this, 'action_links' ) );
    }

    public function action_links( $links )
    {
        $links[] = '<a href="'. get_admin_url(null, 'options-general.php?page=eic_settings') .'">'.__( 'Settings', 'easy-image-collage' ).'</a>';

        return $links;
    }
}