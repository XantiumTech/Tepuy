<?php
class tepuy_include
{
	var $msg;
	function tepuy_include()
	{
		require_once("class_mensajes.php");
		require_once("class_sql.php");
		$this->msg=new class_mensajes();	
	}

	function uf_conectar ($as_databasedestino="") 
	{
		if($as_databasedestino=="")
		{
			if (strtoupper($_SESSION["ls_gestor"])==strtoupper("mysql"))
			{
				$conec = @mysql_connect($_SESSION["ls_hostname"],$_SESSION["ls_login"],$_SESSION["ls_password"]);
	
				if($conec===false)
				{
					$this->msg->message("No pudo conectar con el servidor de datos,".$_SESSION["ls_hostname"]." , contacte al administrador del sistema");
					
					exit;
				}
				else
				{
					$lb_ok=mysql_select_db(trim($_SESSION["ls_database"]),$conec);
					if (!$lb_ok)
					{
						$this->msg->message("No existe la base de datos ".$_SESSION["ls_database"]);					
						exit();
					}
				}
			return $conec;
			}
			
			if(strtoupper($_SESSION["ls_gestor"])==strtoupper("postgre"))
			{
				$conec = @pg_connect("host=".$_SESSION["ls_hostname"]." port=".$_SESSION["ls_port"]."  dbname=".$_SESSION["ls_database"]." user=".$_SESSION["ls_login"]." password=".$_SESSION["ls_password"]); 
			
				if (!$conec)
				{
					$this->msg->message("No pudo conectar al servidor de base de datos, contacte al administrador del sistema");				
					exit();
				}
			 return $conec;
			}
		}
		else
		{
			if (strtoupper($_SESSION["ls_gestor_destino"])==strtoupper("mysql"))
			{
				$conec = @mysql_connect($_SESSION["ls_hostname_destino"],$_SESSION["ls_login_destino"],$_SESSION["ls_password_destino"]);
	
				if($conec===false)
				{
					$this->msg->message("No pudo conectar con el servidor de datos,".$_SESSION["ls_hostname_destino"]." , contacte al administrador del sistema");
					
					exit;
				}
				else
				{
					$lb_ok=mysql_select_db(trim($as_databasedestino),$conec);
					if (!$lb_ok)
					{
						$this->msg->message("No existe la base de datos ".$_SESSION["ls_database_destino"]);					
						exit();
					}
				}
			return $conec;
			}
			if(strtoupper($_SESSION["ls_gestor_destino"])==strtoupper("postgre"))
			{
				$conec = pg_connect("host=".$_SESSION["ls_hostname_destino"]." port=".$_SESSION["ls_port_destino"]."  dbname=".$as_databasedestino." user=".$_SESSION["ls_login_destino"]." password=".$_SESSION["ls_password_destino"]); 
			
				if (!$conec)
				{
					$this->msg->message("No pudo conectar al servidor de base de datos, contacte al administrador del sistema");				
					exit();
				}
			 return $conec;
			}
		}		
	}
}
?>
