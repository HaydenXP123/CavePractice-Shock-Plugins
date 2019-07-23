<?php

namespace practice;

use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\utils\TextFormat;

class KitManager{

	public static $kits = [
		0 => "NoDebuff",
		1 => "BuildUHC",
		2 => "Classic",
		3 => "SG",
		4 => "Combo",
		5 => "Fist",
		6 => "Archer"
	];

	public static function give(PracticePlayer $player, int $type){
		$inventory = $player->getInventory();
		$armorInventory = $player->getArmorInventory();
		$player->getInventory()->clearAll();
		switch($type){
			case 0:
				$armorInventory->setHelmet(Item::get(ItemIds::DIAMOND_HELMET));
				$armorInventory->setChestplate(Item::get(ItemIds::DIAMOND_CHESTPLATE));
				$armorInventory->setLeggings(Item::get(ItemIds::DIAMOND_LEGGINGS));
				$armorInventory->setBoots(Item::get(ItemIds::DIAMOND_BOOTS));

				$inventory->setItem(0, Item::get(ItemIds::DIAMOND_SWORD, 0, 1));
				$inventory->addItem(Item::get(ItemIds::ENDER_PEARL, 0, 16));
				$inventory->addItem(Item::get(ItemIds::STEAK, 0, 64));

				$inventory->addItem(Item::get(Item::POTION, 14, 2));
				$inventory->addItem(Item::get(Item::SPLASH_POTION, 22, 34));
				break;
			case 1:
				$armorInventory->setHelmet(Item::get(ItemIds::DIAMOND_HELMET));
				$armorInventory->setChestplate(Item::get(ItemIds::DIAMOND_CHESTPLATE));
				$armorInventory->setLeggings(Item::get(ItemIds::DIAMOND_LEGGINGS));
				$armorInventory->setBoots(Item::get(ItemIds::DIAMOND_BOOTS));

				$inventory->setItem(0, Item::get(ItemIds::DIAMOND_SWORD, 0, 1));
				$inventory->addItem(Item::get(ItemIds::BOW, 0, 1));
				$inventory->addItem(Item::get(ItemIds::GOLDEN_APPLE, 0, 6));
				$inventory->addItem(Item::get(ItemIds::GOLDEN_APPLE, 1, 3)->setCustomName(TextFormat::RESET . TextFormat::GOLD . "Golden Head"));
				$inventory->addItem(Item::get(ItemIds::STEAK, 0, 64));
				$inventory->addItem(Item::get(ItemIds::BUCKET, 8, 1));
				$inventory->addItem(Item::get(ItemIds::BUCKET, 10, 1));
				$inventory->addItem(Item::get(ItemIds::COBBLESTONE, 0, 64));
				$inventory->addItem(Item::get(ItemIds::DIAMOND_PICKAXE, 0, 1));
				$inventory->addItem(Item::get(ItemIds::ARROW, 0, 32));
				break;
			case 2:
				$armorInventory->setHelmet(Item::get(ItemIds::DIAMOND_HELMET));
				$armorInventory->setChestplate(Item::get(ItemIds::DIAMOND_CHESTPLATE));
				$armorInventory->setLeggings(Item::get(ItemIds::DIAMOND_LEGGINGS));
				$armorInventory->setBoots(Item::get(ItemIds::DIAMOND_BOOTS));

				$inventory->setItem(0, Item::get(ItemIds::DIAMOND_SWORD, 0, 1));
				$inventory->addItem(Item::get(ItemIds::BOW, 0, 1));
				$inventory->addItem(Item::get(ItemIds::GOLDEN_APPLE, 0, 9));
				$inventory->addItem(Item::get(ItemIds::ARROW, 0, 16));
				$inventory->addItem(Item::get(ItemIds::STEAK, 0, 64));
				break;
			case 3:
				$armorInventory->setHelmet(Item::get(ItemIds::GOLD_HELMET));
				$armorInventory->setChestplate(Item::get(ItemIds::IRON_CHESTPLATE));
				$armorInventory->setLeggings(Item::get(ItemIds::CHAIN_LEGGINGS));
				$armorInventory->setBoots(Item::get(ItemIds::IRON_BOOTS));

				$inventory->setItem(0, Item::get(ItemIds::STONE_SWORD, 0, 1));
				$inventory->addItem(Item::get(ItemIds::BOW, 0, 1));
				$inventory->addItem(Item::get(ItemIds::GOLDEN_APPLE, 0, 1));
				$inventory->addItem(Item::get(ItemIds::GOLDEN_CARROT, 0, 1));
				$inventory->addItem(Item::get(ItemIds::PUMPKIN_PIE, 0, 2));
				$inventory->addItem(Item::get(ItemIds::MELON, 0, 2));
				$inventory->addItem(Item::get(ItemIds::BREAD, 0, 1));
				$inventory->addItem(Item::get(ItemIds::FLINT_STEEL, 99, 1));
				$inventory->addItem(Item::get(ItemIds::ARROW, 0, 8));
				break;
			case 4:
				$helmet = Item::get(ItemIds::DIAMOND_HELMET);
				$helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 4));
				$helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
				$chest = Item::get(ItemIds::DIAMOND_CHESTPLATE);
				$chest->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 4));
				$chest->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
				$legs = Item::get(ItemIds::DIAMOND_LEGGINGS);
				$legs->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 4));
				$legs->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
				$boots = Item::get(ItemIds::DIAMOND_BOOTS);
				$boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 4));
				$boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));

				$armorInventory->setHelmet($helmet);
				$armorInventory->setChestplate($chest);
				$armorInventory->setLeggings($legs);
				$armorInventory->setBoots($boots);

				$sword = Item::get(ItemIds::DIAMOND_SWORD);
				$sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::SHARPNESS), 5));
				$sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));

				$inventory->setItem(0, $sword);
				$inventory->addItem(Item::get(Item::STEAK, 0, 64));
				$inventory->addItem(Item::get(Item::ENCHANTED_GOLDEN_APPLE, 0, 64));
				$inventory->addItem(Item::get(Item::POTION, 14, 5));
				$inventory->addItem(Item::get(Item::POTION, 31, 5));
				break;
			case 5:
				$inventory->addItem(Item::get(Item::STEAK, 0, 64));
				break;
			case 6:
				$armorInventory->setHelmet(Item::get(ItemIds::IRON_HELMET));
				$armorInventory->setChestplate(Item::get(ItemIds::IRON_CHESTPLATE));
				$armorInventory->setLeggings(Item::get(ItemIds::IRON_LEGGINGS));
				$armorInventory->setBoots(Item::get(ItemIds::IRON_BOOTS));

				$bow = Item::get(ItemIds::BOW);
				$bow->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::INFINITY), 1));
				$bow->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));

				$inventory->setItem(0, $bow);
				$inventory->addItem(Item::get(Item::STEAK, 0, 64));
				$inventory->addItem(Item::get(Item::ARROW, 0, 1));
				break;
		}
	}

	public static function getKitName(int $id){
		if(isset(self::$kits[$id])){
			return self::$kits[$id];
		}else{
			return "";
		}
	}
}