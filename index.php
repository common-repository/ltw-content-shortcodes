<?php
/*
Plugin Name: LTW Content Shortcodes
Plugin URI: http://www.lessthanweb.com/wordpress-plugins/content-shortcodes
Description: LTW Content Shortcodes is a lightweight plugin that contains some useful shortcodes to enrich your content quick and easy.
Version: 1.0.3
Author: LessThanWeb
Author URI: http://www.lessthanweb.com
Text Domain: ltw_cs
License: GPL2
*/
/*  Copyright 2013  LessThanWeb  (email : contact@lessthanweb.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 *	Main class that contains all the functions.
 *
 */
class ltw_content_shortcodes
{
	const plugin_version = '1.0.3';

	//	Tabs container array
	private $tabs_container = array('counter' => 0, 'tabs' => array());

	/**
	 * Constructor
	 *
	 * @since	1.0
	 *
	 */
	function __construct()
	{
		add_action('plugins_loaded', array($this, 'init'));

		//	Load front-end core scripts/styles
		add_action('wp_enqueue_scripts', array($this, 'load_scripts_styles'), 1);

		//	Shortcode for columns
		add_shortcode('column', array($this, 'column_shortcode'));

		//	Shortcodes for tabs
		add_shortcode('tabs', array($this, 'tabs_shortcode'));
		add_shortcode('tab', array($this, 'tab_shortcode'));

		//	Shortcode for buttons
		add_shortcode('button', array($this, 'button_shortcode'));

		//	Toggle shortcode
		add_shortcode('toggle', array($this, 'toggle_shortcode'));
	}

	/**
	 * Init
	 *
	 * @param	void
	 * @return	void
	 * @since	1.0
	 *
	 */
	public function init()
	{
		//	Make plugin translatable
		load_plugin_textdomain('ltw_cs', FALSE, dirname(plugin_basename(__FILE__)).'/languages/');
	}

	/**
	 * Loads the required JS/CSS files that the shortcodes need. :)
	 *
	 * @param	void
	 * @return	void
	 * @since	1.0
	 *
	 */
	public function load_scripts_styles()
	{
		//	First load jQuery if the theme doesn't load it..
		wp_enqueue_script('jquery');

		//	Register the functions.js file that has all the JS code for the plugins
		wp_register_script('ltw_content_shortcodes', plugins_url('js/functions.js', __FILE__), FALSE, self::plugin_version, TRUE);
		wp_enqueue_script('ltw_content_shortcodes');

		//	Make functions.js string translatable
		$translations = array(
			'toggle_open' => __('Open', 'ltw_cs'),
			'toggle_close' => __('Close', 'ltw_cs')
		);
		wp_localize_script('ltw_content_shortcodes', 'ltw_function', $translations);

		//	Register the default CSS styles
		wp_register_style('ltw_content_shortcodes', plugins_url('css/styles.css', __FILE__), FALSE, self::plugin_version);
       	wp_enqueue_style('ltw_content_shortcodes');
	}

	/**
	 * Output button for shortcode 'button'
	 *
	 * @param	array	$attr
	 * @return	void
	 * @since	1.0
	 *
	 */
	public function button_shortcode($attr = array())
	{
		//	Each button must have at least value and link, without it don't output the button
		if ((isset($attr['value']) == FALSE || mb_strlen(trim($attr['value'])) == 0) || (isset($attr['href']) == FALSE || mb_strlen(trim($attr['href'])) == 0))
		{
			return '';
		}

		$attr['value'] = sanitize_text_field($attr['value']);

		//	If no color is set, use blue
		$color = isset($attr['color']) == TRUE ? 'button_'.esc_attr($attr['color']) : 'button_blue';

		//	Check if we have custom class attribute set
		$custom_class = isset($attr['class']) == TRUE ? ' '.esc_attr($attr['class']) : '';

		//	Check if we have button ID attribute set
		$custom_id = isset($attr['id']) == TRUE ? ' id="'.esc_attr($attr['id']).'"' : '';

		//	Check if we have button target attribute set
		$custom_target = isset($attr['target']) == TRUE ? ' target="'.esc_attr($attr['target']).'"' : '';

		//	Check if we have button onclick attribute set
		$custom_onclick = isset($attr['onclick']) == TRUE ? ' onclick="'.stripslashes(esc_js($attr['onclick'])).'"' : '';

		//	Check if we have button rel attribute set
		$custom_rel = isset($attr['rel']) == TRUE ? ' rel="'.esc_attr($attr['rel']).'"' : '';

		$button_code = '<p><a class="button '.$color.$custom_class.'"'.$custom_id.' href="'.esc_url($attr['href']).'"'.$custom_target.$custom_rel.$custom_onclick.'>'.esc_html($attr['value']).'</a></p>';

		return $button_code;
	}

	/**
	 * Columns. Position and sizes are defined in attributes.
	 *
	 * @param	array	$attr
	 * @param	string	$content
	 * @return	string
	 * @since	1.0
	 *
	 */
	public function column_shortcode($attr = array(), $content = '')
	{
		//	If column size is not set, just return the content
		if (isset($attr['size']) == FALSE)
		{
			return $content;
		}

		$column_first = '';
		$column_last = '';

		//	Check if position is set
		if (isset($attr['position']) == TRUE)
		{
			//	Check if this is the first or last column
			if ($attr['position'] == 'first')
			{
				$column_first = '<div class="columns_wrapper">';
			}

			if ($attr['position'] == 'last')
			{
				$column_last = '</div>';
			}
		}

		$column_output = $column_first.'<div class="column_container column_'.str_replace('/', '_', $attr['size']).'">'.do_shortcode($content).'</div>'.$column_last;

		return $column_output;
	}

	/**
	 * Toggle shortcode.
	 *
	 * @param	array	$attr
	 * @param	string	$content
	 * @return	string
	 * @since	1.0
	 *
	 */
	public function toggle_shortcode($attr = array(), $content = '')
	{
		//	Check if the box should be open or closed by default
		if (isset($attr['status']) == TRUE)
		{
			if ($attr['status'] == 'open')
			{
				$status_style = ' style="display: block;"';
				$status = '<span class="close">'.__('close', 'ltw_cs').'</span>';
			}
			else
			{
				$status_style = '';
				$status = '<span class="open">'.__('open', 'ltw_cs').'</span>';
			}
		}
		else
		{
			$status_style = '';
			$status = '<span class="open">'.__('open', 'ltw_cs').'</span>';
		}

		//	Check if float value is set
		if (isset($attr['float']) == TRUE && $attr['float'] == 'left')
		{
			$float_style = 'float: left;';
		}
		else if (isset($attr['float']) == TRUE && $attr['float'] == 'right')
		{
			$float_style = 'float: right;';
		}
		else
		{
			$float_style = '';
		}

		//	Check if width value is set
		if (isset($attr['width']) == TRUE && mb_strlen($attr['width']) > 0)
		{
			$width_style = 'width: '.$attr['width'].';';
		}
		else
		{
			$width_style = '';
		}

		//	Check if margin value is set
		if (isset($attr['margin']) == TRUE && mb_strlen($attr['margin']) > 0)
		{
			$margin_style = 'margin: '.$attr['margin'].';';
		}
		else
		{
			$margin_style = '';
		}

		//	Check if any of the custom styles are set, if so, use them else not
		if (mb_strlen($float_style) > 0 || mb_strlen($width_style) > 0 || mb_strlen($margin_style) > 0)
		{
			$toggle_wrapper_style = ' style="'.$float_style.$width_style.$margin_style.'"';
		}
		else
		{
			$toggle_wrapper_style = '';
		}

		//	Check if user added custom title or not, by default it should display "Toggle"
		if (isset($attr['title']) == TRUE && mb_strlen($attr['title']) > 0)
		{
			$title = $attr['title'];
		}
		else
		{
			$title = __('Toggle', 'ltw_cs');
		}

		$toggle_id = mt_rand();

		$output = '<div id="toggle_'.$toggle_id.'" class="toggle_wrapper"'.$toggle_wrapper_style.'>';
		$output .= '<div class="toggle_header">'.$title.' '.$status.'</div>';
		$output .= '<div id="toggle_'.$toggle_id.'_content" class="toggle_content"'.$status_style.'>'.do_shortcode($content).'</div>';
		$output .= '</div>';

		return $output;
	}

	/**
	 * Tabs container
	 *
	 * @param	array	$attr
	 * @param	string	$content
	 * @return	string
	 * @since	1.0
	 *
	 */
	public function tabs_shortcode($attr = array(), $content = '')
	{
		$output = '';

		//	Check if title value is set
		if (isset($attr['title']) == TRUE && mb_strlen($attr['title']) > 0)
		{
			$output = '<h3>'.$attr['title'].'</h3>';
		}

		//	Check if float value is set
		if (isset($attr['float']) == TRUE && $attr['float'] == 'left')
		{
			$float_style = 'float: left;';
		}
		else if (isset($attr['float']) == TRUE && $attr['float'] == 'right')
		{
			$float_style = 'float: right;';
		}
		else
		{
			$float_style = '';
		}

		//	Check if width value is set
		if (isset($attr['width']) == TRUE && mb_strlen($attr['width']) > 0)
		{
			$width_style = 'width: '.$attr['width'].';';
		}
		else
		{
			$width_style = '';
		}

		//	Check if margin value is set
		if (isset($attr['margin']) == TRUE && mb_strlen($attr['margin']) > 0)
		{
			$margin_style = 'margin: '.$attr['margin'].';';
		}
		else
		{
			$margin_style = '';
		}

		//	Check if any of the custom styles are set, if so, use them else not
		if (mb_strlen($float_style) > 0 || mb_strlen($width_style) > 0 || mb_strlen($margin_style) > 0)
		{
			$tab_wrapper_style = ' style="'.$float_style.$width_style.$margin_style.'"';
		}
		else
		{
			$tab_wrapper_style = '';
		}

		//	do_shortcode does the "tab" shortcodes that put data into array
		$tab_result = do_shortcode($content);

		//	Get the tabs array
		$tab_container = $this->tabs_container['tabs'];

		//	If we have more then 1 tab present, output it
		if (count($tab_container) > 0)
		{
			//	Tabs wrapper
			$output .= '<div id="tabs_'.$this->tabs_container['counter'].'" class="tabs_container"'.$tab_wrapper_style.'>';

			//	Tabs are "li"
			$output .= '<ul class="tabs_list">';

			//	Loop over tabs and check if any of them is set as active.
			$have_active_tab = FALSE;

			foreach ($tab_container as $key => $value)
			{
				if ($value['tab']['active'] == TRUE)
				{
					$have_active_tab = TRUE;
					break;
				}
			}

			//	If we don't have active tab, set the first one as active
			if ($have_active_tab == FALSE)
			{
				if (isset($tab_container[0]['tab']['active']) == TRUE)
				{
					$tab_container[0]['tab']['active'] = TRUE;
				}
			}

			//	Loop over tabs and create the HTML code
			foreach ($tab_container as $key => $value)
			{
				//	Check if current tab is active
				$tab_active = '';

				if ($value['tab']['active'] == TRUE)
				{
					$tab_active = ' class="tab_active"';
				}

				//	Each tab has it's own ID number that matches the number of the content
				$output .= '<li'.$tab_active.'><a href="#tab_'.$this->tabs_container['counter'].'_'.$key.'" rel="tab_'.$this->tabs_container['counter'].'_'.$key.'">'.$value['tab']['title'].'</a></li>';
			}

			$output .= '</ul>';

			//	Output the tabs content
			$output .= '<div class="tab_content_container">';

			//	Loop over tabs content and create the HTML code
			foreach ($tab_container as $key => $value)
			{
				//	Check if current tab is active
				$content_tab_active_style = '';

				if ($value['tab']['active'] == TRUE)
				{
					$content_tab_active_style = ' style="display: block;"';
				}

				$output .= '<div id="content_tab_'.$this->tabs_container['counter'].'_'.$key.'" class="content_tab"'.$content_tab_active_style.'>'.$value['content']['content'].'</div>';
			}

			//	Tabs content end
			$output .= '</div>';

			//	Tabs wrapper end
			$output .= '</div>';
		}

		//	Increase the tab counter if the page contains more then 1
		$this->tabs_container['counter']++;

		//	Reset the tabs array
		$this->tabs_container['tabs'] = array();

		return $output;
	}

	/**
	 * Output the tabs
	 *
	 * @param	array	$attr
	 * @param	string	$content
	 * @return	string
	 * @since	1.0
	 *
	 */
	public function tab_shortcode($attr = array(), $content = '')
	{
		//	If tab title is not set, dont use it!
		if (isset($attr['title']) == FALSE)
		{
			return '';
		}

		$this->tabs_container['tabs'][] = array(
			'tab' => array(
				'title' => $attr['title'],
				'active' => isset($attr['active']) == TRUE ? TRUE : FALSE
			),
			'content' => array(
				'content' => do_shortcode($content)
			)
		);

		return '';
	}
}
$ltw_content_shortcode = new ltw_content_shortcodes();
?>