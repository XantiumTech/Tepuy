<?PHP
class tepuy_scv_c_tablaviatico
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_personal;
	var $ls_codemp;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function tepuy_scv_c_tablaviatico()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: tepuy_scv_c_tablaviatico
		//		   Access: public (tepuy_snorh_d_tablaviatico)
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/tepuy_include.php");
		$io_include=new tepuy_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($io_conexion);	
		require_once("../shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once("../shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();		
		require_once("../shared/class_folder/tepuy_c_seguridad.php");
		$this->io_seguridad=new tepuy_c_seguridad();
		$this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}// end function tepuy_scv_c_tablaviatico
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (tepuy_snorh_d_tablaviatico)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($io_include);
		unset($io_conexion);
		unset($this->io_sql);	
		unset($this->io_mensajes);		
		unset($this->io_funciones);		
		unset($this->io_seguridad);
        unset($this->ls_codemp);
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_tablaviatico($as_codtabvia,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_tablaviatico_periodo
		//		   Access: public (tepuy_snorh_d_tablaviatico)
		//	    Arguments: as_codtabvia  // código de la tabla de viatico
		//				   ai_totrows  // total de filas del detalle
		//				   ao_object  // objetos del detalle
		//	      Returns: lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que obtiene todos los períodos de una tabla de viaticoes
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, tipoviatico, cantsalarioini, cantsalariofin, utfuera, utdentro ".
				"  FROM scv_tablaviatico ".
				" WHERE codemp='".$this->ls_codemp."'".
				" ORDER BY tipoviatico";
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Tabla Viatico MÉTODO->uf_load_tablaviatico ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows=$ai_totrows+1;
				$li_tipovia=$row["tipoviatico"];
				$li_cantsalini=$row["cantsalarioini"];
				$li_cantsalfin=$row["cantsalariofin"];
				$li_utfuera=$row["utfuera"];
				$li_utdentro=$row["utdentro"];

				$ao_object[$ai_totrows][1]="<input aling=center name=txtcodviatico".$ai_totrows." type=text id=txtcodviatico".$ai_totrows." class=sin-borde size=6 value='".$li_tipovia."' onKeyUp='javascript: ue_validarnumero(this);' readonly>";
				$ao_object[$ai_totrows][2]="<input name=txtcantsalarioini".$ai_totrows." type=text id=txtcantsalarioini".$ai_totrows." class=sin-borde size=6 value='".$li_cantsalini."' onKeyUp='javascript: ue_validarnumero(this);'>";
				$ao_object[$ai_totrows][3]="<input name=txtcantsalariofin".$ai_totrows." type=text id=txtcantsalariofin".$ai_totrows." class=sin-borde size=6 value='".$li_cantsalfin."' onKeyUp='javascript: ue_validarnumero(this);'>";
				$ao_object[$ai_totrows][4]="<input name=txtutfuera".$ai_totrows." type=text id=txtutfuera".$ai_totrows." class=sin-borde size=6 value='".$li_utfuera."' onKeyUp='javascript: ue_validarnumero(this);'> ";
				$ao_object[$ai_totrows][5]="<input name=txtutdentro".$ai_totrows." type=text id=txtutdentro".$ai_totrows." class=sin-borde size=6 value='".$li_utdentro."' onKeyUp='javascript: ue_validarnumero(this);'> ";
				$ao_object[$ai_totrows][6]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/aprobado.png alt=Aceptar width=15 height=15 border=0></a>";
				$ao_object[$ai_totrows][7]="<a href=javascript:uf_delete_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/eliminar.png alt=Aceptar width=15 height=15 border=0></a>";
			}
			$ai_totrows=$ai_totrows+1;
			$ao_object[$ai_totrows][1]="<input aling=center name=txtcodviatico".$ai_totrows." type=text id=txlcodviatico".$ai_totrows." class=sin-borde size=6 maxlength=3 onKeyUp='javascript: ue_validarnumero(this);'>";
			$ao_object[$ai_totrows][2]="<input name=txtcantsalarioini".$ai_totrows." type=text id=txtcantsalarioini".$ai_totrows." class=sin-borde size=6 maxlength=3 onKeyUp='javascript: ue_validarnumero(this);'>";
			$ao_object[$ai_totrows][3]="<input name=txtcantsalariofin".$ai_totrows." type=text id=txtcantsalariofin".$ai_totrows." class=sin-borde size=6 maxlength=3 onKeyUp='javascript: ue_validarnumero(this);'>";
			$ao_object[$ai_totrows][4]="<input name=txtutfuera".$ai_totrows." type=text id=txtutfuera".$ai_totrows." class=sin-borde size=6 maxlength=3 onKeyUp='javascript: ue_validarnumero(this);'>";
			$ao_object[$ai_totrows][5]="<input name=txtutdentro".$ai_totrows." type=text id=txtutdentro".$ai_totrows." class=sin-borde size=6 maxlength=3 onKeyUp='javascript: ue_validarnumero(this);'>";
			$ao_object[$ai_totrows][6]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/aprobado.png alt=Aceptar width=15 height=15 border=0></a>";
			$ao_object[$ai_totrows][7]="<a href=javascript:uf_delete_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/eliminar.png alt=Aceptar width=15 height=15 border=0></a>";
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_tablaviatico
	//-----------------------------------------------------------------------------------------------------------------------------------	
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_tablaviatico_periodo($as_codtabvia,$ai_lappervac,$ai_diadisvac,$ai_diabonvac,$ai_diaadidisvac,$ai_diaadibonvac,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_tablaviatico_periodo
		//		   Access: private
		//	    Arguments: as_codtabvia  // código de la tabla de viatico
		//				   ai_lappervac  // lapso
		//				   ai_diadisvac  // dias de disfrute
		//				   ai_diabonvac  // dias de bono
		//				   ai_diaadidisvac  // días adicionales de disfrute
		//				   ai_diaadibonvac  // días adicionales de bono
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta en la tabla de Viatico período
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_tablavacperiodo ".
				"(codemp,codtabvia,lappervac,diadisvac,diabonvac,diaadidisvac,diaadibonvac)VALUES".
				"('".$this->ls_codemp."','".$as_codtabvia."',".$ai_lappervac.",".$ai_diadisvac.",".
				"".$ai_diabonvac.",".$ai_diaadidisvac.",".$ai_diaadibonvac.")";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Tabla Viatico MÉTODO->uf_insert_tablaviatico_periodo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////					
			$ls_evento="INSERT";
			$ls_descripcion="Insertó el período ".$ai_lappervac." asociado a la tabla de Viatico ".$as_codtabvia;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}
		return $lb_valido;
	}// end function uf_insert_tablaviatico_periodo	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_tablaviatico_periodo($as_codtabvia,$ai_lappervac,$ai_diadisvac,$ai_diabonvac,$ai_diaadidisvac,$ai_diaadibonvac,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_tablaviatico_periodo
		//		   Access: private
		//	    Arguments: as_codtabvia  // código de la tabla de viatico
		//				   ai_lappervac  // lapso
		//				   ai_diadisvac  // dias de disfrute
		//				   ai_diabonvac  // dias de bono
		//				   ai_diaadidisvac  // días adicionales de disfrute
		//				   ai_diaadibonvac  // días adicionales de bono
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza en la tabla de Viatico período
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_tablavacperiodo ".
				"   SET diadisvac = ".$ai_diadisvac.", ".
				"  		diabonvac = ".$ai_diabonvac.", ".
				"		diaadidisvac = ".$ai_diaadidisvac.", ".
				"		diaadibonvac = ".$ai_diaadibonvac." ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codtabvia = '".$as_codtabvia."'".
				"   AND lappervac = ".$ai_lappervac."";

		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Tabla Viatico MÉTODO->uf_update_tablaviatico_periodo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		} 
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////					
			$ls_evento="UPDATE";
			$ls_descripcion="Actualizó el período ".$ai_lappervac." asociado a la tabla de Viatico ".$as_codtabvia;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}		
		return $lb_valido;
	}// end function uf_update_tablaviatico_periodo	
	//-----------------------------------------------------------------------------------------------------------------------------------

	function uf_select_itemviatico($as_tipoviatico)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_itemviatico
		//		   Access: private
		//	    Arguments: as_codtabvac  // código de la tabla de vacacion
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si la tabla de vacacion está registrada
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT tipoviatico ".
				"  FROM scv_tablaviatico ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND tipoviatico='".$as_tipoviatico."'";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Tabla Viatico MÉTODO->uf_select_tablavitico ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if(!$row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=false;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_existe;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_tablaviatico($ai_lappervac,$ai_diadisvac,$ai_diabonvac,$ai_diaadidisvac,$ai_diaadibonvac,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_tablaviatico
		//		   Access: private
		//	    Arguments: 		   ai_lappervac  // lapso
		//				   ai_diadisvac  // dias de disfrute
		//				   ai_diabonvac  // dias de bono
		//				   ai_diaadidisvac  // días adicionales de disfrute
		//				   ai_diaadibonvac  // días adicionales de bono
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta en la tabla de vacación período
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO scv_tablaviatico ".
				"(codemp,tipoviatico,cantsalarioini,cantsalariofin,utfuera,utdentro)VALUES".
				"('".$this->ls_codemp."','".$ai_lappervac."','".$ai_diadisvac."','".$ai_diabonvac."','".$ai_diaadidisvac."','".$ai_diaadibonvac."')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Tabla Viatico MÉTODO->uf_insert_tablaviatico ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////					
			$ls_evento="INSERT";
			$ls_descripcion="Insertó datos en la tabla de Vacaciones";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}
		return $lb_valido;
	}// end function uf_insert_tablavacacion_periodo	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_tablaviatico($ai_lappervac,$ai_diadisvac,$ai_diabonvac,$ai_diaadidisvac,$ai_diaadibonvac,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_tablaviatico
		//		   Access: private
		//	    Arguments: as_codtabvac  // código de la tabla de vacacion
		//				   ai_lappervac  // lapso
		//				   ai_diadisvac  // dias de disfrute
		//				   ai_diabonvac  // dias de bono
		//				   ai_diaadidisvac  // días adicionales de disfrute
		//				   ai_diaadibonvac  // días adicionales de bono
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza en la tabla de vacación período
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE scv_tablaviatico ".
				"   SET cantsalarioini = ".$ai_diadisvac.", cantsalariofin = ".$ai_diabonvac.", ".
				"	utfuera = ".$ai_diaadidisvac.", utdentro = ".$ai_diaadibonvac." ".
				" WHERE codemp='".$this->ls_codemp."' AND tipoviatico = ".$ai_lappervac."";
		//print $ls_sql;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Tabla Viatico MÉTODO->uf_update_tablaviatico ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		} 
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////					
			$ls_evento="UPDATE";
			$ls_descripcion="Actualizó Datos en la tabla de Viaticos";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}		
		return $lb_valido;
	}// end function uf_update_tablaviatico
	//-----------------------------------------------------------------------------------------------------------------------------------


	function uf_guardar_itemviatico($ai_lappervac,$ai_diadisvac,$ai_diabonvac,$ai_diaadidisvac,$ai_diaadibonvac,&$as_existe,$aa_seguridad)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar_periodo
		//		   Access: public (tepuy_snorh_d_tablaviatico)
		//	    Arguments: as_codtabvia  // código de la tabla de viatico
		//				   ai_lappervac  // lapso
		//				   ai_diadisvac  // dias de disfrute
		//				   ai_diabonvac  // dias de bono
		//				   ai_diaadidisvac  // días adicionales de disfrute
		//				   ai_diaadibonvac  // días adicionales de bono
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que almacena el perído relacionado con la tabla de Viatico
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if($this->uf_select_itemviatico($ai_lappervac)===false)
		{
			$as_existe="FALSE";
			$lb_valido=$this->uf_insert_tablaviatico($ai_lappervac,$ai_diadisvac,$ai_diabonvac,$ai_diaadidisvac,$ai_diaadibonvac,$aa_seguridad);
		}
		else
		{
			$as_existe="TRUE";
			$lb_valido=$this->uf_update_tablaviatico($ai_lappervac,$ai_diadisvac,$ai_diabonvac,$ai_diaadidisvac,$ai_diaadibonvac,$aa_seguridad);
		}
		return $lb_valido;
	}// end function uf_guardar_itemviatico	
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_itemviatico($as_codtabvia,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_perido
		//		   Access: public (tepuy_snorh_d_tablaviatico)
		//	    Arguments: as_codtabvia  // código de la tabla de viatico
		//				   ai_lappervac  // lapso
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina la tabla de Viatico período un período en partícular
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="DELETE FROM scv_tablaviatico ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND tipoviatico='".$as_codtabvia."'";
				//"   AND lappervac='".$ai_lappervac."'";		
		//print $ls_sql;
		$this->io_sql->begin_transaction();
	   	$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Tabla Viatico MÉTODO->uf_delete_itemviatico ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó el Tipo de Viatico asociado a la tabla de Viatico ";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Item de Viatico fue Eliminado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Tabla Viatico MÉTODO->uf_delete_itemviatico ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_delete_itemviatico
	//-----------------------------------------------------------------------------------------------------------------------------------


		//-----------------------------------------------------------------------------------------------------------------------------------
}
?>
