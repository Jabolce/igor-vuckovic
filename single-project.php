<?php
/**
 * Single Project Template
 */
get_header();

the_post();

$video_url = get_post_meta( get_the_ID(), '_project_video_url', true );
$embed_url = igor_get_embed_url( $video_url );
$terms     = get_the_terms( get_the_ID(), 'project_category' );

$all_credits = get_post_meta( get_the_ID(), '_project_all_credits', true );
if ( empty( $all_credits ) || ! is_array( $all_credits ) ) {
    $legacy_keys = [
        'Client' => '_project_client',
        'Director' => '_project_director',
        'Production company' => '_project_production_company',
        'Producer' => '_project_producer',
        '1AD' => '_project_1ad',
        'Agency' => '_project_agency',
        'Scenography' => '_project_scenography',
        'Make up' => '_project_make_up',
        'Costume' => '_project_costume',
        'BTS' => '_project_bts',
        'Gaffer' => '_project_gaffer',
        '1AC' => '_project_1ac',
        '2AC' => '_project_2ac',
        'Key Grip' => '_project_key_grip',
        'Rental company' => '_project_rental_company',
        'Colorist' => '_project_colorist',
        'Post production studio' => '_project_post_production_studio',
        'Editor' => '_project_editor',
        'Creative Director' => '_project_creative_director',
        'Steadicam Operator' => '_project_steadicam_operator',
        'Beer-stylist' => '_project_beer_stylist',
        'Location Manager' => '_project_location_manager',
        'Casting' => '_project_casting',
        'Senior Copywriter' => '_project_senior_copywriter',
        'Role' => '_project_role',
        'Year' => '_project_year'
    ];
    $all_credits = [];
    foreach ( $legacy_keys as $label => $key ) {
        $val = get_post_meta( get_the_ID(), $key, true );
        if ( ! empty( $val ) ) {
            $all_credits[] = [ 'label' => $label, 'value' => $val ];
        }
    }
    $old_dynamic = get_post_meta( get_the_ID(), '_project_dynamic_credits', true );
    if ( is_array( $old_dynamic ) ) {
        foreach ( $old_dynamic as $cred ) {
            if ( ! empty( $cred['label'] ) && ! empty( $cred['value'] ) ) {
                $all_credits[] = $cred;
            }
        }
    }
}
?>

<?php
$is_tall_video = strpos( $embed_url, '489946032' ) !== false;
$hero_style = $is_tall_video ? 'style="aspect-ratio: 4/3; max-height: 85vh; width: auto; margin: 0 auto; display: block;"' : '';
?>

<?php if ( $embed_url ) : ?>
<div class="project-hero" style="width: 100%; height: 100vh; max-height: none;">
    <iframe src="<?php echo esc_url( $embed_url ); ?>" allow="autoplay; fullscreen" allowfullscreen></iframe>
</div>
<style>
/* Add a smooth snap effect so scrolling lands perfectly on the fullscreen video */
html { scroll-snap-type: y proximity; }
.single-project .site-header { scroll-snap-align: start; }
.single-project .project-hero { scroll-snap-align: start; scroll-snap-stop: always; }
.single-project .project-content { scroll-snap-align: start; padding-top: 80px; }
</style>
<?php elseif ( has_post_thumbnail() ) : ?>
<div class="project-hero">
    <?php the_post_thumbnail( 'full' ); ?>
</div>
<?php endif; ?>

<div class="project-content">

    <?php if ( $terms && ! is_wp_error( $terms ) ) : ?>
    <p class="project-eyebrow"><?php echo esc_html( $terms[0]->name ); ?></p>
    <?php endif; ?>

    <h2 class="project-title-large"><?php the_title(); ?></h2>

    <div class="project-meta">
        <?php foreach ( $all_credits as $credit ) : ?>
            <?php if ( ! empty( $credit['value'] ) ) : ?>
            <div class="project-meta-item">
                <label><?php echo esc_html( $credit['label'] ); ?></label>
                <span><?php echo esc_html( $credit['value'] ); ?></span>
            </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <?php if ( get_the_content() ) : ?>
    <div class="project-description">
        <?php the_content(); ?>
    </div>
    <?php endif; ?>

</div>

<div class="project-back-link">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>">&#8592; Back to Work</a>
</div>

<?php get_footer(); ?>
