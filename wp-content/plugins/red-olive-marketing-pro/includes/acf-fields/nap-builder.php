<?php

if( function_exists('acf_add_local_field_group') ):

    acf_add_local_field_group(array(
        'key' => 'group_5a56898edb60e',
        'title' => 'RO NAP Builder',
        'fields' => array(
            array(
                'key' => 'field_5a56899ebb612',
                'label' => 'NAP Block',
                'name' => 'nap_block',
                'type' => 'repeater',
                'instructions' => 'Click Add NAP Block button to create a new NAP block.',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'collapsed' => '',
                'min' => 0,
                'max' => 0,
                'layout' => 'block',
                'button_label' => 'Add NAP Block',
                'sub_fields' => array(
                    array(
                        'key' => 'field_5bd73352611c9',
                        'label' => 'Shortcode or JSON',
                        'name' => 'shortcode_or_json',
                        'type' => 'radio',
                        'instructions' => 'Select whether you would like to implement this Schema using a Shortcode or JSON.<br>
    <b>Shortcode</b>: Exposes a Shortcode that can be added to a page to load address HTML wrapped in schema tags.<br>
    <b>JSON</b>: Automatically loads schema information into the metadata of the page. (Settings for which pages load the schema can be set globally in the NAP JSON Meta section below or on individual pages in the NAP JSON metabox.)',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '50',
                            'class' => '',
                            'id' => '',
                        ),
                        'choices' => array(
                            'shortcode' => 'Shortcode',
                            'json' => 'JSON',
                        ),
                        'allow_null' => 0,
                        'other_choice' => 0,
                        'default_value' => 'json',
                        'layout' => 'horizontal',
                        'return_format' => 'value',
                        'save_other_choice' => 0,
                    ),
                    array(
                        'key' => 'field_5a568e708af0d',
                        'label' => 'Block ID',
                        'name' => 'nap_block_id',
                        'type' => 'text',
                        'instructions' => 'The name for this NAP block.<br>
    NAP block names <strong><i>must be unique</i></strong> to make sure correct data gets added to each page.',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '50',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ),
                    array(
                        'key' => 'field_5bd736600667d',
                        'label' => 'Block ID Shortcode',
                        'name' => '',
                        'type' => 'message',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_5bd73352611c9',
                                    'operator' => '==',
                                    'value' => 'shortcode',
                                ),
                            ),
                        ),
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'message' => 'Add this Shortcode to your page to display this schema information.<br>
    
    <b>[ro_nap_block id="your_block_id"]</b>',
                        'new_lines' => 'wpautop',
                        'esc_html' => 0,
                    ),
                    array(
                        'key' => 'field_5a5786bc036d9',
                        'label' => 'Display On One Line',
                        'name' => 'nap_one_line',
                        'type' => 'true_false',
                        'instructions' => 'Display the NAP block in a single line<br>
    <strong>My Business 123 Example Lane Albany, NY 12345 (555) 123-4567</strong>',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_5bd73352611c9',
                                    'operator' => '==',
                                    'value' => 'shortcode',
                                ),
                            ),
                        ),
                        'wrapper' => array(
                            'width' => '50',
                            'class' => '',
                            'id' => '',
                        ),
                        'message' => '',
                        'default_value' => 0,
                        'ui' => 0,
                        'ui_on_text' => '',
                        'ui_off_text' => '',
                    ),
                    array(
                        'key' => 'field_5a578781036da',
                        'label' => 'Separator Character',
                        'name' => 'nap_separator',
                        'type' => 'text',
                        'instructions' => 'A character (like a pipe "|" or a dash "-") added between the different sections of the NAP block.<br>
    <strong>My Business | 123 Example Lane Albany, NY 12345 | (555) 123-4567</strong>',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_5bd73352611c9',
                                    'operator' => '==',
                                    'value' => 'shortcode',
                                ),
                            ),
                        ),
                        'wrapper' => array(
                            'width' => '50',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ),
                    array(
                        'key' => 'field_5a568a2e8af06',
                        'label' => 'Name',
                        'name' => 'nap_name',
                        'type' => 'text',
                        'instructions' => 'The name of your business listing',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '50',
                            'class' => '-c0',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ),
                    array(
                        'key' => 'field_5a568a6a8af07',
                        'label' => 'Business Type',
                        'name' => 'nap_business_type',
                        'type' => 'select',
                        'instructions' => 'Select your business type.<br>
    Visit <a target="_blank" href="http://schema.org/LocalBusiness">schema.org</a> for more information on available business types.',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '50',
                            'class' => '',
                            'id' => '',
                        ),
                        'choices' => array(
                            'LocalBusiness' => 'LocalBusiness',
                            'AnimalShelter' => 'AnimalShelter',
                            'AutomotiveBusiness' => 'AutomotiveBusiness',
                            '-- AutoBodyShop' => '-- AutoBodyShop',
                            '-- AutoDealer' => '-- AutoDealer',
                            '-- AutoPartsStore' => '-- AutoPartsStore',
                            '-- AutoRental' => '-- AutoRental',
                            '-- AutoRepair' => '-- AutoRepair',
                            '-- AutoWash' => '-- AutoWash',
                            '-- GasStation' => '-- GasStation',
                            '-- MotorcycleDealer' => '-- MotorcycleDealer',
                            '-- MotorcycleRepair' => '-- MotorcycleRepair',
                            'ChildCare' => 'ChildCare',
                            'Dentist' => 'Dentist',
                            'DryCleaningOrLaundry' => 'DryCleaningOrLaundry',
                            'EmergencyService' => 'EmergencyService',
                            '-- FireStation' => '-- FireStation',
                            '-- Hospital' => '-- Hospital',
                            '-- PoliceStation' => '-- PoliceStation',
                            'EmploymentAgency' => 'EmploymentAgency',
                            'EntertainmentBusiness' => 'EntertainmentBusiness',
                            '-- AdultEntertainment' => '-- AdultEntertainment',
                            '-- AmusementPark' => '-- AmusementPark',
                            '-- ArtGallery' => '-- ArtGallery',
                            '-- Casino' => '-- Casino',
                            '-- ComedyClub' => '-- ComedyClub',
                            '-- MovieTheater' => '-- MovieTheater',
                            '-- NightClub' => '-- NightClub',
                            'FinancialService' => 'FinancialService',
                            '-- AccountingService' => '-- AccountingService',
                            '-- AutomatedTeller' => '-- AutomatedTeller',
                            '-- BankOrCreditUnion' => '-- BankOrCreditUnion',
                            '-- InsuranceAgency' => '-- InsuranceAgency',
                            'FoodEstablishment' => 'FoodEstablishment',
                            '-- Bakery' => '-- Bakery',
                            '-- BarOrPub' => '-- BarOrPub',
                            '-- Brewery' => '-- Brewery',
                            '-- CafeOrCoffeeShop' => '-- CafeOrCoffeeShop',
                            '-- FastFoodRestaurant' => '-- FastFoodRestaurant',
                            '-- IceCreamShop' => '-- IceCreamShop',
                            '-- Restaurant' => '-- Restaurant',
                            '-- Winery' => '-- Winery',
                            'GovernmentOffice' => 'GovernmentOffice',
                            '-- PostOffice' => '-- PostOffice',
                            'HealthAndBeautyBusiness' => 'HealthAndBeautyBusiness',
                            '-- BeautySalon' => '-- BeautySalon',
                            '-- DaySpa' => '-- DaySpa',
                            '-- HairSalon' => '-- HairSalon',
                            '-- HealthClub' => '-- HealthClub',
                            '-- NailSalon' => '-- NailSalon',
                            '-- TattooParlor' => '-- TattooParlor',
                            'HomeAndConstructionBusiness' => 'HomeAndConstructionBusiness',
                            '-- Electrician' => '-- Electrician',
                            '-- GeneralContractor' => '-- GeneralContractor',
                            '-- HVACBusiness' => '-- HVACBusiness',
                            '-- HousePainter' => '-- HousePainter',
                            '-- Locksmith' => '-- Locksmith',
                            '-- MovingCompany' => '-- MovingCompany',
                            '-- Plumber' => '-- Plumber',
                            '-- RoofingContractor' => '-- RoofingContractor',
                            'InternetCafe' => 'InternetCafe',
                            'LegalService' => 'LegalService',
                            '-- Attorney' => '-- Attorney',
                            '-- Notary' => '-- Notary',
                            'Library' => 'Library',
                            'LodgingBusiness' => 'LodgingBusiness',
                            '-- BedAndBreakfast' => '-- BedAndBreakfast',
                            '-- Campground' => '-- Campground',
                            '-- Hostel' => '-- Hostel',
                            '-- Hotel' => '-- Hotel',
                            '-- Motel' => '-- Motel',
                            '-- Resort' => '-- Resort',
                            'ProfessionalService' => 'ProfessionalService',
                            'RadioStation' => 'RadioStation',
                            'RealEstateAgent' => 'RealEstateAgent',
                            'RecyclingCenter' => 'RecyclingCenter',
                            'SelfStorage' => 'SelfStorage',
                            'ShoppingCenter' => 'ShoppingCenter',
                            'SportsActivityLocation' => 'SportsActivityLocation',
                            '-- BowlingAlley' => '-- BowlingAlley',
                            '-- ExerciseGym' => '-- ExerciseGym',
                            '-- GolfCourse' => '-- GolfCourse',
                            '-- PublicSwimmingPool' => '-- PublicSwimmingPool',
                            '-- SkiResort' => '-- SkiResort',
                            '-- SportsClub' => '-- SportsClub',
                            '-- StadiumOrArena' => '-- StadiumOrArena',
                            '-- TennisComplex' => '-- TennisComplex',
                            'Store' => 'Store',
                            '-- BikeStore' => '-- BikeStore',
                            '-- BookStore' => '-- BookStore',
                            '-- ClothingStore' => '-- ClothingStore',
                            '-- ComputerStore' => '-- ComputerStore',
                            '-- ConvenienceStore' => '-- ConvenienceStore',
                            '-- DepartmentStore' => '-- DepartmentStore',
                            '-- ElectronicsStore' => '-- ElectronicsStore',
                            '-- Florist' => '-- Florist',
                            '-- FurnitureStore' => '-- FurnitureStore',
                            '-- GardenStore' => '-- GardenStore',
                            '-- GroceryStore' => '-- GroceryStore',
                            '-- HardwareStore' => '-- HardwareStore',
                            '-- HobbyShop' => '-- HobbyShop',
                            '-- HomeGoodsStore' => '-- HomeGoodsStore',
                            '-- JewelryStore' => '-- JewelryStore',
                            '-- LiquorStore' => '-- LiquorStore',
                            '-- MensClothingStore' => '-- MensClothingStore',
                            '-- MobilePhoneStore' => '-- MobilePhoneStore',
                            '-- MovieRentalStore' => '-- MovieRentalStore',
                            '-- MusicStore' => '-- MusicStore',
                            '-- OfficeEquipmentStore' => '-- OfficeEquipmentStore',
                            '-- OutletStore' => '-- OutletStore',
                            '-- PawnShop' => '-- PawnShop',
                            '-- PetStore' => '-- PetStore',
                            '-- ShoeStore' => '-- ShoeStore',
                            '-- SportingGoodsStore' => '-- SportingGoodsStore',
                            '-- TireShop' => '-- TireShop',
                            '-- ToyStore' => '-- ToyStore',
                            '-- WholesaleStore' => '-- WholesaleStore',
                            'TelevisionStation' => 'TelevisionStation',
                            'TouristInformationCenter' => 'TouristInformationCenter',
                            'TravelAgency' => 'TravelAgency',
                        ),
                        'default_value' => array(
                            0 => 'LocalBusiness',
                        ),
                        'allow_null' => 0,
                        'multiple' => 0,
                        'ui' => 0,
                        'ajax' => 0,
                        'return_format' => 'value',
                        'placeholder' => '',
                    ),
                    array(
                        'key' => 'field_5a568df38af09',
                        'label' => 'Street Address',
                        'name' => 'nap_street_address_1',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '50',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ),
                    array(
                        'key' => 'field_5a56a03c30899',
                        'label' => 'Street Address Line 2',
                        'name' => 'nap_street_address_2',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '50',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ),
                    array(
                        'key' => 'field_5a568e238af0a',
                        'label' => 'City',
                        'name' => 'nap_city',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '50',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ),
                    array(
                        'key' => 'field_5a568e3e8af0b',
                        'label' => 'State',
                        'name' => 'nap_state',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '25',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ),
                    array(
                        'key' => 'field_5a568e498af0c',
                        'label' => 'Zip Code',
                        'name' => 'nap_zip_code',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '25',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ),
                    array(
                        'key' => 'field_5a568d568af08',
                        'label' => 'Phone',
                        'name' => 'nap_phone',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '50',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ),
                    array(
                        'key' => 'field_5bd0fc4b63e7c',
                        'label' => 'URL',
                        'name' => 'nap_url',
                        'type' => 'url',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '50',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                    ),
                    array(
                        'key' => 'field_5bd78cc908666',
                        'label' => 'Price Range',
                        'name' => 'nap_price_range',
                        'type' => 'text',
                        'instructions' => 'Price range of company product or services -- ex: $50 - $500.<br>
    (This field is set to display:none; in the Shortcode HTML. So, it won\'t appear on the page)',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '51',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ),
                    array(
                        'key' => 'field_5a577ea61a9cc',
                        'label' => 'Google Maps Link',
                        'name' => 'nap_map_link',
                        'type' => 'text',
                        'instructions' => 'Add a URL to hyperlink your business address to its location on Google Maps.<br>
    For instructions on how to create a Google Maps link, <a target="_blank" href="https://support.google.com/maps/answer/144361?co=GENIE.Platform%3DDesktop&hl=en">Click Here</a>',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '50',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ),
                    array(
                        'key' => 'field_5a5cd282799c6',
                        'label' => 'Custom CSS',
                        'name' => 'nap_custom_css',
                        'type' => 'wysiwyg',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_5bd73352611c9',
                                    'operator' => '==',
                                    'value' => 'shortcode',
                                ),
                            ),
                        ),
                        'wrapper' => array(
                            'width' => '50',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'tabs' => 'text',
                        'media_upload' => 0,
                        'toolbar' => 'full',
                        'delay' => 0,
                    ),
                    array(
                        'key' => 'field_5a56a74d0a158',
                        'label' => 'Image',
                        'name' => 'nap_image',
                        'type' => 'image',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '85',
                            'class' => '',
                            'id' => '',
                        ),
                        'return_format' => 'url',
                        'preview_size' => 'thumbnail',
                        'library' => 'all',
                        'min_width' => '',
                        'min_height' => '',
                        'min_size' => '',
                        'max_width' => '',
                        'max_height' => '',
                        'max_size' => '',
                        'mime_types' => '',
                    ),
                    array(
                        'key' => 'field_5a56a7810a159',
                        'label' => 'Display Image',
                        'name' => 'nap_show_image',
                        'type' => 'true_false',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_5bd73352611c9',
                                    'operator' => '==',
                                    'value' => 'shortcode',
                                ),
                            ),
                        ),
                        'wrapper' => array(
                            'width' => '15',
                            'class' => '',
                            'id' => '',
                        ),
                        'message' => '',
                        'default_value' => 0,
                        'ui' => 0,
                        'ui_on_text' => '',
                        'ui_off_text' => '',
                    ),
                    array(
                        'key' => 'field_5bd76c82a3e67',
                        'label' => 'Display Setting',
                        'name' => 'nap_display_setting',
                        'type' => 'radio',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_5bd73352611c9',
                                    'operator' => '==',
                                    'value' => 'json',
                                ),
                            ),
                        ),
                        'wrapper' => array(
                            'width' => '51',
                            'class' => '',
                            'id' => '',
                        ),
                        'choices' => array(
                            'all_pages' => 'All Pages',
                            'specific_pages' => 'Specific Pages',
                        ),
                        'allow_null' => 0,
                        'other_choice' => 0,
                        'default_value' => '',
                        'layout' => 'horizontal',
                        'return_format' => 'value',
                        'save_other_choice' => 0,
                    ),
                    array(
                        'key' => 'field_5bd76bb4a3e63',
                        'label' => 'Display Pages',
                        'name' => 'nap_display_pages',
                        'type' => 'repeater',
                        'instructions' => 'Schema only included on the selected pages.',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_5bd73352611c9',
                                    'operator' => '==',
                                    'value' => 'json',
                                ),
                                array(
                                    'field' => 'field_5bd76c82a3e67',
                                    'operator' => '==',
                                    'value' => 'specific_pages',
                                ),
                            ),
                        ),
                        'wrapper' => array(
                            'width' => '50',
                            'class' => '',
                            'id' => '',
                        ),
                        'collapsed' => '',
                        'min' => 0,
                        'max' => 0,
                        'layout' => 'table',
                        'button_label' => 'Add Page',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_5bd76bd5a3e64',
                                'label' => 'Specified Page',
                                'name' => 'nap_specified_page',
                                'type' => 'post_object',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'post_type' => '',
                                'taxonomy' => '',
                                'allow_null' => 0,
                                'multiple' => 0,
                                'return_format' => 'id',
                                'ui' => 1,
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_5bd76bf6a3e65',
                        'label' => 'Display Pages URL String',
                        'name' => 'nap_display_pages_url_string',
                        'type' => 'repeater',
                        'instructions' => 'Schema only included on pages that contain the specified string in their URL.',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_5bd73352611c9',
                                    'operator' => '==',
                                    'value' => 'json',
                                ),
                                array(
                                    'field' => 'field_5bd76c82a3e67',
                                    'operator' => '==',
                                    'value' => 'specific_pages',
                                ),
                            ),
                        ),
                        'wrapper' => array(
                            'width' => '50',
                            'class' => '',
                            'id' => '',
                        ),
                        'collapsed' => '',
                        'min' => 0,
                        'max' => 0,
                        'layout' => 'table',
                        'button_label' => 'Add URL String',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_5bd76bf6a3e66',
                                'label' => 'Specified String',
                                'name' => 'nap_specified_string',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                                'maxlength' => '',
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_5bd76b69a3e61',
                        'label' => 'Exclude Pages',
                        'name' => 'nap_exclude_pages',
                        'type' => 'repeater',
                        'instructions' => 'Do not include the schema on the selected pages.',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_5bd73352611c9',
                                    'operator' => '==',
                                    'value' => 'json',
                                ),
                            ),
                        ),
                        'wrapper' => array(
                            'width' => '50',
                            'class' => '',
                            'id' => '',
                        ),
                        'collapsed' => '',
                        'min' => 0,
                        'max' => 0,
                        'layout' => 'table',
                        'button_label' => 'Add Page',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_5bd76b87a3e62',
                                'label' => 'Excluded Page',
                                'name' => 'nap_excluded_page',
                                'type' => 'post_object',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'post_type' => '',
                                'taxonomy' => '',
                                'allow_null' => 0,
                                'multiple' => 0,
                                'return_format' => 'id',
                                'ui' => 1,
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_5bd76e5fea41f',
                        'label' => 'Exclude Pages URL String',
                        'name' => 'nap_exclude_pages_url_string',
                        'type' => 'repeater',
                        'instructions' => 'Do not include the schema on pages that contain the specified string in their URL.',
                        'required' => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_5bd73352611c9',
                                    'operator' => '==',
                                    'value' => 'json',
                                ),
                            ),
                        ),
                        'wrapper' => array(
                            'width' => '50',
                            'class' => '',
                            'id' => '',
                        ),
                        'collapsed' => '',
                        'min' => 0,
                        'max' => 0,
                        'layout' => 'table',
                        'button_label' => 'Add URL String',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_5bd76e99ea420',
                                'label' => 'Excluded String',
                                'name' => 'nap_excluded_string',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                                'maxlength' => '',
                            ),
                        ),
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'ro-nap-builder',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => 1,
        'description' => '',
    ));
    
    endif;