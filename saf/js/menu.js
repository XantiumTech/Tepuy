//----------DHTML Menu Created using AllWebMenus PRO ver 5.3-#848---------------
//C:\wamp\www\Sub_Menus-Tepuy\saf\menu_saf.awm
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
var awmHash='DSYYGIJFRZXWDOPGGYBQWIHAISRC';
var awmNoMenuPrint=1;
var awmUseTrs=0;
var awmSepr=["0","","","","90","#808080","","1"];
var awmMarg=[0,0,0,0];
var YY= 82;//((window.innerHeight/2)-(window.innerHeight/4))-(window.innerHeight/42); //82 
var XX= ((window.innerWidth/2)-340); //110  396 para los menus cortos
//alert("Ancho: " + XX + "Alto: " + YY);
function awmBuildMenu(){
if (awmSupported){
awmImagesColl=["activos.png",42,40,"definicion.png",31,22,"main-button-tile.gif",17,34,"indicator.gif",16,8,"main-item-left-float.png",17,24,"main-itemOver-left-float.png",15,24,"procesos.png",31,22,"integrar.png",31,22,"reportes.png",31,22,"retorno.png",31,22];
awmCreateCSS(1,2,1,'#FFFFFF',n,n,'14px sans-serif',n,'none','0','#000000','0px 0px 0px 0',0);
awmCreateCSS(0,2,1,'#FFFFFF',n,n,'14px sans-serif',n,'none','0','#000000','0px 0px 0px 0',0);
awmCreateCSS(0,1,0,n,'#1803FB',n,n,n,'solid','1','#A7AFBC',0,0); /* 1803FB Azul FF0000 Rojo*/
awmCreateCSS(1,2,1,'#4A4A4A',n,2,'12px Segoe UI, Tahoma, Verdana',n,'none','0','#000000','0px 20px 0px 20',1);
awmCreateCSS(0,2,1,'#4A4A4A','#E8EBF1',n,'bold 12px Segoe UI, Tahoma, Verdana',n,'none','0','#000000','0px 20px 0px 20',1);
awmCreateCSS(0,1,0,n,'#A7AFBC',n,n,n,'solid','1','#A7AFBC',0,0);
awmCreateCSS(1,2,0,'#4A4A4A','#E8EBF1',n,'12px Segoe UI, Tahoma, Verdana',n,'none','0','#000000','0px 20px 0px 20',1);
awmCreateCSS(0,2,0,'#1803FB0','#F5F8FE',n,'bold 12px Segoe UI, Tahoma, Verdana',n,'none','0','#000000','0px 20px 0px 20',1); /* 1803FB Azul FF0000 Rojo*/
awmCF(3,5,5,0,-1);
awmCF(4,1,0,0,0);
awmCF(5,1,0,0,0);
//var s0=awmCreateMenu(0,0,0,1,9,0,0,0,0,300,82,0,1,2,1,0,1,n,n,100,0,0,300,82,0,-1,1,500,200,0,0,0,"0,0,0",n,n,n,n,n,n,n,n,0,0,0,0,0,0,0,0,1);
//   XX lo que se mueve a lo ancho y YY se desplaza arriba o abajo
var s0=awmCreateMenu(0,0,0,1,9,0,0,0,0,XX,YY,0,1,2,1,0,1,n,n,100,0,0,XX,YY,0,-1,1,500,200,0,0,0,"0,0,0",n,n,n,n,n,n,n,n,0,0,0,0,0,0,0,0,1);
it=s0.addItemWithImages(0,1,1,"Activos",n,n,"",0,0,0,3,3,3,n,n,n,"",n,n,n,n,n,0,0,0,n,n,n,n,n,n,0,0,0,0,0,n,n,n,0,0,0,n,n);
it=s0.addItemWithImages(3,4,4,"Definiciones",n,n,"",1,1,1,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,n,0,0,0,0,0,8,n);
var s1=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,1,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s1.addItemWithImages(6,7,7,"Método de Rotulación",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_saf_d_rotulacion.php",n,n,n,"../tepuy_saf_d_rotulacion.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,9,n);
it=s1.addItemWithImages(6,7,7,"Condición de Activo",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_saf_d_condicion.php",n,n,n,"../tepuy_saf_d_condicion.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,10,n);
it=s1.addItemWithImages(6,7,7,"Causas de Movimiento",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_saf_d_movimientos.php",n,n,n,"../tepuy_saf_d_movimientos.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,12,n);
it=s1.addItemWithImages(6,7,7,"Grupos",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_saf_d_grupo.php",n,n,n,"../tepuy_saf_d_grupo.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,12,n);
//it=s1.addItemWithImages(6,7,7,"Sub-Grupos",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_saf_d_subgrupo.php",n,n,n,"../tepuy_saf_d_subgrupo.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,12,n);
//it=s1.addItemWithImages(6,7,7,"Secciones",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_saf_d_secciones.php",n,n,n,"../tepuy_saf_d_secciones.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,12,n);
it=s1.addItemWithImages(6,7,7,"Activos",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_saf_d_activossigecof.php",n,n,n,"../tepuy_saf_d_activossigecof.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,13,n);
it=s1.addItemWithImages(6,7,7,"Catálogo SIGECOF",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_saf_d_catalogo.php",n,n,n,"../tepuy_saf_d_catalogo.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,2,n);
it=s1.addItemWithImages(6,7,7,"Categoría CGR",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_saf_d_grupo.php",n,n,n,"../tepuy_saf_d_grupo.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,3,n);
it=s1.addItemWithImages(6,7,7,"Configuración de Activos",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_saf_d_configuracion.php",n,n,n,"../tepuy_saf_d_configuracion.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,16,n);
it=s1.addItemWithImages(6,7,7,"Estructura Predominante de los Inmuebles",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_saf_d_materiales.php",n,n,n,"../tepuy_saf_d_materiales.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,18,n);
it=s0.addItemWithImages(3,4,4,"Procesos",n,n,"",6,6,6,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,n,0,0,0,0,0,11,n);
var s1=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,17,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s1.addItemWithImages(6,7,7,"Movimientos",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,188,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,2,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s2.addItemWithImages(6,7,7,"Incorporaciones",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_saf_p_incorporaciones.php",n,n,n,"../tepuy_saf_p_incorporaciones.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,26,n);
it=s2.addItemWithImages(6,7,7,"Desincorporaciones",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_saf_p_desincorporaciones.php",n,n,n,"../tepuy_saf_p_desincorporaciones.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,30,n);
it=s2.addItemWithImages(6,7,7,"Reasignaciones",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_saf_p_reasignaciones.php",n,n,n,"../tepuy_saf_p_reasignaciones.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,31,n);
it=s2.addItemWithImages(6,7,7,"Modificaciones",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_saf_p_modificaciones.php",n,n,n,"../tepuy_saf_p_modificaciones.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,38,n);
it=s2.addItemWithImages(6,7,7,"Incorporaciones por Lote",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_saf_p_incorporacioneslote.php",n,n,n,"../tepuy_saf_p_incorporacioneslote.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,39,n);
it=s2.addItemWithImages(6,7,7,"Incorporaciones General",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_saf_p_incorporacioneslotegeneral.php",n,n,n,"../tepuy_saf_p_incorporacioneslotegeneral.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,40,n);
it=s1.addItemWithImages(6,7,7,"Cambio de Responsable",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_saf_p_cambioresponsable.php",n,n,n,"../tepuy_saf_p_cambioresponsable.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,5,n);
it=s1.addItemWithImages(6,7,7,"Entrega de la Unidad",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_saf_p_entregaunidad.php",n,n,n,"../tepuy_saf_p_entregaunidad.php",n,0,34,2,n,n,n,n,n,n,0,0,0,1,0,1,2,2,0,0,0,6,n);
it=s1.addItemWithImages(6,7,7,"Depreciación de Activos",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_saf_p_depreciacion.php",n,n,n,"../tepuy_saf_p_depreciacion.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,27,n);
it=s1.addItemWithImages(6,7,7,"Acta de Prestamo",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_saf_p_actaprestamo.php",n,n,n,"../tepuy_saf_p_actaprestamo.php",n,0,34,2,n,n,n,n,n,n,0,0,0,1,0,1,2,2,0,0,0,28,n);
it=s1.addItemWithImages(6,7,7,"Autorización de Salida",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_saf_p_autorizacionsalida.php",n,n,n,"../tepuy_saf_p_autorizacionsalida.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,29,n);
it=s1.addItemWithImages(6,7,7,"Contabilizar Depreciación de Activo",n,n,"",7,7,7,1,1,1,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,1,0,1,2,2,0,0,0,189,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,76,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s2.addItemWithImages(6,7,7,"Contabilizar",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_mis_p_contabiliza_depreciacion_saf.php",n,n,n,"../tepuy_mis_p_contabiliza_depreciacion_saf.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,191,n);
it=s2.addItemWithImages(6,7,7,"Reversar Contabilización",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_mis_p_reverso_depreciacion_saf.php",n,n,n,"../tepuy_mis_p_reverso_depreciacion_saf.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,192,n);
it=s0.addItemWithImages(3,4,4,"Reportes",n,n,"",8,8,8,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,1,2,2,0,0,0,14,n);
var s1=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,18,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s1.addItemWithImages(6,7,7,"SIGECOF",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,0,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,3,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s2.addItemWithImages(6,7,7,"Activos",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_saf_r_activo.php",n,n,n,"../tepuy_saf_r_activo.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,32,n);
it=s2.addItemWithImages(6,7,7,"Incorporación",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_saf_r_incorporacion.php",n,n,n,"../tepuy_saf_r_incorporacion.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,43,n);
it=s2.addItemWithImages(6,7,7,"DesIncorporación",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_saf_r_desincorporacion.php",n,n,n,"../tepuy_saf_r_desincorporacion.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,44,n);
it=s2.addItemWithImages(6,7,7,"Reasignación",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_saf_r_reasignacion.php",n,n,n,"../tepuy_saf_r_reasignacion.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,45,n);
it=s2.addItemWithImages(6,7,7,"Modificación",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_saf_r_modificacion.php",n,n,n,"../tepuy_saf_r_modificacion.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,46,n);
it=s2.addItemWithImages(6,7,7,"Depreciación",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_saf_r_depreciacion.php",n,n,n,"../tepuy_saf_r_depreciacion.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,47,n);
it=s2.addItemWithImages(6,7,7,"Depreciación Mensual",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_saf_r_depmensual.php",n,n,n,"../tepuy_saf_r_depmensual.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,48,n);
it=s2.addItemWithImages(6,7,7,"Listado Cátalogo SIGECOF",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_saf_r_sigecof.php",n,n,n,"../tepuy_saf_r_sigecof.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,49,n);
it=s2.addItemWithImages(6,7,7,"Comprobante de Incorporación",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_saf_r_compincorporacion.php",n,n,n,"../tepuy_saf_r_compincorporacion.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,50,n);
it=s2.addItemWithImages(6,7,7,"Comprobante de DesIncorporación",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_saf_r_compdesincorporacion.php",n,n,n,"../tepuy_saf_r_compdesincorporacion.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,51,n);
it=s2.addItemWithImages(6,7,7,"Comprobante de Reasignación",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_saf_r_compreasignacion.php",n,n,n,"../tepuy_saf_r_compreasignacion.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,52,n);
it=s1.addItemWithImages(6,7,7,"CGR",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,1,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,4,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s2.addItemWithImages(6,7,7,"Inventario de Bienes Muebles BM-1",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_saf_r_activo_bien.php",n,n,n,"../tepuy_saf_r_activo_bien.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,42,n);
it=s2.addItemWithImages(6,7,7,"Relación de Movimientos de Bienes Muebles BM-2",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_saf_r_relmovbm2.php",n,n,n,"../tepuy_saf_r_relmovbm2.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,53,n);
it=s2.addItemWithImages(6,7,7,"Relación de Bienes Muebles Faltantes BM-3",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_saf_r_relbmf3.php",n,n,n,"../tepuy_saf_r_relbmf3.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,54,n);
it=s2.addItemWithImages(6,7,7,"Resumen de Cuenta de Bienes Muebles BM-4",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_saf_r_resctabm4.php",n,n,n,"../tepuy_saf_r_resctabm4.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,55,n);
it=s2.addItemWithImages(6,7,7,"Inventario General de Muebles",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_saf_r_invgenbie.php",n,n,n,"../tepuy_saf_r_invgenbie.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,56,n);
it=s2.addItemWithImages(6,7,7,"Resumen de Bienes Muebles por Grupo",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_saf_r_resbiegru.php",n,n,n,"../tepuy_saf_r_resbiegru.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,57,n);
it=s2.addItemWithImages(6,7,7,"Incorporaciones y DesIncorporaciones por Departamento","Incorporaciones - DesIncorporaciones por Departamento","Incorporaciones - DesIncorporaciones por Departamento","",n,n,n,3,3,3,n,n,n,"../tepuy_saf_r_incdesinc.php",n,n,n,"../tepuy_saf_r_incdesinc.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,58,n);
it=s2.addItemWithImages(6,7,7,"Bienes por Cuenta Contable",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_saf_r_bienmuectacont.php",n,n,n,"../tepuy_saf_r_bienmuectacont.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,59,n);
it=s2.addItemWithImages(6,7,7,"Rendición Mensual de Cuenta",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_saf_r_rendmen.php",n,n,n,"../tepuy_saf_r_rendmen.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,60,n);
it=s2.addItemWithImages(6,7,7,"Tipos de Adquisición de Bienes",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_saf_r_tipos_bien.php",n,n,n,"../tepuy_saf_r_tipos_bien.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,61,n);
it=s2.addItemWithImages(6,7,7,"Adquisición de Bienes General",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_saf_r_bien_general.php",n,n,n,"../tepuy_saf_r_bien_general.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,62,n);
it=s2.addItemWithImages(6,7,7,"Acta de Incorporación",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_saf_r_actaincorporacion.php",n,n,n,"../tepuy_saf_r_actaincorporacion.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,63,n);
it=s2.addItemWithImages(6,7,7,"Acta de DesIncorporación",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_saf_r_actadesincorporacion.php",n,n,n,"../tepuy_saf_r_actadesincorporacion.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,64,n);
it=s2.addItemWithImages(6,7,7,"Acta de Reasignación",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_saf_r_actareasignacion.php",n,n,n,"../tepuy_saf_r_actareasignacion.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,65,n);
it=s0.addItemWithImages(3,4,4,"Retornar",n,n,"",9,9,9,3,3,3,n,n,n,"",n,n,n,"../../tepuy_menu.php",n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,n,0,0,0,0,0,4,n);
s0.pm.buildMenu();
}}
