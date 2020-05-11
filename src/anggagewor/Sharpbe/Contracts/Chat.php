<?php

namespace anggagewor\Sharpbe\Contracts;

use anggagewor\Sharpbe\Main;
use anggagewor\Sharpbe\Support\Db;

class Chat
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
        $this->db->execute('CREATE TABLE IF NOT EXISTS chats (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE,
            username VARCHAR(255) NOT NULL,
            message text NULL,
            created_at timestamp NULL ,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
    }
    public function save($player, $chat)
    {
        $this->db->insert('chats', [
                'id' => null,
                'username' => $player,
                'message' => $chat,
                'created_at' => date('Y-m-d H:i:s')
            ]);
    }
}
