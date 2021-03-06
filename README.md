Symfony2 / nginx / Varnish / ESI Demo
=====================================

For this demo, I'm assuming a clean install of 64-bit Ubuntu Server 11.10 so you
may need to change some package names or configuration file locations for other
distributions. This repo is laid out as if it was starting from the root of the
filesystem, so you can theoretically just extract it as is and go.

The ISO is available here: http://www.ubuntu.com/download/server/download

Add an entry for `sf2-demo.local` in your `/etc/hosts` of whatever machine
you're using to access the installation.

All commands will assume you are starting from the top-level directory of the
repo.

Installing Packages
-------------------

Install:

* Varnish: https://www.varnish-cache.org/
* nginx: http://nginx.org/
* PHP-FPM: http://php-fpm.org/

```
sudo apt-get install varnish nginx php5-fpm php5-cli php-apc php5-xdebug php5-sqlite
```

*Note:* This current Ubuntu bug
(https://bugs.launchpad.net/ubuntu/+source/php5/+bug/875262) means you will
probably want to comment out all the lines in `/etc/php5/conf.d/sqlite.ini`:

```
; configuration for php SQLite module
; extension=sqlite.so
```

Installing Symfony
------------------

Symfony has been extracted into the repo using the 2.0.12 version available
here: http://symfony.com/download?v=Symfony_Standard_Vendors_2.0.12.tgz

The `.gitignore` file has entries matching the Symfony instructions here:
http://symfony.com/doc/current/cookbook/workflow/new_project_git.html

You will need to set permissions in a way inspired by the documentation:
http://symfony.com/doc/current/book/installation.html#configuration-and-setup

```
cd var/www/Symfony/
sudo rm -rf app/cache/*
sudo rm -rf app/logs/*
sudo chown -R :www-data  app/cache/ app/logs/
sudo chmod -R ug+rwX  app/cache/ app/logs/
```

*Note:* Using the shared storage on VMs does seem to be a bit flaky, especially
with the cache files. So, try re-running the above or if in doubt, try turning
it off and turning it back on again.

Configuring nginx
-----------------

Remove the `default` site, create the logging files, and enable the new site.

```
sudo mkdir /var/log/php5/
sudo touch /var/log/php5/fpm.log
sudo chown www-data /var/log/php5/fpm.log
sudo rm /etc/nginx/sites-enabled/default
sudo mkdir -p /var/log/nginx/sf2-demo.local
sudo touch /var/log/nginx/sf2-demo.local/access.log /var/log/nginx/sf2-demo.local/error.log
sudo ln -s ../sites-available/sf2-demo.local /etc/nginx/sites-enabled/sf2-demo.local
sudo /etc/init.d/php5-fpm restart
sudo /etc/init.d/nginx restart
```

If you're running in development mode, you should now be able to access the
Welcome page by visiting: http://sf2-demo.local:8080/ If you're in production
mode, you should still be able to get a successful 404 response.

Configuring Varnish
-------------------

Assuming you're using the provided `etc/default/varnish` file, then Varnish is
already listening on port 80. You should be able to see the same Welcome page or
404 response on: http://sf-2demo.local

The remaining changes have been made as per:
http://symfony.com/doc/current/cookbook/cache/varnish.html

Configuring Symfony
-------------------

The Symfony configuration files have been updated as per:
http://symfony.com/doc/current/book/http_cache.html#edge-side-includes

You can see the changes in:

* `var/www/Symfony/app/config/config.yml`
* `var/www/Symfony/app/config/routing.yml`

Automatically starting the session has also been disabled in `config.yml` to
prevent sending cookies on all requests.

The example bundle has been generated using:

```
cd var/www/Symfony
php app/console generate:bundle --namespace=Ibuildings/EsiDemoBundle --format=yml
```

Using ESI
---------

The use of ESI is enabled through Twig's `render` tag. To demonstrate this, an
additional action called `included` has been added to the default controller
along with a matching template.

* `var/www/Symfony/src/Ibuildings/EsiDemoBundle/Controller/DefaultController.php`
* `var/www/Symfony/src/Ibuildings/EsiDemoBundle/Resources/views/Default/index.html.twig`
* `var/www/Symfony/src/Ibuildings/EsiDemoBundle/Resources/views/Default/included.html.twig`

By setting different values for `s-maxage` in the `cache-control` header we can
see that each individual section is cached for a different amount of time.

You can sit and watch the responses using `curl` as a way to amuse yourself:

```
watch curl -vs http://sf2-demo.local/esi-demo/hello/developers
```
