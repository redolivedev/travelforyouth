<?php 
/*template name: RO Homepage*/
get_header(); 

nectar_page_header($post->ID);  

$nectar_options = get_nectar_theme_options(); 


?>
<?php if(have_posts()) : while(have_posts()) : the_post(); 
      $featured = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full')[0];
?>
<div id="motionPath" class="hero panel" style="background-image: url('<?php echo $featured; ?>');">
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
    <div class="path1 path"> 
        
        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 506 196.7" style="enable-background:new 0 0 506 196.7;" xml:space="preserve">
        <path id="EXPLORE" class="st0" d="M6.8,112v-1.3H1.9v-3.4h3.8V106H1.9v-3.3h4.7v-1.3H0.4V112H6.8z M16.3,112l1.9-3.3
            c0.3-0.6,0.6-1.2,0.6-1.2l0,0c0,0,0.2,0.5,0.5,1l0.1,0.3l0,0l1.9,3.3H23l-3.3-5.5l3.1-5.1h-1.7l-1.7,2.9c-0.3,0.6-0.6,1.3-0.6,1.3
            l0,0v-0.1c-0.1-0.2-0.4-0.7-0.6-1.2l-1.7-2.9h-1.7l3.1,5.1l-3.3,5.5h1.7C16.3,112.1,16.3,112,16.3,112z M33.1,112v-3.9h2.4
            c1.9,0,3.3-1.3,3.3-3.4c0-2-1.4-3.3-3.3-3.3h-3.9V112H33.1L33.1,112z M35.3,106.8h-2.1v-4.1h2.2c1.3,0,2,0.8,2,2
            C37.3,106,36.5,106.8,35.3,106.8z M53.7,112v-1.3H49v-9.3h-1.5V112H53.7z M66.6,112.2c3.1,0,5.4-2.4,5.4-5.5c0-3-2.4-5.4-5.4-5.4
            s-5.4,2.4-5.4,5.4C61.1,109.8,63.5,112.2,66.6,112.2z M66.6,110.8c-2.2,0-3.9-1.8-3.9-4.2c0-2.3,1.8-4.1,3.9-4.1
            c2.2,0,3.9,1.7,3.9,4.1C70.5,109,68.7,110.8,66.6,110.8z M82.4,112v-4.2h2.3l2.2,4.2h1.7l-2.2-4.1c-0.2-0.3-0.3-0.5-0.3-0.5l0,0
            c1.2-0.4,1.9-1.5,1.9-2.9s-0.7-2.5-1.8-2.9c-0.4-0.1-0.9-0.2-2-0.2H81V112H82.4L82.4,112z M84.5,106.5h-2.1v-3.8h1.7
            c0.8,0,1.2,0.1,1.5,0.2c0.5,0.3,0.8,0.9,0.8,1.6C86.4,105.8,85.6,106.5,84.5,106.5z M103.6,112v-1.3h-4.9v-3.4h3.8V106h-3.8v-3.3
            h4.7v-1.3h-6.1V112H103.6z"/>
            <g id="dot">
                <circle id="stay" class="st1" cx="52" cy="37" r="36"/>
       
                <circle id="oval" class="st2" cx="52.5" cy="37.5" r="7.5"/>
            </g>
        <path id="path1" class="st3" d="M522.3,209.9C489.3,174.7,389.3,111.9,283.4,73
            C183.5,36.3,88.5,35.3,52.5,37"/>
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

<div class="blackbg special-relative">
    <div class="wrap rel">
        <img class="about-img" src="<?php the_field('about_image'); ?>">
        <div class="row about-pad">
            <div class="path2 path" id="motionPath2"> 
                <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 1404 383" style="enable-background:new 0 0 1404 323;" xml:space="preserve">
                    <title>About Us</title>
                    <g id="assets">
                        <g id="Homepage-Design-Copy-9" transform="translate(-112.000000, -1578.000000)">
                            <g id="Group-15" transform="translate(112.000000, 1599.205412)">
                                <g id="dot2" transform="translate(0.000000, 189.794588)">
                                    <circle id="Oval" class="st0" cx="10" cy="10" r="5"/>
                                    <circle id="Oval_00000142868518208868632640000003839991568382687918_" class="st1" cx="10" cy="10" r="9.5"/>
                                </g>
                                
                                    <g class="copy" transform="translate(10.322000, 271.932588) rotate(-90.000000) translate(-10.322000, -271.932588) translate(-39.000000, 266.794588)">
                                    <path id="ABOUT" class="st2" d="M1.9,10.1l0.8-2.5h3.5L7,10.1h1.9L5.4,0.2H3.5L0,10.1H1.9z M5.7,6.1H3.1L4,3.4
                                        c0.2-0.6,0.4-1.5,0.4-1.5h0c0,0,0.2,1,0.4,1.5L5.7,6.1z M18,10.1c1.8,0,3.4-0.9,3.4-2.9c0-1-0.5-2-1.6-2.3v0
                                        C20.6,4.5,21,3.7,21,2.7c0-1.6-1.3-2.6-3.1-2.6h-3.6v9.9H18z M18,4.2h-1.8V1.7h1.7c0.8,0,1.3,0.5,1.3,1.2S18.8,4.2,18,4.2z
                                        M18.2,8.6h-1.9V5.7h1.9c0.9,0,1.4,0.6,1.4,1.4C19.6,8,19.1,8.6,18.2,8.6z M32.1,10.3c2.9,0,5.1-2.3,5.1-5.2
                                        C37.2,2.2,35,0,32.1,0S27,2.2,27,5.1C27,8,29.2,10.3,32.1,10.3z M32.1,8.6c-1.8,0-3.3-1.5-3.3-3.6c0-2,1.5-3.4,3.3-3.4
                                        s3.3,1.5,3.3,3.4C35.4,7.1,33.9,8.6,32.1,8.6z M47.2,10.3c2.4,0,4-1.5,4-3.7V0.2h-1.8v6.4c0,1.3-0.9,2.1-2.2,2.1
                                        c-1.3,0-2.2-0.8-2.2-2.1V0.2h-1.8v6.4C43.2,8.8,44.8,10.3,47.2,10.3z M61.7,10.1V1.7H65V0.2h-8.3v1.6h3.3v8.4H61.7z"/>
                                    <path id="US" class="st3" d="M82.1,10.3c2.4,0,4-1.5,4-3.7V0.2h-1.8v6.4c0,1.3-0.9,2.1-2.2,2.1c-1.3,0-2.2-0.8-2.2-2.1V0.2H78
                                        v6.4C78,8.8,79.7,10.3,82.1,10.3z M95.4,10.3c2.1,0,3.3-1.3,3.3-2.9c0-3.2-4.7-2.7-4.7-4.5c0-0.7,0.7-1.2,1.5-1.2
                                        c1.2,0,2.2,0.9,2.2,0.9l0.8-1.5c0,0-1-1.1-3-1.1c-1.9,0-3.3,1.2-3.3,2.9c0,3.1,4.7,2.7,4.7,4.5c0,0.8-0.7,1.2-1.4,1.2
                                        c-1.4,0-2.5-1.1-2.5-1.1l-1,1.4C91.9,8.9,93.2,10.3,95.4,10.3z"/>
                                </g>
                                <path id="path2" class="st4" d="M15,181.1C46.4,117.4,174.9,0,364.5,0s277.3,128,616.6,159.6c141.8,13.2,293.5-7.2,422-70.3"/>
                            </g>
                        </g>
                    </g>
                </svg>
            </div>
            <div class="col-xs-12 col-md-6 pad-l">
                <h2><?php the_field('about_h2'); ?></h2>
                <div class="indent">
                    <p><?php the_field('about_text'); ?></p>
                    <a href="<?php the_field('about_button_url'); ?>" class="ro-button hide-cursor"><?php the_field('about_button_text'); ?></a>
                </div>
            </div>
        </div>
        <div class="row center-xs start-xl pad80">
            <div class="col-xs-12  col-md-8 ">
                <div class="vidb">
                    <a href="<?php the_field('video_lightbox_url'); ?>" data-style="default" data-parent-hover="" data-font-style="p" data-color="default-accent-color" class="play_button_with_text large nectar_video_lightbox" data-fancybox="">
                        <span>
                            <span class="screen-reader-text">Play Video</span>
                            <span class="play">
                                <span class="inner-wrap">
                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="600px" height="800px" x="0px" y="0px" viewBox="0 0 600 800" enable-background="new 0 0 600 800" xml:space="preserve"><path fill="none" d="M0-1.79v800L600,395L0-1.79z"></path> </svg>
                                </span>
                            </span>
                        </span>
                    </a>
                    <a data-fancybox="videos" class="videolightbox" href="<?php the_field('video_lightbox_url'); ?>">
                        <div class="hovwrap">
                            <img src="<?php the_field('video_image_background'); ?>">
                        </div>
        
                    <!-- <div class="bigplay">
                            <svg class="pulse-svg" width="50px" height="50px" viewBox="0 0 50 50" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <circle class="circle first-circle"  cx="25" cy="25" r="25"></circle> -->
                            <!-- <circle class="circle second-circle" fill="#FF6347" cx="25" cy="25" r="25"></circle>
                            <circle class="circle third-circle" fill="#FF6347" cx="25" cy="25" r="25"></circle>
                            <circle class="circle" fill="#FF6347" cx="25" cy="25" r="25"></circle> -->
                            <!-- </svg>
                        </div>  -->
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="path3 path" id="motionPath3">
        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 955 761" style="enable-background:new 0 0 955 761;" xml:space="preserve">
            <g>
                <g id="Homepage-Design-Copy-9" transform="translate(71.000000, -2676.000000)">
                    <g id="Group-23" transform="translate(-69.736570, 2676.719609)">
                        <path id="path3" class="st0" d="M0,0c64,254,88.3,577.5,350.7,467.8c228-95.3,375.6-22.5,442.7,218.5"/>
                        <g id="dot3" transform="translate(782.985167, 676.280391)">
                            <circle id="Oval" class="st1" cx="10" cy="10" r="5"/>
                            <circle id="Oval_00" class="st2" cx="10" cy="10" r="9.5"/>
                        </g>
                    </g>
                </g>
            </g>
        </svg>
    </div>
</div>

<!-- SEE ORIGIONAL FIXED SCROLL FOR IMPORT HERE-->
<div class="blackbg extra-btm">
    <div class="mobile-locations">
        <?php get_template_part( 'map' ); ?>
        <div class="owl-carousel active" id="mobilePlaces">
            <?php while( have_rows('places') ): the_row(); 
             if( get_sub_field('week') == 'two' ):?>
                <div class="item">
                    <div class="m-fact">
                    <?php if(get_sub_field('place_page_url')): ?>
                        <a href="<?php the_sub_field('place_page_url'); ?>" class="place">
                    <?php else: ?>
                        <div class="place">
                    <?php endif; ?>
                            <span><?php the_sub_field('sm_yellow'); ?></span>
                            <h3><?php the_sub_field('name'); ?></h3>
                            <?php if(get_sub_field('highlight_flag')): ?>
                                <div class="flag"><?php the_sub_field('highlight_flag'); ?></div>
                            <?php endif; ?>    
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
                            <?php if(get_sub_field('place_page_url')): ?>
                                </a> <!-- Close Place linked-->
                            <?php else: ?>
                                </div><!-- Close Place div-->
                            <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>    
            <?php endwhile; ?>   
            <?php // get_template_part( 'locations-mobile' ); ?>
        </div>
        <div class="owl-carousel" id="mobilePlaces2">
            <?php while( have_rows('places') ): the_row(); 
            if( get_sub_field('week') == 'one' ):?>
                <div class="item">
                    <div class="m-fact">
                    <?php if(get_sub_field('place_page_url')): ?>
                        <a href="<?php the_sub_field('place_page_url'); ?>" class="place">
                    <?php else: ?>
                        <div class="place">
                    <?php endif; ?>
                            <span><?php the_sub_field('sm_yellow'); ?></span>
                            <h3><?php the_sub_field('name'); ?></h3>
                            <?php if(get_sub_field('highlight_flag')): ?>
						        <div class="flag"><?php the_sub_field('highlight_flag'); ?></div>
					        <?php endif; ?>    
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
                        <?php if(get_sub_field('place_page_url')): ?>
                            </a> <!-- Close Place linked-->
                        <?php else: ?>
                            </div><!-- Close Place div-->
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>    
            <?php endwhile; ?>   
            <?php // get_template_part( 'locations-mobile' ); ?>
        </div>
    </div>


        <div class="dome" href="javascript:void(0);" onclick="togglePopup('61c0df390fcf743752cb40d5')">
            <div class="dome-text">
                <img src="https://travelforyouth.org/wp-content/uploads/2021/12/Quiz_icon.png" alt="TFY Youth Travel">
                <h2><span>Find Your Perfect TFY Trip</span> Take our Trip Finder Quiz</h2>
            </div>
            <div>
                <div id='61c0df390fcf743752cb40d5' data-title='Trip Quiz' data-embedCookieDays='10' data-embedScheduling='false' data-embedTimed='true' data-embedExit='false' data-embedTimeFormat='0' data-embedTimeValue='5' data-embedBorderRadius='0' data-embedFontSize='12' data-textcolor='#ffffff' data-bgcolor='#fb5f66' data-prop='outgrow-p' data-type='outgrow-b' data-url='https://travelforyouth.outgrow.us/61c0df390fcf743752cb40d5?q=1' data-text='Get Started'>
                </div>
                <script src='//dyv6f9ner1ir9.cloudfront.net/assets/js/nploader.js'></script>
                <script>initIframe('61c0df390fcf743752cb40d5');</script>
            </div>
            <style>.outgrow-l,.outgrow-b {display: none !important}</style>
        </div><!-- dome -->
    </div>
</div>
</div><!-- blackbg -->

<div class="bg-white">
    <div class="wrap">
        <div class="heading-surround">
            <div class="path4 path" id="motionPath4">
                <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 1285 793" style="enable-background:new 0 0 1285 793;" xml:space="preserve">
                    <g id="Homepage-Design-Copy-9" transform="translate(-292.000000, -4680.000000)">
                        <g id="Group-24" transform="translate(292.000000, 4681.447835)">
                            <path id="path4" class="st0" d="M9,680.6c46.8-193.2,206.4-297.7,479-313.5c257-14.9,531.3-108.8,672.7-273.5
                                c32.8-38.2,73.7-69.4,122.7-93.5"/>
                            
                                <g id="Why-Us" transform="translate(9.620000, 752.114665) rotate(-90.000000) translate(-9.620000, -752.114665) translate(-29.497500, 746.609665)">
                                <path id="WHY" class="st1" d="M4.9,10.8l1.6-6.3c0.2-0.7,0.3-1.4,0.3-1.4h0c0,0,0.1,0.7,0.3,1.4l1.6,6.3H11l2.7-10.6h-2L10,7.5
                                    C9.8,8.1,9.8,8.7,9.8,8.7h0c0,0,0-0.5-0.2-1.1L7.7,0.2H6L4.1,7.5C3.9,8.2,3.9,8.7,3.9,8.7h0c0,0,0-0.5-0.2-1.1L2,0.2H0l2.7,10.6
                                    H4.9z M21.6,10.8V6.4h4.9v4.5h1.9V0.2h-1.9v4.5h-4.9V0.2h-1.9v10.6H21.6z M39.5,10.8V6.4l3.5-6.2h-2.2l-1.7,3.1
                                    c-0.3,0.7-0.6,1.4-0.6,1.4h0c0,0-0.3-0.8-0.6-1.4l-1.7-3.1h-2.2l3.5,6.2v4.5H39.5z"/>
                                <path id="US" class="st2" d="M60.8,11c2.6,0,4.3-1.6,4.3-4V0.2h-1.9V7c0,1.4-0.9,2.2-2.4,2.2S58.4,8.4,58.4,7V0.2h-1.9V7
                                    C56.5,9.4,58.2,11,60.8,11z M74.7,11c2.2,0,3.5-1.4,3.5-3.1c0-3.4-5-2.9-5-4.8c0-0.8,0.7-1.3,1.6-1.3c1.3,0,2.4,0.9,2.4,0.9
                                    L78,1.1l0,0C77.8,1,76.8,0,74.8,0c-2,0-3.6,1.3-3.6,3.1c0,3.3,5,2.9,5,4.8c0,0.9-0.7,1.3-1.5,1.3c-1.5,0-2.7-1.1-2.7-1.1l-1,1.5
                                    c0,0,0,0,0,0l0.1,0.1C71.5,10,72.7,11,74.7,11z"/>
                            </g>
                            <g id="dot4" transform="translate(0.000000, 685.552165)">
                                <circle id="Oval" class="st3" cx="10" cy="10" r="5"/>
                                <circle id="Oval_00000102503924244504823150000002747920199179435672_" class="st4" cx="10" cy="10" r="9.5"/>
                            </g>
                        </g>
                    </g>
                </svg>            
            </div>
            <h2 class="hum-title"><?php the_field('mission_h2'); ?></h2>
        </div>
        <div class="row pad-btm">
            <div class="col-sx-12 col-md-6 last-xs first-md">
                <img class="up" src="<?php the_field('mission_image'); ?>">
                <div class="ceo ceo-img">
                    <div class="quote">
                        <p><?php the_field('ceo_quote'); ?>
                            <span class="author"><?php the_field('ceo_author'); ?></span>
                        </p>
                    </div>    
                </div>
            </div>
            <div class="col-sx-12 col-md-6 first-xs last-md">
                <div class="lrg">
                <?php the_field('mission_text'); ?> 
                    <a href="<?php the_field('mission_button_url'); ?>" class="ro-button hide-cursor"><?php the_field('mission_button_text'); ?></a>
                </div>
                <div class="ceo">
                    <div class="quote">
                        <p><?php the_field('ceo_quote'); ?>
                            <span class="author"><?php the_field('ceo_author'); ?></span>
                        </p>
                    </div>    
                </div>
            </div>
        </div>
    </div>
    <div class="testimonial">
        <div class="heading-wrap">
            <div class="path5 path" id="motionPath5">
                <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 905 355" style="enable-background:new 0 0 905 355;" xml:space="preserve">
                    <g id="Homepage-Design-Copy-9" transform="translate(-707.000000, -6387.000000)">
                        <g transform="translate(707.000000, 6388.134069)">
                            
                            <g id="dot5" transform="translate(10.000000, 343.865931) scale(-1, 1) translate(-10.000000, -343.865931) translate(0.000000, 333.865931)">
                                <circle id="Oval" class="st0" cx="10" cy="10" r="5"/>
                                <circle id="Oval_00000066485837157968493630000008426532003153735301_" class="st1" cx="10" cy="10" r="9.5"/>
                            </g>
                            <path id="path5" class="st2" d="M903.6,0C720.4,47.1,499.7,67.7,328,50.1C89.2,25.6-16.6,119.9,10.5,332.9"/>
                        </g>
                    </g>
                </svg>
            </div> 
            <h2><?php the_field('testimonial_heading'); ?></h2>
        </div>
        <div class="owl-carousel" id="test">
            <?php while( have_rows('testimonials') ): the_row(); ?>
                <div class="item">
                    <div class="row">
                        <div class="col-sx-12 col-md-3 col-lg last-xs first-md">
                            <img src="<?php the_sub_field('img_1'); ?>">
                        </div>
                        <div class="col-sx-12 col-md-6 col-lg f-width">
                            <p class="textimonial"><?php the_sub_field('testimonial'); ?>
                                <span class="author"><img class="circle" src="<?php the_sub_field('author_img'); ?>"><?php the_sub_field('author_name'); ?></span>
                            </p>
                        </div>
                        <div class="col-sx-12 col-md-3 last-xs col-lg">
                            <img class="down" src="<?php the_sub_field('img_2'); ?>">
                        </div>
                    </div>
                </div> 
            <?php endwhile; ?>   
        </div>
    </div>
</div>
<div class="instagram-area">
    <div class="insta-wrap">
        <div class="path6 path" id="motionPath6">
            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 988 1000" style="enable-background:new 0 0 988 1000;" xml:space="preserve">
                <g id="Homepage-Design-Copy-9" transform="translate(150.000000, -6871.000000)">
                    <g id="Group-28" transform="translate(-148.601562, 6872.500000)">
                        <path id="path6" class="st2" d="M0,0c204.3,40.6,255.9,344.6,273.6,428c62.1,293.4,284,295.5,429,334.5
                            c96.7,26,152,72.9,166,140.7"/>
                        
                        <g id="dot6" transform="translate(868.601562, 914.500000) scale(1, -1) translate(-868.601562, -914.500000) translate(858.601562, 904.500000)">
                            <circle id="Oval" class="st3" cx="10" cy="10" r="5"/>
                            <circle id="Oval_00000101815034747927587810000009340574384191657398_" class="st4" cx="10" cy="10" r="9.5"/>
                        </g>
                    </g>
                </g>
            </svg>
        </div>
        <h2 class="h2">
            <?php the_field('instagram_title'); ?>
        </h2>
   </div>
    <div class="wrap tiles">
        <div class="row middle-xs">
            <div class="col-xs-3">
                <a href="https://www.instagram.com/p/CR7kO0Cpg_i/" target="_blank" class="box">
                   <img src="https://travelforyouth.org/wp-content/uploads/2021/10/Insta_4.jpg">
                </a>
            </div>
            <div class="col-xs-6">
                <div class="box">
                    <div class="row bottom-xs btm-15">
                        <div class="col-xs-7">
                            <a href="https://www.instagram.com/p/CQzNixOpC91/" target="_blank"><img src="https://travelforyouth.org/wp-content/uploads/2021/10/Insta_6.jpg"></a>
                        </div>
                        <div class="col-xs-5">
                            <a href="https://www.instagram.com/p/CQhZdWZptcF/" target="_blank"><img src="https://travelforyouth.org/wp-content/uploads/2021/10/Insta_1.jpg"></a>
                        </div>
                    </div>
                    <div class="row top-xs">
                        <div class="col-xs-5">
                            <a href="https://www.instagram.com/p/CRP7y-gJ9NV/" target="_blank"><img src="https://travelforyouth.org/wp-content/uploads/2021/10/Insta_3.jpg"></a>
                        </div>
                        <div class="col-xs-7">
                            <a href="https://www.instagram.com/p/CQU27SkJ9vV/" target="_blank"><img src="https://travelforyouth.org/wp-content/uploads/2021/10/Insta_5.jpg"></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-3">
                <a href="https://www.instagram.com/p/CQZIVlZJeTf/" target="_blank" class="box">
                    <img src="https://travelforyouth.org/wp-content/uploads/2021/10/Insta_2.jpg">
                </a>
            </div>
        </div>
    </div>
</div>
<div class="btm-cta">
    <div class="path7 path" id="motionPath7">
        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 1446 664" style="enable-background:new 0 0 1446 664;" xml:space="preserve">
            <g id="Homepage-Design-Copy-9" transform="translate(-161.000000, -8609.000000)">
                <g id="Group-29" transform="translate(161.000000, 8610.000000)">
                    <g id="dot7" transform="translate(10.000000, 653.000000) scale(1, -1) translate(-10.000000, -653.000000) translate(0.000000, 643.000000)">
                        <circle id="Oval" class="st0" cx="10" cy="10" r="5"/>
                        <circle id="Oval_00000121252498402912549620000014521032314177114537_" class="st1" cx="10" cy="10" r="9.5"/>
                    </g>
                    <path id="path7" class="st2" d="M1444.9,0c-102.9,125.9-232.4,197.1-388.5,213.7c-329.3,34.9-500.1,20.3-662.8,65.3
                        C180.5,338,5.2,462.2,9,621.5c0.6,9.3,1,16.2,1.3,20.9"/>
                </g>
            </g>
        </svg>                    
    </div>
    <?php  $short = get_field('paste_footer_global_shortcode');  echo do_shortcode($short); ?>
</div>
<?php endwhile; endif; ?>				
<?php get_footer(); ?>