<?php
$user = new PeepSoUser(PeepSoProfileShortcode::get_instance()->get_view_user_id());

$can_edit = FALSE;
if($user->get_id() == PeepSo::get_user_id() || current_user_can('edit_users')) {
	$can_edit = TRUE;
}

?>

<div class="peepso">
	<?php peepso('load-template', 'general/navbar'); ?>
	<?php peepso('load-template', 'profile/submenu'); ?>
	<section id="mainbody" class="ps-page ps-page--preferences">
		<section id="component" role="article" class="clearfix">
		<!--<h4 class="ps-page-title"><?php _e('Preferences', 'peepso'); ?></h4>-->
		
		<div class="cfield-list creset-list">
			<?php if (peepso('profile', 'get.num-preferences-fields')) { ?>
				<?php peepso('profile', 'preferences-form-fields'); ?>
			<?php } else { ?>
				<?php _e('You have no configurable preferences settings.', 'peepso'); ?>
			<?php } ?>
		</div>
		</section><!--end compnent-->
	</section><!--end mainbody-->
</div><!--end row-->

