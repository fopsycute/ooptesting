

<?php
include "header.php"; 

if (!$auth->isLoggedIn()) {
    header("Location: login.php");
    exit;
}
?>


      <div class="container" data-aos="fade-up">
        <div class="row justify-content-center align-items-center min-vh-100">
          <div class="col-md-8 mx-auto">
      
    <div class="card">
    <div class="card-header bg-primary text-dark fw-bold">
        Add New Blog Post
        </div>
        <div class="card-body">
   <form  method="POST" id="addForum" enctype="multipart/form-data">
   
   <div class="row">
    <div class="col-lg-12 text-center mt-1" id="messages"></div> 

    <div class="col-sm-12">
   <div class="form-group mb-3">
   <label class="form-label" for="Title">Title</label>
   <input placeholder="Enter Title" id="title" type="text" class="form-control" name="title" required>
   </div></div>
       
    <div class="col-sm-12">  
    <div class="form-group mb-3">
    <label class="form-label" for="featured image">Attach Featured Image</label>
    <input class="form-control" type="file"  name="featured_image" required  accept="image/*">
    </div></div>
    
  
<input type="hidden" name="action" id="action" value="addforum">
<div class="col-sm-12">
   <div class="form-group">
    <label class="form-label" for="editor">Article Content</label>
    <textarea  class="form-control" name="article"></textarea>
   </div></div>

   <div class="col-sm-12">
   <div class="form-group mb-2">
    <label class="form-label" for="tags">Tags</label>
    <input type="text" name="tags" id="tags" class="form-control">
   </div></div>


  <input type="hidden" name="user" value="<?php echo $user['id']; ?>">
  <div class="col-lg-12 col-md-12 col-sm-12">
  <button type="submit" id="submitBtn"  class="btn btn-primary w-100" name="addforum">Create</button>
  </div></div>
</form>
</div>
</div>
</div>
</div>
</div>

<?php include "footer.php"; ?>