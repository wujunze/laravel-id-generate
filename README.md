# Laravel IdGen

[![Total Downloads](https://poser.pugx.org/wujunze/laravel-id-generate/downloads.svg)](https://packagist.org/packages/wujunze/laravel-id-generate)
[![Build Status](https://travis-ci.org/wujunze/laravel-id-generate.svg?branch=master)](https://travis-ci.org/wujunze/laravel-id-generate)
[![codecov.io](http://codecov.io/github/wujunze/laravel-id-generate/coverage.svg?branch=master)](http://codecov.io/github/wujunze/laravel-id-generate?branch=master)
[![Latest Stable Version](https://poser.pugx.org/wujunze/laravel-id-generate/v/stable.svg)](https://packagist.org/packages/wujunze/laravel-id-generate)
[![Licence](https://poser.pugx.org/wujunze/laravel-id-generate/license.svg)](https://packagist.org/packages/wujunze/laravel-id-generate)

Laravel package to generate and to validate a UUID according to the RFC 4122 standard. Only support for version 1, 3, 4 and 5 UUID are built-in. and generate number id, generate primary key

## Base on [laravel-uuid](https://github.com/webpatser/laravel-uuid)
## Thanks to  [laravel-uuid](https://github.com/webpatser/laravel-uuid)

## Installation

In Laravel 5.5 laravel-uuid will install via the new package discovery feature so you only need to add the package to your composer.json file

```shell
composer require "wujunze/laravel-id-generate"
```

after installation you should see

```shell
Discovered Package: wujunze/laravel-id-generate
```

and you are ready to go

## Basic Usage

To quickly generate a UUID just do

```php
IdGen::generate()
```
	
This will generate a version 1 IdGen `object` with a random generated MAC address.

To echo out the generated UUID, cast it to a string

```php
(string) IdGen::generate()
```

or

```php
IdGen::generate()->string
```

## Advanced Usage

### UUID creation

Generate a version 1, time-based, UUID. You can set the optional node to the MAC address. If not supplied it will generate a random MAC address.

```php
IdGen::generate(1,'00:11:22:33:44:55');
```
	
Generate a version 3, name-based using MD5 hashing, UUID

```php
IdGen::generate(3,'test', IdGen::NS_DNS);
```	

Generate a version 4, truly random, UUID

```php
IdGen::generate(4);
```

Generate a version 5, name-based using SHA-1 hashing, UUID

```php
IdGen::generate(5,'test', IdGen::NS_DNS);
```

### id generate

Generate  sample primary key
```php
 IdGen::getSamplePk();
```

Generate  id by type and share key 
```php
 IdGen::genIdByTypeShareKey(6,89);
```

Generate  id by type 
```php
 IdGen::genIdByType(8);
```

Generate  code  
```php
 IdGen::genCode(9, 9, 888);
```
	
### Some magic features

To import a UUID

```php
$uuid = IdGen::import('d3d29d70-1d25-11e3-8591-034165a3a613');
```	

Extract the time for a time-based UUID (version 1)

```php
$uuid = IdGen::generate(1);
dd($uuid->time);
```

Extract the version of an UUID

```php
$uuid = IdGen::generate(4);
dd($uuid->version);
```

## Eloquent UUID generation

If you want an UUID magically be generated in your Laravel models, just add this boot method to your Model.

```php
/**
 *  Setup model event hooks
 */
public static function boot()
{
    parent::boot();
    self::creating(function ($model) {
        $model->uuid = (string) IdGen::generate(4);
    });
}
```
This will generate a version 4 UUID when creating a new record.

## Model binding to UUID instead of primary key

If  you want to use the UUID in URLs instead of the primary key, you can add this to your model (where 'uuid' is the column name to store the UUID)

```php
/**
 * Get the route key for the model.
 *
 * @return string
 */
public function getRouteKeyName()
{
    return 'uuid';
}
```

When you inject the model on your resource controller methods you get the correct record

```php
public function edit(Model $model)
{
   return view('someview.edit')->with([
        'model' => $model,
    ]);
}
```

## Validation

Just use like any other Laravel validator.

``'uuid-field' => 'uuid'``
``'uuid-field' => 'gen_id'``

Or create a validator from scratch. In the example an IdGen object in validated. You can also validate strings `$uuid->string`, the URN `$uuid->urn` or the binary value `$uuid->bytes`

```php
$uuid = IdGen::generate();
$genId=  IdGen::getSamplePk();
$validator = Validator::make(['uuid' => $uuid], ['uuid' => 'uuid'], ['gen_id' => $genId]);
dd($validator->passes());
```

## Notes

Full details on the UUID specification can be found on [http://tools.ietf.org/html/rfc4122](http://tools.ietf.org/html/rfc4122).
