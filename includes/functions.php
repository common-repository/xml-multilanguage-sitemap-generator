<?php
require(_XMG_PLUGIN_PATH . '/admin/include-admin-style.php');
require(_XMG_PLUGIN_PATH . '/includes/premium/define-premium.php');

$posts_type_useless = array('revision','nav_menu_item','custom_css','customize_changeset','acf-field-group','acf-field','wpcf7_contact_form','attachment','polylang_mo');
add_option('_xmg_useless_posts', $posts_type_useless);

/**
 * Create a folder in the root of the website named xml_sitemap
 * 
 *  @todo let the user decide if he wants it or not
 */
if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/xml-sitemap/')) {
    mkdir($_SERVER['DOCUMENT_ROOT'] . '/xml-sitemap/', 0755, true);
}

/**
 * Create a folder in the root of the website named xml_sitemap
 * 
 *  @param      Array || String || Int || Null
 *  @return     Easy readable print of param.
 */
function _xmg_debug($var){
    echo '<pre>';
    print_r($var);
    echo '</pre>';
}

/**
 * If isset submit on POST refresh current page. Use it when you want to refresh current page.
 * 
 */
function refresh_on_submit(){
    if(isset($_POST['submit'])) {
        echo "<meta http-equiv='refresh' content='0'>";
    }
}

/**
 * Ajax function for activate ordebug mode. 
 * 
 */
add_action( 'wp_ajax_toggle-debug-mode', 'wpse_toggle_debug_mode' );
add_action( 'wp_ajax_nopriv_toggle-debug-mode', 'wpse_toggle_debug_mode' );
function wpse_toggle_debug_mode() {
    $option = '_xmg_is_debug';
    if(get_option($option) == true){
        update_option( $option, false );
        die(
            json_encode(
                array(
                    'success' => 'true',
                    'message' => 'DISATTIVATA'
                )
            )
        );
    } else {
        update_option( $option, true );
        die(
            json_encode(
                array(
                    'success' => 'true',
                    'message' => 'ATTIVATA'
                )
            )
        );
    }
}

/**
 * Get saved sitemap name.
 * 
 * If sitemap name is not defined use default name, xml_mg_sitemap.
 * @return string
 */
function _xmg_get_sitemap_name() {
    $sitemap_name = get_option( '_xmg_sitemap_name');

    if(!$sitemap_name){
        $sitemap_name = 'xml_mg_sitemap';
    }
    return $sitemap_name;
}

/**
 * Get all excluded ids from sitemap. 
 *
 * @return array
 *
 */
function _xmg_get_excluded_id(){
    return get_option('_xmg_id_excluded');
}

function _xmg_get_included_pt(){
    $posts_type = get_option('_xmg_post_type_to_include');
    if(!$posts_type || !isset($posts_type)){
        $posts_type = array('page'=>1);
    }
    return $posts_type;
}


function _xmg_get_optional_val($postid, $value = false){
    if($value == 'priority'){
        $optional_single = get_option( '_xmg_priority_single_value' );
        $optional_general = get_option( '_xmg_priority_value' );
    } elseif ($value == 'changefreq') {
        $optional_single = get_option( '_xmg_changefreq_single_value' );
        $optional_general = get_option( '_xmg_changefreq_value' );
    }

    if(!empty($optional_single[$postid])){
        $value = $optional_single[$postid];
    } elseif (!empty($optional_general[get_post_type($postid)])){
        $value = $optional_general[get_post_type($postid)];
    } else {
        $value = 'weekly';
    }
    return $value;
}

/**
 * Create default sitemap structure. Nothing to touch over there.
 *
 */
function _xmg_structure_sitemap_pt($sitemap_name){
    $objDom = new DOMDocument('1.0');
    $objDom->encoding = 'UTF-8';
    $objDom->formatOutput = true;
    $objDom->preserveWhiteSpace = false;
    $root = $objDom->createElement("urlset");
    $objDom->appendChild($root);
    $root_attr = $objDom->createAttribute("xmlns");
    $another_root_attr = $objDom->createAttribute('xmlns:xhtml');
    $another_root_attr_img = $objDom->createAttribute('xmlns:image');
    $root->appendChild($root_attr);
    $root->appendChild($another_root_attr);
    $root->appendChild($another_root_attr_img);
    $root_attr_text = $objDom->createTextNode("http://www.sitemaps.org/schemas/sitemap/0.9");
    $another_root_attr_text = $objDom->createTextNode("http://www.w3.org/1999/xhtml");
    $another_root_attr_text_img = $objDom->createTextNode("http://www.google.com/schemas/sitemap-image/1.1");
    $root_attr->appendChild($root_attr_text);
    $another_root_attr->appendChild($another_root_attr_text);
    $another_root_attr_img->appendChild($another_root_attr_text_img);

    return array('objDom' => $objDom,'root' => $root);
};

/**
 * Create default sitemap structure. Nothing to touch over there.
 *
 */
function _xmg_structure_index($sitemap_array, $sitemap_name){
    $doc = new DOMDocument( );
    if(file_exists($sitemap_name)){
        $doc->load($sitemap_name);
    }
    $lastmod_ar = date('Y-m-d');
    $ele = $doc->createElement( 'Root' );
    $ele = $doc->createElement( 'Root' );
    $objDom = new DOMDocument('1.0');
    $objDom->encoding = 'UTF-8';
    $objDom->formatOutput = true;
    $objDom->preserveWhiteSpace = false;
    $root = $objDom->createElement("sitemapindex");
    $objDom->appendChild($root);
    $root_attr = $objDom->createAttribute("xmlns");
    $root->appendChild($root_attr);
    $root_attr_text = $objDom->createTextNode("http://www.sitemaps.org/schemas/sitemap/0.9");
    $root_attr->appendChild($root_attr_text);
    foreach ($sitemap_array as $the_sitemap_url) {
        $url = $objDom->createElement("sitemap");
        $root->appendChild($url);
        $loc = $objDom->createElement("loc");
        $url->appendChild($loc);
        $url_text = $objDom->createTextNode($the_sitemap_url);
        $loc->appendChild($url_text);
        $lastmod = $objDom->createElement("lastmod");
        $url->appendChild($lastmod);
        $lastmod_text = $objDom->createTextNode($lastmod_ar);
        $lastmod->appendChild($lastmod_text);
    }
    $objDom->save($_SERVER['DOCUMENT_ROOT'] . $sitemap_name);
}

/**
 * 
 * Build the sitemap for every post.
 * It needs a lot of arguments. With this function you can create a sitemap for every multilanguage plugin. Simply recreate an array equal to the one I pass as $posts_map.
 * @param     string    sitemap_structure
 * @param     string    sitemap_structure
 * @param     array     $posts_map                     
 *            @var      [permalink]     string     required
 *            @var      [changefreq]    string     required
 *            @var      [priority]      int        required
 *            @var      [lastmod]       date       required
 *            @var      [languages]     array      optional
 *                      @var    [hreflang]      string      required
 *                      @var    [href]          string      required
 * @param     string    post type name
 * @param     boolean   Is a multilanguage sitemap?
 */
function _xmg_build_link_structure($objDom, $root, $posts_map, $post_type, $multilanguage){
    if(isset($posts_map[$post_type]) && is_array($posts_map[$post_type]) && !empty(array_filter($posts_map[$post_type]))){
        foreach ($posts_map[$post_type] as $post_map) {
            if(!is_wp_error($post_map['permalink'])){

                $url = $objDom->createElement("url");
                $root->appendChild($url);
                
                $loc = $objDom->createElement("loc");
                $url->appendChild($loc);
                $url_text = $objDom->createTextNode($post_map['permalink']);
                $loc->appendChild($url_text);
                if($multilanguage){
                    if(isset($post_map['languages'])){
                        foreach ($post_map['languages'] as $language) {
                            $node = $objDom->createElement('xhtml:link');
                            $node->setAttribute('rel', 'alternate');
                            $node->setAttribute('hreflang', $language['hreflang']);
                            $node->setAttribute('href', $language['href']);
                            $loc->parentNode->appendChild($node);
                        }
                    }
                }

                if(isset($post_map['last_mod'])){
                    $lastmod = $objDom->createElement("lastmod");
                    $url->appendChild($lastmod);
                    $lastmod_text = $objDom->createTextNode($post_map['lastmod']);
                    $lastmod->appendChild($lastmod_text);
                }

                if(isset($post_map['changefreq'])){
                    $changefreq = $objDom->createElement("changefreq");
                    $url->appendChild($changefreq);
                    $changefreq_text = $objDom->createTextNode($post_map['changefreq']);
                    $changefreq->appendChild($changefreq_text);
                }

                if(isset($post_map['priority'])){
                    $priority = $objDom->createElement("priority");
                    $url->appendChild($priority);
                    $priority_text = $objDom->createTextNode($post_map['priority']);
                    $priority->appendChild($priority_text);
                }
            }
        }
        return true;
    } else {
        return false;
    }
}

function _xmg_run(){
    /**
     * Get all the basics information saved by the user. 
     *
     */
    $sitemap_name = '/xml-sitemap/'._xmg_get_sitemap_name().'.xml';
    $idToExclude = _xmg_get_excluded_id();
    if($idToExclude){
        $idToExclude = array_keys($idToExclude);
    }
    $poststypetoinclude = array_keys(_xmg_get_included_pt());

    //$priority_value = get_option('priority_value');
    //$priority_single_value = get_option('priority_single_value');
    //$changefreq_value = get_option('changefreq_value');
    //$changefreq_single_value = get_option('changefreq_single_value');
    $lastmod_ar = date('Y-m-d');
    /**
     * 
     * Creo la sitemap per ogni post type.
     *
     */
    foreach ($poststypetoinclude as $post_type) {
        //Defined in includes/functions.php
        $sitemap_structure = _xmg_structure_sitemap_pt($sitemap_name);
        $posts_args = array();
        $objDom = $sitemap_structure['objDom'];
        $root = $sitemap_structure['root'];
        //*********Fine della creazione della sitemap***********//
        //Controllo se è attivo WPML o POLYLANG. 
        //Grazie a post__not_in riesco ad escludere gli ID scelti nelle opzioni dall'utente.
        //Se è attivo WPML cambio la lingua globale nella lingua di Default del sito per impostare la variabile $query
        if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
            include('loop/sitepress/wpml-post-type.php');
            $multilanguage = true;
        } 
        elseif ( is_plugin_active( 'polylang/polylang.php' ) ){
            include('loop/polylang/polylang-post-type.php');
            $multilanguage = true;
        }
        else {
            include('loop/notranslate/notranslate-post-type.php');
            $multilanguage = false;
        }
        if(_xmg_build_link_structure($objDom, $root, $posts_args, $post_type, $multilanguage)){
            $sitemap_post_type = $_SERVER['DOCUMENT_ROOT'].'/xml-sitemap/'.$post_type.'.xml';
            $sitemap_domain_url = get_site_url().'/xml-sitemap/'.$post_type.'.xml';
            $sitemap_array[] = $sitemap_domain_url;
            $objDom->save($sitemap_post_type);
        }
    }
    /**
     *  
     * Taxonomies Loop
     * 
     *
     */
    $args = array(
        'public'    =>      1,
    );
    $taxonomies = get_taxonomies($args);
    if(!empty($taxonomies)){
        foreach ($taxonomies as $taxonomy) {
            $sitemap_structure = _xmg_structure_sitemap_pt($sitemap_name);
            $objDom = $sitemap_structure['objDom'];
            $root = $sitemap_structure['root'];

            if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
                include('loop/sitepress/wpml-taxonomies.php');
                $multilanguage = true;
            } 
            elseif ( is_plugin_active( 'polylang/polylang.php' ) ){
                include('loop/polylang/polylang-taxonomies.php');
                $multilanguage = true;
            }
            else {
                include('loop/notranslate/notranslate-taxonomies.php');
                $multilanguage = false;
            }
            if(!$taxonomy_empty){
                _xmg_build_link_structure($objDom, $root, $tax_args, $taxonomy, $multilanguage);
                $sitemap_post_type = $_SERVER['DOCUMENT_ROOT'].'/xml-sitemap/'.$taxonomy.'.xml';
                $sitemap_domain_url = get_site_url().'/xml-sitemap/'.$taxonomy.'.xml';
                $sitemap_array[] = $sitemap_domain_url;
                $objDom->save($sitemap_post_type);
            }
        }
    }
    
    //Defined in includes/functions.php
    _xmg_structure_index($sitemap_array, $sitemap_name);
    wp_reset_query();
    if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
        $sitepress->switch_lang($current_language);
    }
}