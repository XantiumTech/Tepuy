<?php
class tepuy_sfa_c_modcmpret
 {
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $ls_codemp;
	var $io_dscuentas;

	//----------------------------------------------------------------------------------------------------------------
	function tepuy_sfa_c_modcmpret($as_path)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: tepuy_cxp_c_modcmpret
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 02/09/2017  21/09/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once($as_path."shared/class_folder/tepuy_include.php");
		$io_include=new tepuy_include();
		$io_conexion=$io_include->uf_conectar();
		require_once($as_path."shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($io_conexion);	
		require_once($as_path."shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once($as_path."shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();		
		require_once($as_path."shared/class_folder/tepuy_c_seguridad.php");
		$this->io_seguridad= new tepuy_c_seguridad();
		require_once($as_path."shared/class_folder/class_fecha.php");		
		$this->io_fecha= new class_fecha();
		require_once($as_path."shared/class_folder/class_datastore.php");
		require_once($as_path."shared/class_folder/tepuy_c_reconvertir_monedabsf.php");
		$this->io_rcbsf= new tepuy_c_reconvertir_monedabsf();
		$this->li_candeccon=$_SESSION["la_empresa"]["candeccon"];
		$this->li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
		$this->li_redconmon=$_SESSION["la_empresa"]["redconmon"];
	        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}// end function tepuy_sfa_c_modcmpret
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (tepuy_cxp_c_modcmpret.php)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 02/04/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($io_include);
		unset($io_conexion);
		unset($this->io_sql);	
		unset($this->io_mensajes);		
		unset($this->io_funciones);		
		unset($this->io_seguridad);
		unset($this->io_fecha);
        unset($this->ls_codemp);
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	function uf_load_dt_cmpret($as_numcom,$as_codret)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_dt_cmpret
		//		   Access: public
		//		 Argument: as_numcom // Número del Comprobante
		//		           as_codret // Codigo de la Retencion
		//	  Description: Función que busca los Comprobantes de Retencion
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 21/09/2007								Fecha Última Modificación : 21/09/2007
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT DCMRT.numope,DCMRT.numfac,DCMRT.numcon,DCMRT.fecfac,DCMRT.totcmp_sin_iva,DCMRT.totcmp_con_iva,DCMRT.basimp,DCMRT.porimp,DCMRT.totimp,DCMRT.iva_ret,DCMRT.numdoc,DCMRT.codret,DCMRT.numsop,DCMRT.numnd,DCMRT.numnc,DCMRT.tiptrans ".
				"  FROM scb_dt_cmp_ret DCMRT ".	
				"  WHERE codemp='".$this->ls_codemp."' ".
				"  AND numcom= '".$as_numcom."' AND codret='".$as_codret."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Modificar Comprobate MÉTODO->uf_load_dt_cmpret ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		return $rs_data;
	}// end function uf_load_dt_cmpret

//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_dt_cmpret($as_numcom,$as_codret, $ai_totrowrecepciones,$as_probene,$as_codigo, $aa_seguridad)
	{
		 ///////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_dt_cmpret
		//		   Access: private
		//	    Arguments: as_numcom            // Número del Comprobante 
		//				   as_codret            // Código de la retencion
		//				   ai_totrowrecepciones // Total de Filas Detalles del Comprobante
		//				   aa_seguridad         // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta los detalles del comprobante
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 21/09/2007 								Fecha Última Modificación : 21/09/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		for($li_i=1;($li_i<$ai_totrowrecepciones)&&($lb_valido);$li_i++)
		{
			$ls_numope=$_POST["txtnumope".$li_i];
			$ls_fecfac=$this->io_funciones->uf_convertirdatetobd($_POST["txtfecfac".$li_i]);
			$ls_numfac=$_POST["txtnumfac".$li_i];
			$ls_numcon=$_POST["txtnumcon".$li_i];
			$ls_numnd=$_POST["txtnumnd".$li_i];
			$ls_numnc=$_POST["txtnumnc".$li_i];
			$ls_tiptrans=$_POST["txttiptrans".$li_i];
			$ls_tot_cmp_sin_iva=$_POST["txttotsiniva".$li_i];
			$ls_tot_cmp_sin_iva=str_replace(".","",$ls_tot_cmp_sin_iva);
			$ls_tot_cmp_sin_iva=str_replace(",",".",$ls_tot_cmp_sin_iva);
			$ls_tot_cmp_con_iva=$_POST["txttotconiva".$li_i];
			$ls_tot_cmp_con_iva=str_replace(".","",$ls_tot_cmp_con_iva);
			$ls_tot_cmp_con_iva=str_replace(",",".",$ls_tot_cmp_con_iva);
			$ls_basimp=$_POST["txtbasimp".$li_i];
			$ls_basimp=str_replace(".","",$ls_basimp);
			$ls_basimp=str_replace(",",".",$ls_basimp);
			$ls_porimp=$_POST["txtporimp".$li_i];
			$ls_porimp=str_replace(".","",$ls_porimp);
			$ls_porimp=str_replace(",",".",$ls_porimp);
			$ls_totimp=$_POST["txttotimp".$li_i];
			$ls_totimp=str_replace(".","",$ls_totimp);
			$ls_totimp=str_replace(",",".",$ls_totimp);
			$ls_ivaret=$_POST["txtivaret".$li_i];
			$ls_ivaret=str_replace(".","",$ls_ivaret);
			$ls_ivaret=str_replace(",",".",$ls_ivaret);
			$ls_numsop=$_POST["txtnumsop".$li_i];
			$ls_numdoc=$_POST["txtnumdoc".$li_i];
			$li_porret=$_POST["txtporret".$li_i];
						
			$ls_sql="INSERT INTO scb_dt_cmp_ret (codemp,codret,numcom,numope,fecfac,numfac,numcon,numnd,numnc,tiptrans,".
					"							 totcmp_sin_iva,totcmp_con_iva,basimp,porimp,totimp,iva_ret,desope,numsop,codban,".
					"							 ctaban,numdoc,codope)".
					"     VALUES  ('".$this->ls_codemp."','".$as_codret."','".$as_numcom."','".$ls_numope."','".$ls_fecfac."',".
					"			   '".$ls_numfac."','".$ls_numcon."','".$ls_numnd."','".$ls_numnc."','".$ls_tiptrans."',".
					"			   '".$ls_tot_cmp_sin_iva."','".$ls_tot_cmp_con_iva."','".$ls_basimp."','".$ls_porimp."',".
					"			   '".$ls_totimp."','".$ls_ivaret."','','".$ls_numsop."','','','".$ls_numdoc."','')";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Modificar Comprobante MÉTODO->uf_insert_dt_cmpret ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
			else
			{
				/////////////////////////////////           SEGURIDAD             /////////////////////////////////	
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Detalle ".$ls_numope." del comprobate ".$as_numcom.
								 " Asociado a la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////           SEGURIDAD             /////////////////////////////////	
				$lb_valido=$this->uf_actualizar_estcmp($ls_numfac,$as_codigo,$as_codret,$as_probene);
			}
		}
		return $lb_valido;
	}// end function uf_insert_recepciones

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_ded($as_numcom,$as_codret)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_ded
		//		   Access: private
		//	    Arguments: as_numcom           // Número del Comprobante
		//				   as_codret            // Codigo de la retencion
		//	      Returns: lb_valor retorna el valor del campo encontrado
		//	  Description: Funcion que elimina los detalles de un comprobante
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 21/01/2016 								Fecha Última Modificación : 21/01/2016 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valor="";
		$ls_sql="SELECT codded FROM scb_dt_cmp_ret ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND numcom='".$as_numcom."'".
				"   AND codret='".$as_codret."'";
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valor=false;
			$this->io_mensajes->message("CLASE->Modificar Comprobate MÉTODO->uf_delete_dt_cmpret ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if ($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valor=$la_row["codded"];
			}
		}
		return $lb_valor;
	}// end function uf_select_cod_deduccion


	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_dt_cmpret($as_numcom,$as_codret, $aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_dt_cmpret
		//		   Access: private
		//	    Arguments: as_numcom           // Número del Comprobante
		//				   as_codret            // Codigo de la retencion
		//				   aa_seguridad         // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error 
		//	  Description: Funcion que elimina los detalles de un comprobante
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 21/09/2007 								Fecha Última Modificación : 21/09/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE FROM scb_dt_cmp_ret ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND numcom='".$as_numcom."'".
				"   AND codret='".$as_codret."'";
		//print $ls_sql;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Modificar Comprobate MÉTODO->uf_delete_dt_cmpret ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó los Detalle del comprobate ".$as_numcom." Asociado a la empresa ".$this->ls_codemp;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}
		return $lb_valido;
	}// end function uf_insert_recepciones

	function uf_update_cmpret($as_numcom, $as_codret, $ai_totrowrecepciones,$as_probene,$as_codigo,$aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_cmpret
		//		   Access: private
		//	    Arguments: as_numcom            // Número del Comprobante
		//				   as_codret            // Codigo de la retencion
		//				   aa_seguridad         // arreglo de las variables de seguridad
		//                  ai_totrowrecepciones // Total de Filas Detalles del Comprobante 
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error 
		//	  Description: Funcion que actualiza los detalles de un comprobante
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 21/09/2007 								Fecha Última Modificación : 21/09/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$lb_valido=$this->uf_delete_dt_cmpret($as_numcom, $as_codret, $aa_seguridad);
		if($lb_valido)
		{
			$lb_valido=$this->uf_insert_dt_cmpret($as_numcom, $as_codret, $ai_totrowrecepciones,$as_probene,$as_codigo, $aa_seguridad);
		}
		return $lb_valido;
	}
 
 //------------------------------------------------------------------------------------------------------------ 
 function uf_buscar_ultimo($as_numcom,$as_codret)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_buscar_ultimo
		//		   Access: public
		//		 Argument: as_numcom // Número de comprobante
		//				   as_codret // Codigo de la retencion
		//	      Returns: lb_valido True si se ejecuto ó False si hubo error 
		//	  Description: Función que busca el ultimo comprobante
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 29/04/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_periodo=substr($as_numcom,0,6);
		$codigo =substr($as_numcom,7,8);
		settype($codigo,'int');                            
		$codigo =$codigo + 1;                             
		settype($codigo,'string');                         
		$ls_nrocomp=$this->io_funciones->uf_cerosizquierda($codigo,8);
		$ls_numcom=$ls_periodo.$ls_nrocomp;
		
		$ls_sql="SELECT numcom".
				"  FROM scb_cmp_ret".	
				"  WHERE codemp='".$this->ls_codemp."' ".
				"  AND numcom= '".$ls_numcom."' and codret='".$as_codret."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false){
			$this->io_mensajes->message("CLASE->Modificar Comprobate MÉTODO->uf_buscar_ultimo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		else{
		    
			if ($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=false;
			}
		}   
		
		return $lb_valido;
		
	}// end function uf_buscar_ultimo

//----------------------------------------------------------------------------------------------------------------

   function uf_delete_cmpret($as_numcom,$as_codret,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_cmpret
		//		   Access: private
		//	    Arguments: as_numcom            // Número del Comprobante
		//				   as_codret            // Codigo de la retencion
		//				   aa_seguridad         // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto ó False si hubo error 
		//	  Description: Funcion que elimina fisicamente la cabezera del comprobante
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 21/09/2007 								Fecha Última Modificación : 21/09/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_flag=$this->uf_delete_dt_cmpret($as_numcom, $as_codret, $aa_seguridad);
		if($lb_flag)
		{	
			$ls_sql="DELETE FROM scb_cmp_ret ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND numcom='".$as_numcom."'".
					"   AND codret='".$as_codret."'";
			//print $ls_sql;
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_delete_cmpret ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
			else
			{
				//print "elimino comprobante";
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el comprobate ".$as_numcom." Asociado a la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			}
		}
		return $lb_valido;
	}// end function uf_delete_cmpret
  
//////// FUNCION QUE ME LLEVA EL CODIGO DE LA RETENCION////////////
function uf_obtengo_retencion ($as_sql,$quetraigo)
{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	    uf_obtengo_retencion
	// Access:			public
	//	Returns:		Boolean, Retorna true si el documento esta contabilizado, de lo contrario retorna false
	//  Fecha:          17/04/2015
	//	Autor:          Ing. Miguel Palencia		
	//////////////////////////////////////////////////////////////////////////////
	$li_estado=false;
	//$ls_sql=$as_sql;
	//print $as_sql;
	$rs_data=$this->io_sql->select($as_sql);
	if($rs_data===false)
	{
		print "Error en uf_obtengo_retencion".$this->io_function->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
		if($la_row=$this->io_sql->fetch_row($rs_data))
		{
			if($quetraigo==='C')
			{
				$li_estado=$la_row["codcmp"];
			}
			else
			{
				if($quetraigo==='T')
				{
				$li_estado=$la_row["tipo"];
				}
				else
				{
				$li_estado=$la_row["numcmp"];
				}
			}
		}		
	}	
	return $li_estado;
}
////////////////////////// FIN DE LA BUSQUEDA DEL NRO DE CODIGO DE LA RETENCION QUE VIENE///////////////////////


  function uf_liberar_rd($as_codded,$as_probene,$as_codprobene,$ai_totrowrecepciones,$as_numcomp)
    {
	    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_liberar_rd
		//		   Access: private
		//	    Arguments: as_codded             // Codigo de la deduccion
        //				   as_probene            // Campo que indica si se va a procesar un Proveedor o un Beneficiario
        //				   as_codprobene         // Codigo de Proveedor o Beneficiario
		//                 ai_totrowrecepciones  // Total de Filas Detalles del Comprobante 
		//	      Returns: lb_valido True si se ejecuto ó False si hubo error 
		//	  Description: Funcion que cambia el estatus estcmp de la tabla cxp_rd_deducciones de 1 a 0  
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 21/09/2007 								Fecha Última Modificación : 21/09/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		//print "deduccion".$as_codded;
		$ls_codemp=$this->ls_codemp;
		$ejecute="SELECT codcmp,numcmp,tipo FROM cxp_contador WHERE codemp='$ls_codemp' AND codcmp='$as_codded' ";
		$ls_tipoded=$this->uf_obtengo_retencion($ejecute,'T');
		//print "aqui:".$ejecute;
		//->uf_obtengo_retencion($ejecute,"T")
		//print "tipo ded:".$ls_tipoded;
		//print "codded:".$as_codded;
		if($ls_tipoded=="I")
		{
		 // $ls_filtro=" codded IN (SELECT codded FROM tepuy_deducciones WHERE iva='1')";
		  $restaurar=" SET estcmp=0 ";
		  $tipo_ret=" AND (estcmp='1')";
		 }
		 elseif($ls_tipoded=="M")
		 {
		  //$ls_filtro=" codded IN (SELECT codded FROM tepuy_deducciones WHERE estretmun='1')";
		  $restaurar=" SET estcmpim=0 ";
		  $tipo_ret=" AND (estcmpim='1')";
		 }
		 elseif($ls_tipoded=="S")
		 {
		  //$ls_filtro=" codded IN (SELECT codded FROM tepuy_deducciones WHERE islr='1')";
		  $restaurar=" SET estcmpislr=0 ";
		  $tipo_ret=" AND (estcmpislr='1')";
		 }
		 elseif($ls_tipoded=="O")
		 {
		  //	$ls_filtro=" codded IN (SELECT codded FROM tepuy_deducciones WHERE retaposol='1')";
		  	$restaurar=" SET estcmpaporte=0 ";
			$tipo_ret=" AND (estcmpaporte='1')";
		 }
		 elseif($ls_tipoded=="T")
		 {
		  //	$ls_filtro=" codded IN (SELECT codded FROM tepuy_deducciones WHERE timbrefiscal='1')";
		  	$restaurar=" SET estcmptimbrefiscal=0 ";
			$tipo_ret=" AND (estcmptimbrefiscal='1')";
		 }
		// comparo el codigo de la deduccion con el tipo de deducciones //
	   	$ls_filtro="CONCAT(cxp_rd.numrecdoc,cxp_rd_deducciones.porded)";
	   if (strtoupper($_SESSION["ls_gestor"])==strtoupper("mysqlt"))
		{
		   	if($as_tiporet=='I')
			{	   	   
			  $ls_filtro="CONCAT(cxp_rd.numrecdoc,cxp_rd_cargos.porcar)";
			}
			else
			{
			  //$ls_id="CONCAT(RD.numrecdoc,RDD.porded)";
			$ls_filtro="CONCAT(cxp_rd.numrecdoc,cxp_rd_deducciones.porded)";
			}
		}
		elseif(strtoupper($_SESSION["ls_gestor"])==strtoupper("postgres"))
		{
		   	if($as_tiporet=='I')
			{	   	   
				$ls_id="RD.numrecdoc || RDC.porcar";
			}
			else
			{
				$ls_id="RD.numrecdoc || RDD.porded";
			}
		}

		 if($as_probene=="P")
		 {
		  $ls_filtro2="(cod_pro='".$as_codprobene."'"." OR ced_bene='".$as_codprobene."')";
		 }
		 else{
		  $ls_filtro2="(ced_bene='".$as_codprobene."'"."OR cod_pro='".$as_codprobene."')";
		 }
		///$ls_filtro=" a.codded=(select b.codded from scb_dt_cmp_ret b) ";
		
///////////// busca la cantidad de documentos asociados al comprobante y los libera /////////

		for($li_i=1;($li_i<$ai_totrowrecepciones)&&($lb_valido);$li_i++)
		{
			$estaded=$this->uf_select_ded($as_numcomp,$as_codded);
			$ls_filtro=" codded='".$estaded."' ";
			$ls_numdoc=$_POST["txtnumdoc".$li_i];
			$ls_sql="UPDATE cxp_rd_deducciones ".$restaurar.
                    "WHERE (codemp='".$this->ls_codemp."'".
					") AND (".$ls_filtro.") ".
                    "AND (numrecdoc ='".$ls_numdoc."') ".
                    "AND (".$ls_filtro2.")".$tipo_ret;
			//print $ls_sql;
			$li_row=$this->io_sql->execute($ls_sql);			
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Modificar Comprobante MÉTODO->uf_liberar_rd ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
		}
		$this->io_sql->commit;
		return $lb_valido;
	}// end function uf_liberar_rd
	
  function uf_liberar_recepciones($as_codded,$as_numcom,$as_probene,$as_codprobene)
  {
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_liberar_recepciones
		//		   Access: private
		//	    Arguments: as_codded             // Codigo de la deduccion
		//				   ls_numcom            // numero de comprobante de retencion
		//	      Returns: lb_valido True si se ejecuto ó False si hubo error 
		//	  Description: Funcion que cambia el estatus estcmp de la tabla cxp_rd_deducciones de 1 a 0  
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 21/09/2007 								Fecha Última Modificación : 21/09/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if($as_codded=="0000000001"){
		  $ls_filtro="codded IN (SELECT codded FROM tepuy_deducciones WHERE iva='1')";
		$restaurar=" SET estcmp='0' ";
		 }
		 elseif($as_codded=="0000000003")
		 {
		  $ls_filtro="codded IN (SELECT codded FROM tepuy_deducciones WHERE islr='1')";
		   $restaurar=" SET estcmpislr='0' ";
		 }
		 elseif($as_codded=="0000000002")
		 {
		  $ls_filtro="codded IN (SELECT codded FROM tepuy_deducciones WHERE estretmun='1')";
		  $restaurar=" SET estcmpim='0' ";
		 }
		 elseif($as_codded=="0000000004")
		 {
		  	$ls_filtro="codded IN (SELECT codded FROM tepuy_deducciones WHERE retaposol='1')";
		  	$restaurar=" SET estcmpaporte='0' ";
		 }
/*		if($as_codded=="0000000001")
		{
			$ls_filtro="codded IN (SELECT codded FROM tepuy_deducciones WHERE iva='1')";
		}
		else
		{
			$ls_filtro="codded IN (SELECT codded FROM tepuy_deducciones WHERE estretmun='1')";
		}
*/		if($as_probene=="P")
		{
			$ls_filtro2="cod_pro='".$as_codprobene."'";
		}
		else
		{
			$ls_filtro2="ced_bene='".$as_codprobene."'";
		}
		$rs_data=$this->uf_load_dt_cmpret($as_numcom,$as_codded);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Modificar Comprobante MÉTODO->uf_liberar_recepciones ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_numrecdoc=$row["numfac"];
				$ls_sql="UPDATE cxp_rd_deducciones ".$restaurar.
						//"   SET estcmp='0' ".
						" WHERE codemp='".$this->ls_codemp."'".
						"   AND(".$ls_filtro.") ".
						"   AND (numrecdoc ='".$ls_numrecdoc."') ".
						"   AND (".$ls_filtro2.")";
				$li_row=$this->io_sql->execute($ls_sql);
				//print_r($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Modificar Comprobante MÉTODO->uf_liberar_rd1 ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				}
			}
		}
		return $lb_valido;
	}// end function uf_liberar_recepciones

	function uf_anular_cmpret($as_numcom,$aa_seguridad)
    {
	    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_anular_cmpret
		//		   Access: private
		//	    Arguments: as_numcom            // Número del Comprobante
        //				   aa_seguridad         // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto ó False si hubo error 
		//	  Description: Funcion que coloca en estado anulado al comprobante
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 21/09/2007 								Fecha Última Modificación : 21/09/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
		$ls_sql="UPDATE scb_cmp_ret ".
                "SET estcmpret='0' ".
                "WHERE (numcom ='".$as_numcom."') ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Modificar Comprobante MÉTODO->uf_insert_dt_cmpret ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
			else{
				
			  /////////////////////////////////         SEGURIDAD               /////////////////////////////		
			  $ls_evento="UPDATE";
			  $ls_descripcion ="Anulo el comprobate ".$as_numcom." Asociado a la empresa ".$this->ls_codemp;
			  $lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
		      /////////////////////////////////         SEGURIDAD               /////////////////////////////	
		    }
			
		return $lb_valido;
	}// end function uf_anular_cmpret

	function uf_actualizar_estcmp($as_numrecdoc,$as_codprobene,$as_codded,$as_tipo)
	{
	    //////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_actualizar_estcmp
		//		   Access: public
		//		 Argument: $as_numrecdoc // Número de Recepcion de Documento
		//                 $as_codprobene // Codigo del proveedor o beneficiario 
		//                 $as_codret // Codigo de Retencion 
		//                 $as_tipo // Indica si el codprobene es un proveedor o un beneficiario 
		//	  Description: Función que actualiza el campo estcmp al valor 1 en la tabla cxp_rd_deducciones lo
		//                 que indica que ese item ya fue procesado en un comprobante
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 13/09/2007								Fecha Última Modificación : 13/09/2007
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if($as_codded=="0000000001"){
		  $ls_filtro="codded IN (SELECT codded FROM tepuy_deducciones WHERE iva='1')";
		 }
		 elseif($as_codded=="0000000003")
		 {
		  $ls_filtro="codded IN (SELECT codded FROM tepuy_deducciones WHERE estretmun='1')";
		 }
		 elseif($as_codded=="0000000004")
		 {
		  	$ls_filtro="codded IN (SELECT codded FROM tepuy_deducciones WHERE retaposol='1')";
		 }
/*		if($as_codded=="0000000001")
		{
			$ls_cadena=" AND codded IN (SELECT codded FROM tepuy_deducciones WHERE iva='1')";
		}
		else
		{
			$ls_cadena="AND codded IN (SELECT codded FROM tepuy_deducciones WHERE estretmun='1')";
		}
*/		if($as_tipo=="P"){
		   $ls_filtro="cod_pro='".$as_codprobene."'";
		 }
		 elseif($as_tipo="B"){
		   $ls_filtro="ced_bene='".$as_codprobene."'";
		 }
		$ls_sql="UPDATE cxp_rd_deducciones".
				"   SET estcmp='1'".
		        " WHERE codemp='".$this->ls_codemp."'".
				"   AND numrecdoc='".$as_numrecdoc."'". 
				"   AND ".$ls_filtro."".
				$ls_cadena;
		$li_result=$this->io_sql->execute($ls_sql);
		if($li_result===false)
		{	
				$this->io_mensajes->message("CLASE->Modificar Comprobante MÉTODO->uf_actualizar_estcmp ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		return $lb_valido;
    }	  
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_facturasreten($as_numfactura,$as_codret,$ad_fecemides,$ad_fecemihas,$as_tipooperacion)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_facturasreten Facturas que tienen aplicada ese tipo de retencion
		//		   Access: public
		//		 Argument: as_factura  // Numero de Factura
		//                 ad_fecemides     // Fecha (Emision) de inicio de la Busqueda
		//                 ad_fecemihas     // Fecha (Emision) de fin de la Busqueda
		//                 as_codret     // Cod. Tipo de Retencion
		//	  Description: Función que busca las solicitudes de ordenes de pago a aprobar o reversar aprobacion
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 02/09/2017
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
		// BUSCA EL TIPO DE RETENCION SELECCIONADA ANTES DE HACER LA FILTRACION ///
		$ls_sql="SELECT  codcmp, nom, tipo ". 
		        "  FROM   cxp_contador  WHERE codemp='".$this->ls_codemp."' AND codcmp='".$as_codret."'";
		$rs_result=$this->io_sql->select($ls_sql);
		//print $ls_sql;
		if($rs_result===false)
		 {
			$this->io_msg->message("CLASE->Buscar Retenciones MÉTODO->uf_cmb_retencion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			return false;			
		 }
		 else
		 {
			if ($row=$this->io_sql->fetch_row($rs_result))
			{
				$ld_tipo=$row["tipo"];
			}	
		}
		//$ld_tipo="M";
		//print "Tipo: ".$ld_tipo;die();
		switch ($ld_tipo)
		{
			case "I":
			$ls_cadena_tipo=" AND sfa_factura.numfactura=sfa_dt_retenciones.numfactura AND sfa_dt_retenciones.estcmp=0";
			$ls_cadena_tipo1=" AND sfa_dt_retenciones.codded=tepuy_deducciones.codded AND tepuy_deducciones.iva=1 ";
				break;
			case "M":
			$ls_cadena_tipo=" AND sfa_factura.numfactura=sfa_dt_retenciones.numfactura AND sfa_dt_retenciones.estcmpim=0";
			$ls_cadena_tipo1=" AND sfa_dt_retenciones.codded=tepuy_deducciones.codded AND tepuy_deducciones.estretmun=1 ";
				break;
			case "S":
			$ls_cadena_tipo=" AND sfa_factura.numfactura=sfa_dt_retenciones.numfactura AND sfa_dt_retenciones.estcmpislr=0";
			$ls_cadena_tipo1=" AND sfa_dt_retenciones.codded=tepuy_deducciones.codded AND tepuy_deducciones.islr=1 ";
				break;
			case "T":
			$ls_cadena_tipo=" AND sfa_factura.numfactura=sfa_dt_retenciones.numfactura AND sfa_dt_retenciones.estcmptimbrefiscal=0";
			$ls_cadena_tipo1=" AND sfa_dt_retenciones.codded=tepuy_deducciones.codded AND tepuy_deducciones.otras=1 ";
				break;
			case "O":
			$ls_cadena_tipo=" AND sfa_factura.numfactura=sfa_dt_retenciones.numfactura AND sfa_dt_retenciones.estcmpaporte=0";
			$ls_cadena_tipo1=" AND sfa_dt_retenciones.codded=tepuy_deducciones.codded AND tepuy_deducciones.retaposol=1 ";
				break;
		}
		//$res = str_pad($as_codret, 5, '0', STR_PAD_LEFT);
		//$ls_cadena_tipo=$ls_cadena_tipo." AND cxp_rd_deducciones.codded='".$res."' ".$ls_cadena_tipo1;
		$ls_cadena_tipo=$ls_cadena_tipo.$ls_cadena_tipo1;
		///////////////////////////////////////////////////////////////////////////
		$ls_cadena1="rifcli";
		$ls_cadena2="dircli";
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQL":
				$ls_cadena="CONCAT(nomcli,' ',apecli)";
				break;
			case "POSTGRE":
				$ls_cadena="nomcli||' '||apecli";
				break;
		}
		$ls_sql="SELECT sfa_factura.numfactura,sfa_factura.estfac,sfa_factura.montot,".
				" sfa_factura.fecfactura,sfa_factura.cedcli,SUM(sfa_dt_retenciones.monobjret) as monobjret,".
				" SUM(sfa_dt_retenciones.monret) as monret, sfa_dt_retenciones.porded, tepuy_deducciones.codded,".
				"       (SELECT ".$ls_cadena." FROM sfa_cliente WHERE sfa_factura.codemp=sfa_cliente.codemp AND".
				" 	sfa_factura.cedcli=sfa_cliente.cedcli) AS nombre,".
				"       (SELECT ".$ls_cadena1." FROM sfa_cliente WHERE sfa_factura.codemp=sfa_cliente.codemp AND".
				" 	sfa_factura.cedcli=sfa_cliente.cedcli) AS rifcli,".
				"       (SELECT ".$ls_cadena2." FROM sfa_cliente WHERE sfa_factura.codemp=sfa_cliente.codemp AND".
				" 	sfa_factura.cedcli=sfa_cliente.cedcli) AS dircli".
				"  FROM sfa_factura, sfa_dt_retenciones, tepuy_deducciones ".
				" WHERE sfa_factura.codemp = '".$this->ls_codemp."'".
				"   AND sfa_factura.numfactura LIKE '".$as_numfactura."' ".
				"   AND sfa_factura.fecfactura >= '".$ad_fecemides."' ".
				"   AND sfa_factura.fecfactura <= '".$ad_fecemihas."' ";
		//print "codret: ".$as_codret;
		$ls_sql= $ls_sql.$ls_cadena_tipo;
		//print $ls_sql;die();
		$ls_sql= $ls_sql." GROUP BY sfa_factura.numfactura "; //"ORDER BY cxp_solicitudes.numsol ";
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Facturas MÉTODO->uf_load_facturasreten ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		return $rs_data;
	}// end function uf_load_solicitudesreten

	
}
?>
