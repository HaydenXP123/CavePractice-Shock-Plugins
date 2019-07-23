<?php

namespace practice\listener;

use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\ModalForm;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\utils\TextFormat;
use practice\KitManager;
use practice\Loader;
use practice\PracticePlayer;

class PlayerListener implements Listener{
	private $plugin;
	private $kitQueue;

	public function __construct(Loader $plugin){
		$this->plugin = $plugin;
		$plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
	}

	public function onCreation(PlayerCreationEvent $event){
		$event->setPlayerClass(PracticePlayer::class);
	}

	public function onJoin(PlayerJoinEvent $event){
		$event->setJoinMessage("");
		/** @var PracticePlayer $player */
		$player = $event->getPlayer();
		$player->setFood(20);
		$player->setHealth(20);
		$player->teleport($player->getServer()->getDefaultLevel()->getSpawnLocation());
		$player->giveLobbyItems();
	}

	public function onQuit(PlayerQuitEvent $event){
		$event->setQuitMessage("");
	}

	public function handleInteract(PlayerInteractEvent $ev){
		$player = $ev->getPlayer();
		$item = $ev->getItem();
		if($ev->getAction() !== PlayerInteractEvent::RIGHT_CLICK_AIR) return;
		if($player instanceof PracticePlayer){
			if($player->getLevel()->getName() === $player->getServer()->getDefaultLevel()->getName()){
				switch($item->getId()){
					case Item::COMPASS:
						$form = new SimpleForm(function(PracticePlayer $player, $data){
							if($data === null) return;
							if(isset(array_keys(KitManager::$kits)[$data])){
								$player->setQueueKit($data);
								$player->setInQueue(true);
								$player->getInventory()->clearAll();
								$player->getInventory()->setItem(0, Item::get(Item::PAPER, 0, 1)->setCustomName(TextFormat::RESET . TextFormat::RED . "Leave Queue"));
								$player->checkQueue();
							}
						});

						$form->setTitle("Kit Selector");
						foreach(KitManager::$kits as $kit){
							$form->addButton($kit);
						}

						if(!$player->isInQueue()){
							$player->sendForm($form);
						}
						break;
					case Item::PAPER:
						if($player->isPlaying()){
							Loader::getMatchManager()->getMatch($player->getLevel()->getName())->onEnd($player);
						}
						$player->sendMessage(TextFormat::GOLD . "You have left the queue!");
						$player->giveLobbyItems();
						break;
					case Item::ENDER_EYE:
						if(!$player->isInQueue()){
							if(!in_array($player->getDeviceOS(), [1, 2, 4])){
								$player->teleport(new Position(77, 92, 113, $this->plugin->getServer()->getLevelByName("arenas")));
								$player->giveArenaKit();
								$player->sendMessage("You have been teleported to " . TextFormat::GOLD . "Win10 Arena");
							}else{
								$player->teleport(new Position(-263, 88, 157, $this->plugin->getServer()->getLevelByName("arenas")));
								$player->giveArenaKit();
								$player->sendMessage("You have been teleported to " . TextFormat::GOLD . "Mobile Arena");
							}
						}
						break;
					case Item::DIAMOND_SWORD:
						$opponent = $player->getServer()->getPlayer($player->getRequest());
						if($player->getRequest() === ""){
							$form = new CustomForm(function(PracticePlayer $player, $data){
								if($data === null || !isset($data[0]) && !isset($data[1])) return;
								$opponent = $player->getServer()->getPlayer($data[0]);
								if($opponent instanceof PracticePlayer && $opponent->getName() !== $player->getName()){
									if($opponent->getRequest() === ""){
										$player->sendMessage(TextFormat::GREEN . "You have successfully sent a duel request to " . $opponent->getName() . "!");
										$player->setDuel(true);
										$player->setRequest($opponent->getName());
										$opponent->setRequest($player->getName());
										$opponent->sendMessage(TextFormat::YELLOW . $player->getName() . " sent you a duel request.");
										$this->kitQueue = $data[1];
									}else{
										$player->sendMessage(TextFormat::RED . $opponent->getName() . " already has a duel request pending!");
									}
								}else{
									$player->sendMessage(TextFormat::RED . "Player not found!");
								}
							});

							$form->setTitle("Player Duel");
							$form->addInput("\nOpponent", "Steve");
							$form->addDropdown("Kit", array_values(KitManager::$kits));

							$player->sendForm($form);
						}elseif($opponent instanceof PracticePlayer && $player->getRequest() === $opponent->getName()){
							$form = new ModalForm(function(PracticePlayer $player, $data){
								if($data === null) return;
								$opponent = $player->getServer()->getPlayer($player->getRequest());
								if($data){
									if($opponent instanceof PracticePlayer){
										if(!$opponent->isPlaying() and $opponent->isDuel()){
											Loader::getMatchManager()->createMatch($player, $opponent, $this->kitQueue);
											$opponent->setInQueue(false);
											$player->setInQueue(false);
											$opponent->sendMessage(TextFormat::GREEN . "Your duel request has been accepted!");
											$player->sendMessage(TextFormat::GREEN . "You have successfully accepted the duel request!");
										}else $player->sendMessage(TextFormat::RED . "This player is already in a match!");
									}else $player->sendMessage(TextFormat::RED . "Player not found!");
								}else{
									$player->setRequest("");
									if($opponent instanceof PracticePlayer){
										$opponent->setRequest("");
										$opponent->sendMessage(TextFormat::RED . $player->getName() . " has denied your duel request.");
									}
									$player->setDuel(false);
									$player->sendMessage(TextFormat::RED . "You have denied the duel request.");
								}
							});

							$form->setTitle("Player Duel");
							$form->setContent($player->getRequest() . " has sent you a duel request.");
							$form->setButton1("Accept?");
							$form->setButton2("Deny?");
							$player->sendForm($form);
						}
						break;
				}
			}
		}
	}

	public function onDamage(EntityDamageEvent $event){
		$entity = $event->getEntity();
		if($entity instanceof PracticePlayer){
			if($entity->getLevel()->getName() === $entity->getServer()->getDefaultLevel()->getName()){
				$event->setCancelled();
			}
		}
	}

	public function onDrop(PlayerDropItemEvent $event){
		$player = $event->getPlayer();
		if($player->getLevel()->getName() === $player->getServer()->getDefaultLevel()->getName()){
			$event->setCancelled();
		}
	}

	public function onBreak(BlockBreakEvent $event){
		$player = $event->getPlayer();
		if($player instanceof PracticePlayer){
			if($player->getLevel()->getName() === $player->getServer()->getDefaultLevel()->getName()){
				if($player->hasPermission("shock.bypass")) return;
				$event->setCancelled();
			}

			if($player->getLevel()->getName() === "arenas"){
				if($player->hasPermission("shock.bypass")) return;
				$event->setCancelled();
			}
		}
	}

	public function onPlace(BlockPlaceEvent $event){
		$player = $event->getPlayer();
		if($player instanceof PracticePlayer){
			if($player->getLevel()->getName() === $player->getServer()->getDefaultLevel()->getName()){
				if($player->hasPermission("shock.bypass")) return;
				$event->setCancelled();
			}

			if($player->getLevel()->getName() === "arenas"){
				if($player->hasPermission("shock.bypass")) return;
				$event->setCancelled();
			}
		}
	}

	public function onExhaust(PlayerExhaustEvent $event){
		$player = $event->getPlayer();
		if($player instanceof PracticePlayer){
			if($player->getLevel()->getName() === $player->getServer()->getDefaultLevel()->getName()){
				$event->setCancelled();
			}
		}
	}

	public function onDeath(PlayerDeathEvent $event){
		$player = $event->getPlayer();
		if($player instanceof PracticePlayer){
			if($player->getLevel()->getName() === $player->getServer()->getLevelByName("arenas")){
				$event->setDeathMessage(null);
			}
		}
	}

	public function onRespawn(PlayerRespawnEvent $event){
		$player = $event->getPlayer();
		if($player instanceof PracticePlayer){
			$player->giveLobbyItems();
		}
	}
}
