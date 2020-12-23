<?php 

namespace optimy\app\models;

use optimy\app\models\Model;

class Blog extends Model
{
	private $blogId;
	private $userId;
	private	$title;
	private	$content;
	private	$filename;
	private $type;
	private $createdAt;
}
