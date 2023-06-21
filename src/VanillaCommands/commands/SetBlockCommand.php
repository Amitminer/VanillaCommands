<?php

namespace VanillaCommands\commands;

use VanillaCommands\VanillaCommands;
use pocketmine\Server;
use pocketmine\player\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\world\World;
use pocketmine\block\VanillaBlocks;
use pocketmine\math\Vector3;
use pocketmine\block\Block;

class SetBlockCommand extends Command {

    public const Y_MAX = 320;
    public const Y_MIN = -64;

    public function __construct() {
        parent::__construct("setblock", "Place a block at the specified coordinates", "/setblock <x> <y> <z> <blockname>");
        $this->setPermission("vanilla.command.setblock");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!$sender instanceof Player) {
            $sender->sendMessage("§cThis command can only be used in-game.");
            return false;
        }
        if (count($args) < 4) {
            $sender->sendMessage("§bUsage: /setblock <x> <y> <z> <blockname>");
            return false;
        }

        $position = $this->parseCoordinate($sender, $args[0], $args[1], $args[2]);
        $blockName = strtoupper($args[3]);
        $world = $sender->getWorld();

        $this->placeBlock($blockName, $world, $position->getX(), $position->getY(), $position->getZ(), $sender);

        return true;
    }


    private function parseCoordinate(Player $sender, string $xArg, string $yArg, string $zArg): Vector3 {
        $position = $sender->getPosition();

        $x = $this->parseCoordinateValue($xArg, $position->getX());
        $y = $this->parseCoordinateValue($yArg, $position->getY());
        $z = $this->parseCoordinateValue($zArg, $position->getZ());

        return new Vector3($x, $y, $z);
    }

    private function parseCoordinateValue(string $arg, float $defaultValue): float {
        if ($arg === "~") {
            return $defaultValue;
        } elseif (strpos($arg, "~") === 0) {
            $offset = floatval(substr($arg, 1));
            return $defaultValue + $offset;
        } else {
            return floatval($arg);
        }
    }

    public function placeBlock(string $blockName, World $world, float $x, float $y, float $z, Player $sender): void {
        $blockName = str_replace('_BLOCK', '', $blockName);
        $block = $this->getBlockByName($blockName);
        if ($block !== null) {
            if ($y > self::Y_MAX || $y < self::Y_MIN) {
                $sender->sendMessage("§cCannot place blocks outside the valid Y range.");
                return;
            }
            $world->setBlock(new Vector3($x, $y, $z), $block);
            $sender->sendMessage("§aBlock placed at ($x, $y, $z)");
        } else {
            $sender->sendMessage("§cInvalid block name: $blockName");
        }

    }

    public function getBlockByName(string $blockName): ?Block {
        $allBlocks = VanillaBlocks::getAll();
        $blockName = strtoupper($blockName);
        foreach ($allBlocks as $block) {
            if (strtoupper($block->getName()) === $blockName) {
                return $block;
            }
        }
        return null;
    }
}