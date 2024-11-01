<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
add_shortcode(
	'postsnum_sims',
	function ( $atts ) {
		$atts = shortcode_atts(
			array(
				'indexurl' => null,
				'class'    => null,
				'submit'   => '表示数変更',
			),
			$atts
		);
		ob_start();

		// url設定!
		if ( $atts['indexurl'] === 'now' ) {
			$atts['indexurl'] = '';
		} elseif ( empty( $atts['indexurl'] ) ) {
			$atts['indexurl'] = home_url( '/' );
		} else {
			$atts['indexurl'] = $atts['indexurl'];
		}

		// ユーザークラス!
		if ( ! empty( $atts['class'] ) ) {
			$class = ' ' . $atts['class'];
		}

		$postnum = get_query_var( 'postsnum_sims' );
		?>
	<form action="<?php echo esc_url( $atts['indexurl'] ); ?>" method="get" class="sims_form sims_postsnum<?php echo esc_attr( $class ); ?>">
		<input type="number" name="postsnum_sims" class="sims_postsnum-number" value="<?php echo esc_attr( $postnum ); ?>">
		<?php
		foreach ( $_GET as $getkey => $getvalue ) {
			if ( $getkey === 'postsnum_sims' ) {
				continue;
			}
			?>
			<input type="hidden" name="<?php echo esc_attr( $getkey ); ?>" value="<?php echo esc_attr( $getvalue ); ?>">
		<?php } ?>
		<input type="submit" value="<?php esc_attr_e( $atts['submit'] ); ?>">
	</form>
		<?php
		return ob_get_clean();
	}
);
