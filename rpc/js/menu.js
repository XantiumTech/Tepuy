// stm_aix("p1i2","p1i0",[0,"Opci�n 2    ","","",-1,-1,0,""]);
// stm_aix("p1i0","p0i0",[0,"Opci�n 1    ","","",-1,-1,0,"tablas.htm","_self","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);

//-----------------------//
// L�nea de separaci�n
// Para inlcuir l�neas de separaci�n entre las opciones, incoporar la siguiente instrucci�n, entre las opciones a separar
// stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);

//-----------------------//
// Men�es de Tercer Nivel
// Para hacer submen�es, incluir las siguientes l�neas de c�digo
// stm_bpx("pn","p1",[1,4,0,0,2,3,6,7]);   debajo de la l�nea de c�digo de la opci�n principal stm_aix("p0in","p0i0",[0," Opci�n Men� "]);
// luego, buscar la opci�n del men� bajo la cual se abrir� el submen� y agregar al final de esa l�nea de c�digo, los siguientes atributos:
// ,"","",-1,-1,0,"","_self","","","","",6,0,0,"imagebank/arrow.gif","imagebank/arrow.gif",7,7]);
// y justo debajo de esa l�nea agregar las siguientes l�neas de c�digo.
// stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edici�n - Opciones de Tercer Nivel
// stm_aix("p3i0","p1i0",[0,"  Menu Item 1  ","","",-1,-1,0,"","_self","","","","",0]);
// stm_aix("p3i1","p3i0",[0,"  Menu Item 2  "]);
// stm_aix("p3i2","p3i0",[0,"  Menu Item 3  "]);
// stm_aix("p3i3","p3i0",[0,"  Menu Item 4  "]);
// stm_aix("p3i4","p3i0",[0,"  Menu Item 5  "]);
// stm_ep();
// Luego cambiar las opciones "Menu Item 5", por el nombre de la opci�n que corresponda en cada caso.

//-----------------------//
// Hiperv�nculos
// Para incluir los enlaces correspondientes a cada opci�n del men�, se procede de la siguiente manera:
// En aquellas intrucciones, cuyo c�digo es similare a esto:
// stm_aix("p1i0","p0i0",[0,"Opci�n 1    ","","",-1,-1,0,"","_self","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
// agregar el enlace dentro de las comillas, justo delante de "_self"
// En aquellas intrucciones, cuyo c�digo es similare a esto:
// stm_aix("p3i1","p3i0",[0,"  Menu Item 2  "]);
// agregar al final de esta l�nea de c�digo, los siguientes par�metros:
// ,"","",-1,-1,0,"","_self","","","","",0]);
// y luego incorporar el enlace en las comillas que est� justo antes de "_self"

stm_bm(["menu08dd",430,"","../shared/imagebank/blank.gif",0,"","",0,0,0,0,1000,1,0,0,"","100%",0],this);
stm_bp("p0",[0,4,0,0,1,3,0,0,100,"",-2,"",-2,90,0,0,"#000000","#e6e6e6","",3,0,0,"#000000"]);

// Men� Principal- Archivo
stm_ai("p0i0",[0,"   Proveedores   ","","",-1,-1,0,"","_self","","","","",0,0,0,"","",0,0,0,0,1,"#F7F7F7",0,"#f4f4f4",0,"","",3,3,0,0,"#fffff7","#000000","#909090","#909090","8pt 'Tahoma','Arial'","8pt 'Tahoma','Arial'",0,0]);
stm_bp("p1",[1,4,0,0,2,3,6,7,100,"progid:DXImageTransform.Microsoft.Fade(overlap=.5,enabled=0,Duration=0.10)",-2,"",-2,100,2,3,"#999999","#ffffff","",3,1,1,"#F7F7F7"]);
// Archivo - Opciones de Segundo Nivel
stm_aix("p1i0","p0i0",[0,"Par�metro de Calificaci�n","","",-1,-1,0,"tepuy_rpc_d_clasificacion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i1","p0i0",[0,"Maestro de Recaudos      ","","",-1,-1,0,"tepuy_rpc_d_documento.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i2","p0i0",[0,"Especialidad         ","","",-1,-1,0,"tepuy_rpc_d_especialidad.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i3","p0i0",[0,"Tipo de Empresa         ","","",-1,-1,0,"tepuy_rpc_d_tipoempresa.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i4",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p1i5","p0i0",[0,"Ficha         ","","",-1,-1,0,"tepuy_rpc_d_proveedor.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();

// Men� Principal - Definiciones
stm_aix("p0i2","p0i0",[0,"   Beneficiario   "]);
stm_bpx("p4","p1",[1,4,0,0,2,3,6,7]);
stm_aix("p1i5","p0i0",[0,"   Ficha   ","","",-1,-1,0,"tepuy_rpc_d_beneficiario.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();

// Men� Principal - Transferencia
stm_aix("p0i4","p0i0",[0,"   Transferencia   "]);
stm_bpx("p6","p1",[1,4,0,0,2,3,6,7]);
stm_aix("p6i0","p0i0",[0,"Personal N�mina a Beneficiario","","",-1,-1,0,"tepuy_rpc_p_transferencia.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();

// Men� Principal - Reportes
stm_aix("p0i4","p0i0",[0,"   Reportes   "]);
stm_bpx("p6","p1",[1,4,0,0,2,3,6,7]);
stm_aix("p6i0","p0i0",[0,"  Fichas          ","","",-1,-1,0,"tepuy_rpc_r_fichas.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p6i0","p0i0",[0,"  Beneficiarios   ","","",-1,-1,0,"tepuy_rpc_r_beneficiario.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p6i0","p0i0",[0,"  Proveedores     ","","",-1,-1,0,"tepuy_rpc_r_provxespecia.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();

// Men� Principal - Ayuda
stm_aix("p0i8","p0i0",[0,"   Ayuda   "]);
stm_bpx("p10","p1",[]);
stm_ep();

stm_aix("p4i0","p1i0",[0," Ir a M�dulos  ","","",-1,-1,0,"../index_modules.php","","","","","",6,0,0,"","",0,0,0,0,1,"#F7F7F7"]);
stm_bpx("p10","p1",[]);
stm_ep();

stm_ep();
stm_em();
