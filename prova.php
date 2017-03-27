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

$pdf->saveHtml($html);

$pdf->set("css", file_get_contents("./css/embedded.css"));
$pdf->bind("title", "Titolo PDF");

$pdf->replaceAll();

$pdf->generate("prova.pdf");

$pdf->showDebug();

//$mpdf->WriteHTML($html);

// Output a PDF file directly to the browser
//$mpdf->Output();