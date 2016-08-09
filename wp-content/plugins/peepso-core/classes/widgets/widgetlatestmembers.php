<?php


class PeepSoWidgetLatestmembers extends WP_Widget
{

    /**
     * Set up the widget name etc
     */
    public function __construct($id = null, $name = null, $args= null) {
        if(!$id)    $id     = 'PeepSoWidgetLatestMembers';
        if(!$name)  $name   = __( 'PeepSo Latest Members', 'peepso' );
        if(!$args)  $args   = array( 'description' => __( 'PeepSo Latest Members Widget', 'peepso' ), );

        parent::__construct(
            $id, // Base ID
            $name, // Name
            $args // Args
        );

        add_action('peepso_register_new_user', array(&$this,'delete_cache'), 10, 1);
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

        if(isset($instance['is_profile_widget']))
        {
            // Override the HTML wrappers
            $args = apply_filters('peepso_widget_args_internal', $args);
        }

        // Additional shared adjustments
        $instance = apply_filters('peepso_widget_instance', $instance);        

        if(!array_key_exists('template', $instance) || !strlen($instance['template']))
        {
            $instance['template'] = 'latest-members.tpl';
        }

        $trans_latest_members = 'peepso_cache_widget_latestmembers';

        // check cache
        $list_latest_members = get_transient($trans_latest_members);
        if(false === $list_latest_members) {
            // List of links to be displayed
            $args['orderby']= 'registered';
            $args['order']  = 'desc';
            $args['exclude']  = get_current_user_id();
            $args_pagination['offset'] = 0;
            $args_pagination['number'] = $instance['limit'];

            // Merge pagination args and run the query to grab paged results
            $args = array_merge($args, $args_pagination);

            $list_latest_members = new PeepSoUserSearch($args, get_current_user_id(), '');
            set_transient( $trans_latest_members, $list_latest_members, 1 * HOUR_IN_SECONDS );
        }
        
        $members_page = count($list_latest_members->results);
        $members_found = $list_latest_members->total;

        if(!array_key_exists('list', $instance) || !array_key_exists('total', $instance))
        {
            $instance['list'] = $list_latest_members->results;
            $instance['total'] = $list_latest_members->total;
        }

        if(0==$instance['total'] && true == $instance['hideempty']) {
            return FALSE;
        }

        PeepSoTemplate::exec_template( 'widgets', $instance['template'], array( 'args'=>$args, 'instance' => $instance ) );
    }

    /**
     * Outputs the admin options form
     *
     * @param array $instance The widget options
     */
    public function form( $instance ) {

        $instance['fields'] = array(
            // general
            'limit'     => TRUE,
            'title'     => TRUE,

            // peepso
            'integrated'   => TRUE,
            'position'  => TRUE,
            'ordering'  => TRUE,
            'hideempty' => TRUE,

        );

        $this->instance = $instance;

        $settings =  apply_filters('peepso_widget_form', array('html'=>'', 'that'=>$this,'instance'=>$instance));
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
        $instance['title']          = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['limit']       = (int) $new_instance['limit'];

        $instance['integrated']  = 1;
        $instance['hideempty']   = (int) $new_instance['hideempty'];
        $instance['position']    = strip_tags($new_instance['position']);        

        return $instance;
    }

    public function delete_cache($user) {

        // delete cache for latest members
        $trans_latest_members = 'peepso_widget_latestmembers';
        delete_transient($trans_latest_members);

        return $user;
    }
}

// EOF