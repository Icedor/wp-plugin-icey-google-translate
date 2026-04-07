=== Icey for Google Translate ===

Contributors: icedor
Tags: google translate, translation, multilingual, language
Requires at least: 5.5
Tested up to: 6.9
Requires PHP: 7.0
Stable tag: 1.0.03
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Integrates Google Translate into WordPress through a modal with a configurable language selector.

== Description ==

This plugin integrates Google Translate into a WordPress site through a modal in place of the default Google Translate widget.

The plugin provides a settings page where you can configure the modal heading, explanation text, and button labels, set the site's default language, and choose which languages appear in the dropdown. Languages in the dropdown can be reordered using drag and drop. A field for custom CSS is included for adjusting the appearance of the modal.

Translation is performed by Google Translate. Visitors select a target language in the modal, and the page is reloaded with the selected translation applied.

== Usage ==

To open the modal from a menu item or link, add the CSS class `icey_language_toggle` to the link. Clicking the link will open the language selection modal.

== Installation ==

1. Find the plugin in the WordPress repository or upload the plugin folder to `/wp-content/plugins/`.
2. Go to the **Plugins** page in your WordPress admin and activate the plugin.

== Frequently Asked Questions ==

= How do I uninstall the plugin? =
Simply deactivate and delete it from the **Plugins** page.

== Localization ==
* English (default)
* Swedish (`sv_SE`)

== Screenshots ==

None

== Upgrade Notice ==

None

== Changelog ==

= 1.0.02 - 2026-04-07 =
* Initial plugin release.