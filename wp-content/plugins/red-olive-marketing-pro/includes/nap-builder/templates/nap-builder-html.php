<?php if (isset($block['nap_custom_css']) && $block['nap_custom_css']): ?>
	<style>
		<?php echo strip_tags($block['nap_custom_css']); ?>
	</style>
<?php endif; ?>

<div class="nap-block" itemscope="" itemtype="http://schema.org/<?php echo $block['nap_business_type']; ?>">
	<div class="nap-text-block">
		<div class="nap-name" <?php echo $block['nap_one_line']; ?>>
			<span itemprop="name"><?php echo $block['nap_name']; ?></span>
		</div>
		<?php if ($block['nap_one_line']): ?>
			<span class="nap-separator"><?php echo $block['nap_separator']; ?></span>
		<?php endif; ?>
		<div <?php echo $block['nap_one_line']; ?> itemprop="address" itemscope="" itemtype="http://schema.org/PostalAddress">
			<?php if (isset($block['nap_map_link']) && $block['nap_map_link']): ?>
				<div class="nap-street-address" <?php echo $block['nap_one_line']; ?>>
					<span itemprop="streetAddress">
						<a target="_blank" href="<?php echo $block['nap_map_link']; ?>">
							<?php echo $block['nap_address']; ?>&nbsp;
						</a>
					</span>
				</div>
				<div class="nap-csz" <?php echo !empty($block['nap_one_line_nowrap']) ? $block['nap_one_line_nowrap'] : null; ?>>
					<a target="_blank" href="<?php echo $block['nap_map_link']; ?>">
						<span itemprop="addressLocality"><?php echo $block['nap_city']; ?></span>, 
						<span itemprop="addressRegion"><?php echo $block['nap_state']; ?></span> 
						<span itemprop="postalCode"><?php echo $block['nap_zip_code']; ?></span>
					</a>
				</div>
				<?php if ($block['nap_one_line']): ?>
					<span class="nap-separator"><?php echo $block['nap_separator']; ?></span>
				<?php endif; ?>
			<?php else: ?>
				<div class="nap-street-address" <?php echo $block['nap_one_line']; ?>>
					<span itemprop="streetAddress">
						<?php echo $block['nap_address']; ?>&nbsp;
					</span>
				</div>
				<div class="nap-csz" <?php echo !empty($block['nap_one_line_nowrap']) ? $block['nap_one_line_nowrap'] : null; ?>>
					<span itemprop="addressLocality"><?php echo $block['nap_city']; ?></span>, 
					<span itemprop="addressRegion"><?php echo $block['nap_state']; ?></span> 
					<span itemprop="postalCode"><?php echo $block['nap_zip_code']; ?></span>
				</div>
				<?php if ($block['nap_one_line']): ?>
					<span class="nap-separator"><?php echo $block['nap_separator']; ?></span>
				<?php endif; ?>
			<?php endif; ?>
		</div>

		<?php if ($block['nap_phone']): ?>
			<div class="nap-telephone" <?php echo !empty($block['nap_one_line_nowrap']) ? $block['nap_one_line_nowrap'] : null; ?>>
				<span itemprop="telephone">
					<a href="tel:+<?php echo $block['nap_phone']; ?>" data-ctm-watch-id="3" data-ctm-tracked="1">
						<?php echo $block['nap_phone']; ?>
					</a>
				</span>
			</div>
		<?php endif; ?>
	</div>
	
	<?php if ($block['nap_image']): ?>
		<div <?php echo $block['nap_show_image']; ?> class="nap-image">
			<img itemprop="image" src="<?php echo $block['nap_image']; ?>">
		</div>
    <?php endif; ?>
    
    <?php if ($block['nap_price_range']): ?>
        <div style="display:none;">
            <span itemprop="priceRange"><?php echo $block['nap_price_range']; ?></span>
		</div>
	<?php endif; ?>
</div>
