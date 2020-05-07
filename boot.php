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

if(isset($_GET['ip']) {
  echo '
  kernel ip=' . htmlspecialchars($_GET["ip"]) . '::vm_v4defaultgw:vm_v4netmask:vm_nodename.vm_domainname:ens192:none coreos.inst.install_dev=sda coreos.inst.ignition_url=ocp_url_ign
  kernel ${coreos-url}/rhcos-${zstream}-${arch}-installer-kernel-${arch} ${console} ${first-boot} ${auto-login} ${oem}
  initrd ${coreos-url}/rhcos-${zstream}-${arch}-installer-initramfs.${arch}.img

  boot';
} else {
  echo '
  kernel ${coreos-url}/rhcos-${zstream}-${arch}-installer-kernel-${arch} ${console} ${first-boot} ${auto-login} ${oem}
  initrd ${coreos-url}/rhcos-${zstream}-${arch}-installer-initramfs.${arch}.img

  boot';
}

?>
