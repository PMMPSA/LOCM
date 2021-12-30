<?php

namespace phuongaz\LOCMCore\Task;

use pocketmine\scheduler\Task;
use pocketmine\Server;
use phuongaz\LOCMCore\Loader;
class ScorebroadTask extends Task
{
    public function onRun(Int $currentTick)
    {
        foreach(Server::getInstance()->getOnlinePlayers() as $p) {
            if(Loader::getInstance()->isDev($p)) {
                Loader::getInstance()->getScoreManager()->addScore($p, "Developer");
            }else{
            	Loader::getInstance()->getScoreManager()->addScore($p, "Member");
            }
        }
    }
}