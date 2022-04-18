<?php
class Planeacion extends Clase_mysqli { 

 	public $id_plan="";
 	public $contenido_plan="";
 	public $docente;
 	public $fecha_plan="";
 	public $objetivos_plan="";
 	public $actividad="";
 	public $materia="";
 	public $materia2;
 	public $plana="";
 	public $planb="";
 	public $recurso="";
 	public $grado="";
 	public $estrategia="";
 	public $estrategiaa="";
	public $Actividada="";
	public $Recursoa="";
	public $estrategiab="";
	public $Actividadb="";
	public $Recursob="";
	public $institucion_educativa="";
 	public $listado_estrategias;
 	public $listado_actividades;
 	public $listado_recursos;
 	public $observaciones_plan="";
 	public $tiempo_plan="";
 	public $orden="";
 	public $red="";

 	/*Evaluar Constructor*/
public function __set($atributo,$valor){
		 $this->$atributo= utf8_encode($valor) ;
}


public function __construct($id=""){
		if($id<>"")	$this->__set("id_plan",$id);

if($id<>""){
		$this->observaciones_plan = $this->mostrar_campo_plan("observaciones_plan");
		$this->fecha_plan = $this->consultar_fecha_planeación();
		$this->tiempo_plan = $this->mostrar_campo_plan("tiempo_plan");
		$this->red = $this->mostrar_campo_plan("red");
		$this->contenido_plan = $this->consultar_contenido();
		$this->materia = $this->mostrar_campo_plan("materia");	
		$this->grado = $this->mostrar_campo_plan("grado");
		$this->orden = $this->mostrar_campo_plan("orden_plan");
		$this->plana = $this->consultar_plana();
		$this->planb = $this->consultar_planb();
		$this->docente = $this->consultar_docente();
		$this->institucion_educativa = $this->consultar_institucion();
		$this->objetivos_plan = $this->consultar_objetivos();	
		$this->listado_estrategias=$this->mostrar_todas_estrategias();
		$this->listado_actividades=$this->mostrar_todas_actividad();
		$this->listado_recursos=$this->mostrar_todas_recursos();
}

}



public function ultimo_plan($materia,$grado){

  $sql='select orden_plan from plan_clase where materia="'.$materia.'" and  grado="'.$grado.'" order by orden_plan desc limit 1 ';
  $datos = json_decode($this->consultar_datos($sql,true));

return $datos;

}



public static function eliminar_ultimo_caracter($cadena){

	return rtrim($cadena,',');

}



public  function consultar_grado(){

	$sql='SELECT * FROM `categoria_curso` ';

	$datos= $this->consultar_datos($sql);

	$datos=json_decode($datos);

	return($datos);

}



public function mostrar_campo_plan($campo){

	$sql='SELECT '.$campo.' FROM `plan_clase` ';
	$sql.=' where id_plan = '.$this->id_plan; 
	$datos= $this->consultar_datos($sql);
	$datos=json_decode($datos);
	return($datos[0][0]);

}



public function eliminar_plan(){

	$sql='DELETE FROM `plan_clase`  ';

	$sql.=' where id_plan = '.$this->id_plan; 

	return	$this->query_insertar($sql);

	

}

public function intensidad_horaria($materia){

	$sql='SELECT sum(tiempo_plan) FROM `plan_clase` ';

	$sql.=' where materia = '.$materia; 

	$datos= $this->consultar_datos($sql);

	$datos=json_decode($datos);

	return($datos[0][0]);

}



public function mostrar_todas_estrategias(){

	$sql='SELECT estrategia,descripcion_estrategia FROM `estrategias` ';

	$datos= $this->consultar_datos($sql);

	$datos=json_decode($datos);

	return($datos);

}



public function mostrar_todas_actividad(){

	$sql='SELECT actividad,descripcion_actividad FROM `actividades` ';

	$datos= $this->consultar_datos($sql);

	$datos=json_decode($datos);

	return($datos);

}



public function mostrar_todas_recursos(){

	$sql='SELECT nombre_recurso FROM `recursos` ';

	$datos= $this->consultar_datos($sql);

	$datos=json_decode($datos);

	return($datos);

}





public function mostrar_todas_planeaciones($materia="",$grado=""){

	$sql='SELECT id_plan FROM `plan_clase` ';

	if($materia<>"" and $grado<>""){

		$sql.=' where materia='.$materia.'  ';

	}

if(!empty($this->id_plan)){

$sql.=' where id_plan='.$this->id_plan.'  ';

}



	$sql.=" order by orden_plan,id_plan asc ";



	$datos= $this->consultar_datos($sql);

	$datos=json_decode($datos);

	return($datos);

}



public function datalist($input,$datos="mostrar_todas_planeaciones"){ 



if($datos=="consultar_objetivos"){ ?>

	<datalist   id="<?php echo $input; ?>">

<?php 

foreach(explode(",",$this->$datos() ) as $numero)

		    { 

if(!empty($numero)){ ?><option data-ejemplo="<?php echo $numero; ?>" value="<?php echo $numero; ?>"> <?php }  

		    }

?> </datalist> <?php



}



if($datos=="consultar_contenido"){ ?>

	<datalist   id="<?php echo $input; ?>">

<?php 

foreach(explode(",",$this->$datos() ) as $numero)

		    { 

if(!empty($numero)){ ?><option data-ejemplo="<?php echo $numero; ?>" value="<?php echo $numero; ?>"> <?php }  

		    }

?> </datalist> <?php

}else{?> 

<datalist   id="<?php echo $input; ?>">

<?php foreach ($this->$datos() as $key => $value) {

		$planid=new Planeacion($value[0]);

		foreach(explode(",",$planid->$input) as $numero)

		    { 

if(!empty($numero)){ ?><option data-ejemplo="<?php echo $numero; ?>" value="<?php echo $numero; ?>"> <?php }    

		    }

}

?>  </datalist>

	<?php

}



}





public function datalist2($input,$datos="mostrar_todas_planeaciones"){ 	





	?>

<datalist id="<?php echo $input; ?>">

<?php foreach ($this->$datos() as $numero) {



if(!empty($numero[0])){ ?><option title="Hola Mundo" label="<?php if(!empty($numero[1])) echo $numero[1];   ?>" value="<?php echo $numero[0]; ?>"> <?php }    

}

?>  </datalist> <?php

}





public function consultar_contenido(){

	  $sql='select contenido_plan from plan_clase ';

	 if($this->id_plan<>""){

	 	$sql.='where id_plan="'.$this->id_plan.'"';

	 }

	if($this->materia2<>""){

	 	$sql.='where materia="'.$this->materia2.'"';

	 }

	 $datos=$this->consultar_datos($sql);





$contenido="";

if(!empty($datos)){

foreach (json_decode($datos) as $key => $value) {

foreach (json_decode($value[0]) as $value2) {

	$contenido.=$value2.',';

}

}

return $contenido;		

}else{

	return "";

}



}



public function consultar_plana(){

	$datos=json_decode($this->consultar_planeacion());

	$datos_plana=array();

	$dato=json_decode($datos[0][4],true);

	#if(!empty($dato){

foreach($dato as $clave => $valor) {

    if(!empty($valor)){

      foreach ($valor as $campus => $valor_campus) {

 	if(!empty($datos_plana[$clave])) { $info=$datos_plana[$clave]; } else { $info="";}

  	$datos_plana[$clave]= $info.$valor_campus.',';

   }   

    }

}



#}

if(!empty($datos_plana['estrategia'])){

$this->estrategiaa =$datos_plana['estrategia'];

}

$this->Actividada=$datos_plana['Actividad'];

$this->Recursoa =$datos_plana['Recurso'];

return 	$datos_plana;

}



public function consultar_planb(){

	$datos=json_decode($this->consultar_planeacion());

	$datos_planb=array();

#	 echo "<pre>";

#	 print_r(json_decode($datos[0][5]));

#	 echo "</pre>";

	$infoa=json_decode($datos[0][5],true);

		if(!empty($infoa)){

			$info=json_decode($datos[0][5],true) ;

if(!empty($info)){			

foreach($info as $clave => $valor) {

if(!empty($valor)){			



      foreach ($valor as $campus => $valor_campus) {

	 if(!empty($datos_planb[$clave])) { $infob=$datos_planb[$clave]; } else { $infob="";}

   	$datos_planb[$clave]= $infob.$valor_campus.',';

   }   

   }

   }

}

 $this->estrategiab =$datos_planb['estrategiaplanb'];

if(!empty($datos_planb['Actividadplanb'])){

$this->Actividadb=$datos_planb['Actividadplanb'];

}

$this->Recursob =$datos_planb['Recursosplanb'];

return 	$datos_planb;

}

}



public function consultar_objetivos(){

$datos=array();

$sql="SELECT DISTINCT REPLACE( LEFT(objetivos_plan, LOCATE(' ', objetivos_plan) ) , '[\"', '') FROM plan_clase";



	 if($this->id_plan<>""){

	 	$sql.=' where id_plan="'.$this->id_plan.'"';

	 }

	if($this->materia2<>""){

	 	$sql.=' where materia="'.$this->materia2.'"';

	 }





	$datos=json_decode($this->consultar_planeacion($sql));





foreach ($datos as $key => $value) {

	$objetivos[]=json_decode($value[3]);

}



$obj="";

if(!empty($objetivos)){

foreach ($objetivos as $key => $value) {

foreach ($value as  $key2 =>$value2) {

$value2= current(explode(' ',$value2)); 

$datos2[]=$value2;

}

}

}

if(!empty($datos2)){

$datos2 = array_unique($datos2);

foreach ($datos2 as $key => $value) {

$obj.=$value.',';

}

return $obj;

}

}



public function consultar_docente(){

	$sql='select * from docente';

	$datos= $this->consultar_datos($sql);

	$datos=json_decode($datos);

	$teacher=""; 

	foreach ($datos as $key => $value) {

			$teacher.= $value[1].' '.$value[2].' ('.$value[0].')';

	}

	return $teacher;

}



public function consultar_fecha_planeación(){

	$datos=json_decode($this->consultar_planeacion());

	 return $fecha=($datos[0][2]);

}





public function consultar_institucion(){

	$sql='select * from institucion_educativa';

	$datos= $this->consultar_datos($sql);

	$datos=(json_decode($datos));

	return $datos[0][1]; //devuelve el nombre;

}

public function consultar_planeacion(){

	  $sql='select * from plan_clase ';

	 if($this->id_plan<>""){

	 	$sql.='where id_plan="'.$this->id_plan.'"';

	 }

	if($this->materia2<>""){

	 	$sql.='where materia="'.$this->materia2.'"';

	 }

	 

	 $resultado_planeacion=$this->consultar_datos($sql);

	 return	$resultado_planeacion;



}



	public function actualizar_planeacion($id_plan){

$sql="UPDATE `plan_clase` SET orden_plan = ".$_POST['orden'].", `contenido_plan`='$this->contenido_plan',`fecha_plan`='$this->fecha_plan',`objetivos_plan`='$this->objetivos_plan',`plan_a`='$this->plana',`plan_b`='$this->planb',`materia`='$this->materia',`grado`='$this->grado',`observaciones_plan`='$this->observaciones_plan',`tiempo_plan`='$this->tiempo_plan', red='$this->red'  WHERE `id_plan`='$id_plan'";
$materia=$_GET["asignacion"];

if($this->query_actualizar($sql)){

#header('location:../cursos/curso.php?asignacion='.$materia.'');

}



	}





	public function insertar_planeacion($id_plan=""){

			 	  if($id_plan==""){

	 	  $sql="INSERT INTO `plan_clase`(orden_plan, `contenido_plan`, `fecha_plan`, `objetivos_plan`, `plan_a`, `plan_b`, `materia`,  `grado`,observaciones_plan,tiempo_plan,red) VALUES ('$this->orden','$this->contenido_plan','$this->fecha_plan','$this->objetivos_plan','$this->plana','$this->planb','$this->materia','$this->grado','$this->observaciones_plan','$this->tiempo_plan','$this->red') ";	
					#echo $sql;
			return	$this->query_insertar($sql);

	 	}

	 	  if($id_plan<>""){
	 	return $this->actualizar_planeacion($id_plan);

	 	  }

		

 	}



public  function consultar_menu($categoria){

			$sql='SELECT * FROM `menu_items2`  where categoria ="'.$categoria.'"';

			$datos= $this->consultar_datos($sql,true);

			$datos=json_decode($datos);

			return($datos);

	}

public function insertar_contenido(){

	$sql="INSERT INTO contenido (`contenido`) VALUES ('$this->contenido_plan')";

	$this->query_insertar($sql);

}



public function insertar_objetivos(){

  $sql="INSERT INTO objetivos (`objetivos`) VALUES ('$this->objetivos_plan')";

	$this->query_insertar($sql);

}



public function insertar_estrategia(){

	#$variable=mb_strtolower($this->estrategia,'UTF-8');

	  $sql="INSERT INTO estrategias (`estrategia`) VALUES ('$this->estrategia')";

	$this->query_insertar($sql);

}



public function insertar_actividad(){

	  $sql="INSERT INTO actividades (`actividad`) VALUES ('$this->actividad')";

	return $this->query_insertar($sql);

}



public function insertar_recursos(){

	 $sql="INSERT INTO recursos (`nombre_recurso`) VALUES ('$this->recurso')";

return	$this->query_insertar($sql);

}

 }

?>