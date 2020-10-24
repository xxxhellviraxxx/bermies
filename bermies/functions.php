<?php

add_action('wp_enqueue_scripts', 'mpcth_child_enqueue_scripts', 100);
function mpcth_child_enqueue_scripts() {
	wp_enqueue_style( 'mpc-styles-child', get_stylesheet_directory_uri() . '/style.css' );
}



/* Theme setup */
add_action( 'after_setup_theme', 'wpt_setup' );
if ( ! function_exists( 'wpt_setup' ) ):
    function wpt_setup() {  
        register_nav_menu( 'main_menu', __( 'Main Navigation Menu', 'wptuts' ) );
    } endif;

add_action( 'wp_enqueue_scripts', 'wpt_register_css' );


/*function agregar_variation_swatches() {
    if ( ! is_product() ) {
        return false;
    }
	
	global $product;
	$type = $product->get_type();

	if ($type == 'bundle') {

	}
}

add_action( 'wp_footer', 'agregar_variation_swatches' );*/

add_filter( 'rwmb_meta_boxes', 'prefix_register_taxonomy_meta_boxes' );
function prefix_register_taxonomy_meta_boxes( $meta_boxes ){
    $meta_boxes[] = array(
        'title'      => 'Slider ',
        'taxonomies' => 'product_cat',
        'fields' => array(
            array(
                'name' => 'Shortcode Slider',
                'id'   => 'slider_tax',
                'type' => 'text',
            ),
        ),
    );
    return $meta_boxes;
}

add_filter( 'woocommerce_helper_suppress_admin_notices', '__return_true' );


/**
 * Size Filter 
 */
add_action('woocommerce_archive_description', 'category_filters');
function category_filters() {
	?>
	<style>
		/*body:not(.logged-in) div.wrapFilterEcom{display:none}*/
		div.wrapFilterEcom{text-align:center}
		#search-filter-form-25599 h4 {font-size: 11px; padding-bottom: 5px;}
		#search-filter-form-25599 ul {padding:0}
		#search-filter-form-25599>ul>li{display:none;}
		#search-filter-form-25599 ul input{display:none;}
		#search-filter-form-25599>ul>li ul li {display:inline-block;}
		li.sf-level-0{
			border: 1px solid #BBB;
			margin: 0 3px !important;
			padding: 0 !important;
		}
		li.sf-level-0:hover{border: 1px solid #777;}
		li.sf-level-0 label{
			padding:0 !important;
			font-weight:bold;
			font-size:18px;
			color:#00cbff;
			width: 28px;
			height: 28px;
			line-height: 28px;
			cursor:pointer;
		}
		li.sf-option-active{border: 2px solid #06B6ED !important;}
		li.sf-option-active label{
			width: 27px;
			height: 26px;
			line-height: 26px;
		}
		/* Radio - all */
		li.sf-item-0 {
			display:none !important;
		}
		li.sf-item-0 label{
			font-weight: 500 !important;
		}
        
        /* fx woocommerce margins */
        .woocommerce {
            top: 0px !important;
            position: relative;
            bottom: 0px !important;
        }
	</style>
	<?php
	if( is_product_category() ) :
		echo '<div class="wrapFilterEcom">';
	
	// ACÁ SE ASIGNAN LOS TALLES EN LAS PAGINAS DE LOS PRODUCT CATEGORIES
	 
//ACÁ VAN LOS SLUGS PARA ASIGNAR LOS TALLES QUE VAN  DEL S al XL
		echo '<style>';
		if( is_product_category(['men-classics', 'men-originals', 'men-shirts', 'men-shorts', 'linenshirts','men-bermies-x', 'men-poloshirts', 'performance-shirts', 'poloshirt','rayon-shirts', 'mentshirts' ]) ) :
			echo '#search-filter-form-25599 ul li.sf-field-taxonomy-pa_sizes{display:block;}';
//ACÁ VAN LOS SLUGS PARA ASIGNAR LOS TALLES QUE VAN  DEL XS al L

		elseif( is_product_category(['women-classic', 'women-cheeky', 'women', 'one-piece', 'originals', 'classics', 'womencottonstretchshorts']) ) :
			//echo '#search-filter-form-25599 ul li.sf-field-taxonomy-pa_top,
			#search-filter-form-25599 ul .sf-field-taxonomy-pa_bottom{display:block;}';
			echo '#search-filter-form-25599 ul li.sf-field-taxonomy-pa_women-sizes{display:block;}';
	
//ACÁ VAN LOS SLUGS PARA ASIGNAR LOS TALLES QUE VAN DEL 28 al 40

		elseif( is_product_category(['boys', 'rash-guards-short-sleeve', 'boys-swimtrunks', 'rashguards']) ) :
			echo '#search-filter-form-25599 ul li.sf-field-taxonomy-pa_boy-sizes{display:block;}';
		
		elseif( is_product_category(['linen-shorts']) ) :
			echo '#search-filter-form-25599 ul li.sf-field-taxonomy-pa_linen-sizes{display:block;}';
		endif;
		echo '</style>';
		
		echo '<small>FILTER BY SIZE<small>';
		echo do_shortcode('[searchandfilter slug="all-filters"]');
		echo '</div>';
	
	endif;
}


add_filter('the_content', 'lista_crons');
function lista_crons($cont){
	if(is_page(29224)) {
		$cron_jobs = get_option( 'cron' );
		return '<pre>'.var_dump($cron_jobs).'</pre>';
	}
	return $cont;
}


add_filter('woocommerce_mxmerchant_icon', 'bermies_merchantx_icon');

function bermies_merchantx_icon($icon){
	return content_url('/uploads/2018/10/Visa-American-Master-Discover.png');
}

// rename the coupon field on the cart page
function my_text_strings( $translated_text, $text, $domain ) {
    switch ( $translated_text ) {
        case 'Coupon code' :
            $translated_text = __( 'Coupon code / Gift Card', 'woocommerce' );
            break;
    }
    return $translated_text;
}
add_filter( 'gettext', 'my_text_strings', 20, 3 );

//Permitir subir archivos que no sean imagenes
add_filter( 'wp_check_filetype_and_ext', 'ecomerciar_disable_real_mime_check', 10, 4 );
function ecomerciar_disable_real_mime_check( $data, $file, $filename, $mimes ) {
	$wp_filetype = wp_check_filetype( $filename, $mimes );
	return array( 'ext' => $wp_filetype['ext'], 'type' => $wp_filetype['type'], 'proper_filename' => $wp_filetype['proper_filename'] );
}

add_filter( 'woocommerce_product_single_add_to_cart_text', 'bermies_woo_custom_cart_button_text' );
function bermies_woo_custom_cart_button_text()
{
	global $product;
	if ($product || is_a($product, 'WC_Product')){
    	return 'ORDER';
		/*if ($product->get_stock_quantity() === 0 && $product->backorders_allowed()) {
			return 'PRE-ORDER';
		}*/
	}
}

function webp_upload_mimes( $existing_mimes ) {
	// add webp to the list of mime types
	$existing_mimes['webp'] = 'image/webp';

	// return the array back to the function with our added mime type
	return $existing_mimes;
}
add_filter( 'mime_types', 'webp_upload_mimes' );

// plugins_url('assets/images/icon.png', __FILE__)

add_shortcode('bermies_coupon_usage', 'bermies_coupon_usage_func');
function bermies_coupon_usage_func($atts)
{
    global $wpdb;
    $posts_table = $wpdb->prefix . 'posts';
    $postmeta_table = $wpdb->prefix . 'postmeta';
    ob_start();
    $query = "SELECT p.`ID`, 
        p.`post_title`   AS coupon_code, 
        Max(CASE WHEN pm.meta_key = 'usage_count'    AND  p.`ID` = pm.`post_id` THEN pm.`meta_value` END) AS total_usaged
    FROM $posts_table AS p 
        INNER JOIN $postmeta_table AS pm ON  p.`ID` = pm.`post_id` 
    WHERE  p.`post_type` = 'shop_coupon' 
        AND p.`post_status` = 'publish' 
        AND p.`ID` != '36139'
    GROUP  BY p.`ID` 
    ORDER  BY `total_usaged` DESC;";
    $dbcoupons = $wpdb->get_results($query, ARRAY_A);
    if (empty($dbcoupons)) return ob_get_clean();
    echo '<div id="coupons-ranking-container">';
    echo '<form method="get" id="coupons-ranking-form">';
    echo '<input type="text" name="coupon-code" id="coupon-code">';
    echo '<input type="submit" value="Find my coupon">';
    echo '</form>';
    $coupon_to_find = $coupon_found = false;
    if (isset($_GET['coupon-code']) && !empty($_GET['coupon-code']))
        $coupon_to_find = filter_var($_GET['coupon-code'], FILTER_SANITIZE_STRING);
    $coupon_id = wc_get_coupon_id_by_code($coupon_to_find);
    if (isset($_GET['coupon-code']) && !empty($_GET['coupon-code']) && !$coupon_id)
        echo '<h4 class="error">Your coupon code couldn\'t be found</h4>';
    echo '<table id="coupons-ranking-table">';
    echo '    <tr>';
    echo '        <th>Number</th>';
    echo '        <th>Coupon</th>';
    echo '        <th>Times used</th>';
    echo '    </tr>';
    if ($coupon_to_find) {
        for ($i = 0; $i < 20; $i++) {
            $dbcoupon = $dbcoupons[$i];
            if (!$coupon_found) {
                if (strcasecmp($dbcoupon['coupon_code'], $coupon_to_find) === 0) {
                    echo '<tr class="coupon-found">';
                    echo '    <td>' . ($i + 1) . '</td>';
                    echo '    <td>' . $dbcoupon['coupon_code'] . '</td>';
                    echo '    <td>' . $dbcoupon['total_usaged'] . '</td>';
                    echo '</tr>';
                    $coupon_found = true;
                } else {
                    echo '<tr>';
                    echo '    <td>' . ($i + 1) . '</td>';
                    echo '    <td>' . $dbcoupon['coupon_code'] . '</td>';
                    echo '    <td>' . $dbcoupon['total_usaged'] . '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr>';
                echo '    <td>' . ($i + 1) . '</td>';
                echo '    <td>' . $dbcoupon['coupon_code'] . '</td>';
                echo '    <td>' . $dbcoupon['total_usaged'] . '</td>';
                echo '</tr>';
            }
        }
    } else {
        for ($i = 0; $i < 20; $i++) {
            $dbcoupon = $dbcoupons[$i];
            echo '<tr>';
            echo '    <td>' . ($i + 1) . '</td>';
            echo '    <td>' . $dbcoupon['coupon_code'] . '</td>';
            echo '    <td>' . $dbcoupon['total_usaged'] . '</td>';
            echo '</tr>';
        }
    }

    if (!$coupon_found && $coupon_to_find) {
        for ($i = 19; $i < count($dbcoupons); $i++) {
            $dbcoupon = $dbcoupons[$i];
            if (strcasecmp($dbcoupon['coupon_code'], $coupon_to_find) === 0) {
                echo '<tr class="coupon-found">';
                echo '    <td>' . $i . '</td>';
                echo '    <td>' . $dbcoupon['coupon_code'] . '</td>';
                echo '    <td>' . $dbcoupon['total_usaged'] . '</td>';
                echo '</tr>';
            }
        }
    }
    echo '</table>';
    echo '</div>';
    return ob_get_clean();
}



/**
 * Disable gutenberg for old versions of WooCommerce.
 *
 * @param bool   $is_enabled If editor is enabled.
 * @param string $post_type  Post type.
 * @return bool
 */
function wc_no_gutenberg_for_products( $is_enabled, $post_type ) {
    if ( 'product' === $post_type ) {
        return false;
    }
    return $is_enabled;
}
add_filter( 'use_block_editor_for_post_type', 'wc_no_gutenberg_for_products', 10, 2 );
add_filter( 'wc_product_has_unique_sku', '__return_false' ); 


require_once('wp_bootstrap_navwalker.php');
// Remove the additional information tab
function woo_remove_product_tabs( $tabs ) {
    unset( $tabs['additional_information'] );
    return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );

