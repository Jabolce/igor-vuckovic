<?php
/**
 * Generic Page Template
 */
get_header();

the_post();
?>

<div style="max-width:800px;margin:60px auto;padding:0 30px;">
    <h2 style="font-family:'Barlow Condensed',sans-serif;font-size:2rem;letter-spacing:0.1em;text-transform:uppercase;margin-bottom:20px;">
        <?php the_title(); ?>
    </h2>
    <div style="font-size:0.85rem;line-height:1.8;color:#444;">
        <?php the_content(); ?>
    </div>
</div>

<?php get_footer(); ?>
