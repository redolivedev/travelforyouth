<?php

namespace RoMarketingPro;

class NAPBuilder
{
    public function __construct()
    {
        add_shortcode('ro_nap_block', array( $this, 'nap_builder_output' ));
        add_action('wp_head', array( $this, 'nap_builder_json' ));
    }

    public function nap_builder_output($atts)
    {
        $nap_builder_blocks = get_field('nap_block', 'options');

        $block = array_filter($nap_builder_blocks, function ($block) use ($atts) {
            return strtolower($block['nap_block_id']) === strtolower($atts['id']);
        });

        $block = array_values($block);

        if (isset($block[0]) && $block[0]) {
            $block = $block[0];
        } else {
            return;
        }

        // Strip hyphens out of child schemas
        $block['nap_business_type'] = str_replace(array( '-', ' ' ), '', $block['nap_business_type']);

        if ($block['nap_street_address_2']) {
            $block['nap_address'] = $block['nap_street_address_1'] . ', ' . $block['nap_street_address_2'];
        } else {
            $block['nap_address'] = $block['nap_street_address_1'];
        }

        if ($block['nap_show_image']) {
            $block['nap_show_image'] = '';
        } else {
            $block['nap_show_image'] = 'style="display:none;" ';
        }

        if ($block['nap_one_line']) {
            $block['nap_address'] .= ',';
            $block['nap_one_line'] = 'style="display:inline;" ';
            $block['nap_one_line_nowrap'] = 'style="display:inline;white-space:nowrap;" ';
        } else {
            $block['nap_one_line'] = '';
        }

        if ($block['nap_separator']) {
            $block['nap_separator'] = $block['nap_separator'];
        } else {
            $block['nap_separator'] = '&nbsp;';
        }

        ob_start();
        require RO_MARKETING_PRO_DIR . 'includes/nap-builder/templates/nap-builder-html.php';
        return ob_get_clean();
    }

    public function nap_builder_json()
    {
        global $post;
        
        $nap_builder_blocks = get_field('nap_block', 'options');

        // Find which blocks should appear on this page
        $nap_filtered_blocks = $this->get_blocks_for_current_page($nap_builder_blocks, $post);

	if (empty($nap_filtered_blocks)) return;

        $json_data = [
            '@context' => [
                '@vocab' => 'http://schema.org/'
            ],
            '@graph' => []
        ];
        foreach ($nap_filtered_blocks as $nap_builder_block) {
            // Strip hyphens out of child schemas
            $type = str_replace(array( '-', ' ' ), '', $this->getValueFromValue($nap_builder_block, 'nap_business_type'));
            
            $full_address = $this->getValueFromValue($nap_builder_block, 'nap_street_address_2') ?
                $this->getValueFromValue($nap_builder_block, 'nap_street_address_1') . ', ' . $this->getValueFromValue($nap_builder_block, 'nap_street_address_2') :
                $this->getValueFromValue($nap_builder_block, 'nap_street_address_1');

            $json_data['@graph'][] = [
                "@context" => "http://schema.org",
                "@id" => $this->getValueFromValue($nap_builder_block, 'nap_block_id'),
                "@type" => $type,
                "logo" => $this->getValueFromValue($nap_builder_block, 'nap_image'),
                "url" => $this->getValueFromValue($nap_builder_block, 'nap_url'),
                "image" => $this->getValueFromValue($nap_builder_block, 'nap_image'),
                "hasMap" => $this->getValueFromValue($nap_builder_block, 'nap_map_link'),
                "address" => [
                    "@type" => "PostalAddress",
                    "addressLocality" => $this->getValueFromValue($nap_builder_block, 'nap_city'),
                    "addressRegion" => $this->getValueFromValue($nap_builder_block, 'nap_state'),
                    "postalCode" => $this->getValueFromValue($nap_builder_block, 'nap_zip_code'),
                    "streetAddress" => $full_address,
                ],
                "name" => $this->getValueFromValue($nap_builder_block, 'nap_name'),
                "telephone" => $this->getValueFromValue($nap_builder_block, 'nap_phone'),
                "priceRange" => $this->getValueFromValue($nap_builder_block, 'nap_price_range'),
            ];
        }
        
        echo '<script type="application/ld+json">';
        echo json_encode($json_data);
        echo '</script>';
    }
    
    public function getValueFromValue($array, $key)
    {
        return !empty($array[$key]) ? $array[$key] : null;
    }

    /**
     * Determines which blocks should be displayed on the current page based on display configurations
     * set on each NAP block.
     */
    protected function get_blocks_for_current_page($blocks, $post)
    {
        if(!$blocks || !is_array($blocks)) return array();
        $blocks_after_specific_pages_filter = array_filter($blocks, function($block) use($post){
            if($block['shortcode_or_json'] === 'shortcode') return false;

            if($block['nap_display_setting'] === 'specific_pages'){
				// Check if the current page matches any of the specified pages
                if($this->pages_match($block['nap_display_pages'], 'nap_specified_page', $post)) return true;

				// Check if the current page matches any of the specified URLs
				if($this->strings_match($block['nap_display_pages_url_string'], 'nap_specified_string', $_SERVER['REQUEST_URI'])){
                    return true;
                }

				// If the current page didn't match a specified page or url, return false
				return false;
			}else{
				return true;
			}
        });

        // Filter out blocks based on excluded pages settings
		$blocks_after_excluded_pages_filter = array_filter($blocks_after_specific_pages_filter, function($block) use($post){
			// Check if the current page matches any of the excluded pages
			if($this->pages_match($block['nap_exclude_pages'], 'nap_excluded_page', $post)) return false;

			// Check if the current page matches any of the excluded URLs
			if($this->strings_match($block['nap_exclude_pages_url_string'], 'nap_excluded_string', $_SERVER['REQUEST_URI'])){
                return false;
            }

			// If the current page didn't match an excluded page, return true
			return true;
		});

		// Reorder blocks array so the values start at 0, and return the array
		return array_values( $blocks_after_excluded_pages_filter );
    }

    /**
	 * Checks to see if the current_page matches any of the pages in the specified_pages array
	 */
	protected function pages_match($specified_pages, $value_index, $current_page){
		if(!$specified_pages || !is_array($specified_pages) || !$current_page) return false;

		$matching_pages = array_filter($specified_pages, function($specified_page) use($current_page, $value_index){
			return $specified_page[$value_index] === $current_page->ID;
		});

		return(!empty($matching_pages));
    }
    
    /**
	 * Checks to see if the current_page_url contains the specified_url_string
	 */
	protected function strings_match($specified_urls, $value_index, $current_url){
        if(!$specified_urls || !is_array($specified_urls)) return false;

		$matching_strings = array_filter($specified_urls, function($specified_url) use($current_url, $value_index){

			// If the specified URL is just an empty string, return false. It should not be allowed to match the current URL.
			if(!$specified_url[$value_index]) return false;

			// Use '/' as the second argument of preg_quote to make sure it escapes forward slashes
			return preg_match('/' . preg_quote($specified_url[$value_index], '/') . '/', $current_url);
		});

		return (!empty($matching_strings));
	}
}

function ro_load_nap_builder()
{
    if (class_exists('acf')) {
        $nap_builder = new NAPBuilder;
    }
}
add_action('plugins_loaded', 'RoMarketingPro\ro_load_nap_builder');
