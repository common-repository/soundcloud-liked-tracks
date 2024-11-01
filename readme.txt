=== Plugin Name ===
Tags: soundcloud, slideshow, widget
Requires at least: 3.0.1
Tested up to: 4.1
Stable tag: trunk
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Widget that displays Soundcloud tracks, playlists, followed users, following users and liked tracks (favorites).

== Description ==

This plugin displays Soundcloud tracks, playlists, followed users, following users and liked tracks (favorites). It's intentionally pretty bare-bones as most other plugins out the seem to offer dozens of bells and whistles. You can specify the following parameters:

* Soundcloud User ID
* Amount of slides to be displayed
* Slider width
* Slider height
* Whether you want a player or just artwork to be shown
* Slideshow speed
* Animation speed
* What type of content will be shown (tracks, playlists, followed users, following users or favorites

== Installation ==

1. Unzip
2. Upload the folder `soundcloud-liked-tracks` to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Add the widget to a widget area
5. Provide the neccessary information

**Note:** You will need to register your own application on Soundcloud. You need to have a Soundcloud account in order to do that. I have provided an App ID from a throwaway account to get you started smoothly. It is highly recommended that you enter your own App ID.

If you don't know how to do this, please do the following:

* Log in to Soundcloud
* Click the cog symbol to access the menu
* Click "Developers"
* In the right sidebar, click on "Your Apps" and follow the instructions
* Copy the Client ID (*not* the Client Secret)
* Enter the Client ID in the Widget's App ID field

== Frequently Asked Questions ==

= The output is not displayed properly within my theme =

WordPress themes (especially the more fancy ones like Lobo or frameworks like Semplicity) are known to do many things but in their very own way. This plugin is fully compatible and tested thoroughly with Twenty Fourteen and Twenty Fifteen, the standard WordPress themes.

= Acknowledgements =

This plugin uses Flexslider (GPLv2) functionality.

== Screenshots ==

1. Settings

== Changelog ==

= 0.5.0 =
* Fixed possible collision with other plugins.
* Removed unnecessary CSS from Flexslider.
* Updated Flexslider to 2.4.0.

= 0.4.7 =
* First public release.

= 0.2 =
* Initial private release.
