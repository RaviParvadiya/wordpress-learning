jQuery(document).ready(function ($) {
  $("#user-register-form").on("submit", function (e) {
    e.preventDefault();

    const form = $(this);
    $.post(
      register_ajax.ajax_url,
      {
        action: "user_register",
        security: register_ajax.nonce,
        username: form.find('input[name="username"]').val(),
        email: form.find('input[name="email"]').val(),
        password: form.find('input[name="password"]').val(),
      },
      function (response) {
        if (response.success) {
          $("#register-message")
            .text(response.data.message)
            .css("color", "green");
          // window.location.href = "/";
        } else {
          $("#register-message")
            .text(response.data.message)
            .css("color", "red");
        }
      }
    );
  });
});
