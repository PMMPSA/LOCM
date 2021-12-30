<?php

namespace IronGen;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\{Command, CommandSender};
use pocketmine\math\Vector3;
use pocketmine\block\Block;
use pocketmine\event\Listener;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Item;
use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat as TF;
use pocketmine\block\IronOre;
use pocketmine\block\Cobblestone;
use pocketmine\block\Glowingobsidian;
use pocketmine\block\DiamondOre;
use pocketmine\block\EmeraldOre;
use pocketmine\block\GoldOre;
use pocketmine\block\CoalOre;
use pocketmine\block\LapisOre;
use pocketmine\block\RedstoneOre;
use pocketmine\block\Water;
use pocketmine\block\Lava;

class Main extends PluginBase implements Listener {
  
  private $unicname;
  private $blockarr;
  public $blockVarr;
  
  public function onEnable() {
    $this->getServer()->getPluginManager()->registerEvents($this, $this);
    $this->blockarr = [];
    $this->blockVarr = [];
    $this->getScheduler()->scheduleRepeatingTask(new PlaceTask($this, json_encode($this->blockVarr)), 30);
  }
  
  public function onDisable() {
    $this->getLogger()->info("Plugin has been succesfully disable");
  }
  
  public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
  	    if(!$sender->hasPermission("oregen.command")){
            $sender->sendMessage(TF::RED . "You do not have permission to use this command");

            return false;
        }
        if(empty($args)){
            $sender->sendMessage(TF::RED . "Use '/ore <player> [amount]'");

            return false;
        }
        if(($target = $this->getServer()->getPlayer($args[0])) === null){
            $sender->sendMessage(TF::RED . "Player not found");

            return false;
        }
        $amount = 1;
        if(isset($args[1])){
            if((int) $args[1] < 1){
                $sender->sendMessage(TF::RED . "Amount must be more than 0");

                return false;
            }
            $amount = (int) $args[1];
        }
        $item = Item::get(245, 0, $amount);
        $item->setCustomName("ORE Generator");
        $target->getInventory()->addItem($item);
        $target->sendMessage("§l§fBạn vừa nhận được §ex" . $amount . " §fOre Generator");
        if($target->getName() !== $sender->getName()){
            $sender->sendMessage("You have given " . $target->getName() . " " . $amount . "x Ore Generator");
        }
    return true;
  }
  
    public function onTouch(PlayerInteractEvent $ev){
      $player = $ev->getPlayer();
      $item = $player->getInventory()->getItemInHand();
      if($item->getId() == 245){
        $this->blockarr[$player->getName()] = true;
      }
    }


  public function onPlace(BlockPlaceEvent $event){
    
    $player = $event->getPlayer();
    $item = $event->getItem();
    $block = $event->getBlock();
   
    if($item->getId() == 245 && in_array($player->getName(), $this->blockarr)) {
      if($block->getLevel()->getName() !== "skyblock"){
        $event->setCancelled(true);
        return;
      }
      unset($this->blockarr[$player->getName()]);
      $level =  $block->getLevel();
      $pos = new Vector3($block->getX(), $block->getY() + 1, $block->getZ());
      if($level->getBlock($pos)->getId() !== 0){
        $level->setBlock($pos, Block::get(4));        
      }
      $this->blockVarr[$block->getX()] = ["pos" => $pos, "level" => $level];
    }
  }

  public function onBreak(BlockBreakEvent $event){
    $player = $event->getPlayer();
    $block = $event->getBlock();
    if($block->getId() == 245) {
      unset($this->blockVarr[$block->getX()]);
    }
  }
}

class PlaceTask extends Task{

    private $plugin;
    private $array;

    public function __construct($plugin, $array){
        $this->plugin  = $plugin;
        $this->array = json_decode($array, true);
    }

    public function onRun($tick){
        if(!count($this->plugin->blockVarr) <= 0){
            foreach($this->plugin->blockVarr as $bl){
              $level = $bl["level"];
              $pos = new Vector3($bl["pos"]->getX(), $bl["pos"]->getY(), $bl["pos"]->getZ());
              if($level->getBlock($pos)->getId() != 0) return;
                $id = mt_rand(1, 85);
                switch ($id) {
                    case 2;
                        $block = new IronOre();
                        break;
                    case 4;
                        $block = new GoldOre();
                        break;
                    case 6;
                        $block = new EmeraldOre();
                        break;
                    case 8;
                        $block = new CoalOre();
                        break;
                    case 10;
                        $block = new RedstoneOre();
                        break;
                    case 12;
                        $block = new DiamondOre();
                        break;
                    case 9;
                        $block = new LapisOre();
                        break;
                    default:
                        $block = new Cobblestone();
                }
              $level->setBlock($pos, $block);
            }
        }
    }
}
 
