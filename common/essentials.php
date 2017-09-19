<?php
	/*
		Essential Functions: http://www.wpbeginner.com/wp-tutorials/25-extremely-useful-tricks-for-the-wordpress-functions-file/
	*/

	/*
	1. Remove WordPress Version Number

	You should always use the latest version of WordPress. However, you may still want to remove the WordPress version number from your site. Simply add this code snippet to your functions file.
	*/
	function wpb_remove_version() {
		return '';
	}
	add_filter('the_generator', 'wpb_remove_version');

	/*
	2. Add a Custom Dashboard Logo

	Want to white label your WordPress admin area? Adding a custom dashboard logo is the first step in the process.

	First you’ll need to upload your custom logo to your theme’s images folder as custom-logo.png. Make sure your custom logo is 16×16 pixels in size.
	*/

	function wpb_custom_logo() {
		echo '
		<style type="text/css">
			#wpadminbar #wp-admin-bar-wp-logo > .ab-item .ab-icon:before {
				background-image: url(' . get_bloginfo('stylesheet_directory') . '/images/custom-logo.png) !important;
				background-position: 0 0;
				color:rgba(0, 0, 0, 0);
			}

			#wpadminbar #wp-admin-bar-wp-logo.hover > .ab-item .ab-icon {
				background-position: 0 0;
			}
		</style>
		';
	}
	add_action('wp_before_admin_bar_render', 'wpb_custom_logo');

	/*
	3. Change the Footer in WordPress Admin Panel

	The footer in WordPress admin area shows the message ‘Thank you for creating with WordPress’. You can change it to anything you want by adding this code.
	*/

	function remove_footer_admin() {
		echo 'Fueled by <a href="http://www.wordpress.org" target="_blank">WordPress</a> | WordPress Tutorials: <a href="http://www.wpbeginner.com" target="_blank">WPBeginner</a></p>';
	}
    add_filter('admin_footer_text', 'remove_footer_admin');

    /*
    * Remove fixed height on images
    * http://www.codeinwp.com/blog/wordpress-image-code-snippets/
    */
    function remove_width_attribute( $html ) {
        $html = preg_replace( '/(width|height)="\d*"\s/', "", $html );
        return $html;
    }

    add_filter( 'post_thumbnail_html', 'remove_width_attribute', 10 );
    add_filter( 'image_send_to_editor', 'remove_width_attribute', 10 );

    /*
    * Remove P tags from Images
    */
    function filter_ptags_on_images($content){
        return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
    }
    add_filter('the_content', 'filter_ptags_on_images');

    /*
    * Theme titles
    */
    function theme_default_title_settings($title,$separator)
    {
        if(is_feed()){
            return $title;
        }

        global $page;

        //Add Tagline and Name of Blog
        $site_name          = get_bloginfo('name','display');
        $site_description   = get_bloginfo('description');
        $separator          = '&bull';

        $current_page_name = get_the_title(get_the_ID());

        if(is_single() || is_page()){
            $title = "$current_page_name $separator $site_name";
        }

        if($site_description && (is_home() || is_front_page())){
            $title = "$site_name $separator $site_description";
        }

        return $title;
    }
    add_filter('wp-title','theme_default_title_settings',10,2);

    /*
        Destroy Oringial Image and Only put Large
    */
    function replace_uploaded_image($image_data) {
        // if there is no large image : return
        if (!isset($image_data['sizes']['large'])) return $image_data;

        // paths to the uploaded image and the large image
        $upload_dir = wp_upload_dir();
        $uploaded_image_location = $upload_dir['basedir'] . '/' .$image_data['file'];
        $large_image_filename = $image_data['sizes']['large']['file'];

        // Do what wordpress does in image_downsize() ... just replace the filenames ;)
        $image_basename = wp_basename($uploaded_image_location);
        $large_image_location = str_replace($image_basename, $large_image_filename, $uploaded_image_location);

        // delete the uploaded image
        unlink($uploaded_image_location);

        // rename the large image
        rename($large_image_location, $uploaded_image_location);

        // update image metadata and return them
        $image_data['width'] = $image_data['sizes']['large']['width'];
        $image_data['height'] = $image_data['sizes']['large']['height'];
        unset($image_data['sizes']['large']);

        // Check if other size-configurations link to the large-file
        foreach($image_data['sizes'] as $size => $sizeData) {
            if ($sizeData['file'] === $large_image_filename)
                unset($image_data['sizes'][$size]);
        }

        return $image_data;
    }
    add_filter('wp_generate_attachment_metadata', 'replace_uploaded_image');

    //enable theme functionality
    add_theme_support( 'post-thumbnails' );

    //Enable title Flexibility
    //https://teamtreehouse.com/library/seo-for-wordpress/enhancing-the-seo-of-a-wordpress-theme/title-tags-in-wordpress
    add_theme_support( 'title-tag' );

    /*
    *
    */
    add_filter( 'img_caption_shortcode', 'cleaner_caption', 10, 3 );
    function cleaner_caption( $output, $attr, $content )
    {

        /* We're not worried abut captions in feeds, so just return the output here. */
        if (is_feed())
            return $output;

        /* Set up the default arguments. */
        $defaults = array(
            'id' => '',
            'align' => 'alignnone',
            'caption' => ''
        );

        /* Merge the defaults with user input. */
        $attr = shortcode_atts($defaults, $attr);

        /* If the width is less than 1 or there is no caption, return the content wrapped between the [caption]< tags. */

        //if (1 > $attr['width'] || empty($attr['caption']))
        //    return $content;

        /* Set up the attributes for the caption <div>. */
        $attributes = (!empty($attr['id']) ? ' id="' . esc_attr($attr['id']) . '"' : '');
        $attributes .= ' class="wp-caption uk-thumbnail ' . esc_attr($attr['align']) . '"';

        /* Open the caption <div>. */
        $output = '<div' . $attributes . '>';

        /* Allow shortcodes for the content the caption was created for. */
        $output .= do_shortcode($content);

        /* Append the caption text. */
        $output .= '<p class="uk-thumbnail-caption uk-margin-small">' . $attr['caption'] . '</p>';

        /* Close the caption </div>. */
        $output .= '</div>';

        /* Return the formatted, clean caption. */
        return $output;
    }
