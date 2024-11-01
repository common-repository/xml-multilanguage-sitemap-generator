<?php
$terms = get_terms(array(
    'taxonomy'      =>  $taxonomy,
    'hide_empty'    =>  true,
));

$taxonomy_empty = true;
if(!empty($terms)){
	$taxonomy_empty = false;
	foreach ($terms as $term) {
		
	    $changefreq_ar = 'always';
	    $priority_ar = 1;

	    $term_map = array(
	    	'permalink'		=>		get_category_link( $term->term_id ),
	    	'changefreq'	=>		$changefreq_ar,
	    	'priority'		=>		$priority_ar,
	    );
	    $posts_args[$taxonomy][] = $term_map;
	    $langs = array();
	}
}