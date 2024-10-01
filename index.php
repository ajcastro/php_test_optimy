<?php

define('ROOT', __DIR__);

require_once(ROOT . '/utils/NewsManager.php');
require_once(ROOT . '/utils/CommentManager.php');

foreach (NewsManager::getInstance()->listNews() as $news) {
	echo("############ NEWS " . $news->getTitle() . " ############\n");
	echo($news->getBody() . "\n");

	// Instead of using listComments() method, we use listCommentsByNewsId() to query only the comments of a specific newsId.
	// This has better performance because we are only selecting the specific comments related to newId.
	foreach (CommentManager::getInstance()->listCommentsByNewsId($news->getId()) as $comment) {
		echo("Comment " . $comment->getId() . " : " . $comment->getBody() . "\n");
	}
}
