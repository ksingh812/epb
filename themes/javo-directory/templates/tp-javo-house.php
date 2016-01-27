<?php
/* Template Name: Javo-House */

get_header();

/* enqueue */{
	wp_enqueue_script( 'google-map' );
	wp_enqueue_script( 'gmap-v3' );
	wp_enqueue_script( 'Google-Map-Info-Bubble' );
	wp_enqueue_script( 'javo-single-property' );
}

?>

<div class="javo-single-ver-house">

	<!-- Header Map -->
	<div class="javo-single-ver-house-map-area"></div>

</div><!-- /.javo-single-ver-house -->

<div class="container">

	<div class="row">
		<div class="col-md-8">


			<!-- Property Author / Admin Menu -->
			<div class="row">
				<div class="col-md-12 text-right">
					작성자일경우만 보임
					<button type="button" class="btn btn-primary btn-sm">
						<i class="fa fa-pencil"></i>
						<?php _e( "Edit", 'javo_fr' );?>
					</button>
				</div><!-- /.col-md-12 -->
			</div>

			<!-- Header -->
			<header class="row">
				<h3 class="page-header"><?php the_title(); ?></h3>
			</header>


			<!-- Meta Informations -->
			<div class="row">
				<div class="col-md-12">

					<div class="well">
						<strong>50 area</strong>

					</div><!-- /.well -->

				</div><!-- /.col-md-12 -->
			</div><!-- /.row -->

		</div><!-- /.col-md-8 -->
		<div class="col-md-3">


			<!-- Agent Information -->
			<div class="row">
				<div class="col-md-12">

					<img src="<?php echo JAVO_IMG_DIR . "/no-image.png";?>" class="img-responsive">


				</div>
			</div>



		</div><!-- /.col-md-3 -->



	</div><!--/.row -->




</div><!-- /.container -->

<fieldset>


</fieldset>

<script type="text/javascript">
jQuery( function( $ ) {
	$.jv_single_property({
		map_el	: $( ".javo-single-ver-house-map-area" )
	});
} );
</script>

<?php
get_footer();