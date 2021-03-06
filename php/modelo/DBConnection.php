<?php
// Wrapper de PDO en modo Singleton

class DBConnection
{
	private static $db; // Database
	
	private function __construct()
	{}
	
	public static function getConnection()
	{
		if(empty(self::$db)) {
			//self::$db = new PDO('mysql:host=mysql.hostinger.es;dbname=u621484484_equip;charset=utf8', 'u621484484_equip', 'peterete');
			self::$db = new PDO('mysql:host=localhost;dbname=goequip;charset=utf8', 'root', '');
		}
		
		return self::$db;
	}
	
	public static function getStatement($query)
	{
		return self::getConnection()->prepare($query);
	}
}