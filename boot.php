<?php
header("Content-Type: text/plain");

function CIDRtoMask($int) {
    return long2ip(-1 << (32 - (int)$int));
}

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

if(isset($_GET['mask'])) {
  $nm = CIDRtoMask($_GET['mask']);
}

if(isset($_GET['ip'])) {
  echo '
  kernel ip='. ($_GET["ip"]).'::'.($_GET["gw"]).':'.$nm.':'.($_GET["hostname"]).':ens192:none coreos.inst.install_dev=sda';
} else {
  echo '
  kernel ${coreos-url}/rhcos-${zstream}-${arch}-installer-kernel-${arch} ${console} ${first-boot} ${auto-login} ${oem}';
}

  echo '
  initrd ${coreos-url}/rhcos-${zstream}-${arch}-installer-initramfs.${arch}.img
  boot';

?>
