# ocp-dynamic-ipxe-script
Quick PHP script to print a Packet-friendly iPXE script for Red Hat CoreOS servers

# Usage

Assuming you're launching a server with Packet using "Custom iPXE", simply specify the appropriate kernel args as part of the query string:

```
https://ipxe-boot-shiftius.apps.us-west-1.starter.openshift-online.com/boot.php?ip=172.20.2.20&gw=172.20.2.1&mask=26&hostname=bootstrap.domain.com
```

This will generate a plain-text iPXE script that will configure a static IP via kernel args:

```
#!ipxe

set release 4.3
set zstream ${release}.8
set arch x86_64
set coreos-url https://mirror.openshift.com/pub/openshift-v4/dependencies/rhcos/${release}/${zstream}
set console console=ttyS1,115200n8
set first-boot coreos.first_boot=1
set auto-login coreos.autologin
set oem coreos.oem.id=packet

  kernel ip=172.20.2.20::172.20.2.1:255.255.255.192:bootstrap.domain.com:ens192:none coreos.inst.install_dev=sda
  initrd ${coreos-url}/rhcos-${zstream}-${arch}-installer-initramfs.${arch}.img
  boot
  ```

# Notes

- Use `boot.js` to host your own script via Cloudflare Workers
  - Publicly available version here: https://ipxe.shiftius.workers.dev/
- Use `boot.php` to self-host on an existing PHP-enabled web server
