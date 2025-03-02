=== Admin Bar Queries ===
Contributors: carmelosantana
Donate link: http://carmelosantana.com/
Tags: adminbar, mysql queries, script timer, debugging, mysql, timer_stop, php execution time, php timer
Requires at least: 3.1
Tested up to: 3.9.1
Stable tag: 0.5.21
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

MySQL queries and load details added to your admin bar.

== Description ==

Adds MySQL queries, rendering time (in seconds), and CPU load to your admin bar. If installed on a multi-site installation, output is restricted to super admins.

== Installation ==

1. Install via the WordPress admin panel or download from the repository and upload to your '/wp-content/plugins/' directory.
2. Activate the plugin through the 'Plugins' menu in WordPress

== Screenshots ==

1. MySQL queries and time (in seconds) spent rendering page output.

== Changelog ==

= 0.5.21 =
* Fixed Invalid argument supplied for foreach() when sys_getloadavg() is FALSE

= 0.5.2 =
* Added CPU load as drop down element. 

= 0.5.1 =
* Fixed compatibility with php 5.2.4.
* Changed timer action output priority to 999.
* Reduced space needed for timer output.

= 0.5 =
* Initial release