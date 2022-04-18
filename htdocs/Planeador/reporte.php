<?php
ob_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
@session_start();
require '../clases/Fecha.Class.php'; 
require '../clases/Comun.Class.php'; 
require '../clases/Clase_mysqli.Class.php'; 
#require_once("../comun/autoload.php");
require '../clases/Materias.Class.php'; 
require '../clases/Planeacion.Class.php'; 
require_once dirname(__FILE__).'/PHPWord-master/src/PhpWord/Autoloader.php';
\PhpOffice\PhpWord\Autoloader::register();
use PhpOffice\PhpWord\TemplateProcessor;
$templateWord = new TemplateProcessor('plantilla/plan_clasee.docx');
$planeacion=new Planeacion($_GET['idplan']);
if(isset($_GET['eliminar'])){
	$planeacion->eliminar_plan();
	//header("location:index.php");
}

 // --- Asignamos valores a la plantilla
$observaciones= $planeacion->observaciones_plan.'. Duración '.$planeacion->tiempo_plan.' horas';
$templateWord->setValue('institución',$planeacion->institucion_educativa);
$templateWord->setValue('docente',$planeacion->docente);
$templateWord->setValue('contenido',Planeacion::eliminar_ultimo_caracter($planeacion->contenido_plan));
$templateWord->setValue('fecha',Fecha::formato_fecha($planeacion->fecha_plan));
$templateWord->setValue('objetivos',Planeacion::eliminar_ultimo_caracter($planeacion->objetivos_plan));
$templateWord->setValue('estrategia',Planeacion::eliminar_ultimo_caracter($planeacion->estrategiaa));
$templateWord->setValue('Actividad',Planeacion::eliminar_ultimo_caracter($planeacion->Actividada));
$templateWord->setValue('Recurso',Planeacion::eliminar_ultimo_caracter($planeacion->Recursoa));
$templateWord->setValue('estrategiab',Planeacion::eliminar_ultimo_caracter($planeacion->estrategiaa));
$templateWord->setValue('Actividadb',Planeacion::eliminar_ultimo_caracter($planeacion->Actividadb));
$templateWord->setValue('Recursob',Planeacion::eliminar_ultimo_caracter($planeacion->Recursob));
$templateWord->setValue('observaciones',Planeacion::eliminar_ultimo_caracter($observaciones));
// --- Guardamos el documento
unlink('plan_clase.docx');
$templateWord->saveAs('plan_clase.docx');
header("Content-Disposition: attachment; filename=plan_clase.docx; charset=iso-8859-1");
echo file_get_contents('plan_clase.docx');
#ob_clean();	
#}

?>