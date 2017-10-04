<?
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Mayor Anal&iacute;tico</title>
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
$ls_orden=$_GET["orden"];
//print $li_total;
$class_report->uf_cargar_mayor_analitico($ld_fecdesde,$ld_fechasta,$ls_cuentadesde,$ls_cuentahasta,"sc_cuenta");


$li_cont=0;//Contador de lineas
$ldec_totaldebitos=0;
$ldec_totalcreditos=0;
$ldec_saldo=0;
$li_page=0;//Contador de numero de paginas
$li_maxlines=52;//Numero maximo de lineas
$li_total=$class_report->ds_analitico->getRowCount("sc_cuenta");
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
	$li_cont=$li_cont+2;//Incremento 2 al contador porque el detalle de cada cuenta lo manejo con 2 lineas.
	$ldec_mondeb=0;
	$ldec_monhab=0;
	$ls_comprobante=$class_report->ds_analitico->getValue("comprobante",$i);
	$ls_cuenta=$class_report->ds_analitico->getValue("sc_cuenta",$i);
	$ls_denominacion=$class_report->ds_analitico->getValue("denominacion",$i);
	$ls_procede=$class_report->ds_analitico->getValue("procede",$i);
	$ls_concepto=$class_report->ds_analitico->getValue("descripcion",$i);
	if($i>1)
	{
		$ls_cuentaanterior=$class_report->ds_analitico->getValue("sc_cuenta",$i-1);
		if($ls_cuenta==$ls_cuentaanterior)
		{
			$ls_cuenta="";
		}
		
	}
	$ldec_monto=$class_report->ds_analitico->getValue("monto",$i);
	$ld_fecmov=$class_report->ds_analitico->getValue("fecha",$i);
	$ls_debhab=$class_report->ds_analitico->getValue("debhab",$i);
	if($ls_debhab=='D')
	{
		$ldec_mondeb=$ldec_monto;
		$ldec_totaldebitos=$ldec_totaldebitos+$ldec_mondeb;
	}
	else
	{
		$ldec_monhab=$ldec_monto;		
		$ldec_totalcreditos=$ldec_totalcreditos+$ldec_monhab;
	}
	$ld_fecmov=$class_report->fun->uf_convertirfecmostrar($ld_fecmov);

	
	if(($li_cont==2))//si es el primero de la pagina
	{
		$li_page=$li_page+1;
		
		?>
	<table width="633" border="0" cellpadding="0" cellspacing="0" class="report">
      <tr>
        <td width="116"><img src="../../imagebank/logo.jpg" width="110" height="50">
            <div align="center"></div></td>
        <td colspan="4" valign="baseline">
        <div align="center"><strong>Mayor Analitico </strong></div></td>
        <td width="145" align="right" valign="top" class="fecha_report"><? print date("d/m/Y")?></td>
      </tr>
      <tr>
        <td height="25" colspan="6">&nbsp; </td>
      </tr>
      <tr>
        <td height="20" class="titulos">Comprobante</td>
        <td width="62" class="titulos"><div align="center">Procede</div></td>
        <td width="50" class="titulos"><div align="center">Fecha</div></td>
        <td width="158" align="center" class="titulos"><div align="left">Concepto</div></td>
        <td width="98" align="center" class="titulos">Debe</td>
        <td width="145" align="center" class="titulos">Haber</td>
      </tr>
      <?
	}
	if(!empty($ls_cuenta))
	{
	 ?>
	  <tr>
        <td class="data" align="left"><strong><? print $ls_cuenta;?></strong></td>
        <td colspan="5" align="left" class="data"><strong><? print $ls_denominacion?></strong></td>
      </tr>
	 
	 <?
	 }
	  ?>
      <tr>
        <td class="data" align="left"><? print $ls_comprobante;?></td>
        <td class="data" align="center"><? print $ls_procede;?></td>
        <td class="data" align="center"><? print $ld_fecmov;?></td>
        <td class="data" align="left"><? print $ls_concepto;?> </td>
        <td class="data" align="right"><? print number_format($ldec_mondeb,2,",",".");?></td>
        <td class="data" align="right"><? print number_format($ldec_monhab,2,",",".");?></td>
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
