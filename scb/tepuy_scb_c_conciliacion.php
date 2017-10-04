<?php
class tepuy_scb_c_conciliacion
{
	var $io_sql;
	var $fun;
	var $msg;
	var $is_msg_error;	
	var $ds_concil;
	var $dat;
	var $ds_movimientos;
	var $ds_mov_selected;
	var $SQL_aux;
	var $io_fecha;
	var $la_security;
	var $io_seguridad;
	function tepuy_scb_c_conciliacion($aa_security)
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/tepuy_include.php");
		require_once("../shared/class_folder/class_fecha.php");
		require_once("../shared/class_folder/tepuy_c_seguridad.php");
		require_once("../shared/class_folder/tepuy_c_reconvertir_monedabsf.php");
		$this->io_rcbsf= new tepuy_c_reconvertir_monedabsf();
		$this->io_seguridad= new tepuy_c_seguridad();
		$sig_inc=new tepuy_include();
		$con=$sig_inc->uf_conectar();
		$this->io_sql=new class_sql($con);
		$this->SQL_aux=new class_sql($con);
		$this->fun=new class_funciones();
		$this->io_fecha=new class_fecha();
		$this->msg=new class_mensajes();
		$this->dat=$_SESSION["la_empresa"];	
		$this->ds_concil=new class_datastore();
		$this->ds_movimientos=new class_datastore();
		$this->ds_mov_selected=new class_datastore();
		$this->la_security=$aa_security; 
		$this->li_candeccon=$_SESSION["la_empresa"]["candeccon"];
		$this->li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
		$this->li_redconmon=$_SESSION["la_empresa"]["redconmon"];
	}

	
	function uf_cargar_movimientos_a_conciliar($as_codban,$as_ctaban,$object,$ad_fecha,$ad_fechasta,$li_rows, $ls_filtro='T',$as_orden,&$cant_conciliado,&$cant_noconciliado)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	uf_cargar_movimientos_a_conciliar
		//  Access:			public
		//	Returns:			Boolean Retorna si encontro o no errores en la consulta
		//	Description:	Funcion que se encarga de llenar un datastore con los datos de
		//					la conciliacion de la fecha enviada en caso de estar abierta , ademas llena el object con los 
		//					los movimientos bancarios a ser conciliados.
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido   = true;
		$ls_codemp   = $this->dat["codemp"];
		$ld_fecdes   = date($ad_fecha);
		$ld_fechasta = date($ad_fechasta);
		$ls_mesano   = substr($ld_fechasta,3,2).substr($ld_fechasta,6,4);
		$ld_fecdes   = $this->fun->uf_convertirdatetobd($ld_fecdes);
		$ld_fechasta = $this->fun->uf_convertirdatetobd($ld_fechasta);
		
		$li_temp=0;
		//Agregando el filtro segun seleccion
		$ls_cadena="";
		if($ls_filtro!="T")
		{
			switch($ls_filtro)
			{
				case "ND":
					$ls_cadena=" AND codope='ND'";
				break;
				case "NC":
					$ls_cadena=" AND codope='NC'";
				break;
				case "DP":
					$ls_cadena=" AND codope='DP'";
				break;
				case "RE":
					$ls_cadena=" AND codope='RE'";
				break;
				case "CH":
					$ls_cadena=" AND codope='CH'";
				break;				
				case "TR":
					$ls_cadena=" AND codope='TR'";
				break;	
			}
		
		}
		
		//Selecciono las conciliaciones para el mes/aï¿½ seleccionado.
		$ls_sql="SELECT codban,ctaban,mesano,estcon,salseglib,salsegbco,conciliacion 
				 FROM   scb_conciliacion
				 WHERE  codemp = '".$ls_codemp."' AND codban='".$as_codban."' 
				 AND ctaban='".$as_ctaban."' AND mesano='".$ls_mesano."'";
		//print $ls_sql;			
	
		$rs_concil=$this->io_sql->select($ls_sql);
		
		if(($rs_concil==false)&&($this->io_sql->message!=""))
		{
			$lb_valido=false;
			$this->is_msg_error=$this->fun->uf_convertirmsg($this->io_sql->message);		
			print "Error";
		}
		else
		{
			 if($row=$this->io_sql->fetch_row($rs_concil))
			 {
				 $ls_codban=$row["codban"];
				 $ls_ctaban=$row["ctaban"];
				 $ls_mesano=$row["mesano"];
				 $li_estcon=$row["estcon"];
				 $ldec_salseglib=$row["salseglib"];
				 $ldec_salsegbco=$row["salsegbco"];
				 $ldec_conciliacion=$row["conciliacion"];
			}
			else
			{
				 $ls_codban=$as_codban;
				 $ls_ctaban=$as_ctaban;
				 $ls_mesano=$ls_mesano;
				 $li_estcon=0;
				 $ldec_salseglib=0;
				 $ldec_salsegbco=0;
				 $ldec_conciliacion=0;
				 //return false;
			}
			 $this->ds_concil->insertRow("codban",$ls_codban);
			 $this->ds_concil->insertRow("ctaban",$ls_ctaban);
			 $this->ds_concil->insertRow("mesano",$ls_mesano);
			 $this->ds_concil->insertRow("estcon",$li_estcon);
			 $this->ds_concil->insertRow("salseglib",$ldec_salseglib);
			 $this->ds_concil->insertRow("salsegbco",$ldec_salsegbco);
			 $this->ds_concil->insertRow("conciliacion",$ldec_conciliacion);
		}
		$this->io_sql->free_result($rs_concil);
		// Select para los movimientos pertenecientes al mes en conciliacion o a conciliar
		$ls_sql=" SELECT numdoc,fecmov,conmov,(monto - monret) as monto,codope,estmov,estcon,feccon,estreglib
				   FROM   scb_movbco 
				   WHERE  codban='".$as_codban."' AND ctaban ='".$as_ctaban."' 
				   AND codemp='".$ls_codemp."' 
				   AND    ((feccon='1900-01-01' AND fecmov<='".$ld_fechasta."') OR  
				   (feccon = '".$ld_fecdes."') OR (fecmov<='".$ld_fechasta."' AND feccon>'".$ld_fecdes."' ))
				    $ls_cadena";
		//print $ls_sql;
		$ls_sql = $ls_sql." ORDER BY ".$as_orden;
		$rs_movimientos=$this->io_sql->select($ls_sql);
	
		if(($rs_movimientos==false)&&($this->io_sql->message!=""))
		{
			$lb_valido=false;		
			$this->is_msg_error=$this->fun->uf_convertirmsg($this->io_sql->message);		
		}
		else
		{
			 while($row=$this->io_sql->fetch_row($rs_movimientos))
			 {
				$li_temp=$li_temp+1;
				$ls_numdoc=$row["numdoc"];
				$ld_fecmov=$this->fun->uf_convertirfecmostrar($row["fecmov"]);
				$ls_conmov=$row["conmov"];
				$ldec_monto=$row["monto"];//print "Monto$li_temp => ".$ldec_monto.'<br>';
				$ls_codope=$row["codope"];
				$ls_estmov=$row["estmov"];
				$li_estcon_mov=$row["estcon"];
				$ld_feccon=$this->fun->uf_convertirfecmostrar($row["feccon"]);
				$ld_feccon_aux=substr($ld_feccon,3,2).substr($ld_feccon,6,4);
				if(($li_estcon_mov==1)&&($ld_feccon_aux==$ls_mesano))
				{
					$lb_checked="checked";
				}
				else
				{
					$lb_checked="";				
				}
				
				$ls_estreglib=$row["estreglib"];
				if(($li_estcon==1)||(($ld_feccon_aux!=$ls_mesano)&&($ld_feccon_aux!='011900')))				
				{
					$lb_concil="onClick='return false;'";
					$cant_noconciliado=$cant_noconciliado+1;
				}
				else
				{
					$cant_conciliado=$cant_conciliado+1;
					$lb_concil="onClick=javascript:uf_selected($li_temp)";
				}
				$object[$li_temp][1]  = "<input name=chk".$li_temp." type=checkbox id=chk".$li_temp." value=1 class=sin-borde ".$lb_concil."    ".$lb_checked."><input type=hidden name=hidchange".$li_temp." id=hidchange".$li_temp."  value='0'>";
				$object[$li_temp][2]  = "<input type=text name=txtnumdoc".$li_temp." id=txtnumdoc".$li_temp." value='".$ls_numdoc."' class=sin-borde readonly style=text-align:center size=18 maxlength=15>";
				$object[$li_temp][3]  = "<input type=text name=txtfecmov".$li_temp." id=txtfecmov".$li_temp." value='".$ld_fecmov."' class=sin-borde readonly style=text-align:center size=10 maxlength=10>";
				$object[$li_temp][4]  = "<input type=text name=txtconmov".$li_temp." id=txtconmov".$li_temp." value='".$ls_conmov."' class=sin-borde readonly style=text-align:left size=20 maxlength=20>";
				$object[$li_temp][5]  = "<input type=text name=txtmonto".$li_temp."  id=txtmonto".$li_temp."  value='".number_format($ldec_monto,2,",",".")."' class=sin-borde readonly style=text-align:right size=20 maxlength=20>";
				$object[$li_temp][6]  = "<input type=text name=txtcodope".$li_temp." id=txtcodope".$li_temp." value='".$ls_codope."' class=sin-borde readonly style=text-align:center size=5 maxlength=5>";				
				$object[$li_temp][7]  = "<input type=text name=txtestmov".$li_temp." id=txtestmov".$li_temp." value='".$ls_estmov."' class=sin-borde readonly style=text-align:center size=5 maxlength=5>";				
				$object[$li_temp][8]  = "<input type=text name=txtfeccon".$li_temp." id=txtfeccon".$li_temp." value='".$ld_feccon."' class=sin-borde readonly style=text-align:center size=10 maxlength=10>";				
				$object[$li_temp][9] = "<input type=text name=txtestreglib".$li_temp." id=txtestreglib".$li_temp." value='".$ls_estreglib."' class=sin-borde readonly style=text-align:center size=5 maxlength=5>";				
			}
				if($li_temp==0)
				{
					$li_temp=1;
					$object[$li_temp][1]  = "<input name=chk".$li_temp." type=checkbox id=chk".$li_temp." value=1 class=sin-borde onClick=javascript:uf_selected($li_temp);><input type=hidden name=hidchange".$li_temp." id=hidchange".$li_temp." value='0'>";
					$object[$li_temp][2]  = "<input type=text name=txtnumdoc".$li_temp." value='' class=sin-borde readonly style=text-align:center size=18 maxlength=15>";
					$object[$li_temp][3]  = "<input type=text name=txtfecmov".$li_temp." value='' class=sin-borde readonly style=text-align:center size=10 maxlength=10>";
					$object[$li_temp][4]  = "<input type=text name=txtconmov".$li_temp." value='' class=sin-borde readonly style=text-align:left size=20 maxlength=20>";
					$object[$li_temp][5]  = "<input type=text name=txtmonto".$li_temp."  value='".number_format(0,2,",",".")."' class=sin-borde readonly style=text-align:right size=20 maxlength=20>";
					$object[$li_temp][6]  = "<input type=text name=txtcodope".$li_temp." value='' class=sin-borde readonly style=text-align:center size=5 maxlength=5>";				
					$object[$li_temp][7]  = "<input type=text name=txtestmov".$li_temp." value='' class=sin-borde readonly style=text-align:center size=5 maxlength=5>";				
					$object[$li_temp][8]  = "<input type=text name=txtfeccon".$li_temp." value='' class=sin-borde readonly style=text-align:center size=10 maxlength=10>";				
					$object[$li_temp][9]  = "<input type=text name=txtestreglib".$li_temp." value='' class=sin-borde readonly style=text-align:center size=5 maxlength=5>";			
				}
				$this->io_sql->free_result($rs_movimientos);		
		}
		 $ls_sql=" SELECT numdoc,fecmov,conmov,(monmov-monret) as monto,codope,estmov,estcon,fecmesano,esterrcon
				   FROM   scb_errorconcbco 
				   WHERE  codban='".$as_codban."' AND ctaban ='".$as_ctaban."' AND codemp='".$ls_codemp."' 
				   AND    ((fecmesano='1900-01-01' AND fecmov<='".$ld_fechasta."') OR  (fecmesano = '".$ld_fecdes."') OR (fecmov<='".$ld_fechasta."' AND fecmesano>'".$ld_fecdes."' ))";
		 $ls_sql = $ls_sql." ORDER BY ".$as_orden;//print $ls_sql;

		$rs_movimientos=$this->io_sql->select($ls_sql);
	
		if(($rs_movimientos==false)&&($this->io_sql->message!=""))
		{
			$lb_valido=false;		
			$this->is_msg_error=$this->fun->uf_convertirmsg($this->io_sql->message);		
			print $this->io_sql->message;
		}
		else
		{
			 while($row=$this->io_sql->fetch_row($rs_movimientos))
			 {
				$li_temp=$li_temp+1;
				$ls_numdoc=$row["numdoc"];
				$ld_fecmov=$this->fun->uf_convertirfecmostrar($row["fecmov"]);
				$ls_conmov=$row["conmov"];
				$ldec_monto=$row["monto"];
				$ls_codope=$row["codope"];
				$ls_estmov=$row["estmov"];
				$li_estcon_mov=$row["estcon"];
				$ld_feccon=$this->fun->uf_convertirfecmostrar($row["fecmesano"]);
				$ld_feccon_aux=substr($ld_feccon,3,2).substr($ld_feccon,6,4);
				if(($li_estcon_mov==1)&&($ld_feccon_aux==$ls_mesano))
				{
					$lb_checked="checked";
				}
				else
				{
					$lb_checked="";				
				}	
				$ls_estreglib=$row["esterrcon"];
				if(($li_estcon==1)||(($ld_feccon_aux!=$ls_mesano)&&($ld_feccon_aux!='011900')))				
				{
					$lb_concil="onClick='return false;'";
					$cant_noconciliado=$cant_noconciliado+1;
				}
				else
				{
					$cant_conciliado=$cant_conciliado+1;
					$lb_concil="onClick=javascript:uf_selected($li_temp)";
				}
				$object[$li_temp][1] = "<input name=chk".$li_temp." type=checkbox id=chk".$li_temp." value=1 class=sin-borde ".$lb_concil."    ".$lb_checked."><input type=hidden name=hidchange".$li_temp." id=hidchange".$li_temp."  value='0'>";
				$object[$li_temp][2] = "<input type=text name=txtnumdoc".$li_temp." id=txtnumdoc".$li_temp." value='".$ls_numdoc."' class=sin-borde readonly style=text-align:center size=18 maxlength=15>";
				$object[$li_temp][3] = "<input type=text name=txtfecmov".$li_temp." id=txtfecmov".$li_temp." value='".$ld_fecmov."' class=sin-borde readonly style=text-align:center size=10 maxlength=10>";
				$object[$li_temp][4] = "<input type=text name=txtconmov".$li_temp." id=txtconmov".$li_temp." value='".$ls_conmov."' class=sin-borde readonly style=text-align:left size=20 maxlength=20>";
				$object[$li_temp][5] = "<input type=text name=txtmonto".$li_temp."  id=txtmonto".$li_temp."  value='".number_format($ldec_monto,2,",",".")."' class=sin-borde readonly style=text-align:right size=20 maxlength=20>";
				$object[$li_temp][6] = "<input type=text name=txtcodope".$li_temp." id=txtcodope".$li_temp." value='".$ls_codope."' class=sin-borde readonly style=text-align:center size=5 maxlength=5>";				
				$object[$li_temp][7] = "<input type=text name=txtestmov".$li_temp." id=txtestmov".$li_temp." value='".$ls_estmov."' class=sin-borde readonly style=text-align:center size=5 maxlength=5>";				
				$object[$li_temp][8] = "<input type=text name=txtfeccon".$li_temp." id=txtfeccon".$li_temp." value='".$ld_feccon."' class=sin-borde readonly style=text-align:center size=10 maxlength=10>";				
				$object[$li_temp][9] = "<input type=text name=txtestreglib".$li_temp." id=txtestreglib".$li_temp." value='".$ls_estreglib."' class=sin-borde readonly style=text-align:center size=5 maxlength=5>";				
			}
				if($li_temp==0)
				{
					$li_temp=1;
					$object[$li_temp][1] = "<input name=chk".$li_temp." type=checkbox id=chk".$li_temp." value=1 class=sin-borde onClick=javascript:uf_selected($li_temp);><input type=hidden name=hidchange".$li_temp." id=hidchange".$li_temp."  value='0'>";
					$object[$li_temp][2] = "<input type=text name=txtnumdoc".$li_temp." value='' class=sin-borde readonly style=text-align:center size=18 maxlength=15>";
					$object[$li_temp][3] = "<input type=text name=txtfecmov".$li_temp." value='' class=sin-borde readonly style=text-align:center size=10 maxlength=10>";
					$object[$li_temp][4] = "<input type=text name=txtconmov".$li_temp." value='' class=sin-borde readonly style=text-align:left size=20 maxlength=20>";
					$object[$li_temp][5] = "<input type=text name=txtmonto".$li_temp."  value='".number_format(0,2,",",".")."' class=sin-borde readonly style=text-align:right size=20 maxlength=20>";
					$object[$li_temp][6] = "<input type=text name=txtcodope".$li_temp." value='' class=sin-borde readonly style=text-align:center size=5 maxlength=5>";				
					$object[$li_temp][7] = "<input type=text name=txtestmov".$li_temp." value='' class=sin-borde readonly style=text-align:center size=5 maxlength=5>";				
					$object[$li_temp][8] = "<input type=text name=txtfeccon".$li_temp." value='' class=sin-borde readonly style=text-align:center size=10 maxlength=10>";				
					$object[$li_temp][9] = "<input type=text name=txtestreglib".$li_temp." value='' class=sin-borde readonly style=text-align:center size=5 maxlength=5>";			
				}
				$this->io_sql->free_result($rs_movimientos);		
		}
		$li_rows=$li_temp;
		return $lb_valido;
	}
	
	function uf_procesar_conciliacion($arr_concil,$as_codemp,$as_codban,$as_ctaban)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//  Funtion	    :  uf_procesar_conciliacion
		//	Arguments   :  $arr_concil : Arreglo con los valores de la conciliacion
		//				   $li_estcierre_con  : Estatus de cierre de la conciliacion
		//	Return	    :  $lb_valido
		//	Descripcion :  Funcion que se encarga de actualizar los estatus de los movimientos conciliadosjunto con la fecha 
		//				   y registra un movimiento de conciliacion en la tabla scb_conciliacion referente al periodo, banco
		//				   y cuenta conciliado y con un estatus de si la conciliacion fue cerrada o no.
		///////////////////////////////////////////////////////////////////////////// ///////////////////////////////////////
	
		$ldec_salseglib	   = 0;
		$ldec_salsegban    = 0;
		$ldec_conciliacion = 0;
		$lb_valido         = true;
		
		$ldec_salseglib      = $arr_concil["salseglib"];
		$ldec_salsegban      = $arr_concil["salsegban"];
		$ldec_conciliacion   = $arr_concil["conciliacion"];
		$li_est_conciliacion = $arr_concil["estcon"];
		$ls_mesano           = $arr_concil["mesano"];
		 
		$lb_existe = $this->uf_select_conciliacion($as_codemp,$as_codban,$as_ctaban,$ls_mesano);
		if (!$lb_existe)
		   { 		
		     $ls_sql = "INSERT INTO scb_conciliacion(codemp,codban,ctaban,salseglib,salsegbco,conciliacion,mesano,estcon)
						VALUES ('".$as_codemp."','".$as_codban."','".$as_ctaban."',".$ldec_salseglib.",".$ldec_salsegban.",".$ldec_conciliacion.",'".$ls_mesano."',".$li_est_conciliacion.")";
			 $ls_descripcion="Se creo la conciliacion del banco ".$as_codban." de cuenta ".$as_ctaban." de fecha ".$ls_mesano."";
		  	 if ($li_est_conciliacion == 1)
			      $ls_descripcion+=". La Conciliación esta cerrada";
			 else
			   $ls_descripcion+=". La Conciliación esta abierta";
			   $ls_evento="INSERT";
	  	   }
		else 
		   {
		     $ls_sql = "UPDATE scb_conciliacion
						   SET	salseglib=".$ldec_salseglib.",salsegbco=".$ldec_salsegban.",
						        conciliacion=".$ldec_conciliacion.",mesano='".$ls_mesano."',estcon=".$li_est_conciliacion."
						  WHERE codban ='".$as_codban."' 
						    AND ctaban='".$as_ctaban."' 
							AND codemp='".$as_codemp."' 
							AND mesano='".$ls_mesano."'";
				$ls_descripcion="Se actualizo la conciliacion del banco ".$as_codban." de cuenta ".$as_ctaban." de fecha ".$ls_mesano."";
				if($li_est_conciliacion == 1)
					$ls_descripcion+=". La Conciliación esta cerrada";
				else
					$ls_descripcion+=". La Conciliación esta abierta";
				$ls_evento="UPDATE";
		   }
		$rs_data = $this->io_sql->execute($ls_sql);
		if ($rs_data===false)
		   {	
			 $lb_valido=false;
			 print $this->io_sql->message;
			 $this->is_msg_error="Error al registrar conciliacion, ".$this->fun->uf_convertirmsg($this->io_sql->message);
		   }	
		else
		   {
		   $this->io_rcbsf->io_ds_datos->insertRow("campo","salseglibaux");
			 $this->io_rcbsf->io_ds_datos->insertRow("monto",$ldec_salseglib);

			 $this->io_rcbsf->io_ds_datos->insertRow("campo","salsegbcoaux");
			 $this->io_rcbsf->io_ds_datos->insertRow("monto",$ldec_salsegban);
			
			 $this->io_rcbsf->io_ds_datos->insertRow("campo","conciliacionaux");
			 $this->io_rcbsf->io_ds_datos->insertRow("monto",$ldec_conciliacion);
			
			 $this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
			 $this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codemp);
			 $this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			 $this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
			 $this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codban);
			 $this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

			 $this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
			 $this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_ctaban);
			 $this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			 $this->io_rcbsf->io_ds_filtro->insertRow("filtro","mesano");
			 $this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_mesano);
			 $this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

			 $lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scb_conciliacion",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$this->la_security);
			 
			 //////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
			 //$ls_evento="UPDATE";
			 //$ls_descripcion="Actualizo el movimiento bancario de operacion ".$ls_codope." numero ".$ls_numdoc." para el banco ".$ls_codban." cuenta ".$ls_ctaban." por un monto de ".$ldec_monto;
			 $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->la_security["empresa"],$this->la_security["sistema"],$ls_evento,$this->la_security["logusr"],$this->la_security["ventanas"],$ls_descripcion);
			 ////////////////////////////////////////////////////////////////////////////////////////////////////////////									
		   }
		return $lb_valido;
	}
	
function uf_update_movimientos($as_codemp,$as_codban,$as_ctaban,$as_numdoc,$as_codope,$ai_estcon,$ad_feccon)
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function: uf_update_movimientos
//		   Access: private
//	    Arguments: 
//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
//	  Description: Función que actualiza los movimientos bancarios relacionados a las conciliaciones bancarias.
//	   Creado Por: Ing. Nestor Falcón.
// Fecha Creación: 30/07/2007 								Fecha Última Modificación : 30/07/2007
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $lb_valido = true;
	$ls_sql    = "UPDATE scb_movbco
			         SET estcon = '".$ai_estcon."',
				         feccon = '".$ad_feccon."'
			  	   WHERE codemp = '".$as_codemp."' 
			         AND codban = '".$as_codban."' 
					 AND ctaban = '".$as_ctaban."' 
					 AND numdoc = '".$as_numdoc."'
					 AND codope = '".$as_codope."'";
    $rs_data = $this->io_sql->execute($ls_sql);
	if ($rs_data===false)
	   {
	     $lb_valido = false;
		 $this->is_msg_error="Error al registrar conciliacion,Error en Update Movimientos Bancarios, ".$this->fun->uf_convertirmsg($this->io_sql->message);
	   }
    else
	   {
	 	 ///////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
	  	 $ls_evento="UPDATE";
	 	 $ls_descripcion="El movimiento bancario de operacion ".$as_codope." numero ".$as_numdoc." 
		                  banco ".$as_codban." cuenta ".$as_ctaban." fue conciliado ";
	 	 $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->la_security["empresa"],$this->la_security["sistema"],$ls_evento,$this->la_security["logusr"],$this->la_security["ventanas"],$ls_descripcion);
		 ////////////////////////////////////////////////////////////////////////////////////////////////////////////			
	   }
	return $lb_valido;
}	
	
	function uf_select_conciliacion($as_codemp,$as_codban,$as_ctaban,$as_mesano)
	{
		//////////////////////////////////////////////////////////////////////
		// Function :  Uf_select_conciliacion
		//
		// Descripcion : Metodo que se encarga de verificar si una conciliacin existe o no
		// 					 
		// Return :  Boolean    
		//
		/////////////////////////////////////////////////////////////////////////
		
		$lb_valido = false;
		$ls_sql="SELECT estcon
			 	 FROM   scb_conciliacion
				 WHERE  codemp ='".$as_codemp."' 
				   AND codban ='".$as_codban."' 
				   AND ctaban = '".$as_ctaban."' 
				   AND mesano='".$as_mesano."'";
		$rs_data = $this->io_sql->select($ls_sql);
		if ($rs_data===false)
		   {
			 $lb_valido=false;
			 $this->is_msg_error="Error en select conciliacion,".$this->fun->uf_convertirmsg($this->io_sql->message);
		   }
		else
		   {
		     if ($row=$this->io_sql->fetch_row($rs_data))
			    {
				  $lb_valido=true;	
			    }
		   } 
		return $lb_valido;
	}
	
	
	
	
	function uf_calcular_errorbco($as_codban,$as_ctaban,$as_mesano)
	{
		/////////////////////////////////////////////////////////////////////////////
		//  Funtion	    :    uf_calcular_errrobco
		//	Arguments   :	
		//					-$as_codban : Banco a conciliar
		//					-$as_ctaban : Cuenta del banco a conciliar
		//					-$as_mesano : Peridio a conciliar
		//	Return	    :   $ldec_saldo
		//	Descripcion :  Fucnion que se encarga de obtener el monto de los movimientos registraods por errores de banco
		///////////////////////////////////////////////////////////////////////////// 
		
		$ldec_errorbco=0;$ldec_monto_haber=0;$ldec_monto_debe=0;
		
		$ls_codemp = $this->dat["codemp"];
		
		$ls_sql="SELECT monhab,mondeb,(mondeb - monhab) As saldo
				 FROM ( SELECT COALESCE( SUM(monmov - monret),0) As monhab
				 		FROM  scb_errorconcbco 
						WHERE codban='".$as_codban."' AND ctaban='".$as_ctaban."' AND 
	 				    (codope='RE' OR codope='ND' OR codope='CH') AND estmov<>'A' AND estmov<>'O' AND codemp='".$ls_codemp."' AND fecmesano ='".$as_mesano."') D,
					  ( SELECT COALESCE( SUM(monmov - monret ),0) As mondeb
	  		  		    FROM scb_errorconcbco
						WHERE codban='".$as_codban."' AND ctaban='".$as_ctaban."' AND 
						(codope='NC' OR codope='DP') AND estmov<>'A' AND estmov<>'O' AND codemp='".$ls_codemp."' AND fecmesano ='".$as_mesano."') H ";
														
		$rs_saldos=$this->io_sql->select($ls_sql);
		
		if(($rs_saldos==false)&&($this->io_sql->message!=""))
		{
			print "Error banco".$this->io_sql->message;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_saldos))
			{
				$ldec_mondeb=$row["mondeb"];
				$ldec_mondeb=$row["monhab"];
				$ldec_errorbco=$row["saldo"];
				if(is_null($ldec_errorbco))
				{	
					$ldec_errorbco=0;
				}
				if( (is_null($ldec_monto_debe) )&&($ldec_monto_haber>0) )
				{
					$ldec_errorbco=$ldec_monto_haber;
				} 
				if( (is_null($ldec_monto_haber))&&($ldec_monto_debe>0) )
				{
					$ldec_errorbco=$ldec_monto_debe;
				}
				
			}	
			$this->io_sql->free_result($rs_saldos);
			
		}	
	
		return  $ldec_errorbco;	
	}
	
	function uf_calcular_errorlib($as_codban,$as_ctaban,$ad_fecha)
	{
	/////////////////////////////////////////////////////////////////////////////
	// Funtion	  :  uf_calcular_errorlibro
	//	Return	   :	ldec_saldo
	//	Descripcion :  Fucnion que se encarga de obtener el saldo de los errores en libro
	///////////////////////////////////////////////////////////////////////////// 
	
	$ldec_monto_haber=0;$ldec_monto_debe=0;$ldec_saldo=0;
	$ls_codemp = $this->dat["codemp"];
	
	$ls_fecha = "01/".(substr($ad_fecha,3,7));
	
	$ld_fecini = $ls_fecha;
	
	$ld_fecha = $this->fun->uf_convertirdatetobd($ad_fecha);
	$ld_fecini = $this->fun->uf_convertirdatetobd($ld_fecini);
	
	
	$ls_sql="SELECT monhab,mondeb,(mondeb - monhab) As saldo
			 FROM ( SELECT COALESCE( SUM(monto - monret),0) As monhab
				   FROM  scb_movbco 
				   WHERE codban='".$as_codban."' AND ctaban='".$as_ctaban."' AND  
	  			   (codope='RE' OR codope='ND' OR codope='CH') AND estmov<>'A' AND estmov<>'O' 
					 AND estreglib='B' AND codemp='".$ls_codemp."' AND 
					 fecmov>='".$ld_fecini."' AND fecmov<='".$ld_fecha."') D,
				 ( SELECT COALESCE( SUM(monto - monret),0) As mondeb
				   FROM scb_movbco
				   WHERE codban='".$as_codban."' AND ctaban='".$as_ctaban."' AND  
	 			   (codope='NC' OR codope='DP') AND estmov<>'A' AND estmov<>'O' AND estreglib='B' AND codemp='".$ls_codemp."' AND fecmov>='".$ld_fecini."' AND fecmov<='".$ld_fecha."') H ";
																											
//	print $ls_sql;	
	$rs_saldos=$this->io_sql->select($ls_sql);
		
		if(($rs_saldos==false)&&($this->io_sql->message!=""))
		{
			print "Error libro".$this->io_sql->message;
		}
		else
		{
		
			if($row=$this->io_sql->fetch_row($rs_saldos))
			{
				$ldec_mondeb=$row["mondeb"];
				$ldec_monhab=$row["monhab"];
				$ldec_saldo=$row["saldo"];
				
				if(is_null($ldec_saldo))
				{	
					$ldec_saldo=0;
				}
				if( (is_null($ldec_monto_debe) )&&($ldec_monto_haber>0) )
				{
					$ldec_saldo=$ldec_monto_haber;
				} 
				if( (is_null($ldec_monto_haber))&&($ldec_monto_debe>0) )
				{
					$ldec_saldo=$ldec_monto_debe;
				}
			}
			$this->io_sql->free_result($rs_saldos);			
		}			
	
	return  $ldec_saldo;
	}
	
	
	function uf_calcular_tranoregban($as_codban,$as_ctaban,$ad_fecha)
	{
	/////////////////////////////////////////////////////////////////////////////
	// Funtion	  : uf_calcular_tranoregban
	//
	//	Return	   :	ldec_saldo
	//
	//	Descripcion :  Fucnion que se encarga de obtener el saldo de los movimientos no registrdos en banco
	///////////////////////////////////////////////////////////////////////////// 
	$ldec_monto_haber=0;$ldec_monto_debe=0;$ldec_saldo=0;
	$ls_codemp = $this->dat["codemp"];
	$ld_fecha = $this->fun->uf_convertirdatetobd($ad_fecha);
	$ds_debe=new class_datastore();
	$ds_haber=new class_datastore();
	//--------------------DEBE------------------------------------//	
	$ls_sql="SELECT (monto - monret) As mondeb,estmov
		   FROM scb_movbco
		   WHERE codban='".$as_codban."' AND ctaban='".$as_ctaban."' AND  
		   (codope='NC' OR codope='DP')  
		   AND ((estreglib<>'A' AND estreglib<>'B') or (estreglib IS NULL)) AND codemp='".$ls_codemp."' 
		   AND fecmov<='".$ld_fecha."' AND (feccon>'".$ld_fecha."' OR estcon=0)";//print $ls_sql;
		
	$rs_saldos=$this->io_sql->select($ls_sql);
	if(($rs_saldos==false)&&($this->io_sql->message!=""))
	{
		print "Saldolibro".$this->io_sql->message;
	}
	else
	{
	    while($row=$this->io_sql->fetch_row($rs_saldos))
		{
			//$ds_haber->insertRow("numdoc", $row["numdoc"]);
			$ds_debe->insertRow("mondeb", $row["mondeb"]);
			//$ds_haber->insertRow("codope", $row["codope"]);
			//$ds_haber->insertRow("fecmov", $row["fecmov"]);
			$ds_debe->insertRow("estmov", $row["estmov"]);	
			//$ds_haber->insertRow("ctaban", $row["ctaban"]);	
			//$ds_haber->insertRow("codban", $row["codban"]);			
		}
	}
	$this->io_sql->free_result($rs_saldos);
	
//-----------------------HABER---------------------------------------//
	$ls_sql="SELECT (monto - monret) As monhab,estmov
		   FROM  scb_movbco 
		   WHERE codban='".$as_codban."' AND ctaban='".$as_ctaban."' AND  
		   (codope='RE' OR codope='ND' OR codope='CH') 
		    AND ((estreglib<>'A' AND estreglib<>'B') or (estreglib IS NULL)) AND codemp='".$ls_codemp."' 
		   AND fecmov<='".$ld_fecha."'  AND (feccon>'".$ld_fecha."' OR estcon=0 )";
		    
	$rs_saldos=$this->io_sql->select($ls_sql);
	if(($rs_saldos==false)&&($this->io_sql->message!=""))
	{
		print "Saldolibro".$this->io_sql->message;
	}
	else
	{
	    while($row=$this->io_sql->fetch_row($rs_saldos))
		{
			//$ds_debe->insertRow("numdoc", $row["numdoc"]);
			$ds_haber->insertRow("monhab", $row["monhab"]);
			//$ds_debe->insertRow("codope", $row["codope"]);
		//	$ds_debe->insertRow("fecmov", $row["fecmov"]);
			$ds_haber->insertRow("estmov", $row["estmov"]);	
			///$ds_debe->insertRow("ctaban", $row["ctaban"]);
			//$ds_debe->insertRow("codban", $row["codban"]);		
		}
	}
	$this->io_sql->free_result($rs_saldos);	
	//print_r ($ds_haber->data);
	$li_totdebe=$ds_debe->getRowCount("estmov");
	$ldec_totdeb=0;
	$ldec_totdeb_anulado=0;
	for($li_i=1;$li_i<=$li_totdebe;$li_i++)
	{
		//$ls_numdoc=$ds_debe->getValue("numdoc",$li_i);
		//$ls_codban=$ds_debe->getValue("codban",$li_i);
		//$ls_codope=$ds_debe->getValue("codope",$li_i);
		//$ls_ctaban=$ds_debe->getValue("ctaban",$li_i);
		$ls_estmov=$ds_debe->getValue("estmov",$li_i);
		$ls_mondeb=$ds_debe->getValue("mondeb",$li_i);
		if($ls_estmov!='A')
		{
			$ldec_totdeb+=$ls_mondeb;
		}
		else
		{
			$ldec_totdeb_anulado+=$ls_mondeb;
		}		
	}
	
	$ldec_totdeb=$ldec_totdeb-$ldec_totdeb_anulado;
	$li_tothaber=$ds_haber->getRowCount("estmov");
	$ldec_tothab=0;
	$ldec_tothab_anulado=0;
	for($li_i=1;$li_i<=$li_tothaber;$li_i++)
	{
		//$ls_numdoc=$ds_haber->getValue("numdoc",$li_i);
		//$ls_codban=$ds_haber->getValue("codban",$li_i);
		//$ls_codope=$ds_haber->getValue("codope",$li_i);
		//$ls_ctaban=$ds_haber->getValue("ctaban",$li_i);
		$ls_estmov=$ds_haber->getValue("estmov",$li_i);
		$ls_monhab=$ds_haber->getValue("monhab",$li_i);
		if($ls_estmov!='A')
		{
			$ldec_tothab+=$ls_monhab;
		}
		else
		{
			$ldec_tothab_anulado+=$ls_monhab;
		}		
	}
	//print "tot debe $ldec_tothab anulado $ldec_tothab_anulado";
	$ldec_tothab=$ldec_tothab-$ldec_tothab_anulado;
	$ldec_saldo=$ldec_totdeb-$ldec_tothab;
	
	/*if($ldec_totdeb==0 && $ldec_tothab>0)
		$ldec_saldo=$ldec_tothab;
	if($ldec_tothab==0 && $ldec_totdeb>0)
		$ldec_saldo=$ldec_totdeb;*/
	
	return $ldec_saldo;		
	
	}
		
		
	function uf_calcular_tranoreglib($as_codban,$as_ctaban,$ad_fecha)
	{
	/////////////////////////////////////////////////////////////////////////////
	// Funtion	    :  uf_calcular_tranoreglib
	//
	//	Return	    :  ldec_saldo
	//
	//	Descripcion :  Fucnion que se encarga de obtener el saldo de los movimientos no registrdos en libro
	///////////////////////////////////////////////////////////////////////////// 
//print "fecha entra $ad_fecha<br>";
	$ldec_monto_haber=0;$ldec_monto_debe=0;$ldec_saldo=0;
	
	$ls_codemp = $this->dat["codemp"];	
	
	$ld_fecha = $this->fun->uf_convertirdatetobd($ad_fecha);
//print "fecha despues $ld_fecha<br>";		
	$ls_sql="SELECT monhab,mondeb,(mondeb - monhab) As saldo
			 FROM ( SELECT COALESCE( SUM(monto - monret),0) As monhab
				   FROM  scb_movbco 
				   WHERE codban='".$as_codban."' AND ctaban='".$as_ctaban."' AND  
	  			   (codope='RE' OR codope='ND' OR codope='CH') AND  estmov<>'A' 
					 AND estmov<>'O'  AND estreglib='A'  AND codemp='".$ls_codemp."' 
					 AND fecmov<='".$ld_fecha."') D,
				 ( SELECT COALESCE( SUM(monto - monret),0) As mondeb
				   FROM scb_movbco
				   WHERE codban='".$as_codban."' AND ctaban='".$as_ctaban."' AND  
	 			   (codope='NC' OR codope='DP') AND estmov<>'A' AND estmov<>'O'  
					AND estreglib='A' AND codemp='".$ls_codemp."' AND fecmov<='".$ld_fecha."') H ";
																	
		
	$rs_saldos=$this->io_sql->select($ls_sql);
		
		if(($rs_saldos==false)&&($this->io_sql->message!=""))
		{
			print "tranoreglib".$this->io_sql->message;
		}
		else
		{
		
			if($row=$this->io_sql->fetch_row($rs_saldos))
			{
				$ldec_mondeb=$row["mondeb"];
				$ldec_monhab=$row["monhab"];
				$ldec_saldo=$row["saldo"];
				
				if(is_null($ldec_saldo))
				{	
					$ldec_saldo=0;
				}
				if( (is_null($ldec_monto_debe) )&&($ldec_monto_haber>0) )
				{
					$ldec_saldo=$ldec_monto_haber;
				} 
				if( (is_null($ldec_monto_haber))&&($ldec_monto_debe>0) )
				{
					$ldec_saldo=$ldec_monto_debe;
				}
			}
			$this->io_sql->free_result($rs_saldos);			
		}			
	
	return  $ldec_saldo;
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
	
	$ls_codemp = $this->dat["codemp"];	
	$ld_fecha=$this->io_fecha->uf_last_day(substr($ad_fecha,0,2),substr($ad_fecha,3,4));
	$ld_fecha = $this->fun->uf_convertirdatetobd($ld_fecha);
	$ds_debe=new class_datastore();
	$ds_haber=new class_datastore();
	//--------------------DEBE------------------------------------//	
	$ls_sql="SELECT (monto - monret) As mondeb,estmov
				   FROM scb_movbco
				   WHERE codban='".$as_codban."' AND ctaban='".$as_ctaban."' AND  
	 			   (codope='NC' OR codope='DP')  
					AND (((estreglib IS NULL) or estreglib<>'A') or (estreglib='A' AND estcon=1)) AND 
					codemp='".$ls_codemp."' AND fecmov<='".$ld_fecha."'";
		//print $ls_sql;
	$rs_saldos=$this->io_sql->select($ls_sql);
	if(($rs_saldos==false)&&($this->io_sql->message!=""))
	{
		print "Saldolibro".$this->io_sql->message;
	}
	else
	{
	    while($row=$this->io_sql->fetch_row($rs_saldos))
		{
			//$ds_haber->insertRow("numdoc", $row["numdoc"]);
			$ds_debe->insertRow("mondeb", $row["mondeb"]);
			//$ds_haber->insertRow("codope", $row["codope"]);
			//$ds_haber->insertRow("fecmov", $row["fecmov"]);
			$ds_debe->insertRow("estmov", $row["estmov"]);	
			//$ds_haber->insertRow("ctaban", $row["ctaban"]);	
			//$ds_haber->insertRow("codban", $row["codban"]);			
		}
	}
	$this->io_sql->free_result($rs_saldos);
	
//-----------------------HABER---------------------------------------//
	$ls_sql="SELECT (monto - monret) As monhab,estmov, numdoc
				   FROM  scb_movbco 
				   WHERE codban='".$as_codban."' AND ctaban='".$as_ctaban."' AND  
	  			   (codope='RE' OR codope='ND' OR codope='CH'  OR codope='TR') 
					 AND (((estreglib IS NULL) or estreglib<>'A') OR (estreglib='A' AND estcon=1)) AND codemp='".$ls_codemp."'
					  AND fecmov<='".$ld_fecha."'";
	//print $ls_sql;	    
	$rs_saldos=$this->io_sql->select($ls_sql);
	if(($rs_saldos==false)&&($this->io_sql->message!=""))
	{
		print "Saldolibro".$this->io_sql->message;
	}
	else
	{
	    while($row=$this->io_sql->fetch_row($rs_saldos))
		{
			//$ds_debe->insertRow("numdoc", $row["numdoc"]);
			$ds_haber->insertRow("monhab", $row["monhab"]);
			//$ds_debe->insertRow("codope", $row["codope"]);
		//	$ds_debe->insertRow("fecmov", $row["fecmov"]);
			$ds_haber->insertRow("estmov", $row["estmov"]);
		//	$ds_haber->insertRow("numdoc", $row["numdoc"]);	
			///$ds_debe->insertRow("ctaban", $row["ctaban"]);
			//$ds_debe->insertRow("codban", $row["codban"]);
			//if($row["numdoc"]==="NS0000379531")print "Monto: ".$row["monhab"];
		}
	}
	$this->io_sql->free_result($rs_saldos);	
	$li_totdebe=$ds_debe->getRowCount("estmov");
	$ldec_totdeb=0;
	$ldec_totdeb_anulado=0;
	for($li_i=1;$li_i<=$li_totdebe;$li_i++)
	{
		$ls_estmov=$ds_debe->getValue("estmov",$li_i);
		$ls_mondeb=$ds_debe->getValue("mondeb",$li_i);//print "Nom. Doc:".$ls_numdoc." Monto Debe =>".$ls_mondeb.'<br>';
		if($ls_estmov!='A')
		{
			$ldec_totdeb+=$ls_mondeb;
		}
		else
		{
			$ldec_totdeb_anulado+=$ls_mondeb;
		}		
	}
	
	$ldec_totdeb=$ldec_totdeb-$ldec_totdeb_anulado;
	$li_tothaber=$ds_haber->getRowCount("estmov");
	$ldec_tothab=0;
	$ldec_tothab_anulado=0;
	for($li_i=1;$li_i<=$li_tothaber;$li_i++)
	{
		$ls_estmov=$ds_haber->getValue("estmov",$li_i);
		//$ls_numdoc=$ds_haber->getValue("numdoc",$li_i);
		$ls_monhab=$ds_haber->getValue("monhab",$li_i);//print "Nom. Doc:".$ls_numdoc." Monto Haber =>".$ls_monhab.'<br>';
		if($ls_estmov!='A')
		{
			$ldec_tothab+=$ls_monhab;
		}
		else
		{
			$ldec_tothab_anulado+=$ls_monhab;
		}		
	}
	//print substr($ad_fecha,3,4);
	 //AND substr($ad_fecha,4,4)=='2016'
	if((substr($ad_fecha,0,2)=='05') && substr($ad_fecha,3,4)=='2016')$ldec_tothab-=29713.37;
	if((substr($ad_fecha,0,2)=='06') && substr($ad_fecha,3,4)=='2016')$ldec_tothab-=29713.37;
	if((substr($ad_fecha,0,2)=='07') && substr($ad_fecha,3,4)=='2016')$ldec_tothab-=29713.37;
	if((substr($ad_fecha,0,2)=='08') && substr($ad_fecha,3,4)=='2016')$ldec_tothab-=29713.37;
	if((substr($ad_fecha,0,2)=='09') && substr($ad_fecha,3,4)=='2016')$ldec_tothab-=29713.37;
	if((substr($ad_fecha,0,2)=='10') && substr($ad_fecha,3,4)=='2016')$ldec_tothab-=29713.37;
	if((Substr($ad_fecha,0,2)=='11') && substr($ad_fecha,3,4)=='2016')$ldec_tothab-=29713.37;
	if((substr($ad_fecha,0,2)=='12') && substr($ad_fecha,3,4)=='2016')$ldec_tothab-=29713.37;
	if((substr($ad_fecha,0,2)=='12') && substr($ad_fecha,3,4)=='2016')$ldec_tothab-=370.36;
	//print "haber ".$ldec_tothab;
	//print "debe ".$ldec_totdeb;
	//$resta=$ldec_totdeb-$ldec_tothab;;
	//print "resta: ".$resta;
	//print "tot debe $ldec_tothab anulado $ldec_tothab_anulado";
	//print "Anulado =>".$ldec_tothab.'<br>';
	$ldec_tothab=number_format($ldec_tothab,4,'.','');
	$ldec_tothab_anulado=number_format($ldec_tothab_anulado,4,'.','');
	$ldec_tothab=$ldec_tothab-$ldec_tothab_anulado;
	//print "Haber =>".$ldec_tothab.'<br>';
	//print "Debe =>".$ldec_totdeb.'<br>';
	$ldec_saldo=$ldec_totdeb-$ldec_tothab;
	//print "Saldo =>".$ldec_saldo.'<br>';

	
	return $ldec_saldo;	


	}

	function uf_abrir_conciliacion($as_codban,$as_ctaban,$ad_fecha,$ad_fechasta)	
	{
	  /*---------------------------------------------------------------------------
	  	Funcion: uf_abrir_conciliacion
	  	Descripcion: Metodo que permite abrir una conciliacion cerrada
	  	Autor: Ing. Laura Cabré
	  	Fecha: 06/12/2006
	  ----------------------------------------------------------------------------------*/
	 
	  $ls_codemp = $this->dat["codemp"];
	  $ld_fecdes    = date($ad_fecha);
	  $ld_fechasta  = date($ad_fechasta);
	  $ls_mesano    = substr($ld_fechasta,3,2).substr($ld_fechasta,6,4);
	  $ls_sql="UPDATE scb_conciliacion 
	  		   SET estcon=0
			   WHERE  codemp = '".$ls_codemp."' AND codban='".$as_codban."' 
			   AND ctaban='".$as_ctaban."' AND mesano='".$ls_mesano."'";
		//print $ls_sql;		   
		$li_result=$this->io_sql->execute($ls_sql);
				
		if(($li_result==false)&&($this->io_sql->message!=""))
		{	
			$lb_valido=false;
			print $this->io_sql->message;
			$this->is_msg_error="Error en uf_abrir_conciliacion, ".$this->fun->uf_convertirmsg($this->io_sql->message);
			
		}	
		else
		{
			$lb_valido = true;	
			///////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
			$ls_evento="UPDATE";
			$ls_descripcion="La conciliación del Banco ".$as_codban." de la cuenta ".$as_ctaban." 
			del periodo ".$ls_mesano." fue abierta";
			$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->la_security["empresa"],$this->la_security["sistema"],$ls_evento,$this->la_security["logusr"],$this->la_security["ventanas"],$ls_descripcion);
			////////////////////////////////////////////////////////////////////////////////////////////////////////////								
		}
		return $lb_valido;	 
	}	
}
?>
