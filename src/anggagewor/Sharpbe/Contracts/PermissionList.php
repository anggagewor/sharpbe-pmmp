<?php

namespace anggagewor\Sharpbe\Contracts;

use anggagewor\Sharpbe\Main;
use anggagewor\Sharpbe\Support\Db;

class PermissionList
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
        $this->db->execute('CREATE TABLE IF NOT EXISTS pmmp_permissions (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE,
            name VARCHAR(255) NOT NULL,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
    }
    public function save($permission)
    {
		$check = $this->db->select('pmmp_permissions', ['name' => $permission]);
		if(!$check){
			$this->db->insert('pmmp_permissions', [
				'id' => null,
				'name' => $permission
			]);
		}else{
			$this->db->update('pmmp_permissions', ['name'=>$permission], ['id' => $check[0]['id']]);
		}
    }
}
