=== Plugin Name ===
Contributors: LessThanWeb
Donate link: http://www.lessthanweb.com/wordpress-plugins/content-shortcodes
Tags: content, shortcode, tags, columns, toggle, button
Requires at least: 3.5
Tested up to: 3.6
Stable tag: 1.0.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

LTW Content Shortcodes is a lightweight plugin that contains some useful shortcodes to enrich your content quick and easy.

== Description ==

LTW Content Shortcodes is a lightweight plugin that contains some useful shortcodes to enrich your content quick and easy.

Plugin contains 4 shortcodes which are *tabs*, *columns*, *buttons* and *toggle*.

Here are a few examples.

**Tabs:**
[tabs title="Tab Example" float="left" width="500px" margin="0 20px 20px 0"]
[tab title="Tab 1"]Tab 1 Content[/tab]
[tab title="Tab 2" active="true"]Tab 2 Content[/tab]
[tab title="Tab 3"]Tab 3 Content[/tab]
[/tabs]

**Columns:**
[column position="first" size="1/2"]Column 1[/column]
[column position="last" size="1/2"]Column 2[/column]

[column position="first" size="1/4"]Column 1[/column]
[column position="last" size="3/4"]Column 2[/column]

**Buttons**
[button href="http://www.google.com" value="Button" color="black" rel="nofollow" target="_blank" onclick="alert('Button Was Clicked!');" class="custom_class" id="btn_id"]

**Toggle**
[toggle title="Toggle Example" float="left" width="500px" margin="0 20px 20px 0" status="open"]Toggle content[/toggle]

For full list of options and what they do, please visit the plugin website.

== Installation ==

1. Upload `ltw-content-shortcodes` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Can I have multiple tab containers on one page? =

Short and sweet: Yes.

= Can the currently active tab be opened by default just by sharing a URL link? =

Yes. Tabs will check if there is an id of the tag in the URL (added automatically when a tab is clicked) and will set that tab as active on page load even if you set a different tab as active in shortcode.

= Can a button include Javascript on click? =

Yes. Include this shortcode attribute: onclick="alert('Button Was Clicked!');"

= Can the toggle be open by default? =

Yes. Set the shortcode attribute status="open".

== Screenshots ==

1. Shortcode example

== Changelog ==

= 1.0.3 =
* Modifed JavaScript to make the click on the tab affect the tab elements only.

= 1.0.2 =
* New Feature - Using shortcodes inside tabs, columns and toggle is now possible.

= 1.0.1 =
* Removed ( ) from "open" and "close" links.

= 1.0 =
* First release

== Upgrade Notice ==

= 1.0.3 =
Small JavaScript modification that affected the tabs.

= 1.0.2 =
Using shortcodes inside tabs, columns and toggle is now possible.

= 1.0.1 =
Small update that removes the ( ) from "open" and "close" links.

= 1.0 =
First release