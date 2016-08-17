<?php
/**
 * Template part for displaying posts.
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

	<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

	<ul class="blog-meta">
		<li class="sv-author"><?php _e('Post By :','storevilla'); ?> <?php the_author_link(); ?></li>
		<li class="sv-time"><?php the_time('M, d Y'); ?></li>
		<li class="sv-category"><?php the_category( ', ' ); ?></li>
		<li class="sv-tags"><?php the_tags(''); ?></li>
		<li class="sv-comments"><?php comments_popup_link( '0 Comment', '1 Comment', '% Comments' ); ?></li>
	</ul>

	<div class="entry-content">
		<?php
			the_content();

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'storevilla' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

</article><!-- #post-## -->
