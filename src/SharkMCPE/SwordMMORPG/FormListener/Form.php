<?php

namespace SharkMCPE\SwordMMORPG\FormListener;

use pocketmine\item\Item;
use SharkMCPE\SwordMMORPG\SwordLoader;
use SharkMCPE\SwordMMORPG\libs\xenialdan\customui\elements\Button;
use SharkMCPE\SwordMMORPG\libs\xenialdan\customui\elements\Input;
use SharkMCPE\SwordMMORPG\libs\xenialdan\customui\elements\Label;
use SharkMCPE\SwordMMORPG\libs\xenialdan\customui\windows\CustomForm;
use SharkMCPE\SwordMMORPG\libs\xenialdan\customui\windows\ModalForm;
use SharkMCPE\SwordMMORPG\libs\xenialdan\customui\windows\SimpleForm;
use pocketmine\Player;

class Form{
   private $plugin;
   
   public function __construct(SwordLoader $plugin){
      $this->plugin = $plugin;
   }
   public function getPlugin(): SwordLoader{
      return $this->plugin;
   }
  
   public function FormSword(Player $player, string $content = ""): void{
      $array = [];
      if($this->getPlugin()->getCountSword() !== 0){
         foreach($this->getPlugin()->getSword() as $swordnameeeeeeeee){
            $array[] = $swordnameeeeeeeee;
         }
      }
      $form = new SimpleForm("SwordList", $content);
      $form->addButton(new Button("Create Sword\nสร้างดาบต่างๆ"));
      for($i = 0; $i < count($array); $i++){
         $form->addButton(new Button($array[$i]));
      }
      $form->setCallable(function (Player $player, $data) use ($array){
         if(!($data === null)){
            switch($data){
               case ("Create Sword\nสร้างดาบต่างๆ"):
                  $this->CreateSword($player);
                  break;
               default:
                  $this->EditSword($player, $data);
                  break;
            }
         }
      });
      $form->setCallableClose(function (Player $player){
      });
      $player->sendForm($form);
   }
   
   public function CreateSword(Player $player, string $content = "หากไม่เอาพลังใดๆ โปรดอย่าเว้นว่างให้ใส่ 0 แทน"): void{
       $form = new CustomForm("CreateSword");
       $form->addElement(new Label($content));
       $form->addElement(new Input("ชื่อดาบ", "ดาบซามูไร")); //1
       $form->addElement(new Input("ดาเมจดาบ[หากไม่เอาให้ใส่ 0]", "0")); //2
       $form->addElement(new Input("อัตราคริติคอล[หากไม่เอาให้ใส่ 0]", "0")); //3
       $form->addElement(new Input("ความเสียหายคริติคอล[หากไม่เอาให้ใส่ 0]", "0"));//4
       $form->addElement(new Input("ความเร็วในการโจมตี[หากไม่เอาให้ใส่ 0]", "0"));//5
       $form->addElement(new Input("การกระเด็น[หากไม่เอาให้ใส่ 0]", "0"));//6
       $form->addElement(new Input("การติดสโลว์[หากไม่เอาให้ใส่ 0]", "0"));//7
       $form->addElement(new Input("เวลาในการติดสโลว์[หากไม่เอาให้ใส่ 0]", "0"));//78
       $form->addElement(new Input("การติดไฟ[ใส่แค่ เปิด-ปิด]", "ปิด"));//9
	   $form->addElement(new Input("ไอดีดาบ", "276"));//10
       $form->setCallable(function (Player $player, $data) {
           if ($data == null) {
               return;
           }
           $name = explode(" ", $data[1]);
           if ($name[0] == null) {
               $text = "§f[§bS§aw§4o§5r§6d§f] §cโปรดใส่ชื่อดาบ";
               $this->CreateSword($player, $text);
               return;
           }
           $name = $name[0];
           if ($this->getPlugin()->Sword($name)) {
               $text = "§f[§bS§aw§4o§5r§6d§f] §cคุณมีชื่อดาบนี้อยู่แล้ว";
               $this->CreateSword($player, $text);
               return;
           }
           $damage = explode(" ", $data[2]);
           $cri = explode(" ", $data[3]);
           $cridamage = explode(" ", $data[4]);
           $atkspeed = explode(" ", $data[5]);
           $knockback = explode(" ", $data[6]);
           $slow = explode(" ", $data[7]);
           $slowtime = explode(" ", $data[8]);
           $fire = explode(" ", $data[9]);
		   $id = explode(" ", $data[10]);
           if ($damage[0] == null) {
               $text = "§f[§bS§aw§4o§5r§6d§f] §cกรุณาอย่าเว้นว่าง §f(§cดาเมจดาบ§f)";
               $this->CreateSword($player, $text);
               return;
           }
           if (!is_numeric($damage[0])) {
               $text = "§f[§bS§aw§4o§5r§6d§f] §cโปรดกรอกเป็นตัวเลข §f(§cดาเมจดาบ§f)";
               $this->CreateSword($player, $text);
               return;
           }
           $damageint = (int)$damage[0];
           $criint = (int)$cri[0];
           $cridamageint = (int)$cridamage[0];
           $atkspeedint = (int)$atkspeed[0];
           $knockbackint = (float)$knockback[0];
           $slowint = (int)$slow[0];
           $slowtimeint = (int)$slowtime[0];
           if ($cri[0] == null) {
               $text = "§f[§bS§aw§4o§5r§6d§f] §cกรุณาอย่าเว้นว่าง §f(§cอัตราคริติคอล§f)";
               $this->CreateSword($player, $text);
               return;
           }
           if (!is_numeric($cri[0])) {
               $text = "§f[§bS§aw§4o§5r§6d§f] §cโปรดกรอกเป็นตัวเลข §f(§cอัตราคริติคอล§f)";
               $this->CreateSword($player, $text);
               return;
           }
           if($cri[0] > 100){
               $text = "§f[§bS§aw§4o§5r§6d§f] §cค่าสูงสุดคือ 100 §f(§cอัตราคริติคอล§f)";
               $this->CreateSword($player, $text);
               return;
           }
           if ($cridamage[0] == null) {
               $text = "§f[§bS§aw§4o§5r§6d§f] §cกรุณาอย่าเว้นว่าง §f(§cความเสียหายคริติคอล§f)";
               $this->CreateSword($player, $text);
               return;
           }
           if (!is_numeric($cridamage[0])) {
               $text = "§f[§bS§aw§4o§5r§6d§f] §cโปรดกรอกเป็นตัวเลข §f(§cความเสียหายคริติคอล§f)";
               $this->CreateSword($player, $text);
               return;
           }
           if ($atkspeed[0] == null) {
               $text = "§f[§bS§aw§4o§5r§6d§f] §cกรุณาอย่าเว้นว่าง §f(§cความเร็วในการโจมตี§f)";
               $this->CreateSword($player, $text);
               return;
           }
           if (!is_numeric($atkspeed[0])) {
               $text = "§f[§bS§aw§4o§5r§6d§f] §cโปรดกรอกเป็นตัวเลข §f(§cความเร็วในการโจมตี§f)";
               $this->CreateSword($player, $text);
               return;
           }
           if ($knockback[0] == null) {
               $text = "§f[§bS§aw§4o§5r§6d§f] §cกรุณาอย่าเว้นว่าง §f(§cการกระเด็น§f)";
               $this->CreateSword($player, $text);
               return;
           }
           if (!is_numeric($knockback[0])) {
               $text = "§f[§bS§aw§4o§5r§6d§f] §cโปรดกรอกเป็นตัวเลข §f(§cการกระเด็น§f)";
               $this->CreateSword($player, $text);
               return;
           }
           if ($slow[0] == null) {
               $text = "§f[§bS§aw§4o§5r§6d§f] §cกรุณาอย่าเว้นว่าง §f(§cการสโล§f)";
               $this->CreateSword($player, $text);
               return;
           }
           if (!is_numeric($slow[0])) {
               $text = "§f[§bS§aw§4o§5r§6d§f] §cโปรดกรอกเป็นตัวเลข §f(§cการสโล§f)";
               $this->CreateSword($player, $text);
               return;
           }
           if ($slowtime[0] == null) {
               $text = "§f[§bS§aw§4o§5r§6d§f] §cกรุณาอย่าเว้นว่าง §f(§cเวลาการสโล§f)";
               $this->CreateSword($player, $text);
               return;
           }
           if (!is_numeric($slowtime[0])) {
               $text = "§f[§bS§aw§4o§5r§6d§f] §cโปรดกรอกเป็นตัวเลข §f(§cเวลาการสโล§f)";
               $this->CreateSword($player, $text);
               return;
           }
           if ($fire[0] == null) {
               $text = "§f[§bS§aw§4o§5r§6d§f] §cอย่าเว้นว่าง §f(§cการติดไฟ§f)";
               $this->CreateSword($player, $text);
               return;
           }
           if (is_numeric($fire[0])) {
               $text = "§f[§bS§aw§4o§5r§6d§f] §cโปรดใส่แค่ เปิด-ปิด §f(§cการติดไฟ§f)";
               $this->CreateSword($player, $text);
               return;
           }
           if (!$fire[0] == "เปิด" || !$fire[0] == "ปิด") {
               $text = "§f[§bS§aw§4o§5r§6d§f] §cโปรดใส่แค่ เปิด-ปิด §f(§cการติดไฟ§f)";
               $this->CreateSword($player, $text);
               return;
           }
		   if (!is_numeric($id[0])) {
               $text = "§f[§bS§aw§4o§5r§6d§f] §cโปรดใส่ตัวเลข §f(§cไอดีดาบ§f)";
               $this->CreateSword($player, $text);
               return;
           }
           if ($fire[0] == null) {
               $text = "§f[§bS§aw§4o§5r§6d§f] §cอย่าเว้นว่าง §f(§cไอดีดาบ§f)";
               $this->CreateSword($player, $text);
               return;
           }
           $fires = $fire[0];
           $this->getPlugin()->createSword($name, $damageint, $criint, $cridamageint, $atkspeedint, $knockbackint, $slowint, $slowtimeint, $fires);
           $player->sendMessage("§f[§bS§aw§4o§5r§6d§f] §aคุณได้สร้างดาบ " . $name . " §aเรียบร้อยแล้ว");
       });
       $form->setCallableClose(function (Player $player) {
       });
       $player->sendForm($form);
   }
   
   public function EditSword(Player $player, string $swordnameeeeeeeee): void
   {
       $form = new SimpleForm($swordnameeeeeeeee, "§bชื่อดาบ§f: $swordnameeeeeeeee\n§aคุณสามารถแก้ไขดาบนี้ได้เลย");
       $array = ["§bEditSword\n§fแก้ไขดาบ" => 0, "§cDeleteSword\n§fลบดาบ" => 1, "§aAddSword\n§fเสกดาบ" => 2];
       foreach ($array as $button => $value) {
           $form->addButton(new Button($button));
       }
       $form->setCallable(function ($player, $data) use ($swordnameeeeeeeee, $array) {
           if (!($data === null)) {
               switch ($array[$data]) {
                   case 0:
                       $this->EditinsideSword($player, $swordnameeeeeeeee);
                       break;
                   case 1:
                       $this->Remove($player, $swordnameeeeeeeee);
                       break;
                   case 2:
                       $sword = Item::get(276, 0, 1);
                       $sword->setCustomName($swordnameeeeeeeee);
                       $data = $this->plugin->getData()->getAll();
                       $lore = [
                           "",
                           "§fดาเมจ §e".$data["sword"][$swordnameeeeeeeee]["damage"],
                           "§fอัตราคริติคอล §e".$data["sword"][$swordnameeeeeeeee]["cri"],
                           "§fความเสียหายคริติคอล §e".$data["sword"][$swordnameeeeeeeee]["cridamage"],
                           "§fความเร็วในการโจมตี §e".$data["sword"][$swordnameeeeeeeee]["atkspeed"],
                           "§fการติดสโล §e".$data["sword"][$swordnameeeeeeeee]["slow"],
                           "§fเวลาการติดสโล §e".$data["sword"][$swordnameeeeeeeee]["slowtime"],
                           "§fการติดไฟ §e".$data["sword"][$swordnameeeeeeeee]["fire"],
                           "§fการกระเด็น §e".$data["sword"][$swordnameeeeeeeee]["knockback"]
                       ];
                       $sword->setLore($lore);
                       $player->getInventory()->addItem($sword);
                       $player->sendMessage("§f[§bS§aw§4o§5r§6d§f] §aคุณได้รับดาบเรียบร้อยแล้ว");
                       break;
               }
           }
       });
       $form->setCallableClose(function (Player $player) {
       });
       $player->sendForm($form);
   }
   public function EditinsideSword(Player $player, string $swordnameeeeeeeee, string $text = "หากไม่เอาพลังใดๆ โปรดอย่าเว้นว่างให้ใส่ 0 แทน"): void{
       $form = new CustomForm("แก้ไขดาบ ".$swordnameeeeeeeee);
       $form->addElement(new Label($text));
       $form->addElement(new Input("ดาเมจดาบ[หากไม่เอาให้ใส่ 0]", "0"));
       $form->addElement(new Input("อัตราคริติคอล[หากไม่เอาให้ใส่ 0]", "0"));
       $form->addElement(new Input("ความเสียหายคริติคอล[หากไม่เอาให้ใส่ 0]", "0"));
       $form->addElement(new Input("ความเร็วในการโจมตี[หากไม่เอาให้ใส่ 0]", "0"));
       $form->addElement(new Input("การกระเด็น[หากไม่เอาให้ใส่ 0]", "0"));
       $form->addElement(new Input("การติดสโลว์[หากไม่เอาให้ใส่ 0]", "0"));
       $form->addElement(new Input("เวลาในการติดสโลว์[หากไม่เอาให้ใส่ 0]", "0"));
       $form->addElement(new Input("การติดไฟ[ใส่แค่ เปิด-ปิด]", "ปิด"));
       $form->setCallable(function ($player, $data) use ($swordnameeeeeeeee){
           if($data == null){
               return;
           }
          $name = $swordnameeeeeeeee;
          $damage = explode(" ", $data[1]);
          $cri = explode(" ", $data[2]);
          $cridamage = explode(" ", $data[3]);
          $atkspeed = explode(" ", $data[4]);
          $knockback = explode(" ", $data[5]);
          $slow = explode(" ", $data[6]);
          $slowtime = explode(" ", $data[7]);
          $fire = explode(" ", $data[8]);
          if ($damage[0] == null) {
              $text = "§f[§bS§aw§4o§5r§6d§f] §cกรุณาอย่าเว้นว่าง §f(§cดาเมจดาบ§f)";
              $this->EditinsideSword($player, $swordnameeeeeeeee, $text);
              return;
          }
          if (!is_numeric($damage[0])) {
              $text = "§f[§bS§aw§4o§5r§6d§f] §cโปรดกรอกเป็นตัวเลข §f(§cดาเมจดาบ§f)";
              $this->EditinsideSword($player, $swordnameeeeeeeee, $text);
              return;
          }
          if ($cri[0] == null) {
              $text = "§f[§bS§aw§4o§5r§6d§f] §cกรุณาอย่าเว้นว่าง §f(§cอัตราคริติคอล§f)";
              $this->EditinsideSword($player, $swordnameeeeeeeee, $text);
              return;
          }
          if (!is_numeric($cri[0])) {
              $text = "§f[§bS§aw§4o§5r§6d§f] §cโปรดกรอกเป็นตัวเลข §f(§cอัตราคริติคอล§f)";
              $this->EditinsideSword($player, $swordnameeeeeeeee, $text);
              return;
          }
          if ($cridamage[0] == null) {
              $text = "§f[§bS§aw§4o§5r§6d§f] §cกรุณาอย่าเว้นว่าง §f(§cความเสียหายคริติคอล§f)";
              $this->EditinsideSword($player, $swordnameeeeeeeee, $text);
              return;
          }
          if (!is_numeric($cridamage[0])) {
              $text = "§f[§bS§aw§4o§5r§6d§f] §cโปรดกรอกเป็นตัวเลข §f(§cความเสียหายคริติคอล§f)";
              $this->EditinsideSword($player, $swordnameeeeeeeee, $text);
              return;
          }
          if ($atkspeed[0] == null) {
              $text = "§f[§bS§aw§4o§5r§6d§f] §cกรุณาอย่าเว้นว่าง §f(§cความเสียหายคริติคอล§f)";
              $this->EditinsideSword($player, $swordnameeeeeeeee, $text);
              return;
          }
          if (!is_numeric($atkspeed[0])) {
              $text = "§f[§bS§aw§4o§5r§6d§f] §cโปรดกรอกเป็นตัวเลข §f(§cความเร็วในการโจมตี§f)";
              $this->EditinsideSword($player, $swordnameeeeeeeee, $text);
              return;
          }
          if ($knockback[0] == null) {
              $text = "§f[§bS§aw§4o§5r§6d§f] §cกรุณาอย่าเว้นว่าง §f(§cการกระเด็น§f)";
              $this->EditinsideSword($player, $swordnameeeeeeeee, $text);
              return;
          }
          if (!is_numeric($knockback[0])) {
              $text = "§f[§bS§aw§4o§5r§6d§f] §cโปรดกรอกเป็นตัวเลข §f(§cการกระเด็น§f)";
              $this->EditinsideSword($player, $swordnameeeeeeeee, $text);
              return;
          }
          if ($slow[0] == null) {
              $text = "§f[§bS§aw§4o§5r§6d§f] §cกรุณาอย่าเว้นว่าง §f(§cการสโล§f)";
              $this->EditinsideSword($player, $swordnameeeeeeeee, $text);
              return;
          }
          if (!is_numeric($slow[0])) {
              $text = "§f[§bS§aw§4o§5r§6d§f] §cโปรดกรอกเป็นตัวเลข §f(§cการสโล§f)";
              $this->EditinsideSword($player, $swordnameeeeeeeee, $text);
              return;
          }
          if ($slowtime[0] == null) {
              $text = "§f[§bS§aw§4o§5r§6d§f] §cกรุณาอย่าเว้นว่าง §f(§cเวลาการสโล§f)";
              $this->EditinsideSword($player, $swordnameeeeeeeee, $text);
              return;
          }
          if (!is_numeric($slowtime[0])) {
              $text = "§f[§bS§aw§4o§5r§6d§f] §cโปรดกรอกเป็นตัวเลข §f(§cเวลาการสโล§f)";
              $this->EditinsideSword($player, $swordnameeeeeeeee, $text);
              return;
          }
          if ($fire[0] == null) {
              $text = "§f[§bS§aw§4o§5r§6d§f] §cอย่าเว้นว่าง §f(§cการติดไฟ§f)";
              $this->EditinsideSword($player, $swordnameeeeeeeee, $text);
              return;
          }
          if (is_numeric($fire[0])) {
              $text = "§f[§bS§aw§4o§5r§6d§f] §cโปรดใส่แค่ เปิด-ปิด §f(§cการติดไฟ§f)";
              $this->EditinsideSword($player, $swordnameeeeeeeee, $text);
              return;
          }
          if (!$fire[0] == "เปิด" || !$fire[0] == "ปิด") {
              $text = "§f[§bS§aw§4o§5r§6d§f] §cโปรดใส่แค่ เปิด-ปิด §f(§cการติดไฟ§f)";
              $this->EditinsideSword($player, $swordnameeeeeeeee, $text);
              return;
          }
          $damageint = (int)$damage[0];
          $criint = (int)$cri[0];
          $cridamageint = (int)$cridamage[0];
          $atkspeedint = (int)$atkspeed[0];
          $knockbackint = (float)$knockback[0];
          $slowint = (int)$slow[0];
          $slowtimeint = (int)$slowtime[0];
          $name = $swordnameeeeeeeee;
          $fire = $fire[0];
          $this->getPlugin()->editSword($name, $damageint, $criint, $cridamageint, $atkspeedint, $knockbackint, $slowint, $slowtimeint, $fire);
          $player->sendMessage("§f[§bS§aw§4o§5r§6d§f] §2คุณได้แก้ไขดาบ §f" . $name . " §aเรียบร้อยแล้ว");
      });
      $form->setCallableClose(function (Player $player){
      });
      $player->sendForm($form);
   }
   public function Remove(Player $player, string $swordnameeeeeeeee): void{
      $form = new ModalForm(
         "ลบดาบ ".$swordnameeeeeeeee, "§fคุณแน่ใจแล้วใช่ไหมที่จะลบดาบ $swordnameeeeeeeee","ตกลง", "ยกเลิก"
      );
      $form->setCallable(function ($player, $data) use ($swordnameeeeeeeee){
         if(!($data === null)){
            if($data){
               $this->getPlugin()->removeSword($swordnameeeeeeeee);
               $player->sendMessage("§f[§bS§aw§4o§5r§6d§f] §cคุณได้ลบดาบ §f" . $swordnameeeeeeeee . " §aเรียบร้อยแล้ว");
            }
         }
      });
      $form->setCallableClose(function (Player $player){
      });
      $player->sendForm($form);
   }
}