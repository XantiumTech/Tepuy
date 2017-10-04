//----------DHTML Menu Created using AllWebMenus PRO ver 5.3-#848---------------
//C:\wamp\www\Sub_Menus-Tepuy-Alcaldia\sss\menu_sss.awm
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
var awmHash='RYWNUHHVZFASICMMVWIGVQYARKSH';
var awmNoMenuPrint=1;
var awmUseTrs=0;
var awmSepr=["0","","","","90","#808080","","1"];
var awmMarg=[0,0,0,0];
var YY= 82;//((window.innerHeight/2)-(window.innerHeight/4))-(window.innerHeight/42); //82 
var XX= ((window.innerWidth/2)-500); //110 500 para los menus largos osea varias opciones
//alert("Ancho: " + X3 + "nAlto: " + YY);
function awmBuildMenu(){
if (awmSupported){
awmImagesColl=["seguridad.png",42,40,"definicion.png",31,22,"main-button-tile.gif",17,34,"indicator.gif",16,8,"main-item-left-float.png",17,24,"main-itemOver-left-float.png",15,24,"procesos.png",31,22,"reportes.png",31,22,"sistemas.png",31,22,"ingresos.png",31,22,"gastos.png",31,22,"contabilidad.png",31,22,"sep.png",31,22,"compras.png",31,22,"obras.png",31,22,"viatico.png",31,22,"rrhh.png",31,22,"nomina.png",31,22,"medicos.png",31,22,"ventas.png",31,22,"orden_pago.png",31,22,"caja_banco.png",31,22,"inventario.png",31,22,"activo.png",31,22,"mantenimiento.png",31,22,"apertura.png",31,22,"seguridad_1.png",31,22,"retorno.png",31,22];
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
//var s0=awmCreateMenu(0,0,0,1,9,0,0,0,0,220,82,0,1,2,1,0,1,n,n,100,0,0,220,82,0,-1,1,500,200,0,0,0,"0,0,0",n,n,n,n,n,n,n,n,0,0,0,0,0,0,0,0,1);
//   XX lo que se mueve a lo ancho y YY se desplaza arriba o abajo
var s0=awmCreateMenu(0,0,0,1,9,0,0,0,0,XX,YY,0,1,2,1,0,1,n,n,100,0,0,XX,YY,0,-1,1,500,200,0,0,0,"0,0,0",n,n,n,n,n,n,n,n,0,0,0,0,0,0,0,0,1);
it=s0.addItemWithImages(0,1,1,"Seguridad",n,n,"",0,0,0,3,3,3,n,n,n,"",n,n,n,n,n,0,0,0,n,n,n,n,n,n,0,0,0,0,0,n,n,n,0,0,0,n,n);
it=s0.addItemWithImages(3,4,4,"Definiciones",n,n,"",1,1,1,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,n,0,0,0,0,0,8,n);
var s1=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,1,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s1.addItemWithImages(6,7,7,"Sistemas",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sss_d_sistemas.php",n,n,n,"../tepuy_sss_d_sistemas.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,9,n);
//it=s1.addItemWithImages(6,7,7,"Ventanas del Sistemas",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_cfg_d_procedencia.php",n,n,n,"../tepuy_cfg_d_procedencia.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,9,n);
it=s1.addItemWithImages(6,7,7,"Grupos",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sss_d_grupos.php",n,n,n,"../tepuy_sss_d_grupos.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,10,n);
it=s1.addItemWithImages(6,7,7,"Tipos de Menu",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sss_d_tipos_menu.php",n,n,n,"../tepuy_sss_d_tipos_menu.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,12,n);
it=s1.addItemWithImages(6,7,7,"Usuarios",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sss_d_usuarios.php",n,n,n,"../tepuy_sss_d_usuarios.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,12,n);
it=s0.addItemWithImages(3,4,4,"Procesos",n,n,"",6,6,6,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,n,0,0,0,0,0,11,n);
var s1=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,17,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s1.addItemWithImages(6,7,7,"Asignar Usuarios a Grupos",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sss_p_usuariosgrupos.php",n,n,n,"../tepuy_sss_p_usuariosgrupos.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,5,n);
it=s1.addItemWithImages(6,7,7,"Asignar Nóminas a Usuarios",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sss_p_usuariosnominas.php",n,n,n,"../tepuy_sss_p_usuariosnominas.php",n,0,34,2,n,n,n,n,n,n,0,0,0,1,0,1,2,2,0,0,0,6,n);
it=s1.addItemWithImages(6,7,7,"Asignar Presupuesto a Usuarios",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sss_p_usuariospresupuesto.php",n,n,n,"../tepuy_sss_p_usuariospresupuesto.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,27,n);
it=s1.addItemWithImages(6,7,7,"Asignar Unidades Ejecutoras a Usuarios",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sss_p_usuariosunidad.php",n,n,n,"../tepuy_sss_p_usuariosunidad.php",n,0,34,2,n,n,n,n,n,n,0,0,0,1,0,1,2,2,0,0,0,28,n);
it=s0.addItemWithImages(3,4,4,"Reportes",n,n,"",7,7,7,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,1,2,2,0,0,0,14,n);
var s1=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,18,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s1.addItemWithImages(6,7,7,"Auditoría",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sss_r_auditoria.php",n,n,n,"../tepuy_sss_r_auditoria.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,7,n);
it=s1.addItemWithImages(6,7,7,"Permisos",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_sss_r_permisos.php",n,n,n,"../tepuy_sss_r_permisos.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,22,n);
it=s0.addItemWithImages(3,4,4,"Sistemas",n,n,"",8,7,7,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,1,2,2,0,0,0,15,n);
var s1=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,3,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s1.addItemWithImages(6,7,7,"Contabilidad Presupuestaria de Ingresos",n,n,"",9,9,9,3,3,3,n,n,n,"../tepuy_siv_r_articulosxsolicitar.php",n,n,"window.open('../tepuy_c_seleccionar_usuario.php?sist=SPI','catalogo','menubar=no,toolbar=no,scrollbars=yes,width=420,height=130,left=150,top=150,location=no,resizable=yes');","../tepuy_siv_r_articulosxsolicitar.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,17,n);
it=s1.addItemWithImages(6,7,7,"Contabilidad Presupuestaria de Gastos",n,n,"",10,10,10,3,3,3,n,n,n,"../tepuy_siv_r_articulosxsolicitar.php",n,n,"window.open('../tepuy_c_seleccionar_usuario.php?sist=SPG','catalogo','menubar=no,toolbar=no,scrollbars=yes,width=420,height=130,left=150,top=150,location=no,resizable=yes');","../tepuy_siv_r_articulosxsolicitar.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,23,n);
it=s1.addItemWithImages(6,7,7,"Contabilidad Patrimonial",n,n,"",11,11,11,3,3,3,n,n,n,"../tepuy_siv_r_articulosxsolicitar.php",n,n,"window.open('../tepuy_c_seleccionar_usuario.php?sist=SCG','catalogo','menubar=no,toolbar=no,scrollbars=yes,width=420,height=130,left=150,top=150,location=no,resizable=yes');","../tepuy_siv_r_articulosxsolicitar.php",n,0,34,2,n,n,n,n,n,n,0,0,0,1,0,1,2,2,0,0,0,24,n);
it=s1.addItemWithImages(6,7,7,"Solicitud de Ejecución Presupuestaria",n,n,"",12,12,12,3,3,3,n,n,n,"../tepuy_siv_r_articulosxsolicitar.php",n,n,"window.open('../tepuy_c_seleccionar_usuario.php?sist=SEP','catalogo','menubar=no,toolbar=no,scrollbars=yes,width=420,height=130,left=150,top=150,location=no,resizable=yes');","../tepuy_siv_r_articulosxsolicitar.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,25,n);
it=s1.addItemWithImages(6,7,7,"Control de Ayudas",n,n,"",12,12,12,3,3,3,n,n,n,"../tepuy_siv_r_articulosxsolicitar.php",n,n,"window.open('../tepuy_c_seleccionar_usuario.php?sist=AYU','catalogo','menubar=no,toolbar=no,scrollbars=yes,width=420,height=130,left=150,top=150,location=no,resizable=yes');","../tepuy_siv_r_articulosxsolicitar.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,25,n);
it=s1.addItemWithImages(6,7,7,"Ordenes de Compra",n,n,"",13,13,13,3,3,3,n,n,n,"../tepuy_siv_r_articulosxsolicitar.php",n,n,"window.open('../tepuy_c_seleccionar_usuario.php?sist=SOC','catalogo','menubar=no,toolbar=no,scrollbars=yes,width=420,height=130,left=150,top=150,location=no,resizable=yes');","../tepuy_siv_r_articulosxsolicitar.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,26,n);
it=s1.addItemWithImages(6,7,7,"Control de Obras",n,n,"",14,14,14,3,3,3,n,n,n,"../tepuy_siv_r_articulosxsolicitar.php",n,n,"window.open('../tepuy_c_seleccionar_usuario.php?sist=SOB','catalogo','menubar=no,toolbar=no,scrollbars=yes,width=420,height=130,left=150,top=150,location=no,resizable=yes');","../tepuy_siv_r_articulosxsolicitar.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,29,n);
it=s1.addItemWithImages(6,7,7,"Control de Viáticos",n,n,"",15,15,15,3,3,3,n,n,n,"../tepuy_siv_r_articulosxsolicitar.php",n,n,"window.open('../tepuy_c_seleccionar_usuario.php?sist=SCV','catalogo','menubar=no,toolbar=no,scrollbars=yes,width=420,height=130,left=150,top=150,location=no,resizable=yes');","../tepuy_siv_r_articulosxsolicitar.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,30,n);
it=s1.addItemWithImages(6,7,7,"Nómina - Recursos Humanos",n,n,"",16,16,16,3,3,3,n,n,n,"../tepuy_siv_r_articulosxsolicitar.php",n,n,"window.open('../tepuy_c_seleccionar_usuario.php?sist=SNR','catalogo','menubar=no,toolbar=no,scrollbars=yes,width=420,height=130,left=150,top=150,location=no,resizable=yes');","../tepuy_siv_r_articulosxsolicitar.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,31,n);
it=s1.addItemWithImages(6,7,7,"Nómina",n,n,"",17,17,17,3,3,3,n,n,n,"../tepuy_siv_r_articulosxsolicitar.php",n,n,"window.open('../tepuy_c_seleccionar_usuario.php?sist=SNO','catalogo','menubar=no,toolbar=no,scrollbars=yes,width=420,height=130,left=150,top=150,location=no,resizable=yes');","../tepuy_siv_r_articulosxsolicitar.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,32,n);
it=s1.addItemWithImages(6,7,7,"Servicios Médicos",n,n,"",18,18,18,3,3,3,n,n,n,"../tepuy_siv_r_articulosxsolicitar.php",n,n,"window.open('../tepuy_c_seleccionar_usuario.php?sist=SME','catalogo','menubar=no,toolbar=no,scrollbars=yes,width=420,height=130,left=150,top=150,location=no,resizable=yes');","../tepuy_siv_r_articulosxsolicitar.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,32,n);
it=s1.addItemWithImages(6,7,7,"Facturación",n,n,"",19,19,19,3,3,3,n,n,n,"../tepuy_siv_r_articulosxsolicitar.php",n,n,"window.open('../tepuy_c_seleccionar_usuario.php?sist=SFA','catalogo','menubar=no,toolbar=no,scrollbars=yes,width=420,height=130,left=150,top=150,location=no,resizable=yes');","../tepuy_siv_r_articulosxsolicitar.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,33,n);
it=s1.addItemWithImages(6,7,7,"Orden de Pago",n,n,"",20,20,20,3,3,3,n,n,n,"../tepuy_siv_r_articulosxsolicitar.php",n,n,"window.open('../tepuy_c_seleccionar_usuario.php?sist=CXP','catalogo','menubar=no,toolbar=no,scrollbars=yes,width=420,height=130,left=150,top=150,location=no,resizable=yes');","../tepuy_siv_r_articulosxsolicitar.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,34,n);
it=s1.addItemWithImages(6,7,7,"Caja y Banco",n,n,"",21,21,21,3,3,3,n,n,n,"../tepuy_siv_r_articulosxsolicitar.php",n,n,"window.open('../tepuy_c_seleccionar_usuario.php?sist=SCB','catalogo','menubar=no,toolbar=no,scrollbars=yes,width=420,height=130,left=150,top=150,location=no,resizable=yes');","../tepuy_siv_r_articulosxsolicitar.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,35,n);
it=s1.addItemWithImages(6,7,7,"Inventario",n,n,"",22,22,22,3,3,3,n,n,n,"../tepuy_siv_r_articulosxsolicitar.php",n,n,"window.open('../tepuy_c_seleccionar_usuario.php?sist=SIV','catalogo','menubar=no,toolbar=no,scrollbars=yes,width=420,height=130,left=150,top=150,location=no,resizable=yes');","../tepuy_siv_r_articulosxsolicitar.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,36,n);
it=s1.addItemWithImages(6,7,7,"Activos Fijos",n,n,"",23,23,23,3,3,3,n,n,n,"../tepuy_siv_r_articulosxsolicitar.php",n,n,"window.open('../tepuy_c_seleccionar_usuario.php?sist=SAF','catalogo','menubar=no,toolbar=no,scrollbars=yes,width=420,height=130,left=150,top=150,location=no,resizable=yes');","../tepuy_siv_r_articulosxsolicitar.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,37,n);
it=s1.addItemWithImages(6,7,7,"Mantenimiento",n,n,"",24,24,24,3,3,3,n,n,n,"../tepuy_siv_r_articulosxsolicitar.php",n,n,"window.open('../tepuy_c_seleccionar_usuario.php?sist=INS','catalogo','menubar=no,toolbar=no,scrollbars=yes,width=420,height=130,left=150,top=150,location=no,resizable=yes');","../tepuy_siv_r_articulosxsolicitar.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,38,n);
it=s1.addItemWithImages(6,7,7,"Apertura",n,n,"",25,25,25,3,3,3,n,n,n,"../tepuy_siv_r_articulosxsolicitar.php",n,n,"window.open('../tepuy_c_seleccionar_usuario.php?sist=APR','catalogo','menubar=no,toolbar=no,scrollbars=yes,width=420,height=130,left=150,top=150,location=no,resizable=yes');","../tepuy_siv_r_articulosxsolicitar.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,39,n);
it=s1.addItemWithImages(6,7,7,"Seguridad",n,n,"",26,26,26,3,3,3,n,n,n,"../tepuy_siv_r_articulosxsolicitar.php",n,n,"window.open('../tepuy_c_seleccionar_usuario.php?sist=SSS','catalogo','menubar=no,toolbar=no,scrollbars=yes,width=420,height=130,left=150,top=150,location=no,resizable=yes');","../tepuy_siv_r_articulosxsolicitar.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,40,n);
it=s0.addItemWithImages(3,4,4,"Mantenimiento",n,n,"",23,7,7,3,3,3,n,n,n,"",n,n,n,n,n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,1,2,2,0,0,0,19,n);
var s1=it.addSubmenu(0,0,-1,0,0,0,0,5,1,1,0,n,n,100,-1,4,0,-1,1,200,200,0,0,"0,0,0",0,"0",1);
it=s1.addItemWithImages(6,7,7,"Cambio Password de Usuario",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_siv_r_articulosxsolicitar.php",n,n,"window.open('../tepuy_sss_p_repassword.php','catalogo','menubar=no,toolbar=no,scrollbars=yes,width=550,height=310,left=150,top=150,location=no,resizable=no');","../tepuy_siv_r_articulosxsolicitar.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,20,n);
it=s1.addItemWithImages(6,7,7,"Actualizar Ventanas",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_siv_r_articulosxsolicitar.php",n,n,"window.open('../tepuy_sss_p_actualizar_ventanas.php','catalogo','menubar=no,toolbar=no,scrollbars=yes,width=430,height=230,left=150,top=150,location=no,resizable=no');","../tepuy_siv_r_articulosxsolicitar.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,41,n);
it=s1.addItemWithImages(6,7,7,"Administrador Tepuy -&gt; Cambia Password a Usuario",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_siv_r_articulosxsolicitar.php",n,n,"window.open('../tepuy_sss_p_repassword_admin.php','catalogo','menubar=no,toolbar=no,scrollbars=yes,width=550,height=310,left=150,top=150,location=no,resizable=no');","../tepuy_siv_r_articulosxsolicitar.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,42,n);
it=s1.addItemWithImages(6,7,7,"Administrador Tepuy -&gt; Permisos por Sistema",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_siv_r_articulosxsolicitar.php",n,n,"window.open('../tepuy_sss_p_permisos_globales.php','catalogo','menubar=no,toolbar=no,scrollbars=yes,width=550,height=310,left=150,top=150,location=no,resizable=no');","../tepuy_siv_r_articulosxsolicitar.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,43,n);
it=s1.addItemWithImages(6,7,7,"Administrador Tepuy -&gt;Eliminar Permisos a Usuario",n,n,"",n,n,n,3,3,3,n,n,n,"../tepuy_siv_r_articulosxsolicitar.php",n,n,"window.open('../tepuy_sss_p_eliminar_permisos.php','catalogo','menubar=no,toolbar=no,scrollbars=yes,width=550,height=310,left=150,top=150,location=no,resizable=no');","../tepuy_siv_r_articulosxsolicitar.php",n,0,34,2,n,n,n,n,n,n,0,0,0,0,0,1,2,2,0,0,0,44,n);
it=s0.addItemWithImages(3,4,4,"Retornar",n,n,"",26,26,26,3,3,3,n,n,n,"",n,n,n,"../../tepuy_menu.php",n,0,34,2,n,n,n,n,n,n,1,0,0,0,0,n,0,0,0,0,0,4,n);
s0.pm.buildMenu();
}}
