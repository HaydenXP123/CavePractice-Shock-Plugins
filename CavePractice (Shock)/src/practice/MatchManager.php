<?php

namespace practice;

use pocketmine\block\BlockIds;
use pocketmine\level\generator\GeneratorManager;
use practice\generators\ClassicGenerator;
use practice\task\AsyncDeleteFolder;
use practice\task\MatchTask;

class MatchManager{
	private $matches = [];
	private $plugin;

	public function __construct(Loader $plugin){
		$this->plugin = $plugin;
	}

	public function createMatch(PracticePlayer $player1, PracticePlayer $player2, int $kit){
		$player1->getInventory()->clearAll();
		$player2->getInventory()->clearAll();
		$name = 1;
		while($this->isMatch($name) or is_dir($this->plugin->getServer()->getDataPath() . "worlds/" . $name)){
			++$name;
		}
		$this->plugin->getServer()->generateLevel($name, null, GeneratorManager::getGenerator(GeneratorManager::getGeneratorName(ClassicGenerator::class)), []);
		$task = new MatchTask($this->plugin, $name, $player1, $player2, $kit);
		$this->addMatch($name, $task);
		$player1->setPlaying(true);
		$player1->setGameAlive(true);
		$player2->setPlaying(true);
		$player2->setGameAlive(true);
	}

	public function canUseBlock(int $id) : bool{
		if($id == BlockIds::COBBLESTONE){
			return true;
		}else{
			return false;
		}
	}

	public function onDisable(){
		foreach(array_keys($this->getMatches()) as $match){
			$this->stopMatch($match);
		}
	}

	public function stopMatch(string $name){
		$this->removeMatch($name);
		$level = $this->plugin->getServer()->getLevelByName($name);
		$this->plugin->getServer()->unloadLevel($level, true);
		$this->plugin->getServer()->getAsyncPool()->submitTask(new AsyncDeleteFolder($this->plugin->getServer()->getDataPath() . "worlds/" . $name));
	}

	public function getMatches() : array{
		return $this->matches;
	}

	public function setMatches(array $matches){
		$this->matches = $matches;
	}

	public function addMatch(string $name, MatchTask $task){
		$this->getMatches()[$name] = $task;
	}

	public function isMatch($name) : bool{
		return isset($this->getMatches()[$name]);
	}

	public function removeMatch($name){
		if($this->isMatch($name)){
			unset($this->getMatches()[$name]);
		}
	}

	public function getMatch($name) : ?MatchTask{
		if($this->isMatch($name)){
			return $this->getMatches()[$name];
		}

		return null;
	}
}