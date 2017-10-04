<?php

class tepuy_srh_c_asignar_concurso
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

function tepuy_srh_c_asignar_concurso($path)
	{   
	    require_once($path."shared/class_folder/class_sql.php");
		require_once($path."shared/class_folder/class_datastore.php");
		require_once($path."shared/class_folder/class_mensajes.php");
		require_once($path."shared/class_folder/tepuy_include.php");
		require_once($path."shared/class_folder/tepuy_c_seguridad.php");
		require_once($path."shared/class_folder/class_funciones.php");
	    $this->io_msg=new class_mensajes();
		$this->io_funcion = new class_funciones();
		$this->la_empresa=$_SESSION["la_empresa"];
		$in=new tepuy_include();
		$this->con=$in->uf_conectar();
		$this->io_sql=new class_sql($this->con);
		$this->seguridad= new tepuy_c_seguridad();
		$this->ls_codemp=$_SESSION["la_empresa"]["codemp"];

	}
	
	
	
function uf_srh_getProximoCodigo()
  {
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_getProximoCodigo
		//         Access: public (tepuy_srh_p_asignar_concurso)
		//      Argumento: 
		//	      Returns: Retorna el nuevo código del registro de una asignación de concurso
		//    Description: Funcion que genera un código del registro de una asignación de concurso
		//	   Creado Por: Maria Beatriz Unda
		// Fecha Creación:17/01/2008							Fecha Última Modificación:17/01/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $ls_sql = "SELECT MAX(nroreg) AS numero FROM srh_asignar_concurso ";
    $lb_hay = $this->io_sql->seleccionar($ls_sql, $la_datos);
	if ($lb_hay)
    $ls_nroreg = $la_datos["numero"][0]+1;
    $ls_nroreg = str_pad ($ls_nroreg,10,"0","left");	 
    return $ls_nroreg;
  }
  
function uf_srh_guardarasignar_concurso ($ao_asignacion,$as_operacion="insertar", $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardarasignar_concurso																		
		//         access: public (tepuy_srh_asignar_concurso)														    			
		//      Argumento: $ao_asignacion    // arreglo con los datos de la asignación de personas a concurso								
		//                 $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta o modifica una asignación de personas a concurso en la tabla srh_asignar_concurso 
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 10/01/2008							Fecha Última Modificación: 10/01/2008							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
  	if ($as_operacion == "modificar")
	{
	 $this->io_sql->begin_transaction();
	 
	 $ao_asignacion->fecha=$this->io_funcion->uf_convertirdatetobd($ao_asignacion->fecha);
	
	 
	  $ls_sql = "UPDATE srh_asignar_concurso SET ".
		  		"fecha = '$ao_asignacion->fecha' , ".
	            "codcon = '$ao_asignacion->codcon' , ".
				"observacion = '$ao_asignacion->obs'  ".
	            "WHERE nroreg= '$ao_asignacion->nroreg'  AND codemp='".$this->ls_codemp."'" ;
		
	  
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó la asignación de personas a concurso ".$ao_asignacion->nroreg;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			    
	}
	else
	{ $this->io_sql->begin_transaction();
	
 	 $ao_asignacion->fecha=$this->io_funcion->uf_convertirdatetobd($ao_asignacion->fecha);
	
	
		
	  $ls_sql = "INSERT INTO srh_asignar_concurso (nroreg, codcon, fecha, observacion, codemp) ".	  
			"VALUES ('$ao_asignacion->nroreg', '$ao_asignacion->codcon','$ao_asignacion->fecha','$ao_asignacion->obs', '".$this->ls_codemp."')";

	
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la asignación de personas a concurso ".$ao_asignacion->nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	
	}
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->asignar_concurso MÉTODO->uf_srh_guardarasignar_concurso ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				$lb_valido=true;
				$this->io_sql->commit();
		}
	if($lb_guardo)	
		{
		//Guardamos los detalles de la asignación de personas a concurso
		$lb_guardo = $this->guardarDetalles_Asignacion($ao_asignacion, $aa_seguridad);
		}
	return $lb_guardo;
  }
	
	
	
function guardarDetalles_Asignacion ($ao_asignacion, $aa_seguridad)
  {
    //Borramos los registros anteriores 
	$this-> uf_srh_eliminar_persona_concurso($ao_asignacion->nroreg, $aa_seguridad);
	
	  
	//Ahora guardamos
	$lb_guardo = true;
	$li_per= 0;
	while (($li_per < count($ao_asignacion->personal)) &&
	       ($lb_guardo))
	{
	  $lb_guardo = $this-> uf_srh_guardar_persona_concurso($ao_asignacion->personal[$li_per], $aa_seguridad);
	  $li_per++;
	}
	
	$lb_guardo = true;
	$li_per2= 0;
	while (($li_per2 < count($ao_asignacion->personalext)) &&
	       ($lb_guardo))
	{
	  $lb_guardo = $this-> uf_srh_guardar_persona_concurso($ao_asignacion->personalext[$li_per2], $aa_seguridad);
	  $li_per2++;
	}
	
	return $lb_guardo;    
  }

	
	
	
function uf_srh_eliminarasignar_concurso($as_nroreg,  $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminarasignar_concurso																															
		//      Argumento: $as_nroreg       // codigo de la asignación de personas a concurso								
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que elimina una asignación de personas a concurso en la tabla srh_asignar_concurso              
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 10/01/2008							Fecha Última Modificación: 10/01/2008							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
	$this-> uf_srh_eliminar_persona_concurso($as_nroreg, $aa_seguridad);
	
    $ls_sql = "DELETE FROM srh_asignar_concurso ".
	          "WHERE nroreg = '$as_nroreg'   AND codemp='".$this->ls_codemp."'";

  
	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->asignar_concurso MÉTODO->uf_srh_eliminarasignar_concurso ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó la asignación de personas a concurso ".$as_nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				
					$this->io_sql->commit();
			}
	
	return $lb_borro;
  }
	
	
	
function uf_srh_buscar_asignar_concurso($as_nroreg,$as_fecha1, $as_fecha2,$as_codcon)
	{
	
	     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_asignar_concurso																											
		//      Argumento: $as_nroreg   // número de la asignación de personas a concurso                   
		//                 $as_fecha   // fecha de la asignación de personas a concurso
		//                 $as_codcon   //  código del concurso
		//	      Returns: Retorna un XML  																						
		//    Description: Funcion busca una evaluación en la tabla srh_asignar_concurso y crea un XML para mostrar    
		//                  los datos en el catalogo                                                                            //
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 10/01/2008							Fecha Última Modificación: 10/01/2008							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
		
		$as_fecha1=$this->io_funcion->uf_convertirdatetobd($as_fecha1);
		$as_fecha2=$this->io_funcion->uf_convertirdatetobd($as_fecha2);
		
			
	    $ls_nroregdestino="txtnroreg";
		$ls_fechadestino="txtfecha";
		$ls_obsdestino="txtobs";
		$ls_coddestino="txtcodcon";
		$ls_desdestino="txtdescon";
		$ls_fechaaperdestino="txtfechaaper";
		$ls_fechaciedestino="txtfechacie";
		$ls_codcardestino="txtcodcar";
		$ls_dencardestino="txtdencar";
		$ls_cantcardestino="txtcantcar";
		$ls_tipcondestino="txtcodtipconcur";
		
		$lb_valido=true;
		
		
				
		$ls_sql= "SELECT *  FROM srh_asignar_concurso INNER JOIN srh_concurso ON (srh_concurso.codcon = srh_asignar_concurso.codcon)  ".
				"   AND srh_asignar_concurso.nroreg like '$as_nroreg' ".
				"   AND srh_asignar_concurso.codcon like '$as_codcon' ".
				"   AND fecha BETWEEN '".$as_fecha1."' AND '".$as_fecha2."' ".
				" ORDER BY nroreg";				
				
				
	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->asignar_concurso MÉTODO->uf_srh_buscar_asignar_concurso( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);			
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
		
			     
					$ls_nroreg=$row["nroreg"];
					$ls_fecha=$this->io_funcion->uf_formatovalidofecha($row["fecha"]);
				    $ls_fecha=$this->io_funcion->uf_convertirfecmostrar($ls_fecha);
					$ls_obs=trim (htmlentities ($row["observacion"]));
					
					$ls_codcon=$row["codcon"];
					$ls_descon= trim (htmlentities  ($row["descon"]));
					
					$ls_fechaaper=$this->io_funcion->uf_formatovalidofecha($row["fechaaper"]);
				    $ls_fechaaper=$this->io_funcion->uf_convertirfecmostrar($ls_fechaaper);
					
					$ls_fechacie=$this->io_funcion->uf_formatovalidofecha($row["fechacie"]);
				    $ls_fechacie=$this->io_funcion->uf_convertirfecmostrar($ls_fechacie);	
					
					$ls_codcar=$row["codcar"];
					$ls_tipcon= trim (htmlentities  ($row["tipo"]));
					/*$ls_dencar=$row["dencar"];*/
					$li_cantcar=$row["cantcar"];
					
					
					
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$row['nroreg']);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					
					$cell->appendChild($dom->createTextNode($row['nroreg']." ^javascript:aceptar( \"$ls_nroreg\", \"$ls_fecha\", \"$ls_obs\", \"$ls_codcon\", \"$ls_descon\", \"$ls_coddestino\", \"$ls_desdestino\",\"$ls_fechaaperdestino\", \"$ls_fechaaper\" , \"$ls_fechaciedestino\", \"$ls_fechacie\", \"$ls_codcardestino\", \"$ls_codcar\", \"$ls_cantcardestino\", \"$li_cantcar\",  \"$ls_nroregdestino\", \"$ls_fechadestino\", \"$ls_obsdestino\", \"$ls_tipcon\", \"$ls_tipcondestino\");^_self"));
					
				
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_fecha));												
					$row_->appendChild($cell);
					
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_descon));												
					$row_->appendChild($cell);
			
					
			
			}
			return $dom->saveXML();
		
			
			
		
		}
      
		
	} // end function buscar_asignar_concurso
	

//FUNCIONES PARA EL MANEJO DEL DETALLE DE LA ASIGNACIÓN DE PERSONAS A CONCURSO

function uf_srh_guardar_persona_concurso($ao_asignacion, $aa_seguridad)
  { 
  
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_guardar_persona_concurso														     	
		//         access: public (tepuy_dt_srh_asignar_concurso)														
		//      Argumento: $ao_asignacion    // arreglo con los datos de los detalle de la asignación de personas a concurso					
		//                 $as_operacion    //  variable que guarda la operacion a ejecutar (insertar o modificar)              
		//                 $aa_seguridad    //   arreglo de registro de seguridad                                               
		//	      Returns: Retorna un Booleano																					
		//    Description: Funcion que inserta o modifica una asignación de personas a concurso en la tabla 
		//				   srh_persona_concurso           
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 10/01/2008							Fecha Última Modificación: 10/01/2008							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
  
	 $this->io_sql->begin_transaction();
	  
	  $ls_sql = "INSERT INTO srh_persona_concurso (nroreg,codper,tipo, codemp) ".	  
	            " VALUES ('$ao_asignacion->nroreg','$ao_asignacion->codper','$ao_asignacion->tipo','".$this->ls_codemp."')";

		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la persona  al concurso ".$ao_asignacion->nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	
	$lb_guardo = $this->io_sql->execute($ls_sql);

     if($lb_guardo===false)
		{
			$this->io_msg->message("CLASE->asignar_concurso MÉTODO->uf_srh_guardar_persona_concurso ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				$lb_valido=true;
				$this->io_sql->commit();
		}
		
	return $lb_guardo;
  }
	
	
function uf_srh_eliminar_persona_concurso($as_nroreg, $aa_seguridad)
  {
  
         /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_eliminar_persona_concurso																
		//        access:  public (tepuy_srh_persona_concurso)														
		//      Argumento: $as_nroreg        // numero de la selección de elegibles a prueba
		//                 $aa_seguridad    //  arreglo de registro de seguridad                                                
		//	      Returns: Retorna un Booleano																				    
		//    Description: Funcion que elimina una solicitud de adiestramiento en la tabla srh_persona_concurso
		//	   Creado Por: Maria Beatriz Unda																				    
		// Fecha Creación: 10/01/2008							Fecha Última Modificación: 10/01/2008							
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    $this->io_sql->begin_transaction();	
    $ls_sql = "DELETE FROM srh_persona_concurso ".
	          " WHERE nroreg='$as_nroreg'  AND codemp='".$this->ls_codemp."'";
		  

	$lb_borro=$this->io_sql->execute($ls_sql);
	if($lb_borro===false)
	 {
		$this->io_msg->message("CLASE->asignar_concurso MÉTODO->uf_srh_eliminar_persona_concurso ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	else
	 {
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó la persona interna al concurso ".$as_nroreg;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
			
	
	return $lb_borro;
	
  }
  
 
 


function uf_srh_load_asignar_concurso_campos($as_nroreg,&$ai_totrows,&$ao_object,&$hay)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_load_asignar_concurso_campos
		//	    Arguments: as_nroreg  // número de la seleccion de elegibles a prueba
		//				   ai_totrows  // total de filas del detalle
		//				   ao_object  // objetos del detalle
		//	      Returns: lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que obtiene todos los campos de una selección de elegibles a prueba
		// Fecha Creación: 10/01/2008							Fecha Última Modificación : 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;		
		
        $ls_sql="SELECT srh_persona_concurso.*, ".
		        " sno_personal.apeper, sno_personal.nomper, sno_asignacioncargo.denasicar, sno_cargo.descar, ".
				"(SELECT desuniadm FROM sno_unidadadmin,sno_personalnomina,srh_persona_concurso,sno_nomina WHERE 				
				  srh_persona_concurso.tipo = 'I' AND 
 				  srh_persona_concurso.codper = sno_personalnomina.codper AND 
 				  sno_unidadadmin.minorguniadm = sno_unidadadmin.minorguniadm AND 
 				  sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm AND
 				 sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm AND 
  				 sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm AND sno_personalnomina.prouniadm = 
  				 sno_unidadadmin.prouniadm AND sno_personalnomina.codnom=sno_nomina.codnom AND sno_nomina.espnom='0') as desuniadm".
				" FROM srh_persona_concurso ".
				" JOIN sno_personal on (srh_persona_concurso.codper=sno_personal.codper) ".
				" JOIN sno_personalnomina on (sno_personalnomina.codper=srh_persona_concurso.codper) ".
				" LEFT JOIN sno_asignacioncargo on (sno_personalnomina.codasicar=sno_asignacioncargo.codasicar) ".
				" LEFT JOIN sno_cargo on (sno_personalnomina.codcar=sno_cargo.codcar) ".
				" JOIN sno_nomina ON ( sno_nomina.codnom = sno_personalnomina.codnom AND sno_nomina.espnom='0')  ".
				" WHERE srh_persona_concurso.codemp='".$this->ls_codemp."'". 
				" AND srh_persona_concurso.nroreg ='".$as_nroreg."' ".
				" AND srh_persona_concurso.codper = sno_personal.codper ".
				"  AND srh_persona_concurso.tipo = 'I' ".
				"  group by srh_persona_concurso.codemp, srh_persona_concurso.nroreg, srh_persona_concurso.codper, ".
         		"		 srh_persona_concurso.tipo, sno_personal.apeper, sno_personal.nomper,sno_asignacioncargo.denasicar,sno_cargo.descar ".
				" ORDER BY srh_persona_concurso.codper";
	
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->asignar_concurso MÉTODO->uf_srh_load_asignar_concurso_campos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{ $num=$this->io_sql->num_rows($rs_data);
           
		  if ($num!=0) {
			$ai_totrows=0;
			$hay=true;
			$ls_codper="";
			$ls_carper="";
			$ls_dep="";
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows++;
				$ls_codper=$row["codper"];				   
				$ls_cargo1=trim (htmlentities ($row["denasicar"]));
				$ls_cargo2=trim (htmlentities ($row["descar"]));
				
				if ($ls_cargo1!="Sin Asignación de Cargo")
				{
					$ls_carper=$ls_cargo1;
				}
				if ($ls_cargo2!="Sin Cargo")
				{
					$ls_carper=$ls_cargo2;
				}				
					$ls_dep=trim (htmlentities ($row["desuniadm"]));
					$ls_apeper=trim (htmlentities ($row["apeper"]));
										
					if ($ls_apeper!='0') {
					$ls_nomper=trim (htmlentities ($row["nomper"])).' '.trim (htmlentities ($row["apeper"]));
					
					}
					else  {
					 $ls_nomper=htmlentities ($row["nomper"]);
					}
				
				$ao_object[$ai_totrows][1]="<input name=txtcodper".$ai_totrows." type=text id=txtcodper".$ai_totrows."  class=sin-borde readonly size=15 maxlength=10 value='".$ls_codper."'>";
				$ao_object[$ai_totrows][2]="<input name=txtnomper".$ai_totrows." type=text id=txtnomper".$ai_totrows." class=sin-borde readonly size=35 value='".$ls_nomper."'>";
				$ao_object[$ai_totrows][3]="<input name=txtcarper".$ai_totrows." type=text id=txtcarper".$ai_totrows." class=sin-borde  size=20 value='".$ls_carper."' readonly>";
				$ao_object[$ai_totrows][4]="<input name=txtdep".$ai_totrows." type=text id=txtdep".$ai_totrows." class=sin-borde  size=40 value='".$ls_dep."' readonly>";
				$ao_object[$ai_totrows][5]="<a href=javascript:catalogo_personal(".$ai_totrows.");    align=center><img src=../../../../shared/imagebank/tools15/buscar.png alt=Buscar width=15 height=15 border=0 align=center></a>";	
				$ao_object[$ai_totrows][6]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");  align=center><img src=../../../../shared/imagebank/tools15/aprobado.png alt=Aceptar width=15 height=15 border=0 align=center></a>";
				$ao_object[$ai_totrows][7]="<a href=javascript:uf_delete_dt(".$ai_totrows.");     align=center><img src=../../../../shared/imagebank/tools15/eliminar.png alt=Eliminar width=15 height=15 border=0 align=center></a>";
			
	
			}
			$this->io_sql->free_result($rs_data);
		}
		else  {
		    $ai_totrows=1;	
			$hay=false;
			$ao_object[$ai_totrows][1]="<input name=txtcodper".$ai_totrows." type=text id=txtcodper".$ai_totrows."  class=sin-borde readonly size=15 maxlength=10 >";
			$ao_object[$ai_totrows][2]="<input name=txtnomper".$ai_totrows." type=text id=txtnomper".$ai_totrows." class=sin-borde readonly size=35 >";
			$ao_object[$ai_totrows][3]="<input name=txtcarper".$ai_totrows." type=text id=txtcarper".$ai_totrows." class=sin-borde  size=20 >";
			$ao_object[$ai_totrows][4]="<input name=txtdep".$ai_totrows." type=text id=txtdep".$ai_totrows." class=sin-borde  size=40 readonly>";
			$ao_object[$ai_totrows][5]="<a href=javascript:catalogo_personal(".$ai_totrows.");    align=center><img src=../../../../shared/imagebank/tools15/buscar.png alt=Buscar width=15 height=15 border=0 align=center></a>";	
			$ao_object[$ai_totrows][6]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");  align=center><img src=../../../../shared/imagebank/tools15/aprobado.png alt=Aceptar width=15 height=15 border=0 align=center></a>";
			$ao_object[$ai_totrows][7]="<a href=javascript:uf_delete_dt(".$ai_totrows.");     align=center><img src=../../../../shared/imagebank/tools15/eliminar.png alt=Eliminar width=15 height=15 border=0 align=center></a>";
		
		}
		}
		return $lb_valido;
	}
	
function uf_srh_load_asignar_concurso_campos2($as_nroreg,&$ai_totrows,&$ao_object,&$hay)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_load_asignar_concurso_campos2
		//	    Arguments: as_nroreg  // número de la seleccion de elegibles a prueba
		//				   ai_totrows  // total de filas del detalle
		//				   ao_object  // objetos del detalle
		//	      Returns: lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que obtiene todos los campos de una selección de elegibles a prueba
		// Fecha Creación: 10/01/2008							Fecha Última Modificación : 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
		$ls_sql="SELECT * ".
				"  FROM srh_persona_concurso, srh_solicitud_empleo, sno_profesion, srh_nivelseleccion ".
				"  WHERE srh_persona_concurso.codemp='".$this->ls_codemp."'".
				"  AND srh_persona_concurso.nroreg = '".$as_nroreg."' ".
				"  AND srh_persona_concurso.tipo = 'E' ".
				"  AND srh_persona_concurso.codper = srh_solicitud_empleo.cedsol ".
				"  AND srh_solicitud_empleo.codpro = sno_profesion.codpro ".
				"  AND srh_solicitud_empleo.codniv = srh_nivelseleccion.codniv ".
				" ORDER BY srh_solicitud_empleo.cedsol ";

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->asignar_concurso MÉTODO->uf_srh_load_asignar_concurso_campos2 ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{  $num=$this->io_sql->num_rows($rs_data);
          $ls_despro=""; 
		  if ($num!=0) {
			$ai_totrows=0;
			$hay=true;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
					$ai_totrows++;
				    $ls_cedper=$row["cedsol"];
					$ls_nomsol= trim (htmlentities ($row["nomsol"]))." ".trim (htmlentities($row["apesol"]));
					$ls_despro=trim (htmlentities ($row["despro"]));
					$ls_denniv=trim (htmlentities ($row["denniv"]));
					
				
				$ao_object[$ai_totrows][1]="<input name=txtcedper".$ai_totrows." type=text id=txtcedper".$ai_totrows."  class=sin-borde readonly size=15 maxlength=10 value='".$ls_cedper."'>";
				$ao_object[$ai_totrows][2]="<input name=txtnomsol".$ai_totrows." type=text id=txtnomsol".$ai_totrows." class=sin-borde readonly size=35 value='".$ls_nomsol."'>";
				$ao_object[$ai_totrows][3]="<input name=txtdespro".$ai_totrows." type=text id=txtdespro".$ai_totrows." class=sin-borde  size=30 value='".$ls_despro."'>";
				$ao_object[$ai_totrows][4]="<input name=txtdenniv".$ai_totrows." type=text id=txtdenniv".$ai_totrows." class=sin-borde  size=30 value='".$ls_denniv."'>";
				$ao_object[$ai_totrows][5]="<a href=javascript:catalogo_solicitud_empleo(".$ai_totrows.");    align=center><img src=../../../../shared/imagebank/tools15/buscar.png alt=Buscar width=15 height=15 border=0 align=center></a>";	
				$ao_object[$ai_totrows][6]="<a href=javascript:uf_agregar_dt2(".$ai_totrows.");  align=center><img src=../../../../shared/imagebank/tools15/aprobado.png alt=Aceptar width=15 height=15 border=0 align=center></a>";
				$ao_object[$ai_totrows][7]="<a href=javascript:uf_delete_dt2(".$ai_totrows.");     align=center><img src=../../../../shared/imagebank/tools15/eliminar.png alt=Eliminar width=15 height=15 border=0 align=center></a>";
			
	
			}
			$this->io_sql->free_result($rs_data);
		}
	
	else {
			$ai_totrows=1;	
			$hay=false;
			$ao_object[$ai_totrows][1]="<input name=txtcedper".$ai_totrows." type=text id=txtcedper".$ai_totrows."  class=sin-borde readonly size=15 maxlength=10 >";
			$ao_object[$ai_totrows][2]="<input name=txtnomsol".$ai_totrows." type=text id=txtnomsol".$ai_totrows." class=sin-borde readonly size=40 >";
			$ao_object[$ai_totrows][3]="<input name=txtdespro".$ai_totrows." type=text id=txtdespro".$ai_totrows." class=sin-borde  size=30 value='".$ls_despro."'>";
			$ao_object[$ai_totrows][4]="<input name=txtdenniv".$ai_totrows." type=text id=txtdenniv".$ai_totrows." class=sin-borde  size=30 >";
			$ao_object[$ai_totrows][5]="<a href=javascript:catalogo_solicitud_empleo(".$ai_totrows.");    align=center><img src=../../../../shared/imagebank/tools15/buscar.png alt=Buscar width=15 height=15 border=0 align=center></a>";	
			$ao_object[$ai_totrows][6]="<a href=javascript:uf_agregar_dt2(".$ai_totrows.");  align=center><img src=../../../../shared/imagebank/tools15/aprobado.png alt=Aceptar width=15 height=15 border=0 align=center></a>";
			$ao_object[$ai_totrows][7]="<a href=javascript:uf_delete_dt2(".$ai_totrows.");     align=center><img src=../../../../shared/imagebank/tools15/eliminar.png alt=Eliminar width=15 height=15 border=0 align=center></a>";
			}
		}
		return $lb_valido;
	}
	//---------------------------------------------------------------------------------------------------------------------------------
	
	

function uf_srh_buscar_personal_concurso($as_codper,$as_apeper,$as_nomper,$as_hidcodcon)
	{
	
	     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_srh_buscar_asignar_concurso																											
		//      Argumento: as_codper   //  código del personal		
		//                 $as_apeper   //  apellido del personal                                                           
		//                 $as_nomper   //  nombre del personal
		//	      Returns: Retorna un XML  																						
		//    Description: Funcion busca un personal en la tabla srh_persona_concurso y crea un XML para mostrar    
		//                  los datos en el catalogo                                                                            //
		//	   Creado Por: Maria Beatriz Unda																				    //
		// Fecha Creación: 18/03/2008							Fecha Última Modificación: 18/03/2008							//
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	

		
	    $ls_codperdestino="txtcodper";
		$ls_nomperdestino="txtnomper";
		
		$lb_valido=true;
		
		
				
		$ls_sql= "SELECT *  FROM srh_concurso INNER JOIN srh_asignar_concurso ON
 (srh_concurso.codcon = srh_asignar_concurso.codcon) INNER JOIN srh_persona_concurso 
ON (srh_asignar_concurso.nroreg = srh_persona_concurso.nroreg)  LEFT JOIN sno_personal ON (srh_persona_concurso.codper = sno_personal.codper ) LEFT JOIN srh_solicitud_empleo ON (srh_persona_concurso.codper = srh_solicitud_empleo.cedsol)".
				"   WHERE srh_concurso.codcon =  '$as_hidcodcon' ".
				" ORDER BY srh_persona_concurso.codper ";
				
		
				
	    $rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->asignar_concurso MÉTODO-> uf_srh_buscar_personal_concurso( ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	
		
		    $dom = new DOMDocument('1.0', 'iso-8859-1');
		     $team = $dom->createElement('rows');
		     $dom->appendChild($team);			
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
		
			     if ($row["cedsol"]=="") {
					  $ls_codper=$row["codper"]; }
					  else {$ls_codper=$row["cedsol"];}
					
					if ($row["codper"]=="") {
					  $ls_codper=$row["cedsol"]; }
					  else {$ls_codper=$row["codper"];}
					  
								  
					  
					$ls_apeper =htmlentities (trim ($row["apeper"]));
					if ($ls_apeper=="") {
					  $ls_apeper=htmlentities (trim ($row["apesol"])); }
					  
					$ls_nomper =htmlentities (trim ($row["nomper"]));
					if ($ls_nomper=="") {
					$ls_nomper=htmlentities (trim ($row["nomsol"])); }
					
					$ls_nomper_completo=$ls_nomper." ".$ls_apeper;
					
					if ($row["tipo"]=='E') {
				  		 $ls_tipo='Externo';
				 	}
				 	elseif ($row["tipo"]=='I') {
				   		$ls_tipo='Interno';
				 	}
					
					
					$row_ = $team->appendChild($dom->createElement('row'));
					$row_->setAttribute("id",$ls_codper);
					$cell = $row_->appendChild($dom->createElement('cell'));   
					
					$cell->appendChild($dom->createTextNode($ls_codper." ^javascript:aceptar( \"$ls_codper\", \"$ls_nomper_completo\", \"$ls_codperdestino\",  \"$ls_nomperdestino\");^_self"));
					
				
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_apeper));												
					$row_->appendChild($cell);
				
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_nomper));												
					$row_->appendChild($cell);
					
					
					$cell = $row_->appendChild($dom->createElement('cell'));
					$cell->appendChild($dom->createTextNode($ls_tipo));												
					$row_->appendChild($cell);
			
			}
			return $dom->saveXML();
		}
      
		
	} // end function buscar_asignar_concurso


}// end   class tepuy_srh_c_asignar_concurso
?>