<?php
/**
 * Main functions file
 *
 * @package WordPress
 * @subpackage Shop Isle
 */

/**
 * Initialize all the things.
 */
require get_template_directory() . '/inc/init.php';

/**
 * Note: Do not add any custom code here. Please use a child theme so that your customizations aren't lost during updates.
 * http://codex.wordpress.org/Child_Themes
 */

function product_tabs_meta_boxes() {
	add_meta_box(
		'describe-custom-metabox',
        __( 'Вкладки', 'woocommerce' ),
        'describe_meta_box_callback',
        'product'
    );
}
add_action( 'add_meta_boxes', 'product_tabs_meta_boxes' );

function describe_meta_box_callback( $post, $meta ) {
	$categories = get_categories( [
		'taxonomy'     => 'category',
		'type'         => 'product',
		'child_of'     => 0,
		'parent'       => '',
		'orderby'      => 'name',
		'order'        => 'ASC',
		'hide_empty'   => 1,
		'hierarchical' => 1,
		'exclude'      => '',
		'include'      => '',
		'number'       => 0,
		'pad_counts'   => false,
	] );		
	
	$resipes = wc_get_products(array(
		'numberposts' => -1,
		'post_status' => 'published',
		'category' => array('all-recipes'),
	));

	$products = wc_get_products(array(
		'numberposts' => -1,
		'post_status' => 'published',
		'category' => array('all-products'),
	));

	$value_product_input = get_option('name_product');
	$value_recipes_input = get_option('name_recipes');

	$tab_products = get_post_meta($post->ID, 'tab_products', true);
	$tab_recipes  = get_post_meta($post->ID, 'tab_recipes', true);

	?>
	<div>
		<input type="text" name="name_product" value="<?php echo $value_product_input; ?>" ><br><br>
		<select class="product_tabs_meta_boxes" name="tab_products[]" multiple="multiple">
		<?php foreach ( $products as $product ) { ?>
			<option value="<?php echo $product->get_id(); ?>" <?php echo ( !empty($tab_products) && in_array($product->get_id(), $tab_products) ) ? 'selected' : '' ?>> <?php echo $product->get_name() ?> </option>
		<?php } ?>
		</select>
	</div>
	<br>
	<br>
	<div>
		<input type="text" name="name_recipes" value = "<?php echo $value_recipes_input ?>" ><br><br>
		<select class="product_tabs_meta_boxes" name="tab_recipes[]" multiple="multiple">
		<?php foreach ( $resipes as $resip ) { ?>
			<option value="<?php echo $resip->get_id(); ?>" <?php echo ( !empty($tab_recipes) &&  in_array($resip->get_id(), $tab_recipes) ) ? 'selected' : '' ?>> <?php echo $resip->get_name() ?> </option>
		<?php } ?>
		</select>
	</div>
	<?php
}

function myplugin_add_custom_box(){
	add_meta_box( 'myplugin_sectionid', 'Кнопки для завантаження файлів', 'myplugin_meta_box_callback', 'product' );
}
add_action('add_meta_boxes', 'myplugin_add_custom_box');

function myplugin_meta_box_callback( $post, $meta ){
	//description
	$value_button_input_description = get_option('name_button_description');
	$url_downloads_file_description = get_post_meta(get_post()->ID, 'hidden_url_downloads_description', true);
	$strStringDescription = substr(strrchr($url_downloads_file_description, "/"), 1);

	//brochure
	$value_button_input_brochure = get_option('name_button_brochure');
	$url_downloads_file_brochure = get_post_meta(get_post()->ID, 'hidden_url_downloads_brochure', true);
	$strStringBrochure = substr(strrchr($url_downloads_file_brochure, "/"), 1);

	?>
	<div class="description-block">
		<input type="text" class="hidden-input-1" id="hidden_url_downloads_description" name="hidden_url_downloads_description" value="<?php echo $url_downloads_file_description ?>">
		<input type="text" name="name_button_description" value = "<?php echo $value_button_input_description; ?>" placeholder="текст кнопки"><br><br>
		<button type="button" name="button_product_description[]" id="add_item" data-position="1" class="add-item">Select File</button>
		<p name="name_text" class="pathToFileDescription position-1"><?php echo $strStringDescription ?><span data-position="1" class="clear-file clear-file1" style="display: <?php echo (!empty($strStringDescription) ? 'block' : 'none') ?>"></span></p>
	</div>
	<div class="brochure-block">
		<input type="text" class="hidden-input-2" id="hidden_url_downloads_brochure" name="hidden_url_downloads_brochure" value="<?php echo $url_downloads_file_brochure ?>">
		<input type="text" name="name_button_brochure" value = "<?php echo $value_button_input_brochure; ?>" placeholder="текст кнопки"><br><br>
		<button type="button" name="button_product_brochure[]" id="add_itemff" data-position="2"class="add-item">Select File</button>
		<?php //if (!empty($strStringBrochure)) { ?>
			<p name="name_text" class="pathToFileBrochure position-2"><?php echo $strStringBrochure;?><span data-position="2" class="clear-file clear-file2" style="display: <?php echo (!empty($strStringBrochure) ? 'block' : 'none') ?>"></span></p>
		<?php	//} ?>
	</div>
    <?php 
}

function myplugin_save_postdata( $post_id ) {
	if( !empty( $_POST['tab_products'] ) ) {
		update_post_meta( $post_id, 'tab_products', $_POST['tab_products'] );
	}

	if( !empty( $_POST['tab_recipes'] ) ) {
		update_post_meta( $post_id, 'tab_recipes', $_POST['tab_recipes'] );
	}
	if( !empty( $_POST['name_product'] ) ) {
		update_option( 'name_product', $_POST['name_product'] );
	} else {
		update_option( 'name_product', 'Продукти' );
	}

	if( !empty( $_POST['name_recipes'] ) ) {
		update_option( 'name_recipes', $_POST['name_recipes'] );
	} else {
		update_option( 'name_recipes', 'Рецепти' );
	}

	//description
	if ( !empty( $_POST['name_button_description'] ) ) {
		update_option( 'name_button_description', $_POST['name_button_description'] );
	} else {
		update_option( 'name_button_description', 'Опис продукту' );
	}
	if ( isset( $_POST['hidden_url_downloads_description'] ) ) {
		update_post_meta( $post_id, 'hidden_url_downloads_description', $_POST['hidden_url_downloads_description'] );
	}

	//brochure
	if ( !empty( $_POST['name_button_brochure'] ) ) {
		update_option( 'name_button_brochure', $_POST['name_button_brochure'] );
	} else {
		update_option( 'name_button_brochure', 'Брошура' );
	}

	if ( isset( $_POST['hidden_url_downloads_brochure'] ) ) {
		update_post_meta( $post_id, 'hidden_url_downloads_brochure', $_POST['hidden_url_downloads_brochure'] );
	}
}
add_action( 'save_post', 'myplugin_save_postdata' );

function display_speaker_name($post) {
	//description
	$value_button_input_description = get_option('name_button_description');
	$url_downloads_file_description = get_post_meta(get_post()->ID, 'hidden_url_downloads_description', true);
	
	//brochure
	$value_button_input_brochure = get_option('name_button_brochure');
	$url_downloads_file_brochure = get_post_meta(get_post()->ID, 'hidden_url_downloads_brochure', true);
	// print_var(wp_get_current_user());
	
	if ( is_user_logged_in() ) {
		if ( !empty($url_downloads_file_description) ) {
			echo '<div class="row"><div class="col-sm-12"><a class="btn custom-btn btn-b btn-round icon-dwnl" href="/wp-content'. $url_downloads_file_description .'" download>'. $value_button_input_description  .'</a></div></div>';
		}
	}
	if ( !empty($url_downloads_file_brochure) ) {
		echo '<div class="row mt-10"><div class="col-sm-12"><a class="btn custom-btn btn-bb btn-round icon-dwnl" href="/wp-content'. $url_downloads_file_brochure .'" download>'. $value_button_input_brochure  .'</a></div></div>';
	}
}
add_action('woocommerce_single_product_summary', 'display_speaker_name', 30);

function wooc_extra_register_fields() {?>
	<p class="form-row form-row-wide">
		<label for="reg_billing_phone"><?php _e( 'Phone', 'woocommerce' ); ?></label>
		<input type="text" class="input-text" name="billing_phone" id="reg_billing_phone" value="<?php if ( ! empty( $_POST['billing_phone'] ) ) esc_attr_e( $_POST['billing_phone'] ); ?>" />
	</p>
	<p class="form-row form-row-first">
		<label for="reg_billing_first_name"><?php _e( 'First name', 'woocommerce' ); ?><span class="required">*</span></label>
		<input type="text" class="input-text" name="billing_first_name" id="reg_billing_first_name" value="<?php if ( ! empty( $_POST['billing_first_name'] ) ) esc_attr_e( $_POST['billing_first_name'] ); ?>" />
	</p>
	<p class="form-row form-row-last">
		<label for="reg_billing_last_name"><?php _e( 'Last name', 'woocommerce' ); ?><span class="required">*</span></label>
		<input type="text" class="input-text" name="billing_last_name" id="reg_billing_last_name" value="<?php if ( ! empty( $_POST['billing_last_name'] ) ) esc_attr_e( $_POST['billing_last_name'] ); ?>" />
	</p>
	<div class="clear"></div>
	<?php
}
add_action( 'woocommerce_register_form_start', 'wooc_extra_register_fields' );

function wooc_validate_extra_register_fields( $username, $email, $validation_errors ) {
	if ( isset( $_POST['billing_first_name'] ) && empty( $_POST['billing_first_name'] ) ) {
		   $validation_errors->add( 'billing_first_name_error', __( '<strong>Error</strong>: First name is required!', 'woocommerce' ) );
	}
	if ( isset( $_POST['billing_last_name'] ) && empty( $_POST['billing_last_name'] ) ) {
		   $validation_errors->add( 'billing_last_name_error', __( '<strong>Error</strong>: Last name is required!.', 'woocommerce' ) );
	}

	return $validation_errors;
}
add_action( 'woocommerce_register_post', 'wooc_validate_extra_register_fields', 10, 3 );

function action_woocommerce_created_customer( $customer_id, $new_customer_data, $password_generated ) { 
	$uniq_token = md5( uniqid(rand(), 10) );
	
	update_user_meta($customer_id, 'token', $uniq_token );
	update_user_meta($customer_id, 'isRegistered', 'false' );
}
add_action( 'woocommerce_created_customer', 'action_woocommerce_created_customer', 10, 3 ); 

function user_autologout(){
	wp_logout();
	wp_redirect( get_site_url().'/before-verify-account/' );
}
add_action('woocommerce_registration_redirect', 'user_autologout', 2);

function customcode($username, $password ) {
	if (!empty($username) && !empty($password)) {
		if ( preg_match("/[-0-9a-zA-Z.+_]+@[-0-9a-zA-Z.+_]+.[a-zA-Z]{2,4}/", $username) )
			$user = get_user_by( 'email', $username );
		else
			$user = get_user_by( 'slug', $username );

		$isRegisteredFOrLinks = get_user_meta( $user->ID, 'isRegistered' );

		if ( !empty($isRegisteredFOrLinks) ) {
			if ( $isRegisteredFOrLinks[0] == 'false' ) {
				wp_logout();
				wp_redirect( get_site_url().'/before-verify-account/' );
				exit;
			}
		}
	}
}
add_action('wp_authenticate', 'customcode', 30, 2);
