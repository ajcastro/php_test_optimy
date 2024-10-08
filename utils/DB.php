<?php

declare(strict_types=1);

class DB
{
	private \PDO $pdo;

	private static ?self $instance = null;

	private function __construct()
	{
		$dsn = 'mysql:dbname=phptest;host=127.0.0.1';
		$user = 'root';
		$password = '';

		$this->pdo = new \PDO($dsn, $user, $password);
	}

	public static function getInstance(): self
	{
		if (null === self::$instance) {
			$c = __CLASS__;
			self::$instance = new $c;
		}
		return self::$instance;
	}

	public function select(string $sql): array
	{
		$sth = $this->pdo->query($sql);
		return $sth->fetchAll();
	}

	public function exec(string $sql): int|bool
	{
		return $this->pdo->exec($sql);
	}

	public function lastInsertId(): string|false
	{
		return $this->pdo->lastInsertId();
	}
}
