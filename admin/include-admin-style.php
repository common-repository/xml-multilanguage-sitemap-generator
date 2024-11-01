<?php 

function _xmg_admin_style($hook) {
    
        if($hook != 'toplevel_page_xml_multilanguage_sitemap_generator') {
            return;
        }
        wp_enqueue_style('xml_multilanguage_sitemap_generator_styles', plugins_url ( 'css/style.css', __FILE__ ));
        wp_enqueue_script('xml_multilanguage_sitemap_generator_script', plugins_url ( 'js/main.js', __FILE__ ));

}
add_action( 'admin_enqueue_scripts', '_xmg_admin_style' );
