
<?php
require_once "connect_to_db.php";

class UsersDB extends Dbh {

    protected $conn;
     protected $id;
    protected $password;
    protected $userrole;
    protected $name;
    protected $email;


    public function __construct() {
        $this->conn = $this->connect();
    }

    public function emailExists($email) {
        $stmt = $this->conn->prepare(
            "SELECT id FROM users WHERE email = ?"
        );
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        return $stmt->num_rows > 0;
    }

public function insertUser($fullname, $email, $password, $role) {
    $stmt = $this->conn->prepare(
        "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)"
    );
    $stmt->bind_param("ssss", $fullname, $email, $password, $role);
    return $stmt->execute();
}

    public function fetchUserByEmail($email) {

        $stmt = $this->conn->prepare(
            "SELECT id, name,  password, role
             FROM users
             WHERE email = ?
             LIMIT 1"
        );

        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            return false;
        }

        $row = $result->fetch_assoc();

        // Store internally
        $this->id        = $row['id'];
        $this->password  = $row['password'];
        $this->userrole = $row['role'];
        $this->name      = $row['name'] ;


        return true;
    }


    public function fetchUserById($id) {

    $stmt = $this->conn->prepare(
        "SELECT *
         FROM users
         WHERE id = ?
         LIMIT 1"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        return false;
    }

    $row = $result->fetch_assoc();

         $this->id        = $row['id'];
        $this->password  = $row['password'];
        $this->userrole = $row['role'];
        $this->name      = $row['name'] ;
       $this->email     = $row['email'];
    return true;
}

    // ✅ Getters (controlled access)
    public function getId() {
        return $this->id;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getUserrole() {
        return $this->userrole;
    }

    public function getEmail() {
    return $this->email;
    }


    public function getFullName() {
        return $this->name;
    }


    //insert blog function
    
    
    public function insertForum($userId, $title, $article, $tags, $imagePath) {
        $stmt = $this->conn->prepare(
            "INSERT INTO forums (user_id, title, article, tags, featured_image, status)
             VALUES (?, ?, ?, ?, ?, 'published')"
        );

        $stmt->bind_param("issss", $userId, $title, $article, $tags, $imagePath);
        return $stmt->execute();
    }


    public function getPublishedForums() {

    $stmt = $this->conn->prepare(
        "SELECT f.id, f.title, f.article, f.featured_image, f.tags,
                f.created_at, u.name AS author
         FROM forums f
         JOIN users u ON u.id = f.user_id
         WHERE f.status = 'published'
         ORDER BY f.created_at DESC"
    );

    $stmt->execute();

    return $stmt->get_result();
}


public function getForumById($id) {

    $stmt = $this->conn->prepare(
        "SELECT f.id, f.title, f.article, f.featured_image, f.tags,
                f.created_at, u.name AS author
         FROM forums f
         JOIN users u ON u.id = f.user_id
         WHERE f.id = ? AND f.status = 'published'
         LIMIT 1"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();

    return $stmt->get_result();
}

}
