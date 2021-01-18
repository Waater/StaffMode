<?php
declare(strict_types=1);

namespace xXWaterFrogzX\StaffMode;


use muqsit\invmenu\InvMenu;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\inventory\InventoryPickupItemEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\Player;
use pocketmine\utils\TextFormat;


class StaffModeListener implements Listener {
    private $main;


    public function __construct(Main $main) {
        $main->getServer()->getPluginManager()->registerEvents($this, $main);
        $this->setMain($main);
    }
    public function setMain(Main $main) {
        $this->main = $main;
    }
    public function getMain() : Main {
        return $this->main;
    }
    public function onLogOut(PlayerQuitEvent $event) : void{
        $player = $event->getPlayer();
        $ses = $this->getMain()->getSession($player);
        if ($ses->inStaffMode()) {
            $ses->removeStaffMode();
        }
    }
    public function onDrop(PlayerDropItemEvent $event) : void {
        $player = $event->getPlayer();
        $ses = $this->getMain()->getSession($player);
        if ($ses->inStaffMode()) {
            $event->setCancelled(true);
        }
    }
    public function onItemTransaction(InventoryTransactionEvent $event) {
        $player = $event->getTransaction()->getSource();
        $ses = $this->getMain()->getSession($player);
        if ($ses->inStaffMode() == true) {
            $event->setCancelled(true);
        }
    }
    public function pickUpItem(InventoryPickupItemEvent $event) {
        $player = $event->getInventory()->getHolder();
        $ses = $this->getMain()->getSession($player);
        if ($ses->inStaffMode() == true) {
            $event->setCancelled(true);
        }
    }
    public function onBlockBreak(BlockBreakEvent $event) {
        $player = $event->getPlayer();
        $ses = $this->getMain()->getSession($player);
        if ($ses->inStaffMode() == true) {
            $event->setCancelled(true);
        }
    }
    public function onInteract(PlayerInteractEvent $event) {
        $player = $event->getPlayer();
        $ses = $this->getMain()->getSession($player);
        if ($ses->inStaffMode()) {
            if ($ses->inStaffMode() == true) {
                if ($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_BLOCK && $event->getBlock()->getId() == Item::CHEST) {
                    $event->setCancelled(true);
                }
                if ($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_BLOCK && $event->getBlock()->getId() == Item::FENCE_GATE) {
                    $event->setCancelled(true);
                }
                if ($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_BLOCK && $event->getBlock()->getId() == Item::ACACIA_FENCE_GATE) {
                    $event->setCancelled(true);
                }
                if ($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_BLOCK && $event->getBlock()->getId() == Item::BIRCH_FENCE_GATE) {
                    $event->setCancelled(true);
                }
                if ($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_BLOCK && $event->getBlock()->getId() == Item::DARK_OAK_FENCE_GATE) {
                    $event->setCancelled(true);
                }
                if ($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_BLOCK && $event->getBlock()->getId() == Item::JUNGLE_FENCE_GATE) {
                    $event->setCancelled(true);
                }
                if ($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_BLOCK && $event->getBlock()->getId() == Item::SPRUCE_FENCE_GATE) {
                    $event->setCancelled(true);
                }
            }
            $player->setAllowMovementCheats(false);
            if ($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR && $event->getItem()->getId() == Item::COMPASS) {
                if ($player->getDirection() == 2) {
                    $player->setMotion($player->getMotion()->add(-7, 0));
                }
                if ($player->getDirection() == 0) {
                    $player->setMotion($player->getMotion()->add(7, 0));
                }
                if ($player->getDirection() == 1) {
                    $player->setMotion($player->getMotion()->add(0, 0, 7));
                }
                if ($player->getDirection() == 3) {
                    $player->setMotion($player->getMotion()->add(0, 0, -7));
                }
            }
            if ($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR && $event->getItem()->getId() == Item::ENDER_PEARL) {
                $onlinePlayers = [];
                foreach ($player->getServer()->getOnlinePlayers() as $onlinePlayer) {
                    $onlinePlayers[] = $onlinePlayer;
                }
                $number = count($onlinePlayers);
                $rand = $onlinePlayers[mt_rand(0, $number - 1)];
                $playerName = $rand->getPlayer()->getName();
                $player->teleport($rand);
                $player->sendMessage(TextFormat::GRAY . "You have been randomly teleported to " . TextFormat::GREEN . $playerName);
            }
            if ($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR && $event->getItem()->getId() == Item::DYE) {
                if ($ses->inVanish() == true) {
                    $ses->setVanish(false);
                    $player->setGamemode(1);
                    $graydye = Item::get(ItemIds::DYE, 8, 1)->setCustomName(TextFormat::RESET . TextFormat::YELLOW . "Enable Vanish")->setLore([TextFormat::RESET . TextFormat::GRAY .  "Enable vanish"]);
                    $player->getInventory()->setItem(8, $graydye);
                    $player->sendMessage(TextFormat::GRAY . "Vanish has been " . TextFormat::RED . "disabled!");
                } else {
                    $ses->setVanish(true);
                    $limedye = Item::get(ItemIds::DYE, 10, 1)->setCustomName(TextFormat::RESET . TextFormat::YELLOW . "Disable Vanish")->setLore([TextFormat::RESET . TextFormat::GRAY .  "Disable Vanish"]);
                    $player->getInventory()->setItem(8, $limedye);
                    $player->setGamemode(3);
                    $player->sendMessage(TextFormat::GRAY . "Vanish has been " . TextFormat::GREEN . "enabled!");
                }
            }

        }
    }
    public function onEntityDamage(EntityDamageByEntityEvent $event) {
        $damager = $event->getDamager();
        $entity = $event->getEntity();
        if ($entity instanceof Player) {
            $sesE = $this->getMain()->getSession($entity);
            if ($sesE->isFrozen()) {
                $event->setCancelled(true);
            }
        }
        if ($damager instanceof Player) {
            $ses = $this->getMain()->getSession($damager);
            if ($ses->isFrozen()) {
                $event->setCancelled(true);
            }
        }
        if ($damager instanceof Player && $entity instanceof Player) {
            $ses = $this->getMain()->getSession($damager);
            if ($ses->inStaffMode() == true) {
                $event->setCancelled(true);
            }
            if ($damager->getInventory()->getItemInHand()->getId() == Item::BOOK) {
                if ($ses->inStaffMode()) {
                        $helmet = $entity->getArmorInventory()->getHelmet();
                        $chestplate = $entity->getArmorInventory()->getChestplate();
                        $leggings = $entity->getArmorInventory()->getLeggings();
                        $boots = $entity->getArmorInventory()->getBoots();
                        $menu = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST);
                        $entityInventory = $entity->getInventory()->getContents();
                        $menu->getInventory()->setContents($entityInventory);
                        $menu->send($damager->getPlayer());
                        $menu->setName($entity->getName() . "'s Inventory");
                        $menu->setListener(InvMenu::readonly());
                        $glass = Item::get(Item::STAINED_GLASS_PANE, 7);
                        $menu->getInventory()->setItem(45, $glass);
                        $menu->getInventory()->setItem(46, $glass);
                        $menu->getInventory()->setItem(47, $helmet);
                        $menu->getInventory()->setItem(48, $chestplate);
                        $menu->getInventory()->setItem(49, $glass);
                        $menu->getInventory()->setItem(50, $leggings);
                        $menu->getInventory()->setItem(51, $boots);
                        $menu->getInventory()->setItem(52, $glass);
                        $menu->getInventory()->setItem(53, $glass);
                }
            }

        }
        if ($damager instanceof Player && $entity instanceof Player) {
            $ses = $this->getMain()->getSession($damager);
            $sesE = $this->getMain()->getSession($entity);
            if ($damager->getInventory()->getItemInHand()->getId() == Item::ICE) {
                if ($ses->inStaffMode()) {
                    if ($sesE->isFrozen()) {
                        $entity->sendMessage(TextFormat::GREEN . TextFormat::BOLD . "UNFROZEN" . "\n" . TextFormat::RESET . TextFormat::GRAY . "You have been unfrozen by " . $damager->getName());
                        $sesE->setFreeze(false);
                        $damager->sendMessage(TextFormat::GRAY . "You have " . TextFormat::GREEN . "unfrozen " . TextFormat::GRAY . $entity->getName());
                    } else {
                        $entity->sendMessage(TextFormat::RED . TextFormat::BOLD . "FROZEN" . "\n" . TextFormat::RESET . TextFormat::GRAY . "You have been frozen by " . $damager->getName());
                        $sesE->setFreeze(true);
                        $damager->sendMessage(TextFormat::GRAY . "You have " . TextFormat::RED . "frozen " . TextFormat::GRAY . $entity->getName());
                    }
                }
            }
        }

    }
    public function onMoveEvent(PlayerMoveEvent $event) {
        $player = $event->getPlayer();
        $ses = $this->getMain()->getSession($player);
        if ($ses->isFrozen() == true) {
            $event->setCancelled(true);
        }
    }

}