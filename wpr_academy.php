<?php
/**
 * Plugin Name: WPR Academy
 * Author: Aldea Daniel
 * Version: 1.0.0
 * Description: This is my first plugin
 * Text Domain: wpr-academy
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// Plugin URL.
define( 'WPR_ACADEMY_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
// Plugin path.
define( 'WPR_ACADEMY_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );


include_once( WPR_ACADEMY_PATH . '/includes/shortcodes.php' );

function search() {
	search_scripts();
	ob_start(); ?>

	<div id="wpr-filter" class="navigation">
		<select id="regions">
			<option class="active" value="">All Roles</option>
			<?php
			$regions = get_terms(
				array(
					'taxonomy'   => 'role',
					'hide_empty' => true,
				)
			);
			foreach ( $regions as $region ) {
				?>
				<option value="<?php echo $region->term_id; ?>">
					<?php echo $region->name; ?>
				</option>
				<?php
			}
			?>
		</select>
		<label for="name">Search for engineers</label>

		<input type="text" id="name" name="name">
	<?php
	return ob_get_clean();
}

add_shortcode( 'shortcode_search', 'search' );

add_action( 'wp_ajax_search', 'search_callback' );
add_action( 'wp_ajax_nopriv_search', 'search_callback' );

function search_callback() {
	header( 'Content-Type: application/json' );
	$role        = $_GET['role'];
	$tax_query   = '' !== $role ? array(
		'taxonomy' => 'role',
		'field'    => 'term_id',
		'terms'    => $role,
	) : null;
	$search_term = $_GET['search_term'];
	$people      = array();
	$products    =
		array(
			'post_type'   => 'engineer',
			'numberposts' => - 1,
			's'           => $search_term,
			'tax_query'   => array(
				$tax_query,
			),

		);
	$eng = new WP_QUery( $products );
	if ( $eng->have_posts() ) {
		while ( $eng->have_posts() ) {
			$eng->the_post();
			$people[] = array(
				'title'         => get_the_title(),
				'thumbnail_url' => get_the_post_thumbnail_url(),
				'url'           => get_permalink(),
				's'             => $tax_query,
			);
		}
		wp_reset_query();
	}
	echo wp_json_encode( $people );
	wp_die();
}


function search_scripts() {
	wp_enqueue_script( 'search', WPR_ACADEMY_URL . '/assets/search.js', array( 'jquery' ), '1.0.0', true );
	wp_localize_script(
		'search',
		'WPR',
		array(
			'ajax_url'   => admin_url( 'admin-ajax.php' ),
			'ajax_nonce' => wp_create_nonce( 'search' ),
		)
	);
}
