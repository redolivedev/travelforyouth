<?php 
/*template name: RO Homepage*/
get_header(); 

nectar_page_header($post->ID);  

$nectar_options = get_nectar_theme_options(); 


?>
<?php if(have_posts()) : while(have_posts()) : the_post(); 
      $featured = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full')[0];
?>
<div class="hero panel" style="background-image: url('<?php echo $featured; ?>');">
    <div class="blackOpac"></div>
    <div class="btm">
        <div class="wrap">
            <div class="row">
                <div class="col-xs-12 col-md-10 col-md-offset-1">
                    <div class="box">
                        <h1 class="h1 title">Building a Generation of <span class="yellow">Change Makers</span></h1>
                    </div>
                </div>
            </div>
        </div>
     </div>   
</div>

<div class="over-text"> 
    <div class="wrap">  
        <div class="row">
            <div class="col-xs-12 col-md-9 col-md-offset-3 col-lg-7 col-lg-offset-5">
                <div class="box">
                    <p class="xl">Travel is powerful. Done right, it can sometimes be hard and uncomfortable, but it changes you. It creates empathy for all different kinds of people, cultures, and religions. It inspires gratitude, kills entitlement, and instills a deep responsibility to be a better global citizen. Traveling boosts your courage to do hard things and gives you the confidence to do big things.</p>
                    <a href="#" class="ro-button">Let's Go! Register for a Trip</a>   
                </div>
            </div>
        </div> 
    </div>
</div>

<div class="blackbg">
    <div class="wrap rel">
    <img class="about-img" src="https://travelforyouth.redolive.co/wp-content/uploads/2021/10/AboutUs.jpg">
        <div class="row about-pad">
            <div class="col-xs-12 col-md-6">
                <h2>Come Home Different, Come Home Better</h2>
                <div class="indent">
                    <p>Change through travel doesn’t magically happen because you left the country. TFY takes 	the Olympic gold in safe, intentional, life-changing travel. Every piece of each itinerary—every. teeny. tiny. detail—is carefully vetted and crafted to instill powerful change.</p>
                    <a href="#" class="ro-button">Learn More About Us</a>
                </div>
            </div>
        </div>
        <div class="row center-xs start-xl pad80">
            <div class="col-xs-12  col-md-8 ">
                <a data-fancybox="videos" class="videolightbox" href="https://player.vimeo.com/video/362638181?autoplay=1">
                    <div class="hovwrap">
                        <img src="https://travelforyouth.redolive.co/wp-content/uploads/2021/10/About_Us_vid_img.jpg">
                        <div id="play"></div>
                    </div>
                    <div class="play"></div>
                </a>
            </div>
        </div>
    </div>
</div>
<div class="blackbg extra-btm">
        <div class="factsContainer">
         <?php get_template_part( 'map' ); ?>
                <div class="factsContainer_sm">
                    <!-- <div class="fact first">
                         <h3 class="t-group">Two Week<br/>Trips</h3>
                        <div class="place none">
                        <p></p>
                        </div>
                    </div> -->
                    <?php get_template_part( 'locations' ); ?>
                    
                <div class="fact">
                    <div class="place none">
                        <p></p>
                    </div>
                </div>
            </div><!-- close SMl -->


            <!-- <div class="socialContainer">
                <h3>YO</h3>
            </div> -->

    </div> <!-- close Fast L -->
    <div class="mobile-locations">
        <?php get_template_part( 'map' ); ?>
        <div class="owl-carousel" id="mobilePlaces">
            <?php get_template_part( 'locations-mobile' ); ?>
        </div>
    </div>

</div><!-- blackbg -->
<div class="bg-white">
    <div class="wrap rel">
        <div class="heading-surround">
            <h2>We Turn Youth Into<br/>Lifelong Humanitarians</h2>
        </div>
        <div class="row">
            <div class="col-sx-12 col-md-6">
                <img class="up" src="https://travelforyouth.redolive.co/wp-content/uploads/2021/10/why_us_imag.jpg">
            </div>
            <div class="col-sx-12 col-md-6">
                <p class="lrg">
                    TFY takes a very different approach to LDS humanitarian trips. 
                    We are not building houses 	or schools, we are building youth. 
                    Instead of a one-time service project, we bring you up close to social causes that
                    need your attention and inspire a lifetime of involvement in humanitarian work and social impact. 
                </p>
                <div class="ceo">
                    <div class="quote">
                        <p>Our human family is in desperate need. With all we’ve been given, we each have an urgent responsibility to provide relief. 
                            <span class="author">Kimball & Erin Palmer - TFY Founders</span>
                        </p>
                    </div>    
                </div>
            </div>
        </div>
    </div>
    <div class="testimonial">
        <h2>Youth & Parent<br/>Experiences</h2>
        <div class="owl-carousel" id="test">
            <div class="item">
                <div class="row">
                    <div class="col-sx-12 col-lg">
                        <img src="https://travelforyouth.redolive.co/wp-content/uploads/2021/10/Testimonial_Left-1.jpg">
                    </div>
                    <div class="col-sx-12 col-lg f-width">
                        <p class="textimonial">My entire perspective of travel, service, the world, and the Lord's children was shifted due to my TFY experience. The lessons that I learned from the trip leaders and the local people about the Gospel, love, hard work, and culture are priceless and lessons that I'll carry forever.
                            <span class="author"><img class="circle" src="https://travelforyouth.redolive.co/wp-content/uploads/2021/10/testimonial-person.jpg">Em M.</span>
                        </p>
                    </div>
                    <div class="col-sx-12 col-lg">
                        <img class="down" src="https://travelforyouth.redolive.co/wp-content/uploads/2021/10/Testimonial_Right-1-scaled.jpg">
                    </div>
                </div>
            </div> 
            <div class="item">
                <div class="row">
                    <div class="col-sx-12 col-lg">
                        <img src="https://travelforyouth.redolive.co/wp-content/uploads/2021/10/Testimonial_Left-2.jpg">
                    </div>
                    <div class="col-sx-12 col-lg f-width">
                        <p class="textimonial">TFY gave my children amazing, life-changing experiences. We chose TFY because it offered a perfect blend of humanitarian, culture, & adventure. Kimball & Erin put in incredible amounts of work for these magical places, & provide a spiritually enriching environment. The organization, safety, & quality of these trips is impressive. I look forward to sending all of my children!
                            <span class="author"><img class="circle" src="https://travelforyouth.redolive.co/wp-content/uploads/2021/10/testimonial-person.jpg"> Lori T.</span>
                        </p>
                    </div>
                    <div class="col-sx-12 col-lg">
                        <img class="down" src="https://travelforyouth.redolive.co/wp-content/uploads/2021/10/Testimonial_Right-2-scaled.jpg">
                    </div>
                </div>
            </div>  
            <div class="item">
                <div class="row">
                    <div class="col-sx-12 col-lg">
                        <img src="https://travelforyouth.redolive.co/wp-content/uploads/2021/10/Testimonial_Left-3.jpg">
                    </div>
                    <div class="col-sx-12 col-lg f-width">
                        <p class="textimonial">This eye-opening experience taught me so much! It taught me who I am, who I want to be, what I’m grateful for. I found the world is actually quite small but in great need, and that I have the power to do something about it. I wouldn’t trade this experience for 	ANYTHING. Big, big thanks to Travel For Youth!
                            <span class="author"><img class="circle" src="https://travelforyouth.redolive.co/wp-content/uploads/2021/10/testimonial-person.jpg"> Brinley Durrant</span>
                        </p>
                    </div>
                    <div class="col-sx-12 col-lg">
                        <img class="down" src="https://travelforyouth.redolive.co/wp-content/uploads/2021/10/Testimonial_Right-3-scaled.jpg">
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="row">
                    <div class="col-sx-12 col-lg">
                        <img src="https://travelforyouth.redolive.co/wp-content/uploads/2021/10/Testimonial_Left-4.jpg">
                    </div>
                    <div class="col-sx-12 col-lg f-width">
                        <p class="textimonial">TFY has been life-changing for our two daughters to experience different world cultures & eye-opening economic conditions, perform humanitarian service, and develop healthy daily habits that have helped build their strong testimonies. Eternal thanks to TFY.
                        </p>
                    </div>
                    <div class="col-sx-12 col-lg">
                        <img class="down" src="https://travelforyouth.redolive.co/wp-content/uploads/2021/10/Testimonial_Right-4-scaled.jpg">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="instagram-area">
    <h2 class="h2">
        <span><b>@TFY</b>TRAVELFORYOUTH</span>  Feed Your<br/>Wanderlust
    </h2>
    <div class="wrap tiles">
        <div class="row middle-xs">
            <div class="col-xs-3">
                <div class="box">
                   <img src="https://travelforyouth.redolive.co/wp-content/uploads/2021/10/Insta_4.jpg">
                </div>
            </div>
            <div class="col-xs-6">
                <div class="box">
                    <div class="row bottom-xs btm-15">
                        <div class="col-xs-8">
                            <img src="https://travelforyouth.redolive.co/wp-content/uploads/2021/10/Insta_6.jpg">
                        </div>
                        <div class="col-xs-4">
                            <img src="https://travelforyouth.redolive.co/wp-content/uploads/2021/10/Insta_1.jpg">
                        </div>
                    </div>
                    <div class="row top-xs">
                        <div class="col-xs-4">
                            <img src="https://travelforyouth.redolive.co/wp-content/uploads/2021/10/Insta_3.jpg">
                        </div>
                        <div class="col-xs-8">
                            <img src="https://travelforyouth.redolive.co/wp-content/uploads/2021/10/Insta_5.jpg">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-3">
                <div class="box">
                    <img src="https://travelforyouth.redolive.co/wp-content/uploads/2021/10/Insta_2.jpg">
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo do_shortcode('[vc_row type="full_width_background" full_screen_row_position="middle" column_margin="default" column_direction="default" column_direction_tablet="default" column_direction_phone="default" bg_image="466" bg_position="left top" background_image_loading="default" bg_repeat="no-repeat" scene_position="center" top_padding="200" bottom_padding="200" top_padding_tablet="125" bottom_padding_tablet="125" top_padding_phone="95" bottom_padding_phone="95" text_color="dark" text_align="left" row_border_radius="none" row_border_radius_applies="bg" class="final-cta" enable_gradient="true" color_overlay="rgba(13,25,28,0.01)" color_overlay_2="#0d191c" overlay_strength="0.95" gradient_direction="top_to_bottom" shape_divider_position="bottom" bg_image_animation="none" shape_type=""][vc_column column_padding="no-extra-padding" column_padding_tablet="inherit" column_padding_phone="inherit" column_padding_position="all" column_element_spacing="default" background_color_opacity="1" background_hover_color_opacity="1" column_shadow="none" column_border_radius="none" column_link_target="_self" gradient_direction="left_to_right" overlay_strength="0.3" width="1/1" tablet_width_inherit="default" tablet_text_alignment="default" phone_text_alignment="default" bg_image_animation="none" border_type="simple" column_border_width="none" column_border_style="solid"][vc_row_inner column_margin="default" column_direction="default" column_direction_tablet="default" column_direction_phone="default" text_align="left" class="cta-type"][vc_column_inner column_padding="no-extra-padding" column_padding_tablet="inherit" column_padding_phone="inherit" column_padding_position="all" column_element_spacing="default" background_color_opacity="1" background_hover_color_opacity="1" column_shadow="none" column_border_radius="none" column_link_target="_self" gradient_direction="left_to_right" overlay_strength="0.3" width="1/12" tablet_width_inherit="default" bg_image_animation="none" enable_animation="true" animation="fade-in-from-left" border_type="simple" column_border_width="none" column_border_style="solid" offset="vc_hidden-sm vc_hidden-xs"][/vc_column_inner][vc_column_inner column_padding="no-extra-padding" column_padding_tablet="inherit" column_padding_phone="inherit" column_padding_position="all" column_element_spacing="default" background_color_opacity="1" background_hover_color_opacity="1" column_shadow="none" column_border_radius="none" column_link_target="_self" gradient_direction="left_to_right" overlay_strength="0.3" width="7/12" tablet_width_inherit="default" bg_image_animation="none" border_type="simple" column_border_width="none" column_border_style="solid"][vc_custom_heading text="Join the Travel Movement" font_container="tag:h2|text_align:left|color:%23ffffff" use_theme_fonts="yes"][/vc_column_inner][vc_column_inner column_padding="no-extra-padding" column_padding_tablet="inherit" column_padding_phone="inherit" column_padding_position="all" column_element_spacing="default" background_color_opacity="1" background_hover_color_opacity="1" column_shadow="none" column_border_radius="none" column_link_target="_self" gradient_direction="left_to_right" overlay_strength="0.3" width="4/12" tablet_width_inherit="default" bg_image_animation="none" border_type="simple" column_border_width="none" column_border_style="solid" offset="vc_hidden-sm vc_hidden-xs"][/vc_column_inner][/vc_row_inner][vc_row_inner column_margin="default" column_direction="default" column_direction_tablet="default" column_direction_phone="default" text_align="left" el_id="tfy-button"][vc_column_inner column_padding="no-extra-padding" column_padding_tablet="inherit" column_padding_phone="inherit" column_padding_position="all" column_element_spacing="default" background_color_opacity="1" background_hover_color_opacity="1" column_shadow="none" column_border_radius="none" column_link_target="_self" gradient_direction="left_to_right" overlay_strength="0.3" width="2/12" tablet_width_inherit="default" bg_image_animation="none" border_type="simple" column_border_width="none" column_border_style="solid" offset="vc_hidden-sm vc_hidden-xs"][/vc_column_inner][vc_column_inner column_padding="no-extra-padding" column_padding_tablet="inherit" column_padding_phone="inherit" column_padding_position="all" column_element_spacing="default" background_color_opacity="1" background_hover_color_opacity="1" font_color="#ffffff" column_shadow="none" column_border_radius="none" column_link_target="_self" gradient_direction="left_to_right" overlay_strength="0.3" width="6/12" tablet_width_inherit="default" bg_image_animation="none" enable_animation="true" animation="fade-in-from-bottom" border_type="simple" column_border_width="none" column_border_style="solid" delay="100"][vc_column_text css=".vc_custom_1633033101810{margin-top: -10px !important;}"]We are travelers, change-makers, adventurers, passport-stampers, global learners, and followers of Christ. Come with us this summer and start changing the world![/vc_column_text][nectar_btn size="jumbo" button_style="see-through-2" color_override="#e4e85c" hover_text_color_override="#ffffff" icon_family="none" text="Registration Coming Soon!" css_animation="fadeInUp" margin_top="20"][/vc_column_inner][vc_column_inner column_padding="no-extra-padding" column_padding_tablet="inherit" column_padding_phone="inherit" column_padding_position="all" column_element_spacing="default" background_color_opacity="1" background_hover_color_opacity="1" column_shadow="none" column_border_radius="none" column_link_target="_self" gradient_direction="left_to_right" overlay_strength="0.3" width="4/12" tablet_width_inherit="default" bg_image_animation="none" border_type="simple" column_border_width="none" column_border_style="solid" offset="vc_hidden-sm vc_hidden-xs"][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row]'); ?>
<?php endwhile; endif; ?>				
<?php get_footer(); ?>