jQuery(document).ready(function ($) {
  function checkCartStock($input) {
    var product_id = $input
      .closest("tr")
      .find("[data-product_id]")
      .data("product_id");
    var qty = parseInt($input.val());

    if (isNaN(qty)) qty = 0;

    $.post(
      stock_cart_ajax.ajax_url,
      {
        action: "check_cart_stock_ajax",
        security: stock_cart_ajax.nonce,
        product_id: product_id,
        quantity: qty,
      },
      function (response) {

        if (qty <= 0 || !response.success) {
          $("button[name='update_cart']").prop("disabled", true);
        } else {
          $("button[name='update_cart']").prop("disabled", false);
        }

        $input
          .closest("tr")
          .find(".expected-delivery-msg")
          .html(
            '<strong style="color:' +
              (response.success ? "#008000" : "#CC0000") +
              ';">' +
              response.data.message +
              "</strong>"
          );
      }
    );
  }

  // On quantity change
  $(".cart input.qty").on("change keyup", function () {
    checkCartStock($(this));
  });
});
