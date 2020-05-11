<?php

namespace anggagewor\Sharpbe\Contracts;

use anggagewor\Sharpbe\Main;
use anggagewor\Sharpbe\Support\Db;
use pocketmine\network\mcpe\protocol\LoginPacket;

class LoginLog
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
        $this->db->execute('CREATE TABLE IF NOT EXISTS login_logs (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE,
            username VARCHAR(255) NULL,
            protocol VARCHAR(255) NULL,
            client_uuid VARCHAR(255) NULL,
            client_id VARCHAR(255) NULL,
            xuid VARCHAR(255) NULL,
            server_address VARCHAR(255) NULL,
            locale VARCHAR(255) NULL,
            device_model VARCHAR(255) NULL,
            device_os VARCHAR(255) NULL,
            game_version VARCHAR(255) NULL,
            created_at timestamp NULL,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
    }

    public function save(LoginPacket $packages)
    {
        $this->db->insert('login_logs', [
                'id' => null,
                'username' => $packages->username,
                'protocol' => $packages->protocol,
                'client_uuid' => $packages->clientUUID,
                'client_id' => $packages->clientId,
                'xuid' => $packages->xuid,
                'server_address' => $packages->serverAddress,
                'locale' => $packages->locale,
                'device_model' => $packages->clientData['DeviceModel'],
                'device_os' => $packages->clientData['DeviceOS'],
                'game_version' => $packages->clientData['GameVersion'],
                'created_at' => date('Y-m-d H:i:s'),
            ]);
    }
}
