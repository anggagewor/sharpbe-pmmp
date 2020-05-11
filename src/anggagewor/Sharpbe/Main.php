<?php

declare(strict_types=1);

namespace anggagewor\Sharpbe;

use anggagewor\Sharpbe\Contracts\Chat;
use anggagewor\Sharpbe\Contracts\LoginLog;
use anggagewor\Sharpbe\Contracts\PermissionList;
use anggagewor\Sharpbe\Contracts\Player;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\LoginPacket;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class Main extends PluginBase implements Listener
{

    public function onJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();
        $dbPlayer = new Player($this);
        $dbPlayer->save($player);
    }
    public function onEnable()
    {
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onLoad()
    {
		foreach($this->getServer()->getPluginManager()->getPermissions() as $permission){
			$permissionList = new PermissionList($this);
			$permissionList->save($permission->getName());
		}
    }
    public function onDisable()
    {
    }
    public function onPlayerChat(PlayerChatEvent $event)
    {
        if ($event->isCancelled()) {
            return;
        }
        $player = $event->getPlayer();
        $message = $event->getMessage();
        $chat = new Chat($this);
        $chat->save($player->getName(), $message);
    }

    public function onReceive(DataPacketReceiveEvent $e)
    {
        $packages = $e->getPacket();
        if ($packages instanceof LoginPacket) {
            $login_log = new LoginLog($this);
            $login_log->save($packages);
        }
    }
    public function onLeave(PlayerQuitEvent $event)
    {
        $player = $event->getPlayer()->getName();
        $dbPlayer = new Player($this);
        $dbPlayer->offline($event->getPlayer());
    }
	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args) : bool{
		print_r(strtolower($cmd->getName()));
		return true;
	}
}
