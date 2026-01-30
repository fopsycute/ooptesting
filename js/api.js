
$(document).ready(function(){
    $('.registerForm').submit(function(event){
        event.preventDefault();

        var form = this;
        var formData = new FormData(form);

        // Get site URL from hidden input

        var ajaxUrl = "script/auth.php"; // endpoint

        $.ajax({
            type: 'POST',
            url: ajaxUrl,
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            beforeSend: function(){
                $('.btn-enroll').html('Submitting...').prop('disabled', true);
                $('#messages').hide().removeClass('alert-success alert-danger');
            },
            success: function(response){
                if (response.status === 'success') {
                    // Redirect to login with success message
                    var msg = encodeURIComponent(response.messages || 'Registration successful!');
                    window.location.href = "login.php?success=" + msg;
                } else {
                    $('#messages')
                        .addClass('alert alert-danger')
                        .html(response.messages || 'Submission failed. Please check your inputs.')
                        .show();

                    $('html, body').animate({
                        scrollTop: $('#messages').offset().top - 100
                    }, 500);
                }
            },
            error: function(xhr){
                $('#messages')
                    .addClass('alert alert-danger')
                    .text('An error occurred while submitting the form. Please try again.')
                    .show();

                $('html, body').animate({
                    scrollTop: $('#messages').offset().top - 100
                }, 500);

                console.error(xhr.responseText);
            },
            complete: function(){
                $('.btn-enroll').html('<i class="bi bi-check-circle me-2"></i> Submit').prop('disabled', false);
            }
        });
    });
});


//login authentication
$(document).ready(function() {
    $('#login-form').submit(function(e) {
        e.preventDefault();

        var formData = $(this).serialize();
        var ajaxUrl = "script/auth.php"; // ✅ removed siteUrl

        $('#submitBtn').prop('disabled', true).text('Logging in...');

        $.ajax({
            type: 'POST',
            url: ajaxUrl,
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    if (response.status === 'inactive') {
                        $('#login-result').html(
                            '<div class="alert alert-warning">' + response.message + '</div>'
                        );
                        return;
                    }

                    // ✅ redirect directly from PHP response
                    $('#login-result').html(
                        '<div class="alert alert-success">' + response.message + '</div>'
                    );

                    setTimeout(function() {
                        window.location.href = response.redirect;
                    }, 1000);
                } else {
                    $('#login-result').html(
                        '<div class="alert alert-danger">' + 
                        (response.error || 'Invalid credentials') + 
                        '</div>'
                    );
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                $('#login-result').html(
                    '<div class="alert alert-danger">Error: ' + error + '</div>'
                );
            },
            complete: function() {
                $('#submitBtn').prop('disabled', false).text('Login');
            }
        });
    });
});


// add forum
// add blog
$(document).ready(function () {

    // Ensure this handler only attaches once
    $('#addForum').off('submit').on('submit', function (e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);
        const ajaxUrl = "script/blog.php"; // 🔥 direct endpoint

        // Prevent double submit
        $('#submitBtn').prop('disabled', true).text('Submitting...');
        $('#messages').removeClass('alert alert-success alert-danger').hide();

        $.ajax({
            type: 'POST',
            url: ajaxUrl,
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',

            success: function (response) {
                if (response.status === 'success') {
                    $('#messages')
                        .addClass('alert alert-success')
                        .html(response.message)
                        .fadeIn();

                    form.reset();

                    setTimeout(() => {
                        alert(response.message || "Forum post created successfully!");
                        location.reload();
                    }, 500);
                } else {
                    $('#messages')
                        .addClass('alert alert-danger')
                        .html(response.message)
                        .fadeIn();

                    $('html, body').animate({
                        scrollTop: $('#messages').offset().top - 100
                    }, 600);
                }
            },

            error: function (xhr) {
                console.error(xhr.responseText);
                $('#messages')
                    .addClass('alert alert-danger')
                    .text('An error occurred while submitting. Please try again.')
                    .fadeIn();

                $('html, body').animate({
                    scrollTop: $('#messages').offset().top - 100
                }, 600);
            },

            complete: function () {
                $('#submitBtn').prop('disabled', false).text('Create');
            }
        });
    });

});
