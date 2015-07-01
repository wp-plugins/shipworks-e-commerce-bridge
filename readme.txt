=== ShipWorks Connector for WordPress  ===
Contributors: AdvancedCreation
Donate link: http://www.advanced-creation.com
Tags: shipworks, wordpress, ShipWorks Connector, Woocommerce, shopp, shopperpress, WP eCommerce, Cart66 Lite, Cart 66 Pro, Jigoshop, e-commerce, order manager, shipping manager, e-commerce tool, e-commerce shipping, e-commerce manager, multisites, bridge, connect
Requires at least: 3.0.1
Tested up to: 4.2.2
Stable tag: 3.4.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

ShipWorks Connect is a good plugin to improve your order management on based WordPress E-Commerce
sites. ShipWorks for Wordpress build a bridge between your E-Commerce sites on Wordpress
and ShipWorks. It downloads your orders and makes it easy to create shipping labels, manage customers
and emails, and update the online status of each order.

[youtube https://www.youtube.com/watch?v=ECTyDJvfPRs#t=130]

= Compatible E-Commmerce software =
When you activate the plugin on your site it immediately says if you are running or not a compatible
E-Commerce template/plugin. Here the list of the compatible products:

* Woocommerce ( 2.0++ )
* WP eCommerce ( 3.0++ )	
* Shopp ( 1.2.2++ )
* Shopperpress ( 7.1++ )
* Cart66 Lite ( 1.5++ )
* Cart66 Pro ( 1.5++ )
* Jigoshop ( 1++ )
	
Minimum Requires:

* Wordpress 3.0
* Shipworks 3.0 (or minimum version you have)
* PHP 5.0

= Plugins Compatible With =

* Woocommerce Composite Products
* Woocommerce Sequential Order Numbers
* Woocommerce Shipment Tracking
* Woocommerce Checkout Field Editor
* TM Extra Product Options plugin
* Woocommerce Bulk Discount

There is a plugin you would like to add to this list? Contact us at contact@advanced-creation.com.

= Ship faster with integrated shipping tools =
With the most complete shipping integrations available, your shipments get done faster so you can focus 
your time on other aspects of your business.

= Save time and enhance your customer service =
ShipWorks includes support for viewing rates and transit times and printing live shipping labels from within 
ShipWorks. With direct integrations to Endicia, Express1, Fedex, Stamps.com, UPS, and USPS, ShipWorks is 
your one-stop solution for generating shipping labels for your orders with any of the major carriers. And 
since tracking numbers are automatically imported and saved, sending tracking emails and responding to 
customer calls is a snap.

= Reduce costs by simplifying order processing =
ShipWorks has proven itself to save hours per day in real world order fulfillment tasks. From the moment you 
begin using ShipWorks you will notice an intuitive interface that reduces order management to point and click. 
With its low learning curve and multi-computer networking support, you and your employees will be able to start 
taking advantage of ShipWorks right away. Every element of ShipWorks has been designed with an emphasis on time 
savings and usability, which ultimately translates into reduced costs for your business.

= Work smarter with tools designed to make your life easier =
ShipWorks offers unparalleled support for your computer peripherals. ShipWorks supports all standard inkjet and 
laser printers, as well as Eltron thermal printers. ShipWorks allows you to specify which printer and tray each 
print job should go to, greatly simplifying your printing process. And with zero-configuration support for most 
scales, weighing your packages is always fast and accurate.

== Installation ==

1. Upload `shipworks-e-commerce-bridge` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Configure the plugin by going to the `ShipWorks Connect` menu that appears in your admin menu

== Frequently Asked Questions ==

1. If your in trouble with online status, if they are wrong or if the value is not good you need to check a few things with your SHipWorks software.

In ShipWorks go into the menu in Manage -> Actions.
A window opens and there should be an action which is running when "A shipment is processed" and which task is "Upload the shipment details".
If not create one with the appropriate store. So that shipments details are automatically updated online when you ship your product.

2.What to do if I have an error 406 ?

Error 406 is meaning your hosting have a mod_security module on their server. So you have to contact your hosting to ask them to remove it for your account. Sometimes they will need your IP to unlock it only for your computer.

3.Where can I find my IP ?

You can find it on internet by clicking on this link: http://www.whatismyip.com/

4.What to do if I have an error 504 and a large amount of orders to import ?

If you are trying to import more than 1000 orders for your first connection and you got the error 504, please import manually a xls file into shipworks

5.You have an error "Reference to undeclared entity 'rsaquo'" ?

Please change url module (when setup shipworks) starting by https (replace http by https)

6.What to do If I have a bug on my order (in shipworks) ?

Please contact us at contact@advanced-creation.com, we will fix the bug as soon as possible. (Most of the time we are fixing bugs in less than 24h)
If you have any questions or issues about the plugin don't hesitate to contact us :
contact@advanced-creation.com.

7.I want to see only Processing or pending items, In Shipworks:

Step 1: Click on double arrow on the right of Search All orders
Step 2: If all of the following conditions are met -> click on the plus
Step 3: Change order total by Order -> Online Status
Step 4: Online Status Equals -> Select Processing
Renew Step 2 if you want to add Pending items and modify all by any

== Screenshots ==

1. The Settings Page: enter your store address and the credentials that will be used by ShipWorks.
2. The ShipWorks software with a fiew orders downloaded from the site.

== Changelog ==

= 3.4.1 =
All orders are download, even canceled order 

= 3.3.9 =
Compatible with TM Extra Procut Options.

= 3.3.5 =
Bugs were fixed, and compatible with Shipment Tracking plugin.

= 3.1 =
Bugs were fixed, and better support for notes and comments from ShipWorks. Better display for tracking informations.

= 2.9.16 =
Support for addons and variable products on Shopp.

= 2.9.11 =
Coupons can now appear on invoices.

= 2.9.11 =
This new version runs better on Bluehost servers. 

= 2.9.7 =
Some issues were fixed on variable products attributes. 

= 2.9.2 =
This new version supports variable products in Woocommerce including attributes.
It also converts the weight in lbs for ShipWorks on Woocommerce. 

= 2.9.1 =
This new version supports variable products in Jigoshop, and some issues were fixed.

= 2.9 =
This new version supports variable products in Woocommerce.

= 2.8 =
* Tracking numbers are now sent to Woocommerce via ShipWorks. 
* Status option available on the ShipWorks side for Cart66.

= 2.7.5 =
* Better communication with ShipWorks on tracking numbers.

= 2.7.4 =
* Accept UPS tracking number.

= 2.7.3 =
* Better compatibility with every version of Cart66 Pro.

= 2.7.2 =
* Jigoshop displays better on the invoice. Shipping option and SKU number displayed.

= 2.7 =
* Compatible with Cart66 Pro and Woocommerce Composite Products

= 2.6 =
* SKU number and Shipping option on invoices for WP eCommerce, Shopp, and Shopperpress.

= 2.5.2 =
* Better compatibility with Woocommerce

= 2.5.1 =
* SKU number on invoices for easier management

= 2.5 =
* Better compatibility with Woocommerce
* Fixed some bugs

= 2.4 =
* Compatible with Jigoshop
* Fixed some bugs

= 2.3 =
* Compatible with Cart66 Lite
* Fixed some bugs

= 2.2 =
* Compatible with WP e-Commerce
* Update for Shopp 1.2.9 version
* Fixed some bugs

= 2.1 =
* Compatible with Woocommerce
* Fixed some bugs

= 2.0 =
* Totally rebuilt
* More stable
* Compatible with shopperpress

= 1.0 =
* First version

== Upgrade Notice ==

= 3.3.9 =
This new version works better with the TM Extra Product Options plugin.

= 3.3.5 =
This new version works better with shopp and is compatible with the Woocommerce Shipment Tracking plugin.

= 3.1 =
This new version give you a lot's for new possibilities. Comments and Notes are supported. Tracking numbers have a better display in the WordPress backend.

= 2.9.16 =
This new version enable you to use variable products and addons with ShipWorks. 

= 2.9.14 =
This new version enable you to see coupons on the invoice for Woocommerce. 

= 2.9.11 =
This new version runs better on Bluehost servers. 

= 2.9.7 =
This new version supports better attributes for variable products. 

= 2.9.2 =
This new version supports variable products in Woocommerce including attributes.
It also converts the weight in lbs for ShipWorks on Woocommerce. 

= 2.9.1 =
This new version supports variable products in Jigoshop, and some issues were fixed.

= 2.9 =
This new version supports variable products in Woocommerce.

= 2.8 =
A fiew bugs have been fixed for Cart66 and Woocommerce compatibility.

= 2.7.5 =
A fiew bugs have been fixed on the communication with ShipWorks for tracking numbers.

= 2.7.4 =
Now accept UPS tracking numbers.

= 2.7.3 =
This new version has a better compatibility with Cart66.

= 2.7.2 =
This new version has a better compatibility with Jigoshop.

= 2.7 =
Compatible with Cart66 Pro and Woocommerce Composite Products.

= 2.6 =
This new version the are more informations displayed on ShipWorks invoices ( SKU and Shipping_option ) for the following softwares : WP eCommerce, Shopp,Shopperpress.

= 2.5.2 =
This new version is now fully compatible with Woocommerce sequential order number.

= 2.5.1 =
Better support woocommerce invoice items.

= 2.5 =
This new version is now fully compatible with Woocommerce sequential order number.
A bug for the shipping option on woocommerce has been deleted.

= 2.4 =
This new version is now fully compatible with Jigoshop.
Now the 30 orders/month limit is not blocking everything
when you have more than 30 orders/month. 

= 2.3 =
This new version is now fully compatible with Cart66 Lite.

= 2.2 =
This new version is now fully compatible with WP e-Commerce.
Update for the new Shopp 1.2.9 version.

= 2.1 =
This new version is now fully compatible with woocommerce.

= 2.0 =
* Totally rebuilt
* More stable
* Compatible with Shopperpress

= 1.0 =
Fist version

= 0.5 =
This version fixes a security related bug.  Upgrade immediately.

