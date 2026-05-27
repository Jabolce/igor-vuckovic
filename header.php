<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Igor Vukovic – Director of Photography & Cinematographer. Portfolio of commercial, music video and narrative work.">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="site-wrapper">

    <?php if ( ! is_front_page() ) : ?>
    <header class="site-header">
        <div class="site-branding">
            <h1 class="site-title">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">Igor Vukovic</a>
            </h1>
            <p class="site-tagline">Director of Photography</p>
        </div>

        <nav class="site-nav" aria-label="Primary Navigation">
            <?php
            if ( has_nav_menu( 'primary' ) ) {
                wp_nav_menu( [
                    'theme_location' => 'primary',
                    'menu_class'     => 'nav-menu',
                    'container'      => false,
                ] );
            } else {
                $categories   = get_terms( [ 'taxonomy' => 'project_category', 'hide_empty' => false ] );
                $current_cat  = get_query_var( 'project_category' );
                $current_page = get_query_var( 'pagename' );
                ?>
                <ul class="nav-menu" id="primary-nav">
                    <?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
                        <?php foreach ( $categories as $cat ) : ?>
                            <li class="<?php echo ( $current_cat === $cat->slug ) ? 'active' : ''; ?>">
                                <a href="<?php echo esc_url( get_term_link( $cat ) ); ?>"
                                   data-cat="<?php echo esc_attr( $cat->slug ); ?>">
                                    <?php echo esc_html( $cat->name ); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <li class="active"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" data-cat="commercial">Commercial</a></li>
                        <li><a href="#" data-cat="music-video">Music Video</a></li>
                        <li><a href="#" data-cat="narrative">Narrative</a></li>
                    <?php endif; ?>
                    <li class="<?php echo ( $current_page === 'contact' || is_page( 'contact' ) ) ? 'active' : ''; ?>">
                        <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Contact</a>
                    </li>
                </ul>
                <?php
            }
            ?>

            <!-- Hamburger toggle (visible on tablet/mobile only via CSS) -->
            <button class="hamburger-btn" id="hamburger-btn"
                    aria-label="Open menu" aria-expanded="false" aria-controls="mobile-nav">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </nav>
    </header>

    <!-- Full-screen mobile navigation overlay -->
    <nav class="mobile-nav" id="mobile-nav" aria-label="Mobile Navigation" aria-hidden="true">
        <?php
        $mob_categories  = get_terms( [ 'taxonomy' => 'project_category', 'hide_empty' => false ] );
        $mob_current_cat = get_query_var( 'project_category' );
        $mob_current_pg  = get_query_var( 'pagename' );
        ?>
        <ul>
            <?php if ( ! empty( $mob_categories ) && ! is_wp_error( $mob_categories ) ) : ?>
                <?php foreach ( $mob_categories as $cat ) : ?>
                    <li class="<?php echo ( $mob_current_cat === $cat->slug ) ? 'active' : ''; ?>">
                        <a href="<?php echo esc_url( get_term_link( $cat ) ); ?>">
                            <?php echo esc_html( $cat->name ); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            <?php else : ?>
                <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Commercial</a></li>
                <li><a href="#">Music Video</a></li>
                <li><a href="#">Narrative</a></li>
            <?php endif; ?>
            <li class="<?php echo ( $mob_current_pg === 'contact' || is_page( 'contact' ) ) ? 'active' : ''; ?>">
                <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Contact</a>
            </li>
        </ul>
    </nav>
    <?php endif; ?>

    <main class="site-main page-transition">
