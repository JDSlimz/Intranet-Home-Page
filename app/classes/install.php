<?php

class Installer {

  static function check_writable_directory($path) {
    /*
    *   See if the writable directory is writable
    */

    return is_writable($path . "/writable/");
  }

  static function write_db_config($host, $user, $pass, $db, $path) {
    $db_config = array(
      "host" => $host,
      "user" => $user,
      "pass" => $pass,
      "db"   => $db
    );

    file_put_contents($path . "/writable/db.json", json_encode($db_config));
  }

  static function build_database($host, $user, $pass, $db, $path) {
    /*
    *   Connect to MySQL and create necessary tables.
    *   Returns true if successful, or the MySQL error if one is raised
    */

    try {
      $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
      // set the PDO error mode to exception
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
      return $e->getMessage();
    }

    $sql = file_get_contents(APPPATH . "/system/schema.sql");

    $query_result = $conn->query($sql);

    self::write_db_config($host, $user, $pass, $db, $path);

    return true;

  }

}


?>
