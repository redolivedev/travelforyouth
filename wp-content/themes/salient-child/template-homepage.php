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
                        <h1 class="h1 title"><?php the_field('h1'); ?><span class="yellow"> <?php the_field('h1_yellow'); ?></span></h1>
                    </div>
                </div>
            </div>
        </div>
     </div> 
    <div class="path1"> 
        <svg width="548px" height="240px" viewBox="0 0 548 240" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
            <title>Hero Path</title>
            <defs>
                <circle id="path-1" cx="36" cy="36" r="36"></circle>
                <mask id="mask-2" maskContentUnits="userSpaceOnUse" maskUnits="objectBoundingBox" x="-1" y="-1" width="74" height="74">
                    <rect x="-1" y="-1" width="74" height="74" fill="white"></rect>
                    <use xlink:href="#path-1" fill="black"></use>
                </mask>
            </defs>
            <g id="motionPath" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <g>
                    <g>
                        <g id="dot">
                            <use id="Oval" stroke="#8290A0" mask="url(#mask-2)" stroke-width="2" opacity="0.900140807" stroke-linejoin="bevel" stroke-dasharray="8" xlink:href="#path-1"></use>
                            <circle id="Oval" fill="#E4E85C" cx="36.5" cy="36.5" r="7.5"></circle>
                        </g>
                        <path id="path1" d="M546.188091,238.479938 C511.455722,210.987288 473.68684,183.848154 433.34662,158.794874 C327.738543,93.207014 204.507583,41.9147262 72,36" id="Path-Copy-2" stroke="#8290A0" opacity="0.900208939" stroke-dasharray="8"></path>
                    </g>
                </g>
            </g>
        </svg>
    </div>
</div>

<div class="over-text"> 
    <div class="wrap">  
        <div class="row">
            <div class="col-xs-12 col-md-9 col-md-offset-3 col-lg-7 col-lg-offset-5">
                <div class="box">
                    <p class="xl"><?php the_field('over_text'); ?></p>
                    <a href="<?php the_field('button_url'); ?>" class="ro-button hide-cursor"><?php the_field('button_text'); ?></a>   
                </div>
            </div>
        </div> 
    </div>
</div>

<div class="blackbg">
    <div class="wrap rel">
    <img class="about-img" src="<?php the_field('about_image'); ?>">
        <div class="row about-pad">
            <div class="col-xs-12 col-md-6">
                <h2><?php the_field('about_h2'); ?></h2>
                <div class="indent">
                    <p><?php the_field('about_text'); ?></p>
                    <a href="<?php the_field('about_button_url'); ?>" class="ro-button hide-cursor"><?php the_field('about_button_text'); ?></a>
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
                    <?php while( have_rows('places') ): the_row(); ?>
                        <div class="fact">
                            <div class="place">
                                <span><?php the_sub_field('sm_yellow'); ?></span>
                                <h3><?php the_sub_field('name'); ?></h3>
                                <div class="place-img">
                                    <img src="<?php the_sub_field('place_image'); ?>">
                                </div>
                                <div class="hover-text">
                                    <p class="yellow">DATES</p>
                                    <p><?php the_sub_field('dates'); ?></p>
                                    <p class="yellow">length</p>
                                    <p><?php the_sub_field('length'); ?></p>
                                    <p class="yellow">Social Cause</p>
                                    <p><?php the_sub_field('social_cause'); ?></p>
                                    <p class="yellow">Highlights</p>
                                    <p><?php the_sub_field('highlights'); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>   
                    <?php // get_template_part( 'locations' ); ?>
                    
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
            <?php while( have_rows('places') ): the_row(); ?>
                <div class="item">
                    <div class="m-fact">
                        <div class="place">
                            <span><?php the_sub_field('sm_yellow'); ?></span>
                            <h3><?php the_sub_field('name'); ?></h3>
                            <div class="place-img">
                                <img src="<?php the_sub_field('place_image'); ?>">
                            </div>
                            <div class="hover-text">
                                <p class="yellow">DATES</p>
                                <p><?php the_sub_field('dates'); ?></p>
                                <p class="yellow">length</p>
                                <p><?php the_sub_field('length'); ?></p>
                                <p class="yellow"><?php the_sub_field('social_cause_title'); ?></p>
                                <p><?php the_sub_field('social_cause'); ?></p>
                                <p class="yellow">Highlights</p>
                                <p><?php the_sub_field('highlights'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>   
            <?php // get_template_part( 'locations-mobile' ); ?>
        </div>
    </div>

</div><!-- blackbg -->
<div class="bg-white">
    <div class="wrap rel">
        <div class="heading-surround">
            <h2><?php the_field('mission_h2'); ?></h2>
        </div>
        <div class="row">
            <div class="col-sx-12 col-md-6">
                <img class="up" src="https://travelforyouth.redolive.co/wp-content/uploads/2021/10/why_us_imag.jpg">
            </div>
            <div class="col-sx-12 col-md-6">
                <p class="lrg">
                <?php the_field('mission_text'); ?> 
                <a href="<?php the_field('mission_button_url'); ?>" class="ro-button hide-cursor"><?php the_field('mission_button_text'); ?></a>
                </p>
                <div class="ceo">
                    <div class="quote">
                        <p><?php the_field('ceo_quote'); ?> . 
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
<?php  $short = get_field('paste_footer_global_shortcode');  echo do_shortcode($short); ?>
<?php endwhile; endif; ?>				
<?php get_footer(); ?>