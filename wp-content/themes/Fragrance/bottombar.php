<?php
/**
 * The Sidebar containing the primary and secondary widget areas.
 *
 * @package WordPress
 * @subpackage Fragrance
 * @since Fragrance 1.0
 */
?>
<div id="bottombar">
<ul class="bottom">
    <?php if (is_active_sidebar( 'bottombar' )) : ?>
    <?php dynamic_sidebar( 'bottombar' ); ?>
    <?php else : ?>
    <li class="bottomitem">
      <h3 class="title3">
        <?php _e('pages', 'Fragrance');?>
      </h3>
      <ul>
        <?php wp_list_pages('sort_column=menu_order&depth=1&title_li='); ?>
      </ul>
    </li>
    <li class="bottomitem">
      <h3 class="title3">
        <?php _e('categories', 'Fragrance');?>
      </h3>
      <ul>
        <?php wp_list_categories('title_li='); ?>
      </ul>
    </li>
    <li class="bottomitem">
      <h3 class="title3">
        <?php _e('archives', 'Fragrance');?>
      </h3>
      <ul>
        <?php wp_get_archives('type=monthly');?>
      </ul>
    </li>
    
    <li class="bottomitem">
      <h3 class="title3">
        <?php _e('meta', 'Fragrance'); ?>
      </h3>
      <ul>
        <?php wp_register(); ?>
        <li>
          <?php wp_loginout(); ?>
        </li>
        <?php wp_meta(); ?>
      </ul>
    </li>
    <?php endif; ?>
  </ul>
</div>