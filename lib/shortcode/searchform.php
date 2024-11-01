<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
add_shortcode(
	'search_sims',
	function ( $atts ) {
		$atts = shortcode_atts(
			array(
				'indexurl'    => null,
				'class'       => null,
				'placeholder' => null,
				'submit'      => '検索',
				'option'      => 'on',
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
		if ( ! empty( $class ) ) {
			$class = ' ' . $class;
		}

		$word_sims      = get_query_var( 'word_sims' );
		$srelation_sims = get_query_var( 'srelation_sims' );
		$swhere_sims    = get_query_var( 'swhere_sims' );
		$sstrict_sims   = get_query_var( 'sstrict_sims' );
		?>
	<form method="get" action="<?php echo esc_url( $atts['indexurl'] ); ?>" class="sims_form sims_keywordsearch<?php echo esc_attr( $class ); ?>">
		<input class="keyword" name="word_sims" type="text" placeholder="<?php echo esc_attr( $atts['placeholder'] ); ?>" value="<?php echo esc_attr( $word_sims ); ?>">

		<?php if ( $atts['option'] === 'on' ) { // オプション設定! ?>
			<div class="sims_wordoption">
				<label><input <?php checked( $srelation_sims, 'and', true ); ?> type="radio" name="srelation_sims" value="and"><?php esc_html_e( 'AND検索' ); ?></label>
				<label><input <?php checked( $srelation_sims, 'or', true ); ?> type="radio" name="srelation_sims" value="or"><?php esc_html_e( 'OR検索' ); ?></label><br>
				<label><input <?php checked( $swhere_sims, 'title', true ); ?> type="checkbox" name="swhere_sims" value="title"><?php esc_html_e( 'タイトルから検索' ); ?></label>
				<label><input <?php checked( $sstrict_sims, 'strict', true ); ?> type="checkbox" name="sstrict_sims" value="strict"><?php esc_html_e( '厳格検索' ); ?></label>
			</div>
			<?php
		}

		// 既存のクエリを受け取る!
		foreach ( $_GET as $getkey => $getvalue ) {
			if ( $getkey === 'word_sims' || $getkey === 'srelation_sims' || $getkey === 'swhere_sims' || $getkey === 'sstrict_sims' ) {
				continue;
			}
			?>
			<input type="hidden" name="<?php echo esc_attr( $getkey ); ?>" value="<?php echo esc_attr( $getvalue ); ?>">
		<?php } ?>

		<input class="keyword-submit" type="submit" value="<?php echo esc_attr( $atts['submit'] ); ?>">
	</form>
		<?php
		return ob_get_clean();
	}
);
