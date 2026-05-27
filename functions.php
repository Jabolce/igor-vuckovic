<?php
/**
 * Igor Vuckovic Theme - Functions
 */

// Theme setup
function igor_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ] );

    register_nav_menus( [
        'primary' => __( 'Primary Menu', 'igor-vuckovic' ),
    ] );
}
add_action( 'after_setup_theme', 'igor_setup' );

// Enqueue styles and scripts
function igor_enqueue_assets() {
    // Google Fonts
    wp_enqueue_style(
        'igor-fonts',
        'https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;600;700;900&family=Inter:wght@300;400;500&display=swap',
        [],
        null
    );

    // Main stylesheet
    wp_enqueue_style( 'igor-style', get_stylesheet_uri(), [ 'igor-fonts' ], '1.0.0' );

    // Main JS
    wp_enqueue_script( 'igor-main', get_template_directory_uri() . '/js/main.js', [], '1.0.0', true );

    wp_localize_script( 'igor-main', 'igorData', [
        'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'igor_nonce' ),
        'themeUrl' => get_template_directory_uri(),
    ] );
}
add_action( 'wp_enqueue_scripts', 'igor_enqueue_assets' );

// =========================================================
// CUSTOM POST TYPE: Project
// =========================================================
function igor_register_project_cpt() {
    $labels = [
        'name'               => 'Projects',
        'singular_name'      => 'Project',
        'add_new'            => 'Add New Project',
        'add_new_item'       => 'Add New Project',
        'edit_item'          => 'Edit Project',
        'new_item'           => 'New Project',
        'view_item'          => 'View Project',
        'search_items'       => 'Search Projects',
        'not_found'          => 'No projects found',
        'not_found_in_trash' => 'No projects found in trash',
        'menu_name'          => 'Projects',
    ];

    register_post_type( 'project', [
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'rewrite'            => [ 'slug' => 'work' ],
        'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ],
        'menu_icon'          => 'dashicons-format-video',
        'show_in_rest'       => true,
        'capability_type'    => 'post',
    ] );
}
add_action( 'init', 'igor_register_project_cpt' );

// =========================================================
// CUSTOM TAXONOMY: Project Category
// =========================================================
function igor_register_project_category() {
    $labels = [
        'name'              => 'Project Categories',
        'singular_name'     => 'Project Category',
        'search_items'      => 'Search Categories',
        'all_items'         => 'All Categories',
        'edit_item'         => 'Edit Category',
        'update_item'       => 'Update Category',
        'add_new_item'      => 'Add New Category',
        'new_item_name'     => 'New Category Name',
        'menu_name'         => 'Categories',
    ];

    register_taxonomy( 'project_category', [ 'project' ], [
        'labels'            => $labels,
        'hierarchical'      => true,
        'public'            => true,
        'rewrite'           => [ 'slug' => 'portfolio', 'with_front' => false ],
        'show_admin_column' => true,
        'show_in_rest'      => true,
    ] );
}
add_action( 'init', 'igor_register_project_category' );

// =========================================================
// META BOXES: Project Fields
// =========================================================
function igor_add_project_meta_boxes() {
    add_meta_box(
        'igor_project_details',
        'Project Details',
        'igor_project_details_callback',
        'project',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'igor_add_project_meta_boxes' );

function igor_admin_enqueue_assets( $hook ) {
    if ( 'post.php' === $hook || 'post-new.php' === $hook ) {
        wp_enqueue_script( 'jquery-ui-sortable' );
        wp_add_inline_style( 'common', '
            .drag-handle { cursor: grab; padding: 0 10px; color: #aaa; font-size: 20px; vertical-align: middle; }
            .drag-handle:active { cursor: grabbing !important; }
            .dynamic-credit-row.ui-sortable-helper { background: #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.1); display: table; }
        ' );
    }
}
add_action( 'admin_enqueue_scripts', 'igor_admin_enqueue_assets' );

function igor_project_details_callback( $post ) {
    wp_nonce_field( 'igor_save_project', 'igor_project_nonce' );

    $video_url = get_post_meta( $post->ID, '_project_video_url', true );
    $order     = get_post_meta( $post->ID, '_project_order', true );

    $all_credits = get_post_meta( $post->ID, '_project_all_credits', true );
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
            $val = get_post_meta( $post->ID, $key, true );
            if ( ! empty( $val ) ) {
                $all_credits[] = [ 'label' => $label, 'value' => $val ];
            }
        }
        $old_dynamic = get_post_meta( $post->ID, '_project_dynamic_credits', true );
        if ( is_array( $old_dynamic ) ) {
            foreach ( $old_dynamic as $cred ) {
                if ( ! empty( $cred['label'] ) && ! empty( $cred['value'] ) ) {
                    $all_credits[] = $cred;
                }
            }
        }
    }
    ?>
    <p class="description" style="margin-bottom: 15px;">Drag the <strong style="font-size:16px;">&equiv;</strong> icon to reorder your credits. They will appear on the live site exactly in this order.</p>
    <table class="form-table" style="margin-bottom: 20px;">
        <tbody id="all-credits-tbody">
        <?php foreach ( $all_credits as $index => $credit ) : ?>
            <tr class="dynamic-credit-row">
                <td style="width: 20px;"><span class="dashicons dashicons-menu drag-handle"></span></td>
                <th style="width: 30%;"><input type="text" name="all_credit_labels[]" value="<?php echo esc_attr( $credit['label'] ); ?>" placeholder="Role" class="regular-text" style="width:100%;" /></th>
                <td>
                    <input type="text" name="all_credit_values[]" value="<?php echo esc_attr( $credit['value'] ); ?>" placeholder="Name" class="regular-text" style="width: 60%;" />
                    <button type="button" class="button remove-dynamic-credit" style="color:#a00;">Remove</button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <p><button type="button" class="button button-primary" id="add-dynamic-credit" style="margin-bottom: 30px;">Add Credit / Role</button></p>

    <hr style="margin:20px 0;">
    <h4>Core Project Settings</h4>
    <table class="form-table">
        <tr>
            <th><label for="project_video_url">Video URL (Vimeo / YouTube)</label></th>
            <td><input type="url" id="project_video_url" name="project_video_url" value="<?php echo esc_attr( $video_url ); ?>" class="large-text" placeholder="https://vimeo.com/..." /></td>
        </tr>
        <tr>
            <th><label for="project_order">Display Order</label></th>
            <td><input type="number" id="project_order" name="project_order" value="<?php echo esc_attr( $order ); ?>" class="small-text" placeholder="0" /></td>
        </tr>
    </table>

    <script>
    jQuery(document).ready(function($){
        if($.fn.sortable) {
            $('#all-credits-tbody').sortable({
                handle: '.drag-handle',
                axis: 'y',
                opacity: 0.8
            });
        }
        
        $('#add-dynamic-credit').on('click', function(e){
            e.preventDefault();
            var newRow = '<tr class="dynamic-credit-row">' +
                '<td style="width: 20px;"><span class="dashicons dashicons-menu drag-handle"></span></td>' +
                '<th style="width: 30%;"><input type="text" name="all_credit_labels[]" value="" placeholder="Role" class="regular-text" style="width:100%;" /></th>' +
                '<td><input type="text" name="all_credit_values[]" value="" placeholder="Name" class="regular-text" style="width: 60%;" /> ' +
                '<button type="button" class="button remove-dynamic-credit" style="color:#a00;">Remove</button></td>' +
                '</tr>';
            $('#all-credits-tbody').append(newRow);
        });
        
        $(document).on('click', '.remove-dynamic-credit', function(e){
            e.preventDefault();
            $(this).closest('tr').remove();
        });
    });
    </script>
    <?php
}

function igor_save_project_meta( $post_id ) {
    if ( ! isset( $_POST['igor_project_nonce'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['igor_project_nonce'], 'igor_save_project' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    if ( isset( $_POST['project_video_url'] ) ) {
        update_post_meta( $post_id, '_project_video_url', sanitize_url( $_POST['project_video_url'] ) );
    }
    if ( isset( $_POST['project_order'] ) ) {
        update_post_meta( $post_id, '_project_order', sanitize_text_field( $_POST['project_order'] ) );
    }

    if ( isset( $_POST['all_credit_labels'] ) && isset( $_POST['all_credit_values'] ) ) {
        $labels = $_POST['all_credit_labels'];
        $values = $_POST['all_credit_values'];
        $all_credits = [];
        
        for ( $i = 0; $i < count( $labels ); $i++ ) {
            $label = sanitize_text_field( $labels[ $i ] );
            $value = sanitize_text_field( $values[ $i ] );
            if ( ! empty( $label ) || ! empty( $value ) ) {
                $all_credits[] = [
                    'label' => $label,
                    'value' => $value
                ];
            }
        }
        update_post_meta( $post_id, '_project_all_credits', $all_credits );
    } else {
        delete_post_meta( $post_id, '_project_all_credits' );
    }
}
add_action( 'save_post', 'igor_save_project_meta' );

// =========================================================
// AJAX: Load projects by category
// =========================================================
function igor_get_projects() {
    check_ajax_referer( 'igor_nonce', 'nonce' );

    $category_slug = sanitize_text_field( $_POST['category'] ?? '' );

    $args = [
        'post_type'      => 'project',
        'posts_per_page' => -1,
        'meta_key'       => '_project_order',
        'orderby'        => 'meta_value_num',
        'order'          => 'ASC',
    ];

    if ( $category_slug && $category_slug !== 'all' ) {
        $args['tax_query'] = [
            [
                'taxonomy' => 'project_category',
                'field'    => 'slug',
                'terms'    => $category_slug,
            ],
        ];
    }

    $projects = get_posts( $args );
    $output   = '';

    ob_start();
    foreach ( $projects as $post ) {
        setup_postdata( $post );
        get_template_part( 'template-parts/project', 'card', [ 'post' => $post ] );
    }
    wp_reset_postdata();
    $output = ob_get_clean();

    wp_send_json_success( [ 'html' => $output ] );
}
add_action( 'wp_ajax_igor_get_projects', 'igor_get_projects' );
add_action( 'wp_ajax_nopriv_igor_get_projects', 'igor_get_projects' );

// =========================================================
// HELPER: Get embed URL from Vimeo/YouTube
// =========================================================
function igor_get_embed_url( $url ) {
    if ( empty( $url ) ) return '';

    // Vimeo
    if ( preg_match( '/vimeo\.com\/(\d+)/', $url, $m ) ) {
        return 'https://player.vimeo.com/video/' . $m[1] . '?autoplay=1&color=ffffff&title=0&byline=0&portrait=0';
    }

    // YouTube
    if ( preg_match( '/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $url, $m ) ) {
        return 'https://www.youtube.com/embed/' . $m[1] . '?autoplay=1&rel=0';
    }

    return $url;
}

// =========================================================
// HELPER: Get video thumbnail URL (Fallback for projects)
// =========================================================
function igor_get_video_thumbnail_url( $post_id ) {
    $video_url = get_post_meta( $post_id, '_project_video_url', true );
    if ( empty( $video_url ) ) return '';

    $cached_thumb = get_post_meta( $post_id, '_video_thumb_url', true );
    $cached_source = get_post_meta( $post_id, '_video_thumb_source_url', true );

    // If we have already queried this exact URL, return the cached result
    // (We also cache failures as the string 'failed' to prevent infinite retries)
    if ( $cached_source === $video_url ) {
        return ( $cached_thumb === 'failed' ) ? '' : $cached_thumb;
    }

    $thumb_url = 'failed';

    if ( strpos( $video_url, 'vimeo.com' ) !== false ) {
        $api_url = 'https://vimeo.com/api/oembed.json?url=' . urlencode( $video_url );
        // We use a tight 2-second timeout to ensure the page doesn't hang if Vimeo is slow
        $response = wp_remote_get( $api_url, [ 'timeout' => 2 ] );
        
        if ( ! is_wp_error( $response ) && wp_remote_retrieve_response_code( $response ) === 200 ) {
            $data = json_decode( wp_remote_retrieve_body( $response ), true );
            if ( isset( $data['thumbnail_url'] ) ) {
                $thumb_url = preg_replace('/_(\d+)x(\d+)(.*)$/', '_1280x720$3', $data['thumbnail_url']);
            }
        }
    } elseif ( preg_match( '/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $video_url, $m ) ) {
        $thumb_url = 'https://img.youtube.com/vi/' . $m[1] . '/maxresdefault.jpg';
    }

    // Always log that we checked this URL so we don't query it again
    update_post_meta( $post_id, '_video_thumb_source_url', esc_url_raw( $video_url ) );
    
    // Save the thumb string. If it's a valid link, escape it. If it failed, save 'failed'.
    update_post_meta( $post_id, '_video_thumb_url', $thumb_url === 'failed' ? 'failed' : esc_url_raw( $thumb_url ) );

    return ( $thumb_url === 'failed' ) ? '' : $thumb_url;
}

// =========================================================
// FLUSH REWRITE RULES ON ACTIVATION
// =========================================================
function igor_rewrite_flush() {
    igor_register_project_cpt();
    igor_register_project_category();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'igor_rewrite_flush' );

// Also flush on theme switch
add_action( 'after_switch_theme', function() {
    igor_register_project_cpt();
    igor_register_project_category();
    flush_rewrite_rules();
} );

// =========================================================
// META BOXES: Contact Page Fields
// =========================================================
function igor_add_contact_meta_box() {
    add_meta_box(
        'igor_contact_details',
        'Contact Page Details',
        'igor_contact_details_callback',
        'page',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'igor_add_contact_meta_box' );

// Enqueue media uploader on pages that use the contact template
function igor_contact_enqueue_media( $hook ) {
    if ( ! in_array( $hook, [ 'post.php', 'post-new.php' ] ) ) return;
    $screen = get_current_screen();
    if ( $screen && $screen->post_type === 'page' ) {
        wp_enqueue_media();
    }
}
add_action( 'admin_enqueue_scripts', 'igor_contact_enqueue_media' );

function igor_contact_details_callback( $post ) {
    // Only show on the Contact page template
    $template = get_post_meta( $post->ID, '_wp_page_template', true );
    if ( $template !== 'templates/template-contact.php' ) return;

    wp_nonce_field( 'igor_save_contact', 'igor_contact_nonce' );

    $name       = get_post_meta( $post->ID, '_contact_name',       true ) ?: 'Igor Vuckovic';
    $role       = get_post_meta( $post->ID, '_contact_role',       true ) ?: 'Cinematographer';
    $email      = get_post_meta( $post->ID, '_contact_email',      true ) ?: 'igor@igorvuckovic.com';
    $phone      = get_post_meta( $post->ID, '_contact_phone',      true ) ?: '+381 (0) 60 000 0000';
    $instagram  = get_post_meta( $post->ID, '_contact_instagram',  true ) ?: '@igorvuckovic';
    $agency     = get_post_meta( $post->ID, '_contact_agency',     true );
    $ag_email   = get_post_meta( $post->ID, '_contact_ag_email',   true );
    $ag_phone   = get_post_meta( $post->ID, '_contact_ag_phone',   true );
    $photo_id   = get_post_meta( $post->ID, '_contact_photo_id',   true );
    $photo_url  = $photo_id ? wp_get_attachment_image_url( $photo_id, 'medium' ) : '';
    ?>
    <style>
        .igor-contact-meta table { width: 100%; border-collapse: collapse; }
        .igor-contact-meta th { text-align: left; padding: 8px 12px 8px 0; width: 160px; font-weight: 600; color: #444; vertical-align: middle; }
        .igor-contact-meta td { padding: 6px 0; }
        .igor-contact-meta input[type=text], .igor-contact-meta input[type=email], .igor-contact-meta input[type=tel] { width: 100%; max-width: 480px; }
        .igor-contact-meta .section-label { margin: 16px 0 4px; font-weight: 700; font-size: 12px; text-transform: uppercase; color: #888; letter-spacing: .08em; }
        #contact-photo-preview { display: block; max-width: 120px; max-height: 120px; object-fit: cover; margin-bottom: 8px; border: 1px solid #ddd; border-radius: 2px; }
        #contact-photo-preview.hidden { display: none; }
    </style>
    <div class="igor-contact-meta">
        <table>
            <tr><th><label for="_contact_name">Name</label></th>
                <td><input type="text" id="_contact_name" name="_contact_name" value="<?php echo esc_attr( $name ); ?>" class="regular-text" /></td></tr>
            <tr><th><label for="_contact_role">Role / Title</label></th>
                <td><input type="text" id="_contact_role" name="_contact_role" value="<?php echo esc_attr( $role ); ?>" class="regular-text" /></td></tr>
            <tr><th><label for="_contact_email">Email</label></th>
                <td><input type="text" id="_contact_email" name="_contact_email" value="<?php echo esc_attr( $email ); ?>" class="regular-text" /></td></tr>
            <tr><th><label for="_contact_phone">Phone</label></th>
                <td><input type="text" id="_contact_phone" name="_contact_phone" value="<?php echo esc_attr( $phone ); ?>" class="regular-text" /></td></tr>
            <tr><th><label for="_contact_instagram">Instagram handle</label></th>
                <td><input type="text" id="_contact_instagram" name="_contact_instagram" value="<?php echo esc_attr( $instagram ); ?>" class="regular-text" placeholder="@handle" /></td></tr>
            <tr><th>Photo</th>
                <td>
                    <img id="contact-photo-preview"
                         src="<?php echo esc_url( $photo_url ); ?>"
                         class="<?php echo $photo_url ? '' : 'hidden'; ?>" />
                    <input type="hidden" id="_contact_photo_id" name="_contact_photo_id" value="<?php echo esc_attr( $photo_id ); ?>" />
                    <button type="button" id="contact-photo-btn" class="button"><?php echo $photo_url ? 'Change Photo' : 'Choose Photo'; ?></button>
                    <button type="button" id="contact-photo-remove" class="button" style="<?php echo $photo_url ? '' : 'display:none;'; ?> margin-left:6px;">Remove</button>
                    <script>
                    (function($){
                        var frame;
                        $('#contact-photo-btn').on('click', function(e){
                            e.preventDefault();
                            if(frame){ frame.open(); return; }
                            frame = wp.media({ title: 'Choose Contact Photo', button:{ text:'Use this photo' }, multiple: false });
                            frame.on('select', function(){
                                var att = frame.state().get('selection').first().toJSON();
                                $('#_contact_photo_id').val(att.id);
                                $('#contact-photo-preview').attr('src', att.sizes && att.sizes.medium ? att.sizes.medium.url : att.url).removeClass('hidden');
                                $('#contact-photo-btn').text('Change Photo');
                                $('#contact-photo-remove').show();
                            });
                            frame.open();
                        });
                        $('#contact-photo-remove').on('click', function(){
                            $('#_contact_photo_id').val('');
                            $('#contact-photo-preview').attr('src','').addClass('hidden');
                            $('#contact-photo-btn').text('Choose Photo');
                            $(this).hide();
                        });
                    })(jQuery);
                    </script>
                </td>
            </tr>
        </table>
        <p class="section-label">Agency (optional)</p>
        <table>
            <tr><th><label for="_contact_agency">Agency Name</label></th>
                <td><input type="text" id="_contact_agency" name="_contact_agency" value="<?php echo esc_attr( $agency ); ?>" class="regular-text" /></td></tr>
            <tr><th><label for="_contact_ag_email">Agency Email</label></th>
                <td><input type="text" id="_contact_ag_email" name="_contact_ag_email" value="<?php echo esc_attr( $ag_email ); ?>" class="regular-text" /></td></tr>
            <tr><th><label for="_contact_ag_phone">Agency Phone</label></th>
                <td><input type="text" id="_contact_ag_phone" name="_contact_ag_phone" value="<?php echo esc_attr( $ag_phone ); ?>" class="regular-text" /></td></tr>
        </table>

    </div>
    <?php
}

function igor_save_contact_meta( $post_id ) {
    if ( ! isset( $_POST['igor_contact_nonce'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['igor_contact_nonce'], 'igor_save_contact' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $fields = [ '_contact_name', '_contact_role', '_contact_email', '_contact_phone', '_contact_instagram', '_contact_agency', '_contact_ag_email', '_contact_ag_phone', '_contact_photo_id' ];
    foreach ( $fields as $field ) {
        if ( isset( $_POST[ $field ] ) ) {
            update_post_meta( $post_id, $field, sanitize_text_field( $_POST[ $field ] ) );
        }
    }
}
add_action( 'save_post', 'igor_save_contact_meta' );

// =========================================================
// META BOX: Hero Video URL (shown on the home/front page)
// =========================================================
function igor_add_hero_meta_box() {
    // Only on Pages
    add_meta_box(
        'igor_hero_video',
        '🎬 Hero Video Settings',
        'igor_hero_meta_callback',
        'page',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'igor_add_hero_meta_box' );

function igor_hero_meta_callback( $post ) {
    // Only show on the home/front page
    if ( (int) get_option( 'page_on_front' ) !== $post->ID ) return;

    wp_nonce_field( 'igor_save_hero', 'igor_hero_nonce' );
    $video_url = get_post_meta( $post->ID, '_hero_video_url', true );
    ?>
    <p style="margin-bottom:10px;color:#555;font-size:13px;">
        Paste a <strong>Vimeo</strong> or <strong>YouTube</strong> URL. This video will play fullscreen and muted behind your name on the homepage.
    </p>
    <input type="url"
           id="_hero_video_url"
           name="_hero_video_url"
           value="<?php echo esc_attr( $video_url ); ?>"
           class="large-text"
           placeholder="https://vimeo.com/123456789  or  https://youtu.be/abc123" />
    <p style="margin-top:8px;color:#888;font-size:12px;">
        Leave empty to hide the hero section and show the portfolio grid directly.
    </p>
    <?php
}

function igor_save_hero_meta( $post_id ) {
    if ( ! isset( $_POST['igor_hero_nonce'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['igor_hero_nonce'], 'igor_save_hero' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    if ( isset( $_POST['_hero_video_url'] ) ) {
        update_post_meta( $post_id, '_hero_video_url', esc_url_raw( $_POST['_hero_video_url'] ) );
    }
}
add_action( 'save_post', 'igor_save_hero_meta' );
