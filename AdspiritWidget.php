<?php

/**
 * Created by Sebastian Viereck IT-Services
 * www.sebastianviereck.de
 * Date: 12.02.16
 * Time: 13:23
 */
class AdspiritWidget extends WP_Widget
{

    function __construct()
    {
        // Instantiate the parent object
        $widget_ops = array(
            'classname' => 'my_widget',
            'description' => 'Add Adspirit Banners to your site.',
        );

        parent::__construct('adspirit-widget', 'Adspirit', $widget_ops);
    }

    // Creating widget front-end
// This is where the action happens
    public function widget($args, $instance)
    {

        //$title = apply_filters( 'widget_title', $instance['title'] );
        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        /*if ( ! empty( $title ) ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }*/
        // This is where you run the code and display the output

        $id = isset($instance['id']) ? $instance['id'] : null;

        if ($id) {
            $id = (int)$id;
            //display code
            $bannerCode = AdspiritBanners::getBannerCodeById($id);
            echo $bannerCode;
        } else {
            echo 'adspirit plugin: banner id not set';
        }
        echo $args['after_widget'];
    }

// Widget Backend
    public function form($instance)
    {

        //$title =  isset( $instance['title']) ? $instance['title'] : "";
        $id = isset($instance['id']) ? $instance['id'] : "";
        $banners = AdspiritBanners::getAllBanners();
        // Widget admin form
        ?>
        <!--<p>
			<label for="<?php /*echo $this->get_field_id( 'title' ); */ ?>"><?php /*_e( 'Title:' ); */ ?></label>
			<input class="widefat" id="<?php /*echo $this->get_field_id( 'title' ); */ ?>"
			       name="<?php /*echo $this->get_field_name( 'title' ); */ ?>" type="text"
			       value="<?php /*echo esc_attr( $title ); */ ?>"/>
		</p>-->

        <p>
            <label for="<?php echo $this->get_field_id('id'); ?>"><?php _e('Banner-ID:'); ?></label>
            <select id="<?php echo $this->get_field_id('id'); ?>"
                    name="<?php echo $this->get_field_name('id'); ?>">
                <?php
                if ($banners) {
                    foreach ($banners as $banner) {
                        ?>
                        <option
                            <?php echo $banner->id == $id ? "selected" : "" ?>
                            value="<?php echo $banner->id ?>">
                            <?php echo $banner->id ?>
                        </option>
                        <?php
                    }
                }
                ?>
            </select>
        </p>
        <?php
    }

// Updating widget replacing old instances with new
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        //$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['id'] = (!empty($new_instance['id'])) ? (int)$new_instance['id'] : '';

        return $instance;
    }


}