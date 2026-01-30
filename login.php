
<?php include 'header.php'; ?>


<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-6 col-lg-5">
      <div class="card login-box py-4 shadow">
      <div class="card-body p-4">
        <?php if (isset($_GET['success'])): ?>
          <div class="alert alert-success" id="messages">
            <?= $_GET['success']; ?>
          </div>
        <?php endif; ?>

        <h2 class="text-center mb-4">Sign in</h2>

        <form id="login-form">
          <div class="mb-3">
            <div id="login-result"></div>
            <div class="form-group">
              <input class="form-control" type="email" name="email" placeholder="Enter your email address" required>
            </div>
          </div>

          <div class="mb-3">
            <div class="form-group">
              <input class="form-control" type="password" name="password" placeholder="Enter password" required>
            </div>
          </div>

          <input type="hidden" name="action" value="login">

          <div class="text-center mt-3">
            <button type="submit" id="submitBtn" class="btn btn-primary w-100 p-3">
              <i class="fas fa-user-circle"></i> Sign In
            </button>
          </div>

          <!-- Added Create Account and Forgot Password links -->
          <div class="text-center mt-4">
            <p class="mb-1">
            
            </p>
            <p>
              Don’t have an account?
              <a href="register.php" class="text-decoration-none text-primary fw-semibold">
                Create Account
              </a>
            </p>
          </div>
        </form>
      </div>
    </div>
   </div>
    </div>
      </div>




<?php include 'footer.php'; ?>