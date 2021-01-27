<?php defined('ABSPATH') || exit;

$title           = get_post_meta($post_id, '_mbbn_title', true);
$breaking        = get_post_meta($post_id, '_mbbn_breaking', true);
$expiration_date = get_post_meta($post_id, '_mbbn_expiration_date', true);
$expiration_date = empty($expiration_date) ? '' : strtotime($expiration_date);
$expiration      = empty($expiration_date) ? false : true; ?>

<div class="mbbn-row">
	<input
		id="mbbn-breaking-news"
		class="mbbn-input-checkbox"
		type="checkbox"
		name="mbbn-breaking"
		<?php checked($breaking, 1, true);?>
		value="true">
	<label class="mbbn-label" for="mbbn-breaking-news"><?php echo __('Make this post breaking news', $this->plugin_name); ?></label>
</div>

<div class="mbbn-row">
	<label class="mbbn-label" for="mbbn-custom-title"><?php echo __('Set custom title', $this->plugin_name); ?></label>
	<input
		id="mbbn-custom-title"
		class="mbbn-input-text"
		type="text"
		name="mbbn-title"
		value="<?php echo esc_attr($title); ?>">
</div>

<div class="mbbn-row">
	<input
		id="mbbn-expiration"
		class="mbbn-input-checkbox"
		type="checkbox"
		name="mbbn-expiration"
		<?php checked($expiration, 1, true);?>
		value="<?php echo esc_attr($expiration); ?>">
	<label class="mbbn-label" for="mbbn-expiration"><?php echo __('Set expiration date?', $this->plugin_name); ?></label>
</div>

<div class="mbbn-row mbbn-show-if-checked<?php if (!$expiration) {
	echo ' hidden';
}?>">
	<div>
		<input
			id="mbbn-expiration-date"
			class="mbbn-input-text mbbn-datepicker"
			type="text"
			name="mbbn-expiration-date"
			readonly
			value="<?php echo esc_attr($expiration_date) ?>">
		<button id="mbbn-datepicker-btn" type="button" class="button-secondary">Open</button>
	</div>
</div>

<?php
