# Problems and possible issues

## Issues

** General Issues **

- Too many code repetition on the PHP and HTML files.
- Tightly coupled codes, controller and model and view is written on a single file
- Unreuseable codes.
- Unscalable, has no proper scaffolding.
- Hard to maintain since changes on the code would easily break the application
- No error checking and can be hack through sql injection on the form inputs. 

### Backend Issues and Recommendations 

**Table creation**
:Issues on table creation
- Table blog does not have identifier for the blog creator (user of the app)
- No table for users that would relate to the blog created.

```
CREATE DATABASE exam;
USE exam;
CREATE TABLE blog (
    id INT(6) AUTO_INCREMENT PRIMARY KEY,
    title TEXT NULL,
    content TEXT NULL,
    filename TEXT NULL,
    type VARCHAR(50),
    created_at TIMESTAMP NOT NULL
);

```

:Suggestions

- Create tables for users and alter table blog by adding a userid column. Here we are making relations between users and blogs. One user can have multiple blogs.

:example 
```
CREATE TABLE IF NOT EXISTS blogs (
					id INT(6) AUTO_INCREMENT PRIMARY KEY,
					user_id INT,
					title TEXT NOT NULL,
					content TEXT NULL,
					filename TEXT NULL,
					category VARCHAR(50) NOT NULL,
					created_at TIMESTAMP NOT NULL,
					updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
				)
				ENGINE=INNODB;


CREATE TABLE IF NOT EXISTS users (
					id INT AUTO_INCREMENT PRIMARY KEY, 
					email VARCHAR(255) NOT NULL,
					firstname VARCHAR(255) NOT NULL,
					lastname VARCHAR(255) NOT NULL,
					password VARCHAR(255) NOT NULL,
					status TINYINT NOT NULL, 
					created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
				)
				ENGINE=INNODB;
```

- Create tables programmatically. This can be achieve by creating a migration files that will reside inside migrations folder. Every file corresponds to table creation, alteration or deletion.

:example of a migration file
```
class M01_users_table
{
	public function up()
	{
		$app = Application::instance();
		$stmt = "CREATE TABLE IF NOT EXISTS users (
					id INT AUTO_INCREMENT PRIMARY KEY, 
					email VARCHAR(255) NOT NULL,
					firstname VARCHAR(255) NOT NULL,
					lastname VARCHAR(255) NOT NULL,
					password VARCHAR(255) NOT NULL,
					status TINYINT NOT NULL, 
					created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
				)
				ENGINE=INNODB;";

		$app->db->pdo->exec($stmt);

		echo "Users table has been created" . PHP_EOL;
	}

	public function down()
	{
		$app = Application::instance();
		$stmt = "DROP TABLE users;";
		$app->db->pdo->exec($stmt);
		echo "Table users is offline" . PHP_EOL;
	}
}
```

:implementing migration
```
public function migrate()
	{
		$newMigrations = [];

		$dir = __DIR__."\..\migrations";
		$this->createMigrationsTable();
		
		$migrations = $this->getMigrations();

		$migrations = array_map(function($v) {
			return $v = $v.".php";
		}, $migrations);

		$files = scandir($dir);
		
		$pendingMigrations = array_diff($files, $migrations);
		
		foreach ($pendingMigrations as $migration) {
			if ($migration === "." || $migration === "..") {
				// do nothing
				continue;
			}

			require_once($dir . "\\" . $migration);

			$className = pathinfo($migration, PATHINFO_FILENAME);
			$object = new $className;
			
			$this->log("Applying migration $migration");
			$object->up();
			$this->log("Done migrating $migration");
			
			$newMigrations[] = $migration;
		}

		if (!empty($newMigrations)) {
			$this->save($newMigrations);
		} else {
			$this->log("All migrations are applied");
		}
	}

	private function createMigrationsTable()
	{
		$this->pdo->exec(
			"CREATE TABLE IF NOT EXISTS 
				migrations (
					id INT AUTO_INCREMENT PRIMARY KEY, 
					migration VARCHAR(255), 
					created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
				)
				ENGINE=INNODB;"
			);
	}

	private function getMigrations()
	{
		$stmt = $this->pdo->prepare("SELECT migration FROM migrations");
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_COLUMN);
	}

	private function save($migrations)
	{
		$values = implode(",", array_map(
			function($m){
				$m = str_replace(".php", "", $m);
				return "('$m')";
			}, $migrations));
		
		$stmt = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES $values");

		$stmt->execute();
	}
```

**Connecting to the database**

:Issues on the code below are 
- This database call is tightly coupled with the database connection.
- The connection code was again rewritten on the `create_news.php` file. 
- Database connection credential is exposed to the code and potential vulnerable to exploits.
- Connecting to database using `mysqli_connect` restricts the application into using MySQL only.
- No error catching when connecting to database nor when fetching the data.
```
$sql = "SELECT * FROM blog ORDER BY created_at DESC;";
    if (trim($_GET['filter']) !== '') {
        $sql = 'SELECT * FROM blog WHERE type="'.$_GET['filter'].'" ORDER BY created_at DESC;';
    }
    $connection = mysqli_connect("localhost", "root", "", "exam");
    $res = mysqli_query($connection, $sql);
    $blogs = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $blogs[] = $row; 
    }
    mysqli_close($connection);
```

:Suggestions

- Create an database abstraction layer by using PHP Database Object instead of mysqli.
- Create a singleton class that would return the instance of the connection.
- Prepare the sql statement rather that directly getting it from the variables and bind the values.
- Create an environment file where we can define the values needed for our database connection. 
- Parse environment file and put it inside the global variables. By this we are decoupling our app from the actual database.

:example of `.env` file 
```
;change the value of this provider to the intended product
provider = mysql
base_url = optimy/app/

mysql_host = 127.0.0.1
mysql_user = 'root'
mysql_pass = 'Abcd1234'
mysql_dbname = 'exam'
```
:example of putting inside the global variable `init.php` file
```
$env = parse_ini_file("../.env", true);

$GLOBALS["config"] = array(
	// type of database product in use
	"provider" => $env["provider"],
	"base_url" => $env["base_url"],
	"class" => $env["class"],
	"repo" => $env["repo"],
	"mysql" => [
		"host" => $env["mysql_host"],
		"user" => $env["mysql_user"],
		"pass" => $env["mysql_pass"],
		"dbname"   => $env["mysql_dbname"]	
	],
}
```
:example of `MysqlConnection.php` class
```
class MysqlConnection
{
	private static $instance = null;

	private function __construct()
	{
		try {

			$config = Config::get("mysql");

			$dns = "mysql:host=".$config['host'].";dbname=".$config['dbname'];
			$user = $config['user'];
			$pass = $config['pass'];
			$opt = [
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
				PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        	];

			$this->pdo = new PDO($dns, $user, $pass, $opt);

		} catch(PDOException $err) {
			die(json_encode([
				"message" => "Failed to connect to database",
				"error" => $err->getMessage()]
			));
		}
	}
	public static function instance()
	{
		if (!isset(self::$instance)){
			self::$instance = new MysqlConnection();
		}
		return self::$instance;
	}

	public function close()
	{
		return $this->pdo = null;
	}
}
```

**Fetching and Creating Blog**

:Issues
- No try catch error when inserting to database.
- Uploaded file is already moved to the uploads folder even if the insertion fails
- We don't need to put values on `created_at` columns since we define the value during table creation.
- No validations for the uploaded file hence attack can not be prevented.
- Directly getting the values posted from the app is risky and doom to fail due to attackers.
- Creating a new connection instead of checking if there is an instance of connection already in use.

```
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $filename = '';
    if (is_array($_FILES)) {
        move_uploaded_file(
            $_FILES['file']['tmp_name'],
            __DIR__.'/uploads/'.$_FILES['file']['name']
        );
        $filename = $_FILES['file']['name'];
    }

    $sql = '
        INSERT INTO blog SET
            title = "'.$_POST['title'].'",
            content = "'.$_POST['content'].'",
            type = "'.$_POST['type'].'",
            filename = "'.$filename.'",
            created_at = NOW()
    ';

    $connection = mysqli_connect("localhost", "root", "", "exam");
    $res = mysqli_query($connection, $sql);
    mysqli_close($connection);
    header('Location: index.php');
}
```

:Suggestions 
- Create a model that represents our table blogs and users

:example of `User.php` and `Blog.php` models
```
class User extends Model
{	
	public $email = "";
	public $password = "";
	public $confirmPassword = "";
	public $firstname = "";
	public $lastname = "";
	public $status = self::STATUS_INACTIVE;

	public function attributes()
	{
		return ["firstname", "lastname", "email", "password", "status"];
	}
}

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
	
	public function attributes()
	{
		return ["id", "userid", "title", "content", "filename", "category", "created_at", "updated_at"];
	}	
}

```

- Validation should reside in our definded model as rules. In here, we can validate our data according to the rules sets on our model.

:example of rules use for data validation
```
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
				"type" => "none",
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
```

- Create and abstract model where we define the rules and validations
:example of abstract `Model.php` 
```
abstract class Model
{
	public const RULE_TYPE = 'type';
	public const RULE_REQUIRED = 'required';
	public const RULE_EMAIL = 'email';
	public const RULE_STRING = "string";
	public const RULE_NUMERIC = "number";
	public const RULE_FLOAT = "float";
	public const RULE_BOOL = "bool";
	public const RULE_MATCH = 'match';
	public const RULE_MIN = 'min';
	public const RULE_MAX = 'max';
	public const RULE_PASSWORD = 'password';
	public const RULE_UNIQUE = 'unique';
	public const RULE_IMAGE = 'image';
	public const RULE_INVALID = 'invalid';
	
	public $errors = [];

	protected $messages = [
		self::RULE_REQUIRED => 'This field is required',
		self::RULE_EMAIL => 'This field must be a valid email address',
		self::RULE_STRING => 'This field must not contain numbers',
		self::RULE_NUMERIC => 'This field must contain numbers only',
		self::RULE_FLOAT => 'This is a float value field',
		self::RULE_BOOL => 'This field should be true or false only',
		self::RULE_MIN => 'Minimum length must be {min} characters',
		self::RULE_MAX => 'Maximum length is {max} characters',
		self::RULE_MATCH => 'This field must be the same as {match}',
		self::RULE_PASSWORD => 'Password must be alphanumeric with atleast 1 uppercase and atleast 1 lowercase',
		self::RULE_UNIQUE => '{unique} already exists.',
		self::RULE_INVALID => 'Invalid input',
		self::RULE_IMAGE => 'Only image file are allowed'
	];

	public function validate()
	{
		$rulesArray = $this->rules();

		foreach ($rulesArray as $attribute => $rule) {
			
			$value = $this->{$attribute};

			if ($rule[self::RULE_REQUIRED] && empty($value)) {
				$this->error($attribute, self::RULE_REQUIRED);
			}

			if (array_key_exists(self::RULE_UNIQUE, $rule) && !empty($value)) {
				$this->validateUnique($attribute, $value, $rule);
			}

			if ($rule[self::RULE_TYPE] === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
				$this->error($attribute, self::RULE_EMAIL);
			}

			if ($rule[self::RULE_TYPE] === self::RULE_FLOAT && !filter_var($value, FILTER_VALIDATE_FLOAT)) {
				$this->error($attribute, self::RULE_FLOAT);
			}

			if ($rule[self::RULE_TYPE] === self::RULE_BOOL && !filter_var($value, FILTER_VALIDATE_BOOLEAN)) {
				$this->error($attribute, self::RULE_BOOL);
			}

			if ($rule[self::RULE_TYPE] === self::RULE_STRING && !empty($value)) {
				$this->validateString($attribute, $value);
			}

			if ($rule[self::RULE_TYPE] === self::RULE_NUMERIC && !empty($value)) {
				$this->validateNumeric($attribute, $value);
			}

			if ($rule[self::RULE_TYPE] === self::RULE_PASSWORD && !empty($value)) {
				$this->validatePassword($attribute, $value);
			}

			if ($rule[self::RULE_TYPE] === self::RULE_MATCH && $value !== $this->{$rule["match"]}) {
				$this->error($attribute, self::RULE_MATCH, $rule);
			}

			if (array_key_exists(self::RULE_MIN, $rule) && strlen($value) < $rule[self::RULE_MIN]) {
				$this->error($attribute, self::RULE_MIN, $rule);
			}

			if (array_key_exists(self::RULE_MAX, $rule) && strlen($value) > $rule[self::RULE_MAX]) {
				$this->error($attribute, self::RULE_MAX, $rule);
			}
		}

		return empty($this->errors);
	}
}
```

- We need to sanitize all our inputs prior to validation by creating class `Input` and call its static function to clean up global GET and/or global POST.

:example of `Input.php` class
```
class Input
{
	private static function filter($body, $input)
	{
		foreach ($body as $key => $value) {
			$body[$key] = filter_input($input, $key, FILTER_SANITIZE_SPECIAL_CHARS);
		}

		return $body;
	}

	public static function clean($method)
	{
		if ($method === "get") {
		 	return self::filter($_GET, INPUT_GET);
		}

		return self::filter($_POST, INPUT_POST);
	}
}
```

- We also need to check the files and validate if all other pertinent data is good for insertion before moving the file to upload folder.

:example of file checking
```
if (!empty($_FILES["filename"])) {
			$this->uploadFile = $this->uploadDir . $_FILES["filename"]["name"];
			// Checking the MIME type
		    $extension = in_array($_FILES["filename"]["type"], [
		    	"image/jpeg",
		    	"image/png",
		    	"image/gif",
		    	"image/tiff"
		    ]);

			try {

				if ($_FILES["filename"]["error"] != 0 && isset($_FILES["filename"]["error"])) {
					throw new RuntimeException("Invalid parameters");
				}
				
			    if ($_FILES["filename"]["size"] > 1000000) {
			        throw new RuntimeException('Exceeded filesize limit.');
			    }

			    if (!$extension) {
			    	throw new RuntimeException('Invalid file format.');
			    }

			} catch (RuntimeException $e) {
				echo $e->getMessage();
			}
		}
```

- Use try catch method whenever we are communicating to our database. Use prepare statement and bindValues.

```
	protected function insert(array $values)
	{
		$holders = array_map(function($a){
			return $a = ":$a";
		}, $this->attributes);

		try 
		{
			$stmt = $this->pdo->prepare("INSERT INTO $this->table (" . implode(',', $this->attributes) . ") 
			VALUES(". implode(',', $holders) .")");

			array_map(function($a, $v) use ($stmt) {
				$stmt->bindValue(":$a", $v);
			}, $this->attributes, $values);

			$stmt->execute();
		}
		catch(PDOException $e)
		{
			echo 'Connection failed: ' . $e->getMessage();
    		exit;
		}
		return true;	
	}

	protected function findAll($attributes)
	{
		$sql = "SELECT * FROM $this->table";

		try 
		{
			$this->prepareQuery($sql, $attributes);
			$this->query->execute();
			$this->results = $this->query->fetchAll(PDO::FETCH_OBJ);
		}
		catch (PDOException $e)
		{
			echo 'Connection failed: ' . $e->getMessage();
    		exit;
		}

		return $this->results;
	}
```

- Use namespace for better grouping and prevent class name conflicts
- Use Autoloader (composer) to autoload the classes.

:example of namespace and autoload
```
<?php 

namespace optimy\app;

require_once "../vendor/autoload.php";
require_once "init.php";
```
- Create a Renderer responsible for rendering our front end view
- Create Controllers that will pass values to our renderer for viewing.

:example of `Renderer.php`
```
class Renderer
{
	public function view($view, $params = [])
	{
		$layout = $this->layout();
		$viewContent = $this->content($view, $params);

		return str_replace("{{content}}", $viewContent, $layout);
	}

	public function layout()
	{
		$layout = Application::$app->getController()->layout;
		ob_start(); // start caching the output buffering

		include_once Application::$ROOT_PATH . "/app/views/layout/$layout.php"; // the actual output

		return ob_get_clean(); // return the output and clean the cache
	}

	public function content($view, $params = [])
	{
		if (!is_null($params)) {
			foreach ($params as $key => $value) {
				// turn the key which is name into a variable $name
				$$key = $value;
			}
		}
		
		ob_start(); // start caching the output

		include_once Application::$ROOT_PATH . "/app/views/$view.php"; // the actual view output

		return ob_get_clean(); // return the output and clean the cache
	}
}
```

:example of abstract `Controller.php`
```
abstract class Controller
{

	protected $render;
	public $layout = "main"; // default layout

	public function view($view, $params){
		
		$this->render = new Renderer();
		return $this->render->view($view, $params);
	}

	public function setLayout($layout) {
		$this->layout = $layout;
	}
}
```


** HTML side **
:Issues
- Mobile view is broken due to enclosing the div class container
- Footer goes to the middle of the page when the content is short
- Submit button is wrongly reference to index.php
- Links are not working
- Repeated HTML code and broken links
- No front-end error display feedback from the app
```
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">Articles</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php?filter=government">Government</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?filter=sports">Sports</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?filter=food">Food</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-success" href="create-news.php">Create</a>
                </li>
            </ul>
        </div>
    </nav>
```

:Suggestions

- Fix navbar and re-write the links
:example of nav
```
 <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="#"><b>Optimy</b></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      	</button>
      	<div class="collapse navbar-collapse" id="navbarSupportedContent">
      	<ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class='btn btn-primary ?>' href="/blog">Create Blog</a>
              </li>         
              <li class="nav-item">
                <a class="nav-link" href="/foods">Foods</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/places">Places</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/sports">Sports</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/people">People</a>
              </li>
          </ul>
        </div>
    </div>
</nav>
```
- Fix footer to the bottom of the page by adding class footer and height to custom css

:example of footer 
```
<footer class="footer py-5 bg-dark mt-4">
    <div class="container">
        <p class="m-0 text-center text-white">Copyright &copy; 2020</p>
    </div>
</footer>
```

- Create a layout where we can place our content. This will help ensure view consistency on all pages.
- Create form that binds our model validation and can display errors for the users. 
- Create a class `Form.php` that can instatiate a class `Field.php`. Our Field class contains div for invalid-feedback and can accept css style.

:example of `Form.php` class
```
class Form
{
	public function begin($action, $method){
		echo sprintf('<form enctype="multipart/form-data" action="%s" method="%s">', $action, $method);
	}

	public function end()
	{
		echo "</form>";
	}

	public function field(Model $model, $attribute)
	{
		return new Field($model, $attribute);
	}

	public function select(Model $model, $attribute, $options = [])
	{
		return new Select($model, $attribute, $options);
	}

	public function textarea(Model $model, $attribute)
	{
		return new Textarea($model, $attribute);
	}

	public function file(Model $model, $attribute)
	{
		return new Filefield($model, $attribute);
	}

	public static function instance()
	{
		return new Form();
	}
}
```
:example of `Field.php` class
```
class Field
{
	protected $model;
	protected $attribute;
	protected $type;
	protected $css;

	protected const TYPE_TEXT = "text";

	public function __construct(Model $model, $attribute){
		$this->model = $model;
		$this->attribute = $attribute;
		$this->type = self::TYPE_TEXT;
	}

	public function __toString()
	{
		return sprintf(
			'<div class="form-group %s">
	            <label class="center">%s</label>
	            <input type="%s" name="%s" value="%s" class="form-control %s">
	            <div class="invalid-feedback">
	            	%s
	            </div>
	        </div>',
	        $this->css ?? "",
	        $this->model->labels()[$this->attribute] ?? $this->attribute, 
	        $this->type,
	        $this->attribute,
	        $this->model->{$this->attribute},
	        $this->model->hasError($this->attribute)? 'is-invalid' : '',
	        $this->model->firstError($this->attribute)
		);
	}

	public function fieldType($type)
	{
		$this->type = $type;
		return $this;
	}

	public function cssStyle($style)
	{
		$this->css = $style;
		return $this;
	}
}
```
- Create a main layout for the app 

:example of main layout 
```
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <!-- Bootstrap core CSS -->
    <link href="../assets/css/bootstrap.css" rel="stylesheet">
    <link href="../assets/css/custom.css" rel="stylesheet">

    <!-- Bootstrap core JavaScript -->
    

    <title>News</title>
  </head>
  <body>

  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="#"><b>Optimy</b></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        
        <?php if (Application::isGuest()) : ?>

            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link" href="/foods">Foods</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/places">Places</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/sports">Sports</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/people">People</a>
              </li>
            </ul>
            <ul class="navbar-nav ml-auto mb-2 mb-lg-0">
               <li class="nav-item">
                <a class="nav-link" href="/login"><b>Hi Guest</b> <small style="color:blue;">(Login)</small></a>
               </li>
            </ul>

        <?php else: ?>

          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class='btn btn-primary ?>' href="/blog">Create Blog</a>
              </li>         
              <li class="nav-item">
                <a class="nav-link" href="/foods">Foods</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/places">Places</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/sports">Sports</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/people">People</a>
              </li>
          </ul>
            <ul class="navbar-nav ml-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link" href="/logout"><b>Hi <?php echo Application::$app->user->firstname;  ?></b><small style="color:red;"> (Logout) </small></a>
              </li>
            </ul>
        <?php endif; ?>

      </div>
    </div>
  </nav>
 
    <?php if (Application::$app->session->getFlash("success")): ?>
    <div class="alert alert-success alert-dismissible fade show">
      <?php echo Application::$app->session->getFlash("success") ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <?php elseif (optimy\app\core\Application::$app->session->getFlash("fail")): ?>
     <div class="alert alert-warning alert-dismissible fade show">
      <?php echo optimy\app\core\Application::$app->session->getFlash("fail") ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <?php endif; ?>

  <div class="container">
    
    <div class="content">
       

       {{content}}
    

    </div>
  
  </div>

  <footer class="footer py-5 bg-dark mt-4">
    <div class="container">
        <p class="m-0 text-center text-white">Copyright &copy; 2020</p>
    </div>
  </footer>

  <script src="../assets/js/jquery-3.5.0.js"></script>
  <script src="../assets/js/bootstrap.js"></script>
  <script type="text/javascript" src="../assets/js/script.js"></script>
  </body>
</html>
```
