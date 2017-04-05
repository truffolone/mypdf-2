<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Require composer autoload
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/lib/Genpdf.php';
require_once __DIR__ . '/lib/added/Sutter.php';

use lib\Genpdf;

$pdf = new mPDF();

$fullpath = "./pdf/culoduro.pdf";

$pdf->setFooter("footer");
$pdf->WriteHTML(file_get_contents("./css/embedded.css"), 1);
$pdf->WriteHTML(file_get_contents("./partials/complete.html"), 2);
$pdf->Output($fullpath, 'F');

$pdf->Output();