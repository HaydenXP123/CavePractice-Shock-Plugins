<?php

namespace network\commands;

use network\NetworkLoader;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\utils\TextFormat;

class FreezeCommand extends PluginCommand{
	/** @var NetworkLoader */
	private $plugin;
	private $freeze = [];

	public function __construct(NetworkLoader $plugin){
		parent::__construct("freeze", $plugin);
		$this->plugin = $plugin;
		$this->setPermission("shock.command.freeze");
		$this->setUsage("/freeze <playerName>");
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) : bool{
		if(!$this->testPermission($sender)){
			return true;
		}

		if(isset($args[0])){
			$p = $args[0];
			$player = $this->plugin->getServer()->getPlayer($p);
			if($player === null){
				$sender->sendMessage(TextFormat::RED . " Player not found!");
			}else{
				$name = $player->getName();
				if(!isset($this->freeze[$name])){
					$this->freeze[$player->getName()] = $name;
					$player->setImmobile(true);
					$sender->sendMessage("You have froze $name, type /freeze <playerName> again to unfreeze them!");
					$player->sendMessage(TextFormat::RED . "You have been frozen!");

					return true;
				}else{
					$player->setImmobile(false);
					$sender->sendMessage("Unfroze $name.");
					$player->sendMessage(TextFormat::RED . "You have been unfrozen!");
					unset($this->freeze[$name]);

					return true;
				}
			}
		}else{
			throw new InvalidCommandSyntaxException();
		}

		return true;
	}
}