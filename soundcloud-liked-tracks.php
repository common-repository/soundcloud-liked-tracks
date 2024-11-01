<?php
/*
Plugin Name: Soundcloud Liked Tracks
Description: Widget that displays Soundcloud tracks, playlists, followed users, following users and liked tracks (favorites).
Author: Marcel Bischoff
Version: 0.5.0
Author URI: http://herrbischoff.com
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

function slt_custom_scripts() {
    wp_enqueue_script( 'flexslider', plugins_url( 'vendor/flexslider/jquery.flexslider-min.js' , __FILE__ ), array( 'jquery' ) );
    wp_enqueue_style( 'flexslider-style', plugins_url( 'vendor/flexslider/flexslider.css' , __FILE__ ) );
    wp_enqueue_style( 'plugin-style', plugins_url( 'stylesheets/plugin.css' , __FILE__ ) );
}
add_action( 'wp_enqueue_scripts', 'slt_custom_scripts' );

class soundcloud_liked_tracks extends WP_Widget {

    function soundcloud_liked_tracks() {
        $widget_ops = array(
            'classname' => 'soundcloud_liked_tracks',
            'description' => 'Displays latest Soundcloud liked tracks and other information.'
        );
        $this->WP_Widget('soundcloud_liked_tracks', 'Soundcloud Liked Tracks', $widget_ops);
    }

    function form( $instance ) {
        $instance = wp_parse_args( (array) $instance, array(
            'title' => '',
            'app_id' => '',
            'user_id' => '',
            'amount' => '',
            'width' => '',
            'height' => '',
            'player' => '',
            'slideshow_speed' => '',
            'animation_speed' => '',
            'type'
        ) );
        $title = $instance['title'];
        $app_id = $instance['app_id'];
        $user_id = $instance['user_id'];
        $amount = $instance['amount'];
        $width = $instance['width'];
        $height = $instance['height'];
        $player = $instance['player'];
        $slideshow_speed = $instance['slideshow_speed'];
        $animation_speed = $instance['animation_speed'];
        $type = $instance['type'];

        $form_title_id = $this->get_field_id( 'title' );
        $form_title_name = $this->get_field_name( 'title' );

        $form_app_id = $this->get_field_id( 'app_id' );
        $form_app_name = $this->get_field_name( 'app_id' );

        $form_user_id = $this->get_field_id( 'user_id' );
        $form_user_name = $this->get_field_name( 'user_id' );

        $form_amount_id = $this->get_field_id( 'amount' );
        $form_amount_name = $this->get_field_name( 'amount' );

        $form_width_id = $this->get_field_id( 'width' );
        $form_width_name = $this->get_field_name( 'width' );

        $form_height_id = $this->get_field_id( 'height' );
        $form_height_name = $this->get_field_name( 'height' );

        $form_player_id = $this->get_field_id( 'player' );
        $form_player_name = $this->get_field_name( 'player' );

        $form_slideshow_speed_id = $this->get_field_id( 'slideshow_speed' );
        $form_slideshow_speed_name = $this->get_field_name( 'slideshow_speed' );

        $form_animation_speed_id = $this->get_field_id( 'animation_speed' );
        $form_animation_speed_name = $this->get_field_name( 'animation_speed' );

        $form_type_id = $this->get_field_id( 'type' );
        $form_type_name = $this->get_field_name( 'type' );

        $form_large = '<p><label for="%s">%s: </label><input class="widefat" name="%s" type="text" value="%s"></p>';
        $form_small = '<p><label for="%s">%s: </label><input name="%s" type="text" value="%s" size="3"></p>';

        echo sprintf($form_large, $form_title_id, 'Title', $form_title_name, $title);
        echo sprintf($form_large, $form_app_id, 'Soundcloud App ID', $form_app_name, $app_id);
        echo sprintf($form_large, $form_user_id, 'Soundcloud User ID', $form_user_name, $user_id);
        echo sprintf($form_small, $form_amount_id, 'How many should be displayed', $form_amount_name, $amount);
        echo sprintf($form_small, $form_width_id, 'Width', $form_width_name, $width);
        echo sprintf($form_small, $form_height_id, 'Height', $form_height_name, $height);
        echo sprintf($form_small, $form_slideshow_speed_id, 'Slideshow speed', $form_slideshow_speed_name, $slideshow_speed);
        echo sprintf($form_small, $form_animation_speed_id, 'Animation speed', $form_animation_speed_name, $animation_speed);
?>
    <p>
        <label for="<?php echo $form_player_id ?>">Content </label>
        <select class="widefat" name="<?php echo $form_player_name ?>">
            <option value="sc-player" <?php selected($player, "sc-player"); ?>>Soundcloud Player</option>
            <option value="artwork" <?php selected($player, "artwork"); ?>>Artwork</option>
        </select>
    </p>

    <p>
        <label for="<?php echo $form_type_id ?>">What should be displayed </label>
        <select class="widefat" name="<?php echo $form_type_name ?>">
            <option value="list_of_tracks" <?php selected($type, "list_of_tracks"); ?>>List of tracks of the user</option>
            <option value="list_of_playlists" <?php selected($type, "list_of_playlists"); ?>>List of playlists (sets) of the user</option>
            <option value="list_of_followed" <?php selected($type, "list_of_followed"); ?>>List of users who are followed by the user</option>
            <option value="list_of_followers" <?php selected($type, "list_of_followers"); ?>>List of users who are following the user</option>
            <option value="list_of_favorites" <?php selected($type, "list_of_favorites"); ?>>List of tracks favorited by the user</option>
        </select>
    </p>

<?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        $instance['app_id'] = $new_instance['app_id'];
        $instance['user_id'] = $new_instance['user_id'];
        $instance['amount'] = $new_instance['amount'];
        $instance['width'] = $new_instance['width'];
        $instance['height'] = $new_instance['height'];
        $instance['player'] = $new_instance['player'];
        $instance['slideshow_speed'] = $new_instance['slideshow_speed'];
        $instance['animation_speed'] = $new_instance['animation_speed'];
        $instance['type'] = $new_instance['type'];
        return $instance;
    }

    function widget($args, $instance) {
        extract($args, EXTR_SKIP);

        echo $before_widget;
        $title = empty( $instance['title'] ) ? ' ' : apply_filters( 'widget_title', $instance['title'] );
        $app_id = $instance['app_id'];
        $user_id = $instance['user_id'];
        $amount = $instance['amount'];
        $width = $instance['width'];
        $height = $instance['height'];
        $player = $instance['player'];
        $slideshow_speed = $instance['slideshow_speed'];
        $animation_speed = $instance['animation_speed'];
        $type = $instance['type'];

        if ( $app_id == '' )
            $app_id = "d6839ea654fb953952c5799a50a9afb4";

        if ( $user_id == '' )
            $user_id = 'djshadow';

        if ( $amount == '' )
            $amount = '5';

        if ( $width == '' )
            $width = '100%';

        if ( $height == '')
            $height = '241px';

        if ( $player == '' )
            $player = 'sc-player';

        if ( $slideshow_speed == '' )
            $slideshow_speed = 7000;

        if ( $animation_speed == '' )
            $animation_speed = 600;

        if ( $type == '' )
            $type = 'list_of_favorites';

        if ( !empty( $title ) )
            echo $before_title . $title . $after_title;

        if ( $type == 'list_of_tracks' ) :
            $api_url = 'https://api.soundcloud.com/users/' . $user_id . '/tracks?client_id=' . $app_id;
        elseif ( $type == 'list_of_playlists' ) :
            $api_url = 'https://api.soundcloud.com/users/' . $user_id . '/playlists?client_id=' . $app_id;
        elseif ( $type == 'list_of_followed' ) :
            $api_url = 'https://api.soundcloud.com/users/' . $user_id . '/followings?client_id=' . $app_id;
        elseif ( $type == 'list_of_followers' ) :
            $api_url = 'https://api.soundcloud.com/users/' . $user_id . '/followers?client_id=' . $app_id;
        elseif ( $type == 'list_of_favorites' ) :
            $api_url = 'https://api.soundcloud.com/users/' . $user_id . '/favorites?client_id=' . $app_id;
        endif;

        $json = file_get_contents( $api_url );
        $scdata = json_decode( $json );

        echo '<div class="slt-slider" style="width: ' . $width . '; height: ' . $height . ';"><ul class="slides">';

        $i = 1;
        foreach ( $scdata as $item ) :
            if ( $i <= $amount ) :
                if ( $type == 'list_of_followed' || $type == 'list_of_followers' ) :
                    $user_avatar = str_replace( 'large', 't500x500', $item->avatar_url );
                    $user_link = $item->permalink_url;
                    echo '<li><a href="' . $user_link . '" target="_blank"><img src="' . $user_avatar . '"></a></li>';
                else :
                    if ( $player == 'artwork' ) :
                        $track_title = $item->title;
                        $track_artwork = str_replace( 'large', 't500x500', $item->artwork_url );
                        $track_link = $item->permalink_url;
                        echo '<li><a href="' . $track_link . '" target="_blank" style="background-image: url(' . $track_artwork . ');"></a></li>';
                    elseif ( $player == 'sc-player' ) :
                        echo '<li>';
                        echo '<iframe
                                style="position: relative;"
                                width="100%"
                                height="300"
                                scrolling="no"
                                frameborder="no"
                                src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/' . $item->id . '&amp;auto_play=false&amp;hide_related=true&amp;show_comments=false&amp;show_user=true&amp;show_reposts=false&amp;visual=true">
                              </iframe>';
                        echo '</li>';
                    else :
                        echo '<li>Please select content type.</li>';
                    endif;
                endif;
                $i++;
            endif;
        endforeach;

        echo "</ul></div>";

        echo $after_widget;

        echo '
        <script>
            jQuery(window).load(function() {
                jQuery(".slt-slider").flexslider({
                    animation: "slide",
                    animationSpeed: ' . $animation_speed .',
                    controlNav: false,
                    directionNav: false,
                    keyboard: false,
                    pauseOnHover: true,
                    slideshowSpeed: ' . $slideshow_speed . ',
                    video: true
                });
            });
        </script>
        ';
    }

}
add_action( 'widgets_init', create_function('', 'return register_widget("soundcloud_liked_tracks");') );?>
