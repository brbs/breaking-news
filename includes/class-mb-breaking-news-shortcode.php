<?php defined('ABSPATH') || exit;

class Mb_Breaking_News_Shortcode
{
    public static function init()
    {
        add_shortcode('mbbn_breaking_news', array(__CLASS__, 'output'));
    }

    public static function output($atts, $content = null)
    {
        $atts = shortcode_atts(
            array(),
            $atts,
            'mbbn_breaking_news'
        );

        $post = self::get_post();

        if (is_admin()) {
            $output = self::output_admin($post);
        } else {
            $output = self::output_public($post);
        }

        return $output;
    }

    public static function output_admin($post = null)
    {
    	echo '<h2>' . __('Active News', 'mb-breaking-news') . '</h2>';

        if ($post) {
            $output = sprintf(
                '<p class="mbbn-breaking">%s <a href="%s">Edit</a></p>',
                $post->post_title,
                esc_url(admin_url('post.php?action=edit&post=' . $post->ID ))
            );
        } else {
            $output = sprintf(
                '<p class="mbbn-breaking empty">%s</p>',
                __('No breaking news found', 'mbbn-breaking-news')
            );
        }

        return $output;
    }

    public static function output_public($post = null)
    {
        $output = '';

        if ($post) {
            $options = get_option('mbbn_options');

            $post_id    = $post->ID;
            $post_title = get_post_meta($post_id, '_mbbn_title', true);

            if (empty($post_title)) {
                $post_title = $post->post_title;
            }

            $section_title = !empty($options['title']) ? $options['title'] : __('BREAKING NEWS', 'mb-breaking-news');

            $output = sprintf(
                '<div class="breaking news">%s: <a href="%s">%s</a></div>',
                esc_html($section_title),
                esc_url(get_permalink($post_id)),
                esc_html($post_title)
            );
        }

        return $output;
    }

    public static function get_post()
    {

        $args = array(
            'post_type'      => 'post',
            'post_status'    => 'publish',
            'posts_per_page' => 1,
            'no_found_rows'  => true,
            'meta_query'     => array(
                'relation'          => 'AND',
                'breaking_clause'   => array(
                    'key'   => '_mbbn_breaking',
                    'value' => 1,
                ),
                'activation_clause' => array(
                    'key'  => '_mbbn_activation_date',
                    'type' => 'DATETIME',
                ),
            ),
            'orderby'        => array(
                'activation_clause' => 'DESC',
            ),
        );

        $query = new WP_Query($args);

        if (empty($query->post)) {
            return false;
        }

        return $query->post;
    }
}
