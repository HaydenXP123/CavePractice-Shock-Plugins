<?php
declare(strict_types=1);

namespace network;

use network\items\GoldenHead;
use network\managers\CommandManager;
use network\managers\PermissionManager;
use pocketmine\item\ItemFactory;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as TF;

class NetworkLoader extends PluginBase{
	/** @var NetworkLoader */
	private static $instance;
	/** @var array */
	public $playerCache = [];
	/** @var Config */
	public $ranks;

	public function onEnable(){
		//CREATE TABLE IF NOT EXISTS players(username VARCHAR(20) PRIMARY KEY, rank VARCHAR(20) NOT NULL, ip VARCHAR(20) NOT NULL)
		//CREATE TABLE IF NOT EXISTS banned(username VARCHAR(20) PRIMARY KEY, ip VARCHAR(20) NOT NULL, ban_reason VARCHAR(8000) NOT NULL)
		self::$instance = $this;
		$this->saveResource("ranks.yml");
		$this->ranks = new Config($this->getDataFolder() . "ranks.yml", Config::YAML);
		new PermissionManager();
		new EventListener($this);
		new CommandManager($this);
		ItemFactory::registerItem(new GoldenHead(), true);

		foreach(["ops.txt", "banned-players.txt", "banned-ips.txt"] as $configs){
			if(file_exists($this->getServer()->getDataPath() . $configs)){
				$this->getLogger()->notice("Deleting $configs...");
				unlink($this->getServer()->getDataPath() . $configs);
			}
		}
	}

	public function onDisable(){
		foreach($this->getServer()->getOnlinePlayers() as $p){
			$p->transfer("play.theshocknetwork.com", 19132);
		}
	}

	public static function getInstance() : NetworkLoader{
		return self::$instance;
	}

	public static function selectPrefix(string $prefix){
		return TF::GRAY . "[" . TF::BOLD . TF::GOLD . $prefix . TF::RESET . TF::GRAY . "] " . TF::RESET . TF::WHITE;
	}
}