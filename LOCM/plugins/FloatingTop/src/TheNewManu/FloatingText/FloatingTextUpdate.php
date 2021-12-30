<?php
declare(strict_types=1);

namespace TheNewManu\FloatingText;

use pocketmine\math\Vector3;
use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat as TF;
use pocketmine\level\particle\FloatingTextParticle;

class FloatingTextUpdate extends Task {

    /** @var Main */
    private $plugin;

    /**
     * @param Main $plugin
     */
    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
    }

    /**
     * @param int $currentTick
     * @return void
     */
    public function onRun(int $currentTick): void {
        //foreach($this->getPlugin()->getServer()->getOnlinePlayers() as $player) {
            foreach($this->getPlugin()->floatingTexts as $id => $ft) {
                $text = $this->getPlugin()->getFloatingTexts()->getNested("$id.text");
                $level = $this->getPlugin()->getServer()->getLevelByName($this->getPlugin()->getFloatingTexts()->getNested("$id.level"));
                if($level !== null){
                    $ft->setText($this->getPlugin()->replaceProcess(null, $text));                    
                    $level->addParticle($ft);
                    $handler = $this->getHandler()->setNextRun(15*20*60);
                    //Main::$task_tick = 20*60*15;                    
                }
                //if($player->hasPermission("ft.command.admin")) {
                   // $ft->setText($this->getPlugin()->replaceProcess($player, $text) . TF::EOL . TF::YELLOW . "ID: " . $id);
               // }else{

                //}
            }
        //}
    }
    
    /**
     * @return Main
     */
    public function getPlugin(): Main {
        return $this->plugin;
    }
}
