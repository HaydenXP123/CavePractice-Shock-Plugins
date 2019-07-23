<?php
declare(strict_types=1);

namespace network\managers;

use network\commands\BanCommand;
use network\commands\FreezeCommand;
use network\commands\HubCommand;
use network\commands\PardonCommand;
use network\commands\SetRankCommand;
use network\commands\WorldCommand;
use network\NetworkLoader;

class CommandManager{

	private $plugin;

	public function __construct(NetworkLoader $plugin){
		$this->plugin = $plugin;
		$this->init();
	}

	private function init(){
		$map = $this->plugin->getServer()->getCommandMap();
		$commands = [
			"effect", "enchant", "gamemode",
			"give", "list", "setworldspawn",
			"status", "stop", "tell",
			"kick", "tp", "whitelist"
		];
		foreach($map->getCommands() as $command){
			if(!in_array($command->getName(), $commands)){
				$map->unregister($command);
			}
		}

		$map->registerAll("network", [
			new SetRankCommand($this->plugin),
			new WorldCommand($this->plugin),
			new BanCommand($this->plugin),
			new PardonCommand($this->plugin),
			new FreezeCommand($this->plugin),
			new HubCommand($this->plugin)
		]);
	}
}