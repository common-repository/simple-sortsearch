<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
add_shortcode(
	'orderby_sims',
	function ( $atts ) {
		$atts = shortcode_atts(
			array(
				'indexurl' => null,
				'class'    => null,
				'enable'   => 'all',
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
		$orderby = get_query_var( 'orderby_sims' );
		$metakey = get_query_var( 'meta_key_sims' );

		?>
		<select name="orderby_sims" onChange="<?php echo esc_attr( $url ); ?>" class="sims_select<?php echo esc_attr( $class ); ?>">
			<?php
			$optionvalue = '?' . wp_parse_url(
				add_query_arg(
					array(
						'orderby_sims'  => '',
						'meta_key_sims' => '',
					)
				),
				PHP_URL_QUERY
			);
			?>
			<option value="<?php echo esc_url_raw( $optionvalue ); ?>">並べ替え選択</option>

			<?php
			if ( ! empty( $atts['enable'] ) ) {
				sims_template_orderby( $atts['enable'], $orderby, $metakey );
			}
			?>
		</select>
		<?php
		return ob_get_clean();
	}
);

function sims_template_orderby( $enable, $orderby ) {
	if ( $enable === 'all' || strpos( $enable, 'date' ) !== false ) {
		?>
		<option <?php selected( $orderby, 'date' ); ?> value="<?php echo esc_url_raw( '?' . wp_parse_url( add_query_arg( array( 'orderby_sims' => 'date' ) ), PHP_URL_QUERY ) ); ?>">投稿順</option>
		<?php
	}
	if ( $enable === 'all' || strpos( $enable, 'modified' ) !== false ) {
		?>
		<option <?php selected( $orderby, 'modified' ); ?> value="<?php echo esc_url_raw( '?' . wp_parse_url( add_query_arg( array( 'orderby_sims' => 'modified' ) ), PHP_URL_QUERY ) ); ?>">更新順</option>
		<?php
	}
	if ( $enable === 'all' || strpos( $enable, 'title' ) !== false ) {
		?>
		<option <?php selected( $orderby, 'title' ); ?> value="<?php echo esc_url_raw( '?' . wp_parse_url( add_query_arg( array( 'orderby_sims' => 'title' ) ), PHP_URL_QUERY ) ); ?>">タイトル順</option>
		<?php
	}
	if ( $enable === 'all' || strpos( $enable, 'rand' ) !== false ) {
		?>
		<option <?php selected( $orderby, 'rand' ); ?> value="<?php echo esc_url_raw( '?' . wp_parse_url( add_query_arg( array( 'orderby_sims' => 'rand' ) ), PHP_URL_QUERY ) ); ?>">ランダム</option>
		<?php
	}
	if ( $enable === 'all' || strpos( $enable, 'comment_count' ) !== false ) {
		?>
		<option <?php selected( $orderby, 'comment_count' ); ?> value="<?php echo esc_url_raw( '?' . wp_parse_url( add_query_arg( array( 'orderby_sims' => 'comment_count' ) ), PHP_URL_QUERY ) ); ?>">コメント数順</option>
		<?php
	}
}
