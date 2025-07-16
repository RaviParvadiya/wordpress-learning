jQuery(document).ready(function ($) {
  $("#option-page-builder-form").on("submit", function (e) {
    e.preventDefault();

    // JS Validation
    const name = $('[name="option_name"]').val().trim();
    const parent = $('[name="parent_menu"]').val().trim();
    if (!name || !parent) {
      alert("Option name and parent menu are required.");
      return;
    }

    const fields = $("#field-builder-body .field-row");
    if (fields.length === 0) {
      alert("At least one field is required.");
      return;
    }

    let valid = true;
    fields.each(function (i) {
      const label = $(this)
        .find('[name^="fields"][name$="[label]"]')
        .val()
        .trim();
      const fname = $(this)
        .find('[name^="fields"][name$="[name]"]')
        .val()
        .trim();
      if (!label || !fname) {
        alert(`Field #${i + 1} is missing a label or name.`);
        valid = false;
        return false; // break
      }
    });

    if (!valid) return;

    // Send to server
    const form = $(this);
    const formData = form.serialize();

    $.post(
      option_ajax.ajax_url,
      {
        action: "save_option_data",
        nonce: option_ajax.nonce,
        data: formData,
      },
      function (response) {
        console.log("response", response);
        if (response.success) {
          console.log("response.data.redirect_url", response.data.redirect_url);
          location.href = response.data.redirect_url; // optional redirect
        } else {
          alert(response.data || "Something went wrong");
        }
      }
    );
  });
});
