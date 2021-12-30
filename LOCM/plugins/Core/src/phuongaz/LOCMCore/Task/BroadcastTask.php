<?php

namespace phuongaz\LOCMCore\Task;

use pocketmine\Server;
use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat as C;
use phuongaz\LOCMCore\Loader;
class BroadcastTask extends Task{

    public function onRun(int $currentTick) : void{
        /** @var array $input */
        $input = [
            "§l§f✉§6 Bỏ phiếu ủng hộ máy chủ tại:§e soon",
            "§l§f Đống góp ủng hộ máy chủ bằng lệnh §e/lcoin",
            "§l§c Những vấn đề §eHack, Cheat, Bug, Lỗi,...§c báo ngay cho admin để có hướng giải quyết"
        ];
        $details = array_rand($input);
        Loader::broadcast(C::GRAY . $input[$details]);
    }
}
