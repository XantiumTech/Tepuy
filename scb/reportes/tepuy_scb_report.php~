<?php
class tepuy_scb_report
{
	function tepuy_scb_report($conn)
	{
	  require_once("../../shared/class_folder/class_funciones.php");
  	  require_once("../../shared/class_folder/class_sql.php");
  	  require_once("../../shared/class_folder/class_mensajes.php");
  	  require_once("../../shared/class_folder/class_datastore.php");
	  $this->fun = new class_funciones();
	  $this->SQL= new class_sql($conn);
	  $this->SQL_aux= new class_sql($conn);
	  $this->io_msg= new class_mensajes();		
	  $this->dat_emp=$_SESSION["la_empresa"];
	  $this->ds_disponibilidad=new class_datastore();
	  $this->ds_documentos=new class_datastore();
	}
	function uf_cargar_chq_voucher($ls_numdoc,$ls_voucher,$ls_codban,$ls_ctaban,$ls_codope)
	{
	   		
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		if($ls_voucher=="")
		{
		   $ls_sql="SELECT * 
				 FROM scb_movbco 
				 WHERE codemp='".$ls_codemp."' AND numdoc='".$ls_numdoc."' 
				 AND (chevau='".$ls_voucher."' OR chevau=' ' OR chevau='000000000000000000000000')AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' 
				 AND estmov<>'A' AND estmov<>'O' AND codope='".$ls_codope."'";
		}
	    else
	    {
			$ls_sql="SELECT * 
				 FROM scb_movbco 
				 WHERE codemp='".$ls_codemp."' AND numdoc='".$ls_numdoc."' 
				 AND chevau='".$ls_voucher."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' 
				 AND estmov<>'A' AND estmov<>'O' AND codope='".$ls_codope."'";
		}
//print $ls_sql;
		$rs_data=$this->SQL->select($ls_sql);
		if($rs_data===false)
		{
			$data=array();
			$this->is_msg_error="Error al cargar cheque voucher, ".$this->fun->uf_convertirmsg($this->SQL->message);
		}
		else
		{
			if($row=$this->SQL->fetch_row($rs_data)){$data=$this->SQL->obtener_datos($rs_data);	}
			else{$data=array();}
			$this->SQL->free_result($rs_data);
		}
		
		return $data;
	}

//CARGA NOMBRE DE USUARIO
function uf_cargar_usuario($as_codusu)
	{
	   		
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		if($as_codusu=="")
		{
			$nombre_usuario="";
		}
	    else
	    {
			$ls_sql="SELECT * 
				 FROM sss_usuarios 
				 WHERE codemp='".$ls_codemp."' AND codusu='".$as_codusu."'"; 
		}
		//print $ls_sql;
		$rs_data1=$this->SQL->select($ls_sql);
		if($rs_data1===false)
		{
			$data1=array();
			$this->is_msg_error="Error al cargar Usuario, ".$this->fun->uf_convertirmsg($this->SQL->message);
		}
		else
		{
			if($row=$this->SQL->fetch_row($rs_data1)){$data=$this->SQL->obtener_datos($rs_data1);	}
			else{$data1=array();}
			$this->SQL->free_result($rs_data1);
			$datos->data=$data;
			$ls_nombre = $datos->data["nomusu"][1];
			$ls_apellido = $datos->data["apeusu"][1];
			$nombre_usuario=$ls_nombre." ".$ls_apellido;
		}
		
		return $nombre_usuario;
	}


	function uf_cargar_chq_voucher_anulado($as_numdoc,$as_voucher,$as_codban,$as_ctaban,$as_codope)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_cargar_chq_voucher_anulado
		//         Access: public  
		//	    Arguments: as_numdoc    // Numero de Documento
		//                 as_voucher   // Numero de Voucher
		//                 as_codban    // Codigo de Banco
		//                 as_ctaban    // Cuenta de Banco
		//                 as_codope    // Codigo de Operacion
		//	      Returns: La data obtenida en el select.
		//    Description: Funcion que busca la informacion necesaria para imprimir un cheque Anulado.
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 26/03/2008									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		if($as_voucher=="")
		{
			$ls_sql="SELECT scb_movbco.numdoc,scb_movbco.fecmov,scb_movbco.codban,scb_movbco.monto,scb_movbco.nomproben,".
					"		scb_movbco.conanu,scb_movbco.fechaanula,scb_movbco.chevau,scb_movbco.monret".
					"  FROM scb_movbco".
					" WHERE codemp='".$ls_codemp."'".
					"   AND numdoc='".$as_numdoc."'".
					"   AND (chevau='".$as_voucher."' OR chevau=' ')".
					"   AND codban='".$as_codban."'".
					"   AND ctaban='".$as_ctaban."'".
					"   AND estmov='A'".
					"   AND codope='".$as_codope."'";
		}
	    else
	    {
			$ls_sql="SELECT scb_movbco.numdoc,scb_movbco.fecmov,scb_movbco.codban,scb_movbco.monto,scb_movbco.nomproben,".
					"		scb_movbco.conanu,scb_movbco.fechaanula,scb_movbco.chevau,scb_movbco.monret".
					"  FROM scb_movbco".
					" WHERE codemp='".$ls_codemp."'".
					"   AND numdoc='".$as_numdoc."'".
					"   AND chevau='".$as_voucher."'".
					"   AND codban='".$as_codban."'".
					"   AND ctaban='".$as_ctaban."'".
					"   AND estmov='A'".
					"   AND codope='".$as_codope."'";
		}
		$rs_data=$this->SQL->select($ls_sql);
		if($rs_data===false)
		{
			$data=array();
			$this->is_msg_error="Error al cargar cheque voucher, ".$this->fun->uf_convertirmsg($this->SQL->message);
		}
		else
		{
			if($row=$this->SQL->fetch_row($rs_data)){$data=$this->SQL->obtener_datos($rs_data);	}
			else{$data=array();}
			$this->SQL->free_result($rs_data);
		}
		
		return $data;
	}
	
	function uf_actualizar_status_impreso($ls_numdoc,$ls_voucher,$ls_codban,$ls_ctaban,$ls_codope)
	{
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$ls_sql="UPDATE scb_movbco SET estimpche=1 
				 WHERE codemp='".$ls_codemp."' AND numdoc='".$ls_numdoc."' AND chevau='".$ls_voucher."' AND codban='".$ls_codban."' 
				 AND ctaban='".$ls_ctaban."' AND codope='".$ls_codope."'";
		$li_result=$this->SQL->execute($ls_sql);		
		if($li_result===false)
		{
			$this->is_msg_error="Error al actualizar status de impresión, ".$this->fun->uf_convertirmsg($this->SQL->message);
			return false;
		}
		else
		{
			return true;
		}	
	}
	
	function uf_select_solicitudes($ls_numdoc,$ls_codban,$ls_ctaban)
	{
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$ls_solicitudes="";	$i=0;
		$ls_sql="SELECT * 
				 FROM cxp_sol_banco 
				 WHERE codemp='".$ls_codemp."' AND numdoc='".$ls_numdoc."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."'";
	
		$rs_data=$this->SQL->select($ls_sql);		
		if($rs_data===false)
		{
			$this->is_msg_error="Error al cargar cheque voucher, ".$this->fun->uf_convertirmsg($this->SQL->message);
			print $this->SQL->message;
		}
		else
		{
			while($row=$this->SQL->fetch_row($rs_data))
			{
				$i=$i+1;
				if($i==1){$ls_solicitudes=$row["numsol"];}
				else{$ls_solicitudes=$ls_solicitudes."-".$row["numsol"];}				
			}
			$this->SQL->free_result($rs_data);
		}
		return $ls_solicitudes;
	}
	function uf_select_solicitudes_con_enter($ls_numdoc,$ls_codban,$ls_ctaban)
	{
		//*Igual que la anterior pero coloca un \n entre las solicitudes*/
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$ls_solicitudes="";	$i=0;
		$ls_sql="SELECT * 
				 FROM cxp_sol_banco 
				 WHERE codemp='".$ls_codemp."' AND numdoc='".$ls_numdoc."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."'";
	
		$rs_data=$this->SQL->select($ls_sql);		
		if($rs_data===false)
		{
			$this->is_msg_error="Error al cargar cheque voucher, ".$this->fun->uf_convertirmsg($this->SQL->message);
			print $this->SQL->message;
		}
		else
		{
			while($row=$this->SQL->fetch_row($rs_data))
			{
				$i=$i+1;
				if($i==1){$ls_solicitudes=$row["numsol"];}
				else{$ls_solicitudes=$ls_solicitudes."-"."\n".$row["numsol"];}				
			}
			$this->SQL->free_result($rs_data);
		}
		return $ls_solicitudes;
	}
	
	function uf_select_data($SQL,$ls_cadena,$ls_campo)
	{
		$data=$SQL->select($ls_cadena);		
		if($row=$SQL->fetch_row($data)){	$ls_result=$row[$ls_campo];}	
		else{$ls_result="";	}
		$SQL->free_result($data);
		return $ls_result;
	}
	
	function uf_select_rowdata($SQL,$ls_cadena)
	{
		$data=$SQL->select($ls_cadena);		
		if($row=$SQL->fetch_row($data)){	$la_result=$row;}	
		else{$la_result=array();	}
		$SQL->free_result($data);
		return $la_result;
	}

	function uf_cargar_dt_scg($as_numdoc,$as_codban,$as_ctaban,$as_codope,$as_tipodestino,$as_haypartida)
	{
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$dt_scg=array();
		$y=0;
		
		if(!empty($as_codope)){$ls_cad=" AND a.codope='".$as_codope."'";}
		else{$ls_cad="";}
		if($as_haypartida==0){
			$filtro="' AND a.numrecdoc=e.numrecdoc ";
			$ls_sql="SELECT   a.scg_cuenta,a.debhab,a.monto,a.desmov,b.denominacion as denominacion,a.codded as codded
				 FROM     scb_movbco_scg a,scg_cuentas b
				 WHERE    a.codemp='".$ls_codemp ."' AND a.numdoc ='".$as_numdoc."' and a.codban='".$as_codban."' and a.ctaban='".$as_ctaban."' AND a.scg_cuenta=b.sc_cuenta ".$ls_cad."	
				 ORDER BY a.debhab asc,a.scg_cuenta asc ";
		}
		else
		{
			$filtro="' AND a.numrecdoc=e.numrecdoc AND d.numdoc='".$as_numdoc."' AND e.numsol=d.numsol ";
			if($as_tipodestino=="B"){$proveben=" AND a.ced_bene=c.ced_bene ";}else{$proveben=" AND a.cod_pro=c.cod_pro ";}
		$ls_sql="SELECT  a.sc_cuenta as scg_cuenta,a.debhab as debhab,a.monto as monto,b.denominacion as denominacion
				 FROM     cxp_rd_scg a,scg_cuentas b,scb_movbco c,cxp_sol_banco d,cxp_dt_solicitudes e
				 WHERE    a.codemp='".$ls_codemp."' AND c.numdoc ='".$as_numdoc.$filtro.$proveben." AND c.codban='".$as_codban."' AND c.ctaban='".$as_ctaban."' AND a.sc_cuenta=b.sc_cuenta "."	
				 ORDER BY a.debhab asc,a.sc_cuenta asc ";
		}

/*		//PRUEBA
		$filtro="' AND a.numrecdoc=e.numrecdoc AND d.numdoc='".$ls_numdoc."' AND e.numsol=d.numsol ";
		$ls_codemp="0001";
		
		$sql="SELECT  a.sc_cuenta as scg_cuenta,a.debhab as debhab,a.monto as monto,b.denominacion as denominacion
				 FROM     cxp_rd_scg a,scg_cuentas b,scb_movbco c,cxp_sol_banco d,cxp_dt_solicitudes e";
		$sql1="		 WHERE    a.codemp='".$ls_codemp."' AND c.numdoc ='".$ls_numdoc.$filtro;
		$sql2="' AND c.codban='".$ls_codban."' and c.ctaban='".	$ls_ctaban."' AND a.sc_cuenta=b.sc_cuenta "." ORDER BY a.debhab asc,a.sc_cuenta asc ";
//*/


		/*$ls_sql=	"SELECT   a.sc_cuenta as scg_cuenta,a.debhab,a.monto,b.denominacion as denominacion
				 FROM     cxp_rd_scg a,scg_cuentas b,scb_movbco c, cxp_sol_banco d, cxp_dt_solicitudes e
				WHERE    a.codemp="0001" AND a.numrecdoc=e.numrecdoc AND e.numsol=d.numsol AND d.numdoc=c.numdoc AND c.numdoc ="."'000000008937315'". "and c.codban="002" and c.ctaban="."'0000001750416950070953600'"." AND a.sc_cuenta=b.sc_cuenta and c.numdoc =d.numdoc and d.numsol=e.numsol
				 ORDER BY a.debhab asc,a.sc_cuenta asc";*/



		
		//print $ls_sql;

		$rs_scg=$this->SQL->select($ls_sql);		
		if(($rs_scg===false))
		{
			$lb_valido=false;		
		}
		else
		{
			while($row=$this->SQL->fetch_row($rs_scg))
			{
				$y=$y+1;
				$dt_scg["scg_cuenta"][$y]=$row["scg_cuenta"];
				$dt_scg["denominacion"][$y]=$row["denominacion"];
				$dt_scg["debhab"][$y]=$row["debhab"];
				$dt_scg["monto"][$y]=$row["monto"];
				//$dt_scg["desmov"][$y]=$row["desmov"];			
				//$dt_scg["codded"][$y]=$row["codded"];			
			}			
			$this->SQL->free_result($rs_scg);
		}
		return $dt_scg;
	
	}
	
	function uf_cargar_dt_spg($as_numdoc,$as_codban,$as_ctaban,$as_codope)
	{
		$ls_estmodest=$_SESSION["la_empresa"]["estmodest"];
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$ls_gestor = $_SESSION["ls_gestor"];
		$ls_cadena=""	;
		$dt_spg=array();
		$y=0;
		if(!empty($as_codope)){$ls_cad=" AND codope='".$as_codope."'";}
		else{$ls_cad="";}
		if ($ls_gestor=="MYSQL")
			$ls_cadena="CONCAT(b.codestpro1,b.codestpro2,b.codestpro3,b.codestpro4,b.codestpro5)";
		elseif($ls_gestor=="POSTGRE")
			$ls_cadena="(b.codestpro1 || b.codestpro2 || b.codestpro3 || b.codestpro4 || b.codestpro5)";
		$ls_sql="SELECT   a.desmov,a.codestpro as codestpro,a.spg_cuenta as spg_cuenta,a.monto as monto,b.denominacion as denominacion
				 FROM     scb_movbco_spg a,spg_cuentas b
				 WHERE    a.codemp='".$ls_codemp ."' AND a.numdoc ='".$as_numdoc."' 
				 and a.codban='".$as_codban."' and a.ctaban='".$as_ctaban."' 
				 AND a.spg_cuenta=b.spg_cuenta 
				 AND a.codestpro=$ls_cadena
				  ".$ls_cad."	
				 ORDER BY a.codestpro,a.spg_cuenta asc";
		//print $ls_sql;die();
		$rs_spg=$this->SQL->select($ls_sql);

		if(($rs_spg===false))
		{
			$lb_valido=false;
			print "Ocurrio error uf_cargar_dt_spg"		;
		}
		else
		{
			while($row=$this->SQL->fetch_row($rs_spg))
			{
				$y=$y+1;
				//$dt_spg["spg_cuenta"][$y]=$row["spg_cuenta"];
			// ESTA MODIFICACION ME PERMITE VISUALIZAR MEJOR EL CODIGO PRESUPUESTARIO //
				$ls_spg_cuenta=$row["spg_cuenta"];
				$ls_spg_anterior=$ls_spg_cuenta;
				if(substr($ls_spg_anterior,9,4)=="0000")
				{
				$ls_spg_cuenta=substr($ls_spg_cuenta,0,9);
				}
				if(substr($ls_spg_cuenta,7,2)=="00")
				{
				$ls_spg_anterior=$ls_spg_cuenta;
				$ls_spg_cuenta=substr($ls_spg_cuenta,0,7);
				}
				if(substr($ls_spg_cuenta,5,2)=="00")
				{
				$ls_spg_cuenta=substr($ls_spg_cuenta,0,5);
				}
				if(substr($ls_spg_cuenta,3,2)=="00")
				{
				$ls_spg_cuenta=substr($ls_spg_cuenta,0,3);
				}
			// ELIMINANDO LOS CODIGOS CUANDO SEAN 00 //
		// PERMITE AGREGARLE PUNTOS A LA CUENTA PRESUPUESTARIA //
				$hasta=strlen($ls_spg_cuenta);
				//$ls_spg_cuenta=$ls_spg_cuenta;
				if($hasta==13)
				{
					$ls_spg_cuenta1=substr($ls_spg_cuenta,0,3).".".substr($ls_spg_cuenta,3,2).".".substr($ls_spg_cuenta,5,2).".".substr($ls_spg_cuenta,7,2).".".substr($ls_spg_cuenta,9,4);
				}
				if($hasta==9)
				{
					$ls_spg_cuenta1=substr($ls_spg_cuenta,0,3).".".substr($ls_spg_cuenta,3,2).".".substr($ls_spg_cuenta,5,2).".".substr($ls_spg_cuenta,7,2);
				}
				if($hasta==7)
				{
					$ls_spg_cuenta1=substr($ls_spg_cuenta,0,3).".".substr($ls_spg_cuenta,3,2).".".substr($ls_spg_cuenta,5,2);
				}
				if($hasta==5)
				{
					$ls_spg_cuenta1=substr($ls_spg_cuenta,0,3).".".substr($ls_spg_cuenta,3,2);
				}
				if($hasta==3)
				{
					$ls_spg_cuenta1="<b>".substr($ls_spg_cuenta,0,3)."</b>";
					$ls_denominacion="<b>".$ls_denominacion."</b>";
					//$ld_asignado="<b>".$ld_asignado."</b>";
					//$ld_disponible="<b>".$ld_disponible."</b>";
				}
				$ls_spg_cuenta=$ls_spg_cuenta1;
		// AGREGA . A LA CTA. PRESUPUESTARIA//
				$dt_spg["spg_cuenta"][$y]=$ls_spg_cuenta;
				$ls_programatica=$row["codestpro"];
				$ls_codestpro1=substr(substr($ls_programatica,0,20),-2);
				$ls_codestpro2=substr(substr($ls_programatica,20,6),-2);
				$ls_codestpro3=substr(substr($ls_programatica,26,3),-2);
				if($ls_estmodest==2)
				{
					$ls_codestpro4=substr($ls_programatica,29,2);	
					$ls_codestpro5=substr($ls_programatica,31,2);
					$ls_programatica=$ls_codestpro1."-".$ls_codestpro2."-".$ls_codestpro3."-".$ls_codestpro4."-".$ls_codestpro5;
				}
				else
				{
					$ls_programatica=$ls_codestpro1."-".$ls_codestpro2."-".$ls_codestpro3;
					//$ls_programatica=substr($row["codestpro"],0,20)."-".substr($row["codestpro"],20,6)."-".substr($row["codestpro"],26,3);//substr($ls_programatica,0,29);
				}
				$dt_spg["estpro"][$y]=$ls_programatica;
				$dt_spg["denominacion"][$y]=$row["denominacion"];				
				$dt_spg["monto"][$y]=$row["monto"];	
				$dt_spg["desmov"][$y]=$row["desmov"];				
			}
					
			$this->SQL->free_result($rs_spg);
		}
		return $dt_spg;
	
	}

	function uf_cargar_dt_spgop($as_numdoc,$as_codban,$as_ctaban,$as_codope)
	{
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$dt_spg=array();
		$y=0;
		if(!empty($as_codope)){$ls_cad=" AND codope='".$as_codope."'";}
		else{$ls_cad="";}
		$ls_sql="SELECT   a.codestpro as codestpro,a.spg_cuenta as spg_cuenta,a.monto as monto,b.denominacion as denominacion,a.coduniadm as coduniadm
				 FROM     scb_movbco_spgop a,spg_cuentas b
				 WHERE    a.codemp='".$ls_codemp ."' AND a.numdoc ='".$as_numdoc."' and a.codban='".$as_codban."' and a.ctaban='".$as_ctaban."' AND a.spg_cuenta=b.spg_cuenta AND a.codestpro=CONCAT(b.codestpro1,b.codestpro2,b.codestpro3,b.codestpro4,b.codestpro5) ".$ls_cad."	
				 ORDER BY a.codestpro,a.spg_cuenta asc";
		$rs_spg=$this->SQL->select($ls_sql);	

		if(($rs_spg===false))
		{
			$lb_valido=false;		
		}
		else
		{
			while($row=$this->SQL->fetch_row($rs_spg))
			{
				$y=$y+1;
				$dt_spg["spg_cuenta"][$y]=$row["spg_cuenta"];
				$dt_spg["estpro"][$y]=substr($row["codestpro"],0,20).substr($row["codestpro"],20,6).substr($row["codestpro"],26,3);
				$dt_spg["denominacion"][$y]=$row["denominacion"];
				$dt_spg["coduniadm"][$y]=$row["coduniadm"];
				$dt_spg["monto"][$y]=$row["monto"];				
			}			
			$this->SQL->free_result($rs_spg);
		}
		return $dt_spg;
	}


	function uf_generar_estado_cuenta($ls_codemp,$ls_codban,$ls_ctaban,$ls_orden,$ld_fecdesde,$ld_fechasta,$ldec_saldoanterior,$ldec_total_debe,$ldec_total_haber,$lb_libro_banco,$as_tiprep,$as_codconmov)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//            Function: uf_generar_estado_cuenta
		//		        Access: private 
		//	    	 Arguments: 
		//      	 $ls_codemp //Código de la Empresa.
		//      	 $ls_codban //Código del Banco.
		//      	 $ls_ctaban //Número de la Cuenta Bancaria asociada al Banco. 
		//            $ls_orden //Parámetro de Orden para la presentación del reporte.
		//         $ld_fecdesde //Fecha a partir del cual se buscaran los registros.
		//         $ld_fechasta //Fecha hasta el cual se buscaran los registros.
		//  $ldec_saldoanterior //Monto total del Saldo anterior de esa cuenta en ese Banco.
		//     $ldec_total_debe //Monto total de movimientos afectando el debe.
		//    $ldec_total_haber //Monto total de movimientos afectando el haber.
		//      $lb_libro_banco 
		//         Description: Función que carga los detalles de los movimientos Bancarios para ser reportados en el Libro a Banco.
		//	        Creado Por: Ing. Miguel Palencia.                         Modificado Por: Ing. Miguel Palencia.
		//      Fecha Creación: 24/07/2007                       Fecha Última Modificación: 08/08/2007.
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$ldec_creditostmp    = 0; 
		$ldec_creditostmpneg = 0;
		$ldec_debitostmp     = 0; 
		$ldec_debitostmpneg  = 0;
		$ldec_debitosant     = 0;
		$ldec_creditosant    = 0;
		$ldec_saldoanterior  = 0;
		$ldec_total_debe	 = 0;	
		$ldec_total_haber	 = 0;
		
		$ds_edocta   = new  class_datastore();
		$ls_gestor   = $_SESSION["ls_gestor"];
		$ld_fecdesde = $this->fun->uf_convertirdatetobd($ld_fecdesde);
		$ld_fechasta = $this->fun->uf_convertirdatetobd($ld_fechasta);
		
		$ls_sql="SELECT codope ,(monto-monret) as monto, estmov
				   FROM scb_movbco
				  WHERE codemp='".$ls_codemp."' 
				    AND codban='".$ls_codban."' 
					AND ctaban='".$ls_ctaban."' 
					AND fecmov < '".$ld_fecdesde."'";

		$rs_data = $this->SQL->select($ls_sql);
		if ($rs_data===false)
		   {
			 print $this->SQL->message;
			 return false;
		   }
		else
		   {
			 while($row=$this->SQL->fetch_row($rs_data))
			      {
				    $ls_estmov = $row["estmov"];
				    $ls_codope = $row["codope"];
				    $ldec_monto=$row["monto"];
			 	    if((($ls_codope=="CH")||($ls_codope=="TR")||($ls_codope=="ND")||($ls_codope=="RE"))&&($ls_estmov!="A"))	{$ldec_creditostmp=$ldec_creditostmp+$ldec_monto;}
				    if((($ls_codope=="CH")||($ls_codope=="TR")||($ls_codope=="ND")||($ls_codope=="RE"))&&($ls_estmov=="A")) {$ldec_creditostmpneg=$ldec_creditostmpneg+$ldec_monto;}
				    if((($ls_codope=="DP")||($ls_codope=="NC"))&&($ls_estmov!="A")){$ldec_debitostmp=$ldec_debitostmp+$ldec_monto;}
				    if((($ls_codope=="DP")||($ls_codope=="NC"))&&($ls_estmov=="A")){$ldec_debitostmpneg=$ldec_debitostmpneg+$ldec_monto;}
			      }  
			 $this->SQL->free_result($rs_data);
			 $ldec_debitosant    = ($ldec_debitostmp-$ldec_debitostmpneg);
			 $ldec_creditosant   = ($ldec_creditostmp-$ldec_creditostmpneg);
			 $ldec_saldoanterior = ($ldec_debitosant-$ldec_creditosant);
		   }
		switch ($ls_orden){
			case 'D':
				//$ls_sql_orden=' ORDER BY scb_movbco.numdoc ASC';
				$ls_sql_orden=' ORDER BY substr(scb_movbco.numdoc,-4) ASC';
				break;
			case 'F':
				//$ls_sql_orden=' ORDER BY scb_movbco.fecmov ASC';
				// SOLICITUD DE YUDEXI ADMINISTRACION //
				$ls_sql_orden=' ORDER BY scb_movbco.fecmov ASC, substr(scb_movbco.numdoc,-4)';
				break;
			case 'B':
				$ls_sql_orden=' ORDER BY scb_movbco.codban ASC';
				break;
			case 'C':
				$ls_sql_orden=' ORDER BY scb_movbco.ctaban ASC';
				break;
			case 'O':
				$ls_sql_orden=' ORDER BY scb_movbco.codope ASC';
				break;
		}							
		  switch ($ls_gestor){
			case 'MYSQL':
			  $ls_straux = " CONCAT(codestpro1,codestpro2,codestpro3,codestpro4,codestpro5) ";
			break;
			case 'POSTGRE':
			  $ls_straux = " codestpro1||codestpro2||codestpro3||codestpro4||codestpro5 ";
			break;
		  }
		
		$ls_tabla   = "";
		$ls_straux2 = "";
	    $ls_cadaux  = "";
		
		if ($as_codconmov!='---')
		   {
		     $ls_tabla  = ', scb_concepto';
			 $ls_straux2 = " AND scb_movbco.codconmov = '".$as_codconmov."'
			                 AND scb_movbco.codconmov=scb_concepto.codconmov";
		   }
		switch ($as_tiprep){
		  case 'C':
			   $ls_sql = "SELECT scb_movbco.fecmov,scb_movbco.codope,scb_movbco.numdoc,scb_movbco.cod_pro,scb_movbco.ced_bene,
								 scb_movbco.nomproben, scb_movbco.estmov, scb_movbco.conmov as concepto, 
								 (scb_movbco.monto-scb_movbco.monret) as montot
							FROM scb_movbco, scb_banco, scb_ctabanco, scb_tipocuenta
						   WHERE scb_movbco.codemp = '".$ls_codemp."'
							 AND scb_movbco.codban = '".$ls_codban."'
							 AND scb_movbco.ctaban = '".$ls_ctaban."'
							 AND scb_movbco.fecmov BETWEEN '".$ld_fecdesde."' AND '".$ld_fechasta."'
							 AND scb_movbco.codemp = scb_banco.codemp
							 AND scb_movbco.codban = scb_banco.codban
							 AND scb_movbco.codemp = scb_ctabanco.codemp
							 AND scb_movbco.codban = scb_ctabanco.codban
							 AND scb_movbco.ctaban = scb_ctabanco.ctaban
							 AND scb_ctabanco.codtipcta=scb_tipocuenta.codtipcta $ls_sql_orden ";
		  break;
		  case 'D':
			   $ls_sql = "SELECT scb_movbco.fecmov,scb_movbco.codope,scb_movbco.numdoc,scb_movbco.nomproben, scb_movbco_spg.estmov,
								 scb_movbco_spg.spg_cuenta,spg_cuentas.denominacion as denctaspg, scb_movbco_spg.codestpro,scb_movbco.conmov as concepto,
								 scb_movbco_spg.monto, scb_movbco.monret, (scb_movbco.monto - scb_movbco.monret) as montot, scb_movbco.cod_pro,
								 scb_movbco.ced_bene, scb_movbco.tipo_destino
						    FROM scb_movbco, scb_movbco_spg, scb_banco, spg_cuentas, scb_ctabanco, scb_tipocuenta $ls_tabla
						   WHERE scb_movbco.codemp = '".$ls_codemp."'
							 AND scb_movbco.codban = '".$ls_codban."'
							 AND scb_movbco.ctaban = '".$ls_ctaban."'
							 AND scb_movbco.fecmov BETWEEN '".$ld_fecdesde."' AND '".$ld_fechasta."' $ls_straux2
							 AND scb_banco.codemp = scb_movbco.codemp
							 AND scb_banco.codban = scb_movbco.codban
							 AND scb_movbco.codemp = scb_ctabanco.codemp
							 AND scb_movbco.codban = scb_ctabanco.codban
							 AND scb_movbco.ctaban = scb_ctabanco.ctaban
							 AND scb_ctabanco.codtipcta = scb_tipocuenta.codtipcta
							 AND scb_movbco.codemp = scb_movbco_spg.codemp
							 AND scb_movbco.codban = scb_movbco_spg.codban
							 AND scb_movbco.ctaban = scb_movbco_spg.ctaban
							 AND scb_movbco.numdoc = scb_movbco_spg.numdoc
							 AND scb_movbco.codope = scb_movbco_spg.codope
							 AND scb_movbco.estmov = scb_movbco_spg.estmov
							 AND scb_movbco_spg.codemp = spg_cuentas.codemp
							 AND scb_movbco_spg.spg_cuenta = spg_cuentas.spg_cuenta
							 AND scb_movbco_spg.codestpro = $ls_straux
							 $ls_sql_orden";
		  break;
		}

		$rs_data = $this->SQL->select($ls_sql);//print $ls_sql;
		if ($rs_data===false)
		   {
			 print $this->SQL->message;
			 return false;
		   }
		else
		   {
			 while($row=$this->SQL->fetch_row($rs_data))
			      {
				    $ldec_creditos    = 0; 
					$ldec_creditosneg = 0; 
					$ldec_debitos     = 0;  
					$ldec_debitosneg  = 0;
				    
					$ls_codope    = $row["codope"];
				    $ls_estmov    = $row["estmov"];
				    $ls_conmov	  = $row["concepto"];
				    $ls_numdoc	  = $row["numdoc"];
				    $ls_nomproben = $row["nomproben"];
				    $ld_fecmov    = $row["fecmov"];
					$ld_montot    = $row["montot"];
				    if ($as_tiprep=='D')
					   {  
					     $ls_codpro    = $row["cod_pro"];
						 $ls_cedben    = $row["ced_bene"];
						 $ls_region    = ""; 
						 $ls_tipproben = $row["tipo_destino"];
						 if ($ls_tipproben=='P')
						    {
							  $ls_codproben = $ls_codpro;
							}
						 elseif($ls_tipproben=='B')
						    {
							  $ls_codproben = $ls_codpro;							
							}
						 if ($ls_tipproben=='B' || $ls_tipproben=='P')
						    {
							  $ls_region = $this->uf_load_region($ls_codemp,$ls_tipproben,$ls_codproben);
							}
						 $ls_codestpro = $row["codestpro"]; 
					     $ls_spgcta    = $row["spg_cuenta"]; 
					     $ls_denctaspg = $row["denctaspg"]; 
	   					 $ld_monspg    = $row["monto"];
					   }
				    if ((($ls_codope=="CH")||($ls_codope=="TR")||($ls_codope=="ND")||($ls_codope=="RE"))&&($ls_estmov!="A"))
				       {
				         if ($as_tiprep=='C')
						    {
							  $ldec_creditos = ($ldec_creditos+$ld_montot);
							}
						 else 
						    {
							  $ldec_creditos = ($ldec_creditos+$ld_monspg);
							} 
				       }
				    elseif($ls_estmov=="A")
				       {
					     if (($ls_codope!="CH")&&($ls_codope=="TR")&&($ls_codope!="ND")&&($ls_codope!="RE"))
					        {					
					          if ($as_tiprep=='C')
						         {
							       $ldec_creditos = ($ldec_creditos+$ld_montot);
							     }
						      else 
						         {
							       $ldec_creditos = ($ldec_creditos+$ld_monspg);
							     }  
						    }
				       }
				    if ((($ls_codope=="DP")||($ls_codope=="NC"))&&($ls_estmov!="A"))	
				       {
				         if ($as_tiprep=='C')
						    {
						      $ldec_debitos=$ldec_debitos+$ld_montot;
							}
						 else 
						    {
							  $ldec_debitos = ($ldec_debitos+$ld_monspg);
							}  
					   }
				    elseif($ls_estmov=="A")
				       {
					     if (($ls_codope=="CH")||($ls_codope=="TR")||($ls_codope=="ND")||($ls_codope=="RE"))
					        {
				              if ($as_tiprep=='C')
						         {
						           $ldec_debitos = ($ldec_debitos+$ld_montot);	
						         }
						      else 
						         {
							       $ldec_debitos = ($ldec_debitos+$ld_monspg);
							     }  
				            }
					   }
				    $ldec_total_debe  = ($ldec_total_debe+$ldec_debitos);
				    $ldec_total_haber = ($ldec_total_haber+$ldec_creditos);
				    if (!$lb_libro_banco)
				       {
		/*		 	     $ls_operacion = $this->fun->iif_string("'".$ls_codope."'=='CH'","Cheque",$this->fun->iif_string("'".$ls_codope."'=='ND'",'Nota Debito',$this->fun->iif_string("'".$ls_codope."'=='RE'",'Retiro',$this->fun->iif_string("'".$ls_codope."'=='NC'",'Nota Credito',$this->fun->iif_string("'".$ls_codope."'=='DP'",'Deposito','')))));*/
		// CAMBIO A PETICION DE YUDEXI
				 	     $ls_operacion = $this->fun->iif_string("'".$ls_codope."'=='CH'","Cheque",$this->fun->iif_string("'".$ls_codope."'=='TR'",'Pago por Transferencia',$this->fun->iif_string("'".$ls_codope."'=='ND'",'Nota Debito',$this->fun->iif_string("'".$ls_codope."'=='RE'",'Retiro',$this->fun->iif_string("'".$ls_codope."'=='NC'",'Nota Credito',$this->fun->iif_string("'".$ls_codope."'=='DP'",'Deposito',''))))));
				       }
				    else
				       {
					     $ls_operacion=$ls_codope;
				       }
				    $ds_edocta->insertRow("operacion",$ls_operacion);
				    $ds_edocta->insertRow("conmov",$ls_conmov);
				    $ds_edocta->insertRow("codope",$ls_codope);
				    $ds_edocta->insertRow("estmov",$ls_estmov);
				    $ds_edocta->insertRow("numdoc",$ls_numdoc);
				    $ds_edocta->insertRow("fecmov",$ld_fecmov);
				    $ds_edocta->insertRow("beneficiario",$ls_nomproben);
				    $ds_edocta->insertRow("debitos",$ldec_debitos);
				    $ds_edocta->insertRow("creditos",$ldec_creditos);
					$ds_edocta->insertRow("montot",$ld_montot);
			        if ($as_tiprep=='D')
					   {
				         $ds_edocta->insertRow("codestpro",$ls_codestpro);
				         $ds_edocta->insertRow("spg_cuenta",$ls_spgcta);
				         $ds_edocta->insertRow("denctaspg",$ls_denctaspg);
						 $ds_edocta->insertRow("monto",$ld_monspg);
					     $ds_edocta->insertRow("region",$ls_region); 
					   }
				  }
			 $this->SQL->free_result($rs_data);
		   }
		return $ds_edocta->data;
	}

    function uf_load_region($as_codemp,$as_tipproben,$as_codproben)
    {
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//            Function: uf_generar_estado_cuenta
	//		        Access: private 
	//	    	 Arguments: 
	//      	 $ls_codemp //Código de la Empresa.
	//        $as_tipproben //Tipo de P=Proveedor o B=Beneficiario.
	//        $as_codproben //Código del proveedor o cedula del beneficiario
	//         Description: Función que carga el estado de localización de un proveedor o beneficiario determinado.
	//	        Creado Por: Ing. Miguel Palencia.                         Modificado Por: Ing. Miguel Palencia.
	//      Fecha Creación: 24/07/2007                       Fecha Última Modificación: 08/08/2007.
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

      if ($as_tipproben=='P')
	     {
		   $ls_campo = 'cod_pro';
		   $ls_tabla = 'rpc_proveedor';
		 }
	  else
	     {
		   $ls_campo = 'ced_bene';
		   $ls_tabla = 'rpc_beneficiario';
		 }
	  $ls_sql = "SELECT tepuy_estados.desest
				   FROM tepuy_pais, tepuy_estados, $ls_tabla
				  WHERE $ls_tabla.codemp='".$as_codemp."'
				    AND $ls_tabla.$ls_campo='".$as_codproben."'
				    AND $ls_tabla.codpai=tepuy_pais.codpai
				    AND $ls_tabla.codest=tepuy_estados.codest";
      $rs_data = $this->SQL->select($ls_sql);
	  if ($rs_data===false)
	     {
		   $lb_valido = false;
		 }
      else
	     {
		   if ($row=$this->SQL->fetch_row($rs_data))
		      {
			    $ls_region = $row["desest"];
			  }
		 }
	  return $ls_region;
	}

function uf_generar_estado_cuenta_resumido($ls_codban,$ls_ctaban,$ld_fecdesde,$ld_fechasta,$ldec_saldoanterior,$ldec_total_debe,$ldec_total_haber,$ls_nomban,$ls_nomtipcta)
	{
		$ldec_creditostmp=0; $ldec_creditostmpneg=0; $ldec_debitostmp=0; $ldec_debitostmpneg=0;	$ldec_debitosant=0; $ldec_creditosant=0; $ldec_saldoanterior=0;
		$ldec_total_debe=0;	$ldec_total_haber=0;
		$ds_edocta=new class_datastore();
		$ld_fecdesde=$this->fun->uf_convertirdatetobd($ld_fecdesde);
		$ld_fechasta=$this->fun->uf_convertirdatetobd($ld_fechasta);
		$ls_sql="SELECT A.codope as codope ,(A.monto-A.monret) as monto, A.estmov as estmov,B.nomban as nomban,D.nomtipcta as nomtipcta
				 FROM   scb_movbco A,scb_banco B,scb_ctabanco C,scb_tipocuenta D
				 WHERE  A.codban='".$ls_codban."'  AND  A.ctaban='".$ls_ctaban."' AND A.fecmov < '".$ld_fecdesde."' 
				 AND    A.codban=B.codban AND A.codban=C.codban AND A.ctaban=C.ctaban AND C.codtipcta=D.codtipcta";
		$rs_data=$this->SQL->select($ls_sql);
		if( $rs_data===false)
		{
			print $this->SQL->message;
			return false;
		}
		else
		{
			while($row=$this->SQL->fetch_row($rs_data))
			{
				$ls_estmov=$row["estmov"];
				$ls_codope=$row["codope"];
				$ldec_monto=$row["monto"];
				if((($ls_codope=="CH")||($ls_codope=="TR")||($ls_codope=="ND")||($ls_codope=="RE"))&&($ls_estmov!="A")){$ldec_creditostmp=$ldec_creditostmp+$ldec_monto;}
				if((($ls_codope=="CH")||($ls_codope=="TR")||($ls_codope=="ND")||($ls_codope=="RE"))&&($ls_estmov=="A")){$ldec_creditostmpneg=$ldec_creditostmpneg+$ldec_monto;}
				if((($ls_codope=="DP")||($ls_codope=="NC"))&&($ls_estmov!="A")){$ldec_debitostmp=$ldec_debitostmp+$ldec_monto;}
				if((($ls_codope=="DP")||($ls_codope=="NC"))&&($ls_estmov=="A")){$ldec_debitostmpneg=$ldec_debitostmpneg+$ldec_monto;}
			}
			$this->SQL->free_result($rs_data);
			$ldec_debitosant = $ldec_debitostmp-$ldec_debitostmpneg;
			$ldec_creditosant = $ldec_creditostmp-$ldec_creditostmpneg;
			$ldec_saldoanterior = $ldec_debitosant-$ldec_creditosant;
		}
		
		$ls_sql="SELECT A.nomban as nomban,C.nomtipcta as nomtipcta
				 FROM scb_banco A,scb_ctabanco B,scb_tipocuenta C
				 WHERE A.codban=B.codban AND B.codtipcta=C.codtipcta AND A.codban='".$ls_codban."' AND B.ctaban='".$ls_ctaban."'";
		$rs_data=$this->SQL->select	($ls_sql);
		if($rs_data==false)
		{
			return false;
		}
		else
		{
			if($row=$this->SQL->fetch_row($rs_data))
			{	
				$ls_nomtipcta=$row["nomtipcta"];
				$ls_nomban=$row["nomban"];
			}
		}
		
		
		$ls_sql="SELECT (CASE codope
							WHEN 'CH' THEN 'Cheque'
							WHEN 'TR' THEN 'Pago por Transferencia'
							WHEN 'ND' THEN 'Nota de Debito'
							WHEN 'RE' then 'Retiro'
							ELSE ' '
							END ) AS operacion,0 as debitos,sum(monto-monret) as creditos ,count(*) as cantidad
				FROM scb_movbco
				WHERE codban='".$ls_codban."' AND ctaban='".$ls_ctaban."'
				AND (fecmov >= '".$ld_fecdesde."' AND fecmov <= '".$ld_fechasta."')
				AND (codope='CH' OR codope='TR' OR codope='ND' OR codope='RE') 
				GROUP BY codope,fecmov
				ORDER BY fecmov";
		$ls_sql2="SELECT (CASE codope
						WHEN 'NC' THEN 'Nota de Credito'
						WHEN 'DP' THEN 'Deposito'
						ELSE ' '
						END ) AS operacion,sum(monto-monret) as debitos,0 as creditos ,count(*) as cantidad
				FROM scb_movbco
				WHERE codban='".$ls_codban."' AND ctaban='".$ls_ctaban."'
				AND (fecmov >= '".$ld_fecdesde."' AND fecmov <= '".$ld_fechasta."')
				AND (codope='DP' OR codope='NC')  
				GROUP BY codope ,fecmov
				ORDER BY fecmov ";
		$rs_data=$this->SQL->select($ls_sql);
		if( $rs_data===false)
		{
			return false;
		}
		else
		{
			while($row=$this->SQL->fetch_row($rs_data))
			{
				$ldec_creditos=0; $ldec_creditosneg=0; $ldec_debitos=0; $ldec_debitosneg=0;
				$ldec_debitos=$row["debitos"];
				$ldec_creditos=$row["creditos"];
				$ldec_total_debe = $ldec_total_debe+$ldec_debitos;
				$ldec_total_haber= $ldec_total_haber+$ldec_creditos;
				$ls_operacion=$row["operacion"];
				$li_cantidad=$row["cantidad"];
				$ds_edocta->insertRow("operacion",$ls_operacion);
				$ds_edocta->insertRow("debitos",$ldec_debitos);
				$ds_edocta->insertRow("creditos",$ldec_creditos);
				$ds_edocta->insertRow("cantidad",$li_cantidad);
			}
			$this->SQL->free_result($rs_data);			
		}	
		$rs_data=$this->SQL->select($ls_sql2);
		if( $rs_data===false)
		{
			return false;
		}
		else
		{
			while($row=$this->SQL->fetch_row($rs_data))
			{
				$ldec_creditos=0; $ldec_creditosneg=0; $ldec_debitos=0; $ldec_debitosneg=0;
				$ldec_debitos=$row["debitos"];
				$ldec_creditos=$row["creditos"];
				$ldec_total_debe = $ldec_total_debe+$ldec_debitos;
				$ldec_total_haber= $ldec_total_haber+$ldec_creditos;
				$ls_operacion=$row["operacion"];
				$li_cantidad=$row["cantidad"];
				$ds_edocta->insertRow("operacion",$ls_operacion);
				$ds_edocta->insertRow("debitos",$ldec_debitos);
				$ds_edocta->insertRow("creditos",$ldec_creditos);
				$ds_edocta->insertRow("cantidad",$li_cantidad);
			}
			$this->SQL->free_result($rs_data);			
		}	
				
		return $ds_edocta->data;
	}
function uf_obtener_mov_conciliacion($ls_mesano,$ls_codban,$ls_ctaban,$ldec_salseglib,$ldec_salsegbco)
	{
		$io_fecha=new class_fecha();
		$ds_mov=new class_datastore();
		$ds_movimientos=new class_datastore();
		$ls_codemp=$this->dat_emp["codemp"];
		$ld_fechasta=$io_fecha->uf_last_day(substr($ls_mesano,0,2),substr($ls_mesano,2,4));
		$ld_fechasta=$this->fun->uf_convertirdatetobd($ld_fechasta);
		$ld_fecdesde="01/".substr($ls_mesano,0,2)."/".substr($ls_mesano,2,4);
		$ld_fecdesde=$this->fun->uf_convertirdatetobd($ld_fecdesde);

		$ls_sql="SELECT * 
				 FROM scb_movbco
				 WHERE codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND fecmov <='".$ld_fechasta."' AND (estreglib='' OR (estreglib<>'' AND feccon<>'".$ld_fecdesde."'))";
				 
		$rs_data=$this->SQL->select($ls_sql);	
	 
		if($rs_data===false)
		{
			return false;
		}
		else
		{
			while($row=$this->SQL->fetch_row($rs_data))
			{
				$ls_codban=$row["codban"];
				$ds_mov->insertRow("codban",$ls_codban);
				$ls_ctaban=$row["ctaban"];
				$ds_mov->insertRow("ctaban",$ls_ctaban);
				$ls_numdoc=$row["numdoc"];
				$ds_mov->insertRow("numdoc",$ls_numdoc);
				$ls_nomproben=$row["nomproben"];
				$ds_mov->insertRow("nomproben",$ls_nomproben);
				$ld_fecmov=$row["fecmov"];
				$ds_mov->insertRow("fecmov",$ld_fecmov);
				$ldec_monto=$row["monto"];
				$ds_mov->insertRow("monto",$ldec_monto);
				$ls_conmov=$row["conmov"];
				$ds_mov->insertRow("conmov" ,$ls_conmov);	
				$ls_estmov=$row["estmov"];
				$ds_mov->insertRow("estmov" ,$ls_estmov);	
			}
			$this->SQL->free_result($rs_data);
		}		
		$ldec_saldo_ant=$this->uf_calcular_saldolibro($ls_codban,$ls_ctaban,$ld_fechasta);

		if(abs($ldec_saldo_ant-$ldec_salseglib)>0.01)
		{
			$this->io_msg->message("Vuelva a modulo conciliación ya que hay movimientos no registrados");
			return false;
		}
		else
		{
			$this->io_msg->message("Todo Bien");
		}
		
			$ls_sql= "SELECT '01' as tipo, '-' as suma, numdoc , nomproben, fecmov , monto-monret as monto, codope  
					  FROM scb_movbco
					  WHERE fecmov <='".$ld_fechasta."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND 
					        estreglib=''  AND ((feccon > '".$ld_fecdesde."'  ) OR (feccon='1900-01-01')) AND 
					       (((codope='CH' OR codope='TR' OR codope='ND' OR codope='RE') AND estmov<>'A') OR ((codope='DP' OR codope='NC') AND estmov='A'))";


		
		$rs_data= $this->SQL->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message($this->uf_convertirmsg($this->SQL->message));
			return false;
		}
		else
		{
			while($row=$this->SQL->fetch_row($rs_data))
			{
				$ls_tipo=$row["tipo"];
				$ds_movimientos->insertRow("tipo",$ls_tipo);
				$ls_suma=$row["suma"];
				$ds_movimientos->insertRow("suma",$ls_suma);
				$ls_numdoc=$row["numdoc"];
				$ds_movimientos->insertRow("numdoc",$ls_numdoc);
				$ls_cedbene=$row["nomproben"];
				$ds_movimientos->insertRow("nomproben",$ls_cedbene);
				$ls_fecha=$this->fun->uf_convertirfecmostrar($row["fecmov"]);
				$ds_movimientos->insertRow("fecmov",$ls_fecha);
				$ldec_monto=$row["monto"];
				$ds_movimientos->insertRow("monto",$ldec_monto);
				$ls_codope=$row["codope"];
				$ds_movimientos->insertRow("codope",$ls_tipo);
			}
			$this->SQL->free_result($rs_data);
		}


			$ls_sql= "SELECT '02' as tipo, '+' as suma, numdoc, nomproben, fecmov, monto-monret as monto, codope
					  FROM   scb_movbco
					  WHERE  fecmov <='".$ld_fechasta."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND estreglib=''
					  AND ((feccon > '".$ld_fecdesde."' ) OR (feccon='1900-01-01'))
					  AND (((codope='CH' OR codope='TR' OR codope='ND' OR codope='RE') AND estmov='A') OR ((codope='DP' OR codope='NC') AND estmov<>'A'))";
		
		$rs_data= $this->SQL->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message($this->uf_convertirmsg($this->SQL->message));
			return false;
		}
		else
		{
			while($row=$this->SQL->fetch_row($rs_data))
			{
				$ls_tipo=$row["tipo"];
				$ds_movimientos->insertRow("tipo",$ls_tipo);
				$ls_suma=$row["suma"];
				$ds_movimientos->insertRow("suma",$ls_suma);
				$ls_numdoc=$row["numdoc"];
				$ds_movimientos->insertRow("numdoc",$ls_numdoc);
				$ls_cedbene=$row["nomproben"];
				$ds_movimientos->insertRow("nomproben",$ls_cedbene);
				$ls_fecha=$this->fun->uf_convertirfecmostrar($row["fecmov"]);
				$ds_movimientos->insertRow("fecmov",$ls_fecha);
				$ldec_monto=$row["monto"];
				$ds_movimientos->insertRow("monto",$ldec_monto);
				$ls_codope=$row["codope"];
				$ds_movimientos->insertRow("codope",$ls_tipo);
			}
			$this->SQL->free_result($rs_data);
		}
			
		// No Registradas en Libros
		
		   $ls_sql = "SELECT 'A1' as tipo, '+' as suma, numdoc, conmov as nomproben,fecmov, monto-monret as monto, codope
					  FROM   scb_movbco
					  WHERE  fecmov <='".$ld_fechasta."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."'
					  AND  feccon='".$ld_fecdesde."' AND estreglib='A' AND (((codope='CH' OR codope='TR' OR codope='ND' OR codope='RE') AND estmov<>'A') OR 
					  ((codope='DP' OR codope='NC') AND estmov='A'))"; 
		
		$rs_data= $this->SQL->select($ls_sql);		

		if($rs_data===false)
		{
			return false;
		}
		else
		{
			while($row=$this->SQL->fetch_row($rs_data))
			{
				$ls_tipo=$row["tipo"];
				$ds_movimientos->insertRow("tipo",$ls_tipo);
				$ls_suma=$row["suma"];
				$ds_movimientos->insertRow("suma",$ls_suma);
				$ls_numdoc=$row["numdoc"];
				$ds_movimientos->insertRow("numdoc",$ls_numdoc);
				$ls_cedbene=$row["nomproben"];
				$ds_movimientos->insertRow("nomproben",$ls_cedbene);
				$ls_fecha=$this->fun->uf_convertirfecmostrar($row["fecmov"]);
				$ds_movimientos->insertRow("fecmov",$ls_fecha);
				$ldec_monto=$row["monto"];
				$ds_movimientos->insertRow("monto",$ldec_monto);
				$ls_codope=$row["codope"];
				$ds_movimientos->insertRow("codope",$ls_tipo);
			}
			$this->SQL->free_result($rs_data);

		}		
		
		$ls_sql="SELECT 'A2' as tipo, '-' as suma, numdoc, conmov as nomproben, fecmov, monto-monret as monto, codope 
				 FROM  scb_movbco
				 WHERE fecmov <='".$ld_fechasta."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."'
				 AND feccon='".$ld_fecdesde."' AND estreglib='A' 
				 AND (((codope='CH' OR codope='TR' OR codope='ND' OR codope='RE') AND estmov='A') OR ((codope='DP' OR codope='NC') AND estmov<>'A'))";
			
		$rs_data= $this->SQL->select($ls_sql);		

		if($rs_data===false)
		{
			print $this->SQL->message;
			return false;
		}
		else
		{
			while($row=$this->SQL->fetch_row($rs_data))
			{
				$ls_tipo=$row["tipo"];
				$ds_movimientos->insertRow("tipo",$ls_tipo);
				$ls_suma=$row["suma"];
				$ds_movimientos->insertRow("suma",$ls_suma);
				$ls_numdoc=$row["numdoc"];
				$ds_movimientos->insertRow("numdoc",$ls_numdoc);
				$ls_cedbene=$row["nomproben"];
				$ds_movimientos->insertRow("nomproben",$ls_cedbene);
				$ls_fecha=$this->fun->uf_convertirfecmostrar($row["fecmov"]);
				$ds_movimientos->insertRow("fecmov",$ls_fecha);
				$ldec_monto=$row["monto"];
				$ds_movimientos->insertRow("monto",$ldec_monto);
				$ls_codope=$row["codope"];
				$ds_movimientos->insertRow("codope",$ls_tipo);
			}
			$this->SQL->free_result($rs_data);
		}		
				
		// Error Libro
		$ls_sql="SELECT 'B1' as tipo, '+' as suma, numdoc, conmov as nomproben, fecmov , monto-monret as monto, codope 
				FROM scb_movbco
				WHERE fecmov <='".$ld_fechasta."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."'
				AND feccon='".$ld_fecdesde."' AND estreglib='B' 
				AND (((codope='CH' OR codope='TR' OR codope='ND' OR codope='RE') AND estmov<>'A') OR ((codope='DP' OR codope='NC') AND estmov='A')) ";
			
		$rs_data= $this->SQL->select($ls_sql);		

		if($rs_data===false)
		{
			print $this->SQL->message;
			return false;
		}
		else
		{
			while($row=$this->SQL->fetch_row($rs_data))
			{
				$ls_tipo=$row["tipo"];
				$ds_movimientos->insertRow("tipo",$ls_tipo);
				$ls_suma=$row["suma"];
				$ds_movimientos->insertRow("suma",$ls_suma);
				$ls_numdoc=$row["numdoc"];
				$ds_movimientos->insertRow("numdoc",$ls_numdoc);
				$ls_cedbene=$row["nomproben"];
				$ds_movimientos->insertRow("nomproben",$ls_cedbene);
				$ls_fecha=$this->fun->uf_convertirfecmostrar($row["fecmov"]);
				$ds_movimientos->insertRow("fecmov",$ls_fecha);
				$ldec_monto=$row["monto"];
				$ds_movimientos->insertRow("monto",$ldec_monto);
				$ls_codope=$row["codope"];
				$ds_movimientos->insertRow("codope",$ls_tipo);
			}
			$this->SQL->free_result($rs_data);
		}				
		
		$ls_sql="SELECT 'B2' as tipo, '-' as suma, numdoc, conmov as nomproben, fecmov , monto-monret as monto, codope 
				FROM scb_movbco
				WHERE fecmov <='".$ld_fechasta."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."'
				AND feccon='".$ld_fecdesde."' AND estreglib='B' 
				AND  (((codope='CH' OR codope='TR' OR codope='ND' OR codope='RE') AND estmov='A') OR ((codope='DP' OR codope='NC') AND estmov<>'A')) ";
		
		$rs_data= $this->SQL->select($ls_sql);		

		if($rs_data===false)
		{
			print $this->SQL->message;
			return false;
		}
		else
		{
			while($row=$this->SQL->fetch_row($rs_data))
			{
				$ls_tipo=$row["tipo"];
				$ds_movimientos->insertRow("tipo",$ls_tipo);
				$ls_suma=$row["suma"];
				$ds_movimientos->insertRow("suma",$ls_suma);
				$ls_numdoc=$row["numdoc"];
				$ds_movimientos->insertRow("numdoc",$ls_numdoc);
				$ls_cedbene=$row["nomproben"];
				$ds_movimientos->insertRow("nomproben",$ls_cedbene);
				$ls_fecha=$this->fun->uf_convertirfecmostrar($row["fecmov"]);
				$ds_movimientos->insertRow("fecmov",$ls_fecha);
				$ldec_monto=$row["monto"];
				$ds_movimientos->insertRow("monto",$ldec_monto);
				$ls_codope=$row["codope"];
				$ds_movimientos->insertRow("codope",$ls_tipo);
			}
			$this->SQL->free_result($rs_data);

		}
				
		// Error Banco
		$ls_sql="SELECT 'C1' as tipo, '-' as suma, numdoc, conmov as nomproben, fecmov, monmov as monto, codope 
				 FROM scb_errorconcbco 
				 WHERE fecmov <='".$ld_fechasta."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND 
				 fecmesano='".$ld_fecdesde."' AND esterrcon='C' AND 
				 (((codope='CH' OR codope='TR' OR codope='ND' OR codope='RE') AND estmov<>'A') OR ((codope='DP' OR codope='NC') AND estmov='A')) ";
			
		$rs_data= $this->SQL->select($ls_sql);		
	
		if($rs_data===false)
		{
			print $this->SQL->message;
			return false;
		}
		else
		{
			while($row=$this->SQL->fetch_row($rs_data))
			{
				$ls_tipo=$row["tipo"];
				$ds_movimientos->insertRow("tipo",$ls_tipo);
				$ls_suma=$row["suma"];
				$ds_movimientos->insertRow("suma",$ls_suma);
				$ls_numdoc=$row["numdoc"];
				$ds_movimientos->insertRow("numdoc",$ls_numdoc);
				$ls_cedbene=$row["nomproben"];
				$ds_movimientos->insertRow("nomproben",$ls_cedbene);
				$ls_fecha=$this->fun->uf_convertirfecmostrar($row["fecmov"]);
				$ds_movimientos->insertRow("fecmov",$ls_fecha);
				$ldec_monto=$row["monto"];
				$ds_movimientos->insertRow("monto",$ldec_monto);
				$ls_codope=$row["codope"];
				$ds_movimientos->insertRow("codope",$ls_tipo);
			}
			$this->SQL->free_result($rs_data);
		}
		
		$ls_sql="SELECT 'C2' as tipo, '+' as suma, numdoc, conmov as nomproben, fecmov , monmov as monto, codope 
				 FROM  scb_errorconcbco 
		 		 WHERE fecmov <='".$ld_fechasta."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND 
				 fecmesano='".$ld_fecdesde."' and esterrcon='C' AND 
				 (((codope='CH' OR codope='TR' OR codope='ND' OR codope='RE') AND estmov='A') OR ((codope='DP' OR codope='NC') AND estmov<>'A')) ";
		
		$rs_data= $this->SQL->select($ls_sql);		
		if($rs_data===false)
		{
			print $this->SQL->message;
			return false;
		}
		else
		{
			while($row=$this->SQL->fetch_row($rs_data))
			{
				$ls_tipo=$row["tipo"];
				$ds_movimientos->insertRow("tipo",$ls_tipo);
				$ls_suma=$row["suma"];
				$ds_movimientos->insertRow("suma",$ls_suma);
				$ls_numdoc=$row["numdoc"];
				$ds_movimientos->insertRow("numdoc",$ls_numdoc);
				$ls_cedbene=$row["nomproben"];
				$ds_movimientos->insertRow("nomproben",$ls_cedbene);
				$ls_fecha=$this->fun->uf_convertirfecmostrar($row["fecmov"]);
				$ds_movimientos->insertRow("fecmov",$ls_fecha);
				$ldec_monto=$row["monto"];
				$ds_movimientos->insertRow("monto",$ldec_monto);
				$ls_codope=$row["codope"];
				$ds_movimientos->insertRow("codope",$ls_tipo);
			}
			$this->SQL->free_result($rs_data);
		}					
		return $ds_movimientos->data;	
	}
			
	function uf_calcular($data,$ls_mesano)		 
	{
		$ds_mov=new class_datastore();	
		$ds_mov->data=$data;
		$li_total=$ds_mov->getRowCount("numdoc");
		$ldec_CreditosTmp=0;
		$ldec_CreditosTmpNeg=0;
		$ldec_DebitosTmp=0;
		$ldec_DebitosTmpNeg=0;
		for($li_i=1;$li_i<=$li_total;$li_i++)
		{
			$ls_codope=$ds_mov->getValue("codope",$li_i);
			$ls_estmov=$ds_mov->getValue("estmov",$li_i);
			$ldec_monto=$ds_mov->getValue("monto",$li_i);
			if((($ls_codope=='CH')||($ls_codope=='TR')||($ls_codope=='ND')||($ls_codope=='RE'))&&($ls_estmov<>'A')){$ldec_CreditosTmp=$ldec_CreditosTmp+$ldec_monto;}
			if((($ls_codope=='CH')||($ls_codope=='TR')||($ls_codope=='ND')||($ls_codope=='RE'))&&($ls_estmov=='A')){$ldec_CreditosTmpNeg=$ldec_CreditosTmpNeg+$ldec_monto;}
			if((($ls_codope=='DP')||($ls_codope=='NC'))&&($ls_estmov<>'A'))	{$ldec_DebitosTmp=$ldec_DebitosTmp+$ldec_monto;	}
			if((($ls_codope=='DP')||($ls_codope=='NC'))&&($ls_estmov=='A'))	{$ldec_DebitosTmpNeg=$ldec_DebitosTmpNeg+$ldec_monto;}
		}
		$ldec_DebitosAnt = $ldec_DebitosTmp-$ldec_DebitosTmpNeg;
		$ldec_CreditosAnt = $ldec_CreditosTmp-$ldec_CreditosTmpNeg;
		$ldec_SaldoAnterior = $ldec_DebitosAnt - $ldec_CreditosAnt;				
		return round($ldec_SaldoAnterior,2);	
	}
	
	function uf_calcular_saldolibro($as_codban,$as_ctaban,$ad_fecha)
	{
	/////////////////////////////////////////////////////////////////////////////
	// Funtion	    :  uf_calcular_saldolibro
	//
	//	Return	    :  ldec_saldo
	//
	//	Descripcion :  Fucnion que se encarga de obtener el saldo de los movimientos registrdos en libro
	///////////////////////////////////////////////////////////////////////////// 
	$ldec_monto_haber=0;$ldec_monto_debe=0;$ldec_saldo=0;
	
	$ls_codemp = $this->dat_emp["codemp"];		
	$ld_fecha = $this->fun->uf_convertirdatetobd($ad_fecha);
		
	$ls_sql="SELECT monhab,mondeb,(mondeb - monhab) As saldo
			 FROM ( SELECT COALESCE( SUM(monto - monret),0) As monhab
				   FROM  scb_movbco 
				   WHERE codban='".$as_codban."' AND ctaban='".$as_ctaban."' AND  
	  			   (codope='RE' OR codope='ND' OR codope='CH' OR codope='TR') AND  estmov<>'A' AND estmov<>'O' AND (estreglib<>'A' or (estreglib='A' AND estcon=1)) AND codemp='".$ls_codemp."' AND fecmov<='".$ld_fecha."') D,
				 ( SELECT COALESCE( SUM(monto - monret),0) As mondeb
				   FROM scb_movbco
				   WHERE codban='".$as_codban."' AND ctaban='".$as_ctaban."' AND  
	 			   (codope='NC' OR codope='DP') AND estmov<>'A' AND estmov<>'O' AND (estreglib<>'A' or (estreglib='A' AND estcon=1)) AND codemp='".$ls_codemp."' AND fecmov<='".$ld_fecha."') H ";												
		
	$rs_saldos=$this->SQL->select($ls_sql);		
		if(($rs_saldos==false)&&($this->SQL->message!=""))
		{
			print "Saldolibro".$this->SQL->message;
		}
		else
		{		
			if($row=$this->SQL->fetch_row($rs_saldos))
			{
				$ldec_mondeb=$row["mondeb"];
				$ldec_monhab=$row["monhab"];
				$ldec_saldo=$row["saldo"];				
				if(is_null($ldec_saldo)){$ldec_saldo=0;}
				if( (is_null($ldec_monto_debe) )&&($ldec_monto_haber>0) ){$ldec_saldo=$ldec_monto_haber;} 
				if( (is_null($ldec_monto_haber))&&($ldec_monto_debe>0) ){$ldec_saldo=$ldec_monto_debe;}
			}
			$this->SQL->free_result($rs_saldos);			
		}			
		return  $ldec_saldo;
	}
	
	function uf_listado_cheques($ls_codban,$ls_cuenta,$ls_chequera)
	{
		/////////////////////////////////////////////////////////////////////////////
		// Funtion	    :  uf_listado_cheques		
		//	Return	    :  la_data		
		//	Descripcion :  Fucnion que se encarga de retornar los cheques pertenecnientes a una cuenta y a un banco
		///////////////////////////////////////////////////////////////////////////// 
		$ls_codemp = $this->dat_emp["codemp"];		
		$la_data=array();
		$ls_sql="SELECT a.codban,a.ctaban,a.numchequera,a.numche,b.nomproben,b.fecmov,a.estche,b.monto,b.numdoc
				FROM scb_cheques a LEFT OUTER JOIN scb_movbco b
				ON (a.codban=b.codban AND a.ctaban=b.ctaban AND a.numche=b.numdoc AND b.codope='CH')
				WHERE a.codban='$ls_codban' AND a.ctaban='$ls_cuenta' and a.numchequera='$ls_chequera'";												
			
		    $rs_data=$this->SQL->select($ls_sql);		
			if(($rs_data==false)&&($this->SQL->message!=""))
			{
				print $this->SQL->message;
			}
			else
			{		
				if($row=$this->SQL->fetch_row($rs_data))
				{
					$la_data=$this->SQL->obtener_datos($rs_data);					
				}
				$this->SQL->free_result($rs_data);			
			}			
			return  $la_data;		
	}
	
	function uf_comprobante_retencion($ls_numcom,$ls_fechainicio,$ls_fechafin)
	{
		/////////////////////////////////////////////////////////////////////////////
		// Funtion	    :  uf_comprobante_retencion
		//	Return	    :  la_data		
		//	Descripcion :  Funcion que se encarga de retornar los datos de los comprobantes de retencion asociados al codigo dado
		//  Autor       :  Ing. Laura Cabré
		//  Fecha       :  03/10/2006
		///////////////////////////////////////////////////////////////////////////// 
		$ls_codemp = $this->dat_emp["codemp"];		
		$la_data=array();
	    
		$ls_sql = "SELECT scb_cmp_ret.nomsujret, scb_cmp_ret.rif, scb_cmp_ret.estcmpret, scb_cmp_ret.numlic,
				 	      scb_dt_cmp_ret.numfac, scb_dt_cmp_ret.fecfac, scb_dt_cmp_ret.numcon, scb_dt_cmp_ret.numsop,
					      scb_dt_cmp_ret.basimp, scb_dt_cmp_ret.porimp, scb_dt_cmp_ret.iva_ret
					 FROM scb_cmp_ret, scb_dt_cmp_ret
				    WHERE scb_cmp_ret.codemp='".$ls_codemp."'
				      AND scb_cmp_ret.numcom='".$ls_numcom."'
					  AND scb_cmp_ret.codret='0000000003'
					  AND scb_cmp_ret.fecrep BETWEEN '".$ls_fechainicio."' AND '".$ls_fechafin."'
					  AND scb_cmp_ret.codemp=scb_dt_cmp_ret.codemp
					  AND scb_cmp_ret.codret=scb_dt_cmp_ret.codret
					  AND scb_cmp_ret.numcom=scb_dt_cmp_ret.numcom";

			$rs_data=$this->SQL->select($ls_sql);
			if ($rs_data===false)
			   {
				print $this->SQL->message;
			   }
			else
			   {		
			     if ($row=$this->SQL->fetch_row($rs_data))
				    {
					  $la_data=$this->SQL->obtener_datos($rs_data);					
				    }
				 $this->SQL->free_result($rs_data);			
			   }			
			return  $la_data;	
	}
	
	
	
	function uf_formato_cartaorden($as_codigo,&$la_data)
	{
		/////////////////////////////////////////////////////////////////////////////
		// Funtion	    :  uf_comprobante_retencion
		//	Return	    :  la_data		
		//	Descripcion :  Funcion que se encarga de retornar los datos de los comprobantes de retencion asociados al codigo dado
		//  Autor       :  Ing. Laura Cabré
		//  Fecha       :  03/10/2006
		///////////////////////////////////////////////////////////////////////////// 
		$ls_codemp = $this->dat_emp["codemp"];		
		$la_data=array();
		$ls_sql="SELECT * 
				FROM scb_cartaorden
				WHERE codemp='$ls_codemp' and codigo='$as_codigo'";
			$rs_data=$this->SQL->select($ls_sql);		
			if(($rs_data==false)&&($this->SQL->message!=""))
			{
				print $this->SQL->message;
				return false;
			}
			else
			{		
				if($row=$this->SQL->fetch_row($rs_data))
				{
					$la_data=$this->SQL->obtener_datos($rs_data);
					return true;					
				}
				else
					return false;
				$this->SQL->free_result($rs_data);			
			}			
			
	}
	function uf_select_cartaorden($as_numdoc,$as_codban,$as_ctaban)
	{
	  /*--------------------------------------------------------------
		Function:	    uf_select_cartaorden
		   Description: Funcion que se buscar los datos de una carta orden especifica
		         Fecha: 26/12/2006
		         Autor: Ing. Miguel Palencia
		Modificado Por: Ing. Miguel Palencia.    Fecha Última Modificación: 22/08/2007.
	  ----------------------------------------------------------------------------------*/
		$ls_codemp=$this->dat_emp["codemp"];
		
		$ls_sql = "SELECT max(scb_movbco.numcarord) as numcarord,scb_movbco.numdoc,max(scb_movbco.ctaban) as ctaban, max(scb_movbco.monto) as monto,
						  max(scb_banco.nomban) as nomban,max(scb_banco.gerban) as gerban,max(scb_tipocuenta.nomtipcta) as nomtipcta 
					 FROM scb_movbco, scb_banco, scb_ctabanco , scb_tipocuenta 
					WHERE scb_movbco.codemp='".$ls_codemp."' 
					  AND scb_movbco.numcarord='".$as_numdoc."' 
					  AND scb_movbco.codban='".$as_codban."' 
					  AND scb_movbco.ctaban='".$as_ctaban."' 
					  AND scb_movbco.estmov<>'A' 
					  AND scb_movbco.estmov<>'O' 
					  AND scb_movbco.codope='ND' 
					  AND scb_movbco.codemp=scb_banco.codemp 
					  AND scb_movbco.codban=scb_banco.codban 
					  AND scb_movbco.codemp=scb_ctabanco.codemp 
					  AND scb_movbco.codban=scb_ctabanco.codban 
					  AND scb_movbco.ctaban=scb_ctabanco.ctaban 
					  AND scb_ctabanco.codtipcta=scb_tipocuenta.codtipcta
					GROUP BY scb_movbco.numdoc;";
					
		$rs_data=$this->SQL->select($ls_sql);
		if($rs_data===false)
		{
			$data=array();
			$this->is_msg_error="Error uf_select_cartaorden, ".$this->fun->uf_convertirmsg($this->SQL->message);
			print $this->is_msg_error;
			return false;
		}
		else
		{
			if($row=$this->SQL->fetch_row($rs_data))
			{
			  $data=$this->SQL->obtener_datos($rs_data);
			}
			else
			{
			  $data=array();
			}
			$this->SQL->free_result($rs_data);
		}
		return $data;
	}
	
	function uf_check_tipo_cartaorden($as_numdoc,$as_codban,$as_ctaban)
	{
	  /*--------------------------------------------------------------
		Function:	    uf_select_cartaorden
		Description:	Funcion que se buscar los datos de una carta orden especifica
		Fecha: 26/12/2006
		Autor: Ing. Miguel Palencia
	               
	----------------------------------------------------------------------------*/
		$ls_codemp=$this->dat_emp["codemp"];
		$ls_sql="SELECT a.numdoc,b.numdoc
				   FROM scb_movbco a,scb_dt_movbco b
				  WHERE a.codemp='".$ls_codemp."'
				    AND a.numdoc='".$as_numdoc."'
				    AND a.codban='".$as_codban."'
					AND a.ctaban='".$as_ctaban."' 
				    AND a.codope='ND' 
					AND a.codemp=b.codemp 
					AND a.codban=b.codban
					AND a.ctaban=b.ctaban
					AND a.codope=b.codope";	
		$rs_data=$this->SQL->select($ls_sql);
		
		if($rs_data===false)
		{
			$data=array();
			$this->is_msg_error="Error uf_check_tipo_cartaorden, ".$this->fun->uf_convertirmsg($this->SQL->message);
			print $this->is_msg_error;
			return false;
		}
		else
		{
			if($row=$this->SQL->fetch_row($rs_data))
			{
			   $lb_existe=true;
			}
			else
			{
			   $lb_existe=false;
			}
			$this->SQL->free_result($rs_data);
		}
		return $lb_existe;	
	}
	
	
	
	function uf_select_dt_cartaorden($as_numdoc,$as_codban,$as_ctaban,$as_tipproben)
	{
	  /*--------------------------------------------------------------
		Function:	    uf_select_cartaorden
		Description:	Funcion que se buscar los datos de una carta orden especifica
		Fecha: 26/12/2006
		Autor: Ing. Miguel Palencia
	               
	----------------------------------------------------------------------------*/
		$ls_straux = "";
		$ls_codemp=$this->dat_emp["codemp"];
		if ($as_tipproben=='P')
		   {
		     $ls_campo  = "cod_pro";
		     $ls_tabla  = "rpc_proveedor";
		     $ls_straux = ", max(rpc_proveedor.nompro) as nompro";
			 $ls_group1 = "";		   
		     $ls_group2 = " GROUP BY scb_movbco.cod_pro,scb_movbco.ctaban";
		   }
		else
		   {
		     $ls_campo  = "ced_bene";
		     $ls_tabla  = "rpc_beneficiario";
		     $ls_straux = ", max(rpc_beneficiario.nombene) as nombene,max(rpc_beneficiario.apebene) as apebene ";
		     $ls_group1 = " GROUP BY scb_dt_movbco.ced_bene,scb_dt_movbco.ctabanbene";
		     $ls_group2 = " GROUP BY scb_movbco.ced_bene,scb_movbco.ctaban";
		   }
		if($this->uf_check_tipo_cartaorden($as_numdoc,$as_codban,$as_ctaban))
		{
			$ls_sql=  "SELECT max(scb_dt_movbco.ctabanbene) as ctaban,scb_dt_movbco.ced_bene,".
			          "       sum(scb_dt_movbco.monsolpag) as monto, $ls_straux     ".
					  "  FROM scb_dt_movbco, $ls_tabla								".
					  " WHERE scb_dt_movbco.codemp='".$ls_codemp."' 				".
					  "   AND scb_dt_movbco.numdoc='".$as_numdoc."' 				".
					  "   AND scb_dt_movbco.codban='".$as_codban."' 				".
					  "   AND scb_dt_movbco.ctaban='".$as_ctaban."' 				".
					  "   AND scb_dt_movbco.codemp=$ls_tabla.codemp 				".
					  "   AND scb_dt_movbco.$ls_campo=$ls_tabla.$ls_campo $ls_group1";
		}
		else
		{
			$ls_sql = "  SELECT max($ls_tabla.ctaban) as ctaban,scb_movbco.$ls_campo, ".
			          "         sum(scb_movbco.monto) as monto,						  ".
					  " 		max(cxp_sol_banco.numsol) as numsolpag,				  ".
       				  " 		max(cxp_solicitudes.consol) as consolpag $ls_straux	  ".
					  "    FROM scb_movbco, $ls_tabla, cxp_sol_banco, cxp_solicitudes ".
					  "   WHERE scb_movbco.codemp='".$ls_codemp."' 				      ".
					  "     AND scb_movbco.numcarord='".$as_numdoc."' 			      ".
					  "     AND scb_movbco.codban='".$as_codban."' 				      ".
					  "     AND scb_movbco.ctaban='".$as_ctaban."' 				      ".
					  "     AND scb_movbco.codemp=$ls_tabla.codemp 				      ".
					  "     AND scb_movbco.$ls_campo=$ls_tabla.$ls_campo 			  ".
					  "     AND scb_movbco.codemp=cxp_sol_banco.codemp
						    AND scb_movbco.codban=cxp_sol_banco.codban
						    AND scb_movbco.ctaban=cxp_sol_banco.ctaban
						    AND scb_movbco.numdoc=cxp_sol_banco.numdoc
						    AND scb_movbco.codope=cxp_sol_banco.codope 
						    AND scb_movbco.estmov=cxp_sol_banco.estmov
						    AND cxp_sol_banco.codemp=cxp_solicitudes.codemp
						    AND cxp_sol_banco.numsol=cxp_solicitudes.numsol $ls_group2 ";
		}
		$rs_data=$this->SQL->select($ls_sql);
		if($rs_data===false)
		{
			$la_data=array();
			$this->is_msg_error="Error uf_select_dt_cartaorden, ".$this->fun->uf_convertirmsg($this->SQL->message);
			print $this->SQL->message;
			return false;
		}
		else
		{
			$li=0;
			if ($as_tipproben=='B')
			   {
			     while ($row=$this->SQL->fetch_row($rs_data))
			           {
				         $li++;
				         $la_data[$li]=array('cedbene'=>$row["ced_bene"],'nombene'=>$row["nombene"].$row["apebene"],'numsolpag'=>$row["numsolpag"],'consolpag'=>$row["consolpag"],'ctaban'=>$row["ctaban"],'monto'=>number_format($row["monto"],2,",","."));
			           }
			   }
			elseif($as_tipproben=='P')
			   {
			     while ($row=$this->SQL->fetch_row($rs_data))
					   {
					     $li++;
						 $la_data[$li]=array('cod_pro'=>$row["cod_pro"],'nompro'=>$row["nompro"],'ctaban'=>$row["ctaban"],'monto'=>number_format($row["monto"],2,",","."));
					   }
			   }
			$this->SQL->free_result($rs_data);
		}
		
		return $la_data;
	}
	
function uf_generar_cmpret_ordendepagodirecta($ls_codemp,$ls_numdoc,$ls_codban,$ls_ctaban,$ls_tipodestino,$ls_codpro,$ls_cedben,$ls_fecrep,$la_data_cmpret)
	{
		$lb_valido=false;
			 
		$ls_sql="SELECT a.numdoc,a.codban,a.ctaban,a.codope,a.baseimp,b.monobjret as monobjret,a.monto as monto_iva,b.monto as monto_ret,a.codcar,b.codded,d.porcar,c.porded,
						c.estretmun,c.islr,c.iva,a.documento 
				FROM scb_movbco_spgop a,scb_movbco_scg b,tepuy_deducciones c,tepuy_cargos d
				WHERE a.codemp='".$ls_codemp."' AND a.numdoc='".$ls_numdoc."' AND a.codban='".$ls_codban."' AND a.ctaban='".$ls_ctaban."' AND a.codope='OP' AND a.numdoc=b.numdoc AND a.codope=b.codope AND a.codban=b.codban AND a.ctaban=b.ctaban
				AND a.codcar<>'' AND b.codded<>'0000' AND b.codded<>'00000' AND b.codded=c.codded AND a.codcar=d.codcar AND concat(a.codemp,a.numdoc,a.codban,a.ctaban,a.codope) not in (SELECT concat(codemp,numdoc,codban,ctaban,codope) FROM scb_dt_cmp_ret_op)";
		//print $ls_sql;		 
		$rs_data=$this->SQL_aux->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error uf_generar_cmpret_ordendepagodirecta, ".$this->fun->uf_convertirmsg($this->SQL_aux->message);
			print $this->SQL_aux->message;
			return false;
		}
		else
		{
			$li_control_mun=0;
			$li_control_iva=0;
			$li_control_islr=0;
			$ls_tipocomp="";
			$this->SQL->begin_transaction();
			while($row=$this->SQL_aux->fetch_row($rs_data))
			{				
			
				$li_estretmun=$row["estretmun"];
				$li_iva=$row["iva"];
				$li_islr=$row["islr"];
				$ldec_baseimp=$row["baseimp"];
				$ldec_monobjret=$row["monobjret"];
				$ldec_porded=$row["porded"];
				$ldec_porcar=$row["porcar"];
				$ldec_monret=$row["monto_ret"];
				$ldec_iva=$row["monto_iva"];
				$ldec_monto=$la_data_cmpret['monto'];
				$ldec_totcmpsiniva=($ldec_monto-$ldec_iva);
				$ls_numdocres=$la_data_cmpret['numdocres'];
				$ls_fecdocres=$la_data_cmpret['fecdocres'];
				$ls_nrocontrol=$la_data_cmpret['nrocontrol'];
				$ls_desope=$la_data_cmpret["desope"];
				$ls_numsop=$row["documento"];
				if($li_estretmun==1)
				{
					$li_control_iva=0;
					$li_control_islr=0;
					$li_control_mun++;
					if($li_control_mun==1)
					{
						$ls_tipocomp=" MUNICIPAL ";
						$ls_retid='0000000003';
						$lb_valido=$this->uf_guardar_cmpret($ls_codemp,$ls_retid,&$ls_nrocomp,$ls_fecrep,$ls_tipodestino,$ls_codpro,$ls_cedben,$ls_tipocomp,$ls_numdoc);
					}
			
					$this->uf_guardar_dt_cmpret($ls_codemp,$ls_retid,$ls_nrocomp,$li_control_mun,$ls_fecdocres,$ls_numdocres,$ls_nrocontrol,'', 
								  '', '01-reg',$ldec_totcmpsiniva,$ldec_monto,$ldec_monobjret,$ldec_porded,
								  $ldec_monret,$ldec_monret,$ls_desope,$ls_numsop,$ls_codban,$ls_ctaban,$ls_numdoc,'OP');
				}
				if($li_iva==1)
				{
					$li_control_mun=0;
					$li_control_islr=0;
					$li_control_iva++;
					if($li_control_iva==1)
					{
						$ls_tipocomp=" IVA ";	
						$ls_retid='0000000001';
						$lb_valido=$this->uf_guardar_cmpret($ls_codemp,$ls_retid,&$ls_nrocomp,$ls_fecrep,$ls_tipodestino,$ls_codpro,$ls_cedben,$ls_tipocomp,$ls_numdoc);
					}	
														
					$lb_valido=$this->uf_guardar_dt_cmpret($ls_codemp,$ls_retid,$ls_nrocomp,$li_control_iva,$ls_fecdocres,$ls_numdocres,$ls_nrocontrol,'', 
								  '', '01-reg',$ldec_totcmpsiniva,$ldec_monto,$ldec_baseimp,$ldec_porcar,
								  $ldec_iva,$ldec_monret,$ls_desope,$ls_numsop,$ls_codban,$ls_ctaban,$ls_numdoc,'OP');
				}
							
			}//End while	
			if($lb_valido)
			{
				$this->SQL->commit();		
			}
			else
			{
				$this->SQL->rollback();		
			}	
		}	
	}
	
	function uf_guardar_dt_cmpret($ls_codemp,$ls_codret,$ls_numcom,$ls_numope,$ld_fecfac,$ls_numfac,$ls_numcon,$ls_numnd, 
								  $ls_numnc,$ls_tiptrans,$ldec_totcmpsiniva,$ldec_totcmpconiva,$ldec_basimp,$ldec_porimp,
								  $ldec_totimp,$ldec_ivaret,$ls_desope,$ls_numsop,$ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope)
	{
		$ls_sql="INSERT INTO scb_dt_cmp_ret_op(codemp, codret, numcom, numope, fecfac, numfac, numcon, numnd, 
											  numnc, tiptrans, totcmp_sin_iva, totcmp_con_iva, basimp, porimp,
											   totimp, iva_ret, desope, numsop, codban, ctaban, numdoc, codope)
				 VALUES('".$ls_codemp."','".$ls_codret."','".$ls_numcom."','".$ls_numope."','".$this->fun->uf_convertirdatetobd($ld_fecfac)."','".$ls_numfac."','".$ls_numcon."','".$ls_numnd."', 
						'".$ls_numnc."','".$ls_tiptrans."','".$ldec_totcmpsiniva."','".$ldec_totcmpconiva."','".$ldec_basimp."','".$ldec_porimp."',
						'".$ldec_totimp."','".$ldec_ivaret."','".$ls_desope."','".$ls_numsop."','".$ls_codban."','".$ls_ctaban."','".$ls_numdoc."','OP')";							   
		
		$li_result=$this->SQL->execute($ls_sql);	
		if($li_result===false)
		{
			$this->is_msg_error="Error en guardar_dt_cmp".$this->fun->uf_convertirmsg($this->SQL->message);
			print $this->SQL->message;
			return false;			
		}
		else
		{
			return true;
		}
	}
	
	function uf_select_dt_sujret($ls_tipodestino,$ls_codproben,$ls_codemp)
	{	
		if($ls_tipodestino=='P')
		{
			$rs_provben=$this->uf_select_rowdata($this->SQL,"SELECT nompro as nomproben,dirpro as dirproben,rifpro as rifproben,nitpro as nitproben FROM rpc_proveedor WHERE cod_pro ='".$ls_codproben."' AND codemp='".$ls_codemp."'");
		}
		else
		{
			if($_SESSION["ls_gestor"]=="MYSQL")
			{
				$ls_aux=" concat(nombene,apebene) as nomproben";
			}
			else
			{
				$ls_aux=" nombene||apebene as nomproben";
			}
			$rs_provben=$this->uf_select_rowdata($this->SQL,"SELECT ".$ls_aux.",dirbene as dirproben,rifbene as rifproben,ced_bene as nitproben FROM rpc_beneficiario WHERE ced_bene ='".$ls_codproben."' AND codemp='".$ls_codemp."'");
		}
	    return $rs_provben;
	}
	

	function uf_guardar_cmpret($ls_codemp,$ls_codret,$ls_nrocomp,$ls_fecrep,$ls_tipodestino,$ls_codpro,$ls_cedben,$ls_tipocomp,$ls_numdoc)
	{
		$ls_perfiscal=substr($ls_fecrep,-4).substr($ls_fecrep,3,2);
		$ld_fecha=$this->fun->uf_convertirdatetobd($ls_fecrep);
	    require_once("../../shared/class_folder/tepuy_c_seguridad.php");
	    $this->io_seguridad= new tepuy_c_seguridad();
		if($ls_tipodestino=='P')
		{
			$ls_destino=" Proveedor " ;
			$ls_codproben=$ls_codpro;
		}
		else
		{
			$ls_destino=" Beneficiario ";		
			$ls_codproben=$ls_cedben;
		}
		$la_provbene=$this->uf_select_dt_sujret($ls_tipodestino,$ls_codproben,$ls_codemp);
		$this->uf_ccr_get_nro($ls_perfiscal,&$ls_nrocomp,$ls_codret);
		$ls_sql="INSERT INTO scb_cmp_ret_op(codemp, codret, numcom, fecrep, perfiscal, codsujret,
											nomsujret, dirsujret, rif, nit, estcmpret, codusu, numlic,origen)
				 VALUES('".$ls_codemp."','".$ls_codret."','".$ls_nrocomp."','".$ld_fecha."','".$ls_perfiscal."','".$ls_codproben."',
				 		'".$la_provbene["nomproben"]."','".$la_provbene["dirproben"]."','".$la_provbene["rifproben"]."','".$la_provbene["nitproben"]."',
						1,'".$_SESSION["la_logusr"]."','','A')";

		$li_result=$this->SQL->execute($ls_sql);	
		if(($li_result===false))
		{
			$this->is_msg_error="Fallo insercion de movimiento detalle de orden de pago, ".$this->fun->uf_convertirmsg($this->SQL->message);
			return false;
		}
		else
		{
			$this->is_msg_error="El movimiento fue registrado";
			///////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
			$ls_evento="INSERT";			
			$ls_descripcion="Genero el Comprobante de Retencion ".$ls_tipocomp." para la Orden de Pago Directa numero ".$ls_numdoc." para el ".$ls_destino.$la_provbene["nomproben"]." de codigo ".$ls_codproben;
			$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($ls_codemp,'SCB',"INSERT",$_SESSION["la_logusr"],"tepuy_scb_rpp_ordenpago.php",$ls_descripcion);
			////////////////////////////////////////////////////////////////////////////////////////////////////////////
			return true;		
		}	
	}
	
	function uf_ccr_get_nro($ls_periodofiscal,$ls_nrocomp,$ls_retid)
	{
		$ls_codemp=$this->dat_emp["codemp"];	
		$ls_sql="SELECT substr(numcom,7,14) as numcom 
				 FROM   scb_cmp_ret_op 
				 WHERE  codemp='".$ls_codemp."' AND codret = '".$ls_retid."' AND perfiscal = '".$ls_periodofiscal."'
				 ORDER  by numcom desc ";
		
		$rs_result=$this->SQL->select($ls_sql);		
		if($rs_result===false)
		{
			$this->is_msg_error=$this->fun->uf_convertirmsg($this->SQL->message);
			print $this->is_msg_error;
			return false;			
		}
		else
		{
			if ($row=$this->SQL->fetch_row($rs_result))
			{ 
			   $codigo=$row["numcom"];
			   settype($codigo,'int');                             // Asigna el tipo a la variable.
			   $codigo = $codigo + 1;                              // Le sumo uno al entero.
			   settype($codigo,'string');                          // Lo convierto a varchar nuevamente.
			   $ls_nrocomp=$ls_periodofiscal.$this->fun->uf_cerosizquierda($codigo,8);
			   $this->SQL->free_result($rs_result);
			   return true;
		    }
		    else
		    {
			   $codigo="1";
			   $ls_nrocomp=$ls_periodofiscal.$this->fun->uf_cerosizquierda($codigo,8);
			   $this->SQL->free_result($rs_result);
   			   return true;
		    }
		}
	}//fin uf_ccr_get_nro
	
	function uf_comprobante_retencion_mun($ls_numcom)
	{
		/////////////////////////////////////////////////////////////////////////////
		// Funtion	    :  uf_comprobante_retencion
		//	Return	    :  la_data		
		//	Descripcion :  Funcion que se encarga de retornar los datos de los comprobantes de retencion asociados al codigo dado
		//  Autor       :  Ing. Laura Cabré
		//  Fecha       :  03/10/2006
		///////////////////////////////////////////////////////////////////////////// 
		$ls_codemp = $this->dat_emp["codemp"];		
		$la_data=array();
		$ls_sql="SELECT c.*,d.* 
				FROM scb_cmp_ret_op c, scb_dt_cmp_ret_op d 
				WHERE c.codret='0000000003' AND
				c.codemp='$ls_codemp' AND c.codemp=d.codemp AND c.codret=d.codret AND d.numdoc='$ls_numcom' AND c.numcom=d.numcom";												
			$rs_data=$this->SQL->select($ls_sql);		
			if(($rs_data==false)&&($this->SQL->message!=""))
			{
				print $this->SQL->message;
			}
			else
			{		
				if($row=$this->SQL->fetch_row($rs_data))
				{
					$la_data=$this->SQL->obtener_datos($rs_data);					
				}
				$this->SQL->free_result($rs_data);			
			}			
			return  $la_data;	
	}

function uf_print_mayor_presupuestario($as_fecdes,$as_fechas,$as_tipres,&$lb_valido)
{
///////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function:  uf_print_mayor_presupuestario
//		   Access:  public
//		 Argument: 
//	  Description:  Función que busca los datos de la cabecera de la Solicitud de Cotizacion.
//	   Creado Por:  Ing. Miguel Palencia.
// Fecha Creación:  23/04/2008								Fecha Última Modificación : 23/04/2008
////////////////////////////////////////////////////////////////////////////////////////////////////////
  $ls_gestor = $_SESSION["ls_gestor"];
  switch ($ls_gestor){
    case 'MYSQL':
	  $ls_sqlaux = " AND cxp_rd_spg.codestpro = CONCAT(spg_cuentas.codestpro1,spg_cuentas.codestpro2,spg_cuentas.codestpro3,spg_cuentas.codestpro4,spg_cuentas.codestpro5)";
	  $ls_straux = " AND cxp_rd_spg.codestpro = CONCAT(soc_cuentagasto.codestpro1,soc_cuentagasto.codestpro2,soc_cuentagasto.codestpro3,soc_cuentagasto.codestpro4,soc_cuentagasto.codestpro5)";
	break;
	case 'POSTGRE':
	  $ls_sqlaux = " AND cxp_rd_spg.codestpro = spg_cuentas.codestpro1||spg_cuentas.codestpro2||spg_cuentas.codestpro3||spg_cuentas.codestpro4||spg_cuentas.codestpro5";
	  $ls_straux = " AND cxp_rd_spg.codestpro = soc_cuentagasto.codestpro1||soc_cuentagasto.codestpro2||soc_cuentagasto.codestpro3||soc_cuentagasto.codestpro4||soc_cuentagasto.codestpro5";
	break;   
  }
  $ls_auxsql = "";
  if ($as_tipres=='D' || $as_tipres=='E')
     {
	   $ls_auxsql = "AND cxp_rd_spg.spg_cuenta<>'403180100'";
	 }
  if (!empty($as_fecdes) && !empty($as_fechas))
     {
	   $ls_fecdes = $this->fun->uf_convertirdatetobd($as_fecdes);
	   $ls_fechas = $this->fun->uf_convertirdatetobd($as_fechas);
	 }
  $ls_sql = "SELECT cxp_sol_banco.numdoc,cxp_sol_banco.codban,cxp_sol_banco.ctaban,cxp_sol_banco.codope,cxp_sol_banco.estmov,  
                    cxp_sol_banco.numsol,(cxp_sol_banco.monto) as montotche,scb_movbco.fecmov,cxp_rd_spg.procede_doc, 
					cxp_rd_spg.numdoccom,scb_movbco.nomproben, scb_movbco.tipo_destino,cxp_rd_spg.codestpro,soc_cuentagasto.monto,
                    TRIM(cxp_rd_spg.numrecdoc) as numrecdoc,cxp_rd_spg.codtipdoc,cxp_rd_spg.cod_pro,cxp_rd_spg.ced_bene,
					(CASE tipo_destino 
                       WHEN 'P' THEN rpc_proveedor.rifpro 
                       ELSE rpc_beneficiario.rifben 
                        END) AS rifproben,TRIM(soc_cuentagasto.spg_cuenta) as spg_cuenta, spg_cuentas.denominacion
			   FROM cxp_sol_banco,cxp_solicitudes,cxp_dt_solicitudes,scb_movbco,cxp_rd,cxp_rd_spg,spg_cuentas,rpc_proveedor,rpc_beneficiario, soc_cuentagasto
 			  WHERE cxp_sol_banco.codemp='".$_SESSION["la_empresa"]["codemp"]."'
				AND scb_movbco.fecmov BETWEEN '".$ls_fecdes."' AND '".$ls_fechas."' $ls_auxsql
				AND cxp_sol_banco.codemp=cxp_solicitudes.codemp
			    AND cxp_sol_banco.numsol=cxp_solicitudes.numsol
			    AND cxp_solicitudes.codemp=cxp_dt_solicitudes.codemp
			    AND cxp_solicitudes.numsol=cxp_dt_solicitudes.numsol
			    AND cxp_sol_banco.codemp=scb_movbco.codemp
			    AND cxp_sol_banco.codban=scb_movbco.codban
			    AND cxp_sol_banco.ctaban=scb_movbco.ctaban
			    AND cxp_sol_banco.numdoc=scb_movbco.numdoc
			    AND cxp_sol_banco.codope=scb_movbco.codope
			    AND cxp_sol_banco.estmov=scb_movbco.estmov
			    AND cxp_dt_solicitudes.codemp=cxp_rd.codemp
			    AND cxp_dt_solicitudes.numrecdoc=cxp_rd.numrecdoc
			    AND cxp_dt_solicitudes.codtipdoc=cxp_rd.codtipdoc
			    AND cxp_dt_solicitudes.ced_bene=cxp_rd.ced_bene
			    AND cxp_dt_solicitudes.cod_pro=cxp_rd.cod_pro
			    AND cxp_rd.codemp=cxp_rd_spg.codemp
			    AND cxp_rd.numrecdoc=cxp_rd_spg.numrecdoc
			    AND cxp_rd.codtipdoc=cxp_rd_spg.codtipdoc
			    AND cxp_rd.ced_bene=cxp_rd_spg.ced_bene
			    AND cxp_rd.cod_pro=cxp_rd_spg.cod_pro
			    AND cxp_rd_spg.codemp=spg_cuentas.codemp   
			    AND cxp_rd_spg.spg_cuenta=spg_cuentas.spg_cuenta $ls_sqlaux
				AND rpc_proveedor.codemp=scb_movbco.codemp
				AND rpc_proveedor.cod_pro=scb_movbco.cod_pro
				AND rpc_beneficiario.codemp=scb_movbco.codemp
				AND rpc_beneficiario.ced_bene=scb_movbco.ced_bene
				AND soc_cuentagasto.codemp=cxp_rd_spg.codemp
   				AND soc_cuentagasto.numordcom=cxp_rd_spg.numdoccom $ls_straux                 
                AND cxp_rd_spg.spg_cuenta=soc_cuentagasto.spg_cuenta
 			  ORDER BY scb_movbco.fecmov,scb_movbco.numdoc,cxp_rd_spg.numdoccom ASC";
  $rs_data = $this->SQL->select($ls_sql);//echo $ls_sql.'<br>';
  if ($rs_data===false)
	 {
	   $lb_valido = false;
	   $this->io_msg->message("CLASS->tepuy_scb_report.php;MÉTODO->uf_print_mayor_presupuestario;ERROR->".$this->fun->uf_convertirmsg($this->SQL->message));
	   echo $this->SQL->message;
	 }
  return $rs_data;
}//Function uf_print_mayor_presupuestario.	

function uf_load_monto_impuesto($as_numordcom,$as_tipordcom,$as_spgcta,$as_codestpro)
{
///////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function:  uf_load_monto_impuesto
//		   Access:  public
//		 Argument: 
//	  Description:  Función que busca el monto total de los cargos asociados a una orden de compra por estructura presupuestaria.
//	   Creado Por:  Ing. Miguel Palencia.
// Fecha Creación:  05/05/2008								Fecha Última Modificación : 05/05/2008
////////////////////////////////////////////////////////////////////////////////////////////////////////

  $ld_monimp = 0;
  if ($as_tipordcom=='B')
     {
	   $ls_tabla  = "soc_dta_cargos";
	   $ls_campo  = "codart";
	   $ls_table  = "siv_articulo";
	   $ls_tabaux = "siv_cargosarticulo";
	 }
  elseif($as_tipordcom=='S')
     {
	   $ls_tabla  = "soc_dts_cargos";
	   $ls_campo  = "codser";
	   $ls_table  = "soc_servicios";
	   $ls_tabaux = "soc_serviciocargo";
	 }
  $ls_codestpro1 = substr($as_codestpro,0,20);
  $ls_codestpro2 = substr($as_codestpro,20,6);
  $ls_codestpro3 = substr($as_codestpro,26,3);
  $ls_codestpro4 = substr($as_codestpro,29,2);
  $ls_codestpro5 = substr($as_codestpro,31,2);

  $ls_sql = "SELECT COALESCE(sum($ls_tabla.monimp),0) as monimp
			   FROM $ls_tabla, $ls_table, $ls_tabaux, soc_cuentagasto, tepuy_cargos
			  WHERE $ls_tabla.codemp = '".$_SESSION["la_empresa"]["codemp"]."'
			    AND $ls_tabla.numordcom = '".$as_numordcom."'
			    AND TRIM(soc_cuentagasto.spg_cuenta) = '".$as_spgcta."'
			    AND soc_cuentagasto.codestpro1 = '".$ls_codestpro1."'
			    AND soc_cuentagasto.codestpro2 = '".$ls_codestpro2."'
			    AND soc_cuentagasto.codestpro3 = '".$ls_codestpro3."'
			    AND soc_cuentagasto.codestpro4 = '".$ls_codestpro4."'
			    AND soc_cuentagasto.codestpro5 = '".$ls_codestpro5."'
			    AND $ls_tabla.codemp=$ls_table.codemp
			    AND $ls_tabla.$ls_campo=$ls_table.$ls_campo   
			    AND $ls_tabla.codemp=$ls_tabaux.codemp
			    AND $ls_tabla.codcar=$ls_tabaux.codcar    
			    AND $ls_tabla.codemp=tepuy_cargos.codemp
			    AND $ls_tabla.codcar=tepuy_cargos.codcar  
			    AND $ls_tabla.codemp=soc_cuentagasto.codemp
			    AND $ls_tabla.numordcom=soc_cuentagasto.numordcom
			    AND $ls_tabla.estcondat=soc_cuentagasto.estcondat
			    AND $ls_table.codemp=$ls_tabaux.codemp
			    AND $ls_table.$ls_campo=$ls_tabaux.$ls_campo   
			    AND $ls_tabaux.codemp=tepuy_cargos.codemp
			    AND $ls_tabaux.codcar=tepuy_cargos.codcar
			    AND trim($ls_table.spg_cuenta)=soc_cuentagasto.spg_cuenta
              GROUP BY soc_cuentagasto.spg_cuenta,soc_cuentagasto.codestpro1,soc_cuentagasto.codestpro2,soc_cuentagasto.codestpro3,
			           soc_cuentagasto.codestpro4,soc_cuentagasto.codestpro5";
  
  $rs_data   = $this->SQL->select($ls_sql);//echo $ls_sql.'<br><br><br><br>';
  if ($rs_data===false)
	 {
	   $lb_valido = false;
	   $this->io_msg->message("CLASS->tepuy_scb_report.php;MÉTODO->uf_load_monto_impuesto;ERROR->".$this->fun->uf_convertirmsg($this->SQL->message));
	   echo $this->SQL->message;
	 }
  else
     {
	   if ($row=$this->SQL->fetch_row($rs_data))
	      {
		    $ld_monimp = $row["monimp"];	 
		  }	   
	 }
  return $ld_monimp;
}

function uf_load_retenciones_cxp($as_codemp,$as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov,&$as_tipretislr,$as_tipret)
{
///////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function: uf_load_retenciones_cxp
//		   Access: public
//		 Argument: $as_codban = Código del Banco.
//                 $as_ctaban = Número de la Cuenta Bancaria.
//                 $as_numdoc = Número del Movimiento Bancario.
//                 $as_codope = Código de la Operación CH=Cheque,DP=Depósito,ND=Nota de Débito,NC= Nota de Crédito,RE=Retiro.
//                 $as_estmov = Estatus del Movimiento.
//	  Description: Función que extrae la sumatoria de las retenciones de IVA e ISLR aplicadas desde Cuentas Por Pagar, dependiendo
//                 del tipo de retención indicada por el parámetro $as_tipret.
//	   Creado Por: Ing. Miguel Palencia.
// Fecha Creación: 16/06/2008
////////////////////////////////////////////////////////////////////////////////////////////////////////

  if ($as_tipret=='IVA')
     {
	   $ls_straux = ", max(tepuy_deducciones.formula) as formula";
	   $ls_sqlaux = " AND tepuy_deducciones.islr=0 AND tepuy_deducciones.iva=1";
	   $ls_group  = "iva";
	 }
  elseif($as_tipret=='ISLR')
     {
	   $ls_straux = " , tepuy_deducciones.tipopers, max(tepuy_deducciones.formula) as formula";
	   $ls_sqlaux = " AND tepuy_deducciones.islr=1 AND tepuy_deducciones.iva=0";
	   $ls_group  = "islr,tipopers";
	 }
  $ls_sql = "SELECT COALESCE(sum(cxp_rd_cargos.monret),0) as montotret $ls_straux
			   FROM scb_movbco,cxp_sol_banco,cxp_dt_solicitudes,cxp_solicitudes,cxp_rd_cargos,cxp_rd_deducciones,cxp_rd,tepuy_deducciones
			  WHERE scb_movbco.codemp='".$as_codemp."'
			    AND scb_movbco.codban='".$as_codban."'
			    AND scb_movbco.ctaban='".$as_ctaban."' 
			    AND scb_movbco.numdoc='".$as_numdoc."'
			    AND scb_movbco.estmov='".$as_estmov."'
			    AND scb_movbco.codope='".$as_codope."' $ls_sqlaux
			    AND tepuy_deducciones.estretmun=0
			    AND tepuy_deducciones.otras=0
			    AND scb_movbco.codemp=cxp_sol_banco.codemp
			    AND scb_movbco.codban=cxp_sol_banco.codban
			    AND scb_movbco.ctaban=cxp_sol_banco.ctaban
			    AND scb_movbco.numdoc=cxp_sol_banco.numdoc
			    AND scb_movbco.codope=cxp_sol_banco.codope
			    AND scb_movbco.estmov=cxp_sol_banco.estmov
			    AND cxp_sol_banco.codemp=cxp_solicitudes.codemp
			    AND cxp_sol_banco.numsol=cxp_solicitudes.numsol
			    AND cxp_solicitudes.codemp=cxp_dt_solicitudes.codemp
			    AND cxp_solicitudes.numsol=cxp_dt_solicitudes.numsol
			    AND cxp_dt_solicitudes.codemp=cxp_rd.codemp
			    AND cxp_dt_solicitudes.numrecdoc=cxp_rd.numrecdoc
			    AND cxp_dt_solicitudes.codtipdoc=cxp_rd.codtipdoc 
			    AND cxp_dt_solicitudes.ced_bene=cxp_rd.ced_bene
			    AND cxp_dt_solicitudes.cod_pro=cxp_rd.cod_pro
			    AND cxp_dt_solicitudes.codemp=cxp_rd_deducciones.codemp
			    AND cxp_dt_solicitudes.numrecdoc=cxp_rd_deducciones.numrecdoc
			    AND cxp_dt_solicitudes.codtipdoc=cxp_rd_deducciones.codtipdoc 
			    AND cxp_dt_solicitudes.ced_bene=cxp_rd_deducciones.ced_bene
			    AND cxp_dt_solicitudes.cod_pro=cxp_rd_deducciones.cod_pro
			    AND cxp_rd.codemp=cxp_rd_deducciones.codemp
			    AND cxp_rd.numrecdoc=cxp_rd_deducciones.numrecdoc
			    AND cxp_rd.codtipdoc=cxp_rd_deducciones.codtipdoc 
			    AND cxp_rd.ced_bene=cxp_rd_deducciones.ced_bene
			    AND cxp_rd.cod_pro=cxp_rd_deducciones.cod_pro
			    AND cxp_rd.codemp=cxp_rd_cargos.codemp
			    AND cxp_rd.numrecdoc=cxp_rd_cargos.numrecdoc
			    AND cxp_rd.codtipdoc=cxp_rd_cargos.codtipdoc 
			    AND cxp_rd.ced_bene=cxp_rd_cargos.ced_bene
			    AND cxp_rd.cod_pro=cxp_rd_cargos.cod_pro
			    AND tepuy_deducciones.codemp=cxp_rd_deducciones.codemp
			    AND tepuy_deducciones.codded=cxp_rd_deducciones.codded
			  GROUP BY tepuy_deducciones.$ls_group";

  $rs_data = $this->SQL->select($ls_sql);//echo $ls_sql."<br>";
  if ($rs_data===false)
     {
	   $lb_valido = false;
	   $this->io_msg->message("CLASS->tepuy_scb_report.php;MÉTODO->uf_load_retenciones_cxp;ERROR->".$this->fun->uf_convertirmsg($this->SQL->message));
	   echo $this->SQL->message;
	 }		  
  else
     {
	   $li_numrows = $this->SQL->num_rows($rs_data);
	   if ($li_numrows>0)
	      {
		    while($row=$this->SQL->fetch_row($rs_data))
				 {
				   $ld_montotret = $row["montotret"];
				   if ($as_tipret=='ISLR')
                      {
				        $as_tipretislr = $row["tipopers"];
					  }
				 }
		  }
	 }
  return $ld_montotret;			  
}

function uf_load_retenciones_scb($as_codemp,$as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov,&$as_tipretislr,$as_tipret)
{
///////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function: uf_load_retenciones_islr_scb
//		   Access: public
//		 Argument: $as_codban = Código del Banco.
//                 $as_ctaban = Número de la Cuenta Bancaria.
//                 $as_numdoc = Número del Movimiento Bancario.
//                 $as_codope = Código de la Operación CH=Cheque,DP=Depósito,ND=Nota de Débito,NC= Nota de Crédito,RE=Retiro.
//                 $as_estmov = Estatus del Movimiento.
//	  Description: Función que extrae la sumatoria de las retenciones de impuesto sobre la renta aplicadas desde Banco.
//	   Creado Por: Ing. Miguel Palencia.
// Fecha Creación: 13/06/2008
////////////////////////////////////////////////////////////////////////////////////////////////////////

  if ($as_tipret=='IVA')
     {
	   $ls_straux = "";
	   $ls_sqlaux = " AND tepuy_deducciones.islr=0 AND tepuy_deducciones.iva=1";
	   $ls_group  = "iva";
	 }
  elseif($as_tipret=='ISLR')
     {
	   $ls_straux = " , tepuy_deducciones.tipopers";
	   $ls_sqlaux = " AND tepuy_deducciones.islr=1 AND tepuy_deducciones.iva=0";
	   $ls_group  = "islr,tipopers";
	 }

  $ld_montotislr = 0;
  $ls_sql = "SELECT COALESCE(sum(scb_movbco_scg.monto),0) as montotret $ls_straux
			   FROM scb_movbco_scg, tepuy_deducciones
			  WHERE scb_movbco_scg.codemp='".$as_codemp."'
			    AND scb_movbco_scg.codban='".$as_codban."'
			    AND scb_movbco_scg.ctaban='".$as_ctaban."'
			    AND scb_movbco_scg.numdoc='".$as_numdoc."'
			    AND scb_movbco_scg.codope='".$as_codope."'
			    AND scb_movbco_scg.estmov='".$as_estmov."' 
			    AND scb_movbco_scg.debhab='H'
			    AND scb_movbco_scg.codded<>'00000' $ls_sqlaux
			    AND tepuy_deducciones.estretmun=0
			    AND tepuy_deducciones.otras=0
			    AND scb_movbco_scg.codemp=tepuy_deducciones.codemp
			    AND scb_movbco_scg.codded=tepuy_deducciones.codded
			  GROUP BY $ls_group";
  $rs_data = $this->SQL->select($ls_sql);
  if ($rs_data===false)
     {
	   $lb_valido = false;
	   $this->io_msg->message("CLASS->tepuy_scb_report.php;MÉTODO->uf_load_retenciones_scb;ERROR->".$this->fun->uf_convertirmsg($this->SQL->message));
	   echo $this->SQL->message;
	 }		  
  else
     {
	   $li_numrows = $this->SQL->num_rows($rs_data);
	   if ($li_numrows>0)
	      {
		    while($row=$this->SQL->fetch_row($rs_data))
				 {
				   $ld_montotret = $row["montotret"];
				   if ($as_tipret=='ISLR')
                      {
				        $as_tipretislr = $row["tipopers"];
					  }
				 }
		  }
	 }
  return $ld_montotret;
}

function uf_load_denestpre($as_codemp,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5)
{
///////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function: uf_load_denestpre
//		   Access: public
//		 Argument: $as_codemp     = Código de la Empresa.
//                 $as_codestpro1 = Código de la Estructura Presupuestaria Nivel 1.
//                 $as_codestpro2 = Código de la Estructura Presupuestaria Nivel 2.
//                 $as_codestpro3 = Código de la Estructura Presupuestaria Nivel 3.
//                 $as_codestpro4 = Código de la Estructura Presupuestaria Nivel 4.
//                 $as_codestpro5 = Código de la Estructura Presupuestaria Nivel 5.
//	  Description: Función que extrae las denominaciones de cada uno de los niveles de la estructura presupuestaria.
//	   Creado Por: Ing. Miguel Palencia.
// Fecha Creación: 24/09/2008.
////////////////////////////////////////////////////////////////////////////////////////////////////////

  $la_denestpre = array();
  $ls_sql = "SELECT spg_ep1.denestpro1, spg_ep2.denestpro2, spg_ep3.denestpro3, spg_ep4.denestpro4, spg_ep5.denestpro5
			   FROM spg_ep1,spg_ep2,spg_ep3,spg_ep4,spg_ep5
			  WHERE spg_ep5.codemp='".$as_codemp."'
			    AND spg_ep5.codestpro1='".$as_codestpro1."'
			    AND spg_ep5.codestpro2='".$as_codestpro2."'
			    AND spg_ep5.codestpro3='".$as_codestpro3."'
			    AND spg_ep5.codestpro4='".$as_codestpro4."'
			    AND spg_ep5.codestpro5='".$as_codestpro5."'
			    AND spg_ep5.codemp=spg_ep1.codemp
			    AND spg_ep5.codestpro1=spg_ep1.codestpro1
			    AND spg_ep5.codemp=spg_ep2.codemp
			    AND spg_ep5.codestpro1=spg_ep2.codestpro1   
			    AND spg_ep5.codestpro2=spg_ep2.codestpro2
			    AND spg_ep5.codemp=spg_ep3.codemp
			    AND spg_ep5.codestpro1=spg_ep3.codestpro1   
			    AND spg_ep5.codestpro2=spg_ep3.codestpro2
			    AND spg_ep5.codestpro3=spg_ep3.codestpro3
			    AND spg_ep5.codemp=spg_ep4.codemp
			    AND spg_ep5.codestpro1=spg_ep4.codestpro1   
			    AND spg_ep5.codestpro2=spg_ep4.codestpro2
			    AND spg_ep5.codestpro3=spg_ep4.codestpro3
			    AND spg_ep5.codestpro4=spg_ep4.codestpro4";
  $rs_data = $this->SQL->select($ls_sql);
  if ($rs_data===false)
	 {
	   $lb_valido = false;
	   $this->io_msg->message("CLASS->tepuy_scb_report.php;MÉTODO->uf_load_denestpre;ERROR->".$this->fun->uf_convertirmsg($this->SQL->message));
	   echo $this->SQL->message;
	 }
  else
     {
	   if ($row=$this->SQL->fetch_row($rs_data))
	      {
		    $la_denestpre["denestpre1"][1] = $row["denestpro1"];
			$la_denestpre["denestpre2"][1] = $row["denestpro2"];
			$la_denestpre["denestpre3"][1] = $row["denestpro3"];
			$la_denestpre["denestpre4"][1] = $row["denestpro4"];
			$la_denestpre["denestpre5"][1] = $row["denestpro5"];
		  }
	   unset($rs_data,$row);
	 }
  return $la_denestpre;
}

function uf_load_retencion($as_codemp,$as_numrecdoc,$as_codtipdoc,$as_codpro,$as_cedben,$as_numdoccom,$as_prodoc,$as_tipret)
{
  if ($as_tipret=='IVA')
     {
	   $ls_straux = "COALESCE(SUM(cxp_rd_cargos.monret),0)";
	   $ls_sqlaux = " AND tepuy_deducciones.islr=0 AND tepuy_deducciones.iva=1";
	 }
  elseif($as_tipret=='ISLR')
     {
	   $ls_straux = "COALESCE(SUM(cxp_rd_deducciones.monret),0)";
	   $ls_sqlaux = " AND tepuy_deducciones.islr=1 AND tepuy_deducciones.iva=0";
	 }
  $ls_sql = "SELECT $ls_straux as monret,max(tepuy_deducciones.formula) as formula
			   FROM cxp_rd,cxp_rd_cargos,cxp_rd_deducciones,tepuy_deducciones
			  WHERE cxp_rd_cargos.codemp='".$as_codemp."'
			    AND TRIM(cxp_rd_cargos.numrecdoc)='".$as_numrecdoc."'
			    AND cxp_rd_cargos.codtipdoc='".$as_codtipdoc."'
			    AND TRIM(cxp_rd_cargos.cod_pro)='".trim($as_codpro)."'
			    AND TRIM(cxp_rd_cargos.ced_bene)= '".trim($as_cedben)."'
			    AND cxp_rd_cargos.numdoccom='".$as_numdoccom."'
			    AND cxp_rd_cargos.procede_doc='".$as_prodoc."' $ls_sqlaux
			    AND tepuy_deducciones.estretmun=0
			    AND tepuy_deducciones.otras=0
			    AND cxp_rd.codemp=cxp_rd_cargos.codemp
			    AND cxp_rd.numrecdoc=cxp_rd_cargos.numrecdoc
			    AND cxp_rd.codtipdoc=cxp_rd_cargos.codtipdoc
			    AND cxp_rd.cod_pro=cxp_rd_cargos.cod_pro
			    AND cxp_rd.ced_bene=cxp_rd_cargos.ced_bene
			    AND cxp_rd.codemp=cxp_rd_deducciones.codemp
			    AND cxp_rd.numrecdoc=cxp_rd_deducciones.numrecdoc
			    AND cxp_rd.codtipdoc=cxp_rd_deducciones.codtipdoc
			    AND cxp_rd.cod_pro=cxp_rd_deducciones.cod_pro
			    AND cxp_rd.ced_bene=cxp_rd_deducciones.ced_bene
			    AND cxp_rd_deducciones.codemp=tepuy_deducciones.codemp
			    AND cxp_rd_deducciones.codded=tepuy_deducciones.codded";
  $rs_data = $this->SQL->select($ls_sql);//echo $ls_sql;
  if ($rs_data===false)
	 {
	   $lb_valido = false;
	   $this->io_msg->message("CLASS->tepuy_scb_report.php;MÉTODO->uf_load_denestpre;ERROR->".$this->fun->uf_convertirmsg($this->SQL->message));
	   echo $this->SQL->message;
	 }
  else
     {
	   if ($row=$this->SQL->fetch_row($rs_data))
	      {
		    $ld_totmonret = $row["monret"];
			if ($as_tipret=='IVA')
			   {
				 $ls_forret = $row["formula"];
				 $ls_forret = str_replace("\$LD_MONTO",$ld_totmonret,$ls_forret);
				 $ld_totmonret = eval("return $ls_forret;");			   
			   }
		  }
     }
  return $ld_totmonret;
}
}
?>
