<?php


class PeepSoWidgetMe extends WP_Widget
{

    /**
     * Set up the widget name etc
     */
    public function __construct($id = null, $name = null, $args= null) {
        if(!$id)    $id     = 'PeepSoWidgetMe';
        if(!$name)  $name   = __( 'PeepSo Profile', 'peepso' );
        if(!$args)  $args   = array( 'description' => __( 'PeepSo Profile Widget', 'peepso' ), );

        parent::__construct(
            $id, // Base ID
            $name, // Name
            $args // Args
        );
    }

    /**
     * Outputs the content of the widget
     *
     * @param array $args
     * @param array $instance
     */
    public function widget( $args, $instance ) {

        $instance['user_id']    = get_current_user_id();
        $instance['user']       = new PeepSoUser($instance['user_id']);

        // Disable the widget for guests if
        if(isset($instance['guest_behavior']) && 'hide' === $instance['guest_behavior'] && !$instance['user_id'])
        {
            return FALSE;
        }

        // List of links to be displayed
        $links = array();
        $links = apply_filters('peepso_widget_me_links', $links);


        $instance['links'] = $links;

        if(!array_key_exists('template', $instance) || !strlen($instance['template']))
        {
            $instance['template'] = 'me.tpl';
        }

        $instance['toolbar'] = '';
        if(isset($instance['show_notifications'])) {
            if(1 === intval($instance['show_notifications'])) {
                $instance['toolbar'] = $this->toolbar();
            }
        }

        PeepSoTemplate::exec_template( 'widgets', $instance['template'], array( 'args'=>$args, 'instance' => $instance ) );
    }

    // Displays the frontend navbar
    public function toolbar()
    {
        $note = new PeepSoNotifications();
        $unread_notes = $note->get_unread_count_for_user(PeepSo::get_user_id());

        $toolbar = array(
            'notifications' => array(
                'href' => PeepSo::get_page('notifications'),
                'icon' => 'globe',
                'class' => 'dropdown-notification ps-js-notifications',
                'title' => __('Pending Notifications', 'peepso'),
                'count' => $unread_notes,
                'order' => 80
            ),
        );

        /*
         * if there are no notifications, this code will disable the notification icon's click functionality
         * and will prevent the popup from being shown
         */
//      if (0 === $unread_notes) {
//          $toolbar['notifications']['href'] = 'javascript:void(0)';
//          $toolbar['notifications']['class'] = ' visible-desktop ';
//      }

        $toolbar = apply_filters('peepso_profile_widget_toolbar', $toolbar);

        $sort_col = array();

        foreach ($toolbar as $nav)
            $sort_col[] = (isset($nav['order']) ? $nav['order'] : 10);

        array_multisort($sort_col, SORT_ASC, $toolbar);
        
        ob_start();
        echo '<!-- Toolbar -->';
        echo '<div class="ps-widget--profile__notifications">';
        echo '<ul>';
        foreach ($toolbar as $item => $data) {
            echo '<li class=" ', (isset($data['class']) ? $data['class'] : ''), '" ';

            echo '>', PHP_EOL;
            echo '<a href="', $data['href'], '" ';
            if (isset($data['title']))
                echo ' title="', esc_attr($data['title']), '" ';
            echo '>';
            if (isset($data['icon']))
                echo '<i class="ps-icon-', $data['icon'], '"></i>';
            if (isset($data['label']))
                echo $data['label'];
            if (isset($data['count'])) {
                echo '<span class="js-counter ps-notification-counter ps-js-counter"' , ($data['count'] > 0 ? '' : ' style="display:none"'),'>', ($data['count'] > 0 ? $data['count'] : ''), '</span>';
            }
            echo '</a>', PHP_EOL;
            echo '</li>', PHP_EOL;
        }

        echo '</ul>';
        echo '</div>';

        $html = ob_get_clean();

        return $html;
    }

    /**
     * Outputs the admin options form
     *
     * @param array $instance The widget options
     */
    public function form( $instance ) {

        $instance['fields'] = array(
            // general
            'section_general' => FALSE,
            'limit'     => FALSE,
            'title'     => TRUE,

            // peepso
            'integrated'   => FALSE,
            'position'  => FALSE,
            'ordering'  => FALSE,
            'hideempty' => FALSE,

        );

        ob_start();

        $settings =  apply_filters('peepso_widget_form', array('html'=>'', 'that'=>$this,'instance'=>$instance));

        $guest_behavior = !empty($instance['guest_behavior']) ? $instance['guest_behavior'] : 'login';
        $show_notifications = isset($instance['show_notifications']) ? $instance['show_notifications'] : 1;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('guest_behavior'); ?>">
                <?php _e('Guest view', 'peepso'); ?>
                <select class="widefat" id="<?php echo $this->get_field_id('guest_behavior'); ?>"
                        name="<?php echo $this->get_field_name('guest_behavior'); ?>">
                    <option value="login"><?php _e('Log-in form', 'peepso'); ?></option>
                    <option value="hide" <?php if('hide' === $guest_behavior) echo ' selected="selected" ';?>><?php _e('Hide', 'peepso'); ?></option>
                </select>

            </label>
        </p>        
        <p>            
            <input name="<?php echo $this->get_field_name('show_notifications'); ?>" class="ace ace-switch ace-switch-2" 
                    id="<?php echo $this->get_field_id('show_notifications'); ?>" type="checkbox" value="1"
                    <?php if(1 === $show_notifications) echo ' checked="" ';?>>
            <label class="lbl" for="<?php echo $this->get_field_id('show_notifications'); ?>">
                <?php _e('Show notifications', 'peepso'); ?>
            </label>
        </p>
        <?php
        $settings['html']  .= ob_get_clean();
        
        echo $settings['html'];
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['guest_behavior'] = $new_instance['guest_behavior'];
        $instance['show_notifications']      = (int) $new_instance['show_notifications'];
        $instance['title']          = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

        return $instance;
    }
}

// EOF