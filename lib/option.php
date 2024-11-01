<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
//memo 検索結果から特定のロールを除外できるオプション　ショートコードジェネレータ
// 設定ページ追加!
add_action( 'admin_menu', 'sims_addoptionpage' );
function sims_addoptionpage() {
	add_options_page( 'simple sort&search', 'simple sort&search', 'manage_options', 'simple_sortsearch_setting', 'sims_option_content' );
}

// オプション更新処理!
add_action(
	'init',
	function () {
		if ( isset( $_POST['simple-sort&search_name'] ) && wp_verify_nonce( sanitize_key( wp_unslash( $_POST['simple-sort&search_name'] ) ), 'simple-sort&search_action' ) ) {

			if ( isset( $_POST['searchtype'] ) ) {
				$searchtype = array_map( 'sanitize_text_field', wp_unslash( $_POST['searchtype'] ) );
				update_option( 'searchtype', $searchtype );
			} else {
				update_option( 'searchtype', false );
			}

			if ( isset( $_POST['searchsticky_select'] ) ) {
				update_option( 'searchsticky_select', sanitize_text_field( wp_unslash( $_POST['searchsticky_select'] ) ) );
			} else {
				update_option( 'searchsticky_select', false );
			}

			if ( isset( $_POST['poststatus'] ) ) {
				$poststatus = array_map( 'sanitize_text_field', wp_unslash( $_POST['poststatus'] ) );
				update_option( 'poststatus', $poststatus );
			} else {
				update_option( 'poststatus', false );
			}
		}
	}
);

function sims_option_content() {
	?>
	<style>
		label{
			margin-right: 11px;
		}
	</style>

	<div class="wrap">
		<h1>プラグインオプション</h1>
		<form method="post">
			<?php
			wp_nonce_field( 'simple-sort&search_action', 'simple-sort&search_name' );  // nonceフィールド設置!
			?>
			<table class="form-table" role="presentation">
				<tbody>
					<tr>
						<td>
						<p class="description">検索、ソート結果で表示する投稿タイプの指定</p>
							<?php
							$searchtype = get_option( 'searchtype' );
							$args       = array(
								'public' => true,
							);
							$posttypes  = get_post_types( $args );
							foreach ( $posttypes as $name ) {
								echo '<label><input ' . checked( isset( $name, $searchtype[ $name ] ), true, false ) . ' name="searchtype[' . esc_attr( $name ) . ']" value="' . esc_attr( $name ) . '" type="checkbox" class="regular-text">' . esc_html( $name ) . '</label>';
							}
							?>
						</td>
					</tr>
					<tr>
						<td>
						<p class="description">検索、ソート結果で先頭固定ページを表示するか</p>
							<?php
							echo '<label><input ' . checked( get_option( 'searchsticky_select' ), 'enable', false ) . ' name="searchsticky_select" value="enable" type="radio" class="regular-text">先頭に表示する</label>';
							echo '<label><input ' . checked( get_option( 'searchsticky_select' ), 'disable', false ) . ' name="searchsticky_select" value="disable" type="radio" class="regular-text">先頭に表示しない</label>';
							?>
						</td>
					</tr>
					<tr>
						<td>
						<p class="description">検索、ソート結果で表示する投稿ステータスの指定</p>
							<?php
							$poststatus = get_option( 'poststatus' );
							$statuslist = array(
								'publish',
								'pending',
								'draft',
								'auto-draft',
								'future',
								'private',
								'inherit',
								'trash',
								'any',
							);
							foreach ( $statuslist as $status ) {
								echo '<label><input ' . checked( isset( $status, $poststatus[ $status ] ), true, false ) . ' name="poststatus[' . esc_attr( $status ) . ']" value="' . esc_attr( $status ) . '" type="checkbox" class="regular-text">' . esc_html( $status ) . '</label>';
							}
							?>
							<p class="description"><br>※投稿タイプでattachmentを追加している場合は、inheritを追加で指定するか、anyを選択してください</p>
						</td>
					</tr>
				</tbody>
			</table>
			<p class="submit">
				<input type="submit" class="button button-primary" name="simple-sort&searchsubmit" value="変更を保存">
			</p>
		</form>
	</div>
	<?php
}
