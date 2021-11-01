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
        <svg width="447px" height="159px" viewBox="0 0 447 159" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
            <title>Hero Path</title>
            <defs>
                <circle id="path-1" cx="36" cy="36" r="36"></circle>
                <mask id="mask-2" maskContentUnits="userSpaceOnUse" maskUnits="objectBoundingBox" x="-1" y="-1" width="74" height="74">
                    <rect x="-1" y="-1" width="74" height="74" fill="white"></rect>
                    <use xlink:href="#path-1" fill="black"></use>
                </mask>
            </defs>
            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <g transform="translate(-993.000000, -322.000000)">
                    <g transform="translate(993.374794, 323.000000)">
                        <g id="dot" transform="translate(15.625206, 0.000000)">
                            <use id="Oval" stroke="#8290A0" mask="url(#mask-2)" stroke-width="2" opacity="0.900140807" stroke-linejoin="bevel" stroke-dasharray="8" xlink:href="#path-1"></use>
                            <circle id="Oval" fill="#E4E85C" cx="36.5" cy="36.5" r="7.5"></circle>
                        </g>
                        <path d="M6.405,111 L6.405,109.71 L1.485,109.71 L1.485,106.305 L5.28,106.305 L5.28,105.015 L1.485,105.015 L1.485,101.7 L6.15,101.7 L6.15,100.41 L5.68434189e-14,100.41 L5.68434189e-14,111 L6.405,111 Z M15.872916,111 L17.792916,107.745 C18.137916,107.16 18.422916,106.53 18.422916,106.53 L18.452916,106.53 C18.452916,106.53 18.695365,106.981837 18.9818956,107.488776 L19.127916,107.745 L19.127916,107.745 L21.047916,111 L22.727916,111 L19.382916,105.54 L22.517916,100.41 L20.807916,100.41 L19.142916,103.275 C18.797916,103.86 18.497916,104.535 18.497916,104.535 L18.467916,104.535 L18.429396,104.45868 C18.330516,104.2638 18.056916,103.731 17.792916,103.275 L16.127916,100.41 L14.417916,100.41 L17.552916,105.54 L14.207916,111 L15.872916,111 Z M32.705832,111 L32.705832,107.085 L35.090832,107.085 C37.025832,107.085 38.420832,105.735 38.420832,103.725 C38.420832,101.715 37.025832,100.41 35.090832,100.41 L31.220832,100.41 L31.220832,111 L32.705832,111 Z M34.850832,105.795 L32.705832,105.795 L32.705832,101.7 L34.865832,101.7 C36.125832,101.7 36.905832,102.45 36.905832,103.725 C36.905832,105 36.125832,105.795 34.850832,105.795 Z M53.273748,111 L53.273748,109.71 L48.533748,109.71 L48.533748,100.41 L47.048748,100.41 L47.048748,111 L53.273748,111 Z M66.161664,111.18 C69.221664,111.18 71.591664,108.75 71.591664,105.63 C71.591664,102.585 69.221664,100.23 66.161664,100.23 C63.101664,100.23 60.716664,102.585 60.716664,105.63 C60.716664,108.75 63.101664,111.18 66.161664,111.18 Z M66.161664,109.815 C64.001664,109.815 62.246664,108.015 62.246664,105.63 C62.246664,103.32 64.001664,101.58 66.161664,101.58 C68.321664,101.58 70.061664,103.32 70.061664,105.63 C70.061664,108.015 68.321664,109.815 66.161664,109.815 Z M81.95958,111 L81.95958,106.785 L84.22458,106.785 L86.45958,111 L88.13958,111 L85.88958,106.89 C85.69458,106.545 85.57458,106.41 85.57458,106.41 L85.57458,106.38 C86.72958,106.005 87.47958,104.865 87.47958,103.515 C87.47958,102.12 86.77458,101.055 85.69458,100.65 C85.28958,100.5 84.82458,100.41 83.71458,100.41 L80.47458,100.41 L80.47458,111 L81.95958,111 Z M84.08958,105.495 L81.95958,105.495 L81.95958,101.7 L83.66958,101.7 C84.49458,101.7 84.83958,101.79 85.12458,101.94 C85.66458,102.24 85.96458,102.795 85.96458,103.575 C85.96458,104.76 85.24458,105.495 84.08958,105.495 Z M103.217496,111 L103.217496,109.71 L98.297496,109.71 L98.297496,106.305 L102.092496,106.305 L102.092496,105.015 L98.297496,105.015 L98.297496,101.7 L102.962496,101.7 L102.962496,100.41 L96.812496,100.41 L96.812496,111 L103.217496,111 Z" id="EXPLORE" fill="#EFEFF1" fill-rule="nonzero"></path>
                        <path d="M505.623378,195.698111 C388.560825,112.999161 244.602591,42.3076609 87.625206,35.300679" id="path1" stroke="#8290A0" opacity="0.900208939" stroke-dasharray="8"></path>
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
    <div class="path2 path" id="motionPath2"> 
        <svg viewBox="0 0 1404 323" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
            <title>About Us</title>
            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <g>
                    <g>
                        <g id="dot2" transform="translate(0.000000, 189.794588)">
                            <circle id="Oval" fill="#E4E85C" cx="10" cy="10" r="5"></circle>
                            <circle id="Oval" stroke="#E4E85C" opacity="0.450558685" cx="10" cy="10" r="9.5"></circle>
                        </g>
                        <g transform="translate(10.322000, 271.932588) rotate(-90.000000) translate(-10.322000, -271.932588) translate(-39.000000, 266.794588)" fill-rule="nonzero">
                            <path d="M1.862,10.108 L2.702,7.56 L6.188,7.56 L7.028,10.108 L8.89,10.108 L5.39,0.168 L3.5,0.168 L0,10.108 L1.862,10.108 Z M5.74,6.09 L3.136,6.09 L4.018,3.43 C4.214,2.87 4.438,1.89 4.438,1.89 L4.466,1.89 C4.466,1.89 4.676,2.87 4.858,3.43 L5.74,6.09 Z M18.012,10.108 C19.846,10.108 21.442,9.212 21.442,7.224 C21.442,6.216 20.924,5.208 19.832,4.886 L19.832,4.858 C20.588,4.452 21.036,3.654 21.036,2.73 C21.036,1.092 19.762,0.168 17.97,0.168 L14.414,0.168 L14.414,10.108 L18.012,10.108 Z M17.97,4.228 L16.22,4.228 L16.22,1.722 L17.942,1.722 C18.782,1.722 19.202,2.24 19.202,2.954 C19.202,3.668 18.754,4.228 17.97,4.228 Z M18.152,8.554 L16.22,8.554 L16.22,5.684 L18.152,5.684 C19.062,5.684 19.594,6.286 19.594,7.112 C19.594,7.952 19.076,8.554 18.152,8.554 Z M32.104,10.276 C35.03,10.276 37.242,7.994 37.242,5.068 C37.242,2.226 35.03,0 32.104,0 C29.178,0 26.966,2.226 26.966,5.068 C26.966,7.994 29.178,10.276 32.104,10.276 Z M32.104,8.624 C30.284,8.624 28.828,7.112 28.828,5.068 C28.828,3.108 30.284,1.652 32.104,1.652 C33.924,1.652 35.38,3.108 35.38,5.068 C35.38,7.112 33.924,8.624 32.104,8.624 Z M47.204,10.276 C49.598,10.276 51.208,8.764 51.208,6.538 L51.208,0.168 L49.402,0.168 L49.402,6.524 C49.402,7.868 48.52,8.624 47.19,8.624 C45.86,8.624 44.992,7.868 44.992,6.538 L44.992,0.168 L43.186,0.168 L43.186,6.538 C43.186,8.764 44.796,10.276 47.204,10.276 Z M61.702,10.108 L61.702,1.722 L64.964,1.722 L64.964,0.168 L56.634,0.168 L56.634,1.722 L59.896,1.722 L59.896,10.108 L61.702,10.108 Z" fill="#EAEAEA"></path>
                            <path d="M82.06,10.276 C84.454,10.276 86.064,8.764 86.064,6.538 L86.064,0.168 L84.258,0.168 L84.258,6.524 C84.258,7.868 83.376,8.624 82.046,8.624 C80.716,8.624 79.848,7.868 79.848,6.538 L79.848,0.168 L78.042,0.168 L78.042,6.538 C78.042,8.764 79.652,10.276 82.06,10.276 Z M95.368,10.276 C97.44,10.276 98.644,8.946 98.644,7.364 C98.644,4.186 93.968,4.62 93.968,2.856 C93.968,2.156 94.626,1.666 95.466,1.666 C96.712,1.666 97.664,2.534 97.664,2.534 L98.448,1.064 C98.448,1.064 97.44,0 95.48,0 C93.576,0 92.148,1.232 92.148,2.884 C92.148,5.936 96.838,5.628 96.838,7.406 C96.838,8.218 96.152,8.61 95.396,8.61 C93.996,8.61 92.918,7.56 92.918,7.56 L91.938,8.918 C91.938,8.918 93.156,10.276 95.368,10.276 Z" fill="#E4E85C"></path>
                        </g>
                        <path id="path2" d="M15,181.095267 C46.4070953,117.448379 174.933105,0 364.5,0 C554.066895,0 641.797069,127.961546 981.118783,159.645129 C1122.9211,172.885676 1274.58229,152.4624 1403.098,89.3022815" stroke="#8290A0" opacity="0.900208939" stroke-dasharray="8"></path>
                    </g>
                </g>
            </g>
        </svg>
    </div>
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
                <a data-fancybox="videos" class="videolightbox" href="<?php the_field('video_lightbox_url'); ?>">
                    <div class="hovwrap">
                        <img src="<?php the_field('video_image_background'); ?>">
                        <div id="play"></div>
                    </div>
                    <div class="play"></div>
                </a>
            </div>
        </div>
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
</div><!-- blackbg -->

<div class="bg-white">
    <div class="wrap rel">
        <div class="heading-surround">
            <h2><?php the_field('mission_h2'); ?></h2>
        </div>
        <div class="row">
            <div class="col-sx-12 col-md-6">
                <img class="up" src="<?php the_field('mission_image'); ?>">
            </div>
            <div class="col-sx-12 col-md-6">
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
        <h2><?php the_field('testimonial_heading'); ?></h2>
        <div class="owl-carousel" id="test">
            <?php while( have_rows('testimonials') ): the_row(); ?>
                <div class="item">
                    <div class="row">
                        <div class="col-sx-12 col-lg">
                            <img src="<?php the_sub_field('img_1'); ?>">
                        </div>
                        <div class="col-sx-12 col-lg f-width">
                            <p class="textimonial"><?php the_sub_field('testimonial'); ?>
                                <span class="author"><img class="circle" src="<?php the_sub_field('author_img'); ?>"><?php the_sub_field('author_name'); ?></span>
                            </p>
                        </div>
                        <div class="col-sx-12 col-lg">
                            <img class="down" src="<?php the_sub_field('img_2'); ?>">
                        </div>
                    </div>
                </div> 
            <?php endwhile; ?>   
        </div>
    </div>
</div>
<div class="instagram-area">
    <h2 class="h2">
        <?php the_field('instagram_title'); ?>
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
                        <div class="col-xs-7">
                            <img src="https://travelforyouth.redolive.co/wp-content/uploads/2021/10/Insta_6.jpg">
                        </div>
                        <div class="col-xs-5">
                            <img src="https://travelforyouth.redolive.co/wp-content/uploads/2021/10/Insta_1.jpg">
                        </div>
                    </div>
                    <div class="row top-xs">
                        <div class="col-xs-5">
                            <img src="https://travelforyouth.redolive.co/wp-content/uploads/2021/10/Insta_3.jpg">
                        </div>
                        <div class="col-xs-7">
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