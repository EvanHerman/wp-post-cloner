# WP Page Cloner

Clone WordPress pages with ease! Written as a small tutorial and example plugin, but ready for production if needed.

Authors: [Evan Herman](https://github.com/EvanHerman), [Ben Rothman](https://github.com/brothman01) 

#### Feature List

* Clone posts, pages and custom post types
* Full control over what is clone-able (Example: Enable cloning pages but disabled it for posts)
* Complete clone of posts/pages including taxonomies and meta data (featured image, categories, tags and any custom meta assigned to the post/page)
* Lightweight, compact solution
* High quality code adhering to WordPress Coding Standards
* Nonce checks implemented for security

#### FAQ

**How come I can't change my permalinks?**

You need to make sure that your permalinks are set to anything but the default inside of `Settings > Permalinks`. Once the permalinks are changed from the defaults you should have no problem changing the permlink of your page.

**I have (a) custom post type(s) registered on my site. Can I use this plugin to clone it?**

Yes! We've built out a settings page ('Settings > WP Page Cloner') where you can specify which post types are cloneable. By default only pages and posts are active. You can add or remove any registered post type on your site. 

**I have a whole bunch of custom meta assigned to my posts/pages/custom post type, will this plugin transfer over everything?**

Yes! We've built in full support for taxonomies and meta data for default posts, pages and even custom post types that may be specific to your theme or created via third party plugins.
