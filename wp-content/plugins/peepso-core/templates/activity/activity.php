<div class="peepso">
	<section id="mainbody" class="ps-wrapper clearfix">
		<section id="component" role="article" class="clearfix">
			<?php peepso('load-template', 'general/navbar'); ?>
<?php /*	<h2 class="ps-page-title"><?php echo PeepSo::get_option('site_frontpage_title', __('Recent Activities', 'peepso')); ?></h2><?php */ ?>
			<?php peepso('load-template', 'general/register-panel'); ?>

			<div class="ps-body">
			<!--<div class="ps-sidebar"></div>-->
				<div class="ps-main ps-main-full">
					<?php peepso('load-template', 'general/postbox'); ?>

					<?php

					$user_id = get_current_user_id();

					if($user_id && FALSE === peepso('activityshortcode', 'is-permalink-page')) {

						$stream_options = apply_filters('peepso_default_stream_options', array());

						if (count($stream_options) > 1) {

							$current_option = PeepSoActivity::get_stream_filter_users(get_current_user_id());

							if (!array_key_exists($current_option, $stream_options)) {
								$keys = array_keys($stream_options);
								$current_option = $keys[0];
							}

							update_user_meta($user_id, 'peepso_default_stream', $current_option);
							?>
							<div class="ps-tabs__wrapper">
								<ul class="ps-tabs">

									<?php foreach ($stream_options as $option => $label) : ?>

										<li class="ps-tabs__item <?php echo ($option == $current_option) ? 'current':'';?>">
											<a href="<?php echo PeepSo::get_page('activity');?>?switch_default_stream=<?php echo $option;?>"><?php echo $label;?></a>
										</li>

									<?php endforeach;?>

								</ul>
							</div>
						<?php
						}
					}
					?>
					<!-- stream activity -->
					<div class="ps-stream-wrapper">
						<?php if (peepso('activity', 'has-posts')) { ?>
							<div id="ps-activitystream" class="ps-stream-container" data-filter="all" data-filterid="0" data-groupid data-eventid data-profileid>
							<?php
								// display all posts
								while (peepso('activity', 'next-post')) {
									peepso('activity', 'show-post'); // display post and any comments
								}

								peepso('activity', 'show-more-posts-link');
							?>
							</div>
						<?php } else if (0 === PeepSo::get_option('site_activity_hide_stream_from_guest', 0)) { ?>
							<div id="ps-no-posts" class="ps-alert"><?php _e('No posts found. Be the first one to share something amazing!' ,'peepso'); ?></div>
							<div id="ps-activitystream" class="ps-stream-container" style="display:none" data-filter="all" data-filterid="0" data-groupid data-eventid data-profileid>
							</div>
						<?php } ?>
						<?php peepso('load-template', 'activity/dialogs'); ?>
					</div>
				</div>
			</div>
		</section><!--end component-->
	</section><!--end mainbody-->
</div><!--end row-->
