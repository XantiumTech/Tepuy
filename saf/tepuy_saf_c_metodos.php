<?php
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/tepuy_include.php");

class tepuy_saf_c_metodos
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function tepuy_saf_c_metodos()
	{
		$msg=new class_mensajes();
		$this->dat_emp=$_SESSION["la_empresa"];
		$in=new tepuy_include();
		$this->con=$in->uf_conectar();
		$this->io_sql=new class_sql($this->con);
	}//fin de la function tepuy_saf_c_metodos()
	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////           Inicio function uf_sss_select_metodos     	///////////////////////////////////////// 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_saf_select_metodos()
	{
	
	}//fin de la function uf_saf_select_metodos()






}//fin de la class tepuy_saf_c_metodos
?>
