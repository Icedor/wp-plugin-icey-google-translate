=== Icey for Google Translate ===

Contributors: Icedor
Tags: google translate, translation, multilingual, language
Requires at least: 5.5
Tested up to: 6.9  
Requires PHP: 7.0
Stable tag: 1.0.18
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Integrates Google Translate into WordPress through a modal with a configurable language selector.

== Usage ==

To open the modal from a menu item or link, add the CSS class `icey_language_toggle` to the link. Clicking the link will open the language selection modal.

== Description ==

This plugin integrates Google Translate into a WordPress site through a modal in place of the default Google Translate widget.

The plugin provides a settings page where you can configure the modal heading, explanation text, and button labels, set the site's default language, and choose which languages appear in the dropdown. Languages in the dropdown can be reordered using drag and drop.
Visitors select a target language in the modal, and the page is reloaded with the selected translation applied.

== External Services ==

This plugin relies on the Google Translate service to provide automated translations. 

* Service: Google Translate (https://translate.google.com)
* Purpose: To provide on-the-fly translation of website content.
* Data Sent: When a translation is requested, the service may collect the user's IP address, browser information, and the URL of the page being translated. It also sets a 'googtrans' cookie to remember the user's language preference.
* Google Terms of Service: https://policies.google.com/terms
* Google Privacy Policy: https://policies.google.com/privacy

== Installation ==

1. Find the plugin in the WordPress repository or upload the plugin folder to `/wp-content/plugins/`.
2. Go to the **Plugins** page in your WordPress admin and activate the plugin.

== Frequently Asked Questions ==

= How do I uninstall the plugin? =
Simply deactivate and delete it from the **Plugins** page. Leaves nothing behind.

== Localization ==
* English (default)
* Swedish (`sv_SE`)

== Screenshots ==

1. The language selection modal in action.

== Upgrade Notice ==

None

== Changelog ==

= 1.0.18 - 2026-04-13 =
* Added shortcode and extended translation *

= 1.0.17 - 2026-04-09 =
* Fix minor scrpt bug *

= 1.0.16 - 2026-04-09 =
* Uninstall script to leave a blank slate *

= 1.0.15 - 2026-04-09 =
* Fix typos *

= 1.0.14 - 2026-04-09 =
* Minor bugfixes *

= 1.0.13 - 2026-04-08 =
* Final first version :) *

= 1.0.11 - 2026-04-08 =
* Added explicit documentation for external services (Google Translate).
* Removed custom CSS functionality to comply with WordPress guidelines.

= 1.0.1 - 2026-04-07 =
* Initial plugin release.