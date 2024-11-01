<?php
$query = new WP_Query (array( 'post_type' => $post_type, 'post__not_in' => $idToExclude, 'posts_per_page' => -1 ) );
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
        //Creo le informazioni della sitemap quali: URL - LOC - LASTMOD - CHANGEFREQ - PRIORITY da compilare successivamente con le informazione che riesco a ricavare tramite il loop
        $post_map = array(
            'permalink'     => $loc_ar,
            'changefreq'    => $changefreq_ar,
            'priority'      => $priority_ar,
            'lastmod'       => $lastmod_ar
        );
        $posts_args[$post_type][] = $post_map;
        //Resetto l'array creato prima delle lingue
        $langs = array();
    };
}