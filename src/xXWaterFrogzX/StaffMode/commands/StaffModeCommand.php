<?php
declare(strict_types=1);
namespace xXWaterFrogzX\StaffMode\commands;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use xXWaterFrogzX\StaffMode\Main;



class StaffModeCommand extends PluginCommand  {

    private $main;

    public function __construct(Main $main) {
        parent::__construct("sm", $main);
        $this->setAliases(["staffmode"]);
        $this->setPermission("staffmode.command");
        $this->setDescription("Enable or disable staffmode");
        $this->setMain($main);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : bool {
        if (!$sender instanceof Player) {
            $sender->sendMessage(TextFormat::RED . "Please run this command in game!");
        }
        $ses = $this->getMain()->getSession($sender);
        if ($sender->hasPermission($this->getPermission())) {
            if (!$ses->isUsingCommand) {
                $ses->isUsingCommand = true;
                if ($ses->inStaffMode()) {
                    $ses->removeStaffMode();
                } else {
                    $ses->setStaffMode();
                }
            }
            $ses->isUsingCommand = false;
            return true;
        } else {
            $sender->sendMessage(TextFormat::RED . "You do not have permission to use this command");
        }
        return true;
    }
    public function getMain() : Main {
        return $this->main;
    }
    public function setMain(Main $main) {
        $this->main = $main;
    }
}