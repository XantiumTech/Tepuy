<?php
$gi_total=32;

$arbol["sistema"][1]="SFA";
$arbol["nivel"][1]=0;
$arbol["nombre_logico"][1]="Procesos";
$arbol["nombre_fisico"][1]="";
$arbol["id"][1]="001";
$arbol["padre"][1]="000";
$arbol["numero_hijos"][1]=2;

$arbol["sistema"][2]="SFA";
$arbol["nivel"][2]=1;
$arbol["nombre_logico"][2]="Productos";
$arbol["nombre_fisico"][2]="";
$arbol["id"][2]="002";
$arbol["padre"][2]="001";
$arbol["numero_hijos"][2]=2;

$arbol["sistema"][3]="SFA";
$arbol["nivel"][3]=2;
$arbol["nombre_logico"][3]="Tipo de Producto";
$arbol["nombre_fisico"][3]="tepuy_sfa_d_tipo_producto.php";
$arbol["id"][3]="003";
$arbol["padre"][3]="002";
$arbol["numero_hijos"][3]=0;

$arbol["sistema"][4]="SFA";
$arbol["nivel"][4]=2;
$arbol["nombre_logico"][4]="Registro de producto";
$arbol["nombre_fisico"][4]="tepuy_sfa_d_registro_producto.php";
$arbol["id"][4]="004";
$arbol["padre"][4]="002";
$arbol["numero_hijos"][4]=0;

$arbol["sistema"][5]="SFA";
$arbol["nivel"][5]=1;
$arbol["nombre_logico"][5]="Inventario";
$arbol["nombre_fisico"][5]="";
$arbol["id"][5]="005";
$arbol["padre"][5]="001";
$arbol["numero_hijos"][5]=2;

$arbol["sistema"][6]="SFA";
$arbol["nivel"][6]=2;
$arbol["nombre_logico"][6]="Registro";
$arbol["nombre_fisico"][6]="tepuy_sfa_d_productos_inventario.php";
$arbol["id"][6]="006";
$arbol["padre"][6]="006";
$arbol["numero_hijos"][6]=0;

$arbol["sistema"][7]="SFA";
$arbol["nivel"][7]=2;
$arbol["nombre_logico"][7]="Reactualizar inventario";
$arbol["nombre_fisico"][7]="tepuy_sfa_d_reactualizar_inventario.php";
$arbol["id"][7]="007";
$arbol["padre"][7]="005";
$arbol["numero_hijos"][7]=0;

$arbol["sistema"][8]="SFA";
$arbol["nivel"][8]=1;
$arbol["nombre_logico"][8]="Clientes";
$arbol["nombre_fisico"][8]="";
$arbol["id"][8]="008";
$arbol["padre"][8]="001";
$arbol["numero_hijos"][8]=2;

$arbol["sistema"][9]="SFA";
$arbol["nivel"][9]=2;
$arbol["nombre_logico"][9]="Registrar";
$arbol["nombre_fisico"][9]="tepuy_sfa_d_clientes.php";
$arbol["id"][9]="009";
$arbol["padre"][9]="008";
$arbol["numero_hijos"][9]=0;

$arbol["sistema"][10]="SFA";
$arbol["nivel"][10]=2;
$arbol["nombre_logico"][10]="Tranferencia del Personal de Nómina a Cliente";
$arbol["nombre_fisico"][10]="tepuy_sfa_d_transferencia.php";
$arbol["id"][10]="010";
$arbol["padre"][10]="008";
$arbol["numero_hijos"][10]=0;

$arbol["sistema"][11]="SFA";
$arbol["nivel"][11]=1;
$arbol["nombre_logico"][11]="Pedidos";
$arbol["nombre_fisico"][11]="tepuy_sfa_d_pedidos.php";
$arbol["id"][11]="011";
$arbol["padre"][11]="001";
$arbol["numero_hijos"][11]=0;

$arbol["sistema"][12]="SFA";
$arbol["nivel"][12]=1;
$arbol["nombre_logico"][12]="Facturacion";
$arbol["nombre_fisico"][12]="tepuy_sfa_d_facturar.php";
$arbol["id"][12]="012";
$arbol["padre"][12]="001";
$arbol["numero_hijos"][12]=0;


$arbol["sistema"][13]="SFA";
$arbol["nivel"][13]=1;
$arbol["nombre_logico"][13]="Comprobantes de Retencion";
$arbol["nombre_fisico"][13]="";
$arbol["id"][13]="013";
$arbol["padre"][13]="001";
$arbol["numero_hijos"][13]=2;

$arbol["sistema"][14]="SFA";
$arbol["nivel"][14]=2;
$arbol["nombre_logico"][14]="Crear comprobantes";
$arbol["nombre_fisico"][14]="tepuy_sfa_d_comp_retencion.php";
$arbol["id"][14]="014";
$arbol["padre"][14]="013";
$arbol["numero_hijos"][14]=0;

$arbol["sistema"][15]="SFA";
$arbol["nivel"][15]=2;
$arbol["nombre_logico"][15]="Editar comprobantes";
$arbol["nombre_fisico"][15]="tepuy_sfa_d_editcmpreten.php";
$arbol["id"][15]="015";
$arbol["padre"][15]="013";
$arbol["numero_hijos"][15]=0;

$arbol["sistema"][16]="SFA";
$arbol["nivel"][16]=1;
$arbol["nombre_logico"][16]="Contabilizar Facturas";
$arbol["nombre_fisico"][16]="";
$arbol["id"][16]="016";
$arbol["padre"][16]="001";
$arbol["numero_hijos"][16]=2;

$arbol["sistema"][17]="SFA";
$arbol["nivel"][17]=2;
$arbol["nombre_logico"][17]="Cierre Diario";
$arbol["nombre_fisico"][17]="tepuy_mis_p_contabiliza_SFA.php";
$arbol["id"][17]="017";
$arbol["padre"][17]="016";
$arbol["numero_hijos"][17]=0;

$arbol["sistema"][18]="SFA";
$arbol["nivel"][18]=2;
$arbol["nombre_logico"][18]="Reverso de Cierre";
$arbol["nombre_fisico"][18]="tepuy_mis_p_reverso_SFA.php";
$arbol["id"][18]="018";
$arbol["padre"][18]="016";
$arbol["numero_hijos"][18]=0;

////////////////////////// REPORTES //////////////////
$arbol["sistema"][19]= "SFA";
$arbol["nivel"][19]= 0;
$arbol["nombre_logico"][19]= "Reportes";
$arbol["nombre_fisico"][19]= "";
$arbol["id"][19]= "019";
$arbol["padre"][19]= "000";
$arbol["numero_hijos"][19]= 3;

$arbol["sistema"][20]= "SFA";
$arbol["nivel"][20]= 1;
$arbol["nombre_logico"][20]= "Listado de Facturas";
$arbol["nombre_fisico"][20]= "tepuy_sfa_r_resumen_facturas.php";
$arbol["id"][20]= "020";
$arbol["padre"][20]= "019";
$arbol["numero_hijos"][20]= 0;

$arbol["sistema"][21]= "SFA";
$arbol["nivel"][21]= 1;
$arbol["nombre_logico"][21]= "Resumen de Inventario";
$arbol["nombre_fisico"][21]= "tepuy_sfa_r_mov_inventario.php";
$arbol["id"][21]= "021";
$arbol["padre"][21]= "019";
$arbol["numero_hijos"][21]= 0;


$arbol["sistema"][22]="SFA";
$arbol["nivel"][22]=1;
$arbol["nombre_logico"][22]="Retenciones";
$arbol["nombre_fisico"][22]="";
$arbol["id"][22]="022";
$arbol["padre"][22]="019";
$arbol["numero_hijos"][22]=7;

$arbol["sistema"][23]="SFA";
$arbol["nivel"][23]=2;
$arbol["nombre_logico"][23]="Formato Unificado de Retenciones";
$arbol["nombre_fisico"][23]="tepuy_sfa_r_retencionesunificadas.php";
$arbol["id"][23]="023";
$arbol["padre"][23]="022";
$arbol["numero_hijos"][23]=0;

$arbol["sistema"][24]="SFA";
$arbol["nivel"][24]=2;
$arbol["nombre_logico"][24]="Reportes - Retenciones IVA";
$arbol["nombre_fisico"][24]="tepuy_sfa_r_retencionesiva.php";
$arbol["id"][24]="024";
$arbol["padre"][24]="022";
$arbol["numero_hijos"][24]=0;

$arbol["sistema"][25]="SFA";
$arbol["nivel"][25]=2;
$arbol["nombre_logico"][25]="Declaracion Informativa de I.V.A.";
$arbol["nombre_fisico"][25]="tepuy_sfa_r_retencionesdeclaracioniva.php";
$arbol["id"][25]="025";
$arbol["padre"][25]="022";
$arbol["numero_hijos"][25]=0;

$arbol["sistema"][26]="SFA";
$arbol["nivel"][26]=2;
$arbol["nombre_logico"][26]="Retenciones I.S.L.R.";
$arbol["nombre_fisico"][26]="tepuy_sfa_r_retencionesislr.php";
$arbol["id"][26]="026";
$arbol["padre"][26]="022";
$arbol["numero_hijos"][26]=0;

$arbol["sistema"][27]="SFA";
$arbol["nivel"][27]=2;
$arbol["nombre_logico"][27]="Declaracion Informativa de I.S.L.R.";
$arbol["nombre_fisico"][27]="tepuy_sfa_r_retencionesdeclaracionislr.php";
$arbol["id"][27]="027";
$arbol["padre"][27]="022";
$arbol["numero_hijos"][27]=0;

$arbol["sistema"][28]="SFA";
$arbol["nivel"][28]=2;
$arbol["nombre_logico"][28]="Retenciones Municipales";
$arbol["nombre_fisico"][28]="tepuy_sfa_r_retencionesmunicipales.php";
$arbol["id"][28]="028";
$arbol["padre"][28]="022";
$arbol["numero_hijos"][28]=0;

$arbol["sistema"][29]="SFA";
$arbol["nivel"][29]=2;
$arbol["nombre_logico"][29]="Reportes - Retenciones Timbre Fiscal";
$arbol["nombre_fisico"][29]="tepuy_sfa_r_retencionestimbrefiscal.php";
$arbol["id"][29]="029";
$arbol["padre"][29]="022";
$arbol["numero_hijos"][29]=0;

$arbol["sistema"][30]= "SFA";
$arbol["nivel"][30]= 1;
$arbol["nombre_logico"][30]= "Libro de Ventas";
$arbol["nombre_fisico"][30]= "tepuy_sfa_r_libroventas.php";
$arbol["id"][30]= "030";
$arbol["padre"][30]= "019";
$arbol["numero_hijos"][30]= 0;


//////////////////// FIN DE REPORTES ////////////////////////
$arbol["sistema"][31]= "SFA";
$arbol["nivel"][31]= 0;
$arbol["nombre_logico"][31]= "Configuracion";
$arbol["nombre_fisico"][31]= "";
$arbol["id"][31]= "031";
$arbol["padre"][31]= "000";
$arbol["numero_hijos"][31]= 1;

$arbol["sistema"][32]= "SFA";
$arbol["nivel"][32]= 1;
$arbol["nombre_logico"][32]= "Formas de Pago";
$arbol["nombre_fisico"][32]= "tepuy_sfa_d_formapago.php";
$arbol["id"][32]= "032";
$arbol["padre"][32]= "031";
$arbol["numero_hijos"][32]= 0;

?>
