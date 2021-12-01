<style>
	.hidden {
		display: none;
	}

	.fancybox-overlay, .fancybox-outer {
		text-align: center;
	}

	.fancybox-opened .fancybox-skin {
		box-shadow: none;
		border-radius: 0;
		color: <?php echo $this->pop_up_meta['text_color'] ?>;
		border: 4px solid <?php echo $this->pop_up_meta['border_color'] ?>;
		background-color: <?php echo $this->pop_up_meta['background_color'] ?>;
	}
	@media (max-width: 800px) {
		#ro-pop-up {
			width: 100%;
		}

        input#pop-up-email-accept {
            float: left;
            width: 20px;
            margin-top: 7px;
        }

        input#pop-up-email-accept + label {
            display: block;
        }
	}
	@media (min-width: 801px) {
	  	#ro-pop-up {
	    	min-width: 550px;
	  	}
	}
	#ro-pop-up .border-box {
		padding: 0 2em;
		font-weight: bold;
		font-size: 1.35em;
		line-height: 1.1em;
		padding-bottom: 0.1em;
		letter-spacing: 0.1em;
		display: inline-block;
		text-transform: uppercase;
		color: <?php echo $this->pop_up_meta['border_text_color'] ?>;
		background: <?php echo $this->pop_up_meta['border_color'] ?>;
	}
	#ro-pop-up .large-text {
		font-size: 32px;
		font-weight: 800;
		margin: 0.2em 0 0;
		font-weight: bold;
		line-height: 1.1em;
		text-transform: uppercase;
		color: <?php echo $this->pop_up_meta['text_color'] ?>;
	}
	#ro-pop-up .medium-text {
		font-size: 2em;
		margin: 0.1em 0;
		font-weight: 300;
		line-height: 1.1em;
		font-weight: lighter;
		color: <?php echo $this->pop_up_meta['text_color'] ?>;
	}
	#ro-pop-up img {
		display: block;
		max-width: 100%;
		max-height: 400px;
		margin: 1em auto 0;
	}
	#ro-pop-up .time-remaining {
		font-size: 1.3em;
		font-weight: 300;
		margin-top: 0.5em;
		font-weight: lighter;
		color: <?php echo $this->pop_up_meta['time_limit_text_color'] ?>;
	}
	#ro-pop-up .pop-up-button {
		clear: both;
		margin: 0.5em 0;
		font-size: 1.6em;
		font-weight: bold;
		border-radius: 4px;
		display: inline-block;
		text-transform: uppercase;
		background: <?php echo $this->pop_up_meta['button_color'] ?>;
	}
	#ro-pop-up .pop-up-button a {
		display: block;
		padding: 5px 15px;
		text-decoration: none;
		color: <?php echo $this->pop_up_meta['button_text_color'] ?>;
	}
	#ro-pop-up .ignore {
		cursor: pointer;
		font-size: 12px;
		margin-top: 5px;
		color: <?php echo $this->pop_up_meta['dismiss_text_color'] ?>;
	}
</style>
