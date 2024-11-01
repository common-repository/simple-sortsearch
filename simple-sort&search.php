<?php
/**
 * Plugin Name:       simple sort&search
 * Plugin URI:        https://wordpress.org/plugins/simple-sortsearch/
 * Description:       トップページなどにショートコードを設置するだけでテーマ本来のデザインのまま並べ替えや絞り込み、検索をすることのできるプラグインです。
 * Version:           0.0.3
 * Requires at least: 5.0
 * Requires PHP:      7.0
 * Author:            yukimichi
 * Author URI:
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

/*
  Copyright 2021 yukimichi

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
defined( 'ABSPATH' ) || exit;

// 定数設定!
if ( ! defined( 'SIMPLESORTANDSEARCH_BASENAME' ) ) {
	define( 'SIMPLESORTANDSEARCH_BASENAME', plugin_basename( __FILE__ ) );
}
if ( ! defined( 'SIMPLESORTANDSEARCH_BASEDIR' ) ) {
	define( 'SIMPLESORTANDSEARCH_BASEDIR', plugin_dir_path( __FILE__ ) );
}

// ファイルの読み込み !
require_once SIMPLESORTANDSEARCH_BASEDIR . 'lib/shortcode/category.php';
require_once SIMPLESORTANDSEARCH_BASEDIR . 'lib/shortcode/order.php';
require_once SIMPLESORTANDSEARCH_BASEDIR . 'lib/shortcode/orderby.php';
require_once SIMPLESORTANDSEARCH_BASEDIR . 'lib/shortcode/period.php';
require_once SIMPLESORTANDSEARCH_BASEDIR . 'lib/shortcode/postsnum.php';
require_once SIMPLESORTANDSEARCH_BASEDIR . 'lib/shortcode/reset.php';
require_once SIMPLESORTANDSEARCH_BASEDIR . 'lib/shortcode/searchform.php';
require_once SIMPLESORTANDSEARCH_BASEDIR . 'lib/shortcode/tag.php';
require_once SIMPLESORTANDSEARCH_BASEDIR . 'lib/hook.php';
require_once SIMPLESORTANDSEARCH_BASEDIR . 'lib/option.php';

/*css,jsエンキュー */
add_action(
	'wp_enqueue_scripts',
	function () {
		wp_enqueue_script( 'simple-sort&searchjs', plugins_url( 'lib/js/simple-sort&search.js', __FILE__ ), array(), '0.0.1', true );
		wp_enqueue_style( 'simple-sort&searchcss', plugins_url( 'lib/css/simple-sort&search.css', __FILE__ ), array(), '0.0.1' );
	}
);

// JavaScript遅延読み込み!
add_filter(
	'script_loader_tag',
	function ( $tag, $handle ) {
		if ( $handle === 'simple-sort&searchjs' ) {
			return str_replace( 'src', 'async defer src', $tag );
		}
		return $tag;
	},
	10,
	2
);
// css遅延読み込み!
add_filter(
	'style_loader_tag',
	function ( $tag, $handle ) {
		if ( $handle === 'simple-sort&searchcss' ) {
			return str_replace( 'media=\'all\'', 'media=\'print\' onload="this.media=\'all\'"', $tag );
		}
		return $tag;
	},
	10,
	2
);

add_filter( 'plugin_action_links_' . SIMPLESORTANDSEARCH_BASENAME, 'simplesortandsearch_plugin_action_links', 10, 2 );
// プラグインリストページで設定ページへのリンクを表示!
function simplesortandsearch_plugin_action_links( $links, $file ) {
	static $this_plugin;

	if ( ! $this_plugin ) {
		$this_plugin = SIMPLESORTANDSEARCH_BASENAME;
	}
	// var_dump($this_plugin);
	if ( $file == $this_plugin ) {
		$settings_link = '<a href="' . get_admin_url() . 'admin.php?page=simple_sortsearch_setting">' . __( 'Settings' ) . '</a>';
		array_unshift( $links, $settings_link );
	}

	return $links;
}
