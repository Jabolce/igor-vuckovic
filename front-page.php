<?php
/**
 * Front Page Template - Splash Screen
 */
get_header();

// Fetch ONE random project that has a video URL
$args = [
    'post_type'      => 'project',
    'posts_per_page' => 1,
    'orderby'        => 'rand',
    'meta_query'     => [
        [
            'key'     => '_project_video_url',
            'compare' => 'EXISTS',
        ],
        [
            'key'     => '_project_video_url',
            'value'   => '',
            'compare' => '!=',
        ]
    ]
];
$random_project = new WP_Query( $args );
$bg_video_url = '';
$bg_thumbnail = '';

if ( $random_project->have_posts() ) {
    $random_project->the_post();
    $bg_post_id   = get_the_ID();
    $bg_video_url = get_post_meta( $bg_post_id, '_project_video_url', true );
    
    if ( has_post_thumbnail( $bg_post_id ) ) {
        $bg_thumbnail = get_the_post_thumbnail_url( $bg_post_id, 'full' );
    } else {
        $bg_thumbnail = igor_get_video_thumbnail_url( $bg_post_id );
    }
}
wp_reset_postdata();

// We need a background-friendly embed URL (muted, autoplay, looping, background mode)
$embed_url = '';
if ( $bg_video_url ) {
    if ( strpos( $bg_video_url, 'vimeo.com' ) !== false ) {
        preg_match( '/vimeo\.com\/(\d+)/', $bg_video_url, $m );
        if ( ! empty( $m[1] ) ) {
            $embed_url = 'https://player.vimeo.com/video/' . $m[1] . '?background=1&autoplay=1&loop=1&byline=0&title=0&muted=1&dnt=1';
        }
    } elseif ( preg_match( '/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $bg_video_url, $m ) ) {
        $embed_url = 'https://www.youtube.com/embed/' . $m[1] . '?autoplay=1&mute=1&controls=0&loop=1&playlist=' . $m[1] . '&modestbranding=1&showinfo=0';
    }
}
?>

<link rel="preconnect" href="https://player.vimeo.com">
<link rel="preconnect" href="https://i.vimeocdn.com">
<link rel="preconnect" href="https://f.vimeocdn.com">
<link rel="preconnect" href="https://www.youtube.com">

<style>
    /* Force lock the viewport so no theme paddings create scrollbars */
    html, body {
        overflow: hidden !important;
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 0;
        background-color: #000;
    }
    .site-main, .site-wrapper {
        padding: 0 !important;
        margin: 0 !important;
        min-height: 0 !important;
    }

    /* Cinematic masking animations to hide intrinsic iframe buffering times */
    .splash-video-bg iframe {
        opacity: 0;
        animation: revealVideo 1.2s ease-in forwards;
        animation-delay: 0.8s; /* Distract user with poster image for 0.8s, then gentle transition into motion */
    }
    
    .splash-content {
        opacity: 0;
        transform: translateY(15px);
        animation: slideFadeIn 1s ease-out forwards;
        animation-delay: 0.2s;
    }

    @keyframes revealVideo {
        0% { opacity: 0; filter: blur(5px); }
        100% { opacity: 1; filter: blur(0); }
    }

    @keyframes slideFadeIn {
        0% { opacity: 0; transform: translateY(15px); }
        100% { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="splash-screen">
    <?php if ( $embed_url ) : ?>
    <div class="splash-video-bg" style="background-image: url('<?php echo esc_url( $bg_thumbnail ); ?>'); background-size: cover; background-position: center;">
        <iframe src="<?php echo esc_url( $embed_url ); ?>" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
    </div>
    <?php endif; ?>

    <div class="splash-overlay"></div>

    <div class="splash-content">
        <h1 class="splash-title">Igor Vukovic</h1>
        <h2 class="splash-subtitle">Director of Photography</h2>

        <nav class="splash-nav">
            <?php
            $categories = get_terms( [ 'taxonomy' => 'project_category', 'hide_empty' => false ] );
            if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
                foreach ( $categories as $cat ) {
                    echo '<a href="' . esc_url( get_term_link( $cat ) ) . '">' . esc_html( $cat->name ) . '</a>';
                }
            } else {
                echo '<a href="' . esc_url( home_url( '/portfolio/commercial/' ) ) . '">Commercial</a>';
                echo '<a href="' . esc_url( home_url( '/portfolio/music-video/' ) ) . '">Music Video</a>';
                echo '<a href="' . esc_url( home_url( '/portfolio/narrative/' ) ) . '">Narrative</a>';
            }
            ?>
            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Contact</a>
        </nav>
    </div>
</div>

<?php 
get_footer();
?>
