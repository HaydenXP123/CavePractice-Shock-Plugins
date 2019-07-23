<?php
declare(strict_types=1);

namespace network\tasks;

use network\NetworkLoader;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;

class UpdateRankTask extends AsyncTask{

	private $playerName;
	private $rankName;

	public function __construct($playerName, $rankName){
		$this->playerName = $playerName;
		$this->rankName = $rankName;
	}

	public function onRun(){
		$sql = new \mysqli("play.theshocknetwork.com", "root", "theshocknetwork575", "shock", 3306);

		$data = $sql->prepare("UPDATE players SET rank='{$this->rankName}' WHERE username='{$this->playerName}'");
		$data->execute();
		$data->fetch();
		$data->close();
	}

	public function onCompletion(Server $server){
		NetworkLoader::getInstance()->playerCache[$this->playerName] = [
			"rank" => $this->rankName
		];
		$player = $server->getPlayer($this->playerName);

		$player->updatePermissions();
	}
}