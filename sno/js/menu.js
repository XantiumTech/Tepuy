//----------DHTML Menu Created using AllWebMenus PRO ver 5.3-#848---------------
//C:\wamp\www\Sub_Menus-Tepuy\sno\menu_sno.awm
var awmMenuName='menu';
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
var awmHash='RRCDHTKYGXTKFETYMSJMGGTAWUHE';
var awmNoMenuPrint=1;
var awmUseTrs=0;
var awmSepr=["0","","","","90","#808080","","1"];
var awmMarg=[0,0,0,0];
var YY= 82;//((window.innerHeight/2)-(window.innerHeight/4))-(window.innerHeight/42); //82 
var XX= ((window.innerWidth/2)-408); //110  396 para los menus cortos
//alert("Ancho: " + XX + "Alto: " + YY);
function awmBuildMenu(){
if (awmSupported){
awmImagesColl=["nomina.png",42,40,"definicion.png",31,22,"main-button-tile.gif",17,34,"indicator.gif",16,8,"main-item-left-float.png",17,24,"main-itemOver-left-float.png",15,24,"procesos.png",31,22,"integrar.png",31,22,"reportes.png",31,22,"ivss.png",31,22,"configuracion.png",31,22,"retorno.png",31,22];
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
it=s0.addItemWithImages(0,1,1,"Nómina",n,n,"",0,0,0,3,3,3,n,n,n,"",n,n,n,n,n,0,0,0,n,n,n,n,n,n,0,0,0,0,0,n,n,n,0,0,0,n,n);
it=s0.addItemWithImages(3,4,4,"Definiciones",n,n,"",1,n,n,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,n,0,0,0,0,0,8,n);
var s1=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,1,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s1.addItemWithImages(6,7,7,"Nóminas",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_d_nominas.php",n,n,n,"../tepuy_snorh_d_nominas.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,9,n);
it=s1.addItemWithImages(6,7,7,"Profesiones",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_d_profesion.php",n,n,n,"../tepuy_snorh_d_profesion.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,10,n);
it=s1.addItemWithImages(6,7,7,"Unidades Administrativos",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_d_uni_ad.php",n,n,n,"../tepuy_snorh_d_uni_ad.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,12,n);
it=s1.addItemWithImages(6,7,7,"Ubicación Física",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_d_ubicacionfisica.php",n,n,n,"../tepuy_snorh_d_ubicacionfisica.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,13,n);
it=s1.addItemWithImages(6,7,7,"Ficha del Personal",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_d_personal.php",n,n,n,"../tepuy_snorh_d_personal.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,15,n);
it=s1.addItemWithImages(6,7,7,"Feriados",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_d_diaferiado.php",n,n,n,"../tepuy_snorh_d_diaferiado.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,16,n);
it=s1.addItemWithImages(6,7,7,"Cesta Ticket",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_d_ct_met.php",n,n,n,"../tepuy_snorh_d_ct_met.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,18,n);
it=s1.addItemWithImages(6,7,7,"Tabla de Vacaciones",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_d_tablavacacion.php",n,n,n,"../tepuy_snorh_d_tablavacacion.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,19,n);
it=s1.addItemWithImages(6,7,7,"Método a Banco",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_d_metodobanco.php",n,n,n,"../tepuy_snorh_d_metodobanco.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,20,n);
it=s1.addItemWithImages(6,7,7,"Sueldo Mínimo",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_d_sueldominimo.php",n,n,n,"../tepuy_snorh_d_sueldominimo.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,21,n);
it=s1.addItemWithImages(6,7,7,"Constacia de Trabajo",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_d_constanciatrabajo.php",n,n,n,"../tepuy_snorh_d_constanciatrabajo.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,22,n);
it=s1.addItemWithImages(6,7,7,"Archivos TXT",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_d_archivostxt.php",n,n,n,"../tepuy_snorh_d_archivostxt.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,23,n);
it=s1.addItemWithImages(6,7,7,"Dedicación",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_d_dedicacion.php",n,n,n,"../tepuy_snorh_d_dedicacion.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,24,n);
it=s1.addItemWithImages(6,7,7,"Escala Docente",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_d_escaladocente.php",n,n,n,"../tepuy_snorh_d_escaladocente.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,25,n);
it=s1.addItemWithImages(6,7,7,"Clasificación de Obreros",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_d_clasificacionobreros.php",n,n,n,"../tepuy_snorh_d_clasificacionobreros.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,26,n);
it=s0.addItemWithImages(3,4,4,"Procesos",n,n,"",6,6,6,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,n,0,0,0,0,0,11,n);
var s1=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,17,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s1.addItemWithImages(6,7,7,"Seleccionar Nómina",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,"window.open('../tepuy_snorh_p_seleccionarnomina.php','catalogo','menubar=no,toolbar=no,scrollbars=yes,width=530,height=180,left=250,top=200,location=no,resizable=no');",n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,188,n);
it=s1.addItemWithImages(6,7,7,"Prestación de Antigüedad",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_p_fideicomiso.php",n,n,n,"../tepuy_snorh_p_fideicomiso.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,5,n);
it=s1.addItemWithImages(6,7,7,"Histórico x Nómina",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,"window.open('../tepuy_snorh_p_seleccionarhnomina.php','catalogo','menubar=no,toolbar=no,scrollbars=yes,width=530,height=180,left=250,top=200,location=no,resizable=no');",n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,6,n);
it=s1.addItemWithImages(6,7,7,"Cambiar Estatus de Personal",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_p_personalcambioestatus.php",n,n,n,"../tepuy_snorh_p_personalcambioestatus.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,27,n);
it=s1.addItemWithImages(6,7,7,"Programación de Reporte",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_p_programacionreporte.php",n,n,n,"../tepuy_snorh_p_programacionreporte.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,28,n);
it=s1.addItemWithImages(6,7,7,"Buscar Personal",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_p_buscarpersonal.php",n,n,n,"../tepuy_snorh_p_buscarpersonal.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,29,n);
it=s1.addItemWithImages(6,7,7,"Contabilizar Nómina",n,n,"",7,7,7,1,1,1,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,1,0,1,2,2,0,0,0,189,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,76,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s2.addItemWithImages(6,7,7,"Contabilizar",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_mis_p_contabiliza_sno.php",n,n,n,"../tepuy_mis_p_contabiliza_sno.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,191,n);
it=s2.addItemWithImages(6,7,7,"Reversar Contabilización",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_mis_p_reverso_sno.php",n,n,n,"../tepuy_mis_p_reverso_sno.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,192,n);
it=s1.addItemWithImages(6,7,7,"Registrar Beneficiario",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_rpc_d_beneficiario.php",n,n,n,"../tepuy_rpc_d_beneficiario.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,7,n);
it=s0.addItemWithImages(3,4,4,"Reportes",n,n,"",8,8,8,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,1,2,2,0,0,0,14,n);
var s1=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,18,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s1.addItemWithImages(6,7,7,"Personal",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,0,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,2,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s2.addItemWithImages(6,7,7,"Listado de Personal",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_r_listadopersonal.php",n,n,n,"../tepuy_snorh_r_listadopersonal.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,38,n);
it=s2.addItemWithImages(6,7,7,"Listado de Personal Contratado",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_r_listadopersonalcontratado.php",n,n,n,"../tepuy_snorh_r_listadopersonalcontratado.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,39,n);
it=s2.addItemWithImages(6,7,7,"Listado de Personal por Unidad Administrativa",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_r_unidadadministrativa.php",n,n,n,"../tepuy_snorh_r_unidadadministrativa.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,40,n);
it=s2.addItemWithImages(6,7,7,"Ficha de Personal",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_r_fichapersonal.php",n,n,n,"../tepuy_snorh_r_fichapersonal.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,41,n);
it=s2.addItemWithImages(6,7,7,"Listado de Personal al Seguro (HCM)",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_r_listadoseguro.php",n,n,n,"../tepuy_snorh_r_listadoseguro.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,42,n);
it=s2.addItemWithImages(6,7,7,"Antigüedad",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_r_antiguedadpersonal.php",n,n,n,"../tepuy_snorh_r_antiguedadpersonal.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,43,n);
it=s2.addItemWithImages(6,7,7,"Vacaciones",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_r_vacaciones.php",n,n,n,"../tepuy_snorh_r_vacaciones.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,44,n);
it=s2.addItemWithImages(6,7,7,"Constancia de Trabajo",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_r_constanciatrabajo.php",n,n,n,"../tepuy_snorh_r_constanciatrabajo.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,45,n);
it=s2.addItemWithImages(6,7,7,"Cumpleañeros",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_r_listadocumpleano.php",n,n,n,"../tepuy_snorh_r_listadocumpleano.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,46,n);
it=s2.addItemWithImages(6,7,7,"Familiares",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_r_familiar.php",n,n,n,"../tepuy_snorh_r_familiar.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,47,n);
it=s2.addItemWithImages(6,7,7,"Credenciales",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_r_credencialespersonal.php",n,n,n,"../tepuy_snorh_r_credencialespersonal.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,48,n);
it=s1.addItemWithImages(6,7,7,"Instructivos",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,1,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,3,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s2.addItemWithImages(6,7,7,"Recursos Humanos (0406)",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_r_comparado0406.php",n,n,n,"../tepuy_snorh_r_comparado0406.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,49,n);
it=s2.addItemWithImages(6,7,7,"Recursos Humanos (0506)",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_r_comparado0506.php",n,n,n,"../tepuy_snorh_r_comparado0506.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,50,n);
it=s2.addItemWithImages(6,7,7,"Recursos Humanos Clasificados por Tipo de Cargo",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_r_comparado0711.php",n,n,n,"../tepuy_snorh_r_comparado0711.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,51,n);
it=s2.addItemWithImages(6,7,7,"Personal Jubilado, Pensionado y Asignación a Sobreviviente",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_r_comparado0712.php",n,n,n,"../tepuy_snorh_r_comparado0712.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,52,n);
it=s1.addItemWithImages(6,7,7,"Sane",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,32,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,4,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s2.addItemWithImages(6,7,7,"Registro de Ingreso (14-02)",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_r_sane_ingreso.php",n,n,n,"../tepuy_snorh_r_sane_ingreso.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,53,n);
it=s2.addItemWithImages(6,7,7,"Registro de Retiro (14-03)",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_r_sane_retiro.php",n,n,n,"../tepuy_snorh_r_sane_retiro.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,54,n);
it=s2.addItemWithImages(6,7,7,"Registro de Cambio de Salario (14-10)",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_r_sane_salario.php",n,n,n,"../tepuy_snorh_r_sane_salario.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,55,n);
it=s2.addItemWithImages(6,7,7,"Registro de Reposos Médicos (14-10)",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_r_sane_reposos.php",n,n,n,"../tepuy_snorh_r_sane_reposos.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,56,n);
it=s2.addItemWithImages(6,7,7,"Registro de Permisos No Remunerados",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_r_sane_permisos.php",n,n,n,"../tepuy_snorh_r_sane_permisos.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,57,n);
it=s2.addItemWithImages(6,7,7,"Registro de Cambio de Centro Médico",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_r_sane_centromedico.php",n,n,n,"../tepuy_snorh_r_sane_centromedico.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,58,n);
it=s2.addItemWithImages(6,7,7,"Registro de Modificación de Datos",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_r_sane_modificacion.php",n,n,n,"../tepuy_snorh_r_sane_modificacion.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,59,n);
it=s1.addItemWithImages(6,7,7,"Retenciones",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,33,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,5,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s2.addItemWithImages(6,7,7,"Relación de Retención (AR-C)",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_r_retencion_arc.php",n,n,n,"../tepuy_snorh_r_retencion_arc.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,60,n);
it=s2.addItemWithImages(6,7,7,"Relación de Ingresos (AR-I)",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_r_retencion_ari.php",n,n,n,"../tepuy_snorh_r_retencion_ari.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,61,n);
it=s2.addItemWithImages(6,7,7,"Relación de Retención (I.S.L.R.)",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_r_retencion_islr.php",n,n,n,"../tepuy_snorh_r_retencion_islr.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,61,n);
it=s1.addItemWithImages(6,7,7,"Consolidados/Resumen",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,34,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,6,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s2.addItemWithImages(6,7,7,"Conceptos",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_r_conceptos.php",n,n,n,"../tepuy_snorh_r_conceptos.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,62,n);
it=s2.addItemWithImages(6,7,7,"Aportes Patronales",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_r_aportepatronal.php",n,n,n,"../tepuy_snorh_r_aportepatronal.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,63,n);
it=s2.addItemWithImages(6,7,7,"Listado al Banco",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_r_listadobanco.php",n,n,n,"../tepuy_snorh_r_listadobanco.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,64,n);
it=s2.addItemWithImages(6,7,7,"Recibo de Pago",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_r_recibopago.php",n,n,n,"../tepuy_snorh_r_recibopago.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,65,n);
it=s2.addItemWithImages(6,7,7,"Cesta Ticket",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_r_cestaticket.php",n,n,n,"../tepuy_snorh_r_cestaticket.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,66,n);
it=s2.addItemWithImages(6,7,7,"Depósitos al Banco",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_r_depositobanco.php",n,n,n,"../tepuy_snorh_r_depositobanco.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,67,n);
it=s2.addItemWithImages(6,7,7,"Pagos por Unidad Administrativa",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_r_pagosunidadadmin.php",n,n,n,"../tepuy_snorh_r_pagosunidadadmin.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,68,n);
it=s1.addItemWithImages(6,7,7,"Prestación de Antigüedad",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,35,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,7,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s2.addItemWithImages(6,7,7,"Listado de Prestación de Antigûedad",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_r_prestacionantiguedad.php",n,n,n,"../tepuy_snorh_r_prestacionantiguedad.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,69,n);
it=s2.addItemWithImages(6,7,7,"Cuadre Prestación de Antigûedad",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_r_cuadreprestacionantiguedad.php",n,n,n,"../tepuy_snorh_r_cuadreprestacionantiguedad.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,70,n);
it=s2.addItemWithImages(6,7,7,"Afectación Prestación de Antigûedad",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_r_afectacionprestacionantiguedad.php",n,n,n,"../tepuy_snorh_r_afectacionprestacionantiguedad.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,71,n);
it=s1.addItemWithImages(6,7,7,"I.V.S.S.",n,n,"",9,n,n,1,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,36,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,8,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s2.addItemWithImages(6,7,7,"Constancia de Trabajo para el I.V.S.S. (14-100)",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_r_constanciatrabajosegurosocial.php",n,n,n,"../tepuy_snorh_r_constanciatrabajosegurosocial.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,72,n);
it=s1.addItemWithImages(6,7,7,"Pensionados",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,37,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,9,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s2.addItemWithImages(6,7,7,"Beneficiario del Personal",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_r_personal_beneficiario.php",n,n,n,"../tepuy_snorh_r_personal_beneficiario.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,73,n);
it=s2.addItemWithImages(6,7,7,"Pagos Autorizados",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_r_personas_autorizadas.php",n,n,n,"../tepuy_snorh_r_personas_autorizadas.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,74,n);
it=s2.addItemWithImages(6,7,7,"Modos de Envío de Recibos de Pago",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_r_modos_enviosrec.php",n,n,n,"../tepuy_snorh_r_modos_enviosrec.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,75,n);
it=s0.addItemWithImages(3,4,4,"Configuración",n,n,"",10,10,10,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,1,2,2,0,0,0,17,n);
var s1=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,84,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s1.addItemWithImages(6,7,7,"Parámetros por Nómina",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_p_configuracion.php",n,n,n,"../tepuy_snorh_p_configuracion.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,2,n);
it=s1.addItemWithImages(6,7,7,"Fideicomiso",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_d_fideiconfigurable.php",n,n,n,"../tepuy_snorh_d_fideiconfigurable.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,3,n);
it=s1.addItemWithImages(6,7,7,"Cambio ID Personal",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_p_personalcambioid.php",n,n,n,"../tepuy_snorh_p_personalcambioid.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,30,n);
it=s1.addItemWithImages(6,7,7,"Transferencia de Datos entre RAC",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_snorh_p_transferenciadatos.php",n,n,n,"../tepuy_snorh_p_transferenciadatos.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,31,n);
it=s0.addItemWithImages(3,4,4,"Retornar",n,n,"",11,11,11,3,3,3,n,n,n,"",n,n,n,"../../tepuy_menu.php",n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,n,0,0,0,0,0,4,n);
s0.pm.buildMenu();
}}