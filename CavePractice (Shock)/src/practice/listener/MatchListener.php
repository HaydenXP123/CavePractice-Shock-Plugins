<?php

namespace practice\listener;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use practice\Loader;
use practice\PracticePlayer;

class MatchListener implements Listener{
	private $plugin;

	public function __construct(Loader $plugin){
		$this->plugin = $plugin;
		$plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
	}

	public function onDeath(PlayerDeathEvent $event){
		$player = $event->getPlayer();
		if($player instanceof PracticePlayer){
			$event->setDeathMessage("");
			$event->setDrops([]);
			if($player->isPlaying()){
				$player->setGameAlive(false);
				$player->setGamemode(PracticePlayer::SPECTATOR);
				$player->setHealth(20);
				$player->setFood(20);
				$ev = $player->getLastDamageCause();
				if($ev instanceof EntityDamageByEntityEvent){
					$damager = $ev->getDamager();
					if($damager instanceof PracticePlayer and $damager->isOnline()){
						foreach($player->getLevel()->getPlayers() as $p){
							$p->sendMessage(TextFormat::RED . $player->getName() . " was killed by " . $damager->getName());
						}
					}
				}else{
					foreach($player->getLevel()->getPlayers() as $p){
						$p->sendMessage(TextFormat::RED . $player->getName() . " died");
					}
				}
			}
		}
	}

	public function handleEntityDamage(EntityDamageEvent $ev){
		/** @var PracticePlayer $entity */
		$entity = $ev->getEntity();
		$cause = $ev->getCause();
		if($entity instanceof PracticePlayer){
			if($cause == EntityDamageEvent::CAUSE_FALL){
				$ev->setCancelled(true);
			}
			if($cause === EntityDamageEvent::CAUSE_PROJECTILE){
				/** @var Player $damager */
				$damager = $ev->getDamager();
				$damager->sendMessage(TextFormat::GOLD . $entity->getName() . TextFormat::WHITE . " is at " . TextFormat::RED . $entity->getHealth() / 2 . " HP");
			}
		}
	}

	public function onBreak(BlockBreakEvent $event){
		$player = $event->getPlayer();
		if($player instanceof PracticePlayer){
			if($player->isPlaying()){
				if(!Loader::getMatchManager()->canUseBlock($event->getBlock()->getId())){
					$event->setCancelled(true);
				}
			}
		}
	}

	public function onPlace(BlockPlaceEvent $event){
		$player = $event->getPlayer();
		if($player instanceof PracticePlayer){
			if($player->isPlaying()){
				if(!Loader::getMatchManager()->canUseBlock($event->getBlock()->getId())){
					$event->setCancelled(true);
				}
			}
		}
	}

	public function onQuit(PlayerQuitEvent $event){
		$player = $event->getPlayer();
		if($player instanceof PracticePlayer){
			if(Loader::getMatchManager()->isMatch($player->getLevel()->getName())){
				Loader::getMatchManager()->getMatch($player->getLevel()->getName())->onEnd($player);
			}
		}
	}
}