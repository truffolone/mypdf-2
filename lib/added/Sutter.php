<?php 

function get_sutter_table_header($arr) {
    return "<table class=\"table table-bordered custom_preventivo\">
                <thead class=\"custom_preventivo\">
                    <tr>
                        <th class=\"Descrizione col-md-6\">" . $arr['d'] . "</th>
                        <th class=\"Quantità col-md-2\">" . $arr['q'] . "</th>
                        <th class=\"Prezzo col-md-2\">" . $arr['p'] . "</th>
                        <th class=\"Prezzo_Mese col-md-2\">" . $arr['pm'] . "</th>
                    </tr>
                </thead>
                <tbody>";
}

function get_sutter_tableTopRow($arr) {
    return "<tr class=\"title\">
                <td class=\"Descrizione_T\">" . $arr['d'] . "</td>
                <td class=\"Quantità\">" . $arr['q'] . "</td>
                <td class=\"Prezzo\">" . $arr['p'] . "</td>
                <td class=\"Prezzo_Mese\">" . $arr['pm'] . "</td>
            </tr>";
}

function get_sutter_tableDetail($arr) {
    return "<tr class=\"detail\">
                <td class=\"Descrizione_I\">" . $arr['d'] . "</td>
                <td class=\"Quantità_I\">" . $arr['q'] . "</td>
                <td class=\"Prezzo_I\">" . $arr['p'] . "</td>
                <td class=\"Prezzo_Mese_I\">" . $arr['pm'] . "</td>
            </tr>";
}

function get_sutter_tableST($arr) {
    return "<tr class=\"subtotal\">
                <td class=\"Descrizione_st\">" . $arr['d'] . "</td>
                <td class=\"Quantità_st\">" . $arr['q'] . "</td>
                <td class=\"Prezzo_st\">" . $arr['p'] . "</td>
                <td class=\"Prezzo_Mese_st\">" . $arr['pm'] . "</td>
            </tr>";
}

function get_sutter_tableCT($arr) {
    return "<tr class=\"cost_total\">
                <td class=\"Descrizione_ct\">" . $arr['d'] . "</td>
                <td class=\"Quantità_ct\">" . $arr['q'] . "</td>
                <td class=\"Prezzo_ct\">" . $arr['p'] . "</td>
                <td class=\"Prezzo_Mese_ct\">" . $arr['pm'] . "</td>
            </tr>";
}

function get_sutter_tableB($arr) {
    return "<tr class=\"grandtotal\">
                <td class=\"Descrizione\">" . $arr['d'] . "</td>
                <td class=\"Quantità\">" . $arr['q'] . "</td>
                <td class=\"Prezzo\">" . $arr['p'] . "</td>
                <td class=\"Prezzo_Mese\">" . $arr['pm'] . "</td>
            </tr>";
}

function get_sutter_tableF() {
    return "</tbody>
            <tfoot>";
}

function get_sutter_tableClose() {
    return "</tfoot>
        </table>";
}