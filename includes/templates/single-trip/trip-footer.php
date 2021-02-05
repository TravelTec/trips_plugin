<?php

/**

 * Single Trip Footer

 * 

 * This template can be overridden by copying it to yourtheme/wp-travel-engine/single-trip/trip-footer.php.

 * 

 * @package Wp_Travel_Engine

 * @subpackage Wp_Travel_Engine/includes/templates

 * @since @release-version //TODO: change after travel muni is live

*/



if ( ! defined( 'ABSPATH' ) ) {

    exit; // Exit if accessed directly

}

?>

 

<script src="/wp-content/themes/templatewp/js/jquery.mask.js"></script>
<script type="text/javascript">
	jQuery("#enquiry_contact").mask("(00) 00000-0000");
</script>

<?php

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */

