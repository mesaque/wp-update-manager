<?php
/*
Plugin Name: Picasa Photos
Plugin URI: http://www.sandaru1.com/2009/10/18/picasa-wordpress-widget-updated/
Description: Pick photos from picasa albums and display them randomly
Author: Sandaruwan Gunathilake
Version: 1.3.2.1
Author URI: http://www.sandaru1.com
*/

/*  Copyright 2009  Sandaruwan Gunathilake  (sandaruwan@gunathilake.com)

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


class PicasaPhotos extends WP_Widget {
	function PicasaPhotos() {
  	parent::WP_Widget(false,'Picasa Photos',array('description' => 'Pick photos from picasa albums and display them randomly'));	
	}

	function widget($args, $instance) {
  	extract($args);
  	$title = apply_filters('widget_title', $instance['title']);
  	$album = $instance['album'];
  	$size = $instance['size'];
  	$count = $instance['count'];
  	$thickbox = $instance['thickbox'];
  	
		$photos_raw = (array) get_option('widget_picasa_data');
		$photos = array();
	  if ($album=='All') {
	    foreach($photos_raw as $photo_album)
 			  $photos = array_merge($photos,$photo_album);
 		} else {
 		  $photos = $photos_raw[$album];
 		}
		
    echo $before_widget;
    echo $before_title.$title.$after_title;

		for($i=0;$i<$count;$i++) {
   		$selected = $photos[rand(0,count($photos)-1)];

			$photo = $selected['photo'];
			if ($size!=288) {
				$photo = str_replace('s288','s'.$size,$photo);
			}
			if ($thickbox) {
    		$link = str_replace('s288','s1024',$selected['photo']);
  		} else {
  			$link = $selected['link'];
  		}
  ?>
      <p style="text-align:center">
        <a <?php echo ($thickbox?'class="thickbox"':''); ?> href="<?php echo $link; ?>" rel="picasa-photo">
          <img border="0" src="<?php echo $photo; ?>" alt="<?php echo $selected['title']; ?>"/>
        </a>
      </p>
  <?php
		}
    echo $after_widget;
	}

	function update($new_instance, $old_instance) {
	  return $new_instance;
	}

	function form($instance) {
	  $albums = (array) get_option('widget_picasa_albums');
	  $sizes = array('400','200','160','150','72');
	
	  $title = esc_attr($instance['title']);
	  $album = esc_attr($instance['album']);
	  $size = esc_attr($instance['size']);
	  $thickbox = esc_attr($instance['thickbox']);
	  $count = esc_attr(isset($instance['count'])?$instance['count']:5);
	  ?>
      <p>
        <label for="<?php echo $this->get_field_id('title'); ?>">
          <?php _e('Title:'); ?>
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </label>
      </p>
      <p>
        <label for="<?php echo $this->get_field_id('count'); ?>">
          <?php _e('Number of Photos:'); ?>
          <input class="widefat" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo $count; ?>" />
        </label>
      </p>
      <p>
        <label for="<?php echo $this->get_field_id('album'); ?>">
          <?php _e('Album:'); ?>
          <select class="widefat" id="<?php echo $this->get_field_id('album'); ?>" name="<?php echo $this->get_field_name('album'); ?>">
            <?php
              foreach($albums as $cur) {
                echo '<option value="'.$cur.'" '.($cur==$album?'selected':'').'>'.$cur.'</option>';
              }
            ?>
          </select>
        </label>
      </p>
      <p>
        <label for="<?php echo $this->get_field_id('size'); ?>">
          <?php _e('Image Size:'); ?>
          <select class="widefat" id="<?php echo $this->get_field_id('size'); ?>" name="<?php echo $this->get_field_name('size'); ?>">
            <?php
              foreach($sizes as $cur) {
                echo '<option value="'.$cur.'" '.($cur==$size?'selected':'').'>'.$cur.'</option>';
              }
            ?>
          </select>
        </label>
      </p>
      <p>
        <input type="checkbox" name="<?php echo $this->get_field_name('thickbox'); ?>" id="<?php echo $this->get_field_id('thickbox'); ?>" class="checkbox" <?php checked( $thickbox ); ?>/>
        <label for="<?php echo $this->get_field_id('thickbox'); ?>">Show in Thickbox</label>
      </p>
	  <?php
	}
}

function picasa_photos_options() {
  $options = (array)get_option('widget_picasa_photos');
	if ( isset($_POST['picasa-submit']) ) {
    $options['user'] = strip_tags(stripslashes($_POST['picasa_user']));
    $options['delay'] = strip_tags(stripslashes($_POST['picasa_delay']));
		update_option('widget_picasa_photos', $options);
    $options = picasa_photos_download();
	} else if ( isset($_POST['picasa-refresh']) )  {
    $options = picasa_photos_download(true);
	}
?>
  <div class="wrap">
    <div class="icon32" id="icon-options-general"><br/></div>  
    <h2>Picasa Photos</h2>
    <?php
      if (isset($options['error'])) {
    ?>
        <p class="error"><?php echo $options['error']; ?></p>
    <?php
      }
    ?>
    <form method="post" enctype="multipart/form-data">
      <table class="form-table">
        <tr valign="top">
          <th scope="row"><label for="user">Picasa Username</label></th>
          <td>
            <input type="text" class="regular-text" value="<?php echo $options['user']; ?>" id="picasa_user" name="picasa_user"/>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row"><label for="user">Photo cache expires in</label></th>
          <td>
            <input type="text" class="regular-text" value="<?php echo $options['delay']; ?>" id="picasa_delay" name="picasa_delay"/> days
            <?php
              if (isset($options['last'])) {
            ?>
              <br/>
              <span class="description">Last Download At <?php echo date("l dS \of F Y h:i:s A",$options['last']); ?></span>
            <?php
              }
            ?>
          </td>
        </tr>
      </table>
      <p class="submit">
        <input type="submit" value="Save Changes" class="button-primary" name="picasa-submit"/>
        <input type="submit" value="Refersh Cache" classs="button-secondary" name="picasa-refresh" />
      </p>
    </form>
  </div>
<?php
}

function picasa_photos_download($force=false) {
  $options = (array) get_option('widget_picasa_photos');
  
  // check the delay and last download date
  if (!$force) {
  	$delay = (is_numeric($options['delay'])?$options['delay']:1)*24*60*60;
	  if ($options['last']=="") 
	  	$options['last'] = 0;
	  if ((time() - $options['last'])<$delay) 
	    return $options;
	}

  $users=split(';',$options['user']);
  $albums = array('All');
  $photos = array();
  foreach($users as $user) {
    // get albums
    $rss = fetch_feed("http://picasaweb.google.com/data/feed/base/user/{$user}?alt=rss&kind=album&hl=en_US&access=public");
    if (!method_exists($rss,"get_items")) {
      $options['error'] = 'Invalid picasa username';
      return $options;
    }
    $items = $rss->get_items(); 
    if (is_array($items)) {
      foreach($items as $item) {
        $guid = $item->get_id();
        $album = $item->get_title();
        $albums[] = $album; // add album to the list
        
        // get the photos RSS feed
        $rss2 = fetch_feed(str_replace("entry","feed",$guid)."&kind=photo");
        if (!method_exists($rss2,"get_items")) // download error
          continue;
        
        $photos[$album] = array();
        $items2 = $rss2->get_items();
        foreach($items2 as $item2) {
				  preg_match('/.*src="(.*?)".*/',$item2->get_description(),$sub);
					$photos[$album][]=array("photo" => $sub[1],"link" => $item2->get_link(),"title" => $item2->get_title());
        }
      }
    }
  }

  // update last downloaded date
  $options['last']=time();
  update_option('widget_picasa_photos', $options);
  
  // update list of albums
  update_option('widget_picasa_albums',$albums);

  // update list of photos
  update_option('widget_picasa_data',$photos);
  
  return $options;
}

function PicasaPhotosInit() {
  register_widget('PicasaPhotos');
}

function picasa_photos_init() {
  wp_enqueue_style('thickbox');
  wp_enqueue_script('thickbox');
}

function picasa_admin_menu() {
  add_options_page('Picasa Photos options', 'Picasa Photos', 'administrator', basename(__FILE__), 'picasa_photos_options');
}

function picasa_photos_head() {
?>
<script type="text/javascript">
  jQuery(document).ready(
    function() {
      window.tb_pathToImage = "<?php echo get_bloginfo('wpurl'); ?>/wp-includes/js/thickbox/loadingAnimation.gif";
      window.tb_closeImage = "<?php echo get_bloginfo('wpurl'); ?>/wp-includes/js/thickbox/tb-close.png";
    }
  );
</script>
<?php
}

add_action('admin_menu', 'picasa_admin_menu');
add_action('widgets_init', 'PicasaPhotosInit');
add_action('init', 'picasa_photos_init');
add_action('wp_head', 'picasa_photos_head');
?>
