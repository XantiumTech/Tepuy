<?php 
	session_start();
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	        Class: tepuy_cxp_c_ncnd_ajax
	//		   Access: public 
	//	  Description: Clase para muestra de detalles de las notas de debito/credito
	//	   Creado Por: Ing. Nelson Barraez
	//  Fecha Creacin: 08/04/2007 								Fecha Ultima Modificacin : 03/06/2007
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	require_once("class_funciones_cxp.php");
	$io_funciones_cxp=new class_funciones_cxp();
	// Tipo del catalogo que se requiere pintar
	$ls_funcion=$io_funciones_cxp->uf_obtenervalor("funcion",""); 
	switch($ls_funcion){
	 	case "DTRECEPCION"://Pinta los grid de detalle de la recepcion de documento
			uf_cargar_dt_recepcion();
			break;
		case "CARGARDTNOTA"://Pinta los grid de la notas existentes con los detalles que se hayan almacenado para la misma
			uf_cargar_dt_nota();
			break;	
		case "DTNOTA"://Pinta los grid de la nota en blanco
			uf_dt_nota();
			break;	
		case "DTNOTAPRE"://Pinta los grid de detalle de la nota cuando se elimina un detalle,lo usa cuando son recepciones con afectacion presupuestaria
			uf_cargar_dtnota_pre();	
			break;
		case "DTNOTACON"://Pinta los grid de detalle de la nota cuando se elimina un detalle,lo usa cuando son recepciones tipo contable
			uf_cargar_dtnotacon();	
			break;
		case "AGREGARDTNOTAPRE"://Pinta los grid de detalle de la nota cuando se agrega un detalle o un cargo,lo usa cuando son recepciones con afectacion presupuestaria
			uf_agregar_dtnotapre();	
			break;	
		case "AGREGARDTNOTACON"://Pinta los grid de detalle de la nota cuando se agrega un detalle,lo usa cuando son recepciones con afectacion presupuestaria
			uf_agregar_dtnotacon();	
			break;	
	}
	
	function uf_cargar_dt_recepcion()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_cargar_dt_recepcion
		//		   Access: public 
		//	  Description: Funcion para pintar los detalles presupuestarios y contables de la recepcion de documento
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creacin: 27/05/2007 								Fecha ltima Modificacin : 03/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../../shared/class_folder/tepuy_include.php");
		$io_include=new tepuy_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
		require_once("../../shared/class_folder/grid_param.php");
		$io_grid=new grid_param();	
		global $io_funciones_cxp;
		$li=0;
		$ls_aux="";
		$ls_modalidad=$_SESSION['la_empresa']['estmodest'];
		$ls_codemp=$io_funciones_cxp->uf_obtenervalor("codemp","");
		$ls_numrecdoc=$io_funciones_cxp->uf_obtenervalor("numrecdoc",""); 
		$ls_codtipdoc=$io_funciones_cxp->uf_obtenervalor("codtipdoc","");
		$ls_tipproben=$io_funciones_cxp->uf_obtenervalor("tipproben",""); 
		$ls_codproben=$io_funciones_cxp->uf_obtenervalor("codproben",""); 
		if($ls_tipproben=='P')
		{
			$ls_aux=" AND rd.cod_pro='".$ls_codproben."' ";
		}
		elseif($ls_tipproben=='B')
		{
			$ls_aux=" AND rd.ced_bene='".$ls_codproben."' ";
		}
		if($_SESSION["ls_gestor"]=="MYSQL")
		{
			$ls_aux_estpro=" AND rd.codestpro=CONCAT(spg.codestpro1,spg.codestpro2,spg.codestpro3,spg.codestpro4,spg.codestpro5) ";
		}
		else
		{
			$ls_aux_estpro=" AND rd.codestpro=spg.codestpro1||spg.codestpro2||spg.codestpro3||spg.codestpro4||spg.codestpro5 ";
		}	
		$ls_sql=" SELECT rd.codemp, rd.numrecdoc, rd.codtipdoc, rd.ced_bene, rd.cod_pro, rd.procede_doc, rd.numdoccom, rd.codestpro,
						 rd.spg_cuenta, rd.monto,spg.denominacion ".
				" FROM	 cxp_rd_spg rd,spg_cuentas spg".
				" WHERE  rd.codemp='".$ls_codemp."' ".
				" AND    rd.numrecdoc='".$ls_numrecdoc."' AND rd.codtipdoc='".$ls_codtipdoc."' ".$ls_aux." 
				  AND rd.codemp=spg.codemp AND rd.spg_cuenta=spg.spg_cuenta ".$ls_aux_estpro;
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar detalle presupuestario","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$li++;			
				$ls_numcomp=$row["numdoccom"];
				$ls_codestpro=$row["codestpro"];
				switch($ls_modalidad)
				{
					case "1": // Modalidad por Proyecto
						$ls_codestpro=substr($ls_codestpro,0,29);
						break;						
					case "2": // Modalidad por Programa
						$ls_codestpro1=substr(substr($ls_codestpro,0,20),-2);
						$ls_codestpro2=substr(substr($ls_codestpro,20,6),-2);
						$ls_codestpro3=substr(substr($ls_codestpro,26,3),-2);
						$ls_codestpro4=substr($ls_codestpro,29,2);
						$ls_codestpro5=substr($ls_codestpro,31,2);
						$ls_codestpro=$ls_codestpro1."-".$ls_codestpro2."-".$ls_codestpro3."-".$ls_codestpro4."-".$ls_codestpro5;
						break;
				}
				$ls_spgcuenta=$row["spg_cuenta"];
				$ldec_monto=$row["monto"];
				$ls_dencuenta=utf8_encode($row["denominacion"]);
				$lo_object[$li][1]="<input type=text name=txtnumcomp".$li."      id=txtnumcomp".$li." class=sin-borde style=text-align:center size=18 value='".$ls_numcomp."'    readonly>";
				$lo_object[$li][2]="<input type=text name=txtcodestpro".$li."    class=sin-borde style=text-align:center size=40 value='".$ls_codestpro."'    readonly>";
				$lo_object[$li][3]="<input type=text name=txtspgcuenta".$li."    class=sin-borde style=text-align:center size=18  value='".$ls_spgcuenta."'     readonly>"; 
				$lo_object[$li][4]="<input type=text name=txtmonto".$li."        class=sin-borde style=text-align:right  size=20 value='".number_format($ldec_monto,2,",",".")."' readonly>";
				$lo_object[$li][5]="<input type=text name=txtdencuenta".$li."    class=sin-borde style=text-align:left  size=50 value='".$ls_dencuenta."' readonly>";
			}
			if($li==0)
			{
				for($li=1;$li<=4;$li++)
				{
					$lo_object[$li][1]="<input type=text name=txtnumcomp".$li."      id=txtnumcomp".$li." class=sin-borde style=text-align:center size=18 value=''    readonly>";
					$lo_object[$li][2]="<input type=text name=txtcodestpro".$li."    class=sin-borde style=text-align:center   size=40 value=''    readonly>";
					$lo_object[$li][3]="<input type=text name=txtspgcuenta".$li."    class=sin-borde style=text-align:center size=18  value=''     readonly>"; 
					$lo_object[$li][4]="<input type=text name=txtmonto".$li."        class=sin-borde style=text-align:right  size=20 value='' readonly>";
					$lo_object[$li][5]="<input type=text name=txtdencuenta".$li."    class=sin-borde style=text-align:left  size=50  value='' readonly>";
				}
				$li=4;
			}		
			// Titulos del Grid de Bienes
			$lo_title[1]="Compromiso";
			$lo_title[2]="Codigo Programatico";
			$lo_title[3]="Codigo Estadistico";
			$lo_title[4]="Monto";
			$lo_title[5]="Denominaci&oacute;n";
			$io_grid->make_gridScroll($li,$lo_title,$lo_object,758,"Detalle Presupuestario","grid",120);
			$io_sql->free_result($rs_data);			
		}
		print "<input type=hidden name=rowsprerecepcion id=rowsprerecepcion value=".$li.">";
		$li=0;	
		$ls_sql=" SELECT rd.codemp, rd.numrecdoc, rd.codtipdoc, rd.ced_bene, rd.cod_pro, rd.procede_doc, rd.numdoccom, rd.debhab, 
						 rd.sc_cuenta, rd.monto, rd.estgenasi , scg.denominacion".
				" FROM	 cxp_rd_scg rd,scg_cuentas scg".
				" WHERE  rd.codemp='".$ls_codemp."' ".
				" AND    rd.numrecdoc='".$ls_numrecdoc."' AND rd.codtipdoc='".$ls_codtipdoc."' ".$ls_aux." AND rd.codemp=scg.codemp AND rd.sc_cuenta=scg.sc_cuenta";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->uf_mensajes_ajax("Error al cargar detalle contable","ERROR->".$io_funciones->uf_convertirmsg($io_sql->message),false,""); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$li++;			
				$ls_numcomp=$row["numdoccom"];
				$ls_scgcuenta=$row["sc_cuenta"];
				$ls_debhab=$row["debhab"];
				$ldec_monto=$row["monto"];
				$ls_dencuentascg=utf8_encode($row["denominacion"]);
				if($ls_debhab=='D')
				{
					$ldec_mondeb=$ldec_monto;
					$ldec_monhab=0;
				}
				else
				{
					$ldec_mondeb=0;
					$ldec_monhab=$ldec_monto;
				}
				$lo_objectscg[$li][1]="<input type=text name=txtnumcomp".$li."    id=txtnumcomp".$li." class=sin-borde style=text-align:center size=18 value='".$ls_numcomp."'    readonly>";
				$lo_objectscg[$li][2]="<input type=text name=txtscgcuenta".$li."    class=sin-borde style=text-align:center   size=40 value='".$ls_scgcuenta."'    readonly>";
				$lo_objectscg[$li][3]="<input type=text name=txtdebe".$li."    class=sin-borde style=text-align:right size=20  value='".number_format($ldec_mondeb,2,",",".")."' readonly>"; 
				$lo_objectscg[$li][4]="<input type=text name=txthaber".$li."    class=sin-borde style=text-align:right  size=20 value='".number_format($ldec_monhab,2,",",".")."' readonly>";
				$lo_objectscg[$li][5]="<input type=text name=txtdencuentascg".$li."    class=sin-borde style=text-align:left  size=50 value='".$ls_dencuentascg."' readonly>";
			}
			if($li==0)
			{
				for($li=1;$li<=4;$li++)
				{
					$lo_objectscg[$li][1]="<input type=text name=txtnumcomp".$li."    id=txtnumcomp".$li." class=sin-borde style=text-align:center size=18 value=''    readonly>";
					$lo_objectscg[$li][2]="<input type=text name=txtscgcuenta".$li."    class=sin-borde style=text-align:center   size=40 value=''    readonly>";
					$lo_objectscg[$li][3]="<input type=text name=txtdebe".$li."    class=sin-borde style=text-align:right size=20  value='' readonly>"; 
					$lo_objectscg[$li][4]="<input type=text name=txthaber".$li."    class=sin-borde style=text-align:right  size=20 value='' readonly>";
					$lo_objectscg[$li][5]="<input type=text name=txtdencuentascg".$li."    class=sin-borde style=text-align:left  size=50 value='' readonly>";
				}
				$li=4;
			}
			// Titulos del Grid de Bienes
			$lo_titlescg[1]="Compromiso";
			$lo_titlescg[2]="Cuenta Contable";
			$lo_titlescg[3]="Monto Debe";
			$lo_titlescg[4]="Monto Haber";
			$lo_titlescg[5]="Denominaci&oacute;n";
			$io_grid->make_gridScroll($li,$lo_titlescg,$lo_objectscg,758,"Detalle Contable","gridcon",120);
			print "<input type=hidden name=rowsconrecepcion id=rowsconrecepcion value=".$li.">";			
			$io_sql->free_result($rs_data);			
		}			
		unset($io_include);
		unset($io_conexion);
		unset($io_sql);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
	}
	function uf_dt_nota()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_dt_nota
		//		   Access: public 
		//	  Description: Funcion para pintar los detalles presupuestarios y contables de la nota en blanco
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creacin: 27/05/2007 								Fecha ltima Modificacin : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../../shared/class_folder/grid_param.php");
		$io_grid=new grid_param();	
		for($li=1;$li<=4;$li++)
		{
			$lo_object2[$li][1]="<input type=text name=txtcuentaspgncnd".$li."    id=txtcuentaspgncnd".$li." class=sin-borde style=text-align:center size=22 value=''    readonly><input type=hidden name=txtscgcuentadt".$li."    id=txtscgcuentadt".$li."  value=''><input type=hidden name=txtdenscgcuentadt".$li."    id=txtdenscgcuentadt".$li."  value=''><input type=hidden name=txtestcargo".$li."    id=txtestcargo".$li."  value=''>";
			$lo_object2[$li][2]="<input type=text name=txtcodestproncnd".$li."    class=sin-borde style=text-align:center   size=40 value=''    readonly>";
			$lo_object2[$li][3]="<input type=text name=txtdencuentancnd".$li."    class=sin-borde style=text-align:left size=39  value=''     readonly>"; 
			$lo_object2[$li][4]="<input type=text name=txtmontoncnd".$li."    class=sin-borde style=text-align:right  size=20 value='' readonly>";
		}
		$li=4;
				
		// Titulos del Grid de Bienes
		$lo_title[1]="C&oacute;digo Estad&iacute;stico";
		$lo_title[2]="C&oacute;digo Program&aacute;tico ";
		$lo_title[3]="Denominaci&oacute;n";
		$lo_title[4]="Monto";
		$io_grid->make_gridScroll($li,$lo_title,$lo_object2,758,"Detalle Presupuestario de la Nota","grid",120);	
		
		for($li=1;$li<=4;$li++)
		{
			$lo_object2[$li][1]="<input type=text name=txtscgcuentancnd".$li."    id=txtscgcuentancnd".$li." class=sin-borde style=text-align:center size=22 value=''    readonly>";
			$lo_object2[$li][2]="<input type=text name=txtdencuentascgncnd".$li."    class=sin-borde style=text-align:left   size=59 value=''    readonly>";
			$lo_object2[$li][3]="<input type=text name=txtdebencnd".$li."    class=sin-borde style=text-align:center size=20  value=''     readonly>"; 
			$lo_object2[$li][4]="<input type=text name=txthaberncnd".$li."    class=sin-borde style=text-align:right  size=20 value='' readonly>";
		}
		$li=4;
				
		// Titulos del Grid de Bienes
		$lo_title[1]="Cuenta";
		$lo_title[2]="Denominaci&oacute;n";
		$lo_title[3]="Debe";
		$lo_title[4]="Haber";
		$io_grid->make_gridScroll($li,$lo_title,$lo_object2,758,"Detalle Contable de la Nota","gridscg",120);	
		print "<input type=hidden name=numrowsprenota id=numrowsprenota value=".$li.">";
		print "<input type=hidden name=numrowsconnota id=numrowsconnota value=".$li.">";
		print "<table width='758' border='0' align='center' cellpadding='0' cellspacing='0' class='celdas-blancas'>";
		print "        <tr>";
		print "          <td width='508' height='22' align='right'><div align='right'><strong>Total Debe</strong></div></td>";
		print "          <td width='80' height='22' align='right'><input name='txtmontodeb'  type='text' id='txtmontodeb' style='text-align:right' value='0,00' size='22' maxlength='20' readonly align='right' class='letras-negrita'></td>";
		print "          <td width='90' height='22' align='right'><div align='right'><strong>Total Haber</strong></div></td>";
		print "          <td width='80' height='22' align='right'><input name='txtmontohab'  type='text' id='txtmontohab' style='text-align:right' value='0,00' size='22' maxlength='20' readonly align='right' class='letras-negrita'></td>";
		print "        </tr>";
		print "        <tr>";
		print "          <td width='508' height='22' align='right'>&nbsp;</td>";
		print "          <td width='80' height='22' align='right'>&nbsp;</td>";
		print "          <td width='90' height='22' align='right'>&nbsp;</td>";
		print "          <td width='80' height='22' align='right'>&nbsp;</td>";
		print "        </tr>";		
		print "</table>";
		print "<table width=758 border=0 cellpadding=0 cellspacing=0 class=formato-blanco>";
		print " <tr class=titulo-ventana>";
        print "  <td height=23 colspan=4><div align=center class=Estilo1><b>TOTALES</b></div></td>";
        print "  </tr>";
		print "<tr height=20>";
		print " <td width=49>&nbsp;</td>";
		print " <td width=413>&nbsp;</td>";
		print " <td width=167><div align=right><b>SUBTOTAL</b></div></td>";
		print " <td width=151><input name=txtmontosincargo type=text id=txtmontosincargo value='0,00' style='text-align:right' class='letras-negrita' size='22' maxlength='20' readonly></td>";
		print "</tr>";
		print "<tr height=20>";
		print " <td width=49>&nbsp;</td>";
		print " <td width=413>&nbsp;</td>";
		print " <td width=167><div align=right><input name='btnotroscreditos' type='button' class='boton' id='btnotroscreditos' value='Otros Cr&eacute;ditos' onClick='javascript:uf_agregar_dtcargos(\"\",\"\",\"\",\"\",\"\");'></div></td>";
		print " <td width=151><input name=txtmontocargo type=text id=txtmontocargo value='0,00' style='text-align:right' class='letras-negrita' size='22' maxlength='20' readonly></td>";
		print "</tr>";
		print "<tr height=20>";
		print " <td width=49>&nbsp;</td>";
		print " <td width=413>&nbsp;</td>";
		print " <td width=147><div align=right><b>MONTO TOTAL</b></div></td>";
		print " <td width=171><input name=txtmonto type=text class=texto-rojo id=txtmonto value='0,00' style='text-align:right' size='22' maxlength='20'  readonly></td>";
		print "</tr>";
		print "</table>";		
	}	
	
	function uf_cargar_dtnota_pre()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_cargar_dtnotapre
		//		   Access: public 
		//	  Description: Funcion para pintar los detalles presupuestarios a la nota recalculando los cargos y cuadrando los asientos contables 
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creacin: 27/05/2007 								Fecha ltima Modificacin : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../../shared/class_folder/grid_param.php");
		require_once("../../shared/class_folder/class_datastore.php");
		require_once("../../shared/class_folder/evaluate_formula.php");
		$io_formula       = new evaluate_formula();
		$io_grid=new grid_param();	
		global $io_funciones_cxp;	
		$io_grid=new grid_param();
		$ds_detscg=new class_datastore();
		$ldec_total=0;	
		$ldec_totalsincargo=0;
		$ldec_totalcargo=0;
		$ldec_totaldebe=0;
		$ldec_totalhaber=0;	
		$li_totalactual=$io_funciones_cxp->uf_obtenervalor("totalactual","");	
		$ls_tiponota=$io_funciones_cxp->uf_obtenervalor("tiponota","");
		$ls_cuentaprov=$io_funciones_cxp->uf_obtenervalor("txtctaprov","");
		$ls_denctaprov=$io_funciones_cxp->uf_obtenervalor("denctascg","");
		$ls_tipproben=$io_funciones_cxp->uf_obtenervalor("tipproben",""); 
		$ls_codproben=$io_funciones_cxp->uf_obtenervalor("codproben","");
		$ls_numrecdoc=$io_funciones_cxp->uf_obtenervalor("numrecdoc","");
		$ls_codtipdoc=$io_funciones_cxp->uf_obtenervalor("codtipdoc","");
		for($li=1;$li<=$li_totalactual;$li++)
		{
			$ls_estcargo=$io_funciones_cxp->uf_obtenervalor("txtestcargo".$li,"");
			if($ls_estcargo!='C')
			{
				$ls_cuenta=$io_funciones_cxp->uf_obtenervalor("txtcuentaspgncnd".$li,"");
				$ls_codestpro=$io_funciones_cxp->uf_obtenervalor("txtcodestproncnd".$li,"");
				$ls_dencuenta=$io_funciones_cxp->uf_obtenervalor("txtdencuentancnd".$li,"");
				$ldec_monto=$io_funciones_cxp->uf_obtenervalor("txtmontoncnd".$li,"");	
				$ls_scgcuenta=$io_funciones_cxp->uf_obtenervalor("txtscgcuentadt".$li,"");
				$ls_denctascg=$io_funciones_cxp->uf_obtenervalor("txtdenscgcuentadt".$li,"");
					
				$lo_object2[$li][1]="<input type=text name=txtcuentaspgncnd".$li."    id=txtcuentaspgncnd".$li." class=sin-borde style=text-align:center size=22 value='$ls_cuenta' readonly   onClick='javascript:uf_select_filadelete($li);'><input type=hidden name=txtscgcuentadt".$li."    id=txtscgcuentadt".$li."  value='$ls_scgcuenta'    readonly><input type=hidden name=txtdenscgcuentadt".$li."    id=txtdenscgcuentadt".$li."  value='$ls_denctascg'><input type=hidden name=txtestcargo".$li."    id=txtestcargo".$li."  value=''>";
				$lo_object2[$li][2]="<input type=text name=txtcodestproncnd".$li."    class=sin-borde style=text-align:center   size=40 value='$ls_codestpro'  readonly  onClick='javascript:uf_select_filadelete($li);'>";
				$lo_object2[$li][3]="<input type=text name=txtdencuentancnd".$li."    class=sin-borde style=text-align:left size=39  value='$ls_dencuenta'     readonly onClick='javascript:uf_select_filadelete($li);'>"; 
				$lo_object2[$li][4]="<input type=text name=txtmontoncnd".$li."        class=sin-borde style=text-align:right  size=20 value='".$ldec_monto."'  readonly onClick='javascript:uf_select_filadelete($li);uf_mostrar_alerta();'>";
				$lo_object2[$li][5]="<a href=javascript:uf_delete_dtnota('".$li."');><img src=../shared/imagebank/tools15/eliminar.png title=Eliminar width=15 height=10 border=0></a>";
				if($ls_tiponota=="ND")
				{
					//Puesto el comentario por la disyuntiva de cual era el negativo si la NC la ND
					/*$ldec_montoaux=str_replace(".","",$ldec_monto);
					$ldec_montoaux=str_replace(",",".",$ldec_montoaux);
					$ldec_monto=$ldec_montoaux*-1;*/					
					//$ldec_mondebe=number_format($ldec_monto,2,",",".");
					$ldec_monto=str_replace(".","",$ldec_monto);
					$ldec_monto=str_replace(",",".",$ldec_monto);
					$ldec_mondebe=number_format($ldec_monto,2,",",".");
					$ldec_monhaber="0,00";				
					$ls_debhab='D';
				}
				else
				{
					$ldec_montoaux=str_replace(".","",$ldec_monto);
					$ldec_montoaux=str_replace(",",".",$ldec_montoaux);
					$ldec_monto=$ldec_montoaux*-1;
					$ldec_monhaber=number_format($ldec_monto,2,",",".");
					$ldec_mondebe="0,00";
					$ls_debhab='H';
					//$ldec_monhaber=$ldec_monto;
					if($li==1)
					{
						$ds_detscg->insertRow("txtscgcuentancnd",$ls_cuentaprov);
						$ds_detscg->insertRow("txtdencuentascgncnd",$ls_denctaprov);
						$ds_detscg->insertRow("txtdebencnd","0,00");
						$ds_detscg->insertRow("txthaberncnd","0,00");
						$ds_detscg->insertRow("txtdebhab",'');
					}
					
				}
				$ds_detscg->insertRow("txtscgcuentancnd",$ls_scgcuenta);
				$ds_detscg->insertRow("txtdencuentascgncnd",$ls_denctascg);
				$ds_detscg->insertRow("txtdebencnd",$ldec_mondebe);
				$ds_detscg->insertRow("txthaberncnd",$ldec_monhaber);
				$ds_detscg->insertRow("txtdebhab",$ls_debhab);
				$ldec_total=$ldec_total+$ldec_monto;
			}
			else
			{
				$ldec_totalcargo=$ldec_monto+$ldec_totalcargo;
			}
		}
	
		if($li_totalactual==0)
		{
			uf_dt_nota();
		}
		else
		{				
			$ldec_total=number_format($ldec_total,2,",",".");
			if($ls_tiponota=="ND")
			{
				$ldec_mondebe="0,00";
				$ldec_monhaber=$ldec_total;
				$ls_debhab='H';
				$ds_detscg->insertRow("txtscgcuentancnd",$ls_cuentaprov);
				$ds_detscg->insertRow("txtdencuentascgncnd",$ls_denctaprov);
				$ds_detscg->insertRow("txtdebencnd",$ldec_mondebe);
				$ds_detscg->insertRow("txthaberncnd",$ldec_monhaber);
				$ds_detscg->insertRow("txtdebhab",$ls_debhab);
			}
			else
			{
				$ldec_mondebe=$ldec_total;
				$ldec_monhaber="0,00";
				$ls_debhab='D';
				$ds_detscg->updateRow("txtdebencnd",$ldec_mondebe,1);
				$ds_detscg->updateRow("txtdebhab",$ls_debhab,1);
			}
			$aa_items     = array('0'=>'txtscgcuentancnd','1'=>'txtdebhab');
			$aa_sum       = array('0'=>'txtdebencnd','1'=>'txthaberncnd');
			$ds_detscg->group_by_conformato($aa_items,$aa_sum,'txtscgcuentancnd');
			$li_totalrows=$ds_detscg->getRowCount("txtscgcuentancnd");
			for($la=1;$la<=$li_totalrows;$la++)
			{
				$ls_scgcuenta=$ds_detscg->getValue("txtscgcuentancnd",$la);
				$ls_dencuenta=$ds_detscg->getValue("txtdencuentascgncnd",$la);
				$ldec_mondebe=$ds_detscg->getValue("txtdebencnd",$la);
				$ldec_monhaber=$ds_detscg->getValue("txthaberncnd",$la);
				$ldec_auxdebe=str_replace(".","",$ldec_mondebe);
				$ldec_auxdebe=str_replace(",",".",$ldec_auxdebe);
				$ldec_auxhaber=str_replace(".","",$ldec_monhaber);
				$ldec_auxhaber=str_replace(",",".",$ldec_auxhaber);
				$ldec_totaldebe=$ldec_totaldebe+$ldec_auxdebe;
				$ldec_totalhaber=$ldec_totalhaber+$ldec_auxhaber;
				$lo_object[$la][1]="<input type=text name=txtscgcuentancnd".$la."    id=txtscgcuentancnd".$la." class=sin-borde style=text-align:center size=22 value='$ls_scgcuenta'    readonly>";
				$lo_object[$la][2]="<input type=text name=txtdencuentascgncnd".$la."    class=sin-borde style=text-align:left   size=59 value='$ls_dencuenta'    readonly>";
				$lo_object[$la][3]="<input type=text name=txtdebencnd".$la."    class=sin-borde style=text-align:right size=20  value='$ldec_mondebe'     readonly>"; 
				$lo_object[$la][4]="<input type=text name=txthaberncnd".$la."    class=sin-borde style=text-align:right  size=20 value='$ldec_monhaber' readonly>";
			}
			// Titulos del Grid de Bienes
			$lo_title[1]="C&oacute;digo Estad&iacute;stico";
			$lo_title[2]="C&oacute;digo Program&aacute;tico ";
			$lo_title[3]="Denominaci&oacute;n";
			$lo_title[4]="Monto";
			$lo_title[5]=" ";
			
			$io_grid->make_gridScroll(($li-1),$lo_title,$lo_object2,758,"Detalle Presupuestario de la Nota","grid",120);
			$lo_titlesc[1]="Cuenta";
			$lo_titlesc[2]="Denominaci&oacute;n";
			$lo_titlesc[3]="Monto Debe";
			$lo_titlesc[4]="Monto Haber";
			$io_grid->make_gridScroll(($la-1),$lo_titlesc,$lo_object,758,"Detalle Contable de la Nota","gridscg",120);
			print "<input type=hidden name=numrowsprenota id=numrowsprenota value=".($li-1).">";
			print "<input type=hidden name=numrowsconnota id=numrowsconnota value=".($la-1).">";
			print "<table width='758' border='0' align='center' cellpadding='0' cellspacing='0' class='celdas-blancas'>";
			print "        <tr>";
			print "          <td width='508' height='22' align='right'><div align='right'><strong>Total Debe</strong></div></td>";
			print "          <td width='80' height='22' align='right'><input name='txtmontodeb'  type='text' id='txtmontodeb' style='text-align:right' value=".number_format($ldec_totaldebe,2,",",".")." size='22' maxlength='20' readonly align='right' class='letras-negrita'></td>";
			print "          <td width='90' height='22' align='right'><div align='right'><strong>Total Haber</strong></div></td>";
			print "          <td width='80' height='22' align='right'><input name='txtmontohab'  type='text' id='txtmontohab' style='text-align:right' value=".number_format($ldec_totalhaber,2,",",".")." size='22' maxlength='20' readonly align='right' class='letras-negrita'></td>";
			print "        </tr>";
			print "        <tr>";
			print "          <td width='508' height='22' align='right'>&nbsp;</td>";
			print "          <td width='80' height='22' align='right'>&nbsp;</td>";
			print "          <td width='90' height='22' align='right'>&nbsp;</td>";
			print "          <td width='80' height='22' align='right'>&nbsp;</td>";
			print "        </tr>";					
			print "</table>";
			print "<table width=780 border=0 cellpadding=0 cellspacing=0 class=formato-blanco>";    
			print " <tr class=titulo-ventana>";
     	    print "  <td height=23 colspan=4><div align=center class=Estilo1><b>TOTALES</b></div></td>";
     	    print "  </tr>";       
			print "<tr height=20>";
			print " <td width=49>&nbsp;</td>";
			print " <td width=413>&nbsp;</td>";
			print " <td width=167><div align=right><b>SUBTOTAL</b></div></td>";
			print " <td width=151><input name=txtmontosincargo type=text id=txtmontosincargo value=".$ldec_total." style='text-align:right' class='letras-negrita' size='22' maxlength='20' readonly></td>";
			print "</tr>";
			print "<tr height=20>";
			print " <td width=49>&nbsp;</td>";
			print " <td width=413>&nbsp;</td>";
			print " <td width=167><div align=right><input name='btnotroscreditos' type='button' class='boton' id='btnotroscreditos' value='Otros Cr&eacute;ditos' onClick='javascript:uf_agregar_dtcargos(\"\",\"\",\"\",\"\",\"\");'></div></td>";
			print " <td width=151><input name=txtmontocargo type=text id=txtmontocargo value=".number_format($ldec_totalcargo,2,",",".")." style='text-align:right' size='22' maxlength='20' class='letras-negrita' readonly></td>";
			print "</tr>";
			print "<tr height=20>";
            print " <td width=49>&nbsp;</td>";
            print " <td width=413>&nbsp;</td>";
            print " <td width=147><div align=right><b>MONTO TOTAL</b></div></td>";
            print " <td width=171><input name=txtmonto type=text class=texto-rojo id=txtmonto value=".$ldec_total." style='text-align:right' size='22' maxlength='20' readonly></td>";
            print "</tr>";
            print "</table>";			
		}
	}
	
	function uf_agregar_dtnotapre()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_agregar_dtnotapre
		//		   Access: public 
		//	  Description: Funcion para agregar los detalles presupuestarios a la nota recalculando los cargos y cuadrando los asientos contables 
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creacin: 27/05/2007 								Fecha ltima Modificacin : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../../shared/class_folder/grid_param.php");
		require_once("../../shared/class_folder/class_datastore.php");
		require_once("../../shared/class_folder/evaluate_formula.php");
		$io_formula       = new evaluate_formula();
		$io_grid=new grid_param();
		$ds_detscg=new class_datastore();
	    global $io_funciones_cxp;		
		$li_total=$io_funciones_cxp->uf_obtenervalor("selected","");
		$ls_tiponota=$io_funciones_cxp->uf_obtenervalor("tiponota","");
		$ls_cuentaprov=$io_funciones_cxp->uf_obtenervalor("txtctaprov","");
		$ls_denctaprov=$io_funciones_cxp->uf_obtenervalor("denctascg","");
		$ls_tipproben=$io_funciones_cxp->uf_obtenervalor("tipproben",""); 
		$ls_codproben=$io_funciones_cxp->uf_obtenervalor("codproben","");
		$ls_numrecdoc=$io_funciones_cxp->uf_obtenervalor("numrecdoc","");
		$ls_codtipdoc=$io_funciones_cxp->uf_obtenervalor("codtipdoc","");
		$ldec_total=0;
		$li_aux=0;		
		$ldec_totalsincargo=0;
		$ldec_totalcargo=0;
		$ldec_totaldebe=0;
		$ldec_totalhaber=0;
		$li_rows=0;	
		for($li=1;$li<=$li_total;$li++)
		{
			$ls_cuenta=$io_funciones_cxp->uf_obtenervalor("txtspgcuenta".$li,"");
			$ls_codestpro=$io_funciones_cxp->uf_obtenervalor("txtcodestpro".$li,"");
			$ls_dencuenta=$io_funciones_cxp->uf_obtenervalor("txtdencuenta".$li,"");
			$ldec_monto=$io_funciones_cxp->uf_obtenervalor("txtmonto".$li,"");	
			$ls_scgcuenta=$io_funciones_cxp->uf_obtenervalor("txtscgcuenta".$li,"");
			$ls_denctascg=$io_funciones_cxp->uf_obtenervalor("txtdenscgcuentadt".$li,"");	
			$ls_estcargo=$io_funciones_cxp->uf_obtenervalor("txtestcargo".$li,"");	
			if($ls_tiponota=="ND")
			{
				//Este bloque se quito temporalmente por la disyuntiva sobre cual es el que va negativo si la NC la ND para este caso era la ND
				/*$ldec_montoaux=str_replace(".","",$ldec_monto);
				$ldec_montoaux=str_replace(",",".",$ldec_montoaux);
				if($ldec_montoaux>=0)
				{
					$ldec_montoaux=$ldec_montoaux*-1;
				}				
				else
				{
					$ldec_monto=$ldec_montoaux*-1;
					$ldec_monto=number_format($ldec_monto,2,",",".");	
				}
				$ldec_montoaux=number_format($ldec_montoaux,2,",",".");*/
				$ldec_montoaux=$ldec_monto;//Esto reemplaza a la linea de arriba
				$ldec_mondebe=$ldec_monto;
				$ldec_monhaber="0,00";		
				$ls_debhab='D';
			}
			else
			{
				$ldec_montoaux=str_replace(".","",$ldec_monto);
				$ldec_montoaux=str_replace(",",".",$ldec_montoaux);
				
				if($ldec_montoaux>=0)
				{
					$ldec_montoaux=$ldec_montoaux*-1;
				}				
				else
				{
					$ldec_monto=$ldec_montoaux*-1;
					$ldec_monto=number_format($ldec_monto,2,",",".");	
				}
				$ldec_montoaux=number_format($ldec_montoaux,2,",",".");
				//$ldec_montoaux=$ldec_monto;
				$ldec_mondebe="0,00";
				$ldec_monhaber=$ldec_monto;
				$ls_debhab='H';
				if($li==1)
				{
					$ds_detscg->insertRow("txtscgcuentancnd",$ls_cuentaprov);
					$ds_detscg->insertRow("txtdencuentascgncnd",$ls_denctaprov);
					$ds_detscg->insertRow("txtdebencnd",0,00);
					$ds_detscg->insertRow("txthaberncnd","0,00");
					$ds_detscg->insertRow("txtdebhab",'');
				}
				
			}
			$ls_confiva=$_SESSION["la_empresa"]["confiva"];
			if(($ls_estcargo!="C")||($ls_confiva!="C"))
			{
				$li_rows++;
//				print $li_rows;
				$lo_object2[$li][1]="<input type=text name=txtcuentaspgncnd".$li."    id=txtcuentaspgncnd".$li." class=sin-borde style=text-align:center size=22 value='$ls_cuenta' readonly onClick='javascript:uf_select_filadelete($li);'><input type=hidden name=txtscgcuentadt".$li."    id=txtscgcuentadt".$li."  value='$ls_scgcuenta'><input type=hidden name=txtdenscgcuentadt".$li."    id=txtdenscgcuentadt".$li."  value='$ls_denctascg'><input type=hidden name=txtestcargo".$li."    id=txtestcargo".$li."  value='$ls_estcargo'>";
				$lo_object2[$li][2]="<input type=text name=txtcodestproncnd".$li."    class=sin-borde style=text-align:center   size=40 value='$ls_codestpro'    readonly onClick='javascript:uf_select_filadelete($li);'>";
				$lo_object2[$li][3]="<input type=text name=txtdencuentancnd".$li."    class=sin-borde style=text-align:left size=39  value='$ls_dencuenta'       readonly onClick='javascript:uf_select_filadelete($li);'>"; 
				$lo_object2[$li][4]="<input type=text name=txtmontoncnd".$li."        class=sin-borde style=text-align:right  size=20 value='".$ldec_montoaux."' readonly onClick='javascript:uf_select_filadelete($li);uf_mostrar_alerta();'>";
				$lo_object2[$li][5]="<a href=javascript:uf_delete_dtnota('".$li."');><img src=../shared/imagebank/tools15/eliminar.png title=Eliminar width=15 height=10 border=0></a>";
			}
			$ds_detscg->insertRow("txtscgcuentancnd",$ls_scgcuenta);
			$ds_detscg->insertRow("txtdencuentascgncnd",$ls_denctascg);
			$ds_detscg->insertRow("txtdebencnd",$ldec_mondebe);
			$ds_detscg->insertRow("txthaberncnd",$ldec_monhaber);			
			$ds_detscg->insertRow("txtdebhab",$ls_debhab);
			
			$ldec_monto=str_replace(".","",$ldec_monto);
			$ldec_monto=str_replace(",",".",$ldec_monto);
			$ldec_total=$ldec_total+$ldec_monto;
			if($ls_estcargo=='')
			{
				$ldec_totalsincargo=$ldec_totalsincargo+$ldec_monto;	
			}	
			else
			{
				$ldec_totalcargo=$ldec_monto+$ldec_totalcargo;
			}		
		}	
		$ldec_total=number_format($ldec_total,2,",",".");
		$ldec_totalsincargo=number_format($ldec_totalsincargo,2,",",".");;
		if($ls_tiponota=="ND")
		{
			$ldec_mondebe="0,00";
			$ldec_monhaber=$ldec_total;
			$li_aux++;
			$ls_dehbah='H';
			$ds_detscg->insertRow("txtscgcuentancnd",$ls_cuentaprov);
			$ds_detscg->insertRow("txtdencuentascgncnd",$ls_denctaprov);
			$ds_detscg->insertRow("txtdebencnd",$ldec_mondebe);
			$ds_detscg->insertRow("txthaberncnd",$ldec_monhaber);
			$ds_detscg->insertRow("txtdebhab",$ls_debhab);
		}
		else
		{
			$ldec_mondebe=$ldec_total;
			$ldec_monhaber="0,00";
			$ls_debhab='D';
			$ds_detscg->updateRow("txtdebencnd",$ldec_mondebe,1);
			$ds_detscg->updateRow("txtdebhab",$ls_debhab,1);
		}
		
		$aa_items     = array('0'=>'txtscgcuentancnd','1'=>'txtdebhab');
		$aa_sum       = array('0'=>'txtdebencnd','1'=>'txthaberncnd');
		$ds_detscg->group_by_conformato($aa_items,$aa_sum,'txtscgcuentancnd');
		$li_totalrows=$ds_detscg->getRowCount("txtscgcuentancnd");
		for($la=1;$la<=$li_totalrows;$la++)
		{
			$ls_scgcuenta=trim($ds_detscg->getValue("txtscgcuentancnd",$la));
			$ls_dencuenta=$ds_detscg->getValue("txtdencuentascgncnd",$la);
			$ldec_mondebe=$ds_detscg->getValue("txtdebencnd",$la);
			$ldec_monhaber=$ds_detscg->getValue("txthaberncnd",$la);
			$ldec_auxdebe=str_replace(".","",$ldec_mondebe);
			$ldec_auxdebe=str_replace(",",".",$ldec_auxdebe);
			$ldec_auxhaber=str_replace(".","",$ldec_monhaber);
			$ldec_auxhaber=str_replace(",",".",$ldec_auxhaber);
			$ldec_totaldebe=$ldec_totaldebe+$ldec_auxdebe;
			$ldec_totalhaber=$ldec_totalhaber+$ldec_auxhaber;
			$lo_object[$la][1]="<input type=text name=txtscgcuentancnd".$la."    id=txtscgcuentancnd".$la." class=sin-borde style=text-align:center size=22 value='$ls_scgcuenta'    readonly>";
			$lo_object[$la][2]="<input type=text name=txtdencuentascgncnd".$la."    class=sin-borde style=text-align:left   size=59 value='$ls_dencuenta'    readonly>";
			$lo_object[$la][3]="<input type=text name=txtdebencnd".$la."    class=sin-borde style=text-align:right size=20  value='$ldec_mondebe'   readonly>"; 
			$lo_object[$la][4]="<input type=text name=txthaberncnd".$la."    class=sin-borde style=text-align:right  size=20 value='$ldec_monhaber' readonly>";
		}
		$lo_title[1]="C&oacute;digo Estad&iacute;stico";
		$lo_title[2]="C&oacute;digo Program&aacute;tico ";
		$lo_title[3]="Denominaci&oacute;n";
		$lo_title[4]="Monto";
		$lo_title[5]=" ";
		$io_grid->make_gridScroll($li_rows,$lo_title,$lo_object2,758,"Detalle Presupuestario de la Nota","grid",120);
		$lo_titlesc[1]="Cuenta";
		$lo_titlesc[2]="Denominaci&oacute;n";
		$lo_titlesc[3]="Monto Debe";
		$lo_titlesc[4]="Monto Haber";
		$io_grid->make_gridScroll(($la-1),$lo_titlesc,$lo_object,758,"Detalle Contable de la Nota","gridscg",120);	
		print "<input type=hidden name=numrowsprenota id=numrowsprenota value=".$li_rows.">";
		print "<input type=hidden name=numrowsconnota id=numrowsconnota value=".($la-1).">";
		print "<table width='758' border='0' align='center' cellpadding='0' cellspacing='0' class='celdas-blancas'>";
		print "        <tr>";
		print "          <td width='508' height='22' align='right'><div align='right'><strong>Total Debe</strong></div></td>";
		print "          <td width='80' height='22' align='right'><input name='txtmontodeb'  type='text' id='txtmontodeb' style='text-align:right' value=".number_format($ldec_totaldebe,2,",",".")." size='22' maxlength='20' readonly align='right' class='letras-negrita'></td>";
		print "          <td width='90' height='22' align='right'><div align='right'><strong>Total Haber</strong></div></td>";
		print "          <td width='80' height='22' align='right'><input name='txtmontohab'  type='text' id='txtmontohab' style='text-align:right' value=".number_format($ldec_totalhaber,2,",",".")." size='22' maxlength='20' readonly align='right' class='letras-negrita'></td>";
		print "        </tr>";
		print "        <tr>";
		print "          <td width='508' height='22' align='right'>&nbsp;</td>";
		print "          <td width='80' height='22' align='right'>&nbsp;</td>";
		print "          <td width='90' height='22' align='right'>&nbsp;</td>";
		print "          <td width='80' height='22' align='right'>&nbsp;</td>";
		print "        </tr>";		
		print "</table>";
		print "<table width=780 border=0 cellpadding=0 cellspacing=0 class=formato-blanco>";	 
		print " <tr class=titulo-ventana>";
        print "  <td height=23 colspan=4><div align=center class=Estilo1><b>TOTALES</b></div></td>";
        print "  </tr>";  
		print "<tr height=20>";
		print " <td width=49>&nbsp;</td>";
		print " <td width=413>&nbsp;</td>";
		print " <td width=167><div align=right><b>SUBTOTAL</b></div></td>";
		print " <td width=151><input name=txtmontosincargo type=text id=txtmontosincargo value=".$ldec_totalsincargo." style='text-align:right' class='letras-negrita' size='22' maxlength='20' readonly></td>";
		print "</tr>";
		print "<tr height=20>";
		print " <td width=49>&nbsp;</td>";
		print " <td width=413>&nbsp;</td>";
		print " <td width=167><div align=right><input name='btnotroscreditos' type='button' class='boton' id='btnotroscreditos' value='Otros Cr&eacute;ditos' onClick='javascript:uf_agregar_dtcargos(\"\",\"\",\"\",\"\",\"\");'></div></td>";
		print " <td width=151><input name=txtmontocargo type=text id=txtmontocargo value=".number_format($ldec_totalcargo,2,",",".")." style='text-align:right' class='letras-negrita' size='22' maxlength='20' readonly></td>";
		print "</tr>";
		print "<tr height=20>";
		print " <td width=49>&nbsp;</td>";
		print " <td width=413>&nbsp;</td>";
		print " <td width=147><div align=right><b>MONTO TOTAL</b></div></td>";
		print " <td width=171><input name=txtmonto type=text class=texto-rojo id=txtmonto value=".$ldec_total." style='text-align:right' size='22' maxlength='20' readonly></td>";
		print "</tr>";
		print "</table>";		
	}	
	
	function uf_agregar_dtnotacon()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_agregar_dtnotacon
		//		   Access: public 
		//	  Description: Funcion para agregar detalles contables a la nota de debito y credito solo para recepciones documento tipo contable
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creacin: 27/05/2007 								Fecha ltima Modificacin : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../../shared/class_folder/grid_param.php");
		require_once("../../shared/class_folder/class_datastore.php");
		$io_grid=new grid_param();
		$ds_detscg=new class_datastore();
	    global $io_funciones_cxp;		
		$li_total=$io_funciones_cxp->uf_obtenervalor("selected","");
		$ls_tiponota=$io_funciones_cxp->uf_obtenervalor("tiponota","");
		$ls_cuentaprov=$io_funciones_cxp->uf_obtenervalor("txtctaprov","");
		$ls_denctaprov=$io_funciones_cxp->uf_obtenervalor("denctascg","");
		$ls_tipproben=$io_funciones_cxp->uf_obtenervalor("tipproben",""); 
		$ls_codproben=$io_funciones_cxp->uf_obtenervalor("codproben","");
		$ls_numrecdoc=$io_funciones_cxp->uf_obtenervalor("numrecdoc","");
		$ls_codtipdoc=$io_funciones_cxp->uf_obtenervalor("codtipdoc","");
		$ldec_total=0;
		$li_aux=0;		
		$ldec_totaldebe=0;
		$ldec_totalhaber=0;	
		for($lx=1;$lx<=4;$lx++)//Pinto el detalle presupuestario en blanco
		{
			$lo_object2[$lx][1]="<input type=text name=txtcuentaspgncnd".$lx."    id=txtcuentaspgncnd".$lx." class=sin-borde style=text-align:center size=22 value='' readonly ><input type=hidden name=txtscgcuentadt".$lx."    id=txtscgcuentadt".$lx."  value=''><input type=hidden name=txtdenscgcuentadt".$lx."    id=txtdenscgcuentadt".$lx."  value=''><input type=hidden name=txtestcargo".$lx."    id=txtestcargo".$lx."  value=''>";
			$lo_object2[$lx][2]="<input type=text name=txtcodestproncnd".$lx."    class=sin-borde style=text-align:center   size=40 value=''    readonly >";
			$lo_object2[$lx][3]="<input type=text name=txtdencuentancnd".$lx."    class=sin-borde style=text-align:left size=39  value=''       readonly >"; 
			$lo_object2[$lx][4]="<input type=text name=txtmontoncnd".$lx."        class=sin-borde style=text-align:right  size=20 value='' readonly >";						
		}
		for($li=1;$li<=$li_total;$li++)//Pinto el detalle presupuestario en blanco
		{
			$ls_cuenta=$io_funciones_cxp->uf_obtenervalor("txtscgcuenta".$li,"");
			$ls_dencuenta=$io_funciones_cxp->uf_obtenervalor("txtdencuenta".$li,"");
			$ldec_mondeb=$io_funciones_cxp->uf_obtenervalor("txtmondeb".$li,"");
			$ldec_monhab=$io_funciones_cxp->uf_obtenervalor("txtmonhab".$li,"");
			if($ls_tiponota=="NC")
			{
				if($li==1)
				{
					$ds_detscg->insertRow("txtscgcuentancnd",$ls_cuentaprov);
					$ds_detscg->insertRow("txtdencuentascgncnd",$ls_denctaprov);
					$ds_detscg->insertRow("txtdebencnd",0,00);
					$ds_detscg->insertRow("txthaberncnd","0,00");
					$ds_detscg->insertRow("txtdebhab",'');
				}				
				$ds_detscg->insertRow("txtscgcuentancnd",$ls_cuenta);
				$ds_detscg->insertRow("txtdencuentascgncnd",$ls_dencuenta);
				$ds_detscg->insertRow("txtdebencnd",$ldec_monhab);
				$ds_detscg->insertRow("txthaberncnd",$ldec_mondeb);	
				$ds_detscg->insertRow("txtdebhab",'H');	
				$ldec_monto=str_replace(".","",$ldec_monhab);
				$ldec_monto=str_replace(",",".",$ldec_monto);
				$ldec_total=$ldec_total+$ldec_monto;
			}
			else
			{
				$ds_detscg->insertRow("txtscgcuentancnd",$ls_cuenta);
				$ds_detscg->insertRow("txtdencuentascgncnd",$ls_dencuenta);
				$ds_detscg->insertRow("txtdebencnd",$ldec_mondeb);
				$ds_detscg->insertRow("txthaberncnd",$ldec_monhab);			
				$ds_detscg->insertRow("txtdebhab",'D');	
				$ldec_monto=str_replace(".","",$ldec_mondeb);
				$ldec_monto=str_replace(",",".",$ldec_monto);
				$ldec_total=$ldec_total+$ldec_monto;
			}

		}
		$ldec_total=number_format($ldec_total,2,",",".");
		if($ls_tiponota=="ND")
		{
			$ldec_mondebe="0,00";
			$ldec_monhaber=$ldec_total;
			$li_aux++;
			$ds_detscg->insertRow("txtscgcuentancnd",$ls_cuentaprov);
			$ds_detscg->insertRow("txtdencuentascgncnd",$ls_denctaprov);
			$ds_detscg->insertRow("txtdebencnd",$ldec_mondebe);
			$ds_detscg->insertRow("txthaberncnd",$ldec_monhaber);
			$ds_detscg->insertRow("txtdebhab",'H');	
		}
		else
		{
			$ldec_mondebe=$ldec_total;
			$ldec_monhaber="0,00";
			$ds_detscg->updateRow("txtdebencnd",$ldec_mondebe,1);
			$ds_detscg->updateRow("txtdebhab",'D',1);	
		}
		
		$aa_items     = array('0'=>'txtscgcuentancnd','1'=>'txtdebhab');
		$aa_sum       = array('0'=>'txtdebencnd','1'=>'txthaberncnd');
		$ds_detscg->group_by_conformato($aa_items,$aa_sum,'txtscgcuentancnd');
		$li_totalrows=$ds_detscg->getRowCount("txtscgcuentancnd");
		for($la=1;$la<=$li_totalrows;$la++)
		{
			$ls_scgcuenta=trim($ds_detscg->getValue("txtscgcuentancnd",$la));
			$ls_dencuenta=$ds_detscg->getValue("txtdencuentascgncnd",$la);
			$ldec_mondebe=$ds_detscg->getValue("txtdebencnd",$la);
			$ldec_monhaber=$ds_detscg->getValue("txthaberncnd",$la);
			$ldec_auxdebe=str_replace(".","",$ldec_mondebe);
			$ldec_auxdebe=str_replace(",",".",$ldec_auxdebe);
			$ldec_auxhaber=str_replace(".","",$ldec_monhaber);
			$ldec_auxhaber=str_replace(",",".",$ldec_auxhaber);
			$ldec_totaldebe=$ldec_totaldebe+$ldec_auxdebe;
			$ldec_totalhaber=$ldec_totalhaber+$ldec_auxhaber;
			$lo_object[$la][1]="<input type=text name=txtscgcuentancnd".$la."    id=txtscgcuentancnd".$la." class=sin-borde style=text-align:center size=22 value='$ls_scgcuenta'    readonly onClick='javascript:uf_select_filadelete($la);'>";
			$lo_object[$la][2]="<input type=text name=txtdencuentascgncnd".$la."    class=sin-borde style=text-align:left   size=59 value='$ls_dencuenta'    readonly onClick='javascript:uf_select_filadelete($la);'>";
			$lo_object[$la][3]="<input type=text name=txtdebencnd".$la."    class=sin-borde style=text-align:right size=20  value='$ldec_mondebe'   readonly onClick='javascript:uf_select_filadelete($la);uf_mostrar_alerta();'>"; 
			$lo_object[$la][4]="<input type=text name=txthaberncnd".$la."    class=sin-borde style=text-align:right  size=20 value='$ldec_monhaber' readonly onClick='javascript:uf_select_filadelete($la);uf_mostrar_alerta();'>";
			$lo_object[$la][5]="<a href=javascript:uf_delete_dtnota('".$la."');><img src=../shared/imagebank/tools15/eliminar.png title=Eliminar width=15 height=10 border=0></a>";
		}
		$lo_title[1]="C&oacute;digo Estad&iacute;stico";
		$lo_title[2]="C&oacute;digo Program&aacute;tico ";
		$lo_title[3]="Denominaci&oacute;n";
		$lo_title[4]="Monto";
		$io_grid->make_gridScroll(($lx-1),$lo_title,$lo_object2,758,"Detalle Presupuestario de la Nota","grid",120);
		$lo_titlesc[1]="Cuenta";
		$lo_titlesc[2]="Denominaci&oacute;n";
		$lo_titlesc[3]="Monto Debe";
		$lo_titlesc[4]="Monto Haber";
		$lo_titlesc[5]=" ";
		$io_grid->make_gridScroll(($la-1),$lo_titlesc,$lo_object,758,"Detalle Contable de la Nota","gridscg",120);	
		print "<input type=hidden name=numrowsprenota id=numrowsprenota value=".($lx-1).">";
		print "<input type=hidden name=numrowsconnota id=numrowsconnota value=".($la-1).">";
		print "<table width='758' border='0' align='center' cellpadding='0' cellspacing='0' class='celdas-blancas'>";
		print "        <tr>";
		print "          <td width='508' height='22' align='right'><div align='right'><strong>Total Debe</strong></div></td>";
		print "          <td width='80' height='22' align='right'><input name='txtmontodeb'  type='text' id='txtmontodeb' style='text-align:right' value=".number_format($ldec_totaldebe,2,",",".")." size='22' maxlength='20' readonly align='right' class='letras-negrita'></td>";
		print "          <td width='90' height='22' align='right'><div align='right'><strong>Total Haber</strong></div></td>";
		print "          <td width='80' height='22' align='right'><input name='txtmontohab'  type='text' id='txtmontohab' style='text-align:right' value=".number_format($ldec_totalhaber,2,",",".")." size='22' maxlength='20' readonly align='right' class='letras-negrita'></td>";
		print "        </tr>";
		print "        <tr>";
		print "          <td width='508' height='22' align='right'>&nbsp;</td>";
		print "          <td width='80' height='22' align='right'>&nbsp;</td>";
		print "          <td width='90' height='22' align='right'>&nbsp;</td>";
		print "          <td width='80' height='22' align='right'>&nbsp;</td>";
		print "        </tr>";			
		print "</table>";
		print "<table width=780 border=0 cellpadding=0 cellspacing=0 class=formato-blanco>";	
		print " <tr class=titulo-ventana>";
        print "  <td height=23 colspan=4><div align=center class=Estilo1><b>TOTALES</b></div></td>";
        print "  </tr>";	
		print "<tr height=20>";
		print " <td width=49>&nbsp;</td>";
		print " <td width=413>&nbsp;</td>";
		print " <td width=167><div align=right><b>SUBTOTAL</b></div></td>";
		print " <td width=151><input name=txtmontosincargo type=text id=txtmontosincargo value=".$ldec_total." style='text-align:right' class='letras-negrita' size='22' maxlength='20' readonly></td>";
		print "</tr>";
		print "<tr height=20>";
		print " <td width=49>&nbsp;</td>";
		print " <td width=413>&nbsp;</td>";
		print " <td width=167><div align=right><input name='btnotroscreditos' type='button' class='boton' id='btnotroscreditos' value='Otros Cr&eacute;ditos' onClick='javascript:uf_agregar_dtcargos(\"\",\"\",\"\",\"\",\"\");'></div></td>";
		print " <td width=151><input name=txtmontocargo type=text id=txtmontocargo value='0,00' style='text-align:right' class='letras-negrita' size='22' maxlength='20' readonly></td>";
		print "</tr>";
		print "<tr height=20>";
		print " <td width=49>&nbsp;</td>";
		print " <td width=413>&nbsp;</td>";
		print " <td width=147><div align=right><b>MONTO TOTAL</b></div></td>";
		print " <td width=171><input name=txtmonto type=text class=texto-rojo id=txtmonto value=".$ldec_total." style='text-align:right' size='22' maxlength='20' readonly></td>";
		print "</tr>";
		print "</table>";
	}	
	
	function uf_cargar_dtnotacon()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_cargar_dtnotacon
		//		   Access: public 
		//	  Description: Funcion para pintar detalles contables a la nota de debito y credito solo para recepciones documento tipo contable
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creacin: 27/05/2007 								Fecha ltima Modificacin : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../../shared/class_folder/grid_param.php");
		require_once("../../shared/class_folder/class_datastore.php");
		$io_grid=new grid_param();	
		global $io_funciones_cxp;	
		$io_grid=new grid_param();
		$ds_detscg=new class_datastore();
		$ldec_total=0;	
		$li_totalactual=$io_funciones_cxp->uf_obtenervalor("totalactual","");	
		$ls_tiponota=$io_funciones_cxp->uf_obtenervalor("tiponota","");
		$ls_cuentaprov=$io_funciones_cxp->uf_obtenervalor("txtctaprov","");
		$ls_denctaprov=$io_funciones_cxp->uf_obtenervalor("denctascg","");
		$ls_tipproben=$io_funciones_cxp->uf_obtenervalor("tipproben",""); 
		$ls_codproben=$io_funciones_cxp->uf_obtenervalor("codproben","");
		$ls_numrecdoc=$io_funciones_cxp->uf_obtenervalor("numrecdoc","");
		$ls_codtipdoc=$io_funciones_cxp->uf_obtenervalor("codtipdoc","");
		$ldec_totaldebe=0;
		$ldec_totalhaber=0;	
		if($li_totalactual==0)
		{
			uf_dt_nota();
		}
		else
		{	
			for($lx=1;$lx<=4;$lx++)//Pinto el detalle presupuestario en blanco
			{
				$lo_object2[$lx][1]="<input type=text name=txtcuentaspgncnd".$lx."    id=txtcuentaspgncnd".$lx." class=sin-borde style=text-align:center size=22 value='' readonly ><input type=hidden name=txtscgcuentadt".$lx."    id=txtscgcuentadt".$lx."  value=''><input type=hidden name=txtdenscgcuentadt".$lx."    id=txtdenscgcuentadt".$lx."  value=''><input type=hidden name=txtestcargo".$lx."    id=txtestcargo".$lx."  value=''>";
				$lo_object2[$lx][2]="<input type=text name=txtcodestproncnd".$lx."    class=sin-borde style=text-align:center   size=40 value=''    readonly >";
				$lo_object2[$lx][3]="<input type=text name=txtdencuentancnd".$lx."    class=sin-borde style=text-align:left size=39  value=''       readonly >"; 
				$lo_object2[$lx][4]="<input type=text name=txtmontoncnd".$lx."        class=sin-borde style=text-align:right  size=20 value='' readonly >";						
			}
			for($li=1;$li<=$li_totalactual;$li++)//Pinto el detalle presupuestario en blanco
			{
				$ls_cuenta=$io_funciones_cxp->uf_obtenervalor("txtscgcuentancnd".$li,"");
				$ls_dencuenta=$io_funciones_cxp->uf_obtenervalor("txtdencuentascgncnd".$li,"");
				$ldec_mondeb=$io_funciones_cxp->uf_obtenervalor("txtdebencnd".$li,"");
				$ldec_monhab=$io_funciones_cxp->uf_obtenervalor("txthaberncnd".$li,"");
				if($ls_tiponota=="NC")
				{
					if($li==1)
					{
						$ds_detscg->insertRow("txtscgcuentancnd",$ls_cuentaprov);
						$ds_detscg->insertRow("txtdencuentascgncnd",$ls_denctaprov);
						$ds_detscg->insertRow("txtdebencnd",0,00);
						$ds_detscg->insertRow("txthaberncnd","0,00");
						$ds_detscg->insertRow("txtdebhab",'');
					}				
					$ds_detscg->insertRow("txtscgcuentancnd",$ls_cuenta);
					$ds_detscg->insertRow("txtdencuentascgncnd",$ls_dencuenta);
					$ds_detscg->insertRow("txtdebencnd",$ldec_monhab);
					$ds_detscg->insertRow("txthaberncnd",$ldec_mondeb);	
					$ds_detscg->insertRow("txtdebhab",'H');	
					$ldec_monto=str_replace(".","",$ldec_mondeb);
					$ldec_monto=str_replace(",",".",$ldec_monto);
					$ldec_total=$ldec_total+$ldec_monto;
				}
				else
				{
					$ds_detscg->insertRow("txtscgcuentancnd",$ls_cuenta);
					$ds_detscg->insertRow("txtdencuentascgncnd",$ls_dencuenta);
					$ds_detscg->insertRow("txtdebencnd",$ldec_mondeb);
					$ds_detscg->insertRow("txthaberncnd",$ldec_monhab);			
					$ds_detscg->insertRow("txtdebhab",'D');	
					$ldec_monto=str_replace(".","",$ldec_mondeb);
					$ldec_monto=str_replace(",",".",$ldec_monto);
					$ldec_total=$ldec_total+$ldec_monto;
				}
	
			}
			$ldec_total=number_format($ldec_total,2,",",".");
			if($ls_tiponota=="ND")
			{
				$ldec_mondebe="0,00";
				$ldec_monhaber=$ldec_total;
				$li_aux++;
				$ds_detscg->insertRow("txtscgcuentancnd",$ls_cuentaprov);
				$ds_detscg->insertRow("txtdencuentascgncnd",$ls_denctaprov);
				$ds_detscg->insertRow("txtdebencnd",$ldec_mondebe);
				$ds_detscg->insertRow("txthaberncnd",$ldec_monhaber);
				$ds_detscg->insertRow("txtdebhab",'H');	
			}
			else
			{
				$ldec_mondebe=$ldec_total;
				$ldec_monhaber="0,00";
				$ds_detscg->updateRow("txtdebencnd",$ldec_mondebe,1);
				$ds_detscg->updateRow("txtdebhab",'D',1);	
			}
			
			$aa_items     = array('0'=>'txtscgcuentancnd','1'=>'txtdebhab');
			$aa_sum       = array('0'=>'txtdebencnd','1'=>'txthaberncnd');
			$ds_detscg->group_by_conformato($aa_items,$aa_sum,'txtscgcuentancnd');
			$li_totalrows=$ds_detscg->getRowCount("txtscgcuentancnd");
			for($la=1;$la<=$li_totalrows;$la++)
			{
				$ls_scgcuenta=$ds_detscg->getValue("txtscgcuentancnd",$la);
				$ls_dencuenta=$ds_detscg->getValue("txtdencuentascgncnd",$la);
				$ldec_mondebe=$ds_detscg->getValue("txtdebencnd",$la);
				$ldec_monhaber=$ds_detscg->getValue("txthaberncnd",$la);
				$ldec_auxdebe=str_replace(".","",$ldec_mondebe);
				$ldec_auxdebe=str_replace(",",".",$ldec_auxdebe);
				$ldec_auxhaber=str_replace(".","",$ldec_monhaber);
				$ldec_auxhaber=str_replace(",",".",$ldec_auxhaber);
				$ldec_totaldebe=$ldec_totaldebe+$ldec_auxdebe;
				$ldec_totalhaber=$ldec_totalhaber+$ldec_auxhaber;
				$lo_object[$la][1]="<input type=text name=txtscgcuentancnd".$la."    id=txtscgcuentancnd".$la." class=sin-borde style=text-align:center size=22 value='$ls_scgcuenta'    readonly onClick='javascript:uf_select_filadelete($la);'>";
				$lo_object[$la][2]="<input type=text name=txtdencuentascgncnd".$la."    class=sin-borde style=text-align:left   size=59 value='$ls_dencuenta'    readonly onClick='javascript:uf_select_filadelete($la);'>";
				$lo_object[$la][3]="<input type=text name=txtdebencnd".$la."    class=sin-borde style=text-align:right size=20  value='$ldec_mondebe'   readonly onClick='javascript:uf_select_filadelete($la);uf_mostrar_alerta();'>"; 
				$lo_object[$la][4]="<input type=text name=txthaberncnd".$la."    class=sin-borde style=text-align:right  size=20 value='$ldec_monhaber' readonly onClick='javascript:uf_select_filadelete($la);uf_mostrar_alerta();'>";
				$lo_object[$la][5]="<a href=javascript:uf_delete_dtnota('".$la."');><img src=../shared/imagebank/tools15/eliminar.png title=Eliminar width=15 height=10 border=0></a>";
			}
			$lo_title[1]="C&oacute;digo Estad&iacute;stico";
			$lo_title[2]="C&oacute;digo Program&aacute;tico ";
			$lo_title[3]="Denominaci&oacute;n";
			$lo_title[4]="Monto";
			$io_grid->make_gridScroll(($lx-1),$lo_title,$lo_object2,758,"Detalle Presupuestario de la Nota","grid",120);
			$lo_titlesc[1]="Cuenta";
			$lo_titlesc[2]="Denominaci&oacute;n";
			$lo_titlesc[3]="Monto Debe";
			$lo_titlesc[4]="Monto Haber";
			$lo_titlesc[5]=" ";
			$io_grid->make_gridScroll(($la-1),$lo_titlesc,$lo_object,758,"Detalle Contable de la Nota","gridscg",120);	
			print "<input type=hidden name=numrowsprenota id=numrowsprenota value=".($lx-1).">";
			print "<input type=hidden name=numrowsconnota id=numrowsconnota value=".($la-1).">";
			print "<table width='758' border='0' align='center' cellpadding='0' cellspacing='0' class='celdas-blancas'>";
			print "        <tr>";
			print "          <td width='508' height='22' align='right'><div align='right'><strong>Total Debe</strong></div></td>";
			print "          <td width='80' height='22' align='right'><input name='txtmontodeb'  type='text' id='txtmontodeb' style='text-align:right' value=".number_format($ldec_totaldebe,2,",",".")." size='22' maxlength='20' readonly align='right' class='letras-negrita'></td>";
			print "          <td width='90' height='22' align='right'><div align='right'><strong>Total Haber</strong></div></td>";
			print "          <td width='80' height='22' align='right'><input name='txtmontohab'  type='text' id='txtmontohab' style='text-align:right' value=".number_format($ldec_totalhaber,2,",",".")." size='22' maxlength='20' readonly align='right' class='letras-negrita'></td>";
			print "        </tr>";
			print "        <tr>";
			print "          <td width='508' height='22' align='right'>&nbsp;</td>";
			print "          <td width='80' height='22' align='right'>&nbsp;</td>";
			print "          <td width='90' height='22' align='right'>&nbsp;</td>";
			print "          <td width='80' height='22' align='right'>&nbsp;</td>";
			print "        </tr>";				
			print "</table>";
			print "<table width=780 border=0 cellpadding=0 cellspacing=0 class=formato-blanco>";		
			print " <tr class=titulo-ventana>";
       		print "  <td height=23 colspan=4><div align=center class=Estilo1><b>TOTALES</b></div></td>";
       		print "  </tr>";	
 			print "<tr height=20>";
			print " <td width=49>&nbsp;</td>";
			print " <td width=413>&nbsp;</td>";
			print " <td width=167><div align=right><b>SUBTOTAL</b></div></td>";
			print " <td width=151><input name=txtmontosincargo type=text id=txtmontosincargo value=".$ldec_total." style='text-align:right' class='letras-negrita' size='22' maxlength='20' readonly></td>";
			print "</tr>";
			print "<tr height=20>";
			print " <td width=49>&nbsp;</td>";
			print " <td width=413>&nbsp;</td>";
			print " <td width=167><div align=right><input name='btnotroscreditos' type='button' class='boton' id='btnotroscreditos' value='Otros Cr&eacute;ditos' onClick='javascript:uf_agregar_dtcargos(\"\",\"\",\"\",\"\",\"\");'></div></td>";
			print " <td width=151><input name=txtmontocargo type=text id=txtmontocargo value='0,00' style='text-align:right' class='letras-negrita' size='22' maxlength='20' readonly></td>";
			print "</tr>";
			print "<tr height=20>";
            print " <td width=49>&nbsp;</td>";
            print " <td width=413>&nbsp;</td>";
            print " <td width=147><div align=right><b>MONTO TOTAL</b></div></td>";
            print " <td width=171><input name=txtmonto type=text class=texto-rojo id=txtmonto value=".$ldec_total." style='text-align:right' size='22' maxlength='20' readonly></td>";
            print "</tr>";
            print "</table>";
		}		
	}	
	
	function uf_cargar_dt_nota()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_cargar_dt_nota
		//		   Access: public 
		//	  Description: Funcion que carga los detalles de la nota 
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creacin: 29/05/2007 								Fecha ltima Modificacin : 03/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../../shared/class_folder/tepuy_include.php");
		$io_include=new tepuy_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
		require_once("../../shared/class_folder/grid_param.php");
		$io_grid=new grid_param();	
		$ds_detscg=new class_datastore();
	    global $io_funciones_cxp;
		$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
		$ls_confiva=$_SESSION["la_empresa"]["confiva"];
		$ls_codemp=$io_funciones_cxp->uf_obtenervalor("codemp","");
		$ls_numncnd=$io_funciones_cxp->uf_obtenervalor("numncnd",""); 
		$ls_numord=$io_funciones_cxp->uf_obtenervalor("numord",""); 
		$ls_numrecdoc=$io_funciones_cxp->uf_obtenervalor("numrecdoc",""); 
		$ls_codtipdoc=$io_funciones_cxp->uf_obtenervalor("codtipdoc","");
		$ls_tipproben=$io_funciones_cxp->uf_obtenervalor("tipproben",""); 
		$ls_codproben=$io_funciones_cxp->uf_obtenervalor("codproben",""); 
		$ld_fecha=$io_funciones_cxp->uf_obtenervalor("fecha",""); 
		$ls_tiponota =$io_funciones_cxp->uf_obtenervalor("tiponota",""); 
		$ldec_totaldebe=0;
		$ldec_totalhaber=0;		
		if($ls_tipproben=='P')
		{
			$ls_aux=" AND cxp.cod_pro='".$ls_codproben."' ";			
		}
		elseif($ls_tipo=='B')
		{
			$ls_aux=" AND cxp.ced_bene='".$ls_codproben."' ";
		}
		else
		{
			$ls_aux=" AND cxp.cod_pro='----------' ";
		}
		if($_SESSION["ls_gestor"]=="MYSQL")
		{
			$ls_codestpro=" CONCAT(spg.codestpro1,spg.codestpro2,spg.codestpro3,spg.codestpro4,spg.codestpro5)";
		}
		else
		{
			$ls_codestpro=" (spg.codestpro1||spg.codestpro2||spg.codestpro3||spg.codestpro4||spg.codestpro5)";
		}	
		$ls_sql=" SELECT cxp.*,spg.denominacion as dencta,spg.sc_cuenta,scg.denominacion as den_scg,DOC.estcon,DOC.estpre
				  FROM   cxp_dc_spg cxp,spg_cuentas spg,scg_cuentas scg,cxp_documento DOC
				  WHERE  cxp.codemp='".$ls_codemp."'  AND cxp.numdc    ='".$ls_numncnd."'   AND cxp.numrecdoc='".$ls_numrecdoc."' 
				  AND    cxp.numsol='".$ls_numord."'  AND cxp.codtipdoc='".$ls_codtipdoc."' ".$ls_aux." AND cxp.codope='".$ls_tiponota."' 
				  AND    cxp.spg_cuenta=spg.spg_cuenta AND cxp.codestpro=".$ls_codestpro." AND cxp.codemp=scg.codemp AND spg.sc_cuenta=scg.sc_cuenta AND cxp.codtipdoc=DOC.codtipdoc";

		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			
		}
		else
		{	
			$li=0;
			$ldec_total=0;
			$ldec_totalsincargo=0;
			$ldec_totalcargo=0;
			while($row=$io_sql->fetch_row($rs_data))
			{
				$li++;
				$ls_cuenta=$row["spg_cuenta"];
				$ls_codestpro=$row["codestpro"];
				$ls_codestproaux=$ls_codestpro;
				switch($ls_modalidad)
				{
					case "1": // Modalidad por Proyecto
						$ls_codestpro=substr($ls_codestpro,0,29);
						break;						
					case "2": // Modalidad por Programa
						$ls_codestpro1=substr(substr($ls_codestpro,0,20),-2);
						$ls_codestpro2=substr(substr($ls_codestpro,20,6),-2);
						$ls_codestpro3=substr(substr($ls_codestpro,26,3),-2);
						$ls_codestpro4=substr($ls_codestpro,29,2);
						$ls_codestpro5=substr($ls_codestpro,31,2);
						$ls_codestpro=$ls_codestpro1."-".$ls_codestpro2."-".$ls_codestpro3."-".$ls_codestpro4."-".$ls_codestpro5;
						break;
				}
				$ls_dencuenta=utf8_encode($row["dencta"]);
				$ldec_monto=$row["monto"];
				$ldec_total=$ldec_total+abs($ldec_monto);
				if($ls_tiponota=='NC')
				{
					$ldec_monto=$ldec_monto*-1;
				}
				$ldec_monto=number_format($ldec_monto,2,",",".");
				$ls_scgcuenta=$row["sc_cuenta"];
				$ls_denctascg=$row["den_scg"];
				if($ls_confiva=="P")
				{
					if(uf_check_cargo($ls_numrecdoc,$ls_codtipdoc,$ls_codestproaux,$ls_cuenta,$ls_tipproben,$ls_codproben))
					{
						$ls_cargo='C';
						$ldec_totalcargo=abs($row["monto"])+$ldec_totalcargo;
					}
					else
					{
						$ls_cargo='';
						$ldec_totalsincargo=$ldec_totalsincargo+abs($row["monto"]);
					}
				}
				else
				{
					$ls_cargo='';
					$ldec_totalsincargo=$ldec_totalsincargo+abs($row["monto"]);
				}
				$li_estcon=$row["estcon"];
				$li_estpre=$row["estpre"];
				$lo_object2[$li][1]="<input type=text name=txtcuentaspgncnd".$li."    id=txtcuentaspgncnd".$li." class=sin-borde style=text-align:center size=22 value='$ls_cuenta' readonly onClick='javascript:uf_select_filadelete($li);'><input type=hidden name=txtscgcuentadt".$li."    id=txtscgcuentadt".$li."  value='$ls_scgcuenta'><input type=hidden name=txtdenscgcuentadt".$li."    id=txtdenscgcuentadt".$li."  value='$ls_denctascg'><input type=hidden name=txtestcargo".$li."    id=txtestcargo".$li."  value='$ls_cargo'>";
				$lo_object2[$li][2]="<input type=text name=txtcodestproncnd".$li."    class=sin-borde style=text-align:center   size=40 value='$ls_codestpro'    readonly onClick='javascript:uf_select_filadelete($li);'>";
				$lo_object2[$li][3]="<input type=text name=txtdencuentancnd".$li."    class=sin-borde style=text-align:left size=39  value='$ls_dencuenta'       readonly onClick='javascript:uf_select_filadelete($li);'>"; 
				$lo_object2[$li][4]="<input type=text name=txtmontoncnd".$li."        class=sin-borde style=text-align:right  size=20 value='".$ldec_monto."' readonly onClick='javascript:uf_select_filadelete($li);uf_mostrar_alerta();'>";
				if(($li_estcon==1)&&(($li_estpre==3)||($li_estpre==4)))//Chequeo si es contable para pintar el presupuesto igual colocando la variable li en 0 y asignando a la varialbe de eliminacion cntable el valor de la fila a eliminar.
				{

				}
				else
				{
					$lo_object2[$li][5]="<a href=javascript:uf_delete_dtnota('".$li."');><img src=../shared/imagebank/tools15/eliminar.png title=Eliminar width=15 height=10 border=0></a>";
				}
			}
			$io_sql->free_result($rs_data);
		}
		$ldec_total=number_format($ldec_total,2,",",".");
		$ldec_totalsincargo=number_format($ldec_totalsincargo,2,",",".");
				
		$ls_sql=" SELECT cxp.*,scg.denominacion as dencta,DOC.estcon,DOC.estpre
				  FROM   cxp_dc_scg cxp,scg_cuentas scg,cxp_documento DOC
				  WHERE  cxp.codemp='".$ls_codemp."'  AND cxp.numdc    ='".$ls_numncnd."'   AND cxp.numrecdoc='".$ls_numrecdoc."' 
				  AND    cxp.numsol='".$ls_numord."'  AND cxp.codtipdoc='".$ls_codtipdoc."' ".$ls_aux." AND cxp.codope='".$ls_tiponota."' 
				  AND    cxp.sc_cuenta=scg.sc_cuenta  AND cxp.codtipdoc=DOC.codtipdoc 
				  ORDER BY cxp.estgenasi";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
		}
		else
		{
			$la=0;		
			while($row=$io_sql->fetch_row($rs_data))
			{
				$la++;
				$ls_scgcuenta=$row["sc_cuenta"];
				$ls_dencuenta=utf8_encode($row["dencta"]);
				$ls_debhab   =$row["debhab"];
				$ldec_monto  =number_format($row["monto"],2,",",".");
				if($ls_debhab=='D')
				{
					$ldec_mondebe=$ldec_monto;
					$ldec_totaldebe=$ldec_totaldebe+$row["monto"];		
					$ldec_monhaber="0,00";
				}
				else
				{
					$ldec_monhaber=$ldec_monto;
					$ldec_totalhaber=$ldec_totalhaber+$row["monto"];	
					$ldec_mondebe="0,00";
				}
				$li_estcon=$row["estcon"];
				$li_estpre=$row["estpre"];
			/*	if($ls_confiva=="C")
				{
					if($ls_debhab=='D')
					{
						if(uf_check_cargo($ls_numrecdoc,$ls_codtipdoc,"",$ls_scgcuenta,$ls_tipproben,$ls_codproben))
						{
							$ls_cargo='C';
							$ldec_totalcargo=abs($row["monto"])+$ldec_totalcargo;
						}
					}
				}
				else
				{
					$ls_cargo='';
					$ldec_totalsincargo=$ldec_totalsincargo+abs($row["monto"]);
				}*/
				$lo_object[$la][1]="<input type=text name=txtscgcuentancnd".$la."    id=txtscgcuentancnd".$la." class=sin-borde style=text-align:center size=22 value='$ls_scgcuenta'    readonly>";
				$lo_object[$la][2]="<input type=text name=txtdencuentascgncnd".$la."    class=sin-borde style=text-align:left   size=59 value='$ls_dencuenta'    readonly>";
				$lo_object[$la][3]="<input type=text name=txtdebencnd".$la."    class=sin-borde style=text-align:right size=20  value='$ldec_mondebe'   readonly onClick='javascript:uf_mostrar_alerta();'>"; 
				$lo_object[$la][4]="<input type=text name=txthaberncnd".$la."    class=sin-borde style=text-align:right  size=20 value='$ldec_monhaber' readonly onClick='javascript:uf_mostrar_alerta();'>";
				if(($li_estcon==1)&&(($li_estpre==3)||($li_estpre==4)))//Chequeo si es contable para pintar el presupuesto igual colocando la variable li en 0 y asignando a la varialbe de eliminacion cntable el valor de la fila a eliminar.
				{
					$lo_object2[$li][5]="<a href=javascript:uf_delete_dtnota('".$li."');><img src=../shared/imagebank/tools15/eliminar.png title=Eliminar width=15 height=10 border=0></a>";					
				}
			}
		}
		$lo_title[1]="C&oacute;digo Estad&iacute;stico";
		$lo_title[2]="C&oacute;digo Program&aacute;tico ";
		$lo_title[3]="Denominaci&oacute;n";
		$lo_title[4]="Monto";
		if(($li_estcon==1)&&(($li_estpre==3)||($li_estpre==4)))//Chequeo si es contable para pintar el presupuesto igual colocando la variable li en 0 y asignando a la varialbe de eliminacion cntable el valor de la fila a eliminar.
		{
		}
		else
		{
			$lo_title[5]="	";
		}
		$io_grid->make_gridScroll($li,$lo_title,$lo_object2,758,"Detalle Presupuestario de la Nota","grid",120);
		$lo_titlesc[1]="Cuenta";
		$lo_titlesc[2]="Denominaci&oacute;n";
		$lo_titlesc[3]="Monto Debe";
		$lo_titlesc[4]="Monto Haber";
		if(($li_estcon==1)&&(($li_estpre==3)||($li_estpre==4)))//Chequeo si es contable para pintar el presupuesto igual colocando la variable li en 0 y asignando a la varialbe de eliminacion cntable el valor de la fila a eliminar.
		{
			$lo_titlesc[5]="	";
		}
		$io_grid->make_gridScroll($la,$lo_titlesc,$lo_object,758,"Detalle Contable de la Nota","gridscg",120);	
		print "<input type=hidden name=numrowsprenota id=numrowsprenota value=".($li).">";
		print "<input type=hidden name=numrowsconnota id=numrowsconnota value=".($la).">";
		print "<table width='758' border='0' align='center' cellpadding='0' cellspacing='0' class='celdas-blancas'>";
		print "   <tr>";
		print "      <td width='508' height='22' align='right'><div align='right'><strong>Total Debe</strong></div></td>";
		print "      <td width='80'  height='22' align='right'><input name='txtmontodeb'  type='text' id='txtmontodeb' style='text-align:right' value=".number_format($ldec_totaldebe,2,",",".")." size='22' maxlength='20' readonly align='right' class='letras-negrita'></td>";
		print "      <td width='90'  height='22' align='right'><div align='right'><strong>Total Haber</strong></div></td>";
		print "      <td width='80'  height='22' align='right'><input name='txtmontohab'  type='text' id='txtmontohab' style='text-align:right' value=".number_format($ldec_totalhaber,2,",",".")." size='22' maxlength='20' readonly align='right' class='letras-negrita'></td>";
		print "   </tr>";
		print "        <tr>";
		print "          <td width='508' height='22' align='right'>&nbsp;</td>";
		print "          <td width='80' height='22' align='right'>&nbsp;</td>";
		print "          <td width='90' height='22' align='right'>&nbsp;</td>";
		print "          <td width='80' height='22' align='right'>&nbsp;</td>";
		print "        </tr>";				
		print "</table>";
		print "<table width=780 border=0 cellpadding=0 cellspacing=0 class=formato-blanco>";	
		print " <tr class=titulo-ventana>";
        print "  <td height=23 colspan=4><div align=center class=Estilo1><b>TOTALES</b></div></td>";
        print " </tr>";	
		print "<tr height=20>";
		print " <td width=49>&nbsp;</td>";
		print " <td width=413>&nbsp;</td>";
		print " <td width=167><div align=right><b>SUBTOTAL</b></div></td>";
		print " <td width=151><input name=txtmontosincargo type=text id=txtmontosincargo value=".$ldec_totalsincargo." style='text-align:right' class='letras-negrita' size='22' maxlength='20' readonly></td>";
		print "</tr>";
		print "<tr height=20>";
		print " <td width=49>&nbsp;</td>";
		print " <td width=413>&nbsp;</td>";
		print " <td width=167><div align=right><input name='btnotroscreditos' type='button' class='boton' id='btnotroscreditos' value='Otros Cr&eacute;ditos' onClick='javascript:uf_agregar_dtcargos(\"\",\"\",\"\",\"\",\"\");'></div></td>";
		print " <td width=151><input name=txtmontocargo type=text id=txtmontocargo value=".number_format($ldec_totalcargo,2,",",".")." style='text-align:right' class='letras-negrita' size='22' maxlength='20' readonly></td>";
		print "</tr>";
		print "<tr height=20>";
		print " <td width=49>&nbsp;</td>";
		print " <td width=413>&nbsp;</td>";
		print " <td width=147><div align=right><b>MONTO TOTAL</b></div></td>";
		print " <td width=171><input name=txtmonto type=text class=texto-rojo id=txtmonto value=".$ldec_total." style='text-align:right' size='22' maxlength='20'  readonly></td>";
		print "</tr>";
		print "</table>";		
	}
	
	function uf_check_cargo($ls_numrecdoc,$ls_codtipdoc,$ls_codestpro,$ls_cuenta,$ls_tipproben,$ls_codproben)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_check_cargo
		//		   Access: public 
		//	  Description: Funcion que verifica si el detalle presupuestario de la nota corresponde a un cargo
		//	   Creado Por: Ing. Nelson Barraez
		//  Fecha Creacin: 02/06/2007 								Fecha ltima Modificacin : 03/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		require_once("../../shared/class_folder/tepuy_include.php");
		$io_include=new tepuy_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
		if($ls_tipproben=='P')
		{
			$ls_aux=" AND cod_pro='".$ls_codproben."' ";			
		}
		elseif($ls_tipo=='B')
		{
			$ls_aux=" AND ced_bene='".$ls_codproben."' ";
		}
		else
		{
			$ls_aux=" AND cod_pro='----------' ";
		}
		$ls_aux_codestpro="";
		if($ls_codestpro!="")
		{
			if($_SESSION["ls_gestor"]=="MYSQL")
			{
				$ls_aux_codestpro=" AND CONCAT(codestpro1,codestpro2,codestpro3,codestpro4,codestpro5)='".$ls_codestpro."'";
			}
			else
			{
				$ls_aux_codestpro=" AND (codestpro1||codestpro2||codestpro3||codestpro4||codestpro5)='".$ls_codestpro."'";
			}
		}
		$ls_sql="SELECT * ".
				"  FROM cxp_rd_cargos ".
				" WHERE numrecdoc='".$ls_numrecdoc."'".
				"   AND codtipdoc='".$ls_codtipdoc."' ".$ls_aux."". 
				"   AND spg_cuenta='".trim($ls_cuenta)."'  ".$ls_aux_codestpro."";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->message("Error en metodo uf_check_cargo, CLASS->tepuy_cxp_c_ncnd_ajax ".$io_funciones->uf_convertirmsg($io_sql->message));
			return false;
		}
		else
		{
			if($row=$io_sql->fetch_row($rs_data))
			{
				return true;				
			}
			else
			{
				return false;
			}
		}		
	}//fin uf_check_cargo	
?>