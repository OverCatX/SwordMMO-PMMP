<?php

namespace SharkMCPE\SwordMMORPG\EventListener;

use pocketmine\entity\EntityIds;
use pocketmine\item\Armor;
use pocketmine\network\mcpe\protocol\AddActorPacket;
use pocketmine\network\mcpe\protocol\RemoveActorPacket;
use SharkMCPE\SwordMMORPG\Task\CallbackTask;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\item\Item;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\entity\Entity;
use pocketmine\Player;
use pocketmine\math\Vector3;
use SharkMCPE\SwordMMORPG\SwordLoader;

class PlayerDamageEvent implements Listener
{
    private $plugin;

    public function __construct(SwordLoader $plugin)
    {
        $this->plugin = $plugin;
    }

    public function getPlugin(): SwordLoader
    {
        return $this->plugin;
    }

    public function onEntityDamage(EntityDamageEvent $event)
    {
        if ($event instanceof EntityDamageByEntityEvent) {
            $entity = $event->getEntity();
            $damager = $event->getDamager();
            if ($damager instanceof Player) {
                $data = $this->plugin->getData()->getAll();
                $data1 = $this->plugin->getDataArmor()->getAll();
                $item = $damager->getInventory()->getItemInHand();
                $itemname = $item->getCustomName();
                $itemlore = $item->getLore();
                $name = $itemname;
                if (!empty($data["sword"][$name])) {
                    $critical = $data["sword"][$name]["cridamage"];
                    $lore = [
                        "",
                        "§fดาเมจ §e" . $data["sword"][$name]["damage"],
                        "§fอัตราคริติคอล §e" . $data["sword"][$name]["cri"],
                        "§fความเสียหายคริติคอล §e" . $data["sword"][$name]["cridamage"],
                        "§fความเร็วในการโจมตี §e" . $data["sword"][$name]["atkspeed"],
                        "§fการติดสโล §e" . $data["sword"][$name]["slow"],
                        "§fเวลาการติดสโล §e" . $data["sword"][$name]["slowtime"],
                        "§fการติดไฟ §e" . $data["sword"][$name]["fire"],
                        "§fการกระเด็น §e" . $data["sword"][$name]["knockback"]
                    ];
                    if ($itemlore == $lore) {
                        $helmetname = $entity->getArmorInventory()->getHelmet()->getCustomName();
                        $helmets = $entity->getArmorInventory()->getHelmet();
                        $chestplate = $entity->getArmorInventory()->getChestplate()->getCustomName();
                        $chestplates = $entity->getArmorInventory()->getChestplate();
                        $legging = $entity->getArmorInventory()->getLeggings()->getCustomName();
                        $leggings = $entity->getArmorInventory()->getLeggings();
                        $boot = $entity->getArmorInventory()->getBoots()->getCustomName();
                        $boots = $entity->getArmorInventory()->getBoots();
                        if (!$helmets->getId() == 0 or !$chestplates->getId() == 0 or !$leggings->getId() == 0 or !$boots->getId() == 0) {
                            if (!empty($data1["armor"][$helmetname]) or !empty($data1["armor"][$chestplate]) or !empty($data1["armor"][$legging]) or !empty($data1["armor"][$boot])) {
                                if (!$helmets->getId() == 0 and !$chestplates->getId() == 0 and !$leggings->getId() == 0 and !$boots->getId() == 0) {
                                    $lorehelmet = [
                                        "",
                                        "§fป้องกันดาเมจ §e" . $data1["armor"][$helmetname]["defend"],
                                        "§fลดความเสียหายคริติคอล §e" . $data1["armor"][$helmetname]["defendcri"],
                                        "§fลดการติดไฟ §e" . $data1["armor"][$helmetname]["defendfire"],
                                        "§fป้องกันการกระเด็น §e" . $data1["armor"][$helmetname]["defendknock"]
                                    ];
                                    $lorechestplate = [
                                        "",
                                        "§fป้องกันดาเมจ §e" . $data1["armor"][$chestplate]["defend"],
                                        "§fลดความเสียหายคริติคอล §e" . $data1["armor"][$chestplate]["defendcri"],
                                        "§fลดการติดไฟ §e" . $data1["armor"][$chestplate]["defendfire"],
                                        "§fป้องกันการกระเด็น §e" . $data1["armor"][$chestplate]["defendknock"]
                                    ];
                                    $loreleggings = [
                                        "",
                                        "§fป้องกันดาเมจ §e" . $data1["armor"][$legging]["defend"],
                                        "§fลดความเสียหายคริติคอล §e" . $data1["armor"][$legging]["defendcri"],
                                        "§fลดการติดไฟ §e" . $data1["armor"][$legging]["defendfire"],
                                        "§fป้องกันการกระเด็น §e" . $data1["armor"][$legging]["defendknock"]
                                    ];
                                    $loreboots = [
                                        "",
                                        "§fป้องกันดาเมจ §e" . $data1["armor"][$boot]["defend"],
                                        "§fลดความเสียหายคริติคอล §e" . $data1["armor"][$boot]["defendcri"],
                                        "§fลดการติดไฟ §e" . $data1["armor"][$boot]["defendfire"],
                                        "§fป้องกันการกระเด็น §e" . $data1["armor"][$boot]["defendknock"]
                                    ];
                                    if ($helmets->getLore() == $lorehelmet and $chestplates->getLore() == $lorechestplate and $leggings->getLore() == $loreleggings and $boots->getLore() == $loreboots) {
                                        $rr = $data1["armor"][$helmetname]["defend"] + $data1["armor"][$chestplate]["defend"] + $data1["armor"][$legging]["defend"] + $data1["armor"][$boot]["defend"];
                                        $damage = $data["sword"][$name]["damage"] / $rr;
                                        $this->getDamage($event, $entity, $damage);
                                        $kk = $data1["armor"][$helmetname]["defendknock"] + $data1["armor"][$chestplate]["defendknock"] + $data1["armor"][$legging]["defendknock"] + $data1["armor"][$boot]["defendknock"];
                                        $knockback = $data["sword"][$name]["knockback"] / $kk;
                                        $event->setKnockBack($knockback);
                                        $cridefend = $data1["armor"][$helmetname]["defendcri"] + $data1["armor"][$chestplate]["defendcri"] + $data1["armor"][$legging]["defendcri"] + $data1["armor"][$boot]["defendcri"];
                                        $crireal = $cridefend / $critical;
                                        $damageee = $event->getBaseDamage();
                                        $this->CriDamage($name, $crireal, $entity, $damageee, $damager);
                                        $fires = $data1["armor"][$helmetname]["defendfire"] + $data1["armor"][$chestplate]["defendfire"] + $data1["armor"][$legging]["defendfire"] + $data1["armor"][$boot]["defendfire"];
                                        $fire = 10 / $fires;
                                        $this->Fire($name, $entity, $fire);
                                        $this->KnockbackParticle($damager, $entity, $knockback);
                                        $this->DamageParticle($damager, $entity, $damage);
                                    }
                                } else {
                                    if (!$helmets->getId() == 0 and !$chestplates->getId() == 0 and !$leggings->getId() == 0) {
                                        $lorehelmet = [
                                            "",
                                            "§fป้องกันดาเมจ §e" . $data1["armor"][$helmetname]["defend"],
                                            "§fลดความเสียหายคริติคอล §e" . $data1["armor"][$helmetname]["defendcri"],
                                            "§fลดการติดไฟ §e" . $data1["armor"][$helmetname]["defendfire"],
                                            "§fป้องกันการกระเด็น §e" . $data1["armor"][$helmetname]["defendknock"]
                                        ];
                                        $lorechestplate = [
                                            "",
                                            "§fป้องกันดาเมจ §e" . $data1["armor"][$chestplate]["defend"],
                                            "§fลดความเสียหายคริติคอล §e" . $data1["armor"][$chestplate]["defendcri"],
                                            "§fลดการติดไฟ §e" . $data1["armor"][$chestplate]["defendfire"],
                                            "§fป้องกันการกระเด็น §e" . $data1["armor"][$chestplate]["defendknock"]
                                        ];
                                        $loreleggings = [
                                            "",
                                            "§fป้องกันดาเมจ §e" . $data1["armor"][$legging]["defend"],
                                            "§fลดความเสียหายคริติคอล §e" . $data1["armor"][$legging]["defendcri"],
                                            "§fลดการติดไฟ §e" . $data1["armor"][$legging]["defendfire"],
                                            "§fป้องกันการกระเด็น §e" . $data1["armor"][$legging]["defendknock"]
                                        ];
                                        if ($helmets->getLore() == $lorehelmet and $chestplates->getLore() == $lorechestplate and $leggings->getLore() == $loreleggings) {
                                            $rr = $data1["armor"][$helmetname]["defend"] + $data1["armor"][$chestplate]["defend"] + $data1["armor"][$legging]["defend"];
                                            $damage = $data["sword"][$name]["damage"] / $rr;
                                            $this->getDamage($event, $entity, $damage);
                                            $kk = $data1["armor"][$helmetname]["defendknock"] + $data1["armor"][$chestplate]["defendknock"] + $data1["armor"][$legging]["defendknock"];
                                            $knockback = $data["sword"][$name]["knockback"] / $kk;
                                            $event->setKnockBack($knockback);
                                            $cridefend = $data1["armor"][$helmetname]["defendcri"] + $data1["armor"][$chestplate]["defendcri"] + $data1["armor"][$legging]["defendcri"];
                                            $crireal = $cridefend / $critical;
                                            $damageee = $event->getBaseDamage();
                                            $this->CriDamage($name, $crireal, $entity, $damageee, $damager);
                                            $fires = $data1["armor"][$helmetname]["defendfire"] + $data1["armor"][$chestplate]["defendfire"] + $data1["armor"][$legging]["defendfire"];
                                            $fire = 10 / $fires;
                                            $this->Fire($name, $entity, $fire);
                                            $this->KnockbackParticle($damager, $entity, $knockback);
                                            $this->DamageParticle($damager, $entity, $damage);
                                            $damager->sendMessage("Entity has 1 Helmet and 1 Chestplate and 1 Leggings");
                                        }
                                    } else {
                                        if (!$chestplates->getId() == 0 and !$leggings->getId() == 0 and !$boots->getId() == 0) {
                                            $lorechestplate = [
                                                "",
                                                "§fป้องกันดาเมจ §e" . $data1["armor"][$chestplate]["defend"],
                                                "§fลดความเสียหายคริติคอล §e" . $data1["armor"][$chestplate]["defendcri"],
                                                "§fลดการติดไฟ §e" . $data1["armor"][$chestplate]["defendfire"],
                                                "§fป้องกันการกระเด็น §e" . $data1["armor"][$chestplate]["defendknock"]
                                            ];
                                            $loreleggings = [
                                                "",
                                                "§fป้องกันดาเมจ §e" . $data1["armor"][$legging]["defend"],
                                                "§fลดความเสียหายคริติคอล §e" . $data1["armor"][$legging]["defendcri"],
                                                "§fลดการติดไฟ §e" . $data1["armor"][$legging]["defendfire"],
                                                "§fป้องกันการกระเด็น §e" . $data1["armor"][$legging]["defendknock"]
                                            ];
                                            $loreboots = [
                                                "",
                                                "§fป้องกันดาเมจ §e" . $data1["armor"][$boot]["defend"],
                                                "§fลดความเสียหายคริติคอล §e" . $data1["armor"][$boot]["defendcri"],
                                                "§fลดการติดไฟ §e" . $data1["armor"][$boot]["defendfire"],
                                                "§fป้องกันการกระเด็น §e" . $data1["armor"][$boot]["defendknock"]
                                            ];
                                            if ($chestplates->getLore() == $lorechestplate and $leggings->getLore() == $loreleggings and $boots->getLore() == $loreboots) {
                                                $rr = $data1["armor"][$legging]["defend"] + $data1["armor"][$chestplate]["defend"] + $data1["armor"][$boot]["defend"];
                                                $damage = $data["sword"][$name]["damage"] / $rr;
                                                $this->getDamage($event, $entity, $damage);
                                                $kk = $data1["armor"][$legging]["defendknock"] + $data1["armor"][$chestplate]["defendknock"] + $data1["armor"][$boot]["defendknock"];
                                                $knockback = $data["sword"][$name]["knockback"] / $kk;
                                                $event->setKnockBack($knockback);
                                                $cridefend = $data1["armor"][$legging]["defendcri"] + $data1["armor"][$chestplate]["defendcri"] + $data1["armor"][$boot]["defendcri"];
                                                $crireal = $cridefend / $critical;
                                                $damageee = $event->getBaseDamage();
                                                $this->CriDamage($name, $crireal, $entity, $damageee, $damager);
                                                $fires = $data1["armor"][$legging]["defendfire"] + $data1["armor"][$chestplate]["defendfire"] + $data1["armor"][$boot]["defendfire"];
                                                $fire = 10 / $fires;
                                                $this->Fire($name, $entity, $fire);
                                                $this->KnockbackParticle($damager, $entity, $knockback);
                                                $this->DamageParticle($damager, $entity, $damage);
                                                $damager->sendMessage("Entity has 1 Chestplate and 1 Leggings and 1 Boots");
                                            }
                                        } else {
                                            if (!$helmets->getId() == 0 and !$leggings->getId() == 0 and !$boots->getId() == 0) {
                                                $lorehelmet = [
                                                    "",
                                                    "§fป้องกันดาเมจ §e" . $data1["armor"][$helmetname]["defend"],
                                                    "§fลดความเสียหายคริติคอล §e" . $data1["armor"][$helmetname]["defendcri"],
                                                    "§fลดการติดไฟ §e" . $data1["armor"][$helmetname]["defendfire"],
                                                    "§fป้องกันการกระเด็น §e" . $data1["armor"][$helmetname]["defendknock"]
                                                ];
                                                $loreleggings = [
                                                    "",
                                                    "§fป้องกันดาเมจ §e" . $data1["armor"][$legging]["defend"],
                                                    "§fลดความเสียหายคริติคอล §e" . $data1["armor"][$legging]["defendcri"],
                                                    "§fลดการติดไฟ §e" . $data1["armor"][$legging]["defendfire"],
                                                    "§fป้องกันการกระเด็น §e" . $data1["armor"][$legging]["defendknock"]
                                                ];
                                                $loreboots = [
                                                    "",
                                                    "§fป้องกันดาเมจ §e" . $data1["armor"][$boot]["defend"],
                                                    "§fลดความเสียหายคริติคอล §e" . $data1["armor"][$boot]["defendcri"],
                                                    "§fลดการติดไฟ §e" . $data1["armor"][$boot]["defendfire"],
                                                    "§fป้องกันการกระเด็น §e" . $data1["armor"][$boot]["defendknock"]
                                                ];
                                                if ($helmets->getLore() == $lorehelmet and $leggings->getLore() == $loreleggings and $boots->getLore() == $loreboots) {
                                                    $rr = $data1["armor"][$legging]["defend"] + $data1["armor"][$helmetname]["defend"] + $data1["armor"][$boot]["defend"];
                                                    $damage = $data["sword"][$name]["damage"] / $rr;
                                                    $this->getDamage($event, $entity, $damage);
                                                    $kk = $data1["armor"][$legging]["defendknock"] + $data1["armor"][$helmetname]["defendknock"] + $data1["armor"][$boot]["defendknock"];
                                                    $knockback = $data["sword"][$name]["knockback"] / $kk;
                                                    $event->setKnockBack($knockback);
                                                    $cridefend = $data1["armor"][$legging]["defendcri"] + $data1["armor"][$helmetname]["defendcri"] + $data1["armor"][$boot]["defendcri"];
                                                    $crireal = $cridefend / $critical;
                                                    $damageee = $event->getBaseDamage();
                                                    $this->CriDamage($name, $crireal, $entity, $damageee, $damager);
                                                    $fires = $data1["armor"][$legging]["defendfire"] + $data1["armor"][$helmetname]["defendfire"] + $data1["armor"][$boot]["defendfire"];
                                                    $fire = 10 / $fires;
                                                    $this->Fire($name, $entity, $fire);
                                                    $this->KnockbackParticle($damager, $entity, $knockback);
                                                    $this->DamageParticle($damager, $entity, $damage);
                                                    $damager->sendMessage("Entity has 1 Helmet and 1 Leggings and 1 Boots");
                                                }
                                            } else {
                                                if (!$helmets->getId() == 0) {
                                                    $lorehelmet = [
                                                        "",
                                                        "§fป้องกันดาเมจ §e" . $data1["armor"][$helmetname]["defend"],
                                                        "§fลดความเสียหายคริติคอล §e" . $data1["armor"][$helmetname]["defendcri"],
                                                        "§fลดการติดไฟ §e" . $data1["armor"][$helmetname]["defendfire"],
                                                        "§fป้องกันการกระเด็น §e" . $data1["armor"][$helmetname]["defendknock"]
                                                    ];
                                                    if (!$chestplates->getId() == 0) {
                                                        $lorechestplate = [
                                                            "",
                                                            "§fป้องกันดาเมจ §e" . $data1["armor"][$chestplate]["defend"],
                                                            "§fลดความเสียหายคริติคอล §e" . $data1["armor"][$chestplate]["defendcri"],
                                                            "§fลดการติดไฟ §e" . $data1["armor"][$chestplate]["defendfire"],
                                                            "§fป้องกันการกระเด็น §e" . $data1["armor"][$chestplate]["defendknock"]
                                                        ];
                                                        if ($helmets->getLore() == $lorehelmet and $chestplates->getLore() == $lorechestplate) {

                                                            $rr = $data1["armor"][$helmetname]["defend"] + $data1["armor"][$chestplate]["defend"];
                                                            $damage = $data["sword"][$name]["damage"] / $rr;
                                                            $this->getDamage($event, $entity, $damage);
                                                            $kk = $data1["armor"][$helmetname]["defendknock"] + $data1["armor"][$chestplate]["defendknock"];
                                                            $knockback = $data["sword"][$name]["knockback"] / $kk;
                                                            $event->setKnockBack($knockback);
                                                            $cridefend = $data1["armor"][$helmetname]["defendcri"] + $data1["armor"][$chestplate]["defendcri"];
                                                            $crireal = $cridefend / $critical;
                                                            $damageee = $event->getBaseDamage();
                                                            $this->CriDamage($name, $crireal, $entity, $damageee, $damager);
                                                            $fires = $data1["armor"][$helmetname]["defendfire"] + $data1["armor"][$chestplate]["defendfire"];
                                                            $fire = 10 / $fires;
                                                            $this->Fire($name, $entity, $fire);
                                                            $this->KnockbackParticle($damager, $entity, $knockback);
                                                            $this->DamageParticle($damager, $entity, $damage);
                                                            $damager->sendMessage("Entity has 1 Helmet and 1 Chestplate");
                                                        }
                                                    } else {
                                                        if (!$leggings->getId() == 0) {
                                                            $loreleggings = [
                                                                "",
                                                                "§fป้องกันดาเมจ §e" . $data1["armor"][$legging]["defend"],
                                                                "§fลดความเสียหายคริติคอล §e" . $data1["armor"][$legging]["defendcri"],
                                                                "§fลดการติดไฟ §e" . $data1["armor"][$legging]["defendfire"],
                                                                "§fป้องกันการกระเด็น §e" . $data1["armor"][$legging]["defendknock"]
                                                            ];
                                                            if ($helmets->getLore() == $lorehelmet and $leggings->getLore() == $loreleggings) {
                                                                $rr = $data1["armor"][$helmetname]["defend"] + $data1["armor"][$legging]["defend"];
                                                                $damage = $data["sword"][$name]["damage"] / $rr;
                                                                $this->getDamage($event, $entity, $damage);
                                                                $kk = $data1["armor"][$helmetname]["defendknock"] + $data1["armor"][$legging]["defendknock"];
                                                                $knockback = $data["sword"][$name]["knockback"] / $kk;
                                                                $event->setKnockBack($knockback);
                                                                $cridefend = $data1["armor"][$helmetname]["defendcri"] + $data1["armor"][$legging]["defendcri"];
                                                                $crireal = $cridefend / $critical;
                                                                $damageee = $event->getBaseDamage();
                                                                $this->CriDamage($name, $crireal, $entity, $damageee, $damager);
                                                                $fires = $data1["armor"][$helmetname]["defendfire"] + $data1["armor"][$legging]["defendfire"];
                                                                $fire = 10 / $fires;
                                                                $this->Fire($name, $entity, $fire);
                                                                $this->KnockbackParticle($damager, $entity, $knockback);
                                                                $this->DamageParticle($damager, $entity, $damage);
                                                                $damager->sendMessage("Entity has 1 Helmet and 1 Leggings");
                                                            }
                                                        } else {
                                                            if (!$boots->getId() == 0) {
                                                                $loreboots = [
                                                                    "",
                                                                    "§fป้องกันดาเมจ §e" . $data1["armor"][$boot]["defend"],
                                                                    "§fลดความเสียหายคริติคอล §e" . $data1["armor"][$boot]["defendcri"],
                                                                    "§fลดการติดไฟ §e" . $data1["armor"][$boot]["defendfire"],
                                                                    "§fป้องกันการกระเด็น §e" . $data1["armor"][$boot]["defendknock"]
                                                                ];
                                                                if ($helmets->getLore() == $lorehelmet and $boots->getLore() == $loreboots) {
                                                                    $rr = $data1["armor"][$helmetname]["defend"] + $data1["armor"][$boot]["defend"];
                                                                    $damage = $data["sword"][$name]["damage"] / $rr;
                                                                    $this->getDamage($event, $entity, $damage);
                                                                    $kk = $data1["armor"][$helmetname]["defendknock"] + $data1["armor"][$boot]["defendknock"];
                                                                    $knockback = $data["sword"][$name]["knockback"] / $kk;
                                                                    $event->setKnockBack($knockback);
                                                                    $cridefend = $data1["armor"][$helmetname]["defendcri"] + $data1["armor"][$boot]["defendcri"];
                                                                    $crireal = $cridefend / $critical;
                                                                    $damageee = $event->getBaseDamage();
                                                                    $this->CriDamage($name, $crireal, $entity, $damageee, $damager);
                                                                    $fires = $data1["armor"][$helmetname]["defendfire"] + $data1["armor"][$boot]["defendfire"];
                                                                    $fire = 10 / $fires;
                                                                    $this->Fire($name, $entity, $fire);
                                                                    $this->KnockbackParticle($damager, $entity, $knockback);
                                                                    $this->DamageParticle($damager, $entity, $damage);
                                                                    $damager->sendMessage("Entity has 1 Helmet and 1 Boots");
                                                                }
                                                            } else {
                                                                if ($helmets->getLore() == $lorehelmet) {
                                                                    $damage = $data["sword"][$name]["damage"] / $data1["armor"][$helmetname]["defend"];
                                                                    $this->getDamage($event, $entity, $damage);
                                                                    $knockback = $data["sword"][$name]["knockback"] / $data1["armor"][$helmetname]["defendknock"];
                                                                    $event->setKnockBack($knockback);
                                                                    $cridefend = $data1["armor"][$helmetname]["defendcri"];
                                                                    $crireal = $critical % $cridefend;
                                                                    $damageee = $event->getBaseDamage();
                                                                    $this->CriDamage($name, $crireal, $entity, $damageee, $damager);
                                                                    $fires = $data1["armor"][$helmetname]["defendfire"];
                                                                    $fire = $fires / 10;
                                                                    $this->Fire($name, $entity, $fire);
                                                                    $this->KnockbackParticle($damager, $entity, $knockback);
                                                                    $this->DamageParticle($damager, $entity, $damage);
                                                                    $damager->sendMessage("Entity has 1 Helmet");
                                                                }
                                                            }
                                                        }
                                                    }
                                                } else {
                                                    if (!$chestplates->getId() == 0) {
                                                        $lorechestplate = [
                                                            "",
                                                            "§fป้องกันดาเมจ §e" . $data1["armor"][$chestplate]["defend"],
                                                            "§fลดความเสียหายคริติคอล §e" . $data1["armor"][$chestplate]["defendcri"],
                                                            "§fลดการติดไฟ §e" . $data1["armor"][$chestplate]["defendfire"],
                                                            "§fป้องกันการกระเด็น §e" . $data1["armor"][$chestplate]["defendknock"]
                                                        ];
                                                        if (!$leggings->getId() == 0) {
                                                            $loreleggings = [
                                                                "",
                                                                "§fป้องกันดาเมจ §e" . $data1["armor"][$legging]["defend"],
                                                                "§fลดความเสียหายคริติคอล §e" . $data1["armor"][$legging]["defendcri"],
                                                                "§fลดการติดไฟ §e" . $data1["armor"][$legging]["defendfire"],
                                                                "§fป้องกันการกระเด็น §e" . $data1["armor"][$legging]["defendknock"]
                                                            ];
                                                            if ($chestplates->getLore() == $lorechestplate and $leggings->getLore() == $loreleggings) {
                                                                $rr = $data1["armor"][$chestplate]["defend"] + $data1["armor"][$legging]["defend"];
                                                                $damage = $data["sword"][$name]["damage"] / $rr;
                                                                $this->getDamage($event, $entity, $damage);
                                                                $kk = $data1["armor"][$chestplate]["defendknock"] + $data1["armor"][$legging]["defendknock"];
                                                                $knockback = $data["sword"][$name]["knockback"] / $kk;
                                                                $event->setKnockBack($knockback);
                                                                $cridefend = $data1["armor"][$chestplate]["defendcri"] + $data1["armor"][$legging]["defendcri"];
                                                                $crireal = $cridefend / $critical;
                                                                $damageee = $event->getBaseDamage();
                                                                $this->CriDamage($name, $crireal, $entity, $damageee, $damager);
                                                                $fires = $data1["armor"][$chestplate]["defendfire"] + $data1["armor"][$legging]["defendfire"];
                                                                $fire = 10 / $fires;
                                                                $this->Fire($name, $entity, $fire);
                                                                $this->KnockbackParticle($damager, $entity, $knockback);
                                                                $this->DamageParticle($damager, $entity, $damage);
                                                                $damager->sendMessage("Entity has 1 Chestplate and 1 Leggings");
                                                            }
                                                        } else {
                                                            if (!$boots->getId() == 0) {
                                                                $loreboots = [
                                                                    "",
                                                                    "§fป้องกันดาเมจ §e" . $data1["armor"][$boot]["defend"],
                                                                    "§fลดความเสียหายคริติคอล §e" . $data1["armor"][$boot]["defendcri"],
                                                                    "§fลดการติดไฟ §e" . $data1["armor"][$boot]["defendfire"],
                                                                    "§fป้องกันการกระเด็น §e" . $data1["armor"][$boot]["defendknock"]
                                                                ];
                                                                if ($chestplates->getLore() == $lorechestplate && $boots->getLore() == $loreboots) {
                                                                    $rr = $data1["armor"][$chestplate]["defend"] + $data1["armor"][$boot]["defend"];
                                                                    $damage = $data["sword"][$name]["damage"] / $rr;
                                                                    $this->getDamage($event, $entity, $damage);
                                                                    $kk = $data1["armor"][$chestplate]["defendknock"] + $data1["armor"][$boot]["defendknock"];
                                                                    $knockback = $data["sword"][$name]["knockback"] / $kk;
                                                                    $event->setKnockBack($knockback);
                                                                    $cridefend = $data1["armor"][$chestplate]["defendcri"] + $data1["armor"][$boot]["defendcri"];
                                                                    $crireal = $cridefend / $critical;
                                                                    $damageee = $event->getBaseDamage();
                                                                    $this->CriDamage($name, $crireal, $entity, $damageee, $damager);
                                                                    $fires = $data1["armor"][$chestplate]["defendfire"] + $data1["armor"][$boot]["defendfire"];
                                                                    $fire = 10 / $fires;
                                                                    $this->Fire($name, $entity, $fire);
                                                                    $this->KnockbackParticle($damager, $entity, $knockback);
                                                                    $this->DamageParticle($damager, $entity, $damage);
                                                                    $damager->sendMessage("Entity has 1 Chestplate and 1 Boots");
                                                                }
                                                            } else {
                                                                if ($chestplates->getLore() == $lorechestplate) {
                                                                    $damage = $data["sword"][$name]["damage"] / $data1["armor"][$chestplate]["defend"];
                                                                    $this->getDamage($event, $entity, $damage);
                                                                    $knockback = $data["sword"][$name]["knockback"] / $data1["armor"][$chestplate]["defendknock"];
                                                                    $event->setKnockBack($knockback);
                                                                    $cridefend = $data1["armor"][$chestplate]["defendcri"];
                                                                    $crireal = $critical % $cridefend;
                                                                    $damageee = $event->getBaseDamage();
                                                                    $this->CriDamage($name, $crireal, $entity, $damageee, $damager);
                                                                    $fires = $data1["armor"][$chestplate]["defendfire"];
                                                                    $fire = 10 / $fires;
                                                                    $this->Fire($name, $entity, $fire);
                                                                    $this->KnockbackParticle($damager, $entity, $knockback);
                                                                    $this->DamageParticle($damager, $entity, $damage);
                                                                    $damager->sendMessage("Entity has 1 Chestplate");
                                                                }
                                                            }
                                                        }
                                                    } else {
                                                        if (!$leggings->getId() == 0) {
                                                            $loreleggings = [
                                                                "",
                                                                "§fป้องกันดาเมจ §e" . $data1["armor"][$legging]["defend"],
                                                                "§fลดความเสียหายคริติคอล §e" . $data1["armor"][$legging]["defendcri"],
                                                                "§fลดการติดไฟ §e" . $data1["armor"][$legging]["defendfire"],
                                                                "§fป้องกันการกระเด็น §e" . $data1["armor"][$legging]["defendknock"]
                                                            ];
                                                            if (!$boots->getId() == 0) {
                                                                $loreboots = [
                                                                    "",
                                                                    "§fป้องกันดาเมจ §e" . $data1["armor"][$boot]["defend"],
                                                                    "§fลดความเสียหายคริติคอล §e" . $data1["armor"][$boot]["defendcri"],
                                                                    "§fลดการติดไฟ §e" . $data1["armor"][$boot]["defendfire"],
                                                                    "§fป้องกันการกระเด็น §e" . $data1["armor"][$boot]["defendknock"]
                                                                ];
                                                                if ($leggings->getLore() == $loreleggings && $boots->getLore() == $loreboots) {
                                                                    $rr = $data1["armor"][$legging]["defend"] + $data1["armor"][$boot]["defend"];
                                                                    $damage = $data["sword"][$name]["damage"] / $rr;
                                                                    $this->getDamage($event, $entity, $damage);
                                                                    $kk = $data1["armor"][$legging]["defendknock"] + $data1["armor"][$boot]["defendknock"];
                                                                    $knockback = $data["sword"][$name]["knockback"] / $kk;
                                                                    $event->setKnockBack($knockback);
                                                                    $cridefend = $data1["armor"][$legging]["defendcri"] + $data1["armor"][$boot]["defendcri"];
                                                                    $crireal = $cridefend / $critical;
                                                                    $damageee = $event->getBaseDamage();
                                                                    $this->CriDamage($name, $crireal, $entity, $damageee, $damager);
                                                                    $fires = $data1["armor"][$legging]["defendfire"] + $data1["armor"][$boot]["defendfire"];
                                                                    $fire = 10 / $fires;
                                                                    $this->Fire($name, $entity, $fire);
                                                                    $this->KnockbackParticle($damager, $entity, $knockback);
                                                                    $this->DamageParticle($damager, $entity, $damage);
                                                                    $damager->sendMessage("Entity has 1 Leggings and 1 Boots");
                                                                }
                                                            } else {
                                                                if ($leggings->getLore() == $loreleggings) {
                                                                    $damage = $data["sword"][$name]["damage"] / $data1["armor"][$legging]["defend"];
                                                                    $this->getDamage($event, $entity, $damage);
                                                                    $knockback = $data["sword"][$name]["knockback"] / $data1["armor"][$legging]["defendknock"];
                                                                    $event->setKnockBack($knockback);
                                                                    $cridefend = $data1["armor"][$legging]["defendcri"];
                                                                    $crireal = $critical % $cridefend;
                                                                    $damageee = $event->getBaseDamage();
                                                                    $this->CriDamage($name, $crireal, $entity, $damageee, $damager);
                                                                    $fires = $data1["armor"][$legging]["defendfire"];
                                                                    $fire = 10 / $fires;
                                                                    $this->Fire($name, $entity, $fire);
                                                                    $this->KnockbackParticle($damager, $entity, $knockback);
                                                                    $this->DamageParticle($damager, $entity, $damage);
                                                                    $damager->sendMessage("Entity has 1 Leggings");
                                                                }
                                                            }
                                                        } else {
                                                            if (!$boots->getId() == 0) {
                                                                $loreboots = [
                                                                    "",
                                                                    "§fป้องกันดาเมจ §e" . $data1["armor"][$boot]["defend"],
                                                                    "§fลดความเสียหายคริติคอล §e" . $data1["armor"][$boot]["defendcri"],
                                                                    "§fลดการติดไฟ §e" . $data1["armor"][$boot]["defendfire"],
                                                                    "§fป้องกันการกระเด็น §e" . $data1["armor"][$boot]["defendknock"]
                                                                ];
                                                                if ($boots->getLore() == $loreboots) {
                                                                    $damage = $data["sword"][$name]["damage"] / $data1["armor"][$boot]["defend"];
                                                                    $this->getDamage($event, $entity, $damage);
                                                                    $knockback = $data["sword"][$name]["knockback"] / $data1["armor"][$boot]["defendknock"];
                                                                    $event->setKnockBack($knockback);
                                                                    $cridefend = $data1["armor"][$boot]["defendcri"];
                                                                    $crireal = $cridefend % $critical;
                                                                    $damageee = $event->getBaseDamage();
                                                                    $this->CriDamage($name, $crireal, $entity, $damageee, $damager);
                                                                    $fires = $data1["armor"][$boot]["defendfire"];
                                                                    $fire = 10 / $fires;
                                                                    $this->Fire($name, $entity, $fire);
                                                                    $this->KnockbackParticle($damager, $entity, $knockback);
                                                                    $this->DamageParticle($damager, $entity, $damage);
                                                                    $damager->sendMessage("Entity has 1 Boots");
                                                                }
                                                            } else {
                                                                $this->getDamage($event, $entity, $data["sword"][$name]["damage"]);
                                                                $event->setKnockBack($data["sword"][$name]["knockback"]);
                                                                $crireal = $data["sword"][$name]["cridamage"];
                                                                $damageee = $event->getBaseDamage();
                                                                $this->CriDamage($name, $crireal, $entity, $damageee, $damager);
                                                                $fire = 10;
                                                                $this->Fire($name, $entity, $fire);
                                                                $this->KnockbackParticle($damager, $entity, $data["sword"][$name]["knockback"]);
                                                                $this->DamageParticle($damager, $entity, $data["sword"][$name]["damage"]);
                                                                $damager->sendTip("Entity hasn't armor");
                                                                if ($data["sword"][$name]["slow"] == "เปิด") {
                                                                    $effect = new EffectInstance(Effect::getEffect(2), 20 * $data["sword"][$name]["slowtime"], 2);
                                                                    $entity->addEffect($effect);
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            } else {
                                $this->getDamage($event, $entity, $data["sword"][$name]["damage"]);
                                $event->setKnockBack($data["sword"][$name]["knockback"]);
                                $crireal = $data["sword"][$name]["cridamage"];
                                $damageee = $event->getBaseDamage();
                                $this->CriDamage($name, $crireal, $entity, $damageee, $damager);
                                $fire = 10;
                                $this->Fire($name, $entity, $fire);
                                $this->KnockbackParticle($damager, $entity, $data["sword"][$name]["knockback"]);
                                $this->DamageParticle($damager, $entity, $data["sword"][$name]["damage"]);
                                $damager->sendTip("Entity hasn't armor");
                                if ($data["sword"][$name]["slow"] == "เปิด") {
                                    $effect = new EffectInstance(Effect::getEffect(2), 20 * $data["sword"][$name]["slowtime"], 2);
                                    $entity->addEffect($effect);
                                }
                            }
                        } else {
                            $this->getDamage($event, $entity, $data["sword"][$name]["damage"]);
                            $event->setKnockBack($data["sword"][$name]["knockback"]);
                            $crireal = $data["sword"][$name]["cridamage"];
                            $damageee = $event->getBaseDamage();
                            $this->CriDamage($name, $crireal, $entity, $damageee, $damager);
                            $fire = 10;
                            $this->Fire($name, $entity, $fire);
                            $this->KnockbackParticle($damager, $entity, $data["sword"][$name]["knockback"]);
                            $this->DamageParticle($damager, $entity, $data["sword"][$name]["damage"]);
                            $damager->sendTip("Entity hasn't armor");
                            if ($data["sword"][$name]["slow"] == "เปิด") {
                                $effect = new EffectInstance(Effect::getEffect(2), 20 * $data["sword"][$name]["slowtime"], 2);
                                $entity->addEffect($effect);
                            }
                        }
                    }
                }
            }
        }
    }

    public function getDamage($event, $entity, int $basedamage)
    {
        $event->setModifier($basedamage, 14);
        if ($event->canBeReducedByArmor()) {
            $event->setModifier(-$event->getFinalDamage() * $entity->getArmorPoints() * 0.04, EntityDamageEvent::MODIFIER_ARMOR);
        }
        $cause = $event->getCause();
        if ($entity->hasEffect(Effect::DAMAGE_RESISTANCE) and $cause !== EntityDamageEvent::CAUSE_VOID and $cause !== EntityDamageEvent::CAUSE_SUICIDE) {
            $event->setModifier(-$event->getFinalDamage() * min(1, 0.2 * $entity->getEffect(Effect::DAMAGE_RESISTANCE)->getEffectLevel()), EntityDamageEvent::MODIFIER_RESISTANCE);

        }
        $totalEpf = 0;
        foreach ($entity->getArmorInventory()->getContents() as $item) {
            if ($item instanceof Armor) {
                $totalEpf += $item->getEnchantmentProtectionFactor($event);
            }
        }
        $event->setModifier(-$event->getFinalDamage() * min(ceil(min($totalEpf, 25) * (mt_rand(50, 100) / 100)), 20) * 0.04, EntityDamageEvent::MODIFIER_ARMOR_ENCHANTMENTS);
        $event->setModifier(-min($entity->getAbsorption(), $event->getFinalDamage()), EntityDamageEvent::MODIFIER_ABSORPTION);
    }

    public function KnockbackParticle($attacker, $damager, $knockback): void{
        $pk = new AddActorPacket();
        $eid = Entity::$entityCount++;
        $pk->entityRuntimeId = $eid;
        $pk->type = AddActorPacket::LEGACY_ID_MAP_BC[EntityIds::ITEM];
        $pk->position = $damager->asVector3()->add(mt_rand(-6, 6) * 0.1, $damager->getEyeHeight() / 2 + mt_rand(-3, 3) * 0.1, mt_rand(-6, 6) * 0.1);
        $pk->motion = new Vector3(0, 0.15, 0);
        $flags = 0;
        $flags |= 1 << Entity::DATA_FLAG_CAN_SHOW_NAMETAG;
        $text = ($damager instanceof Player) ? "§aK§cn§eo§bc§dk§5b§6a§6c§5k §f* §c{$knockback}" : "§aK§cn§eo§bc§dk§5b§6a§6c§5k §f* §c{$knockback}";
        $pk->metadata = [
            Entity::DATA_FLAGS => [Entity::DATA_TYPE_LONG, $flags],
            Entity::DATA_NAMETAG => [Entity::DATA_TYPE_STRING, $text],
            Entity::DATA_ALWAYS_SHOW_NAMETAG => [Entity::DATA_TYPE_BYTE, 1],
            Entity::DATA_SCALE => [Entity::DATA_TYPE_FLOAT, 1]
        ];
        $attacker->dataPacket($pk);
        $this->plugin->getScheduler()->scheduleDelayedTask(new CallbackTask([$this, 'removeParticle'], [$attacker, $eid]), 8);
    }

    public function DamageParticle($attacker, $damager, $damageee): void{
        $pk = new AddActorPacket();
        $eid = Entity::$entityCount++;
        $pk->entityRuntimeId = $eid;
        $pk->type = AddActorPacket::LEGACY_ID_MAP_BC[EntityIds::ITEM];
        $pk->position = $damager->asVector3()->add(mt_rand(-10, 10) * 0.1, $damager->getEyeHeight() / 2 + mt_rand(-7, 7) * 0.1, mt_rand(-10, 10) * 0.1);
        $pk->motion = new Vector3(0, 0.15, 0);
        $flags = 0;
        $flags |= 1 << Entity::DATA_FLAG_CAN_SHOW_NAMETAG;
        $text = ($damager instanceof Player) ? "§cD§fa§cm§ca§fg§ce §6* §c{$damageee}" : "§cD§fa§cm§ca§fg§ce §6* §c{$damageee}";
        $pk->metadata = [
            Entity::DATA_FLAGS => [Entity::DATA_TYPE_LONG, $flags],
            Entity::DATA_NAMETAG => [Entity::DATA_TYPE_STRING, $text],
            Entity::DATA_ALWAYS_SHOW_NAMETAG => [Entity::DATA_TYPE_BYTE, 1],
            Entity::DATA_SCALE => [Entity::DATA_TYPE_FLOAT, 1]
        ];
        $attacker->dataPacket($pk);
        $this->plugin->getScheduler()->scheduleDelayedTask(new CallbackTask([$this, 'removeParticle'], [$attacker, $eid]), 8);
    }

    public function CriticalDamage($attacker, $damager, $damageee): void{
        $pk = new AddActorPacket();
        $eid = Entity::$entityCount++;
        $pk->entityRuntimeId = $eid;
        $pk->type = AddActorPacket::LEGACY_ID_MAP_BC[EntityIds::ITEM];
        $pk->position = $damager->asVector3()->add(mt_rand(-8, 8) * 0.1, $damager->getEyeHeight() / 2 + mt_rand(-5, 5) * 0.1, mt_rand(-8, 8) * 0.1);
        $pk->motion = new Vector3(0, 0.15, 0);
        $flags = 0;
        $flags |= 1 << Entity::DATA_FLAG_CAN_SHOW_NAMETAG;
        $text = ($damager instanceof Player) ? "§4C§fr§4i§ft§4i§fc§4a§fl §6* §c{$damageee}" : "§4C§fr§4i§ft§4i§fc§4a§fl §6* §c{$damageee}";
        $pk->metadata = [
            Entity::DATA_FLAGS => [Entity::DATA_TYPE_LONG, $flags],
            Entity::DATA_NAMETAG => [Entity::DATA_TYPE_STRING, $text],
            Entity::DATA_ALWAYS_SHOW_NAMETAG => [Entity::DATA_TYPE_BYTE, 1],
            Entity::DATA_SCALE => [Entity::DATA_TYPE_FLOAT, 1]
        ];
        $attacker->dataPacket($pk);
        $this->plugin->getScheduler()->scheduleDelayedTask(new CallbackTask([$this, 'removeParticle'], [$attacker, $eid]), 8);
    }

    public function removeParticle($entity, int $eid): void{
        $pk = new RemoveActorPacket();
        $pk->entityUniqueId = $eid;
        $entity->dataPacket($pk);
    }

    public function Fire(string $name, $entity, $fire){
        $data = $this->plugin->getData()->getAll();
        if($data["sword"][$name]["fire"] == "เปิด"){
            $entity->setOnFire($fire);
        }
    }

    /*
    public function DefentHelmet(int $critical, $entity){
        $data1 = $this->plugin->getDataArmor()->getALl();
        $helmetname = $entity->getArmorInventory()->getHelmet()->getCustomName();
        $lorehelmet = [
            "",
            "§fป้องกันดาเมจ §e" . $data1["armor"][$helmetname]["defend"],
            "§fลดความเสียหายคริติคอล §e" . $data1["armor"][$helmetname]["defendcri"],
            "§fลดการติดไฟ §e" . $data1["armor"][$helmetname]["defendfire"],
            "§fความเร็วในการวิ่งเมื่อใส่เกราะ §e" . $data1["armor"][$helmetname]["speed"],
            "§fป้องกันการกระเด็น §e" . $data1["armor"][$helmetname]["defendknock"]
        ];

    }*/

    public function CriDamage(string $name, int $critical, $entity, $damageee, $damager)
    {
        $data = $this->plugin->getData()->getAll();
        $cri = $data["sword"][$name]["cri"];
        if ($cri >= 1) {
            $rand = rand(1, 5);
            switch ($rand) {
                case 1:
                    $damage = $critical;$entity->setHealth($entity->getHealth() - $damage);
                    $this->CriticalDamage($damager, $entity, $damage);
                    break;
                case 2:
                    break;
                case 3:
                    break;
                case 4:
                    break;
                case 5:
                    break;
            }
            if ($cri >= 26) {
                $rand = rand(1, 5);
                switch ($rand) {
                    case 1:
                        $damage = $critical;$entity->setHealth($entity->getHealth() - $damage);
                        $this->CriticalDamage($damager, $entity, $damage);
                        break;
                    case 2:
                        $damage = $critical;$entity->setHealth($entity->getHealth() - $damage);
                        $this->CriticalDamage($damager, $entity, $damage);
                        break;
                    case 3:
                        break;
                    case 4:
                        break;
                    case 5:
                        break;
                }
                if ($cri >= 51) {
                    $rand = rand(1, 5);
                    switch ($rand) {
                        case 1:
                            $damage = $critical;$entity->setHealth($entity->getHealth() - $damage);
                            $this->CriticalDamage($damager, $entity, $damage);
                            break;
                        case 2:
                            $damage = $critical;$entity->setHealth($entity->getHealth() - $damage);
                            $this->CriticalDamage($damager, $entity, $damage);
                            break;
                        case 3:
                            $damage = $critical;$entity->setHealth($entity->getHealth() - $damage);
                            $this->CriticalDamage($damager, $entity, $damage);
                            break;
                        case 4:
                            break;
                        case 5:
                            break;
                    }
                    if ($cri >= 76) {
                        $rand = rand(1, 5);
                        switch ($rand) {
                            case 1:
                                $damage = $critical;$entity->setHealth($entity->getHealth() - $damage);
                                $this->CriticalDamage($damager, $entity, $damage);
                                break;
                            case 2:
                                $damage = $critical;$entity->setHealth($entity->getHealth() - $damage);
                                $this->CriticalDamage($damager, $entity, $damage);
                                break;
                            case 3:
                                $damage = $critical;$entity->setHealth($entity->getHealth() - $damage);
                                $this->CriticalDamage($damager, $entity, $damage);
                                break;
                            case 4:
                                $damage = $critical;$entity->setHealth($entity->getHealth() - $damage);
                                $this->CriticalDamage($damager, $entity, $damage);
                                break;
                            case 5:
                                break;
                        }
                        if ($cri >= 100) {
                            $rand = rand(1, 5);
                            switch ($rand) {
                                case 1:
                                    $damage = $critical;
                                    $entity->setHealth($entity->getHealth() - $damage);
                                    $this->CriticalDamage($damager, $entity, $damage);
                                    break;
                                case 2:
                                    $damage = $critical;
                                    $entity->setHealth($entity->getHealth() - $damage);
                                    $this->CriticalDamage($damager, $entity, $damage);
                                    break;
                                case 3:
                                    $damage = $critical;
                                    $entity->setHealth($entity->getHealth() - $damage);
                                    $this->CriticalDamage($damager, $entity, $damage);
                                    break;
                                case 4:
                                    $damage = $critical;
                                    $entity->setHealth($entity->getHealth() - $damage);
                                    $this->CriticalDamage($damager, $entity, $damage);
                                    break;
                                case 5:
                                    $damage = $critical;
                                    $entity->setHealth($entity->getHealth() - $damage);
                                    $this->CriticalDamage($damager, $entity, $damage);
                                    break;
                            }
                        }
                    }
                }
            }
        } else {
            $damage = 0;
            $entity->setHealth($entity->getHealth() - $damage);
        }
    }
}