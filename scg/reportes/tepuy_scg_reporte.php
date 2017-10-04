<?php
require_once("../../shared/class_folder/class_sql.php");	  
require_once("../../shared/class_folder/class_funciones.php");
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/class_fecha.php");
require_once("../../shared/class_folder/tepuy_include.php");
require_once("../../shared/class_folder/class_tepuy_int.php");
require_once("../../shared/class_folder/class_tepuy_int_scg.php");
class tepuy_scg_reporte
{
	var $SQL;
	var $dts_empresa; // datastore empresa
	var $fun;
	var $io_msg;
	var $SQL_aux;
	var $ds_analitico;
	var $dts_reporte;
	var $dts_costos;
	var $dts_cab;
	var $dts_egresos;
	var $dts_Prebalance;
	var $dts_Balance1;
	var $tepuy_int_scg;
//---------------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------------
	function tepuy_scg_reporte()
	{
	  $this->fun = new class_funciones();
	  $this->siginc=new tepuy_include();
	  $this->con=$this->siginc->uf_conectar();
	  $this->SQL= new class_sql($this->con);
	  $this->SQL_aux= new class_sql($this->con);
	  $this->io_msg= new class_mensajes();		
	  $this->dts_empresa=$_SESSION["la_empresa"];
	  $this->ds_analitico=new class_datastore();
	  $this->dts_reporte=new class_datastore();
	  $this->dts_costos=new class_datastore();
	  $this->dts_cab=new class_datastore();
	  $this->dts_egresos=new class_datastore();
	  $this->dts_Prebalance=new class_datastore();
	  $this->dts_Balance1=new class_datastore();
      $this->tepuy_int_scg=new class_tepuy_int_scg();
	}
//---------------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------------
    //////////////////////////////////////////////////////////////////
	//   CLASE REPORTES SCG  " COMPROBANTES FORMATO 1 Y FORMATO 2" // 
	////////////////////////////////////////////////////////////////
	function uf_scg_reporte_comprobante_formato1($ls_procede,$ls_comprobante,$ldt_fecha,$ai_orden,&$rs_data)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_scg_reporte_comprobante_formato1
	 //         Access :	private
	 //     Argumentos :    $as_procede  // procede 
	 //                     $as_comprobante  // comprobante  
	 //                     $adt_fecha  // fecha 
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del Comprobante Formato 1  
	 //     Creado por :    Ing. Miguel Palencia
	 // Fecha Creacion :    04-07-2006          Fecha ltima Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = true;
		$ls_codemp = $this->dts_empresa["codemp"];
	    $this->dts_reporte->resetds("sc_cuenta");
        
		if(!empty($ls_procede))
		{
			   $ls_cad_where1=" MV.procede = '".$ls_procede."' ";
		}
		else
		{
		 	   $ls_cad_where1="";
		}
		if(!empty($ls_comprobante))
		{
			   $ls_cad_where2=" MV.comprobante = '".$ls_comprobante."' ";
		}
		else
		{
		 	   $ls_cad_where2="";
		}
		if(!empty($ldt_fecha))
		{
			   $ls_cad_where3=" MV.fecha='".$ldt_fecha."' ";
		}
		else
		{
		 	   $ls_cad_where3="";
		}
		
		$ls_cadena_concat=$ls_cad_where1.$ls_cad_where2.$ls_cad_where3;
		if (!empty($ls_cadena_concat))
		{
			$ls_cad_where=" AND ";
			
			if(!empty($ls_cad_where1))
			{
				$ls_cad_concat=$ls_cad_where2.$ls_cad_where3;
				$ls_cond_iif=$this->iif(!empty($ls_cad_concat)," AND ", "");		    
				$ls_cad_where=$ls_cad_where.$ls_cad_where1.$ls_cond_iif;
			}
			if(!empty($ls_cad_where2))
			{
				$ls_cond_iif=$this->iif(!empty($ls_cad_where3)," AND ", "");		    
				$ls_cad_where=$ls_cad_where.$ls_cad_where2.$ls_cond_iif;
			}
			if(!empty($ls_cad_where3))
			{
				$ls_cad_where=$ls_cad_where.$ls_cad_where3;
			}
	   }
	   else
	   {
	        $ls_cad_where=" ";
	   }	
	   if($ai_orden==1)  
	   {
    	  $ls_orden_cad="MV.debhab,MV.procede,MV.comprobante,MV.fecha,MV.orden";
	   }
	   if($ai_orden==2)  
	   {
    	  $ls_orden_cad="MV.debhab,MV.comprobante,MV.fecha,MV.procede,MV.orden";
	   }
	   if($ai_orden==3)  
	   {
          $ls_orden_cad="MV.debhab,MV.fecha,MV.procede,MV.comprobante,MV.orden";
	   }
	    $ls_sql=" SELECT  MV.procede, MV.comprobante, MV.sc_cuenta , MV.procede_doc, MV.debhab, MV.monto, ".
	   		   "		 CC.denominacion,  CAST(MV.descripcion as char(250)) as cmp_descripcion ".
               " FROM    scg_dt_cmp MV ".
			   " left join  scg_cuentas CC  on (MV.sc_cuenta = CC.sc_cuenta)".
               " WHERE  (MV.codemp = '".$ls_codemp."') ".
			   "        ".$ls_cad_where." ".
               " ORDER BY MV.debhab, MV.debhab";
		$rs_data=$this->SQL->select($ls_sql);
		if($rs_data===false)
		{   // error interno sql
			$this->is_msg_error="Error en consulta metodo uf_scg_reporte_comprobante_formato1 ".$this->fun->uf_convertirmsg($this->SQL->message);
			$lb_valido = false;
		}
     return $lb_valido;
  }// fin uf_spg_reporte_comprobante_formato1
//---------------------------------------------------------------------------------------------------------------------------------


//---------------------------------------------------------------------------------------------------------------------------------
    //////////////////////////////////////////////////////////////////
	//   CLASE REPORTES SCG  " COMPROBANTES FORMATO 1 Y FORMATO 2" // 
	////////////////////////////////////////////////////////////////
	function uf_scg_reporte_libro_diario_general($ls_procede,$ls_comprobante,$ldt_fecha,$ai_orden,&$rs_data)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_scg_reporte_comprobante_formato1
	 //         Access :	private
	 //     Argumentos :    $as_procede  // procede 
	 //                     $as_comprobante  // comprobante  
	 //                     $adt_fecha  // fecha 
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del Comprobante Formato 1  
	 //     Creado por :    Ing. Miguel Palencia
	 // Fecha Creacion :    04-07-2006          Fecha ltima Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = true;
		$ls_codemp = $this->dts_empresa["codemp"];
	    $this->dts_reporte->resetds("sc_cuenta");
        
		if(!empty($ls_procede))
		{
			   $ls_cad_where1=" MV.procede = '".$ls_procede."' ";
		}
		else
		{
		 	   $ls_cad_where1="";
		}
		if(!empty($ls_comprobante))
		{
			   $ls_cad_where2=" MV.comprobante = '".$ls_comprobante."' ";
		}
		else
		{
		 	   $ls_cad_where2="";
		}
		if(!empty($ldt_fecha))
		{
			   $ls_cad_where3=" MV.fecha='".$ldt_fecha."' ";
		}
		else
		{
		 	   $ls_cad_where3="";
		}
		
		$ls_cadena_concat=$ls_cad_where1.$ls_cad_where2.$ls_cad_where3;
		if (!empty($ls_cadena_concat))
		{
			$ls_cad_where=" AND ";
			
			if(!empty($ls_cad_where1))
			{
				$ls_cad_concat=$ls_cad_where2.$ls_cad_where3;
				$ls_cond_iif=$this->iif(!empty($ls_cad_concat)," AND ", "");		    
				$ls_cad_where=$ls_cad_where.$ls_cad_where1.$ls_cond_iif;
			}
			if(!empty($ls_cad_where2))
			{
				$ls_cond_iif=$this->iif(!empty($ls_cad_where3)," AND ", "");		    
				$ls_cad_where=$ls_cad_where.$ls_cad_where2.$ls_cond_iif;
			}
			if(!empty($ls_cad_where3))
			{
				$ls_cad_where=$ls_cad_where.$ls_cad_where3;
			}
	   }
	   else
	   {
	        $ls_cad_where=" ";
	   }	
	   if($ai_orden==1)  
	   {
	  $ls_orden_cad="MV.debhab,MV.fecha,MV.procede,MV.comprobante,MV.orden";
	   }
	   if($ai_orden==2)  
	   {
    	  $ls_orden_cad="MV.debhab,MV.comprobante,MV.fecha,MV.procede,MV.orden";
	   }
	   if($ai_orden==3)  
	   {
          $ls_orden_cad="MV.debhab,MV.procede,MV.comprobante,MV.fecha,MV.orden";
	   }
	    $ls_sql=" SELECT  MV.procede, MV.comprobante, MV.sc_cuenta , MV.procede_doc, MV.debhab, MV.monto, ".
	   		   "		 CC.denominacion,  CAST(MV.descripcion as char(250)) as cmp_descripcion ".
               " FROM    scg_dt_cmp MV ".
			   " left join  scg_cuentas CC  on (MV.sc_cuenta = CC.sc_cuenta)".
               " WHERE  (MV.codemp = '".$ls_codemp."') ".
			   "        ".$ls_cad_where." ".
               " ORDER BY MV.debhab, MV.debhab";
		$rs_data=$this->SQL->select($ls_sql);
		if($rs_data===false)
		{   // error interno sql
			$this->is_msg_error="Error en consulta metodo uf_scg_reporte_comprobante_formato1 ".$this->fun->uf_convertirmsg($this->SQL->message);
			$lb_valido = false;
		}
     return $lb_valido;
  }// fin uf_spg_reporte_libro_diario_general
//---------------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------------
	function iif($ad_condicional,$ad_true,$ad_false)
	{
		if(eval("return $ad_condicional;"))
		{
			$ad_return=$ad_true;
		}
		else
		{
			$ad_return=($ad_false);
		}
		return $ad_return;
	}
//---------------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------------
    function uf_scg_reporte_select_comprobante_formato1($as_procede_ori,$as_procede_des,$as_comprobante_ori,$as_comprobante_des,
	                                                    $adt_fecini,$adt_fecfin,$ai_orden,&$rs_data)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_scg_reporte_select_comprobante_formato1
	 //         Access :	private
	 //     Argumentos :    $as_procede_ori  // procede origen
	 //                     $as_procede_des  // procede destino
	 //                     $as_comprobante_ori  // comprobante origen 
	 //                     $as_comprobante_des  //  comprobante destino
	 //                     $adt_fecini  // fecha  desde 
     //              	    $adt_fecfin  // fecha hasta 
	 //                     $ai_orden  //  orden la consulta  
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del Comprobante Formato 1  
	 //     Creado por :    Ing. Miguel Palencia/  Ing. Juniors Fraga
	 // Fecha Creacion :    23/04/2006          Fecha ltima Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = true;
		$ls_codemp = $this->dts_empresa["codemp"];
        
		if((!empty($as_procede_ori))&&(!empty($as_procede_des)))
		{
			   $ls_cad_where1=" cmp.procede between '".$as_procede_ori."' AND  '".$as_procede_des."' ";
		}
		else
		{
		 	   $ls_cad_where1="";
		}
		if((!empty($as_comprobante_ori))&&(!empty($as_comprobante_des)))
		{
			   $ls_cad_where2=" cmp.comprobante between '".$as_comprobante_ori."' AND  '".$as_comprobante_des."' ";
		}
		else
		{
		 	   $ls_cad_where2="";
		}
		if((!empty($adt_fecini))&&(!empty($adt_fecfin)))
		{
			   $ls_cad_where3=" cmp.fecha between '".$adt_fecini."' AND  '".$adt_fecfin."' ";
		}
		else
		{
		 	   $ls_cad_where3="";
		}
		
		$ls_cadena_concat=$ls_cad_where1.$ls_cad_where2.$ls_cad_where3;
		if (!empty($ls_cadena_concat))
		{
			$ls_cad_where=" AND ";
			
			if(!empty($ls_cad_where1))
			{
				$ls_cad_concat=$ls_cad_where2.$ls_cad_where3;
				$ls_cond_iif=$this->iif(!empty($ls_cad_concat)," AND ", "");		    
				$ls_cad_where=$ls_cad_where.$ls_cad_where1.$ls_cond_iif;
			}
			if(!empty($ls_cad_where2))
			{
				$ls_cond_iif=$this->iif(!empty($ls_cad_where3)," AND ", "");		    
				$ls_cad_where=$ls_cad_where.$ls_cad_where2.$ls_cond_iif;
			}
			if(!empty($ls_cad_where3))
			{
				$ls_cad_where=$ls_cad_where.$ls_cad_where3;
			}
	   }
	   else
	   {
	        $ls_cad_where=" ";
	   }	
	   if($ai_orden==1)  
	   {
    	  $ls_orden="cmp.procede,cmp.comprobante,cmp.fecha";
	   }
	   if($ai_orden==2)  
	   {
    	  $ls_orden="cmp.comprobante,cmp.fecha,cmp.procede";
	   }
	   if($ai_orden==3)  
	   {
    	  $ls_orden="cmp.fecha,cmp.procede,cmp.comprobante";
	   }
		if($_SESSION["ls_gestor"]=='MYSQL')
		{
			 $ls_sql=" SELECT ( CASE   cmp.tipo_destino
						WHEN 'P' THEN prv.nompro
						WHEN 'B' THEN CONCAT(RTRIM(xbf.apebene),',',xbf.nombene)
						ELSE 'Ninguno'
					END )  as  nombre,cmp.codemp,cmp.procede,cmp.comprobante as comprobante,cmp.descripcion,cmp.fecha,cmp.cod_pro,cmp.ced_bene,cmp.tipo_destino
					FROM tepuy_cmp cmp,rpc_beneficiario xbf,rpc_proveedor prv
					WHERE cmp.cod_pro=prv.cod_pro AND  cmp.ced_bene=xbf.ced_bene AND
						  cmp.codemp='".$ls_codemp."' AND tipo_comp=1 ".$ls_cad_where." 
					ORDER BY ".$ls_orden." "; 
		}
		if($_SESSION["ls_gestor"]=='POSTGRE')
		{
			 $ls_sql="SELECT (CASE cmp.tipo_destino
							WHEN 'P' THEN prv.nompro
							WHEN 'B' THEN RTRIM(xbf.apebene)||','||xbf.nombene
							ELSE 'Ninguno'
							END)  as  nombre,
							cmp.codemp,cmp.procede,cmp.comprobante as comprobante,cmp.descripcion,cmp.fecha,
							cmp.cod_pro,cmp.ced_bene,cmp.tipo_destino
					FROM tepuy_cmp cmp,rpc_beneficiario xbf,rpc_proveedor prv
					WHERE cmp.cod_pro=prv.cod_pro AND  cmp.ced_bene=xbf.ced_bene AND
						  cmp.codemp='".$ls_codemp."' AND tipo_comp=1 ".$ls_cad_where." 
					ORDER BY ".$ls_orden." "; 
		}
		$rs_data=$this->SQL->select($ls_sql);
		if($rs_data===false)
		{   // error interno sql
			$this->is_msg_error="Error en consulta metodo uf_scg_reporte_select_comprobante_formato1 ".$this->fun->uf_convertirmsg($this->SQL->message);
			$lb_valido = false;
		}
		return $lb_valido;
  }//uf_scg_reporte_select_comprobante_formato1
//---------------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------------
    function uf_scg_reporte_select_libro_diario_general_2017($adt_fecini,$adt_fecfin,$ai_nivel,&$rs_data)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_scg_reporte_select_libro_diario_general_2017
	 //         Access :	private
	 //     Argumentos :    $as_procede_ori  // procede origen
	 //                     $as_procede_des  // procede destino
	 //                     $as_comprobante_ori  // comprobante origen 
	 //                     $as_comprobante_des  //  comprobante destino
	 //                     $adt_fecini  // fecha  desde 
	 //              	    $adt_fecfin  // fecha hasta 
	 //                     $ai_orden  //  orden la consulta  
	 //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del Comprobante Formato 1  
	 //     Creado por :    Ing. Miguel Palencia/  Ing. Juniors Fraga
	 // Fecha Creacion :    08/06/2017
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = true;
		$ls_codemp = $this->dts_empresa["codemp"];
        
		if((!empty($adt_fecini))&&(!empty($adt_fecfin)))
		{
			   $ls_cad_where1=" cmp.fecha between '".$adt_fecini."' AND  '".$adt_fecfin."' ";
		}
		else
		{
		 	   $ls_cad_where1="";
		}
		
		$ls_cadena=$ls_cad_where1;
	 if(!empty($ai_nivel))  
	 {

    	 	$ls_orden="cmp.fecha,cmp.procede,cmp.comprobante";
	 }
		if($_SESSION["ls_gestor"]=='MYSQL')
		{
			 $ls_sql=" SELECT ( CASE   cmp.tipo_destino
						WHEN 'P' THEN prv.nompro
						WHEN 'B' THEN CONCAT(RTRIM(xbf.apebene),',',xbf.nombene)
						ELSE 'Ninguno'
					END )  as  nombre,cmp.codemp,cmp.procede,cmp.comprobante as comprobante,cmp.descripcion,cmp.fecha,cmp.cod_pro,cmp.ced_bene,cmp.tipo_destino
					FROM tepuy_cmp cmp,rpc_beneficiario xbf,rpc_proveedor prv
					WHERE cmp.cod_pro=prv.cod_pro AND  cmp.ced_bene=xbf.ced_bene AND
						  cmp.codemp='".$ls_codemp."' AND tipo_comp=1 ".$ls_cadena." 
					ORDER BY ".$ls_orden." "; 
		}
		if($_SESSION["ls_gestor"]=='POSTGRE')
		{
			 $ls_sql="SELECT (CASE cmp.tipo_destino
							WHEN 'P' THEN prv.nompro
							WHEN 'B' THEN RTRIM(xbf.apebene)||','||xbf.nombene
							ELSE 'Ninguno'
							END)  as  nombre,
							cmp.codemp,cmp.procede,cmp.comprobante as comprobante,cmp.descripcion,cmp.fecha,

							cmp.cod_pro,cmp.ced_bene,cmp.tipo_destino
					FROM tepuy_cmp cmp,rpc_beneficiario xbf,rpc_proveedor prv
					WHERE cmp.cod_pro=prv.cod_pro AND  cmp.ced_bene=xbf.ced_bene AND
						  cmp.codemp='".$ls_codemp."' AND tipo_comp=1 ".$ls_cadena." 
					ORDER BY ".$ls_orden." "; 
		}
		print $ls_sql;die();
		$rs_data=$this->SQL->select($ls_sql);
		if($rs_data===false)
		{   // error interno sql
			$this->is_msg_error="Error en consulta metodo uf_scg_reporte_select_comprobante_formato1 ".$this->fun->uf_convertirmsg($this->SQL->message);
			$lb_valido = false;
		}
		return $lb_valido;
  }//uf_scg_reporte_select_libro_diario_general_2017
//---------------------------------------------------------------------------------------------------------------------------------



//---------------------------------------------------------------------------------------------------------------------------------
    function uf_scg_reporte_select_libro_diario_general($as_procede_ori,$as_procede_des,$as_comprobante_ori,$as_comprobante_des,
	                                                    $adt_fecini,$adt_fecfin,$ai_orden,&$rs_data)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_scg_reporte_select_comprobante_formato1
	 //         Access :	private
	 //     Argumentos :    $as_procede_ori  // procede origen
	 //                     $as_procede_des  // procede destino
	 //                     $as_comprobante_ori  // comprobante origen 
	 //                     $as_comprobante_des  //  comprobante destino
	 //                     $adt_fecini  // fecha  desde 
     //              	    $adt_fecfin  // fecha hasta 
	 //                     $ai_orden  //  orden la consulta  
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del Comprobante Formato 1  
	 //     Creado por :    Ing. Miguel Palencia/  Ing. Juniors Fraga
	 // Fecha Creacion :    23/04/2006          Fecha ltima Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = true;
		$ls_codemp = $this->dts_empresa["codemp"];
        
		if((!empty($as_procede_ori))&&(!empty($as_procede_des)))
		{
			   $ls_cad_where1=" cmp.procede between '".$as_procede_ori."' AND  '".$as_procede_des."' ";
		}
		else
		{
		 	   $ls_cad_where1="";
		}
		if((!empty($as_comprobante_ori))&&(!empty($as_comprobante_des)))
		{
			   $ls_cad_where2=" cmp.comprobante between '".$as_comprobante_ori."' AND  '".$as_comprobante_des."' ";
		}
		else
		{
		 	   $ls_cad_where2="";
		}
		if((!empty($adt_fecini))&&(!empty($adt_fecfin)))
		{
			   $ls_cad_where3=" cmp.fecha between '".$adt_fecini."' AND  '".$adt_fecfin."' ";
		}
		else
		{
		 	   $ls_cad_where3="";
		}
		
		$ls_cadena_concat=$ls_cad_where1.$ls_cad_where2.$ls_cad_where3;
		if (!empty($ls_cadena_concat))

		{
			$ls_cad_where=" AND ";
			
			if(!empty($ls_cad_where1))
			{
				$ls_cad_concat=$ls_cad_where2.$ls_cad_where3;
				$ls_cond_iif=$this->iif(!empty($ls_cad_concat)," AND ", "");		    
				$ls_cad_where=$ls_cad_where.$ls_cad_where1.$ls_cond_iif;
			}
			if(!empty($ls_cad_where2))
			{
				$ls_cond_iif=$this->iif(!empty($ls_cad_where3)," AND ", "");		    
				$ls_cad_where=$ls_cad_where.$ls_cad_where2.$ls_cond_iif;
			}
			if(!empty($ls_cad_where3))
			{
				$ls_cad_where=$ls_cad_where.$ls_cad_where3;
			}
	   }
	   else
	   {
	        $ls_cad_where=" ";
	   }	
	   if($ai_orden==1)  
	   {
    	  $ls_orden="cmp.fecha,cmp.procede,cmp.comprobante";
	   }
	   if($ai_orden==2)  
	   {
    	  $ls_orden="cmp.comprobante,cmp.fecha,cmp.procede";
	   }
	   if($ai_orden==3)  
	   {
	  $ls_orden="cmp.procede,cmp.comprobante,cmp.fecha";
	   }
		if($_SESSION["ls_gestor"]=='MYSQL')
		{
			 $ls_sql=" SELECT ( CASE   cmp.tipo_destino
						WHEN 'P' THEN prv.nompro
						WHEN 'B' THEN CONCAT(RTRIM(xbf.apebene),',',xbf.nombene)
						ELSE 'Ninguno'
					END )  as  nombre,cmp.codemp,cmp.procede,cmp.comprobante as comprobante,cmp.descripcion,cmp.fecha,cmp.cod_pro,cmp.ced_bene,cmp.tipo_destino
					FROM tepuy_cmp cmp,rpc_beneficiario xbf,rpc_proveedor prv
					WHERE cmp.cod_pro=prv.cod_pro AND  cmp.ced_bene=xbf.ced_bene AND
						  cmp.codemp='".$ls_codemp."' AND tipo_comp=1 ".$ls_cad_where." 
					ORDER BY ".$ls_orden." "; 
		}
		if($_SESSION["ls_gestor"]=='POSTGRE')
		{
			 $ls_sql="SELECT (CASE cmp.tipo_destino
							WHEN 'P' THEN prv.nompro
							WHEN 'B' THEN RTRIM(xbf.apebene)||','||xbf.nombene
							ELSE 'Ninguno'
							END)  as  nombre,
							cmp.codemp,cmp.procede,cmp.comprobante as comprobante,cmp.descripcion,cmp.fecha,

							cmp.cod_pro,cmp.ced_bene,cmp.tipo_destino
					FROM tepuy_cmp cmp,rpc_beneficiario xbf,rpc_proveedor prv
					WHERE cmp.cod_pro=prv.cod_pro AND  cmp.ced_bene=xbf.ced_bene AND
						  cmp.codemp='".$ls_codemp."' AND tipo_comp=1 ".$ls_cad_where." 
					ORDER BY ".$ls_orden." "; 
		}
		$rs_data=$this->SQL->select($ls_sql);
		if($rs_data===false)
		{   // error interno sql
			$this->is_msg_error="Error en consulta metodo uf_scg_reporte_select_comprobante_formato1 ".$this->fun->uf_convertirmsg($this->SQL->message);
			$lb_valido = false;
		}
		return $lb_valido;
  }//uf_scg_reporte_select_libro_diario_general
//---------------------------------------------------------------------------------------------------------------------------------



//---------------------------------------------------------------------------------------------------------------------------------
   function  uf_scg_reporte_comprobante_formato2($ls_comprobante,$ls_procede,$ldt_fecha,$ai_orden,&$rs_data) 
   {				 
	 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_scg_reporte_comprobante_formato2
	 //         Access :	private
	 //     Argumentos :    $as_spg_cuenta_ori  // cuenta origen
	 //                     $as_spg_cuenta_des  // cuenta destino
	 //                     $adt_fecini  // fecha  desde 
     //              	    $adt_fecfin  // fecha hasta 
	 //                     $as_comprobante  // comprobante
	 //                     $ai_orden  // orden de la consulta       
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del Comprobante Formato 2  
	 //     Creado por :    Ing. Miguel Palencia/  Ing. Juniors Fraga
	 // Fecha Creacion :    23/04/2006          Fecha ltima Modificacion :24/04/2006      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido = true;
	    $ls_codemp = $this->dts_empresa["codemp"];
	    $this->dts_reporte->resetds("sc_cuenta");
		if(!empty($ls_procede))
		{
			   $ls_cad_where1=" MV.procede = '".$ls_procede."' ";
		}
		else
		{
		 	   $ls_cad_where1="";
		}
		if(!empty($ls_comprobante))
		{
			   $ls_cad_where2=" MV.comprobante = '".$ls_comprobante."' ";
		}
		else
		{
		 	   $ls_cad_where2="";
		}
		if(!empty($ldt_fecha))
		{
			   $ls_cad_where3=" MV.fecha='".$ldt_fecha."' ";
		}
		else
		{
		 	   $ls_cad_where3="";
		}
		
		$ls_cadena_concat=$ls_cad_where1.$ls_cad_where2.$ls_cad_where3;
		if (!empty($ls_cadena_concat))
		{
			$ls_cad_where=" AND ";
			
			if(!empty($ls_cad_where1))
			{
				$ls_cad_concat=$ls_cad_where2.$ls_cad_where3;
				$ls_cond_iif=$this->iif(!empty($ls_cad_concat)," AND ", "");		    
				$ls_cad_where=$ls_cad_where.$ls_cad_where1.$ls_cond_iif;
			}
			if(!empty($ls_cad_where2))
			{
				$ls_cond_iif=$this->iif(!empty($ls_cad_where3)," AND ", "");		    
				$ls_cad_where=$ls_cad_where.$ls_cad_where2.$ls_cond_iif;
			}
			if(!empty($ls_cad_where3))
			{
				$ls_cad_where=$ls_cad_where.$ls_cad_where3;
			}
	   }
	   else
	   {
	        $ls_cad_where=" ";
	   }	
	  
	  $ls_orden="MV.comprobante,MV.fecha,MV.procede,MV.orden";
	  if($ai_orden==1)  
	  {
    	  $ls_orden="MV.debhab,MV.procede,MV.comprobante,MV.fecha,MV.orden";
	  }
	  if($ai_orden==2)  
	  {
    	  $ls_orden="MV.debhab,MV.comprobante,MV.fecha,MV.procede,MV.orden";
	  }
	  if($ai_orden==3)  
	  {
    	  $ls_orden="MV.debhab,MV.fecha,MV.procede,MV.comprobante,MV.orden";
	  }
	  $ls_sql=" SELECT MV.comprobante, MV.sc_cuenta, MV.procede_doc, MV.debhab, ".
	          "        MV.descripcion, MV.monto, CC.denominacion, ".
			  "        MV.descripcion as cmp_descripcion ".
              " FROM    scg_dt_cmp MV ".
			  " left join  scg_cuentas CC  on (MV.sc_cuenta = CC.sc_cuenta)".
              " WHERE  (MV.codemp = '".$ls_codemp."') ".
			  "        ".$ls_cad_where." ".
			  " ORDER BY  MV.debhab, MV.debhab ";
	  $rs_data=$this->SQL->select($ls_sql);
	  if($rs_data===false)
	  {   // error interno sql
	   	  $this->is_msg_error="Error en consulta metodo uf_scg_reporte_comprobante_formato1 ".$this->fun->uf_convertirmsg($this->SQL->message);
		  $lb_valido = false;
	  }
      return $lb_valido;
 }//uf_spg_reporte_comprobante_formato2
//---------------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------------
   function  uf_scg_reporte_select_comprobante_formato2($as_spg_cuenta_ori,$as_spg_cuenta_des,$adt_fecini,
                                                        $adt_fecfin,$ai_orden,&$rs_data) 
   {				 
	 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_scg_reporte_select_comprobante_formato2
	 //         Access :	private
	 //     Argumentos :    $as_spg_cuenta_ori  // cuenta origen
	 //                     $as_spg_cuenta_des  // cuenta destino
	 //                     $adt_fecini  // fecha  desde 
     //              	    $adt_fecfin  // fecha hasta 
	 //                     $ai_orden  // orden de la consulta       
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del Comprobante Formato 2  
	 //     Creado por :    Ing. Miguel Palencia/  Ing. Juniors Fraga
	 // Fecha Creacion :    23/04/2006          Fecha ltima Modificacion :24/04/2006      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido = true;
	  $ls_codemp = $this->dts_empresa["codemp"];
	  $this->dts_cab->resetds("comprobante");
	  
	  if (empty($as_spg_cuenta_ori) && (!empty($as_spg_cuenta_des)))
	  {
	      $this->io_msg("Debe especificar cuenta DESDE....");
	      return false;
	  }	  
	  if (!empty($as_spg_cuenta_ori) && empty($as_spg_cuenta_des))
	  {
	      $this->io_msg("Debe especificar cuenta HASTA....");
	      return false;
	  }	  
      if (!empty($as_spg_cuenta_ori) && (!empty($as_spg_cuenta_des)))
	  {
	  		$ls_cad_filtro2=" AND MV.sc_cuenta between '".$as_spg_cuenta_ori."' AND '".$as_spg_cuenta_des."' ";
	  } 
      else
	  {
	  		$ls_cad_filtro2="";
	  }
       
	  if ((!empty($adt_fecini)) && (!empty($adt_fecfin)))
	  {
	     $ls_cad_where3=" AND MV.fecha BETWEEN '".$adt_fecini."' AND '".$adt_fecfin."' ";
	  }
      else
	  {
	     $ls_cad_where3="";
	  } 
      $ls_cad_where=$ls_cad_where3;
	 
	  if (empty($adt_fecini) && empty($adt_fecfin))
	  {
	    $ls_cad_where= $ls_cad_where;
	  }	
	  if ( (!empty($adt_fecini) && empty($adt_fecfin))||(empty($adt_fecini) && !empty($adt_fecfin)))
	  {
	      $ls_cad_where= $ls_cad_where."";
      }
	  if($ai_orden==1)  
	  {
    	  $ls_orden="CMP.procede,CMP.comprobante,CMP.fecha";
	  }
	  if($ai_orden==2)  
	  {
    	  $ls_orden="CMP.comprobante,CMP.fecha,CMP.procede";
	  }
	  if($ai_orden==3)  
	  {
    	  $ls_orden="CMP.fecha,CMP.procede,CMP.comprobante";
	  }
	  
	  $ls_sql=" SELECT CMP.comprobante, CMP.procede, CMP.fecha, MAX(CMP.tipo_destino) AS tipo_destino,  ".
	          "        MAX(CMP.cod_pro) AS cod_pro, MAX(CMP.ced_bene) AS ced_bene, ".
              "        MAX(PRV.nompro) AS nompro, MAX(BEN.apebene) AS apebene,  MAX(BEN.nombene) AS nombene,  ".
			  "        MAX(MV.orden) AS orden, MAX(CMP.descripcion) AS descripcion ".
              " FROM   scg_dt_cmp MV, scg_cuentas CC, tepuy_cmp CMP, rpc_proveedor PRV, rpc_beneficiario BEN  ".
              " WHERE CMP.codemp = '".$ls_codemp."' ".
	          "        ".$ls_cad_where3." ".$ls_cad_filtro2." ".
			  "    AND MV.codemp = CC.codemp ".
			  "    AND MV.sc_cuenta = CC.sc_cuenta ".
			  "    AND MV.codemp=CMP.codemp ".
			  "    AND MV.procede=CMP.procede ".
			  "    AND MV.comprobante=CMP.comprobante ".
			  "    AND MV.fecha=CMP.fecha ".
			  "    AND CMP.codemp=PRV.codemp ".
			  "    AND CMP.cod_pro=PRV.cod_pro ".
			  "    AND CMP.codemp=BEN.codemp ".
			  "    AND CMP.ced_bene=BEN.ced_bene ".
			  " GROUP BY CMP.comprobante,CMP.procede,CMP.fecha ".
			  " ORDER BY  ".$ls_orden." ";
	$rs_data=$this->SQL->select($ls_sql);
	if($rs_data===false)
	{   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_scg_reporte_select_comprobante_formato2 ".$this->fun->uf_convertirmsg($this->SQL->message);
		$lb_valido = false;
	}
    return $lb_valido;
  }//uf_scg_reporte_select_comprobante_formato2
//---------------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------------
    //////////////////////////////////////////////////////////////////
	//   CLASE REPORTES SCG  " ESTADO DE RESULTADO  "              // 
	////////////////////////////////////////////////////////////////
   function  uf_scg_reporte_estado_de_resultado_ingreso($adt_fecini,$adt_fecfin,$ai_nivel) 
   {				 
	 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_scg_reporte_estado_de_resultado
	 //         Access :	private
	 //     Argumentos :    $adt_fecini  // fecha  desde 
	 //                     $adt_fecini  // fecha  desde 
	 //                     $ai_nivel   //  nivel de la  cuenta
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del Estado de Resultado 
	 //     Creado por :    Ing. Miguel Palencia/  Ing. Juniors Fraga
	 // Fecha Creacion :    24/04/2006          Fecha ltima Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido = false;
	  $ls_codemp = $this->dts_empresa["codemp"];
	  $this->dts_reporte->resetds("sc_cuenta");
      $li_ingreso = trim($this->dts_empresa["ingreso"]);
      $li_gasto = trim($this->dts_empresa["gasto"]);
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
				$ls_cadena="cast(0 as UNSIGNED ) ";
				break;
			case "POSTGRE":
				$ls_cadena="CAST(0 AS int2) ";
				break;					
		}
	  $ls_sql=" SELECT SC.sc_cuenta, SC.status, SC.denominacion,  curSaldo.saldo, ".
	          "        ".$ls_cadena." as nivel, ".$ls_cadena." as total_ingresos, ".
              "       ".$ls_cadena." as total_egresos ".
              " FROM   scg_cuentas SC, (SELECT sc_cuenta, codemp, sum(haber_mes-debe_mes) as saldo ".
		      "                         FROM   scg_saldos ".
		      "                         WHERE  codemp='".$ls_codemp."' AND fecsal between '".$adt_fecini."' AND '".$adt_fecfin."' ".
		      "                         GROUP BY codemp, sc_cuenta) as curSaldo ".
              " WHERE (SC.sc_cuenta = curSaldo.sc_cuenta) AND (SC.codemp=curSaldo.codemp) AND ".
			  "       (SC.sc_cuenta like '".$li_ingreso."%') AND  (SC.nivel<='".$ai_nivel."') ".
              " ORDER BY SC.sc_cuenta ";
	 $rs_data=$this->SQL->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
			$this->is_msg_error="Error en consulta metodo uf_scg_reporte_estado_de_resultado_ingreso ".$this->fun->uf_convertirmsg($this->SQL->message);
			$lb_valido = false;
	 }
	 else
	 {
	    $ld_total_ingresos=0;
		$lb_valido=$this->uf_scg_reporte_select_saldo_ingreso($adt_fecini,$adt_fecfin,$li_ingreso,$ld_total_ingresos);
	    if($lb_valido)
	    {
			$lb_valido = false;
			while($row=$this->SQL->fetch_row($rs_data))
			{
			   $ls_sc_cuenta=$row["sc_cuenta"];
			   $ls_status=$row["status"];
			   $ls_denominacion=$row["denominacion"];
			   $ld_saldo=$row["saldo"];
			   $ls_nivel=$this->tepuy_int_scg->uf_scg_obtener_nivel($ls_sc_cuenta);			   
			   $this->dts_reporte->insertRow("sc_cuenta",$ls_sc_cuenta);
			   $this->dts_reporte->insertRow("status",$ls_status);
			   $this->dts_reporte->insertRow("denominacion",$ls_denominacion);
			   $this->dts_reporte->insertRow("saldo",$ld_saldo);
			   $this->dts_reporte->insertRow("nivel",$ls_nivel);
			   $this->dts_reporte->insertRow("total_ingresos",$ld_total_ingresos);
			   $lb_valido = true;
		   }//while   
		 }//if
		$this->SQL->free_result($rs_data);
	}//else	  
	return $lb_valido;
}//fin uf_scg_reporte_estado_de_resultado_ingreso
//---------------------------------------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------------------------------
   function  uf_scg_reporte_estado_de_resultado_costos($adt_fecini,$adt_fecfin,$ai_nivel) 
   {				 
	 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_scg_reporte_estado_de_resultado_egreso
	 //         Access :	private
	 //     Argumentos :    $adt_fecini  // fecha  desde 
	 //                     $adt_fecini  // fecha  desde 
	 //                     $ai_nivel   //  nivel de la  cuenta
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del Estado de Resultado 
	 //     Creado por :    Ing. Miguel Palencia/  Ing. Juniors Fraga
	 // Fecha Creacion :    24/04/2006          Fecha ltima Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido = true;
	  $ls_codemp = $this->dts_empresa["codemp"];
      $li_costos = trim($this->dts_empresa["resultado"]);
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
				$ls_cadena="cast(0 as UNSIGNED ) ";
				break;
			case "POSTGRE":
				$ls_cadena="CAST(0 AS int2) ";
				break;					
		}
	  $ls_sql=" SELECT SC.sc_cuenta, SC.status, SC.denominacion,  curSaldo.saldo, ".
	          "        ".$ls_cadena." as nivel,  ".$ls_cadena." as total_ingresos, ".
              "         ".$ls_cadena." as total_egresos ".
//              "         ".$ls_cadena." as total_costos ".
              " FROM   scg_cuentas SC, (SELECT sc_cuenta, codemp, sum(haber_mes-debe_mes) as saldo ".
		      "                         FROM   scg_saldos ".
		      "                         WHERE  codemp='".$ls_codemp."' AND fecsal between '".$adt_fecini."' AND '".$adt_fecfin."' ".
		      "                         GROUP BY codemp, sc_cuenta) as curSaldo ".
              " WHERE (SC.sc_cuenta = curSaldo.sc_cuenta) AND (SC.codemp=curSaldo.codemp) AND ".
			  "       (SC.sc_cuenta like '".$li_costos."%') AND (SC.nivel<='".$ai_nivel."') ".
              " ORDER BY SC.sc_cuenta ";
	 $rs_data=$this->SQL->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
			$this->is_msg_error="Error en consulta metodo uf_scg_reporte_estado_de_resultado_costos ".$this->fun->uf_convertirmsg($this->SQL->message);
			$lb_valido = false;
	 }
	 else
	 {
		$ld_total_costos=0;
		$lb_valido=$this->uf_scg_reporte_select_saldo_costos($adt_fecini,$adt_fecfin,$li_costos,$ld_total_costos);
	    if($lb_valido)
	    {
			$lb_valido = false;
			while($row=$this->SQL->fetch_row($rs_data))
			{
			   $ls_sc_cuenta=$row["sc_cuenta"];
			   $ls_status=$row["status"];
			   $ls_denominacion=$row["denominacion"];
			   $ld_saldo=$row["saldo"];
			   $ls_nivel=$this->tepuy_int_scg->uf_scg_obtener_nivel($ls_sc_cuenta);
			   $this->dts_costos->insertRow("sc_cuenta",$ls_sc_cuenta);
			   $this->dts_costos->insertRow("status",$ls_status);
			   $this->dts_costos->insertRow("denominacion",$ls_denominacion);
			   $this->dts_costos->insertRow("saldo",$ld_saldo);
			   $this->dts_costos->insertRow("nivel",$ls_nivel);
			   $this->dts_costos->insertRow("total_costos",$ld_total_costos);
			   $lb_valido = true;
		   }//while   
		}//if
		$this->SQL->free_result($rs_data);
	}//else	  
	return $lb_valido;
}//fin uf_scg_reporte_estado_de_resultado_costos
//---------------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------------
    //////////////////////////////////////////////////////////////////
	//   CLASE REPORTES SCG  " ESTADO DE RESULTADO  "              // 
	////////////////////////////////////////////////////////////////


//---------------------------------------------------------------------------------------------------------------------------------
   function  uf_scg_reporte_estado_de_resultado_egreso($adt_fecini,$adt_fecfin,$ai_nivel) 
   {				 
	 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_scg_reporte_estado_de_resultado_egreso
	 //         Access :	private
	 //     Argumentos :    $adt_fecini  // fecha  desde 
	 //                     $adt_fecini  // fecha  desde 
	 //                     $ai_nivel   //  nivel de la  cuenta
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del Estado de Resultado 
	 //     Creado por :    Ing. Miguel Palencia/  Ing. Juniors Fraga
	 // Fecha Creacion :    24/04/2006          Fecha ltima Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido = true;
	  $ls_codemp = $this->dts_empresa["codemp"];
      $li_gasto = trim($this->dts_empresa["gasto"]);
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
				$ls_cadena="cast(0 as UNSIGNED ) ";
				break;
			case "POSTGRE":
				$ls_cadena="CAST(0 AS int2) ";
				break;					
		}
	  $ls_sql=" SELECT SC.sc_cuenta, SC.status, SC.denominacion,  curSaldo.saldo, ".
	          "        ".$ls_cadena." as nivel,  ".$ls_cadena." as total_ingresos, ".
              "         ".$ls_cadena." as total_egresos ".
              " FROM   scg_cuentas SC, (SELECT sc_cuenta, codemp, sum(haber_mes-debe_mes) as saldo ".
		      "                         FROM   scg_saldos ".
		      "                         WHERE  codemp='".$ls_codemp."' AND fecsal between '".$adt_fecini."' AND '".$adt_fecfin."' ".
		      "                         GROUP BY codemp, sc_cuenta) as curSaldo ".
              " WHERE (SC.sc_cuenta = curSaldo.sc_cuenta) AND (SC.codemp=curSaldo.codemp) AND ".
			  "       (SC.sc_cuenta like '".$li_gasto."%') AND (SC.nivel<='".$ai_nivel."') ".
              " ORDER BY SC.sc_cuenta ";
	 $rs_data=$this->SQL->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
			$this->is_msg_error="Error en consulta metodo uf_scg_reporte_estado_de_resultado_egreso ".$this->fun->uf_convertirmsg($this->SQL->message);
			$lb_valido = false;
	 }
	 else
	 {
		$ld_total_egresos=0;
		$lb_valido=$this->uf_scg_reporte_select_saldo_gasto($adt_fecini,$adt_fecfin,$li_gasto,$ld_total_egresos);
	    if($lb_valido)
	    {
			$lb_valido = false;
			while($row=$this->SQL->fetch_row($rs_data))
			{
			   $ls_sc_cuenta=$row["sc_cuenta"];
			   $ls_status=$row["status"];
			   $ls_denominacion=$row["denominacion"];
			   $ld_saldo=$row["saldo"];
			   $ls_nivel=$this->tepuy_int_scg->uf_scg_obtener_nivel($ls_sc_cuenta);
			   $this->dts_egresos->insertRow("sc_cuenta",$ls_sc_cuenta);
			   $this->dts_egresos->insertRow("status",$ls_status);
			   $this->dts_egresos->insertRow("denominacion",$ls_denominacion);
			   $this->dts_egresos->insertRow("saldo",$ld_saldo);
			   $this->dts_egresos->insertRow("nivel",$ls_nivel);
			   $this->dts_egresos->insertRow("total_egresos",$ld_total_egresos);
			   $lb_valido = true;
		   }//while   
		}//if
		$this->SQL->free_result($rs_data);
	}//else	  
	return $lb_valido;
}//fin uf_scg_reporte_estado_de_resultado_egreso
//---------------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------------
   function  uf_scg_reporte_select_saldo_ingreso($adt_fecini,$adt_fecfin,$ai_ingreso,&$ad_total_ingresos) 
   {				 
	 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_scg_reporte_select_saldo_ingreso
	 //         Access :	private
	 //     Argumentos :    $adt_fecini  // fecha  desde 
	 //                     $adt_fecfin  // fecha hasta
     //              	    $ai_ingreso  // numero de la cuenta de ingraso 
	 //                     $ad_total_ingresos  //  total de ingreso (referencia)
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del Estado de Resultado  
	 //     Creado por :    Ing. Miguel Palencia/  Ing. Juniors Fraga
	 // Fecha Creacion :    24/04/2006          Fecha ltima Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT COALESCE(sum(curSaldo.SALDO),0) as total_ingresos ".
             " FROM   scg_cuentas SC,(SELECT sc_cuenta, codemp, COALESCE(sum(haber_mes-debe_mes),0) as saldo ".
             "                        FROM   scg_saldos ".
		     "                        WHERE  codemp='".$ls_codemp."' AND fecsal between '".$adt_fecini."' AND '".$adt_fecfin."' ".
		     "                        GROUP BY codemp, sc_cuenta) as curSaldo ".
             " WHERE (SC.sc_cuenta = curSaldo.sc_cuenta) AND (SC.codemp = curSaldo.codemp) AND (SC.status='C') AND ".
	         "       (SC.sc_cuenta like '".$ai_ingreso."%') ";
	 $rs_data=$this->SQL->select($ls_sql);
	 if($rs_data===false)
	 {// error interno sql
		$this->is_msg_error="Error en consulta metodo uf_scg_reporte_select_saldo_ingreso ".$this->fun->uf_convertirmsg($this->SQL->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->SQL->fetch_row($rs_data))
		{
		   $ad_total_ingresos=$row["total_ingresos"];
		}
		$this->SQL->free_result($rs_data);
	 } 
	 return $lb_valido;   
   }//fin uf_scg_reporte_obtener_saldo_ingreso
//---------------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------------
   function  uf_scg_reporte_select_saldo_costos($adt_fecini,$adt_fecfin,$ai_costos,&$ad_total_costos) 
   {				 
	 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_scg_reporte_select_saldo_costos
	 //         Access :	private
	 //     Argumentos :    $adt_fecini  // fecha  desde 
	 //                     $adt_fecfin  // fecha hasta
     //              	    $ai_ingreso  // numero de la cuenta de ingraso 
	 //                     $ad_total_ingresos  //  total de ingreso (referencia)
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del Estado de Resultado  
	 //     Creado por :    Ing. Miguel Palencia/  Ing. Juniors Fraga
	 // Fecha Creacion :    04/07/2013          Fecha ltima Modificacion :      Hora : 04/07/2013
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT COALESCE(sum(curSaldo.SALDO),0) as total_costos ".
             " FROM   scg_cuentas SC,(SELECT sc_cuenta, codemp, COALESCE(sum(haber_mes-debe_mes),0) as saldo ".
             "                        FROM   scg_saldos ".
		     "                        WHERE  codemp='".$ls_codemp."' AND fecsal between '".$adt_fecini."' AND '".$adt_fecfin."' ".
		     "                        GROUP BY codemp, sc_cuenta) as curSaldo ".
             " WHERE (SC.sc_cuenta = curSaldo.sc_cuenta) AND (SC.codemp = curSaldo.codemp) AND (SC.status='C') AND ".
	         "       (SC.sc_cuenta like '".$ai_costos."%') ";
	 $rs_data=$this->SQL->select($ls_sql);
	 if($rs_data===false)
	 {// error interno sql
		$this->is_msg_error="Error en consulta metodo uf_scg_reporte_select_saldo_costos ".$this->fun->uf_convertirmsg($this->SQL->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->SQL->fetch_row($rs_data))
		{
		   $ad_total_costos=$row["total_costos"];
		}
		$this->SQL->free_result($rs_data);
	 } 
	 return $lb_valido;   
   }//fin uf_scg_reporte_obtener_saldo_ingreso
//---------------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------------
   function  uf_scg_reporte_select_saldo_gasto($adt_fecini,$adt_fecfin,$ai_gasto,&$ad_total_gastos) 
   {				 
	 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_scg_reporte_select_saldo_gasto
	 //         Access :	private
	 //     Argumentos :    $adt_fecini  // fecha  desde 
	 //                     $adt_fecfin  // fecha hasta
     //              	    $ai_gasto  // numero de la cuenta de gasto
	 //                     $ad_total_gastos  //  total de gastos (referencia)
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del Estado de Resultado 
	 //     Creado por :    Ing. Miguel Palencia/  Ing. Juniors Fraga
	 // Fecha Creacion :    24/04/2006          Fecha ltima Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT COALESCE(sum(curSaldo.SALDO),0) as total_gastos ".
             " FROM   scg_cuentas SC,(SELECT sc_cuenta, codemp, COALESCE(sum(haber_mes-debe_mes),0) as saldo ".
		     "                        FROM scg_saldos ".
		     "                        WHERE codemp='".$ls_codemp."' AND fecsal between '".$adt_fecini."' AND '".$adt_fecfin."' ".
		     "                        GROUP BY codemp, sc_cuenta) as curSaldo ".
             " WHERE (SC.sc_cuenta = curSaldo.sc_cuenta) AND (SC.codemp = curSaldo.codemp) AND ".
			 "       (SC.status='C') AND (SC.sc_cuenta like '".$ai_gasto."%') ";
	 $rs_data=$this->SQL->select($ls_sql);
	 if($rs_data===false)
	 {// error interno sql
		$this->is_msg_error="Error en consulta metodo uf_scg_reporte_select_saldo_gasto ".$this->fun->uf_convertirmsg($this->SQL->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->SQL->fetch_row($rs_data))
		{
		   $ad_total_gastos=$row["total_gastos"];
		}
		$this->SQL->free_result($rs_data);
	 } 
	 return $lb_valido;   
   }//fin uf_scg_reporte_obtener_saldo_gasto
//---------------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------------
   function  uf_scg_reporte_select_a(&$rs_agno) 
   {				 
	 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_scg_reporte_select_a�
	 //         Access :	private
	 //     Argumentos :    $rs_agno //result 
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del Estado de Resultado 
	 //     Creado por :    Ing. Miguel Palencia/  Ing. Juniors Fraga
	 // Fecha Creacion :    24/04/2006          Fecha ltima Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT distinct substring(fecsal,1,4) as anuales ".
             " FROM   scg_saldos ".
             " WHERE  codemp='".$ls_codemp."' ".
             " ORDER BY anuales DESC";
	 $rs_agno=$this->SQL->select($ls_sql);
	 if($rs_agno===false)
	 {// error interno sql
		$this->is_msg_error="Error en consulta metodo uf_scg_reporte_select_saldo_gasto ".$this->fun->uf_convertirmsg($this->SQL->message);
		$lb_valido = false;
	 }
	 return $lb_valido;   
  }//uf_scg_reporte_select_a�
//---------------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------------
   function  uf_scg_reporte_estado_de_resultado($adt_fecini,$adt_fecfin,$ai_nivel) 
   {				 
	 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_scg_reporte_estado_de_resultado
	 //         Access :	private
	 //     Argumentos :    $adt_fecini  // fecha  desde 
	 //                     $adt_fecini  // fecha  desde 
	 //                     $ai_nivel   //  nivel de la  cuenta
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del Estado de Resultado 
	 //     Creado por :    Ing. Miguel Palencia/  Ing. Juniors Fraga
	 // Fecha Creacion :    24/04/2006          Fecha ltima Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido = true;
	  $ls_codemp = $this->dts_empresa["codemp"];
	  $this->dts_reporte->resetds("sc_cuenta");
      $li_ingreso = $this->dts_empresa["ingreso"];
      $li_gasto = $this->dts_empresa["gasto"];
	  
	  $ls_sql=" SELECT SC.sc_cuenta, SC.status, SC.denominacion,  curSaldo.saldo, ".
	          "        cast(0 as UNSIGNED ) as nivel, cast(0 as UNSIGNED ) as total_ingresos, ".
              "        cast(0 as UNSIGNED ) as total_egresos ".
              " FROM   scg_cuentas SC, (SELECT sc_cuenta, codemp, sum(haber_mes-debe_mes) as saldo ".
		      "                         FROM   scg_saldos ".
		      "                         WHERE  codemp='".$ls_codemp."' AND fecsal between '".$adt_fecini."' AND '".$adt_fecfin."' ".
		      "                         GROUP BY sc_cuenta) as curSaldo ".
              " WHERE (SC.sc_cuenta = curSaldo.sc_cuenta) AND (SC.codemp=curSaldo.codemp) AND ".
			  "       (SC.sc_cuenta like '".$li_ingreso."%' OR SC.sc_cuenta like '".$li_gasto."%') AND ".
              "       (SC.nivel<='".$ai_nivel."') ".
              " ORDER BY SC.sc_cuenta ";
	 $rs_data=$this->SQL->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
			$this->is_msg_error="Error en consulta metodo uf_spg_reporte_comprobante_formato1 ".$this->fun->uf_convertirmsg($this->SQL->message);
			$lb_valido = false;
	 }
	 else
	 {
	    $ld_total_ingresos=0;
		$ld_total_egresos=0;
		$lb_valido=$this->uf_scg_reporte_select_saldo_ingreso($adt_fecini,$adt_fecfin,$li_ingreso,$ld_total_ingresos);
	    if($lb_valido)
	    {
		  $lb_valido=$this->uf_scg_reporte_select_saldo_gasto($adt_fecini,$adt_fecfin,$li_gasto,$ld_total_egresos);
	    }
	   if($lb_valido)
	   {
			while($row=$this->SQL->fetch_row($rs_data))
			{
				   $ls_sc_cuenta=$row["sc_cuenta"];
				   $ls_status=$row["status"];
				   $ls_denominacion=$row["denominacion"];
				   $ld_saldo=$row["saldo"];
			       $ls_nivel=$this->tepuy_int_scg->uf_scg_obtener_nivel($ls_sc_cuenta);
				   
				   $this->dts_reporte->insertRow("sc_cuenta",$ls_sc_cuenta);
				   $this->dts_reporte->insertRow("status",$ls_status);
				   $this->dts_reporte->insertRow("denominacion",$ls_denominacion);
				   $this->dts_reporte->insertRow("saldo",$ld_saldo);
				   $this->dts_reporte->insertRow("nivel",$ls_nivel);
				   $this->dts_reporte->insertRow("total_ingresos",$ld_total_ingresos);
				   $this->dts_reporte->insertRow("total_egresos",$ld_total_egresos);
				   $lb_valido = true;
			   }//if   
		}//while
		$this->SQL->free_result($rs_data);
	}//else	  
	return $lb_valido;
}//fin uf_scg_reporte_estado_de_resultado
//---------------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------------
	//////////////////////////////////////////////////////////////////
	//   CLASE REPORTES SCG  " BALANCE GENERAL   "                 // 
	////////////////////////////////////////////////////////////////
   function  uf_scg_reporte_balance_general($adt_feclimit,$ai_nivel) 
   {				 
	 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_scg_reporte_balance_general
	 //         Access :	private
	 //     Argumentos :    $adt_feclimit  // fecha  limite
     //              	    $ai_nivel  // nivel de la cuenta
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida del Balance General
	 //     Creado por :    Ing. Miguel Palencia/  Ing. Juniors Fraga
	 // Fecha Creacion :    03/05/2006          Fecha ltima Modificacion : 08/05/06     Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido = true;
	  $ls_codemp = $this->dts_empresa["codemp"];
	  $this->dts_egresos->resetds("sc_cuenta");
	  $this->dts_reporte->resetds("sc_cuenta");
	  $dts_Balance2=new class_datastore();
      $li_activo = $this->dts_empresa["activo"];
      $li_pasivo = $this->dts_empresa["pasivo"];
      $li_resultado = $this->dts_empresa["resultado"];
      $li_capital = $this->dts_empresa["capital"];
      $li_orden_d = $this->dts_empresa["orden_d"];
      $li_orden_h = $this->dts_empresa["orden_h"];
	  $li_ingreso = $this->dts_empresa["ingreso"];
      $li_gasto = $this->dts_empresa["gasto"];
      $li_c_resultad = $this->dts_empresa["c_resultad"];
	  
	  $ls_sql=" SELECT SC.sc_cuenta,SC.denominacion,SC.status,SC.nivel as rnivel, ".
              "        coalesce(curSaldo.T_Debe,0) as total_debe, ".
              "        coalesce(curSaldo.T_Haber,0) as total_haber,0 as nivel ".
              " FROM scg_cuentas SC LEFT OUTER JOIN (SELECT codemp,sc_cuenta, coalesce(sum(debe_mes),0)as T_Debe, ".
			  "                                             coalesce(sum(haber_mes),0) as T_Haber ".
              "                                      FROM   scg_saldos ".
              "                                      WHERE  codemp='".$ls_codemp."' AND fecsal<='".$adt_feclimit."' ".
              "                                      GROUP BY sc_cuenta) curSaldo ".
              " ON SC.sc_cuenta=curSaldo.sc_cuenta ".
              " WHERE SC.codemp=curSaldo.codemp AND  curSaldo.codemp='".$ls_codemp."' AND ".
			  "       (SC.sc_cuenta like '".$li_activo."%' OR SC.sc_cuenta like '".$li_pasivo."%' OR ".
			  "        SC.sc_cuenta like '".$li_resultado."%' OR  SC.sc_cuenta like '".$li_capital."%' OR ".
			  "        SC.sc_cuenta like '".$li_orden_d."%' OR SC.sc_cuenta like '".$li_orden_h."%') ".
              " ORDER BY  SC.sc_cuenta ";
	 $rs_data=$this->SQL->select($ls_sql);
	 if($rs_data===false)
	 {// error interno sql
		$this->is_msg_error="Error en consulta metodo uf_scg_reporte_balance_general ".$this->fun->uf_convertirmsg($this->SQL->message);
		$lb_valido = false;
	 }
	 else
	 {
        $ld_saldo_ganancia=0;
		while($row=$this->SQL->fetch_row($rs_data))
		{
		  $ls_sc_cuenta=$row["sc_cuenta"];
		  $ls_denominacion=$row["denominacion"];
		  $ls_status=$row["status"];
		  $ls_rnivel=$row["rnivel"];
		  $ld_total_debe=$row["total_debe"];
		  $ld_total_haber=$row["total_haber"];
		  if($ls_status=="C")
		  {
    		$ls_nivel="4";		
		  }//if
		  else
		  {
    		$ls_nivel=$ls_rnivel;		
		  }//else
		  if($ls_nivel<=$ai_nivel)
		  {
			  $this->dts_Prebalance->insertRow("sc_cuenta",$ls_sc_cuenta);
			  $this->dts_Prebalance->insertRow("denominacion",$ls_denominacion);
			  $this->dts_Prebalance->insertRow("status",$ls_status);
			  $this->dts_Prebalance->insertRow("nivel",$ls_nivel);
			  $this->dts_Prebalance->insertRow("rnivel",$ls_rnivel);
			  $this->dts_Prebalance->insertRow("total_debe",$ld_total_debe);
			  $this->dts_Prebalance->insertRow("total_haber",$ld_total_haber);
		      $lb_valido = true;
		  }//if
		}//while
	    $li=$this->dts_Prebalance->getRowCount("sc_cuenta");
		if($li==0)
		{
		  $lb_valido = false;
		  return false;
		}//if
	 } //else
	 $ld_saldo_i=0;
	 if($lb_valido)
	 {
	   $lb_valido=$this->uf_scg_reporte_select_saldo_ingreso_BG($adt_feclimit,$li_ingreso,$ld_saldo_i);
	 } 
     if($lb_valido)
	 {
       $ld_saldo_g=0;	 
	   $lb_valido=$this->uf_scg_reporte_select_saldo_gasto_BG($adt_feclimit,$li_gasto,$ld_saldo_g);  
	 }//if
	 if($lb_valido)
	 {
	   $ld_saldo_ganancia=$ld_saldo_ganancia+($ld_saldo_i+$ld_saldo_g);
	 }//if
	 $la_sc_cuenta=array();
	 $la_denominacion=array();
	 $la_saldo=array();
	 for($i=1;$i<=$ai_nivel;$i++)
	 {
		 $la_sc_cuenta[$i]="";
		 $la_denominacion[$i]="";
		 $la_saldo[$i]=0;
	 }//for
	 $li_nro_reg=0;
     $ld_saldo_resultado=0;
	 $li_row=$this->dts_Prebalance->getRowCount("sc_cuenta");
	 for($li_i=1;$li_i<=$li_row;$li_i++)
	 {
		  $ls_sc_cuenta=$this->dts_Prebalance->data["sc_cuenta"][$li_i];
		  $ls_status=$this->dts_Prebalance->data["status"][$li_i];
		  $ls_denominacion=$this->dts_Prebalance->data["denominacion"][$li_i];
		  $ls_rnivel=$this->dts_Prebalance->data["rnivel"][$li_i];
		  $ld_total_debe=$this->dts_Prebalance->data["total_debe"][$li_i];
		  $ld_total_haber=$this->dts_Prebalance->data["total_haber"][$li_i]; 
		  $ls_nivel=$this->dts_Prebalance->data["nivel"][$li_i]; 

		  $ls_tipo_cuenta=substr($ls_sc_cuenta,0,1);
		  if($ls_tipo_cuenta==$li_activo) {	$ls_orden="1"; }	
		  if($ls_tipo_cuenta==$li_pasivo) {	$ls_orden="2"; }	
		  if($ls_tipo_cuenta==$li_capital) { $ls_orden="3"; }	
		  if($ls_tipo_cuenta==$li_resultado) { $ls_orden="4"; }	
		  if($ls_tipo_cuenta==$li_orden_d) { $ls_orden="5"; }
		  if($ls_tipo_cuenta== $li_orden_h){ $ls_orden="6"; }
		 
		  $ld_saldo=abs($ld_total_debe-$ld_total_haber);
		  if((($ls_tipo_cuenta==$li_pasivo)||($ls_tipo_cuenta==$li_resultado)||($ls_tipo_cuenta==$li_capital))&&($ls_nivel==1))
		  {
			  $ld_saldo_resultado=$ld_saldo_resultado+$ld_saldo;
		  }//if
		  if($ls_nivel==4)
		  {
		    $li_nro_reg=$li_nro_reg+1; 
		    $this->dts_Balance1->insertRow("orden",$ls_orden);
		    $this->dts_Balance1->insertRow("num_reg",$li_nro_reg);
		    $this->dts_Balance1->insertRow("sc_cuenta",$ls_sc_cuenta);
		    $this->dts_Balance1->insertRow("denominacion",$ls_denominacion);
			$this->dts_Balance1->insertRow("nivel",$ls_nivel);
			$this->dts_Balance1->insertRow("saldo",$ld_saldo);
		  }//if
		  else
		  {
		    if (empty($la_sc_cuenta[$ls_nivel]))
			{
			   $la_sc_cuenta[$ls_nivel]=$ls_sc_cuenta;
			   $la_denominacion[$ls_nivel]=$ls_denominacion;
			   $la_saldo[$ls_nivel]=$ld_saldo;
		       $li_nro_reg=$li_nro_reg+1;
			   $this->dts_Balance1->insertRow("orden",$ls_orden);
			   $this->dts_Balance1->insertRow("num_reg",$li_nro_reg);
			   $this->dts_Balance1->insertRow("sc_cuenta",$ls_sc_cuenta);
			   $this->dts_Balance1->insertRow("denominacion",$ls_denominacion);
			   $this->dts_Balance1->insertRow("nivel",-$ls_nivel);
			   $this->dts_Balance1->insertRow("saldo",$ld_saldo);
			}//if
            else
			{
			   $this->uf_scg_reporte_calcular_total_BG($li_nro_reg,$ls_prev_nivel,$ls_nivel,$la_sc_cuenta,$la_denominacion,$la_saldo,$li_activo,$li_pasivo,$li_capital,$li_resultado,$li_orden_d,$li_orden_h); 
			   $la_sc_cuenta[$ls_nivel]=$ls_sc_cuenta;
			   $la_denominacion[$ls_nivel]=$ls_denominacion;
			   $la_saldo[$ls_nivel]=$ld_saldo;
		       $li_nro_reg=$li_nro_reg+1;
			   $this->dts_Balance1->insertRow("orden",$ls_orden);
			   $this->dts_Balance1->insertRow("num_reg",$li_nro_reg);
			   $this->dts_Balance1->insertRow("sc_cuenta",$ls_sc_cuenta);
			   $this->dts_Balance1->insertRow("denominacion",$ls_denominacion);
			   $this->dts_Balance1->insertRow("nivel",-$ls_nivel);
			   $this->dts_Balance1->insertRow("saldo",$ld_saldo);
			}//else 			
          $ls_prev_nivel=$ls_nivel;		 
		}//else
	 }//for
	 $this->uf_scg_reporte_actualizar_resultado_BG($li_c_resultad,abs($ld_saldo_ganancia),$li_nro_reg,$ls_orden); 
	 if($ld_saldo_ganancia>0)
	 {
	 	$ld_saldo_resultado=$ld_saldo_resultado-$ld_saldo_ganancia;
	 }
	 else
	 {
	 	$ld_saldo_resultado=$ld_saldo_resultado+abs($ld_saldo_ganancia);
	 }
	 $li_total=$this->dts_Balance1->getRowCount("sc_cuenta");
	 for($li_i=1;$li_i<=$li_total;$li_i++)
	 {	
		  $ls_sc_cuenta=$this->dts_Balance1->data["sc_cuenta"][$li_i];
		  $ls_orden=$this->dts_Balance1->data["orden"][$li_i];
		  $li_nro_reg=$this->dts_Balance1->data["num_reg"][$li_i];
		  $ls_denominacion=$this->dts_Balance1->data["denominacion"][$li_i];
		  $ls_nivel=$this->dts_Balance1->data["nivel"][$li_i];
		  $ld_saldo=$this->dts_Balance1->data["saldo"][$li_i];
		  $li_pos=$this->dts_Prebalance->find("sc_cuenta",$ls_sc_cuenta);
		  if($li_pos>0)
		  { 
		    $ls_rnivel=$this->dts_Prebalance->data["rnivel"][$li_pos];
		  }
		  else
		  {
		    $ls_rnivel=0;
		  }
	      $dts_Balance2->insertRow("orden",$ls_orden);
	      $dts_Balance2->insertRow("num_reg",$li_nro_reg);
	      $dts_Balance2->insertRow("sc_cuenta",$ls_sc_cuenta);
	      $dts_Balance2->insertRow("denominacion",$ls_denominacion);
	      $dts_Balance2->insertRow("nivel",$ls_nivel);
	      $dts_Balance2->insertRow("saldo",abs($ld_saldo));
	      $dts_Balance2->insertRow("rnivel",$ls_rnivel);


		  $dts_Balance2->insertRow("total",abs($ld_saldo_resultado));
	 }//for
	 $li_tot=$dts_Balance2->getRowCount("sc_cuenta");
	 for($li_i=1;$li_i<=$li_tot;$li_i++)
	 { 
		  $ls_sc_cuenta=$dts_Balance2->data["sc_cuenta"][$li_i];
		  $ls_orden=$dts_Balance2->data["orden"][$li_i];
		  $li_nro_reg=$dts_Balance2->data["num_reg"][$li_i];
		  $ls_denominacion=$dts_Balance2->data["denominacion"][$li_i];
		  $ls_nivel=$dts_Balance2->data["nivel"][$li_i];
		  $ld_saldo=$dts_Balance2->data["saldo"][$li_i];
		  $ls_rnivel=$dts_Balance2->data["rnivel"][$li_i];
		  $ld_saldo_resultado=$dts_Balance2->data["total"][$li_i];
		  if($ls_rnivel<=$ai_nivel)
		  {
			  $this->dts_reporte->insertRow("orden",$ls_orden);
			  $this->dts_reporte->insertRow("num_reg",$li_nro_reg);
			  $this->dts_reporte->insertRow("sc_cuenta",$ls_sc_cuenta);
			  $this->dts_reporte->insertRow("denominacion",$ls_denominacion);
			  $this->dts_reporte->insertRow("nivel",$ls_nivel);
			  $this->dts_reporte->insertRow("saldo",$ld_saldo);
			  $this->dts_reporte->insertRow("rnivel",$ls_rnivel);
			  $this->dts_reporte->insertRow("total",$ld_saldo_resultado);
		  }//if	  
	 }//for
     unset($this->dts_Prebalance);
     unset($this->dts_Balance1);
     unset($dts_Balance2);
	 return $lb_valido;   
   }//uf_scg_reporte_balance_general
//---------------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------------
   function  uf_scg_reporte_select_saldo_gasto_BG($adt_fecini,$ai_gasto,&$ad_saldo) 
   {				 
	 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_scg_reporte_select_saldo_gasto_BG
	 //         Access :	private
	 //     Argumentos :    $adt_fecini  // fecha  desde 
     //              	    $ai_gasto  // numero de la cuenta de gasto
	 //                     $ad_saldo  //  total saldo (referencia)
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del Estado de Resultado  
	 //     Creado por :    Ing. Miguel Palencia/  Ing. Juniors Fraga
	 // Fecha Creacion :    02/05/2006          Fecha ltima Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 
	 $ls_sql=" SELECT COALESCE(sum(SD.debe_mes-SD.haber_mes),0) as saldo ".
             " FROM   scg_cuentas SC, scg_saldos SD ".
             " WHERE (SC.sc_cuenta = SD.sc_cuenta) AND (SC.codemp = SD.codemp) AND (SC.status='C') AND ".
			 "        fecsal<='".$adt_fecini."' AND (SC.sc_cuenta like '".$ai_gasto."%') ";
	 $rs_data=$this->SQL->select($ls_sql);
	 if($rs_data===false)
	 {// error interno sql
		$this->is_msg_error="Error en consulta metodo uf_scg_reporte_select_saldo_gasto_BG ".$this->fun->uf_convertirmsg($this->SQL->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->SQL->fetch_row($rs_data))
		{
		   $ad_saldo=$row["saldo"];
		}
		$this->SQL->free_result($rs_data);
	 } 
	 return $lb_valido;   
   }//fin uf_scg_reporte_select_saldo_gasto_BG
//---------------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------------
   function  uf_scg_reporte_select_saldo_ingreso_BG($adt_fecini,$ai_ingreso,&$ad_saldo) 
   {				 
	 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_scg_reporte_select_saldo_ingreso_BG
	 //         Access :	private
	 //     Argumentos :    $adt_fecini  // fecha  desde 
     //              	    $ai_ingreso  // numero de la cuenta de ingraso 
	 //                     $ad_saldo  //  total saldo (referencia)
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del Estado de Resultado  
	 //     Creado por :    Ing. Miguel Palencia/  Ing. Juniors Fraga
	 // Fecha Creacion :    02/05/2006          Fecha ltima Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT COALESCE(sum(SD.debe_mes-SD.haber_mes),0) as saldo ".
             " FROM   scg_cuentas SC, scg_saldos SD ".
             " WHERE (SC.sc_cuenta = SD.sc_cuenta) AND (SC.codemp = SD.codemp) AND (SC.status='C') AND ".
			 "        fecsal<='".$adt_fecini."' AND (SC.sc_cuenta like '".$ai_ingreso."%') ";
	 $rs_data=$this->SQL->select($ls_sql);
	 if($rs_data===false)
	 {// error interno sql
		$this->is_msg_error="Error en consulta metodo uf_scg_reporte_select_saldo_ingreso_BG ".$this->fun->uf_convertirmsg($this->SQL->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->SQL->fetch_row($rs_data))
		{
		   $ad_saldo=$row["saldo"];
		}
		$this->SQL->free_result($rs_data);
	 } 
	 return $lb_valido;   
   }//fin uf_scg_reporte_obtener_saldo_ingreso
//---------------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------------
   function  uf_scg_reporte_calcular_total_BG(&$ai_nro_regi,$as_prev_nivel,$as_nivel,&$aa_sc_cuenta,$aa_denominacion,$aa_saldo,
                                              $ai_activo,$ai_pasivo,$ai_capital,$ai_resultado,$ai_orden_d,$ai_orden_h) 
   { //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_scg_reporte_calcular_total_BG
	 //         Access :	private
	 //     Argumentos :    $as_prev_nivel  // nivel de la cuenta anterior
     //              	    $as_nivel  // nivel de  la cuenta 
	 //                     $ai_nro_regi  //  numero de registro (referencia)
	 //                     $aa_sc_cuenta  // arreglo de cuentas (referencia)
	 //                     $aa_denominacion // arreglo de denominacion         
	 //                     $aa_saldo // arreglo de saldo         
     //	       Returns :	Retorna true o false si se realizo el calculo del total para el reporte
	 //	   Description :	Metodo que genera un monto total para la cuenta del balance general 
	 //     Creado por :    Ing. Miguel Palencia/  Ing. Juniors Fraga
	 // Fecha Creacion :    08/05/2006          Fecha ltima Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $i=$as_prev_nivel-1;
	 $x=$as_nivel-1;
	 if($i>$x)
	 {
		  $ls_tipo_cuenta=substr($aa_sc_cuenta[$i],0,1);
		  if($ls_tipo_cuenta==$ai_activo) {	$ls_orden="1"; }	
		  if($ls_tipo_cuenta==$ai_pasivo) {	$ls_orden="2"; }	
		  if($ls_tipo_cuenta==$ai_capital) { $ls_orden="3"; }	
		  if($ls_tipo_cuenta==$ai_resultado) { $ls_orden="4"; }	
		  if($ls_tipo_cuenta==$ai_orden_d) { $ls_orden="5"; }
		  if($ls_tipo_cuenta== $ai_orden_h){ $ls_orden="6"; }
		  else{$ls_orden="7";}
          if(!empty($aa_sc_cuenta[$i]))
		  {
	 	    $ai_nro_regi=$ai_nro_regi+1;
		    $this->dts_Balance1->insertRow("orden",$ls_orden);
		    $this->dts_Balance1->insertRow("num_reg",$ai_nro_regi);
		    $this->dts_Balance1->insertRow("sc_cuenta",$aa_sc_cuenta[$i]);
		    $this->dts_Balance1->insertRow("denominacion","Total ".$aa_denominacion[$i]);
		    $this->dts_Balance1->insertRow("nivel",$i);
		    $this->dts_Balance1->insertRow("saldo",$aa_saldo[$i]);
			$aa_sc_cuenta[$i]="";
			$i--;
		  }//if
	 }//if
    }//uf_scg_reporte_calcular_total_BG
//---------------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------------
   function  uf_scg_reporte_actualizar_resultado_BG($ai_c_resultad,$ad_saldo_ganancia,$ai_nro_reg,$as_orden) 
   {				 
	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_scg_reporte_actualizar_resultado_BG
	 //         Access :	private
	 //     Argumentos :    $ai_c_resultad  // cuenta de resultado
     //              	    $ad_saldo_ganancia  // saldo 
     //              	    $as_sc_cuenta  // cuenta
     //	       Returns :	Retorna true o false si se realizo el calculo para el reporte
	 //	   Description :	Metodo que genera un monto actualizado de la cuenta del resultado
	 //     Creado por :    Ing. Miguel Palencia/  Ing. Juniors Fraga
	 // Fecha Creacion :    08/05/2006          Fecha ltima Modificacion :      Hora :
  	 ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
     $ls_next_cuenta=$ai_c_resultad;
	 $ls_nivel=$this->tepuy_int_scg->uf_scg_obtener_nivel($ls_next_cuenta);
	 while($ls_nivel>=1)
	 {
		  $li_pos=$this->dts_Balance1->find("sc_cuenta",$ls_next_cuenta);
		  if($li_pos>0)
		  {
			  $ld_saldo=$this->dts_Balance1->getValue("saldo",$li_pos);
			  if($ad_saldo_ganancia>0)	
			  { 
			  	$ld_saldo=$ld_saldo-$ad_saldo_ganancia;
			  }
			  else
			  {
			   $ld_saldo=$ld_saldo+abs($ad_saldo_ganancia);
			  }
			  $this->dts_Balance1->updateRow("saldo",$ld_saldo,$li_pos);
		  }	 
		  else
		  {
                $lb_valido=$this->uf_select_denominacion($ls_next_cuenta,$ls_denominacion);			
			    if($lb_valido)
				{
                   $li_nro_reg=$ai_nro_reg+1;
				   $this->dts_Balance1->insertRow("orden",$as_orden);
				   $this->dts_Balance1->insertRow("num_reg",$li_nro_reg);
				   $this->dts_Balance1->insertRow("sc_cuenta",$ls_next_cuenta);
				   $this->dts_Balance1->insertRow("denominacion",$ls_denominacion);
				   $this->dts_Balance1->insertRow("nivel",$ls_nivel);
				   $this->dts_Balance1->insertRow("saldo",$ad_saldo_ganancia);
				}   
		  } 													
		  if($ls_nivel==1)
		  {
			 return;
		  }//if
		  $ls_next_cuenta=$this->tepuy_int_scg->uf_scg_next_cuenta_nivel($ls_next_cuenta);
		  $ls_nivel=$this->tepuy_int_scg->uf_scg_obtener_nivel($ls_next_cuenta);
	 }//while
   }//uf_scg_reporte_actualizar_resultado_BG
  //---------------------------------------------------------------------------------------------------------------------------------
	
  //---------------------------------------------------------------------------------------------------------------------------------
	function uf_select_denominacion($as_sc_cuenta,&$as_denominacion)
	{/////////////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_select_denominacion 
	//	     Arguments:  $as_sc_cuenta  // codigo de la cuenta
	//                   $as_denominacion  // denominacion de la cuenta (referencia)
	//	       Returns:	 retorna un arreglo con las cuentas inferiores  
	//	   Description:  Busca la denominacion de la cuenta
	//     Creado por :  Ing. Miguel Palencia/  Ing. Juniors Fraga
	// Fecha Creacion :  14/08/2006                      Fecha ltima Modificacion : 
	///////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
    $ls_codemp = $this->dts_empresa["codemp"];
	$ls_sql = "SELECT denominacion FROM scg_cuentas WHERE sc_cuenta='".$as_sc_cuenta."' AND codemp='".$ls_codemp."' ";
    $rs_data=$this->SQL->select($ls_sql);
	if($rs_data===false)
	{
	    $lb_valido=false;
		$this->is_msg_error="Error en consulta metodo uf_select_denominacion ".$this->fun->uf_convertirmsg($this->SQL->message);
	}
	else
	{
	   if($row=$this->SQL->fetch_row($rs_data))
	   {
	      $as_denominacion=$row["denominacion"];
	   }
	   $this->SQL->free_result($rs_data);
	}
    return  $lb_valido;
 }//uf_select_denominacion
//---------------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------------
	function uf_cargar_cabecera_mayor_analitico($ld_fecdesde,$ld_fechasta,$ls_cuenta_desde,$ls_cuenta_hasta,
	                                            $ls_orden,&$rs_analitico) 
	{  //////////////////////////////////////////////////////////////////////////////////////////////////////////
	   //	      Function :	uf_cargar_cabecera_mayor_analitico
	   //           Access :	private
	   //       Argumentos :    $ls_cuenta_desde  // cuenta desde 
	   //                       $ls_cuenta_hasta  // cuenta hasta
	   //                       $ld_fecdesde      // fecha desde
	   //                       $ld_fechasta      // fecha hasta
	   //                       $ls_orden         // orden  
	   //                       $rs_analitico     // resultset  referencia   
       //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	   //	   Description :	Devuelve el resulset de una consulta para el reporte 
	   //       Creado por :    Ing. Miguel Palencia. // Ing. Juniors Fraga
	   //   Fecha Creacion :    19/07/2006          Fecha ultima Modificacion :12/05/2008      Hora :
  	   //////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	   $ls_codemp = $this->dts_empresa["codemp"];
	   $li_row=0;
	   $ld_fecdesde=	$this->fun->uf_convertirdatetobd($ld_fecdesde);
  	   $ld_fechasta=	$this->fun->uf_convertirdatetobd($ld_fechasta);
	   $ls_sql=" SELECT distinct scg_dt_cmp.sc_cuenta,b.denominacion,     ".
			   "        (SELECT COALESCE(SUM(debe_mes-haber_mes),0)       ".
			   "    	 FROM   scg_saldos                                ".
			   "	     WHERE  scg_saldos.fecsal<'".$ld_fecdesde."' AND  ".
			   "                scg_dt_cmp.codemp=scg_saldos.codemp AND   ".
			   "	            scg_dt_cmp.sc_cuenta=scg_saldos.sc_cuenta) As saldo_ant	".
			   " FROM   scg_dt_cmp ".
			   "	    JOIN scg_cuentas b ON (scg_dt_cmp.sc_cuenta= b.sc_cuenta) ".
			   "        JOIN tepuy_cmp d ON (scg_dt_cmp.codemp=d.codemp) and     ".
			   "        (scg_dt_cmp.procede=d.procede and        ".
			   "        scg_dt_cmp.comprobante=d.comprobante)    ".
			   " WHERE  scg_dt_cmp.codemp = '".$ls_codemp."' AND ".
			   "        scg_dt_cmp.fecha between '".$ld_fecdesde."' AND '".$ld_fechasta."' AND ".
			   "	    scg_dt_cmp.sc_cuenta between '".$ls_cuenta_desde."' AND '".$ls_cuenta_hasta."' AND  ".
			   "        scg_dt_cmp.codemp=b.codemp AND       ".
			   "	    scg_dt_cmp.sc_cuenta=b.sc_cuenta AND ".
			   "        d.codemp=scg_dt_cmp.codemp AND       ".
			   "        d.procede=scg_dt_cmp.procede AND     ".
			   "        d.comprobante=scg_dt_cmp.comprobante AND ".
			   "        d.fecha=scg_dt_cmp.fecha".
			   " ORDER BY scg_dt_cmp.sc_cuenta";
	   $rs_analitico=$this->SQL->select($ls_sql);
	   if (($rs_analitico===false))
	   {
			$lb_valido=false;
			$this->is_msg_error="Error en consulta metodo uf_cargar_mayor_analitico ".$this->fun->uf_convertirmsg($this->SQL->message);
       }
	   return $lb_valido;         
	}//fin de uf_cargar_mayor analitico
	//---------------------------------------------------------------------------------------------------------------------------------
	
	//---------------------------------------------------------------------------------------------------------------------------------
	function uf_cargar_mayor_detalle_analitico($ld_fecdesde,$ld_fechasta,$ls_cuenta_desde,$ls_cuenta_hasta,$ls_orden,&$rs_analitico) 
	{  //////////////////////////////////////////////////////////////////////////////////////////////////////////
	   //	      Function :	uf_cargar_mayor_detalle_analitico
	   //           Access :	private
	   //       Argumentos :    $ls_cuenta_desde  // cuenta desde 
	   //                       $ls_cuenta_hasta  // cuenta hasta
	   //                       $ld_fecdesde      // fecha desde
	   //                       $ld_fechasta      // fecha hasta
	   //                       $ls_orden         // orden  
	   //                       $rs_analitico     // resultset  referencia   
       //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	   //	   Description :	Devuelve el resulset de una consulta para el reporte 
	   //       Creado por :    Ing. Miguel Palencia. // Ing. Juniors Fraga
	   //   Fecha Creacion :    19/07/2006          Fecha ultima Modificacion :12/05/2008      Hora :
  	   //////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido = true;
	   $ls_codemp = $this->dts_empresa["codemp"];
	   $li_row = 0;
	   $ld_fecdesde = $this->fun->uf_convertirdatetobd($ld_fecdesde);
  	   $ld_fechasta = $this->fun->uf_convertirdatetobd($ld_fechasta);
       $ls_sql="SELECT scg_dt_cmp.sc_cuenta, scg_dt_cmp.procede, scg_dt_cmp.comprobante, ".
               "       scg_dt_cmp.procede_doc, scg_dt_cmp.documento,scg_dt_cmp.fecha, ".
               "       scg_dt_cmp.debhab,scg_dt_cmp.descripcion, scg_dt_cmp.monto, scg_dt_cmp.orden, ".
               "       b.denominacion,d.descripcion as des_comp, d.cod_pro, d.ced_bene, ".
               " (SELECT nompro      ".
			   "  FROM  rpc_proveedor ".
			   "  WHERE rpc_proveedor.codemp = d.codemp AND           ".
			   "        rpc_proveedor.cod_pro =d.cod_pro ) AS nompro, ".
               " (SELECT nombene ".
			   "  FROM  rpc_beneficiario ".
			   "  WHERE rpc_beneficiario.codemp = d.codemp AND ".
			   "        rpc_beneficiario.ced_bene = d.ced_bene ) AS nombene, ".
               " (SELECT apebene ".
			   "  FROM  rpc_beneficiario ".
			   "  WHERE rpc_beneficiario.codemp = d.codemp AND ".
	 		   "        rpc_beneficiario.ced_bene = d.ced_bene ) AS apebene, ".
               " (SELECT COALESCE(SUM(debe_mes-haber_mes),0) ".
			   "  FROM  scg_saldos ".
			   "  WHERE scg_saldos.fecsal<'".$ld_fecdesde."' AND ".	
			   "        scg_dt_cmp.codemp=scg_saldos.codemp  AND ".
			   "        scg_dt_cmp.sc_cuenta=scg_saldos.sc_cuenta) As saldo_ant ". 
			   " FROM  scg_dt_cmp ".
			   " JOIN  scg_cuentas b ON (scg_dt_cmp.sc_cuenta= b.sc_cuenta) ".
			   " JOIN  tepuy_cmp  d ON (scg_dt_cmp.codemp=d.codemp AND  ".
			   "                         scg_dt_cmp.procede=d.procede AND  ".
			   "       					 scg_dt_cmp.comprobante=d.comprobante AND 
			                             scg_dt_cmp.fecha=d.fecha AND
		                                 scg_dt_cmp.codban=d.codban AND 
										 scg_dt_cmp.ctaban=d.ctaban) ".
			   " WHERE scg_dt_cmp.codemp = '".$ls_codemp."' AND ".
               "       scg_dt_cmp.fecha between '".$ld_fecdesde."' AND '".$ld_fechasta."' AND ".
       		   "       scg_dt_cmp.sc_cuenta between '".$ls_cuenta_desde."' AND '".$ls_cuenta_hasta."' AND ".
     		   "       scg_dt_cmp.codemp=b.codemp  AND ".
      		   "       scg_dt_cmp.sc_cuenta=b.sc_cuenta  AND ".
      		   "       d.codemp=scg_dt_cmp.codemp  AND ".
      		   "       d.procede=scg_dt_cmp.procede  AND ".
      		   "       d.comprobante=scg_dt_cmp.comprobante  AND ".
     		   "       d.fecha=scg_dt_cmp.fecha ".
			   " ORDER BY scg_dt_cmp.sc_cuenta,".$ls_orden;
	   $rs_analitico=$this->SQL->select($ls_sql);
	   if (($rs_analitico===false))
	   {
			$lb_valido=false;
			$this->is_msg_error="Error en consulta metodo uf_cargar_mayor_analitico ".$this->fun->uf_convertirmsg($this->SQL->message);
       }
	   return $lb_valido;         
	}//fin de uf_cargar_mayor analitico
	//---------------------------------------------------------------------------------------------------------------------------------


	//---------------------------------------------------------------------------------------------------------------------------------
	/////////////////////////////////////////////////////////////////
	//   CLASE REPORTES SCG  "LIBRO DIARIO LEGAL"   // 
	/////////////////////////////////////////////////////////////////
	function uf_scg_reporte_libro_diario_legal($as_cuenta_desde,$as_cuenta_hasta,$ad_fecdesde,$ad_fechasta,$ai_nivel)
	{  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   //	      Function :	uf_scg_reporte_libro_diario_legal
	   //           Access :	private
	   //       Argumentos :    $as_cuenta_desde  // cuenta desde 
	   //                       $as_cuenta_hasta  // cuenta hasta
	   //                       $ad_fecdesde      // fecha desde
	   //                       $ad_fechasta      // fecha hasta
	   //                       $ai_nivel         // nivel de busqueda de la cuenta  
       //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	   //	   Description :	Devuelve el resulset de una consulta para el reporte 
	   //       Creado por :    Ing. Miguel Palencia. // Ing. Juniors Fraga
	   //   Fecha Creacion :    06/06/2017
  	   ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido = true;	 
	    $ls_codemp = $this->dts_empresa["codemp"];
		$ld_fecdesde=$this->fun->uf_convertirdatetobd($ad_fecdesde);
		$ld_fechasta=$this->fun->uf_convertirdatetobd($ad_fechasta);
		$ls_sql= "SELECT scg_cuentas.sc_cuenta, MAX(scg_cuentas.denominacion) AS denominacion, ".
				 "       COALESCE(SUM(scg_saldos.debe_mes),0) as debe_mes, COALESCE(SUM(scg_saldos.haber_mes),0) as haber_mes,  ".
				 "       COALESCE(0,0) as debe_mes_ant , COALESCE(0,0) as haber_mes_ant,  ".
				 "       COALESCE(0,0) as total_debe , COALESCE(0,0) as total_haber  ".
				 "  FROM scg_cuentas ".
				 "  LEFT OUTER JOIN scg_saldos  ".
				 "    ON scg_saldos.fecsal BETWEEN '".$ld_fecdesde."' AND '".$ld_fechasta."' ".
				 "   AND scg_cuentas.codemp = scg_saldos.codemp ".
				 "   AND scg_cuentas.sc_cuenta = scg_saldos.sc_cuenta ".				   
				 " WHERE scg_cuentas.codemp='".$ls_codemp."' ".
				 "   AND scg_cuentas.sc_cuenta BETWEEN '".$as_cuenta_desde."' AND '".$as_cuenta_hasta."' ".
				 "   AND scg_cuentas.nivel<=".$ai_nivel."".
				 " GROUP BY scg_cuentas.sc_cuenta  ".
				 "UNION ".
				 "SELECT scg_cuentas.sc_cuenta, MAX(scg_cuentas.denominacion) AS denominacion, ".
				 "       COALESCE(0,0) as debe_mes, COALESCE(0,0) as haber_mes, ".
				 "       COALESCE(SUM(scg_saldos.debe_mes),0) as debe_mes_ant ,COALESCE(SUM(scg_saldos.haber_mes),0) as haber_mes_ant, ".
				 "       COALESCE(0,0) as total_debe , COALESCE(0,0) as total_haber  ".
				 "  FROM scg_cuentas, scg_saldos ".
				 " WHERE scg_cuentas.codemp='".$ls_codemp."' ".
				 "   AND scg_cuentas.sc_cuenta BETWEEN '".$as_cuenta_desde."' AND '".$as_cuenta_hasta."' ".
				 "   AND scg_saldos.fecsal<'".$ld_fecdesde."'". 
				 "   AND scg_cuentas.nivel<=".$ai_nivel."".
				 "   AND scg_cuentas.codemp = scg_saldos.codemp ".
				 "   AND scg_cuentas.sc_cuenta = scg_saldos.sc_cuenta ".
				 " GROUP BY scg_cuentas.sc_cuenta  ".
				 "UNION ".
				 "SELECT scg_cuentas.sc_cuenta, MAX(scg_cuentas.denominacion) AS denominacion, ".
				 "       COALESCE(0,0) as debe_mes, COALESCE(0,0) as haber_mes,  ".
				 "       COALESCE(0,0) as debe_mes_ant , COALESCE(0,0) as haber_mes_ant,  ".
				 "       COALESCE(SUM(scg_saldos.debe_mes),0) as total_debe , COALESCE(SUM(scg_saldos.haber_mes),0) as total_haber  ".
				 "  FROM scg_cuentas, scg_saldos  ".
				 " WHERE scg_cuentas.codemp='".$ls_codemp."' ".
				 "   AND scg_cuentas.sc_cuenta BETWEEN '".$as_cuenta_desde."' AND '".$as_cuenta_hasta."' ".
				 "   AND scg_saldos.fecsal BETWEEN '".$ld_fecdesde."' AND '".$ld_fechasta."' ".
				 "   AND scg_cuentas.nivel=1".
				 "   AND scg_cuentas.codemp = scg_saldos.codemp ".
				 "   AND scg_cuentas.sc_cuenta = scg_saldos.sc_cuenta ".
				 " GROUP BY scg_cuentas.sc_cuenta  ".
                 " ORDER BY sc_cuenta ";
		//print $ls_sql; die();
		$rs_balance=$this->SQL->select($ls_sql);
		if($rs_balance===false)

		{   // error interno sql
		   $this->io_msg->message("Error en Reporte".$this->fun->uf_convertirmsg($this->SQL->message));
		   //print $this->SQL->message;
            $lb_valido = false;	 
		}
		else
		{
   		   if($row=$this->SQL->fetch_row($rs_balance))
		   {
			  $this->dts_reporte->data=$this->SQL->obtener_datos($rs_balance);
			  $this->dts_reporte->group_by(array('0'=>'sc_cuenta'),array('0'=>'debe_mes','1'=>'haber_mes','2'=>'debe_mes_ant',
				                                                         '3'=>'haber_mes_ant','4'=>'total_debe','5'=>'total_haber'),'debe_mes');
           }
		   else
		   {
              $lb_valido = false;	 
		   }
	       $this->SQL->free_result($rs_balance);   
		}
        return $lb_valido;
	}//fin libro diario legal
//---------------------------------------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------------------------------------------
	/////////////////////////////////////////////////////////////////
	//   CLASE REPORTES SCG  "LIBRO MAYOR FORMATO 2017"   // 
	/////////////////////////////////////////////////////////////////
	function uf_scg_reporte_libro_mayor_2017($as_cuenta_desde,$as_cuenta_hasta,$ad_fecdesde,$ad_fechasta,$ai_nivel,$as_cmbmesdes)
	{  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   //	      Function :	uf_scg_reporte_libro_mayor_2017
	   //           Access :	private
	   //       Argumentos :    $as_cuenta_desde  // cuenta desde 
	   //                       $as_cuenta_hasta  // cuenta hasta
	   //                       $ad_fecdesde      // fecha desde
	   //                       $ad_fechasta      // fecha hasta
	   //                       $ai_nivel         // nivel de busqueda de la cuenta  
	   //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	   //	   Description :	Devuelve el resulset de una consulta para el reporte 
	   //       Creado por :    Ing. Miguel Palencia.
	   //   Fecha Creacion :    19/06/2017
  	   ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido = true;	 
	$ls_codemp = $this->dts_empresa["codemp"];
	$ld_fecdesde=$this->fun->uf_convertirdatetobd($ad_fecdesde);
	$ld_fechasta=$this->fun->uf_convertirdatetobd($ad_fechasta);
		$ls_sql= "SELECT scg_cuentas.sc_cuenta, MAX(scg_cuentas.denominacion) AS denominacion, ".
				 "       COALESCE(SUM(scg_saldos.debe_mes),0) as debe_mes, COALESCE(SUM(scg_saldos.haber_mes),0) as haber_mes,  ".
				 "       COALESCE(0,0) as debe_mes_ant , COALESCE(0,0) as haber_mes_ant,  ".
				 "       COALESCE(0,0) as total_debe , COALESCE(0,0) as total_haber  ".
				 "  FROM scg_cuentas ".
				 "  LEFT OUTER JOIN scg_saldos  ".
				 "    ON scg_saldos.fecsal BETWEEN '".$ld_fecdesde."' AND '".$ld_fechasta."' ".
				 "   AND scg_cuentas.codemp = scg_saldos.codemp ".
				 "   AND scg_cuentas.sc_cuenta = scg_saldos.sc_cuenta ".				   
				 " WHERE scg_cuentas.codemp='".$ls_codemp."' ".
				 "   AND scg_cuentas.sc_cuenta BETWEEN '".$as_cuenta_desde."' AND '".$as_cuenta_hasta."' ".
				 "   AND scg_cuentas.nivel<=".$ai_nivel."".
				 " GROUP BY scg_cuentas.sc_cuenta  ".
				 "UNION ".
				 "SELECT scg_cuentas.sc_cuenta, MAX(scg_cuentas.denominacion) AS denominacion, ".
				 "       COALESCE(0,0) as debe_mes, COALESCE(0,0) as haber_mes, ".
				 "       COALESCE(SUM(scg_saldos.debe_mes),0) as debe_mes_ant ,COALESCE(SUM(scg_saldos.haber_mes),0) as haber_mes_ant, ".
				 "       COALESCE(0,0) as total_debe , COALESCE(0,0) as total_haber  ".
				 "  FROM scg_cuentas, scg_saldos ".
				 " WHERE scg_cuentas.codemp='".$ls_codemp."' ".
				 "   AND scg_cuentas.sc_cuenta BETWEEN '".$as_cuenta_desde."' AND '".$as_cuenta_hasta."' ".
				 "   AND scg_saldos.fecsal<'".$ld_fecdesde."'". 
				 "   AND scg_cuentas.nivel<=".$ai_nivel."".
				 "   AND scg_cuentas.codemp = scg_saldos.codemp ".
				 "   AND scg_cuentas.sc_cuenta = scg_saldos.sc_cuenta ".
				 " GROUP BY scg_cuentas.sc_cuenta  ".
				 "UNION ".
				 "SELECT scg_cuentas.sc_cuenta, MAX(scg_cuentas.denominacion) AS denominacion, ".
				 "       COALESCE(0,0) as debe_mes, COALESCE(0,0) as haber_mes,  ".
				 "       COALESCE(0,0) as debe_mes_ant , COALESCE(0,0) as haber_mes_ant,  ".
				 "       COALESCE(SUM(scg_saldos.debe_mes),0) as total_debe , COALESCE(SUM(scg_saldos.haber_mes),0) as total_haber  ".
				 "  FROM scg_cuentas, scg_saldos  ".
				 " WHERE scg_cuentas.codemp='".$ls_codemp."' ".
				 "   AND scg_cuentas.sc_cuenta BETWEEN '".$as_cuenta_desde."' AND '".$as_cuenta_hasta."' ".
				 "   AND scg_saldos.fecsal BETWEEN '".$ld_fecdesde."' AND '".$ld_fechasta."' ".
				 "   AND scg_cuentas.nivel=1".
				 "   AND scg_cuentas.codemp = scg_saldos.codemp ".
				 "   AND scg_cuentas.sc_cuenta = scg_saldos.sc_cuenta ".
				 " GROUP BY scg_cuentas.sc_cuenta  ".
                 " ORDER BY sc_cuenta ";
		//print $ls_sql; die();
		$rs_balance=$this->SQL->select($ls_sql);
		if($rs_balance===false)
		{   // error interno sql
			$this->io_msg->message("Error en Reporte".$this->fun->uf_convertirmsg($this->SQL->message));
			//print $this->SQL->message;
			$lb_valido = false;	 
		}
		else
		{
   			if($row=$this->SQL->fetch_row($rs_balance))
			{
				$this->dts_reporte->data=$this->SQL->obtener_datos($rs_balance);
				$this->dts_reporte->group_by(array('0'=>'sc_cuenta'),array('0'=>'debe_mes','1'=>'haber_mes','2'=>'debe_mes_ant',
				                                                         '3'=>'haber_mes_ant','4'=>'total_debe','5'=>'total_haber'),'debe_mes');
			}
			else
			{
				$lb_valido = false;	 
			}
			$this->SQL->free_result($rs_balance);   
		}
        return $lb_valido;
	}//fin libro mayor 2017
//---------------------------------------------------------------------------------------------------------------------------------
	
	//---------------------------------------------------------------------------------------------------------------------------------
	/////////////////////////////////////////////////////////////////
	//   CLASE REPORTES SCG  "BALANCE DE COMPROBACION FORMATO 1"   // 
	/////////////////////////////////////////////////////////////////
	function uf_scg_reporte_balance_comprobante($as_cuenta_desde,$as_cuenta_hasta,$ad_fecdesde,$ad_fechasta,$ai_nivel)
	{  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   //	      Function :	uf_scg_reporte_balance_comprobante
	   //           Access :	private
	   //       Argumentos :    $as_cuenta_desde  // cuenta desde 
	   //                       $as_cuenta_hasta  // cuenta hasta
	   //                       $ad_fecdesde      // fecha desde
	   //                       $ad_fechasta      // fecha hasta
	   //                       $ai_nivel         // nivel de busqueda de la cuenta  
       //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	   //	   Description :	Devuelve el resulset de una consulta para el reporte 
	   //       Creado por :    Ing. Miguel Palencia. // Ing. Juniors Fraga
	   //   Fecha Creacion :    19/07/2006          Fecha ultima Modificacion :12/05/2008      Hora :
  	   ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido = true;	 
	    $ls_codemp = $this->dts_empresa["codemp"];
		$ld_fecdesde=$this->fun->uf_convertirdatetobd($ad_fecdesde);
		$ld_fechasta=$this->fun->uf_convertirdatetobd($ad_fechasta);
		$ls_sql= "SELECT scg_cuentas.sc_cuenta, MAX(scg_cuentas.denominacion) AS denominacion, ".
				 "       COALESCE(SUM(scg_saldos.debe_mes),0) as debe_mes, COALESCE(SUM(scg_saldos.haber_mes),0) as haber_mes,  ".
				 "       COALESCE(0,0) as debe_mes_ant , COALESCE(0,0) as haber_mes_ant,  ".
				 "       COALESCE(0,0) as total_debe , COALESCE(0,0) as total_haber  ".
				 "  FROM scg_cuentas ".
				 "  LEFT OUTER JOIN scg_saldos  ".
				 "    ON scg_saldos.fecsal BETWEEN '".$ld_fecdesde."' AND '".$ld_fechasta."' ".
				 "   AND scg_cuentas.codemp = scg_saldos.codemp ".
				 "   AND scg_cuentas.sc_cuenta = scg_saldos.sc_cuenta ".				   
				 " WHERE scg_cuentas.codemp='".$ls_codemp."' ".
				 "   AND scg_cuentas.sc_cuenta BETWEEN '".$as_cuenta_desde."' AND '".$as_cuenta_hasta."' ".
				 "   AND scg_cuentas.nivel<=".$ai_nivel."".
				 " GROUP BY scg_cuentas.sc_cuenta  ".
				 "UNION ".
				 "SELECT scg_cuentas.sc_cuenta, MAX(scg_cuentas.denominacion) AS denominacion, ".
				 "       COALESCE(0,0) as debe_mes, COALESCE(0,0) as haber_mes, ".
				 "       COALESCE(SUM(scg_saldos.debe_mes),0) as debe_mes_ant ,COALESCE(SUM(scg_saldos.haber_mes),0) as haber_mes_ant, ".
				 "       COALESCE(0,0) as total_debe , COALESCE(0,0) as total_haber  ".
				 "  FROM scg_cuentas, scg_saldos ".
				 " WHERE scg_cuentas.codemp='".$ls_codemp."' ".
				 "   AND scg_cuentas.sc_cuenta BETWEEN '".$as_cuenta_desde."' AND '".$as_cuenta_hasta."' ".
				 "   AND scg_saldos.fecsal<'".$ld_fecdesde."'". 
				 "   AND scg_cuentas.nivel<=".$ai_nivel."".
				 "   AND scg_cuentas.codemp = scg_saldos.codemp ".
				 "   AND scg_cuentas.sc_cuenta = scg_saldos.sc_cuenta ".
				 " GROUP BY scg_cuentas.sc_cuenta  ".
				 "UNION ".
				 "SELECT scg_cuentas.sc_cuenta, MAX(scg_cuentas.denominacion) AS denominacion, ".
				 "       COALESCE(0,0) as debe_mes, COALESCE(0,0) as haber_mes,  ".
				 "       COALESCE(0,0) as debe_mes_ant , COALESCE(0,0) as haber_mes_ant,  ".
				 "       COALESCE(SUM(scg_saldos.debe_mes),0) as total_debe , COALESCE(SUM(scg_saldos.haber_mes),0) as total_haber  ".
				 "  FROM scg_cuentas, scg_saldos  ".
				 " WHERE scg_cuentas.codemp='".$ls_codemp."' ".
				 "   AND scg_cuentas.sc_cuenta BETWEEN '".$as_cuenta_desde."' AND '".$as_cuenta_hasta."' ".
				 "   AND scg_saldos.fecsal BETWEEN '".$ld_fecdesde."' AND '".$ld_fechasta."' ".
				 "   AND scg_cuentas.nivel=1".
				 "   AND scg_cuentas.codemp = scg_saldos.codemp ".
				 "   AND scg_cuentas.sc_cuenta = scg_saldos.sc_cuenta ".
				 " GROUP BY scg_cuentas.sc_cuenta  ".
                 " ORDER BY sc_cuenta ";
		//print $ls_sql; die();
		$rs_balance=$this->SQL->select($ls_sql);
		if($rs_balance===false)
		{   // error interno sql
		   $this->io_msg->message("Error en Reporte".$this->fun->uf_convertirmsg($this->SQL->message));
		   //print $this->SQL->message;
            $lb_valido = false;	 
		}
		else
		{
   		   if($row=$this->SQL->fetch_row($rs_balance))
		   {
			  $this->dts_reporte->data=$this->SQL->obtener_datos($rs_balance);
			  $this->dts_reporte->group_by(array('0'=>'sc_cuenta'),array('0'=>'debe_mes','1'=>'haber_mes','2'=>'debe_mes_ant',
				                                                         '3'=>'haber_mes_ant','4'=>'total_debe','5'=>'total_haber'),'debe_mes');
           }
		   else
		   {
              $lb_valido = false;	 
		   }
	       $this->SQL->free_result($rs_balance);   
		}
        return $lb_valido;
	}//fin balance comprobacion
//---------------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------------
    function uf_spg_reporte_select_cuenta(&$as_sc_cuenta_min,&$as_sc_cuenta_max)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_cuenta
	 //         Access :	private
	 //     Argumentos :    $as_spg_cuenta  // cuenta minima (referencias)
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve la cuenta minima de la tabla spg_cuentas
	 //     Creado por :    Ing. Miguel Palencia/  Ing. Juniors Fraga
	 // Fecha Creacion :    19/07/2006          Fecha ltima Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT min(sc_cuenta) as sc_cuenta_min, max(sc_cuenta) as sc_cuenta_max ".
             " FROM scg_cuentas ".
             " WHERE codemp = '".$ls_codemp."'  AND status='C' ";
	 $rs_data=$this->SQL->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_cuenta ".$this->fun->uf_convertirmsg($this->SQL->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->SQL->fetch_row($rs_data))
		{
		   $as_sc_cuenta_min=$row["sc_cuenta_min"];
		   $as_sc_cuenta_max=$row["sc_cuenta_max"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->SQL->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_min_cuenta
//---------------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------------
   function uf_spg_reporte_select_cuenta_min_max(&$as_sc_cuenta_min,&$as_sc_cuenta_max)
   { //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_cuenta_min_max
	 //         Access :	private
	 //     Argumentos :    $as_spg_cuenta  // cuenta minima (referencias)
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve la cuenta minima de la tabla spg_cuentas
	 //     Creado por :    Ing. Miguel Palencia.
	 // Fecha Creacion :    20/07/2006          Fecha ltima Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT min(sc_cuenta) as sc_cuenta_min, max(sc_cuenta) as sc_cuenta_max ".
             " FROM scg_cuentas ".
             " WHERE codemp = '".$ls_codemp."' ";
	//print $ls_sql; die();
	 $rs_data=$this->SQL->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_cuenta_min_max ".$this->fun->uf_convertirmsg($this->SQL->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->SQL->fetch_row($rs_data))
		{
		   $as_sc_cuenta_min=$row["sc_cuenta_min"];
		   $as_sc_cuenta_max=$row["sc_cuenta_max"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->SQL->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_cuenta_min_max
//---------------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------------
	function uf_scg_reporte_movimientos_mes($as_cuenta_desde,$as_cuenta_hasta,$ad_fecdesde,$ad_fechasta,$as_orden=1)
	{    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 //	      Function :	uf_scg_reporte_balance_comprobante
		 //         Access :	public
		 //     Argumentos :    adt_fecini  // fecha  desde 
		 //              	    adt_fecfin  // fecha hasta 
		 //                     ai_nivel    // nivel de la cuenta  
		 //	       Returns :	Retorna un Boleano y genera un datastore con datos preparado
		 //	   Description :	Reporte que genera el balance de comprobanciona una detewrminada fecha y cuenta
		 //     Creado por :    Ing. Miguel Palencia // Ing.Juniors Fraga 
		 // Fecha Creación :    04/02/2006          Fecha última Modificacion : 12/05/2008 Hora :
		 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        	$lb_existe = false;	 
		if($as_orden==0)
		{
			$ls_orden=" ORDER BY sc_cuenta DESC";
		}
		else
		{
			$ls_orden=" ORDER BY sc_cuenta ASC";
		}
		$ls_codemp = $this->dts_empresa["codemp"];
		$ld_fecdesde=$this->fun->uf_convertirdatetobd($ad_fecdesde);
		$ld_fechasta=$this->fun->uf_convertirdatetobd($ad_fechasta);
		
	    $ls_sql="select COALESCE(SUM(scg_saldos.debe_mes),0) as debe_mes, COALESCE(SUM(scg_saldos.haber_mes),0) as haber_mes,". 
                "       COALESCE(0,0) as debe_mes_ant , COALESCE(0,0) as haber_mes_ant, COALESCE(0,0) as total_debe, ". 
                "       COALESCE(0,0) as total_haber, ".
                "       c.sc_cuenta, MAX(c.denominacion) AS denominacion ".
                "  from scg_saldos ".
                "  join scg_cuentas c on (scg_saldos.sc_cuenta=c.sc_cuenta) and c.codemp='".$ls_codemp."' ".
				"  where scg_saldos.sc_cuenta BETWEEN '".$as_cuenta_desde."' AND '".$as_cuenta_hasta."' ".
                "  AND scg_saldos.fecsal BETWEEN '".$ld_fecdesde."' AND '".$ld_fechasta."' ".
            	"  and scg_saldos.codemp='".$ls_codemp."' ".
                "  and scg_saldos.codemp=c.codemp ".
				"  GROUP BY scg_saldos.sc_cuenta,c.sc_cuenta ".
				"     UNION  ".
				"select COALESCE(0,0) as debe_mes, COALESCE(0,0) as haber_mes, ". 
                "       COALESCE(SUM(scg_saldos.debe_mes),0) as debe_mes_ant, ".
                "       COALESCE(SUM(scg_saldos.haber_mes),0) as haber_mes_ant, COALESCE(0,0) as total_debe, ". 
                "       COALESCE(0,0) as total_haber, ".
                "       c.sc_cuenta, MAX(c.denominacion) AS denominacion ".
				"  from scg_saldos ".
				"  join scg_cuentas c on (scg_saldos.sc_cuenta=c.sc_cuenta)  and c.codemp='".$ls_codemp."' ".
				"  where scg_saldos.sc_cuenta BETWEEN '".$as_cuenta_desde."' AND '".$as_cuenta_hasta."' ".
                "  AND scg_saldos.fecsal<'".$ld_fecdesde."'". 
            	"  AND scg_saldos.codemp='".$ls_codemp."' ".
           		"  and  scg_saldos.codemp=c.codemp ".
				"  GROUP BY scg_saldos.sc_cuenta,c.sc_cuenta ".
				" 		UNION  ".
				"select COALESCE(0,0) as debe_mes, COALESCE(0,0) as haber_mes, ".
                "       COALESCE(0,0) as debe_mes_ant , COALESCE(0,0) as haber_mes_ant, ".
                "       COALESCE(SUM(scg_saldos.debe_mes),0) as total_debe, ".
                " 	  COALESCE(SUM(scg_saldos.haber_mes),0) as total_haber, ".
                "       MAX(c.sc_cuenta) AS sc_cuenta, MAX(c.denominacion) AS denominacion ".
				"  from scg_saldos ".
				"  join scg_cuentas c on (scg_saldos.sc_cuenta=c.sc_cuenta)  and c.codemp='".$ls_codemp."' ".
				"  WHERE scg_saldos.codemp='".$ls_codemp."' ".
				"  AND scg_saldos.sc_cuenta BETWEEN '".$as_cuenta_desde."' AND '".$as_cuenta_hasta."' ".
				"  AND scg_saldos.fecsal BETWEEN '".$ld_fecdesde."' AND '".$ld_fechasta."' ".
				"  AND c.status='C' ".
				"  GROUP BY scg_saldos.codemp, c.codemp ".
				$ls_orden;
		//print $ls_sql;die();
		$rs_balance=$this->SQL->select($ls_sql);
		if($rs_balance===false)
		{   // error interno sql
		   $this->io_msg->message("Error en Reporte".$this->fun->uf_convertirmsg($this->SQL->message));
           return false;
		}
		else
		{
   		   if($row=$this->SQL->fetch_row($rs_balance))
		   {
				$this->dts_reporte->data=$this->SQL->obtener_datos($rs_balance);
				$this->dts_reporte->group_by(array('0'=>'sc_cuenta'),array('0'=>'debe_mes','1'=>'haber_mes','2'=>'debe_mes_ant',
				                                                           '3'=>'haber_mes_ant','4'=>'total_debe','5'=>'total_haber'),'debe_mes');
           }
	       $this->SQL->free_result($rs_balance);   
		}
        return true;
	}//fin movimientos del mes
//---------------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------------
	function uf_scg_reporte_libro_mayor($as_cuenta_desde,$as_cuenta_hasta,$ad_fecdesde,$ad_fechasta,$as_orden=1)
	{    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 //	      Function :	uf_scg_reporte_balance_comprobante
		 //         Access :	public
		 //     Argumentos :    adt_fecini  // fecha  desde 
		 //              	    adt_fecfin  // fecha hasta 
		 //                     ai_nivel    // nivel de la cuenta  
		 //	       Returns :	Retorna un Boleano y genera un datastore con datos preparado
		 //	   Description :	Reporte que genera el balance de comprobanciona una detewrminada fecha y cuenta
		 //     Creado por :    Ing. Miguel Palencia
		 // Fecha Creación :    13/02/2017
		 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        	$lb_existe = false;	 
		if($as_orden==0)
		{
			$ls_orden=" ORDER BY sc_cuenta DESC";
		}
		else
		{
			$ls_orden=" ORDER BY sc_cuenta ASC";
		}
		$ls_codemp = $this->dts_empresa["codemp"];
		$ld_fecdesde=$this->fun->uf_convertirdatetobd($ad_fecdesde);
		$ld_fechasta=$this->fun->uf_convertirdatetobd($ad_fechasta);
		
	    $ls_sql="select COALESCE(SUM(scg_saldos.debe_mes),0) as debe_mes, COALESCE(SUM(scg_saldos.haber_mes),0) as haber_mes,". 
                "       COALESCE(0,0) as debe_mes_ant , COALESCE(0,0) as haber_mes_ant, COALESCE(0,0) as total_debe, ". 
                "       COALESCE(0,0) as total_haber, ".
                "       c.sc_cuenta, MAX(c.denominacion) AS denominacion ".
                "  from scg_saldos ".
                "  join scg_cuentas c on (scg_saldos.sc_cuenta=c.sc_cuenta) and c.codemp='".$ls_codemp."' ".
				"  where scg_saldos.sc_cuenta BETWEEN '".$as_cuenta_desde."' AND '".$as_cuenta_hasta."' ".
                "  AND scg_saldos.fecsal BETWEEN '".$ld_fecdesde."' AND '".$ld_fechasta."' ".
            	"  and scg_saldos.codemp='".$ls_codemp."' ".
                "  and scg_saldos.codemp=c.codemp ".
				"  GROUP BY scg_saldos.sc_cuenta,c.sc_cuenta ".
				"     UNION  ".
				"select COALESCE(0,0) as debe_mes, COALESCE(0,0) as haber_mes, ". 
                "       COALESCE(SUM(scg_saldos.debe_mes),0) as debe_mes_ant, ".
                "       COALESCE(SUM(scg_saldos.haber_mes),0) as haber_mes_ant, COALESCE(0,0) as total_debe, ". 
                "       COALESCE(0,0) as total_haber, ".
                "       c.sc_cuenta, MAX(c.denominacion) AS denominacion ".
				"  from scg_saldos ".
				"  join scg_cuentas c on (scg_saldos.sc_cuenta=c.sc_cuenta)  and c.codemp='".$ls_codemp."' ".
				"  where scg_saldos.sc_cuenta BETWEEN '".$as_cuenta_desde."' AND '".$as_cuenta_hasta."' ".
                "  AND scg_saldos.fecsal<'".$ld_fecdesde."'". 
            	"  AND scg_saldos.codemp='".$ls_codemp."' ".
           		"  and  scg_saldos.codemp=c.codemp ".
				"  GROUP BY scg_saldos.sc_cuenta,c.sc_cuenta ".
				" 		UNION  ".
				"select COALESCE(0,0) as debe_mes, COALESCE(0,0) as haber_mes, ".
                "       COALESCE(0,0) as debe_mes_ant , COALESCE(0,0) as haber_mes_ant, ".
                "       COALESCE(SUM(scg_saldos.debe_mes),0) as total_debe, ".
                " 	  COALESCE(SUM(scg_saldos.haber_mes),0) as total_haber, ".
                "       MAX(c.sc_cuenta) AS sc_cuenta, MAX(c.denominacion) AS denominacion ".
				"  from scg_saldos ".
				"  join scg_cuentas c on (scg_saldos.sc_cuenta=c.sc_cuenta)  and c.codemp='".$ls_codemp."' ".
				"  WHERE scg_saldos.codemp='".$ls_codemp."' ".
				"  AND scg_saldos.sc_cuenta BETWEEN '".$as_cuenta_desde."' AND '".$as_cuenta_hasta."' ".
				"  AND scg_saldos.fecsal BETWEEN '".$ld_fecdesde."' AND '".$ld_fechasta."' ".
				"  AND c.status='C' ".
				"  GROUP BY scg_saldos.codemp, c.codemp ".
				$ls_orden;
		//print $ls_sql;die();
		$rs_balance=$this->SQL->select($ls_sql);
		if($rs_balance===false)
		{   // error interno sql
		   $this->io_msg->message("Error en Reporte".$this->fun->uf_convertirmsg($this->SQL->message));
           return false;
		}
		else
		{
   		   if($row=$this->SQL->fetch_row($rs_balance))
		   {
				$this->dts_reporte->data=$this->SQL->obtener_datos($rs_balance);
				$this->dts_reporte->group_by(array('0'=>'sc_cuenta'),array('0'=>'debe_mes','1'=>'haber_mes','2'=>'debe_mes_ant',
				                                                           '3'=>'haber_mes_ant','4'=>'total_debe','5'=>'total_haber'),'debe_mes');
           }
	       $this->SQL->free_result($rs_balance);   
		}
        return true;
	}//fin de libro mayor
//---------------------------------------------------------------------------------------------------------------------------------



//---------------------------------------------------------------------------------------------------------------------------------
    function uf_spg_reporte_select_cuenta_contable($as_desde,$as_hasta)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_cuenta_contable
	 //         Access :	private
	 //     Argumentos :    $as_spg_cuenta  // cuenta minima (referencias)
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve la cuenta minima de la tabla spg_cuentas
	 //     Creado por :    Ing. Miguel Palencia.
	 // Fecha Creacion :    20/07/2006          Fecha ltima Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
 	 if((!empty($as_desde))&&(!empty($as_hasta)))
	 {
	 	$ls_aux=" AND sc_cuenta between '".$as_desde."' AND '".$as_hasta."'";
	 }
	 $ls_sql="SELECT distinct(sc_cuenta),denominacion ".
	 		 "  FROM scg_cuentas ".
			 " WHERE codemp='".$ls_codemp."'".
			 $ls_aux.
			 " ORDER BY sc_cuenta ";
	 $rs_data=$this->SQL->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_cuenta_contable ".$this->fun->uf_convertirmsg($this->SQL->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->SQL->fetch_row($rs_data))
		{
			$this->dts_reporte->data=$this->SQL->obtener_datos($rs_data);
		}
		else
		{
		   $lb_valido = false;
		}
		$this->SQL->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_cuenta_contable
//---------------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------------
function uf_cargar_listado_cuentas_contables($as_cuenta_desde,$as_cuenta_hasta,&$rs_data) 
{  //////////////////////////////////////////////////////////////////////////////////////////////////////////
   //	      Function :	uf_cargar_listado_cuentas_contables
   //           Access :	private
   //       Argumentos :    $as_cuenta_desde  // cuenta desde 
   //                       $as_cuenta_hasta  // cuenta hasta
   //                       $rs_data     // resultset  referencia   
   //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
   //	   Description :	Devuelve el resulset de una consulta para el reporte 
   //       Creado por :    Ing. Miguel Palencia. // Ing. Juniors Fraga
   //   Fecha Creacion :    13/05/2008           Fecha ultima Modificacion :      Hora :
   //////////////////////////////////////////////////////////////////////////////////////////////////////
   $lb_valido = true;
   $ls_codemp = $this->dts_empresa["codemp"];
   $ls_aux="";
   if((!empty($as_cuenta_desde))&&(!empty($as_cuenta_hasta)))
   {
		$ls_aux=" AND sc_cuenta between '".$as_cuenta_desde."' AND '".$as_cuenta_hasta."'";
   }
   $ls_sql=" SELECT distinct(sc_cuenta),denominacion ".
	   	   " FROM scg_cuentas ".
		   " WHERE codemp='".$ls_codemp."'".$ls_aux." ".
		   " ORDER BY sc_cuenta ";
   $rs_data=$this->SQL->select($ls_sql);
   if (($rs_data===false))
   {
		$lb_valido=false;
		$this->is_msg_error="Error en consulta metodo uf_cargar_listado_cuentas_contables ".$this->fun->uf_convertirmsg($this->SQL->message);
   }
   return $lb_valido;         
}//fin de uf_cargar_mayor analitico
//---------------------------------------------------------------------------------------------------------------------------------
}//Fin de la Clase...
?>
