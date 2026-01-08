<?php
session_start();

echo "<pre>";
print_r($_SESSION);
echo "</pre>";

if (isset($_SESSION['count'])) {
    $_SESSION['count']++;
    echo "Session count: " . $_SESSION['count'];
} else {
    echo "Session count not set!";
}
?>


sudo chown -R ehrmsmmy:ehrmsmmy /var/www/html/Evaluation

sudo chown -R apache:apache /var/www/html/Evaluation

phpinfo();
