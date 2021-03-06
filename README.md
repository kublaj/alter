Alter
=====

[![Build Status](https://travis-ci.org/alterfw/alter.svg?branch=master)](https://travis-ci.org/alterfw/alter)

A small framework that provides the way to develop model-based Wordpress themes

## Installation

Create a `composer.json` for your theme:

	composer init

Then add to your `composer.json`:
	
	"minimum-stability": "dev",
	"require": {
        "alterfw/alter": "0.1.x"
    }    

Run composer:

	composer install

After this, add this line to your **functions.php**:

```php
require_once "vendor/autoload.php";
```

## Documentation

Checkout our [documentation](http://alter-framework.readthedocs.org/en/latest/index.html) on readthedocs.org.

You can also contribute with the documentation in the [separated repository](https://github.com/alterfw/docs).

## Contributing

Fell free to help to improve Alter, you can make pull requests or improve the [documentation](https://github.com/alterfw/docs) also.

If you make a Pull Request of a new feature, make sure to link the [documentation](https://github.com/alterfw/docs) Pull Request and to write the respective tests.

### Development Environment

We use [Vagrant](http://vagrantup.com/) to create the Alter Development Environment. To setup, follow this instructions:

Clone our fork of [Vagrantpress](http://vagrantpress.org/):

	git clone https://github.com/alterfw/vagrantpress

And start the virtual machine:

	cd vagrantpress;
	vagrant up	

So, with the Vagrant VM on, you need to replace the alter dependency (installed over composer) by your clone of the repository;

	vagrant ssh
	cd /vagrant/wordpress/wp-content/themes/example-theme-master/vendor/alterfw
	rm -rf alter
	git clone git@github.com:alterfw/alter.git

**Note:** if you are using Windows you cannot use `vagrant ssh`, follow [this instructions](https://github.com/Varying-Vagrant-Vagrants/VVV/wiki/Connect-to-Your-Vagrant-Virtual-Machine-with-PuTTY) to setup PuTTY.

### Writing the tests

When writing tests please make sure to follow the [Arrange-Act-Assert](http://www.arrangeactassert.com/why-and-what-is-arrange-act-assert/) pattern.

### Running the tests

To run the tests you need first to setup the [development environment](#development-environment).

After this you can run the tests simply:

	cd /vagrant/wordpress/wp-content/themes/example-theme-master/vendor/alterfw/alter
	phpunit

If you receive any errors running `phpunit`, run `vagrant provision` and try again.

