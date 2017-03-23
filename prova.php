<?php

// Require composer autoload
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/lib/Genpdf.php';

use lib\Genpdf;

$pdf = new Genpdf(new mPDF());

$header = file_get_contents("./partials/header.html");
$footer = file_get_contents("./partials/footer.html");

$content = file_get_contents("./partials/content.html");

$html = $header . $content . $footer;

//$mpdf->WriteHTML($html);

$pdf->pippo = "Pluto";
$pdf->paperino = "sono io";
$pdf->override(1);
$pdf->pippo = " and Topolino";

$debug = $pdf->getDebug();

foreach($debug as $k => $v) {
    echo "<p style='color:" . $v['color'] . "'>" . $v['text'] . "</p>";
}

// Output a PDF file directly to the browser
//$mpdf->Output();