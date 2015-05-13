=== LTI SEO ===
Contributors: decarvalho_bruno
Tags: open-source, SEO, meta, google, twitter, facebook, pinterest, social media, webmaster, search engine optimization, moteur, recherche, json-ld, rich snippets, meta tags, optimisation, schema.org, open graph, réseaux, sociaux, robots
Requires at least: 4
Tested up to: 4.2.2
Stable tag: 0.5.0
License: GNU General Public License, version 2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=NHYSUNN8G6M52

Search engine optimization made easy: make your content more visible in search engine results.

== Description ==

In an effort to remain as lightweight as possible, LTI SEO will only add metadata information to the html header of your pages (the data **won't be visible on the page**), and only specialized programs such as [search engine bots](https://en.wikipedia.org/wiki/Web_crawler) will be able to parse and use that information.

It turns out that this metadata is fairly easy to generate and can make a ton of difference when it comes to **the visibility of your content** on the web. Wordpress is very good at creating search engine friendly content; we simply take it one notch further!

= Available languages =

 - English
 - Français

= SEO-friendly metadata =

The following information can be added, if the corresponding option is activated:

- *Link rel* tags:
 - *Canonical*, helps search engine determine a single URL for specific content,
 - *Author*, allows search engines to link the author with their contributed content,
 - *Publisher*, helps identify the entity that's responsible for the content.
- *Keywords* meta tag,
- *Robots* meta tag:
 - NOINDEX
 - NOFOLLOW
 - NOODP
 - NOYDIR
 - NOARCHIVE
 - NOSNIPPET
- *Description* meta tag, featured in search results
- JSON-LD tags, using [Schema.org](htp://schema.org) namespace objects:
 - Front page:
  - Publisher (shown as an *Organization* : name, alias, logo image, website, social accounts),
  - Author (shown as a *Person*: public e-mail, job title, work location, social accounts),
  - Type of site (*Blog* or *WebSite*),
 - Pages and posts:
  - Type of post (*Article*, *Blog post*, *News*, *Scholarly article*, *Tech article*)
  - Author information (same person object as a above)
- [Twitter cards](https://dev.twitter.com/cards/overview):
 - *Summary card* by default
 - *Summary with large image*,
 - *Gallery* for gallery post types,
 - *Photo* for attachments
- [Open Graph](http://ogp.me/) tags:
 - Type *website* on the frontpage
 - Type *article* on posts, with attached or featured images, if any.

= Contribution =

You can help us by:

- Translating the plugin in your own language (get in touch with us for details),
- Submitting bugs and feature requests in the github project's [issue tracker](https://github.com/DeCarvalhoBruno/lti-wp-seo/issues),
- Submitting code via [pull requests](https://github.com/DeCarvalhoBruno/lti-wp-seo/pulls),
- [Visiting our blog](http://dev.linguisticteam.org) and associated resources to interact with us.

== Installation ==

The easiest way to install the plugin is to use the plugins management page in your administration panel.

Also, the package can be downloaded manually and unzipped in the /wp-content/plugins/ directory.

When resources have been copied, the plugin can be activated by looking for a "LTI SEO" entry in the plugins page and clicking on **"Activate"**.

Configure the options through Settings->LTI SEO. Note that **by default, no header tags are added to the page**. LTI SEO will only add content that you activate in the LTI SEO options page.

Clicking on the **"Deactivate"** button will disable the user profile fields and the post editing box information associated with the plugin. The **"Delete"** button will remove any LTI SEO related field in the database.

== Screenshots ==

1. Admin options, General tab
2. Admin options, Front page tab
3. Admin options, Posts and pages tab
4. Admin options, Social tab
5. Meta box in the post editing page.

== Frequently Asked Questions ==

We'll add more entries to this section as we get feedback from you.

= Yet another SEO plugin, why? =

- We wanted to contribute a distraction-free (no ads, no 'premium features'), WYSIWYG plugin.
- Provide a sturdy, testable object-oriented codebase that the community can contribute to.
- Our main concern as the [LTI](http://info.linguisticteam.org) developer community is to automate our own processes, but we also want to show that we're willing to put ourselves out there and share awesome code!

= How is that extra metadata useful? =

A lot of the traffic over the internet goes through search engines, which send hoards of little crawlers to sift through millions of pages every day.

As the provider of content, you can help search engines understand what type of content you're featuring by providing not just code, but semantic information about the content. That information will allow search engines to unmistakably determine the context in which you want this data to be shared.

Also, search engines that find relevant content on your sites are more likely to feature them prominently on search results.


== Changelog ==

- 0.5.0
  - First Version

== Upgrade Notice ==

No particular upgrade instructions.