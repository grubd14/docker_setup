## Wireguard

this config uses the wg-easy docker image for nice Web UI management. There is not much changes from the default configuration provided by the wg-easy.
Only thing you might want to change is to disable IPv6, many VPS still don't have support for it.

```
    enable_ipv6: false
```

It is connected to external network named caddy