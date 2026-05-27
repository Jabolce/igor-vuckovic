<?php
/**
 * 404 Template
 */
get_header();
?>

<div style="text-align:center; padding: 120px 30px;">
    <p style="font-family:'Barlow Condensed',sans-serif; font-size:6rem; font-weight:700; letter-spacing:0.05em; color:#ddd; line-height:1;">404</p>
    <p style="font-size:0.65rem; letter-spacing:0.25em; text-transform:uppercase; color:#888; margin-bottom:30px;">Page not found</p>
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
       style="font-size:0.65rem; letter-spacing:0.2em; text-transform:uppercase; border-bottom:1px solid #111; padding-bottom:2px; color:#111;">
        &larr; Back to Home
    </a>
</div>

<?php get_footer(); ?>
