<?php
function my__enqueue() {
	wp_enqueue_script( 'my__load__more', get_template_directory_uri() . '/assets/js/load_more.js', array( 'jquery' ), '1.0.0', true );

	global $wp_query;

	$que = $wp_query->query_vars;

	if (is_category())
		$varcat   = isset( $que[ 'category_name' ] ) ? $que[ 'category_name' ] : '';
	else if (is_author())
		$varaut   = isset( $que[ 'author_name' ] ) ? $que[ 'author_name' ] : '';
	else if (is_tag())
		$vartag   = isset( $que[ 'tag' ] ) ? $que[ 'tag' ] : '';
	else if (is_date()) {
		$varyear  = isset( $que[ 'year' ] ) ? $que[ 'year' ] : '';
		$varmon   = isset( $que[ 'monthnum' ] ) ? $que[ 'monthnum' ] : '';
		$varday   = isset( $que[ 'day' ] ) ? $que[ 'day' ] : '';
	}

	$ajaxurl = admin_url( 'admin-ajax.php' );

	wp_localize_script( 'my__load__more', 'my__params', array(
		'ajaxurl' => $ajaxurl,
		'varcat'  => $varcat,
		'varaut'  => $varaut,
		'vartag'  => $vartag,
		'varyear' => $varyear,
		'varmon'  => $varmon,
		'varday'  => $varday,
	) );
}
add_action( 'wp_enqueue_scripts', 'my__enqueue' );
function my__load__more() {
	$count = get_option('posts_per_page');
	$add = $_POST['addNum'];
	$getChoose = $_POST['getChoose'];
	$count = $count + $add;
	$read = 1;

	$args__load = array(
		'posts_per_page' => -1,
		'post_type'      => 'post',
		'post_status'    => 'publish',
	);

	if( !empty( $_POST[ 'varcat' ] ) ) $args__load[ 'category_name' ] = $_POST[ 'varcat' ];
	else if( !empty( $_POST[ 'varaut' ] ) ) $args__load[ 'author_name' ] = $_POST[ 'varaut' ];
	else if( !empty( $_POST[ 'vartag' ] ) ) $args__load[ 'tag' ] = $_POST[ 'vartag' ];
	else if( !empty( $_POST[ 'varyear' ] ) ) {
		$args__load[ 'year' ] = $_POST[ 'varyear' ];
		if( !empty( $_POST[ 'varmon' ] ) ) {
			$args__load[ 'monthnum' ] = $_POST[ 'varmon' ];
			if( !empty( $_POST[ 'varday' ] ) ) {
				$args__load[ 'day' ] = $_POST[ 'varday' ];	
			}
		}
	}

	$articles = new WP_Query( $args__load );
	$getPosts = array();
	if( $articles->have_posts() ) {
		while( $articles->have_posts() ) {
			$articles->the_post();
			if($read > $count && $read <= $count + $getChoose) {
	            ob_start(); // start the buffer to capture the output of the template
	            get_template_part('contents/content_general');
	            $getPosts[] = ob_get_contents(); // pass the output to variable
	            ob_end_clean(); // clear the buffer
	            if( $read == $articles->found_posts )
	            	$getPosts[] = false;
	        }
	        $read++;
	    }
	}
	echo json_encode($getPosts);
	die();
}
add_action( 'wp_ajax_my__load__more', 'my__load__more' );
add_action( 'wp_ajax_nopriv_my__load__more', 'my__load__more' );
?>
