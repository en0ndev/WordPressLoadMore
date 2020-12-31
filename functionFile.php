<?php

wp_enqueue_script('yourLoadMore', get_template_directory_uri() . '/assets/js/getLoadMore.js', array('jquery'), '1.0.0', true );
wp_localize_script('yourLoadMore', 'ajaxurl', admin_url( 'admin-ajax.php' ) );

function your_load_more() {

	$count = get_option( 'posts_per_page' ); // It scan your posts in page
	$add = $_POST['addNum'];
	$getChoose = $_POST['getChoose'];
	$count = $count + $add;
	$cpt = 1;
	$args = array(
		'posts_per_page' => -1,
        'post_type'      => 'post', // change the post type if you use a custom post type
        'post_status'    => 'publish',
    );

	$articles = new WP_Query( $args );

	$getPosts = array();

	if( $articles->have_posts() ) {

		while( $articles->have_posts() ) {
			
			$articles->the_post();
			$one_post = "";

			if( $cpt > $count && $cpt <= $count+$getChoose ) {

                ob_start(); // start the buffer to capture the output of the template
                get_template_part('assets/content');
				$getPosts[] = ob_get_contents(); // pass the output to variable
                ob_end_clean(); // clear the buffer

                if( $cpt == $articles->found_posts )
                	$getPosts[] = "0";

            }
            $cpt++;
        }
    }
    
    echo json_encode($getPosts);

    die();

}

add_action( 'wp_ajax_your_load_more', 'your_load_more' );
add_action( 'wp_ajax_nopriv_your_load_more', 'your_load_more' );

?>