<?php
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
	wp_enqueue_style( 'divi', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style('Fjalla_one_font','https://fonts.googleapis.com/css?family=Fjalla+One');
	wp_enqueue_script( 'divi', get_stylesheet_directory_uri() . '/js/scripts.js', array( 'jquery', 'divi-custom-script' ), '0.1.2', true );
}

function modify_user_contact_methods( $user_contact ) {
	$user_contact['avatar']   = __( 'Avatar Image Link'   );
	$user_contact['facebook']   = __( 'Facebook Link'   );
	$user_contact['twitter'] = __( 'Twitter Link' );
	$user_contact['youtube']   = __( 'Youtube Link'   );
	$user_contact['instagram'] = __( 'Instagram Link' );
	$user_contact['video'] = __( 'Video Link' );

	return $user_contact;
}
add_filter( 'user_contactmethods', 'modify_user_contact_methods' );

function custom_avatar( $avatar, $id_or_email, $size, $default, $alt ) {
	$user = false;

	if ( is_numeric( $id_or_email ) ) {

		$id = (int) $id_or_email;
		$user = get_user_by( 'id' , $id );

	} elseif ( is_object( $id_or_email ) ) {

		if ( ! empty( $id_or_email->user_id ) ) {
			$id = (int) $id_or_email->user_id;
			$user = get_user_by( 'id' , $id );
		}

	} else {
		$user = get_user_by( 'email', $id_or_email );
	}

	if ( $user && is_object( $user ) ) {

		$avatar = get_the_author_meta('avatar',$user->ID);
		$avatar = "<img alt='{$alt}' src='{$avatar}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";

	}

	return $avatar;
}
add_filter( 'get_avatar' , 'custom_avatar' , 1 , 5 );

function nj_add_fullwidth_body_class( $classes ){
	$blacklist = array( 'et_right_sidebar');

	$classes[] = 'et_full_width_page';
	$classes[] = 'et_pb_pagebuilder_layout';
	$classes[] = 'et_fullwidth_nav';
	$classes = array_diff($classes,$blacklist);




	return $classes;
}
add_filter( 'body_class', 'nj_add_fullwidth_body_class' , 11);


function nj_infinite_scrolling(){
	wp_register_script(
		'infinite_scrolling',
		get_stylesheet_directory_uri().'/js/jquery.jscroll.min.js',
		array('jquery'),
		null,
		true
		);
	if(is_singular()){
		wp_enqueue_script('infinite_scrolling');
	}
}

add_action('wp_enqueue_scripts', 'nj_infinite_scrolling');

function set_infinite_scrolling(){
	if(is_singular()){
		?>
		<script type="text/javascript">
		jQuery('#main-content').jscroll({
			loadingHtml: '<div class="container" style="text-align: center;"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/ajax-loading.gif" alt="Carregando" /></div>',
			padding: 20,
			nextSelector: 'nav.post-nav .nav-link-previous a',
			contentSelector: '#main-content'
		});

		</script>
		<?php
	}
}

add_action( 'wp_footer', 'set_infinite_scrolling',100);
?>
