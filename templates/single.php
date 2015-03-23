<?php get_header(); ?>
<div class="container">
    <h1><span><?php the_title(); ?></span></h1>
    <?php 
        if ( function_exists('yoast_breadcrumb') ) {
            yoast_breadcrumb('<div id="breadcrumbs">','</div>');
        } 
    ?>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-16" id="content">
            <?php the_content(); ?>
            <?php $meta = get_post_meta(get_the_ID()); ?>
            <h4 class="wp_comp_intro">To be in with a chance to win this amazing prize, simply tell us:</h4>
            <p class="wp_comp_question">Q: <?php echo $meta['wp_comp_question'][0]; ?></p>
            <p class="center"><strong><?php the_field('wp_comp_rules'); ?></strong></p>
            <p class="center"><?php printf('This competition begins on %s and ends at midnight on %s', date( 'jS F Y', strtotime( get_field( 'wp_comp_sdate' ) ) ), date( 'jS F Y', strtotime( get_field( 'wp_comp_edate' ) ) ) ) ; ?></p>
            <p class="center"><a href="#" class="btn btn-default show_comp">Enter Now</a></p>
            <div id="wp_comp_form">
                <?php
                    $comp_manager->frontend_form();
                ?>
            </div>
        </div>
        <div class="col-md-1"></div>
        <div class="col-md-7">
            <?php dynamic_sidebar( 'page_sidebar' ); ?> 
        </div>
    </div>
</div>
<?php get_footer(); ?>