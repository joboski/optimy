<?php 

namespace optimy\app\models;

use optimy\app\models\Model;

class Blog extends Model
{
	public $userid;
	public $title;
	public $content;
	public $filename;
	public $category;
	public $createdAt;

	public function rules()
	{
		return [
			"userid" => [
				"type" => "hidden",
				"required" => true
			],
			"title" => [
				"type" => "string",
				"required" => true,
				"min" => "3",
				"max" => "50"
			],
			"content" => [
				"type" => "text",
				"required" => true,
				"min" => 100,
				"max" => 3000
			],
			"filename" => [
				"type" => "string",
				"required" => false	
			],
			"category" => [
				"type" => "string",
				"required" => true	
			],
			"created_at" => [
				"type" => "date",
				"required" => false		
			]
		];
	}
}
