<?php

namespace RoMarketingPro;

class RoKMLSitemap{

	public function __construct(){
		$this->init();
	}

	protected function init(){
		add_action( 'wp_loaded', array( $this, 'maybe_print_kml' ) );
	}

	public function maybe_print_kml(){
		if( $_SERVER['REQUEST_URI'] != '/geo-sitemap.xml' || ! have_rows( 'kml_sitemap_locations', 'option' ) ){
			return;
		}

		header('Content-Type: application/xml'); //Set header to xml to avoid any validation warnings
		echo '<?xml version="1.0" encoding="UTF-8"?>'; //Print xml line with PHP to avoid syntax errors
		?>			
			<kml xmlns="http://www.opengis.net/kml/2.2" xmlns:atom="http://www.w3.org/2005/Atom">
				<Document>
					<name>Locations for <?php echo get_bloginfo( 'name' ); ?>.</name>
					<atom:link rel="related" href="<?php echo site_url(); ?>" />
					<Folder>
						<?php while( have_rows( 'kml_sitemap_locations', 'option' ) ): the_row(); ?>
							<Placemark>
								<name><![CDATA[<?php the_sub_field( 'name' ); ?>]]></name>
								<address><![CDATA[<?php echo get_sub_field( 'address' ) . ' ' . get_sub_field( 'city' ) . ', ' . get_sub_field( 'state' ) . ', ' . get_sub_field( 'zip' ) . ' United States (US)'; ?>]]>
								</address>
								<description><![CDATA[<?php the_sub_field( 'description' ) ?>]]></description>
								<Point>
									<coordinates><?php echo get_sub_field( 'longitude' ) . ',' . get_sub_field( 'latitude' ); ?></coordinates>
								</Point>
							</Placemark>
						<?php endwhile; ?>
					</Folder>
				</Document>
			</kml>
		<?php
		die;
	}
}

function initialize_kml_sitemap(){
	$ro_kml_sitemap = new RoKMLSitemap;
}
add_action( 'init', 'RoMarketingPro\initialize_kml_sitemap' );