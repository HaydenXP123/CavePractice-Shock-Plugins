<?php

namespace practice\task;

use network\utils\RegionUtils;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat;
use practice\KitManager;
use practice\Loader;
use practice\PracticePlayer;

class MatchTask extends Task{
	const DEFAULT_TIME = 60 * 15;
	const STARTING_TIME = 3;
	const ENDING_TIME = 3;

	const STARTING = 0;
	const PLAYING = 1;
	const ENDING = 2;

	private $time = self::STARTING_TIME;
	private $state = self::STARTING;
	/** @var PracticePlayer */
	private $player1;
	/** @var PracticePlayer */
	private $player2;
	private $kit;
	/** @var Level */
	private $level;
	private $name; //TODO: Remove
	private $winner = "None";
	private $loser = "None";
	private $plugin;

	public function __construct(Loader $plugin, $name, PracticePlayer $player1, PracticePlayer $player2, int $kit){
		$this->plugin = $plugin;
		$this->kit = $kit;
		$this->name = $name;
		$this->level = $this->plugin->getServer()->getLevelByName($name);
		$this->setHandler($plugin->getScheduler()->scheduleRepeatingTask($this, 20));
		$this->player1 = $player1;
		$this->player2 = $player2;
	}

	public function intToString(int $int) : string{
		$m = floor($int / 60);
		$s = floor($int % 60);

		return (($m < 10 ? "0" : "") . $m . ":" . ($s < 10 ? "0" : "") . $s);
	}

	public function onRun(int $currentTick) : void{
		foreach($this->plugin->getServer()->getOnlinePlayers() as $player){
			$player->setNameTag(TextFormat::GOLD . "[" . ucfirst($player->getRank()) . "] " . TextFormat::WHITE . $player->getDisplayName() . TextFormat::GOLD . " [" . $player->getDeviceName() . "]" . TextFormat::EOL . TextFormat::WHITE . round($player->getHealth()) . TextFormat::RED . " ❤");
		}

		foreach($this->getPlayers() as $player){
			if(!$player->isOnline()){
				$this->onEnd($player);
			}else{
				if($player instanceof PracticePlayer){
					$player->clearScoreboard();
					$player->showScoreboard("§6§lShock§fPractice§r");
					$player->setScoreboardLine(1, "§r§7--------------------  ");
					$player->setScoreboardLine(2, "§6§lKit:§r§c " . KitManager::getKitName($this->kit));
					$player->setScoreboardLine(3, "§e§lTime:§r§c " . $this->intToString($this->time));
					$player->setScoreboardLine(4, "§6§lPing:§r§c " . $player->getPing());
					$player->setScoreboardLine(5, "§r§7--------------------");
				}
			}
		}
		switch($this->state){
			case self::STARTING:
				$this->time--;
				switch($this->time){
					case 2:
						$x = 15;
						$z = 40;
						RegionUtils::onChunkGenerated($this->level, $x >> 4, $z >> 4, function() use ($x, $z){
							$this->player1->teleport(new Position($x, 102, $z, $this->level));
						});

						$z = 10;
						RegionUtils::onChunkGenerated($this->level, $x >> 4, $z >> 4, function() use ($x, $z){
							$this->player2->teleport(new Position($x, 102, $z, $this->level));
						});

						break;
					case 1:
						foreach($this->getPlayers() as $player){
							if($player instanceof PracticePlayer){
								KitManager::give($player, $this->kit);
							}
						}

						$this->state = self::PLAYING;
						$this->time = self::DEFAULT_TIME;
						break;
				}
				break;

			case self::PLAYING:
				$this->time--;
				foreach($this->getPlayers() as $player){
					if($player instanceof PracticePlayer){
						if(!$player->isGameAlive()){
							$this->time = self::ENDING_TIME;
							$this->state = self::ENDING;
							$this->loser = $player->getName();
							$this->winner = $player->getName() !== $this->player1->getName() ? $this->player1->getName() : $this->player2->getName();

							return;
						}
					}else{
						$this->onEnd($player);
					}
				}

				if($this->time === 1){
					$this->onEnd(null);
				}
				break;

			case self::ENDING:
				$this->time--;
				if($this->time === 0){
					$this->onEnd(null);
				}
		}
	}

	public function onEnd(?PracticePlayer $playerLeft){
		foreach($this->getPlayers() as $online){
			if($online instanceof PracticePlayer){
				if(is_null($playerLeft) || $online->getName() !== $playerLeft->getName()){
					$online->clearScoreboard();

					$online->sendMessage(TextFormat::GRAY . "---------------");
					$online->sendMessage(TextFormat::GOLD . "Winner: " . TextFormat::WHITE . $this->winner);
					$online->sendMessage(TextFormat::YELLOW . "Loser: " . TextFormat::WHITE . $this->loser);
					$online->sendMessage(TextFormat::GRAY . "---------------");
					$online->giveLobbyItems();
					$online->teleport($online->getServer()->getDefaultLevel()->getSafeSpawn(), 0, 0);
				}
			}
		}
		$this->cancel();
	}

	public function cancel(){
		$this->getHandler()->cancel();
		Loader::getMatchManager()->stopMatch($this->name);
	}

	/**
	 * @return PracticePlayer[]
	 */
	public function getPlayers() : array{
		return [$this->player1, $this->player2];
	}
}