<?php include "header.php"; ?>
<?php
require_once "classes/user.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: blogs.php");
    exit;
}

$forumDB = new UsersDB();
$result  = $forumDB->getForumById((int) $_GET['id']);

if ($result->num_rows === 0) {
    header("Location: index.php");
    exit;
}

$forum = new Forum($result->fetch_assoc());
?>


<div class="container my-5">

  <div class="row justify-content-center">
    <div class="col-lg-8">

      <img 
        src="<?= $forum->getImage(); ?>" 
        class="img-fluid rounded mb-4"
        alt="<?= $forum->getTitle(); ?>"
      >

      <span class="badge bg-warning text-dark mb-3">
        <?= $forum->getTags(); ?>
      </span>

      <h1 class="mb-3">
        <?= $forum->getTitle(); ?>
      </h1>

      <p class="text-muted mb-4">
        By <?= $forum->getAuthor(); ?> • <?= $forum->getDate(); ?>
      </p>

      <div class="blog-content">
        <?= $forum->getContent(); ?>
      </div>

      <a href="index.php" class="btn btn-outline-secondary mt-4">
        ← Back to blogs
      </a>

    </div>
  </div>

</div>

<?php include "footer.php"; ?>