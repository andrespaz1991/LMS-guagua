<?php
function titulo($contenido){
    echo '<h3 style="fon-size:40px;color:white;position:absolute;margin-top:24%;margin-left:5%" >'.$contenido.'</h3>';
}
$html='<style>
  #footer { position: fixed;}
  @import url(https://fonts.googleapis.com/css?family=Roboto);
  body {
      font-family: 'Roboto', sans-serif;
    }
  .separator {
     
      border: 1px solid #C3C3C3;
    }
    #card {
      border-radius: 5px;
      position: absolute;

    
      -webkit-box-shadow: 10px 10px 93px 0px rgba(255, 0, 0, 0.75);
      -moz-box-shadow: 10px 10px 93px 0px rgba(0, 0, 0, 0.75);
      box-shadow: 10px 10px 93px 0px rgba(0, 0, 0, 0.75);
    }
    .right {
  
    }
    .thumbnail {
      float: left;
      position: relative;
      left: 30px;
      top: -30px;
      height: 320px;
      width: 630px;
    }
    p {
      text-align: justify;
      font-size: 0.95rem;
      color: #4B4B4B;
    }
    .footer {
      
    }
    #title, span {display: inline;}
    /** Define the margins of your page **/
    @page {
        margin: 100px 25px;
    }

    header {
        

        /** Extra personal styles **/
      
    }

    footer {
        position: fixed; 
     }
  </style>';

  $html.='<p style="fon-size:12px;color:black;position:absolute;margin-top:33%;margin-left:65%" >|'.Fecha::formato_fecha(date('Y-m-d')).'|</p>';
  $rutaImagen2l = "../../sga-data/foto/".$logo;
  $contenidoBinario2l = file_get_contents($rutaImagen2l);
    $imagenComoBase642l = base64_encode($contenidoBinario2l);
    $html.="<img style='width:10%;position:absolute;margin-top:23%;margin-left:70%' src='data:image/jpeg;base64,$imagenComoBase642l'>";
 $contenidoBinario = file_get_contents($rutaImagen);
$imagenComoBase64 = base64_encode($contenidoBinario);
  $html.="<img style='margin-top:-12%;margin-left:-8%' src='data:image/jpeg;base64,$imagenComoBase64'>";
  $html.='<h3 STYLE="font-family:"Tahoma (Títulos)";margin-left:7.7cm;margin-top:2%;font-size:32px;font-weight: bold;">'.$nombre_estudiante_completo.'</h3></p><br>';
  $rutaImagen2 = "../../sga-data/foto/footer.png";
  $contenidoBinario2 = file_get_contents($rutaImagen2);
    $imagenComoBase642 = base64_encode($contenidoBinario2);
    $html.='<footer>
    '."<img style='margin-top:83%;margin-left:-7%' src='data:image/jpeg;base64,$imagenComoBase642'>".'
 </footer>';