<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title><?php echo get_bloginfo('name'); ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0 " />
    <meta name="format-detection" content="telephone=no"/>
    <link href='https://fonts.googleapis.com/css?family=Nunito:400,300,700' rel='stylesheet' type='text/css'>
    <style type="text/css">
      body {
      -webkit-text-size-adjust: 100% !important;
      -ms-text-size-adjust: 100% !important;
      -webkit-font-smoothing: antialiased !important;
      }
      img {
      border: 0 !important;
      outline: none !important;
      }
      p {
      Margin: 0px !important;
      padding: 0px !important;
      }
      table {
      border-collapse: collapse;
      mso-table-lspace: 0px;
      mso-table-rspace: 0px;
      }
      td, a, span {
      border-collapse: collapse;
      mso-line-height-rule: exactly;
      }
      .ExternalClass * {
      line-height: 100%;
      }
      span.MsoHyperlink {
      mso-style-priority: 99;
      color: inherit;
      }
      span.MsoHyperlinkFollowed {
      mso-style-priority: 99;
      color: inherit;
      }
      .em_white a {
      color: #ffffff !important;
      text-decoration: none !important;
      }
      .hide_desktop {
        display: none;
      }
      .appleLinks a {color:#6f6e5c;}
      .appleLinksWhite a {color:#ffffff;}
      /*Stylesheed for the devices width between 481px to 649px*/
      @media only screen and (min-width:481px) and (max-width:649px) {
      table[class=em_wrapper] {
      width: 100% !important;
      }
      table[class=em_wrapper_logo] {
      width: 111px !important;
      padding-left: 10px !important;
      }
      table[class=em_wrapper_social] {
      width: 96px !important;
      }
      td[class=em_hide], table[class=em_hide], span[class=em_hide], br[class=em_hide] {
      display: none !important;
      }
      img[class=em_full_img] {
      width: 100% !important;
      height: auto !important;
      }
      td[class=em_center] {
      text-align: center !important;
      }
      td[class=em_width10] {
      width: 10px !important;
      }
      td[class=em_height] {
      height: 20px !important;
      font-size: 1px !important;
      line-height: 1px !important;
      }
      td[class=em_pad_top] {
      padding-top: 20px !important;
      }
      td[class=em_pad_top1] {
      padding-top: 15px !important;
      }
      table[class=em_wrapper1] {
      width: 100px !important;
      }
      td[class=em_center1] {
      text-align: center !important;
      padding-top: 9px !important;
      }
      td[class=em_height1] {
      height: 12px !important;
      font-size: 1px !important;
      line-height: 1px !important;
      }
      td[class=em_font1]{
      font-size:27px !important;
      line-height:32px !important;
      }
      td[class=em_height5] {
      height: 10px !important;
      font-size: 1px !important;
      line-height: 1px !important;
      }
      td[class=em_bg] {
      height:auto !important;
      }
      table[class=em_wrapper_1] {
      width: 440px !important;
      }
      td[class=em_text1]{
      font-size:35px !important;
      line-height:35px !important;
      letter-spacing:2px !important;
      }
      .mainCol {
      display: table-header-group;
      max-width: 100% !important;
      }
      .subCol {
      display: table-footer-group;
      width: 100% !important;
      }
      .hide_desktop {
      display: block !important;
      }
      .full_width {
      width: 100% !important;
      }
      }
      /*Stylesheed for the devices width between 0px to 480px*/
      @media only screen and (max-width:480px) {
      table[class=em_wrapper] {
      width: 100% !important;
      }
      table[class=em_wrapper1] {
      width: 100px !important;
      }
      table[class=em_wrapper_1] {
      width: 290px !important;
      }
      table[class=em_wrapper_logo] {
      width: 111px !important;
      padding-left: 10px !important;
      }
      table[class=em_wrapper_social] {
      width: 96px !important;
      }
      td[class=em_hide], table[class=em_hide], span[class=em_hide], br[class=em_hide], span[class=em_hide1] {
      display: none !important;
      }
      img[class=em_full_img] {
      width: 100% !important;
      height: auto !important;
      }
      img[class=em_fix_img] {
      width: 101px !important;
      height: auto !important;
      padding-left: 10px !important;
      }
      td[class=em_center] {
      text-align: center !important;
      }
      td[class=em_center1] {
      text-align: center !important;
      padding-top: 9px !important;
      }
      td[class=em_width10] {
      width: 10px !important;
      }
      td[class=em_height] {
      height: 20px !important;
      font-size: 1px !important;
      line-height: 1px !important;
      }
      td[class=em_hgt] {
      height: 60px !important;
      }
      td[class=em_hgt2] {
      height: 25px !important;
      }
      td[class=em_height1] {
      height: 12px !important;
      font-size: 1px !important;
      line-height: 1px !important;
      }
      td[class=em_height5] {
      height: 5px !important;
      font-size: 1px !important;
      line-height: 1px !important;
      }
      td[class=em_pad_top] {
      padding-top: 20px !important;
      }
      td[class=em_pad_top1] {
      padding-top: 10px !important;
      }
      td[class=em_font1]{
      font-size:27px !important;
      line-height:30px !important;
      }
      td[class=em_font]{
      font-size:14px !important;
      line-height:18px !important;
      }
      td[class=em_text]{
      font-size:30px !important;
      line-height:30px !important;
      letter-spacing:2px !important;
      }
      td[class=em_text1]{
      font-size:35px !important;
      line-height:35px !important;
      letter-spacing:2px !important;
      }
      td[class=em_bg] {
      height:auto !important;
      }
      span[class=em_br] {
      display:block !important;
      }
      .mainCol {
      display: table-header-group;
      max-width: 100% !important;
      }
      .subCol {
      display: table-footer-group;
      width: 100% !important;
      }
      .hide_desktop {
        display: block !important;
      }
      .full_width {
        width: 100% !important;
      }
      }
    </style>
  </head>
  <body style="margin:0px; padding:0px;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
      <tr>
        <td align="center" valign="top">
          <table width="600" border="0" align="center" class="em_wrapper" cellpadding="0" cellspacing="0" style="table-layout:fixed;">
            <tr>
              <td class="em_hide" height="1" style="line-height:0px; font-size:0px;"><img src="https://gallery.mailchimp.com/fd2713e31d667874841b5ad99/images/36d12efe-a869-4068-ab36-a76e307bf38c.gif" height="1" width="600" alt="" style="display:block; width:600px; min-width:600px;" border="0" /></td>
            </tr>
            <tr>
              <td valign="top">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td height="10" bgcolor="#919389" style="font-size:0px; line-height:10px;"><img src="https://gallery.mailchimp.com/fd2713e31d667874841b5ad99/images/36d12efe-a869-4068-ab36-a76e307bf38c.gif" width="1" height="1" alt="" border="0" style="display:block;" /></td>
                  </tr>
                  <tr>
                    <td valign="top">
                      <table width="100%" border="0" bgcolor="#ffffff" cellspacing="0" cellpadding="0">
                        <tr>
                          <td valign="top">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td valign="top">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                                    <tr>
                                      <td width="25" class="em_width10"><img src="https://gallery.mailchimp.com/fd2713e31d667874841b5ad99/images/36d12efe-a869-4068-ab36-a76e307bf38c.gif" width="25" height="1" alt="" border="0" style="display:block;" /></td>
                                      <td valign="top" bgcolor="#ffffff">
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td height="20" class="em_height" style="font-size:1px; line-height:20px;">&nbsp;</td>
                                          </tr>
                                          <tr>
                                            <td valign="top">
                                              <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                                                <tr>
                                                  <td valign="top">
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="em_wrapper_logo">
                                                      <tr>
                                                        <td valign="top">
                                                          <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="em_wrapper_logo">
                                                            <tr>
                                                              <td valign="top" align="center"><a href="<?php echo get_site_url(); ?>" target="_blank" style="text-decoration:none;"><img src="<?php echo $rac_options['logo_url']; ?>" height="<?php echo $rac_options['logo_height'] ?>" width="<?php echo $rac_options['logo_width'] ?>" alt="<?php echo get_bloginfo('name'); ?>"  border="0" style="display:block;"/></a></td>
                                                            </tr>
                                                          </table>
                                                        </td>
                                                      </tr>
                                                    </table>
                                                  </td>
                                                </tr>
                                              </table>
                                            </td>
                                          </tr>
                                          <tr>
                                            <td height="9" class="em_height" style="font-size:1px; line-height:1px;">&nbsp;</td>
                                          </tr>
                                        </table>
                                      </td>
                                      <td width="25" class="em_width10"><img src="https://gallery.mailchimp.com/fd2713e31d667874841b5ad99/images/36d12efe-a869-4068-ab36-a76e307bf38c.gif" width="25" height="1" alt="" border="0" style="display:block;" /></td>
                                    </tr>
                                    <tr>
                                      <td width="25" class="em_width10"><img src="https://gallery.mailchimp.com/fd2713e31d667874841b5ad99/images/36d12efe-a869-4068-ab36-a76e307bf38c.gif" width="25" height="1" alt="" border="0" style="display:block;" /></td>
                                      <td valign="top">
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center"  bgcolor="#ffffff">
                                          <tr>
                                            <td height="15" class="em_height1">&nbsp;</td>
                                          </tr>
                                          <tr>
                                            <td align="center" valign="top">
                                              <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center"  bgcolor="#ffffff">
                                                <tr>
                                                  <td height="26" class="em_height1">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                  <td align="center" valign="top" style="text-align:center;font-family: 'Nunito', sans-serif; font-size:13px; line-height:20px; color:#919289;"><?php echo $rac_options['email_message']; ?></td>
                                                </tr>
                                                <tr>
                                                  <td class="em_hide" height="5" style="font-size:1px; line-height:5px;">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                  <td height="5" style="font-size:1px; line-height:5px;">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                  <td align="center" valign="top" style="text-align:center;font-family: 'Nunito', sans-serif; font-size:18px; line-height:20px; color:#919289; font-weight:bold;">
                                                  		<?php 
                                                  			if( $rac_options['interval'] === 'initial' ){
                                                  				echo $rac_options['cart_discount'];
                                                  			}elseif( $rac_options['interval'] === '3_day' ){
                                                  				echo $rac_options['3_day_discount'];
                                                  			}elseif( $rac_options['interval'] === '7_day' ){
                                                  				echo $rac_options['7_day_discount'];
                                                  			}
                                                  		?>
                                                  </td>
                                                </tr>
                                                <tr>
                                                  <td class="em_hide" height="5" style="font-size:1px; line-height:5px;">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                  <td height="20" style="font-size:1px; line-height:5px;">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                  <td align="center">
                                                    <div>
                                                      <!--[if mso]>
                                                        <v:rect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="<?php echo $full_return_url . '&utm_source=' . $rac_options['utm_code']. '&utm_medium=email&utm_campaign=' . $rac_options['utm_code'] ?>" style="height:40px;v-text-anchor:middle;width:200px;" stroke="f" fillcolor="#919388">
                                                        <w:anchorlock/>
                                                        <center>
                                                      <![endif]-->
                                                      <a href="<?php echo $full_return_url . '&utm_source=' . $rac_options['utm_code']. '&utm_medium=email&utm_campaign=' . $rac_options['utm_code'] ?>" style="background-color:#919388;color:#ffffff;display:inline-block;font-family:'Nunito', sans-serif;;font-size:13px;font-weight:bold;line-height:40px;text-align:center;text-decoration:none;width:200px;-webkit-text-size-adjust:none;"><?php echo $rac_options['button_text']; ?></a>
                                                      <!--[if mso]>
                                                        </center>
                                                        </v:rect>
                                                      <![endif]-->
                                                    </div>
                                                  </td>
                                                </tr>
                                                <tr>
                                                  <td class="em_hide" height="5" style="font-size:1px; line-height:5px;">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                  <td height="20" style="font-size:1px; line-height:5px;">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                  <td align="center" valign="top" style="text-align:center;font-family: 'Nunito', sans-serif; font-size:22px; line-height:22px; color:#6f6e5c; text-transform:uppercase; ">Cart Details</td>
                                                </tr>
                                                <tr>
                                                  <td height="10" style="line-height: 10px;">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                  <td bgcolor="">
                                                  </td>
                                                </tr>
                                                <tr>
                                                  <td>
                                                    <table width="100%" style="border-style: solid; border-width: 1px; border-color: #919289; ">
                                                      <tr>
                                                        <td>
                                                          <table bgcolor="#f5f5f3" width="100%">
                                                            <tr>
                                                              <td width="100%" height="5" style="line-height:5px;">&nbsp;</td>
                                                            </tr>
                                                            <tr>
                                                              <td width="100%">
                                                                <table width="100%">
                                                                  <tr>
                                                                    <td width="280" style="text-align:left;font-family: 'Nunito', sans-serif; font-size:14px; line-height:16px; color:#6f6e5c;padding-left:10px;">Product</td>
                                                                    <td width="100" style="text-align:center;font-family: 'Nunito', sans-serif; font-size:14px; line-height:16px; color:#6f6e5c;">Quantity</td>
                                                                    <td width="100" style="text-align:right;font-family: 'Nunito', sans-serif; font-size:14px; line-height:16px; color:#6f6e5c;padding-right:10px;">Total</td>
                                                                  </tr>
                                                                </table>
                                                              </td>
                                                            </tr>
                                                            <tr>
                                                              <td width="100%" height="5" style="line-height:5px;">&nbsp;</td>
                                                            </tr>
                                                          </table>
                                                        </td>
                                                      </tr>
                                                      <?php
                                                		$pf = new WC_Product_Factory();

                                                		$cart_total = 0;

                                                		foreach( $cart_array as $cart_item ):
                                                          $product = $pf->get_product( $cart_item['product_id'] );
                                                		  if( $product ){
                                                              $img_src = wp_get_attachment_image_src( 
                                                                  $product->get_image_id(), 
                                                		  		array( '80', '80' ) 
                                                            );
                                                		  	$title = $product->get_title();
                                                		  }else{
                                                		  	$img_src = array('');
                                                		  	$title = 'Discontinued Product';
                                                		  }
                                                		  $indiv_price = $cart_item['line_total'] / $cart_item['quantity'];
                                                		  $cart_total += $cart_item['line_total'];
                                                		?>
                                                      <tr>
                                                        <td>
                                                          <table>
                                                            <tr>
                                                              <td width="60">
                                                                <img src="<?php echo $img_src[0] ?>" width="57" height="57" alt="<?php echo get_bloginfo('name'); ?>" style="display:block;" border="0">
                                                              </td>
                                                              <td width="250">
                                                                <table>
                                                                  <tr>
                                                                    <td style="text-align:left;font-family: 'Nunito', sans-serif; font-size:14px; line-height:16px; color:#6f6e5c;"><?php echo $title ?></td>
                                                                  </tr>
                                                                  <tr>
                                                                    <!-- <td style="text-align:left;font-family: 'Nunito', sans-serif; font-size:12px; line-height:16px; color:#6f6e5c;">Size | Color</td> -->
                                                                  </tr>
                                                                </table>
                                                              </td>
                                                              <td width="40" style="text-align:right;font-family: 'Nunito', sans-serif; font-size:14px; line-height:16px; color:#6f6e5c;"><?php echo $cart_item['quantity'] ?></td>
                                                              <td width="200" style="text-align:right;font-family: 'Nunito', sans-serif; font-size:14px; line-height:16px; color:#6f6e5c; padding-right:10px;"><?php echo money_format( '%.2n', $cart_item['line_total'] ) ?></td>
                                                            </tr>
                                                            <tr>
                                                              <td width="60"  height="15" style="line-height:15px;">&nbsp;</td>
                                                              <td width="250" height="15" style="line-height:15px;">&nbsp;</td>
                                                              <td width="40"  height="15" style="line-height:15px;">&nbsp;</td>
                                                              <td width="200" height="15" style="line-height:15px;">&nbsp;</td>
                                                            </tr>
                                                          </table>
                                                        </td>
                                                      </tr>
                                                      <?php endforeach; ?>
                                                    </table>
                                                  </td>
                                                </tr>
                                                <tr>
                                                  <td height="40" class="em_height1">&nbsp;</td>
                                                </tr>
                                              </table>
                                            </td>
                                          </tr>
                                          <tr>
                                            <td height="15" bgcolor="#ffffff" style="font-size:0px; line-height:0px;"><img src="https://gallery.mailchimp.com/fd2713e31d667874841b5ad99/images/36d12efe-a869-4068-ab36-a76e307bf38c.gif" width="1" height="1" alt="" border="0" style="display:block;" /></td>
                                          </tr>
                                        </table>
                                      </td>
                                      <td width="25" class="em_width10"><img src="https://gallery.mailchimp.com/fd2713e31d667874841b5ad99/images/36d12efe-a869-4068-ab36-a76e307bf38c.gif" width="25" height="1" alt="" border="0" style="display:block;" /></td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td height="1" bgcolor="#ffffff" style="font-size:0px; line-height:0px;"><img src="https://gallery.mailchimp.com/fd2713e31d667874841b5ad99/images/36d12efe-a869-4068-ab36-a76e307bf38c.gif" width="1" height="1" alt="" border="0" style="display:block;" /></td>
                  </tr>
                  <tr>
                    <td height="15" class="em_height5">&nbsp;</td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
    <div style="display:none; white-space:nowrap; font:20px courier; color:#ffffff;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</div>
  </body>
</html>
