<?php

namespace RoWooCommerce;

class RO_WC_Report_Recovered_Carts extends \WC_Admin_Report {

    /**
	 * Chart colors.
	 *
	 * @var array
	 */
    public $chart_colours = array();

    /**
	 * The report data.
	 *
	 * @var stdClass
	 */
    private $report_data;
    
    /**
	 * Get report data.
	 *
	 * @return stdClass
	 */
	public function get_report_data() {
		if ( empty( $this->report_data ) ) {
			$this->query_report_data();
		}
		return $this->report_data;
	}
    
    /**
     * Output the report.
     */
    public function output_report() {
        $ranges = array(
            'year'         => __( 'Year', 'woocommerce' ),
            'last_month'   => __( 'Last month', 'woocommerce' ),
            'month'        => __( 'This month', 'woocommerce' )
        );

        $this->chart_colours = array(
			'recovered_amount'     => '#3498db',
			'recovered_count'      => '#dbe1e3'
		);

        $current_range = ! empty( $_GET['range'] ) ? sanitize_text_field( $_GET['range'] ) : 'month';

        if ( ! in_array( $current_range, array( 'custom', 'year', 'last_month', '7day' ) ) ) {
            $current_range = 'month';
        }

        $this->check_current_range_nonce( $current_range );
        $this->calculate_current_range( $current_range );
        include( WC()->plugin_path() . '/includes/admin/views/html-report-by-date.php' );
    }

    /**
	 * Get all data needed for this report and store in the class.
	 */
    private function query_report_data(){
        global $wpdb;
        $this->report_data = new \stdClass();
    
        $this->report_data->recovered_cart_orders = (array) $this->get_order_report_data(
            array(
                'data' => array(
                    'ID' => array(
                        'type'     => 'post_data',
                        'function' => 'COUNT',
                        'name'     => 'count',
                    ),
                    'post_date'    => array(
						'type'     => 'post_data',
						'function' => '',
						'name'     => 'post_date',
					),
                ),
                'where_meta'   => array(
                    array(
                        'meta_key'   => 'ro_cart_recovered',
                        'meta_value' => 'Yes',
                        'operator'   => '=',
                    ),
                ),
                'filter_range' => true,
                'order_types'  => wc_get_order_types( 'sales-reports' ),
                'group_by'     => $this->group_by_query,
                'order_by'     => 'post_date ASC',
                'query_type'   => 'get_results',
                'order_status' => array( 'completed', 'processing', 'on-hold', 'refunded' ),
            )
        );

        $this->report_data->recovered_cart_sales = (array) $this->get_order_report_data(
            array(
                'data' => array(
                    '_order_total' => array(
                        'type'     => 'meta',
                        'function' => 'SUM',
                        'name'     => 'total_sales',
                    ),
                    'post_date'    => array(
						'type'     => 'post_data',
						'function' => '',
						'name'     => 'post_date',
					),
                ),
                'where_meta'   => array(
                    array(
                        'meta_key'   => 'ro_cart_recovered',
                        'meta_value' => 'Yes',
                        'operator'   => '=',
                    ),
                ),
                'filter_range' => true,
                'order_types'  => wc_get_order_types( 'sales-reports' ),
                'group_by'     => $this->group_by_query,
                'order_by'     => 'post_date ASC',
                'query_type'   => 'get_results',
                'order_status' => array( 'completed', 'processing', 'on-hold', 'refunded' ),
            )
        );

        // Get the total recovered orders for this period.
        $this->report_data->total_orders = absint( 
            array_sum( wp_list_pluck( $this->report_data->recovered_cart_orders, 'count' ) ) 
        );

        // Get the total amount of sales for recovered orders for this period.
        $this->report_data->total_sales = wc_format_decimal( 
            array_sum( wp_list_pluck( $this->report_data->recovered_cart_sales, 'total_sales' ) ), 2 
        );
    }

    /**
	 * Get the legend for the main chart sidebar.
	 *
	 * @return array
	 */
	public function get_chart_legend() {
        $legend = array();
        $data   = $this->get_report_data();

		$legend[] = array(
			/* translators: %s: total recovered cart sales */
			'title'             => sprintf(
				__( '%s recovered cart sales in this period', 'woocommerce' ),
				'<strong>' . wc_price( $data->total_sales ) . '</strong>'
			),
			'placeholder'      => __( 'This is the sum of the order totals for recovered carts.', 'woocommerce' ),
			'color'            => $this->chart_colours['recovered_amount'],
			'highlight_series' => 1,
		);

		$legend[] = array(
			/* translators: %s: total recovered orders */
			'title'            => sprintf(
				__( '%s carts recovered', 'woocommerce' ),
				'<strong>' . $data->total_orders . '</strong>'
			),
			'color'            => $this->chart_colours['recovered_count'],
			'highlight_series' => 0,
		);

		return $legend;
    }
  
    /**
     * Get the main chart.
     */
    public function get_main_chart() {
        global $wp_locale;

        // This should get called automatically, but it doesn't. So, I'm calling it manually.
        $this->get_chart_legend();

		// Prepare data for report
		$data = array(
			'recovered_counts' => $this->prepare_chart_data( 
                $this->report_data->recovered_cart_orders, 
                'post_date', 
                'count', 
                $this->chart_interval, 
                $this->start_date, 
                $this->chart_groupby 
            ),
			'recovered_amounts' => $this->prepare_chart_data( 
                $this->report_data->recovered_cart_sales, 
                'post_date', 
                'total_sales', 
                $this->chart_interval, 
                $this->start_date, 
                $this->chart_groupby 
            ),
        );

        // Encode in json format
		$chart_data = json_encode(
			array(
				'recovered_counts'  => array_values( $data['recovered_counts'] ),
				'recovered_amounts' => array_values( $data['recovered_amounts'] )
			)
		);

        ?>
		<div class="chart-container">
			<div class="chart-placeholder main"></div>
		</div>
		<script type="text/javascript">
			var main_chart;
			jQuery(function(){
                var order_data = jQuery.parseJSON( '<?php echo $chart_data; ?>' );
				var drawGraph = function( highlight ) {
					var series = [
						{
							label: "<?php echo esc_js( __( 'Number of cart recoveries', 'woocommerce' ) ); ?>",
							data: order_data.recovered_counts,
							color: '<?php echo $this->chart_colours['recovered_count']; ?>',
							bars: { fillColor: '<?php echo $this->chart_colours['recovered_count']; ?>', fill: true, show: true, lineWidth: 0, barWidth: <?php echo $this->barwidth; ?> * 0.5, align: 'center' },
							shadowSize: 0,
							hoverable: false
						},
						{
							label: "<?php echo esc_js( __( 'Recovered carts sales amount', 'woocommerce' ) ); ?>",
							data: order_data.recovered_amounts,
							yaxis: 2,
							color: '<?php echo $this->chart_colours['recovered_amount']; ?>',
							points: { show: true, radius: 5, lineWidth: 2, fillColor: '#fff', fill: true },
							lines: { show: true, lineWidth: 2, fill: false },
							shadowSize: 0,
							<?php echo $this->get_currency_tooltip(); ?>
						}
					];

					if ( highlight !== 'undefined' && series[ highlight ] ) {
						highlight_series = series[ highlight ];

						highlight_series.color = '#9c5d90';

						if ( highlight_series.bars ) {
							highlight_series.bars.fillColor = '#9c5d90';
						}

						if ( highlight_series.lines ) {
							highlight_series.lines.lineWidth = 5;
						}
					}

					main_chart = jQuery.plot(
						jQuery('.chart-placeholder.main'),
						series,
						{
							legend: {
								show: false
							},
							grid: {
								color: '#aaa',
								borderColor: 'transparent',
								borderWidth: 0,
								hoverable: true
							},
							xaxes: [ {
								color: '#aaa',
								position: "bottom",
								tickColor: 'transparent',
								mode: "time",
								timeformat: "<?php echo ( 'day' === $this->chart_groupby ) ? '%d %b' : '%b'; ?>",
								monthNames: <?php echo json_encode( array_values( $wp_locale->month_abbrev ) ); ?>,
								tickLength: 1,
								minTickSize: [1, "<?php echo $this->chart_groupby; ?>"],
								font: {
									color: "#aaa"
								}
							} ],
							yaxes: [
								{
									min: 0,
									minTickSize: 1,
									tickDecimals: 0,
									color: '#d4d9dc',
									font: { color: "#aaa" }
								},
								{
									position: "right",
									min: 0,
									tickDecimals: 2,
									alignTicksWithAxis: 1,
									color: 'transparent',
									font: { color: "#aaa" }
								}
							],
						}
					);

					jQuery('.chart-placeholder').resize();
				}

				drawGraph();

				jQuery('.highlight_series').hover(
					function() {
						drawGraph( jQuery(this).data('series') );
					},
					function() {
						drawGraph();
					}
				);
			});
		</script>
		<?php
    }
}
