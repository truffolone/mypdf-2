<?php

// Require composer autoload
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/lib/Genpdf.php';
require_once __DIR__ . '/lib/added/Sutter.php';

use lib\Genpdf;

$pdf = new Genpdf(new mPDF());

$header = file_get_contents("./partials/header.html");
$footer = file_get_contents("./partials/footer.html");
$content = file_get_contents("./partials/content.html");

$html = $header . $content . $footer;

$pdf->saveHtml($html);

$pdf->set("css", file_get_contents("./css/embedded.css"));
$pdf->set("title", "Titolo PDF");
$pdf->setFooter("<div class=\"allFooter\">created by SutterAcademy</div>");

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
$pdf->bind("oggetto", "Oggetto");
$pdf->bind("prevoggetto", "Bla Bla oggetto");
$pdf->bind("condvendita", "Condizioni di Vendita");
$pdf->bind("cvendita", "aisdfhuasdufh osd hasdfh as");

//table
$fullTable = get_sutter_table_header(array("d" => "Descrizione", "q" => "Quantità", "p" => "Prezzo", "pm" => "Prezzo Mese")) . 
             get_sutter_tableTopRow(array("d" => "Manodopera", "q" => "", "p" => "", "pm" => "")) . 
             get_sutter_tableDetail(array("d" => "Operatore Base", "q" => "100 H", "p" => "12,00 €", "pm" => "1,00 €")) .
             get_sutter_tableTopRow(array("d" => "Manodopera", "q" => "", "p" => "", "pm" => "")) . 
             get_sutter_tableDetail(array("d" => "Operatore Base", "q" => "100 H", "p" => "12,00 €", "pm" => "1,00 €")) .
             get_sutter_tableST(array("d" => "Totale Manodopera", "q" => "", "p" => "1.200,00 €", "pm" => "100,00 €")) .
             get_sutter_tableTopRow(array("d" => "Manodopera", "q" => "", "p" => "", "pm" => "")) .
             get_sutter_tableDetail(array("d" => "Operatore Base", "q" => "100 H", "p" => "12,00 €", "pm" => "1,00 €")) .
             get_sutter_tableST(array("d" => "Totale Manodopera", "q" => "", "p" => "1.200,00 €", "pm" => "100,00 €")) .
             get_sutter_tableCT(array("d" => "Totale Costi", "q" => "", "p" => "16.403,69 €", "pm" => "1.366,97 €")) .
             get_sutter_tableDetail(array("d" => "Operatore Base", "q" => "100 H", "p" => "12,00 €", "pm" => "1,00 €")) .
             get_sutter_tableST(array("d" => "Totale Manodopera", "q" => "", "p" => "17.263,87 €", "pm" => "100,00 €")) .
             get_sutter_tableF() .
             get_sutter_tableB(array("d" => "", "q" => "Totale Generale", "p" => "17.263,87 €", "pm" => "1,438.66 €")) .
             get_sutter_tableB(array("d" => "", "q" => "a MQ", "p" => "57.55 €", "pm" => "4.80 €")) .
             get_sutter_tableClose();

$pdf->bind("table", $fullTable);

$pdf->replaceAll();

$pdf->generate("prova.pdf", true);

//$pdf->showDebug();

//$mpdf->WriteHTML($html);

// Output a PDF file directly to the browser
//$mpdf->Output();