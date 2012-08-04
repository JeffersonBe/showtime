<div id="comments">
<?php if ( post_password_required() ): ?>
	<?php 
		$btp_object = get_post_type_object(get_post_type());
		$btp_text = sprintf( __( "This %s is password protected. Enter the password to view any comments", 'btp_theme' ), $btp_object->labels->singular_name );
		echo '<p class="no-password">' . esc_html( $btp_text ) . '</p>';
	?>	
<?php else: ?>
	<?php if(get_comments_number()): ?>
		<div class="tabs">
			<div class="tab">
				<?php if (!empty($comments_by_type['comment'])): ?>
					<div class="tab-title">
						<h2 id="comments"><?php echo count($wp_query->comments_by_type['comment']); ?> Comments</h2>
					</div>
					<div class="tab-content">
						<ol class="commentlist">
							<?php wp_list_comments(array( 'type' => 'comment', 'callback' => 'btp_wp_list_comments_callback' )); ?>
						</ol>
					</div>
				<?php endif; ?>
			</div>
			
			<div class="tab">
				<?php if (!empty($comments_by_type['pings'])): ?>
					<div class="tab-title">
						<h2 id="trackbacks"><?php echo count($wp_query->comments_by_type['pings']); ?> Pingbacks &amp; Trackbacks</h2>
					</div>
					<div class="tab-content">
						<ol class="ping-trackbacklist">
							<?php wp_list_comments(array( 'type' => 'pings', 'callback' => 'btp_wp_list_comments_callback' )); ?>
						</ol>
					</div>
				<?php endif; ?>
			</div>
		</div>		
	<?php endif; ?>
	
	<?php 
		$btp_pagination = paginate_comments_links( array( 'echo' => false ) );
		if ( strlen( $btp_pagination ) )
			echo '<div class="pagination">' . $btp_pagination . '</div>';
	
		if ( comments_open() )		
			comment_form();
	?>
	
<?php endif; ?>
</div><!-- #comments -->