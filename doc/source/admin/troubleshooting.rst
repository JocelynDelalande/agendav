Troubleshooting AgenDAV
=======================

If you are having problems with AgenDAV, check you have met all the
requisites and search AgenDAV logs/web server logs for error lines.

You can write to `AgenDAV general list
<http://groups.google.com/group/agendav-general>`_ asking for help. Make
sure you include the following information:

* Software details (OS, PHP version, web server you're using, CalDAV server)
* Clear description of your problem
* Important log lines

Try the following before writing:

Check configuration and installation environment
------------------------------------------------

AgenDAV ships, since version 1.2.4, a simple script that checks installation
environment and configuration files to make sure you meet all basic
requisites.

To run it, edit file :file:`web/public/configtest.php` to set the constant
``ENABLE_SETUP_TESTS`` to ``TRUE``.

Once you save the file with that change, point your browser to
``http://host/path/agendav/configtest.php`` and look for red cells. You'll
find some suggestions to fix the problems.

Remember to set ``ENABLE_SETUP_TESTS`` back to ``FALSE`` inside
``configtest.php``.

More verbose logs
-----------------

Edit ``web/config/config.php`` and set :confval:`enable_debug` to ``TRUE``.

Make sure you
have a valid path configured in :confval:`log_path` and the user which runs
the webserver has write access to it.

Check then the ``debug.log`` file inside your log directory, and check also your webserver logs.

Show errors
-----------

You can switch to ``development`` environment to force PHP to print errors
on generated pages. By default AgenDAV is configured to hide errors to
users.

To achieve that just edit the file ``web/public/index.php`` and replace the
following line::

	define('ENVIRONMENT', 'production');

with::

	define('ENVIRONMENT', 'development');


Enable HTTP logging
-------------------

Sometimes CalDAV servers send unexpected data to AgenDAV or AgenDAV tries to
do an unsupported operation on your CalDAV server. Knowing what happened under
the hood is really useful to spot configuration errors or AgenDAV bugs.

Since AgenDAV 2.0.0 is possible to enable HTTP traffic logging to get a log of
requests and responses. See the :confval:`enable_http_logging` setting to learn
how to enable it.



Debug your browser status
-------------------------

Most browsers can show you network activity and JavaScript errors using its
own interfaces. They can be very handful if you happen to find a bug on
AgenDAV. Some examples of browser which include this support are:

* Mozilla Firefox with Firebug extension
* Google Chrome/Chromium with Developer Tools (no addon required)
