<?php

$default_polylang_lang = pll_default_language();
$all_languages = pll_languages_list();
$query = new WP_Query( array( 'post_type' => $post_type, 'post__not_in' => $idToExclude, 'lang' => $default_polylang_lang, 'posts_per_page' => -1 ) );
if($poststypetoinclude){
    while($query->have_posts()) {
        $query->the_post();
        //prendo l'ID del post corrente e il permalink.
        $postid = get_the_ID();
        $loc_ar = get_permalink();
        $langs = '';
        //Imposto dei valori al momento di DEFAULT.
        $changefreq_ar = _xmg_get_optional_val($postid, 'changefreq');
        $priority_ar = _xmg_get_optional_val($postid, 'priority');
        $lastmod_ar = get_the_modified_date('Y-m-d');
        $languages_arg = array();
        $excluded_default_langs = $all_languages;
        if (($key = array_search($default_polylang_lang, $excluded_default_langs)) !== false) {
            unset($excluded_default_langs[$key]);
        }
        //PLL_THE_LANGUAGES mi stampa un array con molte informazioni utili riguardo all'ID consegnato. 
        //Mi permette di stampare tutti gli id delle traduzioni del post corrente.
        if(is_array($excluded_default_langs)){
            $lang_url = get_permalink($postid);
            foreach($excluded_default_langs as $excluded_default_lang){
                //Se nell'array classes dell'array $lang c'è il valore lang-item-first vuol dire che le informazioni dell'array corrente sono quelle della lingua di default impostata su Polylang
                //Nel caso fosse la lingua principale imposto la url all'interno di LOC 
                //In caso contrario prendo le varie voci che mi servono e le inserisco all'interno delle informazioni per la lingua alternativa
                $lang_code = $excluded_default_lang;
                $current_translated_id = pll_get_post($postid, $excluded_default_lang);
                if($current_translated_id){
                    $href = get_permalink($current_translated_id);
                    
                    $languages_arg[] = array(
                        'hreflang'  => $lang_code,
                        'href'      => $href,
                    );
                }
            };
        }
        $post_map = array(
            'permalink'     => $lang_url,
            'changefreq'    => $changefreq_ar,
            'priority'      => $priority_ar,
            'lastmod'       => $lastmod_ar,
            'languages'     => $languages_arg
        );
        $posts_args[$post_type][] = $post_map;
        $langs = array();
    };
}