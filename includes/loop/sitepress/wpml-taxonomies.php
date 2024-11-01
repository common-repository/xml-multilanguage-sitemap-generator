<?php
global $sitepress;
$current_language = $sitepress->get_current_language();
$default_language = $sitepress->get_default_language();
$sitepress->switch_lang($default_language);

$terms = get_terms(array(
    'taxonomy'      =>  $taxonomy,
    'hide_empty'    =>  true,
));

$taxonomy_empty = true;
$languages_arg = array();
if(!empty($terms)){
    $taxonomy_empty = false;
    foreach ($terms as $term) {
        $languages = icl_get_languages('skip_missing=0');
        if(1 < count($languages)){
            foreach ($languages as $language) {
                if(!in_array( $language['language_code'], apply_filters( 'wpml_setting', array(), 'hidden_languages' ) )){
                    if($language['language_code'] == $default_language){
                        $translated_id = apply_filters( 'wpml_object_id', $term->term_taxonomy_id, $taxonomy, false, $language['language_code']  );
                        $default_permalink = get_term_link($translated_id);
                    } else {
                        $translated_id = apply_filters( 'wpml_object_id', $term->term_taxonomy_id, $taxonomy, false, $language['language_code']  );
                        if(!is_wp_error(get_term($translated_id))){
                            $current_translated_id_count = get_term($translated_id)->count;
                            if($current_translated_id_count != 0){
                                $alternate_permalink = get_term_link($translated_id);
                                if(!is_wp_error($alternate_permalink)){
                                    $languages_arg[] = array(
                                        'hreflang'  => $language['language_code'],
                                        'href'      => $alternate_permalink,
                                    );
                                }
                            }
                        }
                    }
                }
            }
        }
        $changefreq_ar = 'always';
        $priority_ar = 1;
        $term_map = array(
            'permalink'     =>      get_category_link( $term->term_id ),
            'changefreq'    =>      $changefreq_ar,
            'priority'      =>      $priority_ar,
            'languages'     =>      $languages_arg,
        );
        $tax_args[$taxonomy][] = $term_map;
    }
}
$sitepress->switch_lang($current_language);
$langs = array();