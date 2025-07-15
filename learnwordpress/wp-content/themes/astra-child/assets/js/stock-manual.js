jQuery(document).ready(function ($) {
  /*   function loadProducts() {
    const data = {
      action: "get_products_ajax",
      security: stock_manual_ajax.nonce,
    };

    $.post(
      stock_ajax.ajax_url,
      data,
      function (response) {
        if (response.success) {
          $("#manual-stock-table").html(response.data.table);
        } else {
          $("#manual-stock-table").html(response.data.message);
        }
      },
      "json"
    );
  } */

  //   loadProducts();

  // Submit Add manual stock
  $("#manual-stock-form").on("submit", function (e) {
    e.preventDefault();

    const data =
      $(this).serialize() +
      "&action=save_manual_stock_ajax&security=" +
      stock_manual_ajax.nonce;
    $.post(
      stock_manual_ajax.ajax_url,
      data,
      function (response) {
        if (response.data && response.data.message) {
          const noticeHtml = `<div class="notice notice-${response.data.type} is-dismissible"><p>${response.data.message}</p></div>`;
          $(".ajax-response").html(noticeHtml);
        }

        if (response.success) {
          $("#manual-stock-form")[0].reset();
          $("#manual-stock-form [name='edit_id']").remove();
        }
      },
      "json"
    );
  });
});
