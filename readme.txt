=== Publish to Schedule ===
Contributors: alexbenfica,flauius
Tags: posts, scheduling, auto schedule, future post, periodicity, postpone, admin, produtivity, publish control, schedule post, autopost, autopublish
Requires at least: 2.8
Tested up to: 4.4.2
Stable tag: trunk
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=N827XSKKL7388&lc=US&item_name=Publish%20to%20Schedule%20Plugin&item_number=publish%2dto%2dschedule&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted


Write and not worry about scheduling posts, keeping the periodicity for your readers and not waste time finding out next date to publish. 


== Description ==

With this plugin you don't need to manually choose the date when a post will be published. After a simple configuration the plugin will schedule your posts when you click publish.

Just configure days of week, the number of posts each day and time interval you want posts to be auto scheduled and each post will be automatically scheduled for these days with no more than the number you specified of posts per day. 

You can keep reading this... or you can watch this [spontaneous video][ptsvideo] from Eli The Computer Guy explaining how this free plugin works! 
Thank you Eli. I'm really happy to know that my plugin is helping many people. You made my day.

[ptsvideo]: https://www.youtube.com/watch?v=mT8zTAtu7lc
            "Plugin WordPress Publish to Schedule - Video from Eli The Computer Guy"

Now that you have already watched the video... read the rest!

It is useful as readers will have articles with maintain some defined periodicity.

Besides choose the day of week when you want posts published, you can set the time too. You might specify an interval in which you like posts to be scheduled. The plugin will choose a random time within this interval.

In order to make things clear and let no doubts about why an specific data were chosen, the plugins shows some informations in the publish box. You can set up how much info you want to see there. 
This information is important as it allow you to manually change the date and time, if you have your reasons to.

It is very simple to use and avoid you to be searching when the last post were published each time you have to schedule a date to a post you are writing. 
Specially useful if you have multiple blogs and each one have a different post schedule. 

Se preferir, veja a **documentação em português** do [plugin para WordPress Publish to Schedule][doc ptbr].

[doc ptbr]: http://goo.gl/y80h6
            "Plugin WordPress Publish to Schedule - pt_BR"




== Installation ==

To install it, simply unzip the file linked above and save it in your plugins directory under wp-content. In the plugin manager activate the plugin. 
Settings can be acessible from WordPress settings menu or even from the plugins administration area.

There you must define which days of week you want to have your posts schedule... and choose a time interval too, if you prefer.

That's all. Now you'll see the plugin scheduled date and time each time you write a new post. Enjoy it!

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

The plugins is simple. It will schedule the post on the moment that publish button get clicked. 
So, the author who writes first will have your post published first! Fair enough.. anh?


= How does randomized publishing work? =

The publish time will be random in the time interval you chose at configuration screen.


= Can I publish overnight? =

Not yet. But it is already on TODO list.


= Can I ask for changes and new functionalities on this plugin? = 

Yes. You can! I'm availble to talk about this! And I can do other plugins too.

= Should I donate? =

You can, if you want. I would like that. Means that my work e valuable for someone else!
My paypal account for donations is: alexbenfica@gmail.com 

= Can I translate it? =

Yes! Please help translating to other languages.
I am native in Brazilian Portuguese and do speak English, so I can take care of en->pt_BR.
Any other languages... you're welcome! Thank you!


== Screenshots ==

1. Configuration to choose which day of week post can be scheduled.
2. Set the period of day in which post may be scheduled. Simple like that.
3. This is what you'll see while write your post: the date that post will be automatically schedule, and why! To see less information change the related option in the plugin configuration.

== Changelog ==
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



== Upgrade Notice ==
Nothing here yet!
