SbkCronBundle
=============

Symfony2 Bundle for setting up cron jobs via configuration.

[![Build Status](https://travis-ci.org/skck/SbkCronBundle.png?branch=master)](https://travis-ci.org/skck/SbkCronBundle)

# Installation

## Prerequisites

This bundle requires Symfony 2.1+, as it must be installed with composer.

## Download SbkCronBundle with composer

Add the following to your project `composer.json`:

```js
{
    "require": {
        "sbk/cron-bundle": "dev-master"
    }
}
```

Now install it with this command:

```bash
$ php composer.phar update sbk/cron-bundle
```

The bundle should be downloaded to the `vendor` directory.

## Enable the bundle

You need to add the bundle in `app/AppKernel.php`

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Sbk\Bundle\CronBundle\SbkCronBundle(),
    );
}
``` 

## Import Services

The CronBundle depends on certain services, so you need to import the service configuration in `app/config/config.yml`

```
imports:
    # ...
    - { resource: @SbkCronBundle/Resources/config/services.yml }
```

Now you should be able to use the Cron Manager.

# Usage

## Configuring Tasks

Tasks can be configured in `app/config/config(_prod).yml`.

```
sbk_cron:
  tasks:
    clearcache:
      command: "cache:clear --env=prod"
      expression: "0 0 * * *"
    listoutput:
      bin: "ls"
      script: ""
      command: "-l > /var/log/listoutput.log"
      expression: "@daily"
      
```

Each entry in `sbk_cron.tasks` represents a task. 

**`command`**

The command to execute. By default the cron manager will prepend `php %kernel.root_dir%/console` before the command name, so configuring console commands is easy. You can run every command you want with this bundle, read on how to do so.

**`expression`**

The cron expression, any valid expression that you would enter in a cron table.

**`bin`**

The binary which will execute the command (`php` by default, you could enter `''` to omit the bin in the execution command)

**`script`**

The script that will be called (`%kernel.root_dir%/console` by default).

## Runnign the master cron job

Even if you can configure all cron jobs with this bundle, you need to add one line to the crontab manually, the `cron:run` command. 

```
* * * * * php /var/www/app/console cron:run
```

This will execute the cron manager every minute.

The manager will check which tasks need to be executed and will create background processes for every due task. 

