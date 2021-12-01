<h2>Apply Coupon Code via URL</h2>
<hr>
<p><strong>NOTE:</strong> Create the coupon code before testing.</p>
<p>Add <em>?coupon=typecouponcodehere</em> to any url</p>
<p>Example: <a href="<?php echo site_url(); ?>/?coupon=buynow10"><?php echo site_url(); ?>/?coupon=buynow10</a></p>

<h2>Add Products &amp; Quantities to Cart via URL</h2>
<hr>
<p><strong>NOTE:</strong> Your products need SKUs in order for this to work.</p>
<p>There are two parts:</p>
<ol>
  <li>Add the product SKU or SKUs separated by commas, defined by <em>sku=</em></li>
  <li>Add the quantity of the product(s) separated by commas, defined by <em>quant=</em></li>
</ol>

<p>To add a quantity 1 of SKU EGC-4S7-FIN add <em>?sku=EGC-4S7-FIN&amp;quant=1</em> to any url</p>
<p>Example: <a href="<?php echo site_url(); ?>/?sku=EGC-4S7-FIN&amp;quant=1"><?php echo site_url(); ?>/?sku=EGC-4S7-FIN&amp;quant=1</a></p>

<p>To add a quantity 2 of SKU EGC-4S7-FIN and a quantity 4 of SKU Q0Z-IJA-0V9 add <em>?sku=EGC-4S7-FIN,Q0Z-IJA-0V9&amp;quant=2,4</em> to any url</p>
<p>Example: <a href="<?php echo site_url(); ?>/?sku=EGC-4S7-FIN,Q0Z-IJA-0V9&amp;quant=2,4"><?php echo site_url(); ?>/?sku=EGC-4S7-FIN,Q0Z-IJA-0V9&amp;quant=2,4</a></p>

<h2>Add Products with Quantities to Cart &amp; Apply Coupon Code via URL</h2>
<hr>
<p><strong>NOTE:</strong> You need to create the coupon code before testing and your products need SKUs in order for this to work.</p>
<p>We are simply combining the two functions above with an ampersand "&amp;"</p>
<p>To add a quantity 2 of SKU EGC-4S7-FIN and a quantity 4 of SKU Q0Z-IJA-0V9 and coupon code of buynow10 add <em>?sku=EGC-4S7-FIN,Q0Z-IJA-0V9&amp;quant=2,4&amp;coupon=buynow10</em> to any url</p>
<p>Example: <a href="<?php echo site_url(); ?>/?sku=EGC-4S7-FIN,Q0Z-IJA-0V9&amp;quant=2,4&amp;coupon=buynow10"><?php echo site_url(); ?>/?sku=EGC-4S7-FIN,Q0Z-IJA-0V9&amp;quant=2,4&amp;coupon=buynow10</a></p>