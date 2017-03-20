<?php

// Require composer autoload
require_once __DIR__ . '/vendor/autoload.php';

$mpdf = new mPDF();

$html = file_get_contents("./testpdf.html");

$mpdf->WriteHTML($html);

// Output a PDF file directly to the browser
$mpdf->Output();