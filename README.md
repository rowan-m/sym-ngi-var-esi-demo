Symfony2 / nginx / Varnish / ESI Demo
=====================================

For this demo, I'm assuming a clean install of 64-bit Ubuntu Server 11.10 so you
may need to change some package names or configuration file locations for other
distributions. This repo is laid out as if it was starting from the root of the
filesystem, so you can theoretically just extract it as is and go.

The ISO is available here: http://www.ubuntu.com/download/server/download

Add an entry for "`sf2-demo.local`" in your "`/etc/hosts`" of whatever machine
you're using to access the installation.

Installing Packages
-------------------

Install:
* Varnish - https://www.varnish-cache.org/
* nginx - http://nginx.org/
* PHP-FPM - http://php-fpm.org/
```
sudo apt-get install varnish nginx php5-fpm php5-cli php-apc php5-xdebug
php5-sqlite
```

