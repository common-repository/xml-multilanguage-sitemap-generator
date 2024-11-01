<?php
// This is the secret key for API authentication. You configured it in the settings menu of the license manager plugin.
define('XML_GEN_SECRET_KEY', '5937a8faf0f545.87133518'); //Rename this constant name so it is specific to your plugin or theme.

// This is the URL where API query request will be sent to. This should be the URL of the site where you have installed the main license manager plugin. Get this value from the integration help page.
define('XML_GEN_SERVER_URL', 'http://www.marcogiannini.net'); //Rename this constant name so it is specific to your plugin or theme.

// This is a value that will be recorded in the license manager data so you can identify licenses for this item/product.
define('XML_GEN_ITEM_REFERENCE', 'xml multilanguage sitemap generator license'); //Rename this constant name so it is specific to your plugin or theme.

/**
 * Return true if license is valid.
 *
 * @return boolean.
 *
 */
function _xmg_check_license(){
	$license_activate = get_option('_xmg_premium_license_key');
	if($license_activate){
		return true;
	} else {
		return false;
	}
}

function _xmg_activate_license() {
    exit;
    echo '<div class="sponsor xmg_section">';
        if (isset($_REQUEST['activate_license'])) {
            
            $license_key = $_REQUEST['xml_gen_premium_license_key'];

            // API query parameters
            $api_params = array(
                'slm_action' => 'slm_activate',
                'secret_key' => XML_GEN_SECRET_KEY,
                'license_key' => $license_key,
                'registered_domain' => $_SERVER['SERVER_NAME'],
                'item_reference' => urlencode(XML_GEN_ITEM_REFERENCE),
            );

            // Send query to the license manager server
            $query = esc_url_raw(add_query_arg($api_params, XML_GEN_SERVER_URL));
            $response = wp_remote_get($query, array('timeout' => 20, 'sslverify' => false));

            // Check for error in the response
            if (is_wp_error($response)){
                echo "Unexpected Error! The query returned with an error.";
            }

            //var_dump($response);//uncomment it if you want to look at the full response
            
            // License data.
            $license_data = json_decode(wp_remote_retrieve_body($response));
            
            // TODO - Do something with it.
            //var_dump($license_data);//uncomment it to look at the data
            
            if($license_data->result == 'success'){//Success was returned for the license activation
                
                //Uncomment the followng line to see the message that returned from the license server
                echo '<div class="notice notice-success"><p>'.__('The license Key has been activated. Thank you for your choice!','xml-multilanguage-sitemap-generator').'</p></div>';
                
                //Save the license key in the options table
                update_option('xml_gen_premium_license_key', $license_key); 
            }
            else{
                //Show error to the user. Probably entered incorrect license key.
                
                //Uncomment the followng line to see the message that returned from the license server
                echo '<div class="notice notice-error"><p>'.__("I'm sorry but there's an error with the key. Probably you are trying to write a wrong key.","xml-multilanguage-sitemap-generator").'</p></div>';
            }

        }
        /*** End of license activation ***/

        /*** License activate button was clicked ***/
        if (isset($_REQUEST['deactivate_license'])) {
            $license_key = $_REQUEST['xml_gen_premium_license_key'];

            // API query parameters
            $api_params = array(
                'slm_action' => 'slm_deactivate',
                'secret_key' => XML_GEN_SECRET_KEY,
                'license_key' => $license_key,
                'registered_domain' => $_SERVER['SERVER_NAME'],
                'item_reference' => urlencode(XML_GEN_ITEM_REFERENCE),
            );

            // Send query to the license manager server
            $query = esc_url_raw(add_query_arg($api_params, XML_GEN_SERVER_URL));
            $response = wp_remote_get($query, array('timeout' => 20, 'sslverify' => false));

            // Check for error in the response
            if (is_wp_error($response)){
                echo "Unexpected Error! The query returned with an error.";
            }

            //var_dump($response);//uncomment it if you want to look at the full response
            
            // License data.
            $license_data = json_decode(wp_remote_retrieve_body($response));
            
            // TODO - Do something with it.
            //var_dump($license_data);//uncomment it to look at the data
            
            if($license_data->result == 'success'){//Success was returned for the license activation
                
                //Uncomment the followng line to see the message that returned from the license server
                echo '<div class="notice notice-success"><p>'.__("The license key has been removed correctly.","xml-multilanguage-sitemap-generator").'</p></div>';
                
                //Remove the licensse key from the options table. It will need to be activated again.
                update_option('xml_gen_premium_license_key', '');
            }
            else{
                //Show error to the user. Probably entered incorrect license key.
                
                //Uncomment the followng line to see the message that returned from the license server
                echo '<div class="notice notice-error"><p>'.__("The license key on this domain is already inactive.","xml-multilanguage-sitemap-generator").'</p></div>';
            }
            
        }
        /*** End of sample license deactivation ***/
        ?>
        <div class="xmg_section_title">
            <?php if(!_xmg_check_license()) : ?>
                <p><?php _e('Become Premium ','xml-multilanguage-sitemap-generator'); ?><span style="color: #FFBF45;" class="dashicons dashicons-star-empty"></span></p>
            <?php else : ?>
                <p><?php _e('Premium user ','xml-multilanguage-sitemap-generator'); ?><span style="color: #FFBF45;" class="dashicons dashicons-star-filled"></span></p>
            <?php endif; ?></p>
        </div>
        <div class="activation-form">
            <form action="" method="post">
                <?php wp_create_nonce('_xmg_premium_license'); ?>
                <?php wp_nonce_field('_xmg_premium_license'); ?>
                <input placeholder="<?php _e('Insert your license key', 'xml-multilanguage-sitemap-generator'); ?>" class="input-with-btn" type="text" id="xml_gen_premium_license_key" name="xml_gen_premium_license_key"  value="<?php echo get_option('_xmg_premium_license_key'); ?>" >
                <?php if(!_xmg_check_license()) : ?>
                    <input type="submit" name="activate_license" value="<?php _e('Activate', 'xml-multilanguage-sitemap-generator'); ?>" class="button-primary" />
                <?php else : ?>
                    <input type="submit" name="deactivate_license" value="<?php _e('Deactivate', 'xml-multilanguage-sitemap-generator'); ?>" class="button" />
                <?php endif; ?>
            </form>
            <?php if(!_xmg_check_license()) : ?>
                <p class="no-margin clear"><a target="_blank" href="https://marcogiannini.net">Passa alla versione premium per funzioni esclusive!</a></p>
            <?php endif; ?>
        </div>
    </div><?php


}