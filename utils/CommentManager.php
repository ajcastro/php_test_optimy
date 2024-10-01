<?php

declare(strict_types=1);

class CommentManager
{
	private static ?self $instance = null;

	private function __construct()
	{
		require_once(ROOT . '/utils/DB.php');
		require_once(ROOT . '/class/Comment.php');
	}

	public static function getInstance(): self
	{
		if (null === self::$instance) {
			$c = __CLASS__;
			self::$instance = new $c;
		}
		return self::$instance;
	}

	public function listComments(): array
	{
		$db = DB::getInstance();
		$rows = $db->select('SELECT * FROM `comment`');

		return $this->populateCommentsFromRows($rows);
	}

	public function listCommentsByNewsId(int $newsId): array
	{
		$db = DB::getInstance();
		$rows = $db->select("SELECT * FROM `comment` WHERE `news_id` = {$newsId}");

		return $this->populateCommentsFromRows($rows);
	}

	private function populateCommentsFromRows(array $rows): array
	{
		$comments = [];
		foreach($rows as $row) {
			$n = new Comment();
			$comments[] = $n->setId($row['id'])
			  ->setBody($row['body'])
			  ->setCreatedAt($row['created_at'])
			  ->setNewsId($row['news_id']);
		}

		return $comments;
	}

	public function addCommentForNews(string $body, int $newsId): string|false
	{
		$db = DB::getInstance();
		$sql = "INSERT INTO `comment` (`body`, `created_at`, `news_id`) VALUES('". $body . "','" . date('Y-m-d') . "','" . $newsId . "')";
		$db->exec($sql);
		return $db->lastInsertId();
	}

	public function deleteComment(int $id): int|bool
	{
		$db = DB::getInstance();
		$sql = "DELETE FROM `comment` WHERE `id`=" . $id;
		return $db->exec($sql);
	}
}
