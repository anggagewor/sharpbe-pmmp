<?php

namespace anggagewor\Sharpbe\Contracts;

use anggagewor\Sharpbe\Main;
use anggagewor\Sharpbe\Support\Db;
use pocketmine\Player as User;

class Player
{
    protected $db;

    /**
     * @var \anggagewor\Sharpbe\Main
     */
    protected $plugin;

    /**
     * Player constructor.
     *
     * @param \anggagewor\Sharpbe\Main $plugin
     */
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = $this->plugin->getConfig()->get("database", []);
        $this->db = new Db(
            $config['driver'],
            $config['host'],
            $config['user'],
            $config['password'],
            $config['name'],
            $config['charset'],
            $config['prefix']
        );
        $this->db->execute('CREATE TABLE IF NOT EXISTS player (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE,
            username VARCHAR(255) NOT NULL,
            ip_address VARCHAR(255) NULL,
            is_online TINYINT(1) NULL,
            is_banned TINYINT(1) NULL,
            coin TEXT NULL,
            login timestamp NULL ,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
    }


    public function save(User $player)
    {
        $this->plugin->getLogger()->info($player->getName().' Connected');
        $check = $this->db->select('player', ['username' => $player->getName()]);
        if (count($check) == 0) {
            $this->db->insert('player', [
                    'id' => null,
                    'username' => $player->getName(),
                    'login' => date('Y-m-d H:i:s'),
                    'ip_address' => $player->getAddress(),
                    'is_online' => 1,
					'is_banned' => 0,
					'coin' => 0
                ]);
            $this->plugin->getLogger()->info('New Player '.$player->getName());
        } else {
            $this->db->update('player', ['login' => date('Y-m-d H:i:s'),'ip_address' => $player->getAddress(),'is_online' => 1], ['id' => $check[0]['id']]);
            $this->plugin->getLogger()->info('Welcome Back '.$player->getName());
        }
    }

    public function offline(User $player)
    {
        $check = $this->db->select('player', ['username' => $player->getName()]);
        if (count($check) == 0) {
            $this->db->insert('player', [
                    'id' => null,
                    'username' => $player->getName(),
                    'login' => date('Y-m-d H:i:s'),
                    'ip_address' => $player->getAddress(),
                    'is_online' => 0
                ]);
            $this->plugin->getLogger()->info('New Player '.$player->getName());
        } else {
            $this->db->update('player', ['login' => date('Y-m-d H:i:s'),'ip_address' => $player->getAddress(),'is_online' => 0], ['id' => $check[0]['id']]);
            $this->plugin->getLogger()->info('Welcome Back '.$player->getName());
        }
    }
}
