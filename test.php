<?php
require __DIR__ . "/vendor/autoload.php";

use Umardevid\Escpos\Columnify;


$columnify = new Columnify(qtyWidth: 2, priceWidth: 8, separator: "-");
Columnify::columnifyProduct(11, "Supermie Goreng", 10000000);
// echo Columnify::$separator . "\n";
Columnify::columnifyProduct(11, "Mie Sedap Goreng", 10000);
Columnify::columnifyProduct(12, "Indomie Goreng", 2500);
Columnify::columnifyProduct(12, "Indomie", 2500);
Columnify::columnifyProduct(12, "Mie Gelas", 2500);
echo Columnify::$separator . "\n";
/* Info array */
$infoData = array("total :" => 150000, "tunai :" => 10, "kembali :" => 5000);
// Columnify::columnifyPayInfo($infoData);
/* separator */
// echo Columnify::$separator . "\n";

echo "\n";
echo date("l, j F Y h:i:s") . "\n";
