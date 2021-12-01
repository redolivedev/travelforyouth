<div class="hidden">
	<a href="#ro-pop-up" id="ro-pop-up-trigger">Trigger</a>
	<div id="ro-pop-up">
		<?php if( $this->pop_up_meta['border_text'] ): ?>
			<div class="border-box">
				<?php echo $this->pop_up_meta['border_text'] ?>
			</div>
		<?php endif; ?>
		<div class="large-text"><?php echo $this->pop_up_meta['large_text'] ?></div>
		<div class="medium-text"><?php echo $this->pop_up_meta['medium_text'] ?></div>
		<?php if( $this->pop_up_meta['pop_up_image'] ) : ?>
			<img src="<?php echo $this->pop_up_meta['pop_up_image'] ?>" />
		<?php endif ?>
		<?php if( $_SESSION['ro_popup_end_time_' . $this->pop_up->ID] && $this->pop_up_meta['pop_up_content_type'] === 'link' ) : ?>
			<?php $formattedTime = $this->format_time( $_SESSION['ro_popup_end_time_' . $this->pop_up->ID] ) ?>
			<div class="time-remaining">
				Time Remaining <strong>&ndash; <span><?php echo $formattedTime ?></span></strong>
			</div>
        <?php endif ?>
        <?php if( $this->pop_up_meta['pop_up_content_type'] === 'comment' ): ?>
            <textarea class="js-pop-up-comment" cols="30" rows="10"></textarea>
        <?php endif; ?>
		<?php if( $this->pop_up_meta['email_field'] ): ?>
			<input type="text" placeholder="Email Address" class="js-pop-up-email-address">
        <?php endif; ?>
        <?php if( $this->pop_up_meta['email_accept'] ): ?>
            <div>
                <input type="checkbox" class="js-pop-up-email-accept" id="pop-up-email-accept">
                <label for="pop-up-email-accept">I would like to receive marketing messages from <?php bloginfo('name'); ?></label>
            </div>
		<?php endif; ?>
		<?php if( $this->pop_up_meta['button_text'] ): ?>
		<div class="pop-up-button <?php echo $this->pop_up_meta['button_class']; ?>">
			<a href="<?php echo $this->pop_up_meta['button_link'] ?>"><?php echo $this->pop_up_meta['button_text'] ?></a>
		</div>
		<?php endif; ?>
		<div class="ignore">
			<?php echo $this->pop_up_meta['dismiss_text'] ?>
		</div>
	</div>
</div>

<?php ob_start(); ?>
<?php require( 'popup-scripts.php' ); ?>
<?php echo ob_get_clean(); ?>

<?php ob_start(); ?>
<?php require( 'popup-styles.php' ); ?>
<?php echo ob_get_clean(); ?>
