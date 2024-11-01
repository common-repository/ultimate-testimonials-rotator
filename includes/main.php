<?php 

  if ( ! defined( 'ABSPATH' ) ) exit; 

/**
 * Class to enqueue styles and scripts
 */

class Bitcx_Utr_Enqueue{
    /**
     * Constructor to set up actions.
     */
    public function __construct(){
        add_action('init', array($this, 'bitcx_utr_enqueue_styles_and_scripts'));
        add_action('wp_enqueue_scripts', array($this, 'bitcx_utr_localize_reviews'));
        add_action('init', array($this, 'bitcx_utr_register_shortcodes'));

    }
    /**
     * Enqueues styles and scripts for the plugin.
     */
    public function bitcx_utr_enqueue_styles_and_scripts() {
    wp_register_style('style',  plugin_dir_url(__FILE__) . '../public/css/style.css' , array(), time());
    wp_register_style('template_flex_reviews_style', plugin_dir_url(__FILE__) . '../public/css/template_flex_reviews.css', array(), time());
    wp_register_script('template_flex_reviews_script', plugin_dir_url(__FILE__) . '../public/js/reviews_handler.js', array(), time(), true);

    wp_enqueue_style('style');
    wp_enqueue_style('template_flex_reviews_style');
    wp_enqueue_script('template_flex_reviews_script');
    }
     /**
     * Registers shortcodes for each taxonomy term.
     */

public function bitcx_utr_register_shortcodes(){
    $bitcx_utr_taxonomies = get_object_taxonomies('bitcx_utr_reviews');

    foreach ($bitcx_utr_taxonomies as $bitcx_utr_taxonomy) {
        $bitcx_utr_terms = get_terms(array(
            'taxonomy' => $bitcx_utr_taxonomy,
            'hide_empty' => false,
        ));

        foreach ($bitcx_utr_terms as $bitcx_utr_term) {
            $bitcx_utr_id = $bitcx_utr_term->term_id;
            $bitcx_utr_slug = $bitcx_utr_term->slug;

            // Create shortcode with term name and id if it doesn't exist
            $bitcx_utr_shortcode = 'shortcode_review_' . $bitcx_utr_id;
            
            if (!shortcode_exists($bitcx_utr_shortcode)) {
                add_shortcode($bitcx_utr_shortcode, function() use ($bitcx_utr_id, $bitcx_utr_slug) {
                    ob_start(); 
                    $bitcx_utr_plugin_dir_path = plugin_dir_path(__FILE__);
                    // Include the template file using the plugin path
                    include($bitcx_utr_plugin_dir_path . '../public/templates/template_flex_reviews.php');
                    $bitcx_utr_output = ob_get_clean();
                    return $bitcx_utr_output;
                });
            }
        }
    }
}

    /**
     * Localizes data for reviews to be used in JavaScript.
     */

     public function bitcx_utr_localize_reviews() {
        // Get all taxonomies associated with the 'bitcx_utr_reviews' custom post type
        $bitcx_utr_taxonomies = get_object_taxonomies('bitcx_utr_reviews');
        $bitcx_utr_data = array();
    
        // Fetch all posts of the 'bitcx_utr_reviews' post type
        $bitcx_utr_all_posts = get_posts(array(
            'post_type' => 'bitcx_utr_reviews',
            'posts_per_page' => -1,
        ));
    
        // Loop through each taxonomy
        foreach ($bitcx_utr_taxonomies as $bitcx_utr_taxonomy) {
            // Get all terms for the current taxonomy
            $bitcx_utr_terms = get_terms(array(
                'taxonomy' => $bitcx_utr_taxonomy,
                'hide_empty' => false,
            ));
    
            $bitcx_utr_taxonomy_data = array(
                'taxonomy' => $bitcx_utr_taxonomy,
                'terms' => array(),
            );
    
            // Loop through each term in the taxonomy
            foreach ($bitcx_utr_terms as $bitcx_utr_term) {
                // Get metadata for the term
                $bitcx_utr_term_meta = get_term_meta($bitcx_utr_term->term_id);
    
                // Set up term data
                $bitcx_utr_term_data = array(
                    'id' => $bitcx_utr_term->term_id,
                    'name' => $bitcx_utr_term->name,
                    'slug' => $bitcx_utr_term->slug,
                    'posts' => array(),
                    'term_meta' => $bitcx_utr_term_meta,
                );
    
                // Loop through all posts and filter by term
                foreach ($bitcx_utr_all_posts as $bitcx_utr_post) {
                    if (has_term($bitcx_utr_term->term_id, $bitcx_utr_taxonomy, $bitcx_utr_post)) {
                        $bitcx_utr_term_data['posts'][] = array(
                            'title' => get_the_title($bitcx_utr_post),
                            'content' => get_the_content(null, false, $bitcx_utr_post),
                        );
                    }
                }
    
                // Add term data to taxonomy data
                $bitcx_utr_taxonomy_data['terms'][] = $bitcx_utr_term_data;
            }
    
            // Add taxonomy data to the final data array
            $bitcx_utr_data[] = $bitcx_utr_taxonomy_data;
        }
    
        // Localize the script with the collected data
        wp_localize_script('template_flex_reviews_script', 'template_flex_reviews_vars', array(
            'taxonomies_data' => $bitcx_utr_data,
        ));
    }
    
}

new Bitcx_Utr_Enqueue();
?>