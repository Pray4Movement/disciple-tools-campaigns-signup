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


function dt_pcsu_signup_blog_notification_email_rename( $message, $domain, $path, $title, $user, $user_email, $key, $meta ) {
    return str_replace( 'blog', 'site', $message );
}

add_filter( 'wpmu_signup_blog_notification_email', 'dt_pcsu_signup_blog_notification_email_rename', 10, 8 );

add_action( 'signup_extra_fields', function ( $errors ){
    ?>
  <p>
    <span style="text-decoration: underline;">Already have an account?</span> Sign in instead to create a new prayer campaign site:
    <input type="button" class="button" style="border:1px black solid; color: black"
           onclick="location.href='wp-login.php?redirect_to=<?php echo esc_html( urlencode( site_url( 'wp-signup.php' ) ) ); ?>';"
           value="Sign in"/>
  </p>
    <?php
} );

add_action( 'signup_blogform', function ( $errors ){

    wp_nonce_field( 'dt_extra_meta_info', 'dt_signup_blogform' );
    ?>
    <style>
        #privacy { display: none}
        .private-notice { color: #949494 }
    </style>
    <br>
    <br>
    <br>
    <label for="dt_champion_name">
        What is your name? <span class="private-notice">Answer is kept private.</span>
    </label>
    <input type="text" id="dt_champion_name" name="dt_champion_name">
    <label for="dt_prayer_site">
        Do you have an existing prayer network? If so, what is the link? <span class="private-notice">Answer is kept private.</span>
    </label>
    <input type="text" id="dt_prayer_site" name="dt_prayer_site">
    <label for="dt_reason_for_subsite">
        What is your target location or people group? <span class="private-notice">Answer is kept private.</span>
    </label>
    <input type="text" id="dt_reason_for_subsite" name="dt_reason_for_subsite">
    <p>
        <label>Choose a <a target="_blank" href="https://prayer.tools/docs/campaign-types/">Campaign Type</a>:</label>
            <?php
            $wizard_types = apply_filters( 'dt_campaigns_wizard_types', [] );
            if ( empty( $wizard_types ) ){
                $wizard_types = [
                    'ongoing-porch' => [
                        'campaign_type' => 'ongoing',
                        'porch' => 'ongoing-porch',
                        'label' => '24/7 Ongoing Campaign',
                    ],
                    '24hour' => [
                        'campaign_type' => '24hour',
                        'porch' => 'generic-porch',
                        'label' => '24/7 Campaign with a start and end date'
                    ],
                    'ramadan-porch' => [
                        'campaign_type' => '24hour',
                        'porch' => 'ramadan-porch',
                        'label' => '24/7 Ramadan Template',
                    ],
                ];
            }
            foreach ( $wizard_types as $type => $type_value ): ?>
                <label>
                    <input type="radio" name="porch_type" value="<?php echo esc_html( $type ); ?>" required>
                    <?php echo esc_html( $type_value['label'] ); ?>
                </label>
            <?php endforeach; ?>

    </p>


    <p>
        <label for="dt_newsletter">
            <input id="dt_newsletter" type="checkbox" name="dt_newsletter" checked>
            <strong>Sign up for Pray4movement news and opportunities, and occasional communication from GospelAmbition.org</strong>
        </label>
        <label for="p4m_agreement">
            <input id="p4m_agreement" type="checkbox" name="p4m_agreement" required>
            <span>
                I agree to use this prayer campaign tool in accordance with the <a href="https://pray4movement.org/about/" target="_blank">vision and intent</a> of Pray4Movement to mobilize extraordinary prayer for a specific people or place.
            </span>
        </label>
        <label for="p4m_list_agreement">
            <input id="p4m_list_agreement" type="checkbox" name="p4m_list_agreement" required>
                I agree that my prayer campaign can be listed on Pray4Movements.org
        </label>
    </p>


    <!--remove the blog name field so the user put in the campaign name-->
    <script type="text/javascript">
        document.getElementById('blogname').value = '';
    </script>

    <?php
} );


// store extra fields in wp_signups table while activating user
add_filter( 'add_signup_meta', 'dt_add_signup_meta' );
function dt_add_signup_meta( $meta ){

    if ( !isset( $_POST['dt_signup_blogform'] ) || !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['dt_signup_blogform'] ) ), 'dt_extra_meta_info' ) ) {
        return;
    }

    if ( isset( $_POST['dt_newsletter'] ) ){
        $meta['dt_newsletter'] = 1;
    }
    if ( isset( $_POST['porch_type'] ) ){
        $meta['porch_type'] = sanitize_text_field( wp_unslash( $_POST['porch_type'] ) );
    }
    if ( isset( $_POST['dt_champion_name'] ) ) {
        $meta['dt_champion_name'] = sanitize_text_field( wp_unslash( $_POST['dt_champion_name'] ) );
    }
    if ( isset( $_POST['dt_prayer_site'] ) ) {
        $meta['dt_prayer_site'] = sanitize_text_field( wp_unslash( $_POST['dt_prayer_site'] ) );
    }
    if ( isset( $_POST['dt_reason_for_subsite'] ) ) {
        $meta['dt_reason_for_subsite'] = sanitize_text_field( wp_unslash( $_POST['dt_reason_for_subsite'] ) );
    }

    return $meta;
}

/**
 * Fires when a site's initialization routine should be executed.
 *
 * @param \WP_Site $new_site New site object.
 * @param array    $args     Arguments for the initialization.
 */
add_action( 'wp_initialize_site', function( \WP_Site $new_site, array $args ) : void {
    $domain = $new_site->domain;
    $blog_id = $new_site->blog_id;
    $user_id = $args['user_id'];
    $meta = $args['options'];
    if ( isset( $meta['porch_type'] ) ){
        update_blog_option( $blog_id, 'p4m_porch_type_to_set_up', $meta['porch_type'] );
    }

    $dt_tags = [ 'values' => [ [ 'value' => 'add_to_mailing_list_27' ] ] ]; //P4M Campaign Creator
    $steps_takes = [ 'values' => [ [ 'value' => 'P4M Campaign Creator' ] ] ];
    if ( isset( $meta['dt_newsletter'] ) ){
        $dt_tags['values'][] = [ 'value' => 'add_to_mailing_list_23' ]; //P4M Newsletter
        $steps_takes['values'][] = [ 'value' => 'P4M Newsletter' ];
    }
    if ( isset( $meta['porch_type'] ) && $meta['porch_type'] === 'ramadan-porch' ){
        $dt_tags['values'][] = [ 'value' => 'add_to_mailing_list_28' ]; // Ramadan 2024
        $dt_tags['values'][] = [ 'value' => 'add_to_mailing_list_30' ]; // Ramadan Champion
        $dt_tags['values'][] = [ 'value' => 'Ramadan ' . gmdate( 'Y' ) . ' Champion' ]; // Ramadan 202* Champion
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
            'name' => $meta['dt_champion_name'],
            'email' => $email,
        ],
        'instance_links' => $blog->siteurl,
        'dt_prayer_site' => $meta['dt_prayer_site'],
        'dt_reason_for_subsite' => $meta['dt_reason_for_subsite'],
        'source' => 'p4m_campaign_create',
        'tags' => $dt_tags,
        'steps_taken' => $steps_takes,
    ];
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
 * Fires before the site Sign-up form.
 *
 */
add_action( 'before_signup_form', function() : void {
    global $domain, $dt_old_domain;

    $dt_old_domain = $domain;

    $needle = 'campaigns.';

    if ( stripos( $domain, $needle ) === 0 && strpos( $domain, 'pray4movement.org' ) !== false ){
        //phpcs:ignore
        $domain = substr( $domain, strlen( $needle ) );
    }

} );

add_action( 'signup_hidden_fields', function ( $stage ){
    if ( $stage === 'validate-site' ) :?>
        <p>Please choose a <strong>Site Domain</strong> and <strong>Site Title</strong> that describes your prayer
            focus. We recommend domains like pray4france, pray4france-ramadan, france-ramadan, france-lent, france247, etc. The Site Domain
            and Site Title will be publicly visible.</p>
    <?php endif;
} );


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

/**
 * Fires when the site or user sign-up process is complete.
 *
 */
add_action( 'after_signup_form', function() : void {
    global $domain, $dt_old_domain;

    //phpcs:ignore
    $domain = $dt_old_domain;
} );


add_filter( 'update_welcome_email', function( $welcome_email, $blog_id, $user_id, $password, $title, $meta ) : string {
    $welcome_email .= "\r\nTo get started please check out the prayer campaigns documentation: https://prayer.tools/docs/overview/ \r\n";

    return $welcome_email;
}, 10, 6 );