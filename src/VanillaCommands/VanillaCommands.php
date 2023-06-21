<?php

declare(strict_types=1);

namespace VanillaCommands;

use pocketmine\plugin\PluginBase;
use VanillaCommands\CommandManager;

class VanillaCommands extends PluginBase{
    
    public function onEnable(): void{
        $this->getLogger()->info("§aSuccessfully enabled VanillaCommands!");
        $commandmap = new CommandManager($this);
    }
}
