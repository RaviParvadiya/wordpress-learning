jQuery(document).ready(function ($) {
  $("#user-login-form").on("submit", function (e) {
    e.preventDefault();

    const form = $(this);
    $.post(
      login_ajax.ajax_url,
      {
        action: "user_login",
        security: login_ajax.nonce,
        username: form.find('input[name="username"]').val(),
        password: form.find('input[name="password"]').val(),
      },
      function (response) {
        if (response.success) {
          $("#login-message").text(response.data.message).css("color", "green");
          window.location.href = "/" + response.data.username;
        } else {
          $("#login-message").text(response.data.message).css("color", "red");
        }
      }
    );
  });
});
