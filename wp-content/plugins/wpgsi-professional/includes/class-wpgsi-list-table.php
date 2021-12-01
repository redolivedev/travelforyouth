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

if(!class_exists('WP_List_Table')) require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');

// Plugin class.
class Wpgsi_List_Table extends WP_List_Table {

    public $eventsAndTitles ;

  /**
   * Construct function
   * Set default settings.
   */
    function __construct( $eventsAndTitles ) {
        global $status, $page;
        $this->eventsAndTitles = $eventsAndTitles;
        //Set parent defaults
        parent::__construct(array(
            'ajax'     => FALSE,
            'singular' => 'user',
            'plural'   => 'users',
        ));
    }
    
  /**
   * Renders the columns.
   * @since 1.0.0
   */
    public function column_default( $item, $column_name ) {
        $post_excerpt = unserialize( $item->post_excerpt );
        $post_content = '';

        switch ($column_name) {
            case 'id':
                $value = $item->ID;
                break;
            case 'IntegrationTitle':
                $value = $item->post_title;
                break;
            case 'DataSource': 
                $value = $post_excerpt->Data_source ;
                break;
            case 'worksheetName':
                $value = $post_excerpt->Worksheet ;
                break;
            case 'WorksheetID':
                $value = $post_excerpt->Worksheet ;
                break;
            case 'spreadsheetName':
                $value = $post_excerpt->Spreadsheet ; 
                break;
            case 'SpreadsheetID':
                $value = '';
                break;
            case 'remoteTitles':
                $value = $post_excerpt->Worksheet ;
                break;
            case 'relations':
                $value = $post_excerpt->Worksheet ;
                break;
            case 'status':
                $value =  $item->post_status;
                break;
            default:
                $value = '--';
        }
    }

    /**
     * Retrieve the table columns.
     * @since 1.0.0
     * @return array $columns Array of all the list table columns.
     */
    public function get_columns() {
        $columns = array(
            'cb'                 => '<input type="checkbox" />',
            'IntegrationTitle'   => esc_html__( 'Title', 'wpgsi' ),
            'DataSource'         => esc_html__( 'Data Source', 'wpgsi' ),
            'Worksheet'          => esc_html__( 'Worksheet', 'wpgsi' ),
            'Spreadsheet'        => esc_html__( 'Spreadsheet', 'wpgsi' ),
            'Relations'          => esc_html__( 'ID : Column Title ⯈ Relations', 'wpgsi' ),
            'status'             => esc_html__( 'Status', 'wpgsi' )
        );

        return $columns;
    }

    # Render the checkbox column.
    public function column_cb( $item ) {
        return '<input type="checkbox" name="id[]" value="' . absint( $item->ID ) . '" />';
    }

    public function column_DataSource( $item ) {
        
        $post_excerpt = json_decode( $item->post_excerpt, true );
        
        if ( isset( $post_excerpt['DataSource'] ) ) {
            return esc_attr( $post_excerpt['DataSource'] );
        } else {
            _e( "Not Set !" , "wpgsi" );
        }
    }

    public function column_Worksheet( $item ) {
        
        $post_excerpt = json_decode( $item->post_excerpt, true );
       
        if ( isset($post_excerpt['Worksheet'] , $post_excerpt['WorksheetID'] ) ) {
            return  esc_attr( $post_excerpt['Worksheet'] ) . "<br><br><i>"  . esc_attr( $post_excerpt['WorksheetID'] ) ."</i>";
        } else {
            _e( "Not Set !" , "wpgsi" );
        }
    }

    public function column_Spreadsheet( $item ) {
        
        $post_excerpt = json_decode( $item->post_excerpt, true );
        
        if( isset( $post_excerpt['Spreadsheet'], $post_excerpt['SpreadsheetID'] ) ) {
            return esc_attr( $post_excerpt['Spreadsheet'] ) .  "<br><br><i>"  . esc_attr( $post_excerpt['SpreadsheetID'] ) ."</i>";
        } else {
            _e( "Not Set !" , "wpgsi" );
        }
    }

    # Working Here || Need To Change || Remove Empty Value array_filter()
    # Relations Output from DB
    public function column_Relations( $item ) {
        $string         = "";
        $DataSource     = json_decode( $item->post_excerpt, true )['DataSourceID'] ;
        $ColumnTitles   = json_decode( $item->post_content, true )[0];
        # ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        # Keep the Error in the Log 
        # Checking is Custom post_content data is Valid JSON AND didn't edited if edited and not valid return an empty array;
        if ( json_decode( $item->post_content, true ) ){
            $Relations  = array_filter( json_decode( $item->post_content, true )[1] );
        } else {
            $string     = "<b>Error: invalid JSON string. Please delete this integration & create new one.</b>";
            $Relations  = array();
        }

        $data           = array();
        $eventsAndTitlesBracket = array();

        # Change The key to Bracketed 
        if ( isset( $this->eventsAndTitles[$DataSource] ) ){
            foreach ( $this->eventsAndTitles[$DataSource] as $key => $value ) {
                $eventsAndTitlesBracket[ "{{". $key ."}}" ] = "<code><b>" . esc_attr( $value ) ."</b></code>" ;
            }
        }

        # replace the placeholder ;
        $countRelations = count($Relations); 
        $i = 0 ;
        foreach ( $Relations as $key => $value ) {
            $i++ ;
            if ( $i == $countRelations ) {
                $string .= $key . " : " . esc_attr( $ColumnTitles[ $key] ) . " ⯈ " . strtr( $value, $eventsAndTitlesBracket) ;
            } else {  
                $string .= $key . " : " . esc_attr( $ColumnTitles[ $key] ) . " ⯈ " . strtr( $value, $eventsAndTitlesBracket) . "<br>" ;
            }
        }

        return  $string;
    }

    public function column_status( $item ) {
        if ( $item->post_status == 'publish' ) {
            $actions = "<br><span title='Enable or Disable the Integrations'  onclick='window.location=\"admin.php?page=wpgsi&action=status&id=" . $item->ID . "\"'  class='a_activation_checkbox'  ><a class='a_activation_checkbox' href='?page=wpgsi&action=edit&id=".$item->ID."'>  <input type='checkbox' name='status' checked=checked > </a></span>" ;
        } else {
            $actions = "<br><span title='Enable or Disable the Integrations' onclick='window.location=\"admin.php?page=wpgsi&action=status&id=" . $item->ID . " \"'  class='a_activation_checkbox'  ><a class='a_activation_checkbox' href='?page=wpgsi&action=edit&id=".$item->ID."'>  <input type='checkbox' name='status' > </a></span>" ;
        }
        $actions .= "<br><br> <a href='" . admin_url() . "admin.php?page=wpgsi&action=columnTitle&id=" . $item->ID . " ' class='dashicons dashicons-controls-repeat' title='Test Fire ! Please check your Google Spreadsheet for effects' ></a>";

        # Product Update lock;
        if ( wpgsi_fs()->is__premium_only() ){
			if ( wpgsi_fs()->can_use_premium_code() ){
                # if Product then Updates 
                $new_post_excerpt = json_decode( $item->post_excerpt, true );
                
                # Display Update button to Product Update Integration 
                if ( isset( $new_post_excerpt['DataSourceID'] ) AND  $new_post_excerpt['DataSourceID'] == 'wc-new_product' ) {
                    $actions .= "<br><br> <a href='".admin_url()."admin.php?page=wpgsi&action=updateFromSheet&id=" . $item->ID . " ' class='dashicons dashicons-database-import' title='Click here to update the simple products from remote SpreedSheet' target='_blank' ></a>  ";
                }

                # Display Update button to Product Update Integration 
                if ( isset( $new_post_excerpt['DataSourceID'] ) AND  $new_post_excerpt['DataSourceID'] == 'wc-edit_product' ) {
                    $actions .= "<br><br> <a href='".admin_url()."admin.php?page=wpgsi&action=updateFromSheet&id=" . $item->ID . " ' class='dashicons dashicons-database-import' title='Click here to update the simple products from remote SpreedSheet' target='_blank' ></a>  ";
                }
            }
        }

        return   $actions ;
    }

    # Render the form name column with action links.
    public function column_IntegrationTitle( $item ) {
        $name = ! empty( $item->post_title ) ? $item->post_title : '--';
        $name = sprintf( '<span><strong>%s</strong></span>', esc_html__( $name ) );
        # Build all of the row action links.
        $row_actions = array();
        # Edit.
        $row_actions['edit'] = sprintf(
            '<a href="%s" title="%s">%s</a>',
            add_query_arg(
                array(
                    'action' => 'edit',
                    'id'     => $item->ID,
                ),
                admin_url( 'admin.php?page=wpgsi' )
            ),
            esc_html__( 'Edit This Relation', 'wpgsi' ),
            esc_html__( 'Edit', 'wpgsi' )
        );

        # Delete.
        $row_actions['delete'] = sprintf(
            '<a href="%s" class="relation-delete" title="%s">%s</a>',
            wp_nonce_url(
                add_query_arg(
                    array(
                        'action' => 'delete',
                        'id'     => $item->ID,
                    ),
                    admin_url( 'admin.php?page=wpgsi' )
                ),
                'wpgsi_delete_relation_nonce'
            ),
            esc_html__( 'Delete this relation', 'wpgsi' ),
            esc_html__( 'Delete', 'wpgsi' )
        );

        # Build the row action links and return the value.
        return $name . $this->row_actions( apply_filters( 'fts_relation_row_actions', $row_actions, $item ) );
    }

    # Define bulk actions available for our table listing.
    public function get_bulk_actions() {
        $actions = array(
            'delete' => esc_html__( 'Delete', 'wpgsi' ),
        );
        return $actions;
    }

    # +++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    # This Function Should be Remove || Use wpgsi_delete_connection function in wpgsi admin class
    # Process the bulk actions.
    public function process_bulk_actions() {
        # getting the ids
        $ids = isset( $_GET['id'] ) ? $_GET['id'] : array();
        # security and ID Check 
        if ( $this->current_action() == 'delete' && wp_verify_nonce( $_GET['wpgsi_nonce'], 'wpgsi_nonce_bulk_action' ) && ! empty( $ids )  ) {
            # Loop the Ids
            foreach ( $ids as $id ) {
                wp_delete_post( $id );
            }

            # Caching the integrations 
            $integrations =  $this->wpgsi_getIntegrations();
            if ( $integrations[0] ){
                # setting or updating the transient;
                set_transient( 'wpgsi_integrations', $integrations[1] );
            }
        }
    }

    # Message to be displayed when there are no relations.
    public function no_items() {
        printf(
            wp_kses(
                __( 'Whoops, you haven\'t created a relation yet. Want to <a href="%s">give it a go</a>?', 'wpgsi' ),
                array(
                    'a' => array(
                        'href' => array(),
                    ),
                )
            ),
            admin_url( 'admin.php?page=wpgsi&action=new' )
        );
    }

    # Sortable settings.
    public function get_sortable_columns() {
        return array(
            'IntegrationTitle'       => array('IntegrationTitle', TRUE),
            'data_source'            => array('data_source', TRUE),
            'spreadsheetsAndProvider'=> array('spreadsheetsAndProvider', TRUE),
        );
    }

    # Fetching Data from Database 
    public function fetch_table_data() {
        return get_posts( array( 
            'post_type'     =>'wpgsiIntegration',
            'post_status'   => 'any',
            'posts_per_page'=> -1 ,
        )); 
    }

    # Query, filter data, handle sorting, pagination, and any other data-manipulation required prior to rendering
    public function prepare_items() {
        # Process bulk actions if found.
        $this->process_bulk_actions();
        # Defining Values
        $per_page              = 20;
        $count                 = $this->count();
        $columns               = $this->get_columns();
        $hidden                = array();
        $sortable              = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $table_data            = $this->fetch_table_data();
        $this->items           = $table_data;
        $this->admin_header();

        $this->set_pagination_args(
            array(
                'total_items' => $count,
                'per_page'    => $per_page,
                'total_pages' => ceil( $count / $per_page ),
            )
        );
    }

    # Count Items for Pagination 
    public function count() {
        $wpgsi_posts = get_posts( array( 
            'post_type'     => 'wpgsiIntegration',
            'post_status'   => 'any',
            'posts_per_page'=> -1,
        )); 
        return count($wpgsi_posts);
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

    # Check this Function! may be useless 
    public function admin_header() {
        $page = ( isset($_GET['page'] ) ) ? esc_attr( $_GET['page'] ) : false;
        # if another page redirect user;
        if ( 'wpgsi' != $page ){
            return;
        }
        
        echo '<style type="text/css">';
        echo '.wp-list-table .column-id { width: 10%; }';
        echo '.wp-list-table .column-IntegrationTitle { width: 10%; }';
        echo '.wp-list-table .column-DataSource { width: 15%; }';
        echo '.wp-list-table .column-Worksheet { width: 15%; }';
        echo '.wp-list-table .column-Spreadsheet { width: 20%; }';
        echo '.wp-list-table .column-Relations { width: 25%; }';
        echo '.wp-list-table .column-status { width: 5%; }';
        echo '</style>';
    }
}
