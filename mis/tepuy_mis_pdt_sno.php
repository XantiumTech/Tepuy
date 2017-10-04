<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";
		print "</script>";		
	}
	$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
	switch($ls_modalidad)
	{
		case "1": // Modalidad por Proyecto
			$ls_titulo="Estructura Presupuestaria ";
			$li_len1=20;
			$li_len2=6;
			$li_len3=3;
			$li_len4=2;
			$li_len5=2;
			break;
			
		case "2": // Modalidad por Presupuesto
			$ls_titulo="Estructura Programática ";
			$li_len1=2;
			$li_len2=2;
			$li_len3=2;
			$li_len4=2;
			$li_len5=2;
			break;
	}

   //----------------------------------------------------------------------------------------------------------------------------
   function uf_imprimirresultados($as_codcom)
   {
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_imprimirresultados
		//		   Access: private
		//	    Arguments: as_codcom  // Número de Comprobante
		//	  Description: Función que Imprime los detalles del comprobante
		//	   Creado Por: Ing. Miguel Palencia
		// Fecha Creación: 31/10/2006 								Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $in_class_mis;
		global $ls_titulo, $li_len1, $li_len2, $li_len3, $li_len4, $li_len5;
		
		require_once("../shared/class_folder/tepuy_include.php");
		$in=new tepuy_include();
		$con=$in->uf_conectar();
		require_once("../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();
		require_once("../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($con);
		require_once("../shared/class_folder/class_sql.php");
		$io_sql2=new class_sql($con);
		require_once("../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();
        $ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$ls_group="";
		$ls_criterio="";
		switch(substr($as_codcom,14,1))
		{
			case "A": // Aportes
				$ls_group = "GROUP BY codemp, codcom, codcomapo, descripcion, cod_pro, ced_bene, tipo_destino";
				break;

			case "N": // Nómina
				$ls_group = "GROUP BY codemp, codcom, codcomapo, descripcion, cod_pro, ced_bene, tipo_destino ";
				break;

			case "I": // Ingresos
				$ls_group = "GROUP BY codemp, codcom, codcomapo, descripcion, cod_pro, ced_bene, tipo_destino ";
				break;

			case "P": // Prestación
				$ls_group = "GROUP BY codemp, codcom, codcomapo, descripcion, cod_pro, ced_bene, tipo_destino ";
				break;
		}
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQL":
				$ls_cadena="CONVERT('   ' USING utf8) as operacion";
				break;
			case "POSTGRE":
				$ls_cadena="CAST('   ' AS char(3)) as operacion";
				break;					
		}
		$ls_sql="SELECT descripcion, cod_pro, ced_bene, tipo_destino, MAX(operacion) AS operacion, codcomapo, ".
				"		(SELECT nompro FROM rpc_proveedor ".
				"		  WHERE rpc_proveedor.codemp = sno_dt_spg.codemp ".
				"           AND rpc_proveedor.cod_pro = sno_dt_spg.cod_pro ) as nompro, ".
				"		(SELECT nombene FROM rpc_beneficiario ".
				"		  WHERE rpc_beneficiario.codemp = sno_dt_spg.codemp ".
				"           AND rpc_beneficiario.ced_bene = sno_dt_spg.ced_bene ) as nombene, ".
				"		(SELECT apebene FROM rpc_beneficiario ".
				"		  WHERE rpc_beneficiario.codemp = sno_dt_spg.codemp ".
				"           AND rpc_beneficiario.ced_bene = sno_dt_spg.ced_bene ) as apebene ".
				"  FROM sno_dt_spg ".
				" WHERE codemp='".$ls_codemp."'".
				"   AND codcom='".$as_codcom."' ".
				$ls_group.
				" UNION ".
				"SELECT descripcion, cod_pro, ced_bene, tipo_destino, ".$ls_cadena.", codcomapo, ".
				"		(SELECT nompro FROM rpc_proveedor ".
				"		  WHERE rpc_proveedor.codemp = sno_dt_scg.codemp ".
				"           AND rpc_proveedor.cod_pro = sno_dt_scg.cod_pro ) as nompro, ".
				"		(SELECT nombene FROM rpc_beneficiario ".
				"		  WHERE rpc_beneficiario.codemp = sno_dt_scg.codemp ".
				"           AND rpc_beneficiario.ced_bene = sno_dt_scg.ced_bene ) as nombene, ".
				"		(SELECT apebene FROM rpc_beneficiario ".
				"		  WHERE rpc_beneficiario.codemp = sno_dt_scg.codemp ".
				"           AND rpc_beneficiario.ced_bene = sno_dt_scg.ced_bene ) as apebene ".
				"  FROM sno_dt_scg ".
				" WHERE codemp='".$ls_codemp."' ".
				"   AND codcom='".$as_codcom."' ".
				"   AND codcom NOT IN (SELECT codcom FROM sno_dt_spg WHERE codemp = '".$ls_codemp."' )  ".
				$ls_group;
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_criterio="";
				$ls_codcomapo=$row["codcomapo"];
				$ls_comprobante=$as_codcom;
				switch(substr($as_codcom,14,1))
				{
					case "A": // Aportes
						$ls_criterio = " AND codcomapo = '".$ls_codcomapo."'";
						$ls_comprobante=$ls_codcomapo;
						break;
				}
				$ls_descripcion=$row["descripcion"];
				$ls_tipo_destino=$row["tipo_destino"];
				$ls_operacion=rtrim($row["operacion"]);
				switch($ls_tipo_destino)
				{
					case "P":
						$ls_destino="Proveedor";
						$ls_nombre_destino=$row["cod_pro"]." - ".$row["nompro"];
						break;
	
					case "B":
						$ls_destino="Beneficiario";
						$ls_nombre_destino=$row["ced_bene"]." - ".$row["apebene"].", ".$row["nombene"];
						break;
						
					case "-":
						$ls_destino="-";
						$ls_nombre_destino="-";
						break;
				}
				switch($ls_operacion)
				{
					case "O":
						$ls_operacion="COMPROMETE";
						break;
	
					case "OC":
						$ls_operacion="COMPROMETE Y CAUSA";
						break;
	
					case "OCP":
						$ls_operacion="COMPROMETE, CAUSA Y PAGA";
						break;
	
					case "CP":
						$ls_operacion="CAUSAR Y PAGAR";
						break;

					case "DC":
						$ls_operacion="DEVENGADO Y COBRADO";
						break;

					case "":
						$ls_operacion="CONTABLE";
						break;
				}
				print "<table width='450' height='20' border='0' align='center' cellpadding='0' cellspacing='0'>";
				print "	<tr>";
				print "		<td width='450' class='titulo-ventana'>Información del Comprobante</td>";
				print " </tr>";
				print "</table>";
				print "<table width='450' border=0 cellpadding=1 cellspacing=1 align='center' class='formato-blanco'>";
				print "  <tr>";
				print "		<td width='100'><div align='right' class='texto-azul'>Comprobante</div></td>";
				print "		<td width='350'><div align='left'>".$ls_comprobante."</div></td>";
				print "  </tr>";
				print "  <tr>";
				print "		<td><div align='right' class='texto-azul'>Descripci&oacute;n </div></td>";
				print "		<td><div align='justify'>".$ls_descripcion."</div></td>";
				print "  </tr>";
				print "  <tr>";
				print "		<td><div align='right' class='texto-azul'>".$ls_destino."</div></td>";
				print "		<td><div align='left'>".$ls_nombre_destino."</div></td>";
				print "  </tr>";
				print "  <tr>";
				print "		<td><div align='right' class='texto-azul'>Contabilizaci&oacute;n </div></td>";
				print "		<td><div align='left'>".$ls_operacion."</div></td>";
				print "  </tr>";
				print "  <tr>";
				print "		<td><div align='right' class='texto-azul'></div></td>";
				print "		<td><div align='left'></div></td>";
				print "  </tr>";
				print "</table>";
				$ls_sql="SELECT codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, spg_cuenta, monto ".
						"  FROM sno_dt_spg ".
						" WHERE codemp='".$ls_codemp."'".
						"   AND codcom='".$as_codcom."' ".
						$ls_criterio;
				$rs_data2=$io_sql2->select($ls_sql);
				if($rs_data2===false)
				{
					$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql2->message)); 
				}
				else
				{
					print "<table width='450' height='20' border='0' align='center' cellpadding='0' cellspacing='0' class='formato-blanco'>";
					print "	<tr>";
					print "		<td colspan='3' class='titulo-celdanew'>Detalle Presupuestario</td>";
					print " </tr>";
					print " <tr class=titulo-celdanew>";
					print "		<td width='100'>".$ls_titulo."</td>";
					print "		<td width='100'>Cuenta</td>";
					print "		<td width='100'>Monto</td>";
					print "	</tr>";
					$li_total=0;
					while($row=$io_sql2->fetch_row($rs_data2))
					{
						$ls_cuenta=$row["spg_cuenta"];
						$li_total=$li_total+$row["monto"];
						$li_monto=$in_class_mis->uf_formatonumerico($row["monto"]);
						$ls_codest1=$row["codestpro1"];
						$ls_codest1=substr($ls_codest1,(strlen($ls_codest1)-$li_len1),$li_len1);
						$ls_codest2=$row["codestpro2"];
						$ls_codest2=substr($ls_codest2,(strlen($ls_codest2)-$li_len2),$li_len2);
						$ls_codest3=$row["codestpro3"];
						$ls_codest3=substr($ls_codest3,(strlen($ls_codest3)-$li_len3),$li_len3);
						$ls_codest4=$row["codestpro4"];
						$ls_codest4=substr($ls_codest4,(strlen($ls_codest4)-$li_len4),$li_len4);
						$ls_codest5=$row["codestpro5"];
						$ls_codest5=substr($ls_codest5,(strlen($ls_codest5)-$li_len5),$li_len5);
						print "<tr class=celdas-blancas>";
						print "<td align=center width='100'>".$ls_codest1.$ls_codest2.$ls_codest3.$ls_codest4.$ls_codest5."</td>";
						print "<td align=center width='100'>".$ls_cuenta."</td>";
						print "<td align=right width='100'>".$li_monto."  </td>";
						print "</tr>";			
					}
					$li_total=$in_class_mis->uf_formatonumerico($li_total);
					print "	<tr class=celdas-blancas>";
					print "		<td colspan='2' align='right' class='texto-azul'>Total</td>";
					print "		<td width='100' align='right' class='texto-azul'>".$li_total."</td>";
					print " </tr>";
					print "</table>";
				}
				$io_sql2->free_result($rs_data2);	
				$ls_sql="SELECT sc_cuenta, debhab, monto ".
						"  FROM sno_dt_scg ".
						" WHERE codemp='".$ls_codemp."'".
						"   AND codcom='".$as_codcom."' ".
						$ls_criterio.
						" ORDER BY  debhab ";
				$rs_data2=$io_sql2->select($ls_sql);
				if($rs_data2===false)
				{
					$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql2->message)); 
				}
				else
				{
					$li_total_deb=0;
					$li_total_hab=0;
					print "<table width='450' height='20' border='0' align='center' cellpadding='0' cellspacing='0' class='formato-blanco'>";
					print "	<tr>";
					print "		<td colspan='3' class='titulo-celdanew'>Detalle Contable</td>";
					print " </tr>";
					print " <tr class=titulo-celdanew>";
					print "		<td width='100'>Cuenta</td>";
					print "		<td width='100'>Debe</td>";
					print "		<td width='100'>Haber</td>";
					print "	</tr>";
					while($row=$io_sql2->fetch_row($rs_data2))
					{
						$ls_cuenta=$row["sc_cuenta"];
						$li_monto=$row["monto"];
						$ls_debhab=$row["debhab"];
						switch($ls_debhab)
						{
							case "D":
								$li_debe=$li_monto;
								$li_debe=$in_class_mis->uf_formatonumerico($li_debe);
								$li_haber="0,00";
								$li_total_deb=$li_total_deb+$li_monto;
								break;
							case "H":
								$li_debe="0,00";
								$li_haber=$li_monto;
								$li_haber=$in_class_mis->uf_formatonumerico($li_haber);
								$li_total_hab=$li_total_hab+$li_monto;
								break;
						}
						print "<tr class=celdas-blancas>";
						print "<td align=center width='100'>".$ls_cuenta."</td>";
						print "<td align=right width='100'>".$li_debe."</td>";
						print "<td align=right width='100'>".$li_haber."</td>";
						print "</tr>";			
					}
					$li_total_deb=$in_class_mis->uf_formatonumerico($li_total_deb);
					$li_total_hab=$in_class_mis->uf_formatonumerico($li_total_hab);
					print "	<tr>";
					print "		<td align=right class='texto-azul'>Total</td>";
					print "		<td align=right class='texto-azul'>".$li_total_deb."</td>";
					print "		<td align=right class='texto-azul'>".$li_total_hab."</td>";
					print " </tr>";
					print "</table>";
				}
				$io_sql2->free_result($rs_data2);
				print "<br><br>";	
			}
		}
		$io_sql->free_result($rs_data);	
   }
   //----------------------------------------------------------------------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey)){
		window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ 
		return false; 
		} 
		} 
	}
</script>
<title>Detalle Comprobante</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:active {
	color: #006699;
}
-->
</style>
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
</head>
<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
<?php
	require_once("class_folder/class_funciones_mis.php");
	$in_class_mis=new class_funciones_mis();
	$ls_codcom=$in_class_mis->uf_obtenervalor_get("codcom","");
	uf_imprimirresultados($ls_codcom);
?>
</div>
</form>
</body>
</html>