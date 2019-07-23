<?php
declare(strict_types=1);

namespace network;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\event\player\PlayerLoginEvent;

class EventListener implements Listener{
	/** @var NetworkLoader */
	private $plugin;

	public function __construct(NetworkLoader $plugin){
		$this->plugin = $plugin;
		$plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
	}

	public function handleCreation(PlayerCreationEvent $ev){
		$ev->setPlayerClass(NetworkPlayer::class);
	}

	public function handleLogin(PlayerLoginEvent $event){
		/** @var NetworkPlayer $player */
		$player = $event->getPlayer();
		//$this->plugin->getServer()->getAsyncPool()->submitTask(new LoginDataTask($player->getName(), $player->getAddress()));
	}

	public function handleChat(PlayerChatEvent $event){
		/** @var NetworkPlayer $player */
		$player = $event->getPlayer();

		//$event->setFormat($player->setChatFormat($event->getMessage()));
	}
}
