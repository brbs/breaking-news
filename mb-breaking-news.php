<?php

/**
 *
 * @link              https://brbs.works
 * @since             1.0.0
 * @package           Mb_Breaking_News
 *
 * @wordpress-plugin
 * Plugin Name:       Breaking News
 * Plugin URI:        https://brbs.works
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Marin Barbic
 * Author URI:        https://brbs.works
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mb-breaking-news
 * Domain Path:       /languages
 */

if (!defined('WPINC')) {
    die;
}

define('MB_BREAKING_NEWS_VERSION', '1.0.0');
define('MB_BREAKING_NEWS_FILE', __FILE__);

function activate_mb_breaking_news()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-mb-breaking-news-activator.php';
    Mb_Breaking_News_Activator::activate();
}

function deactivate_mb_breaking_news()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-mb-breaking-news-deactivator.php';
    Mb_Breaking_News_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_mb_breaking_news');
register_deactivation_hook(__FILE__, 'deactivate_mb_breaking_news');

require plugin_dir_path(__FILE__) . 'includes/class-mb-breaking-news.php';

function run_mb_breaking_news()
{
    $plugin = new Mb_Breaking_News();
    $plugin->run();
}
run_mb_breaking_news();
