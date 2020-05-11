<a href="https://travis-ci.org/catalyst/moodle-tool_heartbeat">
<img src="https://travis-ci.org/catalyst/moodle-tool_heartbeat.svg?branch=master">
</a>

# A heartbeat test page for Moodle



A very simple plugin which just performs a quick check of all
critical service dependancies (filesystem, DB, caches, and
sessions) and return OK

Use from a load balancer to tell whether a node is OK

Just install the plugin normally and then point your load balance
to a url like this:

http://moodle.example.com/admin/tool/heartbeat/

It will return a page with either a 200 or 503 response code and
if it fails a string for why.

By default it only performs a light check, in particular it does not
check the moodle database. To do a full check add this query param:

http://moodle.example.com/admin/tool/heartbeat/?fullcheck

This check can also be run as a CLI:

```
php index.php fullcheck
```

## Example return values for heartbeat
Example for when the server is healthy.
```
(HTTP 200)
Server is ALIVE
sitedata OK
```

Example for when the server is in command line maintenace mode.
```
(HTTP 200)
Server is in MAINTENANCE
sitedata OK
```

Example for when the server is not healthy.
```
(HTTP 503)
Server is DOWN
Failed: database error
```

# A nagios cron health checker

NOTE: Ideally this plugin should be redundant and most of it's
functionality built into core as a new API, enabling each plugin
to delare it's own extra health checks. See:

https://tracker.moodle.org/browse/MDL-47271

A script croncheck is a nagios compliant checker to see if cron
or any individual tasks are failing, with configurable thresholds

This script can be either run from the web:

http://moodle.example.com/admin/tool/heartbeat/croncheck.php

Or can be run as a CLI in which case it will return in the format
expected by Nagios:

```
sudo -u www-data php /var/www/moodle/admin/tool/heartbeat/croncheck.php
```

The various thresholds can be configured with query params or cli args
see this for details:

```
php croncheck.php -h
```

# Installation

Via the Moodle plugin directory:

https://moodle.org/plugins/view/tool_heartbeat

