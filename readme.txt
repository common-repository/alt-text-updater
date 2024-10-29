=== Alt Text Updater ===
Contributor: anushka04
Tags: alt text, accessibility, SEO, image, media
Requires at least: 5.0
Tested up to: 6.6
Requires PHP: 7.4
Stable Tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Easily bulk update image alt text in WordPress using image titles or a CSV upload. Improve accessibility and SEO with just a few clicks.

== Description ==
Alt Text Updater helps WordPress admins easily update alt text for images in bulk, improving accessibility and SEO. The plugin provides two powerful options to automate the process:

1. **Update Alt Text Based on Image Title**: Automatically update the alt text of all images in your media library using their image titles.
2. **Upload CSV to Update Alt Text**: Upload a CSV file with image URLs and corresponding alt text, and the plugin will update the alt text accordingly.

This plugin is designed for large media libraries, offering a real-time progress bar to track the update process.

**Key Features:**
- Bulk update alt text for all images.
- Update based on image title or CSV upload.
- Optimized for large media libraries.
- Real-time progress bar during updates.

== Installation ==

1. Upload the `alt-text-updater` folder to the `/wp-content/plugins/` directory, or install the plugin through the WordPress Plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Navigate to the Alt Text Updater settings page to begin updating your image alt text.

== Usage ==

1. **Update Alt Text Based on Image Title:**
   - Select the first option to automatically update the alt text of images based on their titles.

2. **Upload CSV to Update Alt Text:**
   - Select the second option to upload an Excel or CSV file.
   - **File Format:**
     - The file should contain two columns:
       1. **Image URL**: The full URL of the image in your WordPress media library.
       2. **Alt Text**: The alt text you want to assign to the corresponding image.

== Frequently Asked Questions ==

= Will the plugin work with a large media library? =
Yes, this plugin is optimized to handle large media libraries by processing updates in batches. The progress bar gives you real-time feedback on the process.

= Can I preview the changes before they are applied? =
No, the plugin does not offer a preview option. Changes will be applied immediately once the process is started.

= Does the plugin overwrite existing alt text? =
No, this plugin does not overwrite alt text that already exists. It only updates images with empty alt text fields.

== Screenshots ==
1. Plugin Interface showing the two options for updating alt text.
2. Progress bar showing the update process in real-time.
3. Sample CSV File format: Image URLs and Alt Text columns for bulk updating.

== Changelog ==

= 1.0.0 =
* Initial release of the plugin.
* Added two options for updating alt text (by title or CSV upload).
* Added real-time progress bar for updates.

== Upgrade Notice ==
Initial release. Always back up your media library and database before running bulk updates.

== License ==
This plugin is licensed under the GPLv2 or later.
