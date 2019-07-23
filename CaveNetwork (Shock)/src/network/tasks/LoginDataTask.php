<?php
declare(strict_types=1);

namespace network\tasks;

use mysqli;
use network\NetworkLoader;
use network\NetworkPlayer;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;

class LoginDataTask extends AsyncTask{

	private $playerName;
	private $playerIp;

	public function __construct($playerName, $playerIp){
		$this->playerName = $playerName;
		$this->playerIp = $playerIp;
	}

	public function onRun(){
		$sql = new mysqli();

		$lowerName = strtolower($this->playerName);
		$checkBan = $sql->prepare("SELECT username,ip,ban_reason FROM banned WHERE username='{$lowerName}'");
		$checkBan->bind_result($bannedName, $bannedIp, $banReason);
		$checkBan->execute();
		$checkBan->fetch();
		$checkBan->close();

		if($bannedIp === null || $bannedIp !== $this->playerIp || $lowerName !== $bannedName){
			$rank = "guest";
			$prepare = $sql->prepare("INSERT IGNORE INTO players(username, rank, ip) VALUES(?, ?, ?)");
			$prepare->bind_param("sss", $this->playerName, $rank, $this->playerIp);
			$prepare->execute();

			$updateIp = $sql->prepare("UPDATE players SET ip='{$this->playerIp}' WHERE username='{$this->playerName}'");
			$updateIp->execute();

			$data = $sql->prepare("SELECT rank,ip FROM players WHERE username='{$this->playerName}'");
			$data->bind_result($rank, $ip);
			$data->execute();
			$data->fetch();
			$data->close();

			$this->setResult([
				"rank" => $rank,
				"ip" => $ip
			]);
		}else{
			$this->setResult([
				"banned" => true,
				"ban_reason" => $banReason
			]);
		}
	}

	public function onCompletion(Server $server){
		$player = $server->getPlayer($this->playerName);
		if($player instanceof NetworkPlayer){
			if(!isset($this->getResult()["banned"])){
				NetworkLoader::getInstance()->playerCache[$this->playerName] = [
					"rank" => $this->getResult()["rank"],
					"ip" => $this->getResult()["ip"]
				];
				$player->updatePermissions();
			}else{
				$player->close("", $this->getResult()["ban_reason"]);
			}
		}
	}
}