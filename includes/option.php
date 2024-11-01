<?php
add_action( 'admin_menu', '_xmg_add_admin_menu' );
add_action( 'admin_init', '_xmg_define_options');
add_action( 'admin_init', '_xmg_configurator_page_init' );

/**
 * List of all options inside plugin.
 *
 */
global $xmg_options_list;
$xmg_options_list = array(
    '_xmg_changefreq_value',
    '_xmg_priority_value',
    '_xmg_changefreq_single_value',
    '_xmg_priority_single_value',
    '_xmg_premium_license_key',
    '_xmg_hide_post',
    '_xmg_id_excluded',
    '_xmg_is_debug',
    '_xmg_post_type_to_include',
    '_xmg_sitemap_name',
    '_xmg_useless_posts'
);

include ('functions.options.php');

if(_xmg_check_license()) include('premium/delete_xml.php');

function _xmg_add_admin_menu(  ) { 
    add_menu_page( 
        'xml multilanguage sitemap generator', 
        'XML Sitemap', 
        'manage_options', 
        'xml_multilanguage_sitemap_generator', 
        '_xmg_settings_page', 
        'dashicons-networking'
    );
}

function _xmg_define_options() {

    //Definisco tutte le varie opzioni che salverà il plugin
    // !-- General Tab Option --! //
    register_setting( '_xmg_configurator_page_general', '_xmg_sitemap_name');
    register_setting( '_xmg_configurator_page_general', '_xmg_post_type_to_include' );
    register_setting( '_xmg_configurator_page_general', '_xmg_priority_value' );
    register_setting( '_xmg_configurator_page_general', '_xmg_changefreq_value' );

    // !-- Post Type Tab Option --! //
    register_setting( '_xmg_configurator_page_post_type', '_xmg_id_excluded' );
    register_setting( '_xmg_configurator_page_post_type', '_xmg_priority_single_value' );
    register_setting( '_xmg_configurator_page_post_type', '_xmg_changefreq_single_value' );
    register_setting( '_xmg_configurator_page_post_type', '_xmg_hide_post');
    register_setting( '_xmg_configurator_page_post_type', '_xmg_is_debug' );
}

function _xmg_configurator_page_init() { 

    add_settings_section(
        '_xmg_configurator_section',
        'Xml Multilanguage Sitemap Generator',
        '_xmg_configurator_section_welcome',
        '_xmg_configurator_page'
    );
}

function _xmg_configurator_section_welcome() {
    echo '<p class="welcome-intro">Benvenuto nel pannello di configurazione.</p>';
}

function _xmg_build_general_options() {
    _xmg_sitemap_name_field();
    _xmg_posts_type_field();
}

function _xmg_build_post_type_options() {
    _xmg_get_current_post_type_subtab();
    _xmg_single_posts_field();
}

function _xmg_build_aside_options() {
    _xmg_credits();
    _xmg_debug_column();
    _xmg_activate_license();
}

function _xmg_check_isset($arg, $index){
    if(is_array($arg) && isset($arg[$index])){
        echo $arg[$index];
    }
}

/*
*
* Print the standard settings page.
*
*/
function _xmg_settings_page() { ?>
<div class="xmg_config_settings">
    <div class="plugin-settings-page">
        <h1>XML Multilanguage Sitemap Generator</h1>
        <p><?php _e('Welcome in configuration panel.', 'xml-multilanguage-sitemap-generator'); ?></p>
        <?php
            $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general';
        ?>
        <?php settings_errors(); ?>
        <h2 class="nav-tab-wrapper">
            <a href="?page=xml_multilanguage_sitemap_generator&tab=general" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>">
                <?php _e('General Options', 'xml-multilanguage-sitemap-generator'); ?>
            </a>
            <a href="?page=xml_multilanguage_sitemap_generator&tab=post_type" class="nav-tab <?php echo $active_tab == 'post_type' ? 'nav-tab-active' : ''; ?>">
                <?php _e('Post Type Options', 'xml-multilanguage-sitemap-generator'); ?>
            </a>
        </h2>
        <form action="options.php" method='post'>
            <?php switch ($active_tab) {
                case 'general':
                    settings_fields( '_xmg_configurator_page_general' );
                    _xmg_build_general_options();
                    break;
                case 'post_type':
                    settings_fields( '_xmg_configurator_page_post_type' );
                    _xmg_build_post_type_options();
                    break;
                default:
                    _xmg_build_general_options();
                    break;
            } ?>
        </form>
    </div>

    <div class="sponsor-sidebar">
        <?php _xmg_build_aside_options(); ?>
    </div> 
</div>
<?php
}
