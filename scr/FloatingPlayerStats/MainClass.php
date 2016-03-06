<?php

namespace FloatingPlayerStats;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat as F;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\math\Vector3;
use pocketmine\level\particle\FloatingTextParticle;
use pocketmine\utils\Config;

class MainClass extends PluginBase implements Listener {

	public $stats;
	private $config;

	public function onLoad() {
		$this->getLogger()->info(F::WHITE . "I've been loaded!");
	}

	public function onEnable() {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->stats = $this->getServer()->getPluginManager()->getPlugin("PlayerStats");
		$this->getLogger()->info(F::DARK_GREEN . "I've been enabled!");
		if(!is_dir($this->getDataFolder())) { 
            @mkdir($this->getDataFolder());
        }
        $this->config = new Config($this->getDataFolder()."config.yml",Config::YAML);

        $this->config->set("x", "118");
        $this->config->set("y", "71.5");
        $this->config->set("z", "100");
        $this->config->save();
    }

	public function spawnParticle(PlayerJoinEvent $e) {
		$this->settingParticle($e->getPlayer());
	}
	
	public function settingParticle($player) {
		$br = F::RESET. "\n";
		$text[0] = F::BLUE. F::BOLD. "  - Your statistic! -";
		$text[1] = F::DARK_GREEN. "Kills: " .F::YELLOW. $this->stats->getKills($player);
		$text[2] = F::DARK_GREEN. "Deaths: " .F::YELLOW. $this->stats->getDeaths($player);
		$text[3] = F::DARK_GREEN. "Places: " .F::YELLOW. $this->stats->getPlaces($player);
		$text[4] = F::DARK_GREEN. "Breaks: " .F::YELLOW. $this->stats->getBreaks($player);
		$text[5] = F::DARK_GREEN. "Joins: " .F::YELLOW. $this->stats->getJoins($player);
		$text[6] = F::DARK_GREEN. "Quits: " .F::YELLOW. $this->stats->getQuits($player);
		
		$level = $this->getServer()->getDefaultLevel();
		
		$title = F::RESET. $text[0]. F::RESET;
		$texter = $text[1]. $br. $text[2]. $br. $text[3]. $br. $text[4]. $br. $text[5]. $br. $text[6];

		$x = $this->config->get("x");
	        $y = $this->config->get("y");
	        $z = $this->config->get("z");

		$particle = new FloatingTextParticle(new Vector3($x, $y, $z), $texter, $title);
		$level->addParticle($particle, [$player]);
	}

	public function onDisable() {
		$this->getLogger()->info(F::DARK_RED . "I've been disabled!");
	}

}
