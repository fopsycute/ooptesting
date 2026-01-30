
<?php include 'header.php'; ?>
<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-6 col-lg-5">
            <div class="card register-card shadow">
                <div class="card-body p-4">
                    <h3 class="text-center mb-3 fw-bold">Create Account</h3>
                    <p class="text-center text-muted mb-4">
                        Fill in the details below to register
                    </p>

                    <form method="post" class="registerForm">
                        <div id="messages"></div>
                        <div class="mb-3">

                            <label class="form-label">Full Name</label>
                            <input type="text" name="fullname" class="form-control" placeholder="John Doe" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="example@email.com" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Create password" required>
                        </div>
                        <input type="hidden" name="action" value="register">
                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control" placeholder="Repeat password" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 btn-enroll">
                            Register
                        </button>
                    </form>

                    <div class="text-center mt-3">
                        <small>
                            Already have an account?
                            <a href="login.php" class="text-decoration-none">Login here</a>
                        </small>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


<?php include 'footer.php'; ?>