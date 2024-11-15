<?php

/**
 * Prints scripts or data in the head tag on the front end.
 *
 */
add_action( 'wp_head', function() : void {
    ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
} );

/* Register styles */
add_action( 'wp_enqueue_scripts', function() {
    wp_enqueue_style( 'reset', trailingslashit( get_template_directory_uri() ) . 'assets/css/reset.css', [ 'normalize' ], filemtime( trailingslashit( get_template_directory() ) . 'assets/css/reset.css' ) );
    wp_enqueue_style( 'normalize', trailingslashit( get_template_directory_uri() ) . 'assets/css/normalize.css', [], filemtime( trailingslashit( get_template_directory() ) . 'assets/css/normalize.css' ) );
    wp_enqueue_style( 'main', trailingslashit( get_template_directory_uri() ) . 'assets/css/main.css', [ 'normalize', 'reset' ], filemtime( trailingslashit( get_template_directory() ) . 'assets/css/main.css' ) );
} );

/**
 * Fires when a site's initialization routine should be executed.
 *
 * @param \WP_Site $new_site New site object.
 * @param array    $args     Arguments for the initialization.
 */
add_action( 'wp_initialize_site', function( \WP_Site $new_site, array $args ) : void {
    $blog_id = $new_site->blog_id;
    $user_id = $args['user_id'];
    $meta = $args['options'];
    if ( isset( $meta['porch_type'] ) ){
        update_blog_option( $blog_id, 'p4m_porch_type_to_set_up', $meta['porch_type'] );
        update_blog_option( $blog_id, 'pt_campaign', $meta );
    }

    $dt_tags = [ 'values' => [ [ 'value' => 'add_to_mailing_list_27' ] ] ]; //P4M Campaign Creator
    $steps_takes = [ 'values' => [ [ 'value' => 'P4M Campaign Creator' ] ] ];
    if ( !empty( $meta['newsletter'] ) ){
        $dt_tags['values'][] = [ 'value' => 'add_to_mailing_list_23' ]; //P4M Newsletter
        $steps_takes['values'][] = [ 'value' => 'P4M Newsletter' ];
    }
    if ( isset( $meta['porch_type'] ) && $meta['porch_type'] === 'ramadan-porch' ){
        $dt_tags['values'][] = [ 'value' => 'add_to_mailing_list_38' ]; // Ramadan 2025
        $dt_tags['values'][] = [ 'value' => 'add_to_mailing_list_30' ]; // Ramadan Champion
        $dt_tags['values'][] = [ 'value' => 'Ramadan 2025 Champion' ]; // Ramadan 202* Champion
    }

    $token = get_option( 'crm_link_token' );
    $domain = get_option( 'crm_link_domain' );

    if ( !$token || !$domain ) {
        error_log( 'token or domain missing in the DB at crm_link_token or crm_link_domain' );
        return;
    }

    $site_key = md5( $token . $domain . get_site()->domain );
    $transfer_token = md5( $site_key . current_time( 'Y-m-dH', 1 ) );

    if ( !$user_id ) {
        $user_id = get_current_user_id();
    }

    $user = get_user_by( 'ID', $user_id );

    if ( !$user ) {
        return;
    }

    if ( !$blog_id ) {
        $blog_id = get_current_blog_id();
    }

    $blog = get_blog_details( $blog_id );

    $email = $user->user_email;
    $fields = [
        'user_info' => [
            'name' => $meta['dt_champion_name'] ?? '',
            'email' => $email,
        ],
        'instance_links' => $blog->siteurl,
        //'dt_prayer_site' => $meta['dt_prayer_site'],
        'dt_reason_for_subsite' => $meta['dt_reason_for_subsite'] ?? '',
        'source' => 'p4m_campaign_create',
        'tags' => $dt_tags,
        'steps_taken' => $steps_takes,
        'notes' => []
    ];
    if ( !empty( $meta['prayer_fuel'] ) ){
        $fields['notes'][] = 'Selected Prayer Fuel' . $meta['prayer_fuel'];
    }
    $args = [
        'method' => 'POST',
        'body' => $fields,
        'headers' => [
            'Authorization' => 'Bearer ' . $transfer_token,
        ],
    ];
    $response = wp_remote_post( 'https://' . $domain . '/wp-json/dt-campaign/v1/contact/import?email=' . urlencode( $email ), $args );
    if ( !is_wp_error( $response ) ){
        $result = json_decode( wp_remote_retrieve_body( $response ), true );
        if ( !empty( $result['contact_id'] ) ){
            update_blog_option( $blog_id, 'p4m_linked_crm_contact', $result['contact_id'] );
        }
    }
    return;
}, 10, 2 );

/**
 * Filters site details and error messages following registration.
 *
 * @param array $result { Array of domain, path, blog name, blog title, user and error messages. @type string         $domain     Domain for the site. @type string         $path       Path for the site. Used in subdirectory installations. @type string         $blogname   The unique site name (slug). @type string         $blog_title Blog title. @type string|WP_User $user       By default, an empty string. A user object if provided. @type WP_Error       $errors     WP_Error containing any errors found.
}
 * @return array { Array of domain, path, blog name, blog title, user and error messages. @type string         $domain     Domain for the site. @type string         $path       Path for the site. Used in subdirectory installations. @type string         $blogname   The unique site name (slug). @type string         $blog_title Blog title. @type string|WP_User $user       By default, an empty string. A user object if provided. @type WP_Error       $errors     WP_Error containing any errors found.
}
 */
add_filter( 'wpmu_validate_blog_signup', function( array $result ) : array {

    require_once( 'bad-words.php' );

    $bad_words = dt_get_bad_words();

    /* check domain, blogname and blog title for key words */
    foreach ( $bad_words as $key_word ) {
        if ( strpos( $result['domain'], $key_word ) !== false ||
        strpos( $result['blog_title'], $key_word ) !== false ||
        strpos( $result['blogname'], $key_word ) !== false ) {
            $result['errors'] = new WP_Error( 'unexpected_key_word', 'There is a banned keyword in the domain or blog title' );
        }
    }

    return $result;
}  );


/**
 * Fires after a network is retrieved.
 *
 * @param \WP_Network $_network Network data.
 * @return \WP_Network Network data.
 */
add_filter( 'get_network', function( \WP_Network $_network ) : \WP_Network {
    global $domain;
    if ( isset( $_SERVER['REQUEST_URI'] ) && $_SERVER['REQUEST_URI'] === '/wp-signup.php' ) {
        $_network->domain = $domain;
    }
    return $_network;
} );

add_filter( 'update_welcome_email', function( $welcome_email, $blog_id, $user_id, $password, $title, $meta ) : string {
    $welcome_email .= "\r\nTo get started please check out the prayer campaigns documentation: https://prayer.tools/docs/overview/ \r\n";

    return $welcome_email;
}, 10, 6 );


//register endpoint
add_action( 'rest_api_init', function() {
    register_rest_route( 'dt-campaigns/v1', 'create_campaign', [
        'methods' => 'POST',
        'callback' => 'create_campaign',
        'permission_callback' => function() {
            return true;
        },
    ] );
} );


/**
 * Endpoint to create a new campaign subsite
 * First time users must activate their account first
 * Upon activate
 * @param WP_REST_Request $request
 * @return array|true[]|WP_Error
 */
function create_campaign( WP_REST_Request $request ){
    $params = $request->get_params();
    if ( empty( $params['email'] ) || empty( $params['campaign_name'] ) || empty( $params['campaign_url'] ) || empty( $params['start_date'] ) ){
        return new WP_Error( 'missing_params', 'Missing required parameters', [ 'status' => 400 ] );
    }

    $dt_cloudflare_site_key = get_site_option( 'dt_cloudflare_site_key', '' );
    $dt_cloudflare_secret_key = get_site_option( 'dt_cloudflare_secret_key', '' );

    if ( empty( $dt_cloudflare_secret_key ) || empty( $dt_cloudflare_site_key ) ){
        return new WP_Error( 'create_campaign', 'Internal Form Error', [ 'status' => 500 ] );
    }

    $cf_token = $params['cf_token'] ?? '';
    if ( empty( $cf_token ) ){
        return new WP_Error( __METHOD__, 'Missing Cloudflare Verification', [ 'status' => 400 ] );
    }

    $ip = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ?? '' ) );
    $url = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
    $response = wp_remote_post( $url, [
        'body' => [
            'secret' => $dt_cloudflare_secret_key,
            'response' => $cf_token,
            'remoteip' => $ip,
        ],
    ] );

    if ( is_wp_error( $response ) ) {
        return new WP_Error( 'cf_token', 'Invalid token', [ 'status' => 400 ] );
    }
    $response_body = json_decode( wp_remote_retrieve_body( $response ), true );
    if ( empty( $response_body['success'] ) ){
        return new WP_Error( 'cf_token', 'Invalid token', [ 'status' => 400 ] );
    }

    $email = $params['email'];
    $name = $params['name'];
    $campaign_name = $params['campaign_name'];
    $campaign_url = $params['campaign_url'];
    $start_date = $params['start_date'];
    $end_date = $params['end_date'];
    $porch_type = $params['porch_type'] ?? ( empty( $end_date ) ? 'ongoing' : 'generic' );

    $meta = [
        'porch_type' => $porch_type,
        'start_date' => $start_date,
        'end_date' => $end_date,
        'newsletter' => $params['newsletter'] ?? false,
        'languages' => $params['languages'] ?? [ 'en_US' ],
        'prayer_fuel' => $params['prayer_fuel'] ?? '',
        'dt_reason_for_subsite' => $params['location'] ?? '',
        'dt_champion_name' => $params['name'] ?? '',
    ];

    //user exists?
    $user = get_user_by( 'email', $email );
    $user_id = $user->ID ?? null;

    $result     = wpmu_validate_blog_signup( $campaign_url, $campaign_name );
    $domain     = $result['domain'];
    $domain = str_replace( 'campaigns.prayer.tools', 'prayer.tools', $domain );
    $path       = $result['path'];
    $blogname   = $result['blogname'];
    $blog_title = $result['blog_title'];
    $errors     = $result['errors'];

    global $wpdb;
    $existing_sites = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->blogs} WHERE domain = %s AND path = %s", $domain, $path ), ARRAY_A );
    if ( !empty( $existing_sites ) ){
        return new WP_Error( 'blog_exists', 'The campaign url is already taken, please try another', [ 'status' => 400 ] );
    }
    //url format
    if ( !preg_match( '|^([a-z0-9-])+$|', $campaign_url ) ) {
        return new WP_Error( 'blogname_error', 'Campaign URL: Only lowercase letters (a-z) and numbers are allowed.', [ 'status' => 400 ] );
    }


    /**
     * If they are a user in the system
     * create the new site
     * email them the confirmation
     *
     */
    if ( !empty( $user ) ){
        $blog_id = wpmu_create_blog( $domain, $path, $campaign_name, $user_id, $meta );
        update_blog_option( $blog_id, 'pt_campaign', $meta );
        update_blog_option( $blog_id, 'p4m_porch_type_to_set_up', $meta['porch_type'] );
        $url = 'https://' . $domain . $path;
        $html = '
Hi ' . ( $name ?: $email ) . ',

<p>Thank you for creating another campaign with Prayer.Tools. Your new campaign is called <strong>' . $campaign_name . '</strong> and can be accessed at <a href="'. $url . '">' . $url . '</a>.</p>

<p>We are excited to see how God uses this new campaign to mobilize extraordinary prayer for a specific people or place.</p>
<br>
Blessings,<br>
The Prayer.Tools Team
        ';

        wp_mail( $email, 'New Campaign Created', $html, [ 'Content-Type: text/html' ] );
        return [
            'success' => true,
            'campaign_url' => $url,
        ];
    }


    wpmu_signup_blog( $domain, $path, $campaign_name, $email, $email, $meta );

    return [
        'success' => true,
    ];
}