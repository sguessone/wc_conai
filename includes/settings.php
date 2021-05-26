<?php
 
function wc_conai_settings_init() {
    register_setting( 'wc_conai', 'wc_conai_options' );
 
    add_settings_section(
        'wc_conai_section_developers',
        __( 'Impostazioni per la gestione del conai.', 'wc_conai' ),
        'wc_conai_section_developers_cb',
        'wc_conai'
    );

    add_settings_field(
        'wc_conai_field_json',
        __( 'json', 'wc_conai' ),
        'wc_conai_json_cb',
        'wc_conai',
        'wc_conai_section_developers',
        [
            'label_for' => 'wc_conai_json',
            'class' => 'wc_conai_row'
        ]
    );
}
add_action( 'admin_init', 'wc_conai_settings_init' );
 
function wc_conai_section_developers_cb( $args ) {
    ?>
        <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Compila tutte le sezioni per attivare il servizio.', 'wc_conai' ); ?></p>
    <?php
}

function wc_conai_json_cb( $args ) {
    $options = get_option( 'wc_conai_options' );
    ?>

        <textarea id="<?php echo esc_attr( $args['label_for'] ); ?>" data-custom="<?php echo esc_attr( $args['wc_conai_custom_data'] ); ?>" name="wc_conai_options[<?php echo esc_attr( $args['label_for'] ); ?>]" rows="4" cols="50"><?php echo isset( $options[ $args['label_for'] ] ) ? $options[ $args['label_for'] ] : ''; ?></textarea>

        <p class="description">
            <?php esc_html_e( 'I valori attualmente impoostati sono presi da https://www.cial.it/wp-content/uploads/2016/01/Guida_Contributo_CONAI_2020_Vol1.pdf', 'wc_conai' ); ?>
        </p>
    <?php
}

/**
 * This function takes a capability which will be used to determine whether or not a page is included in the menu.
 * 
 * add_menu_page( string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '', string $icon_url = '', int $position = null )
 * https://developer.wordpress.org/reference/functions/add_menu_page/
 */
function wc_conai_options_page() {
    // add top level menu page
    add_menu_page(
        'WooCommerce Conai - Contributo Consorzio Nazionale Imballaggi',
        'WooCommerce Conai',
        'manage_woocommerce', // shop managers - https://docs.woocommerce.com/document/roles-capabilities/ 
        'wc_conai',
        'wc_conai_options_page_html'
    );
}
add_action( 'admin_menu', 'wc_conai_options_page' );
 
function wc_conai_options_page_html() {
    if ( ! current_user_can( 'manage_woocommerce' ) ) {
        return;
    }

    if ( isset( $_GET['settings-updated'] ) ) {

        $options = get_option( 'wc_conai_options' );
        $wc_conai_json = json_decode($options['wc_conai_json']);

        if($wc_conai_json === null) {
            // add_settings_error( string $setting, string $code, string $message, string $type = 'error' )
            add_settings_error( 'wc_conai_messages', 'wc_conai_message', __( 'Json errato', 'wc_conai' ), 'error' );
        } else {
            add_settings_error( 'wc_conai_messages', 'wc_conai_message', __( 'Settings Saved', 'wc_conai' ), 'updated' );
        }
    }

    settings_errors( 'wc_conai_messages' );
    ?> 
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="options.php" method="post">
            <?php
                settings_fields( 'wc_conai' );
                do_settings_sections( 'wc_conai' );
                submit_button( 'Save Settings' );
            ?>
        </form>
    </div>
    <?php
}
