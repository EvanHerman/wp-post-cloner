=== Post Cloner === 
Contributors: eherman24, brothman01
Donate link: https://www.evan-herman.com/contact/?contact-reason=I%20want%20to%20make%20a%20donation%20for%20all%20your%20hard%20work
Tags: clone, duplicate, copy, post, posts, page, pages, custom post type, cpt, custom post types, custom, post, type, cpts, duplcator, duplication, copies
Requires at least: 3.5
Tested up to: 4.3.1
Stable tag: 0.1
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Post cloner allows you to easily make complete duplicates of any post on your site. That includes posts, pages and custom post types.

== Description ==

Post Cloner will create a quick to access action button on the post/page edit screen alongside 'Edit', 'Quick Edit', 'Trash' and 'View'. 

All cloned posts, pages and custom post types will have '- Clone' appended to the end of their title. All cloned posts, pages and custom post types will also be set to draft so they don't appear anywhere on your site until you decide to publish them.

><strong>Features</strong>

>- Clone posts, pages and custom post types
>- Full control over what is clone-able (Example: Enable cloning pages but disabled for posts)
>- Complete clone of posts/pages including taxonomies and meta data (includes featured images, categories, tags and any custom metadata assigned to the post/page/custom post type)
>- Lightweight, compact solution
>- Nonce checks implemented for security
>- High quality code adhering to [WordPress Coding Standards](https://make.wordpress.org/core/handbook/best-practices/coding-standards/)


**FuturePlans**

- Clone [Easy Digital Downloads](https://wordpress.org/plugins/easy-digital-downloads/) products
- Clone [WooCommerce](https://wordpress.org/plugins/woocommerce/) products
- Additional settings to adjust post data of the new cloned post

== Installation ==

<strong>Installation</strong>

1. Upload `wp-post-cloner.zip` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to the 'Settings >WP Post Cloner' to toggle which post types can be cloned.
4. Head into Posts, Pages or any Custom Post Type list page. Hover over any post and click 'Clone'.
5. ???
6. Prosper

== Frequently Asked Questions ==

= How come I can't change my permalinks? =

You need to make sure that your permalinks are set to anything but the default inside of `Settings > Permalinks`. Once the permalinks are changed from the defaults you should have no problem changing the permlink of your page.

= I have (a) custom post type(s) registered on my site. Can I use this plugin to clone it? =

Yes! We've built out a settings page (`'Settings > WP Post Cloner'`) where you can specify which post types are cloneable. By default only pages and posts are active. You can add or remove any registered post type on your site. 

= My custom post type doesn't have a 'Duplicate' button. Why not? =

You need to ensure that you've assigned the psot type as 'clone-able' on the settings page. Head into `'Settings > WP Post cloner'` and click inside the input field to reveal a full list of registered post types. You'll want to make sure that your custom post type appears on this list.

= I have a whole bunch of custom meta assigned to my posts/pages/custom post type, will this plugin transfer over everything? = 

Yes! We've built in full support for taxonomies and meta data for default posts, pages and even custom post types that may be specific to your theme or created via third party plugins.

== Screenshots ==

1. Example of the 'Clone Page' button on the Pages list page.
2. Example of the 'Checkout' page after a successful clone.
3. WP Post Cloner settings page, enabling the clone function on the 'Custom Post Type Example' post type.

== Changelog ==

= 0.1 =

- Initial realease of Post Cloner

== Upgrade Notice ==

= 0.1 =

- Initial realease of Post Cloner