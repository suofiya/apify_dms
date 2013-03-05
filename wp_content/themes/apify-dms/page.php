<?php get_header(); ?>
<!--  page -->
<?php if (have_posts()) : ?>
<div id="wrapper">
	<?php while (have_posts()) : the_post(); ?>
	<?php the_content('Read the rest of this entry &raquo;'); ?>
	<?php endwhile; ?>
</div>
<?php else : ?>
<div class="description">
		<h2 class="center">Not Found</h2>
		<p class="center">Sorry, but you are looking for something that isn't here.</p>
</div>
<?php endif; ?>
<!--  eof page -->
<?php get_footer(); ?>
</body>
</html>
