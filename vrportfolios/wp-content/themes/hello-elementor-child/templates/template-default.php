<?php

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

    @media (max-width: 767px) {
      .navbar-light .navbar-nav .nav-link {
        border-radius: 0;
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
          <p style="color: #fff;">vrportfolios</p>
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
            <h2>Welcome, <?php echo ucfirst(esc_html($current_user->user_nicename)); ?>!</h2>
            <p>Your portfolio is currently empty. Click top-right <b>Edit</b> to start building your profile.</p>
            <ul>
              <li>Add your name and a short bio</li>
              <li>Showcase your skills and projects</li>
              <li>Share your social links</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
</body>

</html>