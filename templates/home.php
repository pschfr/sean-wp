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
<section class="recipes row">
<?php
    $randomRecipes = new WP_Query(array(
        'post_type'      => 'recipe',
        'orderby'        => 'rand',
        'posts_per_page' => 3
    ));

    while($randomRecipes->have_posts()) : $randomRecipes->the_post(); ?>
        <div class="recipe columns medium-4 small-4">
                <?php the_post_thumbnail(); ?><br>
                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                <?php the_title(); ?>
            </a><br>
            <p><?php the_excerpt(); ?></p>
        </div>
    <?php endwhile;

    wp_reset_postdata();
?>
</section>
<section class="instagram_shots">
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
