=== Publish to Schedule ===
Contributors: alexbenfica
Tags: posts, scheduling, auto schedule, future post, periodicity, postpone, admin, productivity, publish control, schedule post, autopost, autopublish, post later, auto schedule
Requires at least: 2.8
Tested up to: 6.1.1
Stable tag: trunk
Donate link: https://www.buymeacoffee.com/FQNxAqVUTo

Say goodbye to the hassle of manually scheduling your WordPress blog posts with Publish to Schedule! With our plugin, you can easily automate your post publishing process, ensuring that your readers receive new content on a consistent and timely basis. No more wasted time trying to figure out the next post date or worrying about maintaining your post frequency.

== Description ==
Looking for a way to streamline your WordPress blog post scheduling? Look no further than Publish to Schedule!

Publish to Schedule is a powerful and flexible WordPress scheduling plugin that lets you automate your blog post publishing process. With just a few simple configurations, you can set up a schedule that works for you, ensuring that your content is consistently published on the days and times you choose.

With the ability to set specific days of the week, number of posts per day, and time intervals for scheduling, Publish to Schedule takes the guesswork out of post publishing. And if you ever need to make adjustments, the plugin provides clear and detailed information in the publish box, allowing you to easily modify dates and times as needed.

Publish to Schedule is perfect for bloggers who want to focus on creating great content, without the hassle of manual scheduling. And with its easy-to-use interface, even those with little technical knowledge can quickly get up and running.

So if you're looking to take your blog to the next level, download Publish to Schedule today and see the difference it can make for your content creation process.

== Installation ==

Installing Publish to Schedule is quick and easy. To get started, simply download the file linked above and unzip it. Then, save the file to your plugins directory under wp-content and activate the plugin in the plugin manager.

Once the plugin is activated, you can access the settings from the WordPress settings menu or the plugins administration area. From there, you can define which days of the week you want to schedule your posts and choose a time interval for automatic scheduling.

Once you've set your preferences, you're all set! When you write a new post, you'll see the scheduled date and time provided by the Publish to Schedule plugin. This will help you keep track of your content and ensure that it's consistently published on the days and times you choose.
== Frequently Asked Questions ==


= Why limit what times I publish? =

It all depends on the purpose for your writing.
Some people would want to publish at any time, but if you want to set criteria this allows it.
If your target is a business audience you might want to publish during business hours (or days) - if you have a thought outside of business hours it will be held until the next business hours.
If you are publishing for late night gamers you might want to publish after regular business hours and late into the early morning.


= How the plugin chooses the date to publish? =

It starts in the present day and runs on future dates until find one day in a week in which settings to allow publication.
If this day have not published articles, the plugin will generate a random time in the interval time you set up in the configuration.
When you click Publish, the item will be scheduled.

= Can I bypass the plugin and publish any days I want? =

Yes! Just choose an schedule date in the default WordPress scheduling controls and, when you click the Schedule button, the plugin will not act.
Your post will bu publish in the date you choose. Simple and straightforward!


= How can this help with multiple authors? =

The plugin is simple. It will schedule the post on the moment that publish button get clicked.
So, the author who writes first will have your post published first! Fair enough.. anh?


= How does randomized publishing work? =

The publish time will be random in the time interval you chose at configuration screen.


= Can I publish overnight? =

Not yet. But it is already on TODO list.


= Can I ask for changes and new functionalities on this plugin? =

Yes. You can! I'm available to talk about this! And I can do other plugins too.

= Should I donate? =

You can, if you want. I would like that. Means that my work e valuable for someone else!
My PayPal account for donations is: alexbenfica@gmail.com

= Can I translate it? =

Yes! Please help translating to other languages.
I am native in Brazilian Portuguese and do speak English, so I can take care of en->pt_BR.
Any other languages... you're welcome! Thank you!


== Screenshots ==

1. Configuration to choose which day of week post can be scheduled.
2. Set the period of day in which post may be scheduled. Simple like that.
3. This is what you'll see while write your post: the date that post will be automatically schedule, and why! To see less information change the related option in the plugin configuration.

== Changelog ==

= 4.5.5 =
* Fixing low risk vulnerability

= 4.5.4 =
* Fixing low risk vulnerability
* Removing broken links from readme.
* Improving code readability
* Changed icon and cover to use images created by Stable Diffusion AI

= 4.4.2 =
* Fix error on PHP versions configured as short_open_tag = Off (usually PHP 5.3). This error caused Publish to Schedule to broke sites when installed.

= 4.4.1 =
* Fix error that only 1 post per day was allowed.

= 4.4.0 =
* Allow compatibility with PHP < 7.0 again.

= 4.3.0 =
* Small interface changes.
* Fixing typos.

= 4.2.0 =
* Tested with Gutenberg editor.
* Tested up to WordPress 5.0.2

= 4.0.06 =
* Added Dutch language. Thanks Stephan van Rooij (@svrooij) for sending me the .po file!
* Tested up to WordPress 4.7.3

= 4.0.05 =
* Fixed issue with deprecated WordPress parameter in function add_option.
* Tested up to WordPress 4.4.2

= 4.0.04 =
* Added a video in the description and a new cover and icon.

= 4.0.03 =
* Tested up to WordPress 4.2.2

= 4.0.02 =
* Tested up to WordPress 4.0.1

= 4.0.01 =
* Tested up to WordPress 3.8

= 4.0.00 =
* As requested, now it is possible to choose how many posts will be scheduled each day.

= 3.1.12 =
* Pages and menu items was being automatically scheduled too! Now only posts are scheduled as it should be since the beginning!! Thanks "nordlund".

= 3.1.11 =
* Rarely times a post recently scheduled were not detected and another were scheduled for the same day. Fixed!

= 3.1.10 =

* Tested with WordPress 3.4.1 and it is working fine!
* If the post is being scheduled for the day that is being written, it is scheduled to be published 3 minutes after clicking the "Publish to schedule." It is obviously necessary that the time interval is allowed. This causes the post to be online as soon as possible, generating more revenue that day.

= 3.1.9 =
* Changed direct settings link to work from plugin administration even if the plugin is installed in a different folder under plugins directory.
* Correct function that compare remote WordPress time with local time and displays a warning.

= 3.1.8 =
* First public release.
* The plugin has being tested for a long time before I take some time to publish it here, and I was not actually tracking changes as it was only running locally in one or two blogs.