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
$pdf->set("title", "Titolo PDF");

$pdf->bind("headerimg", "<img src=\"./partials/trasporti.jpg\">");
$pdf->bind("prevtitle", "Preventivo N.788 del 27/03/2017");
$pdf->bind("previcon", "<img src=\"./partials/demo.png\" class=\"littleimg\">");
$pdf->bind("cliente", "Cliente");
$pdf->bind("clientename", "La rapida");
$pdf->bind("clienteicon", "<img src=\"./partials/all.jpg\" class=\"littleimg\">");
$pdf->bind("durata", "Durata");
$pdf->bind("durataprev", "1 anno");
$pdf->bind("clienteicon2", "<img src=\"./partials/all.jpg\" class=\"littleimg2\">");
$pdf->bind("previcon2", "<img src=\"./partials/demo.png\" class=\"littleimg2\">");
$pdf->bind("spettabile", "Spettabile");
$pdf->bind("address", nl2br("La rapida
                            Via Einaudi 23
                            92032 Foggia
                            (FG)"));
$pdf->bind("prevtitle2", nl2br("Preventivo N.788 del 27/03/2017
                                Durata anni 1"));

$pdf->replaceAll();

$pdf->generate("prova.pdf", true);

//$pdf->showDebug();

//$mpdf->WriteHTML($html);

// Output a PDF file directly to the browser
//$mpdf->Output();