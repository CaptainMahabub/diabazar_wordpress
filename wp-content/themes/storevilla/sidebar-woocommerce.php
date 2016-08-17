<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Store_Villa
 */

if(is_singular()){
$post_sidebar =  get_theme_mod( 'storevilla_woocommerce_single_products_page_layout','rightsidebar' );
}else{
$post_sidebar =  get_theme_mod( 'storevilla_woocommerce_products_page_layout','rightsidebar' );
}

if( $post_sidebar == 'rightsidebar' && is_active_sidebar('sidebar-1')){
	?>
		<aside id="secondaryright" class="widget-area" role="complementary">
			<?php dynamic_sidebar( 'sidebar-1' ); ?>
		</aside><!-- #secondary -->
	<?php
}

if( $post_sidebar == 'leftsidebar' && is_active_sidebar('sidebar-2')){
	?>
		<aside id="secondaryleft" class="widget-area" role="complementary">
			<?php dynamic_sidebar( 'sidebar-2' ); ?>
		</aside><!-- #secondary -->
	<?php
}

