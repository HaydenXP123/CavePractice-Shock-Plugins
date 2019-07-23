<?php

namespace network\commands;

use network\NetworkLoader;
use network\NetworkPlayer;
use network\tasks\UpdateRankTask;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\utils\TextFormat;

class SetRankCommand extends PluginCommand{
	/** @var NetworkLoader */
	private $plugin;

	public function __construct(NetworkLoader $plugin){
		parent::__construct("setrank", $plugin);
		$this->plugin = $plugin;
		$this->setPermission("shock.command.setrank");
		$this->setUsage("/setrank <playerName> <rankName>");
	}

	public function execute(CommandSender $sender, $commandLabel, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}

		if(count($args) < 2){
			throw new InvalidCommandSyntaxException();
		}

		$player = $sender->getServer()->getPlayer($args[0]);
		if(!$player instanceof NetworkPlayer) return false;
		if(!$player->isOnline()){
			$sender->sendMessage(TextFormat::RED . "Player not found!");

			return false;
		}
		$rank = strtolower($args[1]);
		if(!array_key_exists($rank, $this->plugin->ranks->getAll())){
			$sender->sendMessage(TextFormat::RED . "Rank not found!");

			return false;
		}
		$sender->getServer()->getAsyncPool()->submitTask(new UpdateRankTask($player->getName(), $rank));
		$sender->sendMessage(TextFormat::GOLD . $player->getName() . "'s" . TextFormat::WHITE . " rank set to " . TextFormat::RED . ucfirst($rank));
		$player->sendMessage(TextFormat::WHITE . "Your rank has been set to " . TextFormat::GOLD . ucfirst($rank));
		return true;
	}
}