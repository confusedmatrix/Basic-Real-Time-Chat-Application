#Basic real-time chat application 
A real-time chat application that makes use of long polling for instant messaging. Built on the [Blueprint PHP framework](https://github.com/jooldesign/Blueprint-PHP-Framework) and [mongoDB](https://github.com/mongodb/mongo).

A demo of the application is available here: [chat.jooldesign.co.uk](http://chat.jooldesign.co.uk/).

_Requires PHP 5.3 or above as it is fully namespaced_

To get started:

* rename /etc/config-default.php to /etc/config.php
* Ammend any settings in /etc/config.php if necessary
* Have fun!

Things of note:

* All messages removed after 24 hours of inactivity
* You can manually remove all messages before this time by navigating to /messages/clear-messages