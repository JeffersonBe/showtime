<?php get_header(); ?>

<?php
	$have_posts = have_posts();
	if ( $have_posts)
		the_post();
?>

	<div id="content">
		<div id="content-inner">
		
			<?php get_template_part('/theme/parts/content_header', 'posts'); ?>

			<div class="bd">
				<div class="grid">	
					<div class="c-8">
						<?php if($have_posts): ?>
							<?php rewind_posts(); ?>
							<?php get_template_part( '/theme/parts/posts', 'c-8' ); ?>
						<?php else: ?>
							<?php get_template_part( 'part_no_results' );?>
						<?php endif; ?>		
					</div>
					
					<div class="c-4 sidebar sidebar-after">
						<div class="helper1"><!--  --></div>
						<?php if(is_active_sidebar( 'primary' )): ?>
							<?php dynamic_sidebar( 'primary' ); ?>
						<?php endif; ?>
						<div class="helper2"><!--  --></div>
					</div><!-- .sidebar -->
					
				</div>
			</div><!-- .bd -->

			<?php get_template_part('/theme/parts/content_footer', 'posts'); ?>
	
		</div><!-- #content-inner -->
		<div class="background"><!--  --></div>
	</div><!-- #content -->

<?php get_footer(); ?>