<?php
$gi_total=24;
$arbol["sistema"][1]="SPI";
$arbol["nivel"][1]=0;
$arbol["nombre_logico"][1]="Procesos";
$arbol["nombre_fisico"][1]="";
$arbol["id"][1]="001";
$arbol["padre"][1]="000";
$arbol["numero_hijos"][1]=5;

$arbol["sistema"][2]="SPI";
$arbol["nivel"][2]=1;
$arbol["nombre_logico"][2]="Formulación de Ingresos";
$arbol["nombre_fisico"][2]="tepuy_spi_p_apertura";
$arbol["id"][2]="002";
$arbol["padre"][2]="001";
$arbol["numero_hijos"][2]=0;

$arbol["sistema"][3]="SPI";
$arbol["nivel"][3]=1;
$arbol["nombre_logico"][3]="Comprobante de Ejecución Financiera";
$arbol["nombre_fisico"][3]="tepuy_spi_p_comprobante.php";
$arbol["id"][3]="003";
$arbol["padre"][3]="001";
$arbol["numero_hijos"][3]=0;

$arbol["sistema"][4]="SPI";
$arbol["nivel"][4]=1;
$arbol["nombre_logico"][4]="Modificaciones Presupuestarias";
$arbol["nombre_fisico"][4]="";
$arbol["id"][4]="004";
$arbol["padre"][4]="001";
$arbol["numero_hijos"][4]=2;

$arbol["sistema"][5]="SPI";
$arbol["nivel"][5]=2;
$arbol["nombre_logico"][5]="Aumentos";
$arbol["nombre_fisico"][5]="tepuy_spi_p_aumento.php";
$arbol["id"][5]="005";
$arbol["padre"][5]="004";
$arbol["numero_hijos"][5]=0;

$arbol["sistema"][6]="SPI";
$arbol["nivel"][6]=2;
$arbol["nombre_logico"][6]="Disminuciones";
$arbol["nombre_fisico"][6]="tepuy_spi_p_disminucion.php";
$arbol["id"][6]="006";
$arbol["padre"][6]="004";
$arbol["numero_hijos"][6]=0;

$arbol["sistema"][7]="SPI";
$arbol["nivel"][7]=1;
$arbol["nombre_logico"][7]="Programación de Reportes";
$arbol["nombre_fisico"][7]="tepuy_spi_progrep.php";
$arbol["id"][7]="007";
$arbol["padre"][7]="001";
$arbol["numero_hijos"][7]=0;

$arbol["sistema"][8]="SPI";
$arbol["nivel"][8]=1;
$arbol["nombre_logico"][8]="Contabilizar Modificaciones Presupuestarias";
$arbol["nombre_fisico"][8]="";
$arbol["id"][8]="008";
$arbol["padre"][8]="001";
$arbol["numero_hijos"][8]=2;

$arbol["sistema"][9]="SPI";
$arbol["nivel"][9]=2;
$arbol["nombre_logico"][9]="Aprobación";
$arbol["nombre_fisico"][9]="tepuy_mis_p_contabiliza_mp_spi.php";
$arbol["id"][9]="009";
$arbol["padre"][9]="008";
$arbol["numero_hijos"][9]=0;

$arbol["sistema"][10]="SPI";
$arbol["nivel"][10]=2;
$arbol["nombre_logico"][10]="Reverso de Aprobación";
$arbol["nombre_fisico"][10]="tepuy_mis_p_reverso_mp_spi.php";
$arbol["id"][10]="010";
$arbol["padre"][10]="008";
$arbol["numero_hijos"][10]=0;

$arbol["sistema"][11]="SPI";
$arbol["nivel"][11]=0;
$arbol["nombre_logico"][11]="Reportes";
$arbol["nombre_fisico"][11]="";
$arbol["id"][11]="011";
$arbol["padre"][11]="000";
$arbol["numero_hijos"][11]=8;

$arbol["sistema"][12]="SPI";
$arbol["nivel"][12]=1;
$arbol["nombre_logico"][12]="Acumulado por Cuentas";
$arbol["nombre_fisico"][12]="tepuy_spi_r_acum_x_cuentas.php";
$arbol["id"][12]="012";
$arbol["padre"][12]="011";
$arbol["numero_hijos"][12]=0;

$arbol["sistema"][13]="SPI";
$arbol["nivel"][13]=1;
$arbol["nombre_logico"][13]="Mayor Analitico";
$arbol["nombre_fisico"][13]="tepuy_spi_r_mayor_analitico.php";
$arbol["id"][13]="013";
$arbol["padre"][13]="011";
$arbol["numero_hijos"][13]=0;

$arbol["sistema"][14]="SPI";
$arbol["nivel"][14]=1;
$arbol["nombre_logico"][14]="Listado de Apertura";
$arbol["nombre_fisico"][14]="tepuy_spi_r_listado_apertura.php";
$arbol["id"][14]="014";
$arbol["padre"][14]="011";
$arbol["numero_hijos"][14]=0;

$arbol["sistema"][15]="SPI";
$arbol["nivel"][15]=1;
$arbol["nombre_logico"][15]="Comprobante";
$arbol["nombre_fisico"][15]="tepuy_spi_r_comprobante_formato1.php";
$arbol["id"][15]="015";
$arbol["padre"][15]="011";
$arbol["numero_hijos"][15]=0;

$arbol["sistema"][16]="SPI";
$arbol["nivel"][16]=1;
$arbol["nombre_logico"][16]="Modificaciones Presupuestarias Aprobadas";
$arbol["nombre_fisico"][16]="tepuy_spi_r_modificaciones_presupuestarias_aprobadas.php";
$arbol["id"][16]="016";
$arbol["padre"][16]="011";
$arbol["numero_hijos"][16]=0;

$arbol["sistema"][17]="SPI";
$arbol["nivel"][17]=1;
$arbol["nombre_logico"][17]="Modificaciones Presupuestarias No Aprobadas";
$arbol["nombre_fisico"][17]="tepuy_spi_r_modificaciones_presupuestarias_no_aprobadas.php";
$arbol["id"][17]="017";
$arbol["padre"][17]="011";
$arbol["numero_hijos"][17]=0;

$arbol["sistema"][18]="SPI";
$arbol["nivel"][18]=1;
$arbol["nombre_logico"][18]="Listado de Cuenta";
$arbol["nombre_fisico"][18]="tepuy_spi_r_cuentas.php";
$arbol["id"][18]="018";
$arbol["padre"][18]="011";
$arbol["numero_hijos"][18]=0;

$arbol["sistema"][19]="SPI";
$arbol["nivel"][19]=1;
$arbol["nombre_logico"][19]="Instructivos";
$arbol["nombre_fisico"][19]="";
$arbol["id"][19]="019";
$arbol["padre"][19]="011";
$arbol["numero_hijos"][19]=3;

$arbol["sistema"][20]="SPI";
$arbol["nivel"][20]=2;
$arbol["nombre_logico"][20]="Ejecucion Trimestral de Presupuesto de Ingresos";
$arbol["nombre_fisico"][21]="tepuy_spi_r_ejecucion_trimestral.php";
$arbol["id"][20]="020";
$arbol["padre"][20]="019";
$arbol["numero_hijos"][20]=0;

$arbol["sistema"][21]="SPI";
$arbol["nivel"][21]=2;
$arbol["nombre_logico"][21]="Consolidado de Ejecucion Trimestral y Fuentes Financieras";
$arbol["nombre_fisico"][21]="tepuy_spi_r_instructivo_consolidado_ejecucion_trimestral.php";
$arbol["id"][21]="021";
$arbol["padre"][21]="019";
$arbol["numero_hijos"][21]=0;

$arbol["sistema"][22]="SPI";
$arbol["nivel"][22]=2;
$arbol["nombre_logico"][22]="Presupuesto de Caja";
$arbol["nombre_fisico"][22]="tepuy_spi_r_instructivo_presupuesto_caja.php";
$arbol["id"][22]="022";
$arbol["padre"][22]="019";
$arbol["numero_hijos"][22]=0;

$arbol["sistema"][23]="SPI";
$arbol["nivel"][23]=0;
$arbol["nombre_logico"][23]="Configuración";
$arbol["nombre_fisico"][23]="";
$arbol["id"][23]="023";
$arbol["padre"][23]="000";
$arbol["numero_hijos"][23]=1;

$arbol["sistema"][24]="SPI";
$arbol["nivel"][24]=1;
$arbol["nombre_logico"][24]="Plan de Cuentas";
$arbol["nombre_fisico"][24]="tepuy_spi_d_planctas.php";
$arbol["id"][24]="024";
$arbol["padre"][24]="023";
$arbol["numero_hijos"][24]=0;

?>
