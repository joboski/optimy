<?php 

namespace optimy\app\models;

use optimy\app\models\Model;

class Blog extends Model
{
	private const TABLE_NAME = "blogs";
	private const PRIMARY_KEY = "id";

	public $id;
	public $userid;
	public $title;
	public $content;
	public $filename;
	public $category;
	public $created_at;

	public function rules()
	{
		return [
			"title" => [
				"type" => "none",
				"required" => true,
				"min" => "3",
				"max" => "50"
			],
			"content" => [
				"type" => "unknown",
				"required" => true,
				"min" => 100,
				"max" => 10000
			],
			"category" => [
				"type" => "string",
				"required" => true	
			]
		];
	}

	public function labels()
	{
		return [
			"title" => "Title",
			"content" => "Description",
			"filename" => "Upload File",
			"category" => "Categories",
			"foods" => "Foods",
			"sports" => "Sports",
			"places" => "Places",
			"people" => "People"
		];
	}

	public function tableName()
	{
		return self::TABLE_NAME;
	}

	public function primaryKey()
	{
		return self::PRIMARY_KEY;
	}

	public function attributes()
	{
		return ["id", "userid", "title", "content", "filename", "category", "created_at", "updated_at"];
	}	
}
