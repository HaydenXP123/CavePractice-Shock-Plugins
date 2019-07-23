<?php

namespace practice;

use pocketmine\entity\Living;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\Potion;
use pocketmine\level\generator\GeneratorManager;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use practice\command\SpawnCommand;
use practice\generators\ClassicGenerator;
use practice\listener\MatchListener;
use practice\listener\PlayerListener;

class Loader extends PluginBase{

	public static $matchManager;

	public function onEnable(){
		self::$matchManager = new MatchManager($this);
		$this->getServer()->loadLevel("arenas");
		GeneratorManager::addGenerator(ClassicGenerator::class, "classic");
		new PlayerListener($this);
		new MatchListener($this);
		$this->registerCommands();
		$this->registerItems();
	}

	public function onDisable(){
		self::getMatchManager()->onDisable();
	}

	public function registerItems(){
		ItemFactory::registerItem((new class extends Potion{
			public function onConsume(Living $consumer){
				if($consumer instanceof Player){
					$consumer->getInventory()->sendContents($consumer);
				}
			}

			public function getResidue(){
				return ItemFactory::get(Item::AIR);
			}
		}), true);
	}

	public function registerCommands(){
		$this->getServer()->getCommandMap()->register("spawn", new SpawnCommand($this));
	}

	public static function getMatchManager() : MatchManager{
		return self::$matchManager;
	}
}