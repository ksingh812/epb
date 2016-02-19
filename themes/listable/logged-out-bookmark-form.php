<div class="job-manager-form action--favorite  action  wp-job-manager-bookmarks-form">
	<span class="action__icon">
		<?php get_template_part( 'assets/svg/add-to-favorites-icon-svg' ); ?>
	</span>
	<?php
	$url = listable_get_login_url();
	if ( ! empty( $url ) ) { ?>
	<a class="action__text" href="<?php echo $url; ?>">
		<?php printf( esc_html__( 'Add to favorites', 'listable' ), $post_type->labels->singular_name ); ?>
	</a>
	<a class="action__text--mobile" href="<?php echo $url; ?>">
		<?php printf( esc_html__( 'Favorite', 'listable' ), $post_type->labels->singular_name ); ?>
	</a>
	<?php } ?>
</div>