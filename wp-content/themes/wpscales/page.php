<?php get_header(); ?>
			<?php while ( have_posts() ) : the_post(); ?>
		        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div class="entry-content">
			        	<?php the_content(); ?>
			    	</div>

			    	<div class="imprimir">
						<?php if(function_exists('pf_show_link')){echo pf_show_link();} ?>
	  		    	</div>
	            </article>		
	        <?php endwhile; ?>
<?php get_footer(); ?>