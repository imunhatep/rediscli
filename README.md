Execute redis cli commands
----
Could be useful in case of executing redis commands out of framework environment. Rediscli uses it's own entrypoint and reads env variables or `.env` for Redis connection.

E.g. In case Doctrine is configured to store entity proxies inside Redis, sometimes it's required to clean up Redis cache before Doctrine will be able to regenerate entity proxies. 

Supported commands:
 - flushdb
 - flushall

##### Installation:
```bash
composer requitre imunhatep/rediscli
```

##### Usage example:

Setup:
```bash
cd ./bin
ln -s ../vendor/imunhatep/rediscli/bin/redis-cli 
cd ..
```

`flushall` example using inline env variables:
```bash
REDIS_HOST=1.2.3.4 REDIS_PORT=6379 REDIS_DB=0 ./bin/redis-cli r:c:flushall
```

`flushdb` example providing `.env`:
```bash
./bin/redis-cli r:c:flushdb --dot-env=".env"
```

`flushbd` if env variables are set in shell
```bash
./bin/redis-cli r:c:flushdb
```
