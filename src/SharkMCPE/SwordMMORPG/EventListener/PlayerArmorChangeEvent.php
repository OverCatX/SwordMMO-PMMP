<?php


namespace SharkMCPE\SwordMMORPG\EventListener;


use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\event\entity\EntityArmorChangeEvent;
use pocketmine\event\Listener;
use SharkMCPE\SwordMMORPG\SwordLoader;

class PlayerArmorChangeEvent implements Listener
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

    public function onChangeArmor(EntityArmorChangeEvent $event)
    {
        $player = $event->getEntity();
        $data1 = $this->plugin->getDataArmor()->getAll();
        $newitem = $event->getNewItem();
        $olditem = $event->getOldItem();
        $helmet = $newitem->getCustomName();
        $chestplate = $newitem->getCustomName();
        $legging = $newitem->getCustomName();
        $boot = $newitem->getCustomName();
        if (!empty($data1["armor"][$helmet]) || !empty($data1["armor"][$chestplate]) || !empty($data1["armor"][$legging]) || !empty($data1["armor"][$boot])) {
            $lorehelmet = [
                "",
                "§fป้องกันดาเมจ §e" . $data1["armor"][$helmet]["defend"],
                "§fลดความเสียหายคริติคอล §e" . $data1["armor"][$helmet]["defendcri"],
                "§fลดการติดไฟ §e" . $data1["armor"][$helmet]["defendfire"],
                "§fความเร็วในการวิ่งเมื่อใส่เกราะ §e" . $data1["armor"][$helmet]["speed"],
                "§fป้องกันการกระเด็น §e" . $data1["armor"][$helmet]["defendknock"]
            ];
            $lorechestplate = [
                "",
                "§fป้องกันดาเมจ §e" . $data1["armor"][$chestplate]["defend"],
                "§fลดความเสียหายคริติคอล §e" . $data1["armor"][$chestplate]["defendcri"],
                "§fลดการติดไฟ §e" . $data1["armor"][$chestplate]["defendfire"],
                "§fความเร็วในการวิ่งเมื่อใส่เกราะ §e" . $data1["armor"][$chestplate]["speed"],
                "§fป้องกันการกระเด็น §e" . $data1["armor"][$chestplate]["defendknock"]
            ];
            $loreleggings = [
                "",
                "§fป้องกันดาเมจ §e" . $data1["armor"][$legging]["defend"],
                "§fลดความเสียหายคริติคอล §e" . $data1["armor"][$legging]["defendcri"],
                "§fลดการติดไฟ §e" . $data1["armor"][$legging]["defendfire"],
                "§fความเร็วในการวิ่งเมื่อใส่เกราะ §e" . $data1["armor"][$legging]["speed"],
                "§fป้องกันการกระเด็น §e" . $data1["armor"][$legging]["defendknock"]
            ];
            $loreboots = [
                "",
                "§fป้องกันดาเมจ §e" . $data1["armor"][$boot]["defend"],
                "§fลดความเสียหายคริติคอล §e" . $data1["armor"][$boot]["defendcri"],
                "§fลดการติดไฟ §e" . $data1["armor"][$boot]["defendfire"],
                "§fความเร็วในการวิ่งเมื่อใส่เกราะ §e" . $data1["armor"][$boot]["speed"],
                "§fป้องกันการกระเด็น §e" . $data1["armor"][$boot]["defendknock"]
            ];
            if(!$newitem->getId() == 0 or $newitem->getLore() == $lorehelmet or $newitem->getLore() == $lorechestplate or $newitem->getLore() == $loreleggings or $newitem->getLore() == $loreboots){
                $effect = new EffectInstance(Effect::getEffect(1), 20 * 999999, 3);
                $effect1 = new EffectInstance(Effect::getEffect(3), 20 * 999999, 3);
                $effect2 = new EffectInstance(Effect::getEffect(5), 20 * 999999, 3);
                $effect4 = new EffectInstance(Effect::getEffect(11), 20 * 999999, 3);
                $effect5 = new EffectInstance(Effect::getEffect(12), 20 * 999999, 3);
                $effect6 = new EffectInstance(Effect::getEffect(16), 20 * 999999, 3);
                $effect->setVisible(false);
                $player->addEffect($effect);
                $player->addEffect($effect1);
                $player->addEffect($effect2);
                $player->addEffect($effect4);
                $player->addEffect($effect5);
                $player->addEffect($effect6);
            }elseif(!$olditem->getId() == 0 or $olditem->getLore() == $lorehelmet or $olditem->getLore() == $lorechestplate or $olditem->getLore() == $loreleggings or $olditem->getLore() == $loreboots){
                $effect = new EffectInstance(Effect::getEffect(1), 20 * 15, 1);
                $player->removeEffect(1);
                $player->removeEffect((int)$effect);
                $player->removeEffect(3);
                $player->removeEffect(5);
                $player->removeEffect(11);
                $player->removeEffect(12);
                $player->removeEffect(16);
            }
        }
    }
}