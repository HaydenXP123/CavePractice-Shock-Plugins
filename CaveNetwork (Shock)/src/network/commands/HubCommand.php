<?php
declare(strict_types=1);

namespace network\commands;

use network\NetworkLoader;
use network\NetworkPlayer;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;

class HubCommand extends PluginCommand{
	public function __construct(NetworkLoader $plugin){
		parent::__construct("hub", $plugin);
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args){
		if(!$sender instanceof NetworkPlayer) return false;

		$sender->transfer("play.theshocknetwork.com", 19132);
		return true;
	}
}