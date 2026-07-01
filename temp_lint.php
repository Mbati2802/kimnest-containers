<?php
$lines = file('F:\Kimnest\pages\product.php');
for ($i = 120; $i <= 135; $i++) {
    echo "L$i: " . bin2hex($lines[$i-1]) . " | " . rtrim($lines[$i-1]) . "\n";
}
