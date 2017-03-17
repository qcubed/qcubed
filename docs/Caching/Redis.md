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

### Expiration of keys
Keys can be set to auto-expire in Redis (like in other cache service providers). However, there is a special behavior for Redis cache controlled by a named constant - `_REDIS_CACHE_PROVIDER_DEFAULT_TTL_`. We will first tell you why we treat Redis cache differently.

Of all cache providers, Redis is the only one which **persists its data on disk**. *LocalMemory* provider would invalidate caches after request ends. *NoCache* invalidates caches immediately. *APC* would invalidate after webserver/fpm service restart. *Memcache* would invalidate after memcache service or server restarts. 

By default, Redis does not exhibit such a behavior. All keys will go on getting saved until they are manually deleted or are evicted because of a expire time applied to the key when setting the value. Since caches should be automatically managed and should use limited resources, it is important that they free resources from time to time. Each cache provider has these limitations in place while a server-reboot is the final solution to a error or resource-overusage for them. The only method Redis' default installation gives us for automatically managing resource usage (in this case, storage) is setting an expire-time on keys using which Redis automatically frees up memory/storage. The `_REDIS_CACHE_PROVIDER_DEFAULT_TTL_` named constant allows us to control Redis' storage requirement. 

When this value is set, each cache key set into Redis by using QCubed's built in Cache-Provider mechanism gets a default expiration time. The expiration time is equal to the number of seconds this value is defined as. 
  
**Example**: 

You have defined the value in `configuration.inc.php` as:

```php
define('_REDIS_CACHE_PROVIDER_DEFAULT_TTL_', 7200);
```

Later, when you save a value into Redis cache using:

```php
QApplication::$objCacheProvider->Set('someKey1', $strSomeValue, 60);
```

Then *someKey1* will automatically be removed from cache in **60 seconds**. This is because you passed the third argument into the method as 60, which overrides the defaults.

However, if you were to set another key using: 

```php
QApplication::$objCacheProvider->Set('someKey2', $strSomeValue);
```

Then *someKey2* will be removed from the cache in 2 hours (7200 seconds).

---
If you set the value to 0 (or anything else which is evaluated as 0 by PHP), like: 

```php
define('_REDIS_CACHE_PROVIDER_DEFAULT_TTL_', 7200);
```

This this statement: 
```php
QApplication::$objCacheProvider->Set('someKey3', $strSomeValue);
```

Will not set a default expiration time and the value corresponding to *someKey3* will stay in Redis cache untill you remove it.

However, this statement: 

```php
QApplication::$objCacheProvider->Set('someKey4', $strSomeValue, 60);
```

would still make *someKey4* expire after 1 minute.


## Full power of Redis is available
 
 The `QApplication::$objCacheProvider` created for Redis Caching is a wrapper around `Predis\Client` instance and can take all commands like `sadd` and `sismember` For example:
  
```php
  QApplication::$objCacheProvider->sadd('keyS', [2,5,10]) 
```
would save 2,5 and 10 into a *Set data structure* named *keyS*.
 
 If you have a persistent Redis instance, you can use the caching system to use the entire Redis functionality using the Predis library.