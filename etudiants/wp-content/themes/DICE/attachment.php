<?php get_header(); ?>

<div id="content">
	<div id="content-inner">
	
		<?php get_template_part( '/theme/parts/content_header' ); ?>
	
		<div class="bd">

		<?php if(have_posts()): ?>
			<?php while(have_posts()): the_post(); ?>
			
				<?php if ( wp_attachment_is_image() ) : ?>
					<?php echo wp_get_attachment_image( $post->ID, 'full'); ?>
				<?php endif; ?>
				<?php the_content(); ?>
				
				<div class="entry-utility">		
					<?php edit_post_link( __( 'Edit', 'btp_theme' ), '<span class="edit-link">', '</span>' ); ?>
				</div><!-- .entry-utility -->
				
			<?php endwhile; ?>		
		<?php endif; ?>
	
		</div><!-- .bd -->
		
		<?php get_template_part( '/theme/parts/content_footer', 'attachments' ); ?>

	</div><!-- #content-inner -->
	<div class="background"><!--  --></div>
</div><!-- #content -->

<?php get_footer(); ?>