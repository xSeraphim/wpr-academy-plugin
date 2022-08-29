<?php


function wpr_software_shortcode( $attr ) {
	$arg = shortcode_atts(
		array(
			'h1'         => 'true',
			'background' => 'white',
			'h1-text'    => 'Ala bala tralala',
		),
		$attr
	);
	ob_start();
	?>
	<?php
	if ( 'true' === $arg['h1'] ) {
		?>
	<h1><?php echo $arg['h1-text']; ?></h1><?php } ?>
	<p style="background-color:<?php echo $arg['background']; ?>">Lorem Ipsum dolores est</p>
	<?php
	return ob_get_clean();
}
add_shortcode( 'software-shortcode', 'wpr_software_shortcode' );
