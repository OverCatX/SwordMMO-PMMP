<?php


namespace SharkMCPE\SwordMMORPG\FormListener;

use pocketmine\item\Item;
use pocketmine\Player;
use SharkMCPE\SwordMMORPG\libs\xenialdan\customui\elements\Button;
use SharkMCPE\SwordMMORPG\libs\xenialdan\customui\elements\Input;
use SharkMCPE\SwordMMORPG\libs\xenialdan\customui\elements\Label;
use SharkMCPE\SwordMMORPG\libs\xenialdan\customui\windows\CustomForm;
use SharkMCPE\SwordMMORPG\libs\xenialdan\customui\windows\ModalForm;
use SharkMCPE\SwordMMORPG\libs\xenialdan\customui\windows\SimpleForm;
use SharkMCPE\SwordMMORPG\SwordLoader;

class FormArmor
{

    private $plugin;

    public function __construct(SwordLoader $plugin){
        $this->plugin = $plugin;
    }
    public function getPlugin(): SwordLoader{
        return $this->plugin;
    }

    public function FormArmor(Player $player, string $content = ""): void{
        $array = [];
        if($this->getPlugin()->getCountArmor() !== 0){
            foreach($this->getPlugin()->getArmor() as $armorname){
                $array[] = $armorname;
            }
        }
        $form = new SimpleForm("Armor", $content);
        $form->addButton(new Button("Create Armor\nสร้างเกราะต่างๆ"));
        for($i = 0; $i < count($array); $i++){
            $form->addButton(new Button($array[$i]));
        }
        $form->setCallable(function (Player $player, $data) use ($array){
            if(!($data === null)){
                switch($data){
                    case ("Create Armor\nสร้างเกราะต่างๆ"):
                        $this->CreateArmor($player);
                        break;
                    default:
                        $this->EditArmor($player, $data);
                        break;
                }
            }
        });
        $form->setCallableClose(function (Player $player){
        });
        $player->sendForm($form);
    }

    public function CreateArmor(Player $player, string $content = "หากไม่เอาพลังใดๆ โปรดอย่าเว้นว่างให้ใส่ 0 แทน"): void{
        $form = new CustomForm("CreateArmor");
        $form->addElement(new Label($content));
        $form->addElement(new Input("ชื่อเกราะ", "เกราะอิอิอิ"));
        $form->addElement(new Input("เลขไอดีของเกราะ", "301"));
        $form->addElement(new Input("ป้องกันดาเมจของดาบ[หากไม่เอาให้ใส่ 0]", "ป้องกันดาเมจของดาบ"));
        $form->addElement(new Input("ลดความเสียหายคริติคอล[หากไม่เอาให้ใส่ 0]", "ลดความเสียหายคริติคอล"));
        $form->addElement(new Input("ป้องการอัตราการติดไฟ[หากไม่เอาให้ใส่ 0]", "ป้องกันการติดไฟ"));
        $form->addElement(new Input("ป้องกันการกระเด็น[หากไม่เอาให้ใส่ 0]", "ป้องกันการกระเด็น"));
        $form->setCallable(function (Player $player, $data) {
            if ($data == null) {
                return;
            }
            $name = explode(" ", $data[1]);
            if ($name[0] == null) {
                $text = "§f[§eA§cr§6m§4o§3r§f] §cโปรดใส่ชื่อเกราะ";
                $this->CreateArmor($player, $text);
                return;
            }
            $name = $name[0];
            if ($this->getPlugin()->Armor($name)) {
                $text = "§f[§eA§cr§6m§4o§3r§f] §cคุณมีชื่อเกราะี้อยู่แล้ว";
                $this->CreateArmor($player, $text);
                return;
            }
            $idd = explode(" ", $data[2]);
            $ddamage = explode(" ", $data[3]);
            $dcri = explode(" ", $data[4]);
            $fireprotect = explode(" ", $data[5]);
            $dknock = explode(" ", $data[6]);
            if ($idd[0] == null) {
                $text = "§f[§eA§cr§6m§4o§3r§f] §cกรุณาอย่าเว้นว่าง §f(§cไอดีเกราะ§f)";
                $this->CreateArmor($player, $text);
                return;
            }
            if (!is_numeric($idd[0])) {
                $text = "§f[§eA§cr§6m§4o§3r§f] §cโปรดกรอกเป็นตัวเลข §f(§cไอดีเกราะ§f)";
                $this->CreateArmor($player, $text);
                return;
            }
            if ($ddamage[0] == null) {
                $text = "§f[§eA§cr§6m§4o§3r§f] §cกรุณาอย่าเว้นว่าง §f(§cป้องกันดาเมจของดาบ§f)";
                $this->CreateArmor($player, $text);
                return;
            }
            if (!is_numeric($ddamage[0])) {
                $text = "§f[§eA§cr§6m§4o§3r§f] §cโปรดกรอกเป็นตัวเลข §f(§cป้องกันดาเมจของดาบ§f)";
                $this->CreateArmor($player, $text);
                return;
            }
            if ($dcri[0] == null) {
                $text = "§f[§eA§cr§6m§4o§3r§f] §cกรุณาอย่าเว้นว่าง §f(§cลดความเสียหายคริติคอล§f)";
                $this->CreateArmor($player, $text);
                return;
            }
            if (!is_numeric($dcri[0])) {
                $text = "§f[§eA§cr§6m§4o§3r§f] §cโปรดกรอกเป็นตัวเลข §f(§cลดความเสียหายคริติคอล§f)";
                $this->CreateArmor($player, $text);
                return;
            }
            if ($fireprotect[0] == null) {
                $text = "§f[§eA§cr§6m§4o§3r§f] §cกรุณาอย่าเว้นว่าง §f(§cลดความเสียหายคริติคอล§f)";
                $this->CreateArmor($player, $text);
                return;
            }
            if (!is_numeric($fireprotect[0])) {
                $text = "§f[§eA§cr§6m§4o§3r§f] §cโปรดกรอกเป็นตัวเลข §f(§cลดความเสียหายคริติคอล§f)";
                $this->CreateArmor($player, $text);
                return;
            }
            if ($dknock[0] == null) {
                $text = "§f[§eA§cr§6m§4o§3r§f] §cกรุณาอย่าเว้นว่าง §f(§cป้องกันการกระเด็น§f)";
                $this->CreateArmor($player, $text);
                return;
            }
            if (!is_numeric($dknock[0])) {
                $text = "§f[§eA§cr§6m§4o§3r§f] §cโปรดกรอกเป็นตัวเลข §f(§cป้องกันการกระเด็น§f)";
                $this->CreateArmor($player, $text);
                return;
            }
            $id = (int)$idd[0];
            $defend = (int)$ddamage[0];
            $defendcri = (int)$dcri[0];
            $defendfire = (int)$fireprotect[0];
            $defendknock = (float)$dknock[0];
            $this->getPlugin()->createArmor($name, $id, $defend, $defendcri, $defendfire, $defendknock);
            $player->sendMessage("§f[§eA§cr§6m§4o§3r§f] §aคุณได้สร้างเกราะ " . $name . " §aเรียบร้อยแล้ว");
        });
        $form->setCallableClose(function (Player $player) {
        });
        $player->sendForm($form);
    }
    public function EditArmor(Player $player, string $armorname): void
    {
        $form = new SimpleForm($armorname, "§bชื่อเกราะ§f: $armorname\n§aคุณสามารถแก้ไขเกราะนี้ได้เลย");
        $array = ["EditArmor\nแก้ไขเกราะ" => 0, "DeleteArmor\nลบเกราะ" => 1, "AddArmor\nเสกเกราะ" => 2];
        foreach ($array as $button => $value) {
            $form->addButton(new Button($button));
        }
        $form->setCallable(function ($player, $data) use ($armorname, $array) {
            if (!($data === null)) {
                switch ($array[$data]) {
                    case 0:
                        $this->EditArmorr($player, $armorname);
                        break;
                    case 1:
                        $this->Remove($player, $armorname);
                        break;
                    case 2:
                        $data = $this->plugin->getDataArmor()->getAll();
                        $armor = Item::get($data["armor"][$armorname]["id"], 0, 1);
                        $armor->setCustomName($armorname);
                        $lore = [
                            "",
                            "§fป้องกันดาเมจ §e".$data["armor"][$armorname]["defend"],
                            "§fลดความเสียหายคริติคอล §e".$data["armor"][$armorname]["defendcri"],
                            "§fลดการติดไฟ §e".$data["armor"][$armorname]["defendfire"],
                            "§fป้องกันการกระเด็น §e".$data["armor"][$armorname]["defendknock"]
                        ];
                        $armor->setLore($lore);
                        $player->getInventory()->addItem($armor);
                        $player->sendMessage("§f[§eA§cr§6m§4o§3r§f] §aคุณได้รับเกราะเรียบร้อยแล้ว");
                         break;
                }
            }
        });
        $form->setCallableClose(function (Player $player) {
        });
        $player->sendForm($form);
    }
    public function EditArmorr(Player $player, string $armorname, string $text = "หากไม่เอาพลังใดๆ โปรดอย่าเว้นว่างให้ใส่ 0 แทน"): void{
        $form = new CustomForm("แก้ไขเกราะ ".$armorname);
        $form->addElement(new Label($text));
        $form->addElement(new Input("ป้องกันดาเมจของดาบ[หากไม่เอาให้ใส่ 0]", "ป้องกันดาเมจของดาบ"));
        $form->addElement(new Input("ลดความเสียหายคริติคอล[หากไม่เอาให้ใส่ 0]", "ลดความเสียหายคริติคอล"));
        $form->addElement(new Input("ป้องการอัตราการติดไฟ[หากไม่เอาให้ใส่ 0]", "ป้องกันการติดไฟ"));
        $form->addElement(new Input("ป้องกันการกระเด็น[หากไม่เอาให้ใส่ 0]", "ป้องกันการกระเด็น"));
        $form->setCallable(function ($player, $data) use ($armorname){
            if($data == null){
                return;
            }
            $ddamage = explode(" ", $data[1]);
            $dcri = explode(" ", $data[2]);
            $fireprotect = explode(" ", $data[3]);
            $dknock = explode(" ", $data[4]);
            if ($ddamage[0] == null) {
                $text = "§f[§eA§cr§6m§4o§3r§f] §cกรุณาอย่าเว้นว่าง §f(§cป้องกันดาเมจของดาบ§f)";
                $this->EditArmorr($player,$armorname, $text);
                return;
            }
            if (!is_numeric($ddamage[0])) {
                $text = "§f[§eA§cr§6m§4o§3r§f] §cโปรดกรอกเป็นตัวเลข §f(§cป้องกันดาเมจของดาบ§f)";
                $this->EditArmorr($player,$armorname, $text);
                return;
            }
            if ($dcri[0] == null) {
                $text = "§f[§eA§cr§6m§4o§3r§f] §cกรุณาอย่าเว้นว่าง §f(§cลดความเสียหายคริติคอล§f)";
                $this->EditArmorr($player,$armorname, $text);
                return;
            }
            if (!is_numeric($dcri[0])) {
                $text = "§f[§eA§cr§6m§4o§3r§f] §cโปรดกรอกเป็นตัวเลข §f(§cลดความเสียหายคริติคอล§f)";
                $this->EditArmorr($player,$armorname, $text);
                return;
            }
            if ($fireprotect[0] == null) {
                $text = "§f[§eA§cr§6m§4o§3r§f] §cกรุณาอย่าเว้นว่าง §f(§cลดความเสียหายคริติคอล§f)";
                $this->EditArmorr($player,$armorname, $text);
                return;
            }
            if (!is_numeric($fireprotect[0])) {
                $text = "§f[§eA§cr§6m§4o§3r§f] §cโปรดกรอกเป็นตัวเลข §f(§cลดความเสียหายคริติคอล§f)";
                $this->EditArmorr($player,$armorname, $text);
                return;
            }
            if ($dknock[0] == null) {
                $text = "§f[§eA§cr§6m§4o§3r§f] §cกรุณาอย่าเว้นว่าง §f(§cป้องกันการกระเด็น§f)";
                $this->EditArmorr($player,$armorname, $text);
                return;
            }
            if (!is_numeric($dknock[0])) {
                $text = "§f[§eA§cr§6m§4o§3r§f] §cโปรดกรอกเป็นตัวเลข §f(§cป้องกันการกระเด็น§f)";
                $this->EditArmorr($player,$armorname, $text);
                return;
            }
            $defend = (int)$ddamage[0];
            $defendcri = (int)$dcri[0];
            $defendfire = (int)$fireprotect[0];
            $defendknock = (float)$dknock[0];
            $name = $armorname;
            $this->getPlugin()->EditArmor($name, $defend, $defendcri, $defendfire, $defendknock);
            $player->sendMessage("§f[§eA§cr§6m§4o§3r§f] §2คุณได้แก้ไขเกราะ §f" . $name . " §aเรียบร้อยแล้ว");
        });
        $form->setCallableClose(function (Player $player){
        });
        $player->sendForm($form);
    }
    public function Remove(Player $player, string $armorname): void{
        $form = new ModalForm(
            "ลบดาบ ".$armorname, "§fคุณแน่ใจแล้วใช่ไหมที่จะลบเกราะ $armorname","ตกลง", "ยกเลิก"
        );
        $form->setCallable(function ($player, $data) use ($armorname){
            if(!($data === null)){
                if($data){
                    $this->getPlugin()->removeArmor($armorname);
                    $player->sendMessage("§f[§eA§cr§6m§4o§3r§f] §cคุณได้ลบเกราะ §f" . $armorname . " §aเรียบร้อยแล้ว");
                }
            }
        });
        $form->setCallableClose(function (Player $player){
        });
        $player->sendForm($form);
    }
}