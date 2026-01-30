<?php include "header.php"; ?>
<?php
require_once "classes/user.php";

$forumDB = new UsersDB();
$result  = $forumDB->getPublishedForums();
?>
<div class="container py-5">
  <div class="row g-4">
<?php while ($row = $result->fetch_assoc()): 
    $forum = new getForum($row);
?>
    <div class="col-lg-4 col-md-6">
      <div class="card h-100 border-0 shadow-sm blog-card">

        <img 
          src="<?= $forum->getImage(); ?>" 
          class="card-img-top"
          alt="<?= $forum->getTitle(); ?>"
        >

        <div class="card-body">
          <span class="badge bg-warning text-dark mb-2">
            <?= $forum->getTags(); ?>
          </span>

          <h5 class="card-title mt-2">
            <?= $forum->getTitle(); ?>
          </h5>

          <p class="card-text text-muted">
            <?= $forum->getExcerpt(10); ?>
          </p>

          <a href="<?= $forum->getReadMoreUrl(); ?>" class="btn btn-sm btn-outline-primary">
            Read More →
          </a>
        </div>

        <div class="card-footer bg-white border-0 d-flex justify-content-between">
          <small class="text-muted">By <?= $forum->getAuthor(); ?></small>
          <small class="text-muted"><?= $forum->getDate(); ?></small>
        </div>

      </div>
    </div>
<?php endwhile; ?>
</div>
</div>
</div>
<?php include "footer.php"; ?>