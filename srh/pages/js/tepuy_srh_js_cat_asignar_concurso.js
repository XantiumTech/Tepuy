
var url= "../../php/tepuy_srh_a_asignar_concurso.php";
var metodo='get';
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";


var loadDataURL = "../../php/tepuy_srh_a_asignar_concurso.php?valor=createXML";
var actionURL = "../../php/tepuy_srh_a_solicitud_empleo.php";
var mygrid;
var timeoutHandler;//update will be sent automatically to server if row editting was stoped;
var rowUpdater;//async. Calls doUpdateRow function when got data from server
var rowEraser;//async. Calls doDeleteRow function when got confirmation about row deletion
var authorsLoader;//sync. Loads list of available authors from server to populate dropdown (co)
var mandFields = [0,1,1,0,0];
		
		
	//initialise (from xml) and populate (from xml) grid
function doOnLoad()
		{
          
			mygrid = new dhtmlXGridObject('gridbox');
		 	mygrid.setImagePath("../../../public/imagenes/"); 
			//set columns properties
			mygrid.setHeader("Numero,Fecha Asignación,Concurso");
			mygrid.setInitWidths("100,100,333");
			mygrid.setColAlign("center,center,center");
			mygrid.setColTypes("link,ro,ro");
			mygrid.setColSorting("str,str,str");//nuevo  ordenacion
			mygrid.setColumnColor("#FFFFFF,#FFFFFF,#FFFFFF");
			//mygrid.loadXML(loadDataURL);
			mygrid.setSkin("xp");
			mygrid.init();
			

		}
		
function terminar_buscar ()
{ 
   divResultado = document.getElementById('mostrar');
   divResultado.innerHTML= '';   
}	
		
function Buscar()
{
	
	 nroreg=document.form1.txtnroreg.value;
	 codcon=document.form1.txtcodcon.value;
	 fechades=document.form1.txtfechades.value;
	 fechahas=document.form1.txtfechahas.value;
	 
	 valfec= ue_comparar_fechas(fechades,fechahas);
	 
     if (!valfec)
	 {
		alert ('Rango de Fecha Invalido.');	 
	 }
	 else
	 {
		  mygrid.clearAll();
		  divResultado = document.getElementById('mostrar');
		  divResultado.innerHTML= img; 
		  mygrid.loadXML("../../php/tepuy_srh_a_asignar_concurso.php?valor=buscar"+"&txtnroreg="+nroreg+"&txtfechades="+fechades+"&txtfechahas="+fechahas+"&txtcodcon="+codcon);
		  setTimeout (terminar_buscar,650);
	 }

}
		
		
	
function Limpiar_busqueda () 
{
	$('txtnroreg').value="";
	$('txtcodcon').value="";
	$('txtdescon').value="";
	$('txtfechades').value=$('txtfecdes2').value;
	$('txtfechahas').value=$('txtfechas2').value;
}
	
	
function aceptar(ls_nroreg, ls_fecha, ls_obs, ls_codcon, ls_descon, ls_coddestino, ls_desdestino,ls_fechaaperdestino, ls_fechaaper , ls_fechaciedestino, ls_fechacie,ls_codcardestino, ls_codcar, ls_cantcardestino, li_cantcar,ls_nroregdestino, ls_fechadestino, ls_obsdestino,ls_tipcon, ls_tipcondestino)
	{
		
		if (opener.document.form1.hidcontrol2.value=="3") {
		 obj=eval("opener.document.form1."+ls_nroregdestino+"");
		 obj.value=ls_nroreg;
			}
		else {
		
  		obj=eval("opener.document.form1."+ls_coddestino+"");
		obj.value=ls_codcon;
		obj1=eval("opener.document.form1."+ls_desdestino+"");
		obj1.value=ls_descon;
		obj2=eval("opener.document.form1."+ls_fechaaperdestino+"");
		obj2.value=ls_fechaaper;
		obj3=eval("opener.document.form1."+ls_fechaciedestino+"");
		obj3.value=ls_fechacie;
		obj4=eval("opener.document.form1."+ls_fechaciedestino+"");
		obj4.value=ls_fechacie;
		obj5=eval("opener.document.form1."+ls_codcardestino+"");
		obj5.value=ls_codcar;
		obj5=eval("opener.document.form1."+ls_cantcardestino+"");
		obj5.value=li_cantcar;
		obj6=eval("opener.document.form1."+ls_nroregdestino+"");
		obj6.value=ls_nroreg;
		obj7=eval("opener.document.form1."+ls_fechadestino+"");
		obj7.value=ls_fecha;
		obj8=eval("opener.document.form1."+ls_obsdestino+"");
		obj8.value=ls_obs;
		obj9=eval("opener.document.form1."+ls_tipcondestino+"");
		obj9.value=ls_tipcon;
		
		ls_ejecucion=document.form1.hidstatus.value
		if(ls_ejecucion=="1")
		{
		 opener.document.form1.hidguardar.value = "modificar";
		 opener.document.form1.hidstatus.value="C";	
		}else{
   		 opener.document.form1.hidguardar.value = "insertar";	
		 opener.document.form1.hidstatus.value="";
		 	
		}
			opener.document.form1.txtnroreg.readOnly=true;
			
			
			opener.document.form1.operacion.value="BUSCARDETALLE";
			opener.document.form1.action="../pantallas/tepuy_srh_p_asignar_concurso.php";
			opener.document.form1.existe.value="TRUE";			
			opener.document.form1.submit();	
		}
	
	      
			
			close ();
				 	
}
	
function nextPAge(val)
		{
			grid.clearAll(); //clear existing data
			grid.loadXML("some_url.php?page="+val);
		}
		
 
 //--------------------------------------------------------
//	Función que verifica que la fecha 2 sea mayor que la fecha 1
//----------------------------------------------------------
   function ue_comparar_fechas(fecha1,fecha2)
{
	vali=false;
	dia1 = fecha1.substr(0,2);
	mes1 = fecha1.substr(3,2);
	ano1 = fecha1.substr(6,4);
	dia2 = fecha2.substr(0,2);
	mes2 = fecha2.substr(3,2);
	ano2 = fecha2.substr(6,4);
	if (ano1 < ano2)
	{
		vali = true; 
	}
    else 
	{ 
    	if (ano1 == ano2)
	 	{ 
      		if (mes1 < mes2)
	  		{
	   			vali = true; 
	  		}
      		else 
	  		{ 
       			if (mes1 == mes2)
	   			{
 					if (dia1 <= dia2)
					{
		 				vali = true; 
					}
	   			}
      		} 
     	} 	
	}
	
	return vali;
	
}
		