// stm_aix("p1i2","p1i0",[0,"Opción 2    ","","",-1,-1,0,""]);
// stm_aix("p1i0","p0i0",[0,"Opción 1    ","","",-1,-1,0,"tablas.htm","_self","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);

//-----------------------//
// Línea de separación
// Para inlcuir líneas de separación entre las opciones, incoporar la siguiente instrucción, entre las opciones a separar
// stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);

//-----------------------//
// Menúes de Tercer Nivel
// Para hacer submenúes, incluir las siguientes líneas de código
// stm_bpx("pn","p1",[1,4,0,0,2,3,6,7]);   debajo de la línea de código de la opción principal stm_aix("p0in","p0i0",[0," Opción Menú "]);
// luego, buscar la opción del menú bajo la cual se abrirá el submenú y agregar al final de esa línea de código, los siguientes atributos:
// ,"","",-1,-1,0,"","_self","","","","",6,0,0,"imagebank/arrow.gif","imagebank/arrow.gif",7,7]);
// y justo debajo de esa línea agregar las siguientes líneas de código.
// stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edición - Opciones de Tercer Nivel
// stm_aix("p3i0","p1i0",[0,"  Menu Item 1  ","","",-1,-1,0,"","_self","","","","",0]);
// stm_aix("p3i1","p3i0",[0,"  Menu Item 2  "]);
// stm_aix("p3i2","p3i0",[0,"  Menu Item 3  "]);
// stm_aix("p3i3","p3i0",[0,"  Menu Item 4  "]);
// stm_aix("p3i4","p3i0",[0,"  Menu Item 5  "]);
// stm_ep();
// Luego cambiar las opciones "Menu Item 5", por el nombre de la opción que corresponda en cada caso.

//-----------------------//
// Hipervínculos
// Para incluir los enlaces correspondientes a cada opción del menú, se procede de la siguiente manera:
// En aquellas intrucciones, cuyo código es similare a esto:
// stm_aix("p1i0","p0i0",[0,"Opción 1    ","","",-1,-1,0,"","_self","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
// agregar el enlace dentro de las comillas, justo delante de "_self"
// En aquellas intrucciones, cuyo código es similare a esto:
// stm_aix("p3i1","p3i0",[0,"  Menu Item 2  "]);
// agregar al final de esta línea de código, los siguientes parámetros:
// ,"","",-1,-1,0,"","_self","","","","",0]);
// y luego incorporar el enlace en las comillas que está justo antes de "_self"

stm_bm(["menu08dd",430,"","../shared/imagebank/blank.gif",0,"","",0,0,0,0,1000,1,0,0,"","100%",0],this);
stm_bp("p0",[0,4,0,0,1,3,0,0,100,"",-2,"",-2,90,0,0,"#000000","#e6e6e6","",3,0,0,"#000000"]);

// Menú Principal- Archivo
stm_ai("p0i0",[0," Definiciones ","","",-1,-1,0,"","_self","","","","",0,0,0,"","",0,0,0,0,1,"#F7F7F7",0,"#f4f4f4",0,"","",3,3,0,0,"#fffff7","#000000","#909090","#909090","8pt 'Tahoma','Arial'","8pt 'Tahoma','Arial'",0,0]);
stm_bp("p1",[1,4,0,0,2,3,6,1,100,"progid:DXImageTransform.Microsoft.Fade(overlap=.5,enabled=0,Duration=0.10)",-2,"",-2,100,2,3,"#999999","#ffffff","",3,1,1,"#F7F7F7"]);
// Archivo - Opciones de Segundo Nivel
/*stm_aix("p1i0","p0i0",[0,"Nuevo    ","","",-1,-1,0,"javascript:ue_nuevo()","","","","../shared/imagebank/tools20/nuevo.gif","../shared/imagebank/tools20/nuevo-off.gif",20,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i2","p1i0",[0,"Guardar    ","","",-1,-1,0,"javascript:ue_guardar()","","","","../shared/imagebank/tools20/grabar.gif","../shared/imagebank/tools20/grabar-off.gif",20,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i4","p1i0",[0,"Eliminar   ","","",-1,-1,0,"javascript:ue_eliminar()","","","","../shared/imagebank/tools20/eliminar.gif","../shared/imagebank/tools20/eliminar-off.gif",20,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i4","p1i0",[0,"Buscar   ","","",-1,-1,0,"javascript:ue_buscar()","","","","../shared/imagebank/tools20/buscar.gif","../shared/imagebank/tools20/buscar-off.gif",20,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i4","p1i0",[0,"Cerrar   ","","",-1,-1,0,"tepuywindow_blank.php","","","","../shared/imagebank/tools20/salir.gif","../shared/imagebank/tools20/salir-off.gif",20,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i4","p1i0",[0,"Salir   ","","",-1,-1,0,"javascript:close();","","","","../shared/imagebank/tools20/salir.png","../shared/imagebank/tools20/salir.png",20,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();*/


// Menú Principal - Definiciones
//stm_aix("p0i2","p0i0",[0," Definiciones "]);
//stm_bpx("p4","p1",[1,4,0,0,2,3,6,7]);
// Definiciones - Opciones de Segundo Nivel
stm_aix("p1i0","p0i0",[0,"Categorías de Partidas     ","","",-1,-1,0,"tepuy_sob_d_categoriapartida.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0,"Organismos Ejecutores ","","",-1,-1,0,"tepuy_sob_d_organismo.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p4i0","p1i0",[0,"Tipo de Unidades  ","","",-1,-1,0,"tepuy_sob_d_tipounidad.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0,"Unidades  ","","",-1,-1,0,"tepuy_sob_d_unidad.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0,"Partidas     ","","",-1,-1,0,"tepuy_sob_d_partida.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p4i0","p1i0",[0,"Tipos de Contratos  ","","",-1,-1,0,"tepuy_sob_d_tipocontrato.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p4i0","p1i0",[0,"Sistemas Constructivos   ","","",-1,-1,0,"tepuy_sob_d_sistemaconstructivo.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0,"Tenencia   ","","",-1,-1,0,"tepuy_sob_d_tenencia.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0,"Tipos de Estructuras ","","",-1,-1,0,"tepuy_sob_d_tipoestructura.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0,"Tipos de Obras ","","",-1,-1,0,"tepuy_sob_d_tipoobra.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p4i0","p1i0",[0,"Documentos ","","",-1,-1,0,"tepuy_sob_d_documentos.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();

// Menú Principal - Procesos
stm_aix("p0i3","p0i0",[0," Procesos "]);
stm_bpx("p5","p1",[1,4,0,0,2,3,6,7]);
// Procesos - Opciones de Segundo Nivel
//stm_aix("p1i0","p0i0",[0," Contratadas ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
//stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edición - Opciones de Tercer Nivel
stm_aix("p3i0","p1i0",[0," Obras    ","","",-1,-1,0,"tepuy_sob_d_obra.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
//stm_aix("p3i0","p1i0",[0," Puntos de Cuenta","","",-1,-1,0,"tepuy_sob_d_puntodecuenta.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p3i0","p1i0",[0," Carta Asignación / Pto de Cuenta ","","",-1,-1,0,"tepuy_sob_d_asignacion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p3i0","p1i0",[0," Contratos ","","",-1,-1,0,"tepuy_sob_d_contrato.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p3i0","p1i0",[0," Anticipos  ","","",-1,-1,0,"tepuy_sob_d_anticipo.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p3i0","p1i0",[0," Valuaciones ","","",-1,-1,0,"tepuy_sob_d_valuacion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p3i0","p1i0",[0," Variaciones del Contrato ","","",-1,-1,0,"tepuy_sob_d_variacion.php",,"","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p3i0",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p3i0","p0i0",[0," Actas       ","","",-1,-1,0,"tepuy_sob_d_acta.php","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_ai("p3i0",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p3i0","p0i0",[0," Reverso de Recepciones de Documentos","","",-1,-1,0,"tepuy_sob_p_revanticipo_rd.php","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_ep();
stm_ep();
//stm_ai("p1i1",[6,0,"#e6e6e6","",0,0,0]);
//stm_aix("p4i0","p1i0",[1," Administración Directa ","","",-1,-1,0,"tepuy_scb_p_desprogpago.php","_self"]);
//stm_ai("p1i1",[6,0,"#e6e6e6","",0,0,0]);
//stm_aix("p3i0","p1i0",[0,"Contabilización de Documentos    ","","",-1,-1,0,"tepuy_sob_d_contabilizacion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
//stm_aix("p3i0","p1i0",[0,"Recepción de Documentos    ","","",-1,-1,0,"tepuy_sob_d_recepcion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
//stm_aix("p4i0","p1i0",[0," Contabilización de Documentos ","","",-1,-1,0,"tepuy_scb_p_desprogpago.php","_self"]);
/*stm_bpx("p4","p1",[1,2,0,0,2,3,0]);
stm_aix("p4i0","p1i0",[0,"Compromiso de Documentos    ","","",-1,-1,0,"tepuy_sob_d_obra.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);*/
stm_ep();
stm_ep();


// Menú Principal - Procesos
stm_aix("p0i3","p0i0",[0," Reportes "]);
stm_bpx("p5","p1",[1,4,0,0,2,3,6,7]);
stm_aix("p4i0","p1i0",[0," Obras  ","","",-1,-1,0,"tepuy_sob_r_reporteobra.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Partidas por Obras  ","","",-1,-1,0,"tepuy_sob_r_reportepartidasobra.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Asignaciones por Obras  ","","",-1,-1,0,"tepuy_sob_r_reporteasignacionesobra.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Seguimiento de Obras  ","","",-1,-1,0,"tepuy_sob_r_reporteseguimientoobra.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Documentos  ","","",-1,-1,0,"tepuy_sob_r_documentos.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
/*stm_aix("p4i0","p1i0",[0," Openoffice  HelloWorld  ","","",-1,-1,0,"tbsooo/Ejemplo.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
/*stm_aix("p4i0","p1i0",[0," Openoffice  Invoice  ","","",-1,-1,0,"example_invoice.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Openoffice  One row per page  ","","",-1,-1,0,"one_row_per_page.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Openoffice  One row per page2  ","","",-1,-1,0,"one_row_per_page2.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Openoffice  blocks  ","","",-1,-1,0,"tbsooo_us_examples_blocks.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Openoffice  Cond  ","","",-1,-1,0,"tbsooo_us_examples_cond.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Openoffice  dataarray  ","","",-1,-1,0,"tbsooo_us_examples_dataarray.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Openoffice  event ","","",-1,-1,0,"tbsooo_us_examples_event.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Openoffice  Hello ","","",-1,-1,0,"tbsooo_us_examples_hello.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Openoffice  loops ","","",-1,-1,0,"tbsooo_us_examples_loops.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Openoffice  prmfrm ","","",-1,-1,0,"tbsooo_us_examples_prmfrm.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Openoffice  prmheader ","","",-1,-1,0,"tbsooo_us_examples_prmheader.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Openoffice  prmmagnet ","","",-1,-1,0,"tbsooo_us_examples_prmmagnet.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Openoffice  prmmax ","","",-1,-1,0,"tbsooo_us_examples_prmmax.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Openoffice  prmserial ","","",-1,-1,0,"tbsooo_us_examples_prmserial.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
/*stm_aix("p4i0","p1i0",[0," Openoffice  subblock ","","",-1,-1,0,"tbsooo_us_examples_subblock.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Openoffice  system ","","",-1,-1,0,"tbsooo_us_examples_system.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Openoffice  var ","","",-1,-1,0,"tbsooo_us_examples_var.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Openoffice  datanum ","","",-1,-1,0,"tbsooo_us_examples_datanum.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
/*stm_aix("p4i0","p1i0",[0," Configuracion Acta PDF    ","","",-1,-1,0,"tepuy_sob_param_acta.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Acta PDF    ","","",-1,-1,0,"class_folder/tepuy_sob_c_pdf_acta.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
*/
stm_ep();

stm_aix("p4i0","p1i0",[0,"Ir a Módulos  ","","",-1,-1,0,"../index_modules.php","","","","","",6,0,0,"","",0,0,0,0,1,"#F7F7F7"]);
stm_bpx("p10","p1",[]);
stm_ep();

stm_em();
