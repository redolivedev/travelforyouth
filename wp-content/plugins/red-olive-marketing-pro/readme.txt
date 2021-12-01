=== Plugin Name ===
Contributors: Michael Bonner, Blake McGillis
Tags: marketing
Requires at least: 4.2
Tested up to: 5.0.3
Stable tag: 4.9.1
License: Proprietary

A plugin to help with Red Olive marketing functions

== Description ==

This plugin helps with the following:
* Google analytics integration
* Call tracking integration
* Stop comment spam
* Auto update plugins

== Changelog ==

= 1.4.26 =
* Added post selection capabilities for the header and footer scripts. Optimized live chat scripts. Optimized code for bugs found.

= 1.4.26 =
* Fixed the pop up triggers.

= 1.4.23 =
* Updated the exit intent pop-up trigger to only fire once per an 8-hour period.

= 1.4.22 =
* Updated NAP builder code to not fire if there isn’t one set.

= 1.4.21 =
* Updated ACF Pro to newest version to fix bug of some ACF functions not working.

= 1.4.20 =
* Don't check for license every admin page request

= 1.4.19 =
* Fix bug showing warnings in NAP builder. 

= 1.4.18 =
* Added index.php files to directories for added security.

= 1.4.17 =
* Update NAP Builder feature to include the option to add schema in JSON header. 

= 1.4.16 =
* Updated text on Promos tab features.

= 1.4.15 =
* Added shortcode line type to Floating CTA feature.

= 1.4.14 =
* Added Comment Pop Up type to allow users to leave feedback on the site.
* Users can also opt into a mailing list when leaving a comment.

= 1.4.13 =
* Update license check to deactivate invalid or expired licenses.

= 1.4.12 =
* Update Deactivate License button to still work if the request fails.

= 1.4.11 =
* Added settings link for plugin on Installed Plugins page.

= 1.4.10 =
* Added Mailing List content type to Pop Ups feature.
* Fixed choppy display for pop up on initial load.
* Updated MailChimp Widget feature to show a spinner while the email is being sent to MailChimp.
* Updated pop up SESSION data to be unique to each pop up to avoid conflicts.

= 1.4.9 =
* Increased timeout on MailChimp list AJAX call. And added spinner gif while it runs. 

= 1.4.8 =
* Adjusted Floating CTA styles for mobile to avoid issue when displaying on screens 1024px wide.

= 1.4.7 =
* Added more schema options to NAP Builder.

= 1.4.6 =
* Fixed bug where a blank excluded page URL string on Floating CTA or Pop Ups settings page caused every page to match the exclusion.

= 1.4.5 =
* Added Custom CSS fields to Pop Up Ads Feature.

= 1.4.4 =
* Fixed bug causing license to deactivate when saving changes on the settings tabs.

= 1.4.3 =
* Added Display URL String and Exclude URL String configuration options to Pop Up and Floating CTA features.

= 1.4.2 =
* Added new options to Pop Up feature. 

= 1.4.1 =
* Updated ACF version to 5.6.7.
* Updated Floating CTA feature to use its own custom post type.
* Added configuration options to Floating CTA to specify which pages display each CTA.

= 1.4.0 =
* Added NAP Builder feature to Local SEO tab.

= 1.3.2 =
* Fixed bug in Pop Up feature which only showed five pages to select from in page select dropdown.
* Converted Pop Up to use ACF instead of WordPress metaboxes.
* Updated Pop Up feature to include a session-based timed pop up.
* Updated Pop Up feature to include a scroll distance-based pop up.
* Updated Pop Up feature to include an exit intent pop up. 

= 1.3.1 =
* Added additional options to Floating CTA feature

= 1.3.0 =
* Added Floating CTA feature in Promos tab

= 1.2.1 =
* Replaced "[]" array notation with "array()" array notation to ensure PHP 5.3 compatibility

= 1.2.0 =
* Added Pop Up Ads feature

= 1.1.3 =
* Updated the way the Site-Wide Banner is implemented to better accommodate sites with absolutely-positioned menus and header elements
* Updated MailChimp Widget code to show an HTML error message when credentials return false, instead of a javascript popup.
* Replaced <?= tag with <?= echo command to ensure PHP 5.3 compatibility.

= 1.1.2 =
* Updated the method of including ACF Pro when the installation does not currently have it. Includes a warning when the wrong version of ACF is installed.
* Updated MailChimp API key warning with more information. 

= 1.1.1 =
* Namespaced all classes and functions to avoid collisions with other plugins

= 1.1.0 =
* Added feature to open all external links in a new window

= 1.0.13 =
* Added a check to see if the Mobile_Detect class already exists before instantiating it

= 1.0.12 =
* Updated styles on license activation html

= 1.0.11 =
* Added null checking on license key in general tab

= 1.0.10 =
* Updated Stop Comment Spam javascript element targets. And updated the mailchimpWidget.js to target the correct element

= 1.0.9 =
* Minor code cleanup

= 1.0.8 =
* Added new line at end of init file

= 1.0.7 =
* Slightly reorganized file structure to be consistent throughout RO plugins

= 1.0.6 =
* Updated the check for the RO Marketing Free menu item to run later so that menu has a chance to get set up.

= 1.0.5 =
* Fixed bug causing plugins page to always mark the plugin ready for update

= 1.0.4 =
* Added menu to display when RO Marketing Free is not installed

= 1.0.3 =
* Added code back in to save license key when clicking activate license button

= 1.0.2 =
* Updated link to on-site text variation creator page

= 1.0.1 =
* Menu and settings page text changes

= 1.0.0 =
* Initial release

== Upgrade Notice ==
