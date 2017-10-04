<?
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Balance Comprobaci&oacute;n</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
body {
	margin-left: 15px;
	margin-top: 15px;
	margin-right: 15px;
	margin-bottom: 15px;
}
-->
</style>
<link href="../../shared/css/report.css" rel="stylesheet" type="text/css">
</head>

<body>
<?
require_once("../../shared/class_folder/tepuy_include.php");
$sig_inc=new tepuy_include();
$con=$sig_inc->uf_conectar();
require_once("tepuy_scg_class_report.php");
$class_report=new tepuy_scg_class_report($con);
$ld_fecdesde=$_GET["fecdes"];
$ld_fechasta=$_GET["fechas"];
$ls_cuentadesde=$_GET["cuentadesde"];
$ls_cuentahasta=$_GET["cuentahasta"];
$li_nivel=$_GET["nivel"];
$ls_orden=$_GET["orden"];
//print $li_total;
$class_report->uf_scg_reporte_balance_comprobante($ls_cuentadesde,$ls_cuentahasta,$ld_fecdesde,$ld_fechasta,$li_nivel);


$li_cont=0;//Contador de lineas
$ldec_totaldebitos=0;
$ldec_totalcreditos=0;
$ldec_saldo=0;
$li_page=0;//Contador de numero de paginas
$li_maxlines=52;//Numero maximo de lineas
$li_total=$class_report->dts_reporte->getRowCount("sc_cuenta");
$li_aux=$li_total/$li_maxlines;//Auxiliar para obtener el numero aproximado de paginas
$li_total_page=ceil($li_aux);//Numero exacto de paginas, el metodo ceil() redondea el numero enviado a la escala siguiente
if($li_total==0)
{
	?>
	<script language="javascript">
		alert("No hay Registros a mostrar");
		close();
	</script>
	<?
}

for($i=1;$i<=$li_total;$i++)
{
	$li_cont=$li_cont+1;//Incremento 2 al contador porque el detalle de cada cuenta lo manejo con 2 lineas.
	$ls_cuenta=$class_report->dts_reporte->getValue("sc_cuenta",$i);
	$ls_denominacion=$class_report->dts_reporte->getValue("denominacion",$i);
	$li_len=strlen($ls_denominacion);
	if($li_len>19)
	{
		$ls_denominacion=substr($ls_denominacion,0,18)."..";
	}
	$ldec_saldo_ant=$class_report->dts_reporte->getValue("saldo_ant",$i);
	$ldec_debe=$class_report->dts_reporte->getValue("debe",$i);
	$ldec_totaldebitos=$ldec_totaldebitos+$ldec_debe;
	$ldec_haber=$class_report->dts_reporte->getValue("haber",$i);
	$ldec_totalcreditos=$ldec_totalcreditos+$ldec_haber;
	$ldec_saldo_act=$class_report->dts_reporte->getValue("saldo_act",$i);
	
	if(($li_cont==1))//si es el primero de la pagina
	{
		$li_page=$li_page+1;
		
		?>
	<table width="656" border="0" cellpadding="0" cellspacing="0" class="report">
      <tr>
        <td width="91"><img src="../../imagebank/logo.jpg" width="87" height="50">
        <div align="center"></div></td>
        <td colspan="4" valign="baseline">
          <div align="center"><strong>Balance Comprobaci&oacute;n </strong></div></td>
        <td width="106" align="right" valign="top" class="fecha_report"><? print date("d/m/Y")?></td>
      </tr>
      <tr>
        <td height="25" colspan="6">&nbsp; </td>
      </tr>
      <tr>
        <td height="20" class="titulos">Cuenta</td>
        <td width="140" class="titulos"><div align="left">Denominaci&oacute;n</div></td>
        <td width="104" class="titulos"><div align="center">Sal<cite>do Anterior </cite></div></td>
        <td width="105" align="center" class="titulos"><div align="center">Debe</div></td>
        <td width="106" align="center" class="titulos">Haber</td>
        <td width="106" align="center" class="titulos">Saldo Actual </td>
      </tr>
      <?
	}
	
	  ?>
      <tr>
        <td class="data" align="left"><? print $ls_cuenta;?></td>
        <td class="data" align="left"><? print $ls_denominacion;?></td>
        <td class="data" align="right"><? print number_format($ldec_saldo_ant,2,",",".");?></td>
        <td class="data" align="right"><? print number_format($ldec_debe,2,",",".");?> </td>
        <td class="data" align="right"><? print number_format($ldec_haber,2,",",".");?></td>
        <td class="data" align="right"><? print number_format($ldec_saldo_act,2,",",".");?></td>
      </tr>
      <?
	if(($li_cont>=$li_maxlines))//Si numero de lineas es mayor o igual al maximo de lineas.
	{
		$li_cont=0;
		?>
    </table>
	<table  width="930">
	 <tr>
	 <? print "<td style=text-align:right>".$li_page." de ".$li_total_page."</td>";?>	 
	 </tr>
</table>
	 <br>	  
	<?
	}
	elseif(($i==$li_total)&&($li_cont<=$li_maxlines))//Si numero de registro == al total de registros y contador <= al numero maximo de lineas, indica que es el final de los registros 
	{
		$ldec_saldo=$ldec_totalcreditos-$ldec_totaldebitos;//Calculo del saldo total para todas las cuentas
		?>
		<tr>
		  <td colspan="6" align="right">
			<table class="report">
		<tr>
 		    <td class="data">&nbsp;</td>
			<td class="data"></td>
			<td class="data" align="center">&nbsp;</td>
			<td class="data"><div align="right"><strong>Total Créditos:</strong></div></td>
			<td align="right" class="data"><? print number_format($ldec_totalcreditos,2,",",".");?></td>
			</tr>
		<tr>
 		    <td class="data">&nbsp;</td>
			<td class="data"></td>
			<td class="data" align="center">&nbsp;</td>
			<td class="data"><div align="right"><strong>Total Débitos:</strong></div></td>
			<td align="right" class="data"><? print number_format($ldec_totaldebitos,2,",",".");?></td>
			</tr>
		<tr>
 		    <td class="data">&nbsp;</td>
			<td class="data"></td>
			<td class="data" align="center">&nbsp;</td>
			<td class="data"><div align="right"><strong>Total Saldo:</strong></div></td>
			<td align="right" class="data"><? print number_format($ldec_saldo,2,",",".");?></td>
			</tr>
		</table>
		  </td>
</tr>
		<?
		$li_cont=$li_cont+3;//Incremento 3 por las filas de total debito, total credito, total saldo.
		for($x=$li_cont+1;$x<=$li_maxlines;$x++)//hago un ciclo para terminar de llenar de espacios en blanco en caso de que no se haya llegado al final de la pagina.
		{
		?>
		  <tr>
			<td colspan="3">&nbsp;</td>
		  </tr>
		<?
		}
		 ?>
		 </table>
		 <table width="930">
			 <tr>
			 <? //print "<td  style=text-align:right>".$li_page." de ".$li_total_page."</td>";?>	 
			 </tr>
		 </table>		 
	<?
	}
}
?> 
</body>
<script language="javascript">
//window.print();
</script>
</html>
