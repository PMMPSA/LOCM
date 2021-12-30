<?php

namespace phuongaz\LOCMCore\Task;

use pocketmine\Server;
use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat as C;
use phuongaz\LOCMCore\Loader;
use phuongaz\LOCMCore\CParticle;
use pocketmine\math\Vector3;

class RankTask extends Task{

    public function onRun(int $currentTick) : void{
    	Loader::getInstance()->getRankManager()->Handling();

        $level = Loader::getInstance()->getServer()->getLevelByName("newvid");
        $cpos = new Vector3(51.5, 25, -19.4);
        for($i = 5; $i > 0; $i -= 0.1){
            $radio = $i / 3;
            $x = $radio * cos(3 * $i);
            $y = 5 - $i;
            $z = $radio * sin(3 * $i);
            $level->addParticle(new CParticle(CParticle::BLUE_FLAME, $cpos->add($x, $y, $z)));
        }
        for($i = 5; $i > 0; $i -= 0.1){
            $radio = $i / 3;
            $x = -$radio * cos(3 * $i);
            $y = 5 - $i;
            $z = -$radio * sin(3 * $i);
            $level->addParticle(new CParticle(CParticle::BLUE_FLAME, $cpos->add($x, $y, $z)));
        }
    }
}
