<?php defined('ABSPATH') || exit;

class Mb_Breaking_News_Settings
{
	private $plugin_name;

	private $version;

	public $options;

	public function __construct($plugin_name, $version)
	{
		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		$this->options = get_option('mbbn_options');
	}

	public function add_menu_page()
	{

		add_options_page(
			__('Breaking News', $this->plugin_name),
			__('Breaking News', $this->plugin_name),
			'manage_options',
			'breaking_news',
			array($this, 'breaking_news_options_page_html')
		);

	}

	public function breaking_news_options_page_html()
	{
		if (!current_user_can('manage_options')) {
			return;
		}

		/*
		if ( isset( $_GET['settings-updated'] ) ) {
		add_settings_error( 'mbbn_messages', 'mbbn_message', __( 'Settings Saved', $this->plugin_name ), 'updated' );
		}*/

		//settings_errors('mbbn_options');

		?>

		<div class="wrap">
			<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
			<form action="options.php" method="post">
				<?php
					settings_fields('mbbn');
					do_settings_sections('mbbn');
					submit_button('Save Settings');
				?>
			</form>

			<?php echo do_shortcode('[mbbn_breaking_news]'); ?>
		</div>

		<?php

	}

	public function settings_init()
	{
		register_setting('mbbn', 'mbbn_options', array($this, 'validate_options'));

		add_settings_section(
			'mbbn_section_general',
			__('General', 'mbbn'),
			array($this, 'settings_callback'),
			'mbbn'
		);

		add_settings_field(
			'title',
			__('Title', 'mbbn'),
			array($this, 'title_field_callback'),
			'mbbn',
			'mbbn_section_general',
			array(
				'id'          => 'mbbn_title',
				'name'        => 'mbbn_title',
				'class'       => 'mbbn-text-field',
				'input_class' => 'mbbn-text-input',
			)
		);

		add_settings_field(
			'bg_color',
			__('Background Color', 'mbbn'),
			array($this, 'bg_color_field_callback'),
			'mbbn',
			'mbbn_section_general',
			array(
				'id'          => 'mbbn_bg_color',
				'name'        => 'mbbn_bg_color',
				'class'       => 'mbbn-color-field',
				'input_class' => 'mbbn-color-input',
			)
		);

		add_settings_field(
			'text_color',
			__('Text Color', 'mbbn'),
			array($this, 'text_color_field_callback'),
			'mbbn',
			'mbbn_section_general',
			array(
				'id'          => 'mbbn_text_color',
				'label'       => __('Text Color', 'mbbn'),
				'name'        => 'mbbn_text_color',
				'class'       => 'mbbn-color-field',
				'input_class' => 'mbbn-color-input',
			)
		);
	}

	public function settings_callback($args)
	{}

	public function title_field_callback($args)
	{
		$val = (isset($this->options['title'])) ? $this->options['title'] : '';
		echo '<input type="text" name="mbbn_options[title]" value="' . $val . '" />';
	}

	public function bg_color_field_callback($args)
	{
		$val = (isset($this->options['bg_color'])) ? $this->options['bg_color'] : '';
		echo '<input type="text" class="mbbn-color-input" name="mbbn_options[bg_color]" value="' . $val . '" />';
	}

	public function text_color_field_callback($args)
	{
		$val = (isset($this->options['text_color'])) ? $this->options['text_color'] : '';
		echo '<input type="text" class="mbbn-color-input" name="mbbn_options[text_color]" value="' . $val . '" />';
	}

	public function validate_options($fields)
	{
		$valid_fields = array();

		$title                 = trim($fields['title']);
		$valid_fields['title'] = strip_tags(stripslashes($title));

		$bg_color = trim($fields['bg_color']);
		$bg_color = strip_tags(stripslashes($bg_color));

		$text_color = trim($fields['text_color']);
		$text_color = strip_tags(stripslashes($text_color));

		if ('' != $bg_color && false === $this->check_color($bg_color)) {
			add_settings_error('mbbn_options', 'mbbn_error', 'Insert a valid color for Background color', 'error');
			$valid_fields['bg_color'] = $this->options['bg_color'];
		} else {
			$valid_fields['bg_color'] = $bg_color;
		}

		if ('' != $text_color && false === $this->check_color($text_color)) {
			add_settings_error('mbbn_options', 'mbbn_error', 'Insert a valid color for Text color', 'error');
			$valid_fields['text_color'] = $this->options['text_color'];
		} else {
			$valid_fields['text_color'] = $text_color;
		}

		return $valid_fields;
	}

	public function check_color($hex)
	{
		if (sanitize_hex_color($hex)) {
			return true;
		}

		return false;
	}

	public function enqueue_assets()
	{
		$current_screen = get_current_screen();
		$current_screen_id = $current_screen ? $current_screen->id : '';

		if('settings_page_breaking_news' == $current_screen_id) {
			wp_enqueue_style('wp-color-picker');
			wp_enqueue_style(
				$this->plugin_name . '-options',
				mbbn_asset_path('styles/options.css'),
				array(),
				$this->version,
				'all'
			);


			wp_enqueue_script('wp-color-picker');
			wp_enqueue_script(
				$this->plugin_name . '-options',
				mbbn_asset_path('scripts/options.js'),
				array('jquery', 'wp-color-picker'),
				$this->version,
				false
			);
		}
	}
}
