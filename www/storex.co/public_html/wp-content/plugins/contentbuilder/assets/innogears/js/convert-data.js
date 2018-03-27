/**
 * @version    $Id$
 * @package    IG_PageBuilder
 * @author     InnoGears Team <support@innogears.com>
 * @copyright  Copyright (C) 2012 InnoGears.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.innogears.com
 */

(function($) {
	$.IG_Data_Conversion = {
		init: function() {
			var self = this;

			// Get data conversion modal and necessary data
			this.modal = $('#data-conversion-modal');
			this.title = this.modal.find('.modal-header *[data-title]').attr('data-title');
			this.notice = this.modal.find('.modal-body .alert').html();
			this.buttons = this.modal.find('.modal-footer a.btn');

			// Init cancel button
			this.buttons.filter('#data-conversion-cancel').click(function() {
				// Check button status
				if ($(this).hasClass('disabled')) {
					return false;
				}

				self.modal.modal('hide');

				// Reset notice in modal body
				self.modal.find('.modal-body .alert').html(self.notice);
			});

			// Setup data conversion links
			$('#data-conversion a[data-toggle="modal"]').click(function(event) {
				event.preventDefault();

				// Get requested converter
				var converter = $(this).attr('id').replace(/^convert-([^\s]+)-data$/, '$1');

				// Enable buttons
				self.buttons.removeClass('disabled');

				// Setup convert button
				self.buttons.filter('#data-conversion-convert').unbind('click').bind('click', function(event) {
					event.preventDefault();

					// Check button status
					if ($(this).hasClass('disabled')) {
						return false;
					}

					// Disable buttons
					self.buttons.addClass('disabled');

					// Toggle button status
					$(this).removeClass('btn-primary').addClass('btn-default');
					$(this).children().addClass('ig-loading').text($(this).children().attr('data-working-text'));

					// Get current post ID
					var post_id = $('input#post_ID').val();

					// Request server-side script to copy post then convert data
					$.ajax({
						url: 'admin-ajax.php?action=ig-pb-convert-data&post=' + post_id + '&converter=' + converter,
						data: self.modal.find('.modal-body input').serializeArray(),
						complete: $.proxy(function(request, status) {
							// Parse response data
							if (response = request.responseText.match(/\{"success":[^,]+,"message":[^\}]+\}/)) {
								response = $.parseJSON(response[0]);
							} else {
								response = {success: false, message: ''};
							}

							// Toggle button status
							$(this).removeClass('btn-default').addClass('btn-primary');
							$(this).children().removeClass('ig-loading').text($(this).children().attr(response.success ? 'data-complete-text' : 'data-default-text'));

							if (response.success) {
								// Remove all unload event handler
								$(window).off('unload').unbind('unload');

								// Redirect to edit page of the newly created post
								window.location.href = window.location.href.replace(/post=\d+/, 'post=' + response.message);
							} else {
								// Display message
								if (response.message && response.message != '') {
									alert(response.message);
								}
							}
						}, this)
					});
				}).children().text($(this).children().attr('data-default-text'));

				// Set modal title
				self.modal.find('.modal-header h3').text(self.title.replace('%NAME%', $(this).text()));

				// Update notice in modal body
				self.modal.find('.modal-body .alert').html(self.notice.replace('%NAME%', $(this).text()));

				// Show data conversion modal
				self.modal.modal('show');
			})
		},
	};

	$(document).ready(function() {
		$.IG_Data_Conversion.init();
	});
})(jQuery);
