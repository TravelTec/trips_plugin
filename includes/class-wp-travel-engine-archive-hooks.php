<?php

/**

 *

 * This class defines all hooks for archive page of the trip.

 *

 * @since      1.0.0

 * @package    Wp_Travel_Engine

 * @subpackage Wp_Travel_Engine/includes

 * @author     WP Travel Engine <https://wptravelengine.com/>

 */

/**

* 

*/

class Wp_Travel_Engine_Archive_Hooks

{

	function __construct()

	{

		add_action( 'wp_travel_engine_trip_archive_outer_wrapper', array( $this, 'wp_travel_engine_trip_archive_wrapper' ) );

		add_action( 'wp_travel_engine_trip_archive_wrap', array( $this, 'wp_travel_engine_trip_archive_wrap' ) );

		add_action( 'wp_travel_engine_trip_archive_outer_wrapper_close', array( $this, 'wp_travel_engine_trip_archive_outer_wrapper_close' ) );

		add_action( 'wp_travel_engine_header_filters', array( $this, 'wp_travel_engine_header_filters_template' ) );

		add_action( 'wp_travel_engine_archive_header_block', array( $this, 'wp_travel_engine_archive_header_block' ) );

		add_action( 'wp_travel_engine_featured_trips_sticky', array( $this, 'wte_featured_trips_sticky' ), 50, 1 );

	}



	/**

	 * Featured Trips sticky section for WP Travel Engine Archives.

	 *

	 * @return void

	 */

	function wte_featured_trips_sticky( $view_mode ) {

		$trips_array = wte_get_featured_trips_array();

		if( empty( $trips_array ) ) return;



		$args = array(

			'post_type' => 'trip',

			'post__in'  => $trips_array 

		);



		$featured_query = new WP_Query( $args );



		while( $featured_query->have_posts() ) : $featured_query->the_post();

			$details = wte_get_trip_details( get_the_ID() );

			wte_get_template( 'content-'.$view_mode.'.php', $details );

		endwhile;



	}

	/**

	 * Header filter section for WP Travel Engine Archives.

	 *

	 * @return void

	 */

	function wp_travel_engine_header_filters_template() {

		$view_mode = wp_travel_engine_get_archive_view_mode();

		$orderby   = isset( $_GET['wte_orderby'] ) && ! empty( $_GET['wte_orderby'] ) ? $_GET['wte_orderby'] : '';

		?>

			<div class="wp-travel-toolbar clearfix">

			<div class="wte-filter-foundposts">

				<h2 class="searchFoundPosts"></h2>

			</div>

				<div class="wp-travel-engine-toolbar wte-view-modes">

					<?php

						$current_url = '//' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

					?>

					<span><?php esc_html_e( 'Ver como:', 'wp-travel-engine' ); ?></span>

					<ul class="wte-view-mode-selection-lists">

						<li class="wte-view-mode-selection <?php echo ( 'grid' === $view_mode ) ? 'active' : ''; ?>" data-mode="grid" >

							<a href="<?php echo esc_url( add_query_arg( 'view_mode', 'grid', $current_url ) ); ?>">

								<i class="fas fa-th"></i>

							</a>

						</li>

						<li class="wte-view-mode-selection <?php echo ( 'list' === $view_mode ) ? 'active' : ''; ?>" data-mode="list" >

							<a href="<?php echo esc_url( add_query_arg( 'view_mode', 'list', $current_url ) ); ?>">

								<i class="fas fa-list"></i>

							</a>

						</li>

					</ul>

				</div>

				<div class="wp-travel-engine-toolbar wte-filterby-dropdown">

					<?php

						$wte_sorting_options = apply_filters( 'wp_travel_engine_archive_header_sorting_options', array(

							''           => __( 'Listagem padrão', 'wp-travel-engine' ),

							'latest'     => __( 'Últimos roteiros', 'wp-travel-engine' ),

							'rating'     => __( 'Mais vistos', 'wp-travel-engine' ),

							'price'      => __( 'Preço: do menor para o maior', 'wp-travel-engine' ),

							'price-desc' => __( 'Preço: do maior para o menor', 'wp-travel-engine' ),

							'days'       => __( 'Dias: do menor para o maior', 'wp-travel-engine' ),

							'days-desc'  => __( 'Dias: do maior para o menor', 'wp-travel-engine' ),

							'name'       => __( 'Nome - Ordem crescente', 'wp-travel-engine' ),

							'name-desc'  => __( 'Nome - Ordem descrecente', 'wp-travel-engine' )

						) );

					?>

					<form class="wte-ordering" method="get">

						<span><?php esc_html_e( 'Listar por:', 'wp-travel-engine' ); ?></span>

						<select name="wte_orderby" class="orderby" aria-label="<?php esc_attr_e( 'Ordenação', 'wp-travel-engine' ); ?>">

							<?php foreach ( $wte_sorting_options as $id => $name ) : ?>

							<option value="<?php echo esc_attr( $id ); ?>" <?php selected( $orderby, $id ); ?>><?php echo esc_html( $name ); ?></option>

							<?php endforeach; ?>

						</select>

						<input type="hidden" name="paged" value="1" />

							<?php wte_query_string_form_fields( null, array( 'wte_orderby', 'submit', 'paged' ) ); ?>

					</form>

				</div>

			</div>

		<?php 

	}



	/**

	 * Hook for the header block ( contains title and description )

	 *

	 * @return void

	 */

	function wp_travel_engine_archive_header_block() {

		$page_header = apply_filters( 'wte_trip_archive_description_page_header', true ); 

		?>

		<div class="page-title-section" style="display: block !important;">		
			<div class="overlay">
				<div class="container">
					<div class="row">
						<div class="col-md-6">
							<div class="page-title">
								<h1><?=single_term_title( '', false )?></h1>					</div>
						</div>
						<div class="col-md-6">
							<ul class="page-breadcrumb">
								<li><a href="https://wp02.montenegroev.com.br">Início</a> &nbsp; / &nbsp;</li><li class="active"><?=single_term_title( '', false )?></li>					</ul>
						</div>
					</div>
				</div>	
			</div>
		</div> 

		<?php
 

	}



	/**

     * Main wrap of the archive.

     *

     * @since    1.0.0

     */

	function wp_travel_engine_trip_archive_wrapper()

	{ ?>

		<div id="wte-crumbs">

            <?php

				do_action('wp_travel_engine_breadcrumb_holder');

            ?>

		</div>
		<?php 

				$header_block = apply_filters( 'wp_travel_engine_archive_header_block_display', true );

				if ( $header_block ) {

					do_action( 'wp_travel_engine_archive_header_block' );

				}

			?>

		<div id="wp-travel-trip-wrapper" class="trip-content-area" itemscope itemtype="http://schema.org/ItemList">

			

            <div class="wp-travel-inner-wrapper">

	<?php

	}



	/**

     * Inner wrap of the archive.

     *

     * @since    1.0.0

     */

	function wp_travel_engine_trip_archive_wrap()

	{ ?>

		<div class="wp-travel-engine-archive-outer-wrap">			

			<?php

				/**

				 * wp_travel_engine_archive_sidebar hook

				 * 

				 * @hooked wte_advanced_search_archive_sidebar - Trip Search addon

				 */

				do_action( 'wp_travel_engine_archive_sidebar' );

			?>

			<div class="">

				<?php 

					/**

					 * Hook - wp_travel_engine_header_filters 

					 * Hook for the new archive filters on trip archive page.

					 * @hooked - wp_travel_engine_header_filters_template.

					 */

					do_action( 'wp_travel_engine_header_filters' );

				?>

				<div class="wte-category-outer-wrap">

					<?php

						$j = 1;

						$view_mode = wp_travel_engine_get_archive_view_mode();

						if ( 'grid' === $view_mode ) {

							$view_class = class_exists( 'Wte_Advanced_Search' ) ? 'col-2 category-grid' : 'col-3 category-grid';

						} else {

							$view_class = 'category-list';

						}

						echo '<div class="category-main-wrap '. esc_attr( $view_class ) .'">';						

							/**

							 * wp_travel_engine_featured_trips_sticky hook

							 * Hook for the featured trips sticky section

							 * @hooked wte_featured_trips_sticky

							 */

							do_action( 'wp_travel_engine_featured_trips_sticky', $view_mode ); 

							while( have_posts() ) : the_post();

								$details = wte_get_trip_details( get_the_ID() );

								$details['j'] = $j;  

								wte_get_template( 'content-'.$view_mode.'.php', $details );

								$j++;

							endwhile;

						echo '</div>';

					?>

				</div>

				<div id="loader" style="display: none">

					<div class="table">

						<div class="table-grid">

							<div class="table-cell">

								<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>

							</div>

						</div>

					</div>

				</div>

			</div>

		</div>

        <div class="trip-pagination">

			<?php

			the_posts_pagination( array(

				'prev_text'          => __( 'Anterior', 'wp-travel-engine' ),

				'next_text'          => __( 'Próximo', 'wp-travel-engine' ),

				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Página', 'wp-travel-engine' ) . ' </span>',

			) );

			?>

        </div>

    <?php

    }

	/**

     * Oter wrap of the archive.

     *

     * @since    1.0.0

     */

	function wp_travel_engine_trip_archive_outer_wrapper_close()

	{ ?>



		</div><!-- wp-travel-inner-wrapper -->

		</div><!-- .wp-travel-trip-wrapper -->

	<?php

	}

}

new Wp_Travel_Engine_Archive_Hooks();

