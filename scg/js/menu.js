//----------DHTML Menu Created using AllWebMenus PRO ver 5.3-#848---------------
//C:\wamp\www\Sub_Menus-Tepuy-Concejo\scg\menu_contabilidad_general.awm
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
var awmHash='YFDUBOOBGMGKSEGYZSWMTGGAJUUR';
var awmNoMenuPrint=1;
var awmUseTrs=0;
var awmSepr=["0","","",""];
var awmMarg=[0,0,0,0];
var YY= 82;//((window.innerHeight/2)-(window.innerHeight/4))-(window.innerHeight/42); //82 
var XX= ((window.innerWidth/2)-382); //110  396 para los menus cortos
//alert("Ancho: " + XX + "Alto: " + YY);
function awmBuildMenu(){
if (awmSupported){
awmImagesColl=["contabilidad.png",25,23,"procesos.png",31,22,"main-button-tile.gif",17,34,"indicator.gif",16,8,"main-item-left-float.png",17,24,"main-itemOver-left-float.png",15,24,"reportes.png",31,22,"indicator2.gif",8,16,"configuracion.png",31,22,"retorno.png",31,22];
awmCreateCSS(1,2,1,'#FFFFFF',n,n,'14px sans-serif',n,'none','0','#000000','0px 0px 0px 0',0);
awmCreateCSS(0,2,1,'#FFFFFF',n,n,'14px sans-serif',n,'none','0','#000000','0px 0px 0px 0',0);
awmCreateCSS(0,1,0,n,'#1803FB',n,n,n,'solid','1','#A7AFBC',0,0); /* 1803FB Azul FF0000 Rojo*/
awmCreateCSS(1,2,1,'#4A4A4A',n,2,'12px Segoe UI, Tahoma, Verdana',n,'none','0','#000000','0px 20px 0px 20',1);
awmCreateCSS(0,2,1,'#4A4A4A','#E8EBF1',n,'bold 12px Segoe UI, Tahoma, Verdana',n,'none','0','#000000','0px 20px 0px 20',1);
awmCreateCSS(0,1,0,n,'#A7AFBC',n,n,n,'solid','1','#A7AFBC',0,0);
awmCreateCSS(1,2,0,'#4A4A4A','#E8EBF1',n,'12px Segoe UI, Tahoma, Verdana',n,'none','0','#000000','0px 20px 0px 20',1);
awmCreateCSS(0,2,0,'#1803FB','#F5F8FE',n,'bold 12px Segoe UI, Tahoma, Verdana',n,'none','0','#000000','0px 20px 0px 20',1); /* 1803FB Azul FF0000 Rojo*/
awmCreateCSS(0,2,0,'#4A4A4A','#F5F8FE',n,'bold 12px Segoe UI, Tahoma, Verdana',n,'none','0','#000000','0px 20px 0px 20',1);
awmCF(3,5,5,0,-1);
awmCF(4,1,0,0,0);
awmCF(5,1,0,0,0);
awmCF(7,4,4,-1,0);
//var s0=awmCreateMenu(0,0,0,1,9,0,0,0,0,115,82,0,1,2,1,0,1,n,n,100,0,0,115,82,0,-1,1,500,200,0,0,0,"0,0,0",n,n,n,n,n,n,n,n,0,0,0,0,0,0,0,0,1);
//   XX lo que se mueve a lo ancho y YY se desplaza arriba o abajo
var s0=awmCreateMenu(0,0,0,1,9,0,0,0,0,XX,YY,0,1,2,1,0,1,n,n,100,0,0,XX,YY,0,-1,1,500,200,0,0,0,"0,0,0",n,n,n,n,n,n,n,n,0,0,0,0,0,0,0,0,1);
it=s0.addItemWithImages(0,1,1,"Contabilidad Patrimonial",n,n,"",0,0,0,3,3,3,n,n,n,"",n,n,n,n,n,0,0,0,n,n,n,n,n,n,0,0,0,0,0,n,n,n,0,0,0,n,n);
it=s0.addItemWithImages(3,4,4,"Procesos",n,n,"",1,1,1,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,n,0,0,0,0,0,11,n);
var s1=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,17,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s1.addItemWithImages(6,7,7,"Comprobante Contable",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuywindow_scg_comprobante.php",n,n,n,"../tepuywindow_scg_comprobante.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,188,n);
it=s1.addItemWithImages(6,7,7,"Comprobante Cierre de Ejercicio",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuywindow_scg_cmp_cierre.php",n,n,n,"../tepuywindow_scg_cmp_cierre.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,5,n);
it=s1.addItemWithImages(6,7,7,"Programación de Reportes",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,189,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,76,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s2.addItemWithImages(6,7,7,"Mensual",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scg_wproc_progrep.php",n,n,n,"../tepuy_scg_wproc_progrep.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,191,n);
it=s2.addItemWithImages(6,7,7,"Trimestral",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scg_wproc_progrep_trim.php",n,n,n,"../tepuy_scg_wproc_progrep_trim.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,192,n);
it=s1.addItemWithImages(6,7,7,"Programación de Reportes OAF",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,6,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,1,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s2.addItemWithImages(6,7,7,"Mensual",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scg_wproc_prog_oaf.php",n,n,n,"../tepuy_scg_wproc_prog_oaf.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,7,n);
it=s2.addItemWithImages(6,7,7,"Trimestral",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scg_wproc_prog_oaf_trim.php",n,n,n,"../tepuy_scg_wproc_prog_oaf_trim.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,13,n);
it=s0.addItemWithImages(3,4,4,"Reportes",n,n,"",6,6,6,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,1,2,2,0,0,0,14,n);
var s1=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,18,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s1.addItemWithImages(6,8,8,"Mayor Analítico",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scg_r_mayor_analitico.php",n,n,n,"../tepuy_scg_r_mayor_analitico.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,n,3,3,0,0,0,0,n);
//it=s1.addItemWithImages(6,8,8,"Libro Diario Resumido",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scg_r_libro_diario.php",n,n,n,"../tepuy_scg_r_libro_diario.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,n,3,3,0,0,0,0,n);
it=s1.addItemWithImages(6,8,8,"Balance de Comprobación",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scg_r_balance_comprobacion.php",n,n,n,"../tepuy_scg_r_balance_comprobacion.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,n,3,3,0,0,0,1,n);
//it=s1.addItemWithImages(6,8,8,"Libro Diario ",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,n,3,3,0,0,0,8,n);
//var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,2,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
//it=s2.addItemWithImages(6,8,8,"Diario General",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scg_r_libro_diario_general.php",n,n,n,"../tepuy_scg_r_libro_diario_general.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,n,3,3,0,0,0,16,n);
it=s1.addItemWithImages(6,8,8,"Libro Diario",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scg_r_libro_diario.php",n,n,n,"../tepuy_scg_r_libro_diario.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,n,3,3,0,0,0,15,n);
it=s1.addItemWithImages(6,8,8,"Estado de Resultado",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scg_r_estado_resultado.php",n,n,n,"../tepuy_scg_r_estado_resultado.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,n,3,3,0,0,0,18,n);
it=s1.addItemWithImages(6,7,7,"Balance General",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,62,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,7,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s2.addItemWithImages(6,7,7,"Formato 1",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scg_r_balance_general.php",n,n,n,"../tepuy_scg_r_balance_general.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,63,n);
it=s2.addItemWithImages(6,7,7,"Balance General Forma 07",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scg_r_balance_general_07.php",n,n,n,"../tepuy_scg_r_balance_general_07.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,64,n);
it=s1.addItemWithImages(6,8,8,"Listado de Cuentas",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scg_r_cuentas.php",n,n,n,"../tepuy_scg_r_cuentas.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,n,3,3,0,0,0,12,n);
//it=s1.addItemWithImages(6,8,8,"Movimientos del Mes",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scg_r_movimientos_mes.php",n,n,n,"../tepuy_scg_r_movimientos_mes.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,n,3,3,0,0,0,10,n);
it=s1.addItemWithImages(6,8,8,"Libro Mayor",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scg_r_libro_mayor.php",n,n,n,"../tepuy_scg_r_libro_mayor.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,n,3,3,0,0,0,10,n);
it=s0.addItemWithImages(3,4,4,"Configuración",n,n,"",8,8,8,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,1,2,2,0,0,0,17,n);
var s1=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,84,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s1.addItemWithImages(6,7,7,"Maestro de Cuentas Fiscales",n,n,"",n,n,n,3,3,3,n,n,n,"../cfg/tepuy_scg_d_plan_unico.php",n,n,n,"../cfg/tepuy_scg_d_plan_unico.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,2,n);
it=s1.addItemWithImages(6,7,7,"Plan de Cuentas Contables (Interno)",n,n,"",n,n,n,3,3,3,n,n,n,"../cfg/tepuy_scg_d_plan_ctas.php",n,n,n,"../cfg/tepuy_scg_d_plan_ctas.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,3,n);
it=s1.addItemWithImages(6,8,8,"Origen y Aplicación de Fondos",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_scg_ctas_oaf.php",n,n,n,"../tepuy_scg_ctas_oaf.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,n,3,3,0,0,0,19,n);
it=s0.addItemWithImages(3,4,4,"Retornar",n,n,"",9,9,9,3,3,3,n,n,n,"",n,n,n,"../../tepuy_menu.php",n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,n,0,0,0,0,0,4,n);
s0.pm.buildMenu();
}}
