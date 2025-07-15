jQuery(document).ready(function ($) {
  // Get container_id from hidden input
  var container_id = $("#assign-product-form [name='container_id'").val();

  // Load products
  function loadAssignedProducts() {
    const data = {
      action: "get_assigned_products_ajax",
      security: stock_container_ajax.nonce,
      container_id: container_id,
    };

    $.post(
      stock_container_ajax.ajax_url,
      data,
      function (response) {
        if (response.success) {
          $("#assigned-products-table-body").html(response.data.table);
          $('#save-button-wrapper').show();
        } else {
          $("#assigned-products-table-body").html(response.data.message);
          $('#save-button-wrapper').hide();
        }
      },
      "json"
    );
  }

  loadAssignedProducts();

  // Assign product
  $("#assign-product-form").on("submit", function (e) {
    e.preventDefault();

    const data =
      $(this).serialize() +
      "&action=assign_product_ajax&security=" +
      stock_container_ajax.nonce +
      "&container_id=" +
      container_id;

    $.post(
      stock_container_ajax.ajax_url,
      data,
      function (response) {
        $(".ajax-response").html(response.data.message);

        if (response.success) {
          $("#assign-product-form")[0].reset();
          loadAssignedProducts();
        }
      },
      "json"
    );
  });

  // Edit product
  $("#update-quantity-form").on("submit", function (e) {
    e.preventDefault();

    const data =
      $(this).serialize() +
      "&action=update_assignment_quantity_ajax&security=" +
      stock_container_ajax.nonce;

    $.post(
      stock_container_ajax.ajax_url,
      data,
      function (response) {
        if (response.success) {
          $(".ajax-response").html(response.data.message);
        }
      },
      "json"
    );
  });

  // Delete product
  $("#assigned-products-table-body").on(
    "click",
    ".delete-assignment",
    function (e) {
      e.preventDefault();

      if (!confirm("Are you sure you want to remove this product?")) return;

      const id = $(this).data("id");

      $.post(
        stock_container_ajax.ajax_url,
        {
          action: "delete_assignment_ajax",
          security: stock_container_ajax.nonce,
          id: id,
        },
        function (response) {
          if (response.success) {
            $(".ajax-response").html(response.data.message);
            $("#product-row-" + id).fadeOut(200, function () {
              $(this).remove;
            });
          } else {
            $(".ajax-response").html(response.data.message);
          }
        },
        "json"
      );
    }
  );
});
