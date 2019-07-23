<?php

namespace practice;

use network\NetworkPlayer;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\utils\TextFormat;

class PracticePlayer extends NetworkPlayer{

	private $gameAlive = false;
	private $isPlaying = false;
	private $inQueue = false;
	private $queueKit;
	private $duel = false;
	private $request = "";

	public function isGameAlive() : bool{
		return $this->gameAlive;
	}

	public function setGameAlive(bool $alive){
		$this->gameAlive = $alive;
	}

	public function isPlaying() : bool{
		return $this->isPlaying;
	}

	public function setPlaying(bool $playing){
		$this->isPlaying = $playing;
	}

	public function giveLobbyItems(){
		$this->setGamemode(0);
		$this->setHealth($this->getMaxHealth());
		$this->setFood($this->getMaxFood());
		$this->getInventory()->clearAll();
		$this->getArmorInventory()->clearAll();
		$this->removeAllEffects();
		$this->extinguish();
		$this->getInventory()->setItem(0, Item::get(ItemIds::COMPASS, 0, 1)->setCustomName(TextFormat::RESET . TextFormat::WHITE . "Kit Selector"));
		$this->getInventory()->setItem(4, Item::get(ItemIds::ENDER_EYE, 0, 1)->setCustomName(TextFormat::RESET . TextFormat::WHITE . "Arena PvP"));
		$this->getInventory()->setItem(8, Item::get(Item::DIAMOND_SWORD, 0, 1)->setCustomName(TextFormat::RESET . TextFormat::WHITE . "Duels"));

		$this->setInQueue(false);
		$this->setPlaying(false);
		$this->setDuel(false);
		$this->setRequest("");
	}

	public function giveArenaKit(){
		$this->setHealth($this->getMaxHealth());
		$this->setFood($this->getMaxFood());
		$this->getInventory()->clearAll();
		$this->removeAllEffects();
		$this->getArmorInventory()->setHelmet(Item::get(ItemIds::DIAMOND_HELMET));
		$this->getArmorInventory()->setChestplate(Item::get(ItemIds::DIAMOND_CHESTPLATE));
		$this->getArmorInventory()->setLeggings(Item::get(ItemIds::DIAMOND_LEGGINGS));
		$this->getArmorInventory()->setBoots(Item::get(ItemIds::DIAMOND_BOOTS));
		$this->getInventory()->setItem(0, Item::get(ItemIds::DIAMOND_SWORD, 0, 1));
		$this->getInventory()->setItem(1, Item::get(ItemIds::GOLDEN_APPLE, 0, 64));
		$this->getInventory()->setItem(2, Item::get(ItemIds::STEAK, 0, 64));
	}

	public function isInQueue() : bool{
		return $this->inQueue;
	}

	public function setInQueue(bool $inqueue){
		$this->inQueue = $inqueue;
	}

	public function getQueueKit() : int{
		return $this->queueKit;
	}

	public function setQueueKit(int $kit){
		$this->queueKit = $kit;
	}

	public function checkQueue(){
		$this->sendMessage(TextFormat::GOLD . "Entering queue...");
		foreach($this->getServer()->getOnlinePlayers() as $player){
			if($player instanceof PracticePlayer and $player->getName() != $this->getName()){
				if($player->isInQueue() && $player->getQueueKit() === $this->getQueueKit()){
					Loader::getMatchManager()->createMatch($this, $player, $this->getQueueKit());
					$this->sendMessage(TextFormat::YELLOW . "Found a match against " . TextFormat::GOLD . $player->getName() . ".");
					$player->sendMessage(TextFormat::YELLOW . "Found a match against " . TextFormat::GOLD . $this->getName() . ".");
					$player->setInQueue(false);
					$this->setInQueue(false);

					return;
				}
			}
		}
	}

	public function isDuel() : bool{
		return $this->duel;
	}

	public function setDuel(bool $duel){
		$this->duel = $duel;
	}

	public function getRequest() : string{
		return $this->request;
	}

	public function setRequest(string $request){
		$this->request = $request;
	}
}