<?php

namespace RoWooCommerce;

class ProductSchema {

	protected $options;
	protected $product;
	protected $schema_object;
	protected $site_name;

	public function __construct(){

		if( ! $this->schema_enabled() ) return;

		$this->initialize_schema_object();
		$this->maybe_add_aggregate_rating();
		$this->maybe_add_category();
		$this->maybe_add_image();
		$this->maybe_add_price();
		$this->maybe_add_availability();
		$this->maybe_add_seller();

		?>
			<!-- Rich Snippets JSON added by ro-woocommerce -->
			<script type="application/ld+json">
				<?php echo json_encode( $this->schema_object, JSON_UNESCAPED_SLASHES ); ?>
			</script>
			<!-- End Rich Snippets JSON -->
		<?php
	}

	public function schema_enabled(){
		if( ! is_product() ) return false;

		global $roWcOptions;

		if( isset( $roWcOptions['product_schema'] ) && $roWcOptions['product_schema'] ){
			$this->product = wc_get_product( get_the_ID() );
			$this->options = $roWcOptions;
			return true;
		}else{
			return false;
		}
	}

	protected function initialize_schema_object(){
		$this->schema_object = new \StdClass();
		$this->schema_object->{'@context'} = 'http://schema.org';
		$this->schema_object->{'@type'} = 'Product';

		$this->site_name = get_bloginfo( 'name' );
		if( ! $this->site_name ){
			$this->site_name = get_bloginfo( 'url' );
		}

		$this->schema_object->name = $this->site_name;	
	}

	protected function maybe_add_aggregate_rating(){
		if( ! isset( $this->options['product_schema_aggregate_rating'] ) || ! $this->options['product_schema_aggregate_rating'] ) return;

		$aggregate_rating = new \StdClass();
		$aggregate_rating->{'@type'} = 'AggregateRating';
		$aggregate_rating->ratingValue = $this->product->get_average_rating();
		$aggregate_rating->reviewCount = $this->product->get_rating_count();

		//Don't include the rating information if there are no ratings
		if( $aggregate_rating->ratingValue == 0 || $aggregate_rating->reviewCount == 0 ) return;

		$this->schema_object->aggregateRating = $aggregate_rating;
	}

	/**
	 * Adds all of the categories found for the product by finding all of the ancestors for each category and concatenating
	 * them together with the ">" character. 
	 * If a product has multiple categories with the same lineage, each lineage will be displayed as a separate category:
	 * Clothing, Clothing > Hoodies, Clothing > Hoodies > Short Sleeve Hoodies, Music
	 */
	protected function maybe_add_category(){
		if( ! isset( $this->options['product_schema_category'] ) || ! $this->options['product_schema_category'] ) return;

		$categories = get_the_terms( $this->product->get_id(), 'product_cat' );

		if( ! $categories || ! is_array( $categories ) ) return;

		$category_sets = array();
		foreach($categories as $category){
			$ancestors = get_ancestors( $category->term_id, 'product_cat' );
			$categories_string = $category->name;
			if( $ancestors && is_array( $ancestors ) ){
				foreach( $ancestors as $ancestor ){
					$ancestor_cat = get_term_by( 'id', $ancestor, 'product_cat' );
					$categories_string = $ancestor_cat->name . ' > ' . $categories_string;
				}
			}

			$category_sets[] = $categories_string;
		}

		sort( $category_sets, SORT_STRING );

		$this->schema_object->category = implode( ', ', $category_sets );
	}

	protected function maybe_add_image(){
		if( ! isset( $this->options['product_schema_image'] ) || ! $this->options['product_schema_image'] ) return;

		$image_id = $this->product->get_image_id();
		if( ! $image_id ) return;

		$image_src = wp_get_attachment_image_src( $image_id );
		if( ! $image_src || ! isset( $image_src[0] ) ) return;

		$this->schema_object->image = $image_src[0];
	}

	protected function maybe_add_price(){
		if( ! isset( $this->options['product_schema_price'] ) || ! $this->options['product_schema_price'] ) return;

		$offer = new \StdClass();
		$offer->{'@type'} = 'Offer';
		$offer->price = $this->product->get_price();
		$offer->priceCurrency = get_woocommerce_currency();

		$this->schema_object->offers = $offer;
	}

	protected function maybe_add_availability(){
		if( ! isset( $this->options['product_schema_availability'] ) || ! $this->options['product_schema_availability'] ) return;

		$raw_availability = $this->product->get_availability();
		if( ! $raw_availability || ! isset( $raw_availability['class'] ) ) return;

		if( $raw_availability['class'] === 'in-stock' ){
			$availability = 'http://schema.org/InStock';
		}else{
			$availability = 'http://schema.org/OutOfStock';
		}

		if( isset( $this->schema_object->offers ) ){
			$this->schema_object->offers->availability = $availability;
		}else{
			$this->schema_object->offers = new \StdClass();
			$this->schema_object->offers->availability = $availability;
		}
	}

	protected function maybe_add_seller(){
		if( ! isset( $this->options['product_schema_seller'] ) || ! $this->options['product_schema_seller'] ) return;

		$seller = new \StdClass();
		$seller->{'@type'} = 'Organization';
		$seller->name = $this->site_name;

		if( isset( $this->schema_object->offers ) ){
			$this->schema_object->offers->seller = $seller;
		}else{
			$this->schema_object->offers = new \StdClass();
			$this->schema_object->offers->seller = $seller;
		}
	}
}

function ro_initialize_product_schema(){
	$ROProductSchema = new ProductSchema;
}
add_action( 'wp_head', 'RoWooCommerce\ro_initialize_product_schema' );
