<?php 

   if ( ! defined( 'ABSPATH' ) ) exit;  

/**
 * Class for registering the custom post type and taxonomy, handling term meta, and adding submenu page.
 */
class Bitcx_Utr_Register_Post_Type {
    
     /**
     * Constructor to set up actions and hooks.
     */
    public function __construct(){
        
        add_action('init', array($this, 'bitcx_utr_review_plugin_register_post_type'));
        add_action('init', array($this, 'bitcx_utr_review_plugin_register_taxonomy'));
        add_action('bitcx_utr_review_slider_edit_form_fields', array($this, 'bitcx_utr_add_custom_fields_to_term_edit_page'), 10, 2);
        add_action('create_term', array($this, 'bitcx_utr_add_options_to_term_meta'), 10, 3);
        add_action('edited_bitcx_utr_review_slider', array($this, 'bitcx_utr_save_custom_fields_on_term_update'));
        add_filter('manage_edit-bitcx_utr_review_slider_columns', array($this, 'bitcx_utr_add_shortcode_column'));
        add_action('manage_bitcx_utr_review_slider_custom_column', array($this, 'bitcx_utr_display_shortcode_column'), 10, 3);

        add_action('admin_menu', array($this, 'bitcx_utr_add_taxonomy_submenu_page'));
    }

    /**
     * Registers the custom post type 'bitcx_utr_reviews'.
     */

    public function bitcx_utr_review_plugin_register_post_type() {
        register_post_type('bitcx_utr_reviews', array(
            'labels' => array(
                'name'                => 'Ultimate Testimonials Rotater',
                'singular_name'       => 'Ultimate Testimonials',
                'add_new'             => 'Add New Testimonial',
                'add_new_item'        => 'Add New Testimonial',
                'edit_item'           => 'Edit Testimonial',
                'new_item'            => 'New Testimonial',
                'all_items'           => 'All Testimonials',
                'view_item'           => 'View Testimonial',
                'search_items'        => 'Search Testimonials',
                'not_found'           => 'No testimonials found',
                'not_found_in_trash'  => 'No testimonials found in Trash',
                'menu_name'           => 'Ultimate Testimonials',
            ),
            'public' => true,          // Make the post type publicly accessible
            'has_archive' => true,     // Enable post type archives
            'supports' => array('title', 'editor', 'thumbnail'),  // Support title, editor, and thumbnail features
            'taxonomies' => array('bitcx_utr_review_slider'), // Assign the default slider
        ));
    }

    /**
     * Registers the custom taxonomy 'bitcx_utr_review_slider'.
     */

    public function bitcx_utr_review_plugin_register_taxonomy() {
        register_taxonomy('bitcx_utr_review_slider', 'bitcx_utr_reviews', array(
            'labels' => array(
                'name'              => 'Add New Rotator',
                'singular_name'     => 'Add New rotator',
                'search_items'      => 'Search Rotators',
                'all_items'         => 'All Rotators',
                'parent_item'       => 'Parent rotator',
                'parent_item_colon' => 'Parent rotator:',
                'edit_item'         => 'Edit rotator',
                'update_item'       => 'Update rotator',
                'add_new_item'      => 'Add New rotator',
                'new_item_name'     => 'New rotator Name',
                'menu_name'         => 'Add New Rotator',
            ),
            'hierarchical' => true,
            'rewrite' => array('slug' => 'bitcx_utr_review-slider'), // Replace with your desired slug
        ));
    }

    /**
     * Adds custom fields to the term edit page.
     */

    public function bitcx_utr_add_custom_fields_to_term_edit_page($term) {

       $term_id = $term->term_id;
    
        $desktop_columns = get_term_meta($term_id, 'desktop_columns', true);
        $tablet_columns = get_term_meta($term_id, 'tablet_columns', true);
        $mobile_columns = get_term_meta($term_id, 'mobile_columns', true);
        $delay_time = get_term_meta($term_id, 'delay_time', true);
        $show_buttons = get_term_meta($term_id, 'show_buttons', true);
        $auto_play = get_term_meta($term_id, 'auto_play', true);
        $infinite_loop = get_term_meta($term_id, 'infinite_loop', true);
    
        ?>
        <table class="form-table" role="presentation">
            <tbody>
                <tr class="form-field form-required term-name-wrap">
                    <th scope="row"><label for="desktop_columns"><?php echo esc_html(__('Desktop Columns' , 'ultimate-testimonials-rotator')); ?></label></th>
                    <td>
                        <input name="desktop_columns" id="desktop_columns" type="number" value="<?php echo esc_attr($desktop_columns); ?>" size="40" aria-required="true">
                        <p class="description"><?php echo esc_html(__('The number of columns you want on desktop view.' , 'ultimate-testimonials-rotator')); ?></p>
                    </td>
                </tr>
               
                <tr class="form-field term-slug-wrap">
                    <th scope="row"><label for="tablet_columns"><?php echo esc_html(__('Tablet Columns' , 'ultimate-testimonials-rotator')); ?></label></th>
                    <td>
                        <input name="tablet_columns" id="tablet_columns" type="number" value="<?php echo esc_attr($tablet_columns); ?>" size="40">
                        <p class="description"><?php echo esc_html(__('The number of columns you want on tablet view.' , 'ultimate-testimonials-rotator')); ?></p>
                    </td>
                </tr>
                <tr class="form-field term-parent-wrap">
                    <th scope="row"><label for="mobile_columns"><?php echo esc_html(__('Mobile Columns' , 'ultimate-testimonials-rotator')); ?></label></th>
                    <td>
                        <input name="mobile_columns" id="mobile_columns" type="number" value="<?php echo esc_attr($mobile_columns); ?>" size="40">
                        <p class="description"><?php echo esc_html(__('The number of columns you want on mobile view.' , 'ultimate-testimonials-rotator')); ?></p>
                    </td>
                </tr>
                <tr class="form-field term-parent-wrap">
                    <th scope="row"><label for="delay_time"><?php echo esc_html(__('Delay Time in seconds' , 'ultimate-testimonials-rotator')); ?></label></th>
                    <td>
                        <input name="delay_time" id="delay_time" type="number" value="<?php echo esc_attr($delay_time); ?>" size="40">
                        <p class="description"><?php echo esc_html(__('This is the delay time of each slide.' , 'ultimate-testimonials-rotator')); ?></p>
                    </td>
                </tr>
                <tr class="form-field term-parent-wrap">
                    <th scope="row"><label for="show_buttons"><?php echo esc_html(__('Show Buttons' , 'ultimate-testimonials-rotator')); ?></label></th>
                    <td>
                        <input name="show_buttons" id="show_buttons" type="checkbox" <?php checked($show_buttons, 1); ?>>
                        <p class="description"><?php echo esc_html(__('Option to toggle the visibility of buttons' , 'ultimate-testimonials-rotator')); ?></p>
                    </td>
                </tr>
                <tr class="form-field term-parent-wrap">
                    <th scope="row"><label for="auto_play"><?php echo esc_html(__('Autoplay' , 'ultimate-testimonials-rotator')); ?></label></th>
                    <td>
                        <input name="auto_play" id="auto_play" type="checkbox" <?php checked($auto_play, 1); ?>>
                        <p class="description"><?php echo esc_html(__('Autoplay the slide' , 'ultimate-testimonials-rotator')); ?></p>
                    </td>
                </tr>
                <tr class="form-field term-parent-wrap">
                    <th scope="row"><label for="infinite_loop"><?php echo esc_html(__('Loop' , 'ultimate-testimonials-rotator')); ?></label></th>
                    <td>
                        <input name="infinite_loop" id="infinite_loop" type="checkbox" <?php checked($infinite_loop, 1); ?>>
                        <p class="description"><?php echo esc_html(__('Autoplay the slide' , 'ultimate-testimonials-rotator')); ?></p>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php
    }
    
    /**
     * Saves custom fields on term update.
     */

    public function bitcx_utr_save_custom_fields_on_term_update($term_id) {
        // Ensure that this is not a WordPress autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
    
        // Check if the current user has permission to edit the term
        if (!current_user_can('edit_term', $term_id)) {
            return;
        }

        if (isset($_POST['_wpnounce'])) {
            $nonce = sanitize_text_field($_POST['_wpnounce']);
            if (!wp_verify_nonce($nonce)) {
                return;
            }
        }
        



        if (isset($_POST['desktop_columns'])) {
            update_term_meta($term_id, 'desktop_columns', absint($_POST['desktop_columns']));
        }
    
        if (isset($_POST['tablet_columns'])) {
            update_term_meta($term_id, 'tablet_columns', absint($_POST['tablet_columns']));
        }
    
        if (isset($_POST['mobile_columns'])) {
            update_term_meta($term_id, 'mobile_columns', absint($_POST['mobile_columns']));
        }
    
        if (isset($_POST['delay_time'])) {
            update_term_meta($term_id, 'delay_time', absint($_POST['delay_time']));
        }
    
        $show_buttons = isset($_POST['show_buttons']) ? 1 : 0;
        update_term_meta($term_id, 'show_buttons', $show_buttons);
    
        $auto_play = isset($_POST['auto_play']) ? 1 : 0;
        update_term_meta($term_id, 'auto_play', $auto_play);
    
        $infinite_loop = isset($_POST['infinite_loop']) ? 1 : 0;
        update_term_meta($term_id, 'infinite_loop', $infinite_loop);
    }
    
    /**
     * Adds options to term meta during term creation.
     */

    public function bitcx_utr_add_options_to_term_meta($term_id, $tt_id, $taxonomy) {
        if ($taxonomy === 'bitcx_utr_review_slider') {
            update_term_meta($term_id, 'desktop_columns', 3);
            update_term_meta($term_id, 'tablet_columns', 2);
            update_term_meta($term_id, 'mobile_columns', 1);
            update_term_meta($term_id, 'delay_time', 5);
            update_term_meta($term_id, 'show_buttons', 1);
            update_term_meta($term_id, 'auto_play', 1);
            update_term_meta($term_id, 'infinite_loop', 1);
        }
    }

    /**
     * Adds a 'Shortcode' column to the term list.
     */

    public function bitcx_utr_add_shortcode_column($columns) {
        $columns['shortcode'] = 'Shortcode';
        return $columns;
    }

     /**
     * Displays the shortcode column content.
     */

    public function bitcx_utr_display_shortcode_column($content, $column, $term_id) {
        if ('shortcode' === $column) {
            $shortcode = '[shortcode_review_' . $term_id . ']';
            echo esc_html($shortcode);
        }
    }

    /**
     * Adds a submenu page for Review Rotators under the custom post type menu.
     */

    public function bitcx_utr_add_taxonomy_submenu_page() {
        add_submenu_page(
            'edit.php?post_type=bitcx_utr_reviews',
            'Review Rotators',
            'Review Rotators',
            'manage_options',
            'review-sliders_slug',
            array($this, 'bitcx_utr_taxonomy_submenu_page_content'),
            0
        );
    }

    /**
     * Callback function for the submenu page content.
     */
    
    public function bitcx_utr_taxonomy_submenu_page_content() {

      include_once(plugin_dir_path(__FILE__) . '../../public/templates/review_slider_submenu_page.php');
    }
}

new Bitcx_Utr_Register_Post_Type();
?>