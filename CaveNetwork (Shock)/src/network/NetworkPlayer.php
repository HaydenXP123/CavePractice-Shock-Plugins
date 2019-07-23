<?php
declare(strict_types=1);

namespace network;

use pocketmine\{network\mcpe\protocol\LoginPacket, permission\PermissionAttachment, Player};

class NetworkPlayer extends Player{
	public const ANDROID = 1;
	public const IOS = 2;
	public const OSX = 3;
	public const FIRE = 4;
	public const GEARVR = 5;
	public const HOLOLENS = 6;
	public const WIN10 = 7;
	public const WIN32 = 8;
	public const DEDICATED = 9;
	public const TVOS = 10;
	public const ORBIS = 11;
	public const NX = 12;
	public const XBOX = 13;
	public const UNKNOWN = -1;
	/** @var int */
	protected $os = self::UNKNOWN;
	/** @var int */
	protected $inputMode;
	/** @var PermissionAttachment */
	private $attachment = [];

	public function getRank(){
		return NetworkLoader::getInstance()->playerCache[$this->getName()]["rank"];
	}

	public function setChatFormat(string $msg){
		$rank = $this->getRank();
		$format = NetworkLoader::getInstance()->ranks->get($rank)["chat"];
		$format = str_replace("{player}", $this->getName(), $format);
		$format = str_replace("{msg}", $msg, $format);

		return $format;
	}

	public function handleLogin(LoginPacket $packet) : bool{
		if(!parent::handleLogin($packet)) return false;

		$this->os = $packet->clientData["DeviceOS"];
		$this->inputMode = $packet->clientData["CurrentInputMode"];

		return true;
	}

	public function getDeviceOS(){
		return $this->os;
	}

	private function attachPermissions(){
		foreach(NetworkLoader::getInstance()->ranks->get($this->getRank())["permissions"] as $permissions){
			$this->attachment[] = $this->addAttachment(NetworkLoader::getInstance(), $permissions, true);
		}
		$this->sendCommandData();
	}

	public function updatePermissions(){
		$this->attachPermissions();
		foreach($this->attachment as $attachment){
			$this->removeAttachment($attachment);
		}
		$this->attachPermissions();
	}

	public function getDeviceName(){
		switch($this->os){
			case self::ANDROID:
				return "Android";
			case self::IOS:
				return "iOS";
			case self::WIN10:
				return "Win10";
			case self::XBOX:
				return "Xbox";
			default:
				$this->getServer()->getLogger()->notice("Unknown device OS: " . $this->getDeviceOS());
				return "Unknown";
		}
	}

	public function getInputName(){
		switch($this->inputMode){
			case 1:
				return "Keyboard";
			case 2:
				return "Touch";
			case 3:
				return "Controller";
			default:
				return "Unknown";
		}
	}

	public function getDeviceInput(){
		return $this->inputMode;
	}
}
