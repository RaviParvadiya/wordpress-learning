jQuery(document).ready(function ($) {
  function bindQtyBoxEvents($scope) {
    var $cartForm = $scope.find(".woocommerce-cart-form");
    var $updateBtn = $cartForm.find("button[name='update_cart']");

    // Disable update button by default
    $updateBtn.prop("disabled", true);

    // Enable button on quantity change
    $cartForm.on(
      "input change",
      ".custom-quantity-box input[type='number']",
      function () {
        $updateBtn.prop("disabled", false);
      }
    );

    // Plus/Minus buttons
    $cartForm.on("click", ".custom-quantity-box .qty-btn", function () {
      var $input = $(this).siblings("input[type='number']");
      var val = parseInt($input.val());
      if ($(this).hasClass("minus")) {
        if (val > 1)
          $input
            .val(val - 1)
            .trigger("input")
            .trigger("change");
      } else {
        $input
          .val(val + 1)
          .trigger("input")
          .trigger("change");
      }
    });

    // Disable button after cart update (form submit)
    $cartForm.on("submit", function () {
      $updateBtn.prop("disabled", true);
      // Unbind events to prevent stacking after AJAX reload
      $cartForm.off("input change click");
    });
  }

  // Initial bind
  bindQtyBoxEvents($(document));

  // Re-bind after AJAX cart update (WooCommerce uses fragments)
  $(document.body).on("updated_cart_totals", function () {
    bindQtyBoxEvents($(document));
  });
});

jQuery(document).ready(function($){
  // Auto-dismiss WooCommerce notices after 3 seconds
  setTimeout(function(){
    $('.woocommerce-message, .woocommerce-info, .woocommerce-error').fadeOut(500, function() { $(this).remove(); });
  }, 3000);

  // Optional: Remove all previous notices before adding a new one (for AJAX updates)
  $(document.body).on('wc_fragments_refreshed updated_cart_totals', function() {
    $('.woocommerce-message, .woocommerce-info, .woocommerce-error').not(':last').remove();
  });
});

/* jQuery(document).ready(function ($) {
  $(".custom-quantity-box").each(function () {
    var box = $(this);
    var minus = box.find(".minus");
    var plus = box.find(".plus");
    var input = box.find("input[type=number]");

    minus.on("click", function () {
      var val = parseInt(input.val());
      if (val > 1) {
        input.val(val - 1);
        input.trigger("input").trigger("change");
      }
    });

    plus.on("click", function () {
      var val = parseInt(input.val());
      input.val(val + 1);
      input.trigger("input").trigger("change");
    });
  });

  $(".custom-quantity-box input").on("input change", function () {
    $("button[name='update_cart']").prop("disabled", false);
  });
}); */
