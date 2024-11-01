<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
add_shortcode(
	'reset_sims',
	function ( $atts ) {
		$atts = shortcode_atts(
			array(
				'indexurl' => null,
				'class'    => null,
				'submit'   => 'リセット',
			),
			$atts
		);
		ob_start();

		// ユーザークラス!
		if ( ! empty( $class ) ) {
			$class = ' ' . $class;
		}
		?>
	<button class="keyword-reset<?php echo esc_attr( $class ); ?>" onclick="location.href='\/\/' + location.host + location.pathname;" name="allreset_sims"><?php echo wp_kses_post( $atts['submit'] ); ?></button>
		<?php
		return ob_get_clean();
	}
);
