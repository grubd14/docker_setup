## nextcloud

### services 
1. postgres:16 for database
2. redis alpine image for caching
3. nextcloud default image

### .env
most of the environment variable are self explanatory, this are the few extra environment changes I made to make it work with my setup
```
      - NEXTCLOUD_TRUSTED_DOMAINS=${NEXTCLOUD_TRUSTED_DOMAINS}
      - OVERWRITEPROTOCOL=https
      - OVERWRITEHOST=${NEXTCLOUD_TRUSTED_DOMAINS}
      - OVERWRITECLIURL=https://${NEXTCLOUD_TRUSTED_DOMAINS}
      - TRUSTED_PROXIES=caddy
```
- NEXTCLOUD_TRUSTED_DOMAINS = makes your domain trusted with nextcloud server
- TRUSTED_PROXIES = nextcloud also has it's own proxy by default for making https connection possible, but for my setup I have setup caddy as the trusted proxy

