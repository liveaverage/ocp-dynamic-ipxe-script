addEventListener('fetch', event => {
  event.respondWith(handleRequest(event.request))
})
/**
 * Respond with hello worker text
 * @param {Request} request
 */
async function handleRequest(request) {

  const url = request.url

	// Function to parse query strings
	function getParameterByName(name) {
		name = name.replace(/[\[\]]/g, '\\$&')
		name = name.replace(/\//g, '')
		var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
			results = regex.exec(url)

		if (!results) return null
		else if (!results[2]) return ''
		else if (results[2]) {
			results[2] = results[2].replace(/\//g, '')
		}
		
		return decodeURIComponent(results[2].replace(/\+/g, ' '));
	}


  var ip = getParameterByName('ip')
  var gw = getParameterByName('gw')
  var nm = getParameterByName('netmask')
  var hn = getParameterByName('hostname')
  var ns = getParameterByName('ns')

  var ka_ip = `ip=${ip}::${gw}:${nm}:${hn}:ens192:none nameserver=` + (ns ? ns : '1.1.1.1')
  var ka    = 'kernel \${coreos-url}/rhcos-\${release}.\${zstream}-${arch}-installer-kernel-\${arch} \${console} \${first-boot} \${auto-login} \${oem} ' + (ip ? ka_ip : 'ip=dhcp')
  var init  = '\${coreos-url}/rhcos-\${release}.\${zstream}-\${arch}-installer-initramfs.\${arch}.img'
  var cs    = `rd.neednet=1 initrd=${init} coreos.inst=yes coreos.inst.install_dev=sda coreos.inst.image_url=\${coreos-img}`
  var ign   = `coreos.inst.ignition_url=http://169.254.169.254/metadata/v1/user-data`

  var boot = 
`#!ipxe

set release 4.3
set zstream 8
set arch x86_64
set coreos-url https://mirror.openshift.com/pub/openshift-v4/dependencies/rhcos/\${release}/\${release}.\${zstream}
set coreos-img \${coreos-url}/rhcos-\${release}.\${zstream}-\${arch}-metal.\${arch}.raw.gz
set console console=ttyS1,115200n8
set first-boot coreos.first_boot=1
set auto-login coreos.autologin
set oem coreos.oem.id=packet
 
${ka} ${cs} ${ign}
initrd ${init}
boot
`

  return new Response(boot, {
    headers: { 'content-type': 'text/plain' },
  })
}
