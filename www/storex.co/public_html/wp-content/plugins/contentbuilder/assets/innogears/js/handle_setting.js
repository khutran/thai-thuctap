/**
 * @version    $Id$
 * @package    IGPGBLDR
 * @author     InnoGears Team <support@www.innogears.com>
 * @copyright  Copyright (C) 2012 InnoGears.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.www.innogears.com
 * Technical Support: Feedback - http://www.www.innogears.com/contact-us/get-support.html
 */

(function($) {
	"use strict";

	$.HandleSetting 		= $.HandleSetting || {};
	$.IGModal				= $.IGModal || {};
	$.IG_ImageElement		= $.IG_ImageElement || {};
	$.IG_ImageElement.setImageSize	= $.IG_ImageElement.setImageSize || {};

    // Gradient picker for row
	$.HandleSetting.gradientPicker = function() {
		var gradientPicker = function() {
			var val = $('#param-background').val();

			if (val == 'gradient') {
				$("input.jsn-grad-ex").each(function(i, e) {
					$(e).next('.classy-gradient-box').first().ClassyGradient({
						gradient: $(e).val(),
						width : 218,
						orientation : $('#param-gradient_direction').val(),
						onChange: function(stringGradient, cssGradient, arrayGradient) {
							$(e).val() == stringGradient || $(e).val(stringGradient);

							$('#param-gradient_color_css').val(cssGradient);

							$.HandleSetting.shortcodePreview();
						}
					});
				});
			}
		};

		$(document).ready(function() {
			setTimeout(function() {
				gradientPicker();
			}, 500);
		});

		$('#param-background').change(function() {
			gradientPicker();
		});

		// Control orientation
		$('#param-gradient_direction').on('change', function() {
			var orientation = $(this).val();

			$('.classy-gradient-box').data('ClassyGradient').setOrientation(orientation);

			// Update gradient background
			if (orientation == 'horizontal') {
				$('#param-gradient_color_css').val($('#param-gradient_color_css').val().replace('left top, left bottom', 'left top, right top').replace(/\(top/g,'(left'));
			} else {
				$('#param-gradient_color_css').val($('#param-gradient_color_css').val().replace('left top, right top', 'left top, left bottom').replace(/\(left/g,'(top'));
			}
		});

	};

	// Check radio button when click button in btn-group
	$.HandleSetting.buttonGroup = function() {
		var data_value;

		$('.ig-btn-group .btn').click(function(i) {
			data_value = $(this).attr('data-value');

			$(this).parent().next('.ig-btn-radio').find('input:radio[value="'+data_value+'"]').prop('checked', true);

			$.HandleSetting.shortcodePreview();
		});
	};

	// Validate input of text field with regular expression
	$.HandleSetting.regexTestInput = function(event, regex_str) {
		var regex = new RegExp(regex_str), key = String.fromCharCode(!event.charCode ? event.which : event.charCode);

		if (!regex.test(key) && key != ' ') {
			event.preventDefault();

			return false;
		}

		return true;
	};

	// Validator input field
	$.HandleSetting.inputValidator = function() {
		var input_action = 'change paste';

		// Disable special characters, limit length of title
		$("#param-el_title, input:text[name$='[title]'], [data-role='title'], .ig-pb-limit-length", '#modalOptions').each(function() {
			$(this).prop('maxlength', 50);
		});

		// Number field: allow typing number only
		$("#modalOptions input[type='number']").bind(input_action, function(event) {
			$.HandleSetting.regexTestInput(event, "^[0-9\b\-]+$");
		});

		// Doesn't allow 0 in items_per_ input field
		$("#modalOptions input[id*='items_per']").bind(input_action, function(event) {
			var regex = /^[1-9\b]+$/g, val = regex.test($(this).val());

			if (!val) {
				$(this).val('1');
			}
		});

		// Positive value
		$('.positive-val').bind(input_action, function(event) {
			var this_val = $(this).val();

			if (parseInt(this_val) <= 0) {
				$(this).val(1);
			}
		});
	};

	// Use jquery-te 3rd-party editor for specified classes
	$.HandleSetting.setEditor = function (selector) {
		$(selector).each(function () {
			var _this = $(this);
			$(this).jqte({blur: function(){
				$(_this).trigger('change');
			}});
		});
	}

	// Custom WYSIWYG
	$.HandleSetting.setTinyMCE = function(selector) {
		$(selector).each(function() {
			var current_id = $(this).attr('id');

			if (current_id) {
				$('#' + current_id).wysiwyg({
					controls: {
						bold: {
							visible: true
						},
						italic: {
							visible: true
						},
						underline: {
							visible: true
						},
						strikeThrough: {
							visible: true
						},

						justifyLeft: {
							visible: true
						},
						justifyCenter: {
							visible: true
						},
						justifyRight: {
							visible: true
						},
						justifyFull: {
							visible: true
						},

						indent: {
							visible: true
						},
						outdent: {
							visible: true
						},

						subscript: {
							visible: true
						},
						superscript: {
							visible: true
						},

						undo: {
							visible: true
						},
						redo: {
							visible: true
						},

						insertOrderedList: {
							visible: true
						},
						insertUnorderedList: {
							visible: true
						},
						insertHorizontalRule: {
							visible: true
						},

						h4: {
							visible: true,
							className: 'h4',
							command: ($.browser.msie || $.browser.safari) ? 'formatBlock' : 'heading',
							arguments: ($.browser.msie || $.browser.safari) ? '<h4>' : 'h4',
							tags: ['h4'],
							tooltip: 'Header 4'
						},
						h5: {
							visible: true,
							className: 'h5',
							command: ($.browser.msie || $.browser.safari) ? 'formatBlock' : 'heading',
							arguments: ($.browser.msie || $.browser.safari) ? '<h5>' : 'h5',
							tags: ['h5'],
							tooltip: 'Header 5'
						},
						h6:{
							visible: true,
							className: 'h6',
							command: ($.browser.msie || $.browser.safari) ? 'formatBlock' : 'heading',
							arguments: ($.browser.msie || $.browser.safari) ? '<h6>' : 'h6',
							tags: ['h6'],
							tooltip: 'Header 6'
						},

						cut:{
							visible: true
						},
						copy:{
							visible: true
						},
						paste:{
							visible: true
						},
						html:{
							visible: true
						},
						increaseFontSize:{
							visible: true
						},
						decreaseFontSize:{
							visible: true
						}
					},
					initialContent: '&nbsp;'
				});
			}
		});
	};

	$.HandleSetting.selectImage = function() {
		var _custom_media = true;

		$('#modalOptions .select-media-remove').on('click', function() {
			var _input	= $(this).closest('div').find('input[type="text"]');

			_input.attr('value', '');
			_input.trigger('change');
		});

		$('#modalOptions .select-media').click(function(e) {
			var	button = $(this),
				id = button.attr('id').replace('_button', ''),
				jqueryParent = window.parent.jQuery.noConflict(),
				filter_type = $(this).attr('filter_type'),
				object = {};

			if (typeof(filter_type) != undefined) {
				object.type = filter_type;
			} else {
				object.type = '';
			}

			jqueryParent('#ig-select-media').val(JSON.stringify(object));
			jqueryParent('#ig-select-media').trigger('change');

			var timer = setInterval(function() {
				var currentValue = jqueryParent('#ig-select-media').val();

				if (currentValue) {
					var jsonObject = JSON.parse( currentValue );

					switch ( jsonObject.type ) {
						case 'media_selected':
							if (typeof($.IG_ImageElement.setImageSize) == 'function') {
								$.IG_ImageElement.setImageSize(jsonObject.select_prop);
							}

							$("#"+id).val(jsonObject.select_url);
							$("#"+id).trigger('change');

							clearInterval(timer);
						break;
					}
				}
			}, 500);
		});

		$('#modalOptions .add_media').on('click', function() {
			_custom_media = false;
		});
	};

	$.HandleSetting.updateState = function(state) {
		if (state != null) {
			$.HandleSetting.doing = state;
		} else {
			if ($.HandleSetting.doing == null || $.HandleSetting.doing) {
				$.HandleSetting.doing = 0;
			} else {
				$.HandleSetting.doing = 1;
			}
		}
	};

	$.HandleSetting.renderModal = function() {
		if ($( "#modalOptions" ).length == 0) {
			return false;
		}

		$("#modalOptions").modal('toggle');

		// Sortable sub-elements
		var group_elements = $("#group_elements");

		if (group_elements.length) {
			group_elements.sortable({
				handle: '.element-drag',
				stop: function( event, ui ) {
					$.HandleSetting.shortcodePreview();

					$('body').trigger('on_after_reorder_element');
				}
			});

			group_elements.disableSelection();

			$('body').trigger('on_remove_handle_reorder');
		}

		$.HandleSetting.setColorPicker('#modalOptions .color-selector');
		$.HandleElement.initTooltip('#modalOptions [data-toggle="tooltip"]');

		// Toggle dependency params
		var ig_HasDepend = $( '#modalOptions .ig_has_depend' );

		ig_HasDepend.each(function() {
			if (($(this).is(":radio") || $(this).is(":checkbox")) && !$(this).is(":checked")) {
				return;
			}

			var this_id  = $(this).attr('id'), this_val  = $(this).val();

			$.HandleSetting.toggleDependency(this_id, this_val);
		});
	};

	$.HandleSetting.setColorPicker = function(selector) {
		if (!selector) {
			return false;
		}

		$(selector).each(function() {
			var	self = $(this),
				colorInput = self.siblings('input').last(),
				inputId = colorInput.attr('id'),
				inputValue = inputId.replace(/_color/i, '') + '_value';

			if ($('#' + inputValue).length) {
				$('#' + inputValue).val($(colorInput).val());
			}

			self.ColorPicker({
				color: $(colorInput).val(),
				onShow: function(colpkr) {
					$(colpkr).fadeIn(500);

					return false;
				},
				onHide: function(colpkr) {
					$(colpkr).fadeOut(500);

					$.HandleSetting.shortcodePreview();

					return false;
				},
				onChange: function(hsb, hex, rgb) {
					$(colorInput).val('#' + hex);

					if ($('#' + inputValue).length) {
						$('#' + inputValue).val('#' + hex);
					}

					self.children().css('background-color', '#' + hex);
				}
			});
		});
	};

	$.HandleSetting.selector = function(curr_iframe, element) {
		var $selector = (curr_iframe != null &&  curr_iframe.contents() != null) ? curr_iframe.contents().find(element) : $(element);

		return $selector;
	};

	$.HandleSetting.shortcodePreview = function(params, shortcode, curr_iframe, callback, stop_reload_iframe, child_element) {
		if (($.HandleSetting.selector(curr_iframe,"#modalOptions").length == 0 || $.HandleSetting.selector(curr_iframe,"#modalOptions").hasClass('submodal_frame')) && curr_iframe == null) {
			return true;
		}

		// Change state to ACTIVE
		$.HandleSetting.updateState(1);

		var tmp_content = '', shortcode_name, shortcode_type;

		if (params == null) {
			shortcode_name = $.HandleSetting.selector(curr_iframe, '#modalOptions #shortcode_name' ).val();
			shortcode_type = $.HandleSetting.selector(curr_iframe, '.ig-pb-form-container #shortcode_type' ).val();

			if (shortcode_type == 'widget') {
				// Widget
				var form_serialize = $.HandleSetting.selector(curr_iframe, '#modalOptions #ig-widget-form' ).serialize();

				$('input[type=checkbox]', '#modalOptions #ig-widget-form').each(function() {
					if (!this.checked) {
						form_serialize += '&'+this.name+'=0';
					}
				});

				tmp_content = '[ig_widget widget_id="'+shortcode_name+'"]' + form_serialize + '[/ig_widget]';
			} else {
				var data = $.HandleSetting.traverseParam( $.HandleSetting.selector(curr_iframe, '#modalOptions .control-group' ) );
                var sc_content = data.sc_content;
                var params_arr = data.params_arr;

				// Update TinyMCE content to #ig_share_data
				window.parent.jQuery.noConflict()('#jsn_view_modal').contents().find('#ig_share_data').text(sc_content);
				
				// For shortcode which has sub-shortcode
				if ($.HandleSetting.selector(curr_iframe,"#modalOptions").find('.has_submodal').length > 0 || $.HandleSetting.selector(curr_iframe, '#modalOptions').find('.submodal_frame_2').length > 0) {
					var sub_items_content = [];

					$.HandleSetting.selector(curr_iframe, "#modalOptions [name^='shortcode_content']").each(function() {
						sub_items_content.push($(this).text());
					});
					sc_content += sub_items_content.join('');
				}

				tmp_content = $.HandleSetting.generateShortcodeContent(shortcode_name, params_arr, sc_content);
			}
		} else {
			shortcode_name = shortcode;
			tmp_content = params;
		}
		
		// step_to_track(5, tmp_content);
		// Update shortcode content
		$.HandleSetting.selector(curr_iframe, '#shortcode_content').text(tmp_content).trigger('change');

		if ( ! child_element ) {
			// Update content of Shortcode tab
			$('#shortcode_content', '#shortcode-content').text(tmp_content);
		} else {
			setTimeout( function() {
				// Rescan parent shortcode, to update content of "Shortcode" tab
				$.HandleElement.rescanShortcode();
			}, 300 );
		}

		if (callback) {
			callback();
		}

		// Change state to inactive
		$.HandleSetting.updateState(0);

		// Dont reload preview iframe
		if (stop_reload_iframe) {
			return false;
		}

		var url = Ig_Ajax.ig_modal_url + '&ig_shortcode_preview=1' + '&ig_shortcode_name=' + shortcode_name + '&ig_nonce_check=' + Ig_Ajax._nonce;

		if ($('#shortcode_preview_iframe').length > 0) {
			// Asign value to a variable (for show/hide preview)
			$.HandleSetting.previewData = {
				curr_iframe: curr_iframe,
				url : url,
				tmp_content: tmp_content
			};

			// Load preview iframe
			$.HandleSetting.loadIframe(curr_iframe, url, tmp_content);
		}

		return false;
	};

    /**
     * Traverse parameters, get theirs values
     */
    $.HandleSetting.traverseParam = function( $selector, get_data_tag ){
        var sc_content = '';
        var params_arr = {};

        $selector.each( function ()
        {
            if ( ! $(this).hasClass( 'ig_hidden_depend' ) )
            {
                $(this).find( "[id^='param-']" ).each(function()
                {
                    // Bypass the Copy style group
                    if ( $(this).attr('id') == 'param-copy_style_from' ) {
                        return;
                    }

                    if(
						$(this).parents(".tmce-active").length == 0 && !$(this).hasClass('tmce-active')
						&& $(this).parents(".html-active").length == 0 && !$(this).hasClass('html-active')
						&& !$(this).parents("[id^='parent-param']").hasClass( 'ig_hidden_depend' )
						&& $(this).attr('id').indexOf('parent-') == -1
                    )
                    {
                        var id = $(this).attr('id');
                        if($(this).attr('data-role') == 'content'){
                            sc_content =  $(this).val().replace(/\[/g,"&#91;").replace(/\]/g,"&#93;");
                        }else{
                            if(($(this).is(":radio") || $(this).is(":checkbox")) && !$(this).is(":checked"));
                            else{
                                if(!params_arr[id.replace('param-','')] || id.replace('param-', '') == 'title_font_face_type' || id.replace('param-', '') == 'title_font_face_value' || id.replace('param-','') == 'font_face_type' || id.replace('param-','') == 'font_face_value' || id.replace('param-', '') == 'image_type_post' || id.replace('param-', '') == 'image_type_page' || id.replace('param-', '') == 'image_type_category' ) {
                                    params_arr[id.replace('param-','')] = $(this).val();
                                } else {
                                    params_arr[id.replace('param-','')] += '__#__' + $(this).val();
                                }

                            }
                        }

                        if( get_data_tag == null || get_data_tag ) {
                            // data-share
                            if($(this).attr('data-share')){
                                var share_element = $('#' + $(this).attr('data-share'));
                                var share_data = share_element.text();
                                if(share_data == "" || share_data == null)
                                    share_element.text($(this).val());
                                else{
                                    share_element.text(share_data + ',' + $(this).val());
                                    var arr = share_element.text().split(',');
                                    $.unique( arr );
                                    share_element.text(arr.join(','));
                                }

                            }

                            // data-merge
                            if($(this).parent().hasClass('merge-data')){
                                var ig_merge_data = window.parent.jQuery.noConflict()( '#jsn_view_modal').contents().find('#ig_merge_data');
                                ig_merge_data.text(ig_merge_data.text() + $(this).val());
                            }

                            // table
                            if($(this).attr("data-role") == "extract"){
                                var extract_holder = window.parent.jQuery.noConflict()( '#jsn_view_modal').contents().find('#ig_extract_data');
                                extract_holder.text(extract_holder.text()+$(this).attr("id")+':'+$(this).val()+'#');
                            }
                        }
                    }

                });
            }
        });

        return { sc_content : sc_content, params_arr : params_arr };
    }

    /**
     * Generate shortcode content
     */
    $.HandleSetting.generateShortcodeContent = function(shortcode_name, params_arr, sc_content){
        var tmp_content = [];

        tmp_content.push('['+ shortcode_name);
        // wrap key, value of params to this format: key = "value"
        $.each(params_arr, function(key, value){
            if ( value ) {
                if ( value instanceof Array ) {
                    value = value.toString();
                }
                tmp_content.push(key + '="' + value.replace(/\"/g,"&quot;").replace(/\[/g,"").replace(/\]/g,"") + '"');

            }
        });
		// step_to_track(6,tmp_content);
        tmp_content.push(']' + sc_content + '[/' + shortcode_name + ']');
        tmp_content	= tmp_content.join( ' ' );

        return tmp_content;
    }

	// Load preview iframe
	$.HandleSetting.loadIframe = function(curr_iframe, url, tmp_content) {
		$('#ig_preview_data').remove();

		var tmp_form = $(
			'<form action="' + url + '" id="ig_preview_data" name="ig_preview_data" method="post" target="shortcode_preview_iframe">' +
				'<input type="hidden" id="ig_preview_params" name="params" value="' + encodeURIComponent(tmp_content) + '">' +
			'</form>'
		).appendTo($('body'));

		$.HandleSetting.selector(curr_iframe, '#modalOptions #ig_overlay_loading').fadeIn('fast');

		$('#ig_preview_data').submit();

		$('#modalOptions #shortcode_preview_iframe').bind('load', function() {
			$('#modalOptions #shortcode_preview_iframe').fadeIn('fast');

			$.HandleSetting.selector(curr_iframe, '#modalOptions #ig_overlay_loading').fadeOut('fast');

			$('#ig_previewing').val('0');
		});

		tmp_form.remove();
	};

	// Hide/show preview
	$.HandleSetting.togglePreview = function() {
		$('#previewToggle *').click(function() {
			if ($(this).attr('id') == 'hide_preview') {
				$(this).addClass('hidden');
				$('#show_preview').removeClass('hidden');

				// Remove iframe
				$('#preview_container iframe').remove();
			} else {
				$(this).addClass('hidden');
				$('#hide_preview').removeClass('hidden');

				$('#preview_container').append(
					"<iframe scrolling='no' id='shortcode_preview_iframe' name='shortcode_preview_iframe' class='shortcode_preview_iframe'></iframe>"
				);

				if ($.HandleSetting.previewData != null) {
					var data = $.HandleSetting.previewData;

					$.HandleSetting.loadIframe(data.curr_iframe, data.url, data.tmp_content);
				}
			}
		});
	};

	// Show/hide dependency params
	$.HandleSetting.toggleDependency = function(this_id, this_val) {
		if (!this_id || !this_val) {
			return;
		}

		$('#modalOptions .ig_depend_other[data-depend-element="' + this_id + '"]').each(function() {
			var operator = $(this).attr('data-depend-operator'), compare_value = $(this).attr('data-depend-value');

			switch(operator) {
				case "=":
					var check_ = 0;

					if (compare_value.indexOf('__#__') > 0) {
						var values_ = compare_value.split('__#__');

						check_ = ($.inArray(this_val, values_) >= 0);
					} else {
						check_  = (this_val == compare_value);
					}

					if (check_) {
						$(this).removeClass('ig_hidden_depend');
					} else {
						$(this).addClass( 'ig_hidden_depend' );
					}
				break;

				case ">":
					if (this_val > compare_value) {
						$(this).removeClass('ig_hidden_depend');
					} else {
						$(this).addClass('ig_hidden_depend');
					}
				break;

				case "<":
					if (this_val < compare_value) {
						$(this).removeClass('ig_hidden_depend');
					} else {
						$(this).addClass('ig_hidden_depend');
					}
				break;

				case "!=":
					if (this_val != compare_value) {
						$(this).removeClass('ig_hidden_depend');
					} else {
						$(this).addClass('ig_hidden_depend');
					}
				break;
			}

			$.HandleSetting.secondDependency($(this).attr('id'), $(this).hasClass('ig_hidden_depend'), $(this).find('select').hasClass('no_plus_depend'));
		});
	};

	// Show/hide 2rd level dependency elements
	$.HandleSetting.secondDependency = function(this_id, hidden, allow) {
		if (!this_id) {
			return;
		}

		this_id = this_id.replace('parent-','');

		$('#modalOptions .ig_depend_other[data-depend-element="'+this_id+'"]').each(function() {
			if (hidden) {
				$(this).addClass('ig_hidden_depend2');
			} else {
				$(this).removeClass('ig_hidden_depend2');
			}
		});

		if (!allow) {
			$('#modalOptions .ig_depend_other[data-depend-element="'+this_id+'"]').each(function() {
				$(this).removeClass('ig_hidden_depend2');
			});
		}

		// Hide label if all options in .controls div have 'ig_hidden_depend' class
		$('#modalOptions .controls').each(function() {
			var hidden_div = 0;

			$(this).children().each(function() {
				if ($(this).hasClass('ig_hidden_depend')) {
					hidden_div++;
				}
			});

			if (hidden_div > 0 && hidden_div == $(this).children().length) {
				$(this).parent('.control-group').addClass('margin0');
				$(this).prev('.control-label').hide();
			} else {
				$(this).parent('.control-group').removeClass('margin0');
				$(this).prev('.control-label').show();
			}
		});
	};

	// Set change event of dependency elements
	$.HandleSetting.changeDependency = function(dp_selector) {
		if (!dp_selector) {
			return false;
		}

		$('.ig-pb-form-container').delegate(dp_selector, 'change', function() {
			var this_id = $(this).attr('id'), this_val	= $(this).val();

			$.HandleSetting.toggleDependency(this_id, this_val);
		});
	};

	// Show tab in Modal Options
	$.HandleSetting.tab = function() {
		if ($('.jsn-tabs').length && !$('.jsn-tabs').find("#Notab").length) {
			$('.jsn-tabs').tabs();

			$('#ig_option_tab a[data-toggle="tab"]').on('click', function() {
				var href = $(this).attr('href');

				if (href == '#shortcode-content') {
					$('#shortcode_content').attr('disabled', 'disabled');
				} else {
					$('#shortcode_content').removeAttr('disabled');

					if (href == '#styling') {
						if ($('#ig_previewing').val() == '1') {
							return;
						}

						$('#ig_previewing').val('1');

						$.HandleSetting.shortcodePreview();
					}
				}
			});

			$('#shortcode_content').removeAttr('disabled');
		}

		return true;
	};

	$.HandleSetting.select2 = function() {
		$(".select2").each(function() {
			var share_element = window.parent.jQuery.noConflict()( '#jsn_view_modal').contents().find('#' + $(this).attr('data-share')), share_data = [];

			if (share_element && share_element.text() != "") {
				share_data = share_element.text().split(',');
				share_data = $.unique(share_data);
			}

			$(this).css('width','300px');

			$(this).select2({
				tags: share_data,
				maximumInputLength: 10
			});
		});

		$('.select2-select').each(function() {
			if ( $(this).is( 'select' ) ) {
				var id = $(this).attr('id');

				if ($('#' + id + '_select_multi').val()) {
					var arr_select_multi = $('#' + id + '_select_multi').val().split('__#__');

					$(this).val(arr_select_multi).select2({
						minimumResultsForSearch: -1
					});
				} else {
					var parent_selector = '';
					parent_selector = $(this).parent('div[id^="parent-param-"]');
					// Only show select2 search textbox for post and page listing
					if ( $(parent_selector).attr('data-depend-value') == 'post' || $(parent_selector).attr('data-depend-value') == 'page' ) {
						$(this).select2();
					} else {
						$(this).select2({
							minimumResultsForSearch: -1
						});
					}
				}
			}
		});

		$.HandleSetting.select2_color();
	};

	$.HandleSetting.select2_color = function() {
		function format(state) {
			if (!state.id) {
				// optgroup
				return state.text;
			}

			var type = state.id.toLowerCase().split('-');

			type = type[type.length - 1];

			return "<img class='color_select2_item' src='" + Ig_Translate.asset_url + "images/icons-16/btn-color/" + type + ".png'/>" + state.text;
		}

		$('.color_select2').not('.hidden').each(function() {
			$(this).find('select').each(function() {
				$(this).select2({
					minimumResultsForSearch: -1,
					formatResult: format,
					formatSelection: format,
					escapeMarkup: function(m) {
						return m;
					}
				});
			});
		});
	};

	// Handle icon change action in Modal box
	$.HandleSetting.icons = function() {
		// Icon type: handle icon click
		$('body').on("click", ".ig-pb-form-container [data-type='ig-icon-item']", function() {
			$(".controls .icon-selected").each(function() {
				$(this).removeClass('icon-selected');
			});

			// Update selected icon
			var	$selected_icon = $(this).attr('class').replace('icon-selected', '').replace(' ',''),
				icon = $(this).parent('li').parent('ul').next("input[id^='param']");

			icon.val($selected_icon);
			icon.trigger('change');

			$(this).addClass('icon-selected');
		});
	};

	// Handle click action on Button in Modal: Convert action/ Add Row, Column / ...
	$.HandleSetting.actionHandle = function() {
		// Handle Convert To ... button
		$('body').on("click", ".ig-pb-form-container .ig_action_btn a", function(e) {
			e.preventDefault();

			var action_type = $(this).attr('data-action-type'), relation = $(this).attr('data-action');

			if (action_type && relation) {

				// Mark the element to be converted
				$('.active-shortcode').addClass('ig_to_convert');

				// Save data to convert
				$.PbDoing.action_data = {'action': action_type, 'relation': relation};

				$('button#selected', '.ig-dialog').trigger('click');
			}
		});
	};

	$.HandleSetting.closeAllSelect2WhenClickModal = function () {
		$('.ig-dialog').on("click", function(e) {
    		var el = $(e.target);
    		if (el.parents(".select2-container").length == 0) {
    			$(".select2-dropdown-open").select2("close");
    		}
		});
	};

	// Handle Copy to Clipboard action
	$.HandleSetting.copyToClipboard = function() {
		var textarea = $("#shortcode_content"), button = $("#copy_to_clipboard"), text_change = button.data('textchange');

		if (textarea.length && button.length) {
			ZeroClipboard.config({ moviePath: Ig_Ajax.assets_url + '/assets/3rd-party/zeroclipboard/ZeroClipboard.swf' });

			var client = new ZeroClipboard(button);

			client.on("datarequested", function() {
				// Copy shortcode content to clipboard
				client.setText(textarea.val());

				// Show message
				button.addClass("disabled").attr("disabled", "disabled");

				// get current text
				var cache_text = button.text();

				// Change text button
				button.text(text_change);

				// Schedule hiding the message
				setTimeout(function() {
					button.removeClass("disabled").removeAttr("disabled");
					$("i", button).animate({"opacity": "0"}, 500, function () { $(this).hide(); });
					button.text(cache_text);
				}, 1000);
			});
		}
	};

	/**
	 * Copy Style from other same-type element
	 */
	$.HandleSetting.initCopyStyle = function () {
		if ($('#modalOptions #param-copy_style_from').length <= 0) {
			return;
		}
		var copy_style_from = $('#modalOptions #param-copy_style_from').select2({minimumResultsForSearch: -1});
		var shortcode_name   = $('#modalOptions #shortcode_name').val();

		if (copy_style_from.length > 0) {
			// Append Copy action button

			var copy_button     = $('<button/>', {'class': 'btn btn-default btn-sm',
													'id': 'copy_style_button',
													'html': Ig_Translate.take_style,
													'style': 'margin-left: 10px'
												});
			copy_style_from.after(copy_button);
		}
		copy_style_from.parents('.control-group').after($('<hr/>'));
		// Init action for copy style button
		copy_button.on('click', function () {
			var shortcode_content  = $('#shortcode_content').val();
			var new_style_content  = copy_style_from.val();
			var setting_form       = $('#frm_shortcode_settings');
			var loading;
			// Init loading overlay inside modal
			loading = $('<div/>');
			loading.append($('<div/>', {
				'class': 'jsn-modal-overlay',
				'style': 'z-index: 1000; display: block;'
			}))
			.append(
				$('<div/>', {
					'class': 'jsn-modal-indicator',
					'style': 'display:block'
				})
			);

			if (typeof(ig_pb_modal_ajax) != 'undefined') {
				if (ig_pb_modal_ajax == 1) {
					setting_form.parents('#modalOptions').css('visibility', 'hidden');
					setting_form.parents('.jsn-modal').append(loading);
				}
			}else{
				$('body').append(loading);
			}

			$.post(
					Ig_Ajax.ajaxurl,
					{
						action : 'merge_style_params',
						content: shortcode_content,
						new_style_content: new_style_content,
						shortcode_name: shortcode_name,
						ig_nonce_check : Ig_Ajax._nonce
					},
					function( data ) {
						$('#frm_shortcode_settings #hid-params').val(data);
							if (typeof(ig_pb_modal_ajax) != 'undefined') {
								if (ig_pb_modal_ajax == 1) {
									// If the modal was initted from ajax
									// then load the modal content by ajax
									$.ajax({
										url: Ig_Ajax.ig_modal_url + '&ig_modal_type=' + shortcode_name + '&form_only=1',
										data: setting_form.serializeArray(),
										type: 'POST',
										dataType: 'html',
										complete: function(data, status) {
											if (status == 'success') {
												setting_form.parents('.jsn-modal').html(data.responseText);
											}
										}
									});
								}
							}else{
								// If the modal was initted by iframe
								// just only pass data by submitting the form
								setting_form.submit();
							}

					}
				);
		});
		// Get the same type elements list.
		$.post(
				Ig_Ajax.ajaxurl,
				{
					action : 'get_same_elements',
					content : window.parent.jQuery.HandleElement.getContent(),
					shortcode_name: shortcode_name,
					ig_nonce_check : Ig_Ajax._nonce
				},
				function( data ) {
					if (data) {
						copy_style_from.html(data);
					}else{
						copy_style_from.parents('.control-group').hide();
						copy_style_from.parents('.control-group').next('hr').hide();
					}

				}
			);


	}

	$.HandleSetting.init = function() {
		$.HandleSetting.togglePreview();

		$.HandleSetting.updateState();

		$.HandleSetting.tab();

		// Trigger action of element which has dependency elements
		$.HandleSetting.changeDependency('.ig_has_depend');

		// Update preview when change param in Modal Box/Styling
		$('#modalOptions').delegate('[id^="param"]', 'change', function() {
			if ($(this).attr('data-role') == 'no_preview') {
				return false;
			}
			if ($(this).attr('id') == 'param-copy_style_from' ) {
				return false;
			}

			var	parent_tab = $(this).parents('.ig-pb-setting-tab'),
				stop_reload_iframe = ((parent_tab.length > 0 && parent_tab.is("#styling")) || (parent_tab.length > 0 && parent_tab.is("#modalAction"))) ? 0 : 1;

			$.HandleSetting.shortcodePreview(null, null, null, null, stop_reload_iframe);
		});

		// Trigger preview for parameters of Widget
		$('#modalOptions #ig-widget-form').delegate('input, select, textarea', 'change', function() {
			$.HandleSetting.shortcodePreview();
		});

		// Send Ajax request for loading shortcode html at first time
		$.HandleSetting.renderModal();

		$.HandleSetting.select2();

		$.HandleSetting.icons();

		$.HandleSetting.actionHandle();

		$.HandleSetting.selectImage();

		$.HandleSetting.gradientPicker();

		$.HandleSetting.buttonGroup();

		$.HandleSetting.inputValidator();

		$.HandleSetting.setTinyMCE('.ig_pb_tiny_mce');

		$.HandleSetting.setEditor( '.ig_pb_editor' );

		$.HandleSetting.initCopyStyle();

		if ($('#shortcode_name').val() != 'ig_image') {
			$('select option[value="large_image"]').hide();
		}

		// Init Copy to Clipboard action
		$.HandleSetting.copyToClipboard();

		$.HandleSetting.closeAllSelect2WhenClickModal();
	};
})(jQuery);
