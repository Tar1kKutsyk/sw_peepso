<form id="form_ban_user" class="ps-form--ban">
	<div class="ps-form__row">
		<label for="ban-period" class="ps-form__label">
			<input type="radio" name="ban_type" id="ban-period" value="ban_period" checked="checked">
			<?php echo __('Ban this user until', 'peepso'); ?>
		</label>
		<div class="ps-form__controls">
			<input type="text" class="ps-input" width="auto" name="ban_period_date" value="<?php echo $start_date; ?>" data-date-start-date="<?php echo $start_date; ?>" />
			<div id="ban-period-empty" class="ps-text-danger ps-form__helper" style="display:none"><?php echo __('Please fill in the date', 'peepso'); ?></div>
		</div>
	</div>
	<div class="ps-form__row">
		<label for="ban-forever" class="ps-form__label ps-full">
			<input type="radio" name="ban_type" id="ban-forever" value="ban_forever">
			<?php echo __('Ban this user forever', 'peepso'); ?>
		</label>
	</div>
</form>
