//----------DHTML Menu Created using AllWebMenus PRO ver 5.3-#848---------------
//C:\wamp\www\Sub_Menus-Tepuy\gastos\menu_gastos.awm
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
var awmHash='TEWCEDIRYCBIBYLOAETUMKVAUQBA';
var awmNoMenuPrint=1;
var awmUseTrs=0;
var awmSepr=["0","","","","90","#808080","","2","90","#808080","","1"];
var awmMarg=[0,0,0,0];
var YY= 82;//((window.innerHeight/2)-(window.innerHeight/4))-(window.innerHeight/42); //82 
var XX= ((window.innerWidth/2)-386); //110  396 para los menus cortos
//alert("Ancho: " + XX + "Alto: " + YY);
function awmBuildMenu(){
if (awmSupported){
awmImagesColl=["gastos.png",31,22,"procesos.png",31,22,"main-button-tile.gif",17,34,"indicator.gif",16,8,"main-item-left-float.png",17,24,"main-itemOver-left-float.png",15,24,"reformular.png",31,22,"integrar.png",31,22,"reportes.png",31,22,"configuracion.png",31,22,"retorno.png",31,22];
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
//var s0=awmCreateMenu(0,0,0,1,9,0,0,0,0,115,82,0,1,2,1,0,1,n,n,100,0,0,115,82,0,-1,1,500,200,0,0,0,"0,0,0",n,n,n,n,n,n,n,n,0,0,0,0,0,0,0,0,1);
var s0=awmCreateMenu(0,0,0,1,9,0,0,0,0,XX,YY,0,1,2,1,0,1,n,n,100,0,0,XX,YY,0,-1,1,500,200,0,0,0,"0,0,0",n,n,n,n,n,n,n,n,0,0,0,0,0,0,0,0,1);
it=s0.addItemWithImages(0,1,1,"Presupuesto de Gastos",n,n,"",0,0,0,3,3,3,n,n,n,"",n,n,n,n,n,0,0,0,n,n,n,n,n,n,0,0,0,0,0,n,n,n,0,0,0,n,n);
it=s0.addItemWithImages(3,4,4,"Procesos",n,n,"",1,1,1,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,n,0,0,0,0,0,11,n);
var s1=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,17,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s1.addItemWithImages(6,7,7,"Formulación de Presupuesto",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_p_apertura.php",n,n,n,"../tepuy_spg_p_apertura.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,187,n);
it=s1.addItemWithImages(6,7,7,"Comprobante de ejecución financiera",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_p_comprobante.php",n,n,n,"../tepuy_spg_p_comprobante.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,188,n);
it=s1.addItemWithImages(6,7,7,"Modificaciones Presupuestarias",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,189,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,76,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s2.addItemWithImages(6,7,7,"Traslados",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_p_traspaso.php",n,n,n,"../tepuy_spg_p_traspaso.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,0,n);
it=s2.addItemWithImages(6,7,7,"Crédito Adicional",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_p_adicional.php",n,n,n,"../tepuy_spg_p_adicional.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,1,n);
it=s2.addItemWithImages(6,7,7,"Rectificaciones",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_p_rectificaciones.php",n,n,n,"../tepuy_spg_p_rectificaciones.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,191,n);
it=s2.addItemWithImages(6,7,7,"Insubsistencias",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_p_insubsistencias.php",n,n,n,"../tepuy_spg_p_insubsistencias.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,192,n);
it=s1.addItemWithImages(6,7,7,"Programación de Reportes",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_p_progrep.php",n,n,n,"../tepuy_spg_p_progrep.php",n,0,34,2,n,n,n,n,n,n,0,0,0,1,0,1,2,2,0,0,0,193,n);
it=s1.addItemWithImages(6,7,7,"Contabilizar Modificaciones Presupuestarias",n,n,"",7,7,7,1,1,1,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,100,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,11,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s2.addItemWithImages(6,7,7,"Aprobación",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_mis_p_contabiliza_mp.php",n,n,n,"../tepuy_mis_p_contabiliza_mp.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,101,n);
it=s2.addItemWithImages(6,7,7,"Reversar Aprobación",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_mis_p_reversa_mp.php",n,n,n,"../tepuy_mis_p_reversa_mp.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,102,n);
it=s0.addItemWithImages(3,4,4,"Reportes",n,n,"",8,8,8,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,1,2,2,0,0,0,14,n);
var s1=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,18,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s1.addItemWithImages(6,7,7,"Estándar",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,15,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,1,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s2.addItemWithImages(6,7,7,"Acumulado por Cuentas",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_acum_x_cuentas.php",n,n,n,"../tepuy_spg_r_acum_x_cuentas.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,8,n);
it=s2.addItemWithImages(6,7,7,"Mayor Analítico",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_mayor_analitico.php",n,n,n,"../tepuy_spg_r_mayor_analitico.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,16,n);
it=s2.addItemWithImages(6,7,7,"Listado de Apertura",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_listado_apertura.php",n,n,n,"../tepuy_spg_r_listado_apertura.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,194,n);
it=s2.addItemWithImages(6,7,7,"Modificaciones Presupuestarias Aprobadas",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_modificaciones_presupuestarias_aprobadas.php",n,n,n,"../tepuy_spg_r_modificaciones_presupuestarias_aprobadas.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,196,n);
it=s2.addItemWithImages(6,7,7,"Modificaciones Presupuestarias NO Aprobadas",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_modificaciones_presupuestarias.php",n,n,n,"../tepuy_spg_r_modificaciones_presupuestarias.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,197,n);
it=s2.addItemWithImages(6,7,7,"Combrobantes",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,9,n);
var s3=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,2,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s3.addItemWithImages(6,7,7,"Formato 1",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_comprobante_formato1.php",n,n,n,"../tepuy_spg_r_comprobante_formato1.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,195,n);
it=s3.addItemWithImages(6,7,7,"Formato 2",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_comprobante_formato2.php",n,n,n,"../tepuy_spg_r_comprobante_formato2.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,10,n);
it=s2.addItemWithImages(6,7,7,"Disponibilidad Presupuestaria",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,12,n);
var s3=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,3,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s3.addItemWithImages(6,7,7,"Formato 1",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_disponibilidad.php",n,n,n,"../tepuy_spg_r_disponibilidad.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,18,n);
it=s3.addItemWithImages(6,7,7,"Formato 2",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_disponibilidad_formato2.php",n,n,n,"../tepuy_spg_r_disponibilidad_formato2.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,19,n);
it=s2.addItemWithImages(6,7,7,"Listado de Cuentas Presupuestarias",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_cuentas.php",n,n,n,"../tepuy_spg_r_cuentas.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,198,n);
it=s2.addItemWithImages(6,7,7,"Ejecución Física y Financiera",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_comparados_ejecucion_financiera_formato4.php",n,n,n,"../tepuy_spg_r_comparados_ejecucion_financiera_formato4.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,20,n);
it=s1.addItemWithImages(6,7,7,"Otros Listados",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,21,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,4,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s2.addItemWithImages(6,7,7,"Distribución Mensual del Presupuesto",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_distribucion_mensual_presupuesto.php",n,n,n,"../tepuy_spg_r_distribucion_mensual_presupuesto.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,22,n);
it=s2.addItemWithImages(6,7,7,"Unidades Ejecutoras",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_unidades_ejecutoras.php",n,n,n,"../tepuy_spg_r_unidades_ejecutoras.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,23,n);
it=s2.addItemWithImages(6,7,7,"Ejecución de Compromisos",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_ejecucion_compromisos.php",n,n,n,"../tepuy_spg_r_ejecucion_compromisos.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,24,n);
it=s2.addItemWithImages(6,7,7,"Compromisos no Causados",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_compromisos_no_causados.php",n,n,n,"../tepuy_spg_r_compromisos_no_causados.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,25,n);
it=s2.addItemWithImages(6,7,7,"Compromisos Causados no Pagados",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_compromisos_causados_no_pagados.php",n,n,n,"../tepuy_spg_r_compromisos_causados_no_pagados.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,26,n);
it=s2.addItemWithImages(6,7,7,"Operaciones por Específica",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_operacion_por_especifica.php",n,n,n,"../tepuy_spg_r_operacion_por_especifica.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,27,n);
it=s2.addItemWithImages(6,7,7,"Ejecutado por Partidas",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_ejecutado_por_partida.php",n,n,n,"../tepuy_spg_r_ejecutado_por_partida.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,28,n);
it=s2.addItemWithImages(6,7,7,"Resumen Proveedor/Beneficiario",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_resumen_prov_bene.php",n,n,n,"../tepuy_spg_r_resumen_prov_bene.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,29,n);
it=s2.addItemWithImages(6,7,7,"Operaciones por Banco",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_operacion_por_banco.php",n,n,n,"../tepuy_spg_r_operacion_por_banco.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,30,n);
it=s1.addItemWithImages(6,7,7,"Instructivos",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,2,0,1,2,2,0,0,0,199,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,77,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s2.addItemWithImages(6,7,7,"Ejecución Financiera del Presupuesto (Sector y Programas)",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_instructivo_06_ejec_fin_pry_acc.php",n,n,n,"../tepuy_spg_r_instructivo_06_ejec_fin_pry_acc.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,31,n);
it=s2.addItemWithImages(6,7,7,"Ejecución Financiera del Presupuesto a Nivel de Actividades",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_instructivo_06_ejec_fin_acc_esp.php",n,n,n,"../tepuy_spg_r_instructivo_06_ejec_fin_acc_esp.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,32,n);
it=s2.addItemWithImages(6,7,7,"Información Mensual de la Ejecución Financiera",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_instructivo_06_inf_men_eje_fin.php",n,n,n,"../tepuy_spg_r_instructivo_06_inf_men_eje_fin.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,33,n);
it=s2.addItemWithImages(6,7,7,"Ejecución Trimestral de Gastos",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_ejecucion_trimestral.php",n,n,n,"../tepuy_spg_r_ejecucion_trimestral.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,34,n);
it=s2.addItemWithImages(6,7,7,"Consolidado de Ejecución Trismestral de Gastos",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_instructivo_consolidado_ejecucion_trimestral.php",n,n,n,"../tepuy_spg_r_instructivo_consolidado_ejecucion_trimestral.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,35,n);
it=s2.addItemWithImages(6,7,7,"Estado de Resultado",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_instructivo_estado_resultado.php",n,n,n,"../tepuy_spg_r_instructivo_estado_resultado.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,36,n);
it=s2.addItemWithImages(6,7,7,"Resumen del Presupuesto de Gastos por Partida (0704)",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_comparados_forma0704.php",n,n,n,"../tepuy_spg_r_comparados_forma0704.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,37,n);
it=s2.addItemWithImages(6,7,7,"Resumen del Presupuesto (0705)",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_comparados_forma0705.php",n,n,n,"../tepuy_spg_r_comparados_forma0705.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,38,n);
it=s2.addItemWithImages(6,7,7,"Ejecución Financiera del Presupuesto de Gastos (0707)",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_comparados_ejecucion_financiera_formato3.php",n,n,n,"../tepuy_spg_r_comparados_ejecucion_financiera_formato3.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,39,n);
it=s2.addItemWithImages(6,7,7,"Ejecución Financiera Mensual del Presupuesto de Gastos (0402)",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_comparados_forma0402.php",n,n,n,"../tepuy_spg_r_comparados_forma0402.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,40,n);
it=s2.addItemWithImages(6,7,7,"Ejecución Financiera de los Proyectos del Ente (0413)",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_comparados_forma0413.php",n,n,n,"../tepuy_spg_r_comparados_forma0413.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,41,n);
it=s2.addItemWithImages(6,7,7,"Ejecución Financiera Por Programas (0414)",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_comparados_forma0414.php",n,n,n,"../tepuy_spg_r_comparados_forma0414.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,42,n);
it=s2.addItemWithImages(6,7,7,"Ejecución Financiera a Nivel de Actividades (0415)",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_comparados_forma0415.php",n,n,n,"../tepuy_spg_r_comparados_forma0415.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,43,n);
it=s2.addItemWithImages(6,7,7,"Ejecución Trimestral de Gastos por Programatica",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_instructivo_ejecucion_trimestral_x_programatica.php",n,n,n,"../tepuy_spg_r_instructivo_ejecucion_trimestral_x_programatica.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,44,n);
it=s1.addItemWithImages(6,7,7,"Ordenanza",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,45,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,5,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);

it=s2.addItemWithImages(6,7,7,"Identificación de la Institución",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,"window.open('../reportes/tepuy_spg_rpp_distribucion_institucion.php','catalogo','menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=250,top=200,location=no,resizable=no');",n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,46,n);

it=s2.addItemWithImages(6,7,7,"Índice de Cátegorias Programáticas",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_distribucion_categorias.php",n,n,n,"../tepuy_spg_r_distribucion_categorias.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,47,n);
it=s2.addItemWithImages(6,7,7,"Presupuesto de Ingresos",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_distribucion_ingresos.php",n,n,n,"../tepuy_spg_r_distribucion_ingresos.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,48,n);
it=s2.addItemWithImages(6,7,7,"Presupuesto a Nivel de Sector, Programa y Actividad",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_distribucion_asignacion.php",n,n,n,"../tepuy_spg_r_distribucion_asignacion.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,49,n);
it=s2.addItemWithImages(6,7,7,"Resumen de Créditos Presupuestarios a Nivel de Sectores y Programas",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_distribucion_sector.php",n,n,n,"../tepuy_spg_r_distribucion_sector.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,50,n);
it=s2.addItemWithImages(6,7,7,"Resumen de Créditos Presupuestarios a Nivel de Partida",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_distribucion_partida.php",n,n,n,"../tepuy_spg_r_distribucion_partida.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,51,n);
it=s2.addItemWithImages(6,7,7,"Resumen de Créditos Presupuestarios a Nivel de Sectores, Programas y Partidas",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_distribucion_sector_programa_partida.php",n,n,n,"../tepuy_spg_r_distribucion_sector_programa_partida.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,52,n);
it=s2.addItemWithImages(6,7,7,"Resumen de Créditos Presupuestarios a Nivel de Sectores y Partidas",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_distribucion_sector_partida.php",n,n,n,"../tepuy_spg_r_distribucion_sector_partida.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,53,n);
it=s2.addItemWithImages(6,7,7,"Resumen Gastos de Inversión",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_distribucion_inversion.php",n,n,n,"../tepuy_spg_r_distribucion_inversion.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,54,n);
it=s2.addItemWithImages(6,7,7,"Relación Global de Proyectos",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_distribucion_proyectos.php",n,n,n,"../tepuy_spg_r_distribucion_proyectos.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,55,n);
it=s1.addItemWithImages(6,7,7,"Distribución",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,56,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,6,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s2.addItemWithImages(6,7,7,"Presupuesto de Ingresos",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_distribucion_ingresos.php",n,n,n,"../tepuy_spg_r_distribucion_ingresos.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,57,n);
it=s2.addItemWithImages(6,7,7,"Presupuesto de Gastos",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_distribucion_asignacion.php",n,n,n,"../tepuy_spg_r_distribucion_asignacion.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,58,n);
it=s2.addItemWithImages(6,7,7,"Créditos Presupuestarios a Nivel de Partidas",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_distribucion_partida.php",n,n,n,"../tepuy_spg_r_distribucion_partida.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,59,n);
it=s2.addItemWithImages(6,7,7,"Relación de Transferencias",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_distribucion_transferencia.php",n,n,n,"../tepuy_spg_r_distribucion_transferencia.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,60,n);
it=s2.addItemWithImages(6,7,7,"Relaciónde de Proyectos de Inversión a ser Financiados",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_distribucion_inversion.php",n,n,n,"../tepuy_spg_r_distribucion_inversion.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,61,n);
it=s1.addItemWithImages(6,7,7,"Hojas Separadores",n,n,"",n,n,n,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,62,n);
var s2=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,7,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s2.addItemWithImages(6,7,7,"Portadas",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_separador_portada.php",n,n,n,"../tepuy_spg_r_separador_portada.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,63,n);
it=s2.addItemWithImages(6,7,7,"Sectores",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_separador_sector.php",n,n,n,"../tepuy_spg_r_separador_sector.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,64,n);
it=s2.addItemWithImages(6,7,7,"Programas",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_separador_programas.php",n,n,n,"../tepuy_spg_r_separador_programas.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,65,n);
it=s2.addItemWithImages(6,7,7,"Contenido",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_spg_r_separador_contenido.php",n,n,n,"../tepuy_spg_r_separador_contenido.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,66,n);
it=s0.addItemWithImages(3,4,4,"Configuración",n,n,"",9,9,9,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,1,2,2,0,0,0,17,n);
var s1=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,84,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s1.addItemWithImages(6,7,7,"Cátalogo de Recursos y Egresos",n,n,"",n,n,n,3,3,3,n,n,n,"../cfg/tepuy_scg_d_plan_unicore.php",n,n,n,"../cfg/tepuy_scg_d_plan_unicore.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,2,n);
it=s1.addItemWithImages(6,7,7,"Categorías Programáticas",n,n,"",n,n,n,3,3,3,n,n,n,"../cfg/tepuy_spg_d_estprog1.php",n,n,n,"../cfg/tepuy_spg_d_estprog1.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,3,n);
it=s1.addItemWithImages(6,7,7,"Fuentes de Financiamiento",n,n,"",n,n,n,3,3,3,n,n,n,"../cfg/tepuy_spg_d_fuentfinan.php",n,n,n,"../cfg/tepuy_spg_d_fuentfinan.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,5,n);
it=s1.addItemWithImages(6,7,7,"Plan de Cuentas",n,n,"",n,n,n,3,3,3,n,n,n,"../cfg/tepuy_spg_d_planctas.php",n,n,n,"../cfg/tepuy_spg_d_planctas.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,228,n);
it=s1.addItemWithImages(6,7,7,"Unidades Administrativas",n,n,"",n,n,n,3,3,3,n,n,n,"../cfg/tepuy_spg_d_uniadm.php",n,n,n,"../cfg/tepuy_spg_d_uniadm.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,6,n);
it=s1.addItemWithImages(6,7,7,"Unidades Ejecutoras",n,n,"",n,n,n,3,3,3,n,n,n,"../cfg/tepuy_spg_d_unidad.php",n,n,n,"../cfg/tepuy_spg_d_unidad.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,7,n);
it=s1.addItemWithImages(6,7,7,"Firmas",n,n,"",n,n,n,3,3,3,n,n,n,"../cfg/tepuy_cfg_d_empresa.php",n,n,n,"../cfg/tepuy_cfg_d_empresa.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,7,n);
it=s0.addItemWithImages(3,4,4,"Retornar",n,n,"",10,10,10,3,3,3,n,n,n,"",n,n,n,"../../tepuy_menu.php",n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,n,0,0,0,0,0,4,n);
s0.pm.buildMenu();
}}
