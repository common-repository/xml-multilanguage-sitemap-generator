<?php
global $sitepress;
$current_language = $sitepress->get_current_language();
$default_language = $sitepress->get_default_language();
$sitepress->switch_lang($default_language);
$query = new WP_Query( array( 'post_type' => $post_type, 'post__not_in' => $idToExclude, 'posts_per_page' => -1) );
if($poststypetoinclude){
    while($query->have_posts()) {
        $query->the_post();
        //prendo l'ID del post corrente e il permalink.
        $postid = get_the_ID();
        $loc_ar = get_permalink();
        $langs = '';
        $languages_arg = array();
        //Imposto dei valori al momento di DEFAULT.
        $lastmod_ar = get_the_modified_date('Y-m-d');
        $changefreq_ar = _xmg_get_optional_val($postid, 'changefreq');
        $priority_ar = _xmg_get_optional_val($postid, 'priority');
        //Creo le informazioni della sitemap quali: URL - LOC - LASTMOD - CHANGEFREQ - PRIORITY da compilare successivamente con le informazione che riesco a ricavare tramite il loop
        //Controllo quale plugin multilingua è attivo
        //MO INIZIANO I CAZZI
        //START WPML
        //Prendo tutte le lingue esistenti nel sito grazie a 'skip_missing=0'
        $languages = icl_get_languages('skip_missing=0');
        $languages_arg = array();
        //Se trovo una lingua alternativa inizio la festa!
        if(1 < count($languages)){
            foreach($languages as $l){  
                if(!in_array( $l['language_code'], apply_filters( 'wpml_setting', array(), 'hidden_languages' ) )){
                    //Se la lingua del post oggetto della query è quella di default, imposto il suo permalink nel campo LOC della sitemap
                    if($l['language_code'] == $default_language){
                        $icl_object_id = apply_filters( 'wpml_object_id', $postid, $post_type, false, $l['language_code'] );
                        //$icl_object_id = icl_object_id($postid, 'page', false, $l['language_code']);
                        $permalink = get_permalink($icl_object_id);
                        $href = apply_filters( 'wpml_permalink', $permalink, $default_language );
                        //$url_text = $objDom->createTextNode($href);
                        /*$loc->appendChild($url_text);*/
                        //Altrimenti costruisco un array con come chiave l'id del post oggetto tradotto e come valore il codice della lingua del post. Ad esempio $langs[325] => en,
                    } else {
                        $icl_object_id = icl_object_id($postid, 'page', false, $l['language_code']);
                        if(!$l['active'] && !is_null($icl_object_id)){  
                            //Salvo in un array il LANGUAGE CODE. Esempio EN, IT, ES eccetera.
                            $langs[$icl_object_id] = $l['language_code'];
                        }
                    }
                }
            } 
            //Controllo se $langs è un array. nel caso non lo fosse vuol dire che il post corrente non ha alcuna traduzione e quindi posso cercare il prossimo post
            if(is_array($langs)){ 
                foreach ($langs as $id => $lang) {
                    $sitepress->switch_lang($lang);
                    //Con get_permalink() non posso prendere l'url del post tradotto ma qualunque ID gli dia lui mi darà sempre l'url della lingua corrente al momento della stampa della sitemap.
                    $permalink = get_permalink($id);
                    //Grazie ad apply_filters wpml permalink posso prendere il permalink tradotto nella lingua corrente. 
                    $href = apply_filters( 'wpml_permalink', $permalink, $lang );
                    //Inserisco i valori appena ricavati all'interno delle informazioni per la lingua alternativa
                    $languages_arg[] = array(
                        'hreflang'  => $lang,
                        'href'      => $href,
                    );
                    $default = $sitepress->get_default_language();
                    $sitepress->switch_lang($default);
                };
            };
        }
        $post_map = array(
            'permalink'     => get_permalink(),
            'changefreq'    => $changefreq_ar,
            'priority'      => $priority_ar,
            'lastmod'       => $lastmod_ar,
            'languages'     => $languages_arg
        );
        $posts_args[$post_type][] = $post_map;
        $sitepress->switch_lang($current_language);
        $langs = array();
    };
}