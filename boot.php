<?php
header("Content-Type: text/plain");

echo '#!ipxe

set release 4.3
set zstream ${release}.8
set arch x86_64
set coreos-url https://mirror.openshift.com/pub/openshift-v4/dependencies/rhcos/${release}/${zstream}
set console console=ttyS1,115200n8
set first-boot coreos.first_boot=1
set auto-login coreos.autologin
set oem coreos.oem.id=packet
';

echo '
kernel ${coreos-url}/rhcos-${zstream}-${arch}-installer-kernel-${arch} ${console} ${first-boot} ${auto-login} ${oem}
initrd ${coreos-url}/rhcos-${zstream}-${arch}-installer-initramfs.${arch}.img

boot';

?>
