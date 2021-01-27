<?php defined('ABSPATH') || exit;

class Mb_Breaking_News_i18n
{
    public function load_plugin_textdomain()
    {
        load_plugin_textdomain(
            'mb-breaking-news',
            false,
            dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );
    }
}
