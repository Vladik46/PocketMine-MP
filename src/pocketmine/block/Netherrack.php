<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____  
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \ 
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/ 
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_| 
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 * 
 *
*/

namespace pocketmine\block;

use pocketmine\item\Item;

class Netherrack extends Solid{
	public function __construct(){
		parent::__construct(self::NETHERRACK, 0, "Netherrack");
		$this->hardness = 2;
	}

	public function getBreakTime(Item $item){

		switch($item->isPickaxe()){
			case 5:
				return 0.1;
			case 4:
				return 0.1;
			case 3:
				return 0.15;
			case 2:
				return 0.05;
			case 1:
				return 0.3;
			default:
				return 2;
		}
	}

	public function getDrops(Item $item){
		if($item->isPickaxe() >= 1){
			return array(
				array(Item::NETHERRACK, 0, 1),
			);
		}else{
			return array();
		}
	}
}