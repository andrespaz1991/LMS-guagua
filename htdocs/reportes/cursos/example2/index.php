<?php
require_once 'bootstrap.php';
require_once 'vendor/autoload.php';
require_once 'src/PhpWord/TemplateProcessor.php';
// Creating the new document...
$phpWord = new \PhpOffice\PhpWord\PhpWord();
$testWord = $phpWord->loadTemplate('hola.docx');
$testWord->setValue('nombre', 'Angie herrera');
$testWord->setValue('observacion', 'Muy buen estudiante');
$testWord->saveAs('result.docx');

