<?php

// Fetch user meta (portfolio data)
$fname = get_user_meta($user->ID, 'portfolio_fname', true);
$lname = get_user_meta($user->ID, 'portfolio_lname', true);
$avatar = get_user_meta($user->ID, 'portfolio_avatar', true);
$role = get_user_meta($user->ID, 'portfolio_role', true);
$description = get_user_meta($user->ID, 'portfolio_description', true);
$about = get_user_meta($user->ID, 'portfolio_about', true);
$skills = json_decode(get_user_meta($user->ID, 'portfolio_skills', true), true) ?: [];
$work_description = get_user_meta($user->ID, 'portfolio_work_description', true);
$projects = json_decode(get_user_meta($user->ID, 'portfolio_projects', true), true) ?: [];
$github_url = get_user_meta($user->ID, 'portfolio_github', true);
$twitter_url = get_user_meta($user->ID, 'portfolio_twitter', true);
$linkedin_url = get_user_meta($user->ID, 'portfolio_linkedin', true);
$selected_template = get_user_meta($user->ID, 'portfolio_template', true);

$predefined_skills = json_decode(get_option('portfolio_skills', []), true);
$user_selected_skills = json_decode(get_user_meta($user->ID, 'portfolio_skills', true), true);
$other_skills = json_decode(get_user_meta($user->ID, 'portfolio_other_skills', true), true) ?: [];

$current_user = wp_get_current_user();
$is_own_profile = ($current_user->ID == $user->ID);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap"
    rel="stylesheet" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
  <style>
    a {
      text-decoration: none !important;
    }

    * {
      font-family: "Inter", sans-serif;
    }

    header .navbar {
      background: linear-gradient(to left, #5b6694, #9132a9, #271b3a);
    }

    .nav-item.active {
      background: linear-gradient(45deg, #64d7f0, #9e3ab1);
      padding: 10px 30px !important;
      border-radius: 50px;
      color: #fff !important;
      font-size: 16px;
      font-weight: 700;
      display: inline-block;
    }

    .navbar-light .navbar-nav .nav-link,
    .navbar-light .navbar-nav .nav-link:hover {
      color: #fff;
    }

    .navbar-nav {
      gap: 50px;
    }

    .navbar-toggler {
      margin-left: auto;
    }

    @media (max-width: 991px) {
      .navbar-nav {
        gap: 0px;
      }
    }

    #Home {
      background: linear-gradient(to right, #271b3a, #9132a9, #5b6694);
      padding: 45px 30px 0;
      color: #fff;
    }

    #Home .content {
      padding: 50px 0;
    }

    #Home .title {
      font-size: 60px;
    }

    #Home .subtitle {
      font-size: 36px;

      background: linear-gradient(45deg, #64d7f0, #9e3ab1);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      display: inline-block;
    }

    #Home .description {
      font-size: 16px;
    }

    #Home .contact-us,
    .custombtn {
      background: linear-gradient(45deg, #64d7f0, #9e3ab1);
      padding: 10px 20px;
      border-radius: 50px;
      color: #fff;
      font-size: 16px;
      font-weight: 700;
      margin-top: 10px;
      display: inline-block;
      cursor: pointer;
    }

    #Home .image {
      width: fit-content;
      margin: auto;
      border-radius: 50%;
      overflow: hidden;
      box-shadow: 0 0 10px #000;
      max-width: 400px;
    }

    #About {
      position: relative;
      background-color: #2f1d42;
      color: #fff;
      padding: 60px 40px;
      text-align: center;
    }

    #About .content {
      max-width: 800px;
      margin: auto;
    }

    #About .description {
      font-size: 16px;
    }

    .section_title {
      font-size: 54px;
      background: linear-gradient(45deg, #64d7f0, #9e3ab1);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      display: inline-block;
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    }

    .grid-lines {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: 0;
      background-image: linear-gradient(rgba(255 255 255 / 0.1) 1px,
          transparent 1px),
        linear-gradient(90deg, rgba(255 255 255 / 0.1) 1px, transparent 1px);
      background-size: 100px 100px;
      opacity: 0.3;
    }

    .technologies_ino {
      display: grid;
      grid-template-columns: repeat(6, 1fr);
      gap: 20px;
      align-items: center;
      margin-top: 30px;
    }

    .technologies_ino img {
      max-width: 65px;
      max-height: 65px;
    }

    .technologies_ino .logo {
      height: 100px;
      display: flex;
      width: 100px;
      padding: 20px 10px;
      background: #fff;
      border-radius: 50%;
      align-items: center;
      justify-content: center;
      margin: auto;
    }

    #Work {
      padding: 60px 40px;
      background: #0f0426;
      color: #fff;
    }

    #Work .description {
      font-size: 16px;
    }

    #Work .work_row {
      position: relative;
    }

    #Work .work_row .content {
      padding: 60px 0;
    }

    #Work .work_row .image {}

    #Work .work_row img {
      width: 100%;
      position: relative;
      z-index: 1;
      box-shadow: 0 0 15px 5px rgba(175, 62, 238, 0.5);
      border-radius: 15px;
    }

    #Work .work_row .content .subtitle {
      color: #00ffee;
      font-size: 16px;
    }

    #Work .work_row .content .project_title {
      font-size: 38px;
    }

    #Work .work_row .content .description {
      max-width: 613px;
      background: #2b1c3b;
      padding: 20px;
    }

    #Work .work_row .content .icons {
      display: flex;
      list-style: none;
      padding-left: 0;
      gap: 20px;
      margin-top: 30px;
    }

    #Work .work_row .content .icons a {
      color: #fff;
    }

    #Work .work_row .content .icons a i {
      font-size: 24px;
    }

    .contact-info {
      flex: 1;
      margin-right: 20px;
      padding: 30px 20px;
    }

    .contact-info h2 {
      color: #00bcd4;
    }

    .info p {
      margin: 10px 0;
      display: flex;
      align-items: center;
    }

    .message-form {
      background: rgba(255, 255, 255, 0.1);
      padding: 20px;
      border-radius: 10px;
    }

    input,
    textarea {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border: none;
      border-radius: 5px;
      background: rgba(255, 255, 255, 0.2);
      color: white;
    }

    input::placeholder,
    textarea::placeholder {
      color: #cccccc;
    }

    #Contact {
      color: #fff;
      background: #0f0426;
      padding: 100px 0;
    }

    input:focus-visible,
    textarea:focus-visible {
      outline: unset;
    }

    #Contact .info .fa {
      background: linear-gradient(45deg, #64d7f0, #9e3ab1);
      height: 40px;
      width: 40px;
      display: grid;
      place-content: center;
      border-radius: 50%;
      font-size: 20px;
    }

    @media (max-width: 767px) {
      .technologies_ino {
        grid-template-columns: repeat(4, 1fr);
      }

      #Work .work_row {
        margin-top: 30px;
      }

      #Work .work_row .row {
        flex-direction: column-reverse !important;
      }

      #Work .work_row .content {
        padding: 25px 0;
      }

      #Contact,
      #Home,
      #About,
      #Work {
        padding: 30px 15px;
      }

      .contact-info {
        padding: 0;
      }

      .contact-info {
        margin: 0;
      }

      .navbar-light .navbar-nav .nav-link {
        border-radius: 0;
      }
    }

    @media (max-width: 580px) {
      .technologies_ino {
        grid-template-columns: repeat(3, 1fr);
      }
    }

    @media (max-width: 450px) {
      .technologies_ino {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    .logout-btn {
      background: linear-gradient(45deg, #ff6a00, #ee0979);
      color: #fff !important;
      font-weight: 700;
      border-radius: 30px;
      padding: 10px 28px;
      margin-left: 30px;
      font-size: 16px;
      box-shadow: 0 2px 10px rgba(238, 9, 121, 0.15);
      transition: background 0.3s, box-shadow 0.3s;
      display: flex;
      align-items: center;
      gap: 8px;
      border: none;
      text-decoration: none !important;
    }

    .logout-btn:hover {
      background: linear-gradient(45deg, #ee0979, #ff6a00);
      color: #fff !important;
      box-shadow: 0 4px 20px rgba(238, 9, 121, 0.25);
      text-decoration: none !important;
    }

    .edit-btn {
      background: linear-gradient(45deg, #00c6ff, #0072ff);
      color: #fff !important;
      font-weight: 700;
      border-radius: 30px;
      padding: 10px 28px;
      margin-left: 10px;
      font-size: 16px;
      box-shadow: 0 2px 10px rgba(0, 114, 255, 0.15);
      transition: background 0.3s, box-shadow 0.3s;
      display: flex;
      align-items: center;
      gap: 8px;
      border: none;
      text-decoration: none !important;
    }

    .edit-btn:hover {
      background: linear-gradient(45deg, #0072ff, #00c6ff);
      color: #fff !important;
      box-shadow: 0 4px 20px rgba(0, 114, 255, 0.25);
      text-decoration: none !important;
    }

    .profile-avatar {
      width: 44px;
      height: 44px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid #9132a9;
      box-shadow: 0 2px 8px rgba(145, 50, 169, 0.10);
      background: #fff;
      transition: box-shadow 0.2s, border-color 0.2s;
      margin-right: 10px;
    }

    .profile-avatar:hover {
      box-shadow: 0 4px 16px rgba(145, 50, 169, 0.18);
      border-color: #64d7f0;
    }
  </style>
</head>

<body>
  <header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <button
        class="navbar-toggler"
        type="button"
        data-toggle="collapse"
        data-target="#navbarNavAltMarkup"
        aria-controls="navbarNavAltMarkup"
        aria-expanded="false"
        aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div
        class="collapse navbar-collapse justify-content-center"
        id="navbarNavAltMarkup">
        <div class="navbar-nav">
          <!-- DONE: Add a profile which shows icon and on click redirects to admin profile section -->
          <?php // if (is_user_logged_in()) : 
          ?>
          <!-- <div class="nav-profile ml-auto d-flex align-items-center">
              <a href="<?php // echo admin_url('profile.php'); 
                        ?>" class="profile-link">
                <img src="<?php // echo esc_url($avatar); 
                          ?>" alt="Profile" class="profile-avatar">
              </a>
            </div> -->
          <?php // endif; 
          ?>
          <a class="nav-item nav-link active" href="Home">Home <span class="sr-only"></span></a>
          <a class="nav-item nav-link" href="#About">About</a>
          <a class="nav-item nav-link" href="#Work">Work</a>
          <a class="nav-item nav-link" href="#Contact">Contact</a>
        </div>
        <?php if (is_user_logged_in()) : ?>
          <a href="<?php echo wp_logout_url(home_url('/')); ?>" class="logout-btn ml-auto">Logout <i class="fa fa-sign-out-alt"></i></a>
          <?php if ($is_own_profile) : ?>
            <a href="<?php echo admin_url('admin.php?page=my-portfolio'); ?>" class="edit-btn ml-2">Edit <i class="fa fa-edit"></i></a>
          <?php endif; ?>
        <?php endif; ?>
      </div>
    </nav>
  </header>
  <section id="Home">
    <div class="container">
      <div class="align-items-center justify-content-between row">
        <div class="col-md-6">
          <div class="content">
            <h2 class="title">Hi, I'm <?php echo ucfirst(esc_html($fname)); ?></h2>
            <h4 class="subtitle"><?php echo esc_html($role); ?></h4>
            <p class="description">
              <?php echo esc_html($description); ?>
            </p>
            <a href="#Contact" class="contact-us">Contact</a>
          </div>
        </div>
        <div class="col-md-6">
          <div class="image text-center">
            <img src="<?php echo $avatar ? esc_url($avatar) : get_stylesheet_directory_uri() . '/templates/profile.png'; ?>" alt="" class="img-fluid" />
          </div>
        </div>
      </div>
    </div>
  </section>
  <section id="About">
    <div class="grid-lines"></div>
    <div class="container">
      <div class="content">
        <h2 class="section_title">About Us</h2>
        <p class="description">
          <?php echo esc_html($about); ?>
        </p>
        <div class="technologies_ino">
          <?php foreach ($predefined_skills as $skill) : ?>
            <?php if (in_array($skill['title'], $user_selected_skills)) : ?>
              <div><span class="logo"><img src="<?php echo esc_url($skill['link']); ?>" class="img-fluid" /></span></div>
            <?php endif; ?>
          <?php endforeach; ?>

          <?php foreach ($other_skills as $skill) : ?>
            <?php if (!empty($skill['link'])) : ?>
              <div><span class="logo"><img src="<?php echo esc_url($skill['link']); ?>" class="img-fluid" /></span></div>
            <?php else : ?>
              <div><span class="logo" style="color:#271b3a; font-weight:600;"> <?php echo esc_html($skill['title']); ?> </span></div>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>
  <section id="Work">
    <div class="container">
      <div class="text-center">
        <h2 class="section_title">Work Experince</h2>
        <p class="description">
          <?php echo esc_html($work_description); ?>
        </p>
      </div>
      <div class="work_row">
        <?php foreach ($projects as $i => $project) : ?>
          <div class="row align-items-center <?php echo $i % 2 === 1 ? 'flex-row-reverse' : '' ?>">
            <div class="col-md-7">
              <div class="content">
                <span class="subtitle">Featured Project</span>
                <h4 class="project_title"><?php echo esc_html($project['title']); ?></h4>
                <p class="description">
                  <?php echo esc_html($project['description']); ?>
                </p>
                <ul class="icons">
                  <li>
                    <a href="#"><span> <i class="fa-brands fa-github"></i></span></a>
                  </li>
                  <li>
                    <a href="#"><span> <i class="fa-brands fa-x-twitter"></i></span></a>
                  </li>
                  <li>
                    <a href="#"><span><i class="fa-brands fa-linkedin"></i> </span></a>
                  </li>
                </ul>
              </div>
            </div>
            <div class="col-md-5">
              <div class="image">
                <img src="<?php echo isset($project['screenshot']) ? esc_url($project['screenshot']) : get_stylesheet_directory_uri() . '/templates/Screenshot_33.png'; ?>" alt="" />
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <section id="Contact">
    <div class="text-center mb-5">
      <h2 class="section_title">Contact</h2>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <div class="contact-info">
            <h2>Drop me a message</h2>
            <p>
              A web app for visualizing personalized Spotify data. View your
              top artists, top tracks, recently played tracks, and detailed
              audio information about each track.
            </p>
            <div class="info">
              <p>
                <span style="display: inline-block; margin-right: 10px"><span class="fa fa-phone"></span></span>
                +91 7894567890
              </p>
              <p>
                <span style="display: inline-block; margin-right: 10px"><span class="fa fa-envelope"></span></span>
                <?php echo esc_html($current_user->user_email); ?>
              </p>
              <p>
                <span style="display: inline-block; margin-right: 10px"><span class="fa fa-map-marker"></span></span>
                123 Demo st. 84020 Bihar, India
              </p>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="message-form">
            <form id="contact-form">
              <input type="hidden" name="receiver_id" id="receiver_id" value="<?php echo esc_attr($user->ID); ?>">
              <input type="hidden" name="sender_id" id="sender_id" value="<?php echo is_user_logged_in() ? esc_attr($current_user->ID) : 0; ?>">
              <input type="text" name="name" placeholder="Name" required />
              <input type="email" name="email" placeholder="Email" required />
              <textarea name="message" placeholder="Message" required></textarea>
              <button type="submit" class="custombtn">Send message</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
  <script>
    jQuery(document).ready(function($) {
      $('#contact-form').on('submit', function(e) {
        e.preventDefault();

        const data = $(this).serialize();
        $.post(
          '<?php echo admin_url('admin-ajax.php'); ?>',
          data + '&action=submit_portfolio_message',
          function(response) {
            if (response.success) {
              $('#contact-form')[0].reset();
              console.log(response);
            } else {
              console.log(response);
            }
          },
          'json'
        )
      })
    });
  </script>
</body>

</html>