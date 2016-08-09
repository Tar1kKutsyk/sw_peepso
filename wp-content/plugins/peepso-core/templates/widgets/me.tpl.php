<?php

echo $args['before_widget'];

?>

	<div class="ps-widget--profile__wrapper ps-widget--external">
		<!-- Title of Profile Widget -->
		<?php
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		}
		?>

		<div class="ps-widget--profile">

		<?php
		if($instance['user_id'] >0)
		{
			$user  = $instance['user'];

			if($instance['user_id'] > 0 && $instance['user_id'] == PeepSo::get_user_id()) {
				$user->profile_fields->load_fields();
				$stats = $user->profile_fields->profile_fields_stats;
			}
			?>

			<div class="ps-widget--profile__header">
				<!-- Avatar -->
				<div class="ps-widget--profile__avatar">
					<a class="ps-avatar" href="<?php echo $user->get_profileurl();?>">
						<img alt="<?php echo $user->get_profileurl();?>" title="<?php echo $user->get_profileurl();?>" src="<?php echo $user->get_avatar();?>">
					</a>
				</div>

				<!-- Name, edit profile -->
				<div class="ps-widget--profile__details">
					<a class="ps-user-name" href="<?php echo $user->get_profileurl();?>">
						<?php echo $user->get_fullname();?>
					</a>
					<a href="<?php echo PeepSo::get_page('profile');?>?edit">
						<small><?php _e('Preferences', 'peepso');?></small>
					</a>

					<?php echo $instance['toolbar']; ?>
				</div>
			</div>

			<!-- Profile Completeness -->
			<?php

			if(isset($stats)) :

				$style = '';
				if ($stats['completeness'] >= 100) {
					$style.='display:none;';
				}
				?>
				<div class="ps-progress-status ps-completeness-status" style="<?php echo $style;?>"><?php echo $stats['completeness_message'];?></div>
				<div class="ps-progress-bar ps-completeness-bar" style="<?php echo $style;?>">
					<span style="width:<?php echo $stats['completeness'];?>%"></span>
				</div>

			<?php endif; ?>

			<!-- Links -->
			<ul class="ps-list ps-list--menu">
				<?php
				foreach($instance['links'] as $priority_number => $links)
				{
					foreach($links as $link) {
						echo '<li class="ps-list-item"><a href="' . $link['href'] . '"><span class="' . $link['icon'] . '"></span> ' . $link['title'] . '</a></li>';
					}
				}
				?>
			</ul>
			<?php
			} else {
			?>
			<form class="ps-form" action="" onsubmit="return false;" method="post" name="login" id="form-login-me">
				<div class="ps-form-row">
					<div class="ps-form-controls ps-form-input-icon">
						<span class="ps-icon"><i class="ps-icon-user"></i></span>
						<input class="ps-input ps-full" type="text" name="username" id="username" placeholder="<?php _e('Username', 'peepso'); ?>" mouseev="true"
							   autocomplete="off" keyev="true" clickev="true" />
					</div>
					<div class="ps-form-controls ps-form-input-icon">
						<span class="ps-icon"><i class="ps-icon-lock"></i></span>
						<input class="ps-input ps-full" type="password" name="password" id="password" placeholder="<?php _e('Password', 'peepso'); ?>" mouseev="true"
							   autocomplete="off" keyev="true" clickev="true" />
					</div>
					<div class="ps-form-controls ps-checkbox">
						<input type="checkbox" alt="<?php _e('Remember Me', 'peepso'); ?>" value="yes" id="remember" name="remember">
						<span><?php _e('Remember Me', 'peepso'); ?></span>
					</div>
					<div class="ps-form-controls">
						<ul class="ps-list ps-list--menu">
							<li><a href="<?php peepso('page-link', 'register'); ?>"><?php _e('Register', 'peepso'); ?></a></li>
							<li><a href="<?php peepso('page-link', 'recover'); ?>"><?php _e('Recover Password', 'peepso'); ?></a></li>
							<li><a href="<?php peepso('page-link', 'register'); ?>?resend"><?php _e('Resend activation code', 'peepso'); ?></a></li>
						</ul>
					</div>
					<button type="submit" id="login-submit" class="ps-btn ps-btn-login">
						<span><?php _e('Login', 'peepso'); ?></span>
						<img style="display:none" src="<?php echo PeepSo::get_asset('images/ajax-loader.gif'); ?>">
					</button>
				</div>

				<input type="hidden" name="option" value="ps_users">
				<input type="hidden" name="task" value="-user-login">
			</form>
			<div style="display:none">
				<form name="loginform" id="loginform" action="<?php peepso('page-link', 'home'); ?>wp-login.php" method="post">
					<input type="text" name="log" id="user_login" />
					<input type="password" name="pwd" id="user_pass" />
					<input type="checkbox" name="rememberme" id="rememberme" value="forever" />
					<input type="submit" name="wp-submit" id="wp-submit" value="Log In" />
					<input type="hidden" name="redirect_to" value="<?php peepso('redirect-login'); ?>" />
					<input type="hidden" name="testcookie" value="1" />
					<?php wp_nonce_field('ajax-login-nonce', 'security'); ?>
				</form>
			</div>
			<?php if( 1 === PeepSo::get_option('wsl_enable',0)) { ?>
			<div class="ps-widget--wsl">
				<?php
				add_filter( 'wsl_render_auth_widget_alter_provider_icon_markup', 'wsl_use_peepso_icons', 10, 3 );
				do_action( 'wordpress_social_login' );
				remove_filter( 'wsl_render_auth_widget_alter_provider_icon_markup', 'wsl_use_peepso_icons', 10 );
				?>
			</div>
			<?php } ?>
			<script>
				jQuery(function( $ ) {
					$('#form-login-me').on('submit', function( e ) {
						e.preventDefault();
						e.stopPropagation();
						ps_login.form_submit( e );
					});
				});
			</script>

			<?php
			}
			?>

		</div>
	</div>

<?php
echo $args['after_widget'];
// EOF
