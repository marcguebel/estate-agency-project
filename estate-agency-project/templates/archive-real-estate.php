<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Estate-Agency-Project
 * @since 1.0.0
 */

get_header();

?>

<?php if ( have_posts() ) : ?>

	<div class="eap-archive-container">

		<?php while ( have_posts() ) : 
			the_post();
			$id = get_the_ID(); 
			$price = get_post_meta( $id, 'eap_price', true );
			if ( $price != "Non spécifié")
				$price .= " €"; ?>

			<article onclick="document.getElementById('eap-archive-<?php echo $id; ?>').click();">

				<div class="eap-archive-img">
					<?php the_post_thumbnail( 'post-thumbnail'); ?>
				</div>

				<div class="eap-archive-data">
					<h2><a id="eap-archive-<?php echo $id; ?>" href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h2>
					<?php echo 'Prix : ' . $price; ?>
				</div>

			</article> 

		<?php endwhile; ?>

	</div>

	<div class="eap-archive-pagination"><?php posts_nav_link(); ?></div>

<?php endif; ?>

<?php get_footer(); ?>
