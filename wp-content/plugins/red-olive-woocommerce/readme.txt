=== Plugin Name ===
Contributors: Michael Bonner
Tags: woocommerce
Requires at least: 4.0
Tested up to: 4.7.4
Stable tag: 4.7.4
License: Proprietary

A plugin to help with woocommerce functions

== Description ==

This plugin helps with the following:
* Google analytics ecommerce integration
* Adwords Tracking Pixel
* Customer lifetime value

To run RO WooCommerce, you need to install our free prerequisite plugin, RO Marketing Free, first. 
You can download it here: https://wordpress.org/plugins/red-olive-marketing/

== Changelog ==

= 1.13.5 =
* Changed URL of the shopping feed to remove URL query strings.

= 1.13.4 =
* Added index.php files to directories for added security.

= 1.13.3 =
* Added column to WooCommerce Orders page to show Recovered Carts.
* Added RO Recovered Carts tab to Orders section of WooCommerce Reports.

= 1.13.2 =
* Added Size definition for Google Product feed.

= 1.13.1 =
* Added Age Group and Color definitions for Google Product feed.

= 1.13.0 =
* Added Google Customer Reviews feature in PPC tab.
* Updated wording of "Google AdWords" to "Google Ads".

= 1.12.9 =
* Refactoring and bug fixes in abandoned cart feature.

= 1.12.8 =
* Updated MailChimp API key section to display an HTML message instead of a javascript alert.
* Updated abandoned cart to not send a 7 day email on the same day as a 3 day email.
* Updated abandoned cart to not send 3 day emails after 5 days and to not send 7 day emails after 10 days.
* Updated abandoned cart to remove duplicate rows from the database table.

= 1.12.7 =
* Updated Product Feed to use whichever currency is configured in the WooCommerce settings instead of assuming USD.

= 1.12.6 =
* Added alternate Product Feed link.

= 1.12.5 =
* Updated license check to deactivate invalid or expired licenses.

= 1.12.4 =
* Updated Deactivate License button to still work if the request fails.

= 1.12.3 =
* Added GTIN field to Google Product Feed.

= 1.12.2 =
* Added settings link for plugin on Installed Plugins page.

= 1.12.1 =
* Added some null checks and default values to avoid errors in Recover Abandoned Cart feature.

= 1.12.0 =
* Added Rich Snippets tab to allow users to include Product Schema information on product pages.

= 1.11.11 =
* Fixed bug causing license to deactivate when saving changes on the settings tabs.

= 1.11.10 =
* Added check for RO_EXECUTION_TIME constant on public product feed page.

= 1.11.9 =
* Updated URL coupon popup to include a message to add products to the cart, except when user is on the cart page with products in the cart already.

= 1.11.8 =
* Fixed bug in URL Coupons feature code that prevented confirmation popup from running when products were added to the cart with the coupon
* Replaced <?= tag with <?= echo command to ensure PHP 5.3 compatibility.

= 1.11.7 =
* Namespaced all classes and functions to avoid collisions with other plugins.

= 1.11.6 =
* Updated public product feed to accommodate older versions of WooCommerce.

= 1.11.5 =
* Updated Abandoned Cart feature to add emails to abandoned cart table when email field is autofilled or user is logged in.

= 1.11.4 =
* Updated return to cart code to redirect user back to the cart page without the URL params so the user can remove cart items. 
* Updated text on Lifetime Value section of General tab. 

= 1.11.3 =
* Added constant to help check to make sure WooCommerce is active. Fixed bug in URL Coupons feature. Updated Add Email to MailChimp feature to be compliant with newer WooCommerce versions.

= 1.11.2 =
* Updated styles on license activation html.

= 1.11.1 =
* Added null checking on license key in general tab.

= 1.11.0 =
* Added email template preview feature.

= 1.10.4 =
* Updated Bing Ads script to use the correct WooCommerce Order object method calls.

= 1.10.3 =
* Updated AdWords Tracking Pixel description in PPC tab. And updated WooCommerce function calls in script.

= 1.10.2 =
* Updated settings page styles in RO WooCommerce for readability.

= 1.10.1 =
* Updated product export script to run on the wp hook. And updated product SKU call to use a method rather than accessing the property directly.

= 1.10.0 =
* Removed Facebook pixel feature along with Social tab to add them to RO Marketing Free.

= 1.9.11 =
* Minor code cleanup.

= 1.9.10 =
* Renamed settings page file.

= 1.9.9 =
* Reorganized file structure to be consistent with other RO plugins.

= 1.9.8 =
* Updated text in PPC tab.

= 1.9.7 =
* Updated the check for the RO Marketing Free menu item to run later so that menu has a chance to get set up.

= 1.9.6 =
* Fixed bug causing plugins page to always mark the plugin ready for update.

= 1.9.5 =
* Updated Abandoned Cart settings options in email tab. And updated abandoned cart email template.

= 1.9.4 =
* Added menu to display when RO Marketing Free is not installed.

= 1.9.3 =
* Added code back in to save license key when clicking activate license button.

= 1.9.2 =
* Menu and settings text changes.

= 1.9.1 =
* Updated text on General tab in Google Analytics section.

= 1.9.0 =
* Reorganized and refactored WooCommerce tabs.

= 1.8.8 =
* Added option to set minimum price for the Google product feed items.

= 1.8.7 =
* Added option in product feed public to use a defined constant ( RO_MEMORY_INCREASE ) to increase the memory limit.

= 1.8.6 =
* Reworked custom labels code to work correctly.

= 1.8.5 =
* Added functionality to add the correct custom labels for sites with more than 1000 products.

= 1.8.4 =
* Updated product feed to limit the length of product fields.

= 1.8.3 =
* Renamed product feed element guid to g:id.

= 1.8.2 =
* Fixed bug which prevented public product feed from cutting down title lengths.

= 1.8.1 =
* Better escaping in product feed.

= 1.8.0 =
* Added feature to add customer email to a specified MailChimp list on checkout success.

= 1.7.0 =
* Added URL Products feature.

= 1.6.33 =
* Removed test code from Abandoned Cart Feature.

= 1.6.32 =
* Added new configuration options for Abandoned Cart Feature.

= 1.6.31 =
* Updated Abandoned Cart feature to accept templates and send emails at different intervals.

= 1.6.30 =
* Fixed bug with custom label 0 pricing.

= 1.6.29 =
* Updated product feed to display product variations.
* Updated product feed to display Post ID as MPN if no SKU is available.
* Truncated custom labels to stay below 100 characters.

= 1.6.28 =
* Fixed some bugs with default values in Abandoned Cart feature.

= 1.6.27 =
* Updated the Test Email functionality of the Abandoned Cart feature.

= 1.6.26 =
* Removed feature better suited for RO Marketing plugin.

= 1.6.25 =
* Updated Google Product Feed to have the product short description as a description option.

= 1.6.24 =
* Updated default setting of address autocomlete option.

= 1.6.23 =
* Added Address Autocomplete option.

= 1.6.22 =
* Added settings page for RO WooCommerce which displays an error message if WooCommerce is not installed.

= 1.6.21 =
* Added backup money_format function back into the plugin. I am not sure how it was removed in the first place.

= 1.6.20 =
* Updated settings tab js to use new settings page body class.

= 1.6.19 =
* Finished integrating MailChimp into Abandoned Cart feature.

= 1.6.18 =
* Removed some testing code.

= 1.6.17 =
* Added preliminary MailChimp integration to Abandoned Cart feature.

= 1.6.16 =
* Added Exclude Product button to Product Feed section of Product pages. When checked, product will not be included in public product feed.

= 1.6.15 =
* Added code to prevent Abandoned Cart code from firing when the feature is disabled.

= 1.6.14 =
* Added Facebook Conversion Pixel to Thank You page.

= 1.6.13 =
* Fixed typo in Apply Coupon code.

= 1.6.12 =
* Updated Apply Coupon button code to work correctly.

= 1.6.11 =
* Added code to fire Apply Coupon button if the coupon field is in focus when the user presses enter.

= 1.6.10 =
* Remove database logging code for Abandoned cart.

= 1.6.9 =
* Remove debug code for Abandoned cart.

= 1.6.8 =
* Added test version of Cart Abandonment code.

= 1.6.7 =
* Fixed bug in product feed which caused a warning message to block headers.

= 1.6.6 =
* Updated adwords pixel public to include dynamic conversion ID and conversion label.

= 1.6.5 =
* Fixed bug which prevented infinite scrolling on pages with only 2 total pages.

= 1.6.4 =
* Fix url coupon conflict with subscriptions.

= 1.6.3 =
* Update to ensure that infinite scroll code only runs when infinite scroll is enabled.

= 1.6.2 =
* Update to better restrict scroll events in infinite scroll.

= 1.6.1 =
* Fixed coding in variable declarations.

= 1.6.0 =
* Added Infinite Scroll feature.

= 1.5.9 =
* Overriding css for url coupon pop up.

= 1.5.8 =
* More array shorthand fixes.

= 1.5.7 =
* Fixed bug in which server did not like array shorthand.

= 1.5.6 =
* Fixed bug preventing RoWooCommerce form from being submitted.

= 1.5.5 =
* Fix line item total for Google Analytics.

= 1.5.4 =
* New popup to alert users of coupon being added.

= 1.5.3 =
* Fixed typo in length restriction code.

= 1.5.2 =
* Updated product feed public to restrict length of product title and description.

= 1.5.1 =
* Added product import option in Product Feeds section.

= 1.5.0 =
* URL coupons.

= 1.4.19 =
* Added product export link in Product Feeds section.

= 1.4.18 =
* Fixing issue caused by multiple versions of 1.4.17.

= 1.4.17 =
* Added additional images to public feed and updated priority for product descriptions.

= 1.4.16 =
* Add email preview functionality.

= 1.4.15 =
* Changed priority for product descriptions.

= 1.4.14 =
* Added global description to public feed.

= 1.4.13 =
* Added global description field.

= 1.4.12 =
* Fixed bug in null checking in textLimit.js.

= 1.4.11 =
* Added null checks in textLimit.js.

= 1.4.10 =
* Added remaining elements to Google product feed.

= 1.4.9 =
* Added global brand and global category options.

= 1.4.8 =
* Change location where items are added to product_array to keep everything in the channel element.

= 1.4.7 =
* Extended SimpleXMLElement class to add function to include CDATA.

= 1.4.6 =
* Added namespace as third parameter in SimpleXMLElement addChild function.

= 1.4.5 =
* Fixed bug which prevented admin tabs from firing.

= 1.4.4 =
* Add htmlspecialchars to individual fields.

= 1.4.3 =
* Remove htmlspecialchars from feed.

= 1.4.2 =
* New fields for custom title and description for product feeds.

= 1.4.1 =
* Handle empty price values better.

= 1.4.0 =
* Google product feeds.

= 1.3.2 =
* Old php version compatibility.

= 1.3.1 =
* Prevent errors on undefined option indexes.

= 1.3.0 =
* Add option to clear expired WC sessions.

= 1.2.5 =
* Fix bug reporting revenue in adwords tracking pixel.

= 1.2.4 =
* Fix bug in updating lifetime values.

= 1.2.3 =
* Add count of customers with more than one order.

= 1.2.2 =
* Add link to lifetime value page from settings page.

= 1.2.1 =
* Missed a couple semi-colons.

= 1.2 =
* Added Adwords Tracking Pixel.

= 1.1.2 =
* Fixed a typo.

= 1.1.1 =
* Changed to manage_woocommerce capability.

= 1.0 =
* Initial release.

== Upgrade Notice ==

= 1.1.1 =
Improves reliability.
