<?php defined('ABSPATH') || exit;

class Mb_Breaking_News
{
	protected $loader;

	protected $plugin_name;

	protected $version;

	public function __construct()
	{
		if (defined('MB_BREAKING_NEWS_VERSION')) {
			$this->version = MB_BREAKING_NEWS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'mb-breaking-news';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	private function load_dependencies()
	{
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/utils/assets.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/utils/helpers.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-mb-breaking-news-loader.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-mb-breaking-news-i18n.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-mb-breaking-cron.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-mb-breaking-news-shortcode.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-mb-breaking-news-settings.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-mb-breaking-news-post-settings.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-mb-breaking-news-public.php';

		$this->loader = new Mb_Breaking_News_Loader();

		Mb_Breaking_News_Shortcode::init();
		Mb_Breaking_News_Cron::init();
	}

	private function set_locale()
	{
		$plugin_i18n = new Mb_Breaking_News_i18n();
		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
	}

	private function define_admin_hooks()
	{
		$plugin_settings      = new Mb_Breaking_News_Settings($this->get_plugin_name(), $this->get_version());
		$plugin_post_settings = new Mb_Breaking_News_Post_Settings($this->get_plugin_name(), $this->get_version());

		// settings
		$this->loader->add_action('admin_menu', $plugin_settings, 'add_menu_page');
		$this->loader->add_action('admin_init', $plugin_settings, 'settings_init');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_settings, 'enqueue_assets');

		// metaboxes
		$this->loader->add_action('add_meta_boxes', $plugin_post_settings, 'add_meta_boxes');
		$this->loader->add_action('save_post', $plugin_post_settings, 'save_post', 10, 2);
		$this->loader->add_action('admin_enqueue_scripts', $plugin_post_settings, 'enqueue_assets');
		$this->loader->add_action('admin_notices', $plugin_post_settings, 'output_errors');
		$this->loader->add_action('shutdown', $plugin_post_settings, 'save_errors');
	}

	private function define_public_hooks()
	{
		$plugin_public = new Mb_Breaking_News_Public($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('wp_head', $plugin_public, 'custom_styles');
		$this->loader->add_action('wp_body_open', $plugin_public, 'do_shortcode', 99);

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_assets' );
	}

	public function run()
	{
		$this->loader->run();
	}

	public function get_plugin_name()
	{
		return $this->plugin_name;
	}

	public function get_loader()
	{
		return $this->loader;
	}

	public function get_version()
	{
		return $this->version;
	}
}
