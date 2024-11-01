<?php 
// =======================================================================// 
// ! GENERAL OPTIONS TAB                                                ! //        
// =======================================================================//  
function _xmg_sitemap_name_field() { 
    $sitemap_name = _xmg_get_sitemap_name();
    ?>
    <div class="xmg_section">
        <div class="xmg_section_title">
            <p><?php _e('Choose the name of your sitemap', 'xml-multilanguage-sitemap-generator'); ?></p>
        </div>
        <div class="xmg_section_content"> <?php
        if($sitemap_name){ ?>
            <input class="inline" type="text" id="sitemap_name" name="_xmg_sitemap_name" value="<?php echo $sitemap_name; ?>"><?php
        } ?>
        </div>
        <div class="xmg_section_footer">
            <p class="hidden-tutorial"><?php _e('This is the link for Google: ','xml-multilanguage-sitemap-generator'); ?> <a target="_blank" href="<?php echo site_url('/'); ?>xml-sitemap/"><b>/xml-sitemap/</b><b data-synctarget="#sitemap_name" class="syncronize-with-name"></b><b>.xml</b></a></p>
        </div>
        <div class="xmg_section_footer warning">
            <p class="no-margin"><?php _e("Please resend sitemap link to Google after 2.0 update. The sitemap path has changed. I'm sorry for the inconvenience.", 'xml-multilanguage-sitemap-generator'); ?></p>
        </div>
    </div>

<?php }

function _xmg_posts_type_field(  ) { 
    $options_pt = _xmg_get_included_pt();
    $priority = get_option( '_xmg_priority_value' );
    $changefreq = get_option( '_xmg_changefreq_value' );
    $hide_posts = get_option( '_xmg_hide_post' );
    $remove_post_types = get_option( '_xmg_useless_posts' );
    $post_types = get_post_types(array('public' => true));

    foreach ($remove_post_types as $remove_post_type) {
        if (($key = array_search($remove_post_type, $post_types)) !== false) {
            unset($post_types[$key]);
        }
    }
    ?>
    <div class="xmg_section">
        <div class="xmg_section_title">
            <p><?php _e('Choose what posts type you want to see in your sitemap', 'xml-multilanguage-sitemap-generator'); ?></p>
        </div>
        <div class="xmg_section_content">
            <?php 
            if($options_pt){ ?>
                <div class="table-responsive-vertical shadow-z-1">
                    <table id="table" class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th><?php _e('Visible in sitemap','xml-multilanguage-sitemap-generator'); ?></th>
                                <th><?php _e('Post type name','xml-multilanguage-sitemap-generator'); ?></th>
                                <th><?php _e('General Priority','xml-multilanguage-sitemap-generator'); ?></th>
                                <th><?php _e('General Changefreq','xml-multilanguage-sitemap-generator'); ?></th>
                            </tr>
                        </thead>
                        <tbody> <?php
                        foreach ($post_types as $post_type) { ?>
                            <tr>
                                <td data-title="Visible in sitemap"><input id="post_type_to_include_'<?php echo $post_type; ?>'" type='checkbox' name='_xmg_post_type_to_include[<?php echo $post_type; ?>]' <?php if(isset($options_pt[$post_type])){checked( $options_pt[$post_type], 1 );} ?> value='1'><label for="post_type_to_include_'<?php echo $post_type; ?>'"></label></td>
                                <td data-title="Post type name"><?php echo $post_type; ?></td>
                                <td data-title="General Priority"><input type='number' step="0.1" min="0" max="1" name='_xmg_priority_value[<?php echo $post_type; ?>]' value="<?php _xmg_check_isset($priority, $post_type); ?>"></td>
                                <td data-title="General Changefreq"><input class="autocomplete" type="text" name="_xmg_changefreq_value[<?php echo $post_type ?>]" value="<?php _xmg_check_isset($changefreq, $post_type); ?>"></td>
                            </tr>
                        <?php }
                        ?>
                        </tbody>
                    </table>   
                </div>
                <input type="submit" name="submit" class="button button-primary" value="<?php _e('Create the Sitemap','xml-multilanguage-sitemap-generator'); ?>">
            <?php 
            } else { ?>
                <div class="table-responsive-vertical shadow-z-1 what">
                    <table id="table" class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th><?php _e('Visible in sitemap','xml-multilanguage-sitemap-generator'); ?></th>
                                <th><?php _e('Post type name','xml-multilanguage-sitemap-generator'); ?></th>
                                <th><?php _e('General Priority','xml-multilanguage-sitemap-generator'); ?></th>
                                <th><?php _e('General Changefreq','xml-multilanguage-sitemap-generator'); ?></th>
                            </tr>
                        </thead>
                        <tbody> <?php
                        foreach ($post_types as $post_type) { ?>
                            <tr>
                                <td data-title="Visible in sitemap"><input type='checkbox' id="post_type_to_include_'<?php echo $post_type; ?>'" name='_xmg_post_type_to_include[<?php echo $post_type; ?>]' value='1'><label for="post_type_to_include_'<?php echo $post_type; ?>'"></label></td>
                                <td data-title="Post type name"><?php echo $post_type; ?></td>
                                <td data-title="General Priority"><input type='number' step="0.1" min="0" max="1" name='_xmg_priority_value[<?php echo $post_type; ?>]' value="<?php _xmg_check_isset($priority, $post_type); ?>"></td>
                                <td data-title="General Changefreq"><input class="autocomplete" type="text" name="_xmg_changefreq_value[<?php echo $post_type ?>]" value="<?php _xmg_check_isset($changefreq, $post_type); ?>"></td>
                            </tr>
                        <?php
                            } 
                        ?>
                        </tbody>
                    </table>   
                </div>
                <input type="submit" name="submit" class="button button-primary" value="<?php _e('Create the Sitemap','xml-multilanguage-sitemap-generator'); ?>">
                <?php
            } ?>
        </div>
    </div>
<?php
}

function _xmg_get_current_post_type_subtab(){
    $show_posts_type = _xmg_get_included_pt();
    $active_post_type = isset( $_GET[ 'active_post_type' ] ) ? $_GET[ 'active_post_type' ] : 'post';
    if(!empty($show_posts_type)){
        echo '<div class="sub-tab-container">';
        $count = 0;
        foreach ($show_posts_type as $show_post_type => $value) { ?>
            <a class="sub-tab <?php if($count == 0) echo 'active'; ?>" data-view="<?php echo $show_post_type; ?>" href="javascript: void(0);"><?php echo get_post_type_object( $show_post_type )->labels->name;?></a>
        <?php 
            $count++;
        }
        echo '</div>';
    }
}

// =======================================================================// 
// ! POST TYPE TAB                                                      ! //        
// =======================================================================//  

function _xmg_single_posts_field(  ) { 

    $post_types_chosen = _xmg_get_included_pt();
    $options_id = _xmg_get_excluded_id();
    $priority = get_option('_xmg_priority_value');
    $priority_single_value = get_option('_xmg_priority_single_value');
    $changefreq = get_option('_xmg_changefreq_value');
    $changefreq_single_value = get_option('_xmg_changefreq_single_value');
    if($post_types_chosen){
        echo '<div class="tables-container-xmg">';
        $counterActive = 0; 
        foreach ($post_types_chosen as $post_type_chosen => $value) {

            $post_type_name = get_post_type_object( $post_type_chosen )->labels->singular_name;
            $args = array(
                'post_type' => $post_type_chosen,
                'posts_per_page' => -1,
            );
            // The Query
            $query = new WP_Query( $args );
            // The Loop
            if ( $query->have_posts() ) { ?>   
                <div class="xmg_section<?php if($counterActive == 0) echo ' visible' ?>" id="<?php echo $post_type_chosen; ?>">
                    <div class="xmg_section_title">
                        <p style="text-transform: capitalize;"><?php echo $post_type_name; ?></p>
                    </div>
                    <div class="xmg_section_content">
                        <div class="table-responsive-vertical shadow-z-1">
                            <table id="table" class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th><?php _e('Hide','xml-multilanguage-sitemap-generator'); ?></th>
                                        <th><?php _e('ID','xml-multilanguage-sitemap-generator'); ?></th>
                                        <th><?php _e('Title','xml-multilanguage-sitemap-generator'); ?></th>
                                        <th><?php _e('Priority','xml-multilanguage-sitemap-generator'); ?></th>
                                        <th><?php _e('Changefreq','xml-multilanguage-sitemap-generator'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ( $query->have_posts() ) {
                                        $query->the_post(); 
                                        $id = get_the_ID();
                                        $title = get_the_title();
                                        $permalink = get_permalink();

                                        if($options_id){ ?>
                                            <tr>
                                                <td data-title="Hide"><input id="id_excluded[<?php echo $id; ?>]" type='checkbox' name='_xmg_id_excluded[<?php echo $id; ?>]' <?php if(isset($options_id[$id])){ checked( $options_id[$id], 1 ); } ?> value='1'><label for='id_excluded[<?php echo $id; ?>]'></label></td>
                                                <td data-title="ID"><?php echo $id; ?></td>
                                                <td data-title="Title"><a target="_blank" href="<?php echo $permalink; ?>"><?php echo $title; ?></a></td>

                                                <?php if(isset($priority_single_value[$id]) && !empty($priority_single_value[$id])){ ?>

                                                    <td data-title="Priority"><input type='number' step="0.1" min="0" max="1" name='_xmg_priority_single_value[<?php echo $id; ?>]' value="<?php echo $priority_single_value[$id]; ?>"></td>
                                                
                                                <?php } else { ?>

                                                    <td data-title="Priority"><input type='number' step="0.1" min="0" max="1" name='_xmg_priority_single_value[<?php echo $id; ?>]' placeholder="<?php echo $priority[get_post_type()]; ?>"></td>

                                                <?php } ?>
                                                <?php if(isset($changefreq_single_value[$id]) && !empty($priority_single_value[$id])){ ?>

                                                    <td data-title="Changefreq"><input class="autocomplete" type='text' name='_xmg_changefreq_single_value[<?php echo $id; ?>]' value="<?php echo $changefreq_single_value[$id]; ?>"></td>

                                                <?php } else { ?>

                                                    <td data-title="Changefreq"><input class="autocomplete" type='text' name='_xmg_changefreq_single_value[<?php echo $id; ?>]' placeholder="<?php echo $changefreq[get_post_type()]; ?>"></td>
                                                <?php } ?>
                                            </tr>

                                        <?php } else { ?>
                                            <tr>
                                                <td data-title="Hide"><input id="id_excluded[<?php echo $id; ?>]" type='checkbox' name='_xmg_id_excluded[<?php echo $id; ?>]' value='1'><label for='id_excluded[<?php echo $id; ?>]'></label></td>
                                                <td data-title="ID"><?php echo $id; ?></td>
                                                <td data-title="Title"><a target="_blank" href="<?php echo $permalink; ?>"><?php echo $title; ?></a></td>

                                                <?php if(isset($priority_single_value[$id]) && !empty($priority_single_value[$id])){ ?>
                                                    <td data-title="Priority"><input type='number' step="0.1" min="0" max="1" name='_xmg_priority_single_value[<?php echo $id; ?>]' value="<?php echo $priority_single_value[$id]; ?>"></td>

                                                <?php } else { ?>

                                                    <td data-title="Priority"><input type='number' step="0.1" min="0" max="1" name='_xmg_priority_single_value[<?php echo $id; ?>]' placeholder="<?php echo $priority[get_post_type()]; ?>"></td>

                                                <?php } ?>
                                                <?php if(isset($changefreq_single_value[$id]) && !empty($changefreq_single_value[$id])){ ?>

                                                    <td data-title="Changefreq"><input class="autocomplete" type='text' name='_xmg_changefreq_single_value[<?php echo $id; ?>]' value="<?php echo $changefreq_single_value[$id]; ?>"></td>

                                                <?php } else { ?>

                                                    <td data-title="Changefreq"><input class="autocomplete" type='text' name='_xmg_changefreq_single_value[<?php echo $id; ?>]' placeholder="<?php echo $changefreq[get_post_type()]; ?>"></td>
                                                <?php } ?>
                                            </tr>
                                    
                                    <?php 

                                        }

                                    } ?>

                                </tbody>

                            </table>

                        </div>
                        <input type="submit" name="submit" class="button button-primary" value="<?php _e('Create the Sitemap','xml-multilanguage-sitemap-generator'); ?>">
                    </div>
                </div>
                <?php
            }

            // Restore original Post Data
            wp_reset_postdata();
            $counterActive++;
        }
        echo '</div>';
    }
}

// =======================================================================// 
// ! SIDEBAR SPONSOR ASIDE                                                      ! //        
// =======================================================================//  

function _xmg_credits(){ ?>
<div class="sponsor xmg_section">
    <div class="xmg_section_title">
        <h3 style="margin: 0">XML MULTILANGUAGE SITEMAP GENERATOR</h3>
    </div>
    <div class="xmg_section_content">
        <p><?php _e("Hi, i'm Marco! Nice to meet you. I've made this plugin for help every site owner to manage his multilanguage sitemap. I hope it will help you. ","xml-multilanguage-sitemap-generator") ?></p>
        <p><?php _e("If yes, please consider leave a vote on WordPress clickin on the stars below or offer me a coffee clicking on this <a target='_blank' href='https://www.paypal.me/gianemi2'>PayPal link</a>.", "xml-multilanguage-sitemap-generator"); ?></p>
        <p><?php _e("I'm proud that you're onboard!", 'xml-multilanguage-sitemap-generator'); ?></p>
        <a class="vote-me" href="https://wordpress.org/plugins/xml-multilanguage-sitemap-generator/" target="_blank"><p><b><?php _e('Vote me on WordPress', 'xml-multilanguage-sitemap-generator'); ?> </b><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span></p></a>
        <h4><?php _e("Useful links:", 'xml-multilanguage-sitemap-generator'); ?></h4>
        <p><a href="https://wordpress.org/plugins/xml-multilanguage-sitemap-generator/#faq" target="_blank">FAQ</a></p>
        <p><a href="https://wordpress.org/support/plugin/xml-multilanguage-sitemap-generator" target="_blank"><?php _e('Support', 'xml-multilanguage-sitemap-generator'); ?></a></p>
        <p><a href="mailto:info@marcogiannini.net"><?php _e('Send me a mail (use sparingly)', 'xml-multilanguage-sitemap-generator'); ?></a></p>
    </div>
    <div class="xmg_section_footer">
        <?php printf( __( 'Made with %s in Italy.', 'xml-multilanguage-sitemap-generator' ), '<span class="dashicons dashicons-heart red"></span>' ); ?>
    </div>
</div>
<?php }

/*
*
* Debug Infos
*
*/
function _xmg_debug_column() { ?>
    <?php if(get_option('_xmg_is_debug')) : ?>
    <div class="sponsor xmg_section">
        <?php 
        if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
            $active_plugin = 'Sitepress';
            $languages_obj = icl_get_languages('skip_missing=0');
            foreach ($languages_obj as $language_obj) {
                $lang_code = $language_obj['language_code'];
                if(in_array( $lang_code, apply_filters( 'wpml_setting', array(), 'hidden_languages' ) )){
                    unset($languages_obj[$lang_code]);
                }
            }
            foreach ($languages_obj as $language_obj) {
                $languages[] = $language_obj['language_code'];
            }
        } elseif (is_plugin_active( 'polylang/polylang.php' )) {
            $active_plugin = 'Polylang';
            $languages = pll_languages_list();
        } else {
            $active_plugin = 'No multilanguage plugin';
            $languages = false;
        } 
        if(is_array($languages) && !empty($languages)){
            $languages = implode(',', $languages);        
        }?>
        <div class="xmg_section_title"><p>Debugger</p></div>
        <div class="debugger">
            <ul>
                <li><b>WordPress info</b></li>
                <li>Versione di WordPress: <u><?php echo get_bloginfo( 'version' ); ?></u></li>
                <li>Dominio: <u><?php echo get_site_url(); ?></u></li>
                <li><b>Plugin info</b></li>
                <li>Versione del plugin: <u><?php echo get_option('_xmg_version'); ?></u></li>
                <li><b>Server Info</b></li>
                <li>Versione di PHP: <u><?php echo PHP_VERSION; ?></u></li>
                <li><b>Premium info</b></li>
                <li>Premium status: <u><?php echo _xmg_check_license(); ?></u></li>
                <li>Premium key: <u><?php echo get_option('_xmg_premium_license_key'); ?></u></li>
                <li><b>Multilanguage info</b></li>
                <li>Plugin attivi: <u><?php print_r($active_plugin); ?></u></li>
                <li>Lingue attive: <u><?php print_r($languages); ?></u></li>
            </ul>
        </div>
    </div>
    <?php 
    endif;
}