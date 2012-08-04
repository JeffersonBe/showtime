<?php get_header(); ?>

<div id="content">
	<div id="content-inner">

		<div class="hd">
			<h1 class="page-title"><?php _e( 'Ooops...', 'btp_theme' ); ?></h1>
		</div>

		<div class="bd">
			<div id="error404">
				
				<p><?php _e( 'This page was not found, page is missing or moved', 'btp_theme' ); ?></p>
						
				<h2><?php _e( 'Try one of the following:', 'btp_theme' ); ?></h2>
				<ul>
					<li>Go to the <a href="<?php echo home_url(); ?>">homepage</a></li>
					<li>Contact us via <a href="mailto=admin@yourcompany.com">admin@yourcompany.com</a></li>
				</ul>
			</div>
		</div>
		
		<?php get_template_part( '/theme/parts/content_footer', '404' ); ?>	
		
	</div><!-- #content-inner -->
	<div class="background"><!--  --></div>	
</div><!-- #content -->
<?php get_footer(); ?>