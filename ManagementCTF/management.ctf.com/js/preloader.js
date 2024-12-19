var strings = [
"GRUB loading.",
"Welcome to GRUB!",
"<br>",
"[    0.000000] Loading Linux 5.15.0-60-generic ...",
"[    0.000000] Loading initial ramdisk ...",
"[    0.000000] Linux version 5.15.0-60-generic (buildd@JKLinux) (gcc (JKLinux 11.2.0-19JKLinux1) 11.2.0) #66-JKLinux SMP Wed Feb 22 14:34:02 UTC 2024",
"[    0.000000] Command line: BOOT_IMAGE=/vmlinuz root=UUID=1234abcd-5678-efgh-9101-112131415161 ro quiet splash vt.handoff=7",
"[    0.000000] KERNEL supported cpus:",
"[    0.000000]   Intel GenuineIntel",
"[    0.000000]   AMD AuthenticAMD",
"[    0.000000] Initializing cgroup subsys cpuset",
"[    0.000000] Initializing cgroup subsys cpu",
"[    0.000000] Initializing cgroup subsys cpuacct",
"[    0.000000] Booting paravirtualized kernel on bare hardware",
"[    0.000000] Memory: 8077324K/8388608K available (16384K kernel code, 2156K rwdata, 4864K rodata, 2048K init, 4608K bss, 311284K reserved)",
"[    0.000000] Kernel/User page tables isolation: enabled",
"[    1.234567] ACPI: Core revision 20201113",
"[    1.567890] PCI: Probing PCI hardware",
"[    1.890123] PCI: Using configuration type 1 for device discovery",
"[    2.234567] Freeing unused kernel memory: 2048K",
"[    2.567890] Write protecting the kernel read-only data: 10240K",
"[    3.123456] Mounting /run as tmpfs...",
"[    3.567890] Mounted /run successfully.",
"[    3.789012] Starting udev Kernel Device Manager...",
"[ OK ] Started udev Kernel Device Manager.",
"[    4.123456] Activating swap /swapfile...",
"[ OK ] Activated swap /swapfile.",
"[    4.678901] Starting Flush Journal to Persistent Storage...",
"[ OK ] Started Flush Journal to Persistent Storage.",
"[ OK ] Reached target Local File Systems (Pre).",
"[ OK ] Mounted /boot.",
"[ OK ] Mounted /var/log.",
"[ OK ] Mounted /run/user/1000.",
"[    5.123456] Remounting root filesystem in read-write mode...",
"[ OK ] Remounted root filesystem in read-write mode.",
"[    6.123456] Initializing network interfaces...",
"[ OK ] Started Network Manager.",
"[    6.567890] Starting Raise network interfaces...",
"[ OK ] Raised network interfaces.",
"[    7.123456] Configuring time synchronization...",
"[ OK ] Started Network Time Synchronization.",
"[    8.123456] Loading kernel modules...",
"[    8.567890] Loaded kernel modules for storage, network, and audio.",
"[    9.123456] Starting GNOME Display Manager...",
"[ OK ] Started GNOME Display Manager.",
"[   10.123456] Starting CUPS Scheduler for printing...",
"[ OK ] Started CUPS Scheduler.",
"[   11.123456] Initializing D-Bus System Message Bus...",
"[ OK ] Started D-Bus System Message Bus.",
"[   12.123456] Starting system logging service...",
"[ OK ] Started system logging service.",
"[   13.123456] Checking and mounting additional filesystems...",
"[ OK ] All filesystems mounted successfully.",
"<br>",
"JKLinux 22.04.3 LTS TRAMUNTHACKCTF tty1",
"<br>",
"TRAMUNTHACKCTF login: root",
"Password: ",
];

var preloader = document.getElementById('preloader');
var delay = 1500;
var count = 0;
var repeat = 0;

function addLog() {
  var row = createLog('ok', count);
  preloader.appendChild(row);
  
  goScrollToBottom();
  
  count++;
  
  if (repeat == 0) {
    if (count > 3) {
      delay = 300*3;
    }
    
    if (count > 6) {
      delay = 100*3;
    }
    
    if (count > 8) {
      delay = 50*3;
    }
    
    if (count > 10) {
      delay = 10*3;
    }
  } else {
    if (count > 3) {
      delay = 10*3;
    }
  }
  
  if (count < strings.length) {
    setTimeout(function() {
      return addLog();
    }, delay);
  } else {
    setTimeout(function() {
      delay = 1000;
      return createLog("ok");
    }, 1000);
  }
}

function createLog(type, index) {
  var row = document.createElement('div');
  
  var spanStatus = document.createElement('span');
  spanStatus.className = type;
  spanStatus.innerHTML = type.toUpperCase();
  
  var message = (index != null) 
              ? strings[index] 
              : 'kernel: Initializing...';

  if(index == null) 
  {
    var preloader = $('#preloader');
    jQuery(preloader).fadeOut("slow");
    jQuery("#main").fadeIn("slow");
  }
  
  var spanMessage = document.createElement('span');
  spanMessage.innerHTML = message;
  
  // row.appendChild(spanStatus);
  row.appendChild(spanMessage);
  
  return row;
}

function goScrollToBottom() {
  $(document).scrollTop($(document).height()); 
}

function setCookie(cname,cvalue,exdays) {
  var d = new Date();
  d.setTime(d.getTime() + (exdays*24*60*60*1000));
  var expires = "expires=" + d.toGMTString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

// below method reference https://stackoverflow.com/questions/5639346/what-is-the-shortest-function-for-reading-a-cookie-by-name-in-javascript/25490531#25490531
function getCookie(a) {
  var b = document.cookie.match('(^|;)\\s*' + a + '\\s*=\\s*([^;]+)');
  return b ? b.pop() : '';
}

function checkCookie() {
  var user=getCookie("visited"); 
  if (parseInt(user) === 1) {   
    setCookie("visited", 1, 30); //this will update the cookie      
    jQuery("#main").fadeIn("slow"); 
  } else {  
    addLog();      
    setCookie("visited", 1, 30);   

  }
}

checkCookie();


