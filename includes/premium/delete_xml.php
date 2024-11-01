<?php 

function _xmg_scan_root(){

	echo '<div class="sponsor xmg_section"><div class="xmg_section_title"><p>Sitemap remover</p></div>';

	if(_xmg_check_license()){

		$root = $_SERVER['DOCUMENT_ROOT'].'/';

		if ($_SERVER["REQUEST_METHOD"] == "POST") {

			$delete_files = $_POST["delete_file"];

			if(isset($delete_files)){

				foreach ($delete_files as $delete_file => $delete_value) {

					unlink($root.$delete_file);

				}

			} ?>

			<script type="text/javascript">	
				window.location.reload();
			</script>

			<?php

		}

	    $files = glob($root.'*.xml');

	    $active_sitemap_name = _xmg_get_sitemap_name();

		$active_custom_posts_type = _xmg_get_included_pt();

		$active_sitemaps = array();

		if($active_sitemap_name){

			$active_sitemaps[] = $active_sitemap_name.'.xml';

		} else {

			$active_sitemaps[] = 'xml_mg_sitemap-index.xml';

		}

		if(isset($active_custom_posts_type)){

			foreach ($active_custom_posts_type as $active_custom_post_type => $value) {
				
				$active_sitemaps[] = $active_custom_post_type.'.xml';

			}

		}

		?>

	    <form action="" method="POST">

		    <div class="table-responsive-vertical shadow-z-1">
		        <table id="table" class="table table-hover table-striped">
		            <thead>
		                <tr>
		                    <th><?php _e('Sitemap name','xml-multilanguage-sitemap-generator'); ?></th>
		                    <th><?php _e('Delete sitemap','xml-multilanguage-sitemap-generator'); ?></th>
		                </tr>
		            </thead>
		            <tbody> <?php

		                if(isset($files)){

						    foreach ($files as $file) {
						    	
						    	$file = str_replace($root, '', $file);

						    	$filepath = get_site_url().'/'. $file; ?>

			                	<tr>
			                        <td data-title="Nome Sitemap"><a target="_blank" href="<?php echo $filepath; ?>"><?php echo $file; ?></a><?php if(in_array($file,$active_sitemaps)) {?> - <span class="warning"><?php _e('Active sitemap','xml-multilanguage-sitemap-generator');  ?></span><?php } ?></td>
			                        <td data-title="Cancella Sitemap"><input type='checkbox' name='delete_file[<?php echo $file; ?>]' <?php if(in_array($file, $active_sitemaps)) echo 'disabled="disabled"'; ?>></td>
			                    </tr> 

		                    <?php

		                    }

		                } ?>

					</tbody>

				</table>

			</div> 

			<input type="submit" class="button delete" value="<?php _e('Delete','xml-multilanguage-sitemap-generator'); ?>">

		</form>

	<?php

	}

	echo '</div>';

}