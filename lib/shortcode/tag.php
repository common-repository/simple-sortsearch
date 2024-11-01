<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
add_shortcode(
	'tag_sims',
	function ( $atts ) {
		$atts = shortcode_atts(
			array(
				'label'    => null,
				'indexurl' => null,
				'class'    => null,
				'size'     => null,
				'type'     => 'select',
			),
			$atts
		);

		ob_start();

		// ユーザークラス!
		if ( ! empty( $atts['class'] ) ) {
			$class = ' ' . $atts['class'];
		}

		// ラベル設定!
		if ( $atts['label'] === null ) {
			$label = 'タグを選択';
		}
		// タイプ設定!
		if ( $atts['type'] === 'select' ) {
			// url設定!
			if ( $atts['indexurl'] === 'now' ) {
				$url = '';
			} elseif ( empty( $atts['indexurl'] ) ) {
				$url = home_url( '/' );
			} else {
				$url = $atts['indexurl'];
			}
			?>

	<form class="sims_form sims_taxform" action="<?php echo esc_url( $url ); ?>" method="get">

				<?php
				$args = array(
					'show_option_none'  => $label,
					'option_none_value' => '',
					'show_count'        => 1,
					'orderby'           => 'name',
					'echo'              => 0,
					'class'             => 'sims_dropdown' . $class,
					'hierarchical'      => true,
					'taxonomy'          => 'post_tag',
				);

				if ( isset( $atts['size'] ) ) {
					$args['name'] = 'tag_sims';
					$select       = wp_dropdown_categories( $args );

					$replace = "<select$1 onchange='return this.form.submit()' size='" . $atts['size'] . "'>";
					$select  = preg_replace( '#<select([^>]*)>#', $replace, $select );
					$select  = str_replace( 'value="' . get_query_var( 'tag_sims' ) . '"', 'selected value=', $select ); // 値が選択中の場合!

				} else {
					$args['name'] = 'tag_sims';
					$select       = wp_dropdown_categories( $args );

					$replace = "<select$1 onchange='return this.form.submit()'>";
					$select  = preg_replace( '#<select([^>]*)>#', $replace, $select );
					$select  = str_replace( 'value="' . get_query_var( 'tag_sims' ) . '"', 'selected value=', $select ); // 値が選択中の場合!
				}

				$allowed_html = array(
					'select' => array(
						'onchange' => array( 'return this.form.submit()' ),
						'size'     => array(),
						'name'     => array(),
						'id'       => array(),
						'class'    => array(),
					),
					'option' => array(
						'value'    => array(),
						'class'    => array(),
						'selected' => array(),
					),
				);
				echo wp_kses( $select, $allowed_html );

				foreach ( $_GET as $getkey => $get ) {
					if ( $getkey == 'tag_sims' ) {
						continue;
					}
					?>
			<input type="hidden" name="<?php echo esc_attr( $getkey ); ?>" value="<?php echo esc_attr( $get ); ?>">
				<?php } ?>

		<noscript>
			<input type="submit" value="View" />
		</noscript>

	</form>
				<?php
		} else {
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

			$getqueryname = get_query_var( 'tag_sims' );
			if ( ! empty( $getqueryname ) ) {
				$style = 'style=display:block;';
			}
			$terms = get_tags( 'orderby=count&order=DESC' );
			?>
				<div class="sims_toglewrap<?php echo esc_attr( $class ); ?>">
					<div class="sims_showbutton"><?php echo esc_html( $label ); ?></div>
					<div class="sims_hiddenwrap sims_checkbox" <?php echo esc_attr( $style ); ?>>
					<?php
					if ( $getqueryname ) {
						$oldvalue = $getqueryname . ',';
					}
					$getquerynamearray = explode( ',', $getqueryname );
					foreach ( $terms as $term ) {
						$checked = strpos( $oldvalue, $term->term_id . ',' );
						if ( $checked !== false ) {
							$checkedvalue = implode( ',', array_diff( $getquerynamearray, array( $term->term_id ) ) );

							echo '<label>' . esc_html( $term->name ) . '<input checked onChange="' . esc_attr( $url ) . '" type="checkbox" value="' . esc_url_raw( '?' . wp_parse_url( add_query_arg( array( 'tag_sims' => $checkedvalue ) ), PHP_URL_QUERY ) ) . '"></label>';
						} else {
							echo '<label>' . esc_html( $term->name ) . '<input onChange="' . esc_attr( $url ) . '" type="checkbox" value="' . esc_url_raw( '?' . wp_parse_url( add_query_arg( array( 'tag_sims' => $oldvalue . $term->term_id ) ), PHP_URL_QUERY ) ) . '"></label>';
						}
					}
					?>
					</div>
				</div>
				<?php
		}

		return ob_get_clean();
	}
);
