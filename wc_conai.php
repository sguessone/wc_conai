<?php

/**
 * Plugin Name:       WooCommerce Contributo Ambientale Conai - Consorzio Nazionale Imballaggi
 * Plugin URI:        https://github.com/riccardodicurti/wc_conai
 * Description:       Plugin in fase di sviluppo per l'aggiunta del calcolo relativo al contributo conai in fase di checkout. 
 * Version:           0.1
 * Author:            Riccardo Di Curti
 * Author URI:        https://riccardodicurti.it/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wc_conai
 * Domain Path:       /languages
 */


// Include file if function does not exist
if (! function_exists('is_plugin_active') ) {
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {

    if ( is_admin() ) {
        require __DIR__ . '/includes/settings.php';
    }
    
    add_action('woocommerce_product_options_general_product_data', 'wc_conai_product_custom_fields');
    add_action('woocommerce_process_product_meta', 'woocommerce_product_custom_fields_save');
    add_filter( 'woocommerce_available_variation', 'custom_load_variation_settings_products_fields' );
    add_action( 'woocommerce_cart_calculate_fees','wc_conai_weight_add_cart_fee' );
} 

function wc_conai_product_custom_fields() {

    $options = get_option( 'wc_conai_options' );
    $wc_conai_json = json_decode($options['wc_conai_json'], true);

    $woocommerce_wp_select_options = array( 
            '0'       => __('Non soggetto a Conai', 'wc_conai' )
    ); 

    foreach( $wc_conai_json as $wc_conai_json_option ) {
        $woocommerce_wp_select_options[ $wc_conai_json_option['id'] ] = __('Contributo conai ' . $wc_conai_json_option['name'] . ' ' .  $wc_conai_json_option['price']  . '' . $wc_conai_json_option['unit'], 'wc_conai');
    }
    
    echo '<div class="product_custom_field">';
    
    woocommerce_wp_select(
        array(
            'id'      => '_conai',
            'label'       => __( 'Conai', 'woocommerce' ),
            /* 'description' => __( 'Il Contributo Ambientale CONAI rappresenta la forma di finanziamento attraverso la quale CONAI ripartisce tra produttori e utilizzatori il costo per i maggiori oneri della raccolta differenziata, per il riciclaggio e per il recupero dei rifiuti di imballaggi.', 'woocommerce' ), */
            'options' =>  $woocommerce_wp_select_options
        )
    );
    
    echo '</div>';
}

function woocommerce_product_custom_fields_save($post_id) {
    // WooCommerce custom dropdown Select
    $woocommerce_custom_product_select = $_POST['_conai'];
    if (!empty($woocommerce_custom_product_select)) {
        update_post_meta($post_id, '_conai', esc_attr($woocommerce_custom_product_select));       
    }
}
   
// Custom Product  Variation Settings
function custom_load_variation_settings_products_fields( $variations ) {
    // duplicate the line for each field
    $variations['_conai'] = get_post_meta( $variations[ 'variation_id' ], '_conai', true );
    return $variations;
}
    


function wc_conai_weight_add_cart_fee() {

    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
        return;
    }

    $options = get_option( 'wc_conai_options' );
    $wc_conai_json = json_decode($options['wc_conai_json'], true);

    $conai_counter_array = array(); 

    foreach( $wc_conai_json as $wc_conai_json_option ) {
        $conai_counter_array[$wc_conai_json_option['id']] = array( __('Contributo conai ' . $wc_conai_json_option['name'] . ' ' .  $wc_conai_json_option['price']  . '' . $wc_conai_json_option['unit'], 'wc_conai'), $wc_conai_json_option['price'], 0);
    }
      
    foreach( WC()->cart->get_cart() as $cart_item ){

        if ( $cart_item['data'] instanceof WC_Product_Variation ) {
            $parent_id = $cart_item['data']->parent->id;
        } else {
            $parent_id = $cart_item['data']->get_id();
        }
      
        $product_conai_class = get_post_meta( $parent_id, '_conai', true );  
        $product_weight = $cart_item['data']->get_weight();

        if ( $product_conai_class ) {
            $conai_counter_array[$product_conai_class][2] += $product_weight * ( $conai_counter_array[$product_conai_class][1] / 1000 ) * $cart_item['quantity'];
        }
    }
      
    foreach( $conai_counter_array as $conai_item ){

        if ($conai_item[2]) {
            $conai_item[2] = floor($conai_item[2] * 100 * 1.22) / 100;
            WC()->cart->add_fee( $conai_item[0], $conai_item[2], false );
        }
    }
}