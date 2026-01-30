
<?php

class Dbh {

    protected $host = "localhost";
    protected $user = "root";
    protected $pass = "";
    protected $db   = "ooptesting";

    protected function connect() {
        $conn = new mysqli(
            $this->host,
            $this->user,
            $this->pass,
            $this->db
        );

        if ($conn->connect_error) {
            die("Database connection failed: " . $conn->connect_error);
        }

        return $conn;
    }
}