<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @since 1.0.0
 */

get_header();

?>

<?php if ( have_posts() ) : ?>
<!-- .page-header -->

	<div class="eap-container">
		<?php while ( have_posts() ) : ?>
			<?php $price = get_post_meta( get_the_ID(), 'eap_price', true );
			if( $price != "Non spécifié")
				$price .= " €"; ?>
			<article onclick="document.getElementById('eap-<?php the_post(); ?>').click();">
				<div class="eap-img">
					<?php the_post_thumbnail( 'post-thumbnail', ['class' => 'eap-img-responsive'] ); ?>
				</div>
				<div class="eap-data">
					<?php the_post(); ?>
					<h2><a id="eap-<?php get_the_ID(); ?>" href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h2>
					<?php echo 'Prix : ' . $price; ?>
				</div>
			</article> 
		<?php endwhile; ?>
	</div>

	<div class="eap-pagination"><?php posts_nav_link(); ?></div>

<?php else : ?>
	<?php get_template_part( 'template-parts/content/content-none' ); ?>
<?php endif; ?>

<?php get_footer(); ?>
