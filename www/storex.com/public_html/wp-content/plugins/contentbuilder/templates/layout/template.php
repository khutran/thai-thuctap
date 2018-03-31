<?php
/**
 * @version	$Id$
 * @package	IG PageBuilder
 * @author	 InnoGears Team <support@www.innogears.com>
 * @copyright  Copyright (C) 2012 www.innogears.com. All Rights Reserved.
 * @license	GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.www.innogears.com
 * Technical Support:  Feedback - http://www.www.innogears.com
 */

/**
 * @todo : HTML form to save page template
 */
?>
<div id="ig-add-layout" style="display: none;">
    <div class="popover top" style="display: block;">
        <div class="arrow"></div>
        <div class="popover-content">

            <div class="layout-box">
                <div id="save-layout" class="layout-action"><a href="javascript:void(0)"><?php _e( 'Save current content as template', IGPBL ); ?> <i class="icon-star"></i></a></div>
                <div id="save-layout-form" class="input-append hidden layout-toggle-form">
                    <input type="text" name="layout_name" id="layout-name" placeholder="<?php _e( 'Layout name', IGPBL ); ?>">
                    <button class="btn" type="button" ><i class="icon-checkmark"></i></button>
                    <button type="button" class="btn btn-layout-cancel" data-id="save-layout"><i class="icon-remove"></i></button>
                </div>
                <div class="hidden layout-loading"><i class="jsn-icon16 jsn-icon-loading"></i></div>
                <div class="hidden layout-message"><?php _e( 'Saved successfully', IGPBL ); ?></div>
            </div>

            <div id="apply-layout"><a href="javascript:void(0)"><?php _e( 'Apply template from library', IGPBL ); ?></a></div>
        </div>
    </div>
</div>