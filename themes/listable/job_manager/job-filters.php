<?php
/**
 * The template for displaying the WP Job Manager Filters
 *
 * @package Listable
 */
?>

<?php wp_enqueue_script( 'wp-job-manager-ajax-filters' ); ?>

<?php do_action( 'job_manager_job_filters_before', $atts ); ?>

<form class="job_filters">
	<?php do_action( 'job_manager_job_filters_start', $atts ); ?>

	<a href="#" class="findme  js-find-me"></a>

	<div class="search_jobs">
		<?php do_action( 'job_manager_job_filters_search_jobs_start', $atts ); ?>
		<input type="hidden" name="search_keywords" id="search_keywords" value=""/>

		<div class="search_location">
			<label for="search_location"><?php esc_html_e( 'Location', 'listable' ); ?></label>
			<input type="text" name="search_location" id="search_location" placeholder="<?php esc_attr_e( 'Location', 'listable' ); ?>" value="<?php echo esc_attr( $location ); ?>"/>
		</div>

		<div class="select-categories">
			<?php
			if ( $show_categories && get_terms( 'job_listing_category' ) ) :

				//select the current category
				if ( ! empty( $categories ) && empty( $selected_category ) ) {
					if ( isset( $categories[0] ) ) {
						if ( is_string( $categories[0] ) ) {
							$term = get_term_by( 'slug', $categories[0], 'job_listing_category' );
							$selected_category = $term->term_id;
						} else {
							$selected_category = intval( $categories[0] );
						}
					}
				} ?>

				<div class="search_categories">
					<label for="search_categories"><?php esc_html_e( 'Category', 'listable' ); ?></label>
					<?php job_manager_dropdown_categories( array( 'taxonomy'        => 'job_listing_category',
					                                              'hierarchical'    => 1,
					                                              'show_option_all' => esc_html__( 'Any category', 'listable' ),
					                                              'name'            => 'search_categories',
					                                              'orderby'         => 'name',
					                                              'selected'        => $selected_category,
					                                              'multiple'        => false
					) ); ?>
				</div>

			<?php endif; ?>
		</div><!-- .select-categories -->
		<?php
		$job_tags = get_terms( array( 'job_listing_tag' ), array( 'hierarchical' => 1 ) );
		if ( ! is_wp_error( $job_tags ) && ! empty ( $job_tags ) ) { ?>
			<div class="select-tags">
				<select class="tags-select" data-placeholder="<?php esc_html_e( 'Filter by tags', 'listable' ); ?>" name="job_tag_select" multiple>
					<?php foreach ( $job_tags as $term ) : ?>
						<option value="<?php echo $term->name ?>"><?php echo $term->name; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="active-tags"></div>
		<?php }
		do_action( 'job_manager_job_filters_search_jobs_end', $atts ); ?>
	</div><!-- .search_jobs -->

	<div class="mobile-buttons">
		<button class="btn btn--filter"><?php esc_html_e( 'Filter', 'listable' ); ?>
			<span><?php esc_html_e( 'Listings', 'listable' ); ?></span></button>
		<button class="btn btn--view btn--view-map"><span><?php esc_html_e( 'Map View', 'listable' ); ?></span>
		</button>
		<button class="btn btn--view btn--view-cards">
			<span><?php esc_html_e( 'Cards View', 'listable' ); ?> </span></button>
	</div>

	<?php do_action( 'job_manager_job_filters_end', $atts ); ?>

</form><!-- .job_filter -->

<?php do_action( 'job_manager_job_filters_after', $atts ); ?>

<noscript><?php esc_html_e( 'Your browser does not support JavaScript, or it is disabled. JavaScript must be enabled in order to view listings.', 'listable' ); ?></noscript>