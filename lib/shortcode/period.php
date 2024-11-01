<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
add_shortcode(
	'period_sims',
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
		if ( $atts['class'] ) {
			$class = ' ' . $atts['class'];
		}
		$period = get_query_var( 'period_sims' );
		?>
	<select class="sims_select<?php echo esc_attr( $class ); ?>" onChange="<?php echo esc_attr( $url ); ?>">
		<option value="<?php echo esc_url_raw( '?' . wp_parse_url( add_query_arg( array( 'period_sims' => '' ) ), PHP_URL_QUERY ) ); ?>">投稿期間</option>
		<option <?php selected( $period, 'year' ); ?> value="<?php echo esc_url_raw( '?' . wp_parse_url( add_query_arg( array( 'period_sims' => 'year' ) ), PHP_URL_QUERY ) ); ?>">１年以内</option>
		<option <?php selected( $period, '3month' ); ?> value="<?php echo esc_url_raw( '?' . wp_parse_url( add_query_arg( array( 'period_sims' => 'month' ) ), PHP_URL_QUERY ) ); ?>">３ヶ月以内</option>
		<option <?php selected( $period, 'month' ); ?> value="<?php echo esc_url_raw( '?' . wp_parse_url( add_query_arg( array( 'period_sims' => 'month' ) ), PHP_URL_QUERY ) ); ?>">１ヶ月以内</option>
		<option <?php selected( $period, 'week' ); ?> value="<?php echo esc_url_raw( '?' . wp_parse_url( add_query_arg( array( 'period_sims' => 'week' ) ), PHP_URL_QUERY ) ); ?>">１週間以内</option>
		<option <?php selected( $period, '3day' ); ?> value="<?php echo esc_url_raw( '?' . wp_parse_url( add_query_arg( array( 'period_sims' => '3day' ) ), PHP_URL_QUERY ) ); ?>">３日以内</option>
		<option <?php selected( $period, 'day' ); ?> value="<?php echo esc_url_raw( '?' . wp_parse_url( add_query_arg( array( 'period_sims' => 'day' ) ), PHP_URL_QUERY ) ); ?>">１日以内</option>
	</select>
		<?php
		return ob_get_clean();
	}
);
