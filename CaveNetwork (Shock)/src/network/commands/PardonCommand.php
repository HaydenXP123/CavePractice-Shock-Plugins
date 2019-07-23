<?php
declare(strict_types=1);

namespace network\commands;

use network\NetworkLoader;
use network\tasks\UpdateBanTask;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\command\utils\InvalidCommandSyntaxException;

class PardonCommand extends PluginCommand{
	/** @var NetworkLoader */
	private $plugin;

	public function __construct(NetworkLoader $plugin){
		parent::__construct("pardon", $plugin);
		$this->plugin = $plugin;
		$this->setPermission("shock.command.pardon");
		$this->setUsage("/pardon <playerName>");
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}

		if(count($args) < 1){
			throw new InvalidCommandSyntaxException();
		}

		$name = strtolower($args[0]);
		$sender->getServer()->getAsyncPool()->submitTask(new UpdateBanTask($name, "", true, ""));
		$sender->sendMessage("You have pardoned " . $name . "!");
		return true;
	}
}