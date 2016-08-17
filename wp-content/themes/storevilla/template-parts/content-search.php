<?php
/**
 * Template part for displaying results in search pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Store_Villa
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('storevilla-blog'); ?>>

	<?php 
		if( has_post_thumbnail() ){
			$image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'storevilla-blog-image', true);
	?>
		<figure>
			<img src="<?php echo esc_url( $image[0] ); ?>" alt="<?php the_title(); ?>">
			<div class="sv-img-hover">
				<div class="holder">				
				</div>
			</div>		
		</figure>
	<?php } ?>

	<div class="sv-post-content">
		
		<div class="post-meta category-name">
			<span><?php the_category( ', ' ); ?></span>
		</div>

		<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<div class="post-meta">
			<span class="date"><?php the_time('M, d Y'); ?></span>
		</div>

		<div class="description"><?php the_excerpt(); ?></div>
		<a href="<?php the_permalink(); ?>" class="sv-btn-countinuereading"><?php _e('countinue reading','storevilla'); ?></a>
		
		<div class="sv-post-foot">
			<?php the_tags(''); ?>
			<div class="post-meta pull-right">
				<span class="sv-post-author"><?php _e('Post By :','storevilla'); ?>
					<?php the_author_link(); ?>
				</span>
			</div>
		</div>
	</div>

</article><!-- #post-## -->

