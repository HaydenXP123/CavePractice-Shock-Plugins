<?php
declare(strict_types=1);

namespace network\utils;

use pocketmine\level\Level;

final class RegionUtils{
	public static function onChunkGenerated(Level $level, int $chunkX, int $chunkZ, callable $callback){
		if($level->isChunkPopulated($chunkX, $chunkZ)){
			$callback();
			return;
		}
		$level->registerChunkLoader(new NetworkChunkLoader($level, $chunkX, $chunkZ, $callback), $chunkX, $chunkZ, true);
	}
}