<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
add_shortcode(
	'order_sims',
	function ( $atts ) {
		$atts = shortcode_atts(
			array(
				'indexurl' => null,
				'class'    => null,
			),
			$atts
		);
		ob_start();
		// url設定!
		if ( $atts['indexurl'] === 'now' ) {
			$url = 'location.href=value;';
		} elseif ( $atts['indexurl'] ) {
			$url = 'location.href=\'' . $atts['indexurl'] . '\'+value;';
		} elseif ( ! is_home() ) {
			$url = 'location.href=\'' . home_url( '/' ) . '\'+value;';
		} else {
			$url = 'location.href=value;';
		}

		// ユーザークラス!
		if ( ! empty( $atts['class'] ) ) {
			$class = ' ' . $atts['class'];
		}
		$getorder_sims = get_query_var( 'order_sims' );
		?>
	<select name="order_sims" onChange="<?php echo esc_attr( $url ); ?>" class="sims_select<?php echo esc_attr( $class ); ?>">
		<option <?php selected( $getorder_sims, 'DESC' ); ?> value="<?php echo esc_url_raw( '?' . wp_parse_url( add_query_arg( array( 'order_sims' => 'DESC' ) ), PHP_URL_QUERY ) ); ?>">降順（高い順）</option>
		<option <?php selected( $getorder_sims, 'ASC' ); ?> value="<?php echo esc_url_raw( '?' . wp_parse_url( add_query_arg( array( 'order_sims' => 'ASC' ) ), PHP_URL_QUERY ) ); ?>">昇順（低い順）</option>
	</select>
		<?php
		return ob_get_clean();
	}
);
