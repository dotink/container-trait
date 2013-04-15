Container (Trait)
============

A trait for classes which predominately wrap/contain other objects

Note:

- Containers contain a final `__construct()` method
- Containers provide `ArrayAccess`

## Usage

```php
class Foo
{
	use Dotink\Traits\Container;
}
```

Once you have a class using the container trait you can work with it as follows:

```php
$foo = new Foo([
	'bar' => new Bar()
]);

$foo['bar']->method();
```

## Why?

Major dependencies can be easily distinguished from other properties on an object using array access.  This works well for constructor based dependency injection and provides a simple and expressive way of calling these dependencies within the class.  For example, inKWell 2.0's controllers use this trait so the router can provide itself and the request.  Inside controller methods, the following is then possible:

```php
public function add()
{
	$api_key = $this['request']->get('api_key', 'string');

	...
}
```



