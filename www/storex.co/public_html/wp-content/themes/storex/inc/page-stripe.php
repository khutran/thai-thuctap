<?php

function pt_get_post_pageribbon( $post_id ) {
    /* Get the post layout. */
    $ribbon = get_post_meta( $post_id, '-pt-pageribbon', true );
    /* Return the layout if one is found.  Otherwise, return 'default'. */
    return ( !empty( $ribbon ) ? $ribbon : '#f2f5f8' );
    return $ribbon;
}

add_action( 'add_meta_boxes', 'pt_pageribbon_metabox' );

add_action( 'save_post', 'pt_pageribbon_save' );

function pt_pageribbon_metabox() {
    $screens = array( 'post', 'page' );
    wp_enqueue_style('ptpanel-farbtastic-css', get_template_directory_uri() . '/ptpanel/css/farbtastic.css');
    wp_enqueue_script('ptpanel-farbtastic-js',get_template_directory_uri() . '/ptpanel/js/farbtastic.js', array('jquery'));
    foreach ($screens as $screen) {
        add_meta_box(
            'pageribbon_id',
            esc_html__( 'Page Stripe Color <br>(Default: #f2f5f8)', 'storex' ),
            'pt_pageribbon_metabox_contents',
            $screen,
            'side'
        );
    }
}

function pt_pageribbon_metabox_contents($post) {
    wp_nonce_field( basename( __FILE__ ), 'pt_pageribbon_nonce' );
    // Get theme-supported theme layouts

    $current_pageribbon = pt_get_post_pageribbon( $post->ID );
    $admin_page = get_template_directory() . 'ptpanel/ptpanel.php';
    ?>

    <div class="control-group color-picker-container page_banner_color">
        <div class="form_holder">
            <input class="picker_field" data-validation-regex-regex="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$" data-validation-regex-message="Must be HEX color value" type="text" id="shop_banner_color" name="pt_pageribbon" value="<?php echo $current_pageribbon ?>" style="color: rgb(255, 255, 255); background-color: rgb(145, 186, 51);">
        </div>
        <div class="picker-container" id="container_shop_banner_color"><div class="close_picker"></div><div id="picker_shop_banner_color"><div class="farbtastic"><div class="color" style="background-color: rgb(178, 255, 0);"></div><div class="wheel"></div><div class="overlay"></div><div class="h-marker marker" style="left: 179px; top: 80px;"></div><div class="sl-marker marker" style="left: 90px; top: 101px;"></div></div></div></div>
        <p class="help-block"></p></div>
    <script>

        jQuery(document).ready(function($) {

            $('#picker_shop_banner_color').farbtastic('#shop_banner_color');
            $('#shop_banner_color_button').click(function(e){
                var offset = $(this).offset();
                e.preventDefault();
                var offset = $(this).offset();
            });

        });
    </script>
<?php
}

function pt_pageribbon_save ( $post_id) {

    /* Verify the nonce for the post formats meta box. */
    if ( !isset( $_POST['pt_pageribbon_nonce'] ) || !wp_verify_nonce( $_POST['pt_pageribbon_nonce'], basename( __FILE__ ) ) )
        return $post_id;

    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id))
            return $post_id;
    } elseif (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    };

    $old_pageribbon = pt_get_post_layout( $post_id );
    $new_pageribbon = esc_attr( $_POST["pt_pageribbon"]);

    if ($new_pageribbon && $new_pageribbon != $old_pageribbon) {
        update_post_meta( $post_id, '-pt-pageribbon', $new_pageribbon ); }

};