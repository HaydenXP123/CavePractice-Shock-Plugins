<?php
declare(strict_types=1);

namespace network\managers;

use pocketmine\permission\DefaultPermissions;
use pocketmine\permission\Permission;

class PermissionManager{

	public function __construct(){
		$this->init();
	}

	private function init(){
		$parent = DefaultPermissions::registerPermission(new Permission("shock"));

		//Network
		DefaultPermissions::registerPermission(new Permission("shock.command.setrank", "", Permission::DEFAULT_OP), $parent);
		DefaultPermissions::registerPermission(new Permission("shock.command.world", "", Permission::DEFAULT_OP), $parent);
		DefaultPermissions::registerPermission(new Permission("shock.command.ban", "", Permission::DEFAULT_OP), $parent);
		DefaultPermissions::registerPermission(new Permission("shock.command.pardon", "", Permission::DEFAULT_OP), $parent);
		//UHC
		DefaultPermissions::registerPermission(new Permission("shock.command.freeze", "", Permission::DEFAULT_OP), $parent);
		DefaultPermissions::registerPermission(new Permission("shock.command.globalmute", "", Permission::DEFAULT_OP), $parent);
		DefaultPermissions::registerPermission(new Permission("shock.staff.report", "", Permission::DEFAULT_OP), $parent);
		DefaultPermissions::registerPermission(new Permission("shock.command.scenarios", "", Permission::DEFAULT_OP), $parent);
		DefaultPermissions::registerPermission(new Permission("shock.command.tpall", "", Permission::DEFAULT_OP), $parent);
		DefaultPermissions::registerPermission(new Permission("shock.command.uhc", "", Permission::DEFAULT_OP), $parent);
		//Bypass
		DefaultPermissions::registerPermission(new Permission("shock.bypass", "", Permission::DEFAULT_OP), $parent);

		$parent->recalculatePermissibles();
	}
}