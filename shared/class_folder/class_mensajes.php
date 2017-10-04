<?php
  ////////////////////////////////////////////////////////////////////////////////////////////////////////
  // Class       : class_mensajes
  // Description : Clase para el manejo de mensajes. 
  // Autor       : Ing. Miguel Palencia    
  ////////////////////////////////////////////////////////////////////////////////////////////////////////
class class_mensajes
{
	function class_mensajes() { } //Constructor de la clase.

	function message($ls_message)
	{
		print "<script language=javascript>";
		print " alert (\"$ls_message\");";
		print "</script>";
	} // end function

	function uf_mensajes_ajax($ls_title,$ls_mensaje,$lb_boton,$ls_onclick)
	{
		?>
		<link rel="stylesheet" href="../../shared/css/tablas.css" type="text/css" media="screen" />
		<style type="text/css">
		<!--
		.Estilo2 {
			color: #FFFFFF;
			font-weight: bold;
		}
		-->
		</style>
		<div id="divmsg" >
		<table  class="fondo-tabla" border="0" cellpadding="0" cellspacing="2" >
			<tr>					
			    <td width="258"  bordercolor="#006699" bgcolor="#666666"><span class="Estilo2"><?php print $ls_title;?></span></td>						
			</tr>
			<tr>
				<td height="81"  bordercolor="#006699" bgcolor="#FFFFFF">
					<div align="center"><?php print $ls_mensaje;?><p>&nbsp;</p></div>
					<?php 
					if($lb_boton)
					{
			        ?>
					<form id="form1" name="form1" method="post" action="">
	                <div align="center"><input name="Submit" type="button" class="celdas-grises" onClick='<?php print $ls_onclick;?>'  value="Aceptar"/></div>
			        </form>
					<?php 
					}
					?>
				</td>
			</tr>					
		</table>
		</div>
		<?php 
	}
	
	function confirm($ls_message,$lb_valid)
	{?>
		
		<script language=javascript>
			var respuesta =confirm("<?php print $ls_message?>");
			if(respuesta)
			{
				var valor=1;
				//window.location.href = window.location.href + "?valor=" + valor;
			}
			else
			{
				var valor=0;
				//window.location.href = window.location.href + "?valor=" + valor;
			}
		</script>
		<?php
		return ;
	}	// end function confirm


} // end class_mensajes
?>