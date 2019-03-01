<?php
function four_corners_scripts() {

	$ver = '1.0.5';
	$env = ( in_array( $_SERVER['REMOTE_ADDR'], array( '127.0.0.1', '::1' ) ) ? 'dev' : 'prod' );
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'script', get_stylesheet_directory_uri() . '/scripts.js' , array(), $ver, true );

	// wp_enqueue_style( 'bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css' );
	wp_enqueue_style( 'style', get_stylesheet_directory_uri() . '/style.css' );

	$url = trailingslashit( home_url() );
	$path = trailingslashit( parse_url( $url, PHP_URL_PATH ) );

	wp_scripts()->add_data( 'react_script', 'data', sprintf( 'var SiteSettings = %s;', wp_json_encode( 
			array(
				'title' => get_bloginfo( 'name', 'display' ),
				'path' => $path,
				'url' => array(
					'api' => esc_url_raw( get_rest_url( null, '/wp/v2/' ) ),
					'root' => esc_url_raw( $url ),
					'theme' => esc_url_raw( get_stylesheet_directory_uri() )
				)
			)
		)
	) );

}
add_action( 'wp_enqueue_scripts', 'four_corners_scripts' );

function slugify ( $str ) {
	return (
		strtolower(
			trim(
				preg_replace( '/[^A-Za-z0-9-]+/', '-', $str )
			)
		)
	);
}

register_nav_menus( array(
	'main' => 'Main',
) );


function page_endpoint( $req ) {

	$slug = $req['slug'];
	$lang = $req['lang'];
	$args = array(
		'post_type' => 'page',
		'posts_per_page'=> 1, 
		'numberposts'=> 1,
		'name' => $slug
	);
	$page = get_posts( $args )[0];
	$acf = get_fields( $page->ID );
	$page->acf = $acf;
	return $page;

}

function menu_endpoint() {

	$menu_items = wp_get_nav_menu_items( 'main' );
	foreach ( $menu_items as $i => $menu_item ) {
		if( $post = get_post( $menu_item->object_id ) ) {
			$menu_items[$i]->slug = $post->post_name;	
		}
	}
	return $menu_items;

}

function options_endpoint() {

	// $options = array();
	// $option_keys = array( 'alert' );
	// foreach( $option_keys as $i => $option_key ) {
	// 	$option = get_field( $option_key , 'option' );
	// 	$options[$option_key ] = $option;
	// }
	// return $options;

}

add_action( 'rest_api_init', function () {

	// register_rest_route( 'wp/v2', '/options', array(
	// 	'methods' => 'GET',
	// 	'callback' => 'options_endpoint'
	// ));

	register_rest_route( 'wp/v2', '/menu', array(
		'methods' => 'GET',
		'callback' => 'menu_endpoint'
	));

	register_rest_route( 'wp/v2', '/page/', array(
		'methods' => 'GET',
		'callback' => 'page_endpoint'
	));


});

if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page();
	
}

show_admin_bar( false );
