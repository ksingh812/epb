<?php
class javo_featured_widget extends WP_Widget
{
	static $load_script;

	public function __construct()
	{
		parent::__construct(
			'javo_featured_widget', // Base ID
			__('[JAVO] Featured Widget', __JAVO), // Name
			array( 'description' => __( 'Javo features item widget', __JAVO ), ) // Args
		);

		add_action( 'widgets_init'	, Array( __CLASS__, 'javo_featured_widget_callback' ) );
		add_action( 'wp_footer'		, Array( __CLASS__, 'enqueue_script' ) );
	}
	public static function javo_featured_widget_callback() {
		register_widget( 'javo_featured_widget' );
	}

	public static function enqueue_script()
	{
		if( self::$load_script ) {
			wp_enqueue_script( 'jQuery-Rating' );
			wp_enqueue_script( 'javo-wg-featured-scripts' );
		}
	}

	public function widget( $args, $instance )
	{
		global $javo_tso;

		self::$load_script		= true;

		$javo_var				= new javo_Array( $instance );
		$javo_wg_featured_args	= Array(
			'post_type'			=> 'item'
			, 'post_status'		=> 'publish'
			, 'posts_per_page'	=> $javo_var->get( 'featured_count', 6 )
			, 'orderby'			=> $javo_var->get( 'random', '' )
			, 'meta_query'		=> Array(
				Array(
					'key'		=> 'javo_this_featured_item'
					, 'compare'	=> '='
					, 'value'	=> 'use'
				)
			)
		);
		$javo_wg_featured		= new WP_Query( $javo_wg_featured_args );

		if( 'grid' === $javo_this_type = $javo_var->get( 'list_type', 'grid' ) ) {
			$is_grid			= true;
		}

		//---- Content -----//
		echo $args[ 'before_widget' ];
		if( '' !== ( $javo_this_title = apply_filters( 'widget_title', $javo_var->get( 'title', '' ) ) ) ) {
			echo "{$args['before_title']}{$javo_this_title}{$args['after_title']}";
		} ?>

		<div class="widget_posts_wrap javo-wgfi-wrap" >
			<ul class="latest-posts items list-unstyled  javo-wgfi-listing-<?php echo $javo_var->get( 'list_type', 'grid' );?>">
				<?php
				if( $javo_wg_featured->have_posts() )
				{
					$i = 1;
					while( $javo_wg_featured->have_posts() )
					{
						$javo_wg_featured->the_post();
						?>
						<li class="col-xs-4 col-sm-4 col-md-4">
							<span class="thumb">
								<a href="<?php the_permalink();?>">
									<div class="img-wrap-shadow">
										<?php
										if( has_post_thumbnail() ){
											the_post_thumbnail('javo-tiny');
										}else{
											printf('<img src="%s" class="wp-post-image" style="width:80px; height:80px;">', $javo_tso->get('no_image', JAVO_IMG_DIR.'/no-image.png'));
										};?>

									</div>
									<div class="label-ribbon-row {f}">
										<div class="label-info-ribbon-row-wrapper">
											<div class="label-info-ribbon-row">
												<div class="ribbons" id="ribbon-15">
													<div class="ribbon-wrap">
														<div class="content">
															<div class="ribbon"><span class="ribbon-span"><?php _e("good", 'javo_fr'); ?></span></div>
														</div><!-- /.content -->
													</div><!-- /.ribbon-wrap -->
												</div><!-- /.ribbons -->
											</div><!-- /.label-info-ribbon -->
										</div><!-- /.ribbon-wrapper -->
									</div><!-- /.label-ribbon -->
								</a>
								<div class="javo-wgfi-listing-meta-container<?php echo isset( $is_grid ) ? ' hidden' : ''; ?>">
									<a href="<?php the_permalink();?>">
										<div class="javo-wgfi-listing-linear-title"><?php the_title(); ?></div>
										<div class="javo-wgfi-listing-linear-description"><?php the_excerpt(); ?></div>
										<div class="javo-wgfi-listing-linear-rating" data-score="<?php echo get_post_meta( get_the_ID(), 'rating_average', true ); ?>">
									</a>
								</div><!-- /.javo-wgfi-listing-meta-container -->
							</span>
						</li><!-- /.col-md-4 -->
						<?php
					}
				}else{
					_e('Not Found Features Items.', __JAVO);
				} ?>

			</ul><!-- /.row -->
		</div><!-- /.widget_posts_wrap -->

		<script type="text/javascript" >
		jQuery( function( $ ){
			var opt = {
				rating:{
					container	: '.javo-wgfi-listing-meta-container > [data-score]'
					, starOff	: '<?php echo JAVO_IMG_DIR?>/star-off-s.png'
					, starOn	: '<?php echo JAVO_IMG_DIR?>/star-on-s.png'
					, starHalf	: '<?php echo JAVO_IMG_DIR?>/star-half-s.png'
				}
			};
			new window.javo_wgfi( opt );
		});

		</script>

		<?php
		wp_reset_query();
		echo $args['after_widget'];
	}

	public function form( $instance )
	{
		$javo_var	= new javo_Array( $instance );

		ob_start(); ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', __JAVO ); ?></label>
			<input
				type	= "text"
				class	= "widefat"
				id		= "<?php echo $this->get_field_id( 'title' ); ?>"
				name	= "<?php echo $this->get_field_name( 'title' ); ?>"
				value	= "<?php echo $javo_var->get( 'title', __( 'Featured Item', __JAVO ) ); ?>" >
		</p>
		<p>
			<input
				type	= "checkbox"
				class	= "widefat"
				id		= "<?php echo $this->get_field_id( 'random' ); ?>"
				name	= "<?php echo $this->get_field_name( 'random' ); ?>"
				value	= "rand"
				<?php checked($random==1);?> >
			<label for="<?php echo $this->get_field_id( 'random' ); ?>"><?php _e( 'Random Ordering',__JAVO ); ?></label>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'featured_count' ) ); ?>"><?php _e( 'Limit:', __JAVO ); ?></label>
			<select
				class	= "widefat"
				name	= "<?php echo $this->get_field_name( 'featured_count' ); ?>"
				id		= "<?php echo $this->get_field_id( 'featured_count' ); ?>" >
				<?php
				for( $i = 1; $i <= 20; $i++ ) {
					echo "<option value=\"{$i}\" ".selected( $i == $javo_var->get( 'featured_count', 6), true, false ).">{$i}</option>";
				}
				?>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'list_type' ) ); ?>"><?php _e( "Display type", __JAVO ); ?>:</label>
			<select
				class	= "widefat"
				name	= "<?php echo $this->get_field_name( 'list_type' ); ?>"
				id		= "<?php echo $this->get_field_id( 'list_type' ); ?>" >
				<?php
				foreach(
					Array(
						__( "Grid Listing (default)", 'javo_fr')	=> 'grid'
						, __( "Line Listing", 'javo_fr')			=> 'linear'
					) as $label => $value
				){
					echo "<option value=\"{$value}\" ".selected( $value == $javo_var->get( 'list_type', 'grid' ), true, false ).">{$label}</option>";
				} ?>
			</select>
		</p>
		<?php
		ob_end_flush();
	}

}

new javo_featured_widget();