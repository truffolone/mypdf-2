<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Require composer autoload
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/lib/Genpdf.php';
require_once __DIR__ . '/lib/added/Sutter.php';
require_once __DIR__ . '/lib_unminified/db.access.php';

use lib\Genpdf;

$pdf = new Genpdf(new mPDF());


//using old code...
$db = new AppDatabase();

$id = $_REQUEST['qid'];
$lang = $_REQUEST['lang'];
$mkt = $_REQUEST['mkt'];

$prefix = "rrt";
$documentId = md5("pdf" . $prefix . $id);
$filename = $prefix . "_" . $documentId;
$folder = str_pad($id, 8, "0", STR_PAD_LEFT);
//preventivo
$preventivo = $db->getPrevForPdf($id);
$prevtitle = "Preventivo N." . $preventivo->id . " del " . $preventivo->quote_date;
$durata = "";
if($preventivo->anni_contratto != 1) {
    $durata .=  $preventivo->anni_contratto . " anni";
} else {
    $durata .= $preventivo->anni_contratto . " anno";
}
$rlogo = "../img/user/" . $preventivo->rlogo;
$mlogo = "../img/" . $preventivo->mlogo;

//cliente
$cliente = $db->getClient($preventivo->id_client);
$clientelogo = "../img/clienti/" . $cliente->logo;

//costigenerali
$costigenerali = $db->gcg($preventivo->id);

//altricosti
$altricosti = $db->getAltriCosti2($preventivo->id);

//figpro
$figpro = json_decode(file_get_contents("http://www.sutteracademy.com/ws.php?a=get_quote_figpros&id=" . $preventivo->id . "&id_user=" . $preventivo->id_user . "&lang=" . $lang));

//attrezzature
$attrezzature = json_decode(file_get_contents("http://www.sutteracademy.com/ws.php?a=get_quote_attrezzature&id=" . $preventivo->id . "&id_user=" . $preventivo->id_user . "&lang=" . $lang));

//materiali
$materiali = json_decode(file_get_contents("http://www.sutteracademy.com/ws.php?a=get_quote_materiali&id=" . $preventivo->id . "&id_user=" . $preventivo->id_user . "&lang=" . $lang));

//macchinari
$macchinari = json_decode(file_get_contents("http://www.sutteracademy.com/ws.php?a=get_quote_macchinari&id=" . $preventivo->id . "&id_user=" . $preventivo->id_user . "&lang=" . $lang));

//prodotti
$prodotti = json_decode(file_get_contents("http://www.sutteracademy.com/ws.php?a=get_quote_prodotti&id=" . $preventivo->id . "&mkt=" . $mkt . "&id_user=" . $preventivo->id_user . "&lang=" . $lang));

//summary
$summary = json_decode(file_get_contents("http://www.sutteracademy.com/ws.php?a=get_quote_summary&id=" . $preventivo->id . "&mkt=" . $mkt . "&id_user=" . $preventivo->id_user . "&lang=" . $lang));
//die("<pre>" . print_r($summary, true) . "</pre>");

//back to mine
$header = file_get_contents("./partials/header.html");
$footer = file_get_contents("./partials/footer.html");
$content = file_get_contents("./partials/content.html");

$pdf->set("css", file_get_contents("./css/embedded.css"));
$pdf->set("title", $preventivo->titolo);
$pdf->setFooter("<div class=\"col-md-12 text-center\">" . $preventivo->pie_pagina_testo . "</div><div class=\"col-md-12 allFooter\">created by SutterAcademy</div>");

$html = $header . $content . $footer;

$pdf->saveHtml($html);

/*if (isset($_REQUEST['download']) && $_REQUEST['download'] == 1) {
    $download = 1;
} else {
    $download = 0;
}
try {
    $start = strtotime(date('Y-m-d H:i:s'));

    $prefix = "rrt";
    $tmp = "pdf".$prefix."".$id;

    $documentId = md5($tmp);
	$fileName = $prefix;
    $fileName .= "_".$documentId;

    $folder = str_pad($id, 8, "0", STR_PAD_LEFT);

    $doc = new QuoteTotalSummary(array(
        'qid' => $id
    ));

    $doc->setLang($lang);
    $doc->setMarket($mkt);
    $doc->preparePdfDocument();
    if (isset($_GET['download'])) {
        $doc->saveDocument();
        $end = strtotime(date('Y-m-d H:i:s'));
        $time = $end - $start;

        $db->saveDocumentForQuote($documentId, $id, $prefix, $fileName.'.pdf', $time);
    } else {
        $doc->writeDocument($fileName, $folder);
		$end = strtotime(date('Y-m-d H:i:s'));
        $time = $end - $start;

        $db->saveDocumentForQuote($documentId, $id, $prefix, $fileName.'.pdf', $time);

        //header('location: elenco_preventivi.php?download=' . $download);
        header('Content-type: application/json');
        echo json_encode(array('code' => true, 'msg' => 'Documento generato con successo'));
    }
} catch (Exception $e) {
    //echo '<div class="error fatal"><h1>Errore grave</h1><p>' . $e->getMessage() . '</p>';
    http_response_code(500);
    echo $e->getMessage();
}*/

$pdf->bind("headerimg", "<img src=\"" . $mlogo . "\">");
$pdf->bind("prevtitle", $prevtitle);
$pdf->bind("previcon", "<img src=\"" . $rlogo . "\" class=\"littleimg\">");
$pdf->bind("cliente", "Cliente");
$pdf->bind("clientename", $cliente->rag_sociale);
$pdf->bind("clienteicon", "<img src=\"" . $clientelogo . "\" class=\"littleimg\">");
$pdf->bind("durata", "Durata");
$pdf->bind("durataprev", $durata);
$pdf->bind("clienteicon2", "<img src=\"" . $clientelogo . "\" class=\"littleimg2\">");
$pdf->bind("previcon2", "<img src=\"" . $rlogo . "\" class=\"littleimg2\">");
$pdf->bind("spettabile", "Spettabile");
$pdf->bind("address", nl2br($cliente->rag_sociale . "
                            " . $cliente->indirizzo . "
                            " . $cliente->cap . " " . $cliente->citta . "
                            (" . $cliente->provincia . ")"));
$pdf->bind("prevtitle2", nl2br($prevtitle . "
                               Durata anni " . $preventivo->anni_contratto));
$pdf->bind("oggetto", "Oggetto");
$pdf->bind("prevoggetto", $preventivo->oggetto);
$pdf->bind("condvendita", "Condizioni di Vendita");
$pdf->bind("cvendita", $preventivo->condizioni_testo);

//table
$fullTable = get_sutter_table_header(array("d" => "Descrizione", "q" => "Quantità", "p" => "Prezzo", "pm" => "Prezzo Mese"));

#figure professionali
if(property_exists($figpro, "figpros")) {
    if(count($figpro->figpros) > 0) {
        $fullTable .= get_sutter_tableTopRow(array("d" => "Manodopera", "q" => "Ore", "p" => "N. Operatori", "pm" => "Prezzo Totale"));
        foreach($figpro->figpros as $fp) {
             $fullTable .= get_sutter_tableDetail(array("d" => $fp->figpro_details->fig_pro . " (tariffa: " . $fp->figpro_details->fig_pro . ")", 
                                                        "q" => $fp->tot_ore, 
                                                        "p" => count($fp->operazioni), 
                                                        "pm" => $fp->figpro_details->prezzo . " €"));
        }
        $fulltable .= get_sutter_tableST(array("d" => "Totale manodopera",
                                               "q" => "",
                                               "p" => $summary->manodopera->prezzo_totale . " €",
                                               "pm" => $summary->manodopera->prezzo_mese . " €"));
    }
}

#attrezzature
if(property_exists($attrezzature, "attrezzature")) {
    if(count($attrezzature->attrezzature) > 0) {
        $fullTable .= get_sutter_tableTopRow(array("d" => "Attrezzature", "q" => "Quantità", "p" => "", "pm" => "Prezzo Totale"));
        foreach($attrezzature->attrezzature as $at) {
             $fullTable .= get_sutter_tableDetail(array("d" => $at->attrezzatura, 
                                                        "q" => $at->num_usate, 
                                                        "p" => "", 
                                                        "pm" => $at->prezzo . " €"));
        }
        $fulltable .= get_sutter_tableST(array("d" => "Totale Attrezzature",
                                               "q" => "",
                                               "p" => $summary->attrezzature->prezzo_totale . " €",
                                               "pm" => $summary->attrezzature->prezzo_mese . " €"));
    }
}

#materiali
if(property_exists($materiali, "materiali")) {
    if(count($materiali->materiali) > 0) {
        $fullTable .= get_sutter_tableTopRow(array("d" => "Materiali", "q" => "Quantità", "p" => "Prezzo Unitario", "pm" => "Prezzo Totale"));
        foreach($materiali->materiali as $mt) {
             $fullTable .= get_sutter_tableDetail(array("d" => $mt->details->nome, 
                                                        "q" => $mt->n_pezzi, 
                                                        "p" => $mt->details->prezzo . " €", 
                                                        "pm" => $mt->details->prezzo * $mt->n_pezzi . " €"));
        }
        $fulltable .= get_sutter_tableST(array("d" => "Totale Materiali",
                                               "q" => "",
                                               "p" => $summary->materiali->prezzo_totale . " €",
                                               "pm" => $summary->materiali->prezzo_mese . " €"));
    }
}

#macchinari
if(property_exists($macchinari, "macchinari")) {
    if(count($macchinari->macchinari) > 0) {
        $fullTable .= get_sutter_tableTopRow(array("d" => "Macchinari", "q" => "Quantità", "p" => "Prezzo Unitario", "pm" => "Prezzo Totale"));
        foreach($macchinari->macchinari as $mn) {
             $fullTable .= get_sutter_tableDetail(array("d" => $mn->descrizione, 
                                                        "q" => $mn->nr_macchinari, 
                                                        "p" => $mn->prezzo . " €", 
                                                        "pm" => $mn->prezzo_totale . " €"));
        }
        $fulltable .= get_sutter_tableST(array("d" => "Totale Macchinari",
                                               "q" => "",
                                               "p" => $summary->macchinari->prezzo_totale . " €",
                                               "pm" => $summary->macchinari->prezzo_mese . " €"));
    }
}

#prodotti
if(property_exists($prodotti, "prodotti")) {
    if(count($prodotti->prodotti) > 0) {
        $fullTable .= get_sutter_tableTopRow(array("d" => "Prodotti", "q" => "Quantità", "p" => "Prezzo Unitario", "pm" => "Prezzo Totale"));
        foreach($prodotti->prodotti as $pd) {
             $fullTable .= get_sutter_tableDetail(array("d" => $pd->product_name, 
                                                        "q" => $pd->prodotto_quantita, 
                                                        "p" => $pd->prezzo . " €", 
                                                        "pm" => $pd->prodotto_prezzo . " €"));
        }
        $fulltable .= get_sutter_tableST(array("d" => "Totale Prodotti",
                                               "q" => $summary->prodotti->quantita_totale,
                                               "p" => $summary->prodotti->prezzo_totale . " €",
                                               "pm" => $summary->prodotti->prezzo_mese . " €"));
    }
}

#costi
if(count($summary->costi_quote) > 0) {
    foreach($summary->costi_quote as $cq) {
        $fullTable .= get_sutter_tableDetail(array("d" => $cq->costo_generale, 
                                                   "q" => $cq->perc . "%", 
                                                   "p" => $cq->prezzo . " €", 
                                                   "pm" => $cq->prezzo_mese . " €"));
    }
}

#altricosti
if($altricosti->rowCount() > 0) {
    foreach($altricosti->fetchAll() as $ac) {
        $fullTable .= get_sutter_tableDetail(array("d" => $ac['nome'], 
                                                   "q" => $ac['quantita'], 
                                                   "p" => $ac['prezzo'] . " €", 
                                                   "pm" => $ac['prezzo'] * $ac['quantita'] . " €"));
    }
}

$fullTable .= get_sutter_tableF();

$fullTable .= get_sutter_tableB(array("d" => "", 
                                      "q" => "Totale Generale",
                                      "p" => $summary->totali->totale_generale . " €", 
                                      "pm" => $summary->totali->totale_generale_mese . " €")) .
              get_sutter_tableB(array("d" => "",
                                      "q" => "a MQ", 
                                      "p" => $summary->totali->totale_mq . " €",
                                      "pm" => $summary->totali->totale_mese_mq . " €")) .
              get_sutter_tableClose();

$pdf->bind("table", $fullTable);

$pdf->replaceAll();

$pdf->generate("prova.pdf", true);
//echo $pdf->getTemplate();
//$pdf->showDebug();

//$mpdf->WriteHTML($html);

// Output a PDF file directly to the browser
//$mpdf->Output();