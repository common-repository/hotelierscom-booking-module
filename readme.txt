=== Hoteliers.com Booking Module ===
Contributors: Hoteliers.com
Tags: hoteliers, hoteliers.com, booking, module
Requires at least: 4.0
Tested up to: 5.1
Requires PHP: 5.6
Stable tag: 1.10.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin lets you place the Hoteliers.com Booking Engine in your widgets.

== Description ==

This plugin is deprecated! You can find a new booking script in the <a href="https://hoteliers.atlassian.net/wiki/spaces/DOCS/pages/996802760/Booking+Engine+Button">Hoteliers.com Support Center</a>


== Installation ==

= Requirements =

Make sure you have PHP 5.4 or above. The plugin won't work on PHP 5.3 or less and will be deactivated automatically.

= From your WordPress dashboard =

1. Visit 'Plugins > Add New'
2. Search for 'Hoteliers.com Booking Module'
3. Activate Hoteliers.com Booking Module from your Plugins page

= From WordPress.org =

1. Download Hoteliers.com Booking Module
2. Upload the `hotelierscom-booking-module` directory to the `/wp-content/plugins/` directory, using your favourite method (ftp, sftp, scp, etc...)
3. Activate Hoteliers.com Booking Module from your Plugins page

= Once Activated =

You will find the 'Booking Module' menu in your WordPress admin panel. Enter the details on the settings page to setup the booking module.
The Custom CSS area can be used to override default CSS of the plugin to customize the look-and-feel of the booking module.

== Upgrade Notice ==

If you previously installed the Hoteliers.com Booking Module from the Hoteliers.com Support Center, please keep in mind that your current settings and custom CSS might get lost during upgrade.


== Screenshots ==

1. Admin settings panel

== Changelog ==

= 1.10.3 =

* Set plugin as deprecated

== Changelog ==

= 1.10.2 =

* Set plugin as deprecated

= 1.10.1 =

* Avoid unnecessary loading of some CSS assets

= 1.10.0 =

* Switched from id-selectors to class-selectors to allow multiple (working) bookers on a single page
* Dropped support for PHP <= 5.5

= 1.9.0 =

* Updated FancyBox to 3.5.6 to avoid issues with recent Chrome updates

= 1.8.0 =

* Monday is now the first day of the week in datepickers (not locale dependent anymore)
* Datepickers are now shown in the language selected in the widget settings

= 1.7.3 =

* Fixed version in plugin file

= 1.7.2 =

* Updated branding
* Tested up to 5.0

= 1.7.1 =

* Version bump

= 1.7.0 =

* Added input field for your Google Analytics code, this will be used if Google Analytics is available on your website

= 1.6.4 =

* Fixes issue with our Booking Engine reminder caused by a recent Google Chrome update

= 1.6.3 =

* Increased the maximum number of characters for the default promotion code to 15

= 1.6.2 =

* Fixed a bug which caused problems with saving certain settings

= 1.6.1 =

* Added default option to language setting in widget settings

= 1.6.0 =

* Moved language setting from admin panel to widget settings which allows you to use the widget in a multi-language installation
* Tested up to WordPress 4.9.1

= 1.5.3 =

* Tested up to WordPress 4.9

= 1.5.2 =

* Fixes weird display glitch in Internet Explorer

= 1.5.1 =

* Size of fancyBox 3 can now be overridden through CSS settings
* Upgraded fancyBox from 3.0.29 to 3.0.47

= 1.5.0 =

* It is now possible to show the booking module multiple times on a single page
* Cleaned up Javascript a little

= 1.4.3 =

* Added a different translation to use as placeholder of hotel select element
* Avoid unnecessary loading of assets

= 1.4.2.1 =

* Bugfix in checkboxes for 'Show booking engine reminder' and 'Show promotion code'

= 1.4.2 =

* PHP 5.4 compatibility fix

= 1.4.1.1 =

* Fixes another fancyBox loading issue

= 1.4.1 =

* Avoid zoom on mobile devices when pointing a datepicker field
* Search in queued scripts instead of registered scripts when registering fancyBox script

= 1.4.0.2 =

* Version bump

= 1.4.0.1 =

* Fixed menu structure

= 1.4.0 =

* Several text fields in the widget can now be translated
* Added Spanish as language option
* Bugfix for keyboard showing up on small screen devices when trying to select an arrival or departure date

= 1.3.2 =

* Changed PHP version requirement check
* Deactivate plugin if PHP 5.3 or lower is installed

= 1.3.1 =

* Made Custom CSS textarea a little wider
* Show default hotel selection in widget area only if there are multiple hotels setup

= 1.3.0.1 =

* Fix for empty Chain ID

= 1.3.0 =

* Make some settings overridable on widget level
* Added possibility to set a Chain ID in admin panel
* Load default styles in admin panel if no styles have been setup
* Fix for grid system

= 1.2.1.1 =

* Bugfix for full screen fancyBox
* Changed page title of admin page

= 1.2.1 =

* Make fancyBox full screen

= 1.2.0 =

* Added option to enable booking engine reminder
* Added a screenshots
* Cleanup some HTML in admin page
* Another bugfix in readme

= 1.1.6.1 =

* Bugfix in readme

= 1.1.6 =

* Added more detailed installation instructions

= 1.1.5 =

* Fix for grid system

= 1.1.4 =

* Added activation hook to check for PHP version

= 1.1.3 =

* Removed some useless and reset CSS
* Removed unused JS
* Fixed paths to some images
* Changed registration of fancyBox to optional (only registered if no handle containing 'fancybox' is found) to avoid collisions with some themes

= 1.1.2 =

* Fix for an encoding issue in the widget

= 1.1.1 =

* Bugfix in queueing custom CSS

= 1.1.0 =

* Added possibility to add some custom CSS to overwrite plugin styles

= 1.0.2 =

* Fixed compatibility with Fancybox v1

= 1.0.1 =

* Fixed issue where HotelID might not be passed to booking engine

= 1.0.0 =

* Initial Release
