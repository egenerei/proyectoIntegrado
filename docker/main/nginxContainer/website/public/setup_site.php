<?php
session_start();
require_once '../includes/user_website_setup.class.php';
require_once '../includes/db.php';
$errorMessage = null;
$account = unserialize($_SESSION['account']);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['subdomain'],$_POST['db_password'], $_POST['confirm_db_password'])) {
        $errorMessage = "Missing required fields.";
    } else {
        try {
            $setup = new user_website_setup($account, $_POST['subdomain'], $_POST['db_password'], $_POST['confirm_db_password'], $domain);
            $setup->register();
            session_destroy();
            echo '<!DOCTYPE html>
              <html>
                  <head>
                    <link rel="stylesheet" href="../css/style.css">
                  </head>
                <body>
                  <a href="http://' . $_POST['subdomain'] . '.' . $domain . '/admin/" class="button">Administration</a>
                </body>
              </html>';
            exit;
        } catch (Exception $e) {
            $errorMessage = $e->getMessage();
        }
    }
}
if (!isset($account)){
    header('Location: index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>DockHost - Create Account</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <?php include("../includes/navbar.php"); ?>
  <div class="fullscreen-center">
    <?php if ($errorMessage): ?>
      <div class="error-message"><?= htmlspecialchars($errorMessage) ?></div>
    <?php endif; ?>
    <form method="POST">
      <h2>Your website settings</h2>
      <input type="text" name="subdomain" placeholder="Subdomain" required>
      <div class="input-with-button">
        <input type="password" id="db_password" name="db_password" placeholder="Database password" required>
        <button type="button" class="toggle-password" onclick="togglePassword('db_password', this)"></button>
      </div>
      <div class="input-with-button">
        <input type="password" id="confirm_db_password" name="confirm_db_password" placeholder="Confirm database password" required>
        <button type="button" class="toggle-password" onclick="togglePassword('confirm_db_password', this)"></button>
      </div>
      <button type="submit">Activate</button>
    </form>
  </div>
  <script src="../js/register.js"></script>
</body>
</html>