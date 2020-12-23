<?php 

namespace optimy\app;

$env = parse_ini_file("../.env", true);

$GLOBALS["config"] = array(
	// type of database product in use
	"provider" => $env["provider"],
	"base_url" => $env["base_url"],
	"mysql" => [
		"host" => $env["mysql_host"],
		"user" => $env["mysql_user"],
		"pass" => $env["mysql_pass"],
		"dbname"   => $env["mysql_dbname"]	
	],
	/*
	 * We can easily swith to other products here:
	 *
	 *	"mssql" => [
	 *     	"host" => $env["mssql_host"],
	 *		"user" => $env["mssql_user"],
	 *		"pass" => $env["mssql_pass"],
	 *		"dbname"   => $env["mssql_dbname"]	
	 *	],
	 *	"oracle" => [
	 *     Oracle config goes here
     *	]
	*/
);

