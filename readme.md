h1. Domaineer

Domaineer is a MODX Revolution plugin to manage domain-specific settings and placeholders.

It has been developed in June 2011 by Mark Hamstra <business@markhamstra.nl> and has been released under the GPL
Open Source license v2 or later. You can find the license in the license.txt file.

h2. When to use Domaineer

Domaineer was developed to provide a way to easily manage domain-specific settings and placeholders. This means
that if you have one context that can be accessed from a number of (sub)domains, and each different "point of entry"
requires for example a different logo, site name or other non-resource, non-context and non-user related setting.

h2. How to use Domaineer

* First of all, install the package (see below).
* Next, find the plugin in Elements > Plugins and click it.
* Open the Properties tab.
* Unlock the default properties using the button that says "Default properties are locked".
* From there you can set element properties. These need to have a key that corresponds with the lowercase domainname,
without http:// and www. if it uses it. This could also be a subdomain. The value of the property needs to be a JSON
encoded array. See below for examples and more advanced usage.
* Now save the plugin and visit your site with the settings or placeholders to see the effect. If you are not seeing
any result, enable the (normally included) "debug" property. It will then post information to the MODX error log.

h2. Installing Domaineer

At this moment (July 20th, 2011, 10:18pm GMT +2) Domaineer has not been wrapped up into a package yet (though I welcome
pull requests!) and you will have to install it manually. That's quite easy.

Just copy the contents of the "domaineer.plugin.php" to a new Plugin called "Domaineer", and tick the "OnWebPageInit"
system event on the System Events tab. Next follow the above "How to use Domaineer" section to get something done!

h2. Property value

The property value is what actually does the magic here. It needs to be a JSON formatted array, so this would be a valid
example:

~~~~ js
{"+site_name":"My new Site Name setting"}
~~~~

You may notice the + sign in front of site_name, that indicates this is actually a system setting we are modifying
instead of a placeholder. You will always need that + sign for a system setting.

You can use as many key:value pairs as you want.

Final note: always make sure your placeholders or system setting tags that are set dynamically are UNCACHED, so with an
exclamation mark: [[!++site_name]] or [[!+foo]].


