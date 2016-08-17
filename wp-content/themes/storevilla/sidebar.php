<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Store_Villa
 */

$post_sidebar = esc_attr(get_post_meta($post->ID, 'storevilla_page_layouts', true));

if(!$post_sidebar){
	$post_sidebar = 'rightsidebar';
}

if ( $post_sidebar ==  'nosidebar' ) {
	return;
}


if( $post_sidebar == 'rightsidebar' || $post_sidebar == 'bothsidebar'  && is_active_sidebar('sidebar-1')){
	?>
		<aside id="secondaryright" class="widget-area" role="complementary">
			<?php dynamic_sidebar( 'sidebar-1' ); ?>
		</aside><!-- #secondary -->
	<?php
}

if( $post_sidebar == 'leftsidebar' || $post_sidebar == 'bothsidebar'  && is_active_sidebar('sidebar-2')){
	?>
		<aside id="secondaryleft" class="widget-area left" role="complementary">
			<?php dynamic_sidebar( 'sidebar-2' ); ?>
		</aside><!-- #secondary -->
	<?php
}
