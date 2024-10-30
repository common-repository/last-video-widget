<?php
/*
Plugin Name: Last Video Widget
Plugin URI: http://yorik.uncreated.net
Description: A widget that displays the last post af a category and resize its video. Viper links plugin needed.
Version: 0.1
Author: Yorik van Havre
Author URI: http://yorik.uncreated.net
*/

/*  Copyright 2009 Yorik van Havre  (email : yorik at uncreated dot net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class Last_Video_Widget extends WP_Widget {
  function Last_Video_Widget() {
    $widget_ops = array('classname' => 'Last_Video_Widget', 'description' => 'A widget that displays your last video post and resizes its youtube video' );
    $this->WP_Widget('last_video', 'Last Video Widget', $widget_ops);
  }
 
  function widget($args, $instance) {
    extract($args, EXTR_SKIP);
    $title = empty($instance['title']) ? '&nbsp;' : apply_filters('widget_title', $instance['title']);
    $category = empty($instance['category']) ? '&nbsp;' : $instance['category'];
    $width = empty($instance['width']) ? '&nbsp;' : $instance['width'];

    if ( empty( $category ) ) { $category = '1'; }
    $category = (int)$category;
    $argstring = 'numberposts=1&category='.$category;
    if ( empty( $width) ) { $width = '100'; }
    $width = (int)$width;
    $height = (int)$width*0.8;
    $newstring = '[youtube width="'.$width.'" height="'.$height.'"]';
 
    echo $before_widget;

    if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };
    $lastcatposts = get_posts($argstring);
    $content = $lastcatposts[0]->post_content;
    $content = str_replace('[youtube]', $newstring, $content);
    $content = apply_filters('the_content',$content);
    echo $content;

    echo $after_widget;
  }
 
  function update($new_instance, $old_instance) {
    $instance = $old_instance;
    $instance['title'] = strip_tags($new_instance['title']);
    $instance['category'] = strip_tags($new_instance['category']);
    $instance['width'] = strip_tags($new_instance['width']);
 
    return $instance;
  }
 
  function form($instance) {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'category' => '', 'width' => '') );
    $title = strip_tags($instance['title']);
    $category = strip_tags($instance['category']);
    $width = strip_tags($instance['width']);
?>
      <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>

      <p><label for="<?php echo $this->get_field_id('category'); ?>">Category where to pick the latest post:
								 
      <select id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>"
          <?php 
          $current = (int)$instance['category'];
          $categories = get_categories(''); 
          foreach ($categories as $cat):
  	      $option = '<option ';
              if ( $cat->cat_ID == $current ) { $option .= 'selected '; }
              $option .= 'value="'.$cat->cat_ID.'">';
	      $option .= $cat->cat_name;
	      $option .= '</option>';
	      echo $option;
          endforeach; ?>
      </select>

      </label></p>

      <p><label for="<?php echo $this->get_field_id('width'); ?>">Width of video: <input class="widefat" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo attribute_escape($width); ?>" /></label></p>

<?php
															}
}

// register_widget('Last_video_Widget');
add_action( 'widgets_init', create_function('', 'return register_widget("Last_Video_Widget");') );

?>