<?php
/**
 * The Header base for MPC Themes
 *
 * Displays all of the <head> section and everything up till <div id="mpcth_main">
 *
 * @package WordPress
 * @subpackage MPC Themes
 * @since 1.0
 */


global $post;
global $page_id;
global $paged;
global $mpcth_options;
global $sidebar_position;
global $content_width;

if ( isset( $post ) && ! is_archive() ) {
	$page_id = $post->ID;
} else {
	$page_id = 0;
}

if ( function_exists( 'is_woocommerce' ) && ( is_shop() || is_product_category() || is_product_tag() ) ) {
	$page_id = get_option( 'woocommerce_shop_page_id' );
}

$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : ( ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1 );

$sidebar_position = mpcth_get_sidebar_position();

if ( $sidebar_position == 'none' ) {
	$content_width = 1200;
} else {
	$content_width = 900;
}

$single_page_layout = '';

if ( $page_id ) {
	$single_page_layout = get_field( 'mpc_site_layout', $page_id );
}

if ( $single_page_layout && $single_page_layout !== 'default' ) {
	$mpcth_options['mpcth_boxed_type'] = $single_page_layout;
}

$style = '';
if ( $mpcth_options['mpcth_boxed_type'] != 'fullwidth' && $mpcth_options['mpcth_background_type'] != 'none' ) {
	$bg_type = $mpcth_options['mpcth_background_type'];

	$style = 'style="';
	if ( $bg_type == 'color' ) {
		$style .= 'background-color:' . $mpcth_options['mpcth_bg_color'];
	} elseif ( $bg_type == 'custom_background' ) {
		if ( ! empty( $mpcth_options['mpcth_bg_image'] ) ) {
			$style .= 'background-image:url(' . $mpcth_options['mpcth_bg_image'] . ');' . ( $mpcth_options['mpcth_enable_bg_image_repeat'] ? 'background-repeat:repeat;' : 'background-repeat:no-repeat;background-position:center;background-size:100%;background-size:cover;background-attachment:fixed;' );
		}
	} elseif ( $bg_type == 'pattern_background' ) {
		$style .= 'background-image:url(' . MPC_THEME_URI . '/panel/images/patterns/' . $mpcth_options['mpcth_bg_pattern'] . '.png); background-repeat:repeat;';
	}
	$style .= '"';

	if ( $bg_type != 'color' ) {
		$bg_cover = '<div class="mpcth-background-cover' . ( $bg_type == 'custom_background' ? ' mpcth-image' : '' ) . '" ' . $style . '></div>';
	}
}

$use_advance_colors             = isset( $mpcth_options['mpcth_use_advance_colors'] ) && $mpcth_options['mpcth_use_advance_colors'];
$disable_responsive             = isset( $mpcth_options['mpcth_disable_responsive'] ) && $mpcth_options['mpcth_disable_responsive'];
$disable_menu_indicators        = isset( $mpcth_options['mpcth_disable_menu_indicators'] ) && $mpcth_options['mpcth_disable_menu_indicators'];
$smart_search                   = isset( $mpcth_options['mpcth_enable_smart_search'] ) && $mpcth_options['mpcth_enable_smart_search'];
$simple_menu                    = isset( $mpcth_options['mpcth_enable_simple_menu'] ) && $mpcth_options['mpcth_enable_simple_menu'];
$simple_menu_label              = isset( $mpcth_options['mpcth_enable_simple_menu_label'] ) && $mpcth_options['mpcth_enable_simple_menu_label'];
$disable_mobile_slider_nav      = isset( $mpcth_options['mpcth_disable_mobile_slider_nav'] ) && $mpcth_options['mpcth_disable_mobile_slider_nav'];
$slider_revolution_original_nav = isset( $mpcth_options['mpcth_rev_nav_original'] ) && $mpcth_options['mpcth_rev_nav_original'];
$disable_product_cart           = isset( $mpcth_options['mpcth_disable_product_cart'] ) && $mpcth_options['mpcth_disable_product_cart'];
$accordion_mobile_menu          = isset( $mpcth_options['mpcth_enable_accordion_menu'] ) && $mpcth_options['mpcth_enable_accordion_menu'];
$disable_product_price          = $disable_product_cart && isset( $mpcth_options['mpcth_disable_product_price'] ) && $mpcth_options['mpcth_disable_product_price'];

$enable_full_width_header = isset( $mpcth_options['mpcth_header_full_width'] ) && $mpcth_options['mpcth_header_full_width'];

if ( ! $enable_full_width_header ) {
	$enable_full_width_header = get_field( 'mpc_force_header_full_width', $page_id );
}

$enable_transparent_header = get_field( 'mpc_enable_transparent_header', $page_id );
$force_simple_buttons      = $enable_transparent_header && get_field( 'mpc_force_simple_buttons', $page_id );
$vertical_center_elements  = $enable_transparent_header ? get_field( 'mpc_vertical_center_elements', $page_id ) : false;

$masonry_shop   = false;
$load_more_shop = false;
if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
	if ( ! empty( $_GET['masonry'] ) ) {
		if ( $_GET['masonry'] == 1 ) {
			$masonry_shop   = true;
			$load_more_shop = false;
		} elseif ( $_GET['masonry'] == 2 ) {
			$masonry_shop   = true;
			$load_more_shop = true;
		}
	} else {
		$masonry_shop   = function_exists( 'is_woocommerce' ) && is_woocommerce() && ! is_product() && isset( $mpcth_options['mpcth_enable_masonry_shop'] ) && $mpcth_options['mpcth_enable_masonry_shop'];
		$load_more_shop = $masonry_shop && isset( $mpcth_options['mpcth_enable_shop_load_more'] ) && $mpcth_options['mpcth_enable_shop_load_more'];
		// $dynamic_height = $masonry_shop && isset($mpcth_options['mpcth_enable_shop_dynamic_height']) && $mpcth_options['mpcth_enable_shop_dynamic_height'];
	}
}

$body_classes  = '';
$body_classes .= ( $disable_product_cart ? ' mpcth-disable-add-to-cart ' : '' );
$body_classes .= ( $disable_product_price ? ' mpcth-disable-price ' : '' );
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?> <?php echo ! $disable_responsive ? 'class="mpcth-responsive"' : ''; ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<?php if ( is_single() ) : ?>
		<meta property="og:image" content="<?php echo wp_get_attachment_url( get_post_thumbnail_id() ); ?>"/>
	<?php endif; ?>

	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php if ( $mpcth_options['mpcth_enable_fav_icon'] ) { ?>
		<link rel="icon" type="image/png" href="<?php echo $mpcth_options['mpcth_fav_icon']; ?>">
	<?php } ?>

	<?php
	if ( is_singular() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

		wp_head();
	?>
	<?php if ( $mpcth_options['mpcth_enable_analytics'] ) { ?>
		<script>
			<?php echo stripslashes( stripslashes( $mpcth_options['mpcth_analytics_code'] ) ); ?>
		</script>
	<?php } ?>
	    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	    <script  src="<?php echo get_stylesheet_directory_uri(); ?>/js/main.min.js"></script>

</head>

<!-- mpcth-responsive -->
<body <?php body_class( 'mpcth-sidebar-' . mpcth_get_sidebar_position() . $body_classes ); ?> <?php echo ! isset( $bg_cover ) ? $style : ''; ?>>
	<?php
	if ( isset( $bg_cover ) ) {
		echo $bg_cover;}
	?>

	<div id="mpcth_page_wrap" class="
	<?php
		echo ( $mpcth_options['mpcth_boxed_type'] != 'fullwidth' ) ? 'mpcth-boxed ' : '';
		echo ( $mpcth_options['mpcth_boxed_type'] == 'floating_boxed' ) ? 'mpcth-floating-boxed ' : '';
		echo ( $mpcth_options['mpcth_theme_skin'] == 'skin_dark' ) ? 'mpcth-skin-dark ' : '';
		echo ( $masonry_shop ? 'mpcth-masonry-shop ' : '' );
		echo ( $load_more_shop ? 'mpcth-load-more-shop ' : '' );
		echo ( is_rtl() ) ? 'mpcth-rtl ' : '';
		echo ( $disable_mobile_slider_nav ? 'mpcth-no-mobile-slider-nav ' : '' );
		echo ( $slider_revolution_original_nav ? '' : 'mpcth-rev-nav-original ' );
		echo ( $use_advance_colors ? 'mpcth-use-advance-colors ' : '' );
		echo ( $enable_transparent_header ? 'mpcth-transparent-header ' : '' );
		echo ( $enable_full_width_header ? 'mpcth-full-width-header ' : '' );
		echo ( $accordion_mobile_menu ? ' mpcth-accordion-menu ' : '' );
	?>
	">

		<?php if ( ! $simple_menu ) { ?>
			<a id="mpcth_toggle_mobile_menu" class="mpcth-color-main-color-hover" href="#"><i class="fa fa-bars"></i><i class="fa fa-times"></i></a>
			<div id="mpcth_mobile_nav_wrap">
				<nav id="mpcth_nav_mobile" role="navigation">
					<?php
					if ( has_nav_menu( 'mpcth_mobile_menu' ) ) {
						wp_nav_menu(
							array(
								'theme_location' => 'mpcth_mobile_menu',
								'container'      => '',
								'menu_id'        => 'mpcth_mobile_menu',
								'menu_class'     => 'mpcth-mobile-menu',
								'link_before'    => '<span class="mpcth-color-main-border">',
								'link_after'     => '</span>',
							)
						);
					} elseif ( has_nav_menu( 'mpcth_menu' ) ) {
						wp_nav_menu(
							array(
								'theme_location' => 'mpcth_menu',
								'container'      => '',
								'menu_id'        => 'mpcth_mobile_menu',
								'menu_class'     => 'mpcth-mobile-menu',
								'link_before'    => '<span class="mpcth-color-main-border">',
								'link_after'     => '</span>',
							)
						);
					} else {
						wp_nav_menu(
							array(
								'container'   => '',
								'menu_id'     => 'mpcth_mobile_menu',
								'menu_class'  => 'mpcth-mobile-menu',
								'link_before' => '<span class="mpcth-color-main-border">',
								'link_after'  => '</span>',
							)
						);
					}
					?>
				</nav><!-- end #mpcth_nav_mobile -->
			</div>
		<?php } ?>

		<?php
		if ( isset( $mpcth_options['mpcth_enable_header_area'] ) && $mpcth_options['mpcth_enable_header_area'] == true ) :

			if ( isset( $mpcth_options['mpcth_header_area_columns'] ) && $mpcth_options['mpcth_header_area_columns'] != '' ) {
				$header_area_columns = $mpcth_options['mpcth_header_area_columns'];
			} else {
				$header_area_columns = 3;
			}
			?>
			<a href="#" id="mpcth_toggle_header_area"><i class="fa fa-plus"></i></a>
			<div id="mpcth_header_area_wrap">
				<div id="mpcth_header_area">
					<ul class="mpcth-widget-column mpcth-widget-columns-<?php echo $header_area_columns; ?>">
					<?php dynamic_sidebar( 'mpcth_header_area' ); ?>
					</ul>
				</div>
			</div>
		<?php endif; ?>

		<?php echo do_shortcode("[rev_slider alias='announcement']"); ?>
		
		<div id="mpcth_page_header_wrap_spacer"></div>
		<?php
			$enable_sticky_header = true;
		if ( isset( $mpcth_options['mpcth_enable_sticky_header'] ) ) {
			$enable_sticky_header = $mpcth_options['mpcth_enable_sticky_header'];
		}

			$enable_mobile_sticky_header = false;
		if ( isset( $mpcth_options['mpcth_enable_mobile_sticky_header'] ) ) {
			$enable_mobile_sticky_header = $enable_sticky_header && $mpcth_options['mpcth_enable_mobile_sticky_header'];
		}

			$enable_simple_buttons = false;
		if ( isset( $mpcth_options['mpcth_enable_simple_buttons'] ) ) {
			$enable_simple_buttons = isset( $mpcth_options['mpcth_enable_simple_buttons'] ) && $mpcth_options['mpcth_enable_simple_buttons'];
		}

		if ( $enable_transparent_header && $force_simple_buttons ) {
			$enable_simple_buttons = true;
		}

			$sticky_header_offset = '75%';
		if ( isset( $mpcth_options['mpcth_enable_sticky_header'] ) && isset( $mpcth_options['mpcth_sticky_header_offset'] ) ) {
			$sticky_header_offset = $mpcth_options['mpcth_sticky_header_offset'];
		}

		?>
		<header id="mpcth_page_header_wrap" class="
		<?php
			echo $enable_sticky_header ? 'mpcth-sticky-header-enabled ' : '';
			echo $enable_mobile_sticky_header ? 'mpcth-mobile-sticky-header-enabled ' : '';
			echo $enable_simple_buttons ? 'mpcth-simple-buttons-enabled ' : '';
			echo $vertical_center_elements ? 'mpcth-vertical-center ' : '';
		?>
		" data-offset="<?php echo $sticky_header_offset; ?>">
			<div id="mpcth_page_header_container">
				<?php if ( $mpcth_options['mpcth_enable_secondary_header'] && $mpcth_options['mpcth_header_secondary_position'] == 'top' ) { ?>
					<div id="mpcth_header_second_section">
						<div class="mpcth-header-wrap">
							<?php mpcth_display_secondary_header(); ?>
						</div>
					</div>
				<?php } ?>
				<?php
				$header_order = 'l_m_s';
				if ( $mpcth_options['mpcth_header_main_layout'] ) {
					$header_order = $mpcth_options['mpcth_header_main_layout'];
				}

				$header_order_items = explode( '_', $header_order );

				$mobile_logo = '';
				if ( isset( $mpcth_options['mpcth_logo_mobile'] ) && $mpcth_options['mpcth_logo_mobile'] ) {
					$mobile_logo = $mpcth_options['mpcth_logo_mobile'];
				}

				$mobile_logo_2x = '';
				if ( isset( $mpcth_options['mpcth_logo_mobile_2x'] ) && $mpcth_options['mpcth_logo_mobile_2x'] ) {
					$mobile_logo_2x = $mpcth_options['mpcth_logo_mobile_2x'];
				}

				$sticky_logo = '';
				if ( isset( $mpcth_options['mpcth_logo_sticky'] ) && $mpcth_options['mpcth_logo_sticky'] ) {
					$sticky_logo = $mpcth_options['mpcth_logo_sticky'];
				}

				$sticky_logo_2x = '';
				if ( isset( $mpcth_options['mpcth_logo_sticky_2x'] ) && $mpcth_options['mpcth_logo_sticky_2x'] ) {
					$sticky_logo_2x = $mpcth_options['mpcth_logo_sticky_2x'];
				}
				?>
				<div id="mpcth_header_section">
					<div class="mpcth-header-wrap">
						<div id="mpcth_page_header_content" class="mpcth-header-order-<?php echo $header_order; ?>">
							<?php
							foreach ( $header_order_items as $item ) {
								if ( $item == 'l' || $item == 'tl' ) {
									?>
									<div id="mpcth_logo_wrap" class="<?php echo $mobile_logo || $mobile_logo_2x ? 'mpcth-mobile-logo-enabled' : ''; ?><?php echo $sticky_logo || $sticky_logo_2x ? ' mpcth-sticky-logo-enabled' : ''; ?>">
										<a id="mpcth_logo" href="<?php echo get_home_url(); ?>">
											<?php if ( ! $mpcth_options['mpcth_enable_text_logo'] && $mpcth_options['mpcth_logo'] != '' ) {

												$logo_width = '';
												if ( isset( $mpcth_options['mpcth_logo_width'] ) && $mpcth_options['mpcth_logo_width'] !== '' ) {
													$logo_width = 'width="' . $mpcth_options['mpcth_logo_width'] . '"';
												}
												?>
												<img <?php echo $logo_width; ?>  src="<?php echo $mpcth_options['mpcth_logo']; ?>" class="mpcth-standard-logo" alt="Logo">
												<?php if ( $mpcth_options['mpcth_logo_2x'] != '' ) { ?>
													<img <?php echo $logo_width; ?> src="<?php echo $mpcth_options['mpcth_logo_2x']; ?>" class="mpcth-retina-logo" alt="Logo">
												<?php } else { ?>
													<img <?php echo $logo_width; ?> src="<?php echo $mpcth_options['mpcth_logo']; ?>" class="mpcth-retina-logo" alt="Logo">
												<?php } ?>

												<?php if ( $mobile_logo != '' ) {
													$logo_mobile_width = '';
													if ( isset( $mpcth_options['mpcth_logo_mobile_width'] ) && $mpcth_options['mpcth_logo_mobile_width'] !== '' ) {
														$logo_mobile_width = 'width="' . $mpcth_options['mpcth_logo_mobile_width'] . '"';
													}
													?>
													<img <?php echo $logo_mobile_width; ?> src="<?php echo $mobile_logo; ?>" class="mpcth-mobile-logo" alt="Logo">
													<?php if ( $mobile_logo_2x != '' ) { ?>
														<img <?php echo $logo_mobile_width; ?> src="<?php echo $mobile_logo_2x; ?>" class="mpcth-retina-mobile-logo" alt="Logo">
													<?php } else { ?>
														<img <?php echo $logo_mobile_width; ?> src="<?php echo $mobile_logo; ?>" class="mpcth-retina-mobile-logo" alt="Logo">
													<?php } ?>
												<?php } ?>

												<?php if ( $sticky_logo != '' ) {
													$logo_sticky_width = '';
													if ( isset( $mpcth_options['mpcth_logo_sticky_width'] ) && $mpcth_options['mpcth_logo_sticky_width'] !== '' ) {
														$logo_sticky_width = 'width="' . $mpcth_options['mpcth_logo_sticky_width'] . '"';
													}
													?>
													<img <?php echo $logo_sticky_width; ?> src="<?php echo $sticky_logo; ?>" class="mpcth-sticky-logo" alt="Logo">
													<?php if ( $sticky_logo_2x != '' ) { ?>
														<img <?php echo $logo_sticky_width; ?> src="<?php echo $sticky_logo_2x; ?>" class="mpcth-retina-sticky-logo" alt="Logo">
													<?php } else { ?>
														<img <?php echo $logo_sticky_width; ?> src="<?php echo $sticky_logo; ?>" class="mpcth-retina-sticky-logo" alt="Logo">
													<?php } ?>
												<?php } ?>
											<?php } else { ?>
												<h2><?php echo $mpcth_options['mpcth_text_logo'] != '' ? $mpcth_options['mpcth_text_logo'] : get_bloginfo( 'name', 'display' ); ?></h2>
											<?php } ?>
										</a>
										<?php if ( $mpcth_options['mpcth_text_logo_description'] ) { ?>
											<small><?php echo get_bloginfo( 'description' ); ?></small>
										<?php } ?>
									</div><!-- end #mpcth_logo_wrap -->
									<?php
								}
								if ($item == 'm' || $item == 'rm' || $item == 'cm') { ?>
									<?php
									if ($header_order == 'tl_m_s')
										echo '<div id="mpcth_center_header_wrap">';
									?>
									
								<?php }
								if ($item == 's' || $item == 'cs') { ?>
									<div id="mpcth_controls_wrap">
										<div id="mpcth_controls_container" class="containercustomcart">

											<a id="" href="<?php echo get_site_url(); ?>/my-account" class="userlogin">
												<div  class="userlog">
												</div>
											</a>

											<?php
												$header_search = true;
												if (isset($mpcth_options['mpcth_enable_header_search']) && ! $mpcth_options['mpcth_enable_header_search'])
													$header_search = false;
											?>
											<?php if ($header_search) { ?>

												<a id="mpcth_search" href="#" class="buscador">
													<div class="customlupa">

													</div>
												</a>

											<?php } ?>
											<?php
												$disable_header_cart = isset($mpcth_options['mpcth_disable_header_cart']) && $mpcth_options['mpcth_disable_header_cart'];
											?>
											<?php if (function_exists('is_woocommerce') && ! $disable_header_cart) { ?>
												<?php if (sizeof( WC()->cart->get_cart()) > 0) { ?>
												<a id="mpcth_cart" href="<?php echo WC()->cart->get_cart_url(); ?>" class="cartbag">
													<div  class="customcart">
														<span class="bagitems"><?php echo WC()->cart->cart_contents_count; ?></span>
													</div>
												</a>

											

												<div id="mpcth_mini_cart">
													<?php mpcth_wc_print_mini_cart(); ?>
												</div>
												<?php } else {?>
													<a id="mpcth_cart" href="<?php echo WC()->cart->get_cart_url(); ?>" class="cartbag">
														<span class="customcartempty">

														</span>
													</a>

												<?php }?>
											<?php } ?>
											
											<?php if ($header_search && ! $smart_search) { ?>
												<div id="mpcth_mini_search">
													<form role="search" method="get" id="searchform" action="<?php echo home_url(); ?>">
														<input type="text" value="" name="s" id="s" placeholder="<?php _e('Search...', 'mpcth'); ?>">
														<input type="submit" id="searchsubmit" value="<?php _e('Search', 'mpcth'); ?>">
													</form>
												</div>
											<?php } ?>
										</div>
									</div><!-- end #mpcth_controls_wrap -->
									<?php
									if ($header_order == 'tl_m_s')
										echo '</div><!-- end #mpcth_center_header_wrap -->';
									?>
								<?php }
							}
							?>
						</div><!-- end #mpcth_page_header_content -->
					</div>
				</div>
				<?php if ( $mpcth_options['mpcth_enable_secondary_header'] && $mpcth_options['mpcth_header_secondary_position'] == 'bottom' ) { ?>
					<div id="mpcth_header_second_section">
						<div class="mpcth-header-wrap">
							<?php mpcth_display_secondary_header(); ?>
						</div>
					</div>
				<?php } ?>
			</div><!-- end #mpcth_page_header_container -->
			<?php
			if ( $smart_search && function_exists( 'is_woocommerce' ) ) {
					$currency_position = get_option( 'woocommerce_currency_pos' );
					$add_space         = $currency_position == 'right_space' || $currency_position == 'left_space' ? ' ' : '';
					// make https if needed
					$shop_page = wc_get_page_permalink( 'shop' );
				if ( get_option( 'woocommerce_force_ssl_checkout' ) == 'yes' ) {
					$shop_page = str_replace( 'http:', 'https:', $shop_page );
				}
				?>
				<div id="mpcth_smart_search_wrap">
					<input type="hidden" name="" id="mpcth_currency_symbol" value="<?php echo ( $currency_position == 'right_space' ? ' ' : '' ) . get_woocommerce_currency_symbol() . ( $currency_position == 'left_space' ? ' ' : '' ); ?>" data-position="<?php echo $currency_position; ?>">
					<form role="search" method="get" id="searchform" action="<?php echo $shop_page; ?>">
						<input type="hidden" name="post_type" value="product">
						<?php
							echo '<ul id="mpcth_smart_search">';
								dynamic_sidebar( 'mpcth_smart_search' );
							echo '</ul>';
						?>
						<div class="mpcth-smart-search-divider"><?php _e( '-&nbsp;&nbsp;&nbsp;OR&nbsp;&nbsp;&nbsp;-', 'mpcth' ); ?></div>
						<input type="text" value="" name="s" id="s" placeholder="<?php _e( 'Search for products', 'mpcth' ); ?>">
						<?php if ( function_exists( 'icl_object_id' ) ) { ?>
						<input type="hidden" value="<?php echo apply_filters( 'wpml_current_language', null ); ?>" name="lang" />
						<?php } ?>
						<div class="mpcth-smart-search-submit-wrap">
							<p>
								<input type="submit" id="searchsubmit" value="<?php _e( 'Find my items', 'mpcth' ); ?>">
								<i class="fa fa-search"></i>
							</p>
						</div>
					</form>
				</div>
			<?php } ?>
			<?php if ( $simple_menu ) { ?>
				<div id="mpcth_simple_mobile_nav_wrap">
					<nav id="mpcth_nav_mobile" role="navigation">
						<?php
						if ( has_nav_menu( 'mpcth_mobile_menu' ) ) {
							wp_nav_menu(
								array(
									'theme_location' => 'mpcth_mobile_menu',
									'container'      => '',
									'menu_id'        => 'mpcth_mobile_menu',
									'menu_class'     => 'mpcth-mobile-menu',
									'link_before'    => '<span class="mpcth-color-main-border">',
									'link_after'     => '</span>',
								)
							);
						} elseif ( has_nav_menu( 'mpcth_menu' ) ) {
							wp_nav_menu(
								array(
									'theme_location' => 'mpcth_menu',
									'container'      => '',
									'menu_id'        => 'mpcth_mobile_menu',
									'menu_class'     => 'mpcth-mobile-menu',
									'link_before'    => '<span class="mpcth-color-main-border">',
									'link_after'     => '</span>',
								)
							);
						} else {
							wp_nav_menu(
								array(
									'container'   => '',
									'menu_id'     => 'mpcth_mobile_menu',
									'menu_class'  => 'mpcth-mobile-menu',
									'link_before' => '<span class="mpcth-color-main-border">',
									'link_after'  => '</span>',
								)
							);
						}
						?>
					</nav><!-- end #mpcth_nav_mobile -->
				</div>
			<?php } ?>
			<div class="clearfix container-fluid">								
  <nav id="mpcth_nav" role="navigation" class="mpcth-disable-indicators">
    <!-- Static navbar -->
    
              <?php /* Primary navigation */
wp_nav_menu( array(
  'menu' => '346',
  'depth' => 3,
  'container' => false,
  'menu_class' => 'navbar-nav',
  'menu_id' => 'mpcth_menu',
  //Process nav menu using our custom nav walker
  'walker' => new wp_bootstrap_navwalker())
);
?>

  </nav>
  </div>
		</header><!-- end #mpcth_page_header_wrap -->



  <style>

.dropdown,
.dropup {
    position: relative;
}
.dropdown-toggle:focus {
    outline: 0;
}
.dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    z-index: 1000;
    display: none;
    float: left;
    min-width: 160px;
    padding: 5px 0;
    margin: 2px 0 0;
    font-size: 14px;
    text-align: left;
    list-style: none;
    background-color: #fff;
    -webkit-background-clip: padding-box;
    background-clip: padding-box;
    border: 1px solid #ccc;
    border: 1px solid rgba(0, 0, 0, 0.15);
    border-radius: 4px;
    -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
}
.dropdown-menu.pull-right {
    right: 0;
    left: auto;
}
.dropdown-menu .divider {
    height: 1px;
    margin: 9px 0;
    overflow: hidden;
    background-color: #e5e5e5;
}
.dropdown-menu > li > a {
    display: block;
    padding: 3px 20px;
    clear: both;
    font-weight: 400;
    line-height: 1.42857143;
    color: #333;
    white-space: nowrap;
}
.dropdown-menu > li > a:focus,
.dropdown-menu > li > a:hover {
    color: #262626;
    text-decoration: none;
    background-color: #f5f5f5;
}
.dropdown-menu > .active > a,
.dropdown-menu > .active > a:focus,
.dropdown-menu > .active > a:hover {
    color: #fff;
    text-decoration: none;
    background-color: #f1f1f1;
    outline: 0;
}
.dropdown-menu > .disabled > a,
.dropdown-menu > .disabled > a:focus,
.dropdown-menu > .disabled > a:hover {
    color: #777;
}
.dropdown-menu > .disabled > a:focus,
.dropdown-menu > .disabled > a:hover {
    text-decoration: none;
    cursor: not-allowed;
    background-color: transparent;
    background-image: none;
    filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
}
.open > .dropdown-menu {
    display: block;
}
.open > a {
    outline: 0;
}
.dropdown-menu-right {
    right: 0;
    left: auto;
}
.dropdown-menu-left {
    right: auto;
    left: 0;
}
.dropdown-header {
    display: block;
    padding: 3px 20px;
    font-size: 12px;
    line-height: 1.42857143;
    color: #777;
    white-space: nowrap;
}
.dropdown-backdrop {
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: 990;
}
.pull-right > .dropdown-menu {
    right: 0;
    left: auto;
}
.dropup .caret,
.navbar-fixed-bottom .dropdown .caret {
    content: "";
    border-top: 0;
    border-bottom: 4px dashed;
    border-bottom: 4px solid\9;
}
.dropup .dropdown-menu,
.navbar-fixed-bottom .dropdown .dropdown-menu {
    top: auto;
    bottom: 100%;
    margin-bottom: 2px;
}
@media (min-width: 768px) {
    .navbar-right .dropdown-menu {
        right: 0;
        left: auto;
    }
    .navbar-right .dropdown-menu-left {
        right: auto;
        left: 0;
    }
}
.nav {
    padding-left: 0;
    margin-bottom: 0;
    list-style: none;
}
.nav > li {
    position: relative;
    display: block;
}
.nav > li > a {
    position: relative;
    display: block;
    padding: 10px 15px;
}
.nav > li > a:focus,
.nav > li > a:hover {
    text-decoration: none;
    background-color: #eee;
}
.nav > li.disabled > a {
    color: #777;
}
.nav > li.disabled > a:focus,
.nav > li.disabled > a:hover {
    color: #777;
    text-decoration: none;
    cursor: not-allowed;
    background-color: transparent;
}
.nav .open > a,
.nav .open > a:focus,
.nav .open > a:hover {
    background-color: #eee;
    border-color: #f1f1f1;
}
.nav .nav-divider {
    height: 1px;
    margin: 9px 0;
    overflow: hidden;
    background-color: #e5e5e5;
}
.nav > li > a > img {
    max-width: none;
}
.nav-tabs .dropdown-menu {
    margin-top: -1px;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
}
.navbar {
    position: relative;
    min-height: 50px;
    margin-bottom: 20px;
    border: 1px solid transparent;
}
@media (min-width: 768px) {
    .navbar {
        border-radius: 4px;
    }
}
@media (min-width: 768px) {
    .navbar-header {
        float: left;
    }
}
.navbar-collapse {
    padding-right: 15px;
    padding-left: 15px;
    overflow-x: visible;
    -webkit-overflow-scrolling: touch;
    border-top: 1px solid transparent;
    -webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.1);
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.1);
}
.navbar-collapse.in {
    overflow-y: auto;
}
@media (min-width: 768px) {
    .navbar-collapse {
        width: auto;
        border-top: 0;
        -webkit-box-shadow: none;
        box-shadow: none;
    }
    .navbar-collapse.collapse {
        display: block !important;
        height: auto !important;
        padding-bottom: 0;
        overflow: visible !important;
    }
    .navbar-collapse.in {
        overflow-y: visible;
    }
    .navbar-fixed-bottom .navbar-collapse,
    .navbar-fixed-top .navbar-collapse,
    .navbar-static-top .navbar-collapse {
        padding-right: 0;
        padding-left: 0;
    }
}
.navbar-fixed-bottom .navbar-collapse,
.navbar-fixed-top .navbar-collapse {
    max-height: 340px;
}
@media (max-device-width: 480px) and (orientation: landscape) {
    .navbar-fixed-bottom .navbar-collapse,
    .navbar-fixed-top .navbar-collapse {
        max-height: 200px;
    }
}
.container-fluid > .navbar-collapse,
.container-fluid > .navbar-header,
.container > .navbar-collapse,
.container > .navbar-header {
    margin-right: -15px;
    margin-left: -15px;
}
@media (min-width: 768px) {
    .container-fluid > .navbar-collapse,
    .container-fluid > .navbar-header,
    .container > .navbar-collapse,
    .container > .navbar-header {
        margin-right: 0;
        margin-left: 0;
    }
}
.navbar-static-top {
    z-index: 1000;
    border-width: 0 0 1px;
}
@media (min-width: 768px) {
    .navbar-static-top {
        border-radius: 0;
    }
}
.navbar-fixed-bottom,
.navbar-fixed-top {
    position: fixed;
    right: 0;
    left: 0;
    z-index: 1030;
}
@media (min-width: 768px) {
    .navbar-fixed-bottom,
    .navbar-fixed-top {
        border-radius: 0;
    }
}
.navbar-fixed-top {
    top: 0;
    border-width: 0 0 1px;
}
.navbar-fixed-bottom {
    bottom: 0;
    margin-bottom: 0;
    border-width: 1px 0 0;
}
.navbar-brand {
    float: left;
    height: 50px;
    padding: 15px 15px;
    font-size: 18px;
    line-height: 20px;
}
.navbar-brand:focus,
.navbar-brand:hover {
    text-decoration: none;
}
.navbar-brand > img {
    display: block;
}
@media (min-width: 768px) {
    .navbar > .container .navbar-brand,
    .navbar > .container-fluid .navbar-brand {
        margin-left: -15px;
    }
}
.navbar-toggle {
    position: relative;
    float: right;
    padding: 9px 10px;
    margin-top: 8px;
    margin-right: 15px;
    margin-bottom: 8px;
    background-color: transparent;
    background-image: none;
    border: 1px solid transparent;
    border-radius: 4px;
}
.navbar-toggle:focus {
    outline: 0;
}
.navbar-toggle .icon-bar {
    display: block;
    width: 22px;
    height: 2px;
    border-radius: 1px;
}
.navbar-toggle .icon-bar + .icon-bar {
    margin-top: 4px;
}
@media (min-width: 768px) {
    .navbar-toggle {
        display: none;
    }
}
.navbar-nav {
    margin: 7.5px -15px;
}
.navbar-nav > li > a {
    padding-top: 10px;
    padding-bottom: 10px;
    line-height: 20px;
}
@media (max-width: 767px) {
    .navbar-nav .open .dropdown-menu {
        position: static;
        float: none;
        width: auto;
        margin-top: 0;
        background-color: transparent;
        border: 0;
        -webkit-box-shadow: none;
        box-shadow: none;
    }
    .navbar-nav .open .dropdown-menu .dropdown-header,
    .navbar-nav .open .dropdown-menu > li > a {
        padding: 5px 15px 5px 25px;
    }
    .navbar-nav .open .dropdown-menu > li > a {
        line-height: 20px;
    }
    .navbar-nav .open .dropdown-menu > li > a:focus,
    .navbar-nav .open .dropdown-menu > li > a:hover {
        background-image: none;
    }
}
@media (min-width: 768px) {
    .navbar-nav {
        float: left;
        margin: 0;
    }
    .navbar-nav > li {
        float: left;
    }
    .navbar-nav > li > a {
        padding-top: 15px;
        padding-bottom: 15px;
    }
}
.navbar-form {
    padding: 10px 15px;
    margin-top: 8px;
    margin-right: -15px;
    margin-bottom: 8px;
    margin-left: -15px;
    border-top: 1px solid transparent;
    border-bottom: 1px solid transparent;
    -webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.1), 0 1px 0 rgba(255, 255, 255, 0.1);
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.1), 0 1px 0 rgba(255, 255, 255, 0.1);
}
@media (min-width: 768px) {
    .navbar-form .form-group {
        display: inline-block;
        margin-bottom: 0;
        vertical-align: middle;
    }
    .navbar-form .form-control {
        display: inline-block;
        width: auto;
        vertical-align: middle;
    }
    .navbar-form .form-control-static {
        display: inline-block;
    }
    .navbar-form .input-group {
        display: inline-table;
        vertical-align: middle;
    }
    .navbar-form .input-group .form-control,
    .navbar-form .input-group .input-group-addon,
    .navbar-form .input-group .input-group-btn {
        width: auto;
    }
    .navbar-form .input-group > .form-control {
        width: 100%;
    }
    .navbar-form .control-label {
        margin-bottom: 0;
        vertical-align: middle;
    }
    .navbar-form .checkbox,
    .navbar-form .radio {
        display: inline-block;
        margin-top: 0;
        margin-bottom: 0;
        vertical-align: middle;
    }
    .navbar-form .checkbox label,
    .navbar-form .radio label {
        padding-left: 0;
    }
    .navbar-form .checkbox input[type="checkbox"],
    .navbar-form .radio input[type="radio"] {
        position: relative;
        margin-left: 0;
    }
    .navbar-form .has-feedback .form-control-feedback {
        top: 0;
    }
}
@media (max-width: 767px) {
    .navbar-form .form-group {
        margin-bottom: 5px;
    }
    .navbar-form .form-group:last-child {
        margin-bottom: 0;
    }
}
@media (min-width: 768px) {
    .navbar-form {
        width: auto;
        padding-top: 0;
        padding-bottom: 0;
        margin-right: 0;
        margin-left: 0;
        border: 0;
        -webkit-box-shadow: none;
        box-shadow: none;
    }
}
.navbar-nav > li > .dropdown-menu {
    margin-top: 0;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
}
.navbar-fixed-bottom .navbar-nav > li > .dropdown-menu {
    margin-bottom: 0;
    border-top-left-radius: 4px;
    border-top-right-radius: 4px;
    border-bottom-right-radius: 0;
    border-bottom-left-radius: 0;
}
.navbar-btn {
    margin-top: 8px;
    margin-bottom: 8px;
}
.navbar-btn.btn-sm {
    margin-top: 10px;
    margin-bottom: 10px;
}
.navbar-btn.btn-xs {
    margin-top: 14px;
    margin-bottom: 14px;
}
.navbar-text {
    margin-top: 15px;
    margin-bottom: 15px;
}
@media (min-width: 768px) {
    .navbar-text {
        float: left;
        margin-right: 15px;
        margin-left: 15px;
    }
}
@media (min-width: 768px) {
    .navbar-left {
        float: left !important;
    }
    .navbar-right {
        float: right !important;
        margin-right: -15px;
    }
    .navbar-right ~ .navbar-right {
        margin-right: 0;
    }
}
.navbar-default {
    background-color: #f8f8f8;
    border-color: #e7e7e7;
}
.navbar-default .navbar-brand {
    color: #777;
}
.navbar-default .navbar-brand:focus,
.navbar-default .navbar-brand:hover {
    color: #5e5e5e;
    background-color: transparent;
}
.navbar-default .navbar-text {
    color: #777;
}
.navbar-default .navbar-nav > li > a {
    color: #777;
}
.navbar-default .navbar-nav > li > a:focus,
.navbar-default .navbar-nav > li > a:hover {
    color: #333;
    background-color: transparent;
}
.navbar-default .navbar-nav > .active > a,
.navbar-default .navbar-nav > .active > a:focus,
.navbar-default .navbar-nav > .active > a:hover {
    color: #555;
    background-color: #e7e7e7;
}
.navbar-default .navbar-nav > .disabled > a,
.navbar-default .navbar-nav > .disabled > a:focus,
.navbar-default .navbar-nav > .disabled > a:hover {
    color: #ccc;
    background-color: transparent;
}
.navbar-default .navbar-toggle {
    border-color: #ddd;
}
.navbar-default .navbar-toggle:focus,
.navbar-default .navbar-toggle:hover {
    background-color: #ddd;
}
.navbar-default .navbar-toggle .icon-bar {
    background-color: #888;
}
.navbar-default .navbar-collapse,
.navbar-default .navbar-form {
    border-color: #e7e7e7;
}
.navbar-default .navbar-nav > .open > a,
.navbar-default .navbar-nav > .open > a:focus,
.navbar-default .navbar-nav > .open > a:hover {
    color: #555;
    background-color: #e7e7e7;
}
@media (max-width: 767px) {
    .navbar-default .navbar-nav .open .dropdown-menu > li > a {
        color: #777;
    }
    .navbar-default .navbar-nav .open .dropdown-menu > li > a:focus,
    .navbar-default .navbar-nav .open .dropdown-menu > li > a:hover {
        color: #333;
        background-color: transparent;
    }
    .navbar-default .navbar-nav .open .dropdown-menu > .active > a,
    .navbar-default .navbar-nav .open .dropdown-menu > .active > a:focus,
    .navbar-default .navbar-nav .open .dropdown-menu > .active > a:hover {
        color: #555;
        background-color: #e7e7e7;
    }
    .navbar-default .navbar-nav .open .dropdown-menu > .disabled > a,
    .navbar-default .navbar-nav .open .dropdown-menu > .disabled > a:focus,
    .navbar-default .navbar-nav .open .dropdown-menu > .disabled > a:hover {
        color: #ccc;
        background-color: transparent;
    }
}
.navbar-default .navbar-link {
    color: #777;
}
.navbar-default .navbar-link:hover {
    color: #333;
}
.navbar-default .btn-link {
    color: #777;
}
.navbar-default .btn-link:focus,
.navbar-default .btn-link:hover {
    color: #333;
}
.navbar-default .btn-link[disabled]:focus,
.navbar-default .btn-link[disabled]:hover,
fieldset[disabled] .navbar-default .btn-link:focus,
fieldset[disabled] .navbar-default .btn-link:hover {
    color: #ccc;
}
.navbar-inverse {
    background-color: #222;
    border-color: #080808;
}
.navbar-inverse .navbar-brand {
    color: #9d9d9d;
}
.navbar-inverse .navbar-brand:focus,
.navbar-inverse .navbar-brand:hover {
    color: #fff;
    background-color: transparent;
}
.navbar-inverse .navbar-text {
    color: #9d9d9d;
}
.navbar-inverse .navbar-nav > li > a {
    color: #9d9d9d;
}
.navbar-inverse .navbar-nav > li > a:focus,
.navbar-inverse .navbar-nav > li > a:hover {
    color: #fff;
    background-color: transparent;
}
.navbar-inverse .navbar-nav > .active > a,
.navbar-inverse .navbar-nav > .active > a:focus,
.navbar-inverse .navbar-nav > .active > a:hover {
    color: #fff;
    background-color: #080808;
}
.navbar-inverse .navbar-nav > .disabled > a,
.navbar-inverse .navbar-nav > .disabled > a:focus,
.navbar-inverse .navbar-nav > .disabled > a:hover {
    color: #444;
    background-color: transparent;
}
.navbar-inverse .navbar-toggle {
    border-color: #333;
}
.navbar-inverse .navbar-toggle:focus,
.navbar-inverse .navbar-toggle:hover {
    background-color: #333;
}
.navbar-inverse .navbar-toggle .icon-bar {
    background-color: #fff;
}
.navbar-inverse .navbar-collapse,
.navbar-inverse .navbar-form {
    border-color: #101010;
}
.navbar-inverse .navbar-nav > .open > a,
.navbar-inverse .navbar-nav > .open > a:focus,
.navbar-inverse .navbar-nav > .open > a:hover {
    color: #fff;
    background-color: #080808;
}
@media (max-width: 767px) {
    .navbar-inverse .navbar-nav .open .dropdown-menu > .dropdown-header {
        border-color: #080808;
    }
    .navbar-inverse .navbar-nav .open .dropdown-menu .divider {
        background-color: #080808;
    }
    .navbar-inverse .navbar-nav .open .dropdown-menu > li > a {
        color: #9d9d9d;
    }
    .navbar-inverse .navbar-nav .open .dropdown-menu > li > a:focus,
    .navbar-inverse .navbar-nav .open .dropdown-menu > li > a:hover {
        color: #fff;
        background-color: transparent;
    }
    .navbar-inverse .navbar-nav .open .dropdown-menu > .active > a,
    .navbar-inverse .navbar-nav .open .dropdown-menu > .active > a:focus,
    .navbar-inverse .navbar-nav .open .dropdown-menu > .active > a:hover {
        color: #fff;
        background-color: #080808;
    }
    .navbar-inverse .navbar-nav .open .dropdown-menu > .disabled > a,
    .navbar-inverse .navbar-nav .open .dropdown-menu > .disabled > a:focus,
    .navbar-inverse .navbar-nav .open .dropdown-menu > .disabled > a:hover {
        color: #444;
        background-color: transparent;
    }
}
.navbar-inverse .navbar-link {
    color: #9d9d9d;
}
.navbar-inverse .navbar-link:hover {
    color: #fff;
}
.navbar-inverse .btn-link {
    color: #9d9d9d;
}
.navbar-inverse .btn-link:focus,
.navbar-inverse .btn-link:hover {
    color: #fff;
}
.navbar-inverse .btn-link[disabled]:focus,
.navbar-inverse .btn-link[disabled]:hover,
fieldset[disabled] .navbar-inverse .btn-link:focus,
fieldset[disabled] .navbar-inverse .btn-link:hover {
    color: #444;
}

















    .navbar-default {
      background-color: #fff;
      border-color: #fff;
    }

  /* ========================================================================
   * DECOM d.o.o.
   * Distribucija sadrzaja zabranjena
   * ========================================================================
   * NAVIGACIja - NAVIGATION ======================================================================== */

   .navigacija {text-transform:uppercase; position:relative !important;}
   .navbar-collapse.collapse {overflow:hidden !important;}
   .navigacija > ul  { margin-left:28% !important;}
   @media (max-width: 768px) {
    .navigacija > ul {margin-top:0;margin-left:0 !important;}
    .navbar-collapse.collapse {overflow:visible !important}
  }

  .dropdown-menu {width: 100%;
    top: 43px;
    left: 0;
    z-index: 1000;
    display: none;
    float: left;
    min-width: 160px;
    padding: 5px 0;
    margin: 0 auto;
    text-align:center;
  }
.navbar {
    /* position: relative; */
    min-height: 50px;
    margin-bottom: 0px; 
    border: 1px solid transparent;
}
  ul ul.dropdown-menu.show {
    margin: -1px 0px;
}
  .dropdown-menu li {display:inline-block;}



  .dropdown-menu li.dropdown {
    background-position: 5px 9px !important;
  }

  .navbar-brand img {max-width:130px;}

  ul ul.dropdown-menu.show {
    margin: 36px 0px;
}

.navbar-nav>li>.dropdown-menu {
    margin-top: -1px;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
}
ul.dropdown-menu ul {
    left: 0%;
}
.dropdown-menu {
    width: 100%;
    position: absolute;
    /* top: 178px; */
    left: 0;
    z-index: 1000;
    display: none;
    float: unset;
    padding: 5px 0;
    margin: 0 auto;
    text-align: center;
    left: 0px;
}

ul ul.dropdown-menu.show {
    margin: -2px 0px;
}
#mpcth_page_header_content #mpcth_nav {
    display: flex !important;
}
li.menu-item.menu-item-type-taxonomy a {
    margin: 0 -4px;
    padding: 0px 0px;
}












nav#mpcth_nav {
    text-align: center;
    align-items: center;
    margin: 0 auto;
    padding: inherit;
    display: table;
}
nav {
    display: block;
}



ul {
    display: block;
    list-style-type: disc;
    margin-block-start: 1em;
    margin-block-end: 1em;
    margin-inline-start: 0px;
    margin-inline-end: 0px;
    padding-inline-start: 40px;
}

element.style {
}

#mpcth_menu .menu-item {
    margin: 0 1.2em;
}
.mpcth-menu .page_item, .mpcth-menu .menu-item, #mpcth_menu .page_item, #mpcth_menu .menu-item {
    position: unset;
    z-index: 1;
    list-style: none;
    padding: 0;
    text-transform: uppercase;
}



ul.dropdown-menu {
}
.dropdown-menu {
    width: 100%;
    top: 132px;
    left: 0;
    z-index: 1000;
}
.dropdown-menu {
    width: 100%;
    top: -4px;
    left: 0;
    z-index: 1000;
    display: none;
    padding: 5px 0;
}
.dropdown-menu {
    top: 100%;
    left: 0;
    z-index: 1000;
    display: none;
    padding: 5px 0;
    margin: 2px 0 0;
    /* font-size: 14px; */
    text-align: center;
    list-style: none;
    background-color: #fff;
    /* background-clip: padding-box; */
    /* border: 1px solid #ccc; */
    border: 1px solid rgba(0,0,0,.15);
    border-radius: 4px;
    -webkit-box-shadow: 0 6px 12px rgba(0,0,0,.175);
    box-shadow: 0 6px 12px rgba(0,0,0,.175);
}
}

#mpcth_menu .menu-item {
    margin: 0 1.2em;
}
.mpcth-menu .page_item, .mpcth-menu .menu-item, #mpcth_menu .page_item, #mpcth_menu .menu-item {
    position: unset;
    z-index: 1;
    list-style: none;
    padding: 0;
    text-transform: uppercase;
}
.dropdown-menu li.dropdown {
    background-position: 5px 9px !important;
}
.dropdown-menu li {
    display: inline-block;
}
.dropdown, .dropup {
    position: relative;
}
* {
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
}
.dropdown-menu li.dropdown .dropdown-menu {
    top: 33px;
}



.nav > li .icon-caret {
  position: absolute;
  z-index: 1;
  padding: 0;
  right: 0;
  padding: 0.5em;
}
.sub-menu {
  display: none;
}







.dropdown-submenu {
  position: relative;
}

.dropdown-submenu a::after {
  transform: rotate(-90deg);
  position: absolute;
  right: 6px;
  top: .8em;
}

.dropdown-submenu .dropdown-menu {
  top: 0;
  left: 100%;
  margin-left: .1rem;
  margin-right: .1rem;
}
/* to show the arrow */
.dropdown-submenu a::after {
  transform: rotate(-90deg);
  position: absolute;
  right: 6px;
  top: .8em;
}
.dropdown-toggle a::after{
  transform: rotate(-90deg);
  position: absolute;
  right: 6px;
  top: .8em;
}

.dropdown-menu li.dropdown .dropdown-menu {
    top: 37px;
}


/**NEW**/

.dropdown-menu li.dropdown .dropdown-menu {
    top: 22px !important;
    display: table !important;
    float: left!important;
    position: inherit !important;
    border: 0px !important;
    text-shadow: none !important;
}


.dropdown-menu {
    top: 100% ;
    left: 0;
    z-index: 1000;
    display: none;
    padding: 5px 0;
    margin: 2px 0 0;
    /* font-size: 14px; */
    text-align: center;
    list-style: none;
    background-color: #fff;
    /* background-clip: padding-box; */
    /* border: 1px solid #ccc; */
    border: 1px solid rgba(0,0,0,.15);
    border-radius: 4px;
    -webkit-box-shadow: 0 6px 12px rgba(0,0,0,.175);
    box-shadow: none;
}

.mpcth-menu .page_item, .mpcth-menu .menu-item, #mpcth_menu .page_item, #mpcth_menu .menu-item {
    position: unset !important;
    z-index: 1;
    list-style: none;
    padding: 0;
    text-transform: uppercase;
    /* display: */
}

.navbar-nav>li>.dropdown-menu ul li {
    margin-top: 0;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
    display: block !important;
    position: relative !important;
    box-shadow: none !important;
}

li.menu-item.menu-item-type-taxonomy.menu-item-object-product_cat.menu-item-has-children.dropdown.open {
    border-color: #21b7ea !important;
    border-bottom: 1px solid transparent;
    padding: .501em 0;
}

.navbar-nav li li {
    min-width: 121px !important;
}


#mpcth_menu > .menu-item > a, #mpcth_page_wrap #mpcth_mega_menu .widget ul.menu > li > a, body #mpcth_page_header_content #mpcth_controls_wrap {
    padding: 1.5em 0.3em 0.5em;
}

#mpcth_menu {
    margin-top: -27px !important;
    font-weight: 400;
    color: #d60d0d !important;
    font-size: 15px;
}

li ul .dropdown-toggle {
    border-bottom: 1px solid #cccccc;
}

.mpcth-menu .page_item > a, .mpcth-menu .menu-item > a, #mpcth_menu .page_item > a, #mpcth_menu .menu-item > a {
    position: relative;
    color: #969696;
}


@media screen and (min-width: 768px){

	div#mpcth_controls_container {
	    top: 24px;
	}
}

.clearfix.container-fluid {
    background: white;
}
div#mpcth_mobile_nav_wrap {
    box-shadow: none !important;
    text-shadow: none;
}

a#mpcth_toggle_mobile_menu {
    box-shadow: none !important;
}

@media (max-width: 590px){
.mpcth-responsive #mpcth_page_wrap #mpcth_page_header_container #mpcth_page_header_content #mpcth_controls_wrap {
    margin-top: -25px;
}
}
#mpcth_toggle_mobile_menu:hover {
    left: 0px;
}

a#mpcth_toggle_mobile_menu {
    top: 127px;
    color: #b9b9b9;
}

#mpcth_toggle_mobile_menu:hover + #mpcth_mobile_nav_wrap {
    left: -320px;
    box-shadow: 0 0 25px rgba(0, 0, 0, 0.25);
}

#mpcth_toggle_header_area, #mpcth_toggle_mobile_sidebar, #mpcth_toggle_mobile_menu {
    position: absolute;
}

li ul .dropdown-toggle {
    border-bottom: 1px solid #cccccc;
    border-right: 48px solid white;
    margin: 0px 8px !important;
    /* padding: 0 !important; */
}


@media (min-width: 590px){
	.mpcth-menu .page_item > a > i.fa, .mpcth-menu .menu-item > a > i.fa, #mpcth_menu .page_item > a > i.fa, #mpcth_menu .menu-item > a > i.fa {
	    margin-right: 0em;
	    font-size: 14px;
	}
}


@media only screen and (min-width: 600px) and (orientation: landscape) {
.sub {
    border-bottom: 1px solid #cccccc;
    border-right: 48px solid white;
}
.dropdown-menu ul{
		text-align:left;
		letter-spacing:0.5px;
		font-size:9.7pt;
}
	
	.dropdown-menu ul a{
		padding-bottom:3px !important;

}

.dropdown-menu li{
	text-align:left;

}

.dropdown-menu .menu-item > a:hover{
		color:#46A8CF !important;
	background-color:rgba(252,252,252,0);
}

li ul .dropdown-toggle{
	padding-bottom:7px !important; 
	pointer-events: none;
}

li.menu-item.menu-item-type-taxonomy.menu-item-object-product_cat.menu-item-has-children.dropdown.open{
	border-bottom:solid 3px #46A8CF !important;
}

	
	
.dropdown-menu .menu-item .menu-item-object-product_cat > a{
		line-height:26px;
	}


}


.dropdown-menu>li>a {
    display: block;
    padding: 3px 0px !important;
    clear: both;
    font-weight: 400;
    line-height: 1.42857143;
    color: #333;
    white-space: nowrap;
}

.dropdown-menu>.active>a, .dropdown-menu>.active>a:focus, .dropdown-menu>.active>a:hover {
    color: #fff;
    text-decoration: none;
    background-color: #fff !important;
    outline: 0;
    color: #666666 !important;
}
/*end*/

.mpcth_nav_mobile .mpcth-mobile-menu .children, #mpcth_nav_mobile .mpcth-mobile-menu .sub-menu, #mpcth_nav_mobile #mpcth_mobile_menu .children, #mpcth_nav_mobile #mpcth_mobile_menu .sub-menu {
    margin: 0;
    padding: revert;
    font-size: .75em;
}


 </style>

