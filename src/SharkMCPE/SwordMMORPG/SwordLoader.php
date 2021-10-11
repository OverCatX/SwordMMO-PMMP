<?php

namespace SharkMCPE\SwordMMORPG;

use pocketmine\event\Listener;
use SharkMCPE\SwordMMORPG\Commands\ArmorCommand;
use SharkMCPE\SwordMMORPG\Commands\SwordCommand;
use SharkMCPE\SwordMMORPG\EventListener\PlayerArmorChangeEvent;
use SharkMCPE\SwordMMORPG\FormListener\Form;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use SharkMCPE\SwordMMORPG\EventListener\PlayerDamageEvent;
use pocketmine\utils\Config;
use SharkMCPE\SwordMMORPG\FormListener\FormArmor;

class SwordLoader extends PluginBase implements Listener {

    private static $instance = null;
    private $data = null;
    public $array = [];
    private $form = null;
    private $data1 = null;
    private $formarmor = null;

    public static function getInstance(): SwordLoader
    {
        return self::$instance;
    }

    public function onLoad(): void
    {
        self::$instance = $this;
    }

    public function onEnable(): void
    {
        @mkdir($this->getDataFolder());
        $this->saveDefaultConfig();
        $this->data = new Config($this->getDataFolder() . "SwordMMORPG.yml", Config::YAML, array());
        $this->data1 = new Config($this->getDataFolder() . "ArmorMMORPG.yml", Config::YAML, array());
        $c = $this->data->getAll();
        $d = $this->data1->getAll();
        if (!isset($c["sword"])) {
            $c["sword"] = [];
            $this->data->setAll($c);
            $this->data->save();
        }
        if (!isset($d["armor"])) {
            $d["armor"] = [];
            $this->data1->setAll($d);
            $this->data1->save();
        }
        $this->form = new Form($this);
        $this->formarmor = new FormArmor($this);
        $this->getServer()->getCommandMap()->register("sword", new SwordCommand($this));
        $this->getServer()->getCommandMap()->register("armor", new ArmorCommand($this));
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerDamageEvent($this), $this);
        //$this->getServer()->getPluginManager()->registerEvents(new PlayerArmorChangeEvent($this), $this);
    }

    public function getFormListener(){
        return $this->form;
    }
    public function getFormArmorListener(){
        return $this->formarmor;
    }
    public function getData(){
        return $this->data;
    }
    public function getDataArmor(){
        return $this->data1;
    }

    public function getSword(): array
    {
        $swordData = $this->getData();
        $data = $swordData->getAll();
        return array_keys($data["sword"]);
    }

    public function Sword(string $name): bool
    {
        $swordData = $this->getData();
        $data = $swordData->getAll();
        return isset($data["sword"][$name]);
    }

    public function getCountSword(): int
    {
        $swordData = $this->getData();
        $data = $swordData->getAll();
        return count($data["sword"]);
    }

    #Armor
    public function getArmor(): array
    {
        $armordata = $this->getDataArmor();
        $data = $armordata->getAll();
        return array_keys($data["armor"]);
    }

    public function Armor(string $name): bool
    {
        $armordata = $this->getDataArmor();
        $data = $armordata->getAll();
        return isset($data["armor"][$name]);
    }

    public function getCountArmor(): int
    {
        $armordata = $this->getDataArmor();
        $data = $armordata->getAll();
        return count($data["armor"]);
    }

    #เกราะ
    public function createArmor(string $name, int $id, int $defend, int $defendcri, int $defendfire, float $defendknock): void
    {
        $swordData = $this->getDataArmor();
        $data = $swordData->getAll();
        $data["armor"][$name]["id"] = $id;
        $data["armor"][$name]["defend"] = $defend;
        $data["armor"][$name]["defendcri"] = $defendcri;
        $data["armor"][$name]["defendfire"] = $defendfire;
        $data["armor"][$name]["defendknock"] = $defendknock;
        $swordData->setAll($data);
        $swordData->save();
    }

    #เกราะ
    public function EditArmor(string $name, int $defend, int $defendcri, int $defendfire, float $defendknock): void
    {
        $swordData = $this->getDataArmor();
        $data = $swordData->getAll();
        $data["armor"][$name]["defend"] = $defend;
        $data["armor"][$name]["defendcri"] = $defendcri;
        $data["armor"][$name]["defendfire"] = $defendfire;
        $data["armor"][$name]["defendknock"] = $defendknock;
        $swordData->setAll($data);
        $swordData->save();
    }

    public function createSword(string $name,int $id, int $damageint, int $criint, int $cridamageint, int $atkspeedint, float $knockbackint, int $slowint, int $slowtimeint, string $fires): void
    {
        $swordData = $this->getData();
        $data = $swordData->getAll();
		$data["sword"][$name]["Id"] = $id;
        $data["sword"][$name]["damage"] = $damageint;
        $data["sword"][$name]["cri"] = $criint;
        $data["sword"][$name]["cridamage"] = $cridamageint;
        $data["sword"][$name]["atkspeed"] = $atkspeedint;
        $data["sword"][$name]["knockback"] = $knockbackint;
        $data["sword"][$name]["slow"] = $slowint;
        $data["sword"][$name]["slowtime"] = $slowtimeint;
        $data["sword"][$name]["fire"] = $fires;
        $swordData->setAll($data);
        $swordData->save();
    }

    public function editSword(string $name,int $id, int $damageint, int $criint,  int $cridamageint, int $atkspeedint, float $knockbackint, int $slowint, int $slowtimeint, string $fire): void
    {
        $swordData = $this->getData();
        $data = $swordData->getAll();
		$data["sword"][$name]["Id"] = $id;
        $data["sword"][$name]["damage"] = $damageint;
        $data["sword"][$name]["cri"] = $criint;
        $data["sword"][$name]["cridamage"] = $cridamageint;
        $data["sword"][$name]["atkspeed"] = $atkspeedint;
        $data["sword"][$name]["knockback"] = $knockbackint;
        $data["sword"][$name]["slow"] = $slowint;
        $data["sword"][$name]["slowtime"] = $slowtimeint;
        $data["sword"][$name]["fire"] = $fire;
        $swordData->setAll($data);
        $swordData->save();
    }

    public function removeSword(string $name): void
    {
        $swordData = $this->getData();
        $data = $swordData->getAll();
        unset($data["sword"][$name]);
        $swordData->setAll($data);
        $swordData->save();
    }

    public function removeArmor(string $name): void
    {
        $armordata = $this->getDataArmor();
        $data = $armordata->getAll();
        unset($data["armor"][$name]);
        $armordata->setAll($data);
        $armordata->save();
    }
}