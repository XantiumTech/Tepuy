<?php
//include ("shared/jpgraph/jpgraph.php");
//include ("shared/jpgraph/jpgraph_line.php");
//include ("shared/jpgraph/jpgraph_pie.php");
//include ("shared/jpgraph/jpgraph_pie3d.php"); 
require_once("shared/class_folder/tepuy_include.php");
$_SESSION["ls_database"]="db_barinas_2015";
$_SESSION["ls_gestor"]="MYSQL";
$_SESSION["ls_hostname"]="localhost";
$_SESSION["ls_port"]="3306";
$_SESSION["ls_login"]="root";
$_SESSION["ls_password"]="root";
 
$io_in=new tepuy_include();
$con=$io_in->uf_conectar();

require_once("shared/class_folder/class_datastore.php");
$io_ds=new class_datastore();
//require_once("cfg/class_folder/tepuy_cfg_c_empresa.php");

require_once("shared/class_folder/class_sql.php");
$io_sql=new class_sql($con);

require_once("shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();

require_once("shared/class_folder/class_funciones.php");
$io_funcion=new class_funciones(); 
$io_conect = new tepuy_include();
$msg=new class_mensajes();

$conec = @mysql_connect($_SESSION["ls_hostname"],$_SESSION["ls_login"],$_SESSION["ls_password"]);

			if($conec===false)
			{
				$msg->message("No pudo conectar con el servidor de datos,".$_SESSION["ls_hostname"]." , contacte al administrador del sistema");
				
				
			}
			else
			{
				$lb_ok=mysql_select_db(trim($_SESSION["ls_database"]),$conec);
				if (!$lb_ok)
				{
					$msg->message("No existe la base de datos ".$_SESSION["ls_database"]);					
					
				}
			}
////
	//$io_empresa = new tepuy_cfg_c_empresa($conn);
	//$obj_sql=new class_sql($conn);
	//$ls_sql="SELECT * FROM tepuy_empresa ";
	//$result=$obj_sql->select($ls_sql);
	//print $ls_sql;
	//}
	///
	$nivelmas=" WHERE  c.nivel=1 AND c.codestpro1='00000000000000000001' ";
	$agrupa=" GROUP BY c.spg_cuenta, c.nivel,  c.codestpro1  ";
	$ls_sql=" SELECT e.denestpro1, c.spg_cuenta, c.nivel, max(c.denominacion) as denominacion, sum(c.asignado) as asignado, ".
                "        sum(c.comprometido) as comprometido, sum(c.causado) as causado, sum(c.pagado) as pagado, ".
                "        sum(c.aumento) as aumento, sum(c.disminucion) as disminucion, sum(c.enero) as enero, ".
                "        sum(c.febrero) as febrero, sum(c.marzo) as marzo, sum(c.abril) as abril, sum(c.mayo) as mayo, ".
                "        sum(c.junio) as junio, sum(c.julio) as julio, sum(c.agosto) as agosto, sum(c.septiembre) as septiembre,".
                "        sum(c.octubre) as octubre, sum(c.noviembre) as noviembre, sum(c.diciembre) as diciembre, ".
                "        c.codestpro1, c.codestpro2, c.codestpro3, c.codestpro4, c.codestpro5 ".
		" FROM   spg_cuentas c, spg_ep1 e".
		$nivelmas.
                $agrupa.
                " ORDER  BY c.codestpro1, c.spg_cuenta";
	///
	//print $ls_sql;
	//$query = "select ventas from spg_cuentas order by nombre asc";
	$rs_data=$io_sql->select($ls_sql);
	if ($rs_data===false)
		{
			$lb_valido = false;
			$this->io_msg->message("CLASE->Report MÉTODO->ERROR No se encontraron Datos->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_numrows = $io_sql->num_rows($rs_data);
			//print "pasa".$li_numrows;
			$total=0.0000;
			$porcentaje=0.0000;	
			if($li_numrows>0)
			{
    				while($row=$io_sql->fetch_row($rs_data))
    				{
        			$ydata[]=$row["asignado"]; //concatenamos el los options para luego ser insertado en el HTML
				$total= $total + $row["asignado"];
				$nombres[] = '"'.$row["denominacion"].'"'; 
				//print $row["denominacion"]." ".$row["asignado"];
				//print "Acum: ".$total;
    				}

				//print "Total: ".$total;
				//foreach ($array as &$ydata)
				$i=0;
				foreach ($ydata as $v)
				{
					$v = ($v*100)/$total;
					//print "<b>".$nombres[$i]."</b>"." =>".$v;
    					$i++;

    				}
			}
		}
	
	// Create the graph. These two calls are always required

	/*$graph = new Graph(800,600,"auto");
	$graph->SetScale("textlin");
	$graph->img->SetAntiAliasing();
	$graph->xgrid->Show();
	// Create the linear plot
	$lineplot=new LinePlot($ydata);
	$lineplot->SetColor("black");
	$lineplot->SetWeight(2);
	$lineplot->SetLegend("Presupuesto");
	// Setup margin and titles
	$graph->img->SetMargin(40,20,20,40);
	$graph->title->Set("Ejemplo: Ventas por Empleado");
	$graph->xaxis->title->Set("Partidas");
	$graph->yaxis->title->Set("Montos");
	//$graph->ygrid->SetFill(true,’#EFEFEF@0.5′,’#F9BB64@0.5′); No existe este metodo en Grid
	$graph->SetShadow();
	// Add the plot to the graph
	$graph->Add($lineplot);
	// Display the graph
	$graph->Stroke(); */
	
	// Grafico de Torta //
	//error_reporting(0);
//Se define el grafico

/////////////////////////////////////////////////////////////////////////////////////////////////
print "paso";
	// Evita que se vean los errores
	//error_reporting(0);
	include("shared/jpgraph/jpgraph.php");
	include("shared/jpgraph/jpgraph_pie.php");
	$datos = array(9, 5, 12, 11, 6);
	$grafico = new PieGraph(400, 300, "auto");
	$grafico->SetScale("textlin");
	$pieplot = new PiePlot($datos);
	$grafico->Add($pieplot);
	$grafico->Stroke();

/////////////////////////////////////////////////////////////////////////////////////////////////
/*

$grafico = new PieGraph(450,300);

//Definimos el titulo
$grafico->title->Set("Mi primer grafico de torta");
$grafico->title->SetFont(FF_FONT1,FS_BOLD);

//Añadimos el titulo y la leyenda
$datos = array(20,10,51,19);
$leyenda = array("Morenas","Rubias","Pelirrojas","Otras");
$p1 = new PiePlot($datos);
$p1->SetLegends($leyenda);
$p1->SetPrecision(2);
$p1->SetCenter(0.4);

//Se muestra el grafico
$grafico->Add($p1);
$grafico->Stroke();

	//$graph->img->SetAntiAliasing();
	//$graph->SetMarginColor('gray');
	//$graph->SetShadow();

	// Setup margin and titles
	//$graph->title->Set("Distribucion del Presupuesto");

	//$p1 = new PiePlot3D($ydata);
	//$p1->SetSliceColors(array("red", "green", "blue", "yellow", "white"));
	//$p1->SetSize(0.35);
	//$p1->SetCenter(0.5);

	// Setup slice labels and move them into the plot
	//$p1->value->SetFont(FF_FONT1,FS_BOLD);
	//$p1->value->SetColor("black");
	//$p1->SetLabelPos(0.2);

	//$nombres=array("pepe","luis","miguel","alberto");
	//$p1->SetLegends($nombres);

	// Explode all slices
	//$p1->ExplodeAll();

	//$graph->Add($p1);
	//$graph->Stroke();  
	}*/
//mysql_free_result($rs_data);
//mysql_close($conn);
?>
