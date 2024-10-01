<?php

declare(strict_types=1);

class NewsManager
{
	private static ?self $instance = null;

	private function __construct()
	{
		require_once(ROOT . '/utils/DB.php');
		require_once(ROOT . '/utils/CommentManager.php');
		require_once(ROOT . '/class/News.php');
	}

	public static function getInstance(): self
	{
		if (null === self::$instance) {
			$c = __CLASS__;
			self::$instance = new $c;
		}
		return self::$instance;
	}

	/**
	* list all news
	*/
	public function listNews(): array
	{
		$db = DB::getInstance();
		$rows = $db->select('SELECT * FROM `news`');

		$news = [];
		foreach($rows as $row) {
			$n = new News();
			$news[] = $n->setId($row['id'])
			  ->setTitle($row['title'])
			  ->setBody($row['body'])
			  ->setCreatedAt($row['created_at']);
		}

		return $news;
	}

	/**
	* add a record in news table
	*/
	public function addNews($title, $body): int
	{
		$db = DB::getInstance();
		$sql = "INSERT INTO `news` (`title`, `body`, `created_at`) VALUES('". $title . "','" . $body . "','" . date('Y-m-d') . "')";
		$db->exec($sql);

		return $db->lastInsertId();
	}

	/**
	* deletes a news, and also linked comments
	*/
	public function deleteNews($id): int|bool
	{
		$comments = CommentManager::getInstance()->listCommentsByNewsId($id);

		foreach ($comments as $comment) {
			CommentManager::getInstance()->deleteComment($comment->getId());
		}

		$db = DB::getInstance();
		$sql = "DELETE FROM `news` WHERE `id`=" . $id;
		return $db->exec($sql);
	}
}
