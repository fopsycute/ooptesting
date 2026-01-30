
<?php
require_once "UsersDB.php";

class User {

    private $fullname;
    private $email;
    private $password;
    private $confirmPassword;
    private $role = 'user'; // default role

    public function __construct($fullname, $email, $password, $confirmPassword) {
        $this->fullname        = trim($fullname);
        $this->email           = trim($email);
        $this->password        = $password;
        $this->confirmPassword = $confirmPassword;

    }

    public function register() {

        if (
            empty($this->fullname) ||
            empty($this->email) ||
            empty($this->password) ||
            empty($this->confirmPassword)
        ) {
            return [
                "status"   => "error",
                "messages" => "All fields are required"
            ];
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            return [
                "status"   => "error",
                "messages" => "Invalid email address"
            ];
        }

        if ($this->password !== $this->confirmPassword) {
            return [
                "status"   => "error",
                "messages" => "Passwords do not match"
            ];
        }

        if (strlen($this->password) < 6) {
            return [
                "status"   => "error",
                "messages" => "Password must be at least 6 characters"
            ];
        }

        $usersDB = new UsersDB();

        if ($usersDB->emailExists($this->email)) {
            return [
                "status"   => "error",
                "messages" => "Email already exists"
            ];
        }

        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
   

        if ($usersDB->insertUser($this->fullname, $this->email, $hashedPassword, $this->role)) {
            return [
                "status"   => "success",
                "messages" => "Registration successful"
            ];
        }

        return [
            "status"   => "error",
            "messages" => "Registration failed"
        ];
    }
//login function

    private function verifyPassword($plainPassword, $hashedPassword) {
    return password_verify($plainPassword, $hashedPassword);
}


private function setAuthCookie($userId, $userType) {

    $cookieMap = [
        'admin'     => 'admin_auth',
        'user'     => 'user_auth'
    ];

    // Clear all auth cookies
    foreach ($cookieMap as $cookie) {
        setcookie($cookie, '', time() - 3600, '/');
    }

    $cookieName = $cookieMap[$userType] ?? 'user_auth';
    setcookie($cookieName, $userId, time() + (86400 * 360), '/');
}

public function login() {

    if (empty($this->email) || empty($this->password)) {
        return [
            'success' => false,
            'error'   => 'Email and password are required.'
        ];
    }

    $usersDB = new UsersDB();

    if (!$usersDB->fetchUserByEmail($this->email)) {
        return [
            'success' => false,
            'error'   => 'Invalid email or password.'
        ];
    }

    if (!$this->verifyPassword($this->password, $usersDB->getPassword())) {
        return [
            'success' => false,
            'error'   => 'Invalid email or password.'
        ];
    }


    $userType = strtolower(trim($usersDB->getUserrole()));


    $this->setAuthCookie($usersDB->getId(), $userType);

    return [
        'success'   => true,
        'role' => $userType,
        'name'      => $usersDB->getFullName(),
        'message'   => 'Login successful. Redirecting...',
        'redirect'  => $this->getRedirectUrl($userType)
    ];
}

private function getRedirectUrl($userType) {
    return match ($userType) {
        'admin'     => 'admin.php',
        'user'      => 'dashboard.php',
        default     => 'dashboard.php'
    };
}



}

// Authentication class
class Auth {

    private $userType;
    private $cookieMap = [
        'admin'     => 'admin_auth',
        'user'      => 'user_auth'
    ];

    private $usersDB;
    private $userData = null;

    public function __construct($userType = 'user') {
        $this->userType = $userType;
        $this->usersDB  = new UsersDB();
        $this->checkLogin();
    }

      public function logout() {

        foreach ($this->cookieMap as $cookie) {
            if (isset($_COOKIE[$cookie])) {
                setcookie($cookie, '', time() - 3600, '/');
            }
        }

        return true;
    }
    private function checkLogin() {

        $cookieName = $this->cookieMap[$this->userType] ?? null;

        if (!$cookieName || empty($_COOKIE[$cookieName])) {
            return;
        }

        $userId = (int) $_COOKIE[$cookieName];

        if ($this->usersDB->fetchUserById($userId)) {
            $this->userData = [
                'id'        => $this->usersDB->getId(),
                'name'      => $this->usersDB->getFullName(),
                'email'     => $this->usersDB->getEmail(),
                'role' => $this->usersDB->getUserrole()
            ];
        }
    }

    public function isLoggedIn() {
        return $this->userData !== null;
    }

    public function getUser() {
        return $this->userData;
    }
}

// Forum class 


class Forum extends UsersDB {

    protected $id;
    private $userId;
    private $title;
    private $article;
    private $tags;
    private $image;
    private $author;
    private $createdAt;


    public function __construct($userId, $title = null, $article = null, $tags = null, $image = null) {
        parent::__construct();
        
        // If first parameter is an array, we're loading from database
        if (is_array($userId)) {
            $row = $userId;
            $this->id        = $row['id'] ?? null;
            $this->userId    = null; // Not needed for display
            $this->title     = $row['title'] ?? '';
            $this->article   = $row['article'] ?? '';
            $this->tags      = $row['tags'] ?? '';
            $this->image     = $row['featured_image'] ?? '';
            $this->author    = $row['author'] ?? '';
            $this->createdAt = $row['created_at'] ?? '';
        } else {
            // Creating new forum
            $this->userId  = (int) $userId;
            $this->title   = trim($title);
            $this->article = trim($article);
            $this->tags    = trim($tags);
            $this->image   = $image;
        }
    }

    public function create() {

        if (empty($this->title)) {
            return ["status" => "error", "message" => "Title is required"];
        }
        if (empty($this->article)) {
            return ["status" => "error", "message" => "Article content is required"];
        }

        $imagePath = $this->uploadImage();
        if (!$imagePath) {
            return ["status" => "error", "message" => "Image upload failed"];
        }

        if ($this->insertForum(
            $this->userId,
            $this->title,
            $this->article,
            $this->tags,
            $imagePath
        )) {
            return ["status" => "success", "message" => "Forum post created successfully"];
        }

        return ["status" => "error", "message" => "Failed to create forum post"];
    }

    private function uploadImage() {

        if ($this->image['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($this->image['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            return false;
        }

        $newName = uniqid("forum_", true) . "." . $ext;
        $uploadDir = "../uploads/";

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $destination = $uploadDir . $newName;

        if (move_uploaded_file($this->image['tmp_name'], $destination)) {
            return $newName;
        }

        return false;
    }

    //get published forums

    public function getPublishedForums($limit = 10) {

        $stmt = $this->conn->prepare(
            "SELECT f.id, f.title, f.article, f.featured_image, f.tags,
                    f.created_at, u.name AS author
             FROM forums f
             JOIN users u ON u.id = f.user_id
             WHERE f.status = 'published'
             ORDER BY f.created_at DESC
             LIMIT ?"
        );

        $stmt->bind_param("i", $limit);
        $stmt->execute();

        return $stmt->get_result();
    }

    /* ===== Getters ===== */

    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return htmlspecialchars($this->title);
    }

    public function getImage() {
        return htmlspecialchars("uploads/" . $this->image);
    }

    public function getTags() {
        return htmlspecialchars($this->tags);
    }

    public function getAuthor() {
        return htmlspecialchars($this->author);
    }

    public function getDate() {
        return date('M d, Y', strtotime($this->createdAt));
    }

    public function getContent() {
        return nl2br($this->article);
    }

    /* ===== Logic ===== */

    public function getExcerpt($limit = 10) {
        $words = explode(' ', strip_tags($this->article));
        return implode(' ', array_slice($words, 0, $limit)) . '...';
    }

    public function getReadMoreUrl() {
        return "blog.php?id=" . $this->id;
    }
}



class getForum {

    private $id;
    private $title;
    private $article;
    private $image;
    private $tags;
    private $author;
    private $createdAt;

    public function __construct($row) {
        $this->id        = $row['id'];
        $this->title     = $row['title'];
        $this->article   = $row['article'];
        $this->image     = $row['featured_image'];
        $this->tags      = $row['tags'];
        $this->author    = $row['author'];
        $this->createdAt = $row['created_at'];
    }

    /* ===== Getters ===== */

    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return htmlspecialchars($this->title);
    }

    public function getImage() {
        return htmlspecialchars("uploads/" . $this->image);
    }

    public function getTags() {
        return htmlspecialchars($this->tags);
    }

    public function getAuthor() {
        return htmlspecialchars($this->author);
    }

    public function getDate() {
        return date('M d, Y', strtotime($this->createdAt));
    }

    public function getContent() {
    return nl2br($this->article);
    }


    /* ===== Logic ===== */

    public function getExcerpt($limit = 10) {
        $words = explode(' ', strip_tags($this->article));
        return implode(' ', array_slice($words, 0, $limit)) . '...';
    }

    public function getReadMoreUrl() {
        return "blog.php?id=" . $this->id;
    }
}