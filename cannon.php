<?php

	$file = ".cannon/config.json";
	
	function writefile($file,$content){
		$fp = fopen($file,"w+");
		fwrite($fp, $content . PHP_EOL);
		fclose($fp);	
	}

	function contentfile($file){
		return file_get_contents($file);
	}	

	function status($project, $port, $url, $pid, $file){
		$process = '/usr/bin/php -S localhost:' . $port . ' -t ' . $url;
		$bash   = exec('ps '.$pid);
		$pos = strpos($bash, $process);
		if ($pos !== false) {
		    $find = true;
		} else {
			$content = contentfile($file);
			$servers = json_decode($content,true);	
			$servers[$project]['pid'] = "";
			$json = json_encode($servers);
			writefile($file,$json);	
		    $find = false;
		}
		return $find;
	}

	if ($_GET) {
		switch ($_GET['action']) {
			case 'start':
					$content = contentfile($file);
					$servers = json_decode($content,true);	
					$command =  '/usr/bin/php -S localhost:' . $servers[$_GET['project']]['port'] . ' -t ' . $servers[$_GET['project']]['url'] . ' > /dev/null 2>&1 & echo $!; ';
					$pid = exec($command, $output);
					$servers[$_GET['project']]['pid'] = $pid;
					$json = json_encode($servers);
					writefile($file,$json);					
					header('Location: cannon.php');
				break;
			case 'stop':
					$content = contentfile($file);
					$servers = json_decode($content,true);	
					exec("kill -9 ".$servers[$_GET['project']]['pid']);
					$servers[$_GET['project']]['pid'] = "";
					$json = json_encode($servers);
					writefile($file,$json);					
					header('Location: cannon.php');
				break;
			case 'remove':
					$content = contentfile($file);
					$servers = json_decode($content,true);	
					exec("kill -9 ".$servers[$_GET['project']]['pid']);
					unset($servers[$_GET['project']]);
					$json = json_encode($servers);
					writefile($file,$json);					
					header('Location: cannon.php');
				break;							
		}

	}

	if ($_POST) {
		$content = contentfile($file);
		$servers = json_decode($content,true);
		$servers[$_POST['project']]=array(
			'project' => $_POST['project'], 
			'url' => $_POST['url'], 
			'port' => $_POST['port'], 
		);
		$json = json_encode($servers);
		writefile($file,$json);		
		header('Location: cannon.php');
	}

?>
<h2>New server</h2>
<form action="" method="post">
	Name project  <input type="text" name="project"> 
	Url project * <input type="text" name="url">
	Port <input type="text" name="port">
	<input type="submit" value="Create">
</form>
<br>
<h2>List servers</h2>
<table>
	<tr>
		<td>Project</td>
		<td>Port</td>
		<td>Pid</td>
		<td>Status</td>
		<td>Action</td>
	</tr>
	<?php
		if (!file_exists($file)) {
			mkdir(".cannon/", 0777);
			writefile($file,'');		
		} 
		$content = contentfile($file);
		$servers = json_decode($content,true);
		if (is_array($servers)):
		foreach ($servers as $value):
	?>
	<tr>
		<td><?= $value['project']?></td>
		<td><?= $value['port']?></td>
		<td>
		<?php
			if (isset($value['pid'])) {
				echo $value['pid'];
			}
		?>
		</td>
		<td>
		<?php
			if (isset($value['pid'])) {
				if (status($value['project'], $value['port'], $value['url'], $value['pid'], $file)) {
					echo "Running";
				} else {
					echo "Stop";
				}
			}
		?>	
		</td>
		<td>
			<a href="cannon.php?project=<?=$value['project']?>&action=start">Start</a> 
			<a href="cannon.php?project=<?=$value['project']?>&action=stop">Stop</a>  
			<a href="cannon.php?project=<?=$value['project']?>&action=remove">Remove</a>  
		</td>
	</tr>
	<?php
		endforeach;
		endif;
	?>
</table>