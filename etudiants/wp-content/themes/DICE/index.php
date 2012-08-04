<?php get_header(); ?>
	
	<?php get_template_part( '/theme/parts/precontent' ); ?>

	<div id="content">
		<div id="content-inner"> 
		
			<?php get_template_part( '/theme/parts/content_header', 'posts' ); ?>
 
			 <div class="bd">
				<div class="grid">
					<div class="c-8">
						<?php get_template_part( '/theme/parts/posts', 'c-8' ); ?>
					</div>
									
					<div class="c-4 sidebar sidebar-after">
						<div class="helper1"><!-- --></div>
						<?php 
							$btp_sidebar = btp_get_first_active_sidebar(array(
								btp_get_theme_option('post_index_sidebar_primary', true),
								'primary'
							));
							if($btp_sidebar) 
								dynamic_sidebar($btp_sidebar);
						?>	
						<div class="helper2"><!-- --></div>
					</div><!-- .sidebar -->	
				</div><!-- .grid -->
			</div><!-- .bd -->
	
			<?php get_template_part( '/theme/parts/content_footer', 'posts' ); ?>
	
		</div><!-- #content-inner -->
		<div class="background"><!--  --></div>
	</div><!-- #content -->

<?php get_footer(); ?>