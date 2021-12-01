<?php
/**
 * Define the internationalization functionality.
 * Loads and defines the internationalization files for this plugin
 *
 * @since      1.0.0
 * @package    Wpgsi
 * @subpackage Wpgsi/includes
 * @author     javmah <jaedmah@gmail.com>
 */

class Wpgsi_Admin {

	/**
	 * Events Children titles .
	 *
	 * @since    1.0.0
	 * @access   Public
	 * @var      array    $eventsAndTitles    Events list.
	 */	
	private $plugin_name;

	/**
	 * Events Children titles .
	 *
	 * @since    1.0.0
	 * @access   Public
	 * @var      array    $eventsAndTitles    Events list.
	 */	
	private $version;

	/**
	 * Events Children titles .
	 *
	 * @since    1.0.0
	 * @access   Public
	 * @var      array    $eventsAndTitles    Events list.
	 */	
	public $googleSheet;

	/**
	 * The current Date.
	 *
	 * @since    1.0.0
	 * @access   Public
	 * @var      string    $Date    The current version of the plugin.
	 */
	Public $Date = "";

	/**
	 * The current Time.
	 *
	 * @since    1.0.0
	 * @access   Public
	 * @var      string    $Time   The current Time.
	 */
	Public $Time = "";

	/**
	 * Events list.
	 *
	 * @since    1.0.0
	 * @access   Public
	 * @var      array    $events    Events list.
	 */				
	public $events	= array();

	/**
	 * Events Children titles.
	 * @since    1.0.0
	 * @access   Public
	 * @var      array    $eventsAndTitles    Events list.
	 */	
	public $eventsAndTitles = array();																				# Event Key and Event Title 
	
	/**
	 * WooCommerce Order Statuses.
	 * @since    1.0.0
	 * @access   Public
	 * @var      array    $active_plugins     List of active plugins .
	*/	
	public $wooCommerceOrderStatuses  = array();

	/**
	 * List of active plugins.
	 * @since    1.0.0
	 * @access   Public
	 * @var      array    $active_plugins     List of active plugins .
	*/	
	public $active_plugins  = array();


	# Class Constrictors 
	public function __construct( $plugin_name, $version, $googleSheet ) {
		# Plugin Name
		$this->plugin_name 	= $plugin_name;
		# WPGSI version 
		$this->version 		= $version;
		# Events
		$this->googleSheet 	= $googleSheet;
	}

	# Register the stylesheets for the admin area.
	public function wpgsi_enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wpgsi-admin.css', array(), $this->version, 'all' );
	}

	# Register the JavaScript for the admin area.
	public function wpgsi_enqueue_scripts() {

		# ============================= 3.4.0 starts =================================
		# Limit The Code only For WPGSI Page So that It will Not slow the Process
		if ( get_current_screen()->id == 'toplevel_page_wpgsi' ) {
			
			# +++++++++++++++++++++++++++++++ Below code should Fix ++++++++++++++++++++++++++++++++++++++++++++
			# There are come Default function for This, So Why Custom  Thing

			# Set date 
			# Current Date 
			$date_format 	= get_option( 'date_format' );
			$this->Date		= ( $date_format ) ? current_time( $date_format  ) : current_time( 'd/m/Y' );
			# Current Time 
			$time_format 	= get_option( 'time_format' );
			$this->Time		= ( $date_format ) ? current_time( $time_format  ) : current_time( 'g:i a' );
			# Active Plugins, Checking Active And Inactive Plugin 
			$this->active_plugins = get_option( 'active_plugins');		
			
			# ++++++++++++++++++++++++++++++ below Code also Should Change as you see Custom Order Status will not Display +++++++++++++++++++
			# WooCommerce order Statuses 
			if ( function_exists ( "wc_get_order_statuses" ) ) {
				$woo_order_statuses =  wc_get_order_statuses();
				# for Woocommerce New orders;
				$this->wooCommerceOrderStatuses['wc-new_order']  =  'WooCommerce New Checkout Page Order';
				# For Default Status
				foreach ( $woo_order_statuses as $key => $value ) {
					$this->wooCommerceOrderStatuses[ $key ]  =  'WooCommerce ' . $value;
				}
			} else {
				# If Function didn't exist do it 
				$this->wooCommerceOrderStatuses = array(
					"wc-new_order"	=> "WooCommerce New Checkout Page Order",
					"wc-pending"	=> "WooCommerce Order Pending payment",
					"wc-processing"	=> "WooCommerce Order Processing",
					"wc-on-hold"	=> "WooCommerce Order On-hold",
					"wc-completed"	=> "WooCommerce Order Completed",
					"wc-cancelled"	=> "WooCommerce Order Cancelled",
					"wc-refunded"	=> "WooCommerce Order Refunded",
					"wc-failed"		=> "WooCommerce Order Failed",
				);
			}

			# User Starts
			# wordpress user events 
			$wordpressUserEvents =  array( 
				"wordpress_newUser" 			=> 'Wordpress New User', 
				"wordpress_UserProfileUpdate" 	=> 'Wordpress User Profile Update', 
				"wordpress_deleteUser" 			=> 'Wordpress Delete User',
				"wordpress_userLogin" 			=> 'Wordpress User Login', 
				"wordpress_userLogout" 			=> 'Wordpress User Logout',
			);

			# Inserting User Events to All Events 
			$this->events += $wordpressUserEvents ;

			# New Code for User 
			foreach ( $wordpressUserEvents as $key => $value ) {
				# This is For Free User 
				if ( ! wpgsi_fs()->can_use_premium_code() ) {
					$this->eventsAndTitles[$key] = array(
						"userID" 				=> "User ID",
						"userName" 				=> "User Name",
						"firstName" 			=> "User First Name",
						"lastName" 				=> "User Last Name",
						"nickname" 				=> "User Nickname",
						"displayName" 			=> "User Display Name",
						"eventName" 			=> "Event Name",
						"description" 			=> "User Description",
						"userEmail" 			=> "User Email",
						"userRegistrationDate" 	=> "User Registration Date",
						"userRole"				=> "User Role",
						#
						"site_time"				=> "Site Time",
						"site_date"				=> "Site Date",
					);
				}

				# This is For Paid User 
				if ( wpgsi_fs()->is__premium_only() ) {
					if ( wpgsi_fs()->can_use_premium_code() ) {
						$this->eventsAndTitles[$key] = array(
							"userID" 				=> "User ID",
							"userName" 				=> "User Name",
							"firstName" 			=> "User First Name",
							"lastName" 				=> "User Last Name",
							"nickname" 				=> "User Nickname",
							"displayName" 			=> "User Display Name",
							"eventName" 			=> "Event Name",
							"description" 			=> "User Description",
							"userEmail" 			=> "User Email",
							"userRegistrationDate" 	=> "User Registration Date",
							"userRole"				=> "User Role",
							#
							"site_time"				=> "Site Time",
							"site_date"				=> "Site Date",
							# New Code Starts From Here 
							#++++++++++++++++++++++++++++++++++++
							"user_date_year" 		=> "Year of the Date",
							"user_date_month"		=> "Month of the Date",
							"user_date_date" 		=> "Date of the Date",
							"user_date_time" 		=> "Time of the Date",
							#+++++++++++++++++++++++++++++++++++++
							# New Code Ends Here 
						);
					}
				}
		
				if ( $key == 'wordpress_userLogin' ){
					$this->eventsAndTitles[$key]["userLogin"] 		= "Logged in ";
					$this->eventsAndTitles[$key]["userLoginTime"] 	= "Logged in Time";
					$this->eventsAndTitles[$key]["userLoginDate"] 	= "Logged in Date";
				}

				if ( $key == 'wordpress_userLogout' ){
					$this->eventsAndTitles[$key]["userLogout"] 		= "User Logout";
					$this->eventsAndTitles[$key]["userLogoutTime"] 	= "Logout Time";
					$this->eventsAndTitles[$key]["userLogoutDate"] 	= "Logout Date";
				}

				if ( wpgsi_fs()->is__premium_only() ) {
					if ( wpgsi_fs()->can_use_premium_code() ) {
						# For user Meta 
						$usersMeta = $this->wpgsi_users_metaKeys();
						if ( $usersMeta[0]  && ! empty( $usersMeta[1] ) && wpgsi_fs()->can_use_premium_code() ) {
							# Looping comment Meta 
							foreach ( $usersMeta[1] as $metaKey ) {
								$this->eventsAndTitles[ $key ][$metaKey] = "User Meta  " . $metaKey;
							}
						}
					}
				}
			}

			# Post Event array 
			$wordpressPostEvents = array(
				'wordpress_newPost'		  => 'Wordpress New Post',
				'wordpress_editPost'	  => 'Wordpress Edit Post',
				'wordpress_deletePost'	  => 'Wordpress Delete Post',
				'wordpress_page'		  => 'Wordpress Page',
			);

			# Inserting WP Post Events to All Events 
			$this->events += $wordpressPostEvents;

			# post loop 
			foreach( $wordpressPostEvents as $key => $value ){
				# setting wordpress_page profile update events
				if ( $key != 'wordpress_page' ){
					# This is For Free User 
					if ( ! wpgsi_fs()->can_use_premium_code() ) {
						$this->eventsAndTitles[$key] = array(
							"postID" 			=> "Post ID",
							"post_authorID"		=> "Post Author ID",
							"authorUserName"	=> "Post Author User name",
							"authorDisplayName"	=> "Post Author Display Name",
							"authorEmail"		=> "Post Author Email",
							"authorRole"		=> "Post Author Role",

							"post_title" 		=> "Post Title",
							"post_date" 		=> "Post Date",
							"post_date_gmt" 	=> "Post Date GMT",

							"post_content" 		=> "Post Content",
							"post_excerpt" 		=> "Post Excerpt",
							"post_status" 		=> "Post Status",
							"eventName" 		=> "Event Name",
							"comment_status" 	=> "Comment Status",
							"ping_status" 		=> "Ping Status",
							"post_password" 	=> "Post Password",
							"post_name" 		=> "Post Name",
							"to_ping" 			=> "To Ping",
							"pinged" 			=> "Pinged",
							"post_modified" 	=> "Post Modified Date",
							"post_modified_gmt" => "Post Modified GMT",

							"post_parent" 		=> "Post Parent",
							"guid" 				=> "Guid",
							"menu_order" 		=> "Menu Order",
							"post_type" 		=> "Post Type",
							"post_mime_type" 	=> "Post Mime Type",
							"comment_count" 	=> "Comment Count",
							"filter" 			=> "Filter",
							#
							"site_time"			=> "Site Time",
							"site_date"			=> "Site Date",
						);
					}

					# This For paid User 
					if ( wpgsi_fs()->is__premium_only() ) {
						if ( wpgsi_fs()->can_use_premium_code() ) {
							$this->eventsAndTitles[$key] = array(
								"postID" 			=> "Post ID",
								"post_authorID"		=> "Post Author ID",
								"authorUserName"	=> "Post Author User name",
								"authorDisplayName"	=> "Post Author Display Name",
								"authorEmail"		=> "Post Author Email",
								"authorRole"		=> "Post Author Role",

								"post_title" 		=> "Post Title",
								"post_date" 		=> "Post Date",
								"post_date_gmt" 	=> "Post Date GMT",
								#
								"site_time"			=> "Site Time",
								"site_date"			=> "Site Date",

								# New Code Starts From Here 
								#++++++++++++++++++++++++++++++++++++
								"post_date_year" 	=> "Post on Year",
								"post_date_month"	=> "Post on Month",
								"post_date_date" 	=> "Post on Date",
								"post_date_time" 	=> "Post on Time",
								#+++++++++++++++++++++++++++++++++++++
								# New Code Ends Here 

								"post_content" 		=> "Post Content",
								"post_excerpt" 		=> "Post Excerpt",
								"post_status" 		=> "Post Status",
								"eventName" 		=> "Event Name",
								"comment_status" 	=> "Comment Status",
								"ping_status" 		=> "Ping Status",
								"post_password" 	=> "Post Password",
								"post_name" 		=> "Post Name",
								"to_ping" 			=> "To Ping",
								"pinged" 			=> "Pinged",
								"post_modified" 	=> "Post Modified Date",
								"post_modified_gmt" => "Post Modified GMT",

								# New Code Starts From Here 
								#++++++++++++++++++++++++++++++++++++
								"post_modified_year" 	=> "Post modified Year",
								"post_modified_month"	=> "Post modified Month",
								"post_modified_date" 	=> "Post modified Date",
								"post_modified_time" 	=> "Post modified Time",
								#+++++++++++++++++++++++++++++++++++++
								# New Code Ends Here 

								"post_parent" 		=> "Post Parent",
								"guid" 				=> "Guid",
								"menu_order" 		=> "Menu Order",
								"post_type" 		=> "Post Type",
								"post_mime_type" 	=> "Post Mime Type",
								"comment_count" 	=> "Comment Count",
								"filter" 			=> "Filter",
							);
						}
					}

					if ( wpgsi_fs()->is__premium_only() ) {
						if ( wpgsi_fs()->can_use_premium_code() ) {
							# For Post Meta 
							$postsMeta = $this->wpgsi_posts_metaKeys();
							if ( $postsMeta[0]  && ! empty( $postsMeta[1] ) && wpgsi_fs()->can_use_premium_code() ){
								# Looping comment Meta 
								foreach ( $postsMeta[1] as $metaKey ) {
									$this->eventsAndTitles[ $key ][$metaKey] = "Post Meta  " . $metaKey;
								}	
							}
						}
					}
				}

				if ( $key == 'wordpress_page' ){
					
					$this->eventsAndTitles[$key] = array(
						"postID" 				=> "Page ID",
						"post_authorID"			=> "Page Author ID",
						"authorUserName"		=> "Page Author User name",
						"authorDisplayName"		=> "Page Author Display Name",
						"authorEmail"			=> "Page Author Email",
						"authorRole"			=> "Page Author Role",

						"post_title" 			=> "Page Title",
						"post_date" 			=> "Page Date",
						"post_date_gmt" 		=> "Page Date GMT",
						#
						"site_time"				=> "Site Time",
						"site_date"				=> "Site Date",
						
						# New Code Starts From Here 
						#+++++++++++++++++++++++++++++++++++++
						"post_date_year" 		=>	"Page on Year",
						"post_date_month"		=>	"Page on Month",
						"post_date_date" 		=>	"Page on Date",
						"post_date_time" 		=>	"Page on Time",
						#++++++++++++++++++++++++++++++++++++++
						# New Code Ends Here 

						"post_content" 			=> "Page Content",
						"post_excerpt" 			=> "Page Excerpt",
						"post_status" 			=> "Page Status",
						"eventName" 			=> "Event Name",
						"comment_status" 		=> "Comment Status",
						"ping_status" 			=> "Ping Status",
						"post_password" 		=> "Page Password",
						"post_name" 			=> "Page Name",
						"to_ping" 				=> "To Ping",
						"pinged" 				=> "Pinged",
						"post_modified" 		=> "Page Modified",
						"post_modified_gmt" 	=> "Page Modified GMT",

						# New Code Starts From Here 
						#++++++++++++++++++++++++++++++++++++
						"post_modified_year" 	=> "Page modified Year",
						"post_modified_month"	=> "Page modified Month",
						"post_modified_date" 	=> "Page modified Date",
						"post_modified_time" 	=> "Page modified Time",
						#+++++++++++++++++++++++++++++++++++++
						# New Code Ends Here 

						"post_parent" 			=> "Page Parent",
						"guid" 					=> "Guid",
						"menu_order" 			=> "Menu Order",
						"post_type" 			=> "Page Type",
						"post_mime_type" 		=> "Page Mime Type",
						"comment_count" 		=> "Comment Count",
						"filter" 				=> "Filter",
					);

					if ( wpgsi_fs()->is__premium_only() ) {
						if ( wpgsi_fs()->can_use_premium_code() ) {
							# For page Meta 
							$pagesMeta = $this->wpgsi_pages_metaKeys();
							if ( $pagesMeta[0]  && ! empty( $pagesMeta[1] ) && wpgsi_fs()->can_use_premium_code() ){
								# Looping comment Meta 
								foreach ( $pagesMeta[1] as $metaKey ) {
									$this->eventsAndTitles[ $key ][$metaKey] = "Page Meta  " . $metaKey;
								}	
							}
						}
					}
				}
			} # Loop Ends 

			# Comment Starts
			$wordpressCommentEvents = array(
				'wordpress_comment'		  => 'Wordpress Comment',
				'wordpress_edit_comment'  => 'Wordpress Edit Comment',
			);

			# Inserting comment Events to All Events 
			$this->events += $wordpressCommentEvents;

			# setting wordpress comments events
			foreach ( $wordpressCommentEvents as $key => $value ) {
				# For Free User 
				if ( ! wpgsi_fs()->can_use_premium_code() ) {
					$this->eventsAndTitles[ $key ] = array(
						"comment_ID" 				=> "Comment ID",
						"comment_post_ID" 			=> "Comment Post ID",
						"comment_author"			=> "Comment Author",
						"comment_author_email" 		=> "Comment Author Email",
						"comment_author_url" 		=> "Comment Author Url",
						"comment_content" 			=> "Comment Content",
						"comment_type" 				=> "Comment Type",
						"user_ID" 					=> "Comment User ID",
						"comment_author_IP" 		=> "Comment Author IP",
						"comment_agent" 			=> "Comment Agent",
						"comment_date" 				=> "Comment Date",
						"comment_date_gmt" 			=> "Comment Date GMT",
					
						"filtered" 					=> "Filtered",
						"comment_approved" 			=> "Comment Approved",
						#
						"site_time"					=> "Site Time",
						"site_date"					=> "Site Date",
					);
				}

				# For Paid User 
				if ( wpgsi_fs()->is__premium_only() ) {
					if ( wpgsi_fs()->can_use_premium_code() ) {
						$this->eventsAndTitles[ $key ] = array(
							"comment_ID" 			=> "Comment ID",
							"comment_post_ID" 		=> "Comment Post ID",
							"comment_author"		=> "Comment Author",
							"comment_author_email" 	=> "Comment Author Email",
							"comment_author_url" 	=> "Comment Author Url",
							"comment_content" 		=> "Comment Content",
							"comment_type" 			=> "Comment Type",
							"user_ID" 				=> "Comment User ID",
							"comment_author_IP" 	=> "Comment Author IP",
							"comment_agent" 		=> "Comment Agent",
							"comment_date" 			=> "Comment Date",
							"comment_date_gmt" 		=> "Comment Date GMT",
							#
							"site_time"				=> "Site Time",
							"site_date"				=> "Site Date",
							# New Code Starts From Here 
							#+++++++++++++++++++++++++++++
							"year_of_comment" 		=> "Year of the Comment",
							"month_of_comment"		=> "Month of the Comment",
							"date_of_comment" 		=> "Date of the Comment",
							"time_of_comment" 		=> "Time of the Comment",
							#+++++++++++++++++++++++++++++
							# New Code Ends Here 
							"filtered" 				=> "Filtered",
							"comment_approved" 		=> "Comment Approved",
						);
					}
				}
			} # Loop ends Here 

			if ( wpgsi_fs()->is__premium_only() ) {
				if ( wpgsi_fs()->can_use_premium_code() ) {
					# For Comment Meta 
					$commentsMeta = $this->wpgsi_comments_metaKeys();
					if ( $commentsMeta[0]  && ! empty( $commentsMeta[1] ) && wpgsi_fs()->can_use_premium_code__premium_only() ){
						# Looping the comment event 
						foreach ( $wordpressCommentEvents as $key => $value ) {
							# Looping comment Meta 
							foreach ( $commentsMeta[1] as $metaKey ) {
								$this->eventsAndTitles[ $key ][$metaKey] = "Comment Meta  " . $metaKey;
							}	
						}
					}
				}
			}

			# Woocommerce 
			if( in_array('woocommerce/woocommerce.php' , $this->active_plugins) ) {
				# Woo product  Starts 
				# WooCommerce Product Event Array 
				$wooCommerceProductEvents 		= array(
					'wc-new_product'			=> 'WooCommerce New Product',
					'wc-edit_product'			=> 'WooCommerce Update Product',
					'wc-delete_product'			=> 'WooCommerce Delete Product',
				);

				# Inserting WooCommerce product Events to All Events 
				$this->events += $wooCommerceProductEvents;

				# WooCommerce Products 
				foreach ( $wooCommerceProductEvents as $key => $value) {
					# Default fields
					$this->eventsAndTitles[ $key ]	= array(
						"productID"			=> "Product ID",
						"type"				=> "Type",
						"name"				=> "Name",
						"slug"				=> "Slug",
						"date_created"		=> "Date created",
						"date_modified"		=> "Date modified",
						# Get Product Prices
						
						# Get Product Tax, Shipping & Stock
						
						# Get Product Dimensions
						"weight"			=> "Weight",
						"length"			=> "Length",
						"width"				=> "Width",
						"height"			=> "Height",
						"attributes"		=> "Attributes",
						"default_attributes"=> "Default attributes",
						"category_ids"		=> "Category ids",
						"tag_ids"			=> "Tag ids",
						"image_id"			=> "Image id",
						"gallery_image_ids"	=> "Gallery image ids",
						#
						"site_time"			=> "Site Time",
						"site_date"			=> "Site Date",
					);
					
					# freemius 
					if( wpgsi_fs()->is__premium_only() ) {
						if( wpgsi_fs()->can_use_premium_code() ) {
							$this->eventsAndTitles[ $key ]	= array(
								"productID"					=> "Product ID",
								"type"						=> "Type",
								"name"						=> "Name",
								"slug"						=> "Slug",
								"date_created"				=> "Date created",
								"date_modified"				=> "Date modified",
								
								# New Code Starts Here 
								#++++++++++++++++++++++++++++++++++++++
								"date_created_year"	 		=>	"Created on Year",
								"date_created_month" 		=>	"Created on Month",
								"date_created_date"	 		=>	"Created on Date",
								"date_created_time"	 		=>	"Created on Time",
								# 
								"date_modified_year" 		=>	"Modified on Year",
								"date_modified_month"		=>	"Modified on Month",
								"date_modified_date" 		=>	"Modified on Date",
								"date_modified_time" 		=>	"Modified on Time",
								#
								"site_time"			 		=> "Site Time",
								"site_date"			 		=> "Site Date",
								#++++++++++++++++++++++++++++++++++++++
								# New Code Ends Here 

								"status"			 		=> "Status",
								"eventName"			 		=> "Event name",
								"featured"			 		=> "Featured",
								"catalog_visibility" 		=> "Catalog visibility",
								"description"		 		=> "Description",
								"short_description"	 		=> "Short description",
								"sku"				 		=> "SKU",
								"menu_order"		 		=> "Menu order",
								"virtual"			 		=> "Virtual",
								"permalink"			 		=> "Permalink",
								# Get Product Prices
								"price"				 		=> "Price",
								"regular_price"		 		=> "Regular price",
								"sale_price"		 		=> "Sale price",
								"date_on_sale_from"	 		=> "Date on sale from",
								"date_on_sale_to"	 		=> "Date on sale to",
								"total_sales"		 		=> "Total sales",
								# Get Product Tax, Shipping & Stock
								"tax_status"		 		=> "Tax status",
								"tax_class"			 		=> "Tax class",
								"manage_stock"		 		=> "Manage stock",
								"stock_quantity"	 		=> "Stock quantity",
								"stock_status"		 		=> "Stock status",
								"backorders"		 		=> "Back orders",
								"sold_individually"	 		=> "Sold individually",
								"purchase_note"		 		=> "Purchase note",
								# Get Product Dimensions
								"shipping_class_id"	 		=> "Shipping class id",
								"weight"			 		=> "Weight",
								"length"			 		=> "Length",
								"width"				 		=> "Width",
								"height"			 		=> "Height",
								"attributes"		 		=> "Attributes",
								"default_attributes" 		=> "Default attributes",
								"category_ids"		 		=> "Category ids",
								"tag_ids"			 		=> "Tag ids",
								"image_id"			 		=> "Image id",
								"image"				 		=> "Image",
								"gallery_image_ids"	 		=> "Gallery image ids",
								"get_attachment_image_url"	=> "image url",
							);
						}
					}
				}
				#Product status Loop ends here

				if ( wpgsi_fs()->is__premium_only() ) {
					if( wpgsi_fs()->can_use_premium_code() ) {
						# For WooCommerce Product Meta to the product  event
						$productsMeta = $this->wpgsi_wooCommerce_product_metaKeys();
						# Check and Balance & Premium Code only 
						if ( $productsMeta[0]  && ! empty( $productsMeta[1] ) && wpgsi_fs()->can_use_premium_code() ){
							# Looping the WooCommerce Product Event
							foreach ( $wooCommerceProductEvents as $key => $value) {
								# Looping comment Meta 
								foreach ( $productsMeta[1] as $metaKey ) {
									$this->eventsAndTitles[ $key ][$metaKey] = "Product Meta  " . $metaKey;
								}	
							}
						}
					}
				}
				
				# Inserting WooCommerce Order Events to All Events 
				$this->events += $this->wooCommerceOrderStatuses;

				# +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				#(1) Product Meta 
				#(2) Product Info
				#(3) Product Details
				#(4) Empty Product Place Holder 
				# +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

				# WooCommerce Orders 
				foreach ( $this->wooCommerceOrderStatuses as $key => $value) {
					# Default fields
					$this->eventsAndTitles[ $key ]	= array(
						"orderID"						=>	"Order ID",
						# Billing Information
						"billing_first_name"			=>	"Billing first name",	
						"billing_last_name"				=>	"Billing last name",
						"billing_company"				=>	"Billing company",
						"billing_address_1"				=>	"Billing address 1",
						"billing_address_2"				=>	"Billing address 2",
						"billing_city"					=>	"Billing city",
						"billing_state"					=>	"Billing state",
						"billing_postcode"				=>	"Billing postcode",
						# Shipping Information
						"shipping_first_name"			=>	"Shipping first name",
						"shipping_last_name"			=>	"Shipping last name",	
						"shipping_company"				=>	"Shipping company",
						"shipping_address_1"			=>	"Shipping address 1",
						"shipping_address_2"			=>	"Shipping address 2",
						"shipping_city"					=>	"Shipping city",
						"shipping_state"				=>	"Shipping state",
						"shipping_postcode"				=>	"Shipping postcode",
						#
						"site_time"			 			=> "Site Time",
						"site_date"			 			=> "Site Date",
						# Developer defined 
						"status"						=>	"Status",	
						"eventName"						=>	"Event name",		
					);

					# freemius 
					if ( wpgsi_fs()->is__premium_only() ) {
						if ( wpgsi_fs()->can_use_premium_code() ){
							$this->eventsAndTitles[ $key ]	= array(
								"orderID"							=>	"Order ID",
								"cart_tax"							=>	"Cart tax",
								"currency"							=>	"Currency",
								"discount_tax"						=>	"Discount tax",
								"discount_total"					=>	"Discount total",
								"fees"								=>	"Fees",
								"shipping_tax"						=>	"Shipping tax",	
								"shipping_total"					=>	"Shipping total",
								"subtotal"							=>	"Subtotal",
								"subtotal_to_display"				=>	"Subtotal to display",
								"tax_totals"						=>	"Tax totals",
								"taxes"								=>	"Taxes",
								"total"								=>	"Total",
								"total_discount"					=>	"Total discount",
								"total_tax"							=>	"Total tax",
								"total_refunded"					=>	"Total refunded",
								"total_tax_refunded"				=>	"Total tax refunded",
								"total_shipping_refunded"			=>	"Total shipping refunded",
								"item_count_refunded"				=>	"Item count refunded",
								"total_qty_refunded"				=>	"Total qty refunded",
								"remaining_refund_amount"			=>	"Remaining refund amount",
								# items Details 
								# ********************************************************************
								"items"								=>	"Items",
								"get_product_id"					=>	"Items id",
								"get_name"							=>	"Items name",
								"get_quantity"						=>	"Items quantity",
								"get_total"							=>	"Items total",
								"get_sku"		 					=>	"Items sku",	
								"get_type"	   						=>	"Items type",
								"get_slug"							=>	"Items slug",
								"get_price"							=>	"Items price",
								"get_regular_price"					=>	"Items regular_price",
								"get_sale_price"					=>	"Items sale_price", 
								"get_virtual" 						=>	"Items virtual",
								"get_permalink"						=>	"Items permalink",
								"get_featured"						=>	"Items featured",
								"get_status"						=>	"Items status",
								"get_tax_status" 					=>	"Items tax_status",
								"get_tax_class"						=>	"Items tax_class",
								"get_manage_stock"					=>	"Items manage_stock",
								"get_stock_quantity"				=>	"Items stock_quantity",
								"get_stock_status"					=>	"Items stock_status",
								"get_backorders"					=>	"Items backorders",
								"get_sold_individually"				=>	"Items sold individually",
								"get_purchase_note"					=>	"Items purchase note",
								"get_shipping_class_id"				=>	"Items shipping class id",
								"get_weight"		 				=>	"Items weight",
								"get_length"	 					=>	"Items length",
								"get_width"	 						=>	"Items width",
								"get_height"		 				=>	"Items height",
								"get_default_attributes"			=>	"Items default attributes",
								"get_category_ids"					=>	"Items category ids",
								"get_tag_ids" 						=>	"Items tag ids",
								"get_image_id"	 					=>	"Items image id",
								"get_gallery_image_ids"				=>	"Items gallery image ids",
								"get_attachment_image_url"			=>	"Items attachment image url",
								# ********************************************************************
								"item_count"						=>	"Item count",
								"downloadable_items"				=>	"Downloadable items",
								# customer Details
								"customer_id"						=>	"Customer id",
								"user_id"							=>	"User id",	
								"user"								=>	"User",
								"customer_ip_address"				=>	"Customer ip address",
								"customer_user_agent"				=>	"Customer user agent",
								"created_via"						=>	"Created via",
								"customer_note"						=>	"Customer note",
								# Order Date 
								"date_created"						=>	"Date created",
								"date_modified"						=>	"Date modified",
								"date_completed"					=>	"Date completed",
								"date_paid"							=>	"Date paid",
								# New Code Starts  
								# +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
								"date_created_year"					=>	"Created on year",
								"date_created_month"				=>	"Created on Month",
								"date_created_date"					=>	"Created on date",
								"date_created_time"					=>	"Created on time",
								
								"date_modified_year"				=>	"Modified on year",
								"date_modified_month"				=>	"Modified on Month",
								"date_modified_date"				=>	"Modified on date",
								"date_modified_time"				=>	"Modified on time",
								
								"date_completed_year"				=>	"Completed on year",
								"date_completed_month"				=>	"Completed on Month",
								"date_completed_date"				=>	"Completed on date",
								"date_completed_time"				=>	"Completed on time",

								"date_paid_year"					=>	"Paid on year",
								"date_paid_month"					=>	"Paid on Month",
								"date_paid_date"					=>	"Paid on date",
								"date_paid_time"					=>	"Paid on time",
								# +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
								# New Code Starts  

								# Billing Information
								"billing_first_name"			=>	"Billing first name",
								"billing_last_name"				=>	"Billing last name",
								"billing_company"				=>	"Billing company",
								"billing_address_1"				=>	"Billing address 1",
								"billing_address_2"				=>	"Billing address 2",
								"billing_city"					=>	"Billing city",
								"billing_state"					=>	"Billing state",
								"billing_postcode"				=>	"Billing postcode",
								"billing_country"				=>	"Billing country",
								"billing_email"					=>	"Billing email",
								"billing_phone"					=>	"Billing phone",
								# Shipping method 
								"shipping_method"				=>	"Shipping method",
								# Shipping Information  
								"shipping_first_name"			=>	"Shipping first name",
								"shipping_last_name"			=>	"Shipping last name",	
								"shipping_company"				=>	"Shipping company",
								"shipping_address_1"			=>	"Shipping address 1",
								"shipping_address_2"			=>	"Shipping address 2",
								"shipping_city"					=>	"Shipping city",
								"shipping_state"				=>	"Shipping state",
								"shipping_postcode"				=>	"Shipping postcode",
								"shipping_country"				=>	"Shipping country",
								"address"						=>	"Address",
								"shipping_address_map_url"		=>	"Shipping address map url",
								"formatted_billing_full_name"	=>	"Formatted billing full name",
								"formatted_shipping_full_name"	=>	"Formatted shipping full name",
								"formatted_billing_address"		=>	"Formatted billing address",	
								"formatted_shipping_address"	=>	"Formatted shipping address",
								# Payment methods
								"payment_method"				=>	"Payment method",
								"payment_method_title"			=>	"Payment method title",
								"transaction_id"				=>	"Transaction id",
								# URLS
								"checkout_payment_url"			=>	"Checkout payment url",
								"checkout_order_received_url"	=>	"Checkout order received url",
								"cancel_order_url"				=>	"Cancel order url",
								"cancel_order_url_raw"			=>	"Cancel order url raw",
								"cancel_endpoint"				=>	"Cancel endpoint",
								"view_order_url"				=>	"View order url",
								"edit_order_url"				=>	"Edit order url",
								# 
								"status"						=>	"Status",	
								"eventName"						=>	"Event name",			
							);
						}
					}
				}
				# main Order status Loop ends here 

				if ( wpgsi_fs()->is__premium_only() ) {
					if ( wpgsi_fs()->can_use_premium_code() ) {
						# **************************** Items Meta ****************************
						# For WooCommerce order item Meta.
						$itemsMeta = $this->wpgsi_wooCommerce_product_metaKeys();
						if ( $itemsMeta[0]  && ! empty( $itemsMeta[1] ) ) {
							# Looping the WooCommerce Product Event
							foreach (  $this->wooCommerceOrderStatuses as $key => $value) {
								# Looping comment Meta 
								foreach ( $itemsMeta[1] as $metaKey ) {
									$this->eventsAndTitles[ $key ][$metaKey] = "Items Meta  " . $metaKey;
								}	
							}
						}

						# For WooCommerce Order Meta Data insert to the order Events
						$ordersMeta = $this->wpgsi_wooCommerce_order_metaKeys();
						# Check and Balance & Premium Code only 
						if ( $ordersMeta[0]  && ! empty( $ordersMeta[1] ) && wpgsi_fs()->can_use_premium_code__premium_only() ) {
							# Looping the WooCommerce Product Event
							foreach (  $this->wooCommerceOrderStatuses as $key => $value) {
								# Looping comment Meta s
								foreach ( $ordersMeta[1] as $metaKey ) {
									$this->eventsAndTitles[ $key ][$metaKey] = "Order Meta  " . $metaKey;
								}
							}
						}
					}
				}
			}

			# Below are Contact forms 
			# Contact Form 7
			$cf7 = $this->cf7_forms_and_fields();
			if ( $cf7[0] ) {
				foreach ( $cf7[1] as $form_id => $form_name ) {
					$this->events[ $form_id ] =  $form_name;		
				}

				foreach ( $cf7[2] as $form_id => $fields_array ) {
					$this->eventsAndTitles[ $form_id ] = $fields_array; 			
				}
			}

			# For Ninja Form 
			$ninja =  $this->ninja_forms_and_fields();
			if ( $ninja[0] ){
				foreach ( $ninja[1] as $form_id => $form_name ) {
					$this->events[ $form_id ] = $form_name;		
				}

				foreach ( $ninja[2] as $form_id => $fields_array ) {
					$this->eventsAndTitles[ $form_id ] = $fields_array; 			
				}
			}

			# formidable form 
			$formidable =  $this->formidable_forms_and_fields();
			if ( $formidable[0] ){
				foreach ( $formidable[1] as $form_id => $form_name ) {
					$this->events[$form_id ] = $form_name;		
				}

				foreach ( $formidable[2] as $form_id => $fields_array ) {
					$this->eventsAndTitles[$form_id ] = $fields_array; 			
				}
			}

			# wpforms-lite/wpforms.php
			$wpforms  =  $this->wpforms_forms_and_fields();
			if ( $wpforms[0] ){
				foreach ( $wpforms[1] as $form_id => $form_name ) {
					$this->events[$form_id ] = $form_name;		
				}

				foreach ( $wpforms[2] as $form_id => $fields_array ) {
					$this->eventsAndTitles[$form_id ] = $fields_array; 			
				}
			}

			# weforms/weforms.php
			$weforms  =  $this->weforms_forms_and_fields();
			if ( $weforms[0] ){
				foreach ( $weforms[1] as $form_id => $form_name ) {
					$this->events[$form_id ] = $form_name;		
				}

				foreach ( $weforms[2] as $form_id => $fields_array ) {
					$this->eventsAndTitles[$form_id ] = $fields_array; 			
				}
			}

			# gravity forms/gravity forms.php
			$gravityForms  =  $this->gravity_forms_and_fields();
			if ( $gravityForms[0] ){
				foreach ( $gravityForms[1] as $form_id => $form_name ) {
					$this->events[$form_id ] = $form_name;		
				}

				foreach ( $gravityForms[2] as $form_id => $fields_array ) {
					$this->eventsAndTitles[$form_id ] = $fields_array; 			
				}
			}

			# forminator forminator/forminator.php
			$forminatorForms  =  $this->forminator_forms_and_fields();
			if ( $forminatorForms[0] ){
				foreach ( $forminatorForms[1] as $form_id => $form_name ) {
					$this->events[$form_id ] = $form_name;		
				}

				foreach ( $forminatorForms[2] as $form_id => $fields_array ) {
					$this->eventsAndTitles[$form_id ] = $fields_array; 			
				}
			}


			if ( wpgsi_fs()->is__premium_only() ) {
				if ( wpgsi_fs()->can_use_premium_code() ) {
					# Adding CPT Events and Fields 
					$CptEvents = $this->wpgsi_allCptEvents();
					# Check and Balance 
					if ( $CptEvents[0] ) {
						# Adding events to main events array 
						$this->events += $CptEvents[2];
						# Looping the Custom post type Event
						foreach ( $CptEvents[2] as $key => $value) {
							# Looping comment Meta 
							foreach ( $CptEvents[3] as $cptDataFieldID => $cptDataFieldName  ) {
								# Adding event data fields 
								$this->eventsAndTitles[ $key ][ $cptDataFieldID ] = $cptDataFieldName;
							}
						}
					}
				}
			}

		} # toplevel_page_wpgsi ends Here
		# ============================= 3.4.0 ends ==================================
		
		# Passing the Data To WPGSI Page 
		if ( get_current_screen()->id == 'toplevel_page_wpgsi' ) {
			
			wp_register_script( 'vue', plugin_dir_url( __FILE__ ) . 'js/vue.js', '', FALSE, FALSE );
			wp_enqueue_script( 'wpgsi-admin', plugin_dir_url( __FILE__ ) . 'js/wpgsi-admin.js', array('vue'), '0.1', TRUE );  
			
			if ( isset( $_GET["action"] , $_GET["id"] ) ){
				# getting the integration
				$Integration = $this->wpgsi_getIntegration( sanitize_text_field( $_GET["id"] ) );
				# if There is a integration
				if ( $Integration[0] ){
					
					$frontEnd = array( 
						"ajaxUrl"  				=> admin_url( 'admin-ajax.php' ),
						"CurrentPage" 			=> 'edit',  
						"DataSourceTitles" 		=> json_encode( $this->events ),
						"DataSourceFields" 		=> json_encode( $this->eventsAndTitles ),
						"IntegrationTitle"    	=> ( isset( $Integration[1]["IntegrationTitle"] ) )   ?	 $Integration[1]["IntegrationTitle"] 	 : '', 
						"DataSource"          	=> ( isset( $Integration[1]["DataSource"] ) ) 		  ?	 $Integration[1]["DataSource"] 			 : '',
						"DataSourceID"          => ( isset( $Integration[1]["DataSourceID"] ) ) 	  ?	 $Integration[1]["DataSourceID"] 		 : '', 
						"Worksheet"           	=> ( isset( $Integration[1]["Worksheet"] ) ) 		  ?	 $Integration[1]["Worksheet"] 			 : '', 
						"WorksheetID"         	=> ( isset( $Integration[1]["WorksheetID"] ) ) 		  ?	 $Integration[1]["WorksheetID"] 		 : '',
						"Spreadsheet"         	=> ( isset( $Integration[1]["Spreadsheet"] ) ) 		  ?	 $Integration[1]["Spreadsheet"] 		 : '', 
						"SpreadsheetID"       	=> ( isset( $Integration[1]["SpreadsheetID"] )) 	  ?	 $Integration[1]["SpreadsheetID"]		 : '', 
						"WorksheetColumnsTitle" => ( isset( $Integration[1]["WorksheetColumnsTitle"]))?  $Integration[1]["WorksheetColumnsTitle"]: '', 
						"Relations"				=> ( isset( $Integration[1]["Relations"] ) ) 		  ?	 $Integration[1]["Relations"] 			 : '',
						"GoogleSpreadsheets"	=> json_encode( $this->wpgsi_GoogleSpreadsheets()[1] ),
						'nonce' 				=> wp_create_nonce( 'wpgsiProNonce' ),
					);
				}
			} else {
				$frontEnd = array(
					"ajaxUrl"  					=> admin_url( 'admin-ajax.php' ),
					"CurrentPage" 				=> 'new',
					"DataSourceTitles" 			=> json_encode( $this->events ),
					"DataSourceFields" 			=> json_encode( $this->eventsAndTitles ),
					"GoogleSpreadsheets"		=> json_encode( $this->wpgsi_GoogleSpreadsheets()[1] ),
					'nonce' 					=> wp_create_nonce( 'wpgsiProNonce' ),
				);
			}

			# Localizing js data to the script
			if ( isset( $frontEnd ) && ! empty( $frontEnd ) ){
				wp_localize_script( 'wpgsi-admin', 'frontEnd', $frontEnd );   
			} else {
				$this->wpgsi_log( get_class( $this ), __METHOD__,"500","Error: frontEnd array is empty ! wp_localize_script has no data to Pass.");
			}
			
		} 
	}

	/**
	 * Admin menu init
	 * @since    	1.0.0
	 * @return 	   	array    Integrations details  .
	*/
	public function wpgsi_admin_menu(){
		add_menu_page( __( 'Spreadsheet Integrations', 'wpgsi' ), __( 'Spreadsheet Integrations', 'wpgsi' ),'manage_options','wpgsi', array( $this,'wpgsi_requestDispatcher' ),'dashicons-media-spreadsheet' );
	}

	/**
	 * URL routers for main landing Page 
	 * @since    	1.0.0
	 * @return 	   	array 		Integrations details  .
	*/
	public function wpgsi_requestDispatcher(){

		$action = isset( $_GET['action'] ) ? sanitize_text_field($_GET['action'])  	  : 'list';
		$id     = isset( $_GET['id'] )     ? intval(sanitize_text_field($_GET['id'])) : 0;
		# routing to the Pages
		switch ( $action ) {
			
			case 'new':
				$this->wpgsi_new_integration();													
				break;

		    case 'edit':
		    	( $id ) ? $this->wpgsi_edit_integration($id)  : $this->wpgsi_new_integration();	
				break;

		    case 'status':
		    	( $id ) ? $this->wpgsi_connection_status($id) : $this->wpgsi_connections();		
				break;

		    case 'delete':
		    	( $id ) ? $this->wpgsi_delete_connection($id) : $this->wpgsi_connections();		
				break;
				
			case 'columnTitle':
		    	( $id ) ? $this->wpgsi_columnTitle($id) 	  : $this->wpgsi_connections();		
				break;
				
			case 'updateFromSheet':
		    	( $id ) ? $this->wpgsi_update_wooProduct($id) : $this->wpgsi_update_wooProduct($id);		
				break;

		    default:
		        $this->wpgsi_connections();														
		    break;
		}
	}

	# comments;
	public function wpgsi_admin_notices( ){
		echo "<pre>";

		
		
		echo "</pre>";
	}
	
	/**
	 * Third party plugin :
	 * Checkout Field Editor ( Checkout Manager ) for WooCommerce
	 * BETA testing;
	 * @since    2.0.0
	*/
	public function wpgsi_woo_checkout_field_editor_pro_fields( ){
		
		$active_plugins 				= get_option( 'active_plugins');
		$woo_checkout_field_editor_pro 	= array();

		if( in_array('woo-checkout-field-editor-pro/checkout-form-designer.php', $active_plugins )){
			
			$a  = get_option( "wc_fields_billing" );
			$b  = get_option( "wc_fields_shipping" );
			$c  = get_option( "wc_fields_additional" );
			
			if ( $a ){
				foreach ( $a as $key => $field ) {
					if( isset( $field['custom'] ) &&  $field['custom'] == 1  ){
						$woo_checkout_field_editor_pro[ $key ]['type']  = $field['type'];
						$woo_checkout_field_editor_pro[ $key ]['name']  = $field['name'];
						$woo_checkout_field_editor_pro[ $key ]['label'] = $field['label'];
					}
				}
			}

			if ( $b ){
				foreach ( $b as $key => $field ) {
					if( isset( $field['custom'] ) &&  $field['custom'] == 1  ){
						$woo_checkout_field_editor_pro[ $key ]['type']  = $field['type'];
						$woo_checkout_field_editor_pro[ $key ]['name']  = $field['name'];
						$woo_checkout_field_editor_pro[ $key ]['label'] = $field['label'];
					}
				}
			}

			if ( $c ){
				foreach ( $c as $key => $field ) {
					if( isset( $field['custom'] ) &&  $field['custom'] == 1  ){
						$woo_checkout_field_editor_pro[ $key ]['type']  = $field['type'];
						$woo_checkout_field_editor_pro[ $key ]['name']  = $field['name'];
						$woo_checkout_field_editor_pro[ $key ]['label'] = $field['label'];
					}
				}
			}

		} else {
			return array( FALSE, "Error : Checkout Field Editor aka Checkout Manager for WooCommerce is not INSTALLED." );
		}

		if ( empty(  $woo_checkout_field_editor_pro ) ){
			return array( FALSE, "Error : Checkout Field Editor aka Checkout Manager for WooCommerce is EMPTY no Custom Field. " );
		} else {
			return array( TRUE, $woo_checkout_field_editor_pro );
		}	
	}
	
	/**
	 * Main Landing Page . List of Integrations
	 * @since    	1.0.0
	 * @return 	   	
	*/
	public function wpgsi_connections(){
		# Adding List table
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wpgsi-list-table.php';
		$credential = get_option( 'wpgsi_google_credential', FALSE );
		# Creating view Page layout 
		echo"<div class='wrap'>";
			# if credentials is empty; Show this message to create credential.
			if ( ! $credential  ){
				echo"<div class='notice notice-warning inline'>";
					echo"<p> Please integrate Google APIs & Service Account before creating new connection. Get <code><b><a href=" . admin_url('admin.php?page=wpgsi-settings&action=service-account-help') ." style='text-decoration: none;'> step-by-step</a></b></code> help. This plugin will not work without Google APIs & Service Account. </p>";
				echo"</div>";
			}

			echo "<h1 class='wp-heading-inline'> Integrations </h1>";
			echo "<a href=". admin_url( 'admin.php?page=wpgsi&action=new' ) . " class='page-title-action'>Add New Integration</a>";
			# Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions
	        echo"<form id='newIntegration' method='get'>";
           		# For plugins, we also need to ensure that the form posts back to our current page 
            	echo"<input type='hidden' name='page' value='". esc_attr( $_REQUEST['page'] ) ."' />";
            	echo"<input type='hidden' name='wpgsi_nonce' value='". wp_create_nonce( 'wpgsi_nonce_bulk_action' ) ."' />";
	            # Now we can render the completed list table 
				$wpgsi_table = new Wpgsi_List_Table( $this->eventsAndTitles );
				$wpgsi_table->prepare_items();
				$wpgsi_table->display();
			echo"</form>";
		echo"</div>";

		# Caching the integrations 
		$integrations =  $this->wpgsi_getIntegrations();
		if ( $integrations[0] ){
			# setting or updating the transient;
			set_transient( 'wpgsi_integrations', $integrations[1] );
		}
	}

	/**
	 * wpgsi Add new Connections  view page 
	 * @since    	1.0.0
	 * @return 	   	array 		Integrations details.
	*/
	public function wpgsi_new_integration(){
		if ( @fsockopen('www.google.com', 80) ) {
			require_once plugin_dir_path(dirname(__FILE__)).'admin/partials/wpgsi-new-integration-display.php';
		} else {
			$this->wpgsi_log( get_class( $this ), __METHOD__,"501","Error: No internet connection.");
			echo"<h3> No internet connection. Sorry ! you can't create a integrations now.</h3>";
			return array( FALSE, "Error: No internet connection." );
		}
	}

	/**
	 * Edit a Connection view page  
	 * @since    	1.0.0
	 * @return 	   	array 		Integrations details  .
	*/
	public function wpgsi_edit_integration( $id='' ){
		if ( @fsockopen( 'www.google.com', 80 ) ){
			require_once plugin_dir_path( dirname(__FILE__) ).'admin/partials/wpgsi-edit-integration-display.php';
		}else{
			$this->wpgsi_log( get_class( $this ), __METHOD__,"502","Error: No internet connection.");
			echo"<h3> No internet connection. Sorry ! you can't edit a integrations now. </h3>";
			return array( FALSE, "Error: No internet connection." );
		}
	}

	/**
	 * Getting Google Spreadsheets 
	 * @since    	1.0.0
	 * @return 	   	array    Integrations details.
	*/
	public function wpgsi_GoogleSpreadsheets(){
		# Internet Connection Testing .
		if ( ! @fsockopen( 'www.google.com', 80 ) ){
			$this->wpgsi_log( get_class( $this ), __METHOD__, "503", "Error: No internet connection !" );
			return array( FALSE, "Error: No internet connection !" );
		}

		# Token task Starts
	  	$credential 	= get_option( 'wpgsi_google_credential', FALSE );
		$google_token 	= get_option( 'wpgsi_google_token', FALSE );
		# Checking Token Validation
		if ( $google_token  &&  time() > $google_token['expires_in'] ) {
			# if Credentials & Not empty
			if ( $credential ) {
				$new_token = $this->googleSheet->wpgsi_token( $credential );
				# Check & Balance
				if ( $new_token[0] ) {
					# Change The Token Info
					$new_token[1]['expires_in'] = time() + $new_token[1]['expires_in'];
					# coping The Token
					$google_token = $new_token[1];
					# Save in Options
					update_option( 'wpgsi_google_token', $new_token[1] );
				} else {
					echo "<b> error : false credential ! Google said so ;-D  </b> ";
					$this->wpgsi_log( get_class( $this ), __METHOD__,"503", "Error: from  wpgsi_GoogleSpreadsheets func. " . json_encode( $new_token ) );
				}
			}
		}
		# Token Task Ends
		
		if ( $google_token ) {
			$r =  $this->googleSheet->wpgsi_spreadsheetsAndWorksheets( $google_token );
			if ( isset($r[0]) && $r[0] ){
				return $r;
			} else {
				$this->wpgsi_log( get_class( $this ), __METHOD__, "504", "Error: from wpgsi_spreadsheetsAndWorksheets func. ". json_encode( $r ) );
				return array( FALSE, array());
			}
		} else {
			$this->wpgsi_log( get_class( $this ), __METHOD__, "505", "Error: google_token is False. ". json_encode( $google_token ) );
			return array( FALSE, array());
		}
	}

	/**
	 * Change connection status;
	 * @since    	1.0.0
	 * @return 	   	array 		Integrations details  .
	*/
	public function wpgsi_connection_status( $id='' ){
		# check the Post type status
		if ( get_post( $id )->post_status == 'publish' ) {
			$custom_post = array( 'ID' => $id, 'post_status' => 'pending');
		} else {
			$custom_post = array( 'ID' => $id, 'post_status' => 'publish');
		}
		# Keeping Log 
		$this->wpgsi_log( get_class( $this ), __METHOD__, "200", "Success: ID " . $id . " Integration status  change to .". get_post( $id )->post_status );
		# redirect 
		wp_update_post( $custom_post  ) ? wp_redirect( admin_url('/admin.php?page=wpgsi&rms=success_from_status_change') ) : wp_redirect(admin_url('/admin.php?page=wpgsi&rms=fail'));
	}

	/**
	 * Delete the Connection;
	 * @since    	1.0.0
	 * @return 	   	array 		Integrations details  .
	*/
	public function wpgsi_delete_connection( $id='' ){
		# insert log
		$this->wpgsi_log( get_class( $this ), __METHOD__,"200","Success: Integration Deleted Successfully. ID ". $id );
		# Redirect 
		wp_delete_post( $id ) ? wp_redirect(admin_url('/admin.php?page=wpgsi&rms=success')) : wp_redirect(admin_url('/admin.php?page=wpgsi&rms=fail'));
		# Reset And Caching the integrations 
		$integrations =  $this->wpgsi_getIntegrations();
		if ( $integrations[0] ) {
			# setting or updating the transient;
			set_transient( 'wpgsi_integrations', $integrations[1] );
		}
	}

	/**
	 * Creating Column titles;
	 * @since    	1.0.0
	 * @return 	   	array 		Integrations details  .
	*/
	public function wpgsi_columnTitle( $id = null ){
		# get the post with Post ID 
		$post = get_post( $id );
		# Check & balance if there is a Post
		if ( $post ) {
			# Converting to PHP array from JSON
			$post_content = json_decode( $post->post_content, TRUE );
			$post_excerpt = json_decode( $post->post_excerpt );
			# Replacing Sheet ABC With Event Titles;
			$newArray = array();
			if ( isset( $this->eventsAndTitles[ $post_excerpt->DataSourceID] ) ){
				foreach ( $this->eventsAndTitles[ $post_excerpt->DataSourceID ] as $key => $value) {
					$newArray["{{" . $key . "}}"] = $value ;
				}
			}
		
			$FinalArray = array();
			foreach ( $post_content[1]  as $key => $value) {
				$FinalArray[ $key ] =  strip_tags( strtr( $value, $newArray ) );
			}

			$returns = $this->googleSheet->wpgsi_append_row( $post_excerpt->SpreadsheetID, $post_excerpt->WorksheetID, $FinalArray );
			
			# Redirect The User With message 
			if ( $returns[0] ){
				$this->wpgsi_log( get_class($this), __METHOD__,"200","Success : Google spreadsheet column title created, ". json_encode($returns) );
				wp_redirect(admin_url('/admin.php?page=wpgsi&rms=success'));
			} else {
				$this->wpgsi_log( get_class($this), __METHOD__,"506","Error: Google spreadsheet column title didn't created ". json_encode( array( "ret"=>$returns, "SpreadsheetID" => $post_excerpt->SpreadsheetID, "WorksheetID"=>$post_excerpt->WorksheetID, "FinalArray"=> $FinalArray) ) );
				wp_redirect(admin_url('/admin.php?page=wpgsi&rms=failed'));
			}
		}
	}
	
	/**
	 * Save getIntegration Data to Database , New getIntegration and Edit getIntegration use This Function;
	 * @since    	1.0.0
	 * @return 	   	array 		Integrations details.
	*/
	public function wpgsi_save_integration(){
		# Setting error status 
		$errorStatus = TRUE;
		// 
		// It Should be removed From $_POST Array ***
		// unset( $_POST['SpreadsheetAndWorksheet'] );
		// 
		# Check and Balance 
		if ( ! isset( $_POST['IntegrationTitle'] ) OR empty( $_POST['IntegrationTitle'] ) ) {
			$errorStatus = FALSE;
			$this->wpgsi_log( get_class( $this ), __METHOD__, "507", "Error: IntegrationTitle is Empty. " );
		  	wp_redirect( admin_url( '/admin.php?page=wpgsi&action=new&rms=fail_empty_IntegrationTitle' ) );
		}

		if ( ! isset($_POST['DataSource']) OR empty($_POST['DataSource']) ) {
			$errorStatus = FALSE;
			$this->wpgsi_log( get_class( $this ), __METHOD__, "508", "Error: DataSource name is Empty." );
			wp_redirect( admin_url( '/admin.php?page=wpgsi&action=new&rms=fail_empty_DataSource' ) );
		}

		if ( ! isset( $_POST['DataSourceID'] ) OR empty( $_POST['DataSourceID'] ) ) {
			$errorStatus = FALSE;
			$this->wpgsi_log( get_class( $this ), __METHOD__, "508", "Error: DataSourceID is Empty." );
		  	wp_redirect( admin_url( '/admin.php?page=wpgsi&action=new&rms=fail_empty_DataSourceID' ) );
		}

		if ( empty( $_POST['Worksheet'] ) OR is_null( $_POST['WorksheetID'] ) ){
			$errorStatus = FALSE;
			$this->wpgsi_log( get_class( $this ), __METHOD__, "508", "Error: Worksheet or WorksheetID is Empty. " );
		  	wp_redirect(admin_url('/admin.php?page=wpgsi&action=new&rms=fail_empty_Worksheet_worksheetID'));
		}

		if ( empty( $_POST['Spreadsheet'] ) OR empty( $_POST['Spreadsheet'] ) ){
			$errorStatus = FALSE;
			$this->wpgsi_log( get_class( $this ), __METHOD__, "508", "Error: Spreadsheet is Empty." );
		  	wp_redirect(admin_url('/admin.php?page=wpgsi&action=new&rms=fail_empty_Spreadsheet'));
		}
		
		if ( ! isset( $_POST['SpreadsheetID'] ) OR empty( $_POST['SpreadsheetID'] ) ){
			$errorStatus = FALSE;
			$this->wpgsi_log( get_class( $this ), __METHOD__, "508", "Error: SpreadsheetID is Empty. " );
		  	wp_redirect(admin_url('/admin.php?page=wpgsi&action=new&rms=fail_empty_SpreadsheetID'));
		}

		if ( $_POST['status'] == "edit_Integration"  AND  empty( $_POST['ID'] ) ) {
			$errorStatus = FALSE;
			$this->wpgsi_log( get_class( $this ), __METHOD__, "508", "Error: edit_Integration ID is Empty. " );
			wp_redirect( admin_url('/admin.php?page=wpgsi&action=new&rms=empty_edit_id') );
		}

		if ( empty( $_POST['Relation'] ) OR empty( $_POST['Relation'] ) ){
			$errorStatus = FALSE;
			$this->wpgsi_log( get_class( $this ), __METHOD__, "508", "Error: Relations is Empty." );
		  	wp_redirect(admin_url('/admin.php?page=wpgsi&action=new&rms=fail_empty_Relation'));
		}

		# sanitize_text_field 
		$ColumnTitle = array_map( 'sanitize_text_field', $_POST['ColumnTitle'] );
		$Relation 	 = array_map( 'sanitize_text_field', $_POST['Relation'] );
		
		# Save new integration
		if ( $_POST['status'] == "new_Integration"  AND  $errorStatus  ) {
			# Preparing Post array for DB insert
			$customPost = array(
				'ID'				=> '',
				'post_content'  	=> json_encode( array( $ColumnTitle, $Relation ) ), 										// Used for JSON ||ColumnHeaders || OutputsHolder 
				'post_title'    	=> sanitize_text_field( $_POST['IntegrationTitle'] ), 										// used for title
				'post_status'   	=> 'publish',																				// Use for status  || on or off
				'post_excerpt'  	=> json_encode( array( 	"DataSource"	=> sanitize_text_field( $_POST['DataSource']), 
															"DataSourceID"	=> sanitize_text_field( $_POST['DataSourceID'] ),
															"Worksheet"		=> sanitize_text_field( $_POST['Worksheet'] ),
															"WorksheetID"	=> sanitize_text_field( $_POST['WorksheetID'] ),
															"Spreadsheet"	=> sanitize_text_field( $_POST['Spreadsheet'] ),
															"SpreadsheetID"	=> sanitize_text_field( $_POST['SpreadsheetID'] )) 
													), 
				'post_name'  		=> '',																						//  Use it for  " DataSource " like "cf7_5", "wordpress_newUser"
				'post_type'   		=> 'wpgsiIntegration',																		//  Use || wpgsi_connection OR wpgsi_
				'menu_order'		=> '',																						//  Is used for fields Serializations  || to know what after what
				'post_parent'		=> '',																						//  This Should be the id of New Connection
			);
			# Inserting New integration custom Post type 
			$post_id = wp_insert_post( $customPost );																			//  Insert the post into the database
		}

		# Save edited Integration
		if ( ( $_POST['status'] == "edit_Integration" AND ! empty( $_POST['ID'] ) ) AND $errorStatus  ) {
			# Preparing Post array for status Change 
			$customPost = array(
				'ID'				=> sanitize_text_field( $_POST['ID'] ),														// Edit ID 
				'post_content'  	=> json_encode( array( $ColumnTitle, $Relation ) ), 										// Used for JSON ||ColumnHeaders || OutputsHolder 
				'post_title'    	=> sanitize_text_field( $_POST['IntegrationTitle'] ), 										// used for title
				'post_status'   	=> 'publish',																				// Use for status  || on or off
				'post_excerpt'  	=> json_encode( array( 	"DataSource"	=> sanitize_text_field( $_POST['DataSource']), 
															"DataSourceID"	=> sanitize_text_field( $_POST['DataSourceID'] ),
															"Worksheet"		=> sanitize_text_field( $_POST['Worksheet'] ),
															"WorksheetID"	=> sanitize_text_field( $_POST['WorksheetID'] ),
															"Spreadsheet"	=> sanitize_text_field( $_POST['Spreadsheet'] ),
															"SpreadsheetID"	=> sanitize_text_field( $_POST['SpreadsheetID'] ) ) 
													),
				'post_name'  		=> '',																						//  Use it for  " DataSource " like "cf7_5", "wordpress_newUser"
				'post_type'   		=> 'wpgsiIntegration',																		//  Use || wpgsi_connection OR wpgsi_
				'menu_order'		=> '',																						//  Is used for fields Serializations  || to know what after what
				'post_parent'		=> '',																						//  This Should be the id of New Connection
			);
			# Updating Custom Post Type 
			$post_id = wp_update_post( $customPost );																			// Insert the post into the database
		}

		# if There is a Post Id , That Means Post is success fully saved
		if ( $post_id AND $errorStatus ) {
			# inserting on log
			$this->wpgsi_log( get_class( $this ), __METHOD__, "200", "Success: Integration saved. ". json_encode( $customPost ) );
			# Caching integrations to wp set_transient
			$integrations =  $this->wpgsi_getIntegrations();
			if ( $integrations[0] ) {
				# setting or updating the Options
				set_transient( 'wpgsi_integrations', $integrations[1] );
			}
			# Redirecting
			wp_redirect( admin_url('/admin.php?page=wpgsi&rms=success') );														// Redirect User With Success Note is not With Error Note 
		} else {
			# Inserting on log
			$this->wpgsi_log( get_class( $this ), __METHOD__, "507", "Error: Integration didn't saved. Integration insert fail. " . json_encode( $customPost ) );
			# redirecting
			wp_redirect( admin_url('/admin.php?page=wpgsi&rms=fail_insert') );													// Redirect User With Success Note is not With Error Note 
		}
	}
	
	/**
	 * Get getIntegration Data from Database  by there id
	 * @since    	1.0.0
	 * @param     	int    		Integration id      .
	 * @return 	   	array 		Integrations details  .
	*/
	public function wpgsi_getIntegration( $IntegrationID = '' ) {
		# Check IntegrationID is empty or not
		if ( empty( $IntegrationID )){
			$this->wpgsi_log( get_class( $this ), __METHOD__, "508", "Error: IntegrationID id is Empty.");																			// Check Data is Any returns or Not 
			return array( FALSE, "Error: IntegrationID id is Empty." );
		}
		# Check IntegrationID is numeric or not 
		if ( ! is_numeric ( $IntegrationID ) ){
			$this->wpgsi_log( get_class( $this ), __METHOD__, "509", "Error: IntegrationID id is not numeric.");																			// Check Data is Any returns or Not 
			return array( FALSE, "Error: IntegrationID id is not numeric.");
		}
		# getting the integration 
		$post_data = get_post( $IntegrationID );																				// Check There is a Data in the Database !
		
		if ( empty( $post_data ) ) {
			$this->wpgsi_log( get_class( $this ), __METHOD__, "510", "Error: Nothing in the Database on this ID or Empty Data or ID is Wrong !");																			// Check Data is Any returns or Not 
			return array( FALSE, "Nothing in the Database on this ID or Empty Data or ID is Wrong !" );
		}

		$data		  							= json_decode( $post_data->post_excerpt, TRUE ); 								// Getting Data from WP server 
		$return_array 							= array();
		$return_array['IntegrationTitle'] 		= sanitize_text_field( $post_data->post_title );
		$return_array['DataSource'] 			= sanitize_text_field( $data['DataSource'] );		
		$return_array['DataSourceID'] 			= sanitize_text_field( $data['DataSourceID'] );		
		$return_array['Worksheet'] 				= sanitize_text_field( $data['Worksheet'] );
		$return_array['WorksheetID'] 			= sanitize_text_field( $data['WorksheetID'] );
		$return_array['Spreadsheet'] 			= sanitize_text_field( $data['Spreadsheet'] );
		$return_array['SpreadsheetID'] 			= sanitize_text_field( $data['SpreadsheetID'] );
		
		$post_content 							= json_decode( $post_data->post_content, TRUE );
		$return_array['WorksheetColumnsTitle']  = $post_content[0];
		$return_array['Relations'] 				= $post_content[1];
		$return_array['Status'] 				= $post_data->post_status;
		
		return array( TRUE, $return_array );
	}

	/**
	 * AJAX events  function for New integration and edit integration , This will supply worksheet column titles 
	 * @since    	1.0.0
	 * @param     	string    	$SpreadsheetID       The name of this plugin.
	 * @param      	string    	$Worksheet    The version of this plugin.
	 * @return 	   	string 		This will return json string ,of column titles .
	*/
	public function wpgsi_WorksheetColumnsTitle(){
		# Testing security nonce Set and Valid test
		if ( ! isset( $_POST['nonce']) OR ! wp_verify_nonce( $_POST['nonce'], 'wpgsiProNonce' ) ){
			$this->wpgsi_log( get_class($this), __METHOD__,"511","Error : invalid nonce.");
			json_encode( array( "status" => FALSE ,"message"=>"Error: invalid nonce." ), TRUE );
			exit;
		}

		# Checking  Worksheet is set or not
		if ( ! isset( $_POST['Worksheet'] ) ){
			$this->wpgsi_log( get_class($this), __METHOD__,"512","Error : Worksheet is not set.");
			json_encode( array( "status" => FALSE ,"message"=>"Error: Worksheet is not set." ), TRUE );
			exit;
		}
		# Checking  SpreadsheetID is set or not
		if ( ! isset( $_POST['SpreadsheetID'] ) ){
			$this->wpgsi_log( get_class($this), __METHOD__,"513","Error : SpreadsheetID is not set.");
			json_encode( array( "status" => FALSE ,"message"=>"Error: SpreadsheetID is not set." ), TRUE );
			exit;
		}
		# Checking  Worksheet is empty or not
		if ( empty( $_POST['Worksheet'] ) ){
			$this->wpgsi_log( get_class($this), __METHOD__,"514","Error : Worksheet is empty !");
			json_encode( array( "status" => FALSE ,"message"=>"Error: Worksheet is empty !" ), TRUE );
		}
		# Checking  SpreadsheetID is empty or not
		if ( empty( $_POST['SpreadsheetID'] ) ){
			$this->wpgsi_log( get_class($this), __METHOD__,"515","Error : SpreadsheetID is empty !");
			json_encode( array( "status" => FALSE ,"message"=>"Error: SpreadsheetID is empty !" ), TRUE );
		}

		$WorksheetName	= strip_tags( $_POST['Worksheet'] ) ;
		$SpreadsheetID 	= sanitize_text_field( $_POST['SpreadsheetID'] );
		$google_token 	= get_option( 'wpgsi_google_token', FALSE );
		$columnTitle 	= $this->googleSheet->wpgsi_columnTitle( $WorksheetName, $SpreadsheetID, $google_token );
		# Printing, not returning 
		echo json_encode( $columnTitle );
		exit ;
	}

	/**
	 * Using custom hook sending data to Google spreadsheet 
	 * @since    	1.0.0
	 * @param     	string    	$plugin_name       The name of this plugin.
	 * @param      	string    	$version    The version of this plugin.
	 * @return 	   	array 		$columns Array of all the list table columns.
	*/
	public function wpgsi_SendToGS( $Evt_DataSource, $Evt_DataSourceID, $data_array, $id ){
		# Don't do anything if there is No internet , As you know it is a Integration Plugin.
		# This Code Should Be Change | Change Code in WooTrello
		if ( ! @fsockopen('www.google.com', 80) ){
			$this->wpgsi_log( get_class($this), __METHOD__,"516","Error: No internet connection.");
			return array( FALSE ,"Error: No internet connection." );
		}
		# Token task Starts , Very important . Now token will validate in every event so, nothing will miss on token failure .
		$credential 	= get_option( 'wpgsi_google_credential', FALSE );
		$google_token 	= get_option( 'wpgsi_google_token', FALSE );
		# Checking Token Validation
		if ( $google_token  &&  time() > $google_token['expires_in'] ) {
			# if there is a credential
			if ( $credential ) {
				# creating new Token 
				$new_token = $this->googleSheet->wpgsi_token( $credential );
				# if token is True 
				if ( $new_token[0] ) {
					# Change The Token Info
					$new_token[1]['expires_in'] = time() + $new_token[1]['expires_in'];
					# coping The Token
					$google_token = $new_token[1];
					# Save in Options
					update_option( 'wpgsi_google_token', $new_token[1] );
				} else {
					$this->wpgsi_log( get_class( $this ), __METHOD__,"517", "Error: from  wpgsi_SendToGS func. ". json_encode( $credential ) );
				}
			}
		}
		# Token Task Ends 
		$integrations   = get_posts( array(
			'post_type'   	 => 'wpgsiIntegration',
			'post_status' 	 => 'publish',
			'posts_per_page' => -1
		));
		# Looping the integrations
		foreach ( $integrations as  $integration ) {
			#
			$post_content 	= json_decode( $integration->post_content, TRUE );
			$post_excerpt 	= json_decode( $integration->post_excerpt, TRUE );
			#
			$DataSource		= $post_excerpt["DataSource"];
			$DataSourceID	= $post_excerpt["DataSourceID"];
			$Worksheet		= $post_excerpt["Worksheet"];
			$WorksheetID	= $post_excerpt["WorksheetID"];
			$Spreadsheet	= $post_excerpt["Spreadsheet"];
			$SpreadsheetID	= $post_excerpt["SpreadsheetID"];
			$ColumnsTitle 	= $post_content[0];
			$relation 		= $post_content[1];
			# Pre-process
			$ArrayKeyAndValue = array();
			foreach ($data_array as $relationKey => $relationValue) {
				$ArrayKeyAndValue["{{" . $relationKey . "}}"] = $relationValue;
			}
			
			# Check the value change depends on type 
			$dataWithRelationKey = array();
			foreach ( $relation as $key => $value ) {
				if ( is_array($value) ) {
					$dataWithRelationKey[ $key ] = implode( ", ", $value );
				} else {
					$dataWithRelationKey[ $key ] =  strtr( $value, $ArrayKeyAndValue );
				}
			} 

			# Sending Request;
			if ( $Evt_DataSourceID == $DataSourceID ) {
				# getting last time this Integrator Occurred TimeStamp, So that i Can Prevent Dual Submission 
				# Integration_id , wpgsi_lastFired, New Code After 3.5.0
				$wpgsi_lastFired = (int)get_post_meta( $integration->ID ,'wpgsi_lastFired', TRUE );
				
				# dualSubmission Prevention 
				# lastFired is set and value is Not grater then 301 seconds
				if( $wpgsi_lastFired  AND  ( time() - $wpgsi_lastFired ) < 33 ){
					$this->wpgsi_log( get_class($this), __METHOD__, "518", "ERROR: Dual submission Prevented of Integration : <b> ". $integration->ID ." </b> ". json_encode( $dataWithRelationKey ) );
				} else {
					# Send the request 
					$ret = $this->googleSheet->wpgsi_append_row( $SpreadsheetID, $WorksheetID, $dataWithRelationKey );
					# Check error or success 
					if ( $ret[0] ){
						$this->wpgsi_log( get_class($this), __METHOD__, "200", "Success: okay, on the event . " . json_encode( $ret ) );
						# New Code after 3.5.0
						# New Code for preventing Dual Submission || saving last Fired time 
						update_post_meta( $integration->ID, 'wpgsi_lastFired', time() );
					} else {
						$this->wpgsi_log( get_class($this), __METHOD__, "519", "Error: on sending data . " . json_encode( array( "SpreadsheetID" => $SpreadsheetID, "WorksheetID" => $WorksheetID,  "dataWithRelationKey" => $dataWithRelationKey ,"Google_response" => $ret ) ) );
					}
				}
			}
		}
	}

	/**
	 * This Function will return [wordPress Pages] Meta keys.
	 * @since      3.3.0
	 * @return     array    This array has two vale First one is Bool and Second one is meta key array.
	*/
	public function wpgsi_pages_metaKeys(){
		# Global Db object 
		global $wpdb;
		# Query 
		$query  =  "SELECT DISTINCT($wpdb->postmeta.meta_key) 
					FROM $wpdb->posts 
					LEFT JOIN $wpdb->postmeta 
					ON $wpdb->posts.ID = $wpdb->postmeta.post_id 
					WHERE $wpdb->posts.post_type = 'page' 
					AND $wpdb->postmeta.meta_key != '' ";
		# execute Query
		$meta_keys = $wpdb->get_col( $query );
		# return Depend on the Query result 
		if ( empty( $meta_keys ) ){
			return array( FALSE, 'Error: Empty! No Meta key exist of the Post type page.');
		} else {
			return array( TRUE, $meta_keys );
		}
	}

	/**
	 * This Function will return [wordPress Posts] Meta keys.
	 * @since      3.3.0
	 * @return     array    This array has two vale First one is Bool and Second one is meta key array.
	*/
	public function wpgsi_posts_metaKeys(){
		# Global Db object 
		global $wpdb;
		# Query 
		$query  =  "SELECT DISTINCT($wpdb->postmeta.meta_key) 
				  	FROM $wpdb->posts 
					LEFT JOIN $wpdb->postmeta 
					ON $wpdb->posts.ID = $wpdb->postmeta.post_id 
					WHERE $wpdb->posts.post_type = 'post' 
					AND $wpdb->postmeta.meta_key != '' ";
		# execute Query
		$meta_keys = $wpdb->get_col( $query );
		# return Depend on the Query result 
		if ( empty( $meta_keys ) ){
			return array( FALSE, 'Error: Empty! No Meta key exist of the Post.');
		} else {
			return array( TRUE, $meta_keys );
		}
	}

	/**
	 * This Function will return [wordPress Users] Meta keys.
	 * @since      3.3.0
	 * @return     array    This array has two vale First one is Bool and Second one is meta key array.
	*/
	public function wpgsi_users_metaKeys(){
		# Global Db object 
		global $wpdb;
		# Query 
		$query = "SELECT DISTINCT( $wpdb->usermeta.meta_key ) FROM $wpdb->usermeta ";
		# execute Query
		$meta_keys = $wpdb->get_col( $query );
		# return Depend on the Query result 
		if ( empty( $meta_keys ) ){
			return array( FALSE, 'Error: Empty! No Meta key exist of users.');
		} else {
			return array( TRUE, $meta_keys );
		}
	}

	/**
	 * This Function will return [wordPress Users] Meta keys.
	 * @since      3.3.0
	 * @return     array    This array has two vale First one is Bool and Second one is meta key array.
	*/
	public function wpgsi_comments_metaKeys(){
		# Global Db object 
		global $wpdb;
		# Query 
		$query = "SELECT DISTINCT( $wpdb->commentmeta.meta_key ) FROM $wpdb->commentmeta ";
		# execute Query
		$meta_keys = $wpdb->get_col( $query );
		# return Depend on the Query result 
		if ( empty( $meta_keys ) ){
			return array( FALSE, 'Error: Empty! No Meta key exist on comment meta.');
		} else {
			return array( TRUE, $meta_keys );
		}
	}

	/**
	 * This Function will return [WooCommerce Order] Meta keys.
	 * @since      3.3.0
	 * @return     array    This array has two vale First one is Bool and Second one is meta key array.
	*/
	public function wpgsi_wooCommerce_order_metaKeys(){
		# Global Db object 
		global $wpdb;
		# Query 
		$query  =  "SELECT DISTINCT($wpdb->postmeta.meta_key) 
					FROM $wpdb->posts 
					LEFT JOIN $wpdb->postmeta 
					ON $wpdb->posts.ID = $wpdb->postmeta.post_id 
					WHERE $wpdb->posts.post_type = 'shop_order' 
					AND $wpdb->postmeta.meta_key != '' ";
		# execute Query
		$meta_keys = $wpdb->get_col( $query );
		# return Depend on the Query result 
		if ( empty( $meta_keys ) ){
			return array( FALSE, 'Error: Empty! No Meta key exist of the post type WooCommerce Order.');
		} else {
			return array( TRUE, $meta_keys );
		}
	}

	/**
	 * This Function will return [WooCommerce product] Meta keys.
	 * @since      3.3.0
	 * @return     array    This array has two vale First one is Bool and Second one is meta key array.
	*/
	public function wpgsi_wooCommerce_product_metaKeys(){
		# Global Db object 
		global $wpdb;
		# Query 
		$query  =  "SELECT DISTINCT($wpdb->postmeta.meta_key) 
					FROM $wpdb->posts 
					LEFT JOIN $wpdb->postmeta 
					ON $wpdb->posts.ID = $wpdb->postmeta.post_id 
					WHERE $wpdb->posts.post_type = 'product' 
					AND $wpdb->postmeta.meta_key != '' ";
		# execute Query
		$meta_keys = $wpdb->get_col( $query );
		# return Depend on the Query result 
		if ( empty( $meta_keys ) ){
			return array( FALSE, 'Error: Empty! No Meta key exist of the Post type WooCommerce Product.');
		} else {
			return array( TRUE, $meta_keys );
		}
	}

	/**
	 *  Contact form 7,  form  fields 
	 *  @since    3.1.0
	*/
	//  This Function should Change;
	public function cf7_forms_and_fields(){
		# is there CF7 
		if ( ! in_array('contact-form-7/wp-contact-form-7.php' , $this->active_plugins ) OR  ! $this->wpgsi_dbTableExists( 'posts' )   ) {
			return array(FALSE, "Error:  Contact form 7 is Not installed or DB Table is Not Exist  " );
		}

		$cf7forms 		= array();
		$fieldsArray 	= array();	
		global $wpdb;	
		$cf7Forms = $wpdb->get_results( "SELECT * FROM {$wpdb->posts} INNER JOIN {$wpdb->postmeta} ON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id WHERE {$wpdb->posts}.post_type = 'wpcf7_contact_form' AND {$wpdb->postmeta}.meta_key = '_form'");
		# Looping the Forms 
		foreach ( $cf7Forms as $form ) {	
			# Inserting Fields 																			# Loop the Custom Post ;
			$cf7forms[ "cf7_" . $form->ID ] = "Cf7 - " . $form->post_title;	
			# Getting Fields Meta 
			$formFieldsMeta = get_post_meta( $form->ID, '_form', true );
			# Replacing Quoted string 
			$formFieldsMeta =  preg_replace('/"((?:""|[^"])*)"/', "", $formFieldsMeta);
			# Removing : txt 
			$formFieldsMeta =  preg_replace('/\w+:\w+/', "", $formFieldsMeta);
			# Removing submit
			$formFieldsMeta =  preg_replace('/\bsubmit\b/', "", $formFieldsMeta);
			# if txt is Not empty 
			if ( ! empty( $formFieldsMeta )){
				# Getting Only [] txt 
				$bracketTxt = array();
				# Separating bracketed txt and inserting theme to  $bracketTxt array
				preg_match_all('/\[(.*?)\]/', $formFieldsMeta, $bracketTxt);
				# Check is set & not empty
				if ( isset( $bracketTxt[1] ) && !empty( $bracketTxt[1] )){
					# Field Loop 
					foreach( $bracketTxt[1] as $txt ){
						# Divide the TXT after every space 
						$tmpArr =  explode(' ', $txt);
						# taking Only the second Element of every array || first one is Field type || Second One is Field key 
						$singleItem =  array_slice($tmpArr, 1, 1);
						# Remove Submit Empty Array || important i am removing submit 
						if ( isset( $singleItem[0]  ) && !empty( $singleItem[0] ) ){
							$fieldsArray["cf7_" . $form->ID][$singleItem[0]] = $singleItem[0];
						}
					}
				}
			}
		} # Loop ends 

		# Adding extra fields || like Date and Time || Add more in future  
		if ( wpgsi_fs()->is__premium_only() ) {
			if ( wpgsi_fs()->can_use_premium_code() ) {
				if ( ! empty( $fieldsArray ) ){
					foreach( $fieldsArray as $formID => $formFieldsArray ){
						# For Time
						if ( ! isset( $formFieldsArray['wpgsi_submitted_time'] ) ){
							$fieldsArray[$formID]['wpgsi_submitted_time'] = "wpgsi Form submitted  time";
						}

						# for Date 
						if ( ! isset( $formFieldsArray['wpgsi_submitted_date'] ) ){
							$fieldsArray[$formID]['wpgsi_submitted_date'] = "wpgsi Form submitted date";
						}
					}
				}
			}
		}
		
		return array( TRUE, $cf7forms, $fieldsArray );
	}

	/**
	 *  Ninja  form  fields 
	 *  @param     int     $user_id     username
	 *  @param     int     $old_user_data     username
	 *  @since     1.0.0
	*/
	public function ninja_forms_and_fields() {
		
		if ( ! in_array('ninja-forms/ninja-forms.php', $this->active_plugins ) OR ! $this->wpgsi_dbTableExists( 'nf3_forms' ) ) {
			return array( FALSE, "Error:  Ninja form 7 is Not Installed "  );
		}
		global $wpdb;	
		$FormArray 	 	= array();																								# Empty Array for Value Holder 
		$fieldsArray 	= array();		
		$ninjaForms 	= $wpdb->get_results("SELECT * FROM {$wpdb->prefix}nf3_forms", ARRAY_A);
		
		foreach ( $ninjaForms as $form ) {
			$FormArray[ "ninja_". $form["id"] ] = "Ninja - ". $form["title"];	
			$ninjaFields =  $wpdb->get_results("SELECT * FROM {$wpdb->prefix}nf3_fields where parent_id = '".$form["id"]."'", ARRAY_A);
			foreach ($ninjaFields as $field) {
				
				$field_list = array( "textbox","textarea" );

				# freemius 
				if ( wpgsi_fs()->is__premium_only() ) {
					if ( wpgsi_fs()->can_use_premium_code() ) {
						$field_list = array( "textbox","email","textarea","phone","checkbox","listmultiselect","listradio");
					}
				}

				if( in_array( $field["type"], $field_list  ) ){
					$fieldsArray[ "ninja_". $form["id"] ] [ $field["key"] ] = $field["label"];
				}
			}
		}

		# Adding extra fields || like Date and Time || Add more in future  
		if ( wpgsi_fs()->is__premium_only() ) {
			if ( wpgsi_fs()->can_use_premium_code() ) {
				if ( ! empty( $fieldsArray ) ){
					foreach( $fieldsArray as $formID => $formFieldsArray ){
						# For Time
						if ( ! isset( $formFieldsArray['wpgsi_submitted_time'] ) ){
							$fieldsArray[$formID]['wpgsi_submitted_time'] = "wpgsi Form submitted  time";
						}
						
						# for Date 
						if ( ! isset( $formFieldsArray['wpgsi_submitted_date'] ) ){
							$fieldsArray[$formID]['wpgsi_submitted_date'] = "wpgsi Form submitted date";
						}
					}
				}
			}
		}

		return array( TRUE, $FormArray, $fieldsArray );
	}
 
	/**
	 *  formidable form  fields 
	 *  @since    1.0.0
	*/
	public function formidable_forms_and_fields(){
		
		if ( ! in_array( 'formidable/formidable.php', $this->active_plugins ) OR  ! $this->wpgsi_dbTableExists( 'frm_forms' ) ) {
			return array( FALSE, "Error: formidable form  is Not Installed OR DB table is Not Exist" );
		}
		
		global $wpdb;
		$FormArray 	 = array();																						# Empty Array for Value Holder 
		$fieldsArray = array();																						# Empty Array for Holder 
		$frmForms 	 = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}frm_forms");								# Getting  Forms Database 
		
		foreach ( $frmForms as $form ) {
			$FormArray["frm_".$form->id] =  "Formidable - " . $form->name ;											# Inserting ARRAY title 
			# Getting Meta Fields || maybe i don't Know ;-D
			$fields = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}frm_fields WHERE form_id= " . $form->id . " ORDER BY field_order"); 	# Getting  Data from Database 
			foreach ($fields as $field) {
				# Default fields
				$field_list = array("text","textarea");

				# freemius
				if ( wpgsi_fs()->is__premium_only() ){
					if ( wpgsi_fs()->can_use_premium_code() ){
						$field_list = array( "text", "textarea", "number", "email", "phone", "hidden", "url", "user_id", "select", "radio", "checkbox", "rte", "date", "time", "star", "range", "password", "address" );
					}
				}

				if ( in_array( $field->type, $field_list  ) ){
					$fieldsArray["frm_".$form->id][$field->id] = $field->name;
				}
			}
		}

		# Adding extra fields || like Date and Time || Add more in future  
		if ( wpgsi_fs()->is__premium_only() ) {
			if ( wpgsi_fs()->can_use_premium_code() ) {
				if ( ! empty( $fieldsArray ) ){
					foreach( $fieldsArray as $formID => $formFieldsArray ){
						# For Time
						if ( ! isset( $formFieldsArray['wpgsi_submitted_time'] ) ){
							$fieldsArray[$formID]['wpgsi_submitted_time'] = "wpgsi Form submitted  time";
						}
						
						# for Date 
						if ( ! isset( $formFieldsArray['wpgsi_submitted_date'] ) ){
							$fieldsArray[$formID]['wpgsi_submitted_date'] = "wpgsi Form submitted date";
						}
					}
				}
			}
		}

		return array( TRUE, $FormArray, $fieldsArray );																# Inserting Data to the Main [$eventsAndTitles ] Array 
	}

	/**
	 *  wpforms fields 
	 *  @since    1.0.0
	*/
	public function wpforms_forms_and_fields(){

		if ( ! count( array_intersect( $this->active_plugins, array('wpforms-lite/wpforms.php', 'wpforms/wpforms.php') ) )  OR  ! $this->wpgsi_dbTableExists( 'posts' ) ) {
			return array( FALSE, "Error:  wp form is Not Installed OR DB Table is Not Exist  "  );
		}

		$FormArray	 = array();
		$fieldsArray = array();	
		# Getting Data from Database 
		global $wpdb;
		$wpforms 	 = $wpdb->get_results("SELECT * FROM {$wpdb->posts} WHERE post_type = 'wpforms'  ");
		
		foreach ( $wpforms as $wpform ) {
			$FormArray[ "wpforms_". $wpform->ID ] = "WPforms - ".$wpform->post_title ;	
			$post_content =  json_decode( $wpform->post_content );
			
			foreach( $post_content->fields as $field ){
				# Default fields
				$field_list = array( "name", "text", "textarea" );

				# freemius
				if ( wpgsi_fs()->is__premium_only() ) {
					if ( wpgsi_fs()->can_use_premium_code() ){
						$field_list = array( 
							"name", 
							"text", 
							"email", 
							"textarea", 
							"number", 
							"number-slider", 
							"phone", 
							"address", 
							"date-time", 
							"url", 
							"password", 
							"hidden", 
							"rating", 
							"checkbox", 
							"radio", 
							"select", 
							"payment-single", 
							"payment-checkbox", 
							"payment-total", 
							"stripe-credit-card"
						);
					}
				}

				if( in_array( $field->type, $field_list  ) ){
					$fieldsArray["wpforms_". $wpform->ID ][$field->id] = $field->label;
				}
			}	
		}

		# Adding extra fields || like Date and Time || Add more in future  
		if ( wpgsi_fs()->is__premium_only() ) {
			if ( wpgsi_fs()->can_use_premium_code() ) {
				if ( ! empty( $fieldsArray ) ){
					foreach( $fieldsArray as $formID => $formFieldsArray ){
						# For Time
						if ( ! isset( $formFieldsArray['wpgsi_submitted_time'] ) ){
							$fieldsArray[$formID]['wpgsi_submitted_time'] = "wpgsi Form submitted  time";
						}
						
						# for Date 
						if ( ! isset( $formFieldsArray['wpgsi_submitted_date'] ) ){
							$fieldsArray[$formID]['wpgsi_submitted_date'] = "wpgsi Form submitted date";
						}
					}
				}
			}
		}

		return array( TRUE, $FormArray, $fieldsArray );	
	}

	# FIXME:
	# do it after Upload || last off all forms 
	/**
	 *  WE forms fields 
	 *  @since    1.0.0
	*/
	public function weforms_forms_and_fields() {
		
		if ( ! in_array('weforms/weforms.php', $this->active_plugins )  OR  ! $this->wpgsi_dbTableExists('posts') ) {
			return array( FALSE, "Error:  weForms  is Not Active  OR DB is not exist"  );
		}
		
		$FormArray	 	= array();
		$fieldsArray 	= array();
		$fieldTypeArray = array();

		global $wpdb;
		$weforms 	 = $wpdb->get_results("SELECT * FROM {$wpdb->posts} WHERE post_type = 'wpuf_contact_form'  ");
		$weFields 	 = $wpdb->get_results("SELECT * FROM {$wpdb->posts} WHERE post_type = 'wpuf_input'  ");
		
		foreach ( $weforms as $weform ) {
			$FormArray[ "we_" . $weform->ID ] = 'weForms - '. $weform->post_title;
		}

		foreach ( $weFields as $Field ) {
			foreach ($FormArray as $weformID => $weformTitle ) {
				if ( $weformID  ==  "we_" .$Field->post_parent ){
					$content_arr = unserialize(  $Field->post_content );
					$fieldsArray[ $weformID ][ $content_arr['name'] ] 	  =   $content_arr['label'] ;
					$fieldTypeArray[ $weformID ][ $content_arr['name'] ]  =   $content_arr['template'] ;
				}
			}
		}

		# Adding extra fields || like Date and Time || Add more in future  
		if ( wpgsi_fs()->is__premium_only() ) {
			if ( wpgsi_fs()->can_use_premium_code() ) {
				if ( ! empty( $fieldsArray ) ){
					foreach( $fieldsArray as $formID => $formFieldsArray ){
						# For Time
						if ( ! isset( $formFieldsArray['wpgsi_submitted_time'] ) ){
							$fieldsArray[$formID]['wpgsi_submitted_time'] = "wpgsi Form submitted  time";
						}
						
						# for Date 
						if ( ! isset( $formFieldsArray['wpgsi_submitted_date'] ) ){
							$fieldsArray[$formID]['wpgsi_submitted_date'] = "wpgsi Form submitted date";
						}
					}
				}
			}
		}

		return array( TRUE, $FormArray, $fieldsArray, $fieldTypeArray );
	}

	/**
	 * 	Under Construction 
	 *  gravity forms fields 
	 *  @since    1.0.0
	*/
	public function gravity_forms_and_fields( ) {
		
		if ( ! in_array('gravityforms/gravityforms.php', $this->active_plugins )  ) {
			return array( FALSE, "Error:  gravity forms  is Not Active  OR DB is not exist"  );
		}

		if ( ! class_exists('GFAPI')) {
    		return array( FALSE, "Error:  gravityForms class GFAPI is not exist"  );
		}

		$gravityForms = GFAPI::get_forms();
		#check and Test 
		if ( ! empty( $gravityForms ) ){
			# Empty array holder Declared
			$FormArray 	 	= array();																								# Empty Array for Value Holder 
			$fieldsArray 	= array();	
			$fieldTypeArray = array();	
			# New Code Loop
			foreach ( $gravityForms as $form ) {
				$FormArray[ "gravity_". $form["id"] ] = "Gravity - ". $form["title"];	
				# Form Fields || Check fields are set or Not
				if ( isset( $form['fields'] ) AND is_array( $form['fields'] ) ) {
					foreach ( $form['fields'] as $field ) {
						if ( empty( $field['inputs'])) {
							# if there is no subfields
							$fieldsArray[ "gravity_" . $form["id"] ] [ $field["id"] ] 		= $field["label"];
							$fieldTypeArray[ "gravity_" . $form["id"] ] [ $field["id"] ] 	= $field["type"];
						} else {
							# Looping Subfields
							foreach( $field["inputs"] as $subField ){
								$fieldsArray[ "gravity_". $form["id"] ] [ $subField["id"] ] 	= $field["label"].' ('. $subField["label"] .')';
								$fieldTypeArray[ "gravity_". $form["id"] ] [ $subField["id"] ] 	= $field["type"];
							}
						}
					}
				}
			}

		} else {
			return array( FALSE, "Error:  gravityForms form object is empty."  );
		}

		return array( TRUE, $FormArray, $fieldsArray, $fieldTypeArray );
	}

	/**
	 * forminator forms fields 
	 * @since      3.6.0
	 * @return     array   First one is CPS and Second one is CPT's Field source.
	*/
	public function forminator_forms_and_fields( ) {
		
		if ( ! in_array( 'forminator/forminator.php', $this->active_plugins ) ) {
			return array( FALSE, "Error: forminator form  is Not Installed OR no integration Exist" );
		}

		$FormArray 	 = array();			# Empty Array for Value Holder 
		$fieldsArray = array();			# Empty Array for Holder 
		# Getting Forminator Fields 
		$forms = Forminator_API::get_forms();
		# Check And Balance 
		if( ! empty( $forms ) ) {
			# Looping the Forms 
			foreach( $forms as $form  ) {
				# inserting Forms 
				$FormArray[ "forminator_". $form->id ] = "forminator - ". $form->name;
				# Getting Fields 
				$fields = get_post_meta( $form->id , 'forminator_form_meta');
				# Check & balance 
				if( isset( $fields[0]['fields'] ) AND !empty( $fields[0]['fields'] )  ){
					# Looping the Fields 
					foreach( $fields[0]['fields'] as $field ){
						if( isset( $field['id'], $field['field_label'] ) ){
							$fieldsArray[ "forminator_". $form->id ][ $field['id'] ] = $field['field_label'];
						}
					}
					# Date And Time 
					$fieldsArray[ "forminator_". $form->id ][ 'wpgsi_submitted_time' ] = "wpgsi Form submitted  time";
					$fieldsArray[ "forminator_". $form->id ][ 'wpgsi_submitted_date' ] = "wpgsi Form submitted date";
				}
			}
		}
		
		return array( TRUE, $FormArray, $fieldsArray );		
	}

	
	/**
	 * This Function will All Custom Post types 
	 * @since      3.3.0
	 * @return     array   First one is CPS and Second one is CPT's Field source.
	*/
	public function wpgsi_allCptEvents( ) {
		# Getting The Global wp_post_types array
		global $wp_post_types;
		# Check And Balance 
		if ( isset( $wp_post_types ) && !empty( $wp_post_types ) ) {
			# CPT holder empty array declared
			$cpts = array();
			# List of items for removing 
			$removeArray = array( 	"wpforms",
									"acf-field-group",
									"acf-field",
									"product",
									"product_variation", 
									"shop_order",
									"shop_order_refund"
								);
			# Looping the Post types 
			foreach ( $wp_post_types as $postKey => $PostValue ) {
				# if Post type is Not Default 
				if ( isset( $PostValue->_builtin )  AND ! $PostValue->_builtin   ){
					# Look is it on remove list, if not insert 
					if ( ! in_array(  $postKey, $removeArray )  ){
						# Pre populate $cpts array 
						if ( isset( $PostValue->label ) AND ! empty( $PostValue->label )  ){
							$cpts[ $postKey ]  =  $PostValue->label ." (".  $postKey. ")";
						} else {
							$cpts[ $postKey ]  = $postKey;
						}
					}
				}
			}

			# Empty Holder Array for CPT events 
			$cptEvents = array();
			# Creating events 
			if ( ! empty( $cpts ) ) {
				# Looping for Creating Extra Events Like Update and Delete 
				foreach ( $cpts as $key => $value ) {
					$cptEvents['cpt_new_'.$key] 	=  'CPT New '.$value;
					$cptEvents['cpt_update_'.$key] 	=  'CPT Update '.$value;
					$cptEvents['cpt_delete_'.$key] 	=  'CPT Delete '.$value;
				}
				# Now setting default Event data Source Fields; Those events data source  are common in all WordPress Post type 
				$eventDataFields = array(
									"postID"				=>"ID",
									"post_authorID"			=>"post author_ID",
									"authorUserName"		=>"author User Name",
									"authorDisplayName"		=>"author Display Name",
									"authorEmail"			=>"author Email",
									"authorRole"			=>"author Role",
									#
									"post_title"			=>"post title",
									"post_date"				=>"post date",
									"post_date_gmt"			=>"post date gmt",
									#
									"site_time"				=>"Site Time",
									"site_date"				=>"Site Date",
									#
									"post_content"			=>"post content",
									"post_excerpt"			=>"post excerpt",
									"post_status"			=>"post status",
									"comment_status"		=>"comment status",
									"ping_status"			=>"ping status",
									"post_password"			=>"post password",
									"post_name"				=>"post name",
									"to_ping"				=>"to ping",
									"pinged"				=>"pinged",
									#
									"post_modified"			=>"post modified date",
									"post_modified_gmt"		=>"post modified date GMT",
									"post_parent"			=>"post parent",
									"guid"					=>"guid",
									"menu_order"			=>"menu order",
									"post_type"				=>"post type",
									"post_mime_type"		=>"post mime type",
									"comment_count"			=>"comment count",
									"filter"				=>"filter",
								);
				# Global Db object 
				global $wpdb;
				# Query for getting Meta keys 
				$query  =  "SELECT DISTINCT($wpdb->postmeta.meta_key) 
							FROM $wpdb->posts 
							LEFT JOIN $wpdb->postmeta 
							ON $wpdb->posts.ID = $wpdb->postmeta.post_id 
							WHERE $wpdb->posts.post_type != 'post' 
							AND $wpdb->posts.post_type   != 'page' 
							AND $wpdb->posts.post_type   != 'product' 
							AND $wpdb->posts.post_type   != 'shop_order' 
							AND $wpdb->posts.post_type   != 'shop_order_refund' 
							AND $wpdb->posts.post_type   != 'product_variation' 
							AND $wpdb->posts.post_type 	 != 'wpforms' 
							AND $wpdb->postmeta.meta_key != '' ";
				# execute Query for getting the Post meta key it will use for event data source 
				$meta_keys = $wpdb->get_col( $query );
				# Inserting Meta keys to Main $eventDataFields Array;
				if ( ! empty( $meta_keys ) AND is_array( $meta_keys ) ){
					foreach ( $meta_keys as  $value ) {
						if ( ! isset( $eventDataFields[ $value ] ) ){
							$eventDataFields[ $value ] = "CPT Meta ". $value; 
						}
					}
				} else {
					# insert to the log but don't return
					# Error:  Meta keys  are empty;
				}
				
				# Everything seems ok, Now send the CPT events and Related Data source;
				return array( TRUE, $cpts, $cptEvents, $eventDataFields, $meta_keys );
			} else {
				return array( FALSE, "Error: cpts Array is Empty." );
			}

		} else {
			return array( FALSE, "Error: wp_post_types global array is not exists or Empty." );
		}
	}

	/**
	 * LOG ! For Good , This the log Method 
	 * @since      1.0.0
	 * @param      string    $file_name       	File Name . Use  [ get_class($this) ]
	 * @param      string    $function_name     Function name.	 [  __METHOD__  ]
	 * @param      string    $status_code       The name of this plugin.
	 * @param      string    $status_message    The version of this plugin.
	*/
	public function wpgsi_log( $file_name = '', $function_name = '', $status_code = '', $status_message = '' ){
		# Log status
		$logStatusOption = get_option( 'wpgsi_logStatus', false );
		# check log status 
		if(  $logStatusOption  AND  $logStatusOption == 'disable' ){
			return  array( FALSE, "Log is disable." ); 
		} 

		# Check and Balance 
		if ( empty( $status_code ) or empty( $status_message ) ){
			return  array( FALSE, "Error: status_code OR status_message is Empty");
		}

		$r = wp_insert_post( 
			array(
				'post_content'  => $status_message,
				'post_title'  	=> $status_code,
				'post_status'  	=> "publish",
				'post_excerpt'  => json_encode( array( "file_name" => $file_name, "function_name" => $function_name ) ),
				'post_type'  	=> "wpgsi_log",
			)
		);

		if ( $r ){
			return  array( TRUE, "Success: Successfully inserted to the Log" ); 
		}
	}

	/**
	 * This is a Helper function to check Table is Exist or Not 
	 * If DB table Exist it will return True if Not it will return False
	 * @since      3.2.0
	 * @param      string    $data_source    Which platform call this function s
	*/
	public function wpgsi_dbTableExists( $tableName = null ) {
		if ( empty( $tableName ) ){
			return FALSE;
		}

		global $wpdb;
		$r = $wpdb->get_results("SHOW TABLES LIKE '". $wpdb->prefix. $tableName ."'");
		
		if ( $r ){
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * This Function Will return all the Save integrations from database 
	 * @since      3.4.0
	 * @return     array   	 This Function Will return an array 
	*/
	public function wpgsi_getIntegrations( ) {
		# Setting Empty Array
		$integrationsArray 		= array();
		# Getting All Posts
		$listOfConnections   	= get_posts( array(
			'post_type'   	 	=> 'wpgsiIntegration',
			'post_status' 		=> array('publish', 'pending'),
			'posts_per_page' 	=> -1
		));

		# integration loop starts
		foreach ( $listOfConnections as $key => $value ) {
			# Compiled to JSON String 
			$post_excerpt = json_decode( $value->post_excerpt, TRUE );
			# if JSON Compiled successfully 
			if ( is_array( $post_excerpt ) AND !empty( $post_excerpt ) ) {
				$integrationsArray[$key]["IntegrationID"] 	= $value->ID;
				$integrationsArray[$key]["DataSource"] 		= $post_excerpt["DataSource"];
				$integrationsArray[$key]["DataSourceID"] 	= $post_excerpt["DataSourceID"];
				$integrationsArray[$key]["Worksheet"] 		= $post_excerpt["Worksheet"];
				$integrationsArray[$key]["WorksheetID"] 	= $post_excerpt["WorksheetID"];
				$integrationsArray[$key]["Spreadsheet"] 	= $post_excerpt["Spreadsheet"];
				$integrationsArray[$key]["SpreadsheetID"] 	= $post_excerpt["SpreadsheetID"];
				$integrationsArray[$key]["Status"] 			= $value->post_status;
			} else {
				# Display Error, Because Data is corrected or Empty 
			}
		}
		# integration loop Ends
		# return  array with First Value as Bool and second one is integrationsArray array
		if ( count( $integrationsArray ) ) {
			return array( TRUE, $integrationsArray );
		} else {
			return array( FALSE, $integrationsArray );
		}
	}


	/**
	 * This Function Will get Data from Google Sheet, Then process that Data and Save that to the Site Option
	 * This Function Also save the integration ID to the Option table for AJAX function Use 
	 * This Function Also Display the Update View Page 
	 * @since     3.5.0
	 * @return     array   	it will not return anything, It just save data to the Site Option Table 
	*/
	public function wpgsi_update_wooProduct( $id ) {
		# Global database instance 
		global $wpdb;
		# Relation ID Check 
		if ( empty( $id ) ){
			echo"integration ID is Empty!";
			$this->wpgsi_log( get_class($this), __METHOD__, "519", "integration ID is Empty!" );
			exit;
		}
		#Product List Empty Array
		$productList 	= array();
		# Getting the integration 
		$Integration 	= get_post( $id );
		# Post Content 
		$post_content 	=  json_decode( $Integration->post_content, TRUE);
		# is set check
		if( ! isset( $post_content[0], $post_content[1] ) ){
			echo"Saved Relation array is Not Set !";
			$this->wpgsi_log( get_class($this), __METHOD__, "519", "Saved Relation array is Not Set !");
			exit;
		}
		# Empty Check 
		if( empty( $post_content[0]) AND empty( $post_content[1] ) ){
			echo" Saved Relation array is EMPTY !";
			$this->wpgsi_log( get_class($this), __METHOD__, "520", "Saved Relation array is EMPTY ! ");
			exit;
		}
		# Converting The Content to array
		$post_excerpt =  ( ! empty( $Integration->post_content ) ) ? json_decode(  $Integration->post_excerpt, TRUE) : array();
		# Empty check, if empty then return the error message 
		if( ! isset( $post_excerpt['Worksheet'] ) OR empty( $post_excerpt['Worksheet'] ) ){
			$this->wpgsi_log( get_class($this), __METHOD__, "522", "Worksheet Name or Worksheet is empty!");
			echo"Worksheet Name or Worksheet is empty!";
			exit;
		}
		# Empty check, if empty then return the error message 
		if( ! isset( $post_excerpt['SpreadsheetID'] ) OR empty( $post_excerpt['SpreadsheetID'] ) ){
			$this->wpgsi_log( get_class($this), __METHOD__, "523", "Worksheet Name or SpreadsheetID is empty!");
			echo"Worksheet Name or SpreadsheetID is empty!";
			exit;
		}
		# Integration Platform check 
		if( ! isset( $post_excerpt['DataSourceID'] ) OR empty( $post_excerpt['DataSourceID'] ) ){
			$this->wpgsi_log( get_class($this), __METHOD__, "524", "DataSourceID is Empty!");
			echo"DataSourceID is Empty!";
			exit;
		}
		# DataSourceID is not 'wc-new_product', 'wc-edit_product'
		if( ! in_array( $post_excerpt['DataSourceID'], array( 'wc-new_product', 'wc-edit_product' ) ) ){
			$this->wpgsi_log( get_class($this), __METHOD__, "525", "SORRY not this time : integration id is wc-new_product or wc-edit_product !");
			echo"SORRY not this time : integration id is wc-new_product or wc-edit_product !";
			exit;
		}
		# getting post content 
		$post_content = ( ! empty( $Integration->post_content) ) ? json_decode( $Integration->post_content, TRUE) : array();
		if( !isset( $post_content[1] ) OR empty( $post_content[1] ) ){
			$this->wpgsi_log( get_class($this), __METHOD__, "526", "Relation is Empty!");
			echo"Relation is Empty!";
			exit;
		}
		# Processing the relation 
		$relations 			= array_flip( array_filter( array_values( $post_content[1] ))) ; 
		$spreadsheets_id    = $post_excerpt['SpreadsheetID'];
		$worksheet_name     = $post_excerpt['Worksheet'];
		
		# Getting token 
		$token 	            = get_option( 'wpgsi_google_token', FALSE );
		if( !isset( $token['access_token'] ) OR empty( $token['access_token']) ){
			$this->wpgsi_log( get_class($this), __METHOD__, "527", "access_token is not set or Empty!");
			echo"access_token is not set or Empty!<br>";
			print_r( $token );
			exit;
		}
		
		# If passed parameter is Array and Not String  || Creating Query URL
		$request = wp_remote_get( 'https://sheets.googleapis.com/v4/spreadsheets/'. $spreadsheets_id . '/values/'. $worksheet_name . '?access_token='. $token['access_token'] );
		
		# Request Check and error Handel
		if (  is_wp_error( $request ) OR ! isset( $request['response']['code'])  OR $request['response']['code'] != 200) {
			# if request is array or object convert that and save to array || if request is String save it as is 
			if( is_array( $request ) OR is_object( $request )){
				$this->wpgsi_log( get_class($this), __METHOD__, "528", "Error: Google response is!" . json_encode( $request ) );
				echo "Error: Google response is : " . json_encode( $request );
			} else {
				$this->wpgsi_log( get_class($this), __METHOD__, "528", "Error: Google response is! ". $request );
				echo "Error: Google response is : " . $request ;
			}
		}
		
		# Json encoding the response Data 
		$dataArray  = ( isset( $request['body'] )  AND  !empty( $request['body'] ) ) ? json_decode( $request['body'], TRUE ) : array();
		
		# Check & balance, 
		if(! isset( $dataArray['values'] ) OR  empty( $dataArray['values'] ) ){
			$this->wpgsi_log( get_class($this), __METHOD__, "529", "dataArray is not array or dataArray is Empty!");
			echo"dataArray is not array or dataArray is Empty!";
			exit;
		}

		// For Testing 
		// echo"<pre>";
		// print_r($dataArray['values']);
		// echo"</pre>";

		# Empty holders
		# Now Update the Product to Store The Things 
		$updateDataForInput  	= array();
		# Now Update to the Product Meta
		$updateMetaDataForInput = array();
		# Looping the Spreadsheet data that got From Google Sheet
		foreach ( $dataArray['values'] as $key => $rowData ) {
			# match the value with the Relation 
			$updateData = $this->relationToValue( $rowData, $relations );
			# Getting Product Details 
			if( isset($updateData[1]['productID']) AND is_numeric( $updateData[1]['productID']) ){
				# *** Place for Improvement; Get all the Product ID in One Single Mysql Query;
				$product = wc_get_product( $updateData[1]['productID'] );
			} else {
				$product = false;
			}
			# Check AND Balance 
			if( isset(  $updateData[0] ) AND  $product ) {
				
				# For Product ID 
				if( isset($updateData[1]['productID']) AND !empty($updateData[1]['productID']) ){
					$updateDataForInput['ID'] = $updateData[1]['productID'];
				}
				# Assigning one-to-one relations
				# post_date relation,
				if( isset($updateData[1]['post_date']) AND !empty($updateData[1]['post_date']) ){
					$updateDataForInput['post_date'] = $updateData[1]['post_date'];
				}
				# Modified Date
				# Need to add this

				# Product Description relation || Content
				if( isset($updateData[1]['description']) AND !empty($updateData[1]['description']) ){
					$updateDataForInput['post_content'] = $updateData[1]['description'];
				}
				# Product post_title relation || Title
				if( isset($updateData[1]['name']) AND !empty($updateData[1]['name']) ){
					$updateDataForInput['post_title'] = $updateData[1]['name'];
				}
				# Product post_excerpt relation || Short description
				if( isset($updateData[1]['short_description']) AND !empty($updateData[1]['short_description']) ){
					$updateDataForInput['post_excerpt'] = $updateData[1]['short_description'];
				}
				# Product post_status relation || post status
				if( isset($updateData[1]['post_status']) AND !empty($updateData[1]['post_status']) ){
					$updateDataForInput['post_status'] = $updateData[1]['post_status'];
				}
				# Product post_status relation  || comment status
				if( isset($updateData[1]['comment_status']) AND !empty($updateData[1]['comment_status']) ){
					$updateDataForInput['comment_status'] = $updateData[1]['comment_status'];
				}
				# Product post_type relation || Post type 
				if( isset($updateData[1]['post_type']) AND !empty($updateData[1]['post_type']) ){
					$updateDataForInput['post_type'] = 'product';
				}
				# Product menu_order relation || Menu order
				if( isset($updateData[1]['menu_order']) AND !empty($updateData[1]['menu_order']) ){
					$updateDataForInput['menu_order'] = $updateData[1]['menu_order'];
				}

				# New Code For Meta Data 
				if( is_numeric($updateDataForInput['ID'])  AND ! empty( $updateDataForInput['ID'] ) ){
					# getting Product Meta 
					$product_meta = $wpdb->get_results( "SELECT  meta_key  FROM ". $wpdb->prefix. "postmeta WHERE post_id = " . $updateDataForInput['ID']  , ARRAY_A );
					# Looping the Product Meta Data;
					foreach ( $product_meta as  $value ) {
						if( $product->get_type() == 'simple' ) {
							# SKU
							if( $value['meta_key'] == '_sku' AND isset($updateData[1]['sku'])  ){
								$updateMetaDataForInput['_sku'] =  $updateData[1]['sku'];
							}
							# Price
							if( $value['meta_key'] == '_price' AND isset($updateData[1]['price'])  ){
								$updateMetaDataForInput['_price'] =  $updateData[1]['price'];
							}
							# Regular Price
							if( $value['meta_key'] == '_regular_price' AND isset($updateData[1]['regular_price'])  ){
								$updateMetaDataForInput['_regular_price'] =  $updateData[1]['regular_price'];
							}
							# Sale Price
							if( $value['meta_key'] == '_sale_price' AND isset($updateData[1]['sale_price'])  ){
								$updateMetaDataForInput['_sale_price'] 	=  $updateData[1]['sale_price'];
							}
							# Sales price date from
							if( $value['meta_key'] == '_sale_price_dates_from' AND isset($updateData[1]['date_on_sale_from'])  ){
								$updateMetaDataForInput['_sale_price_dates_from'] =  $updateData[1]['date_on_sale_from'];
							}
							# Sales price date to
							if( $value['meta_key'] == '_sale_price_dates_to' AND isset($updateData[1]['date_on_sale_to'])  ){
								$updateMetaDataForInput['_sale_price_dates_to'] =  $updateData[1]['date_on_sale_to'];
							}
							# tax status
							if( $value['meta_key'] == '_tax_status' AND isset($updateData[1]['tax_status'])  ){
								$updateMetaDataForInput['_tax_status'] =  $updateData[1]['tax_status'];
							}
							# tax class || tax class 
							if( $value['meta_key'] == '_tax_class' AND isset($updateData[1]['tax_class'])  ){
								$updateMetaDataForInput['_tax_class'] 	=   $updateData[1]['tax_class'];
							}
							# manage stock || manage stock
							if( $value['meta_key'] == '_manage_stock' AND isset($updateData[1]['manage_stock'])  ){
								$updateMetaDataForInput['_manage_stock']=  $updateData[1]['manage_stock'];
							}
							# backorders sell
							if( $value['meta_key'] == '_backorders' AND isset($updateData[1]['backorders'])  ){
								$updateMetaDataForInput['_backorders'] 	=  $updateData[1]['backorders'];
							}
							# weight
							if( $value['meta_key'] == '_weight' AND isset($updateData[1]['weight'])  ){
								$updateMetaDataForInput['_weight']	=  $updateData[1]['weight'];
							}
							# length
							if( $value['meta_key'] == '_length' AND isset($updateData[1]['length'])  ){
								$updateMetaDataForInput['_length'] 	=  $updateData[1]['length'];
							}
							# width
							if( $value['meta_key'] == '_width' AND isset($updateData[1]['width'])  ){
								$updateMetaDataForInput['_width']	=  $updateData[1]['width'];
							}
							# height
							if( $value['meta_key'] == '_height' AND isset($updateData[1]['height'])  ){
								$updateMetaDataForInput['_height']	=  $updateData[1]['height'];
							}
						}
						
						# For Unknown and Unrelated Meta Value 
						# New Code Starts
						if ( ! array_key_exists( $value['meta_key'], $updateMetaDataForInput ) AND isset( $updateData[1][ $value['meta_key'] ] ) ){
							$updateMetaDataForInput[ $value['meta_key'] ]  =   $updateData[1][ $value['meta_key'] ];
						}
					}
				}

				if( $updateDataForInput['ID'] ){
					$productList[ $updateDataForInput['ID'] ] = array(
						"postData" => $updateDataForInput,
						"metaData" => $updateMetaDataForInput
					);
				}
			} 
		}

		# Setting Update List on the Site Option cache *** important without saving it will n
		update_option( 'wpgsi_update_product_cache', $productList );
		update_option( 'wpgsi_update_product_integrationID', $Integration->ID );

		// For Testing!
		// echo"<pre>";
		// 	print_r( $productList );
		// 	print_r( $updateDataForInput );
		// 	print_r( $updateMetaDataForInput );
		// echo"</pre>";
		
		# Update Page layout 
		?>
		<h2><?php _e( 'Updating Products !', 'WpAdminStyle' ); ?></h2>

		<div class="wrap">
			<!-- <div id="icon-options-general" class="icon32"></div>
			<h1><?php esc_attr_e( 'Heading', 'WpAdminStyle' ); ?></h1> -->

			<div id="poststuff">

				<div id="post-body" class="metabox-holder columns-2">

					<!-- main content -->
					<div id="post-body-content">

						<div class="meta-box-sortables ui-sortable">

							<div class="postbox">

								<h2><span><?php esc_attr_e( 'Product list', 'wpgsi' ); ?></span></h2>

								<div class="inside">
									<!--<p></p> -->
									<?php
										echo "<i>Total Product : <b>" .  count( $productList ) ."</b></i>";
										echo "<br>";
										echo "<i><b style='color:red;'>Important :</b> <a target='_blank' href='".admin_url( 'edit.php?post_type=product&page=product_exporter')."'> Please backup your Product </a> Before Update.</i><br>";
										echo "<i>Only WooCommerce <b>simple product</b> data will be update. </i><br>";
										echo "<i>If there is no relation between <b> Product ID </b> with Spreadsheets <b> Product ID </b> Update will not work. </i><br>";
										echo "<i style='color:#F64823;'>Please don't leave or cancels this page till the update is complete. PHP has <b>max_execution_time</b> limit, if you leave the page update will stop. </i>";
										echo "<br><i>We recommend updating less than <b> 20 - 30 </b> products. If you have more Please use <a target='_blank' href='".admin_url( 'edit.php?post_type=product&page=product_importer')."'> Default offline Importer</a>. <b>It's a beta feature, Please use this feature responsibly </b>. <b>If it's not absolutely needed, Please Don't use this feature</b>.</i> ";
										echo "<br><i> Tips: <a target='_blank' href='".admin_url('admin.php?page=wpgsi&action=new')."'>Create a new integration </a>only for Update, with fewer relations so that fewer things change after every import.</i>";
										echo "<br><i> Tips: For Price update select price not regular_price or sale_price and should be single source .</i>";
									?>
								</div>
								<!-- .inside -->
							</div>
							<!-- .postbox -->
						</div>
						<!-- .meta-box-sortables .ui-sortable -->

					</div>
					<!-- post-body-content -->

					<!-- sidebar -->
					<div id="postbox-container-1" class="postbox-container">

						<div class="meta-box-sortables">

							<div class="postbox">

								<h2><span><?php esc_attr_e('Actions and information', 'wpgsi'); ?></span></h2>

								<div class="inside">
									<p> 
										<a href='#' id='wpgsiUpdateProduct' class='button-secondary'> <span style='padding-top:4px;' id='updateIcon' class='dashicons dashicons-image-rotate'></span> Click here to start update. </a> 
										<br>
										<b  style="vertical-align: middle; visibility: hidden;" id='updateAnimation'> <img  src="<?php echo plugins_url( 'css/loading.gif', __FILE__  ); ?>" alt="Product updating..."> Updating... </b> 
										<br>
										<b  style="vertical-align: middle; visibility: hidden;" id='updateDone'> <span class="dashicons dashicons-yes"></span> Done. </b> 
									</p>
								</div>
								<!-- .inside -->
							</div>
							<!-- .postbox -->
						</div>
						<!-- .meta-box-sortables -->
					</div>
					<!-- #postbox-container-1 .postbox-container -->
				</div>
				<!-- #post-body .metabox-holder .columns-2 -->
				<br class="clear">
			</div>
			<!-- #poststuff -->
		</div> <!-- .wrap -->
		<?php
		# ------------------------------------------------------------------
		# After Update to the Array Unset The Variable For Memory management 
		unset( $updateDataForInput );
		unset( $updateMetaDataForInput );
		unset( $productList );
	}

	/**
	 * This Function will create a relation between data and the Integration key || Its a Helper Function 
	 * @since     3.5.0
	 * @return     array   	it will not return  array of relation
	*/
	public function relationToValue( $data = [], $relations = [] ) {
		# data array empty check,
		if( empty( $data ) ){
			return array( FALSE, 'Data array is Empty! '.json_encode( $data, TRUE ) );
		}
		# relations array empty check 
		if( empty( $relations ) ){
			return array( FALSE, 'Relation array is Empty!' );
		}
		# Empty Array Holder 
		$rtnArr = array();
		# Looping starts 
		foreach( $relations as $key => $value  ){
			if ( isset( $data[$value] ) ){
				$rtnArr[ str_replace( array('{{','}}'),'', $key ) ] = ( $data[$value] == '--' )? '': trim( $data[$value] );
			}
		}
		# This is The return 
		if( ! empty( $rtnArr ) ){
			return array( TRUE, $rtnArr );
		} else {
			return array( FALSE, "Empty array!" );
		}
	}

	/**
	 * This Function Start The Product Update || it will get The Product Data From Option 
	 * After getting data from the Option Table It Will Update the Product 
	 * It will Update 12 Product at a time, A frontend Ajax Function will run this Function After every 20 second 
	 * Most Error Pron And Critical function ***
	 * @since     3.5.0
	*/
	public function wpgsi_productUpdateAJAX() {
		// For testing 
		// echo json_encode(array(false,"Ok response From Server"));
		// 1 start 
		// If integration is Active make it Pending First;
		// getting integration ID
		$integration_id = get_option('wpgsi_update_product_integrationID');
		// if ID Present proceed on 
		if( !empty(  $integration_id  ) ){
			// getting Post 
			$post = get_post( $integration_id );
			// if Post is Publish, Stop the Post by making it Pending 
			if ( $post->post_status == 'publish' ) {
				$update_post = array( 'ID' => $integration_id, 'post_status' => 'pending' );
				wp_update_post( $update_post, true );
			} 
		} else {
			$this->wpgsi_log( get_class($this), __METHOD__, "526", "Error: No integration ID on the Option Page.");
			echo json_encode( array( true, "Error: No integration ID on the Option Page." ) );
			exit;
		}
		// 1 End
		// Get The Product List array From Site Option 
		$productList = get_option( 'wpgsi_update_product_cache' );
		// Loop the Option Saved Product list 
		// Counting Variable $i  this will necessary to count the Loop to Break 
		$i = 0;
		// productList is not empty 
		if( ! empty( $productList ) ) {
			foreach( $productList  as $key => $dataArray){
				// increment the Counter;
				$i++ ;
				// Updating Post meta 
				$r =@ wp_update_post( $dataArray['postData'], true );
				// Error Checking
				if( is_wp_error( $r) ) {
					// Error Message handle
					// keep that in the Log 
					print_r( $r->get_error_message() );
					// Keeping the Log
					$this->wpgsi_log( get_class($this), __METHOD__, "527", "Error: ".$r->get_error_message() );
				} else {
					// Updating Product Meta 
					if ( ! empty( $r )  ) {
						foreach( $dataArray['metaData'] as $meta_key => $meta_value ) {
							update_post_meta( $key, $meta_key, $meta_value );
						}
					}
				}
				// unset the inserted array item 
				unset( $productList[$key] );
				// Break the Loop after 100
				if( $i == 12 ) {
					break;
				}
			}
			// Update Product list cache
			update_option( 'wpgsi_update_product_cache', $productList );
			// returning FeedBack 
			echo json_encode( array(false, "Product is updating ...! remaining ". count( $productList ) ) );
		} else {
			// Enable The off integration if Integration is Disable || Check first 
			$post = get_post( $integration_id );
			// if Post is Publish, Stop the Post by making it Pending 
			if ( $post->post_status == 'pending' ) {
				$update_post = array( 'ID' => $integration_id, 'post_status' => 'publish');
				wp_update_post( $update_post, true );
			}
			// Delete Product cache Too || no garbage 
			delete_option('wpgsi_update_product_cache');
			// Sending Feedback To connected JS 
			echo json_encode( array( true, "Everything seems ok." ) );
		}
		// Exit the Function
		exit;
	}
}

# information about WooCommerce Product Variation 
# WooCommerce Variation >> every WooCommerce Variable Product is also a Product In Post Table With Post Type as [product_variation]
# Post Parent ID is the Main product ID 