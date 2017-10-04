<?php
require_once("../shared/class_folder/class_sql.php");
class tepuy_sfa_c_factura
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function tepuy_sfa_c_factura()
	{
		require_once("../shared/class_folder/class_datastore.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/tepuy_include.php");
		require_once("../shared/class_folder/tepuy_c_seguridad.php");
		require_once("../shared/class_folder/class_funciones_db.php");
		require_once("../shared/class_folder/tepuy_c_reconvertir_monedabsf.php");
		require_once("../shared/class_folder/class_funciones.php");
		//require_once("../shared/class_folder/class_datastore.php");
		
		$in=               new tepuy_include();
		$this->con=        $in->uf_conectar();
		$this->io_sql=     new class_sql($this->con);
		$this->seguridad=  new tepuy_c_seguridad();
		$this->fun=        new class_funciones_db($this->con);
		$this->io_msg =    new class_mensajes();
		$this->DS=         new class_datastore();
		$this->io_ds_deducciones=new class_datastore(); // Datastored de Deducciones
		$this->io_funcion= new class_funciones();
		$this->io_rcbsf=   new tepuy_c_reconvertir_monedabsf();
		$this->li_candeccon=$_SESSION["la_empresa"]["candeccon"];
		$this->li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
		$this->li_redconmon=$_SESSION["la_empresa"]["redconmon"];
	}


//---------------------------------------------------------------------------------------------------------
	function uf_sfa_tipocontribuyente($as_codemp)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sfa_tipocontribuyente
		//         Access: public (tepuy_sfa_d_facturar)
		//		Retorna el valor del Tipo de Contribuyente
		//		O=Ordinario	E=Especial // Para ver si aplica o no retenciones
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_tipocont="";
		$ls_sql = "SELECT tipocontribuyente FROM sfa_configuracion WHERE codemp='".$as_codemp."'";
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->factura MÉTODO->uf_sfa_tiporcontribuyente ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_tipocont=$row["tipocontribuyente"];
			}
		}
		//print "Tipo Contribuyente: ".$ls_tipocont;die();
		return $ls_tipocont;

	} // End uf_sfa_tipocontribuyente
//---------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_total($ai_subtotfac,$ai_moniva,$ai_totfac,$ai_deducciones,$ai_totgeneral,$as_tipocontribuyente)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_total
		//		   Access: private
		//	    Arguments: ai_subtotal    // Valor del subtotal
		//				   ai_cargos      // Valor total de los cargos
		//				   ai_total       // Total de la solicitud de pago
		//				   ai_deducciones // Total de deducciones
		//				   ai_totgeneral  // Total General
		//	  Description: Método que imprime los totales de la Recepcion de Documentos
		//	   Creado Por: Ing. Miguel Palencia/ Ing. Juniors Fraga
		// Fecha Creación: 05/04/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		print "<table width='785' height='116' border='0' align='center' cellpadding='0' cellspacing='0' class='celdas-blancas'>";
		print "        <tr class='titulo-celdanew'>";
		print "          <td height='22' colspan='6'><div align='center'>Totales</div></td>";
		print "        </tr>";
		print "        <tr>";
		print "          <td width='200' height='13'>&nbsp;</td>";
		print "          <td width='125' height='13' align='left'></td>";
		print "          <td width='355' height='13' align='right'></td>";
		print "          <td width='100' height='13' align='right'>&nbsp;</td>";
		print "        </tr>";
		print "        <tr>";
		print "          <td height='22'>&nbsp;</td>";
		print "          <td height='22' align='left'></td>";
		print "          <td height='22' align='right'><strong>Subtotal&nbsp;&nbsp;</strong></td>";
/*if($ai_subtotal>$ai_totgeneral)
{
	$ai_subtotal=$ai_totgeneral-$ai_cargos;
	$ai_total=$ai_subtotal+$ai_cargos;
	$ai_totgeneral=$ai_total-$ai_deducciones;
}*/
		print "          <td height='22'><input name='txtsubtotfac'  type='text' id='txtsubtotfac' style='text-align:right' value='".$ai_subtotfac."' size='22' maxlength='20' readonly align='right' class='letras-negrita' ></td>";
		print "        </tr>";
		print "        <tr>";
		print "          <td height='22'>&nbsp;</td>";
		print "          <td height='22' align='left'></td>";
		print "          <td height='22' align='right'> ";
//		print "          	<input name='btnotroscreditos' type='button' class='boton' id='btnotroscreditos' value='Otros Cr&eacute;ditos' onClick='ue_catalogocreditos();'> ";
		print "          	<input name='btnotroscreditos' type='button' class='boton' id='btnotroscreditos' value='I.V.A.' ;'> ";
		print "          </td>";
		print "          <td height='22'><input name='txttotivafac' type='text' id='txttotivafac' style='text-align:right' value='".$ai_moniva."' size='22' maxlength='20' readonly align='right' class='letras-negrita' ></td>";
		print "        </tr>";
		print "        <tr>";
		print "          <td height='22'>&nbsp;</td>";
		print "          <td height='22'>&nbsp;</td>";
		print "          <td height='22' align='right'><div align='right'><strong>Total&nbsp;&nbsp;</strong></div></td>";
		print "          <td height='22'><input name='txttotfac' type='text' id='txttotfac' style='text-align:right' value='".$ai_totfac."' size='22' maxlength='20' readonly align='right' class='texto-azul' ></td>";
		print "        </tr>";
		print "        <tr>";
		print "          <td height='22'>&nbsp;</td>";
		print "          <td height='22'>&nbsp;</td>";
		print "          <td height='22' align='right'>";
		// Si es contribuyente especial // Puede aplicar retenciones //
		if($as_tipocontribuyente=="C")
		{
			print "          	<input name='btndeducciones' type='button' class='boton' id='btndeducciones' value='Retenciones' onClick='ue_catalogoretenciones();'> ";
			print "			 </td>";
			print "          <td height='22'><input name='txtdeducciones' type='text' id='txtdeducciones' style='text-align:right' value='".$ai_deducciones."' size='22' maxlength='20' readonly align='right' class='texto-rojo'></td>";
		}
		print "        </tr>";
		print "        <tr>";
		print "          <td height='22'>&nbsp;</td>";
		print "          <td height='22'>&nbsp;</td>";
		print "          <td height='22' align='right'><div align='right'><strong>Total General&nbsp;&nbsp;</strong></div></td>";
		print "          <td height='22'><input name='txttotalgener' type='text' id='txttotalgener' style='text-align:right' value='".$ai_totgeneral."' size='22' maxlength='20' readonly align='right' class='letras-negrita' ></td>";
		print "        </tr>";
		print "        <tr>";
		print "          <td height='13' colspan='4'>&nbsp;</td>";
		print "			</tr>";
		print "</table>";
		
	}// end function uf_print_total
	//-----------------------------------------------------------------------------------------------------------------------------------


//------------------------------------------------Pedidos----------------------------------//
	function uf_sfa_insert_pedidos($as_codemp,$as_numpedido,$as_cedcli,$ad_fecrecbd,$as_forpag,$as_obsped,$as_monsubtot,$as_monimp,$as_montot,$as_codusu,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sfa_insert_pedidos
		//         Access: public (tepuy_sfa_d_facturar)
		//      Argumento: $as_codemp    // codigo de empresa               
		//		$as_numpedido // numero del pedido
		// 		$as_cedcli    // cedula del Cliente
		//		$ad_fecrec    // fecha de registro
		//              $as_obsrec    // Notas de la factura
		//		$aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion  que inserta  los  datos del Pedido
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 20/06/2017
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$io_fun=  new class_funciones_db($this->con);
		$est_ped='1';
		$ls_sql="INSERT INTO sfa_pedidos (codemp,numpedido,cedcli,fecpedido,forpagped,estped,obsped,monsubtot,monbasimp,monimp,montot,usuario)".
				" VALUES ('".$as_codemp."', '".$as_numpedido."', '".$as_cedcli."', '".$ad_fecrecbd."', '".$as_forpag."', '".
				$est_ped."', '".$as_obsped."', ".$as_monsubtot.", ".$as_monsubtot.", ".$as_monimp.", ".$as_montot.", '".$as_codusu."')";
		//print $ls_sql;
		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->pedido MÉTODO->uf_sfa_insert_pedido ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;

		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el pedido Nro. ".$as_numpedido.
					 " Asociado a la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
					$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
					$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	} // end function uf_sfa_insert_pedido

//---------------
	function uf_sfa_insert_dt_pedido($as_codemp,$as_numpedido,$as_codpro,$ai_canoripro,$ai_canpro,$ai_poriva,$ai_preunipro,$ai_monsubtotpro,$ai_monsubivapro,$ai_montotpro,$ai_orden,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sfa_insert_dt_pedido
		//         Access: public (tepuy_sfa_d_pedidos)
		//      Argumento: 	$as_codemp       // codigo de empresa
		//			$as_numfactura   // numero de pedido
		//	   		$as_codpro       // codigo de articulo
		//			$ai_canoripro	 // Cantidad En existencia a la fecha de la factura del Producto
		//			$as_unidad       // codigo de unidad de medida
		//	   		$ai_canpro       // cantidad de productos solicitados
		//			$ai_preunipro    // precio unitario del producto
		//			$ai_monsubtotpro // monto sub-total del producto
		//			$ai_montotpro    // monto total del producto
		//			$ai_monsubivapro // monto IVA del producto
		//			$ai_montotart 	 // monto total del producto
		//			$ai_i     	 // orden consecutivo de registro
		//			$aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta detalleS del Pedido
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 15/11/2016
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sfa_dt_pedido (codemp,numpedido,codpro,canquexipro,cantpro,poriva,preunipro,monsubpro,moncarpro,montotpro,orden)".
					" VALUES ('".$as_codemp."','".$as_numpedido."','".$as_codpro."', ".$ai_canoripro.", ".$ai_canpro.", ".
					$ai_poriva.", ".$ai_preunipro.", ".$ai_monsubtotpro.", ".$ai_monsubivapro.", ".$ai_montotpro.",".$ai_orden.")";
		print $ls_sql;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->pedido MÉTODO->uf_sfa_insert_dt_pedido ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Ingreso ".$ai_canpro." Productos ".$as_codpro." Asociado al Pedido ".$as_numpedido." de la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
			$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	}  // end   function uf_sfa_insert_dt_pedido

//---------------
	function uf_sfa_eliminar_pedido($as_codemp,$as_numpedido,$as_cedcli,$ad_fecrecbd,$as_forpag,$as_obsped,$as_monsubtot,$as_monimp,$as_montot,$as_codusu,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sfa_eliminar_pedido
		//         Access: public (tepuy_sfa_d_facturar)
		//      Argumento: $as_codemp    // codigo de empresa               
		//		$as_numpedido // numero del Pedido
		// 		$as_cedcli    // cedula del Cliente
		//		$ad_fecrec    // fecha de registro
		//              $as_obsrec    // Notas del Pedido
		//		$aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion  que elimina  los  datos del Pedido
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 15/11/2016
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$io_fun=  new class_funciones_db($this->con);
		$est_fac='1';
		$ls_sql="DELETE FROM sfa_pedidos WHERE codemp='".$as_codemp."' ".
			" AND numpedido='".$as_numpedido."' ".
			" AND cedcli='".$as_cedcli."' ";

		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->pedido MÉTODO->uf_sfa_eliminar_pedido ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;

		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó el Pedido Nro. ".$as_numpedido.
					 " Asociado a la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
					$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
					$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	} // end function uf_sfa_eliminar_pedido
//--------------------------------------------
	function uf_sfa_eliminar_dt_pedido($as_codemp,$as_numpedido,$as_codpro,$ai_canoripro,$ai_canpro,$ai_poriva,$ai_preunipro,$ai_monsubtotpro,$ai_monsubivapro,$ai_montotpro,$ai_orden,$la_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sfa_eliminar_dt_pedido
		//         Access: public (tepuy_sfa_d_pedidos)
		//      Argumento: 	$as_codemp       // codigo de empresa
		//			$as_numpedido    // numero del Pedido
		//	   		$as_codpro       // codigo de articulo
		//			$ai_canoripro	 // Cantidad En existencia a la fecha del pedido del Producto
		//			$as_unidad       // codigo de unidad de medida
		//	   		$ai_canpro       // cantidad de productos incluidos en el pedido
		//			$ai_preunipro    // precio unitario del producto
		//			$ai_monsubtotpro // monto sub-total del producto
		//			$ai_montotpro    // monto total del producto
		//			$ai_monsubivapro // monto IVA del producto
		//			$ai_montotart 	 // monto total del producto
		//			$ai_i     	 // orden consecutivo de registro
		//			$aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta detalles del Pedido
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 15/11/2016
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$io_fun=  new class_funciones_db($this->con);
		$ls_sql="DELETE FROM sfa_dt_pedido WHERE codemp='".$as_codemp."' ".
			" AND numpedido='".$as_numpedido."' ".
			" AND codpro='".$as_codpro."' ";

		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->pedido MÉTODO->uf_sfa_eliminar_dt_pedidp ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;

		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó el Producto ".$as_codpro." del Pedido Nro. ".$as_numpedido.
					 " Asociado a la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
					$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
					$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	} // end function uf_sfa_eliminar_dt_pedido

	function uf_sfa_obtener_dt_pedido($as_codemp,$as_numpedido,&$ai_totrows,&$ao_object,&$ai_subped,&$ai_totiva,&$ai_totentsum,&$ls_status)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sfa_obtener_dt_pedidos
		//         Access: public (tepuy_sfa_d_pedidos)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			$as_numpedido // numero de Pedido
		//  			   $ai_totrows   // total de filas encontradas
		//  			   $ao_object    // arreglo de objetos para pintar el grid
		//	      Returns: Retorna un Booleano
		//    Description: Funcion  que busca los productos asociados a la factura en la tabla sfa_dt_factura para luego 
		//                 imprimirlos en el grid de la pagina exepto que ya se recibieron por completo.
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 10/11/2016
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql= "SELECT sfa_dt_pedido.*, siv_unidadmedida.denunimed, sfa_pedidos.estped, ".
				  "      (SELECT codunimed FROM sfa_producto ".
				  "	       WHERE sfa_dt_pedido.codpro = sfa_producto.codpro) AS codunimed,".
				  "      (SELECT MAX(denpro) FROM sfa_producto".
				  "        WHERE sfa_dt_pedido.codpro=sfa_producto.codpro) AS denpro".
				  "  FROM sfa_dt_pedido, sfa_pedidos,sfa_producto, siv_unidadmedida ".
				  " WHERE sfa_dt_pedido.codemp=sfa_pedidos.codemp".
				  "   AND sfa_dt_pedido.codpro=sfa_producto.codpro".
				  "   AND sfa_producto.codunimed=siv_unidadmedida.codunimed".
				  "   AND sfa_dt_pedido.numpedido=sfa_pedidos.numpedido".
				  "   AND sfa_dt_pedido.codemp='".$as_codemp."'".
				  "   AND sfa_dt_pedido.codemp=sfa_pedidos.codemp ".
				  "   AND sfa_dt_pedido.numpedido='".$as_numpedido."'".
				  " ORDER BY sfa_dt_pedido.orden ASC";

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->pedido MÉTODO->uf_sfa_obtener_dt_pedido ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			return false;
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_status=	$row["estped"];
				$ls_codart=    $row["codpro"];
				$ls_denart=    $row["denpro"];
				$ls_unidad=    $row["denunimed"];
				//$li_unidad=    $row["unidades"];
				$li_preuniart= $row["preunipro"];
				$li_poriva=    $row["poriva"];
				$li_canoriart= $row["canquexipro"];
				$li_canart=    $row["cantpro"];
				$li_monsubtotart= $row["monsubpro"];
				$li_monsubivaart= $row["moncarpro"];
				$li_montotart= $row["montotpro"];
				$ai_subped=($ai_subped+$li_monsubtotart);
				$ai_totiva=($ai_totiva+$li_monsubivaart);
				$ai_totentsum=($ai_totentsum+$li_montotart);
				$ai_totrows=$ai_totrows+1;
				$ao_object[$ai_totrows][1]="<input name=txtdenart".$ai_totrows."    type=text id=txtdenart".$ai_totrows."    class=sin-borde size=15 maxlength=50 value='".$ls_denart."' readonly><input name=txtcodart".$ai_totrows." type=hidden id=txtcodart".$ai_totrows." class=sin-borde size=20 maxlength=20 value='".$ls_codart."' readonly>";
				$ao_object[$ai_totrows][2]="<div align='center'></div><input name=txtunidad".$ai_totrows."    type=text id=txtunidad".$ai_totrows."    class=sin-borde size=12 maxlength=12 value='".$ls_unidad."' readonly>";
				$ao_object[$ai_totrows][3]="<input name=txtcanoriart".$ai_totrows." type=text id=txtcanoriart".$ai_totrows." class=sin-borde size=12 maxlength=12 value='".number_format ($li_canoriart,2,",",".")."'  readonly>";
				$ao_object[$ai_totrows][4]="<input name=txtcanart".$ai_totrows."    type=text id=txtcanart".$ai_totrows."    class=sin-borde size=10 maxlength=12 value='".number_format ($li_canart,2,",",".")."' readonly>";
				$ao_object[$ai_totrows][5]="<input name=txtpreart".$ai_totrows."    type=text id=txtpreart".$ai_totrows."    class=sin-borde size=10 maxlength=12 value='".number_format ($li_preuniart,2,",",".")."' readonly>";
				$ao_object[$ai_totrows][6]="<input name=txtporiva".$ai_totrows." type=text id=txtporiva".$ai_totrows." class=sin-borde size=8 maxlength=8 value='".number_format ($li_poriva,2,",",".")."' readonly>";
				$ao_object[$ai_totrows][7]="<input name=txtsubtotart".$ai_totrows." type=text id=submontotart".$ai_totrows." class=sin-borde size=14 maxlength=15 value='".number_format ($li_monsubtotart,2,",",".")."' readonly>";
				$ao_object[$ai_totrows][8]="<input name=txtsubivaart".$ai_totrows." type=text id=txtsubivaart".$ai_totrows." class=sin-borde size=14 maxlength=15 value='".number_format ($li_monsubivaart,2,",",".")."' readonly>";
				$ao_object[$ai_totrows][9]="<input name=txttotart".$ai_totrows." type=text id=txttotart".$ai_totrows." class=sin-borde size=14 maxlength=15 value='".number_format ($li_montotart,2,",",".")."' readonly>";
				$ao_object[$ai_totrows][10]="";
				$ao_object[$ai_totrows][11]="";
				//uf_agregarlineablanca($lo_object,$li_totrows);
			}//while
			//die();
		}//else
		if ($ai_totrows==0)
		{$lb_valido=false;}
		$this->io_sql->free_result($rs_data);
		return $lb_valido;
	} // end function uf_sfa_obtener_dt_pedido

//-----------------------------------------Fin de Librerias de Pedidos --------------------//


//---------------------------------------- Facturas ----------------------------------------------------//

	function uf_sfa_select_factura($as_codemp,$as_numfactura)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_select_recepcion
		//         Access: public (tepuy_siv_p_recepcion)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica que exista una entrada de suministo a almacen en la tabla de  siv_recepcion
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 10/02/2006							Fecha Última Modificación : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM sfa_factura  ".
				  " WHERE codemp='".$as_codemp."'".
				  "   AND numfactura='".$as_numfactura."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->factura MÉTODO->uf_sfa_select_factura ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$this->io_sql->free_result($rs_data);
			}
			else
			{
				$lb_valido=false;
			}
		}
		return $lb_valido;
	} // end function uf_sfa_select_factura
//--------------------------------------------------------------------------------------------------------------------------------------
	function uf_sfa_insert_factura($as_codemp,$as_numfactura,$as_cedcli,$ad_fecrecbd,$as_forpag,$as_obsfac,$as_monsubtot,$as_monimp,$as_montot,$as_deducciones,$as_totalgeneral,$as_codusu,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sfa_insert_factura
		//         Access: public (tepuy_sfa_d_facturar)
		//      Argumento: $as_codemp    // codigo de empresa               
		//		$as_numfactura // numero de la Factura
		// 		$as_cedcli    // cedula del Cliente
		//		$ad_fecrec    // fecha de registro
		//              $as_obsrec    // Notas de la factura
		//		$aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion  que inserta  los  datos  de la factura
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 15/11/2016
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$io_fun=  new class_funciones_db($this->con);
		$est_fac='1';
		$ls_sql="INSERT INTO sfa_factura (codemp,numfactura,cedcli,fecfactura,forpagfac,estfac,obsfac,monsubtot,monbasimp,monimp,montot,deducciones,totalgeneral,usuario)".
				" VALUES ('".$as_codemp."', '".$as_numfactura."', '".$as_cedcli."', '".$ad_fecrecbd."', '".$as_forpag."', '".
				$est_fac."', '".$as_obsfac."', ".$as_monsubtot.", ".$as_monsubtot.", ".$as_monimp.", ".$as_montot.", ".$as_deducciones.", ".$as_totalgeneral.", '".$as_codusu."')";
		//print $ls_sql;die();
		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->factura MÉTODO->uf_sfa_insert_factura ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;

		}
		else
		{
			$lb_valido=true;
			if($as_deducciones>0)
			{
				$lb_valido=$this->uf_insert_dt_retenciones($as_codemp,$as_numfactura,$as_cedcli);
			}
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó la Factura Nro. ".$as_numfactura.
					 " Asociado a la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
					$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
					$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	} // end function uf_sfa_insert_factura
//-------------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_dt_retenciones($as_codemp,$as_numfactura,$as_cedcli)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_dt_retenciones
		//		   Access: private
		//	    Arguments: as_numfactura  // Número de Factura 
		//			as_rifbene  // Cédula del Beneficiario
		//			aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta las retenciones de la factura
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/08/2017 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if(array_key_exists("deducciones",$_SESSION))
		{
			$this->io_ds_deducciones->data=$_SESSION["deducciones"];
			$li_totrow=$this->io_ds_deducciones->getRowCount('codded');	
			for($li_fila=1;$li_fila<=$li_totrow;$li_fila++)
			{
				$ls_codded=$this->io_ds_deducciones->getValue('codded',$li_fila);
				$ls_nrocomp=$this->io_ds_deducciones->getValue('documento',$li_fila);
				$li_monobjret=$this->io_ds_deducciones->getValue('monobjret',$li_fila);
				$li_monret=$this->io_ds_deducciones->getValue('monret',$li_fila);
				$ls_sccuenta=$this->io_ds_deducciones->getValue('sccuenta',$li_fila);
				$li_porded=$this->io_ds_deducciones->getValue('porded',$li_fila);
				$ls_procede=$this->io_ds_deducciones->getValue('procededoc',$li_fila);
				$li_monobjret=str_replace(".","",$li_monobjret);
				$li_monobjret=str_replace(",",".",$li_monobjret);
				$li_monret=str_replace(".","",$li_monret);
				$li_monret=str_replace(",",".",$li_monret);

				$ls_sql="INSERT INTO sfa_dt_retenciones (codemp,numfactura,cedcli,codded,procede_doc,".
						"monobjret,monret,porded,sc_cuenta) VALUES ('".$as_codemp."','".$as_numfactura."','".$as_cedcli."','".$ls_codded."','".$ls_procede."', ".$li_monobjret.", "."".$li_monret.",".$li_porded.",'".$ls_sccuenta."')";
				//print $ls_sql;die();
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_msg->message("CLASE->Recepción MÉTODO->uf_insert_dt_retenciones ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
				}

			}
		}
		return $lb_valido;
	}// end function uf_insert_dt_retenciones
	//-----------------------------------------------------------------------------------------------------------------------------------


//-------------------------------------------------------------------------------------------------------------------------------------

	function uf_sfa_eliminar_factura($as_codemp,$as_numfactura,$as_cedcli,$ad_fecrecbd,$as_forpag,$as_obsfac,$as_monsubtot,$as_monimp,$as_montot,$as_deducciones,$as_totalgeneral,$as_codusu,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sfa_eliminar_factura
		//         Access: public (tepuy_sfa_d_facturar)
		//      Argumento: $as_codemp    // codigo de empresa               
		//		$as_numfactura // numero de la Factura
		// 		$as_cedcli    // cedula del Cliente
		//		$ad_fecrec    // fecha de registro
		//              $as_obsrec    // Notas de la factura
		//		$aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion  que elimina  los  datos  de la factura
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 15/11/2016
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$io_fun=  new class_funciones_db($this->con);
		$est_fac='1';
		$ls_sql="DELETE FROM sfa_factura WHERE codemp='".$as_codemp."' ".
			" AND numfactura='".$as_numfactura."' ".
			" AND cedcli='".$as_cedcli."' ";

		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->factura MÉTODO->uf_sfa_eliminar_factura ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;

		}
		else
		{
			$lb_valido=true;
			if($as_deducciones>0)
			{
				$lb_valido=$this->uf_eliminar_dt_retenciones($as_codemp,$as_numfactura,$as_cedcli);
			}
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó la Factura Nro. ".$as_numfactura.
					 " Asociado a la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
					$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
					$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	} // end function uf_sfa_eliminar_factura

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_eliminar_dt_retenciones($as_codemp,$as_numfactura,$as_rifbene)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_eliminar_dt_retenciones
		//		   Access: private
		//	    Arguments: as_numfactura  // Número de Factura 
		//			as_rifbene  // Cédula del Beneficiario
		//			aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que elimina las retenciones de la factura
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 01/08/2017 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE FROM sfa_dt_retenciones WHERE codemp='".$as_codemp."' ".
			" AND numfactura='".$as_numfactura."' ".
			" AND rifcli='".$as_rifbene."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->Factura MÉTODO->uf_eliminar_dt_retenciones ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message)); 
		}
		return $lb_valido;
	}// end function uf_eliminar_dt_retenciones
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	function uf_sfa_anular_factura($as_codemp,$as_numfactura,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sfa_anuklar_factura
		//         Access: public (tepuy_sfa_d_facturar)
		//      Argumento: $as_codemp    // codigo de empresa               
		//		$as_numfactura // numero de la Factura
		//		$aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion  que elimina  los  datos  de la factura
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 16/01/2017
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$io_fun=  new class_funciones_db($this->con);
		$est_fac='3';
		$ls_sql="UPDATE sfa_factura SET estfac='".$est_fac."' ".
			" WHERE codemp='".$as_codemp."' "." AND numfactura='".$as_numfactura."' ";

		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->factura MÉTODO->uf_sfa_anular_factura ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;

		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Anuló la Factura Nro. ".$as_numfactura.
					 " Asociado a la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
					$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
					$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	} // end function uf_sfa_anular_factura
	

	function uf_sfa_insert_dt_factura($as_codemp,$as_numfactura,$as_codpro,$ai_canoripro,$ai_canpro,$ai_poriva,$ai_preunipro,$ai_monsubtotpro,$ai_monsubivapro,$ai_montotpro,$ai_orden,$la_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sfa_insert_dt_factura
		//         Access: public (tepuy_sfa_d_factura)
		//      Argumento: 	$as_codemp       // codigo de empresa
		//			$as_numfactura   // numero de Factura
		//	   		$as_codpro       // codigo de articulo
		//			$ai_canoripro	 // Cantidad En existencia a la fecha de la factura del Producto
		//			$as_unidad       // codigo de unidad de medida
		//	   		$ai_canpro       // cantidad de productos facturados
		//			$ai_preunipro    // precio unitario del producto
		//			$ai_monsubtotpro // monto sub-total del producto
		//			$ai_montotpro    // monto total del producto
		//			$ai_monsubivapro // monto IVA del producto
		//			$ai_montotart 	 // monto total del producto
		//			$ai_i     	 // orden consecutivo de registro
		//			$aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta detalleS de LA FACTURA
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 15/11/2016
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sfa_dt_factura (codemp,numfactura,codpro,canquexipro,cantpro,poriva,preunipro,monsubpro,moncarpro,montotpro,orden)".
					" VALUES ('".$as_codemp."','".$as_numfactura."','".$as_codpro."', ".$ai_canoripro.", ".$ai_canpro.", ".
					$ai_poriva.", ".$ai_preunipro.", ".$ai_monsubtotpro.", ".$ai_monsubivapro.", ".$ai_montotpro.",".$ai_orden.")";
		//print $ls_sql;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->factura MÉTODO->uf_sfa_insert_dt_factura ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Egreso ".$ai_canpro." Productos ".$as_codpro." Asociado a la Factura ".$as_numfactura." de la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
			$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	}  // end   function uf_sfa_insert_dt_factura

	function uf_sfa_eliminar_dt_factura($as_codemp,$as_numfactura,$as_codpro,$ai_canoripro,$ai_canpro,$ai_poriva,$ai_preunipro,$ai_monsubtotpro,$ai_monsubivapro,$ai_montotpro,$ai_orden,$la_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sfa_insert_dt_factura
		//         Access: public (tepuy_sfa_d_factura)
		//      Argumento: 	$as_codemp       // codigo de empresa
		//			$as_numfactura   // numero de Factura
		//	   		$as_codpro       // codigo de articulo
		//			$ai_canoripro	 // Cantidad En existencia a la fecha de la factura del Producto
		//			$as_unidad       // codigo de unidad de medida
		//	   		$ai_canpro       // cantidad de productos facturados
		//			$ai_preunipro    // precio unitario del producto
		//			$ai_monsubtotpro // monto sub-total del producto
		//			$ai_montotpro    // monto total del producto
		//			$ai_monsubivapro // monto IVA del producto
		//			$ai_montotart 	 // monto total del producto
		//			$ai_i     	 // orden consecutivo de registro
		//			$aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta detalleS de LA FACTURA
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 15/11/2016
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$io_fun=  new class_funciones_db($this->con);
		$ls_sql="DELETE FROM sfa_dt_factura WHERE codemp='".$as_codemp."' ".
			" AND numfactura='".$as_numfactura."' ".
			" AND codpro='".$as_codpro."' ";

		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->factura MÉTODO->uf_sfa_eliminar_dt_factura ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;

		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó el Producto ".$as_codpro." de la Factura Nro. ".$as_numfactura.
					 " Asociado a la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
					$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
					$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	} // end function uf_sfa_eliminar_dt_factura


	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_sfa_combo_formapago($as_seleccionado)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sfa_combo_formapago
		//		   Access: private
		//		 Argument: $as_seleccionado // Valor del campo que va a ser seleccionado
		//	  Description: Función que busca en la tabla la forma de pagos registrados
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 14/11/2016
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		//print "Seleccionado= ".$as_seleccionado;
		$ls_sql=" SELECT codfor,denfor ".
                " FROM  sfa_formapago    ".
				" WHERE codfor<>'--'  ".
                " ORDER BY codfor ASC  ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->factura MÉTODO->uf_sfa_c_factura ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			print "<select name='cmbforpag' id='cmbforpag' style='width:120px'>";
			print " <option value='---'>---seleccione---</option>";
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_seleccionado="";
				$ls_codfor=trim($row["codfor"]);
				$ls_denfor=utf8_encode(trim($row["denfor"]));
				if($as_seleccionado==$ls_codfor)
				{
					$ls_seleccionado="selected";
				}
				print "<option value='".$ls_codfor."' ".$ls_seleccionado.">".$ls_denfor."</option>";
			}

			$this->io_sql->free_result($rs_data);
			print "</select>";
		}
		return $lb_valido;
	}// end function uf_sfa_combo_formapago
	//-----------------------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_sfa_combo_tipoproducto($as_seleccionado)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sfa_combo_tipoproducto
		//		   Access: private
		//		 Argument: $as_seleccionado // Valor del campo que va a ser seleccionado
		//	  Description: Función que busca en la tabla la forma de pagos registrados
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 14/11/2016
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		//print "Seleccionado= ".$as_seleccionado;
		$ls_sql=" SELECT codtippro,dentippro ".
                " FROM  sfa_tipoproducto    ".
				" WHERE codtippro<>'--'  ".
                " ORDER BY codtippro ASC  ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->factura MÉTODO->uf_sfa_c_factura ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			print "<select name='cmbtippro' id='cmbtippro' style='width:120px'>";
			print " <option value='----'>---seleccione---</option>";
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_seleccionado="";
				$ls_codtippro=trim($row["codtippro"]);
				$ls_dentippro=utf8_encode(trim($row["dentippro"]));
				if($as_seleccionado==$ls_codfor)
				{
					$ls_seleccionado="selected";
				}
				print "<option value='".$ls_codtippro."' ".$ls_seleccionado.">".$ls_dentippro."</option>";
			}

			$this->io_sql->free_result($rs_data);
			print "</select>";
		}
		return $lb_valido;
	}// end function uf_sfa_combo_tipoproducto
	//-----------------------------------------------------------------------------------------------------------------------------------

///////////////////////////////////////////////////////////////////////////////
	function uf_sfa_obtener_dt_factura($as_codemp,$as_numfactura,&$ai_totrows,&$ao_object,&$ai_subfac,&$ai_totiva,&$ai_totentsum,&$ls_status)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sfa_obtener_dt_factura
		//         Access: public (tepuy_sfa_d_factura)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			$as_numfactura // numero de factura
		//  			   $ai_totrows   // total de filas encontradas
		//  			   $ao_object    // arreglo de objetos para pintar el grid
		//	      Returns: Retorna un Booleano
		//    Description: Funcion  que busca los productos asociados a la factura en la tabla sfa_dt_factura para luego 
		//                 imprimirlos en el grid de la pagina exepto que ya se recibieron por completo.
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 10/11/2016
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql= "SELECT sfa_dt_factura.*, siv_unidadmedida.denunimed, sfa_factura.estfac, ".
				  "      (SELECT codunimed FROM sfa_producto ".
				  "	       WHERE sfa_dt_factura.codpro = sfa_producto.codpro) AS codunimed,".
				  "      (SELECT MAX(denpro) FROM sfa_producto".
				  "        WHERE sfa_dt_factura.codpro=sfa_producto.codpro) AS denpro".
				  "  FROM sfa_dt_factura, sfa_factura,sfa_producto, siv_unidadmedida ".
				  " WHERE sfa_dt_factura.codemp=sfa_factura.codemp".
				  "   AND sfa_dt_factura.codpro=sfa_producto.codpro".
				  "   AND sfa_producto.codunimed=siv_unidadmedida.codunimed".
				  "   AND sfa_dt_factura.numfactura=sfa_factura.numfactura".
				  "   AND sfa_dt_factura.codemp='".$as_codemp."'".
				  "   AND sfa_dt_factura.codemp=sfa_factura.codemp ".
				  "   AND sfa_dt_factura.numfactura='".$as_numfactura."'".
				  " ORDER BY sfa_dt_factura.orden ASC";

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->FACTURA MÉTODO->uf_sfa_obtener_dt_factura ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			return false;
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_status=	$row["estfac"];
				$ls_codart=    $row["codpro"];
				$ls_denart=    $row["denpro"];
				$ls_unidad=    $row["denunimed"];
				//$li_unidad=    $row["unidades"];
				$li_preuniart= $row["preunipro"];
				$li_poriva=    $row["poriva"];
				$li_canoriart= $row["canquexipro"];
				$li_canart=    $row["cantpro"];
				$li_monsubtotart= $row["monsubpro"];
				$li_monsubivaart= $row["moncarpro"];
				$li_montotart= $row["montotpro"];
				$ai_subfac=($ai_subfac+$li_monsubtotart);
				$ai_totiva=($ai_totiva+$li_monsubivaart);
				$ai_totentsum=($ai_totentsum+$li_montotart);
				$ai_totrows=$ai_totrows+1;
				$ao_object[$ai_totrows][1]="<input name=txtdenart".$ai_totrows."    type=text id=txtdenart".$ai_totrows."    class=sin-borde size=15 maxlength=50 value='".$ls_denart."' readonly><input name=txtcodart".$ai_totrows." type=hidden id=txtcodart".$ai_totrows." class=sin-borde size=20 maxlength=20 value='".$ls_codart."' readonly>";
				$ao_object[$ai_totrows][2]="<div align='center'></div><input name=txtunidad".$ai_totrows."    type=text id=txtunidad".$ai_totrows."    class=sin-borde size=12 maxlength=12 value='".$ls_unidad."' readonly>";
				$ao_object[$ai_totrows][3]="<input name=txtcanoriart".$ai_totrows." type=text id=txtcanoriart".$ai_totrows." class=sin-borde size=12 maxlength=12 value='".number_format ($li_canoriart,2,",",".")."'  readonly>";
				$ao_object[$ai_totrows][4]="<input name=txtcanart".$ai_totrows."    type=text id=txtcanart".$ai_totrows."    class=sin-borde size=10 maxlength=12 value='".number_format ($li_canart,2,",",".")."' readonly>";
				$ao_object[$ai_totrows][5]="<input name=txtpreart".$ai_totrows."    type=text id=txtpreart".$ai_totrows."    class=sin-borde size=10 maxlength=12 value='".number_format ($li_preuniart,2,",",".")."' readonly>";
				$ao_object[$ai_totrows][6]="<input name=txtporiva".$ai_totrows." type=text id=txtporiva".$ai_totrows." class=sin-borde size=8 maxlength=8 value='".number_format ($li_poriva,2,",",".")."' readonly>";
				$ao_object[$ai_totrows][7]="<input name=txtsubtotart".$ai_totrows." type=text id=submontotart".$ai_totrows." class=sin-borde size=14 maxlength=15 value='".number_format ($li_monsubtotart,2,",",".")."' readonly>";
				$ao_object[$ai_totrows][8]="<input name=txtsubivaart".$ai_totrows." type=text id=txtsubivaart".$ai_totrows." class=sin-borde size=14 maxlength=15 value='".number_format ($li_monsubivaart,2,",",".")."' readonly>";
				$ao_object[$ai_totrows][9]="<input name=txttotart".$ai_totrows." type=text id=txttotart".$ai_totrows." class=sin-borde size=14 maxlength=15 value='".number_format ($li_montotart,2,",",".")."' readonly>";
				$ao_object[$ai_totrows][10]="";
				$ao_object[$ai_totrows][11]="";
				//uf_agregarlineablanca($lo_object,$li_totrows);
			}//while
			//die();
		}//else
		if ($ai_totrows==0)
		{$lb_valido=false;}
		$this->io_sql->free_result($rs_data);
		return $lb_valido;
	} // end function uf_sfa_obtener_dt_factura
	
	function uf_siv_update_ultimocosto($as_codemp,$as_codart,$ai_preuniart,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_update_ultimocosto
		//         Access: private
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codart     // numero de orden de compra
		//  			   $ai_preuniart  // precio unitario del articulo
		//                 $aa_seguridad  // 
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza el monto del ultimo costo con el cual el articulo ha ingresado a la empresa
		//				   en la tabla siv_articulo
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 10/02/2006							Fecha Última Modificación : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $ls_sql = "UPDATE siv_articulo ".
		 		   "   SET ultcosart='".$ai_preuniart."' ".
				   " WHERE codemp='".$as_codemp."' ".
				   "   AND codart='".$as_codart."' ";
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->recepcion MÉTODO->uf_siv_update_ultimocosto ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$this->io_rcbsf->io_ds_datos->insertRow("campo","ultcosartaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ai_preuniart);
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codemp);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codart");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codart);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("siv_articulo",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);
			//$lb_valido=true;
		}
	    return $lb_valido;
	} // end function uf_siv_update_ultimocosto

	function uf_sfa_actualizar_cantidad_productos($as_codemp,$as_codpro,$ai_canpro,$aa_seguridad,$operacion)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_actualizar_costo_promedio
		//         Access: private
		//      Argumento: $as_codemp    // codigo de empresa
		//  		   $as_codpro     // codigo del articulo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que se encarga de actualizar la existencia del articulo a Facturar
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 16/11/2016
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT codpro, exipro FROM sfa_producto  WHERE codemp='".$as_codemp."' AND codpro='".$as_codpro."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->factura MÉTODO->uf_sfa_actualizar_cantidad_proucto ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		while($row=$this->io_sql->fetch_row($rs_data))
		{
			$ls_codpro=$row["codpro"];
			$li_exipro=$row["exipro"];
			if($operacion=="-")
			{
				$li_nuevo=$li_exipro - $ai_canpro; // Quito del Inventario
			}
			else
			{
				$li_nuevo=$li_exipro + $ai_canpro; // Repongo Inventario
			}
			//print "Existencia actual ".$li_exipro." Nueva Existencia ".$li_nuevo;
			$lb_valido=$this->uf_sfa_update_cantidad_producto($as_codemp,$ls_codpro,$li_nuevo,$aa_seguridad);		
		}		
		return $lb_valido;

	}  // end function uf_siv_actualizar_costo_promedio

	function uf_sfa_update_cantidad_producto($as_codemp,$as_codpro,$ai_exinueva,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sfa_update_cantidad_producto
		//         Access: private
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codpro     // codigo del producto
		//  			   $ai_cexinueva  // Nueva existencia del producto
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza la existencia del producto
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 16/11/2016
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sfa_producto SET exipro='".$ai_exinueva."' ".
			" WHERE codemp='".$as_codemp."' ".
			" AND codpro='".$as_codpro."' ";
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->factura MÉTODO->uf_sfa_update_cantidad_producto ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	} // end  function uf_sfa_update_cantidad_producto

}//fin  class tepuy_sfa_c_factura
?>
