<?php
declare(strict_types=1);

namespace network\utils;

use network\NetworkPlayer;
use pocketmine\network\mcpe\protocol\SetDisplayObjectivePacket;
use pocketmine\network\mcpe\protocol\SetScorePacket;
use pocketmine\network\mcpe\protocol\types\ScorePacketEntry;

class Scoreboard{

	private $player;
	/** @var array */
	protected $scoreboardLines = [];

	public function __construct(NetworkPlayer $player){
		$this->player = $player;
	}

	public function setTitle(string $title) : void{
		$pk = new SetDisplayObjectivePacket();
		$pk->displaySlot = "sidebar";
		$pk->objectiveName = $this->player->getName();
		$pk->displayName = $title;
		$pk->criteriaName = "dummy";
		$pk->sortOrder = 0;
		$this->player->sendDataPacket($pk);
	}

	public function getLine(int $score) : ?string{
		return empty($this->scoreboardLines[$score]) ? null : $this->scoreboardLines[$score];
	}

	public function clear(){
		for($line = 0; $line <= 15; $line++){
			$this->removeLine($line);
		}
	}

	public function removeLine(int $line){
		$pk = new SetScorePacket();
		$pk->type = SetScorePacket::TYPE_REMOVE;
		$entry = new ScorePacketEntry();
		$entry->objectiveName = $this->player->getName();
		$entry->score = 15 - $line;
		$entry->scoreboardId = ($line);
		$pk->entries[] = $entry;
		$this->player->sendDataPacket($pk);
	}

	public function setLine(int $score, string $line) : void{
		$entry = new ScorePacketEntry();
		$entry->objectiveName = $this->player->getName();
		$entry->type = $entry::TYPE_FAKE_PLAYER;
		$entry->customName = $line;
		$entry->score = $score;
		$entry->scoreboardId = $score;
		if(isset($this->scoreboardLines[$score])){
			$pk = new SetScorePacket();
			$pk->type = $pk::TYPE_REMOVE;
			$pk->entries[] = $entry;
			$this->player->sendDataPacket($pk);
		}
		$pk = new SetScorePacket();
		$pk->type = $pk::TYPE_CHANGE;
		$pk->entries[] = $entry;
		$this->player->sendDataPacket($pk);
		$this->scoreboardLines[$score] = $line;
	}

	public function setEmptyLine(int $line){
		$text = str_repeat(" ", $line);
		$this->setLine($line, $text);
	}
}