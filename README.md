# pong
Application for Queue , using swoole and Lamins

# Installing DotKernel `pong`

- [Installing DotKernel `pong`](#installing-dotkernel-pong)
    - [Installation](#installation)
        - [Composer](#composer)
    - [Choose a destination path for DotKernel `pong` installation](#choose-a-destination-path-for-dotkernel-pong-installation)
    - [Installing the `pong` Composer package](#installing-the-pong-composer-package)
        - [Installing DotKernel pong](#installing-dotkernel-pong)
    - [Configuration - First Run](#configuration---first-run)
    - [Testing (Running)](#testing-running)

### Composer

Installation instructions:

- [Composer Installation -  Linux/Unix/OSX](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx)
- [Composer Installation - Windows](https://getcomposer.org/doc/00-intro.md#installation-windows)

> If you have never used composer before make sure you read the [`Composer Basic Usage`](https://getcomposer.org/doc/01-basic-usage.md) section in Composer's documentation

## Choosing an installation path for DotKernel `pong` 

Example:

- absolute path `/var/www/pong`
- or relative path `pong` (equivalent with `./pong`)

## Installing DotKernel `pong`

After choosing the path for DotKernel (`pong` will be used for the remainder of this example) it must be installed. 

#### Installing DotKernel `pong` using git clone

This method requires more manual input, but it ensures that the default branch is installed, even if it is not released. Run the following command:

```bash
$ git clone https://github.com/dotkernel/pong.git .
```

The dependencies have to be installed separately, by running this command
```bash
$ composer install
```

The setup asks for configuration settings regarding injections (type `0` and hit `enter`) and a confirmation to use this setting for other packages (type `y` and hit `enter`)

## Configuration - First Run

- Remove the `.dist` extension from the files `config/autoload/local.php.dist`, `config/autoload/redis.local.php.dist`, `config/autoload/swoole.local.php.dist`
- Edit `config/autoload/local.php` according to your dev machine

## Testing (Running)

Note: **Do not enable dev mode in production**

- Run the following commands in your project's directory ( in different tabs ):

```bash
$ redis-cli
$ php bin/dot-swoole start
$ php bin/cli.php process
$ php bin/cli.php result
```

**NOTE:**
If you are still getting exceptions or errors regarding some missing services, try running the following command

```bash
$ php bin/clear-config-cache.php
```

## Daemons (services) files content
```bash
app-main.service
[Unit]
Description=pong startup service
StartLimitIntervalSec=1

[Service]
#The dummy program will exit
Type=oneshot
ExecStart=/bin/true
RemainAfterExit=yes

[Install]
WantedBy=multi-user.target
```

```bash
app-process-queue.service
[Unit]
Description=Queue startup service
After=mysqld.service
PartOf=app-main.service
StartLimitIntervalSec=1

[Service]
Type=simple
Restart=always
RestartSec=1
User=root
ExecStart=/usr/bin/php /var/www/html/app-directory/bin/cli.php process

[Install]
WantedBy=app-main.service
```

```bash
app-result-queue.service
[Unit]
Description=Queue result service
After=mysqld.service
PartOf=app-main.service
StartLimitIntervalSec=1

[Service]
Type=simple
Restart=always
RestartSec=1
User=root
ExecStart=/usr/bin/php /var/www/html/app-directory/bin/cli.php result

[Install]
WantedBy=app-main.service
```

```bash
app-swoole.service
[Unit]
Description=pong startup service
After=mysqld.service
PartOf=app-main.service
StartLimitIntervalSec=1

[Service]
Type=simple
Restart=always
RestartSec=1
User=root
ExecStart=/usr/bin/php /var/www/html/app-directory/bin/dot-swoole start
ExecStop=/usr/bin/php /var/www/html/app-directory/bin/dot-swoole stop

[Install]
WantedBy=app-main.service
```
