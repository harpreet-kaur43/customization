<?php
	add_shortcode('dynamic_content','dynamic_content_callback');
	function dynamic_content_callback($atts, $content = null){
		 // Attributes
        $atts =  shortcode_atts(
            array(
                'content'      => '',
                'template'      => '',
            ), $atts , 'dynamic_content' );

        $output = "";
        if($atts['content'] == 'jobs'){
        	switch_to_blog(1);
        	$posts = do_shortcode('[posts post_type="job"  api="yes"]');
        	//$output .= do_shortcode('[posts post_type="job" posts_per_page="10" pagination="yes" template="'.$atts['template'].'"]');
        	//$output .= aione_pagination(json_decode($posts));
        	restore_current_blog();
        	
        	foreach (json_decode($posts) as $key => $post_array) {
        		//echo "<pre>";print_r($post_array);echo "</pre>";
        		//$output .= do_shortcode('[post post_id = '.$post->ID.' template="'.$atts['template'].'"]');
        		$output .= apply_template($post_array,$atts['template']);
        	}
        	
        }
        return $output;
	}

	function apply_template($post_object,$template ) {
		
		$output = "";
		/*if(empty($post)){
			return;
		}*/
		//global $post;
		//global $wp_query;
		//$wp_query->setup_postdata( $post );
		//$GLOBALS['post'] =& $post_object ;
		//setup_postdata( $post_object );
		//setup_postdata( $post ); 

		/*$post_id = -99; // negative ID, to avoid clash with a valid post
		$post = new stdClass();
		$post->ID = $post_id;
		$post->post_title = 'Some title or other';*/
		$wp_post = new WP_Post( $post_object );
		//wp_cache_add( $post_id, $wp_post, 'posts' );
		global $wp, $wp_query;
		$wp_query->post = $wp_post;
		$wp_query->posts = array( $wp_post );
		$wp_query->queried_object = $wp_post;
		$wp_query->queried_object_id = $post_id;
		$wp_query->is_404=false;
		$wp_query->is_page=true;
		$GLOBALS['wp_query'] = $wp_query;
		$GLOBALS['post'] = $post_object ;
		$wp->register_globals();

		echo "##################";
		echo "<pre>";print_r(do_shortcode('[title]'));echo "</pre>";
		//echo "<pre>";print_r($post_object);echo "</pre>";
		
		$is_template = false;

		if( !empty( $template ) ) {
			$aione_templates = @get_option( 'aione-templates' );
			$aione_template = @$aione_templates[$template]['content'];
			if( !empty( $aione_template ) ) {
				$is_template = true;
			}
		}

		if( $is_template ) {
			$output .= '<div class="aione-post '.$template.' ">';
			$output .= do_shortcode( $aione_template );
			$output .= '</div>';
		} else{
			$output .= '<h5 class="font-size-16 align-center">Template does not exist</h5>';
		}
		

		wp_reset_postdata( );
		
		return $output;
	} 
?>