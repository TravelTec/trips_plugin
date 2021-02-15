<?php

/**

* The template for displaying trips according to activities .

*

* @package Wp_Travel_Engine

* @subpackage Wp_Travel_Engine/includes/templates

* @since 1.0.0

*/

get_header(); ?>

<div id="wte-crumbs">

   <?php

        $obj = new Wp_Travel_Engine_Functions();

        do_action('wp_travel_engine_breadcrumb_holder');

        ?>

</div>

<div class="page-title-section">        
                    <div class="overlay">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="page-title" data-id="<?php echo $taxonomyName;?>">
                                        <h1><?php the_title(); ?></h1>                    </div>
                                </div>
                                <div class="col-md-6">
                                    <ul class="page-breadcrumb">
                                        <li><a href="http://wp02.montenegroev.com.br">Início</a> &nbsp; / &nbsp;</li><li class="active"><?php the_title(); ?></li>                    </ul>
                                </div>
                            </div>
                        </div>  
                    </div>
                </div> 

<div class="page-builder">

    <div class="container-fluid">

        <div class="row justify-content-center">

         <!-- Blog Area -->

            <div class="col-md-12">
<div id="wp-travel-trip-wrapper" class="trip-content-area" itemscope itemtype="http://schema.org/LocalBusiness" >

    <div class="wp-travel-inner-wrapper">

        <div class="wp-travel-engine-archive-outer-wrap">

            <?php

            global $post;

            $termID = get_queried_object()->term_id; // Parent A ID 

            $post_conteudo = str_replace("<!-- wp:shortcode -->", "", str_replace("<!-- /wp:shortcode -->", "", get_queried_object()->post_content));
            $post_name = get_queried_object()->post_name; 
 
            if (strpos($post_conteudo, "DESTINATIONS")) {
                $taxonomyName = "destination";
            }else if (strpos($post_conteudo, "ACTIVITIES")) {
                $taxonomyName = "activities";
            }else if (strpos($post_conteudo, "TYPES")) {
                $taxonomyName = "trip_types";
            } 
 

            $order = apply_filters('wpte_activities_terms_order','ASC');

            $orderby = apply_filters('wpte_activities_terms_order_by','date');

            $termchildren = get_terms($taxonomyName, array('orderby' => $orderby, 'order' => $order));

            

            if($termchildren) {

                ?>

                

                <div class="activities-holder">

                    <?php 

                    foreach ($termchildren as $child) {

                        $term = get_term_by( 'id', $child->term_id, $taxonomyName ); 

                        $term_link = get_term_link( $term );

                        $child_term_description = term_description( $term, $taxonomyName );

                        ?> 

                            <div class="item">

                                <address itemprop="address" style="display: none;"><?php the_title(); ?></address>

                                <div class="img-holder">

                                        <?php 

                                        $image_id = get_term_meta ( $child->term_id, 'category-image-id', true );

                                        if(isset($image_id) && $image_id!='' )

                                        {

                                            $activities_thumb_size = apply_filters('wp_travel_engine_activities_img_size', 'activities-thumb-size');

                                            echo wp_get_attachment_image ( $image_id, $activities_thumb_size, false, array( 'itemprop' => 'image' ) );

                                        }

                                        else{

                                            echo '<img itemprop="image" src="'.esc_url(  WP_TRAVEL_ENGINE_IMG_URL . '/public/css/images/activity-trip-type.jpg' ).'">';

                                        }

                                        ?>

                                    <h2 class="title-holder"><?php echo esc_attr( $term->name );?></h2>

                                    <div class="text-holder">

                                        <div class="text">

                                        <h3 class="title" itemprop="name"><?php echo esc_attr( $term->name );?></h3>

                                        <p><?php echo term_description( $child->term_id, 'activities' ); ?></p>

                                        </div>

                                        <a class="btn-more" href="<?php echo esc_url( $term_link );?>">Veja mais &rarr;</a>

                                    </div>

                                </div>

                            </div>

                        <?php

                    }

                    ?>

                </div>

            <?php     

            }

            else{

                ?>

                <div class="page-header">

                    <h1 class="page-title" data-id="<?php echo $taxonomyName;?>"><?php the_title(); ?></h1>

                    <div class="page-feat-image">

                      <?php

                      the_post_thumbnail();

                      ?> 

                    </div>

                    <div class="page-content">

                      <?php

                      $content = apply_filters('the_content', $post->post_content); 

                      echo $content; ?>

                    </div>

                </div>

                <?php

              } 

            ?>

        </div>

    </div>

</div>
</div>
</div>
</div>
</div>

<?php get_footer();