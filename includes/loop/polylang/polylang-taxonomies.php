<?php
$default_polylang_lang = pll_default_language();
$all_languages = pll_languages_list();

$terms = get_terms(array(
    'taxonomy'      =>  $taxonomy,
    'hide_empty'    =>  true,
    'lang'          =>  $default_polylang_lang
));

$taxonomy_empty = true;

if(!empty($terms)){
    $taxonomy_empty = false;
    foreach ($terms as $term) {
        $languages_arg = array();
        $term_id = $term->term_id;
        $default_lang_term_id = pll_get_term($term_id, $default_polylang_lang);
        $langs = '';
        $excluded_default_langs = $all_languages;
        if (($key = array_search($default_polylang_lang, $excluded_default_langs)) !== false) {
            unset($excluded_default_langs[$key]);
        }
        if(is_array($excluded_default_langs)){
            $lang_url = get_term_link($term_id);
            foreach($excluded_default_langs as $excluded_default_lang){
                //Se nell'array classes dell'array $lang c'è il valore lang-item-first vuol dire che le informazioni dell'array corrente sono quelle della lingua di default impostata su Polylang
                //Nel caso fosse la lingua principale imposto la url all'interno di LOC 
                //In caso contrario prendo le varie voci che mi servono e le inserisco all'interno delle informazioni per la lingua alternativa
                $lang_code = $excluded_default_lang;
                $current_translated_id = pll_get_term($term_id, $excluded_default_lang);
                if($current_translated_id){
                    $current_translated_id_count = get_term($current_translated_id)->count;
                    if($current_translated_id_count != 0){
                        $alternate_permalink = get_term_link($current_translated_id);
                        if(!is_wp_error($alternate_permalink)){
                            $languages_arg[] = array(
                                'hreflang'  => $lang_code,
                                'href'      => $alternate_permalink,
                            );
                        }
                    }
                }
            };
        }
        $changefreq_ar = 'always';
        $priority_ar = 1;
        $term_map = array(
            'permalink'     =>      get_term_link( $default_lang_term_id ),
            'changefreq'    =>      $changefreq_ar,
            'priority'      =>      $priority_ar,
            'languages'     =>      $languages_arg,
        );
        $tax_args[$taxonomy][] = $term_map;
    }
}