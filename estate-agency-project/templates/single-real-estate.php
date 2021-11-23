<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Estate-Agency-Project
 * @since 1.0.0
 */

get_header();

while ( have_posts() ) :
	the_post();

	$id = get_the_ID(); ?>

	<article id="post-<?php get_the_ID(); ?>" <?php post_class('eap-single-article'); ?>>

		<a href="<?php echo get_post_type_archive_link('real_estate'); ?>">Retour</a>

		<header class="eap-single-header">
			<?php the_post_thumbnail( 'full' ); ?>
			<?php the_title( '<h1 class="eap-single-title">', '</h1>' ); ?>
			<?php echo "Le : " . get_the_date("d/m/y") . " à " . get_the_date("H:s"); ?>
		</header>

		<div class="eap-single-content">
			<p class="eap-single-info">
				<span class="eap-single-title-span">Prix : </span>
				<?php 
					$price = get_post_meta( $id, 'eap_price', true );
					if ( $price != "Non spécifié")
						$price .= " €"; 
			 		echo $price;
		 		?>
			</p>

			<p class="eap-single-info">
				<span class="eap-single-title-span">Superficie : </span>
				<?php 
					$area = get_post_meta( $id, 'eap_area', true );
					if ( $area != "Non spécifié")
						$area .= " m²"; 
			 		echo $area;
		 		?>
			</p>

			<p class="eap-single-info">
				<span class="eap-single-title-span">Ville : </span>
				<?php echo get_post_meta( $id, 'eap_city', true ); ?>
			</p>

			<p class="eap-single-info">
				<span class="eap-single-title-span">Nature : </span>
				<?php echo get_post_meta( $id, 'eap_nature', true ); ?>
			</p>
		</div>

	</article>

	<?php the_post_navigation(
		[
			'next_text' => '<p>Suivant</p><span>%title</span>',
			'prev_text' => '<p>Précédent</p><span>%title</span>',
		]
	);
endwhile;

get_footer();