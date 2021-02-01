<?php

/**

 * The template for displaying trip-content in single trip

 * 

 * This template can be overridden by copying it to yourtheme/wp-travel-engine/content-single-trip.php.

 * 

 * @package Wp_Travel_Engine

 * @subpackage Wp_Travel_Engine/includes/templates

 * @since @release-version //TODO: change after travel muni is live

*/



if ( ! defined( 'ABSPATH' ) ) {

    exit; // Exit if accessed directly

}



global $post;

$config               = get_post_meta($post->ID, 'wp_travel_engine_setting', true);
$token_cliente = $config['token_cliente'];
$trip_type = $config['trip_type'];

if ($trip_type == 1) {
    $get_token = $_GET['ac'];

    if (empty($get_token) || $get_token !== $token_cliente) {
        header("Location: /");
    }else{
        do_action( 'wte_before_single_trip' );

        ?>

        <main class="site-main">

            <article id="post-<?php the_ID();?>" <?php post_class('trip-post'); ?>>

                <?php

                    /**

                     * wte_single_trip_content hook.

                     *

                     * @hooked display_single_trip_title - 5 

                     * @hooked display_single_trip_gallery - 10

                     * @hooked display_single_trip_content - 15

                     * @hooked display_single_trip_tabs_nav - 20

                     * @hooked display_single_trip_tabs_content - 25

                     */

                    do_action( 'wte_single_trip_content' );



                    /**

                     * wte_single_trip_footer hook.

                     *

                     * @hooked display_single_trip_footer - 5

                     */

                    do_action( 'wte_single_trip_footer' );



                    /**

                     * display_wte_rich_snippet hook.

                     *

                     * @hooked wp_travel_engine_json_ld

                     */

                    do_action( 'display_wte_rich_snippet' );

                ?>

            </article>

            <!-- .article -->

        </main>

        <!-- ./main -->



        <script

          src="https://code.jquery.com/jquery-3.2.1.min.js"

          integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="

          crossorigin="anonymous"></script>

        <script src="https://wp02.montenegroev.com.br/wp-content/plugins/wp-travel-engine/admin/js/jquery.mask.js"></script>

        <script type="text/javascript">

            jQuery( document ).ready(function() {

                jQuery('#enquiry_contact').mask('(00) 00000-0000');

            });

        </script>



        <?php

        do_action( 'wte_after_single_trip' ); 

        /* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
    }  
}else{ 

    do_action( 'wte_before_single_trip' );

    ?>

    <main class="site-main">

        <article id="post-<?php the_ID();?>" <?php post_class('trip-post'); ?>>

            <?php

                /**

                 * wte_single_trip_content hook.

                 *

                 * @hooked display_single_trip_title - 5 

                 * @hooked display_single_trip_gallery - 10

                 * @hooked display_single_trip_content - 15

                 * @hooked display_single_trip_tabs_nav - 20

                 * @hooked display_single_trip_tabs_content - 25

                 */

                do_action( 'wte_single_trip_content' );



                /**

                 * wte_single_trip_footer hook.

                 *

                 * @hooked display_single_trip_footer - 5

                 */

                do_action( 'wte_single_trip_footer' );



                /**

                 * display_wte_rich_snippet hook.

                 *

                 * @hooked wp_travel_engine_json_ld

                 */

                do_action( 'display_wte_rich_snippet' );

            ?>

        </article>

        <!-- .article -->

    </main>

    <!-- ./main -->



    <script

      src="https://code.jquery.com/jquery-3.2.1.min.js"

      integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="

      crossorigin="anonymous"></script>

    <script src="https://wp02.montenegroev.com.br/wp-content/plugins/wp-travel-engine/admin/js/jquery.mask.js"></script>

    <script type="text/javascript">

        jQuery( document ).ready(function() {

            jQuery('#enquiry_contact').mask('(00) 00000-0000');

        });

    </script>



    <?php

    do_action( 'wte_after_single_trip' ); 

    /* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
}
