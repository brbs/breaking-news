<?php defined('ABSPATH') || exit;

class Mb_Breaking_News_Public
{
	private $plugin_name;

	private $version;

	public function __construct($plugin_name, $version)
	{
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	public function custom_styles() {

		$options = get_option('mbbn_options');
		$bg_color = empty($options['bg_color']) ? 'transparent' : $options['bg_color'];
		$text_color = empty($options['text_color']) ? 'inherit' : $options['text_color']; ?>

		<style type="text/css">
			.breaking {
				background-color: <?php echo esc_attr($bg_color); ?>;
				color: <?php echo esc_attr($text_color); ?>;
			}
			.breaking a {
				color: inherit;
			}
		</style>

		<?php
	}

	public function do_shortcode()
	{
		echo Mb_Breaking_News_Shortcode::output(array());
	}

	public function enqueue_assets()
	{
		wp_enqueue_style($this->plugin_name, mbbn_asset_path('styles/main.css'), array(), '', 'all');
	}
}
