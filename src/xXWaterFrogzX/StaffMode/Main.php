<?php
declare(strict_types=1);

namespace xXWaterFrogzX\StaffMode;


use muqsit\invmenu\InvMenuHandler;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use xXWaterFrogzX\StaffMode\commands\StaffModeCommand;


class Main extends PluginBase implements Listener {
    private $sessions = [];

    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getCommandMap()->registerAll("staffmode", [
            new StaffModeCommand($this)
        ]);
        new StaffModeListener($this);
        if(!InvMenuHandler::isRegistered()){
            InvMenuHandler::register($this);
        }

    }
    public function onPlayerJoin(PlayerJoinEvent $event) {
        $this->sessions[$event->getPlayer()->getId()] = new PlayerSession($this, $event->getPlayer());
    }
    public function getSession(Player $player){
        return isset($this->sessions[$id = $player->getId()]) ? $this->sessions[$id] : null;
    }

}