<?php


namespace SharkMCPE\SwordMMORPG\Commands;


use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use SharkMCPE\SwordMMORPG\SwordLoader;

class ArmorCommand extends Command implements PluginIdentifiableCommand
{

    private $plugin;

    public function __construct(SwordLoader $plugin)
    {
        parent::__construct("armor", "เปิดเมนูสร้างเกราะ");
        $this->plugin = $plugin;
        $this->setPermission("SwordMMORPG.command");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if($sender instanceof Player){
            if(empty($args)){
                $this->plugin->getFormArmorListener()->FormArmor($sender);
                return true;
            }
            $sub = array_shift($args);
            switch($sub) {
                case "give":
                    $data = $this->plugin->getDataArmor()->getAll();
                    if (count($args) < 2) {
                        $sender->sendMessage("§cโปรดพิมพ์: /armor give (ชื่อเกราะ) (ชื่อผู้เล่น)");
                        return true;
                    }
                    $armorname = array_shift($args);
                    if (!isset($data["armor"][$armorname])) {
                        $sender->sendMessage("§f[§eA§cr§6m§4o§3r§f] §cไม่พบเกราะ " . $armorname);
                        return true;
                    }
                    $player = array_shift($args);
                    $player = $this->plugin->getServer()->getPlayer($player);
                    if (!$player instanceof Player) {
                        $sender->sendMessage("§f[§eA§cr§6m§4o§3r§f] §cไม่พบชื่อผู้เล่นคนนี้");
                        return true;
                    }
                    $armor = Item::get($data["armor"][$armorname]["id"], 0, 1);
                    $armor->setCustomName($armorname);
                    $lore = [
                        "",
                        "§fป้องกันดาเมจ §e" . $data["armor"][$armorname]["defend"],
                        "§fลดความเสียหายคริติคอล §e" . $data["armor"][$armorname]["defendcri"],
                        "§fลดการติดไฟ §e" . $data["armor"][$armorname]["defendfire"],
                        "§fความเร็วในการวิ่งเมื่อใส่เกราะ §e" . $data["armor"][$armorname]["speed"],
                        "§fป้องกันการกระเด็น §e" . $data["armor"][$armorname]["defendknock"]
                    ];
                    $armor->setLore($lore);
                    $player->getInventory()->addItem($armor);
                    $sender->sendMessage("§f[§eA§cr§6m§4o§3r§f] §aคุณได้รับเกราะ " . $armorname . " เรียบร้อยแล้ว");
                    break;
                case "lists":
                    $data = $this->plugin->getDataArmor()->getAll();
                    if(count($data) == 0){
                        $sender->sendMessage("§f[§eA§cr§6m§4o§3r§f] §cไม่มีรายชื่อเกราะในฐานข้อมูล");
                        return true;
                    }
                    $sender->sendMessage("§f[§eA§cr§6m§4o§3r§f] §fรายชื่อเกราะ ".implode(", ", array_keys($data["armor"])));
                    break;
                default:
                    $sender->sendMessage("§f[§eA§cr§6m§4o§3r§f] §aโปรดใช้:\n§f- /armor เพื่อเปิดUI Armor\n§f- /armor give (ชื่อเกราะ) (ชื่อผู้เล่น)\n§f- /armor lists เพื่อดูรายชื่อเกราะ");
                    break;
            }
        }
    }

    public function getPlugin(): Plugin
    {
        return $this->plugin;
    }
}