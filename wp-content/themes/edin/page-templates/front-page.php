<?php
/**
 * Template Name: Front Page
 *
 * @package Edin
 */

get_header(); ?>

	<?php while ( have_posts() ) : the_post(); ?>

		<?php get_template_part( 'content', 'hero' ); ?>

	<?php endwhile; ?>

	<?php rewind_posts(); ?>

<?php edin_featured_pages(); ?>
<?php get_sidebar( 'front-page' ); ?>
<?php get_footer(); ?>