# Redis Caching

Redis is a key-value style data-structures store. The key length as well as the value can be as large as 512 MB. The 'value' in Redis can be one of the multiple [data structures](https://redis.io/topics/data-types). 
 
 Performance of Redis depends on whether it is running in persistent mode (saving data to disk) or in-memory mode (all contents are within the specified memory limits). Default configurations run in persistent mode. However, depending on use-case (data structures stored, amount of data stored etc.), performance of Redis can be between "a little slower" to "just a little faster" than memcached. However, implementation of Redis is way more powerful because of the data-structures implementation.
 
 ## Usage of Predis library
 [Predis](https://github.com/nrk/predis) is a popular (and widely regarded one of the most powerful) library for Redis written in PHP. QCubed uses Predis for using Redis as cache. QCubed's cache system cannot use Redis unless you install Predis. It is also a suggested package in our `composer.json`.
 
 You can simply use `composer require "predis/predis":"^1"` to install. Please see the [project page](https://github.com/nrk/predis) for installation instructions.
 
 ## QCubed Implementation
 
 QCubed's Redis Cache system is non-opinionated. What we mean to convey is - we do not limit you by how you want to set your system up. Start your redis server the way you want (server modes cannot be set by QCubed) and use the connection parameters in the configuration file to connect with the server. 
 
 Redis cache provider's options are taken in an array by using the definition: 
 
```php
define ('CACHE_PROVIDER_OPTIONS', serialize(
		array(
			'parameters' => array(),
			'options' => array()
		)
	));
```

The configuration **must always contain the 'parameters' and 'options' keys**. These go in the constructor of `Predis\Client`. Please see the [project page](https://github.com/nrk/predis) if you want to use custom connection options for your redis-server installation.

## Full power of Redis is available
 
 The `QApplication::$objCacheProvider` created for Redis Caching is a wrapper around `Predis\Client` instance and can take all commands like `sadd` and `sismember` (e.g. `QApplication::$objCacheProvider->sadd('keyS', [2,5,10])`). 
 
 If you have a persistent Redis instance, you can use the caching system to use the entire Redis functionality using the Predis library.