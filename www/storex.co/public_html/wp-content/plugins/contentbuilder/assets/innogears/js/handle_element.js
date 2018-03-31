(function($) {
	"use strict";
	$.IGModal = $.IGModal || {};
	$.HandleElement = $.HandleElement || {};
	$.PbDoing = $.PbDoing || {};
	$.HandleSetting = $.HandleSetting || {};

	$.options = {
		min_column_span : 2,
		layout_span : 12,
		new_sub_element : false,
		curr_iframe_ : null,
		clicked_column : null,
		if_childmodal : 0,
		modal_settings : {
			modalId: 'jsn_view_modal'
		},
		effect: 'easeOutCubic'
	}

	var clk_title_el , append_title_el;
	var el_type; // save type of editing shortcode: element/widget
	var input_enter;

	/**
	 * 1. Common
	 * 2. Resizable
	 * 3. PageBuilder
	 * 4. Modal
	 */

	/***************************************************************************
	 * 1. Common
	 **************************************************************************/

	// alias for jQuery
	$.HandleElement.selector = function(curr_iframe, element) {
		var $selector = (curr_iframe != null && curr_iframe.contents() != null) ? curr_iframe.contents().find(element) : window.parent.jQuery.noConflict()(element);
		return $selector;
	},

	// Capitalize first character of whole string
	$.HandleElement.capitalize = function(text) {
		return text.charAt(0).toUpperCase()
		+ text.slice(1).toLowerCase();
	},

	// Capitalize first character of each word
	$.HandleElement.ucwords = function(text) {
		return (text + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
			return $1.toUpperCase();
		});
	},

	// Remove underscore character from string
	$.HandleElement.remove_underscore_ucwords = function(text) {
		var arr = text.split('_');
		return $.HandleElement.ucwords( arr.join(' ') ).replace(/^(Wp)\s+/g, '');
	},

	// Strip HTML tag from string
	$.HandleElement.strip_tags = function(input, allowed) {
		// Make sure the allowed argument is a string containing only tags in lowercase (<a><b><c>)
		allowed = (((allowed || '') + '').toLowerCase().match(/<[a-z][a-z0-9]*>/g) || []).join('');

		var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi, commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;

		return input.replace(commentsAndPhpTags, '').replace(tags, function($0, $1) {
			return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
		});
	},

	// Get n first words of string
	$.HandleElement.sliceContent = function(text, limit) {
		text = unescape(text);
		text = text.replace(/\+/g, ' ');
		text = $.HandleElement.strip_tags(text);

		var arr = text.split(' ');
			arr = arr.slice(0, limit ? limit : 10);
		return arr.join(' ');
	},

	// Get cookie value by key
	$.HandleElement.getCookie = function ( c_name ) {
		if ( ! c_name )
			return null;
		c_name = c_name + "=";
		var ca = document.cookie.split(';');
		for(var i=0;i < ca.length;i++) {
			var c = ca[i];
			while (c.charAt(0)==' ') c = c.substring(1,c.length);
			if (c.indexOf(c_name) == 0) return c.substring(c_name.length,c.length);
		}
		return null;
	},

	// Store cookie data
	$.HandleElement.setCookie = function ( c_name, c_value ) {
		c_value = c_value + ";max-age=" + 60 * 3 + ";path=/";
		document.cookie	= c_name + "=" + c_value;
	},

	// Remove cookie
	$.HandleElement.removeCookie = function ( c_name ) {
		if ( ! c_name )
			return null;
		document.cookie = c_name + "=;max-age=0;path=/";
	}
	/**
	 * Show tooltip
	 */
	$.HandleElement.initTooltip = function ( selector, gravity ) {
		if ( ! selector ) {
			return false;
		}

		// Init tooltip
		$(selector).tooltip({
			placement: gravity ? gravity : 'right',
			html: true,
		});

		return true;
	};

	/*******************************************************************
	 * 3. PageBuilder
	 ******************************************************************/

	/**
	 * add Element to IG PageBuilder when click on an element in Add Elements Popover
	 */
	$.HandleElement.addElement = function() {
		$("body").delegate(".ig-add-element .shortcode-item","click",function(e) {
			_self(e, this);
		});
		$("#ig-add-element").delegate(".shortcode-item","click",function(e) {
			_self(e, this);
		});

		function _self(e, this_){
			e.preventDefault();
			var $shortcode = $(this_).attr('data-shortcode');
			var $type = $(this_).parent().attr('data-type');

			if($.PbDoing.addElement)
				return;
			$.PbDoing.addElement = 1;

			// check if adding shortcode from button in Classic Editor
			if($(this_).parents('#ig-shortcodes').length)
				top.addInClassic = 1;

			// Check if user is adding raw shortcode
			if ($(this_).attr('data-shortcode') == 'raw') {
				return $.HandleElement.appendToHolder(this_, null, 'raw');
			}

			$("#ig-add-element").hide();
			$.HandleElement.showLoading();

			// Get title of clicked element
			clk_title_el = $.trim($(this_).html().replace(/<i\sclass.*><\/i>/, ''));

			// Append element to PageBuilder

			$.HandleElement.appendToHolder($shortcode, null, $type);
		}
	},

	/**
	 * Add sub Item on Modal setting of an element (Accordion, Tab, Carousel...)
	 */
	$.HandleElement.addItem = function() {
		// Add Element in Pagebuilder
		$(".ig-pb-form-container").delegate(".ig-more-element","click",function(e) {
			_self(e, this);
		});

		// Add Item in Modal
		$("body").delegate(".ig-more-element","click",function(e) {
			_self(e, this);
		});

		function _self(e, this_){
			e.preventDefault();

			$.options.clicked_column = $(this_).parent('.item-container').find('.item-container-content');
			// add item in Accordion/ List ...
			if ($(this_).attr('data-shortcode-item') != null) {
				$.HandleElement.showLoading();

				$.options.new_sub_element = true;
				var $count = $.options.clicked_column.find(".jsn-item").length;
				var $replaces = {};
				$replaces['index'] = parseInt($count) + 1;
				// Get title of clicked element
				clk_title_el = $.trim($(this_).attr('item_common_title'));

				$.HandleElement.appendToHolder($(this_).attr('data-shortcode-item'), $replaces);
			}
		}
	},

	/**
	 * delete an element (a row OR a column OR an shortcode item)
	 */
	$.HandleElement.deleteElement = function() {
		$('body').on("click", ".ig-pb-form-container .element-delete", function(e) {
			$.HandleElement._deleteElement(this);
		});
	},

	$.HandleElement._deleteElement = function(target, silent) {
		var msg,is_column;

		if ($(target).hasClass('row') || $(target).attr("data-target") == "row_table") {
			msg = Ig_Translate.delete_row;
		} else if ($(target).hasClass('column') || $(target).attr("data-target") == "column_table") {
			msg = Ig_Translate.delete_column;
			is_column = 1;
		} else {
			msg = Ig_Translate.delete_element;
		}

		var confirm_ = silent ? true : confirm(msg);

		if (confirm_) {
			var $column = $(target).parent('.jsn-iconbar').parent('.shortcode-container');

			if (is_column == 1) {
				// Delete a Column in Table element
				if($(target).attr("data-target") == "column_table") {
					var table = new $.IGTable();
					table.deleteColRow($(target), 'column', Ig_Translate);
					$.HandleSetting.shortcodePreview();
				} else {
					var $row = $column.parent('.row-content').parent('.row-region');

					// If this is the last column of a row, remove the row instead
					if ($column.parent('.row-content').find('.column-region').length == 1) {
						$.HandleElement.removeElement($row, !silent);
					} else {
						$.HandleElement.removeElement($column, !silent);
					}
				}
			} else {
				// Delete a Row in Table element
				if ($(target).attr("data-target") == "row_table") {
					table = new $.IGTable();
					table.deleteColRow($(target), 'row', Ig_Translate);
					$.HandleSetting.shortcodePreview();
				} else {
					$.HandleElement.removeElement($column, !silent);
				}
			}
		}
	},

	/**
	 * Add an element to Parent Holder (a column [in PageBuilder], a
	 * group list[in Modal of Accordion, Tab...])
	 */
	$.HandleElement.appendToHolder = function($shortcode, $replaces, $type, sc_html, elem_title) {
		var append_to_div = $("#form-design-content .ig-pb-form-container");
		if(!$(this).hasClass('layout-element') && $.options.clicked_column != null){
			append_to_div = $.options.clicked_column;
		}

		// Check if user is adding raw shortcode
		if ($type == 'raw') {
			return $.HandleElement.addRawShortcode($shortcode, append_to_div);
		}

		// get HTML template of shortcode
		var html, appent_obj;
		if ( sc_html ) {
			appent_obj = $.HandleElement.appendToHolderFinish($shortcode, sc_html, $replaces, append_to_div, null, elem_title);
		} else {
			html = $("#tmpl-"+$shortcode).html();
			appent_obj = $.HandleElement.appendToHolderFinish($shortcode, html, $replaces, append_to_div, null, elem_title);
		}
		// Load the default shortcode structure then append it
		$.post(
				Ig_Ajax.ajaxurl,
				{
					action 		: 'get_default_shortcode_structure',
					shortcode   : $shortcode,
					type   : $type,
					ig_nonce_check : Ig_Ajax._nonce
				},
				function( data ) {
					$('textarea.shortcode-content', appent_obj).html(data);
				});
	},
	$.HandleElement.elTitle = function($shortcode, clk_title_el, exclude_this){
		if(clk_title_el == '')
			return '';
		var count_element = $(".ig-pb-form-container").find("a.element-edit[data-shortcode='"+$shortcode+"']").length;
		exclude_this = (exclude_this != null) ? exclude_this : 0;
		return clk_title_el + ' ' + parseInt(count_element + 1 - exclude_this);
	},

	/**
	 * Add supported element from raw shortcode.
	 *
	 * @param   object  btn  The clicked button to add element from raw shortcode.
	 * @param   object  div  The container to append new element into.
	 *
	 * @return  void
	 */
	$.HandleElement.addRawShortcode = function(btn, div) {

		// Toggle adding state
		$(btn).parent().addClass('ig-loading');

		// Verify raw shortcode
		var shortcode = $(btn).parents('div').prev('textarea').val(), sc_element = shortcode.match(/^\[([^\s\t\r\n\]]+)/), is_valid = false;

		if (sc_element) {
			sc_element = sc_element[1];

			// Check if shortcode element is supported
			var sc_element = $('button[data-shortcode="' + sc_element + '"]');

			if (sc_element.length) {
				is_valid = true;
			}
		}

		if (!is_valid) {
			// Toggle adding state
			$(btn).prev('textarea').val('').text('').parent().removeClass('ig-loading');

			// Reset processing state
			$.PbDoing.addElement = 0;

			return alert(Ig_Translate.element_not_existed);
		}

		// Add loading icon for add element button
		$('<i class="jsn-icon16 jsn-icon-loading"></i>').appendTo('.rawshortcode-container');

		// Request server-side to generate HTML code for insertion
		$.ajax({
			url: Ig_Ajax.ig_modal_url + '&action=insert',
			type: 'POST',
			data: {raw_shortcode: shortcode},
			complete: function(response, status) {
				if (status == 'success') {
					// Remove icon loading beside button add element
					$('.rawshortcode-container .jsn-icon-loading').remove();
					// Toggle adding state
					$(btn).prev('textarea').val('').text('').parent().removeClass('ig-loading');

					// Insert element into working area
					var title = response.responseText.match(/el_title="([^"]+)"/);

					$.HandleElement.appendToHolderFinish(sc_element.attr('data-shortcode'), response.responseText, null, div, null, title ? title[1] : '');
				}
			}
		});
	};

	$.HandleElement.appendToHolderFinish = function($shortcode, html, $replaces, append_to_div, $type, elem_title, position) {
		// Append new element
		if (position) {
			var	rows = $('#ig_page_builder .jsn-row-container'),
				html = $(html).css({
					display: '',
					height: '',
					opacity: '',
					overflow: '',
					'min-height': '',
					'padding-bottom': '',
					'padding-top': '',
				});

			for (var i = 0; i < rows.length; i++) {
				if (i == position.row) {
					var columns = rows.eq(i).find('.jsn-column-container');

					for (var j = 0; j < columns.length; j++) {
						if (j == position.column) {
							var elements = columns.eq(j).find('.jsn-element');

							if (elements.length) {
								if (position.position >= elements.length) {
									elements.last().after(html);
								} else {
									for (var k = 0; k < elements.length; k++) {
										if (k == position.position) {
											elements.eq(k).before(html);

											break;
										}
									}
								}
							} else {
								columns.eq(j).find('.jsn-element-container').prepend(html);
							}

							break;
						}
					}

					// Remove junk element
					html.find('i.jsn-icon-loading').remove();

					break;
				}
			}
		} else {
			// Hide popover
			$("#ig-add-element").hide();

			// Count existing elements which has same type
			append_title_el = $.HandleElement.elTitle($shortcode, clk_title_el);

			if (append_title_el.indexOf('undefined') >= 0) {
				append_title_el = '';
			}

			if (elem_title) {
				append_title_el = elem_title;
			}

			if ($type != null && $type == 'widget') {
				html = ig_pb_remove_placeholder(html, 'widget_title', 'title=' + append_title_el);
			} else if (typeof html == 'string') {
				html = html.replace(/el_title=\"\"/, 'el_title="' + append_title_el + '"');
			}

			if ($replaces != null) {
				html = ig_pb_remove_placeholder(html, 'index', $replaces['index']);
			} else {
				var idx = 0;

				html = ig_pb_remove_placeholder(html, 'index', function(match, number){
					return ++idx;
				});
			}

			html = $(ig_pb_remove_placeholder(html, 'custom_style', 'style="display:none"'));

			append_to_div.append(html);

			// Check if this is not a sub-item
			if (!($shortcode.match(/_item_/) || !append_to_div.hasClass('jsn-element-container'))) {
				// Trigger an event after adding an element
				$(document).trigger('ig_pb_after_add_element', html);
			}

			// Animation
			var height_ = html.height();

			$.HandleElement.appendElementAnimate(html, height_);

			// Show loading image
			html.append('<i class="jsn-icon16 jsn-icon-loading"></i>');

			// Open Setting Modal box right after add new element
			html.find('.element-edit').trigger('click');
		}
		return html;
	},

	// animation when add new element to container
	$.HandleElement.appendElementAnimate = function(new_el, height_, callback, finished){
		var obj_return = {
			obj_element:new_el
		};
		$('body').trigger('on_clone_element_item', [obj_return]);
		new_el = obj_return.obj_element;
		new_el.css({
			'min-height' : 0,
			'height' : 0,
			'opacity' : 0
		});
		new_el.addClass('padTB0');
		if(callback)callback();
		new_el.show();
		new_el.animate({
			height: height_
		},500,$.options.effect, function(){
			$(this).animate({
				opacity:1
			},300,$.options.effect,function(){
				new_el.removeClass('padTB0');
				new_el.css('height', 'auto');
				$('body').trigger('on_update_attr_label_common');
				$('.ig-pb-form-container').trigger('ig-pagebuilder-layout-changed');
				if(finished)finished();
			});
		});
	},

	/**
	 * Remove an element in IG PageBuilder / In Modal
	 */
	$.HandleElement.removeElement = function(element, announce) {
		if (announce) {
			// Prepare for animation
			element.css({
				'min-height' : 0,
				'overflow' : 'hidden'
			});

			// Animate
			element.animate({
				opacity: 0
			}, 300, $.options.effect, function() {
				element.animate({
					height: 0,
					'padding-top': 0,
					'padding-bottom': 0
				}, 300, $.options.effect, function() {
					// Trigger an event before deleting an element
					$(document).trigger('ig_pb_before_delete_element', element);

					element.remove();

					// Trigger an event after deleting an element
					$(document).trigger('ig_pb_after_delete_element');

					// For shortcode which has sub-shortcode
					if ($("#modalOptions").find('.has_submodal').length > 0) {
						$.HandleElement.rescanShortcode();
					}

					$('.ig-pb-form-container').trigger('ig-pagebuilder-layout-changed');
				});
			});
		} else {
			element.remove();

			// For shortcode which has sub-shortcode
			if ($("#modalOptions").find('.has_submodal').length > 0) {
				$.HandleElement.rescanShortcode();
			}

			$('.ig-pb-form-container').trigger('ig-pagebuilder-layout-changed');
		}
	},

	// Clone an Element
	$.HandleElement.cloneElement = function() {
		$('body').on("click", ".ig-pb-form-container .element-clone", function(e) {
			if ($.PbDoing.cloneElement) {
				return;
			}

			$.PbDoing.cloneElement = 1;

			var	parent_item = $(this).parent('.jsn-iconbar').parent('.jsn-item'),
				height_ = parent_item.height(),
				clone_item = parent_item.clone(true),
				item_class = $('#modalOptions').length ? '.jsn-item-content' : '.ig-pb-element';

			// Update title for clone element
			var html = clone_item.html();

			if (item_class == '.jsn-item-content') {
				append_title_el = parent_item.find(item_class).html();
			} else {
				append_title_el = parent_item.find(item_class).find('span').html();
			}

			if (append_title_el) {
				var regexp = new RegExp(append_title_el, "g");

				html = html.replace(regexp, append_title_el + ' ' + Ig_Translate.copy);
			}

			clone_item.html(html);

			// Add animation before insert
			$.HandleElement.appendElementAnimate(clone_item, height_, function() {
				clone_item.insertAfter(parent_item);

				// Trigger an event after cloning an element
				$(document).trigger('ig_pb_after_add_element', [clone_item, 'cloned']);

				if ($('.ig-pb-form-container').hasClass('fullmode')) {
					// active iframe preview for cloned element
					$(clone_item[0]).find('form.shortcode-preview-form').remove();
					$(clone_item[0]).find('iframe').remove();
					$.HandleElement.turnOnShortcodePreview(clone_item[0]);
				}

				$.HandleElement.rescanShortcode();
			}, function() {
				$.PbDoing.cloneElement = 0;
			});
		});
	},

	// Deactivate an Element
	$.HandleElement.deactivateElement = function() {
		$('body').on("click", ".ig-pb-form-container .element-deactivate", function(e) {
			var	parent_item = $(this).parents('.jsn-item'),
				before = parent_item.outerHTML(),
				textarea = parent_item.find("[data-sc-info^='shortcode_content']").first(),
				textarea_text = textarea.text(),
				child_i = $(this).find('i');

			if (child_i.hasClass('icon-checkbox-partial')) {
				textarea_text = textarea_text.replace('disabled_el="yes"', 'disabled_el="no"');

				// Update icon
				child_i.removeClass('icon-checkbox-partial').addClass('icon-checkbox-unchecked');

				// Update title
				$(this).attr('title', Ig_Translate.disabled.deactivate);
			} else {
				if (textarea_text.indexOf('disabled_el="no"') > 0) {
					textarea_text = textarea_text.replace('disabled_el="no"', 'disabled_el="yes"');
				} else {
					textarea_text = textarea_text.replace(']', ' disabled_el="yes" ]');
				}

				// Update icon
				child_i.removeClass('icon-checkbox-unchecked').addClass('icon-checkbox-partial');

				// Update title
				$(this).attr('title', Ig_Translate.disabled.reactivate);
			}

			parent_item.toggleClass('disabled');

			// Replace shortcode content
			textarea.text(textarea_text);

			// Trigger an event after activating / deactivating an element
			$(document).trigger('ig_pb_after_edit_element', [parent_item, before]);

			$('.ig-pb-form-container').trigger('ig-pagebuilder-layout-changed');
		});
	},

	// Edit an Element in IG PageBuilder / in Modal
	$.HandleElement.editElement = function() {
		// Fix error in element which uses Ajax modal and has child element (Accordion)
		$('body').on("click", ".ig-dialog", function (e) {
			e.preventDefault();
		});

		$('body').on("click", ".ig-dialog input:radio, .ig-dialog input:checkbox, .ig-dialog label[for]", function (e) {
			e.stopPropagation();
		});

		// Add action edit element directly on layout page without click edit element icon.
		$('body').on('click', '.item-container-content .jsn-element', function (e, restart_edit) {
			e.stopPropagation();

			// Prevent trigger edit element when click jsn-iconbar collections
			if ( $(e.target).closest('.jsn-iconbar').length || $(e.target).hasClass('element-drag') ) {
				return false;
			}
			$(this).find('.jsn-iconbar .element-edit').trigger('click');
		});

		$('body').on("click", ".ig-pb-form-container .element-edit", function (e, restart_edit) {
			e.stopPropagation();

            if ($(this).attr('data-custom-action')) {
                return;
            }
			// Main variables
            var parent_item, shortcode = $(this).attr("data-shortcode"), el_title = '';

			// Modal of current shortcode is open
            if ($.options.current_shortcode == shortcode && restart_edit == null) {
                return;
            }

			// Hide exit modal
//            $.HandleElement.removeModal();

			// Show loading icon
            $.HandleElement.showLoading();

			// Set flag to sign editting a shortcode, prevent duplicator
            $.options.current_shortcode = shortcode;

			// Set temporary flag to sign current editted element
			var cur_shortcode    = $(this).parents('.jsn-item').find('textarea.shortcode-content:first');
			var editted_flag_str = '#_EDITTED';
			if (cur_shortcode.length > 0) {
				cur_shortcode.html(cur_shortcode.val().replace('[' + shortcode, '[' + shortcode + ' ' + editted_flag_str + ' ' ));
			}

			// Get wrapper div & Type of current shortcode
            if ($(this).hasClass('row')) {
                parent_item = $(this).parent('.jsn-iconbar').parent('.jsn-row-container');
                el_type = 'element';
            }
            else {
                parent_item = $(this).parent('.jsn-iconbar').parent('.jsn-item');
                el_type = parent_item.attr('data-el-type');
            }
            parent_item.addClass('active-shortcode');

			// Get Heading text for Modal settings
			if(el_type == 'widget'){
				el_title = $.HandleElement.elTitle(shortcode, clk_title_el, 1);
			}

			if (!el_title) {
				el_title = Ig_Translate.no_title;
			}

			// Get shortcode content
			var params		= parent_item.find("[data-sc-info^='shortcode_content']").first().text();

			// Get custom info for the Modal : frameId, frame_url
			var title = $.HandleElement.getModalTitle(shortcode, parent_item.attr('data-modal-title'));
			var frameId = $.options.modal_settings.modalId;
			var has_submodal = 0;
			if( $(this).parents('.has_submodal').length > 0 ){
				has_submodal = 1;
				el_title = $.HandleElement.elTitle(shortcode, clk_title_el, 1);
			}

			var frame_url = Ig_Ajax.ig_modal_url + '&ig_modal_type=' + shortcode;

			// Append temporary form to submit
			var form = $("<form/>").attr({
				method: "post",
				style: "display:none",
				action: frame_url
			});
			form.append($("<input/>").attr( {name : "shortcode", value : shortcode} ) );
			form.append($("<textarea/>").attr( {name : "params", value : params} ) );
			form.append($("<input/>").attr( {name : "el_type", value : el_type} ) );
			form.append($("<input/>").attr( {name : "el_title", value : el_title} ) );
			form.append($("<input/>").attr( {name : "submodal", value : has_submodal} ) );

			// Check if this element/its parent element requires iframe for editing
			var	parent_shortcode = shortcode.replace('_item', ''),
				iframe_required = !parseInt($(e.target.nodeName == 'I' ? e.target.parentNode : e.target).attr('data-use-ajax'));

			if (iframe_required) {
				iframe_required = !parseInt($('button.shortcode-item[data-shortcode="' + parent_shortcode + '"]').attr('data-use-ajax'));
			}

			// for Pricing table attributes
			if ( $(this).closest('.jsn-item').attr('data-using-ajax') === '1' ) {
				iframe_required = false;
			}
			var modal = new $.IGModal({
				iframe: iframe_required,
				frameId: frameId,
				dialogClass: 'ig-dialog jsn-bootstrap3',
				jParent : window.parent.jQuery.noConflict(),
				title: $.HandleElement.remove_underscore_ucwords(title),
				///url: Ig_Ajax.ig_modal_url + '&ig_modal_type=' + shortcode,
				buttons: [{
					'text'  : Ig_Ajax.delete,
					'id'    : 'delete_element',
					'class' : 'btn btn-danger pull-right',
					'click' : function() {
						$.HandleElement.enablePageScroll();

						var current_element = $('.active-shortcode').last();

						if ( current_element && $.HandleCommon.removeConfirmMsg( current_element, 'element' ) ) {
							$.HandleElement.removeSelect2Active();
							$.HandleElement.closeModal(iframe_required ? window.parent.jQuery.noConflict()( '#' + frameId ) : modal.container, iframe_required);
						}
					}
				}, {
					'text'	: Ig_Ajax.save,
					'id'	: 'selected',
					'class' : 'btn btn-primary ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only',
					'click'	: function() {
						$.HandleElement.enablePageScroll();
						$(this).attr('disabled', 'disabled');
						$('body').trigger('add_exclude_jsn_item_class');
						$.HandleElement.closeModal(iframe_required ? window.parent.jQuery.noConflict()( '#' + frameId ) : modal.container, iframe_required);
						var cur_shortcode   = $(".ig-pb-form-container .active-shortcode").last().find('textarea.shortcode-content:first');
						if (cur_shortcode.length > 0) {
							cur_shortcode.html(cur_shortcode.html().replace(new RegExp(editted_flag_str, 'g'), ''));
						}
					}
				}, {
					'text'	: Ig_Ajax.cancel,
					'id'	: 'close',
					'class' : 'btn btn-default ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only',
					'click'	: function() {
						$.HandleElement.removeSelect2Active();
						$.HandleElement.enablePageScroll();
//						modal.close();
						$('body').trigger('add_exclude_jsn_item_class');

						var curr_iframe = iframe_required ? window.parent.jQuery.noConflict()('#' + frameId) : modal.container;
						var is_submodal = (iframe_required ? curr_iframe.contents() : curr_iframe).find('.submodal_frame').length;

						$.HandleElement.finalize(is_submodal);

						// Get current active shortcode
						var active_item = $(".ig-pb-form-container .active-shortcode").last();

						// Update Element Title to Active element (only for not child element)
						if (!$.options.new_sub_element && append_title_el) {
							var active_title = active_item.find('.ig-pb-element').first();

							// Check current edit element has exists title.
							var active_title_span = active_item.find('.ig-pb-element span').first();

							if (!is_submodal && active_title.length && active_title_span.length == 0) {
								active_title.html(active_title.html().split(':')[0] + ": " + '<span>' + Ig_Translate.no_title + '</span>');
							}

							append_title_el = '';
						}

						// remove loading image from active child element
						active_item.find('.jsn-icon-loading').remove();

						active_item.removeClass('active-shortcode');

						$('body').trigger('on_update_shortcode_widget', 'is_cancel');
						// Remove editted flag
						var cur_shortcode   = active_item.find('textarea.shortcode-content:first');
						if (cur_shortcode.length > 0) {
							cur_shortcode.html(cur_shortcode.html().replace(new RegExp(editted_flag_str, 'g'), ''));
						}
					}
				}],
				loaded: function (obj, iframe) {
					$('body').trigger('ig_submodal_load',[iframe]);
					// Remove editted flag in shortcode content
					var shortcode_content   = $(iframe).contents().find('#shortcode_content');
					shortcode_content.html(shortcode_content.length ? shortcode_content.html().replace(new RegExp(editted_flag_str, 'g'), '') : '');

					// remove title of un-titled element
					var title = $(iframe).contents().find('[data-role="title"]').val();
					var index = ig_pb_get_placeholder( 'index' );
					if ( title != null && title.indexOf(index) >= 0 ) {
						$(iframe).contents().find('[data-role="title"]').val('');
					}

					// Track shortcode content change
					$.HandleElement.__changed = false;

					setTimeout(function() {
						if (iframe.contentWindow) {
							iframe.contentWindow.jQuery('#shortcode_content').change(function() {
								window.parent.jQuery.HandleElement.__changed = true;
							});
						}
					}, 2000);
				},
				fadeIn:200,
				scrollable: true,
				width: resetModalSize(has_submodal, 'w'),
				height: resetModalSize(has_submodal, 'h')
			});

			modal.show(function(modal) {
				if (iframe_required) {
					// Append form to document body so it can be submitted
					$("body").append(form);

					// Set name for iframe
					window.parent.document.getElementById(frameId).name = frameId;
					window.parent.document.getElementById(frameId).src = 'about:blank';

					// Set form target
					form.attr('target', frameId);

					// Submit form data to iframe
					form.submit();

					// Remove form
					setTimeout(function(){form.remove();}, 200);

					// Make page unscrollable
					$.HandleElement.disablePageScroll();
				} else {
					// Request server for necessary data
					$.ajax({
						url: frame_url + '&form_only=1',
						data: form.serializeArray(),
						type: 'POST',
						dataType: 'html',
						complete: function(data, status) {
							if (status == 'success') {
								if ( $('#' + $.options.modal_settings.modalId).length == 0 ) {
									modal.container.attr('id', $.options.modal_settings.modalId);
								}
								modal.container.html(data.responseText).dialog('open').dialog('moveToTop');

								// Track shortcode content change
								$.HandleElement.__changed = false;

								setTimeout(function() {
									modal.container.find('#shortcode_content').change(function() {
										$.HandleElement.__changed = true;
									});
								}, 2000);

								// Make page unscrollable
								$.HandleElement.disablePageScroll();
								
								if ( $('.jsn-modal').last().attr('id') != $.options.modal_settings.modalId ) {
									$('body').trigger('ig_submodal_load',[modal.container]);
								}
							}
						}
					});
				}
			});

			setTimeout(function(){
				if($('.ig-dialog').length < 1 && $('.jsn-modal-overlay').is(':visible')){
					$.HandleElement.hideLoading();
				}
			}, 3000);
		});
	},

	// Remove select2 active
	$.HandleElement.removeSelect2Active = function () {
		$('.select2-drop-active').remove();
	}

	// Disable page scroll
	$.HandleElement.disablePageScroll = function() {
		if ( $('body').hasClass('wp-admin') ) {
			$('body').addClass('ig-overflow-hidden');
		}
	}

	// Enable page scroll
	$.HandleElement.enablePageScroll = function() {
		if ( $('body').hasClass('wp-admin') ) {
			$('body').removeClass('ig-overflow-hidden');
		}
	}

	// fix error of TinyMCE on Modal setting iframe
	$.HandleElement.fixTinyMceError = function(){
		$('#content-html').trigger('click');
	},

	/*******************************************************************
	 * 4. Modal
	 ******************************************************************/

	/**
	 * Generate Title for Modal
	 */
	$.HandleElement.getModalTitle = function(shortcode, modal_title) {
		var title = Ig_Translate.page_modal;
		if (shortcode != '') {
			if(modal_title)
				title = modal_title;
			else{
				shortcode = shortcode.replace('ig_','').replace('_',' ');
				title = $.HandleElement.capitalize(shortcode);
			}
		}
		return title + ' ' + Ig_Translate.settings;
	},

	/**
	 * Remove Modal, Show Loading, Hide Loading
	 */
	$.HandleElement.removeModal = function() {
		$.HandleElement.enablePageScroll();
		$('.jsn-modal').remove();
	},

	// Show Overlay & Loading of Modal
	$.HandleElement.showLoading = function(container) {
		container	= container ? container : 'body'
		var $selector = $;//window.parent.jQuery.noConflict();

		var $overlay = $selector('.jsn-modal-overlay');
		if ($overlay.size() == 0) {
			$overlay = $('<div/>', {
				'class': 'jsn-modal-overlay'
			});
		}

		var $indicator = $selector('.jsn-modal-indicator');
		if ($indicator.size() == 0) {
			$indicator = $('<div/>', {
				'class': 'jsn-modal-indicator'
			});
		}


		$selector(container)
		.append($overlay)
		.append($indicator);
		$overlay.css({
			'z-index': 100
		}).show();
		$indicator.show();

		return $indicator;
	},

	// Hide Overlay of Modal
	$.HandleElement.hideLoading = function(container, is_submodal) {
		container = container ? $(container) : $('body');
		var $selector = $;//window.parent.jQuery.noConflict()
		if(is_submodal){
			$selector('.jsn-modal-overlay').last().hide();
			$selector('.jsn-modal-indicator').last().hide();
		} else {
			$selector('.jsn-modal-overlay').remove();
			$selector('.jsn-modal-indicator').remove();
		}
	},

	/**
	 * Extract shortcode params of sub-shortcodes, then update merged
	 * data to a #div
	 */
	$.HandleElement.extractParam = function(shortcode_, param_,
		updateTo_) {
		var sub_data = [];
		$("#modalOptions #group_elements .jsn-item").each(function() {
			sub_data.push($(this).find('textarea').text());
		});
		$.post(Ig_Ajax.ajaxurl, {
			action : 'shortcode_extract_param',
			param : param_,
			shortcode : shortcode_,
			data : sub_data.join(""),
			ig_nonce_check : Ig_Ajax._nonce
		}, function(data) {
			$(updateTo_).text(data);
		});
	},

	/**
	 * For Parent Shortcode: Rescan sub-shortcodes content, call preview
	 * function to regenerate preview
	 */
	$.HandleElement.rescanShortcode = function(curr_iframe, callback, child_element) {
		try {
			$.HandleSetting.shortcodePreview(null, null, curr_iframe, callback, 1, child_element);
		} catch (err) {
			// Do nothing
		}
	},

	/**
	 * save shortcode data before close Modal
	 */
	$.HandleElement.closeModal = function(curr_iframe, iframe_required) {
		$.options.curr_iframe_ = curr_iframe;

		var	contents = curr_iframe.contents ? curr_iframe.contents() : curr_iframe,
			submodal = contents.find('.has_submodal'),
			submodal2 = curr_iframe.contents().find('.submodal_frame_2');
		
		if (submodal2.length > 0) {
			// step_to_track('1.1');
			$.options.if_childmodal = 1;

			// Call Preview to get content of params + tinymce. Finally, update #shortcode_content, Close Modal, call Preview of parents shortcode
			$.HandleElement.rescanShortcode(curr_iframe, function() {
				$.HandleElement.updateBeforeClose(window.parent.jQuery.noConflict()('#'+$.options.modal_settings.modalId), null, iframe_required);
			});
		} else if (submodal.length > 0) {
			// step_to_track('1.2');
			// Shortcodes like Tabs, Accordion
			$.HandleElement.rescanShortcode(curr_iframe, function() {
				$.HandleElement.updateBeforeClose();
			});
		} else {
			// Sub shortcodes of Tabs, Accordion
			if (contents.find('.submodal_frame').length) {
				// step_to_track('1.3');
				$.options.if_childmodal = 1;

				// Call Preview to get content of params + tinymce. Finally, update #shortcode_content, Close Modal, call Preview of parents shortcode
				$.HandleElement.rescanShortcode(curr_iframe, function() {
					var selector, update_iframe;
					selector = (window.parent) ? window.parent.jQuery.noConflict(): $;
					if(iframe_required){
						update_iframe = selector('#' + $.options.modal_settings.modalId);
					} else {
						update_iframe = selector('.jsn-modal').first();
					}
					$.HandleElement.finishCloseModal(curr_iframe, update_iframe);
				}, 'child element');
			} else {
				// step_to_track('1.4');
        		$.HandleElement.finishCloseModal(curr_iframe);
			}
		}
	},

	/**
	 * Parent shortcode like Tab, Accordion: Collect sub shortcodes
	 * content and update to #shortecode_content before close
	 */
	$.HandleElement.updateBeforeClose = function(update_iframe, rebuild_shortcode, iframe_required) {

		// Get sub-shorcodes content
		var sub_items_content = [];

		if ( iframe_required ) {
			$.options.curr_iframe_.contents().find( "#modalOptions [name^='shortcode_content']" ).each(function() {
				sub_items_content.push($(this).text());
			});
		} else {
			$( "#modalOptions [name^='shortcode_content']" ).each(function() {
				sub_items_content.push($(this).text());
			});
		}
		sub_items_content = sub_items_content.join('');

		var shortcode_content_obj;
		if ( iframe_required ) {
			shortcode_content_obj = $.options.curr_iframe_.contents().find( '#shortcode_content' );
		} else {
			shortcode_content_obj = $( '#shortcode_content' );
		}

		var	shortcode_content = shortcode_content_obj.text(),
			arr = shortcode_content.split(']['),
			before = $.HandleElement.selector(update_iframe, '.ig-pb-form-container .active-shortcode').first().outerHTML();

		// step_to_track('2.5', shortcode_content);

		if (arr.length >= 2) {
			// Extract name & parameters of parent shortcode
			var parent_sc_start = shortcode_content.replace('#_EDITTED', '').match(/\[[^\s"]+\s+([A-Za-z0-9_-]+=\"[^"\']*\"\s*)*\s*\]/);
			var head_shortcode = parent_sc_start[0];
			head_shortcode = head_shortcode.replace(']', '');

			// step_to_track('2.6', parent_sc_start);

            var data = head_shortcode + ']' + sub_items_content + '[' + arr[arr.length - 1];

			// Update shortcode content
			shortcode_content_obj.text(data);

			// step_to_track(2, data);
		}

		if (!rebuild_shortcode) {
			$.HandleElement.finishCloseModal($.options.curr_iframe_, update_iframe, before);
		}
	},

	/**
	 * update shortcode-content & close Modal & call preview (shortcode
	 * has sub-shortcode) action_data: null (Save button) OR { 'convert' :
	 * 'tab_to_accordion'}
	 */
	$.HandleElement.finishCloseModal = function(curr_iframe, update_iframe, before) {
		var	contents = curr_iframe.contents ? curr_iframe.contents() : curr_iframe,
			shortcode_content = contents.find( '#shortcode_content' ).first().text();

		// Trigger update shortcode for IG PageBuilder widget element
		$('body').trigger('on_update_shortcode_widget', [shortcode_content]);

		var in_sub_modal = ($('.ig-dialog').length == 2);

		if (!top.addInClassic || in_sub_modal) {
			var item_title = "", title_prepend, title_prepend_val = "";

			if (contents.find('[data-role="title"]').length) {
				title_prepend = contents.find('[data-role="title_prepend"]');
				title_prepend_val = '';

				// Process append title_prepend with title
				if (title_prepend.length && in_sub_modal) {
					title_prepend = title_prepend.first();

					var title_prepend_type = title_prepend.attr("data-title-prepend");

					title_prepend_val = title_prepend.val();

					if (typeof(title_prepend_val) != "undefined" && Ig_Js_Html[title_prepend_type]) {
						if (title_prepend.val() == '' && title_prepend_type == 'icon') {
							title_prepend_val = '';
						} else {
							title_prepend_val = ig_pb_remove_placeholder(Ig_Js_Html[title_prepend_type], 'standard_value', title_prepend.val());
						}
					}
				}

				item_title = title_prepend_val + contents.find('[data-role="title"]').first().val();
			}

			if (contents.find('#ig-widget-form').length) {
				title_prepend = contents.find('#ig-widget-form').find("input:text[name$='[title]']");
				item_title = title_prepend.val();
			}

			// Assign item_title use data-role=content instead data-role=title if it not exists
			if ( ! contents.find('[data-role="title"]').length && contents.find('[data-role="content"]').length ) {
				item_title = contents.find('[data-role="content"]').val();
			}

			if ( item_title ) {
				item_title = item_title.replace(/\[/g,"").replace(/\]/g,"");
			}

			$.HandleElement.updateActiveElement(update_iframe, shortcode_content, item_title, before);
		}

		if (top.addInClassic || !in_sub_modal) {
			// Update to textarea of Classdic Editor

			// Inserts the shortcode into the active editor
			if (typeof tinymce != 'undefined' && tinymce.activeEditor) {
				tinymce.activeEditor.execCommand('mceInsertContent', 0, shortcode_content);
			}

			// Close Thickbox
			tb_remove();
		}

		$.HandleElement.finalize($.options.if_childmodal);

		if ($.options.if_childmodal) {
			// Update Tags of sub-element in Accordion
			if ($("#modalOptions #shortcode_name").val() == "ig_accordion") {
				$.HandleElement.extractParam("ig_accordion", "tag", "#ig_share_data");
			}
			// step_to_track(4, 'Rescan');
			// Rescan sub-element shortcode of Parent element (Accordion, Tab...)
			$.HandleElement.rescanShortcode();
		}
	},

	/**
	 * Update to active element
	 */
	$.HandleElement.updateActiveElement = function(update_iframe, shortcode_content, item_title, before) {
		// Check item_title is undefined
		if ( typeof( item_title ) == 'undefined' || ! item_title )
			item_title = Ig_Translate.no_title;

		var	active_shortcode = $.HandleElement.selector(update_iframe,".ig-pb-form-container .active-shortcode").last(),
			before = before || active_shortcode.outerHTML(),
			editted_flag_str = '#_EDITTED';

		if (active_shortcode.hasClass('jsn-row-container')) {
			shortcode_content = shortcode_content.replace('[/ig_row]','');
		}
		// step_to_track(3, shortcode_content);
		active_shortcode.find("[data-sc-info^='shortcode_content']").first().text(shortcode_content);
		active_shortcode.find("[data-sc-info^='shortcode_content']").first().val(shortcode_content);

		// update content to current active sub-element in group elements (Accordions, Tabs...)
		var item_class = ($.options.if_childmodal) ? ".jsn-item-content" : ".ig-pb-element";
		// if sub modal, use item_title as title. If in pagebuilder, show like this (Element Type : item_title)
		if(!$.options.if_childmodal && active_shortcode.find(item_class).first().length){
			if(item_title != '')
				item_title = active_shortcode.find(item_class).first().html().split(':')[0] + ": " + '<span>'+item_title+'</span>';
			else
				item_title = active_shortcode.find(item_class).first().html().split(':')[0];
		}

		if ( ! item_title || item_title == "<i class=''></i>" )
			item_title = Ig_Translate.no_title;
		active_shortcode.find(item_class).first().html(item_title);
		// update content to current active Cell in Table
		if(window.parent.jQuery.noConflict()( '.ui-dialog:last').contents().find('#shortcode_name').val() == "ig_item_table"){
			var table = new $.IGTable();
			table.init(active_shortcode);
		}

		var element_html = active_shortcode.html();

		if (typeof(element_html) != 'undefined') {
			// Remove editted flag
			element_html = element_html.replace(new RegExp(editted_flag_str, 'g'), '');
		}

		active_shortcode.html(element_html);

		// Trigger an event after editing an element
		// State that this is a silent action if undo / redo
		active_shortcode.addClass('silent_action');

		if (window.parent) {
			window.parent.jQuery(window.parent.document).trigger('ig_pb_after_edit_element', [active_shortcode, before]);
		} else {
			$(document).trigger('ig_pb_after_edit_element', [active_shortcode, before]);
		}

		active_shortcode.removeClass('active-shortcode');

		$.HandleSetting.updateState(0);

		// Hide Loading in Group elements
		if ($(active_shortcode).parents('#group_elements').length) {
			$(active_shortcode).parents('#group_elements').find('.jsn-item').last().find('.jsn-icon-loading').remove();
		}

		// Check if in Fullmode, then turn live preview on
		if ($(active_shortcode).parents('.ig-pb-form-container.fullmode').length > 0) {
			$.HandleElement.turnOnShortcodePreview(active_shortcode);
		}

		// Update package attribute label common json
		$('body').trigger('on_update_attr_label_common');
		$('body').trigger('on_update_attr_label_setting');

	}

	// finalize when click Save/Cancel modal
	$.HandleElement.finalize = function(is_submodal, remove_modal){
		// remove modal
		if(remove_modal || remove_modal == null)
			window.parent.jQuery.noConflict()('.jsn-modal').last().remove();

		$(".ig-pb-form-container").find('.jsn-icon-loading').remove();

		// reactive TinyMCE tab
		if(top.addInClassic){
			top.addInClassic = 0;
			if(typeof switchEditors != 'undefined')
				switchEditors.switchto(document.getElementById('content-tmce'));
		}
		// reset/update status
		$.options.if_childmodal = 0;
		$.PbDoing.addElement = 0;
		$.options.current_shortcode = 0;

		// remove overlay & loading
		$.HandleElement.hideLoading(null, is_submodal);
		if(!is_submodal) {
			$.HandleElement.removeModal();
		}
		$('.ig-pb-form-container').trigger('ig-pagebuilder-layout-changed');

		// Do action : convert

		var action_data = ($.PbDoing.action_data !== null) ? $.PbDoing.action_data : null;

		if (action_data) {

			if (action_data.action === 'convert')
			{
				$.HandleElement.convertTo(action_data);
			}

			// Reset value of data
			$.PbDoing.action_data = null;
		}
	}

	// Convert to another element
	$.HandleElement.convertTo = function(action_data) {

		var arr = action_data.relation.split('_');
		var active_shortcode = $('.ig_to_convert');
		var element_html = active_shortcode.html();

		if (arr.length === 3)
		{
			var from_shortcode = arr[0];
			var to_shortcode = arr[2];

			// replace old shortcode tag by new shortcode tag
			var regexp = new RegExp("ig_" + from_shortcode, "g");
			element_html = element_html.replace(regexp, "ig_" + to_shortcode);

			regexp = new RegExp("ig_item_" + from_shortcode, "g");
			element_html = element_html.replace(regexp, "ig_item_" + to_shortcode);

			// Update shortcode name in PageBuilder
			regexp = new RegExp($.HandleElement.capitalize(from_shortcode), "g");
			element_html = element_html.replace(regexp, $.HandleElement.capitalize(to_shortcode));

			// Update text of "Convert to" button
			regexp = new RegExp(Ig_Translate.convertText + to_shortcode, "g");
			element_html = element_html.replace(regexp, Ig_Translate.convertText + from_shortcode);

			// Update whole HTML of element
			active_shortcode.html(element_html);
		}

		// Trigger click on edit button to open Setting Modal
		setTimeout(function() {
			active_shortcode.find(".element-edit").trigger('click', [true]);
			active_shortcode.removeClass('ig_to_convert');
		}, 300);
	}

	$.HandleElement.checkSelectMedia = function() {
		$('body').delegate('#ig-select-media', 'change', function () {
			var currentValue = $(this).val();
			if ( currentValue ) {
				var jsonObject = JSON.parse( currentValue );
				$('#ig-select-media').val('');
				var send_attachment_bkp = wp.media.editor.send.attachment;
				var button 				= $(this);

				if (typeof(jsonObject.type) != undefined) {
					var _custom_media = true;
					wp.media.editor.send.attachment = function(props, attachment){
						if ( _custom_media ) {
							var select_url 	= attachment.url;

							if ( props.size && attachment.type == jsonObject.type) {
								var select_prop 	= props.size;
								var object 			= {};
								object.type			= 'media_selected';
								object.select_prop	= select_prop;
								object.select_url	= select_url;
								$('#ig-select-media').val(JSON.stringify(object));
							}
						} else {
							return _orig_send_attachment.apply( this, [props, attachment] );
						};

					}
					// Open wp media editor without select multiple media option
					wp.media.editor.open(button, {
						multiple: false
					});
				}else{
					// Open wp media editor without select multiple media option
					wp.media.editor.open(button, {
						multiple: false
					});
				}
			}
		});
	}

	/**
	 * Init events for Mode Switcher to turn view to full or compact
	 */
	$.HandleElement.initModeSwitcher	= function (){
		var switcher_group	= $('#mode-switcher');
		var container		= $('.ig-pb-form-container');
		var cur_url			= window.location.search.substring(1);

		$('.switchmode-button', switcher_group).on('click', function (){
			if($(this).hasClass('disabled')) return false;
			if($(this).attr('id')	== 'switchmode-full'){
				$('#switchmode-compact').removeClass('active');
				container.addClass('fullmode');
				$.HandleElement.switchToFull(container);
				container.on('ig-pagebuilder-layout-changed', function (event, ctn){
					$.HandleElement.switchToFull(ctn);
				});

				container.on('ig-pagebuilder-column-size-changed', function (event, ctn_row){
					$(ctn_row).find('.shortcode-preview-iframe').each(function (){
						var _iframe			= $(this);
						var _iframe_width	= _iframe.width();
						if (_iframe.contents().find('#shortcode_inner_wrapper').length > 0){
							_iframe.contents().find('#shortcode_inner_wrapper').width(_iframe_width - 25);
							var _contentHeight	= _iframe.contents().find('#shortcode_inner_wrapper')[0].scrollHeight;
							_iframe.height(_contentHeight);
						}

					});
				});
				$.HandleElement.setCookie('ig-pb-mode-' + cur_url, 2);

			}else if ($(this).attr('id') == 'switchmode-compact'){
				$('#switchmode-full').removeClass('active');
				container.removeClass('fullmode');
				$.HandleElement.switchToCompact(container);
				container.unbind('ig-pagebuilder-layout-changed');
				$.HandleElement.setCookie('ig-pb-mode-' + cur_url, 1)
			}
		});
		// Auto switch to full mode if it was
		if ($.HandleElement.getCookie('ig-pb-mode-' + cur_url) == 2) {
			$('#switchmode-full', switcher_group).click();
		}

	}

	/**
	 * Turn view to Full mode
	 */
	$.HandleElement.switchToFull = function (container){
		// Load preview frames for each shortcode item
		if ($(container).hasClass('jsn-item') || $(container).parents('jsn-item').length > 0) {
			$.HandleElement.turnOnShortcodePreview(container);
		}else{
			$('.jsn-item', container).each(function (){
				var _shortcode_title	= $('.ig-pb-element', $(this)).text();
				$(this).find('.ig-pb-fullmode-shortcode-title').remove();
				$(this).append(
					$("<div/>", {
						"class":"jsn-percent-column ig-pb-fullmode-shortcode-title"
					}).append(
						$("<div/>", {
							"class":"jsn-percent-arrow"
						})
						).append(
						$("<div/>", {
							"class":"jsn-percent-inner"
						}).append(_shortcode_title)
						)
					);
				$(this).find(".jsn-percent-column .jsn-percent-arrow").css({
					"left": "10px"
				});
				$.HandleElement.turnOnShortcodePreview(this);
			});
		}


	}

	/**
	 * Turn live preview of a shortcode on
	 */
	$.HandleElement.turnOnShortcodePreview	= function (shortcode_wrapper){
		// Create form and iframe used for submitting data
		// to preview.
		var _rnd_id				= randomString(5);
		var _shortcode_params	= $(shortcode_wrapper).find('textarea.shortcode-content').clone();
		_shortcode_params.attr('name', 'params').removeAttr('data-sc-info').removeClass('shortcode-content');

		var _shorcode_name		= $(shortcode_wrapper).find('textarea.shortcode-content').attr('shortcode-name');
		if ( typeof(_shorcode_name) == 'undefined' || _shorcode_name == null ) {
			return;
		}
		$(shortcode_wrapper).find('.jsn-overlay').show();

		if ($(shortcode_wrapper).find('form.shortcode-preview-form').length == 0){
			var _form				= $('<form/>', {
				'class': 'shortcode-preview-form',
				'method': 'post',
				'target': 'iframe-' + _rnd_id,
				'action': Ig_Ajax.ig_modal_url + '&ig_shortcode_preview=1' + '&ig_shortcode_name=' + _shorcode_name + '&ig_nonce_check=' + Ig_Ajax._nonce
			});
			var _iframe				= $('<iframe/>', {
				'scrolling': 'no',
				'id': 'iframe-' + _rnd_id,
				'name': 'iframe-' + _rnd_id,
				'width': '100%',
				'height': '50',
				'class': 'shortcode-preview-iframe'
			});
			var _preview_container	= $(shortcode_wrapper).find('.shortcode-preview-container');

			// Append cloned shortcode content to temporary form

			_shortcode_params.appendTo(_form);

			// Append form and iframe to shorcode preview div
			_form.appendTo(_preview_container);
			_iframe.appendTo(_preview_container);
			_form.submit();
		}else{
			var _form	= $(shortcode_wrapper).find('form.shortcode-preview-form').first();
			_form.find('textarea').remove();
			_shortcode_params.appendTo(_form);
			_form.submit();
			_iframe	= $('#' + _form.attr('target'));
		//_iframe.css('height', '50');
		}

		$('.shortcode-preview-container', shortcode_wrapper).show();
		// Show preview content after preview iframe loaded successfully
		_iframe.on('load', function (){
			// Return if current mode is not Full mode
			var cur_url			= window.location.search.substring(1);
			if ($.HandleElement.getCookie('ig-pb-mode-' + cur_url) != 2) {
				return;
			}

			var self	= this;
			var	_frame_id	= $(this).attr('id');
			setTimeout(function (){
				$(self).contents().find('#shortcode_inner_wrapper').css({
					'height': 'auto',
					'width': $(self).width()
				});
				if (document.getElementById(_frame_id).contentWindow.document.getElementById('shortcode_inner_wrapper')){
					var _contentHeight	= document.getElementById(_frame_id).contentWindow.document.getElementById('shortcode_inner_wrapper').scrollHeight - 10;
					$(self).height(_contentHeight) ;
					$(self).contents().find('#shortcode_inner_wrapper').height(_contentHeight);
				}

			}, 100);
			$(this).parents('.jsn-item').find('.jsn-overlay').hide('slow');
			// Hide shorcode title when iframe loaded
			$(this).parents('.jsn-item').find('.ig-pb-element').hide('slow');
			// update content for Classic editor - to make php "Save post hook" works well
			var tab_content = '';
			$(".ig-pb-form-container textarea[name^='shortcode_content']").each(function(){
				tab_content += $(this).val();
			});
			$.HandleElement.updateClassicEditor(tab_content);
		});
	}

	/**
	 * Turn view to Compact mode
	 */
	$.HandleElement.switchToCompact	= function (container){
		$('.shortcode-preview-container', container).hide();
		$('.jsn-overlay', container).show();
		$('.ig-pb-element', container).show();
		$('.ig-pb-fullmode-shortcode-title', container).remove();
	}

	/**
	 * Init events for Status Switcher to turn on/off pagebuilder
	 */
	$.HandleElement.initStatusSwitcher	= function (){
		var switcher_group	= $('#status-switcher');
		var container		= $('.ig-pb-form-container');
		var class_btn = new Array();
		class_btn['status-on'] = 'btn-success';
		class_btn['status-off'] = 'btn-danger';
		$('.switchmode-button', switcher_group).on('click', function (e, doit){
			// Remove all active class
			$('.switchmode-button').removeClass('active');

			if($(this).attr('id')	== 'status-off'){
				// Set the HTML alternative content to default editor and clear pagebuilder content
				var tab_content = '';
				$(".ig-pb-form-container textarea[name^='shortcode_content']").each(function(){
					tab_content += $(this).val();
				});

				// UPDATE CLASSIC EDITOR
				var cf;
				if(doit != null || !tab_content)
					cf = 1;
				else
					cf = confirm(Ig_Translate.deactivatePb);
				if(cf){
					// Disable Page Template feature if PageBuilder is disabled
					$('#page-template .dropdown-toggle').addClass('disabled');
					// disable Mode switcher buttons
					$('#mode-switcher button').addClass('disabled');
					// Hide IG PageBuilder UI
					container.addClass('hidden');
					// Show message
					$('#deactivate-msg').removeClass('hidden');

					$('#ig_deactivate_pb').val("1");

					if(doit == null){
						// Update tracking field



						// disable WP Update button
						$('#publishing-action #publish').attr('disabled', true);
						// remove placeholder text which was inserted to &lt; and &gt;
						tab_content = ig_pb_remove_placeholder(tab_content, 'wrapper_append', '');

						$.post(
							Ig_Ajax.ajaxurl,
							{
								action : 'get_html_content',
								content : tab_content,
								ig_nonce_check : Ig_Ajax._nonce
							},
							function( tab_content ) {
								$.HandleElement.updateClassicEditor(tab_content, function(){
									$('#status-on').removeClass(class_btn['status-on']);
									$('#status-off').addClass(class_btn['status-off']);
								});
							});

					}
					else{
						$('#status-on').removeClass('btn-success');
						$(this).addClass('btn-danger');
					}

                    // disable Off button
                    $(this).addClass('disabled');

					return true;
				}
				return false;
			}else if ($(this).attr('id') == 'status-on'){
                // enable Off button
                $('#status-off').removeClass('disabled');

				// Enable Page Template feature if PageBuilder is enable.
				$('#page-template .dropdown-toggle').removeClass('disabled');
				// UPDATE PAGE BUILDER
				// enable Mode switcher buttons
				$('#mode-switcher button').removeClass('disabled');
				// Show IG PageBuilder UI
				container.removeClass('hidden');
				// Hide message
				$('#deactivate-msg').addClass('hidden');
				// Update tracking field
				$('#ig_deactivate_pb').val("0");

				// Get content of default editor, parse to a Text shortcode and add to IG PageBuilder
				var classic_content = $('#ig_editor_tab1 #content').val();
				classic_content = classic_content.replace(/^content=/, '');
				$.HandleElement.updatePageBuilder(classic_content, function(){
					$('#status-off').removeClass(class_btn['status-off']);
					$('#status-on').addClass(class_btn['status-on']);
				});
			}
		});

		// Find the Turn-on link the trigger click for it.
		$('#status-on-link', $('#ig_page_builder')).click(function (){
			$('#status-on', $('#ig_page_builder')).trigger('click');
		});
	}

	/**
	 * Update UI of IG PageBuilder
	 */
	$.HandleElement.updatePageBuilder = function (tab_content, callback){
		// disable WP Update button
		$('#publishing-action #publish').attr('disabled', true);
		// show loading indicator
		$(".ig-pb-form-container").css('opacity',0);
		$("#ig-pbd-loading").css('display','block');
		if($.trim(tab_content) != ''){
			$.post(
				Ig_Ajax.ajaxurl,
				{
					action 		: 'text_to_pagebuilder',
					content   : tab_content,
					ig_nonce_check : Ig_Ajax._nonce
				},
				function( data ) {
					_self(data);
				});
		}
		else
			_self('');

		function _self(data){
			// remove current content of IG PageBuilder
			$("#jsn-add-container").prevAll().remove();

			// insert placeholder text to &lt; and &gt; before prepend, then replace it
			data = ig_pb_add_placeholder( data, '&lt;', 'wrapper_append', '&{0}lt;');
			data = ig_pb_add_placeholder( data, '&gt;', 'wrapper_append', '&{0}gt;');
			$(".ig-pb-form-container").prepend(data);
			$(".ig-pb-form-container").html(ig_pb_remove_placeholder($(".ig-pb-form-container").html(), 'wrapper_append', ''));

			if(callback != null)
				callback();

			// show IG PageBuilder
			$("#ig-pbd-loading").hide();
			$(".ig-pb-form-container").animate({
				'opacity':1
			},200,'easeOutCubic');

			// active WP Update button
			$('#publishing-action #publish').removeAttr('disabled');
		}
	}

	/**
	 * Update Content of Classic Editor
	 */
	$.HandleElement.updateClassicEditor	= function (tab_content, callback){
		// update Visual tab content
		if(tinymce.get('content'))
			tinymce.get('content').setContent(tab_content);
		// update Text tab content

		$("#ig_editor_tab1 #content").val(tab_content);

		if(callback != null)
			callback();
		// active WP Update button
		$('#publishing-action #publish').removeAttr('disabled');
	}

	// Disable click on a tag inside preview iframe
	$.HandleElement.disableHref = function() {
		$('#modalOptions a, #shortcode_inner_wrapper a').click(function(e){
			e.preventDefault();
		});
		// disable form submit
		$('#shortcode_inner_wrapper form').submit(function(e){
			e.preventDefault();
			return false;
		});
	}

	/**
	 * Update Content of Classic Editor
	 */
	$.HandleElement.getContent	= function (){
		var tab_content = '';
		$(".ig-pb-form-container.jsn-layout textarea[name^='shortcode_content']").each(function(){
			tab_content += $(this).val();
		});
		return tab_content;
	}

	/**
	 * Deactivate element
	 */
	$.HandleElement.deactivateShow = function() {
		// Disable element
		$('.shortcode-content').each(function(){
			var content = $(this).val();
			var shortcode = $(this).attr('shortcode-name');
			var regex = new RegExp("\\[" + shortcode + '\\s' + '([^\\]])*' + 'disabled_el="yes"' + '([^\\]])*' + '\\]', "g");
			var val = regex.test(content);
			if (val) {
				$(this).parent().addClass('disabled');
				var deactivate_btn = $(this).parent().find('.element-deactivate');
				deactivate_btn.attr('title', Ig_Translate.disabled.reactivate);
				deactivate_btn.find('i').attr('class', 'icon-checkbox-partial');
			}

		});
	}

	/**
	 * Add trigger to activate premade pages modal
	 */
	$.HandleElement.initPremadeLayoutAction = function () {

		// Show modal of layouts
		var modal_width = 500;
		var modal_height = $(window.parent).height()*0.9;
		var frameId = 'ig-layout-lib-modal';
		var modal;

		//----------------------------------- ADD LAYOUT -----------------------------------
		$('#ig_page_builder #page-template #apply-page').click(function(){
			modal = new $.IGModal({
 				frameId: frameId,
 				dialogClass: 'ig-dialog jsn-bootstrap3',
 				jParent : window.parent.jQuery.noConflict(),
 				title: Ig_Translate.layout.modal_title,
 				url: Ig_Ajax.ig_modal_url + '&ig_layout=1',
 				buttons: [{
 					'text'	: Ig_Ajax.cancel,
 					'id'	: 'close',
 					'class' : 'btn btn-default ui-button ui-widget ui-corner-all ui-button-text-only',
 					'click'	: function () {
 						$.HandleElement.hideLoading();
 						$.HandleElement.removeModal();
 					}
 				}],
 				loaded: function (obj, iframe) {
 					$.HandleElement.disablePageScroll();
 				},
 				fadeIn:200,
 				scrollable: true,
 				width: modal_width,
 				height: $(window.parent).height()*0.9
			});
			modal.show();
		});

		// Open save template modal.
		$('#ig_page_builder #page-template #save-as-new').click( function () {
			// Open the loading overlay
			var loading	= $.HandleElement.showLoading();
			// Hide the loading indicator, we don't need it here.
			$('.jsn-modal-indicator').hide();

			$('#save-as-new-dialog').modal();

		} );

		// Click on Save button of the modal.
		$('#save-as-new-dialog .template-save').click (function () {
			// get template content
			var layout_content = '';
			$(".ig-pb-form-container textarea[name^='shortcode_content']").each(function(){
				layout_content += $(this).val();
			});
			layout_content = ig_pb_remove_placeholder(layout_content, 'wrapper_append', '');
			var layout_name	= $('#template-name', $('#save-as-new-dialog')).val();
			if (!layout_name) {
				alert(Ig_Translate.layout.no_layout_name);
				$('#template-name', $('#save-as-new-dialog')).focus();
				return false;
			}
			$('#template-name', $('#save-as-new-dialog')).val('');
			$('#save-as-new-dialog').modal('hide');
			$.HandleElement.showLoading();
			// ajax post to save.
			$.post(
				Ig_Ajax.ajaxurl,
				{
					action		 : 'save_layout',
					layout_name	: layout_name,
					layout_content	: layout_content,
					ig_nonce_check  : Ig_Ajax._nonce
				},
				function(response) {
					$.HandleElement.hideLoading();
					if ( response == 'error' ) {
                		alert( Ig_Translate.layout.name_exist );
                	}
				}
			);
		});
		// Click on Cancel button of the modal.
		$('#save-as-new-dialog .template-cancel').click (function () {
			$('#save-as-new-dialog').modal('hide');
			$.HandleElement.hideLoading();
		});
	}

	/**
	 * Custom CSS for post
	 */
	$.HandleElement.customCss = function () {

		// Show modal
		var modal_width = 600;
		var frameId = 'ig-custom-css-modal';
		var modal;

		var post_id = $('#ig-pb-css-value').find('[name="ig_pb_post_id"]').val();
		var frame_url = Ig_Ajax.ig_modal_url + '&ig_custom_css=1' + '&pid=' + post_id;

		$('.jsn-form-bar #page-custom-css').click(function(){
			if( input_enter ) {
				return;
			}
			modal = new $.IGModal({
				frameId: frameId,
				dialogClass: 'ig-dialog jsn-bootstrap3',
				jParent : window.parent.jQuery.noConflict(),
				title: Ig_Translate.custom_css.modal_title,
				url: frame_url,
				buttons: [{
					'text'	: Ig_Ajax.save,
					'id'	: 'selected',
					'class' : 'btn btn-primary ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only',
					'click'	: function () {

						var jParent = window.parent.jQuery.noConflict();

						// Get css files (link + checked status), save custom css
						var iframe_content = jParent( '#' + frameId ).contents();

						var css_files = [];
						iframe_content.find('#ig-pb-custom-css-box').find('.jsn-items-list').find('li').each(function(i){
							var input = $(this).find('input');
							var checked = input.is(':checked');
							var url = input.val();

							var item = {
								"checked": checked,
								"url": url
							};
							css_files.push(item);
						});
						var css_files = JSON.stringify({data: css_files});

						// get Custom css code
						var custom_css = iframe_content.find('#custom-css').val();

						// save data
						$.post(
							Ig_Ajax.ajaxurl,
							{
								action 		: 'save_css_custom',
								post_id	: post_id,
								css_files   : css_files,
								custom_css   : custom_css,
								ig_nonce_check : Ig_Ajax._nonce
							},
							function( data ) {
								// close loading
								$.HandleElement.hideLoading();
						});

						// close modal
						$.HandleElement.finalize(0);
						// show loading
						$.HandleElement.showLoading();
					}
				},{
					'text'	: Ig_Ajax.cancel,
					'id'	: 'close',
					'class' : 'btn btn-default ui-button ui-widget ui-corner-all ui-button-text-only',
					'click'	: function () {
						$.HandleElement.hideLoading();
						$.HandleElement.removeModal();
					}
				}],
				loaded: function (obj, iframe) {
					$.HandleElement.disablePageScroll();
				},
			fadeIn:200,
				scrollable: true,
				width: modal_width,
				height: $(window.parent).height()*0.9
			});
			modal.show();
		});

		// Return if it's not inside customcss modal.
		if (!document.getElementById('ig-pb-custom-css-box')) {
			return;
		}

		// Transform custom CSS textarea to codeMirror editor
		var editor = CodeMirror.fromTextArea(document.getElementById('custom-css'), {
			mode: "text/css",
			styleActiveLine: true,
			lineNumbers: true,
			lineWrapping: true
		});

		editor.on('change',function (){
			$('#custom-css').html(editor.getValue());
		});

		// Set editor's height to fullfill the modal
		$(window).resize(function() {
			editor.setSize('100%' , $(window).height() - 250);
		});
		/**
		 * Action inside Modal
		 */
		var parent = $('#ig-pb-custom-css-box');
		var css_files = parent.find('.jsn-items-list');

		// sort the CSS files list
		css_files.sortable();

		parent.find('#items-list-edit, #items-list-save').click(function(e){
			e.preventDefault();

			$(this).toggleClass('hidden');
			$(this).parent().find('.btn').not(this).toggleClass('hidden');

			css_files.toggleClass('hidden');
			parent.find('.items-list-edit-content').toggleClass('hidden');

			// get current css files, add to textarea value
			if( $(this).is('#items-list-edit') ) {
				var files = '';
				css_files.find('input').each(function(){
					files += $(this).val() + '\n';
				});
				var textarea = parent.find('.items-list-edit-content').find('textarea');
				textarea.val(files);
				textarea.focus();
			}
		});

		// Save Css files
		parent.find('#items-list-save').click(function(e){
			e.preventDefault();

			/**
			 * Add file to CSS files list
			 */
			// store exist urls
			var exist_urls = new Array();

			// store valid urls
			var valid_urls = new Array();

			// get HTML template of an item in CSS files list
			var custom_css_item_html = $('#tmpl-ig-custom-css-item').html();

			// get list of files url
			var files	= parent.find('.items-list-edit-content').find('textarea').val();
			files = files.split("\n");

			css_files.empty();
			$.each(files, function(i, file){
				var regex = /^[^\s]+\.[^\s]+/i;

				// check if input is something like abc.xyz
				if (regex.test(file))
				{
					css_files.append(custom_css_item_html.replace(/VALUE/g, file).replace(/CHECKED/g, ''));
					valid_urls[i] = file;
				}
			});

			// add loading icon
			css_files.find('li.jsn-item').each(function(){
				var file = $(this).find('input').val();

				// if file is not checked whether exists or not, add loading icon
				if( $.inArray( file, exist_urls ) < 0 ) {
					$(this).append('<i class="jsn-icon16 jsn-icon-loading"></i>');
				}
			});

			var hide_file = function(css_files, file) {
				var item = css_files.find('input[value="'+file+'"]');

				item.attr('disabled', 'disabled');
				item.parents('li').attr('data-title', Ig_Translate.custom_css.file_not_found);

				// remove loading icon
				item.parents('li.jsn-item').find('.jsn-icon-loading').remove();
			}

			// check if file exists
			$.each(valid_urls, function(i, file){
				if (!file) {
					return;
				}

				var file_ = file;

				// check if is relative path
				var regex = /^(?:(?:https?|ftp):\/\/)/i;
				if (!regex.test(file))
				{
					// add WP root path to url to check
					file_ = Ig_Translate.site_url + '/' + file;
				}

				// check if file exists or not
				$.ajax({
					url: file_,
					statusCode: {
						404: function () {
							hide_file(css_files, file);
						}
					},
					success: function () {
						exist_urls[i] = file;

						var item = css_files.find('input[value="'+file+'"]');
						// check the checbox
						item.attr('checked', 'checked');

						// remove loading icon
						item.parents('li.jsn-item').find('.jsn-icon-loading').remove();
					},
					error: function () {
						hide_file(css_files, file);
					}
				});
			});
		});
		// show tooltip
		$.HandleElement.initTooltip( '[data-toggle="tooltip"]', 'auto left' );
	}

	/**
	 * Recognize when hit Enter on textbox
	 */
	$.HandleElement.inputEnter = function() {
		$("input:text").keypress(function (e) {
			if (e.keyCode == 13) {
				input_enter = 1;
			} else {
				input_enter = 0;
			}
		});
    }

    /**
     * Extract shortcode parameters
     */
    $.HandleElement.extractScParam = function(shortcode_content) {
        var result = {};

        var regexp = /(\w+)\s*=\s*"([^"]*)"(?:\s|$)|(\w+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/g;
        var res = shortcode_content.match(regexp);
        for (var i = 0; i < res.length; i++){
            var key_val = res[i];
            if( ! ( key_val.indexOf('[') >= 0 || key_val.indexOf('=') < 0 ) ) {
                var arr     = key_val.split('=');
                var key     = arr[0];
                var value   = $.trim(arr[1]);

                value       = value.replace(/(^"|"$)/g, '');
                result[key] = value;
            }
        }

        return result;
	}

	/**
	 * Renerate a random string
	 */
	function randomString(length) {
		var result 	= '';
		var chars	= '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		for (var i = length; i > 0; --i) result += chars[Math.round(Math.random() * (chars.length - 1))];
		return result;
	}

	/**
	 * Method to resize modal when window resized
	 */
	function resetModalSize(has_submodal, _return) {
		var modal_width, modal_height;

		if( has_submodal == 0 ){
			modal_width = ($(window).width() > 750) ? 750 : $(window).width()*0.9;
			modal_height = $(window.parent).height()*0.9;
		}
		else{
			modal_width = (parent.document.body.clientWidth > 800) ? 800 : parent.document.body.clientWidth*0.9;
			modal_height = parent.document.body.clientHeight*0.95;
		}
		if (_return == 'w'){
			return modal_width;
		}else{
			return modal_height;
		}
	}

	// Init IG PageBuilder element
	$.HandleElement.init = function() {
		$.HandleElement.inputEnter();
		$.HandleElement.addItem();
		$.HandleElement.addElement();
		$.HandleElement.deleteElement();
		$.HandleElement.editElement();
		$.HandleElement.cloneElement();
		$.HandleElement.deactivateElement();
		$.HandleElement.deactivateShow();
		$.HandleElement.initPremadeLayoutAction();
		$.HandleElement.customCss();
		$.HandleElement.checkSelectMedia();
		$.HandleElement.initModeSwitcher();
		$.HandleElement.initStatusSwitcher();
		$.HandleElement.disableHref();
	};

	$(document).ready($.HandleElement.init);
})(jQuery);
