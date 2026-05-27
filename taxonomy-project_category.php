<?php
/**
 * Archive template for project_category taxonomy
 */
get_header();

$term      = get_queried_object();
$term_slug = $term ? $term->slug : '';

$args = [
    'post_type'      => 'project',
    'posts_per_page' => -1,
    'meta_key'       => '_project_order',
    'orderby'        => 'meta_value_num',
    'order'          => 'ASC',
    'tax_query'      => [
        [
            'taxonomy' => 'project_category',
            'field'    => 'slug',
            'terms'    => $term_slug,
        ],
    ],
];

$projects = new WP_Query( $args );
?>

<div class="portfolio-grid" id="portfolio-grid">
    <?php if ( $projects->have_posts() ) : ?>
        <?php while ( $projects->have_posts() ) : $projects->the_post(); ?>
            <?php
            $client    = get_post_meta( get_the_ID(), '_project_client', true );
            $video_url = get_post_meta( get_the_ID(), '_project_video_url', true );
            $embed_url = igor_get_embed_url( $video_url );
            ?>
            <a href="<?php echo esc_url( get_permalink() ); ?>" class="portfolio-item-link">
            <article class="portfolio-item"
                     data-embed="<?php echo esc_attr( $embed_url ); ?>"
                     data-title="<?php echo esc_attr( get_the_title() ); ?>">

                <?php if ( has_post_thumbnail() ) : ?>
                    <?php the_post_thumbnail( 'large', [ 'alt' => get_the_title() ] ); ?>
                <?php else : 
                    $fallback = igor_get_video_thumbnail_url( get_the_ID() );
                    if ( ! $fallback ) {
                        $fallback = get_template_directory_uri() . '/images/placeholder.jpg';
                    }
                ?>
                    <img src="<?php echo esc_url( $fallback ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" />
                <?php endif; ?>

                <div class="portfolio-item-overlay">
                    <div>
                        <div class="portfolio-item-title"><?php the_title(); ?></div>
                        <?php if ( $client ) : ?>
                            <div class="portfolio-item-client"><?php echo esc_html( $client ); ?></div>
                        <?php endif; ?>
                    </div>
                </div>

            </article>
            </a>
        <?php endwhile; wp_reset_postdata(); ?>
    <?php else : ?>
        <div class="no-projects" style="grid-column: 1/-1;">
            <p>No projects in this category yet.</p>
        </div>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
