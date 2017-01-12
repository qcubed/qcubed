# Caching in QCubed

QCubed allows you to cache various types of data at different levels. Caching, as of now is divided into two different parts: 

1. File based caching (**QCache**): Uses the server's local file system to store cached data.
2. Provider based caching (**QCacheProvider\* Classes**) - Adaptable Caching based on third party libraries (Memcached, Redis etc.)

