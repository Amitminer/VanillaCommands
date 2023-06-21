<?php

declare(strict_types = 1);

namespace VanillaCommands;

use pocketmine\plugin\PluginBase;
use VanillaCommands\VanillaCommands;
use VanillaCommands\commands\{
    SetBlockCommand
};

class CommandManager {
    private VanillaCommands $plugin;

    public function __construct(VanillaCommands $plugin) {
        $this->plugin = $plugin;
        $this->registercommands();
    }
    private function registercommands(): void {
        $commandMap = $this->plugin->getServer()->getCommandMap();
        #setblock
        $commandMap->register("setblock", new SetBlockCommand());
    }
}