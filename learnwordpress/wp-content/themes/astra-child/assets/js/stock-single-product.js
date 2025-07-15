jQuery(document).ready(function ($) {
  //  Prefix with $ to indicate that the variable holds a jQuery object, not a raw DOM element.
  const $button = document.querySelector(".single_add_to_cart_button");
  const originalText = $button.textContent;

  function checkAvailability() {
    const qty = parseInt($("input.qty").val()) || 1;

    $(".single_add_to_cart_button").prop("disabled", true);
    $button.textContent = "Checking availability...";

    $.post(
      stock_product_ajax.ajax_url,
      {
        action: "get_container_stock_info",
        security: stock_product_ajax.nonce,
        product_id: stock_product_ajax.product_id,
        needed_qty: qty,
      },
      function (response) {
        $button.textContent = originalText;

        // Remove old hidden fields if any
        $("#container_id, #container_name, #container_week").remove();

        if (
          response.success &&
          response.data.available &&
          response.data.container
        ) {
          const c = response.data.container;
          // Inject hidden fields into the add-to-cart form
          const $form = $("form.cart");
          $form.append(
            '<input type="hidden" id="container_id" name="container_id" value="' +
              c.container.id +
              '">'
          );
          $form.append(
            '<input type="hidden" id="container_name" name="container_name" value="' +
              c.container.name +
              '">'
          );
          $form.append(
            '<input type="hidden" id="container_week" name="container_week" value="' +
              c.week +
              '">'
          );
          $(".single_add_to_cart_button").prop("disabled", false);
          $button.textContent = response.data.message;
        } else {
          $(".single_add_to_cart_button").prop("disabled", true);
          // $(".cart input[type=number]").remove();
          $button.textContent = response.data.message;
        }
      },
      "json"
    );
  }

  // Run on page load
  checkAvailability();

  // Run when quantity is changed
  $("input.qty").on("change keyup", function () {
    checkAvailability();
  });
});
