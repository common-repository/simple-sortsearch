<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'query_vars', 'sims_add_meta_query_vars' );
/**
 * カスタムクエリの追加
 *
 * @param [type] $public_query_vars
 * @return void
 */
function sims_add_meta_query_vars( $public_query_vars ) {
	$public_query_vars[] = 'word_sims';
	$public_query_vars[] = 'srelation_sims';
	$public_query_vars[] = 'swhere_sims';
	$public_query_vars[] = 'sstrict_sims';
	$public_query_vars[] = 'cat_sims';
	$public_query_vars[] = 'tag_sims';
	$public_query_vars[] = 'period_sims';
	$public_query_vars[] = 'order_sims';
	$public_query_vars[] = 'orderby_sims';
	$public_query_vars[] = 'postsnum_sims';
	return $public_query_vars;
}

add_action( 'pre_get_posts', 'sims_change_pre_posts' );
/**
 * 検索、フィルターのためにメインクエリ変更
 *
 * @param [type] $query
 * @return void
 */
function sims_change_pre_posts( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	/**
	 * プラグインで追加したパラメーターのチェック
	 */
	$paramlist = array(
		'word_sims'      => true,
		'srelation_sims' => true,
		'swhere_sims'    => true,
		'sstrict_sims'   => true,
		'cat_sims'       => true,
		'tag_sims'       => true,
		'period_sims'    => true,
		'order_sims'     => true,
		'orderby_sims'   => true,
		'postsnum_sims'  => true,
	);

	if ( array_intersect_key( $_GET, $paramlist ) ) {
		// オプション設定 !
		if ( get_option( 'searchsticky_select' ) === 'disable' || ! get_option( 'searchsticky_select' ) ) {
			$query->set( 'ignore_sticky_posts', 1 );
		}
		$typelist = get_option( 'searchtype' );
		if ( $typelist ) {
			// $typelist = explode( ',', $typelist );
			$query->set( 'post_type', $typelist );
		}

		/**
		 * キーワード検索
		 */
		// https://meshikui.com/2019/04/01/1605/ !
		$wordsims = get_query_var( 'word_sims' );
		if ( ! empty( $wordsims ) ) {
			$wordsims = wp_strip_all_tags( str_replace( '　', ' ', $wordsims ), true );
			$query->set( 's', $wordsims );

			add_filter(
				'posts_search',
				function ( $search ) {
					$sstrictsims   = get_query_var( 'sstrict_sims' );
					$srelationsims = get_query_var( 'srelation_sims' );
					$swheresims    = get_query_var( 'swhere_sims' );

					// 厳格に検索 検索方式を「LIKE」から「LIKE BINARY」へ変更するコード https://mycus-tom.com/posts/30 !
					if ( ! empty( $sstrictsims ) && $sstrictsims === 'strict' ) {
						$search = str_replace( 'LIKE', 'LIKE BINARY', $search );
					}
					// or検索に変更 https://inafukukazuya.com/archives/6684 !
					if ( ! empty( $srelationsims ) && $srelationsims === 'or' ) {
						$search = str_replace( ')) AND ((', ')) OR ((', $search );
					}
					// title検索に変更 https://mycus-tom.com/posts/31 !
					if ( ! empty( $swheresims ) && $swheresims === 'title' ) {
						$search = str_replace(
							array( 'excerpt', 'content' ),
							array( 'title', 'title' ),
							$search
						);
					}

					return $search;
				}
			);
		}

		/**
		 * 表示数
		 */
		$postnum = get_query_var( 'postsnum_sims' );
		if ( ! empty( $postnum ) ) {
			$query->set( 'posts_per_page', $postnum );
		}

		/**
		 * ソート用カテゴリ
		 */
		$catsims = get_query_var( 'cat_sims' );
		if ( ! empty( $catsims ) ) {
			$query->set( 'cat', $catsims );
		}

		/**
		 * ソート用タグ
		 */
		$tagsims = get_query_var( 'tag_sims' );
		if ( ! empty( $tagsims ) ) {
			$query->set( 'tag_id', $tagsims );
		}

		/**
		 * 期間指定
		 */
		$peropdsims = get_query_var( 'period_sims' );
		// 期間入力!
		if ( ! empty( $peropdsims ) ) {
			if ( $peropdsims === 'day' ) {
				$query->set(
					'date_query',
					array(
						'after' => '1 day ago',
					)
				);
			}
			if ( $peropdsims === '3day' ) {
				$query->set(
					'date_query',
					array(
						'after' => '3 day ago',
					)
				);
			}
			if ( $peropdsims === 'week' ) {
				$query->set(
					'date_query',
					array(
						'after' => '1 week ago',
					)
				);
			}
			if ( $peropdsims === 'month' ) {
				$query->set(
					'date_query',
					array(
						'after' => '1 month ago',
					)
				);
			}
			if ( $peropdsims === '3month' ) {
				$query->set(
					'date_query',
					array(
						'after' => '3 month ago',
					)
				);
			}
			if ( $peropdsims === 'year' ) {
				$query->set(
					'date_query',
					array(
						'after' => '1 year ago',
					)
				);
			}
		}

		/**
		 * 並べ替え条件
		 */
		$ordersims = get_query_var( 'order_sims', 'DESC' );
		if ( $ordersims ) {
			$query->set( 'order', $ordersims );
		}
		// ordeby設定 !
		$orderbysims = get_query_var( 'orderby_sims' );
		if ( ! empty( $orderbysims ) ) {
			$query->set( 'orderby', $orderbysims );
		}
		// wp_die( print_r($_GET) ); !
	}
}
