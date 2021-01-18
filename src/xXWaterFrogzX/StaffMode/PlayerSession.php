<?php
declare(strict_types=1);

namespace xXWaterFrogzX\StaffMode;


use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class PlayerSession {

    private $main;
    private $player;
    private $staff = false;
    private $orgInv = [];
    private $xCoordinate;
    private $yCoordinate;
    private $zCoordinate;
    private $helmet;
    private $chestplate;
    private $leggings;
    private $boots;
    public $isUsingCommand = false;
    public $freeze = false;
    public $vanish = true;
    private $gamemode;


    public function __construct(Main $main, Player $player) {
        $this->player = $player;
        $this->main = $main;
    }
    public function getPlayer() : Player {
        return $this->player;
    }
    public function inStaffMode() : bool {
        return $this->staff;
    }
    public function setVanish(bool $vanish) {
        $this->vanish = $vanish;
    }
    public function inVanish() : bool {
        return $this->vanish;
    }
    public function setOrgInv() : array {
        return $this->orgInv;
    }
    public function getOrgInv(array $orgInv) {
        $this->orgInv = $orgInv;
    }
    public function setMode(bool $staff) {
        $this->staff = $staff;
    }
    public function getXCoordinate(int $xCoordinate) {
        $this->xCoordinate = $xCoordinate;
    }
    public function setXCoordinate() : int {
        return $this->xCoordinate;
    }
    public function getYCoordinate(int $yCoordinate) {
        $this->yCoordinate = $yCoordinate;
    }
    public function setYCoordinate() : int {
        return $this->yCoordinate;
    }
    public function getZCoordinate(int $zCoordinate) {
        $this->zCoordinate = $zCoordinate;
    }
    public function setZCoordinate() : int {
        return $this->zCoordinate;
    }
    public function setStaffMode() : void {
        $this->gamemode = $this->getPlayer()->getGamemode();
        $this->setVanish(true);
        $this->getPlayer()->removeAllEffects();
        $this->getPlayer()->addEffect(new EffectInstance(Effect::getEffect(Effect::NIGHT_VISION)));
        $this->getXCoordinate($this->getPlayer()->getFloorX());
        $this->getYCoordinate($this->getPlayer()->getFloorY());
        $this->getZCoordinate($this->getPlayer()->getFloorZ());
        $this->setMode(true);
        $this->getOrgInv($this->getPlayer()->getInventory()->getContents());
        $this->getPlayer()->getInventory()->clearAll();
        $this->getPlayer()->setGamemode(3);
        $this->helmet = $this->getPlayer()->getArmorInventory()->getHelmet();
        $this->chestplate = $this->getPlayer()->getArmorInventory()->getChestplate();
        $this->leggings = $this->getPlayer()->getArmorInventory()->getLeggings();
        $this->boots = $this->getPlayer()->getArmorInventory()->getBoots();
        $this->getPlayer()->getArmorInventory()->clearAll();

        $limedye = Item::get(ItemIds::DYE, 10, 1)->setCustomName(TextFormat::RESET . TextFormat::YELLOW . "Disable Vanish")->setLore([TextFormat::RESET . TextFormat::GRAY .  "Disable Vanish"]);
        $pearl = Item::get(ItemIds::ENDER_PEARL, 0, 1)->setCustomName(TextFormat::RESET . TextFormat::AQUA . "Random Teleport")->setLore([TextFormat::RESET . TextFormat::GRAY . "Randomly teleport to an online player"]);
        $book = Item::get(ItemIds::BOOK, 0, 1)->setCustomName(TextFormat::RESET . TextFormat::RED . "Inventory Inspect")->setLore([TextFormat::RESET . TextFormat::GRAY . "Inspect a player's inventory"]);
        $compass = Item::get(ItemIds::COMPASS, 0, 1)->setCustomName(TextFormat::RESET . TextFormat::YELLOW . "Compass")->setLore([TextFormat::RESET . TextFormat::GRAY . "Speeeeeeeeeed!"]);
        $ice = Item::get(ItemIds::ICE, 0, 1)->setCustomName(TextFormat::RESET . TextFormat::AQUA . "Freeze")->setLore([TextFormat::RESET . TextFormat::GRAY . "Freeze a player"]);
        $this->getPlayer()->getInventory()->setItem(0, $compass);
        $this->getPlayer()->getInventory()->setItem(1, $book);
        $this->getPlayer()->getInventory()->setItem(2, $ice);
        $this->getPlayer()->getInventory()->setItem(3, $pearl);
        $this->getPlayer()->getInventory()->setItem(8, $limedye);

        $this->getPlayer()->sendMessage(TextFormat::GRAY . "(" . TextFormat::RED . "!" . TextFormat::GRAY . ")" . TextFormat::YELLOW . " Staff mode has been " . TextFormat::GREEN . "enabled");

    }
    public function removeStaffMode() : void {
        $this->setVanish(false);
        $this->setMode(false);
        $this->getPlayer()->setGamemode($this->gamemode);
        $this->getPlayer()->getInventory()->setContents($this->setOrgInv());
        $this->getPlayer()->teleport(new Vector3($this->setXCoordinate(),$this->setYCoordinate(),$this->setZCoordinate()));
        $this->getPlayer()->sendMessage(TextFormat::GRAY . "(" . TextFormat::RED . "!" . TextFormat::GRAY . ")" . TextFormat::YELLOW . " Staff mode has been " . TextFormat::RED . "disabled");
        $this->getPlayer()->getArmorInventory()->setHelmet($this->helmet);
        $this->getPlayer()->getArmorInventory()->setChestplate($this->chestplate);
        $this->getPlayer()->getArmorInventory()->setLeggings($this->leggings);
        $this->getPlayer()->getArmorInventory()->setBoots($this->boots);
        $this->getPlayer()->removeAllEffects();

    }
    public function setFreeze(bool $freeze) {
        $this->freeze = $freeze;
    }
    public function isFrozen() : bool {
        return $this->freeze;
    }
}