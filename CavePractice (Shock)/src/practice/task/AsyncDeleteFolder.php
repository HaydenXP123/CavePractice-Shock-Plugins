<?php

namespace practice\task;

use pocketmine\plugin\PluginException;
use pocketmine\scheduler\AsyncTask;

class AsyncDeleteFolder extends AsyncTask{
	private $dir = "";

	public function __construct(string $dir){
		$this->dir = $dir;
	}

	public function onRun(){
		$this->deleteDir($this->dir);
	}

	public function deleteDir($dirPath){
		if(!is_dir($dirPath)){
			throw new PluginException("$dirPath must be a directory");
		}
		if(substr($dirPath, strlen($dirPath) - 1, 1) != '/'){
			$dirPath .= '/';
		}
		$files = glob($dirPath . '*', GLOB_MARK);
		foreach($files as $file){
			if(is_dir($file)){
				$this->deleteDir($file);
			}else{
				unlink($file);
			}
		}
		rmdir($dirPath);
	}
}