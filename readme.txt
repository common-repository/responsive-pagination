=== Responsive Pagination ===
Contributors: sasikirono
Tags: pagination, responsive, mobile
Requires at least: 3.5.0
Tested up to: 5.8
Stable tag: 1.4.1
Requires PHP: 5.3
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Configure your paginations to adapt to different screen size like phones, tablets, and desktops.

== Description ==

Responsive Pagination plugin lets you configure your paginations to adapt to different screen size. Your paginations can be shown differently based on browser width as in responsive web design concept.

For instance, you might want to have a longer pagination with many page numbers when your site visitors are using desktops and large screens, but need shorter pagination with only *Prev* and *Next* link when they're using phones.

You may try using this plugin when you found your theme is not doing well with the responsive design on its pagination. This plugin lets you configure manually to the desired breakpoints.


### Two Different Methods to Apply Responsive Pagination ###

You can choose the way you want to apply responsive pagination.

#### Method A : Convert Existing Paginations (No Coding Required)  ####

This method will convert the existing paginations from your theme into responsive paginations without needs to add any WordPress shortcode or PHP code. You only need to fill the selectors (*CSS* or *jQuery*-like selector) of the existing pagination elements. You'll input the selectors in Admin Settings Page (*Settings > Responsive Pagination*)

#### Method B : Create new Pagination Programmatically from Scratch ####

This method will need you to insert PHP code into template files directly using *Responsive Pagination API*. This method supports queries using *WP_Query*, or even something more generic without *WP_Query*.


### Responsive Pagination API (For Programmatic Usage) ###

*Note : This API section is a short guide for creating responsive pagination programmatically. However, there is easier solution using this plugin without touching any code - that is by converting your theme's existing pagination from within Admin Settings Page with just providing its CSS/jQuery selector.*

If you want to create responsive pagination programmatically, this plugin adds new function for you to use, which will render a new pagination where you put the function.

~~~
<?php create_responsive_pagination( $id, $args ) ?>
~~~

Parameters :

* `$id`: *(string)* *(required)* ID for the new pagination you want to create in *kebab-case* format.
* `$args` : *(WP_Query | array)* *(required)* [WP_Query](https://developer.wordpress.org/reference/classes/wp_query/) instance, or an associative array contains :

    - `$current` : *(int)* Current page
    - `$total`: *(int)* Total pages
    - `$urlFirstPage` : *(string)* URL for first page
    - `$urlPattern`: *(string)* URL pattern for this pagination by using `{pagenum}` tag.

Note : Pagination settings and Breakpoint Configurations are still configured from within Admin Settings Page.


#### Example (For Programmatic Usage) ####

Example for creating pagination for posts within main loop using *WP_Query*. This also works with custom post type as long as you have *WP_Query* within loop.

~~~
<?php
  global $wp_query;   // or some custom WP_Query instance
  if( function_exists( 'create_responsive_pagination' ) ) {
      create_responsive_pagination( 'my-pagination-id', $wp_query );
  }
?>
~~~

Example for creating a more generic pagination by providing your own data for current page, total pages, URL first page, and URL pattern without *WP_Query*.
~~~
<?php
  if( function_exists( 'create_responsive_pagination' ) ) {
      create_responsive_pagination( 'my-pagination-id', array(
        'current'         => $my_current_page,   // your current page here
        'total'           => $my_total_pages,    // your total page here
        'url_first_page'  => 'https://www.example.com/archives/',   // URL when current page = 1
        'url_pattern'     => 'https://www.example.com/archives/page/{pagenum}'   // the pattern using {pagenum} tag
      ) );
  }
?>
~~~


== Screenshots ==

1. Pagination example on Desktop
2. Pagination example on Tablet
3. Pagination example on Phone
4. Admin - Convert Paginations
5. Admin - Pagination Settings
6. Admin - Pagination Settings (Breakpoint Configurations)


== Frequently Asked Questions ==

= Where can I found the Admin Settings Page =

In the admin, go to "Settings > Responsive Pagination"

= What is "320px and up", "720px and up", etc ? =

Those are the breakpoints you'll want to configure. Each denotes the minimum width of viewport where the corresponding configuration will be applied until the next breakpoint. The highest breakpoint configuration will be applied for all bigger viewport.

For instance, if there are 3 breakpoints (320px, 720px, and 1024px), then 

* "320px and up" configuration is applied to viewport width of 320px - 719px
* "720px and up" configuration is applied to viewport width of 720px - 1023px.
* "1024px and up" configuration is applied to viewport width of 1024px and more


= What is "tags" ? =

A tag in Responsive Pagination is like a variable where it holds a value and will be rendered with the actual value. `{current}` and `{total}` are the example of tags.

So, if the current page is 5 and total page is 12, then `"Page {current} of {total}"` will render "Page 5 of 12".



= My pagination is responsive now, but I want to customize it more =

If you want to apply more custom styling, you can set additional class to the components from Admin Settings, and apply manual CSS.


== Installation ==

The installation is pretty standard, just like any other plugins.

1. install the plugin through the WordPress admin on 'Plugins' screen directly, or upload the plugin files to the `/wp-content/plugins/responsive-pagination` directory.
2. Activate the plugin through the 'Plugins' screen in WordPress admin.
3. Use "Settings > Responsive Pagination" screen within WordPress admin to configure the plugin.


== Changelog ==

= 1.4.1 =
* Fix: Responsive issue on Firefox in 1 pixel below lowest breakpoint

= 1.4.0 =

* Feature : Take approach to avoid theme styling interference as possible
* Improvement: Breakpoint Configurations now in separate tab
* Improvement: Change in Pagination Settings looks
* Improvement: Active vertical menu style when in focus
* Improvement: Active vertical menu go to right tab after adding & removing breakpoints
* Improvement: Add footer signature

= 1.3.0 =

* Feature: Introduce visual styling feature
* Feature: Different items width for every component in every breakpoint (pagenumbers, prevNext link, and firstLast link)
* Feature: Different items height in every breakpoints
* Feature: Different space between items (gutter) in every breakpoints
* Tweak: Pagination settings initial values

= 1.2.0 =

* First public release.