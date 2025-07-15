jQuery(document).ready(function ($) {
  function loadContainers(filters = {}) {
    const data = {
      action: "get_containers_ajax",
      security: stock_ajax.nonce,
      ...filters, // optional filtering params
    };

    $.post(
      stock_ajax.ajax_url,
      data,
      function (response) {
        if (response.success) {
          $("#container-table-body").html(response.data.table); // Replace <tbody>
        } else {
          $("#container-table-body").html(response.data.message);
        }
      },
      "json"
    );
  }

  // Load container list when page is ready
  loadContainers();

  // Submit Add/Edit Container
  $("#stock-container-form").on("submit", function (e) {
    e.preventDefault();

    const data =
      $(this).serialize() +
      "&action=save_container_ajax&security=" +
      stock_ajax.nonce;

    $.post(
      stock_ajax.ajax_url,
      data,
      function (response) {
        const noticeHtml = `<div class="notice notice-${response.data.type} is-dismissible"><p>${response.data.message}</p></div>`;
        $(".ajax-response").html(noticeHtml);

        if (response.success) {
          $("#stock-container-form")[0].reset();
          $("#stock-container-form [name='edit_id']").remove();
          $("#stock-container-form [type='submit']").val("Add Container");
          loadContainers();
        }
      },
      "json"
    );
  });

  // Delete Container (event delegation for dynamic content)
  $("#container-table-body").on("click", ".delete-container", function (e) {
    e.preventDefault();

    if (!confirm("Are you sure you want to delete this container?")) return;

    const id = $(this).data("id");

    $.post(
      stock_ajax.ajax_url,
      {
        action: "delete_container_ajax",
        security: stock_ajax.nonce,
        container_id: id,
      },
      function (response) {
        if (response.success) {
          $(".ajax-response").html(response.data.message);

          // Remove row from table
          $("#container-row-" + id).fadeOut(200, function () {
            $(this).remove();
          });
        } else {
          $(".ajax-response").html(response.data.message);
        }
      },
      "json"
    );
  });

  // Edit Container
  $("#container-table-body").on("click", ".edit-container", function (e) {
    e.preventDefault();

    const id = $(this).data("id");

    $.post(
      stock_ajax.ajax_url,
      {
        action: "get_container_ajax",
        security: stock_ajax.nonce,
        id: id,
      },
      function (response) {
        if (response.success) {
          const c = response.data.container;

          // Change heading and button
          $("h2:contains('Container')").text("Edit Container");
          $("#stock-container-form [type='submit']").val("Update Container");

          // Populate the form
          $("#stock-container-form [name='container_name']").val(c.name);
          $("#stock-container-form [name='container_type']").val(c.type);
          $("#stock-container-form [name='container_status']").val(c.status);
          $("#stock-container-form [name='container_date']").val(c.date);

          // Add hidden input for edit ID
          let $editId = $("#stock-container-form [name='edit_id']");
          if ($editId.length) {
            $editId.val(c.id);
          } else {
            $("#stock-container-form").append(
              `<input type="hidden" name="edit_id" value="${c.id}">`
            );
          }
        } else {
          $(".ajax-response").html(
            `<div class="notice notice-error"><p>${response.data.message}</p></div>`
          );
        }
      },
      "json"
    );
  });
});
