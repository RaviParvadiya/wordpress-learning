jQuery(document).ready(function($) {
    $('#repeater-builder-form').on('submit', function(e) {
        e.preventDefault();

        const form = $(this);
        const formData = form.serialize();

        $.post(RepeaterAjax.ajax_url, {
            action: 'save_repeater_data',
            nonce: RepeaterAjax.nonce,
            data: formData
        }, function(response) {
            if (response.success) {                
                location.href = response.data.redirect_url; // optional redirect
            } else {                
            }
        });
    });
});

function set_multi_select_value(arr) {
    const hiddenInput = document.querySelector('input[name="post_type"]');
    if (hiddenInput) {
        hiddenInput.value = arr.join(',');
    }
}