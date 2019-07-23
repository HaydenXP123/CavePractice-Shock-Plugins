<?php

namespace practice\generators;

use pocketmine\block\BlockIds;
use pocketmine\level\ChunkManager;
use pocketmine\level\generator\Generator;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class ClassicGenerator extends Generator{
	protected $level, $random, $count;

	public function __construct(array $settings = []){
	}

	/**
	 * @param ChunkManager $level
	 * @param Random       $random
	 */
	public function init(ChunkManager $level, Random $random) : void{
		$this->level = $level;
		$this->random = $random;
		$this->count = 0;
	}

	public function generateChunk(int $chunkX, int $chunkZ) : void{
		if($this->level instanceof ChunkManager){
			$chunk = $this->level->getChunk($chunkX, $chunkZ);
			$chunk->setGenerated(true);
			if($chunkX % 20 == 0 && $chunkZ % 20 == 0){

				for($x = 0; $x < 16; ++$x){
					for($z = 0; $z < 16; ++$z){
						if($x == 0 or $z == 0){
							for($y = 99; $y < 110; ++$y){
								$chunk->setBlock($x, $y, $z, BlockIds::INVISIBLE_BEDROCK);
							}
						}else{
							$ground = [BlockIds::GRASS, BlockIds::GRASS, BlockIds::GRASS, BlockIds::GRASS, BlockIds::STONE, BlockIds::COBBLESTONE][mt_rand(0, 5)];
							$chunk->setBlock($x, 99, $z, $ground);
							$chunk->setBlock($x, 98, $z, BlockIds::BEDROCK);
							$chunk->setBlock($x, 110, $z, BlockIds::INVISIBLE_BEDROCK);
						}
					}
				}
				$chunk->setX($chunkX);
				$chunk->setZ($chunkZ);
			}else if($chunkX % 20 == 1 && $chunkZ % 20 == 0){
				for($x = 0; $x < 16; ++$x){
					for($z = 0; $z < 16; ++$z){
						if($x == 15 or $z == 0){
							for($y = 99; $y < 110; ++$y){
								$chunk->setBlock($x, $y, $z, BlockIds::INVISIBLE_BEDROCK);
							}
						}else{
							$ground = [BlockIds::GRASS, BlockIds::GRASS, BlockIds::GRASS, BlockIds::GRASS, BlockIds::STONE, BlockIds::COBBLESTONE][mt_rand(0, 5)];
							$chunk->setBlock($x, 99, $z, $ground);
							$chunk->setBlock($x, 98, $z, BlockIds::BEDROCK);
							$chunk->setBlock($x, 110, $z, BlockIds::INVISIBLE_BEDROCK);
						}
					}
				}
				$chunk->setX($chunkX);
				$chunk->setZ($chunkZ);
			}else if($chunkX % 20 == 0 && $chunkZ % 20 == 1){
				for($x = 0; $x < 16; ++$x){
					for($z = 0; $z < 16; ++$z){
						if($x == 0){
							for($y = 99; $y < 110; ++$y){
								$chunk->setBlock($x, $y, $z, BlockIds::INVISIBLE_BEDROCK);
							}
						}else{
							$ground = [BlockIds::GRASS, BlockIds::GRASS, BlockIds::GRASS, BlockIds::GRASS, BlockIds::STONE, BlockIds::COBBLESTONE][mt_rand(0, 5)];
							$chunk->setBlock($x, 99, $z, $ground);
							$chunk->setBlock($x, 98, $z, BlockIds::BEDROCK);
							$chunk->setBlock($x, 110, $z, BlockIds::INVISIBLE_BEDROCK);
						}
					}
				}
				$chunk->setX($chunkX);
				$chunk->setZ($chunkZ);
			}else if($chunkX % 20 == 1 && $chunkZ % 20 == 1){

				for($x = 0; $x < 16; ++$x){
					for($z = 0; $z < 16; ++$z){
						if($x == 15){
							for($y = 99; $y < 110; ++$y){
								$chunk->setBlock($x, $y, $z, BlockIds::INVISIBLE_BEDROCK);
							}
						}else{
							$ground = [BlockIds::GRASS, BlockIds::GRASS, BlockIds::GRASS, BlockIds::GRASS, BlockIds::STONE, BlockIds::COBBLESTONE][mt_rand(0, 5)];
							$chunk->setBlock($x, 99, $z, $ground);
							$chunk->setBlock($x, 98, $z, BlockIds::BEDROCK);
							$chunk->setBlock($x, 110, $z, BlockIds::INVISIBLE_BEDROCK);
						}
					}
				}
				$chunk->setX($chunkX);
				$chunk->setZ($chunkZ);
			}else if($chunkX % 20 == 1 && $chunkZ % 20 == 2){

				for($x = 0; $x < 16; ++$x){
					for($z = 0; $z < 16; ++$z){
						if($x == 15 or $z == 15){
							for($y = 99; $y < 110; ++$y){
								$chunk->setBlock($x, $y, $z, BlockIds::INVISIBLE_BEDROCK);
							}
						}else{
							$ground = [BlockIds::GRASS, BlockIds::GRASS, BlockIds::GRASS, BlockIds::GRASS, BlockIds::STONE, BlockIds::COBBLESTONE][mt_rand(0, 5)];
							$chunk->setBlock($x, 99, $z, $ground);
							$chunk->setBlock($x, 98, $z, BlockIds::BEDROCK);
							$chunk->setBlock($x, 110, $z, BlockIds::INVISIBLE_BEDROCK);
						}
					}
				}
				$chunk->setX($chunkX);
				$chunk->setZ($chunkZ);
			}else if($chunkX % 20 == 0 && $chunkZ % 20 == 2){

				for($x = 0; $x < 16; ++$x){
					for($z = 0; $z < 16; ++$z){
						if($x == 0 or $z == 15){
							for($y = 99; $y < 110; ++$y){
								$chunk->setBlock($x, $y, $z, BlockIds::INVISIBLE_BEDROCK);
							}
						}else{
							$ground = [BlockIds::GRASS, BlockIds::GRASS, BlockIds::GRASS, BlockIds::GRASS, BlockIds::STONE, BlockIds::COBBLESTONE][mt_rand(0, 5)];
							$chunk->setBlock($x, 99, $z, $ground);
							$chunk->setBlock($x, 98, $z, BlockIds::BEDROCK);
							$chunk->setBlock($x, 110, $z, BlockIds::INVISIBLE_BEDROCK);
						}
					}
				}
				$chunk->setX($chunkX);
				$chunk->setZ($chunkZ);
			}
		}
	}

	/**
	 * @param int $chunkX
	 * @param int $chunkZ
	 */
	public function populateChunk(int $chunkX, int $chunkZ) : void{
		// Nothing.
	}

	/**
	 * @return array
	 */
	public function getSettings() : array{
		return [];
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return "classic";
	}

	/**
	 * @return Vector3
	 */
	public function getSpawn() : Vector3{
		return new Vector3(0, 100, 0);
	}
}