<?php
declare(strict_types=1);

namespace TheNewManu\FloatingText;

use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\math\Vector3;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat as TF;
use pocketmine\level\particle\FloatingTextParticle;
use phuongaz\coin\Coin;

class Main extends PluginBase {

    /** @var array FloatingTexts[] */
    public $floatingTexts = [];

    public static $task_tick;

    public function onEnable() {
        $this->saveDefaultConfig();
        self::$task_tick = 20*60*2;
        $this->floatingText = new Config($this->getDataFolder() . "floating-text.yml", Config::YAML);
        $this->getServer()->getCommandMap()->register(strtolower($this->getName()), new FloatingTextCommand($this));
        $this->getScheduler()->scheduleRepeatingTask(new FloatingTextUpdate($this), self::$task_tick);

        $this->reloadFloatingText();
        self::$task_tick = 20*60*15;
    }
    
    public function reloadFloatingText() {
        foreach($this->getFloatingTexts()->getAll() as $id => $array) {
            $this->floatingTexts[$id] = new FloatingTextParticle(new Vector3($array["x"], $array["y"], $array["z"]), "");
        }
    }

    public static function getTickTask(){
        return self::$task_tick;
    }
    public static function setTickTask(int $tick){
        self::$task_tick = $tick;
    }
    
    /**
     * @param Player $player
     * @param string $string
     * @return string
     */
    public function replaceProcess(?Player $player, string $string): string {
        $string = str_replace("{line}", TF::EOL, $string);
/*        $string = str_replace("{player_name}", $player->getName(), $string);
        $string = str_replace("{player_health}", round($player->getHealth(), 1), $string);
        $string = str_replace("{player_max_health}", $player->getMaxHealth(), $string);
        $string = str_replace("{online_players}", count($this->getServer()->getOnlinePlayers()), $string);
        $string = str_replace("{online_max_players}", $this->getServer()->getMaxPlayers(), $string);*/
        /*FLOATING TOP*/
        $string = str_replace("{lcoin_top}", $this->getLcoinTop(), $string);
/*        $string = str_replace("{}", $this->getServer()->getMaxPlayers(), $string);
        $string = str_replace("{online_max_players}", $this->getServer()->getMaxPlayers(), $string);
        $string = str_replace("{online_max_players}", $this->getServer()->getMaxPlayers(), $string);*/
        //----
        return $string;
    }
    
    /**
     * @return Config
     */
    public function getFloatingTexts(): Config {
        return $this->floatingText;
    }
    
    /**
     * @return int
     */
    public function getUpdateTime(): int {
        return $this->getConfig()->get("update-time");
    }

    public function getLcoinTop() :string {
        $count = Coin::getAll();
        arsort($count);
        $count = array_slice($count, 0, 4);
        $i = 1;
        $text = "§l§eBXH LCOIN";
        $rank = ["0", "１","２","３","４","５"];
        foreach($count as $name => $money){
            $top = $rank[$i];
            $text .= "\n§l§6TOP§e " . $i . "§7 【§f" . $name . "§7】 §e" . $money. "§f LCoin\n";
            $i++;
        }
        return $text;
    }
}
