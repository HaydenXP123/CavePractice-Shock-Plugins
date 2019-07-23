<?php

namespace network\commands;

use network\NetworkLoader;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\utils\TextFormat;

class WorldCommand extends PluginCommand{
	/** @var NetworkLoader */
	private $plugin;

	public function __construct(NetworkLoader $plugin){
		parent::__construct("world", $plugin);
		$this->plugin = $plugin;
		$this->setPermission("shock.command.world");
		$this->setUsage("/world <worldName>");
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) : bool{
		if(!$this->testPermission($sender)){
			return true;
		}

		if(count($args) === 1){
			$sender->getServer()->loadLevel($args[0]);
			if(($level = $sender->getServer()->getLevelByName($args[0])) !== null){
				$sender->teleport($level->getSafeSpawn());
				$sender->sendMessage("Teleported to Level: " . $level->getName());

				return true;
			}else{
				$sender->sendMessage(TextFormat::RED . "World: \"" . $args[0] . "\" does not exist");

				return false;
			}
		}elseif(count($args) > 1 && count($args) < 3){
			$sender->getServer()->loadLevel($args[1]);
			if(($level = $sender->getServer()->getLevelByName($args[1])) !== null){
				$player = $sender->getServer()->getPlayer($args[0]);
				if($player === null){
					$sender->sendMessage("Player not found.");

					return false;
				}
				$player->teleport($level->getSafeSpawn());
				$player->sendMessage("Teleported to Level: " . $level->getName());

				return true;
			}else{
				$sender->sendMessage(TextFormat::RED . "World: \"" . $args[1] . "\" does not exist!");

				return false;
			}
		}else{
			throw new InvalidCommandSyntaxException();
		}
	}
}