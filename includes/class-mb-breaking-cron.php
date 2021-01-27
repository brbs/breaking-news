<?php defined('ABSPATH') || exit;

class Mb_Breaking_News_Cron
{
    public static function init()
    {
        add_action('mbbn_clear_breaking_news', array(__CLASS__, 'clear_breaking_news'));
    }

    public static function schedule_event($post_id, $timestamp)
    {
        if (wp_next_scheduled('mbbn_clear_breaking_news', array($post_id))) {
            wp_clear_scheduled_hook('mbbn_clear_breaking_news', array($post_id));
        }

        wp_schedule_single_event($timestamp, 'mbbn_clear_breaking_news', array($post_id));
    }

    public static function clear_breaking_news($post_id)
    {
    	$post_id = absint($post_id);

        update_post_meta($post_id, '_mbbn_breaking', '');
        update_post_meta($post_id, '_mbbn_expiration_date', '');
        update_post_meta($post_id, '_mbbn_activation_date', '');
    }
}
