<?php
/*
Template Name: Home Page
*/
?>
<?php get_header(); ?>
<section id="content" role="main" class="row">
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class('column small-12'); ?>>
<section class="entry-content">
<?php if ( has_post_thumbnail() ) { the_post_thumbnail(); } ?>
<?php the_content(); ?>
<div class="entry-links"><?php wp_link_pages(); ?></div>
</section>
</article>
<?php if ( ! post_password_required() ) comments_template( '', true ); ?>
<?php endwhile; endif; ?>
</section>
<section class="instagram_shots row">
<h3 class="sub-heading">My Instagram Shots</h3>
<?php echo do_shortcode('[instashow source="@seanmwoodsfood" cache_media_time="86400" effect="fade"]'); ?>
<a href="https://instagram.com/seanmwoodsfood" target="_blank" class="call-out">View more on Instagram</a>
</section>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
