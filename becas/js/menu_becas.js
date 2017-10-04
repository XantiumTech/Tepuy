//----------DHTML Menu Created using AllWebMenus PRO ver 5.3-#848---------------
//C:\wamp\www\Sub_Menus-Tepuy\becas\menu_nomina.awm
var awmMenuName='menu_becas';
var awmLibraryBuild=848;
var awmLibraryPath='/';
var awmImagesPath='/';
var awmSupported=(navigator.appName + navigator.appVersion.substring(0,1)=="Netscape5" || document.all || document.layers || navigator.userAgent.indexOf('Opera')>-1 || navigator.userAgent.indexOf('Konqueror')>-1)?1:0;
if (awmAltUrl!='' && !awmSupported) window.location.replace(awmAltUrl);
if (awmSupported){
var nua=navigator.userAgent,scriptNo=(nua.indexOf('Chrome')>-1)?2:((nua.indexOf('Safari')>-1)?7:(nua.indexOf('Gecko')>-1)?2:((nua.indexOf('Opera')>-1)?4:1));
var mpi=document.location,xt="";
var mpa=mpi.protocol+"//"+mpi.host;
var mpi=mpi.protocol+"//"+mpi.host+mpi.pathname;
if(scriptNo==1){oBC=document.all.tags("BASE");if(oBC && oBC.length) if(oBC[0].href) mpi=oBC[0].href;}
while (mpi.search(/\\/)>-1) mpi=mpi.replace("\\","/");
mpi=mpi.substring(0,mpi.lastIndexOf("/")+1);
var e=document.getElementsByTagName("SCRIPT");
for (var i=0;i<e.length;i++){if (e[i].src){if (e[i].src.indexOf(awmMenuName+".js")!=-1){xt=e[i].src.split("/");if (xt[xt.length-1]==awmMenuName+".js"){xt=e[i].src.substring(0,e[i].src.length-awmMenuName.length-3);if (e[i].src.indexOf("://")!=-1){mpi=xt;}else{if(xt.substring(0,1)=="/")mpi=mpa+xt; else mpi+=xt;}}}}}
while (mpi.search(/\/\.\//)>-1) {mpi=mpi.replace("/./","/");}
var awmMenuPath=mpi.substring(0,mpi.length-1);
while (awmMenuPath.search("'")>-1) {awmMenuPath=awmMenuPath.replace("'","%27");}
document.write("<SCRIPT SRC='"+awmMenuPath+awmLibraryPath+"/awmlib"+scriptNo+".js'><\/SCRIPT>");
var n=null;
awmzindex=1000;
}

var awmImageName='';
var awmPosID='';
var awmSubmenusFrame='';
var awmSubmenusFrameOffset;
var awmOptimize=0;
var awmHash='UNVWYUGVKCUAYASARAUAXAQAZAQX';
var awmNoMenuPrint=1;
var awmUseTrs=0;
var awmSepr=["0","","","","90","#808080","","1"];
var awmMarg=[0,0,0,0];
var YY= 82;//((window.innerHeight/2)-(window.innerHeight/4))-(window.innerHeight/42); //82 
var XX= ((window.innerWidth/2)-408); //110  396 para los menus cortos
//alert("Ancho: " + XX + "Alto: " + YY);
function awmBuildMenu(){
if (awmSupported){
awmImagesColl=["becas.png",42,40,"definicion.png",31,22,"main-button-tile.gif",17,34,"indicator.gif",16,8,"main-item-left-float.png",17,24,"main-itemOver-left-float.png",15,24,"procesos.png",31,22,"reportes.png",31,22,"configuracion.png",31,22,"retorno.png",31,22];
awmCreateCSS(1,2,1,'#FFFFFF',n,n,'14px sans-serif',n,'none','0','#000000','0px 0px 0px 0',0);
awmCreateCSS(0,2,1,'#FFFFFF',n,n,'14px sans-serif',n,'none','0','#000000','0px 0px 0px 0',0);
awmCreateCSS(0,1,0,n,'#1803FB',n,n,n,'solid','1','#A7AFBC',0,0); /* 1803FB Azul FF0000 Rojo*/
awmCreateCSS(1,2,1,'#4A4A4A',n,2,'12px Segoe UI, Tahoma, Verdana',n,'none','0','#000000','0px 20px 0px 20',1);
awmCreateCSS(0,2,1,'#4A4A4A','#E8EBF1',n,'bold 12px Segoe UI, Tahoma, Verdana',n,'none','0','#000000','0px 20px 0px 20',1);
awmCreateCSS(0,1,0,n,'#A7AFBC',n,n,n,'solid','1','#A7AFBC',0,0);
awmCreateCSS(1,2,0,'#4A4A4A','#E8EBF1',n,'12px Segoe UI, Tahoma, Verdana',n,'none','0','#000000','0px 20px 0px 20',1);
awmCreateCSS(0,2,0,'#1803FB','#F5F8FE',n,'bold 12px Segoe UI, Tahoma, Verdana',n,'none','0','#000000','0px 20px 0px 20',1); /* 1803FB Azul FF0000 Rojo*/
awmCF(3,5,5,0,-1);
awmCF(4,1,0,0,0);
awmCF(5,1,0,0,0);
//var s0=awmCreateMenu(0,0,0,1,9,0,0,0,0,90,82,0,1,2,1,0,1,n,n,100,0,0,90,82,0,-1,1,500,200,0,0,0,"0,0,0",n,n,n,n,n,n,n,n,0,0,0,0,0,0,0,0,1);
//   XX lo que se mueve a lo ancho y YY se desplaza arriba o abajo
var s0=awmCreateMenu(0,0,0,1,9,0,0,0,0,XX,YY,0,1,2,1,0,1,n,n,100,0,0,XX,YY,0,-1,1,500,200,0,0,0,"0,0,0",n,n,n,n,n,n,n,n,0,0,0,0,0,0,0,0,1);
it=s0.addItemWithImages(0,1,1,"Becas",n,n,"",0,0,0,3,3,3,n,n,n,"",n,n,n,n,n,0,0,0,n,n,n,n,n,n,0,0,0,0,0,n,n,n,0,0,0,n,n);
it=s0.addItemWithImages(3,4,4,"Definiciones",n,n,"",1,n,n,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,n,0,0,0,0,0,8,n);
var s1=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,1,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s1.addItemWithImages(6,7,7,"Cargos",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sno_d_cargo.php",n,n,n,"../tepuy_sno_d_cargo.php",n,0,34,2,n,n,n,n,n,n,0,0,0,1,0,1,2,2,0,0,0,9,n);
it=s1.addItemWithImages(6,7,7,"Tabulador",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sno_d_tabulador.php",n,n,n,"../tepuy_sno_d_tabulador.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,10,n);
it=s1.addItemWithImages(6,7,7,"Asignación de Cargos",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sno_d_asignacioncargo.php",n,n,n,"../tepuy_sno_d_asignacioncargo.php",n,0,34,2,n,n,n,n,n,n,0,0,0,1,0,1,2,2,0,0,0,12,n);
it=s1.addItemWithImages(6,7,7,"Asignación de Personal -&gt; Cargo",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sno_d_personalnomina.php",n,n,n,"../tepuy_sno_d_personalnomina.php",n,0,34,2,n,n,n,n,n,n,0,0,0,1,0,1,2,2,0,0,0,13,n);
it=s1.addItemWithImages(6,7,7,"Constantes",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sno_d_constantes.php",n,n,n,"../tepuy_sno_d_constantes.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,15,n);
it=s1.addItemWithImages(6,7,7,"Conceptos",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sno_d_concepto.php",n,n,n,"../tepuy_sno_d_concepto.php",n,0,34,2,n,n,n,n,n,n,0,0,0,1,0,1,2,2,0,0,0,16,n);
it=s1.addItemWithImages(6,7,7,"Constante por Persona",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sno_d_persxconst.php",n,n,n,"../tepuy_sno_d_persxconst.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,18,n);
it=s1.addItemWithImages(6,7,7,"Concepto por Persona",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sno_d_persxconce.php",n,n,n,"../tepuy_sno_d_persxconce.php",n,0,34,2,n,n,n,n,n,n,0,0,0,1,0,1,2,2,0,0,0,19,n);
it=s1.addItemWithImages(6,7,7,"Tipo de Prestamo",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sno_d_tipoprestamo.php",n,n,n,"../tepuy_sno_d_tipoprestamo.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,20,n);
it=s1.addItemWithImages(6,7,7,"Concepto de Vacación",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sno_d_vacacionconcepto.php",n,n,n,"../tepuy_sno_d_vacacionconcepto.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,21,n);
it=s1.addItemWithImages(6,7,7,"Concepto de Prima",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sno_d_primaconcepto.php",n,n,n,"../tepuy_sno_d_primaconcepto.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,22,n);
it=s0.addItemWithImages(3,4,4,"Procesos",n,n,"",6,6,6,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,n,0,0,0,0,0,11,n);
var s1=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,17,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s1.addItemWithImages(6,7,7,"Nómina",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,188,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,10,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s2.addItemWithImages(6,7,7,"Prenómina",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sno_p_calcularprenomina.php",n,n,n,"../tepuy_sno_p_calcularprenomina.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,23,n);
it=s2.addItemWithImages(6,7,7,"Cálculo",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sno_p_calcularnomina.php",n,n,n,"../tepuy_sno_p_calcularnomina.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,24,n);
it=s2.addItemWithImages(6,7,7,"Reverso",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sno_p_reversarnomina.php",n,n,n,"../tepuy_sno_p_reversarnomina.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,25,n);
it=s1.addItemWithImages(6,7,7,"Manejo de Períodos",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sno_p_manejoperiodo.php",n,n,n,"../tepuy_sno_p_manejoperiodo.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,5,n);
it=s1.addItemWithImages(6,7,7,"Vacaciones",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,6,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,11,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s2.addItemWithImages(6,7,7,"Generar vacaciones Vencidas",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sno_p_vacacionvencida.php",n,n,n,"../tepuy_sno_p_vacacionvencida.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,26,n);
it=s2.addItemWithImages(6,7,7,"Programar Vacaciones",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sno_p_vacacionprogramar.php",n,n,n,"../tepuy_sno_p_vacacionprogramar.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,76,n);
it=s1.addItemWithImages(6,7,7,"Préstamos",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sno_p_prestamo.php",n,n,n,"../tepuy_sno_p_prestamo.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,28,n);
it=s1.addItemWithImages(6,7,7,"Cambiar Estatus de Personal",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sno_p_personalcambioestatus.php",n,n,n,"../tepuy_sno_p_personalcambioestatus.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,27,n);
it=s1.addItemWithImages(6,7,7,"Aplicar Conceptos Lote",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sno_p_aplicarconcepto.php",n,n,n,"../tepuy_sno_p_aplicarconcepto.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,29,n);
it=s1.addItemWithImages(6,7,7,"Ajustes",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,1,0,1,2,2,0,0,0,189,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,76,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s2.addItemWithImages(6,7,7,"Sueldos",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sno_p_ajustarsueldo.php",n,n,n,"../tepuy_sno_p_ajustarsueldo.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,191,n);
it=s2.addItemWithImages(6,7,7,"Aportes",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sno_p_ajustaraporte.php",n,n,n,"../tepuy_sno_p_ajustaraporte.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,192,n);
it=s1.addItemWithImages(6,7,7,"Importar/Exportar Datos",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sno_p_impexpdato.php",n,n,n,"../tepuy_sno_p_impexpdato.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,7,n);
it=s1.addItemWithImages(6,7,7,"Importar Definiciones",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sno_p_importardefiniciones.php",n,n,n,"../tepuy_sno_p_importardefiniciones.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,77,n);
it=s1.addItemWithImages(6,7,7,"Movimiento entre Nóminas",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sno_p_movimientonominas.php",n,n,n,"../tepuy_sno_p_movimientonominas.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,78,n);
it=s0.addItemWithImages(3,4,4,"Reportes",n,n,"",7,7,7,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,1,2,2,0,0,0,14,n);
var s1=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,18,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s1.addItemWithImages(6,7,7,"Nómina",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,0,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,2,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s2.addItemWithImages(6,7,7,"Prenomina",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sno_r_prenomina.php",n,n,n,"../tepuy_sno_r_prenomina.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,38,n);
it=s2.addItemWithImages(6,7,7,"Nómina de Pago",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sno_r_pagonomina.php",n,n,n,"../tepuy_sno_r_pagonomina.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,39,n);
it=s2.addItemWithImages(6,7,7,"Nómina de pago por Unidad Administrativa",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sno_r_pagonominaunidadadmin.php",n,n,n,"../tepuy_sno_r_pagonominaunidadadmin.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,40,n);
it=s2.addItemWithImages(6,7,7,"Recibo de Pago",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sno_r_recibopago.php",n,n,n,"../tepuy_sno_r_recibopago.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,41,n);
it=s1.addItemWithImages(6,7,7,"Listados",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,1,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,3,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s2.addItemWithImages(6,7,7,"Listado de Conceptos",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sno_r_listadoconcepto.php",n,n,n,"../tepuy_sno_r_listadoconcepto.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,49,n);
it=s2.addItemWithImages(6,7,7,"Listado de Personal Cobro por Cheque",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sno_r_listadopersonalcheque.php",n,n,n,"../tepuy_sno_r_listadopersonalcheque.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,50,n);
it=s2.addItemWithImages(6,7,7,"Listado al Banco",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sno_r_listadobanco.php",n,n,n,"../tepuy_sno_r_listadobanco.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,51,n);
it=s2.addItemWithImages(6,7,7,"Listado de Firmas",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sno_r_listadofirmas.php",n,n,n,"../tepuy_sno_r_listadofirmas.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,52,n);
it=s2.addItemWithImages(6,7,7,"Listado de Beneficiarios",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sno_r_listadobeneficiario.php",n,n,n,"../tepuy_sno_r_listadobeneficiario.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,79,n);
it=s1.addItemWithImages(6,7,7,"Aporte Patronal",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sno_r_aportepatronal.php",n,n,n,"../tepuy_sno_r_aportepatronal.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,32,n);
it=s1.addItemWithImages(6,7,7,"Resumenes",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,34,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,6,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s2.addItemWithImages(6,7,7,"Concepto",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sno_r_resumenconcepto.php",n,n,n,"../tepuy_sno_r_resumenconcepto.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,62,n);
it=s2.addItemWithImages(6,7,7,"Concepto por Unidad",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sno_r_resumenconceptounidad.php",n,n,n,"../tepuy_sno_r_resumenconceptounidad.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,63,n);
it=s2.addItemWithImages(6,7,7,"Cuadre de Nómina",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sno_r_cuadrenomina.php",n,n,n,"../tepuy_sno_r_cuadrenomina.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,64,n);
it=s2.addItemWithImages(6,7,7,"Cuadre de Conceptos y Aportes",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,"window.open('../reportes/tepuy_sno_rpp_cuadreconceptoaporte.php','catalogo','menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes');",n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,65,n);
it=s2.addItemWithImages(6,7,7,"Contable de Conceptos",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sno_r_contableconceptos.php",n,n,n,"../tepuy_sno_r_contableconceptos.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,66,n);
it=s2.addItemWithImages(6,7,7,"Contable de Aportes",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sno_r_contableaportes.php",n,n,n,"../tepuy_sno_r_contableaportes.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,67,n);
it=s1.addItemWithImages(6,7,7,"Vacaciones",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,35,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,7,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s2.addItemWithImages(6,7,7,"Relación de Vacaciones",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sno_r_relacionvacaciones.php",n,n,n,"../tepuy_sno_r_relacionvacaciones.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,69,n);
it=s2.addItemWithImages(6,7,7,"Programación de Vacaciones",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sno_r_programacionvacaciones.php",n,n,n,"../tepuy_sno_r_programacionvacaciones.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,70,n);
it=s1.addItemWithImages(6,7,7,"Prestamos",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,37,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,9,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s2.addItemWithImages(6,7,7,"Listado de Prestamos",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sno_r_listadoprestamo.php",n,n,n,"../tepuy_sno_r_listadoprestamo.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,73,n);
it=s2.addItemWithImages(6,7,7,"Detalle de Prestamos",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sno_r_detalleprestamo.php",n,n,n,"../tepuy_sno_r_detalleprestamo.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,74,n);
it=s0.addItemWithImages(3,4,4,"Configuración",n,n,"",8,8,8,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,1,2,2,0,0,0,17,n);
var s1=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,84,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s1.addItemWithImages(6,7,7,"Parámetros de Reportes Nómina",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,2,n);
it=s0.addItemWithImages(3,4,4,"Retornar",n,n,"",9,9,9,3,3,3,n,n,n,"",n,n,n,"../../tepuy_menu.php",n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,n,0,0,0,0,0,4,n);
s0.pm.buildMenu();
}}
