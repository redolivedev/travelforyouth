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
    <div class="wrap">
        <div class="row">
            <div class="col-xs-12 col-md-10 col-md-offset-1">
                <div class="box">
                    <h1 class="h1">Building a Generation of <span class="yellow">Change Makers</span></h1>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="panel">
    <div class="over-text"> 
        <div class="wrap">   
            <div class="row">
                <div class="col-xs-7 col-xs-offset-5">
                    <div class="box">
                        <p class="xl">Travel is powerful. Done right, it can sometimes be hard and uncomfortable, but it changes us.
                            It creates empathy for all different kinds of people, cultures, and religions. Its inspires gratitude, kills
                            entitlement, and instills a deep responsibolity to be a better global citizen. Traveling boosts our courage
                            to do hard thinks and gives us the confidence to do big things.</p>
                        <a href="#" class="ro-button">Let's Go! Register for a Trip</a>   
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="blackbg">
    <div class="wrap">
        <div class="row pad80">
            <div class="col-xs-6">
                <h2>After your Trip you will never Be the Same</h2>
                <div class="indent">
                    <p>powerful. Done right, it can sometimes be hard and uncomfortable, but it changes us.
                        It creates empathy for all different kinds of people, cultures, and religions. Its inspires gratitude, kills
                        entitlement, and instills a deep re
                    </p>
                    <a href="#" class="ro-button">Learn More About Us</a>
                </div>
            </div>
        </div>
        <div class="row pad80">
            <div class="col-xs-8">
                <div class="videolightbox">
                    <img src="https://travelforyouth.redolive.co/wp-content/uploads/2021/10/About_Us_vid_img.jpg">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="wrap">
    <div class="row pad80">
        <div class="col-xs-8 rel">
            <div class="gallery">
                <ul class="cards">
                    <li>0</li>
                    <li>1</li>
                    <li>2</li>
                    <li>3</li>
                    <li>4</li>
                    <li>5</li>
                    <li>6</li>
                    <li>7</li>
                    <li>8</li>
                    <li>9</li>
                    <li>10</li>
                </ul>
                <div class="actions">
                    <button class="prev">Prev</button>
                    <button class="next">Next</button>
                </div>
            </div>
        </div>
    </div>
</div> 

<?php endwhile; endif; ?>				
<?php get_footer(); ?>