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
<?php
    include(get_template_directory() . '/includes/instagram.php');
    $isg   = new instagramPHP('seanmwoodsfood', '2092274.1677ed0.58ecb89ee52c47ab8ac5797f7564165c');
    $shots = $isg->getUserMedia(array('count'=>32));
    if(!empty($shots)) { echo $isg->simpleDisplay($shots); }
?>
<div class="lightbox">
<div class="content">
<img src="#">
<div class="caption"></div>
</div>
</div>
</section>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
