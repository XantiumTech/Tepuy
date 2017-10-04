<?
 class class_funciones
 {
	var $cadena="";
	var $cadenabuscar="";
	var $ocurrencias=0;
	var $fecha="";
	var $scadena="";
 	var $scaracter;
	
	function class_funciones()
	{
	}
	
	function uf_posocurrencia($cad, $cadbus, $ocurr)
	{
	/*Function:  uf_posocurrencia( $cad, $cadbus, $ocurr )
	 *
	 *Descripción: Devuelve la posicion, segun la cantidad de ocurrencia, de una cadena
	 *             encontrada dentro de otra.
	 *Arguments:   $cad: cadena, $cadbus:cadena a buscar, $ocurr:*/
	   $pos    = 0;
	   $count  = 0;
	   $pos2   = 0;
	   $possig = 0;
	
      for ($i=0; $i<$ocurr; $i++)
	  {
	   if ($i==0) 
	   {
           $pos=strpos($cad,$cadbus);
	   } 
	  else 
	  { 
	    $lencad=strlen($cadbus);
		$possig=$lencad + $pos;
	    $pos=strpos($cad,$cadbus,$possig);
	  }
 }	 
  $posret=$pos;
  return $posret;
  
}


   function uf_cerosderecha($cadena, $longitud)
   {
	/*Function:  uf_cerosderecha( $cadena, $longuitud )
	 *
	 *Descripción: Devuelve una cadena rellena con ceros a la derecha
	 *             
	 *Arguments:   $cadena: cadena, $longuitud:cantidad de ceros a rellenar*/
 
  	 $len=0;
     $aux=$cadena;
	 $pos=strlen($cadena);
     $len=$longitud-$pos;
     for ($i=0;$i<$len;$i++)
     {
	   $aux=$aux."0";      
     }
	 return $aux; 
   }
   
	 /*Function:  uf_cerosizquierda($cadena,$longitud)
	 *
	 *Descripción: Devuelve una cadena rellena con ceros a la izquierda
	 *             
	 *Arguments:   $cadena: cadena, $longuitud:cantidad de ceros a rellenar*/
 
   function uf_cerosizquierda($cadena,$longitud)
   { 
     $len=0;
     $aux=$cadena;
	 $pos=strlen($cadena);
     $len=$longitud-$pos;
     for ($i=0;$i<$len;$i++)
     {
       $aux="0".$aux;      
     }
     return $aux; 
   }
   
  /// Rellenar por la derecha y por la izquierda
  function uf_rellenar_der ( $cadena , $caracter , $longitud ) 
  {
  // 	$i = strlen ( $cadena) + $longitud;
   	$cadena = str_pad ( $cadena , $longitud , $caracter , STR_PAD_RIGHT);
	return $cadena;
   }
   
  function uf_rellenar_izq ( $cadena , $caracter , $longitud )
  {
//   $i = strlen($cadena) + $longitud;
   $cadena = str_pad ($cadena,$longitud,$caracter,STR_PAD_LEFT);
   return $cadena;
  }
   
  function uf_rellenar_lados ( $cadena , $caracter , $longitud ) 
  {
   	$i = strlen ( $cadena ) + ( $longitud * 2 );
   	$cadena = str_pad ( $cadena , $i , $caracter , STR_PAD_BOTH );
   	return $cadena;
  }
  
  function uf_convertirdatetobd($as_cadena)
  {
    $ls_fecreg=""; 
	$ls_fecreg=(substr($as_cadena,6,4)."-".substr($as_cadena,3,2)."-".substr($as_cadena,0,2)); 
    return $ls_fecreg;
  }
  
  function uf_convertirfecmostrar($as_cadena)
  {
    $ls_fecha=""; 
    $ls_fecha=(substr($as_cadena,8,2)."/".substr($as_cadena,5,2)."/".substr($as_cadena,0,4)); 
    return $ls_fecha;
 }
 
 function uf_trim($cadena)
 {
  $oldcadena=$cadena;
  $newcadena="";
  $schar="";
  $blanco=""; 
  $i=0;
  $ac_cadena=str_split($oldcadena);
  $tot=count($ac_cadena);
 	for($i=0;$i<$tot;$i++) 
	{
			if($ac_cadena[$i]!=' ')
			{
		  		$newcadena.=$ac_cadena[$i];
			}		
 	}
 	return $newcadena;
  }	
 
  function uf_convertirmsg($as_mensaje) 
	{
	    ////////////////////////////////////////////////////////////////////////////////////////////////////////	   
	    //    Function:   uf_convertirmsg($as_mensaje) 
	    // Descripción:   método que convierte una tira de catracteres en mensaje visual
	    //   Arguments:   $as_mensaje
	    ////////////////////////////////////////////////////////////////////////////////////////////////////////	   	   
		$ls_mensaje=substr($as_mensaje,0,36);
		$ls_mensaje=str_replace("'"," ",$ls_mensaje);
		$ls_mensaje=str_replace(";"," ",$ls_mensaje);
		$ls_mensaje=str_replace("("," ",$ls_mensaje);
		$ls_mensaje=str_replace(")"," ",$ls_mensaje);
		$ls_mensaje=str_replace("+"," ",$ls_mensaje);
		$ls_mensaje=str_replace("-"," ",$ls_mensaje);
		$ls_mensaje=str_replace(chr(13)," ",$ls_mensaje);
		$ls_mensaje=str_replace(chr(10)," ",$ls_mensaje);
		return $ls_mensaje;
	} // end function
  
 
}	
?>