<?php
$gi_total=19;

$arbol["sistema"][1]="SCF";
$arbol["nivel"][1]=0;
$arbol["nombre_logico"][1]="Procesos";
$arbol["nombre_fisico"][1]="";
$arbol["id"][1]="001";
$arbol["padre"][1]="000";
$arbol["numero_hijos"][1]=2;

$arbol["sistema"][2]="SCF";
$arbol["nivel"][2]=1;
$arbol["nombre_logico"][2]="Comprobantes Contables";
$arbol["nombre_fisico"][2]="tepuy_scf_p_comprobante.php";
$arbol["id"][2]="002";
$arbol["padre"][2]="001";
$arbol["numero_hijos"][2]=0;

$arbol["sistema"][3]="SCF";
$arbol["nivel"][3]=1;
$arbol["nombre_logico"][3]="Cierre de Ejercicio";
$arbol["nombre_fisico"][3]="";
$arbol["id"][3]="003";
$arbol["padre"][3]="001";
$arbol["numero_hijos"][3]=2;

$arbol["sistema"][4]="SCF";
$arbol["nivel"][4]=2;
$arbol["nombre_logico"][4]="Cierre Mensual";
$arbol["nombre_fisico"][4]="tepuy_scf_p_cierremensual.php";
$arbol["id"][4]="004";
$arbol["padre"][4]="003";
$arbol["numero_hijos"][4]=0;

$arbol["sistema"][5]="SCF";
$arbol["nivel"][5]=2;
$arbol["nombre_logico"][5]="Cierre Anual";
$arbol["nombre_fisico"][5]="tepuy_scf_p_cierreanual.php";
$arbol["id"][5]="005";
$arbol["padre"][5]="003";
$arbol["numero_hijos"][5]=0;

$arbol["sistema"][6]="SCF";
$arbol["nivel"][6]=0;
$arbol["nombre_logico"][6]="Reportes";
$arbol["nombre_fisico"][6]="";
$arbol["id"][6]="006";
$arbol["padre"][6]="000";
$arbol["numero_hijos"][6]=7;

$arbol["sistema"][7]="SCF";
$arbol["nivel"][7]=1;
$arbol["nombre_logico"][7]="Reportes - Mayor Analítico";
$arbol["nombre_fisico"][7]="tepuy_scf_r_mayor_analitico.php";
$arbol["id"][7]="007";
$arbol["padre"][7]="006";
$arbol["numero_hijos"][7]=0;

$arbol["sistema"][8]="SCF";
$arbol["nivel"][8]=1;
$arbol["nombre_logico"][8]="Reportes - Balance Comprobación";
$arbol["nombre_fisico"][8]="tepuy_scf_r_balance_comprobacion.php";
$arbol["id"][8]="008";
$arbol["padre"][8]="006";
$arbol["numero_hijos"][8]=0;

$arbol["sistema"][9]="SCF";
$arbol["nivel"][9]=1;
$arbol["nombre_logico"][9]="Reportes - Balance General";
$arbol["nombre_fisico"][9]="";
$arbol["id"][9]="009";
$arbol["padre"][9]="006";
$arbol["numero_hijos"][9]=2;

$arbol["sistema"][10]="SCF";
$arbol["nivel"][10]=2;
$arbol["nombre_logico"][10]="Reportes - Balance General Mensual";
$arbol["nombre_fisico"][10]="tepuy_scf_r_balance_general.php";
$arbol["id"][10]="010";
$arbol["padre"][10]="009";
$arbol["numero_hijos"][10]=0;

$arbol["sistema"][11]="SCF";
$arbol["nivel"][11]=2;
$arbol["nombre_logico"][11]="Reportes - Balance General Anual";
$arbol["nombre_fisico"][11]="tepuy_scf_r_balance_general_anual.php";
$arbol["id"][11]="011";
$arbol["padre"][11]="009";
$arbol["numero_hijos"][11]=0;

$arbol["sistema"][12]="SCF";
$arbol["nivel"][12]=1;
$arbol["nombre_logico"][12]="Reportes - Comprobantes";
$arbol["nombre_fisico"][12]="tepuy_scf_r_comprobantes.php";
$arbol["id"][12]="012";
$arbol["padre"][12]="006";
$arbol["numero_hijos"][12]=0;

$arbol["sistema"][13]="SCF";
$arbol["nivel"][13]=1;
$arbol["nombre_logico"][13]="Reportes - Listado Cuentas";
$arbol["nombre_fisico"][13]="tepuy_scf_r_listadocuentas.php";
$arbol["id"][13]="013";
$arbol["padre"][13]="006";
$arbol["numero_hijos"][13]=0;

$arbol["sistema"][14]="SCF";
$arbol["nivel"][14]=1;
$arbol["nombre_logico"][14]="Reportes - Plan Unico de Cuentas";
$arbol["nombre_fisico"][14]="tepuy_scf_r_listadoplanunico.php";
$arbol["id"][14]="014";
$arbol["padre"][14]="006";
$arbol["numero_hijos"][14]=0;

$arbol["sistema"][15]="SCF";
$arbol["nivel"][15]=1;
$arbol["nombre_logico"][15]="Reportes - Hoja de Trabajo";
$arbol["nombre_fisico"][15]="tepuy_scf_r_hoja_trabajo.php";
$arbol["id"][15]="015";
$arbol["padre"][15]="006";
$arbol["numero_hijos"][15]=0;

$arbol["sistema"][16]="SCF";
$arbol["nivel"][16]=0;
$arbol["nombre_logico"][16]="Configuración";
$arbol["nombre_fisico"][16]="";
$arbol["id"][16]="016";
$arbol["padre"][16]="000";
$arbol["numero_hijos"][16]=2;

$arbol["sistema"][17]="SCF";
$arbol["nivel"][17]=1;
$arbol["nombre_logico"][17]="Maestro de Cuentas Fiscales";
$arbol["nombre_fisico"][17]="tepuy_scg_d_plan_unico.php";
$arbol["id"][17]="017";
$arbol["padre"][17]="016";
$arbol["numero_hijos"][17]=0;

$arbol["sistema"][18]="SCF";
$arbol["nivel"][18]=1;
$arbol["nombre_logico"][18]="Plan de Cuentas";
$arbol["nombre_fisico"][18]="tepuy_scg_d_plan_ctas.php";
$arbol["id"][18]="018";
$arbol["padre"][18]="016";
$arbol["numero_hijos"][18]=0;

$arbol["sistema"][19]="SCF";
$arbol["nivel"][19]=1;
$arbol["nombre_logico"][19]="Institución";
$arbol["nombre_fisico"][19]="tepuy_cfg_d_empresa.php";
$arbol["id"][19]="019";
$arbol["padre"][19]="016";
$arbol["numero_hijos"][19]=0;
?>
