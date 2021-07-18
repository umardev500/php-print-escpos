<?php
require __DIR__ . "/vendor/autoload.php";

use Umardevid\Escpos\Columnify;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\Printer;

$connector = new FilePrintConnector("/dev/usb/lp0");
$printer = new Printer($connector);
$columnify = new Columnify(qtyWidth: 2, priceWidth: 5, separator: "-");

/* Heading */
$printer -> setJustification(Printer::JUSTIFY_CENTER);
$printer -> text("PT INDONESIA TECH\n");
$printer -> text("TELP. 083379154310 Jl. Raya Panimbang Km. 12 No. 45\n\n");

/* Barcode */
$printer -> qrCode("hello world", Printer::QR_ECLEVEL_L, 8);

/* date time */
$dateStr = date("l, j F Y h:i:s\n");
$printer -> text($dateStr);

/* separator */
$printer -> text(Columnify::$separator);

/* Products */
$printer -> setJustification(Printer::JUSTIFY_LEFT); // set to normal
$printer -> text(Columnify::columnifyProduct(1, "Supermie Goreng", 5000));
$printer -> text(Columnify::columnifyProduct(1, "Mie Sedap Kuah", 2500));
$printer -> text(Columnify::columnifyProduct(2, "Indomie Karie", 6000));
$printer -> text(Columnify::columnifyProduct(4, "Mie Gelas", 10000));

/* separator */
$printer -> text(Columnify::$separator);

$infoData = array(
	"total :" => 23000,
	"tunai :" => 25000,
	"kembali :" => 2000
); // dumy data

/* print payment info */
$printer -> text(Columnify::columnifyPayInfo($infoData));

/* separator */
// $printer -> text(Columnify::$separator);

// Feed the printer
$printer -> feed(2);

/* set justifucation to center */
$printer -> setJustification(Printer::JUSTIFY_CENTER);

$printer -> text(">>BIG THANKS<<\n\n");


// Feed the printer
$printer -> feed(2);

$printer -> cut();
$printer -> close();
echo Columnify::$separator . "\n";
