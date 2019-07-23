<?php
declare(strict_types=1);

namespace network\tasks;

use pocketmine\scheduler\AsyncTask;

class UpdateBanTask extends AsyncTask{

	private $playerName;
	private $playerIp;
	private $isPardon;
	private $banReason;

	public function __construct($playerName, $playerIp, $isPardon, $banReason){
		$this->playerName = $playerName;
		$this->playerIp = $playerIp;
		$this->isPardon = $isPardon;
		$this->banReason = $banReason;
	}

	public function onRun(){
		$sql = new \mysqli();

		if(!$this->isPardon){
			$ban = $sql->prepare("INSERT IGNORE INTO banned(username, ip, ban_reason) VALUES(?, ?, ?)");
			$ban->bind_param("sss", $this->playerName, $this->playerIp, $this->banReason);
			$ban->execute();
			$ban->close();
		}else{
			$pardon = $sql->prepare("DELETE FROM banned WHERE username='{$this->playerName}'");
			$pardon->execute();
			$pardon->close();
		}
	}
}