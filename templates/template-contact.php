<?php
/**
 * Contact Page Template
 * Template Name: Contact
 */
get_header();

$id        = get_the_ID();
$name      = get_post_meta( $id, '_contact_name',      true ) ?: 'Igor Vukovic';
$role      = get_post_meta( $id, '_contact_role',      true ) ?: 'Cinematographer';
$email     = get_post_meta( $id, '_contact_email',     true ) ?: 'i_vukovic@yahoo.com';
$phone     = get_post_meta( $id, '_contact_phone',     true ) ?: '+381 64 117 0 389';
$photo_id  = get_post_meta( $id, '_contact_photo_id',  true );

// Agency info
$agency       = get_post_meta( $id, '_contact_agency',      true ) ?: 'STUNNING';
$ag_heading   = get_post_meta( $id, '_contact_ag_heading',  true ) ?: 'Represented by Stunning Artist';
$ag_rep       = get_post_meta( $id, '_contact_ag_rep',      true ) ?: 'Sarida Bossoni';
$ag_email     = get_post_meta( $id, '_contact_ag_email',    true ) ?: 'hey@stunning-artists.com';
$ag_website   = get_post_meta( $id, '_contact_ag_website',  true ) ?: 'stunning-artists.com';
$ag_phone_1   = get_post_meta( $id, '_contact_ag_phone1',   true ) ?: '+41 44 620 04 48';
$ag_phone_2   = get_post_meta( $id, '_contact_ag_phone2',   true ) ?: '+41 79 279 11 99';
?>

<div class="contact-page">

    <!-- LEFT COLUMN: Photo + Personal Info -->
    <div class="contact-left">

        <div class="contact-photo">
            <?php
            if ( $photo_id ) {
                echo wp_get_attachment_image( $photo_id, 'large', false, [ 'alt' => esc_attr( $name ) ] );
            } elseif ( has_post_thumbnail() ) {
                the_post_thumbnail( 'large', [ 'alt' => esc_attr( $name ) ] );
            } else {
                echo '<img src="' . get_template_directory_uri() . '/images/igor-portrait.jpg" alt="' . esc_attr( $name ) . '" />';
            }
            ?>
        </div>

        <div class="contact-info">
            <p class="contact-name"><?php echo esc_html( $name ); ?></p>
            <p class="contact-role"><?php echo esc_html( $role ); ?></p>

            <?php if ( $email ) : ?>
            <p class="contact-detail">
                <span class="contact-label">Email:</span>
                <a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
            </p>
            <?php endif; ?>

            <?php if ( $phone ) : ?>
            <p class="contact-detail">
                <span class="contact-label">T:</span>
                <a href="tel:<?php echo esc_attr( preg_replace( '/[\s\(\)]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
            </p>
            <?php endif; ?>
        </div>

    </div><!-- .contact-left -->

    <!-- RIGHT COLUMN: Agency -->
    <div class="contact-right">

        <?php if ( $agency ) : ?>
        <div class="contact-agency">
            <p class="contact-agency-name"><?php echo esc_html( $agency ); ?></p>

            <?php if ( $ag_heading ) : ?>
            <p class="contact-agency-heading"><?php echo esc_html( $ag_heading ); ?></p>
            <?php endif; ?>

            <?php if ( $ag_rep ) : ?>
            <p class="contact-agency-rep"><?php echo esc_html( $ag_rep ); ?></p>
            <?php endif; ?>

            <?php if ( $ag_email ) : ?>
            <a href="mailto:<?php echo esc_attr( $ag_email ); ?>" class="contact-agency-email"><?php echo esc_html( $ag_email ); ?></a>
            <?php endif; ?>

            <?php if ( $ag_website ) : ?>
            <a href="https://<?php echo esc_attr( $ag_website ); ?>" target="_blank" rel="noopener noreferrer" class="contact-agency-website"><?php echo esc_html( $ag_website ); ?></a>
            <?php endif; ?>

            <?php if ( $ag_phone_1 ) : ?>
            <a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $ag_phone_1 ) ); ?>" class="contact-agency-phone"><?php echo esc_html( $ag_phone_1 ); ?></a>
            <?php endif; ?>

            <?php if ( $ag_phone_2 ) : ?>
            <a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $ag_phone_2 ) ); ?>" class="contact-agency-phone"><?php echo esc_html( $ag_phone_2 ); ?></a>
            <?php endif; ?>
        </div>
        <?php endif; ?>

    </div><!-- .contact-right -->

</div><!-- .contact-page -->

<?php get_footer(); ?>
