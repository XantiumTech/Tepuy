// JavaScript Document
function objetoAjax()
{
	var xmlhttp=false;
	try {
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	} catch (e) {
		try {
		   xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		} catch (E) {
			xmlhttp = false;
  		}
	}

	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
		xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}

var url= "../../php/tepuy_srh_a_cargo.php";
var metodo="POST";
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";
var ajax=objetoAjax();

function ue_validaexiste()
{
  
	var divResultado= $('existe');
	var paran="txtcodcar="+$F('txtcodcar');
	if (($F('txtcodcar')!=""))
	{
	   ajax.open(metodo,url+"?valor=existe",true);
	   ajax.onreadystatechange=function() 
	   {
		  if (ajax.readyState==4)
		  {
				if(ajax.status==200)
				{
					 divResultado.innerHTML = ajax.responseText
					 if(divResultado.innerHTML=='')
					 {
					 }
					 else
					 {
						  Field.clear('txtcodcar');
						  Field.activate('txtcodcar');
						  Field.clear('txtdescar');						  
						  alert(divResultado.innerHTML);
						 
					 }
				}
				else
				{
					 alert('ERROR '+ajax.status);
				}
		  }
	   }
	
      ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	  ajax.send(paran);
	}
}//fin ue_validaexiste()



function ue_cancelar()
{
  document.form1.reset();
  document.form1.txtcodcar.readOnly=false;
  document.form1.hidstatus.value="";
   scrollTo(0,0);
}

function ue_nuevo()
{
  
  ue_cancelar();
}


function ue_validavacio()
{
  lb_valido=true;
  f=document.form1;
  
if(f.txtcodcar.value=="")
  {
		alert('Falta Codigo de Cargo');
		lb_valido=false;
   }
   else if(f.txtdescar.value=="")
   {
	   alert('Falta Desnominacion del Cargo');
	   lb_valido=false;
   }
   else if(f.txtcodnom.value=="")
   {
	   alert('Falta Codigo de Nomina')  ;
	   lb_valido=false;
   }
   
   return lb_valido;
 

}





function ue_guardar_registro()
{
		  //donde se mostrará lo resultados
			  divResultado = document.getElementById('mostrar');
			  divResultado.innerHTML= img;
			
			  //valores de las cajas de texto
			  codnom=document.form1.txtcodnom.value;
			  descar=document.form1.txtdescar.value;
			  codcar=document.form1.txtcodcar.value;
			
			 
			  //instanciamos el objetoAjax
			  ajax=objetoAjax();
			  //uso del medoto POST
			  //archivo que realizará la operacion
			 
			  ajax.open(metodo,url+"?valor=guardar",true);
			  ajax.onreadystatechange=function() 
			  {
				  if (ajax.readyState==4)
				  {
				  //mostrar resultados en esta capa
				  divResultado.innerHTML = ajax.responseText;
				  
				  
				   if(divResultado.innerHTML)
					{
					   
					   
					   if(ajax.status==200)
					   {
					   	alert( divResultado.innerHTML);
					   }
					   else
					   {
						alert(ajax.statusText);   
						}
					 
					}
					
				 					
				  }
				
				 ue_nuevo();
			  }
   ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
  //enviando los valores
   ajax.send("txtcodnom="+codnom+"&txtdescar="+descar+"&txtcodcar="+codcar);


}


function ue_guardar()
{
	lb_valido=ue_validavacio();
	
	if(lb_valido)
	{
		ue_guardar_registro();
	}//lb_valido
}//ue_guardar





function ue_eliminar()
{
		if(document.form1.hidstatus.value=="C")
		{
			
			if (confirm("Esta seguro de eliminar este registro?"))
			{
			  //donde se mostrará lo resultados
  
			  divResultado = document.getElementById('mostrar');
			   divResultado.innerHTML=img;
			
			  codcar=document.form1.txtcodcar.value;
			  codnom=document.form1.txtcodnom.value;
			
			  
			 
			  //instanciamos el objetoAjax
			  ajax=objetoAjax();
			  //uso del medoto POST
		
			  
			  ajax.open(metodo,url+"?valor=eliminar",true);
			  ajax.onreadystatechange=function() 
			  {
				   if (ajax.readyState==4)
				  {
				  //mostrar resultados en esta capa
				  divResultado.innerHTML = ajax.responseText;
				  
				  
				   if(divResultado.innerHTML)
					{
					   
					   
					   if(ajax.status==200)
					   {
					   	alert(divResultado.innerHTML);
					   }
					   else
					   {
						alert(ajax.statusText);   
						}
			
					} 
					
					
					
				  }
		
				 ue_nuevo();
			  }
   ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
  //enviando los valores
   ajax.send("txtcodcar="+codcar+"&txtcodnom="+codnom);

   		}	
   }
		else
	   {
		
		alert('Debe elegir un Cargo del Catalogo');
		
		}
}




  
function catalogo_nomina()
{
      pagina="../catalogos/tepuy_srh_cat_nomina.php";
		window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
		
	
}




function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		f.hidstatus.value='C';
		window.open("../catalogos/tepuy_srh_cat_cargo.php?valor_cat=1","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}


function ue_cerrar()
{
	window.location.href="tepuywindow_blank.php";
}


  
  
  

  
  
  
