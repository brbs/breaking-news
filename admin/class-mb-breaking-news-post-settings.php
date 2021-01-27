<?php defined('ABSPATH') || exit;

class Mb_Breaking_News_Post_Settings
{
	private $plugin_name;

	private $version;

	public $errors = array();

	public function __construct($plugin_name, $version)
	{
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	public function add_meta_boxes()
	{
		add_meta_box(
			'mbbn-box',
			__('Breaking News', $this->plugin_name),
			array($this, 'meta_box_html'),
			'post',
			'side'
		);
	}

	public function meta_box_html($post)
	{
		$post_id = $post->ID;

		wp_nonce_field('mbbn-nonce', 'mbbn-nonce');

		include_once 'partials/meta-box-html.php';
	}

	public function save_post($post_id, $post)
	{
		if (empty($post_id) || empty($post)) {
			return;
		}

		if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || is_int(wp_is_post_revision($post)) || is_int(wp_is_post_autosave($post))) {
			return;
		}

		if (empty($_POST['mbbn-nonce']) || !wp_verify_nonce($_POST['mbbn-nonce'], 'mbbn-nonce')) {
			return;
		}

		if (empty($_POST['post_ID']) || $_POST['post_ID'] != $post_id) {
			return;
		}

		if (!current_user_can('edit_post', $post_id)) {
			return;
		}

		$title                = !empty($_POST['mbbn-title']) ? sanitize_text_field($_POST['mbbn-title']) : '';
		$breaking             = isset($_POST['mbbn-breaking']);
		$expiration           = isset($_POST['mbbn-expiration']);
		$expiration_timestamp = !empty($_POST['mbbn-expiration-date']) ? (int) $_POST['mbbn-expiration-date'] : '';

		if ($expiration) {
			if ($expiration_timestamp) {
				if ($this->is_timestamp($expiration_timestamp)) {
					$expiration_date = gmdate('Y-m-d H:i:s', $expiration_timestamp);
					Mb_Breaking_News_Cron::schedule_event($post_id, $expiration_timestamp);
				}
				else {
					$this->errors[] = __('Please select a valid date', 'mb-breaking-news');
				}
			} else {
				$this->errors[] = __('Please select a valid date', 'mb-breaking-news');
			}
		}

		if(count($this->errors) > 0) {
			return;
		}

		if($breaking) {
			$activation_date = get_post_meta($post_id, '_mbbn_activation_date', true);

			if (!$activation_date) {
				$activation_date = current_time('mysql', true);
			}
		}
		else {
			$activation_date = '';
			$expiration_date = '';
		}

		update_post_meta($post_id, '_mbbn_title', $title);
		update_post_meta($post_id, '_mbbn_breaking', $breaking);
		update_post_meta($post_id, '_mbbn_expiration_date', $expiration_date);
		update_post_meta($post_id, '_mbbn_activation_date', $activation_date);
	}

	public function is_timestamp($timestamp)
	{
		if (strtotime(gmdate('Y-m-d H:i:s', $timestamp)) === (int) $timestamp) {
			return true;
		} else {
			return false;
		}
	}

	public function save_errors()
	{
		update_option('mbbn_post_setting_errors', $this->errors);
	}

	public function output_errors()
	{
		$errors = array_filter((array) get_option('mbbn_post_setting_errors'));

		if (!empty($errors)) {

			echo '<div id="" class="notice notice-error is-dismissible">';

			foreach ($errors as $error) {
				echo '<p>' . wp_kses_post($error) . '</p>';
			}

			echo '</div>';

			delete_option('mbbn_post_setting_errors');
		}
	}

	public function enqueue_assets()
	{
		$current_screen    = get_current_screen();
		$current_screen_id = $current_screen ? $current_screen->id : '';

		if ('post' == $current_screen_id) {
			wp_enqueue_style(
				$this->plugin_name . '-post',
				mbbn_asset_path('styles/post.css'),
				array(),
				$this->version,
				'all'
			);

			wp_enqueue_script(
				$this->plugin_name . '-post',
				mbbn_asset_path('scripts/post.js'),
				array('jquery'),
				$this->version,
				false
			);
		}
	}
}
