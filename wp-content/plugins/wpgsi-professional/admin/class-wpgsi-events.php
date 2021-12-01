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
class Wpgsi_Events {

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
	 * @since    1.0.0
	 * @access   Public
	 * @var      string    $Time   The current Time.
	 */
	Public $Time = "";

	/**
	 * List of active plugins.
	 * @since    1.0.0
	 * @access   Public
	 * @var      array    $active_plugins     List of active plugins .
	*/	
	public $active_plugins  = array();

	/**
	 * Define the class variables, arrays for Events to use ;
	 * @since    1.0.0s
	*/
	public function __construct( $plugin_name, $version) {
		# Set date 
		$date_format 			= get_option( 'date_format' );
		$this->Date				= ( $date_format ) ? current_time( $date_format  ) : current_time( 'd/m/Y' );
		# set time 
		$time_format 			= get_option( 'time_format' );
		$this->Time			  	= ( $date_format ) ? current_time( $time_format  ) : current_time( 'g:i a' );
		# Active Plugins 
		$this->active_plugins 	= get_option( 'active_plugins');														# Checking Active And Inactive Plugin 
	}
	# construct Ends Here 

	/**
	* For Testing purpose 
	*/
	public function wpgsi_event_notices() {
		// echo "<pre>";

		// echo "</pre>";
	}

	/**
	 *  WordPress new User Registered  HOOK's callback function
	 *  @param     int     $user_id     	  username
	 *  @param     int     $old_user_data     username
	 *  @since     1.0.0
	*/
	public function wpgsi_wordpress_newUser( $user_id ){
		# if There is a integration on  new user 
		if ( $this->wpgsi_integrations( 'wordpress_newUser' )[0] ) {
			# if get_userdata() and get_user_meta() Functions are exist;
			if ( function_exists( 'get_userdata' ) AND function_exists( 'get_user_meta' ) ) {
				$user_data 							= array();   
				$user 								= get_userdata($user_id);
				$userMeta							= get_user_meta($user_id);
				#
				$user_data['userID'] 				= ( isset( $user->ID ) 				&& !empty( $user->ID )) 			?  $user->ID		 	:	"";
				$user_data['userName'] 				= ( isset( $user->user_login ) 		&& !empty( $user->user_login )) 	?  $user->user_login 	:	"";
				$user_data['firstName'] 			= ( isset( $user->first_name ) 		&& !empty( $user->first_name )) 	?  $user->first_name 	:	"";
				$user_data['lastName'] 				= ( isset( $user->last_name ) 		&& !empty( $user->last_name)) 		?  $user->last_name  	:	"";
				$user_data['nickname'] 				= ( isset( $user->nickname ) 		&& !empty( $user->nickname )) 		?  $user->nickname		:	"";
				$user_data['displayName'] 			= ( isset( $user->display_name )	&& !empty( $user->display_name )) 	?  $user->display_name	:	"";
				$user_data['eventName'] 			= "New User";
				$user_data['description'] 			= ( isset( $userMeta['description'])&& is_array( $userMeta['description'] )) ? implode (", ", $userMeta['description'] ) : "";
				$user_data['userEmail'] 			= ( isset( $user->user_email ) 		&& !empty( $user->user_email )) 	?  $user->user_email 	 :	"";
				$user_data['userUrl'] 				= ( isset( $user->user_url ) 		&& !empty( $user->user_url )) 		? $user->user_url		 :	"";
				$user_data['userLogin'] 			= ( isset( $user->user_login ) 		&& !empty( $user->user_login )) 	? $user->user_login		 :	"";
				$user_data['userRegistrationDate'] 	= ( isset( $user->user_registered ) && !empty( $user->user_registered ))? $user->user_registered :  "";
				$user_data['userRole'] 				= ( isset( $user->roles ) 			&& is_array( $user->roles ) ) 		? implode (", ", $user->roles) : "";  
				# site Current Time
				$user_data['site_time'] 			= ( isset( $this->Time ) ) ? 	$this->Time	 :	'';
				$user_data['site_date'] 			= ( isset( $this->Date ) ) ? 	$this->Date	 :	'';
				# 
				$user_data["user_date_year"]	 	= date( 'Y', current_time( 'timestamp', 0 ) );
				$user_data["user_date_month"]		= date( 'm', current_time( 'timestamp', 0 ) );
				$user_data["user_date_date"]	 	= date( 'd', current_time( 'timestamp', 0 ) );
				$user_data["user_date_time"]		= date( 'H:i',current_time('timestamp', 0 ) );
				# 
				if ( wpgsi_fs()->is__premium_only() ) {
					if ( wpgsi_fs()->can_use_premium_code() ){
						# User Meta Data Starts;
						# empty Holder array;
						$metaOutPut = array();	
						# Global Db object;
						global $wpdb;
						# execute Query;
						$usersMetaKeyValue = $wpdb->get_results( "SELECT * FROM $wpdb->usermeta WHERE user_id = " . $user_id, ARRAY_A );
						# get Distinct Keys;
						$metaKeys = $this->wpgsi_users_metaKeys();
						# Check and Balance for all the Meta keys
						if ( $metaKeys[0] &&  ! empty( $usersMetaKeyValue ) ){
							# populating Output array in revers with  empty value
							foreach ( $metaKeys[1]  as $key => $value ){
								$metaOutPut[$value] = "--";
							}
							# Looping the Meta key & value of Certain Comment And Populating the $metaOutPut Key array with Value 
							foreach ( $usersMetaKeyValue  as $oneArray ) {
								if ( is_array( $oneArray ) && isset( $oneArray['meta_key'], $metaOutPut[ $oneArray[ 'meta_key' ] ], $oneArray[ 'meta_value' ] ) ){
									# Convert text to  an array then JSON for reducing the String 
									$isArrayTest = @unserialize( $oneArray[ 'meta_value' ] );
									if ( $isArrayTest == null ) {
										$metaOutPut[ $oneArray['meta_key'] ] = $oneArray[ 'meta_value' ];
									} else {
										$metaOutPut[ $oneArray['meta_key'] ] =  $isArrayTest;
									}
								}
							}

						}
						# Append New metaOutPut array to $commentData data array;
						$user_data = array_merge( $user_data, $metaOutPut);
						# User Meta Data Ends 
					}
				}
				# Action
				if ( $user_id ){
					$r = $this->wpgsi_eventBoss('wp', 'wordpress_newUser', $user_data, $user_id );
				} else {
					$this->wpgsi_log( get_class($this), __METHOD__, "701", "Error: wordpress_newUser fired but no User ID . ".json_encode( array( $user_id, $user_data ) ) );
				}
			} else {
				$this->wpgsi_log( get_class($this), __METHOD__, "702", "Error: get_userdata or get_user_meta is not Exist" );
			}
		}
	}

	/**
	 *  WordPress new User Profile Update HOOK's callback function
	 *  @param     int     $user_id     		user ID
	 *  @param     int     $old_user_data     	user Data
	 *  @since     1.0.0
	*/
	public function wpgsi_wordpress_profileUpdate( $user_id, $old_user_data ){
		# if There is a integration on User profile update 
		if ( $this->wpgsi_integrations( 'wordpress_UserProfileUpdate' )[0] ) {
			# if get_userdata() and get_user_meta() Functions are exist
			if ( function_exists( 'get_userdata' ) && function_exists( 'get_user_meta' ) && ! empty( $user_id )  ) {
				$user_data 							= array(); 
				$user 								= get_userdata($user_id);
				$userMeta							= get_user_meta($user_id);
				#
				$user_data['userID'] 				= ( isset( $user->ID ) 				&& !empty( $user->ID )) 			?  $user->ID		 	:	"";
				$user_data['userName'] 				= ( isset( $user->user_login ) 		&& !empty( $user->user_login )) 	?  $user->user_login 	:	"";
				$user_data['firstName'] 			= ( isset( $user->first_name ) 		&& !empty( $user->first_name )) 	?  $user->first_name 	:	"";
				$user_data['lastName'] 				= ( isset( $user->last_name ) 		&& !empty( $user->last_name)) 		?  $user->last_name  	:	"";
				$user_data['nickname'] 				= ( isset( $user->nickname ) 		&& !empty( $user->nickname )) 		?  $user->nickname		:	"";
				$user_data['displayName'] 			= ( isset( $user->display_name )	&& !empty( $user->display_name )) 	?  $user->display_name	:	"";
				$user_data['eventName'] 			= "New User";
				$user_data['description'] 			= ( isset( $userMeta['description'])&& is_array( $userMeta['description'] )) ? implode (", ", $userMeta['description'] ) : "";
				$user_data['userEmail'] 			= ( isset( $user->user_email ) 		&& !empty( $user->user_email )) 	?  $user->user_email 	 :	"";
				$user_data['userUrl'] 				= ( isset( $user->user_url ) 		&& !empty( $user->user_url )) 		? $user->user_url		 :	"";
				$user_data['userLogin'] 			= ( isset( $user->user_login ) 		&& !empty( $user->user_login )) 	? $user->user_login		 :	"";
				$user_data['userRegistrationDate'] 	= ( isset( $user->user_registered ) && !empty( $user->user_registered ))? $user->user_registered :  "";
				$user_data['userRole'] 				= ( isset( $user->roles ) 			&& is_array( $user->roles ) ) 		? implode (", ", $user->roles) : "";  
				# site Current Time
				$user_data['site_time'] 			= ( isset( $this->Time ) ) ? 	$this->Time		:	'';
				$user_data['site_date'] 			= ( isset( $this->Date ) ) ? 	$this->Date		:	'';
				# New Code Starts From Here 
				$user_data["user_date_year"]	 	= date( 'Y', current_time( 'timestamp', 0 ) );
				$user_data["user_date_month"]		= date( 'm', current_time( 'timestamp', 0 ) );
				$user_data["user_date_date"]	 	= date( 'd', current_time( 'timestamp', 0 ) );
				$user_data["user_date_time"]		= date( 'H:i',current_time('timestamp', 0 ) );
				#
				if ( wpgsi_fs()->is__premium_only() ) {
					if ( wpgsi_fs()->can_use_premium_code() ){
						# User Meta Data Starts
						# empty Holder array  for user meta keys
						$metaOutPut = array();	
						# Global Db object 
						global $wpdb;
						# execute Query
						$usersMetaKeyValue = $wpdb->get_results( "SELECT * FROM $wpdb->usermeta WHERE user_id = " . $user_id, ARRAY_A );
						# get Distinct Keys;
						$metaKeys = $this->wpgsi_users_metaKeys();
						# Check and Balance for all the Meta keys
						if ( $metaKeys[0]  &&  ! empty( $usersMetaKeyValue ) ){
							# populating Output array in revers with  empty value
							foreach ( $metaKeys[1]  as $key => $value ){
								$metaOutPut[$value] = "--";
							}
							# Looping the Meta key & value of Certain Comment And Populating the $metaOutPut Key array with Value 
							foreach ( $usersMetaKeyValue  as $oneArray ) {
								if ( is_array( $oneArray ) && isset( $oneArray['meta_key'], $metaOutPut[ $oneArray[ 'meta_key' ] ], $oneArray[ 'meta_value' ] ) ){
									# Convert text to  an array then JSON for reducing the String 
									$isArrayTest = @unserialize( $oneArray[ 'meta_value' ] );
									if ( $isArrayTest == null ) {
										$metaOutPut[ $oneArray['meta_key'] ] = $oneArray[ 'meta_value' ];
									} else {
										$metaOutPut[ $oneArray['meta_key'] ] =  $isArrayTest;
									}
								}
							}
						}
						# Append New metaOutPut array to $commentData data array;
						$user_data = array_merge( $user_data, $metaOutPut);
						# User Meta Data Ends 
					}
				}
				# Action
				if ( $user_id && $user->ID  ) {
					$r = $this->wpgsi_eventBoss('wp', 'wordpress_UserProfileUpdate', $user_data, $user_id );
				} else {
					$this->wpgsi_log( get_class($this), __METHOD__, "703", "Error: wordpress_UserProfileUpdate fired but no User ID . ".json_encode( array( $user_id, $user->ID, $user_data ) ) );
				}

			} else {
				$this->wpgsi_log( get_class($this), __METHOD__, "704", "Error:  get_userdata or get_user_meta or User id is not Exist" );
			}
		}
	}

	/**
	 *  WordPress Delete User HOOK's callback function
	 *  @param    int     $user_id     user ID
	 *  @since    1.0.0
	*/
	public function wpgsi_wordpress_deleteUser( $user_id ){
		# if There is a integration on Delete user  
		if ( $this->wpgsi_integrations( 'wordpress_deleteUser' )[0] ) {
			# if get_userdata() and get_user_meta() Functions are exist
			if ( function_exists( 'get_userdata' ) && function_exists( 'get_user_meta' ) && ! empty( $user_id )   ) {
				# Empty Holder 
				$user_data 							= array() ; 
				$user 								= get_userdata($user_id);
				$userMeta							= get_user_meta($user_id);
				#
				$user_data['userID'] 				= ( isset( $user->ID ) 				&& !empty( $user->ID )) 			?  $user->ID		 	:	"";
				$user_data['userName'] 				= ( isset( $user->user_login ) 		&& !empty( $user->user_login )) 	?  $user->user_login 	:	"";
				$user_data['firstName'] 			= ( isset( $user->first_name ) 		&& !empty( $user->first_name )) 	?  $user->first_name 	:	"";
				$user_data['lastName'] 				= ( isset( $user->last_name ) 		&& !empty( $user->last_name)) 		?  $user->last_name  	:	"";
				$user_data['nickname'] 				= ( isset( $user->nickname ) 		&& !empty( $user->nickname )) 		?  $user->nickname		:	"";
				$user_data['displayName'] 			= ( isset( $user->display_name )	&& !empty( $user->display_name )) 	?  $user->display_name	:	"";
				$user_data['eventName'] 			= "New User";
				$user_data['description'] 			= ( isset( $userMeta['description'])&& is_array( $userMeta['description'] )) ? implode (", ", $userMeta['description'] ) : "";
				$user_data['userEmail'] 			= ( isset( $user->user_email ) 		&& !empty( $user->user_email )) 	?  $user->user_email 	 :	"";
				$user_data['userUrl'] 				= ( isset( $user->user_url ) 		&& !empty( $user->user_url )) 		? $user->user_url		 :	"";
				$user_data['userLogin'] 			= ( isset( $user->user_login ) 		&& !empty( $user->user_login )) 	? $user->user_login		 :	"";
				$user_data['userRegistrationDate'] 	= ( isset( $user->user_registered ) && !empty( $user->user_registered ))? $user->user_registered :  "";
				$user_data['userRole'] 				= ( isset( $user->roles ) 			&& is_array( $user->roles ) ) 		? implode (", ", $user->roles) : "";  
				# site Current Time
				$user_data['site_time'] 			= ( isset( $this->Time ) ) ? 	$this->Time		:	'';
				$user_data['site_date'] 			= ( isset( $this->Date ) ) ? 	$this->Date		:	'';
				#
				$user_data["user_date_year"]	 	= date( 'Y', current_time( 'timestamp', 0 ) );
				$user_data["user_date_month"]		= date( 'm', current_time( 'timestamp', 0 ) );
				$user_data["user_date_date"]	 	= date( 'd', current_time( 'timestamp', 0 ) );
				$user_data["user_date_time"]		= date( 'H:i',current_time('timestamp', 0 ) );
				# 
				if ( wpgsi_fs()->is__premium_only() ) {
					if ( wpgsi_fs()->can_use_premium_code() ){
						# User Meta Data Starts
						# empty Holder array  for user meta keys
						$metaOutPut = array();	
						# Global Db object 
						global $wpdb;
						# execute Query
						$usersMetaKeyValue = $wpdb->get_results( "SELECT * FROM $wpdb->usermeta WHERE user_id = " . $user_id, ARRAY_A );
						# get Distinct Keys;
						$metaKeys = $this->wpgsi_users_metaKeys();
						# Check and Balance for all the Meta keys
						if ( $metaKeys[0] &&  ! empty( $usersMetaKeyValue ) ) {
							# populating Output array in revers with  empty value
							foreach ( $metaKeys[1]  as $key => $value ){
								$metaOutPut[$value] = "--";
							}
							# Looping the Meta key & value of Certain Comment And Populating the $metaOutPut Key array with Value 
							foreach ( $usersMetaKeyValue  as $oneArray ) {
								if ( is_array( $oneArray ) && isset( $oneArray['meta_key'], $metaOutPut[ $oneArray[ 'meta_key' ] ], $oneArray[ 'meta_value' ] ) ){
									# Convert text to  an array then JSON for reducing the String 
									$isArrayTest = @unserialize( $oneArray[ 'meta_value' ] );
									if ( $isArrayTest == null ) {
										$metaOutPut[ $oneArray['meta_key'] ] = $oneArray[ 'meta_value' ];
									} else {
										$metaOutPut[ $oneArray['meta_key'] ] =  $isArrayTest;
									}
								}
							}
						}
						# Append New metaOutPut array to $commentData data array;
						$user_data = array_merge( $user_data, $metaOutPut);
						# User Meta Data Ends 
					}
				}
				# Action
				if ( $user_id && $user->ID ){
					$r = $this->wpgsi_eventBoss('wp', 'wordpress_deleteUser', $user_data, $user_id );
				} else {
					$this->wpgsi_log( get_class($this), __METHOD__, "705", "Error: wordpress_deleteUser fired but no User ID . ". json_encode( array( $user_id, $user->ID,  $user_data ) ) );
				}

			} else {
				$this->wpgsi_log( get_class($this), __METHOD__, "706", "Error: get_userdata or get_user_meta or user_id is not Exist" );
			}
		}
	}

	/**
	 * User Logged in  HOOK's callback function
	 * @param     int     $username     username
	 * @param     int     $user     	user
	 * @since     1.0.0
	*/
	public function wpgsi_wordpress_userLogin( $username, $user ){
		# if There is a integration on user login 
		if ( $this->wpgsi_integrations( 'wordpress_userLogin' )[0] ) {
			# if get_user_meta() function and $user->ID exist
			if ( function_exists( 'get_user_meta' ) AND  ! empty( $user->ID ) ) {
				# Pre-populating User Data 
				$user_data 							= array(); 
				$userMeta							= get_user_meta( $user->ID );
				#
				$user_data['userID'] 				= ( isset( $user->ID ) 				&& !empty( $user->ID )) 			?  $user->ID		 			:	"";
				$user_data['userName'] 				= ( isset( $user->user_login ) 		&& !empty( $user->user_login )) 	?  $user->user_login 			:	"";
				$user_data['firstName'] 			= ( isset( $user->first_name ) 		&& !empty( $user->first_name )) 	?  $user->first_name 			:	"";
				$user_data['lastName'] 				= ( isset( $user->last_name ) 		&& !empty( $user->last_name)) 		?  $user->last_name  			:	"";
				$user_data['nickname'] 				= ( isset( $user->nickname ) 		&& !empty( $user->nickname )) 		?  $user->nickname				:	"";
				$user_data['displayName'] 			= ( isset( $user->display_name )	&& !empty( $user->display_name )) 	?  $user->display_name			:	"";
				$user_data['eventName'] 			= "New User";
				$user_data['description'] 			= ( isset( $userMeta['description'])&& is_array( $userMeta['description'] )) ? implode (", ", $userMeta['description'] ) : "";
				$user_data['userEmail'] 			= ( isset( $user->user_email ) 		&& !empty( $user->user_email )) 	?  $user->user_email 	 		:	"";
				$user_data['userUrl'] 				= ( isset( $user->user_url ) 		&& !empty( $user->user_url )) 		? $user->user_url		 		:	"";
				$user_data['userLogin'] 			= ( isset( $user->user_login ) 		&& !empty( $user->user_login )) 	? $user->user_login		 		:	"";
				$user_data['userRegistrationDate'] 	= ( isset( $user->user_registered ) && !empty( $user->user_registered ))? $user->user_registered 		:  	"";
				$user_data['userRole'] 				= ( isset( $user->roles ) 			&& is_array( $user->roles ) ) 		? implode (", ", $user->roles) 	: 	"";  
				#
				$user_data['userLoginTime'] 		= $this->Time;
				$user_data['userLoginDate'] 		= $this->Date;
				#
				# site Current Time
				$user_data['site_time'] 			= ( isset( $this->Time ) ) ? 	$this->Time		:	'';
				$user_data['site_date'] 			= ( isset( $this->Date ) ) ? 	$this->Date		:	'';
				# New Code Starts From Here 
				$user_data["user_date_year"]	 	= date( 'Y', current_time( 'timestamp', 0 ) );
				$user_data["user_date_month"]		= date( 'm', current_time( 'timestamp', 0 ) );
				$user_data["user_date_date"]	 	= date( 'd', current_time( 'timestamp', 0 ) );
				$user_data["user_date_time"]		= date( 'H:i', current_time( 'timestamp', 0 ) );
				# New Code Ends Here 
				if ( wpgsi_fs()->is__premium_only() ) {
					if ( wpgsi_fs()->can_use_premium_code() ){
						# User Meta Data Starts
						# empty Holder array  for user meta keys
						$metaOutPut = array();	
						# Global Db object 
						global $wpdb;
						# execute Query
						$usersMetaKeyValue = $wpdb->get_results( "SELECT * FROM $wpdb->usermeta WHERE user_id = " . $user->ID , ARRAY_A );
						# get Distinct Keys;
						$metaKeys = $this->wpgsi_users_metaKeys();
						# Check and Balance for all the Meta keys
						if ( $metaKeys[0] &&  ! empty( $usersMetaKeyValue ) ){
							# populating Output array in revers with  empty value
							foreach ( $metaKeys[1]  as $key => $value ){
								$metaOutPut[$value] = "--";
							}
							# Looping the Meta key & value of Certain Comment And Populating the $metaOutPut Key array with Value 
							foreach ( $usersMetaKeyValue  as $oneArray ) {
								if ( is_array( $oneArray ) && isset( $oneArray['meta_key'], $metaOutPut[ $oneArray[ 'meta_key' ] ], $oneArray[ 'meta_value' ] ) ){
									# Convert text to  an array then JSON for reducing the String 
									$isArrayTest = @unserialize( $oneArray[ 'meta_value' ] );
									if ( $isArrayTest == null ) {
										$metaOutPut[ $oneArray['meta_key'] ] = $oneArray[ 'meta_value' ];
									} else {
										$metaOutPut[ $oneArray['meta_key'] ] =  $isArrayTest;
									}
								}
							}

						}
						# Append New metaOutPut array to $commentData data array;
						$user_data = array_merge( $user_data, $metaOutPut);
						# User Meta Data Ends 
					}
				}

				# Action,  Sending Data to Event Boss
				$r = $this->wpgsi_eventBoss('wp', 'wordpress_userLogin', $user_data, $user->ID );
			} else {
				$this->wpgsi_log( get_class($this), __METHOD__, "707", "Error: user->ID Not Exist OR get_user_meta is not Exist" );
			}
		}
	}

	/**
	 * User wp_logout  HOOK's callback function
	 * @since   1.0.0
	*/
	public function wpgsi_wordpress_userLogout( $userInfo ){
		# if There is a integration on user logout  
		if ( $this->wpgsi_integrations( 'wordpress_userLogout' )[0] ) {
			# if wp_get_current_user() function and wp_get_current_user()->ID exist
			if (  function_exists( 'wp_get_current_user' ) && !empty( wp_get_current_user()->ID ) ) {
				# Pre-populating User Data 
				$user 								= wp_get_current_user();
				$user_data 							= array(); 
				#
				$user_data['userID'] 				= ( isset( $user->ID ) 				&& !empty( $user->ID )) 			?  $user->ID		 			:	"";
				$user_data['userName'] 				= ( isset( $user->user_login ) 		&& !empty( $user->user_login )) 	?  $user->user_login 			:	"";
				$user_data['firstName'] 			= ( isset( $user->first_name ) 		&& !empty( $user->first_name )) 	?  $user->first_name 			:	"";
				$user_data['lastName'] 				= ( isset( $user->last_name ) 		&& !empty( $user->last_name)) 		?  $user->last_name  			:	"";
				$user_data['nickname'] 				= ( isset( $user->nickname ) 		&& !empty( $user->nickname )) 		?  $user->nickname				:	"";
				$user_data['displayName'] 			= ( isset( $user->display_name )	&& !empty( $user->display_name )) 	?  $user->display_name			:	"";
				$user_data['eventName'] 			= "New User";
				$user_data['description'] 			= ( isset( $userMeta['description'])&& is_array( $userMeta['description'] )) ? implode (", ", $userMeta['description'] ) : "";
				$user_data['userEmail'] 			= ( isset( $user->user_email ) 		&& !empty( $user->user_email )) 	?  $user->user_email 	 		:	"";
				$user_data['userUrl'] 				= ( isset( $user->user_url ) 		&& !empty( $user->user_url )) 		? $user->user_url		 		:	"";
				$user_data['userLogin'] 			= ( isset( $user->user_login ) 		&& !empty( $user->user_login )) 	? $user->user_login		 		:	"";
				$user_data['userRegistrationDate'] 	= ( isset( $user->user_registered ) && !empty( $user->user_registered ))? $user->user_registered 		: 	"";
				$user_data['userRole'] 				= ( isset( $user->roles ) 			&& is_array( $user->roles ) ) 		? implode (", ", $user->roles) 	: 	"";  
				#
				$user_data['userLogoutTime'] 		= $this->Time;
				$user_data['userLogoutDate'] 		= $this->Date;
				#
				# site Current Time
				$user_data['site_time'] 			= ( isset( $this->Time ) ) ? 	$this->Time		:	'';
				$user_data['site_date'] 			= ( isset( $this->Date ) ) ? 	$this->Date		:	'';
				# New Code Starts From Here 
				$user_data["user_date_year"]	 	= date( 'Y', current_time( 'timestamp', 0 ) );
				$user_data["user_date_month"]		= date( 'm', current_time( 'timestamp', 0 ) );
				$user_data["user_date_date"]	 	= date( 'd', current_time( 'timestamp', 0 ) );
				$user_data["user_date_time"]		= date( 'H:i', current_time('timestamp', 0) );
				# New Code Ends Here 
				if ( wpgsi_fs()->is__premium_only() ) {
					if ( wpgsi_fs()->can_use_premium_code() ){
						# User Meta Data Starts
						# empty Holder array  for user meta keys
						$metaOutPut = array();	
						# Global Db object 
						global $wpdb;
						# execute Query
						$usersMetaKeyValue = $wpdb->get_results( "SELECT * FROM $wpdb->usermeta WHERE user_id = " . $user->ID , ARRAY_A );
						# get Distinct Keys;
						$metaKeys = $this->wpgsi_users_metaKeys();
						# Check and Balance for all the Meta keys
						if ( $metaKeys[0] &&  ! empty( $usersMetaKeyValue ) ){
							# populating Output array in revers with  empty value
							foreach ( $metaKeys[1]  as $key => $value ){
								$metaOutPut[$value] = "--";
							}
							# Looping the Meta key & value of Certain Comment And Populating the $metaOutPut Key array with Value 
							foreach ( $usersMetaKeyValue  as $oneArray ) {
								if ( is_array( $oneArray ) && isset( $oneArray['meta_key'], $metaOutPut[ $oneArray[ 'meta_key' ] ], $oneArray[ 'meta_value' ] ) ){
									# Convert text to  an array then JSON for reducing the String 
									$isArrayTest = @unserialize( $oneArray[ 'meta_value' ] );
									if ( $isArrayTest == null ) {
										$metaOutPut[ $oneArray['meta_key'] ] = $oneArray[ 'meta_value' ];
									} else {
										$metaOutPut[ $oneArray['meta_key'] ] =  $isArrayTest ;
									}
								}
							}
						}
						# Append New metaOutPut array to $commentData data array;
						$user_data = array_merge( $user_data, $metaOutPut);
						# User Meta Data Ends 
					}
				}
				# Action
				if ( $user->ID ){
					$r = $this->wpgsi_eventBoss('wp', 'wordpress_userLogout', $user_data, $user->ID );
				} else {
					$this->wpgsi_log( get_class($this), __METHOD__, "708", "Error:  wordpress_userLogout fired but no User ID . ". json_encode( array( $user->ID, $user_data ) ) );
				}

			} else {
				$this->wpgsi_log( get_class($this), __METHOD__, "709", "Error: User ID OR  Function  wp_get_current_user() is not exists " );
			}
		}
	}

	/**
	 * WordPress Post   HOOK's callback function
	 * @since     1.0.0   
	 * @param     int     $post_id      Order ID
	 * @param     int     $post    		Order ID
	 * @param     int     $update     	Product Post 
	*/
	public function wpgsi_wordpress_post( $post_id, $post, $update ) {
		# Check Empty Post Id or Post 
		if ( empty( $post_id ) OR  empty( $post ) ){
			return;
		}
		# Default Post type array 
		$postType = array('post' => 'Post', 'page' => "Page");
		if ( wpgsi_fs()->is__premium_only() ){
			if ( wpgsi_fs()->can_use_premium_code() ) {
				# getting CPTs
				$cpts = $this->wpgsi_allCptEvents();
				if ( $cpts[0] ){
					$postType = array_merge( $postType, $cpts[1] );
				}
			}
		}
		# If Free and Post type is Not Post or Page return
		if ( ! isset( $postType[ $post->post_type ]  ) ){
			return;
		}
		# Setting the Values 
		$post_data 							= array() ; 
		$userData 							= get_userdata( $post->post_author );		
		$post_data['postID'] 				= $post->ID;
		#
		$post_data['post_authorID'] 		= ( isset( $post->post_author ) ) 		? 	$post->post_author		:	''; 		// property_exists // isset 
		$post_data['authorUserName'] 		= ( isset( $userData->user_login ) ) 	? 	$userData->user_login	:	''; 		//
		$post_data['authorDisplayName'] 	= ( isset( $userData->display_name ) )	? 	$userData->display_name :	''; 
		$post_data['authorEmail'] 			= ( isset( $userData->user_email ) ) 	? 	$userData->user_email 	:	''; 
		$post_data['authorRole'] 			= ( isset( $userData->roles ) && is_array( $userData->roles ) ) ? implode (", ", $userData->roles) : "";  
		#
		$post_data['post_title'] 			= ( isset( $post->post_title ) ) 		? 	$post->post_title		:	'';
		$post_data['post_date'] 			= ( isset( $post->post_date ) ) 		? 	$post->post_date		:	'';
		$post_data['post_date_gmt'] 		= ( isset( $post->post_date_gmt ) ) 	? 	$post->post_date_gmt	:	'';
		# site Current Time
		$post_data['site_time'] 			= ( isset( $this->Time ) ) 				? 	$this->Time				:	'';
		$post_data['site_date'] 			= ( isset( $this->Date ) ) 				? 	$this->Date				:	'';
		# New Code Starts From Here
		# date of the Post Creation 
		$post_data["post_date_year"]		= ( isset( $post->ID ) AND !empty( get_the_date('Y',   $post->ID ) ) ) ? 	date( 'Y', strtotime( "$post->post_modified" ) )	:	''; 
		$post_data["post_date_month"]		= ( isset( $post->ID ) AND !empty( get_the_date('m',   $post->ID ) ) ) ? 	date( 'm', strtotime( "$post->post_modified" ) )	:	''; 
		$post_data["post_date_date"]		= ( isset( $post->ID ) AND !empty( get_the_date('d',   $post->ID ) ) ) ? 	date( 'd', strtotime( "$post->post_modified" ) )	:	'';
		$post_data["post_date_time"]		= ( isset( $post->ID ) AND !empty( get_the_date('H:i', $post->ID ) ) ) ? 	date( 'H:i',strtotime("$post->post_modified" ) )	:	'';
		# date of Post Modification 
		$post_data["post_modified_year"]	= ( isset( $post->post_modified ) AND !empty( $post->post_modified ) ) ? 	date( 'Y', strtotime( "$post->post_modified" ) )	:	'';
		$post_data["post_modified_month"]	= ( isset( $post->post_modified ) AND !empty( $post->post_modified ) ) ? 	date( 'm', strtotime( "$post->post_modified" ) )	:	'';
		$post_data["post_modified_date"]	= ( isset( $post->post_modified ) AND !empty( $post->post_modified ) ) ? 	date( 'd', strtotime( "$post->post_modified" ) )	:	'';
		$post_data["post_modified_time"]	= ( isset( $post->post_modified ) AND !empty( $post->post_modified ) ) ? 	date( 'H:i', strtotime( "$post->post_modified" ) )	:	'';
		# New Code Ends Here
		$post_data['post_content'] 			= ( isset( $post->post_content ) ) ? 	$post->post_content			:	'';
		$post_data['post_excerpt'] 			= ( isset( $post->post_excerpt ) ) ? 	$post->post_excerpt			:	'';
		$post_data['post_status'] 			= ( isset( $post->post_status ) ) ? 	$post->post_status			:	'';
		$post_data['comment_status']		= ( isset( $post->comment_status )) ? 	$post->comment_status		:	'';
		$post_data['ping_status'] 			= ( isset( $post->ping_status ) ) ? 	$post->ping_status			:	'';
		$post_data['post_password'] 		= ( isset( $post->post_password ) ) ? 	$post->post_password		:	'';
		$post_data['post_name'] 			= ( isset( $post->post_name ) ) ? 		$post->post_name			:	'';
		$post_data['to_ping'] 				= ( isset( $post->to_ping ) ) ? 		$post->to_ping				:	'';
		$post_data['pinged'] 				= ( isset( $post->pinged ) ) ? 			$post->pinged				:	'';
		$post_data['post_modified'] 		= ( isset( $post->post_modified ) ) ?	$post->post_modified		:	'';
		$post_data['post_modified_gmt']		= ( isset( $post->post_modified_gmt ))? $post->post_modified_gmt	:	'';
		$post_data['post_parent'] 			= ( isset( $post->post_parent ) ) ? 	$post->post_parent			:	'';
		$post_data['guid']  				= ( isset( $post->guid ) ) ? 			$post->guid					:	'';
		$post_data['menu_order'] 			= ( isset( $post->menu_order ) ) ? 		$post->menu_order 			:	'';
		$post_data['post_type'] 			= ( isset( $post->post_type ) ) ? 		$post->post_type			:	'';		
		$post_data['post_mime_type'] 		= ( isset( $post->post_mime_type )) ? 	$post->post_mime_type 		:	'';
		$post_data['comment_count'] 		= ( isset( $post->comment_count ) ) ? 	$post->comment_count  		:	'';
		$post_data['filter'] 				= ( isset( $post->filter ) ) ?			$post->filter 				:	'';

		if ( wpgsi_fs()->is__premium_only() ) {
			if ( wpgsi_fs()->can_use_premium_code() ){
				# Post Meta Data portion Starts
				# empty Holder array 
				$metaOutPut = array();	
				# Global Db object 
				global $wpdb;
				# Execute Query for getting Post or Page meta Data;
				$metaKeyValue = $wpdb->get_results( "SELECT * FROM $wpdb->postmeta WHERE post_id = " . $post->ID , ARRAY_A );
				# get Distinct Keys;
				if ( $post->post_type == 'post' ) {
					$metaKeys = $this->wpgsi_posts_metaKeys();
				} elseif ($post->post_type == 'page') {
					$metaKeys = $this->wpgsi_pages_metaKeys();
				} else {
					# Setting Meta, getting those Meta from  wpgsi_allCptEvents() function Where;
					if ( isset( $cpts[4] ) AND !empty( $cpts[4] )  ){
						$metaKeys = array( TRUE, $cpts[4] );
					}else{
						$metaKeys = array( FALSE, "" );
					}
				}

				# Check and Balance for all the Meta keys
				if ( $metaKeys[0] &&  ! empty( $metaKeyValue ) ){
					# pre-populating Output array in revers with  empty -- value
					foreach ( $metaKeys[1]  as $key => $value ) {
						$metaOutPut[$value] = "--";
					}
					# Looping the Meta key & value of Certain Comment And Populating the $metaOutPut Key array with Value 
					foreach ( $metaKeyValue  as $oneArray ){
						if ( is_array( $oneArray ) && isset( $oneArray['meta_key'], $metaOutPut[ $oneArray[ 'meta_key' ] ], $oneArray[ 'meta_value' ] ) ){
							# Convert text to  an array then JSON for reducing the String 
							$isArrayTest = @unserialize( $oneArray[ 'meta_value' ] );
							if ( $isArrayTest == null ) {
								$metaOutPut[ $oneArray['meta_key'] ] = $oneArray[ 'meta_value' ];
							} else {
								$metaOutPut[ $oneArray['meta_key'] ] =  $isArrayTest;
							}
						}
					}
				}
				# Append New metaOutPut array to $commentData data array;
				$post_data = array_merge( $post_data, $metaOutPut );
				# Post Meta Data portion Ends 
			}
		}

		# if Post type is Post
		if ( $post->post_type == 'post' ) {
			# getting Time Difference 
			if ( ! empty( $post->post_date ) AND  ! empty( $post->post_modified ) ){
				$post_time_diff = strtotime( $post->post_modified ) - strtotime( $post->post_date ) ;
			}

			# New Post,
			if (  $post->post_status == 'publish' AND $post_time_diff <= 1   ) {
				$post_data['eventName'] = "New Post";
				
				# Action
				$r = $this->wpgsi_eventBoss( 'wp', 'wordpress_newPost', $post_data, $post->ID );
				# event Log for Trash
				$this->wpgsi_log( get_class($this), __METHOD__,"200", "Success: testing the post from new post. ". json_encode( array(  $post_id, $post, $update, $post_data  ) ) );
			}
			
			# Updated post 
			if (  $post->post_status == 'publish' AND $post_time_diff > 1 ) {
				$post_data['eventName'] = "Posts Edited";
				
				# Action
				$r = $this->wpgsi_eventBoss('wp', 'wordpress_editPost', $post_data, $post->ID );
				# event Log for Trash
				$this->wpgsi_log( get_class($this), __METHOD__,"200", "Success: testing the post edited publish. ". json_encode( array(  $post_id, $post, $update, $post_data  ) ) );
			}
			
		    # Post Is trash  || If Post is Trashed This Will fired
		    if ( $post->post_status == 'trash') {
				$post_data['eventName'] = "Trash";
				$r = $this->wpgsi_eventBoss( 'wp', 'wordpress_deletePost', $post_data, $post->ID );
				# event Log for Trash
				$this->wpgsi_log( get_class($this), __METHOD__,"200", "Success: testing the post from trash. ". json_encode( array(  $post_id, $post, $update, $post_data  ) ) );
			}
		}

		# if Post type is Page 
		if ( $post->post_type == 'page' ) {
			$post_data['eventName'] = "New Page";
			# Action
			$r = $this->wpgsi_eventBoss('wp' ,'wordpress_page' ,$post_data ,$post->ID );
			# event Log for Trash
			$this->wpgsi_log( get_class($this), __METHOD__,"200", "Success: testing page. ". json_encode( array(  $post_id, $post, $update, $post_data  ) )  );
		}

		# For Custom Post Type  [CPT]
		if ( wpgsi_fs()->is__premium_only() ) {
			if ( wpgsi_fs()->can_use_premium_code() ) {
				if ( $post->post_type != 'post' AND $post->post_type != 'page' ) {
					# For Status Not Trash ;-D
					if ( ! in_array( $post->post_status, array('auto-draft','draft','trash') ) ) {
						# getting Time Difference 
						if ( ! empty( $post->post_date ) AND  ! empty( $post->post_modified ) ){
							$post_time_diff = strtotime( $post->post_modified ) - strtotime( $post->post_date );
						}
						# if Difference is Lager its Edit Or Its New 
						if ( $post_time_diff < 5 ) {
							$post_data['eventName'] = 'cpt_new_'.$post->post_type ;
							# Action
							$r = $this->wpgsi_eventBoss('cpt', 'cpt_new_'.$post->post_type , $post_data, $post->ID );
							# event Log for Trash
							$this->wpgsi_log( get_class( $this ), __METHOD__,"200", "Success: testing the post edited publish. ". json_encode( array(  $post_id, $post, $update, $post_data  ) ) );
						
						} else {
							$post_data['eventName'] = 'cpt_update_'.$post->post_type ;
							# Action
							$r = $this->wpgsi_eventBoss('cpt',  'cpt_update_'.$post->post_type , $post_data, $post->ID );
							# event Log for Trash
							$this->wpgsi_log( get_class($this), __METHOD__,"200", "Success: testing the post edited publish. ". json_encode( array(  $post_id, $post, $update, $post_data  ) ) );
						}
					} 
					# For Post status Trash 
					if ( $post->post_status == 'trash' ) {
						$post_data['eventName'] = 'cpt_delete_'.$post->post_type;
						# Action
						$r = $this->wpgsi_eventBoss('cpt',  'cpt_delete_'.$post->post_type , $post_data, $post->ID );
						# event Log for Trash
						$this->wpgsi_log( get_class($this), __METHOD__,"200", "Success: testing the post edited publish. ". json_encode( array(  $post_id, $post, $update, $post_data  ) ) );
					}
				}
			}
		}
	}

	/**
	 * WordPress New Comment   HOOK's callback function
	 * @since     1.0.0
	 * @param     int     $commentID     			Order ID
	 * @param     int     $commentApprovedStatus    Order ID
	 * @param     int     $commentData     	  		Product Post 
	*/
	public function wpgsi_wordpress_comment( $commentID, $commentApprovedStatus, $commentData ) {
		# if There is a integration on  Comment 
		if ( $this->wpgsi_integrations( 'wordpress_comment' )[0] ) {
			# Check Comment ID is exist 
			if ( empty( $commentID ) ){
				$this->wpgsi_log( get_class($this), __METHOD__,"710", "Error:  Comment ID is Empty! "  );
			}
			# Setting Data 
			$Data 							=  array(); 
			$Data["comment_ID"]  			=  $commentID;
			$Data["comment_post_ID"]  		=  ( isset( $commentData["comment_post_ID"] )) 		? 	$commentData["comment_post_ID"]	 	: '';
			$Data["comment_author"]  		=  ( isset( $commentData["comment_author"] )) 		? 	$commentData["comment_author"]		: '';
			$Data["comment_author_email"] 	=  ( isset( $commentData["comment_author_email"] ))	?	$commentData["comment_author_email"]: '';
			$Data["comment_author_url"]  	=  ( isset( $commentData["comment_author_url"] )) 	? 	$commentData["comment_author_url"]	: '';
			$Data["comment_content"]  		=  ( isset( $commentData["comment_content"] )) 		? 	$commentData["comment_content"]		: '';
			$Data["comment_type"]  			=  ( isset( $commentData["comment_type"] )) 		? 	$commentData["comment_type"]		: '';
			$Data["user_ID"]  				=  ( isset( $commentData["user_ID"] )) 				? 	$commentData["user_ID"]				: '';
			$Data["comment_author_IP"]  	=  ( isset( $commentData["comment_author_IP"] )) 	? 	$commentData["comment_author_IP"]	: '';
			$Data["comment_agent"]  		=  ( isset( $commentData["comment_agent"] )) 		? 	$commentData["comment_agent"]		: '';
			$Data["comment_date"]  			=  ( isset( $commentData["comment_date"] )) 		? 	$commentData["comment_date"]		: '';
			$Data["comment_date_gmt"]  		=  ( isset( $commentData["comment_date_gmt"] )) 	? 	$commentData["comment_date_gmt"]	: '';
			#
			$Data['site_time'] 				=  ( isset( $this->Time ) ) ? 	$this->Time		: '';
			$Data['site_date'] 				=  ( isset( $this->Date ) ) ? 	$this->Date		: '';
			# New Code Starts From Here
			$Data["year_of_comment"]		= get_comment_date(	"Y", 	$commentID);
			$Data["month_of_comment"]		= get_comment_date(	"m", 	$commentID);
			$Data["date_of_comment"]		= get_comment_date(	"d", 	$commentID);
			$Data["time_of_comment"]		= get_comment_date(	"H:t", 	$commentID);
			# New Code Ends Here
			$Data["filtered"]  				=  ( isset( $commentData["filtered"] )) 	? 		$commentData["filtered"]			: '';
			$Data["comment_approved"]  		=  ( isset( $commentData["comment_approved"] ) &&   $commentData["comment_approved"] )  ? "True" : "False";
			
			if ( wpgsi_fs()->is__premium_only() ) {
				if ( wpgsi_fs()->can_use_premium_code() ){
					# Comment Meta Data portion Starts
					# empty Holder array 
					$metaOutPut = array();	
					# Global Db object 
					global $wpdb;
					# execute Query
					$commentMetaKeyValue = $wpdb->get_results( "SELECT * FROM $wpdb->commentmeta WHERE comment_id = " . $commentID, ARRAY_A );
					# get Distinct Keys;
					$metaKeys = $this->wpgsi_comments_metaKeys();
					# Check and Balance for all the Meta keys
					if ( $metaKeys[0] &&  ! empty( $commentMetaKeyValue ) ){
						# populating Output array in revers with  empty value
						foreach ( $metaKeys[1]  as $key => $value ){
							$metaOutPut[$value] = "--";
						}
						# Looping the Meta key & value of Certain Comment And Populating the $metaOutPut Key array with Value 
						foreach ( $commentMetaKeyValue  as $oneArray ) {
							if ( is_array( $oneArray ) && isset( $oneArray['meta_key'], $metaOutPut[ $oneArray[ 'meta_key' ] ], $oneArray[ 'meta_value' ] ) ){
								# Convert text to  an array then JSON for reducing the String 
								$isArrayTest = @unserialize( $oneArray[ 'meta_value' ] );
								if ( $isArrayTest == null ) {
									$metaOutPut[ $oneArray['meta_key'] ] = $oneArray[ 'meta_value' ];
								} else {
									$metaOutPut[ $oneArray['meta_key'] ] =  $isArrayTest;
								}
							}
						}
					}
					# Append New metaOutPut array to $commentData data array;
					$Data = array_merge( $Data, $metaOutPut);
					# Comment Meta Data portion Ends 
				}
			}

			# Action
			if ( empty( $commentID ) OR empty( $commentData ) OR empty( $Data )  ){
				$this->wpgsi_log( get_class($this), __METHOD__,"711", "Error:  commentID or commentData is empty !" );
			} else {
				$r = $this->wpgsi_eventBoss( 'wp', 'wordpress_comment', $Data, $commentID );	
			}
		}
	}

	# There should be an Edit Comment Hook Function in Here !
	# Create the Function and The Code for Edit product 
	/**
	 * WordPress Edit Comment   HOOK's callback function
	 * @since     1.0.0
	 * @param     int     $commentID     			Order ID
	 * @param     int     $commentData     	  		Product Post 
	*/
	public function wpgsi_wordpress_edit_comment( $commentID, $commentData ) {
		# if There is a integration on edit Comment 
		if ( $this->wpgsi_integrations( 'wordpress_edit_comment' )[0] ) {
			# Check Comment ID is exist 
			if ( empty( $commentID ) ){
				$this->wpgsi_log( get_class($this), __METHOD__,"712", " Comment ID is Empty! "  );
			}
			
			$Data 							=  array(); 
			$Data["comment_ID"]  			=  $commentID;
			$Data["comment_post_ID"]  		=  ( isset( $commentData["comment_post_ID"] )) 		? 	$commentData["comment_post_ID"]	 	: '';
			$Data["comment_author"]  		=  ( isset( $commentData["comment_author"] )) 		? 	$commentData["comment_author"]		: '';
			$Data["comment_author_email"] 	=  ( isset( $commentData["comment_author_email"] )) ?	$commentData["comment_author_email"]: '';
			$Data["comment_author_url"]  	=  ( isset( $commentData["comment_author_url"] )) 	? 	$commentData["comment_author_url"]	: '';
			$Data["comment_content"]  		=  ( isset( $commentData["comment_content"] )) 		? 	$commentData["comment_content"]		: '';
			$Data["comment_type"]  			=  ( isset( $commentData["comment_type"] )) 		? 	$commentData["comment_type"]		: '';
			$Data["user_ID"]  				=  ( isset( $commentData["user_ID"] )) 				? 	$commentData["user_ID"]				: '';
			$Data["comment_author_IP"]  	=  ( isset( $commentData["comment_author_IP"] )) 	? 	$commentData["comment_author_IP"]	: '';
			$Data["comment_agent"]  		=  ( isset( $commentData["comment_agent"] )) 		? 	$commentData["comment_agent"]		: '';
			$Data["comment_date"]  			=  ( isset( $commentData["comment_date"] )) 		? 	$commentData["comment_date"]		: '';
			$Data["comment_date_gmt"]  		=  ( isset( $commentData["comment_date_gmt"] )) 	? 	$commentData["comment_date_gmt"]	: '';
			#
			$Data['site_time'] 				=  ( isset( $this->Time ) ) ? 	$this->Time		: '';
			$Data['site_date'] 				=  ( isset( $this->Date ) ) ? 	$this->Date		: '';
			# New Code Starts From Here
			$Data["year_of_comment"]		= get_comment_date(	"Y", 	$commentID);
			$Data["month_of_comment"]		= get_comment_date(	"m", 	$commentID);
			$Data["date_of_comment"]		= get_comment_date(	"d", 	$commentID);
			$Data["time_of_comment"]		= get_comment_date(	"H:t", 	$commentID);
			# New Code Ends Here
			$Data["filtered"]  				=  ( isset( $commentData["filtered"] )) ? 			$commentData["filtered"]			: '';
			$Data["comment_approved"]  		=  ( isset( $commentData["comment_approved"] ) &&   $commentData["comment_approved"] )  ? "True" : "False";

			if ( wpgsi_fs()->is__premium_only() ) {
				if ( wpgsi_fs()->can_use_premium_code() ){
					# Comment Meta Data portion Starts
					# empty Holder array 
					$metaOutPut = array();	
					# Global Db object 
					global $wpdb;
					# execute Query
					$commentMetaKeyValue = $wpdb->get_results( "SELECT * FROM $wpdb->commentmeta WHERE comment_id = " . $commentID, ARRAY_A );
					# get Distinct Keys;
					$metaKeys = $this->wpgsi_comments_metaKeys();
					# Check and Balance for all the Meta keys
					if ( $metaKeys[0] &&  ! empty( $commentMetaKeyValue ) ) {
						# populating Output array in revers with  empty value
						foreach ( $metaKeys[1]  as $key => $value ){
							$metaOutPut[$value] = "--";
						}
						# Looping the Meta key & value of Certain Comment And Populating the $metaOutPut Key array with Value 
						foreach ( $commentMetaKeyValue  as $oneArray ) {
							if ( is_array( $oneArray ) && isset( $oneArray['meta_key'], $metaOutPut[ $oneArray[ 'meta_key' ] ], $oneArray[ 'meta_value' ] ) ){
								# Convert text to  an array then JSON for reducing the String 
								$isArrayTest = @unserialize( $oneArray[ 'meta_value' ] );
								if ( $isArrayTest == null ) {
									$metaOutPut[ $oneArray['meta_key'] ] = $oneArray[ 'meta_value' ];
								} else {
									$metaOutPut[ $oneArray['meta_key'] ] =  $isArrayTest;
								}
							}
						}
					}
					# Append New metaOutPut array to $commentData data array;
					$Data = array_merge( $Data, $metaOutPut);
					# Comment Meta Data portion Ends here 
				}
			}

			# Action
			if ( empty( $commentID ) OR empty( $commentData ) OR empty( $Data )  ){
				$this->wpgsi_log( get_class($this), __METHOD__,"713", "Error: commentID or commentData is empty !" );
			}else{
				$r = $this->wpgsi_eventBoss( 'wp', 'wordpress_edit_comment', $Data, $commentID );	
			}
		}
	}

	/**
	 * Woocommerce  Products  HOOK's callback function
	 * @since     1.0.0
	 * @param     int     $new_status     Order ID
	 * @param     int     $old_status     Order ID
	 * @param     int     $post     	  Product Post 
	*/
	public function wpgsi_woocommerce_product( $new_status, $old_status, $post ) {
		# If Post type is Not product
		if ( $post->post_type !== 'product' ){
			return;
		}
		# getting Product information 
		$product							= 	wc_get_product( $post->ID );
		$product_data 						= 	array(); 
		# Get Product General Info 
		$product_data['productID'] 			=   $post->ID;
		$product_data['type'] 				=  	( method_exists( $product, 'get_type' ) 			&& is_string( $product->get_type() )) 	 			?	$product->get_type()  				: "--";
		$product_data['name'] 				=  	( method_exists( $product, 'get_name' ) 			&& is_string( $product->get_name() )) 				?   $product->get_name()  				: "--";
		$product_data['slug'] 				=  	( method_exists( $product, 'get_slug' ) 			&& is_string( $product->get_slug() ))  				? 	$product->get_slug()  				: "--";
		$product_data['date_created'] 		= 	( method_exists( $product, 'get_date_created' )  	&& is_object( $product->get_date_created() )) 		?  	$product->get_date_created()->date("F j, Y, g:i:s A T")  : "--";
		$product_data['date_modified'] 		=   ( method_exists( $product, 'get_date_modified' ) 	&& is_object( $product->get_date_modified() )) 		?  	$product->get_date_modified()->date("F j, Y, g:i:s A T") : "--";
		# site Current Time
		$product_data['site_time'] 			= ( isset( $this->Time ) ) ? 	$this->Time		:	'';
		$product_data['site_date'] 			= ( isset( $this->Date ) ) ? 	$this->Date		:	'';
		# New Code Ends Here
		if ( wpgsi_fs()->is__premium_only() ) {
			if ( wpgsi_fs()->can_use_premium_code() ){
				# New Code Starts From Here
				$product_data["date_created_year"]	= ( method_exists( $product, 'get_date_created' )  && ! empty($product->get_date_created()) 	&&	is_string( $product->get_date_created()->date("F j, Y, g:i:s A T")) )  	? 	$product->get_date_created()->date("Y")  	: "";
				$product_data["date_created_month"]	= ( method_exists( $product, 'get_date_created' )  && ! empty($product->get_date_created()) 	&&	is_string( $product->get_date_created()->date("F j, Y, g:i:s A T")) )  	? 	$product->get_date_created()->date("m")  	: "";
				$product_data["date_created_date"]	= ( method_exists( $product, 'get_date_created' )  && ! empty($product->get_date_created()) 	&&	is_string( $product->get_date_created()->date("F j, Y, g:i:s A T")) )  	? 	$product->get_date_created()->date("d")  	: "";
				$product_data["date_created_time"]	= ( method_exists( $product, 'get_date_created' )  && ! empty($product->get_date_created())		&&	is_string( $product->get_date_created()->date("F j, Y, g:i:s A T")) )  	? 	$product->get_date_created()->date("H:i")  	: "";
				# for Two Different Kind Of Date Set 
				$product_data["date_modified_year"]	= ( method_exists( $product, 'get_date_modified' )  && ! empty($product->get_date_modified()) &&	is_string( $product->get_date_modified()->date("F j, Y, g:i:s A T")) )  ?	$product->get_date_modified()->date("Y")  	: "";
				$product_data["date_modified_month"]= ( method_exists( $product, 'get_date_modified' )  && ! empty($product->get_date_modified()) &&	is_string( $product->get_date_modified()->date("F j, Y, g:i:s A T")) )  ? 	$product->get_date_modified()->date("m")  	: "";
				$product_data["date_modified_date"]	= ( method_exists( $product, 'get_date_modified' )  && ! empty($product->get_date_modified()) &&	is_string( $product->get_date_modified()->date("F j, Y, g:i:s A T")) )  ? 	$product->get_date_modified()->date("d")  	: "";
				$product_data["date_modified_time"]	= ( method_exists( $product, 'get_date_modified' )  && ! empty($product->get_date_modified()) &&	is_string( $product->get_date_modified()->date("F j, Y, g:i:s A T")) )  ? 	$product->get_date_modified()->date("H:i")	: "";
		
				$product_data['status'] 			=   ( method_exists( $product, 'get_status' )   	 	&& is_string( $product->get_status())) 				?  	$product->get_status()  		 	: "--";
				$product_data['featured'] 			=   ( method_exists( $product, 'get_featured' ) 	 	&& is_bool(   $product->get_featured() )) 			?  	$product->get_featured()      		: "--";
				$product_data['catalog_visibility'] =   ( method_exists( $product, 'get_catalog_visibility')&& is_string( $product->get_catalog_visibility())) 	?  	$product->get_catalog_visibility()  : "--";
				$product_data['description'] 		=   ( method_exists( $product, 'get_description'  ) 	&& is_string( $product->get_description())  ) 		?  	$product->get_description()  		: "--";
				$product_data['short_description'] 	=   ( method_exists( $product, 'get_short_description') && is_string( $product->get_short_description()))  	?  	$product->get_short_description()  	: "--";
				$product_data['sku'] 				=   ( method_exists( $product, 'get_sku'  ) 	 		&& is_string( $product->get_sku())) 				? 	$product->get_sku()  				: "--";
				$product_data['menu_order'] 		=   ( method_exists( $product, 'get_menu_order' )		&& is_int( $product->get_menu_order())) 			? 	$product->get_menu_order()   		: "--";
				$product_data['virtual'] 			=   ( method_exists( $product, 'get_virtual'  )  		&& is_bool( $product->get_virtual())) 				? 	$product->get_virtual()  			: "--";
				$product_data['permalink'] 			=   ( method_exists( $product, 'get_permalink' ) 		&& is_string( $product->get_permalink())) 			? 	$product->get_permalink() 			: "--";
			
				# Get Product Prices
				$product_data['price'] 				=   ( method_exists( $product, 'get_price' ) 	 		&& is_string( $product->get_price() )) 				? 	$product->get_price()  				: "--";
				$product_data['regular_price'] 		=   ( method_exists( $product, 'get_regular_price' )    && is_string( $product->get_regular_price() )) 		? 	$product->get_regular_price() 		: "--";
				$product_data['sale_price'] 		=   ( method_exists( $product, 'get_sale_price' )	    && is_string( $product->get_sale_price() )) 		? 	$product->get_sale_price() 			: "--";
				$product_data['date_on_sale_from'] 	=   ( method_exists( $product, 'get_date_on_sale_from' )&& is_string( $product->get_date_on_sale_from() ))  ? 	$product->get_date_on_sale_from()	: "--";
				$product_data['date_on_sale_to'] 	=   ( method_exists( $product, 'get_date_on_sale_to'  ) && is_string( $product->get_date_on_sale_to() )) 	? 	$product->get_date_on_sale_to() 	: "--";
				$product_data['total_sales'] 		=   ( method_exists( $product, 'get_total_sales' ) 		&& is_int( $product->get_total_sales() )  ) 		? 	$product->get_total_sales()			: "--";
				
				# Get Product Tax, Shipping & Stock
				$product_data['tax_status'] 		=   ( method_exists( $product, 'get_tax_status' ) 		&& is_string( $product->get_tax_status() ))			? 	$product->get_tax_status() 			: "--";
				$product_data['tax_class'] 			=   ( method_exists( $product, 'get_tax_class'  ) 		&& is_string( $product->get_tax_class() )) 			? 	$product->get_tax_class() 			: "--";
				$product_data['manage_stock'] 		=   ( method_exists( $product, 'get_manage_stock' )   	&& is_bool(   $product->get_manage_stock() ))		? 	$product->get_manage_stock() 		: "--";
				$product_data['stock_quantity'] 	=   ( method_exists( $product, 'get_stock_quantity' ) 	&& is_string( $product->get_stock_quantity())) 		?  	$product->get_stock_quantity()  	: "--";
				$product_data['stock_status'] 		=   ( method_exists( $product, 'get_stock_status') 		&& is_string( $product->get_stock_status() )) 		? 	$product->get_stock_status() 		: "--";
				$product_data['backorders'] 		=   ( method_exists( $product, 'get_backorders' )  		&& is_string( $product->get_backorders() )  ) 		?   $product->get_backorders()   		: "--";
				$product_data['sold_individually'] 	=   ( method_exists( $product, 'get_sold_individually') && is_bool(  $product->get_sold_individually()))	? 	$product->get_sold_individually()  	: "--";
				$product_data['purchase_note'] 		=   ( method_exists( $product, 'get_purchase_note' ) 	&& is_string( $product->get_purchase_note() ) ) 	?  	$product->get_purchase_note()      	: "--";
				$product_data['shipping_class_id'] 	=   ( method_exists( $product, 'get_shipping_class_id') && is_int(  $product->get_shipping_class_id() ))	? 	$product->get_shipping_class_id()  	: "--";
			}
		}
		# Get Product Dimensions
		$product_data['weight'] 					=   ( method_exists( $product, 'get_weight' )     		&& 	is_string( $product->get_weight() )) 			? 	$product->get_weight() 				: "--";
		$product_data['length'] 					=   ( method_exists( $product, 'get_length' )     		&& 	is_string( $product->get_length() )) 			? 	$product->get_length() 				: "--";
		$product_data['width'] 						=   ( method_exists( $product, 'get_width'  )     		&& 	is_string(  $product->get_width() )) 			? 	$product->get_width()  				: "--";
		$product_data['height'] 					=   ( method_exists( $product, 'get_height' ) 	  		&& 	is_string( $product->get_height() )) 			? 	$product->get_height()  			: "--";
		# Get Product Variations
		$product_data['attributes'] 				=   ( method_exists( $product, 'get_variation_attributes' ) && is_array( $product->get_variation_attributes())) ?  json_encode($product->get_variation_attributes()): "--";	
		$product_data['default_attributes'] 		=   ( method_exists( $product, 'get_default_attributes')	&& is_array($product->get_default_attributes())) 	?  json_encode($product->get_default_attributes())	: "--";	
		# Get Product Taxonomies
		$product_data['category_ids'] 				=   ( method_exists( $product, 'get_category_ids') 		&& is_array(  $product->get_category_ids() ) ) 		?  implode(", ", $product->get_category_ids())			: "--";	
		$product_data['tag_ids'] 					=   ( method_exists( $product, 'get_tag_ids' ) 	 		&& is_array( $product->get_tag_ids() )  )  			?  implode(", ", $product->get_gallery_image_ids())		: "--";	
		# Get Product Images
		$product_data['image_id'] 					=   ( method_exists( $product, 'get_image_id' ) 		&& is_string( $product->get_image_id() ))  			?   $product->get_image_id()							: "--" ;
		$product_data['gallery_image_ids'] 			=   ( method_exists( $product, 'get_gallery_image_ids') && is_array( $product->get_gallery_image_ids())) 	? 	implode(", ", $product->get_gallery_image_ids()) 	: "--";	
		$product_data['get_attachment_image_url'] 	=  	((method_exists( $product, 'get_image_id')			AND function_exists('wp_get_attachment_image_url') ) AND !empty( $product->get_image_id() ) )  ?  wp_get_attachment_image_url( $product->get_image_id() )	:	"--";
		#
		if ( wpgsi_fs()->is__premium_only() ) {
			if ( wpgsi_fs()->can_use_premium_code() ){
				# Post Meta Data portion Starts
				# Empty Holder array 
				$metaOutPut = array();	
				# Global Db object 
				global $wpdb;
				# execute Query
				$productMetaKeyValue = $wpdb->get_results( "SELECT * FROM $wpdb->postmeta WHERE post_id = " . $post->ID , ARRAY_A );
				# get Distinct Keys;
				$metaKeys = $this->wpgsi_wooCommerce_product_metaKeys();
				# Check and Balance for all the Meta keys
				if ( $metaKeys[0] &&  ! empty( $productMetaKeyValue ) ) {
					# populating Output array in revers with  empty value
					foreach ( $metaKeys[1]  as $key => $value ){
						$metaOutPut[$value] = "--";
					}
					# Looping the Meta key & value of Certain Comment And Populating the $metaOutPut Key array with Value 
					foreach ( $productMetaKeyValue  as $oneArray ) {
						if ( is_array( $oneArray ) && isset( $oneArray['meta_key'], $metaOutPut[ $oneArray[ 'meta_key' ] ], $oneArray[ 'meta_value' ] ) ){
							# Convert text to  an array then JSON for reducing the String 
							$isArrayTest = @unserialize( $oneArray[ 'meta_value' ] );
							if ( $isArrayTest == null ) {
								$metaOutPut[ $oneArray['meta_key'] ] = $oneArray[ 'meta_value' ];
							} else {
								$metaOutPut[ $oneArray['meta_key'] ] =  $isArrayTest;
							}
						}
					}
				}
				# Append New metaOutPut array to $commentData data array;
				$product_data = array_merge( $product_data, $metaOutPut);
				# Post Meta Data portion Ends
			}
		}

		if ( $new_status == 'publish' && $old_status !== 'publish' ) {
			# New Product Insert 
			$product_data['price'] 			= wp_strip_all_tags( $_POST['_sale_price'] );
			$product_data['regular_price'] 	= wp_strip_all_tags( $_POST['_regular_price'] );
			$product_data['sale_price'] 	= wp_strip_all_tags( $_POST['_sale_price'] );
			$product_data['eventName'] 		= "New Product";
			# Action
			$r = $this->wpgsi_eventBoss('Woocommerce', 'wc-new_product', $product_data, $post->ID );
		} elseif ( $new_status == 'trash' ) {
			# Delete  Product ;
			$product_data['eventName'] = "Trash";
			# Action
			$r = $this->wpgsi_eventBoss('Woocommerce', 'wc-delete_product', $product_data, $product->ID );
		} else {
			# Update 
			$product_data['eventName']  = "Update Product";
			# Action
			$r = $this->wpgsi_eventBoss('Woocommerce', 'wc-edit_product', $product_data, $post->ID );
		}
	}

	/**
	 * WooCommerce Order  HOOK's callback function
	 * @since    1.0.0
	 * @param    int     $order_id     Order ID
	*/
	public function wpgsi_woocommerce_order_status_changed( $order_id, $this_status_transition_from, $this_status_transition_to ) {
		# check to see is there any integration on this order change Status.
		if ( ! $this->wpgsi_integrations(  'wc-' . $this_status_transition_to )[0] ) {
			return;
		}
		# getting order data 
		$order 					=  wc_get_order( $order_id );
		$order_data 			=  array();
		#  ++++++++++++ This below of Code Is Not Working | change the Code ++++++++++++
		# New system For Stopping Dabble Submission # If Order Created Date Is Less than 3 mints and Order is from checkout
		$orderDateTimeStamp 	=  strtotime( $order->get_date_created() );
		$currentDateTimeStamp 	=  strtotime( current_time("Y-m-d H:i:s") );
		$timDiffMin 			=  round( ( $currentDateTimeStamp - $orderDateTimeStamp ) / 60 );
		# check the arguments || if time difference is less than 5 mints stop  
		if ( $order->get_created_via() == 'checkout' AND $timDiffMin < 5 ){
			$this->wpgsi_log( get_class($this), __METHOD__,"400", "Error: Dabble Submission Stopped!");
			return;
		}
		# ++++++++++++ This above of Code Is Not Working | change the Code  ++++++++++++
		# 
		$order_data['orderID'] 						=  ( method_exists( $order, 'get_id' ) 			  		&&	is_int( $order->get_id()))						? 	$order->get_id()					 : "";
		$order_data['billing_first_name'] 			=  ( method_exists( $order, 'get_billing_first_name' )  && 	is_string( $order->get_billing_first_name() ))	? 	$order->get_billing_first_name()	 : "";
		$order_data['billing_last_name'] 			=  ( method_exists( $order, 'get_billing_last_name' ) 	&& 	is_string( $order->get_billing_last_name() ))	? 	$order->get_billing_last_name()		 : "";
		$order_data['billing_company'] 				=  ( method_exists( $order, 'get_billing_company' ) 	&& 	is_string( $order->get_billing_company() ))		? 	$order->get_billing_company()		 : "";
		$order_data['billing_address_1'] 			=  ( method_exists( $order, 'get_billing_address_1' ) 	&& 	is_string( $order->get_billing_address_1() ))	? 	$order->get_billing_address_1()		 : "";
		$order_data['billing_address_2'] 			=  ( method_exists( $order, 'get_billing_address_2' ) 	&& 	is_string( $order->get_billing_address_2() ))	? 	$order->get_billing_address_2()		 : "";
		$order_data['billing_city'] 				=  ( method_exists( $order, 'get_billing_city' ) 		&& 	is_string( $order->get_billing_city() ))		? 	$order->get_billing_city()			 : "";
		$order_data['billing_state'] 				=  ( method_exists( $order, 'get_billing_state' ) 		&& 	is_string( $order->get_billing_state() )) 		? 	$order->get_billing_state()			 : "";
		$order_data['billing_postcode'] 			=  ( method_exists( $order, 'get_billing_postcode' ) 	&& 	is_string( $order->get_billing_postcode() ))	? 	$order->get_billing_postcode()		 : "";
		# 
		if ( wpgsi_fs()->is__premium_only() ) {
			if ( wpgsi_fs()->can_use_premium_code() ){
				$order_data['billing_country'] 				= ( method_exists( $order, 'get_billing_country' ) 	    && 	is_string( $order->get_billing_country() ))	? 	$order->get_billing_country()	  : "";
				$order_data['billing_email'] 				= ( method_exists( $order, 'get_billing_email' ) 		&& 	is_string( $order->get_billing_email() ))	? 	$order->get_billing_email()		  : "";
				$order_data['billing_phone'] 				= ( method_exists( $order, 'get_billing_phone' ) 		&& 	is_string( $order->get_billing_phone()))	? 	$order->get_billing_phone()		  : "";
				$order_data['cart_tax'] 					= ( method_exists( $order, 'get_cart_tax' ) 	  		&& 	is_string( $order->get_cart_tax()  ))		? 	$order->get_cart_tax() 			  : "";
				$order_data['currency'] 					= ( method_exists( $order, 'get_currency' ) 	  		&& 	is_string( $order->get_currency()  ))		? 	$order->get_currency() 			  : "";
				$order_data['discount_tax'] 				= ( method_exists( $order, 'get_discount_tax' )   		&& 	is_string( $order->get_discount_tax() ))	?	$order->get_discount_tax() 		  : "";
				$order_data['discount_total'] 				= ( method_exists( $order, 'get_discount_total' ) 		&& 	is_string( $order->get_discount_total() ))	? 	$order->get_discount_total()	  : "";
				$order_data['fees'] 						= ( method_exists( $order, 'get_fees' ) 		  		&&  ! empty( $order->get_fees() ) && is_array( $order->get_fees()) ) 			?   json_encode( $order->get_fees()) 	:   "";
				$order_data['shipping_method'] 				= ( method_exists( $order, 'get_shipping_method' )		&& 	is_string( $order->get_shipping_method() ))	? 	$order->get_shipping_method() 	  :	"";
				$order_data['shipping_tax'] 				= ( method_exists( $order, 'get_shipping_tax' ) 		&& 	is_string( $order->get_shipping_tax()  ))	? 	$order->get_shipping_tax() 		  :	"";
				$order_data['shipping_total'] 				= ( method_exists( $order, 'get_shipping_total' ) 		&& 	is_string( $order->get_shipping_total()  ))	? 	$order->get_shipping_total()	  :	"";
				$order_data['subtotal'] 					= ( method_exists( $order, 'get_subtotal' ) 			&& 	is_float( $order->get_subtotal()  ))		? 	$order->get_subtotal()			  :	"";
				
				$order_data['subtotal_to_display'] 			= ( method_exists( $order, 'get_subtotal_to_display') 	&& 	is_string( $order->get_subtotal_to_display()))? $order->get_subtotal_to_display() : "";
				$order_data['tax_totals'] 					= ( method_exists( $order, 'get_tax_totals' ) 			&&  ! empty($order->get_tax_totals()) 	&& is_array( $order->get_tax_totals())) ?  json_encode( $order->get_tax_totals()) 	: ""; 
				$order_data['taxes'] 						= ( method_exists( $order, 'get_taxes' ) 				&&  ! empty($order->get_taxes()) 		&& is_array( $order->get_taxes()) ) 	?  json_encode( $order->get_taxes()) 		: "";  
				$order_data['total'] 						= ( method_exists( $order, 'get_total' ) 				&& 	is_string( $order->get_total() ))			  	 ?  $order->get_total() 		 			:	"";
				$order_data['total_discount'] 				= ( method_exists( $order, 'get_total_discount' ) 		&& 	is_float( $order->get_total_discount()  ))   	 ?  $order->get_total_discount() 			:	"";
				$order_data['total_tax'] 					= ( method_exists( $order, 'get_total_tax'  ) 			&& 	is_string( $order->get_total_tax() ))		 	 ? 	$order->get_total_tax() 	 			:	"";
				$order_data['total_refunded'] 				= ( method_exists( $order, 'get_total_refunded' ) 		&& 	is_float( $order->get_total_refunded() ))	   	 ? 	$order->get_total_refunded() 			:	"";
				$order_data['total_tax_refunded'] 			= ( method_exists( $order, 'get_total_tax_refunded' ) 	&& 	is_int( $order->get_total_tax_refunded()))	 	 ?  $order->get_total_tax_refunded()		:	"";
				$order_data['total_shipping_refunded'] 		= ( method_exists( $order, 'get_total_shipping_refunded')&& is_int( $order->get_total_shipping_refunded() )) ?  $order->get_total_shipping_refunded() 	:	"";
				$order_data['item_count_refunded'] 			= ( method_exists( $order, 'get_item_count_refunded' ) 	&& 	is_int( $order->get_item_count_refunded() )) 	 ?  $order->get_item_count_refunded() 		:	"";
				$order_data['total_qty_refunded'] 			= ( method_exists( $order, 'get_total_qty_refunded' ) 	&& 	is_int( $order->get_total_qty_refunded() ))  	 ?  $order->get_total_qty_refunded() 		:	"";
				$order_data['remaining_refund_amount']  	= ( method_exists( $order, 'get_remaining_refund_amount')&& is_string($order->get_remaining_refund_amount()))?  $order->get_remaining_refund_amount()	:	"";
				# Order Item process Starts
				if ( method_exists( $order, 'get_items') AND is_array( $order->get_items()) ){ 
					# Declaring Empty Array Holder 
					$product_ids = array();
					$order_data['items'] 	     					= " ";
					$order_data['get_product_id'] 			 		= " ";	 
					$order_data['get_name'] 				 		= " ";	  
					$order_data['get_quantity'] 			 		= " ";	  
					$order_data['get_total'] 				 		= " ";	 	
					$order_data['get_sku'] 					 		= " ";	 	
					$order_data['get_type'] 			 	 		= " ";	   
					$order_data['get_slug'] 			 	 		= " ";	
					
					$order_data['get_price'] 				 		= " ";	
					$order_data['get_regular_price'] 		 		= " ";
					$order_data['get_sale_price'] 			 		= " ";	 
					
					$order_data['get_virtual'] 				 		= " "; 	
					$order_data['get_permalink'] 			 		= " ";	
					$order_data['get_featured'] 			 		= " ";	
					$order_data['get_status'] 				 		= " ";	 
					$order_data['get_tax_status'] 			 		= " "; 	
					$order_data['get_tax_class'] 			 		= " "; 	
					$order_data['get_manage_stock'] 		 		= " "; 	
					$order_data['get_stock_quantity'] 		 		= " ";  
					$order_data['get_stock_status'] 		 		= " "; 	
					$order_data['get_backorders'] 			 		= " "; 
					$order_data['get_sold_individually']	 		= " "; 	
					$order_data['get_purchase_note'] 		 		= " ";
					$order_data['get_shipping_class_id']	 		= " ";
					
					$order_data['get_weight'] 				 		= " ";
					$order_data['get_length'] 				 		= " ";
					$order_data['get_width'] 				 		= " ";
					$order_data['get_height'] 				 		= " "; 	
					
					$order_data['get_default_attributes'] 	 		= " ";
					
					$order_data['get_category_ids'] 		 		= " ";
					$order_data['get_tag_ids'] 				 		= " ";
					
					$order_data['get_image_id'] 			 		= " ";
					$order_data['get_gallery_image_ids'] 	 		= " "; 
					$order_data['get_attachment_image_url'] 	 	= " "; 
					# Item Meta Empty Holders 
					# New Code Ends 
					foreach ( $order->get_items() as $item_id => $item_data ) {
						
						$order_data['items'] .= (( method_exists( $item_data, "get_product_id" ) AND 	is_int( $item_data->get_product_id())) 	AND !empty($item_data->get_product_id()))	?  $item_data->get_product_id() 			: "--"; 
						$order_data['items'] .= (( method_exists( $item_data, "get_name" ) 	   	 AND 	is_string( $item_data->get_name() )) 	AND	!empty($item_data->get_name()))			?  " " . $item_data->get_name() 		 	: "--"; 
						$order_data['items'] .= (( method_exists( $item_data, "get_quantity" ) 	 AND 	is_int( $item_data->get_quantity() ))	AND !empty($item_data->get_quantity()))		?  " qty - " . $item_data->get_quantity() 	: "--"; 
						$order_data['items'] .= (( method_exists( $item_data, "get_total" ) 	 AND 	is_string( $item_data->get_total() ))	AND !empty($item_data->get_total()))		?  " total - " .  	$item_data->get_total() : "--"; 
						
						# New Code Starts 
						$product_ids[] 								     =	( (method_exists( $item_data, 'get_product_id')							AND is_int( $item_data->get_product_id() ))						    AND !empty( $item_data->get_product_id()) )						?  $item_data->get_product_id()														:	"--";
						$order_data['get_product_id'] 					.=	( (method_exists( $item_data, 'get_product_id')							AND is_int( $item_data->get_product_id() ))						    AND !empty( $item_data->get_product_id()) )						?  $item_data->get_product_id()														:	"--";
						$order_data['get_name'] 						.=  ( (method_exists( $item_data, 'get_name')								AND is_string( $item_data->get_name() ))						    AND !empty( $item_data->get_name())	)							?  $item_data->get_name()															:	"--";
						$order_data['get_quantity'] 					.=  ( (method_exists( $item_data, 'get_quantity')							AND is_int( $item_data->get_quantity() ))						    AND !empty( $item_data->get_quantity())	)						?  $item_data->get_quantity()														:	"--";
						$order_data['get_total'] 						.= 	( (method_exists( $item_data, 'get_total')								AND is_string( $item_data->get_total() ))						    AND !empty( $item_data->get_total()) )							?  $item_data->get_total()															:	"--";	
						$order_data['get_sku'] 							.= 	( (method_exists( $item_data->get_product(), 'get_sku')					AND is_string( $item_data->get_product()->get_sku() )) 			    AND !empty( $item_data->get_product()->get_sku()) )				?  $item_data->get_product()->get_sku()												:	"--";
						$order_data['get_type'] 						.= 	( (method_exists( $item_data->get_product(), 'get_type')				AND is_string( $item_data->get_product()->get_type() ))			    AND !empty( $item_data->get_product()->get_type()) )			?  $item_data->get_product()->get_type()											:	"--";
						$order_data['get_slug'] 						.= 	( (method_exists( $item_data->get_product(), 'get_slug')				AND is_string( $item_data->get_product()->get_slug() ))			    AND !empty( $item_data->get_product()->get_slug()) )			?  $item_data->get_product()->get_slug()											:	"--";
					
						$order_data['get_price'] 						.= 	( (method_exists( $item_data->get_product(), 'get_price')				AND is_string( $item_data->get_product()->get_price() ))		    AND !empty( $item_data->get_product()->get_price())	)			?  $item_data->get_product()->get_price()											:	"--";
						$order_data['get_regular_price'] 				.= 	( (method_exists( $item_data->get_product(), 'get_regular_price')		AND is_string( $item_data->get_product()->get_regular_price()))     AND !empty( $item_data->get_product()->get_regular_price())	)	?  $item_data->get_product()->get_regular_price()									:	"--";
						$order_data['get_sale_price'] 					.= 	( (method_exists( $item_data->get_product(), 'get_sale_price')			AND is_string( $item_data->get_product()->get_sale_price()  ))	    AND !empty( $item_data->get_product()->get_sale_price()) )		?  $item_data->get_product()->get_sale_price()										:	"--";
						
						$order_data['get_virtual'] 						.= 	( (method_exists( $item_data->get_product(), 'get_virtual')				AND is_bool( $item_data->get_product()->get_virtual()  ))		    AND !empty( $item_data->get_product()->get_virtual()) )			?  $item_data->get_product()->get_virtual()											:	"--";
						$order_data['get_permalink'] 					.=	( (method_exists( $item_data->get_product(), 'get_permalink')			AND is_string( $item_data->get_product()->get_permalink() ))	    AND !empty( $item_data->get_product()->get_permalink()) )		?  $item_data->get_product()->get_permalink()										:	"--";
						$order_data['get_featured'] 					.=	( (method_exists( $item_data->get_product(), 'get_featured')			AND is_bool( $item_data->get_product()->get_featured()  ))		    AND !empty( $item_data->get_product()->get_featured()) )		?  $item_data->get_product()->get_featured()										:	"--";
						$order_data['get_status'] 						.=	( (method_exists( $item_data->get_product(), 'get_status')				AND is_string( $item_data->get_product()->get_status()  ))		    AND !empty( $item_data->get_product()->get_status()) )			?  $item_data->get_product()->get_status()											:	"--";
						$order_data['get_tax_status'] 					.= 	( (method_exists( $item_data->get_product(), 'get_tax_status')			AND is_string( $item_data->get_product()->get_tax_status()  ))	    AND !empty( $item_data->get_product()->get_tax_status()) )		?  $item_data->get_product()->get_tax_status()										:	"--";
						$order_data['get_tax_class'] 					.= 	( (method_exists( $item_data->get_product(), 'get_tax_class')			AND is_string( $item_data->get_product()->get_tax_class()  ))	    AND !empty( $item_data->get_product()->get_tax_class() ) )		?  $item_data->get_product()->get_tax_class()										:	"--";
						$order_data['get_manage_stock'] 				.= 	( (method_exists( $item_data->get_product(), 'get_manage_stock')		AND is_bool( $item_data->get_product()->get_manage_stock()  ))	    AND !empty( $item_data->get_product()->get_manage_stock() )	)	?  $item_data->get_product()->get_manage_stock()									:	"--";
						$order_data['get_stock_quantity'] 				.= 	( (method_exists( $item_data->get_product(), 'get_stock_quantity')		AND is_string( $item_data->get_product()->get_stock_quantity() ))   AND !empty( $item_data->get_product()->get_stock_quantity()) )	?  $item_data->get_product()->get_stock_quantity()									:	"--";
						$order_data['get_stock_status'] 				.= 	( (method_exists( $item_data->get_product(), 'get_stock_status')		AND is_string( $item_data->get_product()->get_stock_status()  ))    AND !empty( $item_data->get_product()->get_stock_status()) )	?  $item_data->get_product()->get_stock_status()									:	"--";
						$order_data['get_backorders'] 					.= 	( (method_exists( $item_data->get_product(), 'get_backorders')			AND is_string( $item_data->get_product()->get_backorders()  ))	    AND !empty($item_data->get_product()->get_backorders()) )		?  $item_data->get_product()->get_backorders()										:	"--";
						$order_data['get_sold_individually']			.= 	( (method_exists( $item_data->get_product(), 'get_sold_individually')	AND is_bool( $item_data->get_product()->get_sold_individually()))   AND !empty($item_data->get_product()->get_sold_individually()) )?  $item_data->get_product()->get_sold_individually()								:	"--";
						$order_data['get_purchase_note'] 				.= 	( (method_exists( $item_data->get_product(), 'get_purchase_note')		AND is_string( $item_data->get_product()->get_purchase_note() ))    AND !empty( $item_data->get_product()->get_purchase_note()) )	?  $item_data->get_product()->get_purchase_note()									:	"--";
						$order_data['get_shipping_class_id']			.= 	( (method_exists( $item_data->get_product(), 'get_shipping_class_id')	AND is_int( $item_data->get_product()->get_shipping_class_id() ))   AND !empty($item_data->get_product()->get_shipping_class_id() ))?  $item_data->get_product()->get_shipping_class_id()								:	"--";
						
						$order_data['get_weight'] 						.= 	( (method_exists( $item_data->get_product(), 'get_weight')				AND is_string( $item_data->get_product()->get_weight() ))		    AND !empty($item_data->get_product()->get_weight())	)			?  $item_data->get_product()->get_weight()											:	"--";
						$order_data['get_length'] 						.= 	( (method_exists( $item_data->get_product(), 'get_length')				AND is_string( $item_data->get_product()->get_length() ))		    AND !empty($item_data->get_product()->get_length())	)			?  $item_data->get_product()->get_length()											:	"--";
						$order_data['get_width'] 						.= 	( (method_exists( $item_data->get_product(), 'get_width')				AND is_string( $item_data->get_product()->get_width()  ))		    AND !empty( $item_data->get_product()->get_width())	)			?  $item_data->get_product()->get_width()											:	"--";
						$order_data['get_height'] 						.= 	( (method_exists( $item_data->get_product(), 'get_height')				AND is_string( $item_data->get_product()->get_height() ))		    AND !empty( $item_data->get_product()->get_height()))			?  $item_data->get_product()->get_height()											:	"--";
						
						$order_data['get_default_attributes'] 			.= 	( (method_exists( $item_data->get_product(), 'get_default_attributes')	AND is_array( $item_data->get_product()->get_default_attributes())) AND !empty($item_data->get_product()->get_default_attributes()))? json_encode($item_data->get_product()->get_default_attributes()) 					:	"--";
						
						$order_data['get_image_id'] 					.= 	( (method_exists( $item_data->get_product(), 'get_image_id')			AND is_string( $item_data->get_product()->get_image_id()  ))		AND !empty($item_data->get_product()->get_image_id()))			?  $item_data->get_product()->get_image_id()										:	"--";
						$order_data['get_gallery_image_ids'] 			.= 	( (method_exists( $item_data->get_product(), 'get_gallery_image_ids')	AND is_array( $item_data->get_product()->get_gallery_image_ids() ))	AND !empty($item_data->get_product()->get_gallery_image_ids()))	?  json_encode( $item_data->get_product()->get_gallery_image_ids())					:	"--";
						$order_data['get_attachment_image_url'] 		.= 	( (method_exists( $item_data->get_product(), 'get_image_id')			AND function_exists('wp_get_attachment_image_url') )				AND !empty($item_data->get_product()->get_image_id()))			?  wp_get_attachment_image_url( $item_data->get_product()->get_image_id())			:	"--";
						# get all the Products( many Products ) Same Meta Key Value # reduce The 
						# New Code 
						# Creating New Line 
						$order_data['items'] 							.=  " \n ";
						$order_data['get_product_id'] 			 		.=	" \n "; 
						$order_data['get_name'] 				 		.=	" \n ";  
						$order_data['get_quantity'] 			 		.=	" \n ";  
						$order_data['get_total'] 				 		.=	" \n "; 	
						$order_data['get_sku'] 					 		.=	" \n "; 	
						$order_data['get_type'] 			 	 		.=	" \n ";   
						$order_data['get_slug'] 			 	 		.=	" \n ";
  
						$order_data['get_price'] 				 		.=	" \n "; 	
						$order_data['get_regular_price'] 		 		.=	" \n "; 
						$order_data['get_sale_price'] 			 		.=	" \n "; 	 
  
						$order_data['get_virtual'] 				 		.=	" \n "; 	
						$order_data['get_permalink'] 			 		.=	" \n ";	
						$order_data['get_featured'] 			 		.=	" \n ";	
						$order_data['get_status'] 				 		.=	" \n ";	 
						$order_data['get_tax_status'] 			 		.=	" \n "; 	
						$order_data['get_tax_class'] 			 		.=	" \n "; 	
						$order_data['get_manage_stock'] 		 		.=	" \n "; 	
						$order_data['get_stock_quantity'] 		 		.=	" \n ";  
						$order_data['get_stock_status'] 		 		.=	" \n "; 	
						$order_data['get_backorders'] 			 		.=	" \n "; 
						$order_data['get_sold_individually']	 		.=	" \n "; 	
						$order_data['get_purchase_note'] 		 		.=	" \n ";
						$order_data['get_shipping_class_id']	 		.=	" \n ";
  
						$order_data['get_weight'] 					 	.=	" \n ";
						$order_data['get_length'] 				 		.=	" \n ";
						$order_data['get_width'] 				 		.=	" \n ";
						$order_data['get_height'] 				 		.=	" \n "; 	

						$order_data['get_default_attributes'] 	 		.=	" \n ";

						$order_data['get_category_ids'] 		 		.=	" \n ";
						$order_data['get_tag_ids'] 				 		.=	" \n ";

						$order_data['get_image_id'] 			 		.=	" \n ";
						$order_data['get_gallery_image_ids'] 	 		.=	" \n ";
						$order_data['get_attachment_image_url'] 	 	.=	" \n ";
						# New Code Ends 
					}
				}

				# Inserting Order items Meta value
				if( ! empty( $product_ids ) ) {
					#item meta key value Holder 
					$itemMetaKeyValue = array();
					# wpdb
					global $wpdb;
					# DB query 
					$query 	 = "SELECT * FROM $wpdb->postmeta WHERE post_id IN ('". join("','",$product_ids) ."') ORDER BY FIELD(post_id, ".join(",",$product_ids).") ";
					# Running the Query
					$productMeta = $wpdb->get_results( $query, ARRAY_A );
					if( ! empty( $productMeta ) ){
						foreach( $productMeta as $key => $valueArray) {
							if( isset( $valueArray['meta_key']  ) AND  isset( $valueArray['meta_value'] ) ) {
								# error handling 
								if( isset( $itemMetaKeyValue[ $valueArray['meta_key'] ]  ) ){
									$itemMetaKeyValue[ $valueArray['meta_key'] ] .= ( !empty($valueArray['meta_value']) )? $valueArray['meta_value'] . " \n " : "-- \n ";
								} else {
									$itemMetaKeyValue[ $valueArray['meta_key'] ]  ="";
									$itemMetaKeyValue[ $valueArray['meta_key'] ] .= ( !empty($valueArray['meta_value']) )? $valueArray['meta_value'] . " \n " : "-- \n ";
								}
							}
						}
					}
				}
				# Joining the Product meta to Order data 
				$order_data = array_merge( $order_data, $itemMetaKeyValue );	
				# Order Item process Ends
				$order_data['item_count'] 			    	=  ( method_exists( $order, 'get_item_count') 			&& 	is_int($order->get_item_count() )) 			? 	$order->get_item_count() : "";
				$order_data['downloadable_items'] 			=  ( method_exists( $order, 'get_downloadable_items' ) 	&& ! empty($order->get_downloadable_items())&&  is_array(  $order->get_downloadable_items()) ) 	? json_encode( $order->get_downloadable_items()) : "";   
				#
				$order_data['date_created'] 				=  ( method_exists( $order, 'get_date_created' ) 	&& ! empty($order->get_date_created()) 	&&	is_string( $order->get_date_created()->date("F j, Y, g:i:s A T") ) ) 	? 	$order->get_date_created()->date("F j, Y, g:i:s A T") 	: ""; 
				$order_data['date_modified'] 				=  ( method_exists( $order, 'get_date_modified' ) 	&& ! empty($order->get_date_modified()) &&	is_string( $order->get_date_modified()->date("F j, Y, g:i:s A T")) ) 	? 	$order->get_date_modified()->date("F j, Y, g:i:s A T") 	: ""; 
				$order_data['date_completed'] 				=  ( method_exists( $order, 'get_date_completed' ) 	&& ! empty($order->get_date_completed())&&	is_string( $order->get_date_completed()->date("F j, Y, g:i:s A T"))) 	? 	$order->get_date_completed()->date("F j, Y, g:i:s A T") : "";
				$order_data['date_paid'] 					=  ( method_exists( $order, 'get_date_paid' ) 		&& ! empty($order->get_date_paid()) 	&&	is_string( $order->get_date_paid()->date("F j, Y, g:i:s A T")) ) 	 	? 	$order->get_date_paid()->date("F j, Y, g:i:s A T") 		: "";
				
				# New Code Starts  
				# ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$order_data["date_created_year"]			=	( method_exists( $order, 'get_date_created' ) 	&& ! empty($order->get_date_created()) 	&&	is_string( $order->get_date_created()->date("F j, Y, g:i:s A T")) )  	? 	$order->get_date_created()->date("Y") 	  	: "";
				$order_data["date_created_month"]			=	( method_exists( $order, 'get_date_created' ) 	&& ! empty($order->get_date_created()) 	&&	is_string( $order->get_date_created()->date("F j, Y, g:i:s A T")) )  	? 	$order->get_date_created()->date("m") 	  	: "";
				$order_data["date_created_date"]			=	( method_exists( $order, 'get_date_created' ) 	&& ! empty($order->get_date_created()) 	&&	is_string( $order->get_date_created()->date("F j, Y, g:i:s A T")) )  	? 	$order->get_date_created()->date("d") 	  	: "";
				$order_data["date_created_time"]			=	( method_exists( $order, 'get_date_created' ) 	&& ! empty($order->get_date_created()) 	&&	is_string( $order->get_date_created()->date("F j, Y, g:i:s A T")) )  	? 	$order->get_date_created()->date("H:i")   	: "";
				
				$order_data["date_modified_year"]			=	( method_exists( $order, 'get_date_modified' ) 	&& ! empty($order->get_date_modified()) &&	is_string( $order->get_date_modified()->date("F j, Y, g:i:s A T")) )  	? 	$order->get_date_modified()->date("Y") 	  	: "";
				$order_data["date_modified_month"]			=	( method_exists( $order, 'get_date_modified' ) 	&& ! empty($order->get_date_modified()) &&	is_string( $order->get_date_modified()->date("F j, Y, g:i:s A T")) )  	? 	$order->get_date_modified()->date("m") 	  	: "";
				$order_data["date_modified_date"]			=	( method_exists( $order, 'get_date_modified' ) 	&& ! empty($order->get_date_modified()) &&	is_string( $order->get_date_modified()->date("F j, Y, g:i:s A T")) )  	? 	$order->get_date_modified()->date("d") 	  	: "";
				$order_data["date_modified_time"]			=	( method_exists( $order, 'get_date_modified' ) 	&& ! empty($order->get_date_modified()) &&	is_string( $order->get_date_modified()->date("F j, Y, g:i:s A T")) )  	? 	$order->get_date_modified()->date("H:i")  	: "";
				
				$order_data["date_completed_year"]			=	( method_exists( $order, 'get_date_completed' ) && ! empty($order->get_date_completed()) &&	is_string( $order->get_date_completed()->date("F j, Y, g:i:s A T")) )  	? 	$order->get_date_completed()->date("Y")   	: "";
				$order_data["date_completed_month"]			=	( method_exists( $order, 'get_date_completed' ) && ! empty($order->get_date_completed()) &&	is_string( $order->get_date_completed()->date("F j, Y, g:i:s A T")) )  	? 	$order->get_date_completed()->date("m")   	: "";
				$order_data["date_completed_date"]			=	( method_exists( $order, 'get_date_completed' ) && ! empty($order->get_date_completed()) &&	is_string( $order->get_date_completed()->date("F j, Y, g:i:s A T")) )  	? 	$order->get_date_completed()->date("d")   	: "";
				$order_data["date_completed_time"]			=	( method_exists( $order, 'get_date_completed' ) && ! empty($order->get_date_completed()) &&	is_string( $order->get_date_completed()->date("F j, Y, g:i:s A T")) )  	? 	$order->get_date_completed()->date("H:i") 	: "";
		
				$order_data["date_paid_year"]				=	( method_exists( $order, 'get_date_paid' ) 		&& ! empty($order->get_date_paid()) 	 &&	is_string( $order->get_date_paid()->date("F j, Y, g:i:s A T")) )  	  	? 	$order->get_date_paid()->date("Y")	  		: "";
				$order_data["date_paid_month"]				=	( method_exists( $order, 'get_date_paid' ) 		&& ! empty($order->get_date_paid()) 	 &&	is_string( $order->get_date_paid()->date("F j, Y, g:i:s A T")) )  		? 	$order->get_date_paid()->date("m")	  		: "";
				$order_data["date_paid_date"]				=	( method_exists( $order, 'get_date_paid' ) 		&& ! empty($order->get_date_paid()) 	 &&	is_string( $order->get_date_paid()->date("F j, Y, g:i:s A T")) )  		? 	$order->get_date_paid()->date("d")	  		: "";
				$order_data["date_paid_time"]				=	( method_exists( $order, 'get_date_paid' ) 		&& ! empty($order->get_date_paid()) 	 &&	is_string( $order->get_date_paid()->date("F j, Y, g:i:s A T")) )  		? 	$order->get_date_paid()->date("H:i")	  	: "";
				# +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				# New Code Starts  
				$order_data['user'] 						=  ( method_exists( $order, 'get_user')  				&&  ! empty($order->get_user()) && is_object( $order->get_user()) ) ? 	$order->get_user()->user_login  . " - " . $order->get_user()->user_email 	: "";
				$order_data['customer_id'] 					=  ( method_exists( $order, 'get_customer_id' ) 		&& 	is_int( $order->get_customer_id() )) 			? 	$order->get_customer_id() 			: "";
				$order_data['user_id'] 						=  ( method_exists( $order, 'get_user_id' ) 			&& 	is_int( $order->get_user_id() )) 				? 	$order->get_user_id()				: "";
				$order_data['customer_ip_address'] 			=  ( method_exists( $order, 'get_customer_ip_address')  && 	is_string( $order->get_customer_ip_address())) 	? 	$order->get_customer_ip_address()	: "";
				$order_data['customer_user_agent'] 			=  ( method_exists( $order, 'get_customer_user_agent')  && 	is_string( $order->get_customer_user_agent()))	? 	$order->get_customer_user_agent()	: "";
				$order_data['created_via'] 					=  ( method_exists( $order, 'get_created_via' ) 		&& 	is_string( $order->get_created_via() ))			? 	$order->get_created_via()			: "";
				$order_data['customer_note'] 				=  ( method_exists( $order, 'get_customer_note' ) 		&& 	is_string( $order->get_customer_note() ))		? 	$order->get_customer_note()			: "";
			}
		}
		# site Current Time
		$order_data['site_time'] 					= ( isset( $this->Time ) ) ? 	$this->Time		:	'';
		$order_data['site_date'] 					= ( isset( $this->Date ) ) ? 	$this->Date		:	'';
		# Start
		$order_data['shipping_first_name'] 			=  ( method_exists( $order, 'get_shipping_first_name' ) && 	is_string( $order->get_shipping_first_name())) 	? 	$order->get_shipping_first_name()	: "";
		$order_data['shipping_last_name'] 			=  ( method_exists( $order, 'get_shipping_last_name' )  && 	is_string( $order->get_shipping_last_name() ))	? 	$order->get_shipping_last_name()	: "";
		$order_data['shipping_company'] 			=  ( method_exists( $order, 'get_shipping_company' ) 	&& 	is_string( $order->get_shipping_company() ))	?	$order->get_shipping_company()		: "";
		$order_data['shipping_address_1'] 			=  ( method_exists( $order, 'get_shipping_address_1' )  && 	is_string( $order->get_shipping_address_1() ))	? 	$order->get_shipping_address_1()	: "";
		$order_data['shipping_address_2'] 			=  ( method_exists( $order, 'get_shipping_address_2' )  && 	is_string( $order->get_shipping_address_2() ))	? 	$order->get_shipping_address_2()	: "";
		$order_data['shipping_city'] 				=  ( method_exists( $order, 'get_shipping_city' ) 		&& 	is_string( $order->get_shipping_city() ))		? 	$order->get_shipping_city()			: "";
		$order_data['shipping_state'] 				=  ( method_exists( $order, 'get_shipping_state' ) 	 	&& 	is_string( $order->get_shipping_state() )) 		? 	$order->get_shipping_state()		: "";
		$order_data['shipping_postcode'] 			=  ( method_exists( $order, 'get_shipping_postcode' ) 	&& 	is_string( $order->get_shipping_postcode() ))	? 	$order->get_shipping_postcode()		: "";
		# Start
		if ( wpgsi_fs()->is__premium_only() ) {
			if ( wpgsi_fs()->can_use_premium_code() ){
				$order_data['shipping_country'] 			=  ( method_exists( $order, 'get_shipping_country' ) 			&& 	is_string( $order->get_shipping_country() )) 			? 	$order->get_shipping_country()				: "";
				$order_data['address'] 						=  ( method_exists( $order,	'get_address' ) 	 				&& 	is_array(  $order->get_address()) ) 					? 	json_encode( $order->get_address()) 		: "";
				$order_data['shipping_address_map_url'] 	=  ( method_exists( $order, 'get_shipping_address_map_url' ) 	&&	is_string( $order->get_shipping_address_map_url()))		?	$order->get_shipping_address_map_url()		: "";
				$order_data['formatted_billing_full_name'] 	=  ( method_exists( $order, 'get_formatted_billing_full_name' ) && is_string( $order->get_formatted_billing_full_name() ))	?	$order->get_formatted_billing_full_name()	: "";
				$order_data['formatted_shipping_full_name']	=  ( method_exists( $order, 'get_formatted_shipping_full_name' )&& is_string( $order->get_formatted_shipping_full_name() ))?	$order->get_formatted_shipping_full_name()	: "";
				$order_data['formatted_billing_address'] 	=  ( method_exists( $order, 'get_formatted_billing_address' ) 	&& is_string( $order->get_formatted_billing_address() ))	?	$order->get_formatted_billing_address()		: "";
				$order_data['formatted_shipping_address'] 	=  ( method_exists( $order, 'get_formatted_shipping_address' )  && is_string( $order->get_formatted_shipping_address() ))	?	$order->get_formatted_shipping_address()	: "";
				#
				$order_data['payment_method'] 				=  ( method_exists( $order, 'get_payment_method' ) 				&& 	is_string( $order->get_payment_method() ))				?	$order->get_payment_method()				: "";
				$order_data['payment_method_title'] 		=  ( method_exists( $order, 'get_payment_method_title' ) 		&& 	is_string( $order->get_payment_method_title() ))		? 	$order->get_payment_method_title()			: "";
				$order_data['transaction_id'] 				=  ( method_exists( $order, 'get_transaction_id' ) 				&& 	is_string( $order->get_transaction_id() ))				? 	$order->get_transaction_id()				: "";
				#
				$order_data['checkout_payment_url'] 		=  ( method_exists( $order, 'get_checkout_payment_url' ) 		&&	is_string( $order->get_checkout_payment_url() ))		? 	$order->get_checkout_payment_url()			: "";
				$order_data['checkout_order_received_url'] 	=  ( method_exists( $order, 'get_checkout_order_received_url') 	&& 	is_string( $order->get_checkout_order_received_url() )) ? 	$order->get_checkout_order_received_url()	: "";
				$order_data['cancel_order_url'] 			=  ( method_exists( $order, 'get_cancel_order_url' ) 			&& 	is_string( $order->get_cancel_order_url() ))			? 	$order->get_cancel_order_url()				: "";
				$order_data['cancel_order_url_raw'] 		=  ( method_exists( $order, 'get_cancel_order_url_raw' ) 		&& 	is_string( $order->get_cancel_order_url_raw()))			? 	$order->get_cancel_order_url_raw()			: "";
				$order_data['cancel_endpoint'] 				=  ( method_exists( $order, 'get_cancel_endpoint' ) 			&& 	is_string( $order->get_cancel_endpoint() ))				? 	$order->get_cancel_endpoint()				: "";
				$order_data['view_order_url'] 				=  ( method_exists( $order, 'get_view_order_url' ) 				&& 	is_string( $order->get_view_order_url() ))				? 	$order->get_view_order_url()				: "";
				$order_data['edit_order_url'] 				=  ( method_exists( $order, 'get_edit_order_url' ) 				&& 	is_string( $order->get_edit_order_url() )) 				? 	$order->get_edit_order_url()				: "";
			}
		}
		#
		$order_data['eventName']  							=  $order->get_status();
		$order_data['status'] 								= "wc-".$order->get_status();
		#
		if ( wpgsi_fs()->is__premium_only() ) {
			if ( wpgsi_fs()->can_use_premium_code() ){
				# Order Meta Data Starts
				# Empty Holder array 
				$metaOutPut = array();	
				# Global Db object 
				global $wpdb;
				# execute Query
				$orderMetaKeyValue = $wpdb->get_results( "SELECT * FROM $wpdb->postmeta WHERE post_id = " . $order->get_id() , ARRAY_A );
				# get Distinct Keys;
				$metaKeys = $this->wpgsi_wooCommerce_order_metaKeys();
				# Check and Balance for all the Meta keys
				if ( $metaKeys[0] &&  ! empty( $orderMetaKeyValue ) ){
					# populating Output array in revers with  empty value
					foreach ( $metaKeys[1]  as $key => $value ){
						$metaOutPut[$value] = "--";
					}
					# Looping the Meta key & value of Certain Comment And Populating the $metaOutPut Key array with Value 
					foreach ( $orderMetaKeyValue  as $oneArray ) {
						if ( is_array( $oneArray ) && isset( $oneArray['meta_key'], $metaOutPut[ $oneArray[ 'meta_key' ] ], $oneArray[ 'meta_value' ] ) ){
							# Convert text to  an array then JSON for reducing the String 
							$isArrayTest = @unserialize( $oneArray[ 'meta_value' ] );
							if ( $isArrayTest == null ) {
								$metaOutPut[ $oneArray['meta_key'] ] = $oneArray[ 'meta_value' ];
							} else {
								$metaOutPut[ $oneArray['meta_key'] ] =  $isArrayTest;
							}
						}
					}
				}
				# Append New metaOutPut array to $commentData data array;
				$order_data = array_merge( $order_data, $metaOutPut);
				# Order Meta Data Ends
			}
		}
		
		# freemius
		if ( wpgsi_fs()->is__premium_only() ) {
			if ( wpgsi_fs()->can_use_premium_code() ) {
				# Getting Checkout Field Editor Value.
				# Checkout Field Editor (Checkout Manager) for WooCommerce By ThemeHigh  || Starts
				$woo_checkout_field_editor_pro = $this->wpgsi_woo_checkout_field_editor_pro_fields();
				if ( $woo_checkout_field_editor_pro[0] ){
					foreach ( $woo_checkout_field_editor_pro[1] as $key => $value ) {
						$order_data[ $key ] = ( isset( $woo_checkout_field_editor_pro[1][$key], $order_data["orderID"] )  &&  ! empty( get_post_meta( $order_data["orderID"], $key )[0] ) )   ?    get_post_meta( $order_data["orderID"], $key )[0]   :  "";
					}
				}
				# Checkout Field Editor (Checkout Manager) for WooCommerce By ThemeHigh  || Ends
			}
		}

		# Action
		if ( empty( $order_id ) ){
			$this->wpgsi_log( get_class($this), __METHOD__,"714", "Error: Order is empty !" );
		} else {
			$r = $this->wpgsi_eventBoss('Woocommerce', 'wc-' . $this_status_transition_to ,$order_data, $order_id );
		}
	}

	/**
	 * woocommerce_new_orders New Order  HOOK's callback function
	 * @since     1.0.0
	 * @param     int     $order_id     Order ID
	*/
	public function wpgsi_woocommerce_new_order_admin( $order_id ) {
		$order_data =  array();
		# getting order information 
		$order 		=  wc_get_order( $order_id );
		# if not admin returns
		if ( empty( $order_id ) && $order->get_created_via() != 'admin' ){
			return;
		}
		# check to see is there any integration on this order change Status.
		if ( ! $this->wpgsi_integrations(  "wc-".$order->get_status() )[0] ) {
			return;
		}
		#
		$order_data['orderID'] 						=  ( method_exists( $order, 'get_id' ) 			  		&&	is_int( $order->get_id()))						? 	$order->get_id()					: "";
		$order_data['billing_first_name'] 			=  ( method_exists( $order, 'get_billing_first_name' )  && 	is_string( $order->get_billing_first_name() ))	? 	$order->get_billing_first_name()	: "";
		$order_data['billing_last_name'] 			=  ( method_exists( $order, 'get_billing_last_name' ) 	&& 	is_string( $order->get_billing_last_name() ))	? 	$order->get_billing_last_name()		: "";
		$order_data['billing_company'] 				=  ( method_exists( $order, 'get_billing_company' ) 	&& 	is_string( $order->get_billing_company() ))		? 	$order->get_billing_company()		: "";
		$order_data['billing_address_1'] 			=  ( method_exists( $order, 'get_billing_address_1' ) 	&& 	is_string( $order->get_billing_address_1() ))	? 	$order->get_billing_address_1()		: "";
		$order_data['billing_address_2'] 			=  ( method_exists( $order, 'get_billing_address_2' ) 	&& 	is_string( $order->get_billing_address_2() ))	? 	$order->get_billing_address_2()		: "";
		$order_data['billing_city'] 				=  ( method_exists( $order, 'get_billing_city' ) 		&& 	is_string( $order->get_billing_city() ))		? 	$order->get_billing_city()			: "";
		$order_data['billing_state'] 				=  ( method_exists( $order, 'get_billing_state' ) 		&& 	is_string( $order->get_billing_state() )) 		? 	$order->get_billing_state()			: "";
		$order_data['billing_postcode'] 			=  ( method_exists( $order, 'get_billing_postcode' ) 	&& 	is_string( $order->get_billing_postcode() ))	? 	$order->get_billing_postcode()		: "";
		# 
		if ( wpgsi_fs()->is__premium_only() ) {
			if ( wpgsi_fs()->can_use_premium_code() ){
				$order_data['billing_country'] 				= ( method_exists( $order, 'get_billing_country' ) 	    && 	is_string( $order->get_billing_country() ))		? 	$order->get_billing_country()			:   "";
				$order_data['billing_email'] 				= ( method_exists( $order, 'get_billing_email' ) 		&& 	is_string( $order->get_billing_email() ))		? 	$order->get_billing_email()				:   "";
				$order_data['billing_phone'] 				= ( method_exists( $order, 'get_billing_phone' ) 		&& 	is_string( $order->get_billing_phone()))		? 	$order->get_billing_phone()				:   "";
				$order_data['cart_tax'] 					= ( method_exists( $order, 'get_cart_tax' ) 	  		&& 	is_string( $order->get_cart_tax()  ))			? 	$order->get_cart_tax() 					: 	"";
				$order_data['currency'] 					= ( method_exists( $order, 'get_currency' ) 	  		&& 	is_string( $order->get_currency()  ))			? 	$order->get_currency() 					:	"";
				$order_data['discount_tax'] 				= ( method_exists( $order, 'get_discount_tax' )   		&& 	is_string( $order->get_discount_tax() ))		?	$order->get_discount_tax() 				:	"";
				$order_data['discount_total'] 				= ( method_exists( $order, 'get_discount_total' ) 		&& 	is_string( $order->get_discount_total() ))		? 	$order->get_discount_total()			:	"";
				$order_data['fees'] 						= ( method_exists( $order, 'get_fees' ) 		  		&&  ! empty( $order->get_fees() ) && is_array( $order->get_fees()) ) 			?   json_encode( $order->get_fees()) 		: "";
				$order_data['shipping_method'] 				= ( method_exists( $order, 'get_shipping_method' )		&& 	is_string( $order->get_shipping_method() ))		? 	$order->get_shipping_method() 			:	"";
				$order_data['shipping_tax'] 				= ( method_exists( $order, 'get_shipping_tax' ) 		&& 	is_string( $order->get_shipping_tax()  ))		? 	$order->get_shipping_tax() 				:	"";
				$order_data['shipping_total'] 				= ( method_exists( $order, 'get_shipping_total' ) 		&& 	is_string( $order->get_shipping_total()  ))		? 	$order->get_shipping_total()			:	"";
				$order_data['subtotal'] 					= ( method_exists( $order, 'get_subtotal' ) 			&& 	is_float( $order->get_subtotal()  ))			? 	$order->get_subtotal()					:	"";
				
				$order_data['subtotal_to_display'] 			= ( method_exists( $order, 'get_subtotal_to_display') 	&& 	is_string( $order->get_subtotal_to_display()))? $order->get_subtotal_to_display() 			: 	"";
				$order_data['tax_totals'] 					= ( method_exists( $order, 'get_tax_totals' ) 			&&  ! empty($order->get_tax_totals()) 	&& is_array( $order->get_tax_totals())) ?  json_encode( $order->get_tax_totals()) 	: ""; 
				$order_data['taxes'] 						= ( method_exists( $order, 'get_taxes' ) 				&&  ! empty($order->get_taxes()) 		&& is_array( $order->get_taxes()) ) 	?  json_encode( $order->get_taxes()) 		: "";  
				$order_data['total'] 						= ( method_exists( $order, 'get_total' ) 				&& 	is_string( $order->get_total() ))			 	 ?  $order->get_total() 		 			:	"";
				$order_data['total_discount'] 				= ( method_exists( $order, 'get_total_discount' ) 		&& 	is_float( $order->get_total_discount()  ))   	 ?  $order->get_total_discount() 			:	"";
				$order_data['total_tax'] 					= ( method_exists( $order, 'get_total_tax'  ) 			&& 	is_string( $order->get_total_tax() ))		 	 ? 	$order->get_total_tax() 	 			:	"";
				$order_data['total_refunded'] 				= ( method_exists( $order, 'get_total_refunded' ) 		&& 	is_float( $order->get_total_refunded() ))	 	 ? 	$order->get_total_refunded() 			:	"";
				$order_data['total_tax_refunded'] 			= ( method_exists( $order, 'get_total_tax_refunded' ) 	&& 	is_int( $order->get_total_tax_refunded()))	 	 ?  $order->get_total_tax_refunded()		:	"";
				$order_data['total_shipping_refunded'] 		= ( method_exists( $order, 'get_total_shipping_refunded')&& is_int( $order->get_total_shipping_refunded() )) ?  $order->get_total_shipping_refunded() 	:	"";
				$order_data['item_count_refunded'] 			= ( method_exists( $order, 'get_item_count_refunded' ) 	&& 	is_int( $order->get_item_count_refunded() )) 	 ?  $order->get_item_count_refunded() 		:	"";
				$order_data['total_qty_refunded'] 			= ( method_exists( $order, 'get_total_qty_refunded' ) 	&& 	is_int( $order->get_total_qty_refunded() ))  	 ?  $order->get_total_qty_refunded() 		:	"";
				$order_data['remaining_refund_amount']  	= ( method_exists( $order, 'get_remaining_refund_amount')&& is_string($order->get_remaining_refund_amount()))?  $order->get_remaining_refund_amount()	:	"";
				# Order Item process Starts
				if ( method_exists( $order, 'get_items') AND is_array( $order->get_items()) ){ 
					# Declaring Empty Array Holder 
					$product_ids = array();
					$order_data['items'] 	     					= " ";
					$order_data['get_product_id'] 			 		= " ";	 
					$order_data['get_name'] 				 		= " ";	  
					$order_data['get_quantity'] 			 		= " ";	  
					$order_data['get_total'] 				 		= " ";	 	
					$order_data['get_sku'] 					 		= " ";	 	
					$order_data['get_type'] 			 	 		= " ";	   
					$order_data['get_slug'] 			 	 		= " ";	
 
					$order_data['get_price'] 				 		= " ";	
					$order_data['get_regular_price'] 		 		= " ";
					$order_data['get_sale_price'] 			 		= " ";	 
 
					$order_data['get_virtual'] 				 		= " "; 	
					$order_data['get_permalink'] 			 		= " ";	
					$order_data['get_featured'] 			 		= " ";	
					$order_data['get_status'] 				 		= " ";	 
					$order_data['get_tax_status'] 			 		= " "; 	
					$order_data['get_tax_class'] 			 		= " "; 	
					$order_data['get_manage_stock'] 		 		= " "; 	
					$order_data['get_stock_quantity'] 		 		= " ";  
					$order_data['get_stock_status'] 		 		= " "; 	
					$order_data['get_backorders'] 			 		= " "; 
					$order_data['get_sold_individually']	 		= " "; 	
					$order_data['get_purchase_note'] 		 		= " ";
					$order_data['get_shipping_class_id']	 		= " ";
 
					$order_data['get_weight'] 				 		= " ";
					$order_data['get_length'] 				 		= " ";
					$order_data['get_width'] 				 		= " ";
					$order_data['get_height'] 				 		= " "; 	
 
					$order_data['get_default_attributes'] 	 		= " ";
 
					$order_data['get_category_ids'] 		 		= " ";
					$order_data['get_tag_ids'] 				 		= " ";
					 
					$order_data['get_image_id'] 			 		= " ";
					$order_data['get_gallery_image_ids'] 	 		= " "; 
					$order_data['get_attachment_image_url'] 	 	= " "; 
					# Item Meta Empty Holders 
					# New Code Ends 
					foreach ( $order->get_items() as $item_id => $item_data ) {
						
						$order_data['items'] .= (( method_exists( $item_data, "get_product_id" ) AND 	is_int( $item_data->get_product_id())) 	AND !empty($item_data->get_product_id()))	?  $item_data->get_product_id() 			: "--"; 
						$order_data['items'] .= (( method_exists( $item_data, "get_name" ) 	   	 AND 	is_string( $item_data->get_name() )) 	AND	!empty($item_data->get_name()))			?  " " . $item_data->get_name() 		 	: "--"; 
						$order_data['items'] .= (( method_exists( $item_data, "get_quantity" ) 	 AND 	is_int( $item_data->get_quantity() ))	AND !empty($item_data->get_quantity()))		?  " qty - " . $item_data->get_quantity() 	: "--"; 
						$order_data['items'] .= (( method_exists( $item_data, "get_total" ) 	 AND 	is_string( $item_data->get_total() ))	AND !empty($item_data->get_total()))		?  " total - " .  	$item_data->get_total() : "--"; 
						
						# New Code Starts 
						$product_ids[] 								     =	( (method_exists( $item_data, 'get_product_id')							AND is_int( $item_data->get_product_id() ))						    AND !empty( $item_data->get_product_id()) )						?  $item_data->get_product_id()														:	"--";
						$order_data['get_product_id'] 					.=	( (method_exists( $item_data, 'get_product_id')							AND is_int( $item_data->get_product_id() ))						    AND !empty( $item_data->get_product_id()) )						?  $item_data->get_product_id()														:	"--";
						$order_data['get_name'] 						.=  ( (method_exists( $item_data, 'get_name')								AND is_string( $item_data->get_name() ))						    AND !empty( $item_data->get_name())	)							?  $item_data->get_name()															:	"--";
						$order_data['get_quantity'] 					.=  ( (method_exists( $item_data, 'get_quantity')							AND is_int( $item_data->get_quantity() ))						    AND !empty( $item_data->get_quantity())	)						?  $item_data->get_quantity()														:	"--";
						$order_data['get_total'] 						.= 	( (method_exists( $item_data, 'get_total')								AND is_string( $item_data->get_total() ))						    AND !empty( $item_data->get_total()) )							?  $item_data->get_total()															:	"--";	
						$order_data['get_sku'] 							.= 	( (method_exists( $item_data->get_product(), 'get_sku')					AND is_string( $item_data->get_product()->get_sku() )) 			    AND !empty( $item_data->get_product()->get_sku()) )				?  $item_data->get_product()->get_sku()												:	"--";
						$order_data['get_type'] 						.= 	( (method_exists( $item_data->get_product(), 'get_type')				AND is_string( $item_data->get_product()->get_type() ))			    AND !empty( $item_data->get_product()->get_type()) )			?  $item_data->get_product()->get_type()											:	"--";
						$order_data['get_slug'] 						.= 	( (method_exists( $item_data->get_product(), 'get_slug')				AND is_string( $item_data->get_product()->get_slug() ))			    AND !empty( $item_data->get_product()->get_slug()) )			?  $item_data->get_product()->get_slug()											:	"--";
					
						$order_data['get_price'] 						.= 	( (method_exists( $item_data->get_product(), 'get_price')				AND is_string( $item_data->get_product()->get_price() ))		    AND !empty( $item_data->get_product()->get_price())	)			?  $item_data->get_product()->get_price()											:	"--";
						$order_data['get_regular_price'] 				.= 	( (method_exists( $item_data->get_product(), 'get_regular_price')		AND is_string( $item_data->get_product()->get_regular_price()))     AND !empty( $item_data->get_product()->get_regular_price())	)	?  $item_data->get_product()->get_regular_price()									:	"--";
						$order_data['get_sale_price'] 					.= 	( (method_exists( $item_data->get_product(), 'get_sale_price')			AND is_string( $item_data->get_product()->get_sale_price()  ))	    AND !empty( $item_data->get_product()->get_sale_price()) )		?  $item_data->get_product()->get_sale_price()										:	"--";
						
						$order_data['get_virtual'] 						.= 	( (method_exists( $item_data->get_product(), 'get_virtual')				AND is_bool( $item_data->get_product()->get_virtual()  ))		    AND !empty( $item_data->get_product()->get_virtual()) )			?  $item_data->get_product()->get_virtual()											:	"--";
						$order_data['get_permalink'] 					.=	( (method_exists( $item_data->get_product(), 'get_permalink')			AND is_string( $item_data->get_product()->get_permalink() ))	    AND !empty( $item_data->get_product()->get_permalink()) )		?  $item_data->get_product()->get_permalink()										:	"--";
						$order_data['get_featured'] 					.=	( (method_exists( $item_data->get_product(), 'get_featured')			AND is_bool( $item_data->get_product()->get_featured()  ))		    AND !empty( $item_data->get_product()->get_featured()) )		?  $item_data->get_product()->get_featured()										:	"--";
						$order_data['get_status'] 						.=	( (method_exists( $item_data->get_product(), 'get_status')				AND is_string( $item_data->get_product()->get_status()  ))		    AND !empty( $item_data->get_product()->get_status()) )			?  $item_data->get_product()->get_status()											:	"--";
						$order_data['get_tax_status'] 					.= 	( (method_exists( $item_data->get_product(), 'get_tax_status')			AND is_string( $item_data->get_product()->get_tax_status()  ))	    AND !empty( $item_data->get_product()->get_tax_status()) )		?  $item_data->get_product()->get_tax_status()										:	"--";
						$order_data['get_tax_class'] 					.= 	( (method_exists( $item_data->get_product(), 'get_tax_class')			AND is_string( $item_data->get_product()->get_tax_class()  ))	    AND !empty( $item_data->get_product()->get_tax_class() ) )		?  $item_data->get_product()->get_tax_class()										:	"--";
						$order_data['get_manage_stock'] 				.= 	( (method_exists( $item_data->get_product(), 'get_manage_stock')		AND is_bool( $item_data->get_product()->get_manage_stock()  ))	    AND !empty( $item_data->get_product()->get_manage_stock() )	)	?  $item_data->get_product()->get_manage_stock()									:	"--";
						$order_data['get_stock_quantity'] 				.= 	( (method_exists( $item_data->get_product(), 'get_stock_quantity')		AND is_string( $item_data->get_product()->get_stock_quantity() ))   AND !empty( $item_data->get_product()->get_stock_quantity()) )	?  $item_data->get_product()->get_stock_quantity()									:	"--";
						$order_data['get_stock_status'] 				.= 	( (method_exists( $item_data->get_product(), 'get_stock_status')		AND is_string( $item_data->get_product()->get_stock_status()  ))    AND !empty( $item_data->get_product()->get_stock_status()) )	?  $item_data->get_product()->get_stock_status()									:	"--";
						$order_data['get_backorders'] 					.= 	( (method_exists( $item_data->get_product(), 'get_backorders')			AND is_string( $item_data->get_product()->get_backorders()  ))	    AND !empty($item_data->get_product()->get_backorders()) )		?  $item_data->get_product()->get_backorders()										:	"--";
						$order_data['get_sold_individually']			.= 	( (method_exists( $item_data->get_product(), 'get_sold_individually')	AND is_bool( $item_data->get_product()->get_sold_individually()))   AND !empty($item_data->get_product()->get_sold_individually()) )?  $item_data->get_product()->get_sold_individually()								:	"--";
						$order_data['get_purchase_note'] 				.= 	( (method_exists( $item_data->get_product(), 'get_purchase_note')		AND is_string( $item_data->get_product()->get_purchase_note() ))    AND !empty( $item_data->get_product()->get_purchase_note()) )	?  $item_data->get_product()->get_purchase_note()									:	"--";
						$order_data['get_shipping_class_id']			.= 	( (method_exists( $item_data->get_product(), 'get_shipping_class_id')	AND is_int( $item_data->get_product()->get_shipping_class_id() ))   AND !empty($item_data->get_product()->get_shipping_class_id() ))?  $item_data->get_product()->get_shipping_class_id()								:	"--";
						
						$order_data['get_weight'] 						.= 	( (method_exists( $item_data->get_product(), 'get_weight')				AND is_string( $item_data->get_product()->get_weight() ))		    AND !empty($item_data->get_product()->get_weight())	)			?  $item_data->get_product()->get_weight()											:	"--";
						$order_data['get_length'] 						.= 	( (method_exists( $item_data->get_product(), 'get_length')				AND is_string( $item_data->get_product()->get_length() ))		    AND !empty($item_data->get_product()->get_length())	)			?  $item_data->get_product()->get_length()											:	"--";
						$order_data['get_width'] 						.= 	( (method_exists( $item_data->get_product(), 'get_width')				AND is_string( $item_data->get_product()->get_width()  ))		    AND !empty( $item_data->get_product()->get_width())	)			?  $item_data->get_product()->get_width()											:	"--";
						$order_data['get_height'] 						.= 	( (method_exists( $item_data->get_product(), 'get_height')				AND is_string( $item_data->get_product()->get_height() ))		    AND !empty( $item_data->get_product()->get_height()))			?  $item_data->get_product()->get_height()											:	"--";
						
						$order_data['get_default_attributes'] 			.= 	( (method_exists( $item_data->get_product(), 'get_default_attributes')	AND is_array( $item_data->get_product()->get_default_attributes())) AND !empty($item_data->get_product()->get_default_attributes()))? json_encode($item_data->get_product()->get_default_attributes()) 					:	"--";
						
						$order_data['get_image_id'] 					.= 	( (method_exists( $item_data->get_product(), 'get_image_id')			AND is_string( $item_data->get_product()->get_image_id()  ))		AND !empty($item_data->get_product()->get_image_id()))			?  $item_data->get_product()->get_image_id()										:	"--";
						$order_data['get_gallery_image_ids'] 			.= 	( (method_exists( $item_data->get_product(), 'get_gallery_image_ids')	AND is_array( $item_data->get_product()->get_gallery_image_ids() ))	AND !empty($item_data->get_product()->get_gallery_image_ids()))	?  json_encode( $item_data->get_product()->get_gallery_image_ids())					:	"--";
						$order_data['get_attachment_image_url'] 		.= 	( (method_exists( $item_data->get_product(), 'get_image_id')			AND function_exists('wp_get_attachment_image_url') )				AND !empty($item_data->get_product()->get_image_id()))			?  wp_get_attachment_image_url( $item_data->get_product()->get_image_id())			:	"--";
						# get all the Products( many Products ) Same Meta Key Value # reduce The 
						# New Code 
						# Creating New Line 
						$order_data['items'] 							.=  " \n ";
						$order_data['get_product_id'] 			 		.=	" \n "; 
						$order_data['get_name'] 				 		.=	" \n ";  
						$order_data['get_quantity'] 			 		.=	" \n ";  
						$order_data['get_total'] 				 		.=	" \n "; 	
						$order_data['get_sku'] 					 		.=	" \n "; 	
						$order_data['get_type'] 			 	 		.=	" \n ";   
						$order_data['get_slug'] 			 	 		.=	" \n ";
  
						$order_data['get_price'] 				 		.=	" \n "; 	
						$order_data['get_regular_price'] 		 		.=	" \n "; 
						$order_data['get_sale_price'] 			 		.=	" \n "; 	 
  
						$order_data['get_virtual'] 				 		.=	" \n "; 	
						$order_data['get_permalink'] 			 		.=	" \n ";	
						$order_data['get_featured'] 			 		.=	" \n ";	
						$order_data['get_status'] 				 		.=	" \n ";	 
						$order_data['get_tax_status'] 			 		.=	" \n "; 	
						$order_data['get_tax_class'] 			 		.=	" \n "; 	
						$order_data['get_manage_stock'] 		 		.=	" \n "; 	
						$order_data['get_stock_quantity'] 		 		.=	" \n ";  
						$order_data['get_stock_status'] 		 		.=	" \n "; 	
						$order_data['get_backorders'] 			 		.=	" \n "; 
						$order_data['get_sold_individually']	 		.=	" \n "; 	
						$order_data['get_purchase_note'] 		 		.=	" \n ";
						$order_data['get_shipping_class_id']	 		.=	" \n ";
  
						$order_data['get_weight'] 					 	.=	" \n ";
						$order_data['get_length'] 				 		.=	" \n ";
						$order_data['get_width'] 				 		.=	" \n ";
						$order_data['get_height'] 				 		.=	" \n "; 	

						$order_data['get_default_attributes'] 	 		.=	" \n ";

						$order_data['get_category_ids'] 		 		.=	" \n ";
						$order_data['get_tag_ids'] 				 		.=	" \n ";

						$order_data['get_image_id'] 			 		.=	" \n ";
						$order_data['get_gallery_image_ids'] 	 		.=	" \n ";
						$order_data['get_attachment_image_url'] 	 	.=	" \n ";
						# New Code Ends 
					}
				}

				# Inserting Order items Meta value
				if( ! empty( $product_ids ) ) {
					#item meta key value Holder 
					$itemMetaKeyValue = array();
					# wpdb
					global $wpdb;
					# DB query 
					$query 	 = "SELECT * FROM $wpdb->postmeta WHERE post_id IN ('". join("','",$product_ids) ."') ORDER BY FIELD(post_id, ".join(",",$product_ids).") ";
					# Running the Query
					$productMeta = $wpdb->get_results( $query, ARRAY_A );
					if( ! empty( $productMeta ) ){
						foreach( $productMeta as $key => $valueArray) {
							if( isset( $valueArray['meta_key']  ) AND  isset( $valueArray['meta_value'] ) ) {
								# error handling 
								if( isset( $itemMetaKeyValue[ $valueArray['meta_key'] ]  ) ){
									$itemMetaKeyValue[ $valueArray['meta_key'] ] .= ( !empty($valueArray['meta_value']) )? $valueArray['meta_value'] . " \n " : "-- \n ";
								} else {
									$itemMetaKeyValue[ $valueArray['meta_key'] ]  ="";
									$itemMetaKeyValue[ $valueArray['meta_key'] ] .= ( !empty($valueArray['meta_value']) )? $valueArray['meta_value'] . " \n " : "-- \n ";
								}
							}
						}
					}
				}
				# Joining the Product meta to Order data 
				$order_data = array_merge( $order_data, $itemMetaKeyValue );	
				# Order Item process Ends
				$order_data['item_count'] 			    	=  ( method_exists( $order, 'get_item_count') 			&& 	is_int($order->get_item_count() )) 			? 	$order->get_item_count() : "";
				$order_data['downloadable_items'] 			=  ( method_exists( $order, 'get_downloadable_items' ) 	&& ! empty($order->get_downloadable_items())&&  is_array(  $order->get_downloadable_items()) ) 	? json_encode( $order->get_downloadable_items()) : "";   
				#
				$order_data['date_created'] 				=  ( method_exists( $order, 'get_date_created' ) 	&& ! empty($order->get_date_created()) 	&&	is_string( $order->get_date_created()->date("F j, Y, g:i:s A T") ) ) 	? 	$order->get_date_created()->date("F j, Y, g:i:s A T") 	: ""; 
				$order_data['date_modified'] 				=  ( method_exists( $order, 'get_date_modified' ) 	&& ! empty($order->get_date_modified()) &&	is_string( $order->get_date_modified()->date("F j, Y, g:i:s A T")) ) 	? 	$order->get_date_modified()->date("F j, Y, g:i:s A T") 	: ""; 
				$order_data['date_completed'] 				=  ( method_exists( $order, 'get_date_completed' ) 	&& ! empty($order->get_date_completed())&&	is_string( $order->get_date_completed()->date("F j, Y, g:i:s A T"))) 	? 	$order->get_date_completed()->date("F j, Y, g:i:s A T") : "";
				$order_data['date_paid'] 					=  ( method_exists( $order, 'get_date_paid' ) 		&& ! empty($order->get_date_paid()) 	&&	is_string( $order->get_date_paid()->date("F j, Y, g:i:s A T")) ) 	 	? 	$order->get_date_paid()->date("F j, Y, g:i:s A T") 		: "";
				
				# New Code Starts  
				# +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$order_data["date_created_year"]			=	( method_exists( $order, 'get_date_created' ) 	&& ! empty($order->get_date_created()) 	&&	is_string( $order->get_date_created()->date("F j, Y, g:i:s A T")) )  	? 	$order->get_date_created()->date("Y") 	  	: "";
				$order_data["date_created_month"]			=	( method_exists( $order, 'get_date_created' ) 	&& ! empty($order->get_date_created()) 	&&	is_string( $order->get_date_created()->date("F j, Y, g:i:s A T")) )  	? 	$order->get_date_created()->date("m") 	  	: "";
				$order_data["date_created_date"]			=	( method_exists( $order, 'get_date_created' ) 	&& ! empty($order->get_date_created()) 	&&	is_string( $order->get_date_created()->date("F j, Y, g:i:s A T")) )  	? 	$order->get_date_created()->date("d") 	  	: "";
				$order_data["date_created_time"]			=	( method_exists( $order, 'get_date_created' ) 	&& ! empty($order->get_date_created()) 	&&	is_string( $order->get_date_created()->date("F j, Y, g:i:s A T")) )  	? 	$order->get_date_created()->date("H:i")   	: "";
				
				$order_data["date_modified_year"]			=	( method_exists( $order, 'get_date_modified' ) 	&& ! empty($order->get_date_modified()) &&	is_string( $order->get_date_modified()->date("F j, Y, g:i:s A T")) )  	? 	$order->get_date_modified()->date("Y") 	  	: "";
				$order_data["date_modified_month"]			=	( method_exists( $order, 'get_date_modified' ) 	&& ! empty($order->get_date_modified()) &&	is_string( $order->get_date_modified()->date("F j, Y, g:i:s A T")) )  	? 	$order->get_date_modified()->date("m") 	  	: "";
				$order_data["date_modified_date"]			=	( method_exists( $order, 'get_date_modified' ) 	&& ! empty($order->get_date_modified()) &&	is_string( $order->get_date_modified()->date("F j, Y, g:i:s A T")) )  	? 	$order->get_date_modified()->date("d") 	  	: "";
				$order_data["date_modified_time"]			=	( method_exists( $order, 'get_date_modified' ) 	&& ! empty($order->get_date_modified()) &&	is_string( $order->get_date_modified()->date("F j, Y, g:i:s A T")) )  	? 	$order->get_date_modified()->date("H:i")  	: "";
				
				$order_data["date_completed_year"]			=	( method_exists( $order, 'get_date_completed' ) && ! empty($order->get_date_completed()) &&	is_string( $order->get_date_completed()->date("F j, Y, g:i:s A T")) )  	? 	$order->get_date_completed()->date("Y")   	: "";
				$order_data["date_completed_month"]			=	( method_exists( $order, 'get_date_completed' ) && ! empty($order->get_date_completed()) &&	is_string( $order->get_date_completed()->date("F j, Y, g:i:s A T")) )  	? 	$order->get_date_completed()->date("m")   	: "";
				$order_data["date_completed_date"]			=	( method_exists( $order, 'get_date_completed' ) && ! empty($order->get_date_completed()) &&	is_string( $order->get_date_completed()->date("F j, Y, g:i:s A T")) )  	? 	$order->get_date_completed()->date("d")   	: "";
				$order_data["date_completed_time"]			=	( method_exists( $order, 'get_date_completed' ) && ! empty($order->get_date_completed()) &&	is_string( $order->get_date_completed()->date("F j, Y, g:i:s A T")) )  	? 	$order->get_date_completed()->date("H:i") 	: "";
		
				$order_data["date_paid_year"]				=	( method_exists( $order, 'get_date_paid' ) 		&& ! empty($order->get_date_paid()) 	 &&	is_string( $order->get_date_paid()->date("F j, Y, g:i:s A T")) )  	  	? 	$order->get_date_paid()->date("Y")	  		: "";
				$order_data["date_paid_month"]				=	( method_exists( $order, 'get_date_paid' ) 		&& ! empty($order->get_date_paid()) 	 &&	is_string( $order->get_date_paid()->date("F j, Y, g:i:s A T")) )  		? 	$order->get_date_paid()->date("m")	  		: "";
				$order_data["date_paid_date"]				=	( method_exists( $order, 'get_date_paid' ) 		&& ! empty($order->get_date_paid()) 	 &&	is_string( $order->get_date_paid()->date("F j, Y, g:i:s A T")) )  		? 	$order->get_date_paid()->date("d")	  		: "";
				$order_data["date_paid_time"]				=	( method_exists( $order, 'get_date_paid' ) 		&& ! empty($order->get_date_paid()) 	 &&	is_string( $order->get_date_paid()->date("F j, Y, g:i:s A T")) )  		? 	$order->get_date_paid()->date("H:i")	  	: "";
				# +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				# New Code Starts  
				$order_data['user'] 						=  ( method_exists( $order, 'get_user')  				&&  ! empty($order->get_user()) && is_object( $order->get_user()) ) ? 	$order->get_user()->user_login  . " - " . $order->get_user()->user_email 	: "";
				$order_data['customer_id'] 					=  ( method_exists( $order, 'get_customer_id' ) 		&& 	is_int( $order->get_customer_id() )) 			? 	$order->get_customer_id() 			: "";
				$order_data['user_id'] 						=  ( method_exists( $order, 'get_user_id' ) 			&& 	is_int( $order->get_user_id() )) 				? 	$order->get_user_id()				: "";
				$order_data['customer_ip_address'] 			=  ( method_exists( $order, 'get_customer_ip_address')  && 	is_string( $order->get_customer_ip_address())) 	? 	$order->get_customer_ip_address()	: "";
				$order_data['customer_user_agent'] 			=  ( method_exists( $order, 'get_customer_user_agent')  && 	is_string( $order->get_customer_user_agent()))	? 	$order->get_customer_user_agent()	: "";
				$order_data['created_via'] 					=  ( method_exists( $order, 'get_created_via' ) 		&& 	is_string( $order->get_created_via() ))			? 	$order->get_created_via()			: "";
				$order_data['customer_note'] 				=  ( method_exists( $order, 'get_customer_note' ) 		&& 	is_string( $order->get_customer_note() ))		? 	$order->get_customer_note()			: "";
			}
		}
		# site Current Time
		$order_data['site_time'] 					= ( isset( $this->Time ) ) ? 	$this->Time		:	'';
		$order_data['site_date'] 					= ( isset( $this->Date ) ) ? 	$this->Date		:	'';
		# Start
		$order_data['shipping_first_name'] 			=  ( method_exists( $order, 'get_shipping_first_name' ) && 	is_string( $order->get_shipping_first_name())) 	? 	$order->get_shipping_first_name()	: "";
		$order_data['shipping_last_name'] 			=  ( method_exists( $order, 'get_shipping_last_name' )  && 	is_string( $order->get_shipping_last_name() ))	? 	$order->get_shipping_last_name()	: "";
		$order_data['shipping_company'] 			=  ( method_exists( $order, 'get_shipping_company' ) 	&& 	is_string( $order->get_shipping_company() ))	?	$order->get_shipping_company()		: "";
		$order_data['shipping_address_1'] 			=  ( method_exists( $order, 'get_shipping_address_1' )  && 	is_string( $order->get_shipping_address_1() ))	? 	$order->get_shipping_address_1()	: "";
		$order_data['shipping_address_2'] 			=  ( method_exists( $order, 'get_shipping_address_2' )  && 	is_string( $order->get_shipping_address_2() ))	? 	$order->get_shipping_address_2()	: "";
		$order_data['shipping_city'] 				=  ( method_exists( $order, 'get_shipping_city' ) 		&& 	is_string( $order->get_shipping_city() ))		? 	$order->get_shipping_city()			: "";
		$order_data['shipping_state'] 				=  ( method_exists( $order, 'get_shipping_state' ) 	 	&& 	is_string( $order->get_shipping_state() )) 		? 	$order->get_shipping_state()		: "";
		$order_data['shipping_postcode'] 			=  ( method_exists( $order, 'get_shipping_postcode' ) 	&& 	is_string( $order->get_shipping_postcode() ))	? 	$order->get_shipping_postcode()		: "";
		# Start
		if ( wpgsi_fs()->is__premium_only() ) {
			if ( wpgsi_fs()->can_use_premium_code() ) {
				$order_data['shipping_country'] 			=  ( method_exists( $order, 'get_shipping_country' ) 			 && is_string( $order->get_shipping_country() )) 			? 	$order->get_shipping_country()				: "";
				$order_data['address'] 						=  ( method_exists( $order,	'get_address' ) 	 				 && is_array(  $order->get_address()) ) 					? 	json_encode( $order->get_address()) 		: "";
				$order_data['shipping_address_map_url'] 	=  ( method_exists( $order, 'get_shipping_address_map_url' ) 	 &&	is_string( $order->get_shipping_address_map_url()))		?	$order->get_shipping_address_map_url()		: "";
				$order_data['formatted_billing_full_name'] 	=  ( method_exists( $order, 'get_formatted_billing_full_name' )  && is_string( $order->get_formatted_billing_full_name() ))	?	$order->get_formatted_billing_full_name()	: "";
				$order_data['formatted_shipping_full_name']	=  ( method_exists( $order, 'get_formatted_shipping_full_name' ) && is_string( $order->get_formatted_shipping_full_name() ))?	$order->get_formatted_shipping_full_name()	: "";
				$order_data['formatted_billing_address'] 	=  ( method_exists( $order, 'get_formatted_billing_address' ) 	 && is_string( $order->get_formatted_billing_address() ))	?	$order->get_formatted_billing_address()		: "";
				$order_data['formatted_shipping_address'] 	=  ( method_exists( $order, 'get_formatted_shipping_address' )   && is_string( $order->get_formatted_shipping_address() ))	?	$order->get_formatted_shipping_address()	: "";
				#
				$order_data['payment_method'] 				=  ( method_exists( $order, 'get_payment_method' ) 				&& 	is_string( $order->get_payment_method() ))				?	$order->get_payment_method()				: "";
				$order_data['payment_method_title'] 		=  ( method_exists( $order, 'get_payment_method_title' ) 		&& 	is_string( $order->get_payment_method_title() ))		? 	$order->get_payment_method_title()			: "";
				$order_data['transaction_id'] 				=  ( method_exists( $order, 'get_transaction_id' ) 				&& 	is_string( $order->get_transaction_id() ))				? 	$order->get_transaction_id()				: "";
				#
				$order_data['checkout_payment_url'] 		=  ( method_exists( $order, 'get_checkout_payment_url' ) 		&&	is_string( $order->get_checkout_payment_url() ))		? 	$order->get_checkout_payment_url()			: "";
				$order_data['checkout_order_received_url'] 	=  ( method_exists( $order, 'get_checkout_order_received_url') 	&& 	is_string( $order->get_checkout_order_received_url() )) ? 	$order->get_checkout_order_received_url()	: "";
				$order_data['cancel_order_url'] 			=  ( method_exists( $order, 'get_cancel_order_url' ) 			&& 	is_string( $order->get_cancel_order_url() ))			? 	$order->get_cancel_order_url()				: "";
				$order_data['cancel_order_url_raw'] 		=  ( method_exists( $order, 'get_cancel_order_url_raw' ) 		&& 	is_string( $order->get_cancel_order_url_raw()))			? 	$order->get_cancel_order_url_raw()			: "";
				$order_data['cancel_endpoint'] 				=  ( method_exists( $order, 'get_cancel_endpoint' ) 			&& 	is_string( $order->get_cancel_endpoint() ))				? 	$order->get_cancel_endpoint()				: "";
				$order_data['view_order_url'] 				=  ( method_exists( $order, 'get_view_order_url' ) 				&& 	is_string( $order->get_view_order_url() ))				? 	$order->get_view_order_url()				: "";
				$order_data['edit_order_url'] 				=  ( method_exists( $order, 'get_edit_order_url' ) 				&& 	is_string( $order->get_edit_order_url() )) 				? 	$order->get_edit_order_url()				: "";
			}
		}

		$order_data['eventName'] 							=  $order->get_status();  //'wc-new_order'
		$order_data['status'] 								=  "wc-".$order->get_status();
		# freemius
		if ( wpgsi_fs()->is__premium_only() ) {
			if ( wpgsi_fs()->can_use_premium_code() ) {
				# Checkout Field Editor (Checkout Manager) for WooCommerce By ThemeHigh  || Starts
				$woo_checkout_field_editor  =  $this->wpgsi_woo_checkout_field_editor_pro_fields();
				if ( $woo_checkout_field_editor[0] ){
					$woo_checkout_field_editor_options  =  get_option( "woo_checkout_field_editor" ) ;
					foreach ( $woo_checkout_field_editor_options as $key => $value ) {
						$order_data[ $key ] = ( isset( $woo_checkout_field_editor[1][$key], $order_data["orderID"] )  &&  ! empty( get_post_meta( $order_data["orderID"], $key )[0] ) )   ?    get_post_meta( $order_data["orderID"], $key )[0]   :  "";
					}
				}
				# Checkout Field Editor (Checkout Manager) for WooCommerce By ThemeHigh  || Ends
			}
		}

		if ( wpgsi_fs()->is__premium_only() ) {
			if ( wpgsi_fs()->can_use_premium_code() ) {
				# Order Meta Data Starts
				# Empty Holder array 
				$metaOutPut = array();	
				# Global Db object 
				global $wpdb;
				# execute Query
				$orderMetaKeyValue = $wpdb->get_results( "SELECT * FROM $wpdb->postmeta WHERE post_id = " . $order->get_id() , ARRAY_A );
				# get Distinct Keys;
				$metaKeys = $this->wpgsi_wooCommerce_order_metaKeys();
				# Check and Balance for all the Meta keys
				if ( $metaKeys[0] &&  ! empty( $orderMetaKeyValue ) ){
					# populating Output array in revers with  empty value
					foreach ( $metaKeys[1]  as $key => $value ){
						$metaOutPut[$value] = "--";
					}
					# Looping the Meta key & value of Certain Comment And Populating the $metaOutPut Key array with Value 
					foreach ( $orderMetaKeyValue  as $oneArray ) {
						if ( is_array( $oneArray ) && isset( $oneArray['meta_key'], $metaOutPut[ $oneArray[ 'meta_key' ] ], $oneArray[ 'meta_value' ] ) ){
							# Convert text to  an array then JSON for reducing the String 
							$isArrayTest = @unserialize( $oneArray[ 'meta_value' ] );
							if ( $isArrayTest == null ) {
								$metaOutPut[ $oneArray['meta_key'] ] = $oneArray[ 'meta_value' ];
							} else {
								$metaOutPut[ $oneArray['meta_key'] ] =  $isArrayTest;
							}
						}
					}
				}
				# Append New metaOutPut array to $commentData data array;
				$order_data = array_merge( $order_data, $metaOutPut);
				# Order Meta Data Ends
			}
		}

		# Action
		if ( empty( $order_id ) ){
			$this->wpgsi_log( get_class($this), __METHOD__,"715", "Error: Order is empty !" );
		} else {
			$r = $this->wpgsi_eventBoss( 'Woocommerce', $order_data['status'], $order_data, $order_id );
		}
	}

	/**
	 * WooCommerce Checkout PAge Order CallBack Function 
	 * @since     1.0.0
	 * @param     int     $order_id     Order ID
	*/
	public function wpgsi_woocommerce_new_order_checkout( $order_id ) {
		$order_data =  array();
		$order  	=  wc_get_order( $order_id );
		# if not checkout returns
		if ( empty( $order_id ) && $order->get_created_via() != 'checkout' ){
			return;
		}
		# check to see is there any integration on this order change Status.
		if ( ! $this->wpgsi_integrations( 'wc-new_order' )[0] ) {
			return;
		}
		#
		$order_data['orderID'] 						=  ( method_exists( $order, 'get_id' ) 			  		&&	is_int( $order->get_id()))						? 	$order->get_id()								: 	"";
		$order_data['billing_first_name'] 			=  ( method_exists( $order, 'get_billing_first_name' )  && 	is_string( $order->get_billing_first_name() ))	? 	$order->get_billing_first_name()				: 	"";
		$order_data['billing_last_name'] 			=  ( method_exists( $order, 'get_billing_last_name' ) 	&& 	is_string( $order->get_billing_last_name() ))	? 	$order->get_billing_last_name()					: 	"";
		$order_data['billing_company'] 				=  ( method_exists( $order, 'get_billing_company' ) 	&& 	is_string( $order->get_billing_company() ))		? 	$order->get_billing_company()					: 	"";
		$order_data['billing_address_1'] 			=  ( method_exists( $order, 'get_billing_address_1' ) 	&& 	is_string( $order->get_billing_address_1() ))	? 	$order->get_billing_address_1()					: 	"";
		$order_data['billing_address_2'] 			=  ( method_exists( $order, 'get_billing_address_2' ) 	&& 	is_string( $order->get_billing_address_2() ))	? 	$order->get_billing_address_2()					: 	"";
		$order_data['billing_city'] 				=  ( method_exists( $order, 'get_billing_city' ) 		&& 	is_string( $order->get_billing_city() ))		? 	$order->get_billing_city()						: 	"";
		$order_data['billing_state'] 				=  ( method_exists( $order, 'get_billing_state' ) 		&& 	is_string( $order->get_billing_state() )) 		? 	$order->get_billing_state()						: 	"";
		$order_data['billing_postcode'] 			=  ( method_exists( $order, 'get_billing_postcode' ) 	&& 	is_string( $order->get_billing_postcode() ))	? 	$order->get_billing_postcode()					: 	"";
		# 
		if ( wpgsi_fs()->is__premium_only() ) {
			if ( wpgsi_fs()->can_use_premium_code() ){
				$order_data['billing_country'] 				= ( method_exists( $order, 'get_billing_country' ) 	    && 	is_string( $order->get_billing_country() ))		? 	$order->get_billing_country()			: 	"";
				$order_data['billing_email'] 				= ( method_exists( $order, 'get_billing_email' ) 		&& 	is_string( $order->get_billing_email() ))		? 	$order->get_billing_email()				: 	"";
				$order_data['billing_phone'] 				= ( method_exists( $order, 'get_billing_phone' ) 		&& 	is_string( $order->get_billing_phone()))		? 	$order->get_billing_phone()				: 	"";
				$order_data['cart_tax'] 					= ( method_exists( $order, 'get_cart_tax' ) 	  		&& 	is_string( $order->get_cart_tax()  ))		? 	$order->get_cart_tax() 						: 	"";
				$order_data['currency'] 					= ( method_exists( $order, 'get_currency' ) 	  		&& 	is_string( $order->get_currency()  ))		? 	$order->get_currency() 						:	"";
				$order_data['discount_tax'] 				= ( method_exists( $order, 'get_discount_tax' )   		&& 	is_string( $order->get_discount_tax() ))	?	$order->get_discount_tax() 					:	"";
				$order_data['discount_total'] 				= ( method_exists( $order, 'get_discount_total' ) 		&& 	is_string( $order->get_discount_total() ))	? 	$order->get_discount_total()				:	"";
				$order_data['fees'] 						= ( method_exists( $order, 'get_fees' ) 		  		&&  ! empty( $order->get_fees() ) && is_array( $order->get_fees()) ) 			?   json_encode( $order->get_fees()) 	:   "";
				$order_data['shipping_method'] 				= ( method_exists( $order, 'get_shipping_method' )		&& 	is_string( $order->get_shipping_method() ))	? 	$order->get_shipping_method() 				:	"";
				$order_data['shipping_tax'] 				= ( method_exists( $order, 'get_shipping_tax' ) 		&& 	is_string( $order->get_shipping_tax()  ))	? 	$order->get_shipping_tax() 					:	"";
				$order_data['shipping_total'] 				= ( method_exists( $order, 'get_shipping_total' ) 		&& 	is_string( $order->get_shipping_total()  ))	? 	$order->get_shipping_total()				:	"";
				$order_data['subtotal'] 					= ( method_exists( $order, 'get_subtotal' ) 			&& 	is_float( $order->get_subtotal()  ))		? 	$order->get_subtotal()						:	"";
				
				$order_data['subtotal_to_display'] 			= ( method_exists( $order, 'get_subtotal_to_display') 	&& 	is_string( $order->get_subtotal_to_display()))? $order->get_subtotal_to_display() 			: 	"";
				$order_data['tax_totals'] 					= ( method_exists( $order, 'get_tax_totals' ) 			&&  ! empty($order->get_tax_totals()) 	&& is_array( $order->get_tax_totals())) ?  json_encode( $order->get_tax_totals()) 	: ""; 
				$order_data['taxes'] 						= ( method_exists( $order, 'get_taxes' ) 				&&  ! empty($order->get_taxes()) 		&& is_array( $order->get_taxes()) ) 	?  json_encode( $order->get_taxes()) 		: "";  
				$order_data['total'] 						= ( method_exists( $order, 'get_total' ) 				&& 	is_string( $order->get_total() ))			 ?  $order->get_total() 		 				:	"";
				$order_data['total_discount'] 				= ( method_exists( $order, 'get_total_discount' ) 		&& 	is_float( $order->get_total_discount()  ))   ?  $order->get_total_discount() 				:	"";
				$order_data['total_tax'] 					= ( method_exists( $order, 'get_total_tax'  ) 			&& 	is_string( $order->get_total_tax() ))		 ? 	$order->get_total_tax() 	 				:	"";
				$order_data['total_refunded'] 				= ( method_exists( $order, 'get_total_refunded' ) 		&& 	is_float( $order->get_total_refunded() ))	 ? 	$order->get_total_refunded() 				:	"";
				$order_data['total_tax_refunded'] 			= ( method_exists( $order, 'get_total_tax_refunded' ) 	&& 	is_int( $order->get_total_tax_refunded()))	 ?  $order->get_total_tax_refunded()			:	"";
				$order_data['total_shipping_refunded'] 		= ( method_exists( $order, 'get_total_shipping_refunded')&& is_int( $order->get_total_shipping_refunded() )) ?  $order->get_total_shipping_refunded() 	:	"";
				$order_data['item_count_refunded'] 			= ( method_exists( $order, 'get_item_count_refunded' ) 	&& 	is_int( $order->get_item_count_refunded() )) 	 ?  $order->get_item_count_refunded() 		:	"";
				$order_data['total_qty_refunded'] 			= ( method_exists( $order, 'get_total_qty_refunded' ) 	&& 	is_int( $order->get_total_qty_refunded() ))  	 ?  $order->get_total_qty_refunded() 		:	"";
				$order_data['remaining_refund_amount']  	= ( method_exists( $order, 'get_remaining_refund_amount')&& is_string($order->get_remaining_refund_amount()))?  $order->get_remaining_refund_amount()	:	"";
				# Order Item process Starts
				if ( method_exists( $order, 'get_items') AND is_array( $order->get_items()) ){ 
					# Declaring Empty Array Holder 
					$product_ids = array();
					$order_data['items'] 	     					= " ";
					$order_data['get_product_id'] 			 		= " ";	 
					$order_data['get_name'] 				 		= " ";	  
					$order_data['get_quantity'] 			 		= " ";	  
					$order_data['get_total'] 				 		= " ";	 	
					$order_data['get_sku'] 					 		= " ";	 	
					$order_data['get_type'] 			 	 		= " ";	   
					$order_data['get_slug'] 			 	 		= " ";	
 
					$order_data['get_price'] 				 		= " ";	
					$order_data['get_regular_price'] 		 		= " ";
					$order_data['get_sale_price'] 			 		= " ";	 
 
					$order_data['get_virtual'] 				 		= " "; 	
					$order_data['get_permalink'] 			 		= " ";	
					$order_data['get_featured'] 			 		= " ";	
					$order_data['get_status'] 				 		= " ";	 
					$order_data['get_tax_status'] 			 		= " "; 	
					$order_data['get_tax_class'] 			 		= " "; 	
					$order_data['get_manage_stock'] 		 		= " "; 	
					$order_data['get_stock_quantity'] 		 		= " ";  
					$order_data['get_stock_status'] 		 		= " "; 	
					$order_data['get_backorders'] 			 		= " "; 
					$order_data['get_sold_individually']	 		= " "; 	
					$order_data['get_purchase_note'] 		 		= " ";
					$order_data['get_shipping_class_id']	 		= " ";
 
					$order_data['get_weight'] 				 		= " ";
					$order_data['get_length'] 				 		= " ";
					$order_data['get_width'] 				 		= " ";
					$order_data['get_height'] 				 		= " "; 	
 
					$order_data['get_default_attributes'] 	 		= " ";
 
					$order_data['get_category_ids'] 		 		= " ";
					$order_data['get_tag_ids'] 				 		= " ";
					 
					$order_data['get_image_id'] 			 		= " ";
					$order_data['get_gallery_image_ids'] 	 		= " "; 
					$order_data['get_attachment_image_url'] 	 	= " "; 
					# Item Meta Empty Holders 
					# New Code Ends 
					foreach ( $order->get_items() as $item_id => $item_data ) {
						
						$order_data['items'] .= (( method_exists( $item_data, "get_product_id" ) AND 	is_int( $item_data->get_product_id())) 	AND !empty($item_data->get_product_id()))	?  $item_data->get_product_id() 			: "--"; 
						$order_data['items'] .= (( method_exists( $item_data, "get_name" ) 	   	 AND 	is_string( $item_data->get_name() )) 	AND	!empty($item_data->get_name()))			?  " " . $item_data->get_name() 		 	: "--"; 
						$order_data['items'] .= (( method_exists( $item_data, "get_quantity" ) 	 AND 	is_int( $item_data->get_quantity() ))	AND !empty($item_data->get_quantity()))		?  " qty - " . $item_data->get_quantity() 	: "--"; 
						$order_data['items'] .= (( method_exists( $item_data, "get_total" ) 	 AND 	is_string( $item_data->get_total() ))	AND !empty($item_data->get_total()))		?  " total - " .  	$item_data->get_total() : "--"; 
						
						# New Code Starts 
						$product_ids[] 								     =	( (method_exists( $item_data, 'get_product_id')							AND is_int( $item_data->get_product_id() ))						    AND !empty( $item_data->get_product_id()) )						?  $item_data->get_product_id()														:	"--";
						$order_data['get_product_id'] 					.=	( (method_exists( $item_data, 'get_product_id')							AND is_int( $item_data->get_product_id() ))						    AND !empty( $item_data->get_product_id()) )						?  $item_data->get_product_id()														:	"--";
						$order_data['get_name'] 						.=  ( (method_exists( $item_data, 'get_name')								AND is_string( $item_data->get_name() ))						    AND !empty( $item_data->get_name())	)							?  $item_data->get_name()															:	"--";
						$order_data['get_quantity'] 					.=  ( (method_exists( $item_data, 'get_quantity')							AND is_int( $item_data->get_quantity() ))						    AND !empty( $item_data->get_quantity())	)						?  $item_data->get_quantity()														:	"--";
						$order_data['get_total'] 						.= 	( (method_exists( $item_data, 'get_total')								AND is_string( $item_data->get_total() ))						    AND !empty( $item_data->get_total()) )							?  $item_data->get_total()															:	"--";	
						$order_data['get_sku'] 							.= 	( (method_exists( $item_data->get_product(), 'get_sku')					AND is_string( $item_data->get_product()->get_sku() )) 			    AND !empty( $item_data->get_product()->get_sku()) )				?  $item_data->get_product()->get_sku()												:	"--";
						$order_data['get_type'] 						.= 	( (method_exists( $item_data->get_product(), 'get_type')				AND is_string( $item_data->get_product()->get_type() ))			    AND !empty( $item_data->get_product()->get_type()) )			?  $item_data->get_product()->get_type()											:	"--";
						$order_data['get_slug'] 						.= 	( (method_exists( $item_data->get_product(), 'get_slug')				AND is_string( $item_data->get_product()->get_slug() ))			    AND !empty( $item_data->get_product()->get_slug()) )			?  $item_data->get_product()->get_slug()											:	"--";
					
						$order_data['get_price'] 						.= 	( (method_exists( $item_data->get_product(), 'get_price')				AND is_string( $item_data->get_product()->get_price() ))		    AND !empty( $item_data->get_product()->get_price())	)			?  $item_data->get_product()->get_price()											:	"--";
						$order_data['get_regular_price'] 				.= 	( (method_exists( $item_data->get_product(), 'get_regular_price')		AND is_string( $item_data->get_product()->get_regular_price()))     AND !empty( $item_data->get_product()->get_regular_price())	)	?  $item_data->get_product()->get_regular_price()									:	"--";
						$order_data['get_sale_price'] 					.= 	( (method_exists( $item_data->get_product(), 'get_sale_price')			AND is_string( $item_data->get_product()->get_sale_price()  ))	    AND !empty( $item_data->get_product()->get_sale_price()) )		?  $item_data->get_product()->get_sale_price()										:	"--";
						
						$order_data['get_virtual'] 						.= 	( (method_exists( $item_data->get_product(), 'get_virtual')				AND is_bool( $item_data->get_product()->get_virtual()  ))		    AND !empty( $item_data->get_product()->get_virtual()) )			?  $item_data->get_product()->get_virtual()											:	"--";
						$order_data['get_permalink'] 					.=	( (method_exists( $item_data->get_product(), 'get_permalink')			AND is_string( $item_data->get_product()->get_permalink() ))	    AND !empty( $item_data->get_product()->get_permalink()) )		?  $item_data->get_product()->get_permalink()										:	"--";
						$order_data['get_featured'] 					.=	( (method_exists( $item_data->get_product(), 'get_featured')			AND is_bool( $item_data->get_product()->get_featured()  ))		    AND !empty( $item_data->get_product()->get_featured()) )		?  $item_data->get_product()->get_featured()										:	"--";
						$order_data['get_status'] 						.=	( (method_exists( $item_data->get_product(), 'get_status')				AND is_string( $item_data->get_product()->get_status()  ))		    AND !empty( $item_data->get_product()->get_status()) )			?  $item_data->get_product()->get_status()											:	"--";
						$order_data['get_tax_status'] 					.= 	( (method_exists( $item_data->get_product(), 'get_tax_status')			AND is_string( $item_data->get_product()->get_tax_status()  ))	    AND !empty( $item_data->get_product()->get_tax_status()) )		?  $item_data->get_product()->get_tax_status()										:	"--";
						$order_data['get_tax_class'] 					.= 	( (method_exists( $item_data->get_product(), 'get_tax_class')			AND is_string( $item_data->get_product()->get_tax_class()  ))	    AND !empty( $item_data->get_product()->get_tax_class() ) )		?  $item_data->get_product()->get_tax_class()										:	"--";
						$order_data['get_manage_stock'] 				.= 	( (method_exists( $item_data->get_product(), 'get_manage_stock')		AND is_bool( $item_data->get_product()->get_manage_stock()  ))	    AND !empty( $item_data->get_product()->get_manage_stock() )	)	?  $item_data->get_product()->get_manage_stock()									:	"--";
						$order_data['get_stock_quantity'] 				.= 	( (method_exists( $item_data->get_product(), 'get_stock_quantity')		AND is_string( $item_data->get_product()->get_stock_quantity() ))   AND !empty( $item_data->get_product()->get_stock_quantity()) )	?  $item_data->get_product()->get_stock_quantity()									:	"--";
						$order_data['get_stock_status'] 				.= 	( (method_exists( $item_data->get_product(), 'get_stock_status')		AND is_string( $item_data->get_product()->get_stock_status()  ))    AND !empty( $item_data->get_product()->get_stock_status()) )	?  $item_data->get_product()->get_stock_status()									:	"--";
						$order_data['get_backorders'] 					.= 	( (method_exists( $item_data->get_product(), 'get_backorders')			AND is_string( $item_data->get_product()->get_backorders()  ))	    AND !empty($item_data->get_product()->get_backorders()) )		?  $item_data->get_product()->get_backorders()										:	"--";
						$order_data['get_sold_individually']			.= 	( (method_exists( $item_data->get_product(), 'get_sold_individually')	AND is_bool( $item_data->get_product()->get_sold_individually()))   AND !empty($item_data->get_product()->get_sold_individually()) )?  $item_data->get_product()->get_sold_individually()								:	"--";
						$order_data['get_purchase_note'] 				.= 	( (method_exists( $item_data->get_product(), 'get_purchase_note')		AND is_string( $item_data->get_product()->get_purchase_note() ))    AND !empty( $item_data->get_product()->get_purchase_note()) )	?  $item_data->get_product()->get_purchase_note()									:	"--";
						$order_data['get_shipping_class_id']			.= 	( (method_exists( $item_data->get_product(), 'get_shipping_class_id')	AND is_int( $item_data->get_product()->get_shipping_class_id() ))   AND !empty($item_data->get_product()->get_shipping_class_id() ))?  $item_data->get_product()->get_shipping_class_id()								:	"--";
						
						$order_data['get_weight'] 						.= 	( (method_exists( $item_data->get_product(), 'get_weight')				AND is_string( $item_data->get_product()->get_weight() ))		    AND !empty($item_data->get_product()->get_weight())	)			?  $item_data->get_product()->get_weight()											:	"--";
						$order_data['get_length'] 						.= 	( (method_exists( $item_data->get_product(), 'get_length')				AND is_string( $item_data->get_product()->get_length() ))		    AND !empty($item_data->get_product()->get_length())	)			?  $item_data->get_product()->get_length()											:	"--";
						$order_data['get_width'] 						.= 	( (method_exists( $item_data->get_product(), 'get_width')				AND is_string( $item_data->get_product()->get_width()  ))		    AND !empty( $item_data->get_product()->get_width())	)			?  $item_data->get_product()->get_width()											:	"--";
						$order_data['get_height'] 						.= 	( (method_exists( $item_data->get_product(), 'get_height')				AND is_string( $item_data->get_product()->get_height() ))		    AND !empty( $item_data->get_product()->get_height()))			?  $item_data->get_product()->get_height()											:	"--";
						
						$order_data['get_default_attributes'] 			.= 	( (method_exists( $item_data->get_product(), 'get_default_attributes')	AND is_array( $item_data->get_product()->get_default_attributes())) AND !empty($item_data->get_product()->get_default_attributes()))? json_encode($item_data->get_product()->get_default_attributes()) 					:	"--";
						
						$order_data['get_image_id'] 					.= 	( (method_exists( $item_data->get_product(), 'get_image_id')			AND is_string( $item_data->get_product()->get_image_id()  ))		AND !empty($item_data->get_product()->get_image_id()))			?  $item_data->get_product()->get_image_id()										:	"--";
						$order_data['get_gallery_image_ids'] 			.= 	( (method_exists( $item_data->get_product(), 'get_gallery_image_ids')	AND is_array( $item_data->get_product()->get_gallery_image_ids() ))	AND !empty($item_data->get_product()->get_gallery_image_ids()))	?  json_encode( $item_data->get_product()->get_gallery_image_ids())					:	"--";
						$order_data['get_attachment_image_url'] 		.= 	( (method_exists( $item_data->get_product(), 'get_image_id')			AND function_exists('wp_get_attachment_image_url') )				AND !empty($item_data->get_product()->get_image_id()))			?  wp_get_attachment_image_url( $item_data->get_product()->get_image_id())			:	"--";
						# get all the Products( many Products ) Same Meta Key Value # reduce The 
						# New Code 
						# Creating New Line 
						$order_data['items'] 							.=  " \n ";
						$order_data['get_product_id'] 			 		.=	" \n "; 
						$order_data['get_name'] 				 		.=	" \n ";  
						$order_data['get_quantity'] 			 		.=	" \n ";  
						$order_data['get_total'] 				 		.=	" \n "; 	
						$order_data['get_sku'] 					 		.=	" \n "; 	
						$order_data['get_type'] 			 	 		.=	" \n ";   
						$order_data['get_slug'] 			 	 		.=	" \n ";
  
						$order_data['get_price'] 				 		.=	" \n "; 	
						$order_data['get_regular_price'] 		 		.=	" \n "; 
						$order_data['get_sale_price'] 			 		.=	" \n "; 	 
  
						$order_data['get_virtual'] 				 		.=	" \n "; 	
						$order_data['get_permalink'] 			 		.=	" \n ";	
						$order_data['get_featured'] 			 		.=	" \n ";	
						$order_data['get_status'] 				 		.=	" \n ";	 
						$order_data['get_tax_status'] 			 		.=	" \n "; 	
						$order_data['get_tax_class'] 			 		.=	" \n "; 	
						$order_data['get_manage_stock'] 		 		.=	" \n "; 	
						$order_data['get_stock_quantity'] 		 		.=	" \n ";  
						$order_data['get_stock_status'] 		 		.=	" \n "; 	
						$order_data['get_backorders'] 			 		.=	" \n "; 
						$order_data['get_sold_individually']	 		.=	" \n "; 	
						$order_data['get_purchase_note'] 		 		.=	" \n ";
						$order_data['get_shipping_class_id']	 		.=	" \n ";
  
						$order_data['get_weight'] 					 	.=	" \n ";
						$order_data['get_length'] 				 		.=	" \n ";
						$order_data['get_width'] 				 		.=	" \n ";
						$order_data['get_height'] 				 		.=	" \n "; 	

						$order_data['get_default_attributes'] 	 		.=	" \n ";

						$order_data['get_category_ids'] 		 		.=	" \n ";
						$order_data['get_tag_ids'] 				 		.=	" \n ";

						$order_data['get_image_id'] 			 		.=	" \n ";
						$order_data['get_gallery_image_ids'] 	 		.=	" \n ";
						$order_data['get_attachment_image_url'] 	 	.=	" \n ";
						# New Code Ends 
					}
				}

				# Inserting Order items Meta value
				if( ! empty( $product_ids ) ) {
					#item meta key value Holder 
					$itemMetaKeyValue = array();
					# wpdb
					global $wpdb;
					# DB query 
					$query 	 = "SELECT * FROM $wpdb->postmeta WHERE post_id IN ('". join("','",$product_ids) ."') ORDER BY FIELD(post_id, ".join(",",$product_ids).") ";
					# Running the Query
					$productMeta = $wpdb->get_results( $query, ARRAY_A );
					if( ! empty( $productMeta ) ){
						foreach( $productMeta as $key => $valueArray) {
							if( isset( $valueArray['meta_key']  ) AND  isset( $valueArray['meta_value'] ) ) {
								# error handling 
								if( isset( $itemMetaKeyValue[ $valueArray['meta_key'] ]  ) ){
									$itemMetaKeyValue[ $valueArray['meta_key'] ] .= ( !empty($valueArray['meta_value']) )? $valueArray['meta_value'] . " \n " : "-- \n ";
								} else {
									$itemMetaKeyValue[ $valueArray['meta_key'] ]  ="";
									$itemMetaKeyValue[ $valueArray['meta_key'] ] .= ( !empty($valueArray['meta_value']) )? $valueArray['meta_value'] . " \n " : "-- \n ";
								}
							}
						}
					}
				}
				# Joining the Product meta to Order data 
				$order_data = array_merge( $order_data, $itemMetaKeyValue );	
				# Order Item process Ends
				$order_data['item_count'] 			    	=  ( method_exists( $order, 'get_item_count') 			&& 	is_int($order->get_item_count() )) 			? 	$order->get_item_count() : "";
				$order_data['downloadable_items'] 			=  ( method_exists( $order, 'get_downloadable_items' ) 	&& ! empty($order->get_downloadable_items())&&  is_array(  $order->get_downloadable_items()) ) 	? json_encode( $order->get_downloadable_items()) : "";   
				#
				$order_data['date_created'] 				=  ( method_exists( $order, 'get_date_created' ) 	&& ! empty($order->get_date_created()) 	&&	is_string( $order->get_date_created()->date("F j, Y, g:i:s A T") ) ) 	? 	$order->get_date_created()->date("F j, Y, g:i:s A T") 	: ""; 
				$order_data['date_modified'] 				=  ( method_exists( $order, 'get_date_modified' ) 	&& ! empty($order->get_date_modified()) &&	is_string( $order->get_date_modified()->date("F j, Y, g:i:s A T")) ) 	? 	$order->get_date_modified()->date("F j, Y, g:i:s A T") 	: ""; 
				$order_data['date_completed'] 				=  ( method_exists( $order, 'get_date_completed' ) 	&& ! empty($order->get_date_completed())&&	is_string( $order->get_date_completed()->date("F j, Y, g:i:s A T"))) 	? 	$order->get_date_completed()->date("F j, Y, g:i:s A T") : "";
				$order_data['date_paid'] 					=  ( method_exists( $order, 'get_date_paid' ) 		&& ! empty($order->get_date_paid()) 	&&	is_string( $order->get_date_paid()->date("F j, Y, g:i:s A T")) ) 	 	? 	$order->get_date_paid()->date("F j, Y, g:i:s A T") 		: "";
				
				# New Code Starts  
				# +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$order_data["date_created_year"]			=	( method_exists( $order, 'get_date_created' ) 	&& ! empty($order->get_date_created()) 	&&	is_string( $order->get_date_created()->date("F j, Y, g:i:s A T")) )  	? 	$order->get_date_created()->date("Y") 	  	: "";
				$order_data["date_created_month"]			=	( method_exists( $order, 'get_date_created' ) 	&& ! empty($order->get_date_created()) 	&&	is_string( $order->get_date_created()->date("F j, Y, g:i:s A T")) )  	? 	$order->get_date_created()->date("m") 	  	: "";
				$order_data["date_created_date"]			=	( method_exists( $order, 'get_date_created' ) 	&& ! empty($order->get_date_created()) 	&&	is_string( $order->get_date_created()->date("F j, Y, g:i:s A T")) )  	? 	$order->get_date_created()->date("d") 	  	: "";
				$order_data["date_created_time"]			=	( method_exists( $order, 'get_date_created' ) 	&& ! empty($order->get_date_created()) 	&&	is_string( $order->get_date_created()->date("F j, Y, g:i:s A T")) )  	? 	$order->get_date_created()->date("H:i")   	: "";
				
				$order_data["date_modified_year"]			=	( method_exists( $order, 'get_date_modified' ) 	&& ! empty($order->get_date_modified()) &&	is_string( $order->get_date_modified()->date("F j, Y, g:i:s A T")) )  	? 	$order->get_date_modified()->date("Y") 	  	: "";
				$order_data["date_modified_month"]			=	( method_exists( $order, 'get_date_modified' ) 	&& ! empty($order->get_date_modified()) &&	is_string( $order->get_date_modified()->date("F j, Y, g:i:s A T")) )  	? 	$order->get_date_modified()->date("m") 	  	: "";
				$order_data["date_modified_date"]			=	( method_exists( $order, 'get_date_modified' ) 	&& ! empty($order->get_date_modified()) &&	is_string( $order->get_date_modified()->date("F j, Y, g:i:s A T")) )  	? 	$order->get_date_modified()->date("d") 	  	: "";
				$order_data["date_modified_time"]			=	( method_exists( $order, 'get_date_modified' ) 	&& ! empty($order->get_date_modified()) &&	is_string( $order->get_date_modified()->date("F j, Y, g:i:s A T")) )  	? 	$order->get_date_modified()->date("H:i")  	: "";
				
				$order_data["date_completed_year"]			=	( method_exists( $order, 'get_date_completed' ) && ! empty($order->get_date_completed()) &&	is_string( $order->get_date_completed()->date("F j, Y, g:i:s A T")) )  	? 	$order->get_date_completed()->date("Y")   	: "";
				$order_data["date_completed_month"]			=	( method_exists( $order, 'get_date_completed' ) && ! empty($order->get_date_completed()) &&	is_string( $order->get_date_completed()->date("F j, Y, g:i:s A T")) )  	? 	$order->get_date_completed()->date("m")   	: "";
				$order_data["date_completed_date"]			=	( method_exists( $order, 'get_date_completed' ) && ! empty($order->get_date_completed()) &&	is_string( $order->get_date_completed()->date("F j, Y, g:i:s A T")) )  	? 	$order->get_date_completed()->date("d")   	: "";
				$order_data["date_completed_time"]			=	( method_exists( $order, 'get_date_completed' ) && ! empty($order->get_date_completed()) &&	is_string( $order->get_date_completed()->date("F j, Y, g:i:s A T")) )  	? 	$order->get_date_completed()->date("H:i") 	: "";
		
				$order_data["date_paid_year"]				=	( method_exists( $order, 'get_date_paid' ) 		&& ! empty($order->get_date_paid()) 	 &&	is_string( $order->get_date_paid()->date("F j, Y, g:i:s A T")) )  	  	? 	$order->get_date_paid()->date("Y")	  		: "";
				$order_data["date_paid_month"]				=	( method_exists( $order, 'get_date_paid' ) 		&& ! empty($order->get_date_paid()) 	 &&	is_string( $order->get_date_paid()->date("F j, Y, g:i:s A T")) )  		? 	$order->get_date_paid()->date("m")	  		: "";
				$order_data["date_paid_date"]				=	( method_exists( $order, 'get_date_paid' ) 		&& ! empty($order->get_date_paid()) 	 &&	is_string( $order->get_date_paid()->date("F j, Y, g:i:s A T")) )  		? 	$order->get_date_paid()->date("d")	  		: "";
				$order_data["date_paid_time"]				=	( method_exists( $order, 'get_date_paid' ) 		&& ! empty($order->get_date_paid()) 	 &&	is_string( $order->get_date_paid()->date("F j, Y, g:i:s A T")) )  		? 	$order->get_date_paid()->date("H:i")	  	: "";
				# +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				# New Code Starts  
				$order_data['user'] 						=  ( method_exists( $order, 'get_user')  				&&  ! empty($order->get_user()) && is_object( $order->get_user()) ) ? 	$order->get_user()->user_login  . " - " . $order->get_user()->user_email 	: "";
				$order_data['customer_id'] 					=  ( method_exists( $order, 'get_customer_id' ) 		&& 	is_int( $order->get_customer_id() )) 			? 	$order->get_customer_id() 			: "";
				$order_data['user_id'] 						=  ( method_exists( $order, 'get_user_id' ) 			&& 	is_int( $order->get_user_id() )) 				? 	$order->get_user_id()				: "";
				$order_data['customer_ip_address'] 			=  ( method_exists( $order, 'get_customer_ip_address')  && 	is_string( $order->get_customer_ip_address())) 	? 	$order->get_customer_ip_address()	: "";
				$order_data['customer_user_agent'] 			=  ( method_exists( $order, 'get_customer_user_agent')  && 	is_string( $order->get_customer_user_agent()))	? 	$order->get_customer_user_agent()	: "";
				$order_data['created_via'] 					=  ( method_exists( $order, 'get_created_via' ) 		&& 	is_string( $order->get_created_via() ))			? 	$order->get_created_via()			: "";
				$order_data['customer_note'] 				=  ( method_exists( $order, 'get_customer_note' ) 		&& 	is_string( $order->get_customer_note() ))		? 	$order->get_customer_note()			: "";
			}
		}
		# site Current Time
		$order_data['site_time'] 					= ( isset( $this->Time ) ) ? 	$this->Time		:	'';
		$order_data['site_date'] 					= ( isset( $this->Date ) ) ? 	$this->Date		:	'';
		# Start
		$order_data['shipping_first_name'] 			=  ( method_exists( $order, 'get_shipping_first_name' ) && 	is_string( $order->get_shipping_first_name())) 	? 	$order->get_shipping_first_name()	: "";
		$order_data['shipping_last_name'] 			=  ( method_exists( $order, 'get_shipping_last_name' )  && 	is_string( $order->get_shipping_last_name() ))	? 	$order->get_shipping_last_name()	: "";
		$order_data['shipping_company'] 			=  ( method_exists( $order, 'get_shipping_company' ) 	&& 	is_string( $order->get_shipping_company() ))	?	$order->get_shipping_company()		: "";
		$order_data['shipping_address_1'] 			=  ( method_exists( $order, 'get_shipping_address_1' )  && 	is_string( $order->get_shipping_address_1() ))	? 	$order->get_shipping_address_1()	: "";
		$order_data['shipping_address_2'] 			=  ( method_exists( $order, 'get_shipping_address_2' )  && 	is_string( $order->get_shipping_address_2() ))	? 	$order->get_shipping_address_2()	: "";
		$order_data['shipping_city'] 				=  ( method_exists( $order, 'get_shipping_city' ) 		&& 	is_string( $order->get_shipping_city() ))		? 	$order->get_shipping_city()			: "";
		$order_data['shipping_state'] 				=  ( method_exists( $order, 'get_shipping_state' ) 	 	&& 	is_string( $order->get_shipping_state() )) 		? 	$order->get_shipping_state()		: "";
		$order_data['shipping_postcode'] 			=  ( method_exists( $order, 'get_shipping_postcode' ) 	&& 	is_string( $order->get_shipping_postcode() ))	? 	$order->get_shipping_postcode()		: "";
		# Start
		if ( wpgsi_fs()->is__premium_only() ) {
			if ( wpgsi_fs()->can_use_premium_code() ){
				$order_data['shipping_country'] 			=  ( method_exists( $order, 'get_shipping_country' ) 	&& 	is_string( $order->get_shipping_country() )) 	? 	$order->get_shipping_country()		: "";
				$order_data['address'] 						=  ( method_exists( $order,	'get_address' ) 	 		&& 	is_array(  $order->get_address()) ) 			? 	json_encode( $order->get_address()) : "";
				$order_data['shipping_address_map_url'] 	=  ( method_exists( $order, 'get_shipping_address_map_url' ) 	 &&	is_string( $order->get_shipping_address_map_url()))		?	$order->get_shipping_address_map_url()		: "";
				$order_data['formatted_billing_full_name'] 	=  ( method_exists( $order, 'get_formatted_billing_full_name' )  && is_string( $order->get_formatted_billing_full_name() ))	?	$order->get_formatted_billing_full_name()	: "";
				$order_data['formatted_shipping_full_name']	=  ( method_exists( $order, 'get_formatted_shipping_full_name' ) && is_string( $order->get_formatted_shipping_full_name() ))?	$order->get_formatted_shipping_full_name()	: "";
				$order_data['formatted_billing_address'] 	=  ( method_exists( $order, 'get_formatted_billing_address' ) 	 && is_string( $order->get_formatted_billing_address() ))	?	$order->get_formatted_billing_address()		: "";
				$order_data['formatted_shipping_address'] 	=  ( method_exists( $order, 'get_formatted_shipping_address' )   && is_string( $order->get_formatted_shipping_address() ))	?	$order->get_formatted_shipping_address()	: "";
				#
				$order_data['payment_method'] 				=  ( method_exists( $order, 'get_payment_method' ) 				&& 	is_string( $order->get_payment_method() ))				?	$order->get_payment_method()				: "";
				$order_data['payment_method_title'] 		=  ( method_exists( $order, 'get_payment_method_title' ) 		&& 	is_string( $order->get_payment_method_title() ))		? 	$order->get_payment_method_title()			: "";
				$order_data['transaction_id'] 				=  ( method_exists( $order, 'get_transaction_id' ) 				&& 	is_string( $order->get_transaction_id() ))				? 	$order->get_transaction_id()				: "";
				#
				$order_data['checkout_payment_url'] 		=  ( method_exists( $order, 'get_checkout_payment_url' ) 		&&	is_string( $order->get_checkout_payment_url() ))		? 	$order->get_checkout_payment_url()			: "";
				$order_data['checkout_order_received_url'] 	=  ( method_exists( $order, 'get_checkout_order_received_url') 	&& 	is_string( $order->get_checkout_order_received_url() )) ? 	$order->get_checkout_order_received_url()	: "";
				$order_data['cancel_order_url'] 			=  ( method_exists( $order, 'get_cancel_order_url' ) 			&& 	is_string( $order->get_cancel_order_url() ))			? 	$order->get_cancel_order_url()				: "";
				$order_data['cancel_order_url_raw'] 		=  ( method_exists( $order, 'get_cancel_order_url_raw' ) 		&& 	is_string( $order->get_cancel_order_url_raw()))			? 	$order->get_cancel_order_url_raw()			: "";
				$order_data['cancel_endpoint'] 				=  ( method_exists( $order, 'get_cancel_endpoint' ) 			&& 	is_string( $order->get_cancel_endpoint() ))				? 	$order->get_cancel_endpoint()				: "";
				$order_data['view_order_url'] 				=  ( method_exists( $order, 'get_view_order_url' ) 				&& 	is_string( $order->get_view_order_url() ))				? 	$order->get_view_order_url()				: "";
				$order_data['edit_order_url'] 				=  ( method_exists( $order, 'get_edit_order_url' ) 				&& 	is_string( $order->get_edit_order_url() )) 				? 	$order->get_edit_order_url()				: "";
			}
		}
		#
		$order_data['status'] 				=  "wc-".$order->get_status();  //'wc-new_order'
		$order_data['eventName'] 			=  "New order";
		
		if ( wpgsi_fs()->is__premium_only() ) {
			if ( wpgsi_fs()->can_use_premium_code() ){
				# Order Meta Data Starts
				# Empty Holder array 
				$metaOutPut = array();
				# Global Db object 
				global $wpdb;
				# execute Query
				$orderMetaKeyValue = $wpdb->get_results( "SELECT * FROM $wpdb->postmeta WHERE post_id = " . $order->get_id() , ARRAY_A );
				# get Distinct Keys;
				$metaKeys = $this->wpgsi_wooCommerce_order_metaKeys();
				# Check and Balance for all the Meta keys
				if ( $metaKeys[0] &&  ! empty( $orderMetaKeyValue ) ){
					# populating Output array in revers with  empty value
					foreach ( $metaKeys[1]  as $key => $value ){
						$metaOutPut[ $value ] = "--";
					}

					# Looping the Meta key & value of Certain Comment And Populating the $metaOutPut Key array with Value 
					foreach ( $orderMetaKeyValue  as $oneArray ) {
						if ( is_array( $oneArray ) && isset( $oneArray['meta_key'], $metaOutPut[ $oneArray[ 'meta_key' ] ], $oneArray[ 'meta_value' ] ) ){
							# Convert text to  an array then JSON for reducing the String 
							$isArrayTest = @unserialize( $oneArray[ 'meta_value' ] );
							if ( $isArrayTest == null ) {
								$metaOutPut[ $oneArray['meta_key'] ] = $oneArray[ 'meta_value' ];
							} else {
								$metaOutPut[ $oneArray['meta_key'] ] =  $isArrayTest;
							}
						}
					}
				}
				# Append New metaOutPut array to $commentData data array;
				$order_data = array_merge( $order_data, $metaOutPut );
				# Order Meta Data Ends
			}
		}

		# Action
		if ( empty( $order_id ) ){
			$this->wpgsi_log( get_class($this), __METHOD__,"716", "Error: Order is empty !" );
		} else {
			$r = $this->wpgsi_eventBoss('Woocommerce', 'wc-new_order', $order_data, $order_id );
		}
	}

	/**
	 * CF7 Form Submission Event || its a HOOK  callback function of Contact form 7 form
	 * Contact form 7 is a Disgusting Code || Noting is good of this Plugin || 
	 * @since    3.1.0
	 * @param    array     $form_data     data_array
	*/
	public function wpgsi_cf7_submission( $contact_form ) {
		$id 		 = $contact_form->id();
		$submission  = WPCF7_Submission::get_instance();
		$posted_data = $submission->get_posted_data();

		# if There is a integration on this Form Submission
		if ( ! empty( $id ) AND  $this->wpgsi_integrations(  'cf7_' . $id  )[0] ) {
			# extra fields values
			if ( wpgsi_fs()->is__premium_only() ) {
				if ( wpgsi_fs()->can_use_premium_code() ) {
					# Site date and time 
					$posted_data['wpgsi_submitted_date'] = ( isset( $this->Date ) ) ? 	$this->Date		:	'';
					$posted_data['wpgsi_submitted_time'] = ( isset( $this->Time ) ) ? 	$this->Time		:	'';
				}
			}

			if ( isset( $id  ) && !empty( $id  ) ) {
				# Calling Event Boss 
				$r = $this->wpgsi_eventBoss( 'cf7', 'cf7_' . $id , $posted_data, $id  );
			} else {
				$this->wpgsi_log( get_class($this), __METHOD__,"717", "Error: Contact form 7 Form Submitted But No Form ID !" );
			}
		}
	}

	/**
	 * ninja after saved entry to DB || its a HOOK  callback function of ninja form
	 * @since    1.0.0
	 * @param    array     $form_data     data_array
	*/
	public function wpgsi_ninja_forms_after_submission( $form_data ) {
		# if There is a integration on this Form Submission
		if ( isset( $form_data["form_id"] ) AND  $this->wpgsi_integrations( 'ninja_' . $form_data["form_id"] ) ){
			# Empty array holder 
			$data = array();
			# Looping the Fields
			foreach ( $form_data["fields"] as $field ) {
				$data[ $field["key"] ] = $field["value"];
			}
			# extra fields value
			if ( wpgsi_fs()->is__premium_only() ) {
				if ( wpgsi_fs()->can_use_premium_code() ) {
					# Site date and time 
					$data['wpgsi_submitted_date'] = ( isset( $this->Date ) ) ? 	$this->Date		:	'';
					$data['wpgsi_submitted_time'] = ( isset( $this->Time ) ) ? 	$this->Time		:	'';

					# Check And Balance 
					if ( ! empty( $form_data )  AND  isset(  $form_data["form_id"] ) ) {
						# Action
						$r = $this->wpgsi_eventBoss( 'ninja', 'ninja_' . $form_data["form_id"], $data, $form_data["form_id"] );
					} else {
						$this->wpgsi_log( get_class($this), __METHOD__,"718", "Error: ninja Form entries are empty Or form_id is empty!" );
					}
				}
			}
		}
	}
	
	/**
	 * formidable after saved entry to DB || its a HOOK  callback function of formidable form
	 * @since    1.0.0
	 * @param    array    $entry_id    Which platform call this function 
	 * @param    array    $form_id     event_name 
	*/
	public function wpgsi_formidable_after_save( $entry_id, $form_id ) {
		# if There is a integration on this Form Submission
		if ( !empty( $form_id ) AND  $this->wpgsi_integrations( 'frm_' . $form_id )[0]  ) {
			# Check to see database table exist or not 
			if ( $this->wpgsi_dbTableExists("frm_item_metas") ) {
				# Code 
				$dataArray = array();
				global $wpdb;
				$entrees = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}frm_item_metas WHERE item_id = ". $entry_id ." ORDER BY field_id");
				foreach ( $entrees as $entre ) {
					$dataArray[$entre->field_id] = $entre->meta_value;
				}
				# extra fields value
				if ( wpgsi_fs()->is__premium_only() ) {
					if ( wpgsi_fs()->can_use_premium_code() ) {
						# Site date and time 
						$dataArray['wpgsi_submitted_date'] = ( isset( $this->Date ) ) ? 	$this->Date		:	'';
						$dataArray['wpgsi_submitted_time'] = ( isset( $this->Time ) ) ? 	$this->Time		:	'';

						# Check And Balance 
						if ( ! empty( $entry_id ) ){
							# Action
							$r = $this->wpgsi_eventBoss( 'formidable', 'frm_'.$form_id, $dataArray, $form_id );
						} else {
							$this->wpgsi_log( get_class($this), __METHOD__,"720", "Error: formidable Form entries ID is empty!" );
						}
					}
				}
			} else {
				$this->wpgsi_log( get_class( $this ), __METHOD__,"721", "Error: formidable frm_item_metas table is Not Exist!" );
			}
		}
	}

	/**
	 * wpforms Submit Action Handler || its a HOOK  callback function of WP form
	 * @since      1.0.0
	 * @param      array    $fields    		Which platform call this function 
	 * @param      array    $entry     		event_name 
	 * @param      array    $form_data     	data_array
	*/
	public function wpgsi_wpforms_process( $fields, $entry, $form_data ) {
		# if There is a integration on this Form Submission
		if ( isset( $form_data["id"]  ) AND  $this->wpgsi_integrations( 'wpforms_' . $form_data["id"] )[0]  ) {
			# extra fields value
			if ( wpgsi_fs()->is__premium_only() ) {
				if ( wpgsi_fs()->can_use_premium_code() ) {
					# Site date and time 
					$entry["fields"]['wpgsi_submitted_date'] = ( isset( $this->Date ) ) ? 	$this->Date		:	'EMPTY';
					$entry["fields"]['wpgsi_submitted_time'] = ( isset( $this->Time ) ) ? 	$this->Time		:	'EMPTY';

					# Check And Balance 
					if (  ! empty( $entry ) AND  ! empty( $form_data["id"] ) ) {
						# Action 
						$r = $this->wpgsi_eventBoss('wpforms', 'wpforms_' . $form_data["id"], $entry["fields"], $form_data["id"]);
					} else {
						$this->wpgsi_log( get_class($this), __METHOD__,"723", "Error: wpforms Form entries are empty Or form_id is empty!" );
					}
				}
			}
		}
	}

	/**
	 * weforms forms_after_submission 
	 * @param    string   $entry_id   		entry_id;
	 * @param    string   $form_id   		form_id;
	 * @param    string   $page_id     		page_id;
	 * @param    array    $form_settings    form_data;
	 * @since    2.0.0
	*/
	public function wpgsi_weforms_entry_submission( $entry_id, $form_id, $page_id, $form_settings  ) {
		# if There is a integration on this Form Submission
		if ( ! empty( $form_id  ) AND  $this->wpgsi_integrations( 'we_' . $form_id )  ) {
			# Check if frm_item_metas table exists or not 
			if ( $this->wpgsi_dbTableExists("frm_item_metas") ) {
				# code
				$dataArray = array();
				global $wpdb;
				$entrees = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}weforms_entrymeta WHERE weforms_entry_id = ". $entry_id ." ORDER BY meta_id DESC");
				
				foreach ( $entrees as $entre ) {
					$dataArray[ $entre->meta_key ] = $entre->meta_value;
				}

				# extra fields value
				if ( wpgsi_fs()->is__premium_only() ) {
					if ( wpgsi_fs()->can_use_premium_code() ) {
						# Site date and time 
						$dataArray['wpgsi_submitted_date'] = ( isset( $this->Date ) ) ? 	$this->Date		:	'';
						$dataArray['wpgsi_submitted_time'] = ( isset( $this->Time ) ) ? 	$this->Time		:	'';

						# Check And Balance 
						if (  !empty( $entry_id ) AND  !empty( $form_id ) ){
							# Action
							$r = $this->wpgsi_eventBoss('weforms', 'we_' . $form_id, $dataArray, $form_id );
						} else {
							$this->wpgsi_log( get_class($this), __METHOD__,"725", "Error: weforms Form entries are empty Or form_id is empty!" );
						}
					}
				}
			} else {
				$this->wpgsi_log( get_class( $this ), __METHOD__,"726", "Error: weform frm_item_metas table is Not Exist!" );
			}
		}
	}

	/**
	 * gravityForms gform_after_submission 
	 * @param    array   $entry     All the Entries with Some Extra;
	 * @param    array   $formObj   Submitted form Object ;
	 * @since    3.3.0
	*/
	public function wpgsi_gravityForms_after_submission( $entry, $formObj  ) {
		# if There is a integration on this Form Submission
		if ( isset( $entry['form_id']  ) AND  $this->wpgsi_integrations( 'gravity_' . $entry['form_id'] )[0]  ) {
			# extra fields value
			if ( wpgsi_fs()->is__premium_only() ) {
				if ( wpgsi_fs()->can_use_premium_code() ) {
					

					# Calling the Event Boss
					if ( ! empty( $entry ) AND  isset( $entry['form_id'] ) ) {
						# Site date and time 
						$entry['wpgsi_submitted_date'] = ( isset( $this->Date ) ) ? 	$this->Date		:	'';
						$entry['wpgsi_submitted_time'] = ( isset( $this->Time ) ) ? 	$this->Time		:	'';
						# Action
						$r = $this->wpgsi_eventBoss('gravityForm', 'gravity_' . $entry['form_id'], $entry, $entry['form_id'] );
					} else {
						$this->wpgsi_log( get_class($this), __METHOD__,"728", "Error: gravity Form entries are empty Or form_id is empty!" );
					}
				}
			}
		}
	}

	/**
	 * forminator forminator_custom_form_submit_field_data 
	 * @param    array   $field_data_array   Data array;
	 * @param    array   $form_id    form ID ;
	 * @since    3.6.0
	*/
	public function wpgsi_forminator_custom_form_submit_field_data( $field_data_array, $form_id ) {
		# if There is a integration on this Form Submission
		if ( isset( $form_id  ) AND  $this->wpgsi_integrations( 'forminator_' . $form_id )[0]  ) {
			if ( wpgsi_fs()->is__premium_only() ) {
				if ( wpgsi_fs()->can_use_premium_code() ) {
					# Empty Holder
					$dataArray = array();
					# Looping
					foreach(  $field_data_array as $fieldValue ){
						if( isset( $fieldValue['name'], $fieldValue['value']  )){
							$dataArray[ $fieldValue['name'] ] = $fieldValue['value'];
						}
					}

					# Calling the Event Boss
					if ( ! empty( $dataArray ) AND ! empty( $form_id )  ) {
						# Site date and time 
						$dataArray['wpgsi_submitted_time'] = ( isset( $this->Time ) ) ? $this->Time		:	'';
						$dataArray['wpgsi_submitted_date'] = ( isset( $this->Date ) ) ? $this->Date		:	'';
						# Action
						$r = $this->wpgsi_eventBoss('forminator', 'forminator_' . $form_id, $dataArray, $form_id );
					} else {
						$this->wpgsi_log( get_class($this), __METHOD__,"728", "Error: forminator Form entries are empty Or form_id is empty!" );
					}
				}
			}
		}
	}

	/**
	 * Third party plugin :
	 * Checkout Field Editor ( Checkout Manager ) for WooCommerce
	 * BETA testing;
	 * @since    2.0.0
	*/
	public function wpgsi_woo_checkout_field_editor_pro_fields( ) {
		# getting The Active Plugin list
		$active_plugins 				= get_option( 'active_plugins');
		# Empty Holder 
		$woo_checkout_field_editor_pro 	=  array();

		if ( in_array('woo-checkout-field-editor-pro/checkout-form-designer.php' , $active_plugins ) ) {
			# Getting data from wp options
			$a  = get_option( "wc_fields_billing" );
			$b  = get_option( "wc_fields_shipping" );
			$c  = get_option( "wc_fields_additional" );

			if ( $a ){
				foreach ( $a as $key => $field ) {
					if ( isset( $field['custom'] ) &&  $field['custom'] == 1  ){
						$woo_checkout_field_editor_pro[ $key ]['type']  = $field['type'];
						$woo_checkout_field_editor_pro[ $key ]['name']  = $field['name'];
						$woo_checkout_field_editor_pro[ $key ]['label'] = $field['label'];
					}
				}
			}

			if ( $b ){
				foreach ( $b as $key => $field ) {
					if ( isset( $field['custom'] ) &&  $field['custom'] == 1  ){
						$woo_checkout_field_editor_pro[ $key ]['type']  = $field['type'];
						$woo_checkout_field_editor_pro[ $key ]['name']  = $field['name'];
						$woo_checkout_field_editor_pro[ $key ]['label'] = $field['label'];
					}
				}
			}

			if ( $c ){
				foreach ( $c as $key => $field ) {
					if ( isset( $field['custom'] ) &&  $field['custom'] == 1  ){
						$woo_checkout_field_editor_pro[ $key ]['type']  = $field['type'];
						$woo_checkout_field_editor_pro[ $key ]['name']  = $field['name'];
						$woo_checkout_field_editor_pro[ $key ]['label'] = $field['label'];
					}
				}
			}

			if ( empty(  $woo_checkout_field_editor_pro ) ) {
				return array( FALSE, "Error: Checkout Field Editor aka Checkout Manager for WooCommerce is EMPTY no Custom Field. " );
			} else {
				return array( TRUE, $woo_checkout_field_editor_pro );
			}	

		} elseif ( in_array('woocommerce-checkout-field-editor-pro/woocommerce-checkout-field-editor-pro.php' , $active_plugins )) {
			# this part is for professional Version of that Plugin;
			# if Check to see class is exists or not 
			if ( class_exists('For_WCFE_Checkout_Fields_Utils') AND class_exists('WCFE_Checkout_Fields_Utils')  ) {
				# it declared in the Below of this Class 
				For_WCFE_Checkout_Fields_Utils::fields();
			}
			
		} else {
			return array( FALSE, "Error: Checkout Field Editor aka Checkout Manager for WooCommerce is not installed " );
		}
	}
	
	/**
	 * Centralized Events , All events Will call this Function & feed Data to this Func || It Will Do All event Job 
	 * Creating new token if token is not valid . as you know frontend user comes and goes without Notice !
	 * @since      1.0.0
	 * @param      string    $data_source    Which platform call this function 
	 * @param      string    $event_name     event_name 
	 * @param      array     $data_array     data_array
	 * @param      int    	 $id    		 ID is optional so that , 
	*/
	public function wpgsi_eventBoss( $data_source = '', $event_name = '', $data_array = '', $id = '' ) {
		# Got the Event Data [Custom Action Hook]	||  raw data hook 
		do_action('wpgsi_event_raw', $data_source, $event_name, $data_array, $id ); 

		# data_source Empty test;
		if ( empty( $data_source ) ){
			$this->wpgsi_log( get_class($this), __METHOD__, "729", "Error: data_source  is Empty!. ". json_encode( array("data_source" => $data_source, "event_name" => $event_name, "data_array" => $data_array, "id"=>$id ) ) );
			return FALSE;
		}

		# event_name Empty test;
		if ( empty( $event_name ) ){
			$this->wpgsi_log( get_class($this), __METHOD__, "730", "Error: event_name  is Empty!. ". json_encode( array("data_source" => $data_source, "event_name" => $event_name, "data_array" => $data_array, "id"=>$id ) ) );
			return FALSE;
		}

		#  data_array Empty test;
		if ( empty( $data_array ) ){
			$this->wpgsi_log( get_class($this), __METHOD__, "731", "Error: data_array  is Empty!. ".  json_encode( array("data_source" => $data_source, "event_name" => $event_name, "data_array" => $data_array, "id"=>$id ) ) );
			return FALSE;
		}

		# Nested array and sanitize array ;
		foreach ( $data_array as $key => $value ) { 
			if( is_array( $value ) OR is_object( $value )  ) {
				$this->wpgsi_log( get_class($this), __METHOD__, "200", "Success: value should be string, not Array or Object. Array or Object converted to json_encode_ed string! ".  json_encode( array( "data_source" => $data_source, "event_name" => $event_name, "data_array" => $data_array, "id"=>$id ) ) );
				$data_array[$key] = json_encode( $value );
			} else {
				$data_array[$key] = strip_tags( $value );
			}
		}
		
		# If everything okay than Proceed on
		$this->wpgsi_log( get_class($this), __METHOD__, "200", "Success: okay, on the event A1 . " . json_encode( array( "data_source" => $data_array, "event_name" => $event_name, "data_array" => $data_array, "id"=>$id ) ) );
		# Event checked AND before  Passed [Custom Action Hook]  || If you Need Modify Data DO it here;
		do_action( 'wpgsi_event_before', $data_source, $event_name, $data_array, $id ); 
		# Event Passed  [Custom Action Hook]  || Only for GOOGLE || Don't do Anything here - lat it go; 
		do_action( 'wpgsi_khatas', $data_source, $event_name, $data_array, $id ); 
		# Sending a True
		return TRUE;
	}

	/**
	 * This Function will return [wordPress Users] Meta keys.
	 * @since      3.2.0
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
	 * This Function will return [wordPress Posts] Meta keys.
	 * @since      3.2.0
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
	 * This Function will return [wordPress Pages] Meta keys.
	 * @since      3.2.0
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

	# Getting Meta Key of WooCommerce Order, Product, Post, Page, User, Comment Meta Keys 
	/**
	 * This Function will return [WooCommerce Order] Meta keys.
	 * @since      3.2.0
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
	 * @since      3.2.0
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
		if ( empty( $meta_keys ) ) {
			return array( FALSE, 'Error: Empty! No Meta key exist of the Post type WooCommerce Product.');
		} else {
			return array( TRUE, $meta_keys );
		}
	}

	/**
	 * This Function will return [wordPress Users] Meta keys.
	 * @since      3.2.0
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
			return array( FALSE, 'Error: Empty! No Meta key exist on comment meta');
		} else {
			return array( TRUE, $meta_keys );
		}
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
					# Error:  Meta keys are empty;
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
	 * This is a Helper function to check is There Any integration saved. Also set the transient cache
	 * @since      3.4.0
	 * @param      string    $data_source    Which platform call this function s
	*/
	public function wpgsi_integrations( $DataSourceID = '' ) {
		# getting the Options 
		$integrations 			  = get_transient("wpgsi_integrations");
		# Number of published Integration
		$publish				  = 0;
		# Number of Pending Integration
		$pending				  = 0;
		# Setting Default Value 
		$integrationForDataSource = FALSE;
		# Setting Empty Array
		$integrationsArray 		  = array();
		#  from Cache or From DB
		if ( $integrations AND is_array( $integrations ) ) {
			# integration loop starts for Counting the publish and pending Statuses 
			foreach ( $integrations as $value ) {
				# Testing if DataSource is Exist or Not
				if ( $value["DataSourceID"] == $DataSourceID AND  $value["Status"] == "publish" ){
					$integrationForDataSource = TRUE;
				}
				# Counting Publish 
				if ( $value["Status"] == 'publish') {
					$publish++;
				}
				# Counting pending 
				if ( $value["Status"] == 'pending') {
					$pending++;
				}
			}
			# return  array with First Value as Bool and second one is integrationsArray array
			return array( $integrationForDataSource, $integrations, $publish, $pending, "From transient" );
		} else {
			# Getting All Posts
			$listOfConnections   	  =  get_posts( array(
				'post_type'   	 	  => 'wpgsiIntegration',
				'post_status' 		  => array('publish', 'pending'),
				'posts_per_page' 	  => -1
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
					
					# Testing if DataSource is Exist or Not
					if ( $post_excerpt["DataSourceID"] == $DataSourceID AND  $value->post_status == "publish" ){
						$integrationForDataSource = TRUE;
					}
					# Counting Publish 
					if( $integrationsArray[$key]["Status"] == 'publish'){
						$publish++;
					}
					# Counting pending 
					if( $integrationsArray[$key]["Status"] == 'pending'){
						$pending++;
					}
				} 
			}
			# updating the options cache
			set_transient( 'wpgsi_integrations', $integrationsArray );
			# return  array with First Value as Bool and second one is integrationsArray array
			return array( $integrationForDataSource, $integrationsArray, $publish, $pending, "From DB" );
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
		# Inserting The log custom Post 
		$r = wp_insert_post( 
			array(
				'post_content'  => $status_message,
				'post_title'  	=> $status_code,
				'post_status'  	=> "publish",
				'post_excerpt'  => json_encode( array( "file_name" => $file_name, "function_name" => $function_name ) ),
				'post_type'  	=> "wpgsi_log",
			)
		);
		# return 
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
		# Check and Balance 
		if ( empty( $tableName ) ){
			return FALSE;
		}
		# 
		global $wpdb;
		$r = $wpdb->get_results("SHOW TABLES LIKE '". $wpdb->prefix. $tableName ."'");
		
		if ( $r ){
			return TRUE;
		} else {
			return FALSE;
		}
	}
# class Ends
}

# Below code is Out side of the Parent Class s
# Spacial class for getting Data from " Checkout Field Editor aka Checkout Manager for WooCommerce" professional version;
if ( class_exists('WCFE_Checkout_Fields_Utils') ) {
	# Class starts
	class For_WCFE_Checkout_Fields_Utils extends WCFE_Checkout_Fields_Utils {
		# static method 
		# This Static Method will return a nested array; 
		public static function fields() {
			# Check to see is method exist on the Parent class 
			if ( method_exists('WCFE_Checkout_Fields_Utils', 'get_all_custom_checkout_fields') ){
				# Creating Empty Array
				$woo_checkout_field_editor_pro 	=  array();
				# Calling Parent method 
				$custom_field_list = parent::get_all_custom_checkout_fields();
				# Populating The array 
				foreach( $custom_field_list as $key => $val) {
					$woo_checkout_field_editor_pro[$key]['type'] 	= $val->type;
					$woo_checkout_field_editor_pro[$key]['name'] 	= $val->name;
					$woo_checkout_field_editor_pro[$key]['label'] 	= "CFE - ". $val->title;
				}

				# return Value 
				if ( empty( $woo_checkout_field_editor_pro ) ){
					return array( FALSE, "Error: Checkout Field Editor aka Checkout Manager for WooCommerce is EMPTY no Custom Field." );
				} else {
					return array( TRUE, $woo_checkout_field_editor_pro );
				}		

			} else {
				# if method is not exist;
				return array( FALSE, "Error: This get_all_custom_checkout_fields() method is not exists in the For_WCFE_Checkout_Fields_Utils class;" );
			}
		}
	}
	# Class is ends 
}



#------------------------------- TODO: -------------------------------
#----------------------------- 14/jul/2021 ---------------------------
# 2. Test Every Fields and Data source					[x] Done
# 5. Stop Dual Submission -- Important 					[x] Done
# 6. Add Hook For Custom Platform						[x] Fix it Toady 
# 7. Release Version 3.6.0  							[x] Fix it Toady 
#-------------------------------- FIXME: -----------------------------