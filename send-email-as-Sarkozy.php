<?php
$additional_headers = "From: nicolas.sarkozy@elysee.fr \r\n";
$additional_headers.= "Content-Type: text/plain; charset=iso-8859-1";
$destinataires = 'gauthier.coste@viacesi.fr';
$sujet = utf8_decode("Poste de ministre de la défense");

$message = "\n";
$message.= utf8_decode("Tu me dois 50 euros =D \n");

var_dump( mail($destinataires, $sujet, $message, $additional_headers));
?>
