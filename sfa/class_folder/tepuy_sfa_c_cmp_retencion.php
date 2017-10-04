<?php
class tepuy_sfa_c_cmp_retencion
{
    var $io_function;
    var $la_empresa;
	var $ls_codusu;
    var $io_sql;
    var $io_msg;
    var $io_fec;
	var $io_seguridad;
	var $io_dataprov;
	var $io_datadocu;
	
	
	
	function tepuy_sfa_c_cmp_retencion($as_path)
	{
		require_once($as_path."shared/class_folder/tepuy_include.php");
		require_once($as_path."shared/class_folder/class_sql.php");
		require_once($as_path."shared/class_folder/class_funciones.php");
		require_once($as_path."shared/class_folder/class_mensajes.php");
        	require_once($as_path."shared/class_folder/tepuy_c_seguridad.php");
		require_once($as_path."shared/class_folder/class_fecha.php");
		require_once($as_path."shared/class_folder/class_datastore.php");
		require_once($as_path."shared/class_folder/tepuy_c_generar_consecutivo.php");
		$this->io_keygen= new tepuy_c_generar_consecutivo();
         
		$this->io_include=new tepuy_include();
		$io_connect=$this->io_include->uf_conectar();
		$this->io_dataprov= new class_datastore();
		$this->io_datadocu= new class_datastore();
		$this->io_datavali= new class_datastore();
	        $this->io_seguridad= new tepuy_c_seguridad();
		$this->io_sql= new class_sql($io_connect);	
		$this->io_function= new class_funciones();
		$this->io_msg= new class_mensajes();
		$this->io_fec= new class_fecha();
		$this->la_empresa= $_SESSION["la_empresa"];
		$this->ls_codusu= $_SESSION["la_logusr"];
	       // $this->ls_basdatcmp=$_SESSION["la_empresa"]["basdatcmp"];
		//print "ls_basdatcmp:".$this->ls_basdatcmp;
		/*if($this->ls_basdatcmp!="")
		{
			$this->io_include->uf_obtener_parametros_conexion($as_path,$this->ls_basdatcmp,&$as_hostname,&$as_login,&$as_password,&$as_gestor);
			if($as_hostname!="")
			{
				$this->io_keygen->io_conexion=$this->io_include->uf_conectar_otra_bd($as_hostname, $as_login, $as_password,$this->ls_basdatcmp,$as_gestor);
				$this->io_keygen->io_sql=new class_sql($this->io_keygen->io_conexion);
		
				$io_connectaux=$this->io_include->uf_conectar_otra_bd($as_hostname, $as_login, $as_password,$this->ls_basdatcmp,$as_gestor);
				$this->io_sqlaux=new class_sql($io_connectaux);
			}
			else
			{
				$this->io_msg->message("Esta mal configurada la BD integradora");
				print "<script language=JavaScript>";
				print "location.href='tepuywindow_blank.php'";
				print "</script>";		
			}
		}*/
//		require_once("../shared/class_folder/tepuy_c_reconvertir_monedabsf.php");
//		$this->io_rcbsf= new tepuy_c_reconvertir_monedabsf();
//		$this->li_candeccon=$_SESSION["la_empresa"]["candeccon"];
//		$this->li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
//		$this->li_redconmon=$_SESSION["la_empresa"]["redconmon"];
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}
	
/******************************************************************************************************************/
/***********************************    FUNCIONES DE BUSQUEDA      ************************************************/	
/******************************************************************************************************************/		
	function uf_get_provbene($as_mes,$as_agno,$as_probendesde,$as_probenhasta,$as_tipo,$as_tiporet,&$aa_sujret)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function: uf_get_provbene
		//	 Access: public
		//	 Argument: $as_mes // Mes  | $as_agno // Año
		//             $as_probendesde // Codigo de Proveedor o Benficiario | $as_probenhasta // Codigo de Proveedor o Benficiario
		//             $as_tipo // Indica si se trabaja con proveedores o beneficiarios 
		//             $as_tiporet // Indica el tipo de retencion
		//             $aa_sujret // arreglo con el resultado de la busqueda 
		//  Description: Función que genera una lista con proveedores o beneficiarios con posibilidad de tener movimientos
		//               que ameriten la generacion de un comprobante de retencion  
		//	Creado Por: Ing. Miguel Palencia
		//  Fecha Creación: 13/09/2007								Fecha Última Modificación : 14/09/2007
		//////////////////////////////////////////////////////////////////////////////
		$ld_fecdesde= $this->io_function->uf_convertirdatetobd("01/".$as_mes."/".$as_agno);
		$ld_hasta   = $this->io_fec->uf_last_day($as_mes,$as_agno);
		$ld_fechasta= $this->io_function->uf_convertirdatetobd($ld_hasta);
		$lb_valido  = true;

	
		if($as_tiporet=="I") //RETENCION DE IVA
		 {
		     $ls_sqlaux = "";
			 if (!empty($as_probendesde) && !empty($as_probenhasta))
			    {
				  $ls_sqlaux = "AND (RD.cod_pro BETWEEN '".$as_probendesde."' AND '".$as_probenhasta."')";
				}
		   if($as_tipo=='P')
		    {
  		     $ls_sql = "SELECT RD.cod_pro,MAX(RDD.codded) AS codded,MAX(RDD.numrecdoc) AS numrecdoc,MAX(PRO.nompro) AS nompro,
			                   MAX(PRO.dirpro) AS dirpro,MAX(PRO.rifpro) AS rifpro
			              FROM tepuy_deducciones SD,cxp_rd_deducciones RDD,cxp_rd RD,rpc_proveedor PRO,
						       cxp_dt_solicitudes DS,cxp_solicitudes SO
					     WHERE SD.codemp='".$this->la_empresa["codemp"]."'
						   AND SD.iva=1 $ls_sqlaux
						   AND (SO.fecaprosol BETWEEN '".$ld_fecdesde."' AND '".$ld_fechasta."') 
						   AND RD.estprodoc='C'
						   AND SD.codemp=RDD.codemp
						   AND SD.codemp=RD.codemp
					       AND SD.codemp=PRO.codemp
						   AND SD.codemp=DS.codemp
						   AND SD.codemp=SO.codemp					       
						   AND SD.codded=RDD.codded
						   AND RDD.numrecdoc=RD.numrecdoc						   
						   AND RD.cod_pro=PRO.cod_pro
						   AND RDD.cod_pro=RD.cod_pro
						   AND RDD.codtipdoc=RD.codtipdoc						   
						   AND RD.numrecdoc=DS.numrecdoc
						   AND RD.cod_pro=DS.cod_pro
						   AND DS.numsol=SO.numsol
					     GROUP BY RD.cod_pro";
		    	 
		    }
		    else
		    {
			  $ls_sqlaux = "";
			  if (!empty($as_probendesde) && !empty($as_probenhasta))
			     {
				   $ls_sqlaux = "AND (RD.ced_bene BETWEEN '".$as_probendesde."' AND '".$as_probenhasta."')";
				 }
			 if (strtoupper($_SESSION["ls_gestor"])==strtoupper("mysqlt")){
		       $ls_parametro="CONCAT(BEN.nombene,BEN.apebene) AS nompro";
		     }
		     elseif(strtoupper($_SESSION["ls_gestor"])==strtoupper("postgres")){
		       $ls_parametro="(MAX(BEN.nombene)||MAX(BEN.apebene)) AS nompro";
		     }
			$ls_parametro="CONCAT(BEN.nombene,' ',BEN.apebene) AS nompro";
			 $ls_sql =  "SELECT RD.ced_bene as cod_pro,MAX(RDD.codded) AS codded,MAX(RDD.numrecdoc) AS numrecdoc,$ls_parametro,
			                    MAX(BEN.dirbene) as dirpro,MAX(BEN.rifben) as rifpro
			               FROM tepuy_deducciones SD,cxp_rd_deducciones RDD,cxp_rd RD,rpc_beneficiario BEN,cxp_dt_solicitudes DS,
						        cxp_solicitudes SO
		  	              WHERE SD.codemp='".$this->la_empresa["codemp"]."'
						    AND SD.iva=1
							AND RD.estprodoc='C' $ls_sqlaux							 
							AND (SO.fecaprosol BETWEEN '".$ld_fecdesde."' AND '".$ld_fechasta."')
							AND SD.codemp=RDD.codemp
							AND SD.codemp=RD.codemp
							AND SD.codemp=BEN.codemp
							AND SD.codemp=DS.codemp
							AND SD.codemp=SO.codemp					        
							AND SD.codded=RDD.codded
							AND RDD.numrecdoc=RD.numrecdoc							
							AND RD.ced_bene=BEN.ced_bene
							AND RDD.ced_bene=RD.ced_bene 
							AND RDD.codtipdoc=RD.codtipdoc							
							AND RD.numrecdoc=DS.numrecdoc
							AND RD.ced_bene=DS.ced_bene
							AND DS.numsol=SO.numsol
					      GROUP BY RD.ced_bene";
		    }
	     }

		 elseif($as_tiporet=="S") // retencion de ISLR
		 {
		 if($as_tipo=='P')
		   {
			$ls_sql =  " SELECT RD.cod_pro,MAX(RDD.codded) AS codded,MAX(RDD.numrecdoc) AS numrecdoc,".
			           " MAX(PRO.nompro) AS nompro,MAX(PRO.dirpro) AS dirpro,MAX(PRO.rifpro) AS rifpro".
			           " FROM tepuy_deducciones SD,cxp_rd_deducciones RDD,cxp_rd RD,rpc_proveedor PRO
					     ,cxp_dt_solicitudes DS,cxp_solicitudes SO".
					   " WHERE (SD.codemp='".$this->la_empresa["codemp"]."') AND (SD.codemp=RDD.codemp) AND (SD.codemp=RD.codemp)". 
					   " AND (SD.codemp=PRO.codemp) AND (SD.codemp=DS.codemp) AND (SD.codemp=SO.codemp)".
					   " AND (SD.islr=1) AND (SD.codded=RDD.codded) AND (RDD.numrecdoc=RD.numrecdoc) AND".
					   " (RD.cod_pro BETWEEN '".$as_probendesde."' AND '".$as_probenhasta."') AND".
					   " (RD.cod_pro=PRO.cod_pro) AND (RDD.cod_pro=RD.cod_pro) AND (RDD.codtipdoc=RD.codtipdoc)".
					   " AND (SO.fecaprosol BETWEEN '".$ld_fecdesde."' AND '".$ld_fechasta."') AND (RD.estprodoc='C') AND".
					   " (RD.numrecdoc=DS.numrecdoc) AND (RD.cod_pro=DS.cod_pro) AND (DS.numsol=SO.numsol)".
					   " GROUP BY RD.cod_pro";
		    	   
		       }
		       else
		       {
			     if (strtoupper($_SESSION["ls_gestor"])==strtoupper("mysqlt")){
		            $ls_parametro="CONCAT(BEN.nombene,BEN.apebene) AS nompro";
		          }
		          elseif(strtoupper($_SESSION["ls_gestor"])==strtoupper("postgres")){
		            $ls_parametro="(MAX(BEN.nombene) ||MAX(BEN.apebene)) AS nompro";
		          }
					$ls_parametro="CONCAT(BEN.nombene,' ',BEN.apebene) AS nompro";
			    
				  $ls_sql = " SELECT RD.ced_bene as cod_pro,MAX(RDD.codded) AS codded,MAX(RDD.numrecdoc)".
				            "  AS numrecdoc,".$ls_parametro.",MAX(BEN.dirbene) as dirpro,MAX(BEN.rifben) as rifpro".
			                " FROM tepuy_deducciones SD,cxp_rd_deducciones RDD,cxp_rd RD,rpc_beneficiario BEN".
					        " ,cxp_dt_solicitudes DS,cxp_solicitudes SO".
		  	                " WHERE (SD.codemp='".$this->la_empresa["codemp"]."') AND (SD.codemp=RDD.codemp) AND". 
					        " (SD.codemp=RD.codemp) AND (SD.codemp=BEN.codemp) AND (SD.codemp=DS.codemp) AND (SD.codemp=SO.codemp)".
					        " AND (SD.estretmun=1) AND (SD.codded=RDD.codded) AND (RDD.numrecdoc=RD.numrecdoc) AND".
					        " (RD.ced_bene BETWEEN '".$as_probendesde."' AND '".$as_probenhasta."') AND".
					        " (RD.ced_bene=BEN.ced_bene) AND (RDD.ced_bene=RD.ced_bene) AND (RDD.codtipdoc=RD.codtipdoc)".
					        " AND (SO.fecaprosol BETWEEN '".$ld_fecdesde."' AND '".$ld_fechasta."') AND (RD.estprodoc='C') AND".
					        " (RD.numrecdoc=DS.numrecdoc) AND (RD.ced_bene=DS.ced_bene) AND (DS.numsol=SO.numsol)".
					        " GROUP BY RD.ced_bene";
		       }
		
		 }



		 elseif($as_tiporet=="M") //RETENCION DE IMPUESTO MUNICIPAL
		 {
		 if($as_tipo=='P')
		   {
			$ls_sql =  " SELECT RD.cod_pro,MAX(RDD.codded) AS codded,MAX(RDD.numrecdoc) AS numrecdoc,".
			           " MAX(PRO.nompro) AS nompro,MAX(PRO.dirpro) AS dirpro,MAX(PRO.rifpro) AS rifpro".
			           " FROM tepuy_deducciones SD,cxp_rd_deducciones RDD,cxp_rd RD,rpc_proveedor PRO
					     ,cxp_dt_solicitudes DS,cxp_solicitudes SO".
					   " WHERE (SD.codemp='".$this->la_empresa["codemp"]."') AND (SD.codemp=RDD.codemp) AND (SD.codemp=RD.codemp)". 
					   " AND (SD.codemp=PRO.codemp) AND (SD.codemp=DS.codemp) AND (SD.codemp=SO.codemp)".
					   " AND (SD.estretmun=1) AND (SD.codded=RDD.codded) AND (RDD.numrecdoc=RD.numrecdoc) AND".
					   " (RD.cod_pro BETWEEN '".$as_probendesde."' AND '".$as_probenhasta."') AND".
					   " (RD.cod_pro=PRO.cod_pro) AND (RDD.cod_pro=RD.cod_pro) AND (RDD.codtipdoc=RD.codtipdoc)".
					   " AND (SO.fecaprosol BETWEEN '".$ld_fecdesde."' AND '".$ld_fechasta."') AND (RD.estprodoc='C') AND".
					   " (RD.numrecdoc=DS.numrecdoc) AND (RD.cod_pro=DS.cod_pro) AND (DS.numsol=SO.numsol)".
					   " GROUP BY RD.cod_pro";
		    	   
		       }
		       else
		       {
			     if (strtoupper($_SESSION["ls_gestor"])==strtoupper("mysqlt")){
		            $ls_parametro="CONCAT(BEN.nombene,BEN.apebene) AS nompro";
		          }
		          elseif(strtoupper($_SESSION["ls_gestor"])==strtoupper("postgres")){
		            $ls_parametro="(MAX(BEN.nombene) ||MAX(BEN.apebene)) AS nompro";
		          }
			    	$ls_parametro="CONCAT(BEN.nombene,' ',BEN.apebene) AS nompro";
				  $ls_sql = " SELECT RD.ced_bene as cod_pro,MAX(RDD.codded) AS codded,MAX(RDD.numrecdoc)".
				            "  AS numrecdoc,".$ls_parametro.",MAX(BEN.dirbene) as dirpro,MAX(BEN.rifben) as rifpro".
			                " FROM tepuy_deducciones SD,cxp_rd_deducciones RDD,cxp_rd RD,rpc_beneficiario BEN".
					        " ,cxp_dt_solicitudes DS,cxp_solicitudes SO".
		  	                " WHERE (SD.codemp='".$this->la_empresa["codemp"]."') AND (SD.codemp=RDD.codemp) AND". 
					        " (SD.codemp=RD.codemp) AND (SD.codemp=BEN.codemp) AND (SD.codemp=DS.codemp) AND (SD.codemp=SO.codemp)".
					        " AND (SD.estretmun=1) AND (SD.codded=RDD.codded) AND (RDD.numrecdoc=RD.numrecdoc) AND".
					        " (RD.ced_bene BETWEEN '".$as_probendesde."' AND '".$as_probenhasta."') AND".
					        " (RD.ced_bene=BEN.ced_bene) AND (RDD.ced_bene=RD.ced_bene) AND (RDD.codtipdoc=RD.codtipdoc)".
					        " AND (SO.fecaprosol BETWEEN '".$ld_fecdesde."' AND '".$ld_fechasta."') AND (RD.estprodoc='C') AND".
					        " (RD.numrecdoc=DS.numrecdoc) AND (RD.ced_bene=DS.ced_bene) AND (DS.numsol=SO.numsol)".
					        " GROUP BY RD.ced_bene";
		       }
		
		 }
		 elseif($as_tiporet=="T") //RETENCION TIMBRE FISCAL
		 {
		 if($as_tipo=='P')
		   {
			$ls_sql =  " SELECT RD.cod_pro,MAX(RDD.codded) AS codded,MAX(RDD.numrecdoc) AS numrecdoc,".
			           " MAX(PRO.nompro) AS nompro,MAX(PRO.dirpro) AS dirpro,MAX(PRO.rifpro) AS rifpro".
			           " FROM tepuy_deducciones SD,cxp_rd_deducciones RDD,cxp_rd RD,rpc_proveedor PRO
					     ,cxp_dt_solicitudes DS,cxp_solicitudes SO".
					   " WHERE (SD.codemp='".$this->la_empresa["codemp"]."') AND (SD.codemp=RDD.codemp) AND (SD.codemp=RD.codemp)". 
					   " AND (SD.codemp=PRO.codemp) AND (SD.codemp=DS.codemp) AND (SD.codemp=SO.codemp)".
					   " AND (SD.otras=1) AND (SD.codded=RDD.codded) AND (RDD.numrecdoc=RD.numrecdoc) AND".
					   " (RD.cod_pro BETWEEN '".$as_probendesde."' AND '".$as_probenhasta."') AND".
					   " (RD.cod_pro=PRO.cod_pro) AND (RDD.cod_pro=RD.cod_pro) AND (RDD.codtipdoc=RD.codtipdoc)".
					   " AND (SO.fecaprosol BETWEEN '".$ld_fecdesde."' AND '".$ld_fechasta."') AND (RD.estprodoc='C') AND".
					   " (RD.numrecdoc=DS.numrecdoc) AND (RD.cod_pro=DS.cod_pro) AND (DS.numsol=SO.numsol)".
					   " GROUP BY RD.cod_pro";
		    	   
		       }
		       else
		       {
			     if (strtoupper($_SESSION["ls_gestor"])==strtoupper("mysqlt")){
		            $ls_parametro="CONCAT(BEN.nombene,BEN.apebene) AS nompro";
		          }
		          elseif(strtoupper($_SESSION["ls_gestor"])==strtoupper("postgres")){
		            $ls_parametro="(MAX(BEN.nombene) ||MAX(BEN.apebene)) AS nompro";
		          }
			    	$ls_parametro="CONCAT(BEN.nombene,' ',BEN.apebene) AS nompro";
				  $ls_sql = " SELECT RD.ced_bene as cod_pro,MAX(RDD.codded) AS codded,MAX(RDD.numrecdoc)".
				            "  AS numrecdoc,".$ls_parametro.",MAX(BEN.dirbene) as dirpro,MAX(BEN.rifben) as rifpro".
			                " FROM tepuy_deducciones SD,cxp_rd_deducciones RDD,cxp_rd RD,rpc_beneficiario BEN".
					        " ,cxp_dt_solicitudes DS,cxp_solicitudes SO".
		  	                " WHERE (SD.codemp='".$this->la_empresa["codemp"]."') AND (SD.codemp=RDD.codemp) AND". 
					        " (SD.codemp=RD.codemp) AND (SD.codemp=BEN.codemp) AND (SD.codemp=DS.codemp) AND (SD.codemp=SO.codemp)".
					        " AND (SD.otras=1) AND (SD.codded=RDD.codded) AND (RDD.numrecdoc=RD.numrecdoc) AND".
					        " (RD.ced_bene BETWEEN '".$as_probendesde."' AND '".$as_probenhasta."') AND".
					        " (RD.ced_bene=BEN.ced_bene) AND (RDD.ced_bene=RD.ced_bene) AND (RDD.codtipdoc=RD.codtipdoc)".
					        " AND (SO.fecaprosol BETWEEN '".$ld_fecdesde."' AND '".$ld_fechasta."') AND (RD.estprodoc='C') AND".
					        " (RD.numrecdoc=DS.numrecdoc) AND (RD.ced_bene=DS.ced_bene) AND (DS.numsol=SO.numsol)".
					        " GROUP BY RD.ced_bene";
		       }
		
		 }


		 elseif($as_tiporet=="O")
		 {
		  if($as_tipo=='P')
		   {
			$ls_sql =  "SELECT RD.cod_pro,MAX(RDD.codded) AS codded,MAX(RDD.numrecdoc) AS numrecdoc,".
			           "       MAX(PRO.nompro) AS nompro,MAX(PRO.dirpro) AS dirpro,MAX(PRO.rifpro) AS rifpro".
			           "  FROM tepuy_deducciones SD,cxp_rd_deducciones RDD,cxp_rd RD,rpc_proveedor PRO,".
					   "        cxp_dt_solicitudes DS,cxp_solicitudes SO".
					   " WHERE SD.codemp='".$this->la_empresa["codemp"]."'".
					   "   AND SD.codemp=RDD.codemp".
					   "   AND SD.codemp=RD.codemp". 
					   "   AND SD.codemp=PRO.codemp".
					   "   AND SD.codemp=DS.codemp".
					   "   AND SD.codemp=SO.codemp".
					   "   AND SD.retaposol=1".
					   "   AND SD.codded=RDD.codded".
					   "   AND RDD.numrecdoc=RD.numrecdoc".
					   "   AND (RD.cod_pro BETWEEN '".$as_probendesde."' AND '".$as_probenhasta."')".
					   "   AND RD.cod_pro=PRO.cod_pro".
					   "   AND RDD.cod_pro=RD.cod_pro".
					   "   AND RDD.codtipdoc=RD.codtipdoc".
					   "   AND (SO.fecaprosol BETWEEN '".$ld_fecdesde."' AND '".$ld_fechasta."')".
					   "   AND RD.estprodoc='C'".
					   "   AND RD.numrecdoc=DS.numrecdoc".
					   "   AND RD.cod_pro=DS.cod_pro".
					   "   AND DS.numsol=SO.numsol".
					   " GROUP BY RD.cod_pro";
		    	   
		       }
		       else
		       {
			     if (strtoupper($_SESSION["ls_gestor"])==strtoupper("mysqlt")){
		            $ls_parametro="CONCAT(BEN.nombene,BEN.apebene) AS nompro";
		          }
		          elseif(strtoupper($_SESSION["ls_gestor"])==strtoupper("postgre")){
		            $ls_parametro="(MAX(BEN.nombene) ||MAX(BEN.apebene)) AS nompro";
		          }
				
				$ls_parametro="CONCAT(BEN.nombene,' ',BEN.apebene) AS nompro";
			    
				  $ls_sql ="SELECT RD.ced_bene as cod_pro,MAX(RDD.codded) AS codded,MAX(RDD.numrecdoc)".
				           "       AS numrecdoc,".$ls_parametro.",MAX(BEN.dirbene) AS dirpro,MAX(BEN.rifben) AS rifpro".
			               "  FROM tepuy_deducciones SD,cxp_rd_deducciones RDD,cxp_rd RD,rpc_beneficiario BEN,".
					       "       cxp_dt_solicitudes DS,cxp_solicitudes SO".
		  	               " WHERE SD.codemp='".$this->la_empresa["codemp"]."'".
						   "   AND SD.codemp=RDD.codemp ". 
					       "   AND SD.codemp=RD.codemp".
						   "   AND SD.codemp=BEN.codemp".
						   "   AND SD.codemp=DS.codemp".
						   "   AND SD.codemp=SO.codemp".
					       "   AND SD.retaposol=1".
						   "   AND SD.codded=RDD.codded".
						   "   AND RDD.numrecdoc=RD.numrecdoc".
						   "   AND (RD.ced_bene BETWEEN '".$as_probendesde."' AND '".$as_probenhasta."') ".
					       "   AND RD.ced_bene=BEN.ced_bene".
						   "   AND RDD.ced_bene=RD.ced_bene".
						   "   AND RDD.codtipdoc=RD.codtipdoc".
					       "   AND (SO.fecaprosol BETWEEN '".$ld_fecdesde."' AND '".$ld_fechasta."')".
						   "   AND (RD.estprodoc='C') ".
					       "   AND RD.numrecdoc=DS.numrecdoc".
						   "   AND RD.ced_bene=DS.ced_bene".
						   "   AND DS.numsol=SO.numsol".
					       " GROUP BY RD.ced_bene";
		       }
		 
		 }
//print $ls_sql;
        $rs_result=$this->io_sql->select($ls_sql);
		if($rs_result===false)
		{
			$this->io_msg->message("CLASE->Generar Comprobate MÉTODO->uf_get_nrocomprobante ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;	
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_result))
			{
				$aa_sujret=$this->io_sql->obtener_datos($rs_result);
			}
			$this->io_sql->free_result($rs_result);
		}

		return $lb_valido;	
	}//FIN DE LA FUNCION uf_get_provbene 	

	function uf_get_documento($as_mes,$as_agno,$as_cedcli,$as_tiporet,&$ls_RD,$as_numfactura)
	{
	   //////////////////////////////////////////////////////////////////////////////
	   //	Function: uf_get_documento
	   //	 Access: public
	   //	 Argument: $as_mes // Mes  | $as_agno // Año
	   //             $as_codpro // Codigo del proveedor o beneficiaro
	   //             $as_tipo // Indica si se trabaja con proveedores o beneficiarios | $as_tiporet // Indica el tipo de retencion
	   //             $aa_sujret // arreglo con el resultado de la busqueda 
	   //  Description: Función que genera una lista con proveedores o beneficiarios con posibilidad de tener movimientos
	   //               que ameriten la generacion de un comprobante de retencion  
	   //	Creado Por: Ing. Miguel Palencia
	   //  Fecha Creación: 17/09/2017
	   //////////////////////////////////////////////////////////////////////////////
	//$ld_fecdesde= $this->io_function->uf_convertirdatetobd("01/".$as_mes."/".$as_agno);
	//$ld_hasta   = $this->io_fec->uf_last_day($as_mes,$as_agno);
	//$ld_fechasta= $this->io_function->uf_convertirdatetobd($ld_hasta);
	$lb_valido  = true;
	switch ($as_tiporet) 
	{
		case "I":
			$filtro=" (SD.iva=1) AND (SD.codded=DTR.codded) AND (DTR.estcmp='0')";
			break;
		case "S":
			$filtro=" (SD.islr=1) AND (SD.codded=DTR.codded) AND (DTR.estcmpislr='0')";
			break;
		case "M":
			$filtro=" (SD.estretmun=1) AND (SD.codded=DTR.codded) AND (DTR.estcmpim='0')";
			break;
		case "T":
			$filtro=" (SD.otras=1) AND (SD.codded=DTR.codded) AND (DTR.estcmptimbrefiscal='0')";
			break;
		case "O":
			$filtro=" (SD.retaposol=1) AND (SD.codded=DTR.codded) AND (DTR.estcmpaporte='0')";
			break;
	}
	$ls_sql = " SELECT FAC.numfactura as id, FAC.fecfactura, DTR.codded as codded, DTR.monobjret as monobjret, ".
		" DTR.monret as monret, DTR.porded as porded, SD.dended as dended".
			" FROM sfa_factura FAC, tepuy_deducciones SD,sfa_dt_retenciones DTR ".
			" WHERE (FAC.codemp='".$this->la_empresa["codemp"]."') AND (FAC.codemp=DTR.codemp) AND".
                	" (FAC.codemp=SD.codemp) AND (SD.codemp=DTR.codemp) AND". 
			" (FAC.numfactura='".$as_numfactura."' AND FAC.cedcli='".$as_cedcli."') AND ".
			$filtro.
			" GROUP by id";
		//print $ls_sql;
		$rs_result=$this->io_sql->select($ls_sql);
		if($rs_result===false)
		{
			$this->io_msg->message("CLASE->Generar Comprobante MÉTODO->uf_get_documento ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;	
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_result))
			{
				$cuantos++;
				$ls_RD=$this->io_sql->obtener_datos($rs_result);
				//print "cuantos: ".$cuantos;
			}
			else
			{
			  $lb_valido=false;
			}
			$this->io_sql->free_result($rs_result);
		}
		return $lb_valido;	
	}//FIN DE LA FUNCION uf_get_documento 
/******************************************************************************************************************/
/***********************************    INSERCION DE DATA        **************************************************/	
/******************************************************************************************************************/

    function uf_crear_comprobante($as_codret,$as_numcom,$as_fecrep,$as_perfiscal,$as_codsujret,$as_nomsujret,$as_dirsujret,$as_rif,$as_nit,$as_estcmpret,$as_codusu,$as_numlic,$as_origen,$aa_seguridad)
	{
	    //////////////////////////////////////////////////////////////////////////////
		//	      Function: uf_crear_comprobante
		//	        Access: public
		//	      Argument: $as_codret // Codigo de la retencion,$as_numcom // Numero del comprobante
		//                  $as_fecrep // Fecha del comprobante,$as_perfiscal // perido fiscal
		//                  $as_codsujret // Codigo del proveedor o beneficiario,$as_nomsujret // Nombre del proveedor o beneficiario
		//                  $as_dirsujret // Direccion del proveedor o beneficiario ,$as_rif // RIF del proveedor o beneficiario
		//                  $as_nit // NIT del proveedor ,$as_estcmpret // Estatus del comprobante,
		//                  $as_codusu // codigo del usuario ,$as_numlic // Numero de licencia del proveedor,$as_origen 
		//     Description: Función que guarda la cabezera de un comprobante de retencion  
		//	    Creado Por: Ing. Miguel Palencia
		//  Fecha Creación: 13/09/2007								Fecha Última Modificación : 13/09/2007
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql=" INSERT INTO scb_cmp_ret (codemp,codret,numcom,fecrep,perfiscal,codsujret,nomsujret,dirsujret,rif,".
				"                          nit,estcmpret,codusu,numlic,origen)".
				  " VALUES ('".$this->la_empresa["codemp"]."','".$as_codret."','".$as_numcom."','".$as_fecrep."',".
				  "         '".$as_perfiscal."','".$as_codsujret."','". $as_nomsujret."','".$as_dirsujret."','".$as_rif."',".
				  "         '".$as_nit."','".$as_estcmpret."','".$as_codusu."','".$as_numlic."','".$as_origen."')";
		//print $ls_sql;
		$li_result=$this->io_sql->execute($ls_sql);
		if($li_result===false)
		{	
			$this->io_msg->message("CLASE->Generar Comprobante MÉTODO->uf_crear_comprobante ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el Comprobante ".$as_numcom.
							 " Asociado a la empresa ".$this->la_empresa["codemp"];
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}
		return $lb_valido;
    }//FIN DE LA FUNCION uf_crear_comprobante

    function uf_crear_comprobante_consolida($as_codret,&$as_numcom,$as_fecrep,$as_perfiscal,$as_codsujret,$as_nomsujret,$as_dirsujret,$as_rif,$as_nit,$as_estcmpret,$as_codusu,$as_numlic,$as_origen,$aa_seguridad)
	{
	    //////////////////////////////////////////////////////////////////////////////
		//	      Function: uf_crear_comprobante_consolida
		//	        Access: public
		//	      Argument: $as_codret // Codigo de la retencion,$as_numcom // Numero del comprobante
		//                  $as_fecrep // Fecha del comprobante,$as_perfiscal // perido fiscal
		//                  $as_codsujret // Codigo del proveedor o beneficiario,$as_nomsujret // Nombre del proveedor o beneficiario
		//                  $as_dirsujret // Direccion del proveedor o beneficiario ,$as_rif // RIF del proveedor o beneficiario
		//                  $as_nit // NIT del proveedor ,$as_estcmpret // Estatus del comprobante,
		//                  $as_codusu // codigo del usuario ,$as_numlic // Numero de licencia del proveedor,$as_origen 
		//     Description: Función que guarda la cabezera de un comprobante de retencion  
		//	    Creado Por: Ing. Miguel Palencia
		//  Fecha Creación: 13/09/2007								Fecha Última Modificación : 13/09/2007
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_basdatori=$_SESSION["ls_database"];
		$ls_sql=" INSERT INTO scb_cmp_ret (codemp,codret,codded,numcom,fecrep,perfiscal,codsujret,nomsujret,dirsujret,rif,".
				"                          nit,estcmpret,codusu,numlic,origen,basdatori)".
			  	" VALUES ('".$this->la_empresa["codemp"]."','".$as_codret."','".$as_numcom."','".$as_fecrep."',".
				  "         '".$as_perfiscal."','".$as_codsujret."','". $as_nomsujret."','".$as_dirsujret."','".$as_rif."',".
				  "         '".$as_nit."','".$as_estcmpret."','".$as_codusu."','".$as_numlic."','".$as_origen."','".$ls_basdatori."')";
//print $ls_sql;
		$li_result=$this->io_sqlaux->execute($ls_sql);
		if($li_result===false)
		{	
				if($this->io_sql->errno=='23505' || $this->io_sql->errno=='1062' || $this->io_sql->errno=='-239' || $this->io_sql->errno=='-5'|| $this->io_sql->errno=='-1')
				{
					$this->uf_get_nrocomprobante($as_codret,$as_perfiscal,&$as_numcom);
					$this->uf_crear_comprobante_consolida($as_codret,&$as_numcom,$as_fecrep,$as_perfiscal,$as_codsujret,$as_nomsujret,$as_dirsujret,$as_rif,
												  		  $as_nit,$as_estcmpret,$as_codusu,$as_numlic,$as_origen,$aa_seguridad);
				}
				else
				{
					$this->io_msg->message("CLASE->Generar Comprobate MÉTODO->uf_crear_comprobante_consolida ERROR->".$this->io_function->uf_convertirmsg($this->io_sqlaux->message));
					$lb_valido=false;
				}
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el Comprobante ".$as_numcom.
							 " Asociado a la empresa ".$this->la_empresa["codemp"];
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}
		return $lb_valido;
    }//FIN DE LA FUNCION uf_crear_comprobante_consolida

    function uf_guardar_comprobante($as_codret,$as_numfactura,$as_numcom,$as_codded,$as_feccomp,$as_perfiscal,$as_cedcli,$as_nomcli,$as_dircli,
$as_rifcli,$as_estcmpret,$as_codusu)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	      Function: uf_guardar_comprobante
		//	        Access: public
		//	      Argument: $as_codret // Codigo de la retencion,$as_numcom // Numero del comprobante
		//                  $as_fecrep // Fecha del comprobante,$as_perfiscal // perido fiscal
		//                  $as_codsujret // Codigo del proveedor o beneficiario,$as_nomsujret // Nombre del proveedor o beneficiario
		//                  $as_dirsujret // Direccion del proveedor o beneficiario ,$as_rif // RIF del proveedor o beneficiario
		//                  $as_nit // NIT del proveedor ,$as_estcmpret // Estatus del comprobante,
		//                  $as_codusu // codigo del usuario ,$as_numlic // Numero de licencia del proveedor,$as_origen 
		//     Description: Función que guarda la cabezera de un comprobante de retencion  
		//	    Creado Por: Ing. Miguel Palencia
		//  Fecha Creación: 13/09/2007								Fecha Última Modificación : 13/09/2007
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = " INSERT INTO sfa_cmp_retencion (codemp, codret, numcom, numfactura, feccomp, perfiscal, cedcli, nomcli, dircli, rifcli, tipret,estcmpret, codusu) ".
			  " VALUES  ('".$this->la_empresa["codemp"]."','".$as_codded."','".$as_numcom."','".$as_numfactura."','".$as_feccomp."','".$as_perfiscal."','".$as_cedcli."','".$as_nomcli."','".$as_dircli."',".
				  "          '".$as_rifcli."','".$as_codret."','".$as_estcmpret."','".$as_codusu."')";
		//print $ls_sql;return false;
		$li_result=$this->io_sql->execute($ls_sql);
		if($li_result ===false)
		{	
			$this->io_msg->message("CLASE->Generar Comprobate MÉTODO->uf_guardar_comprobante ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}//FIN DE LA FUNCION uf_guardar_detallecmp

    function uf_guardar_detallecmp_consolida($as_codret,$as_numcom,$as_numope,$as_fecfac,$as_numfac,$as_numcon,$as_numnd,$as_numnc,$as_tiptrans,$as_tot_cmp_sin_iva,
											 $as_tot_cmp_con_iva,$as_basimp,$as_porimp,$as_totimp,$as_ivaret,$as_desope,$as_numsop,$as_codban,$as_ctaban,
											 $as_numdoc,$as_codope)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	      Function: uf_crear_comprobante
		//	        Access: public
		//	      Argument: $as_codret // Codigo de la retencion,$as_numcom // Numero del comprobante
		//                  $as_fecrep // Fecha del comprobante,$as_perfiscal // perido fiscal
		//                  $as_codsujret // Codigo del proveedor o beneficiario,$as_nomsujret // Nombre del proveedor o beneficiario
		//                  $as_dirsujret // Direccion del proveedor o beneficiario ,$as_rif // RIF del proveedor o beneficiario
		//                  $as_nit // NIT del proveedor ,$as_estcmpret // Estatus del comprobante,
		//                  $as_codusu // codigo del usuario ,$as_numlic // Numero de licencia del proveedor,$as_origen 
		//     Description: Función que guarda la cabezera de un comprobante de retencion  
		//	    Creado Por: Ing. Miguel Palencia
		//  Fecha Creación: 13/09/2007								Fecha Última Modificación : 13/09/2007
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = " INSERT INTO scb_dt_cmp_ret (codemp,codret,numcom,numope,fecfac,numfac,numcon,numnd,numnc,tiptrans,".
		          "                             totcmp_sin_iva,totcmp_con_iva,basimp,porimp,totimp,iva_ret,desope,". 
				  "                              numsop,codban,ctaban,numdoc,codope) ".
				  " VALUES  ('".$this->la_empresa["codemp"]."','".$as_codret."','".$as_numcom."','".$as_numope."',".
				  "          '".$as_fecfac."','".$as_numfac."','".$as_numcon."','".$as_numnd."','".$as_numnc."',".
				  "          '".$as_tiptrans."','".$as_tot_cmp_sin_iva."','".$as_tot_cmp_con_iva."','".$as_basimp."',".
				  "          '".$as_porimp."','".$as_totimp."','".$as_ivaret."','".$as_desope."','".$as_numsop."',".
				  "          '".$as_codban."','".$as_ctaban."','".$as_numdoc."','".$as_codope."')";
		//print $ls_sql;
		$li_result=$this->io_sqlaux->execute($ls_sql);
		if($li_result===false)
		{	
			$this->io_msg->message("CLASE->Generar Comprobate MÉTODO->uf_guardar_detallecmp ERROR->".$this->io_function->uf_convertirmsg($this->io_sqlaux->message));
			$lb_valido=false;
		}
		return $lb_valido;
	}//FIN DE LA FUNCION uf_crear_comprobante
	
/******************************************************************************************************************/
/****************************    PROCESO CREACION COMPROBANTE         *********************************************/	
/******************************************************************************************************************/

	
/******************************************************************************************************************/
/**********************    PROCESO CREACION COMPROBANTE  DE RETENCION DE IVA 2016**********************************/	
/******************************************************************************************************************/
//					       ($ld_fecemicom,$ls_mes,$ls_agno,$ls_cedcodpro,$ls_tipoproben,$ls_tiporeten,$la_numcmp,$la_seguridad);
	function uf_procesar_cmp_retencion_2017($as_tiporeten,$ad_fecemision,$as_mes,$as_agno,$as_numfactura,$as_rifcli,$as_cedcli,$as_dircli,$as_cliente,$as_codded,$ai_monobjret,$ai_monret,$ai_porret,&$ai_numcmp,$aa_seguridad)
	{
	    //////////////////////////////////////////////////////////////////////////////
		//	Function: uf_procesar_cmp_retencion
		//	 Access: public
		//	 Argument: $as_mes // Mes  | $as_agno // Año
		//             $as_probendesde // Codigo de Proveedor o Benficiario | $as_probenhasta // Codigo de Proveedor o Benficiario
		//             $as_tipo // Indica si se trabaja con proveedores o beneficiarios 
		//             $as_tiporet // Indica el tipo de retencion
		//             $aa_numcmp // Arreglo con los numeros de los comprobantes generados 
		//  Description: Función que se encarga de generar los comprobante de retencion  
		//	Creado Por: Ing. Miguel Palencia
		//  Fecha Creación: 21/01/2016								Fecha Última Modificación : 21/01/2016
		//////////////////////////////////////////////////////////////////////////////

		/*print "Emision: ".$ad_fecemision;
		print "Factura: ".$as_numfactura;
		print "Mes: ".$as_mes;
		print "Año: ".$as_agno;
		print "rifcli: ".$as_rifcli;
		print "cliente:".$as_cliente;
		print "coded:".$as_codded;
		print "monto objeto ".$ai_monobjret;
		print "monto retenido ".$ai_monret;
		print "% objeto ".$ai_porret; //die();*/


		$aa_numcmp [0] ="";
		$li_numcmp= 0;
		$as_codemp= $this->la_empresa["codemp"];
		$ad_fecact=substr($ad_fecemision,-4)."-".substr($ad_fecemision,3,2)."-".substr($ad_fecemision,0,2);
		//print "Fecha del Comprobante 2: ".$ad_fecemision; 
		$as_agno=substr($ad_fecact,0,4);
		$as_mes=substr($ad_fecact,5,2);
		//print "año:".$as_agno." mes: ".$as_mes;
		$ls_perfis= $as_agno.$as_mes;
		//print "cliente:".$as_cliente;
		$ejecuto="SELECT codcmp,numcmp,tipo FROM cxp_contador WHERE codemp='$as_codemp' AND codcmp='$as_tiporeten'";
		// Busca el proximo numero de comprobante a generar dependiendo del tipo ///
		$as_numcompreten=$this->uf_obtengo_retencion($ejecuto,'N');
		$as_tiporet=$this->uf_obtengo_retencion($ejecuto,'T');
		//print "comprobante:".$as_numcompreten." Tipo Retencion: ".$as_tiporet; return false;
		$ejecuto="SELECT numcom FROM sfa_cmp_retencion WHERE codemp='$as_codemp' AND codret= '$as_codded' AND numcom='$as_numcompreten'";
		//print "  ".$ejecuto;
		$busco_comprobante=$this->uf_obtengo_certificado_retencion($ejecuto,$existe);
		if($busco_comprobante==0)
		{
			return false;
		}
		else
		{
			$valido=$this->uf_get_documento($as_mes,$as_agno,$as_cedcli,$as_tiporet,&$la_RD,$as_numfactura);
			//$li_rowrd=count($la_RD); 
			if($valido)
			{
				$ls_nrocomp=$as_numcompreten;
				$li_numcmp++;
				$aa_numcmp [$li_numcmp]=$ls_nrocomp;
				$li_i=0;
				$this->io_datadocu->data=$la_RD;
				$li_totalfilas=$this->io_datadocu->getRowCount("id");
				//print "documento x: ".$cuantos." Total Filas:  ".$li_totalfilas;
				for($li_j=1;$li_j<=$li_totalfilas;$li_j++)
				{
					$li_i++;
					$ls_factura=$this->io_datadocu->getValue("id",$li_j); // Factura
					$ls_fecha=$this->io_datadocu->getValue("fecfactura",$li_j);
					$ls_codded=$this->io_datadocu->getValue("codded",$li_j);
					$li_monobjret=$this->io_datadocu->getValue("monobjret",$li_j);
					$li_monret=$this->io_datadocu->getValue("monret",$li_j);
					$li_porded=$this->io_datadocu->getValue("porded",$li_j);
					/*print "Factura: ".$ls_coddoc;
					print "Fecha: ".$ls_fecha;
					print "Base imponible: ".$li_monobjret;
					print "Monto retenido: ".$li_monret;
					print "% Ret: ".$li_porded;
					print "cod. Ded: ".$ls_codded;*/
					$lb_valido=$this->uf_guardar_comprobante($as_tiporeten,$ls_factura,$ls_nrocomp,$ls_codded,$ad_fecact,$ls_perfis,$as_cedcli,$as_cliente,$as_dircli,$as_rifcli,"1",$this->ls_codusu);
					if($lb_valido)
					{
						$lb_valido=$this->uf_actualizar_estcmp($ls_factura,$as_cedcli,$ls_codded,$as_tiporet);
					// Genera el proximo numero de comprobante dependiendo del tipo  y lo almacena en cxp_contador///
					//$ls_nrocomnext=$this->io_keygen->uf_generar_numero_nuevo("CXP","cxp_contador","SUBSTR(numcmp,9,7)","CXPCMP",7,"","codcmp",$as_codded);
						$actual=(int)substr($ls_nrocomp,9,6);
						$actual++;
						$as_proximo=(string)$actual;
						//print "proximo: ".$as_proximo;
						$largo=strlen($as_proximo);
						$as_proximo=substr($ls_nrocomp,0,14-$largo).$as_proximo;
						//$as_proximo= $ls_nrocomp,0,8).$ls_nrocomnext;
						//print "proximo:".$as_proximo."largo:".strlen($as_proximo);
						
						$ejecuto="UPDATE cxp_contador SET numcmp='$as_proximo' WHERE codemp='$as_codemp' AND codcmp='$as_tiporeten'";
						//print $ejecuto;
						$li_numcmp=$ls_nrocomp;
						$as_numcompreten=$this->uf_actualizar_nrocmpretencion($ejecuto);
						//print $ejecuto;
						// Ayuda a mnatener la secuencia real de los comprobantes por tipo ///
					}
				} 
			}
			else
			{
				$this->io_msg->message("No existen documentos validos para realizar el proceso");
			}
		}
		if(($lb_valido)&&($li_numcmp>0))
		{
			$this->io_sql->commit();
		}
		else
		{
			$this->io_sql->rollback();
			$li_numcmp=0;
		}
		return $li_numcmp;
	}



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
	$ls_codemp=$this->la_empresa["codemp"];
	$ls_sql=$as_sql;
	//print $ls_sql;
	$rs_data=$this->io_sql->select($ls_sql);
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

//////// FUNCION QUE ME VERIFICA QUE EL NUMERO DE COMPROBANTE A GENERAR NO HAYA SIDO GENERADO PREVIAMENTE ////////////
function uf_obtengo_certificado_retencion ($as_sql,&$existe)
{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	    uf_obtengo_certificado_retencion
	// Access:			public
	//	Returns:		Boolean, Retorna true si el documento esta contabilizado, de lo contrario retorna false
	//  Fecha:          21/01/2016
	//	Autor:          Ing. Miguel Palencia		
	//////////////////////////////////////////////////////////////////////////////
	$ls_sql=$as_sql;
	//print $ls_sql;
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{
		$existe=0;
	}
	else
	{
		$existe=1;
	}
	//print "valor=".$existe;	
	return $existe;
}
////////////////////////// REVISA QUE EL NRO DE COMPROBANTE DE RETENCION QUE VIENE NO ESTE GENERADO///////////////////////


//////// FUNCION QUE BUSCA DATOS DEL PROVEEDOR O BENEFICIARIO ////////////
function uf_obtengo_datos_proben ($ls_sql,$campo)
{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:	    uf_obtengo_datos_proben
	// Access:			public
	//	Returns:		Boolean, Retorna true si el documento esta contabilizado, de lo contrario retorna false
	//  Fecha:          21/01/2016
	//	Autor:          Ing. Miguel Palencia		
	//////////////////////////////////////////////////////////////////////////////
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{
		$valor=0;
	}
	else
	{
		if($la_row=$this->io_sql->fetch_row($rs_data))
		{
			if($campo==='rif')
			{
				$valor=$la_row["rifpro"];
			}
			else
			{
				$valor=$la_row["dirpro"];
			}
		}		

	}
	//print "valor=".$valor;
	return $valor;
}
////////////////////////// REVISA QUE EL NRO DE COMPROBANTE DE RETENCION QUE VIENE NO ESTE GENERADO///////////////////////



	function uf_procesar_ndnc($as_numsop,$as_coddoc,$as_codtipdoc,$as_cod_pro,$as_ced_bene,&$ai_i,$as_nrocomp,$as_fecha,$as_numref,$as_codded)
	{
	    //////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_ndnc
		//		   Access: public
		//		 Argument: $ls_numsol // Numero de solicitud de pago
		//                 $ls_numrecdoc // Número de Recepcion de Documento
		//                 $ls_codtipdoc // Codigo de Tipo de documento 
		//                 $ls_cod_pro // Codigo de proveedor
		//                 $ls_ced_bene // Cedula de Beneficiario
		//	  Description: Función que verifica si existen notas de debito o credito asociadas al pago
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 16/09/2008								Fecha Última Modificación : 13/09/2007
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT cxp_sol_dc.codope,cxp_sol_dc.numrecdoc,cxp_sol_dc.numdc,cxp_sol_dc.fecope,cxp_sol_dc.monto,".
				//"       cxp_sol_dc.moncar,cxp_dc_cargos.porcar ".
				"       cxp_dc_cargos.porcar ".
				"  FROM cxp_sol_dc,cxp_dc_cargos".
				" WHERE cxp_sol_dc.codemp='".$this->ls_codemp."'".
				"   AND cxp_sol_dc.numsol='".$as_numsop."'".
				"   AND cxp_sol_dc.numrecdoc='".$as_coddoc."'".
				"   AND cxp_sol_dc.codtipdoc='".$as_codtipdoc."'".
				"   AND cxp_sol_dc.cod_pro='".$as_cod_pro."'".
				"   AND cxp_sol_dc.ced_bene='".$as_ced_bene."'".
				"   AND cxp_sol_dc.estnotadc='C'".
				"   AND cxp_sol_dc.codemp=cxp_dc_cargos.codemp".
				"   AND cxp_sol_dc.numsol=cxp_dc_cargos.numsol".
				"   AND cxp_sol_dc.numrecdoc=cxp_dc_cargos.numrecdoc".
				"   AND cxp_sol_dc.ced_bene=cxp_dc_cargos.ced_bene".
				"   AND cxp_sol_dc.cod_pro=cxp_dc_cargos.cod_pro".
				"   AND cxp_sol_dc.codope=cxp_dc_cargos.codope".
				"   AND cxp_sol_dc.numdc=cxp_dc_cargos.numdc";
//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Generar Comprobate MÉTODO->uf_procesar_ndnc ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			return false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_i=$ai_i+1;
				$ls_codope=$row["codope"];
				$ls_numdc=$row["numdc"];
				$ld_fecope=$row["fecope"];
				$li_monto=$row["monto"];
				$li_moncar=$row["moncar"];
				$ls_porcar=$row["porcar"];
				$li_basimp=$li_monto-$li_moncar;
				$ls_numope=$this->uf_get_nrooperacion($ai_i);
				if($ls_codope=="NC")
				{
					$ls_numnd="";
					$ls_numnc=$ls_numdc;
					$li_monto=$li_monto*(-1);
					$li_basimp=$li_basimp*(-1);
					$li_moncar=$li_moncar*(-1);
				}
				else
				{
					$ls_numnd=$ls_numdc;
					$ls_numnc="";
				}
				if($this->ls_basdatcmp!="")
				{
					$lb_valido=$this->uf_guardar_detallecmp_consolida($as_codded,$as_nrocomp,$ls_numope,$ld_fecope,$as_coddoc,$as_numref,$ls_numnd,$ls_numnc,"01-reg",
																	  0,$li_monto,$li_basimp,$ls_porcar,$li_moncar,0,"",
																	  $as_numsop,"","",$as_coddoc,"01");
				}
				if($lb_valido)
				{
					$lb_valido=$this->uf_guardar_detallecmp($as_codded,$as_nrocomp,$ls_corete,$ls_numope,$ld_fecope,$as_coddoc,$as_numref,$ls_numnd,$ls_numnc,
															"01-reg",0,$li_monto,$li_basimp,$ls_porcar,$li_moncar,
															0,"",$as_numsop,"","",$as_coddoc,"01");
				}
			//	$ls_concomiva=$row["concomiva"];
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}

	function uf_actualizar_estcmp($as_factura,$as_cedcli,$as_codded,$as_tiporet)
	{
	    //////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_actualizar_estcmp
		//		   Access: public
		//		 Argument: $as_factura // Número de Factura
		//                 $as_cedcli // Cedula del Cliente
		//                 $as_codded // Codigo de Retencion 
		//	  Description: Función que actualiza el campo estcmp al valor 1 en la tabla scb_dt_retenciones lo
		//                 que indica que ese item ya fue procesado en un comprobante
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 17/09/2017
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		switch ($as_tiporet) 
		{
			case "I":
				$ls_status="   SET estcmp='1'";
				break;
			case "S":
				$ls_status="   SET estcmpislr='1'";
				break;
			case "M":
				$ls_status="   SET estcmp='1'";
				break;
			case "T":
				$ls_status="   SET estcmptimbrefiscal='1'";
				break;
			case "O":
				$ls_status="   SET estcmpaporte='1'";
				break;
		}
		$ls_sql="UPDATE sfa_dt_retenciones".
			$ls_status.
		        " WHERE codemp='".$this->la_empresa["codemp"]."'".
				"   AND numfactura='".$as_factura."'". 
				"   AND cedcli='".$as_cedcli."'".
				"   AND codded='".$as_codded."'";
		//print $ls_sql;return false;
		$li_result=$this->io_sql->execute($ls_sql);
		if($li_result===false)
		{	
			$this->io_msg->message("CLASE->Generar Comprobate MÉTODO->uf_actualizar_estcmp ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
    }

function uf_actualizar_nrocmpretencion($ls_sql)
	{
	    //////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_actualizar_nrocmp
		//		   Access: public
		//		 Argument: $ls_sql // sentencia
		//	  Description: Función que actualiza el nrocmp al valor siguiente en la tabla cxp_contador lo
		//                 que indica que el comprobante fue procesado correctamente
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 22/01/2016								Fecha Última Modificación : 22/01/2016
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_result=$this->io_sql->execute($ls_sql);
		if($li_result===false)
		{	
			$this->io_msg->message("CLASE->Guardar el Proximo Comprobate MÉTODO->uf_actualizar_nrocmp ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
    }	
//////////uf_actualizar_nrocmp ///////////////////  
	
/******************************************************************************************************************/
/************************   UTILIDADES (CORELATIVOS,VALIDACIONES)       *******************************************/	
/******************************************************************************************************************/

function uf_get_nrocomprobante($as_codret,$as_periodofiscal,&$as_nrocomp,$comprobantecxp)
{
 	    //////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_get_nrocomprobante
		//		   Access: public
		//		 Argument: $as_codret // Codigo de Retencion 
		//                 $as_periodofiscal // Perido fiscal AAAAMM 
		//                 $as_nrocomp // Numero del Comprobante generado
		//	  Description: Función que genera el numero del comprobante
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 13/09/2007								Fecha Última Modificación : 13/09/2007
		//////////////////////////////////////////////////////////////////////////////
//print "codigo retencion: ".$as_codret;
		$ls_sql=" SELECT numcom ".
				"   FROM scb_cmp_ret".
				"  WHERE codemp='".$this->la_empresa["codemp"]."'".
				"    AND codret='".$as_codret."'".
				"  ORDER by numcom desc ";
		//print $ls_sql;
		$rs_result=$this->io_sql->select($ls_sql);		
		if($rs_result===false)
		{
			$this->io_msg->message("CLASE->Generar Comprobate MÉTODO->uf_get_nrocomprobante ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			return false;			
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_result))
			{
				
				$ls_nrocom1=$this->io_keygen->uf_generar_numero_nuevo("CXP","scb_cmp_ret","SUBSTR(numcom,7,9)","CXPCMP",9,"","codret",$as_codret);
				$as_nrocomp= $as_periodofiscal.$ls_nrocom1;   //substr($ls_nrocom,0.7).$ls_nrocom1;
			}
			else
			{
			   $as_nrocomp=$this->uf_load_numeroinicial($as_codret);
			   /*if($codigo=='')
			   {
			   		$codigo=1;
			   }
			   //$as_nrocomp=$this->io_function->uf_cerosizquierda($codigo,7);
			   $this->io_sql->free_result($rs_result);
			   if($as_codret=="01")
			   {
			   	//$as_nrocomp=$as_periodofiscal."0".$as_nrocomp;
			   }
			   else
			   {
			   	$as_nrocomp=$comprobantecxp;//$as_periodofiscal.$as_nrocomp;
			   }*/
			}
		}

/*		$this->ds_numcmp= new class_datastore();
		$ls_sql=" SELECT numcom ".
				"   FROM scb_cmp_ret".
				"  WHERE codemp='".$this->la_empresa["codemp"]."'".
				"    AND codret='".$as_codret."'".
				"  ORDER by numcom desc ";
		$rs_result=$this->io_sql->select($ls_sql);		
		if($rs_result===false)
		{
			$this->io_msg->message("CLASE->Generar Comprobate MÉTODO->uf_get_nrocomprobante ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			return false;			
		}
		else
		{
			$li_i=0;
			while($row=$this->io_sql->fetch_row($rs_result))
			{
				$li_i=$li_i+1;
			   	$codigo =$row["numcom"];				   
			    $codigo =substr($codigo,6,9);			      			   		   
				$this->ds_numcmp->insertRow("codigo",$codigo);
			}
			if($li_i>0)
			{
				$this->ds_numcmp->sortData("codigo");
				$ls_codigo=$this->ds_numcmp->getValue("codigo",$li_i);
				settype($ls_codigo,'int');
			    $li_newcodigo =$ls_codigo + 1;                             
			    settype($li_newcodigo,'string');  
				$ls_nrocomp=$this->io_function->uf_cerosizquierda($li_newcodigo,8);
			    $as_nrocomp=$as_periodofiscal.$ls_nrocomp;
			    $this->io_sql->free_result($rs_result);
			    return true;
			}
		    else
		    {
			   $codigo=$this->uf_load_numeroinicial();
			   $as_nrocomp=$this->io_function->uf_cerosizquierda($codigo,8);
			   $this->io_sql->free_result($rs_result);
			   $as_nrocomp=$as_periodofiscal.$as_nrocomp;
   			   return true;
		    }							
		}	*/		
	}	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_numeroinicial($as_codret)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_numeroinicial
		//		   Access: public
		//		 Argument: $as_codret // Codigo de retencion
		//	  Description: Función que busca la configuracion del numero inicial
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 26/02/2008								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$ls_concomiva=1;
		$ls_sql="SELECT codcmp,numcmp ".
				"  FROM cxp_contador ".
				" WHERE codemp='".$this->ls_codemp."'".
				" AND codcmp='".$as_codret."'";
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Generar Comprobate MÉTODO->uf_load_numeroinicial ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				//if($as_codret=='0000000001')
					$ls_concomiva=$row["numcmp"];
				//else
				//	$ls_concomiva=$row["numcmp"];
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $ls_concomiva;
	}// end function uf_load_numeroinicial
	//-----------------------------------------------------------------------------------------------------------------------------------
	
function uf_get_nrooperacion($as_num)
	{
 	    //////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_get_nrooperacion
		//		   Access: public
		//		 Argument: $as_num // Numero de operacion 
		//	  Description: Función que le da el formato al numero de operacion
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 13/09/2007								Fecha Última Modificación : 13/09/2007
		//////////////////////////////////////////////////////////////////////////////
	                          
	   settype($as_num,'string');                         
	   $ls_codigo=$this->io_function->uf_cerosizquierda($as_num,10);
	   return $ls_codigo;
				
	}
	
	function uf_validar_estempresa()
	{
 	     //////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_validar_estempresa
		//		   Access: public
		//		 Argument: Sin argumentos 
		//	  Description: Función que valida segun la configuracion de empresa si los comprobantes de
		//                 impuesto municipal pueden ser generados por Cuentas por pagar 
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 13/09/2007								Fecha Última Modificación : 13/09/2007
		//////////////////////////////////////////////////////////////////////////////
	    $lb_flag=true;
	    $ls_sql="SELECT modageret ".
				"  FROM tepuy_empresa". 
				" WHERE codemp='".$this->la_empresa["codemp"]."'";
		$rs_result=$this->io_sql->select($ls_sql);		
		if($rs_result===false)
		{
		    $this->io_msg->message("CLASE->Generar Comprobate MÉTODO->uf_validar_estempresa ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			return false;			
		}
		else
		{
			if ($row=$this->io_sql->fetch_row($rs_result))
			{  
				$ls_modret=$row["modageret"];
				if($ls_modret=='B')
				{
					$lb_flag=false;
				}
			}
		}
		return $lb_flag;	
	}
	
	
	function uf_validar_dt_rdmanual($as_codprobene,$as_numrd,$as_cuenta,$as_tipo)
	{
 	    ///////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_validar_dt_rdmanual
		//		   Access: public
		//		 Argument: $as_codprobene // Codigo del proveedo o beneficiario 
		//                 $as_tipo // Indica si el codprobene es un proveedor o un beneficiario 
	    //                 $as_munrd // numero de la recepcion de documento
		//				   $as_cuenta //Codigo de la cuenta contable asociada a ese detalle
		//	  Description: Función que valida un grupo de detalles en el caso de las recepciones manuales
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 13/09/2007								Fecha Última Modificación : 13/09/2007
		//////////////////////////////////////////////////////////////////////////////
	    $lb_flag=false;
		if($as_tipo=="P"){
		   $ls_filtro="cod_pro='".$as_codprobene."'";
		 }
		 elseif($as_tipo="B"){
		   $ls_filtro="ced_bene='".$as_codprobene."'";
		 }
		$ls_sql="SELECT estasicon,debhab". 
		        "  FROM cxp_rd_scg ".
			    " WHERE codemp='".$this->la_empresa["codemp"]."'".
				"   AND numrecdoc='".$as_numrd."'". 
				"   AND ".$ls_filtro."".
				"   AND sc_cuenta='".$as_cuenta."'";
		$rs_result=$this->io_sql->select($ls_sql);		
		if($rs_result===false)
		{
			$this->io_msg->message("CLASE->Generar Comprobate MÉTODO->uf_validar_dt_rdmanual ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			return false;			
		}
		else
		{
			if ($row=$this->io_sql->fetch_row($rs_result))
			{
				$ls_estasi=$row["estasicon"];
				$ls_debhab=$row["debhab"];
				if($ls_estasi=='A')
				{
					$lb_flag=true;  
				}
			    elseif($ls_estasi=='M')
				{
					if($ls_debhab=='D')
					{
						$lb_flag=true;  
					} 
				}
			}
		}
		return $lb_flag;	
	}
	
	function uf_buscar_monto_rdmanual($as_codprobene,$as_numrd,$as_tipo)
	{
 	    ///////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_buscar_monto_rdmanual
		//		   Access: public
		//		 Argument: $as_codprobene // Codigo del proveedo o beneficiario 
		//                 $as_tipo // Indica si el codprobene es un proveedor o un beneficiario 
	    //                 $as_munrd // numero de la recepcion de documento
		//	  Description: Función que ubica el monto de el detalle manual incluido por el haber para balancear
		//                 el monto total presentado en el comprobante
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 18/09/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
	    $ld_monto=0;
		if($as_tipo=="P"){
		   $ls_filtro="cod_pro='".$as_codprobene."'";
		 }
		 elseif($as_tipo="B"){
		   $ls_filtro="ced_bene='".$as_codprobene."'";
		 }
		$ls_sql="SELECT  monto ". 
		        "  FROM   cxp_rd_scg ".
			    " WHERE  codemp='".$this->la_empresa["codemp"]."'".
				"   AND numrecdoc='".$as_numrd."'". 
				"   AND ".$ls_filtro."".
				"   AND estasicon='M'".
				"   AND debhab='H'";
		$rs_result=$this->io_sql->select($ls_sql);		
		if($rs_result===false)
		 {
			$this->io_msg->message("CLASE->Generar Comprobate MÉTODO->uf_buscar_monto_rdmanual ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			return false;			
		 }
		 else
		 {
			if ($row=$this->io_sql->fetch_row($rs_result))
			{
				$ld_monto=$row["monto"];
			}
		 }
		 return $ld_monto;	
	}
///////// FUNCION QUE UBICA LOS TIPOS DE RETENCION QUE SE APLICAN //////////
	function uf_cmb_retencion($as_tiporeten)
	{
		$ls_sql="SELECT  codcmp, nom ". 
		        "  FROM   cxp_contador  WHERE codemp='".$this->la_empresa["codemp"]."'";

		$rs_result=$this->io_sql->select($ls_sql);		
		if($rs_result===false)
		 {
			$this->io_msg->message("CLASE->Buscar Retenciones MÉTODO->uf_cmb_retencion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			return false;			
		 }
		 else
		 {
			print "<select name=tiporeten id=tiporeten onchange=validaretencion();>";
			while($row=$this->io_sql->fetch_row($rs_result))
			//if ($row=$this->io_sql->fetch_row($rs_result))
			{
				print '<OPTION VALUE="'.$row['codcmp'].'">'.$row['nom'].'</OPTION>';
			}
			print "</select>";
		 }


	}
/////////////////////// FIN DE LA FUNCION DE BUSQUEDAS DE TIPOS DE RETENCION /////////
	function uf_cmb_mes($as_mes)
	{
		///////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_cmb_mes
		//		   Access: public
		//		 Argument: $as_mes // numero que representa el mes en curso 
		//	  Description: Función que construye el combo de meses
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 26/09/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		switch ($as_mes) {
		   case '01':
			   $lb_selEnero="selected";
			   $lb_selFebrero="";
			   $lb_selMarzo="";
			   $lb_selAbril="";
			   $lb_selMayo="";
			   $lb_selJunio="";
			   $lb_selJulio="";
			   $lb_selAgosto="";
			   $lb_selSeptiembre="";
			   $lb_selOctubre="";
			   $lb_selNoviembre="";	
			   $lb_selDiciembre="";
			   break;
		   case '02':
			   $lb_selEnero="";
			   $lb_selFebrero="selected";
			   $lb_selMarzo="";
			   $lb_selAbril="";
			   $lb_selMayo="";
			   $lb_selJunio="";
			   $lb_selJulio="";
			   $lb_selAgosto="";
			   $lb_selSeptiembre="";
			   $lb_selOctubre="";
			   $lb_selNoviembre="";	
			   $lb_selDiciembre="";
			   break;
		   case '03':
			   $lb_selEnero="";
			   $lb_selFebrero="";
			   $lb_selMarzo="selected";
			   $lb_selAbril="";
			   $lb_selMayo="";
			   $lb_selJunio="";
			   $lb_selJulio="";
			   $lb_selAgosto="";
			   $lb_selSeptiembre="";
			   $lb_selOctubre="";
			   $lb_selNoviembre="";	
			   $lb_selDiciembre="";
			   break;
		   case '04':
			   $lb_selEnero="";
			   $lb_selFebrero="";
			   $lb_selMarzo="";
			   $lb_selAbril="selected";
			   $lb_selMayo="";
			   $lb_selJunio="";
			   $lb_selJulio="";
			   $lb_selAgosto="";
			   $lb_selSeptiembre="";
			   $lb_selOctubre="";
			   $lb_selNoviembre="";	
			   $lb_selDiciembre="";
			   break;
		   case '05':
			   $lb_selEnero="";
			   $lb_selFebrero="";
			   $lb_selMarzo="";
			   $lb_selAbril="";
			   $lb_selMayo="selected";
			   $lb_selJunio="";
			   $lb_selJulio="";
			   $lb_selAgosto="";
			   $lb_selSeptiembre="";
			   $lb_selOctubre="";
			   $lb_selNoviembre="";	
			   $lb_selDiciembre="";
			   break;
		   case '06':
			   $lb_selEnero="";
			   $lb_selFebrero="";
			   $lb_selMarzo="";
			   $lb_selAbril="";
			   $lb_selMayo="";
			   $lb_selJunio="selected";
			   $lb_selJulio="";
			   $lb_selAgosto="";
			   $lb_selSeptiembre="";
			   $lb_selOctubre="";
			   $lb_selNoviembre="";	
			   $lb_selDiciembre="";
			   break;		   
		   case '07':
			   $lb_selEnero="";
			   $lb_selFebrero="";
			   $lb_selMarzo="";
			   $lb_selAbril="";
			   $lb_selMayo="";
			   $lb_selJunio="";
			   $lb_selJulio="selected";
			   $lb_selAgosto="";
			   $lb_selSeptiembre="";
			   $lb_selOctubre="";
			   $lb_selNoviembre="";	
			   $lb_selDiciembre="";
			   break;		   		 
		   case '08':
			   $lb_selEnero="";
			   $lb_selFebrero="";
			   $lb_selMarzo="";
			   $lb_selAbril="";
			   $lb_selMayo="";
			   $lb_selJunio="";
			   $lb_selJulio="";
			   $lb_selAgosto="selected";
			   $lb_selSeptiembre="";
			   $lb_selOctubre="";
			   $lb_selNoviembre="";	
			   $lb_selDiciembre="";
			   break;		   		 		     
		   case '09':
			   $lb_selEnero="";
			   $lb_selFebrero="";
			   $lb_selMarzo="";
			   $lb_selAbril="";
			   $lb_selMayo="";
			   $lb_selJunio="";
			   $lb_selJulio="";
			   $lb_selAgosto="";
			   $lb_selSeptiembre="selected";
			   $lb_selOctubre="";
			   $lb_selNoviembre="";	
			   $lb_selDiciembre="";
			   break;
		   case '10':
			   $lb_selEnero="";
			   $lb_selFebrero="";
			   $lb_selMarzo="";
			   $lb_selAbril="";
			   $lb_selMayo="";
			   $lb_selJunio="";
			   $lb_selJulio="";
			   $lb_selAgosto="";
			   $lb_selSeptiembre="";
			   $lb_selOctubre="selected";
			   $lb_selNoviembre="";	
			   $lb_selDiciembre="";
			   break;		   
		   case '11':
			   $lb_selEnero="";
			   $lb_selFebrero="";
			   $lb_selMarzo="";
			   $lb_selAbril="";
			   $lb_selMayo="";
			   $lb_selJunio="";
			   $lb_selJulio="";
			   $lb_selAgosto="";
			   $lb_selSeptiembre="";
			   $lb_selOctubre="";
			   $lb_selNoviembre="selected";	
			   $lb_selDiciembre="";
			   break;		   
		   case '12':
			   $lb_selEnero="";
			   $lb_selFebrero="";
			   $lb_selMarzo="";
			   $lb_selAbril="";
			   $lb_selMayo="";
			   $lb_selJunio="";
			   $lb_selJulio="";
			   $lb_selAgosto="";

			   $lb_selSeptiembre="";
			   $lb_selOctubre="";
			   $lb_selNoviembre="";	
			   $lb_selDiciembre="selected";
			   break;		   
		}
	
		print "<select name=mes id=mes onchange=validarmes();>";
		print "<option value=01 ".$lb_selEnero.">ENERO</option>";
		print "<option value=02 ".$lb_selFebrero.">FEBRERO</option>";
		print "<option value=03 ".$lb_selMarzo.">MARZO</option>";
		print "<option value=04 ".$lb_selAbril.">ABRIL</option>";
		print "<option value=05 ".$lb_selMayo.">MAYO</option>";
		print "<option value=06 ".$lb_selJunio.">JUNIO</option>";
		print "<option value=07 ".$lb_selJulio.">JULIO</option>";
		print "<option value=08 ".$lb_selAgosto.">AGOSTO</option>";
		print "<option value=09 ".$lb_selSeptiembre.">SEPTIEMBRE</option>";
		print "<option value=10 ".$lb_selOctubre.">OCTUBRE</option>";
		print "<option value=11 ".$lb_selNoviembre.">NOVIEMBRE</option>";
		print "<option value=12 ".$lb_selDiciembre.">DICIEMBRE</option>";
		print "</select>";
	}
	function uf_cmb_mes1($as_mes1)
	{
		///////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_cmb_mes
		//		   Access: public
		//		 Argument: $as_mes // numero que representa el mes en curso 
		//	  Description: Función que construye el combo de meses
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 26/09/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		switch ($as_mes1) {
		   case '01':
			   $lb_selEnero="selected";
			   $lb_selFebrero="";
			   $lb_selMarzo="";
			   $lb_selAbril="";
			   $lb_selMayo="";
			   $lb_selJunio="";
			   $lb_selJulio="";
			   $lb_selAgosto="";
			   $lb_selSeptiembre="";
			   $lb_selOctubre="";
			   $lb_selNoviembre="";	
			   $lb_selDiciembre="";
			   break;
		   case '02':
			   $lb_selEnero="";
			   $lb_selFebrero="selected";
			   $lb_selMarzo="";
			   $lb_selAbril="";
			   $lb_selMayo="";
			   $lb_selJunio="";
			   $lb_selJulio="";
			   $lb_selAgosto="";
			   $lb_selSeptiembre="";
			   $lb_selOctubre="";
			   $lb_selNoviembre="";	
			   $lb_selDiciembre="";
			   break;
		   case '03':
			   $lb_selEnero="";
			   $lb_selFebrero="";
			   $lb_selMarzo="selected";
			   $lb_selAbril="";
			   $lb_selMayo="";
			   $lb_selJunio="";
			   $lb_selJulio="";
			   $lb_selAgosto="";
			   $lb_selSeptiembre="";
			   $lb_selOctubre="";
			   $lb_selNoviembre="";	
			   $lb_selDiciembre="";
			   break;
		   case '04':
			   $lb_selEnero="";
			   $lb_selFebrero="";
			   $lb_selMarzo="";
			   $lb_selAbril="selected";
			   $lb_selMayo="";
			   $lb_selJunio="";
			   $lb_selJulio="";
			   $lb_selAgosto="";
			   $lb_selSeptiembre="";
			   $lb_selOctubre="";
			   $lb_selNoviembre="";	
			   $lb_selDiciembre="";
			   break;
		   case '05':
			   $lb_selEnero="";
			   $lb_selFebrero="";
			   $lb_selMarzo="";
			   $lb_selAbril="";
			   $lb_selMayo="selected";
			   $lb_selJunio="";
			   $lb_selJulio="";
			   $lb_selAgosto="";
			   $lb_selSeptiembre="";
			   $lb_selOctubre="";
			   $lb_selNoviembre="";	
			   $lb_selDiciembre="";
			   break;
		   case '06':
			   $lb_selEnero="";
			   $lb_selFebrero="";
			   $lb_selMarzo="";
			   $lb_selAbril="";
			   $lb_selMayo="";
			   $lb_selJunio="selected";
			   $lb_selJulio="";
			   $lb_selAgosto="";
			   $lb_selSeptiembre="";
			   $lb_selOctubre="";
			   $lb_selNoviembre="";	
			   $lb_selDiciembre="";
			   break;		   
		   case '07':
			   $lb_selEnero="";
			   $lb_selFebrero="";
			   $lb_selMarzo="";
			   $lb_selAbril="";
			   $lb_selMayo="";
			   $lb_selJunio="";
			   $lb_selJulio="selected";
			   $lb_selAgosto="";
			   $lb_selSeptiembre="";
			   $lb_selOctubre="";
			   $lb_selNoviembre="";	
			   $lb_selDiciembre="";
			   break;		   		 
		   case '08':
			   $lb_selEnero="";
			   $lb_selFebrero="";
			   $lb_selMarzo="";
			   $lb_selAbril="";
			   $lb_selMayo="";
			   $lb_selJunio="";
			   $lb_selJulio="";
			   $lb_selAgosto="selected";
			   $lb_selSeptiembre="";
			   $lb_selOctubre="";
			   $lb_selNoviembre="";	
			   $lb_selDiciembre="";
			   break;		   		 		     
		   case '09':
			   $lb_selEnero="";
			   $lb_selFebrero="";
			   $lb_selMarzo="";
			   $lb_selAbril="";
			   $lb_selMayo="";
			   $lb_selJunio="";
			   $lb_selJulio="";
			   $lb_selAgosto="";
			   $lb_selSeptiembre="selected";
			   $lb_selOctubre="";
			   $lb_selNoviembre="";	
			   $lb_selDiciembre="";
			   break;
		   case '10':
			   $lb_selEnero="";
			   $lb_selFebrero="";
			   $lb_selMarzo="";
			   $lb_selAbril="";
			   $lb_selMayo="";
			   $lb_selJunio="";
			   $lb_selJulio="";
			   $lb_selAgosto="";
			   $lb_selSeptiembre="";
			   $lb_selOctubre="selected";
			   $lb_selNoviembre="";	
			   $lb_selDiciembre="";
			   break;		   
		   case '11':
			   $lb_selEnero="";
			   $lb_selFebrero="";
			   $lb_selMarzo="";
			   $lb_selAbril="";
			   $lb_selMayo="";
			   $lb_selJunio="";
			   $lb_selJulio="";
			   $lb_selAgosto="";
			   $lb_selSeptiembre="";
			   $lb_selOctubre="";
			   $lb_selNoviembre="selected";	
			   $lb_selDiciembre="";
			   break;		   
		   case '12':
			   $lb_selEnero="";
			   $lb_selFebrero="";
			   $lb_selMarzo="";
			   $lb_selAbril="";
			   $lb_selMayo="";
			   $lb_selJunio="";
			   $lb_selJulio="";
			   $lb_selAgosto="";

			   $lb_selSeptiembre="";
			   $lb_selOctubre="";
			   $lb_selNoviembre="";	
			   $lb_selDiciembre="selected";
			   break;		   
		}
	
		print "<select name=mes1 id=mes1;>";
		print "<option value=01 ".$lb_selEnero.">ENERO</option>";
		print "<option value=02 ".$lb_selFebrero.">FEBRERO</option>";
		print "<option value=03 ".$lb_selMarzo.">MARZO</option>";
		print "<option value=04 ".$lb_selAbril.">ABRIL</option>";
		print "<option value=05 ".$lb_selMayo.">MAYO</option>";
		print "<option value=06 ".$lb_selJunio.">JUNIO</option>";
		print "<option value=07 ".$lb_selJulio.">JULIO</option>";
		print "<option value=08 ".$lb_selAgosto.">AGOSTO</option>";
		print "<option value=09 ".$lb_selSeptiembre.">SEPTIEMBRE</option>";
		print "<option value=10 ".$lb_selOctubre.">OCTUBRE</option>";
		print "<option value=11 ".$lb_selNoviembre.">NOVIEMBRE</option>";
		print "<option value=12 ".$lb_selDiciembre.">DICIEMBRE</option>";
		print "</select>";
	}
}
?>
