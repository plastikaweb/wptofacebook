<?php
//EXCERPT TPL
if ( $my_query->have_posts() ) :
?>
<div class="contents">
<?php //all posts content

while($my_query->have_posts()) : $my_query->the_post();
?>

	<div class="content">

		<div class="permalink_class">
			<h2>
				<a target="_blank" href="<?php the_permalink() ?>" rel="bookmark"
					title="<?php the_title_attribute(); ?>"><?php the_title(); ?>
				</a>
			</h2>
			
			<div class="fb-like" 
			data-href="<?php the_permalink() ?>" 
			data-send="true" data-width="490" data-layout="button_count" data-show-faces="false"></div>
				
			</div>

		
		<div class="date_span">
		<?php the_time( 'd/m' );?>
			<br /> <span class="year"><?php the_time( 'Y' );?> </span>
			<a class="more_class" href="<?php the_permalink()?>" target="_blank">+</a>
		</div>
		
		<!-- Display a comma separated list of the Post's Categories. -->
		<div class="excerpt_class">
		<?php the_excerpt();?>
		</div>
		
	</div>
	<!-- closes the first div box -->
	<?php
endwhile;
wp_reset_query();
?>
</div>
<?php endif;
?>