<?php

namespace SharkMCPE\SwordMMORPG\Commands;

use pocketmine\item\Item;
use SharkMCPE\SwordMMORPG\SwordLoader;
use pocketmine\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\plugin\Plugin;

class SwordCommand extends Command implements PluginIdentifiableCommand
{

    private $plugin;

    public function __construct(SwordLoader $plugin)
    {
        parent::__construct("sword", "เปิดเมนูการสร้างดาบ");
        $this->plugin = $plugin;
        $this->setPermission("SwordMMORPG.command");
    }

    public function getPlugin(): Plugin
    {
        return $this->plugin;
    }
    public function execute(CommandSender $sender, $commandLabel, array $args)
    {
        if($sender instanceof Player){
            if(empty($args)){
                $this->plugin->getFormListener()->FormSword($sender);
                return true;
            }
            $sub = array_shift($args);
            switch($sub) {
                case "give":
                    $data = $this->plugin->getData()->getAll();
                    if (count($args) < 2) {
                        $sender->sendMessage("§cโปรดพิมพ์: /sword give (ชื่อดาบ) (ชื่อผู้เล่น)");
                        return true;
                    }
                    $swordname = array_shift($args);
                    if (!isset($data["sword"][$swordname])) {
                        $sender->sendMessage("§f[§bS§aw§4o§5r§6d§f] §cไม่พบดาบ " . $swordname);
                        return true;
                    }
                    $player = array_shift($args);
                    $player = $this->plugin->getServer()->getPlayer($player);
                    if (!$player instanceof Player) {
                        $sender->sendMessage("§f[§bS§aw§4o§5r§6d§f] §cไม่พบชื่อผู้เล่นคนนี้");
                        return true;
                    }
                    $sword = Item::get(276, 0, 1);
                    $sword->setCustomName($swordname);
                    $data = $this->plugin->getData()->getAll();
                    $lore = [
                        "",
                        "§fดาเมจ §e".$data["sword"][$swordname]["damage"],
                        "§fอัตราคริติคอล §e".$data["sword"][$swordname]["cri"],
                        "§fความเสียหายคริติคอล §e".$data["sword"][$swordname]["cridamage"],
                        "§fความเร็วในการโจมตี §e".$data["sword"][$swordname]["atkspeed"],
                        "§fการติดสโล §e".$data["sword"][$swordname]["slow"],
                        "§fเวลาการติดสโล §e".$data["sword"][$swordname]["slowtime"],
                        "§fการติดไฟ §e".$data["sword"][$swordname]["fire"],
                        "§fการกระเด็น §e".$data["sword"][$swordname]["knockback"]
                    ];
                    $sword->setLore($lore);
                    $player->getInventory()->addItem($sword);
                    $sender->sendMessage("§f[§bS§aw§4o§5r§6d§f] §aคุณได้รับดาบ" . $swordname . " เรียบร้อยแล้ว");
                    break;
                case "lists":
                    $data = $this->plugin->getData()->getAll();
                    if (count($data) == 0) {
                        $sender->sendMessage("§f[§bS§aw§4o§5r§6d§f] §cไม่มีรายชื่อดาบในฐานข้อมูล");
                        return true;
                    }
                    $sender->sendMessage("§f[§bS§aw§4o§5r§6d§f] §fรายชื่อดาบ " . implode(", ", array_keys($data["sword"])));
                    break;
                default:
                    $sender->sendMessage("§f[§bS§aw§4o§5r§6d§f]§aโปรดใช้:\n§f- /sword เพื่อเปิด UI Sword\n§f- /sword give (ชื่อดาบ) (ชื่อผู้เล่น)\n§f- /sword lists เพื่อดูรายชื่อดาบ");
                    break;
            }
        }
    }
}