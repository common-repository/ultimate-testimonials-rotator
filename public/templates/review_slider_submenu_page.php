<?php 

  if ( ! defined( 'ABSPATH' ) ) exit;  
  
$taxonomy = 'bitcx_utr_review_slider';
$post_type = 'bitcx_utr_reviews';

?>

<div class="wrap">
    <h1 class="wp-heading-inline"><?php echo esc_html(sanitize_title(__('Ultimate Testimonials Rotator' , 'ultimate-testimonials-rotator'))); ?></h1>
    <a href="<?php echo esc_url(sanitize_url(admin_url("edit-tags.php?taxonomy=bitcx_utr_review_slider&post_type=bitcx_utr_reviews&orderby=name&order=desc"))); ?>" class="page-title-action"><?php echo esc_html(sanitize_title(__('Add New Rotator' , 'ultimate-testimonials-rotator'))); ?></a>

    <?php

if ( isset( $_POST['_wpnonce'] ) && ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ), sanitize_text_field( $_POST['action'] ) ) ) {
    return;
}


     $orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'name';
     $order = isset($_GET['order']) ? sanitize_text_field($_GET['order']) : 'asc';

    $terms = get_terms(array(
        'taxonomy' => $taxonomy,
        'hide_empty' => false,
        'orderby' => $orderby,
        'order' => $order
    ));
    ?>
    <h3 class="wp-heading-inline"><?php echo esc_html(sanitize_title(__('Testimonials Rotators' , 'ultimate-testimonials-rotator'))); ?></h1>
    <table style="margin-top:30px" class="wp-list-table widefat fixed striped table-view-list tags bitcx_mt-5">
        <caption class="screen-reader-text"><?php echo esc_html(sanitize_title(__('Table ordered hierarchically. Ascending.' , 'ultimate-testimonials-rotator'))); ?></caption>
        <thead>
            <tr>
                <td id="cb" class="manage-column column-cb check-column"><input id="cb-select-all-1" type="checkbox">
                    <label for="cb-select-all-1"><span class="screen-reader-text"><?php echo esc_html(sanitize_title(__('Select All' , 'ultimate-testimonials-rotator'))); ?></span></label>
                </td>
                <?php $order_params = 'orderby=name&order=desc'; ?>
                <th scope="col" id="name" class="manage-column column-name column-primary sorted asc"
                    aria-sort="ascending" abbr="Name"><a
                        href="<?php echo esc_url(sanitize_url(admin_url('edit.php?post_type=bitcx_utr_reviews&page=review-sliders_slug&orderby=name&order=' . ($order == 'asc' ? 'desc' : 'asc')))); ?>"><span><?php echo esc_html(sanitize_title(__('Name' , 'ultimate-testimonials-rotator'))); ?></span><span
                            class="sorting-indicators"><span class="sorting-indicator asc"
                                aria-hidden="true"></span><span class="sorting-indicator desc"
                                aria-hidden="true"></span></span></a></th>
                <th scope="col" id="description" class="manage-column column-description hidden sortable desc"
                    abbr="Description"><a
                        href="<?php echo esc_url(sanitize_url(admin_url('edit.php?post_type=bitcx_utr_reviews&page=review-sliders_slug&orderby=description&order=' . ($order == 'asc' ? 'desc' : 'asc')))); ?>"><span><?php echo esc_html(sanitize_title(__('Description' , 'ultimate-testimonials-rotator'))); ?></span><span
                            class="sorting-indicators"><span class="sorting-indicator asc"
                                aria-hidden="true"></span><span class="sorting-indicator desc"
                                aria-hidden="true"></span></span> <span class="screen-reader-text"><?php echo esc_html(sanitize_title(__('Sort ascending.' , 'ultimate-testimonials-rotator'))); ?></span></a></th>
                <th scope="col" id="slug" class="manage-column column-slug sortable desc" abbr="Slug"><a
                        href="<?php echo esc_url(sanitize_url(admin_url('edit.php?post_type=bitcx_utr_reviews&page=review-sliders_slug&orderby=slug&order=' . ($order == 'asc' ? 'desc' : 'asc')))); ?>"><span><?php echo esc_html(sanitize_title(__('Slug' , 'ultimate-testimonials-rotator'))); ?></span><span
                            class="sorting-indicators"><span class="sorting-indicator asc"
                                aria-hidden="true"></span><span class="sorting-indicator desc"
                                aria-hidden="true"></span></span> <span class="screen-reader-text"><?php echo esc_html(sanitize_title(__('Sort ascending.' , 'ultimate-testimonials-rotator'))); ?></span></a></th>
                <th scope="col" id="posts" class="manage-column column-posts num sortable desc" abbr="Count"><a
                        href="<?php echo esc_url(sanitize_url(admin_url('edit.php?post_type=bitcx_utr_reviews&page=review-sliders_slug&orderby=count&order=' . ($order == 'asc' ? 'desc' : 'asc')))); ?>"><span><?php echo esc_html(sanitize_title(__('Count' , 'ultimate-testimonials-rotator'))); ?></span><span
                            class="sorting-indicators"><span class="sorting-indicator asc"
                                aria-hidden="true"></span><span class="sorting-indicator desc"
                                aria-hidden="true"></span></span> <span class="screen-reader-text"><?php echo esc_html(sanitize_title(__('Sort ascending.' , 'ultimate-testimonials-rotator'))); ?></span></a></th>
                <th scope="col" id="shortcode" class="manage-column column-shortcode"><?php echo esc_html(sanitize_title(__('Shortcode' , 'ultimate-testimonials-rotator'))); ?></th>
            </tr>
        </thead>

        <tbody>
        <?php if (empty($terms)) : ?>
            <tr class="no-items"><td class="colspanchange" colspan="5"><?php echo esc_html(sanitize_title(__('No Rotator found.' , 'ultimate-testimonials-rotator'))); ?></td></tr>
        <?php else : ?>
            <?php foreach ($terms as $term) : ?>
                <tr id="tag-<?php echo esc_attr($term->term_id); ?>" class="level-0" >
                    <th scope="row" class="check-column">
                        <input type="checkbox" name="delete_tags[]" value="<?php echo esc_attr($term->term_id); ?>" id="cb-select-<?php echo esc_attr($term->term_id); ?>">
                        <label for="cb-select-<?php echo esc_attr($term->term_id); ?>">
                            <span class="screen-reader-text">Select <?php echo esc_html(sanitize_title($term->name)); ?></span>
                        </label>
                    </th>
                    <td class="name column-name has-row-actions column-primary" data-colname="Name">
                        <strong>
                            <a class="row-title"
                                href="<?php echo esc_url(sanitize_url(admin_url("term.php?taxonomy={$taxonomy}&tag_ID={$term->term_id}&post_type={$post_type}"))); ?>"
                                aria-label="Edit “<?php echo esc_attr($term->name); ?>”"><?php echo esc_html(sanitize_title($term->name)); ?></a>
                        </strong>
                        <br>
                        <div class="row-actions">
                            <span class="edit">
                                <a href="<?php echo esc_url(sanitize_url(admin_url("term.php?taxonomy={$taxonomy}&tag_ID={$term->term_id}&post_type={$post_type}"))); ?>"
                                    aria-label="Edit “<?php echo esc_attr($term->name); ?>”"><?php echo esc_html(sanitize_title(__('Edit' , 'ultimate-testimonials-rotator'))); ?></a>
                                |
                            </span>
                            <span class="delete">
                                <a href="<?php echo esc_url(sanitize_url(admin_url("edit-tags.php?action=delete&taxonomy={$taxonomy}&tag_ID={$term->term_id}&_wpnonce=" . wp_create_nonce('delete-tag_' . $term->term_id)))); ?>"
                                    class="delete-tag aria-button-if-js"
                                    aria-label="Delete “<?php echo esc_attr($term->name); ?>”" role="button"><?php echo esc_html(sanitize_title(__('Delete' , 'ultimate-testimonials-rotator'))); ?></a>
                                |
                            </span>
                        </div>
                        <button type="button" class="toggle-row">
                            <span class="screen-reader-text"><?php echo esc_html(sanitize_title(__('Show more details' , 'ultimate-testimonials-rotator'))); ?></span>
                        </button>
                    </td>
                    <td class="slug column-slug" data-colname="Slug"><?php echo esc_html(sanitize_title($term->slug)); ?></td>
                    <td class="posts column-posts" data-colname="Count">
                        <a href="<?php esc_url(sanitize_url(admin_url("edit.php?{$taxonomy}={$term->slug}&post_type={$post_type}"))); ?>">
                            <?php echo esc_html(sanitize_title($term->count)); ?>
                        </a>
                    </td>
                    <td class="shortcode column-shortcode" data-colname="Shortcode">
                        [shortcode_review_<?php echo esc_html(sanitize_title($term->term_id)); ?>]
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
        <tfoot>
        <tr>
                <td id="cb" class="manage-column column-cb check-column"><input id="cb-select-all-1" type="checkbox">
                    <label for="cb-select-all-1"><span class="screen-reader-text"><?php echo esc_html(sanitize_title(__('Select All' , 'ultimate-testimonials-rotator'))); ?></span></label>
                </td>
                <?php $order_params = 'orderby=name&order=desc'; ?>
                <th scope="col" id="name" class="manage-column column-name column-primary sorted asc"
                    aria-sort="ascending" abbr="Name"><a
                        href="<?php echo esc_url(sanitize_url(admin_url('edit.php?post_type=bitcx_utr_reviews&page=review-sliders_slug&orderby=name&order=' . ($order == 'asc' ? 'desc' : 'asc')))); ?>"><span><?php echo esc_html(sanitize_title(__('Name' , 'ultimate-testimonials-rotator'))); ?></span><span
                            class="sorting-indicators"><span class="sorting-indicator asc"
                                aria-hidden="true"></span><span class="sorting-indicator desc"
                                aria-hidden="true"></span></span></a></th>
                <th scope="col" id="description" class="manage-column column-description hidden sortable desc"
                    abbr="Description"><a
                        href="<?php echo esc_url(sanitize_url(admin_url('edit.php?post_type=bitcx_utr_reviews&page=review-sliders_slug&orderby=description&order=' . ($order == 'asc' ? 'desc' : 'asc')))); ?>"><span><?php echo esc_html(sanitize_title(__('Description' , 'ultimate-testimonials-rotator'))); ?></span><span
                            class="sorting-indicators"><span class="sorting-indicator asc"
                                aria-hidden="true"></span><span class="sorting-indicator desc"
                                aria-hidden="true"></span></span> <span class="screen-reader-text"><?php echo esc_html(sanitize_title(__('Sort ascending.' , 'ultimate-testimonials-rotator'))); ?></span></a></th>
                <th scope="col" id="slug" class="manage-column column-slug sortable desc" abbr="Slug"><a
                        href="<?php echo esc_url(sanitize_url(admin_url('edit.php?post_type=bitcx_utr_reviews&page=review-sliders_slug&orderby=slug&order=' . ($order == 'asc' ? 'desc' : 'asc')))); ?>"><span><?php echo esc_html(sanitize_title(__('Slug' , 'ultimate-testimonials-rotator'))); ?></span><span
                            class="sorting-indicators"><span class="sorting-indicator asc"
                                aria-hidden="true"></span><span class="sorting-indicator desc"
                                aria-hidden="true"></span></span> <span class="screen-reader-text"><?php echo esc_html(sanitize_title(__('Sort ascending.' , 'ultimate-testimonials-rotator'))); ?></span></a></th>
                <th scope="col" id="posts" class="manage-column column-posts num sortable desc" abbr="Count"><a
                        href="<?php echo esc_url(sanitize_url(admin_url('edit.php?post_type=bitcx_utr_reviews&page=review-sliders_slug&orderby=count&order=' . ($order == 'asc' ? 'desc' : 'asc')))); ?>"><span><?php echo esc_html(sanitize_title(__('Count' , 'ultimate-testimonials-rotator'))); ?></span><span
                            class="sorting-indicators"><span class="sorting-indicator asc"
                                aria-hidden="true"></span><span class="sorting-indicator desc"
                                aria-hidden="true"></span></span> <span class="screen-reader-text"><?php echo esc_html(sanitize_title(__('Sort ascending.' , 'ultimate-testimonials-rotator'))); ?></span></a></th>
                <th scope="col" id="shortcode" class="manage-column column-shortcode"><?php echo esc_html(sanitize_title(__('Shortcode' , 'ultimate-testimonials-rotator'))); ?></th>
            </tr>
        </tfoot>
    </table>
</div>
