<?php
declare(strict_types=1);

namespace network\commands;

use network\NetworkLoader;
use network\NetworkPlayer;
use network\tasks\UpdateBanTask;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\command\utils\InvalidCommandSyntaxException;

class BanCommand extends PluginCommand{
	/** @var NetworkLoader */
	private $plugin;

	public function __construct(NetworkLoader $plugin){
		parent::__construct("ban", $plugin);
		$this->plugin = $plugin;
		$this->setPermission("shock.command.ban");
		$this->setUsage("/ban <playerName> <banReason>");
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}

		if(count($args) < 2){
			throw new InvalidCommandSyntaxException();
		}

		$name = array_shift($args);
		$reason = implode(" ", $args);
		$player = $sender->getServer()->getPlayer($name);
		if(!$player instanceof NetworkPlayer){
			$sender->sendMessage("Player not found!");
			return false;
		}
		$sender->getServer()->getAsyncPool()->submitTask(new UpdateBanTask(strtolower($player->getName()), $player->getAddress(), false, $reason));
		$sender->sendMessage("You have banned " . $player->getName() . "!");

		$player->kick($reason, false);

		return true;
	}
}