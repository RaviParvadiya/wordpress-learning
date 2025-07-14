<?php

get_header();
?>

<section class="progress-section">
    <div class="container progress-section__container">
        <div class="progress-section__header">
            <div class="progress-section__tab">
                <a class="progress-section__tab-active progress-section__tab-title">Printing Steps</a>
                <a href="#" class="progress-section__nav-link progress-section__tab-title">Process Guide</a>
                <a href="#" class="progress-section__nav-link progress-section__tab-title">BEFORE YOU PRINT!!</a>
            </div>

            <!-- Old way using spacer and fixed position -->
            <!-- <div class="progress-section__tab"> -->
                <!-- Empty box placeholder to maintain layout spacing -->
                <!-- <div class="progress-section__tab-spacer"></div> -->

                <!-- Remaining tabs -->
                <!-- <a href="#" class="progress-section__nav-link progress-section__tab-title">Process Guide</a>
                <a href="#" class="progress-section__nav-link progress-section__tab-title">BEFORE YOU PRINT!!</a> -->

                <!-- Real active box placed over the spacer -->
                <!-- <div class="progress-section__tab-active progress-section__tab-title">Printing Steps</div> -->
            <!-- </div> -->
        </div>

        <div class="progress-section__content">
            <p class="progress-section__description">
                Our fabric printing is ON-DEMAND service. Our process takes 6-8 business days and shipping takes 5-7 days depending on the location. Please see the Process Guide for the details and read other notes carefully before printing.
            </p>

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
            <!-- Old steps -->
            <!-- <div class="progress-section__steps">
                <div class="progress-step">
                    <div class="progress-step__icon">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/upload.png" alt="Upload" class="progress-step__icon-img">
                    </div>
                    <h4 class="progress-step__title">Upload Images</h4>
                    <div class="progress-step__arrow">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/right.png" alt="Next" class="progress-step__arrow-img">
                    </div>
                </div>

                <div class="progress-step">
                    <div class="progress-step__icon">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/clicking.png" alt="Order" class="progress-step__icon-img">
                    </div>
                    <h4 class="progress-step__title">Order</h4>
                    <div class="progress-step__arrow">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/right.png" alt="Next" class="progress-step__arrow-img">
                    </div>
                </div>

                <div class="progress-step">
                    <div class="progress-step__icon">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/credit-card.png" alt="Payment" class="progress-step__icon-img">
                    </div>
                    <h4 class="progress-step__title">Payment</h4>
                    <div class="progress-step__arrow">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/right.png" alt="Next" class="progress-step__arrow-img">
                    </div>
                </div>

                <div class="progress-step">
                    <div class="progress-step__icon">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/printer.png" alt="Digital Printing" class="progress-step__icon-img">
                    </div>
                    <h4 class="progress-step__title">Digital Printing</h4>
                    <div class="progress-step__arrow">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/right.png" alt="Next" class="progress-step__arrow-img">
                    </div>
                </div>

                <div class="progress-step">
                    <div class="progress-step__icon">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/drop.png" alt="Post-Processing" class="progress-step__icon-img">
                    </div>
                    <h4 class="progress-step__title">Post-Processing</h4>
                    <div class="progress-step__arrow">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/right.png" alt="Next" class="progress-step__arrow-img">
                    </div>
                </div>

                <div class="progress-step">
                    <div class="progress-step__icon">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/express-delivery.png" alt="Delivery" class="progress-step__icon-img">
                    </div>
                    <h4 class="progress-step__title">Delivery</h4>
                </div>
            </div> -->

        </div>
    </div>
</section>

<!-- Upload Image section -->
<section class="image-section">
    <div class="container">
        <div class="container image-section__container">
            <h2 class="image-section__heading">Upload Image</h2>
            <div class="image-section__upload-image">
                <label class="image-section__placeholder" for="image-upload">
                    Click "Select Image" to upload the image file.
                    <input type="file" id="image-upload" style="display: none;" accept="image/*">
                </label>
                <label class="image-section__btn" for="image-upload">
                    Select Image
                </label>

            </div>
            <label class="custom-checkbox custom-checkbox--image-section" for="check-terms">
                <input type="checkbox" name="check-terms" id="check-terms">
                <span class="checkmark"></span>
                I own the rights or have permission to use this design, and I agree to RealFabric USA Terms of Service and Privacy Policy.
            </label>
        </div>

        <div class="container image-section__notes-container image-section__notes">
            <div class="image-section__notes-card">
                <ul class="custom-list">
                    <li>JPG, PSD, TIF, PNG, and GIF files only. (uploading time may take longer depending on connection)</li>
                    <li>RGB format highly recommended. Visit F&Q for detailed color code.</li>
                    <li>Please note that the screen preview is based on 200 dpi.</li>
                    <li>Images can be uploaded within 100Mb, WIDTH/HEIGHT can NOT exceed 25,000px</li>
                    <li>A single image file can not be LONGER than 3 yards.</li>
                    <li>Please use the "Your Design Size" option to make SMALL size adjustment.</li>
                    <li>Prints sharing the same fabric type will be arranged in a spread pattern on a fabric sheet, with intervals between them.</li>
                    <li>Any uploaded image will be automatically deleted from the server after 6 months.</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- image section -->
<section class="image-section">
    <div class="container progress-section__container image-section__container">
        <h2 class="image-section__heading image-section__heading--lh">My Uploaded Image</h2>
        <div class="image-section__divider-top"></div>
    </div>
</section>

<?php get_footer(); ?>