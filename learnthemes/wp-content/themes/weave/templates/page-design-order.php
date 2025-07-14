<?php

get_header();
?>

<section class="order-section">
    <div class="container order-section__container">
        <div class="order-section__notice">
            <p>The image below is the actual output size and layout. If it is not the size you want, please use the "Your Design Size" below.</p>
        </div>

        <div class="order-section__grid">
            <div class="order-section__left">
                <!-- Image -->
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/untitled.jpg" alt="Design Preview" style="width: 100%; max-width: 600px;">
            </div>

            <div class="order-section__right">
                <!-- Right-side content (fabric select, dropdowns, etc.) -->

                <div class="order-section__right-info">
                    <p>Choose Fabric</p>
                    <span>
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/info.png" alt="" width="13" height="13">
                        <p class="order-section__right-info--text">Fabric Information</p>
                    </span>
                </div>

                <!-- TODO: might use grid column and for row if need flex -->
                <div class="order-section__right-options">
                    <div class="order-section__right-row">
                        <!-- TODO: this cols are slecteable -->
                        <div class="col">
                            <div class="order-section__right-options-card order-section__right-options-card--active">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/cloud.png" alt="Cloud" width="40" height="23.44">
                                <p>Cotton</p>
                                <!-- TODO: need to adjust right space for each card as it is not 130x120 -->
                                <h5>100% cotton</h5>
                            </div>
                        </div>

                        <div class="col">
                            <div class="order-section__right-options-card">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/hash.png" alt="Cloud" width="25" height="25">
                                <p>Polyester</p>
                                <h5>100% Polyester</h5>
                            </div>
                        </div>

                        <div class="col">
                            <div class="order-section__right-options-card">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/silk.png" alt="Cloud" width="25" height="25">
                                <p>Silk</p>
                                <h5>100% Natural Silk</h5>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="order-section__right-dropdown">
                    <div class="custom-dropdown">
                        <span class="custom-dropdown__label">Plain Weave</span>
                        <img class="custom-dropdown__arrow" src="<?php echo get_template_directory_uri(); ?>/assets/images/down_1.png" alt="Dropdown arrow">
                    </div>
                </div>

                <div class="order-section__right-details">
                    <h2 class="order-section__details-title">Selected Fabric</h2>
                    <div class="order-section__details-card">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/untitled_2.jpg" alt="" width="120" height="120">
                        <div class="order-section__card-content">
                            <h3 style="margin: 0; margin-top: 15px">Cotton Plain Weave</h3>
                            <p style="margin-bottom: 0;">Everyday generic cotton fabric</p>
                            <p style="margin: 0;">Usage: Apparel, Home textile, Soft Toy</p>
                            <p style="margin-bottom: 0;">(w:43 X h:36)</p>
                        </div>
                        <a href="#" class="order-section__card-btn">Detail</a>
                    </div>
                </div>

                <div class="order-section__right-info order-section__right-info--qty">
                    <p>Choose Quantity</p>
                    <span>
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/info.png" alt="" width="13" height="13">
                        <p class="order-section__right-info--text">Price Chart</p>
                    </span>
                </div>
                <label class="custom-checkbox custom-checkbox--qty" for="check-fat">
                    <input type="checkbox" id="check-fat" name="check-fat" />
                    <span class="checkmark custom-checkbox__checkmark--qty"></span>
                    Fat Quarter (21.5 in x 18 in) : $ 13.90
                </label>
                <label class="custom-checkbox custom-checkbox--qty" for="check-yard">
                    <input type="checkbox" id="check-yard" name="check-yard" checked />
                    <span class="checkmark custom-checkbox__checkmark--qty"></span>
                    1 Yard(s)(43 in x 36 in) : $ 18.90
                </label>
                <!-- <label class="custom-checkbox" for="customCheck1">
                    <input type="checkbox" id="customCheck1" name="accept_terms" />
                    <span class="checkmark"></span>
                    Your label text
                </label> -->

                <div class="quantity-selector">
                    <button type="button" class="quantity-btn quantity-btn--minus">−</button>
                    <span class="quantity-value">1</span>
                    <button type="button" class="quantity-btn quantity-btn--plus">+</button>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="progress-section__design">
    <div class="container progress-steps_container">
        <div class="progress-section__row">

            <div class="progress-section__col">
                <div class="progress-section__col-content">
                    <div class="progress-section__col-icon">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/upload.png" alt="Upload" class="progress-step__icon-img">
                    </div>
                    <h4 class="progress-section__col-title">
                        Upload Images
                    </h4>
                </div>
                <div class="progress-section__col-next">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/right.png" alt="Next" class="progress-step__arrow-img">
                </div>
            </div>

            <div class="progress-section__col">
                <div class="progress-section__col-content">
                    <div class="progress-section__col-icon">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/clicking.png" alt="Order" class="progress-step__icon-img">
                    </div>
                    <h4 class="progress-section__col-title">
                        Order
                    </h4>
                </div>
                <div class="progress-section__col-next">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/right.png" alt="Next" class="progress-step__arrow-img">
                </div>
            </div>

            <div class="progress-section__col">
                <div class="progress-section__col-content">
                    <div class="progress-section__col-icon">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/credit-card.png" alt="Payment" class="progress-step__icon-img">
                    </div>
                    <h4 class="progress-section__col-title">
                        Payment
                    </h4>
                </div>
                <div class="progress-section__col-next">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/right.png" alt="Next" class="progress-step__arrow-img">
                </div>
            </div>

            <div class="progress-section__col">
                <div class="progress-section__col-content">
                    <div class="progress-section__col-icon">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/printer.png" alt="Digital Printing" class="progress-step__icon-img">
                    </div>
                    <h4 class="progress-section__col-title">
                        Digital Printing
                    </h4>
                </div>
                <div class="progress-section__col-next">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/right.png" alt="Next" class="progress-step__arrow-img">
                </div>
            </div>

            <div class="progress-section__col">
                <div class="progress-section__col-content">
                    <div class="progress-section__col-icon">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/drop.png" alt="Post-Processing" class="progress-step__icon-img">
                    </div>
                    <h4 class="progress-section__col-title">
                        Post-Processing
                    </h4>
                </div>
                <div class="progress-section__col-next">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/right.png" alt="Next" class="progress-step__arrow-img">
                </div>
            </div>

            <div class="progress-section__col">
                <div class="progress-section__col-content">
                    <div class="progress-section__col-icon">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/express-delivery.png" alt="Delivery" class="progress-step__icon-img">
                    </div>
                    <h4 class="progress-section__col-title">
                        Delivery
                    </h4>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
get_footer();
?>