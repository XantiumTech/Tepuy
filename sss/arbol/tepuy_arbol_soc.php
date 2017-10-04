<?php
$gi_total=36;

$arbol["sistema"][1]="SOC";
$arbol["nivel"][1]=0;
$arbol["nombre_logico"][1]="Procesos";
$arbol["nombre_fisico"][1]="";
$arbol["id"][1]="001";
$arbol["padre"][1]="000";
$arbol["numero_hijos"][1]=2;

$arbol["sistema"][2]="SOC";
$arbol["nivel"][2]=1;
$arbol["nombre_logico"][2]="Cotizaciones";
$arbol["nombre_fisico"][2]="";
$arbol["id"][2]="002";
$arbol["padre"][2]="001";
$arbol["numero_hijos"][2]=5;

$arbol["sistema"][3]="SOC";
$arbol["nivel"][3]=2;
$arbol["nombre_logico"][3]="Solicitud";
$arbol["nombre_fisico"][3]="tepuy_soc_p_solicitud_cotizacion.php";
$arbol["id"][3]="003";
$arbol["padre"][3]="002";
$arbol["numero_hijos"][3]=0;

$arbol["sistema"][4]="SOC";
$arbol["nivel"][4]=2;
$arbol["nombre_logico"][4]="Recepcion";
$arbol["nombre_fisico"][4]="tepuy_soc_p_registro_cotizacion.php";
$arbol["id"][4]="004";
$arbol["padre"][4]="002";
$arbol["numero_hijos"][4]=0;

$arbol["sistema"][5]="SOC";
$arbol["nivel"][5]=2;
$arbol["nombre_logico"][5]="Analisis y Revision";
$arbol["nombre_fisico"][5]="tepuy_soc_p_analisis_cotizacion.php";
$arbol["id"][5]="005";
$arbol["padre"][5]="002";
$arbol["numero_hijos"][5]=0;

$arbol["sistema"][6]="SOC";
$arbol["nivel"][6]=2;
$arbol["nombre_logico"][6]="Aprobacion del Analisis";
$arbol["nombre_fisico"][6]="tepuy_soc_p_aprobacion_analisis_cotizacion.php";
$arbol["id"][6]="006";
$arbol["padre"][6]="002";
$arbol["numero_hijos"][6]=0;

$arbol["sistema"][7]="SOC";
$arbol["nivel"][7]=2;
$arbol["nombre_logico"][7]="Generacion de Orden de Compra";
$arbol["nombre_fisico"][7]="tepuy_soc_p_generar_orden.php";
$arbol["id"][7]="007";
$arbol["padre"][7]="002";
$arbol["numero_hijos"][7]=0;

$arbol["sistema"][8]="SOC";
$arbol["nivel"][8]=1;
$arbol["nombre_logico"][8]="Ordenes de Compras";
$arbol["nombre_fisico"][8]="";
$arbol["id"][8]="008";
$arbol["padre"][8]="001";
$arbol["numero_hijos"][8]=4;

$arbol["sistema"][9]="SOC";
$arbol["nivel"][9]=2;
$arbol["nombre_logico"][9]="Registro de Orden de Compra";
$arbol["nombre_fisico"][9]="tepuy_soc_p_registro_orden_compra.php";
$arbol["id"][9]="009";
$arbol["padre"][9]="008";
$arbol["numero_hijos"][9]=0;

$arbol["sistema"][10]="SOC";
$arbol["nivel"][10]=2;
$arbol["nombre_logico"][10]="Aprobacion de Orden";
$arbol["nombre_fisico"][10]="tepuy_soc_p_aprobacion_orden_compra.php";
$arbol["id"][10]="010";
$arbol["padre"][10]="008";
$arbol["numero_hijos"][10]=0;

$arbol["sistema"][11]="SOC";
$arbol["nivel"][11]=2;
$arbol["nombre_logico"][11]="Anulacion de Orden sin Contabilizar";
$arbol["nombre_fisico"][11]="tepuy_soc_p_anulacion_orden_compra.php";
$arbol["id"][11]="011";
$arbol["padre"][11]="008";
$arbol["numero_hijos"][11]=0;

$arbol["sistema"][12]="SOC";
$arbol["nivel"][12]=2;
$arbol["nombre_logico"][12]="Reverso de Anulacion de Orden sin Contabilizar";
$arbol["nombre_fisico"][12]="tepuy_soc_p_reverso_anula_soc.php";
$arbol["id"][12]="012";
$arbol["padre"][12]="008";
$arbol["numero_hijos"][12]=0;

$arbol["sistema"][13]="SOC";
$arbol["nivel"][13]=2;
$arbol["nombre_logico"][13]="Aceptacion/Reverso de Servicios";
$arbol["nombre_fisico"][13]="tepuy_soc_p_aceptacion_servicio.php";
$arbol["id"][13]="013";
$arbol["padre"][13]="008";
$arbol["numero_hijos"][13]=0;

$arbol["sistema"][14]="SOC";
$arbol["nivel"][14]=1;
$arbol["nombre_logico"][14]="Contabilizar Ordenes de Compras";
$arbol["nombre_fisico"][14]="";
$arbol["id"][14]="014";
$arbol["padre"][14]="001";
$arbol["numero_hijos"][14]=4;

$arbol["sistema"][15]="SOC";
$arbol["nivel"][15]=2;
$arbol["nombre_logico"][15]="Contabilizar Orden de Compra";
$arbol["nombre_fisico"][15]="tepuy_mis_p_contabiliza_soc.php";
$arbol["id"][15]="015";
$arbol["padre"][15]="014";
$arbol["numero_hijos"][15]=0;

$arbol["sistema"][16]="SOC";
$arbol["nivel"][16]=2;
$arbol["nombre_logico"][16]="Reverso de Contabilizacion";
$arbol["nombre_fisico"][16]="tepuy_mis_p_reverso_soc.php";
$arbol["id"][16]="016";
$arbol["padre"][16]="014";
$arbol["numero_hijos"][16]=0;

$arbol["sistema"][17]="SOC";
$arbol["nivel"][17]=2;
$arbol["nombre_logico"][17]="Anular Orden de Compra";
$arbol["nombre_fisico"][17]="tepuy_mis_p_anula_soc.php";
$arbol["id"][17]="017";
$arbol["padre"][17]="014";
$arbol["numero_hijos"][17]=0;

$arbol["sistema"][18]="SOC";
$arbol["nivel"][18]=2;
$arbol["nombre_logico"][18]="Reversar Anulacion";
$arbol["nombre_fisico"][18]="tepuy_mis_p_reverso_anula_soc.php";
$arbol["id"][18]="018";
$arbol["padre"][18]="014";
$arbol["numero_hijos"][18]=0;

$arbol["sistema"][19]= "SOC";
$arbol["nivel"][19]= 1;
$arbol["nombre_logico"][19]= "Proveedores";
$arbol["nombre_fisico"][19]= "";
$arbol["id"][19]= "019";
$arbol["padre"][19]= "001";
$arbol["numero_hijos"][19]= 5;

$arbol["sistema"][20]= "SOC";
$arbol["nivel"][20]= 2;
$arbol["nombre_logico"][20]= "Parametros de Calificacion";
$arbol["nombre_fisico"][20]= "tepuy_rpc_d_clasificacion.php";
$arbol["id"][20]= "020";
$arbol["padre"][20]= "019";
$arbol["numero_hijos"][20]= 0;

$arbol["sistema"][21]= "SOC";
$arbol["nivel"][21]= 2;
$arbol["nombre_logico"][21]= "Maestro de Recaudos";
$arbol["nombre_fisico"][21]= "tepuy_rpc_d_documento.php";
$arbol["id"][21]= "021";
$arbol["padre"][21]= "019";
$arbol["numero_hijos"][21]= 0;

$arbol["sistema"][22]= "SOC";
$arbol["nivel"][22]= 2;
$arbol["nombre_logico"][22]= "Especialidad";
$arbol["nombre_fisico"][22]= "tepuy_rpc_d_especialidad.php";
$arbol["id"][22]= "022";
$arbol["padre"][22]= "019";
$arbol["numero_hijos"][22]= 0;

$arbol["sistema"][23]= "SOC";
$arbol["nivel"][23]= 2;
$arbol["nombre_logico"][23]= "Tipo de Empresa";
$arbol["nombre_fisico"][23]= "tepuy_rpc_d_tipoempresa.php";
$arbol["id"][23]= "023";
$arbol["padre"][23]= "019";
$arbol["numero_hijos"][23]= 0;

$arbol["sistema"][24]= "SOC";
$arbol["nivel"][24]= 2;
$arbol["nombre_logico"][24]= "Ficha";
$arbol["nombre_fisico"][24]= "tepuy_rpc_d_proveedor.php";
$arbol["id"][24]= "024";
$arbol["padre"][24]= "019";
$arbol["numero_hijos"][24]= 0;

$arbol["sistema"][25]= "SOC";
$arbol["nivel"][25]= 0;
$arbol["nombre_logico"][25]= "Reportes";
$arbol["nombre_fisico"][25]= "";
$arbol["id"][25]= "025";
$arbol["padre"][25]= "000";
$arbol["numero_hijos"][25]= 7;

$arbol["sistema"][26]= "SOC";
$arbol["nivel"][26]= 1;
$arbol["nombre_logico"][26]= "Listado de Ordenes de Compra";
$arbol["nombre_fisico"][26]= "tepuy_soc_r_orden_compra.php";
$arbol["id"][26]= "026";
$arbol["padre"][26]= "025";
$arbol["numero_hijos"][26]= 0;

$arbol["sistema"][27]= "SOC";
$arbol["nivel"][27]= 1;
$arbol["nombre_logico"][27]= "Sumario de Ordenes de Compra";
$arbol["nombre_fisico"][27]= "tepuy_soc_r_sumario_orden_compra.php";
$arbol["id"][27]= "027";
$arbol["padre"][27]= "025";
$arbol["numero_hijos"][27]= 0;

$arbol["sistema"][28]= "SOC";
$arbol["nivel"][28]= 1;
$arbol["nombre_logico"][28]= "Solicitud de Cotizacion";
$arbol["nombre_fisico"][28]= "tepuy_soc_r_solicitud_cotizacion.php";
$arbol["id"][28]= "028";
$arbol["padre"][28]= "025";
$arbol["numero_hijos"][28]= 0;

$arbol["sistema"][29]= "SOC";
$arbol["nivel"][29]= 1;
$arbol["nombre_logico"][29]= "Registro de Cotizaciones";
$arbol["nombre_fisico"][29]= "tepuy_soc_r_registro_cotizacion.php";
$arbol["id"][29]= "029";
$arbol["padre"][29]= "025";
$arbol["numero_hijos"][29]= 0;

$arbol["sistema"][30]= "SOC";
$arbol["nivel"][30]= 1;
$arbol["nombre_logico"][30]= "Analisis de Cotizaciones";
$arbol["nombre_fisico"][30]= "tepuy_soc_r_analisis_cotizacion.php";
$arbol["id"][30]= "030";
$arbol["padre"][30]= "025";
$arbol["numero_hijos"][30]= 0;

$arbol["sistema"][31]= "SOC";
$arbol["nivel"][31]= 1;
$arbol["nombre_logico"][31]= "Acta de Aceptacion de Servicios";
$arbol["nombre_fisico"][31]= "tepuy_soc_r_aceptacion_servicio.php";
$arbol["id"][31]= "031";
$arbol["padre"][31]= "025";
$arbol["numero_hijos"][31]= 0;

$arbol["sistema"][32]= "SOC";
$arbol["nivel"][32]= 1;
$arbol["nombre_logico"][32]= "Listado de Proveedores";
$arbol["nombre_fisico"][32]= "tepuy_soc_r_proveedores.php";
$arbol["id"][32]= "032";
$arbol["padre"][32]= "025";
$arbol["numero_hijos"][32]= 0;

$arbol["sistema"][33]= "SOC";
$arbol["nivel"][33]= 1;
$arbol["nombre_logico"][33]= "Ficha del Proveedor";
$arbol["nombre_fisico"][33]= "tepuy_rpc_r_fichas.php";
$arbol["id"][33]= "033";
$arbol["padre"][33]= "025";
$arbol["numero_hijos"][33]= 0;

$arbol["sistema"][34]= "SOC";
$arbol["nivel"][34]= 0;
$arbol["nombre_logico"][34]= "Configuracion";
$arbol["nombre_fisico"][34]= "";
$arbol["id"][34]= "034";
$arbol["padre"][34]= "000";
$arbol["numero_hijos"][34]= 2;

$arbol["sistema"][35]= "SOC";
$arbol["nivel"][35]= 1;
$arbol["nombre_logico"][35]= "Tipos de Servicios";
$arbol["nombre_fisico"][35]= "tepuy_soc_d_tiposer.php";
$arbol["id"][35]= "035";
$arbol["padre"][35]= "034";
$arbol["numero_hijos"][35]= 0;

$arbol["sistema"][36]= "SOC";
$arbol["nivel"][36]= 1;
$arbol["nombre_logico"][36]= "Servicios";
$arbol["nombre_fisico"][36]= "tepuy_soc_d_servicio.php";
$arbol["id"][36]= "036";
$arbol["padre"][36]= "034";
$arbol["numero_hijos"][36]= 0;

?>
