# cakephp-sluggable
Plugin for CakePHP 3.x that enables automatic, configurable slugging of database fields

***WHY?***

Because slugs are great for human-readable yet seo-friendly page titles, urls, image urls, etc! They're pretty much the standard nowadays and CakePHP makes it super easy to give your app the power to create them for you.

***HOW?***

Just add the `Sluggable.Sluggable` behaviour to any model whose field(s) you need to slug. See the [usage]() section for customization.

## Requirements

* PHP 5.4+
* [CakePHP 3.x](http://cakephp.org)

## TOC
1. [Plugin Installation]()
2. [Usage]()
  3. [Examples]()
4. [Contributing]()

### Plugin Installation

1. [Composer Install]()
2. [Manual Install]()
  3. [Loading the plugin in your app]()
  4. [Setting up the namespace / autoloader]()
  
#### Composer Install

This plugin is on Packagist which means it can be easily installed with Composer.

```
composer require cwbit/cakephp-sluggable
```
Then simply load the plugin normally in your `config/bootstrap.php` file

```php
# in ../config/bootstrap.php - right after Plugin::load('Migrations') is fine!
Plugin::load('Sluggable');
```

#### Manual Install

You can also manually load this plugin in your App

##### loading the plugin in your app
Add the source code in this project into `plugins/Sluggable`

Then configure your App to actually load this plugin

```php
# in ../config/bootstrap.php
Plugin::load('Sluggable');
```

##### setting up the namespace / autoloader
Tell the autoloader where to find your namespace in your `composer.json` file

```json
	(..)
    "autoload": {
        "psr-4": {
           (..)
            "EmailQueue\\": "./plugins/EmailQueue/src"
        }
    },
    (..)
```
Then you need to issue the following command on the commandline
```
	php composer.phar dumpautoload
```
If you are unable to get composer autoloading to work, add `'autoload' => true` line in your `bootstrap.php` `Plugin::load(..)` command (see loading section)

## Usage

The sluggable behavior is extremely easy to implement, simply add it, like any other behavior, to your `Table`

```php

class PostsTable extends Table
{
	public function initialize(array $options)
	{
		parent::initialize($options);
		
		$this->addBehavior('Sluggable.Sluggable');
	}
}
```

By default the plugin will automatically generate a slug based on `name`, will store it in a column called `slug` and will use a dash `-` replacement, and will NOT automatically overwrite the slug field whenever `name` changes.

All of these settings are, of course, configurable.

* `pattern`
  * `:name` *(default)*
  * a `\Cake\Utility\Text::insert()`-friendly tokenized string. any of the entity fields are valid options
* `field`
  * `slug` *(default)*
  * field in the entity to store the slug
* `replacement`
  * `-` *(default)*
  * string used to replace spaces when building the slug
* `overwrite`
  * `false` *(default)*
  * `true`, if the slug should ALWAYS be re-generated on save. `false`, to generate once

#### Examples

Generate a slug based on the `title` field instead of `name`

```php

class PostsTable extends Table
{
	public function initialize(array $options)
	{
		parent::initialize($options);
		
		$this->addBehavior('Sluggable.Sluggable', [
			'pattern' => ':title',
		]);
	}
}
```

Generate a slug based on `id` **and** `title`

```php

class PostsTable extends Table
{
	public function initialize(array $options)
	{
		parent::initialize($options);
		
		$this->addBehavior('Sluggable.Sluggable', [
			'pattern' => ':id-:title',
		]);
	}
}
```
Generate a slug based on the latest version of the `title` (always)

```php

class PostsTable extends Table
{
	public function initialize(array $options)
	{
		parent::initialize($options);
		
		$this->addBehavior('Sluggable.Sluggable', [
			'pattern' => ':title',
			'overwrite' => true,
		]);
	}
}
```

Generate a slug normally, but store it in the `foo` column

```php

class PostsTable extends Table
{
	public function initialize(array $options)
	{
		parent::initialize($options);
		
		$this->addBehavior('Sluggable.Sluggable', [
			'field' => 'foo',
		]);
	}
}
```
Generate a slug using `.` dots instead of `-` dashes

```php

class PostsTable extends Table
{
	public function initialize(array $options)
	{
		parent::initialize($options);
		
		$this->addBehavior('Sluggable.Sluggable', [
			'replacement' => '.',
		]);
	}
}
```
# Contributing

If you'd like to contribute, please submit a PR with your changes! 

Requests will be accepted more readily if they come complete with **TESTS** :D