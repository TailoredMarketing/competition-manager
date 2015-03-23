<?php 
	global $comp_manager;
	$meta = get_post_meta(get_the_ID()); 
?>
<h4 class="wp_comp_intro">To be in with a chance to win this amazing prize, simply tell us:</h4>
<p class="wp_comp_question">Q: <?php echo $meta['wp_comp_question'][0]; ?></p>
<p class="center"><strong><?php $meta['wp_comp_rules'][0]; ?></strong></p>
<p class="center"><?php printf('This competition begins on %s and ends at midnight on %s', date( 'jS F Y', strtotime( $meta['wp_comp_sdate'][0] ) ), date( 'jS F Y', strtotime( $meta['wp_comp_edate'][0] ) ) ) ; ?></p>
<p class="center"><a href="#" class="btn btn-default show_comp">Enter Now</a></p>
<div id="wp_comp_form">
    <?php
        $comp_manager->frontend_form();
    ?>
</div>
        