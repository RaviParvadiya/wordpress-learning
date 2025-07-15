<?php

function my_carousal_enqueue_styles()
{
    // Only load on frontend
    if (!is_admin()) {
        wp_enqueue_style(
            'my-carousal-style',
            plugins_url('carousal.css', __FILE__),
            array(),
            filemtime(plugin_dir_path(__FILE__) . 'carousal.css')
        );
    }
}
add_action('wp_enqueue_scripts', 'my_carousal_enqueue_styles');

function my_carousal_enqueue_scripts()
{
    if (!is_admin()) {
        wp_enqueue_script(
            'my-carousal-script',
            plugins_url('carousal.js', __FILE__),
            array(), // Add 'jquery' here if you use jQuery
            filemtime(plugin_dir_path(__FILE__) . 'carousal.js'),
            true // Load in footer
        );
    }
}
add_action('wp_enqueue_scripts', 'my_carousal_enqueue_scripts');

function my_carousal_card_shortcode()
{
    ob_start();
?>
    <div class="bx-wrapper">
        <div class="bx-viewport">
            <ul class="rpp-list" id="repList">
                <?php
                // Carousel items array (add/remove as needed)
                $items = [
                    [
                        'img' => 'https://dynamic.realestateindia.com/prop_images/1333/1382216_1-200x200.jpg',
                        'name' => 'Varunjit Singh',
                        'desc' => 'posted a 4 BHK Flats & Apartments for Sale in Sector 56, Gurgaon',
                        'date' => '13 Jun 2025'
                    ],
                    [
                        'img' => 'https://dynamic.realestateindia.com/prop_images/3824460/1382195_1-200x200.jpg',
                        'name' => 'Lakhi Sherani',
                        'desc' => 'posted a 2 BHK Flats & Apartments for Sale in Manpada, Thane',
                        'date' => '13 Jun 2025'
                    ],
                    [
                        'img' => 'https://dynamic.realestateindia.com/prop_images/3842602/1382187_1-200x200.jpg',
                        'name' => 'Saahithi',
                        'desc' => 'posted a 2 BHK Flats & Apartments for Rent in Kr Puram, Bangalore',
                        'date' => '13 Jun 2025'
                    ],
                    [
                        'img' => 'https://dynamic.realestateindia.com/prop_images/1440302/1382184_1-200x200.jpeg',
                        'name' => 'Mr. Kalpesh Shah',
                        'desc' => 'posted a Warehouse/Godown for Rent in Bhiwandi, Thane',
                        'date' => '13 Jun 2025'
                    ],
                    [
                        'img' => 'https://dyimg2.realestateindia.com/prop_images/2673998/1382167_1-200x200.jpg',
                        'name' => 'Vishal J Pandey',
                        'desc' => 'posted a Warehouse/Godown for Rent in Bhiwandi, Thane',
                        'date' => '13 Jun 2025'
                    ],
                    [
                        'img' => 'https://dyimg2.realestateindia.com/prop_images/1070118/1382158_1-200x200.jpeg',
                        'name' => 'Abbas Sayyed',
                        'desc' => 'posted a Office Space for Rent in Nahur West, Mumbai',
                        'date' => '13 Jun 2025'
                    ],
                    [
                        'img' => 'https://dyimg2.realestateindia.com/prop_images/3071169/1382151_1-200x200.jpg',
                        'name' => 'Rinku Singh Jangra',
                        'desc' => 'posted a Individual Houses for Rent in Narwana, Jind',
                        'date' => '13 Jun 2025'
                    ],
                    [
                        'img' => 'https://dyimg2.realestateindia.com/prop_images/971618/1382088_1-200x200.jpg',
                        'name' => 'Mr. Dhruv Jain',
                        'desc' => 'posted a 1 BHK Flats & Apartments for Sale in Mahal Road, Jaipur',
                        'date' => '13 Jun 2025'
                    ],
                    [
                        'img' => 'https://dyimg2.realestateindia.com/prop_images/1234328/1380983_1-200x200.jpg',
                        'name' => 'arvind soni',
                        'desc' => 'posted a Individual Houses for Sale in Riddhi Siddhi Colony, Rajnandgaon',
                        'date' => '13 Jun 2025'
                    ],
                    [
                        'img' => 'https://dyimg1.realestateindia.com/prop_images/2034116/1382072_1-200x200.jpeg',
                        'name' => 'Sk tarip uddin',
                        'desc' => 'posted a Flats & Apartments for Rent in Chinar Park, Kolkata',
                        'date' => '13 Jun 2025'
                    ],
                ];

                foreach ($items as $item) {
                ?>
                    <li>
                        <div class="rpp-item">
                            <div class="rpp-img">
                                <img src="<?php echo esc_url($item['img']); ?>" alt="" height="44" width="44">
                            </div>
                            <div class="rpp-info">
                                <p><strong><?php echo esc_html($item['name']); ?></strong> <?php echo esc_html($item['desc']); ?></p>
                                <div class="post-ago"><?php echo esc_html($item['date']); ?></div>
                            </div>
                        </div>
                    </li>
                <?php
                }
                ?>
            </ul>
        </div>
        <div class="bx-controls bx-has-pager">
            <div class="bx-pager bx-default-pager" id="bxPager">
                <?php for ($i = 0; $i < count($items); $i++): ?>
                    <div class="bx-pager-item">
                        <a href="#" data-slide-index="<?php echo $i; ?>" class="bx-pager-link<?php echo $i === 0 ? ' active' : ''; ?>"><?php echo $i + 1; ?></a>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
    </div>
<?php
    return ob_get_clean();
}
add_shortcode('realestate_cards', 'my_carousal_card_shortcode');
