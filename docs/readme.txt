# Domaineer

Domaineer is a MODX Revolution plugin to manage domain-specific settings and placeholders.

It has been developed in June 2011 by Mark Hamstra <business@markhamstra.nl> and has been released under the GPL
Open Source license v2 or later. You can find the license in the license.txt file.

## When to use Domaineer

Domaineer was developed to provide a way to easily manage domain-specific settings and placeholders. This means
when you have one context that can be accessed from a number of (sub)domains, and each different "point of entry"
requires for example a different logo, site name or other non-resource, non-context and non-user related setting.

## How to use Domaineer

* Install the package (that's what you're doing now, it seems!)
* Next, find the plugin in Elements > Plugins and click it.
* Open the Properties tab.
* Unlock the default properties using the button that says "Default properties are locked".
* From there you can set element properties. These need to have a key that corresponds with the lowercase domainname,
without http:// and www. if it uses it. This could also be a subdomain. The value of the property needs to be a JSON
encoded array. An example: {"+site_name":"My new Site Name setting"}
* Now save the plugin and visit your site with the settings or placeholders to see the effect. If you are not seeing
any result, add a "debug" property with value 1/true. It will then post some information to the MODX error log.
