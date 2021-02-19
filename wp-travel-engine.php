<?php

/*

Plugin Name: Voucher Tec - Roteiros de viagens

Plugin URI: https://github.com/TravelTec/trips_plugin

GitHub Plugin URI: https://github.com/TravelTec/trips_plugin

Description: Voucher Tec - Roteiros de viagens é um plugin de gerenciamento de viagens. Você pode cadastrar seus roteiros, compartilhar promoções, gerenciar suas reservas e manter contato com os clientes que desejam saber mais sobre seus pacotes. Tudo de forma rápida e intuitiva. 

Version: 1.5.6

Author: Travel Tec

Author URI: https://traveltec.com.br

License: GPLv2

*/



/*

 * Plugin Update Checker

 * 

 * Note: If you're using the Composer autoloader, you don't need to explicitly require the library.

 * @link https://github.com/YahnisElsts/plugin-update-checker

 */

require_once 'plugin-update-checker-4.10/plugin-update-checker.php';





/*

 * Plugin Update Checker Setting

 *

 * @see https://github.com/YahnisElsts/plugin-update-checker for more details.

 */

function my_plugin_update_checker_setting() {

    if ( ! is_admin() || ! class_exists( 'Puc_v4_Factory' ) ) {

        return;

    }



    $myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(

        'https://github.com/TravelTec/trips_plugin',

        __FILE__,

        'trips_plugin'

    );

    

    // (Opcional) If you're using a private repository, specify the access token like this:

    $myUpdateChecker->setAuthentication('your-token-here');



    // (Opcional) Set the branch that contains the stable release.

    $myUpdateChecker->setBranch('main');

}



add_action( 'admin_init', 'my_plugin_update_checker_setting' );



 // Freemius

if ( ! function_exists( 'wte_fs' ) ) {

    // Create a helper function for easy SDK access.

    function wte_fs() {

        global $wte_fs;



        if ( ! isset( $wte_fs ) ) {

            // Include Freemius SDK.

            require_once dirname(__FILE__) . '/includes/lib/freemius/start.php';



            $wp_travel_engine_first_time_activation_flag = get_option('wp_travel_engine_first_time_activation_flag',false);



            if( $wp_travel_engine_first_time_activation_flag == false ){

                $slug = "wp-travel-engine-onboard";

            }else{

                $slug = "class-wp-travel-engine-admin.php";

            }

            $arg_array =  array(

                'id'                 => '5392',

                'slug'               => 'wp-travel-engine',

                'type'               => 'plugin',

                'public_key'         => 'pk_d9913f744dc4867caeec5b60fc76d',

                'is_premium'         => false,

                'has_addons'         => false,

                'has_paid_plans'     => false,

                'menu'               => array(

                    'slug'           => $slug, // Default: class-wp-travel-engine-admin.php

                    'account'        => false,

                    'contact'        => false,

                    'support'        => false,

                    'parent'         => array(

                        'slug'    => 'edit.php?post_type=booking',

                    ),

                ),

            );

            $wte_fs = fs_dynamic_init($arg_array);

        }

        return $wte_fs;

    }



    // Init Freemius.

    wte_fs();

    // Signal that SDK was initiated.

    do_action( 'wte_fs_loaded' );

}



// If this file is called directly, abort.

if ( ! defined( 'WPINC' ) ) {

    die;

}



// Not like register_uninstall_hook(), you do NOT have to use a static function.

wte_fs()->add_action('after_uninstall', 'wte_fs_uninstall_cleanup');

function wte_fs_uninstall_cleanup(){}



$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings',true );

$payment_debug_mode        = isset( $wp_travel_engine_settings['payment_debug'] ) && $wp_travel_engine_settings['payment_debug'] === 'yes' ? true : false;



define( 'WP_TRAVEL_ENGINE_PAYMENT_DEBUG', $payment_debug_mode );

define( 'WP_TRAVEL_ENGINE_FILE_PATH', __FILE__ );

define( 'WP_TRAVEL_ENGINE_BASE_PATH', dirname( __FILE__ ) );

define( 'WP_TRAVEL_ENGINE_ABSPATH', dirname( __FILE__ ) . '/' );

define( 'WP_TRAVEL_ENGINE_IMG_PATH', WP_TRAVEL_ENGINE_BASE_PATH.'/admin/css/icons' );

define( 'WP_TRAVEL_ENGINE_TEMPLATE_PATH', WP_TRAVEL_ENGINE_BASE_PATH.'/includes/templates' );

define( 'WP_TRAVEL_ENGINE_FILE_URL', plugins_url( '', __FILE__ ) );

define( 'WP_TRAVEL_ENGINE_VERSION', '4.1.9' );

define( 'WP_TRAVEL_ENGINE_POST_TYPE', 'trip' );

define( 'WP_TRAVEL_ENGINE_URL', rtrim( plugin_dir_url( __FILE__ ), '/' ) );

define( 'WP_TRAVEL_ENGINE_IMG_URL', rtrim( plugin_dir_url( __FILE__ ), '/' ) );

// this is the URL our updater / license checker pings. This should be the URL of the site with EDD installed

define( 'WP_TRAVEL_ENGINE_STORE_URL', 'https://wptravelengine.com/' ); // IMPORTANT: change the name of this constant to something unique to prevent conflicts with other plugins using this system

define( 'WP_TRAVEL_ENGINE_PLUGIN_LICENSE_PAGE', 'wp_travel_engine_license_page' );



/**

 * Load plugin updater file

 */

require plugin_dir_path( __FILE__ ) . 'updater.php';



/**

 * The code that runs during plugin activation.

 * This action is documented in includes/class-wp-travel-engine-activator.php

 */

function activate_wp_travel_engine() {

    require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-travel-engine-activator.php';

    Wp_Travel_Engine_Activator::activate();

}



/**

 * The code that runs during plugin deactivation.

 * This action is documented in includes/class-wp-travel-engine-deactivator.php

 */

function deactivate_wp_travel_engine() {

    require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-travel-engine-deactivator.php';

    Wp_Travel_Engine_Deactivator::deactivate();

}



// register_activation_hook( __FILE__, 'wte_activate' );

register_activation_hook( __FILE__, 'activate_wp_travel_engine' );

register_deactivation_hook( __FILE__, 'deactivate_wp_travel_engine' );



/**

 * The core plugin class that is used to define internationalization,

 * admin-specific hooks, and public-facing site hooks.

 */

require plugin_dir_path( __FILE__ ) . 'includes/class-wp-travel-engine.php';



/**

 * Begins execution of the plugin.

 *

 * Since everything within the plugin is registered via hooks,

 * then kicking off the plugin from this point in the file does

 * not affect the page life cycle.

 *

 * @since    1.0.0

 */

function run_Wp_Travel_Engine() {



    $plugin = new Wp_Travel_Engine();

    $plugin->run();



}

run_Wp_Travel_Engine();

