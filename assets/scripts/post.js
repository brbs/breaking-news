/* eslint-disable */

import Flatpickr from 'flatpickr';

jQuery(document).ready(function($) {

	const date = new Date();
	const getMinTime = function() {
		return date.getHours() + ':' + (date.getMinutes() + 2);
	};

	const fp = Flatpickr('.mbbn-datepicker', {
		enableTime: true,
		altInput: true,
		dateFormat: 'U',
		altFormat: 'Y-m-d H:i',
		minDate: 'today',
		minTime: getMinTime(),
		time_24hr: true,
	});

	$('#mbbn-expiration').on('change', function() {
		let $this = $(this);

		if($this.is(':checked')) {
			$('.mbbn-show-if-checked').show();
			fp.open();
		}
		else {
			$('.mbbn-show-if-checked').hide();
			fp.close();
		}
	});

	$('#mbbn-datepicker-btn').on('click', () => fp.open());
});
