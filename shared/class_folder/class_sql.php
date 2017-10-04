<?php
  ////////////////////////////////////////////////////////////////////////////////////////////////////////
  //       Class : class_sql
  // Description : Clase creada para el manejo de comando sql para cualquier gestor de base de datos.
  ////////////////////////////////////////////////////////////////////////////////////////////////////////
class class_sql
{
	var $conn;	//objeto de conexion a la base de datos
	var $fetch_row;
	var $fetch_array;
	var $message;
	var $errno;
	var $datastore;//Instancia de la clase datastore
	function class_sql($con)
	{
		require_once("class_datastore.php");
		$obj=new class_datastore();
		$this->datastore=$obj;
		$this->conn=$con;
	}

	function seleccionar($ps_sentencia, &$pa_datos="")
	 {
	   $lb_valido = false;
	   switch ($_SESSION["ls_gestor"]) {
		   case 'MYSQL':
				$resultado = @mysql_query($ps_sentencia,$this->conn);
				if ($resultado != null && @mysql_num_rows($resultado) > 0)
				{
				  $lb_valido = true;
				  $i = 0;
				  while ($fila = @mysql_fetch_assoc($resultado))
				  {
					for ($j=0; $j<count($fila); $j++)
					{
					  $ls_campo = @mysql_field_name($resultado,$j);
					  $pa_datos[$ls_campo][$i] = $fila[$ls_campo];
					}
					$i++;		
				  }
				}
	        break;
			case 'POSTGRE':
				$resultado = @pg_query($this->conn,$ps_sentencia);
				if($resultado != null && @pg_num_rows($resultado) > 0 )
				{
				  $lb_valido = true;
				  $i = 0;
				  while ($fila = @pg_fetch_assoc($resultado))
				  {
					for ($j=0; $j<count($fila); $j++)
					{
					  $ls_campo = @pg_field_name($resultado,$j);
					  $pa_datos[$ls_campo][$i] = $fila[$ls_campo];
					}
					$i++;		
				  }
				}
			break;
			}
				return $lb_valido;
	} // end function
	  
	
	function select($sql)
	{
		////////////////////////////////////////////////////////////////////////////////////////////
		// Function:   	 select
		// Description : Realiza una consulta SQL y retorna un resulset con los datos obtenidos.
		// Arguments:
		//			     $sql->cadena que contiene la sentencia SQL
		////////////////////////////////////////////////////////////////////////////////////////////
		switch ($_SESSION["ls_gestor"]) {
		   case 'MYSQL':
				$result = @mysql_query($sql,$this->conn);
				if(!$result)
				{
				   $this->message  = @mysql_error() . "\n";
				   $this->message .= @mysql_errno();
				   return false;
				}
				else
				{
					//$data=$this->obtener_datos($result);
					return $result;
				}
				break;
			case 'POSTGRE':
				$result = @pg_query($this->conn,$sql);
				if(!$result)
				{
				   $this->message  = 'Invalid query: ' . @pg_last_error() . "\n";
				   $this->message .= 'Whole query: ' . $sql;
				   return false;
				}
				else
				{
					//$data=$this->obtener_datos($result);
					return $result;
				}
			break;
			}
	} // end function
		
	function execute($sql)
	{
		////////////////////////////////////////////////////////////////////////////////////////////
		// Function:   	 execute
		// Description : Realiza una transaccion SQL y retorna el numero de filas afectadas
		// Arguments:
		//			     $sql->cadena que contiene la sentencia SQL
		////////////////////////////////////////////////////////////////////////////////////////////
		switch ($_SESSION["ls_gestor"]) {
		   case 'MYSQL':
				$result = @mysql_query($sql,$this->conn);
				$rows   = @mysql_affected_rows();
				if(!$result)
				{
				   $this->message  = 'Invalid query: ' . @mysql_error() . "\n";
				   $this->message .= 'Whole query: ' . $sql;
				   $this->errno    = mysql_errno();
				  // die($message);
				   return false;
				}
				else
				{
					return $rows;
				}
				break;
			case 'POSTGRE':
				/* esto es lo anterior cambiado el 31-01-2007
				$result = @pg_query($this->conn,$sql);
				$rows   = @pg_affected_rows($result) ;

				if(!$result)
				{
				   $this->message  = 'Invalid query: '.pg_last_error($this->conn) . "\n";
				   $this->message .= 'Whole query: '.$sql;
				  // die($message);
				   return false;
				}
				else
				{
					return $rows;
				}*/
			  
			 	 $this->message="";
			  	 $result = @pg_query($this->conn,$sql);
			 	 $rows   = @pg_affected_rows($result) ;
			 	  if($result===false)
			 	  {			
					  $this->rollback();
 			  		  $this->begin_transaction();
					  pg_send_query($this->conn,$sql);
					  $result_error = pg_get_result($this->conn)	; 	
					  $this->errno=pg_result_error_field($result_error,PGSQL_DIAG_SQLSTATE); 
					  pg_free_result($result_error);
					  $this->message ='Invalid query: '.pg_last_error($this->conn) . "\n";
					  $this->message .= 'Whole query: '.$sql.$this->errno;
					  $this->rollback();
					  return false;
			 	 }
			 	 else
			 	 {
					return $rows;
			 	 }
				break;
			}		
	}// end function
	
	function fetch_row($rs_data)
	{
		////////////////////////////////////////////////////////////////////////////////////////////
		// Function:   	 fetch_row
		// Description : Retorna el valor siguiente encontrado en el resulset
		// Arguments:
		//			     $rs_data->Resulset obtenido del metodo select.
		////////////////////////////////////////////////////////////////////////////////////////////
		if(isset($rs_data)) {
			if(strtoupper($_SESSION["ls_gestor"])==strtoupper("mysql"))$this->fetch_row = @mysql_fetch_assoc($rs_data);
			if(strtoupper($_SESSION["ls_gestor"])==strtoupper("postgre"))$this->fetch_row = @pg_fetch_assoc($rs_data);
			return $this->fetch_row;
		}
	}// end function

	function num_rows($rs_data) 
	{
		////////////////////////////////////////////////////////////////////////////////////////////
		// Function:   	 num_rows
		// Description : Retorna el numero de registros que resultaron del metodo select
		// Arguments:
		//			     $rs_data->Resulset obtenido del metodo select.
		////////////////////////////////////////////////////////////////////////////////////////////
		if(isset($rs_data)) {
			if(strtoupper($_SESSION["ls_gestor"])==strtoupper("mysql")) $this->numrows = @mysql_num_rows($rs_data);
			if(strtoupper($_SESSION["ls_gestor"])==strtoupper("postgre")) $this->numrows = @pg_num_rows($rs_data);
			return $this->numrows;
		}
	}// end function

	function field_name($rs_data,$field) 
	{
		////////////////////////////////////////////////////////////////////////////////////////////
		// Function:   	filed_name
		// Description : Retorna el nombre de la columna correspondiente al numero enviado como parametro
		// Arguments:
		//			     $rs_data->Resulset obtenido del metodo select.
		//				 $field->numero de la columna a buscar
		////////////////////////////////////////////////////////////////////////////////////////////		
		if(isset($rs_data) && isset($field)) {
			if(strtoupper($_SESSION["ls_gestor"])==strtoupper("mysql")) $this->fetcharray = @mysql_field_name($rs_data,$field);
			if(strtoupper($_SESSION["ls_gestor"])==strtoupper("postgre")) $this->fetcharray = @pg_field_name($rs_data,$field);
			return $this->fetcharray;
		}
	}// end function

	function field_type ($rs_data,$field) 
	{
		////////////////////////////////////////////////////////////////////////////////////////////
		// Function:   	filed_type
		// Description : Retorna el tipo de datos de la columna correspondiente al numero enviado como parametro
		// Arguments:
		//			     $rs_data->Resulset obtenido del metodo select.
		//				 $field->numero de la columna a buscar
		////////////////////////////////////////////////////////////////////////////////////////////				
		if(isset($rs_data) && isset($field)) {
			if(strtoupper($_SESSION["ls_gestor"])==strtoupper("mysql")) $this->fetcharray = @mysql_field_type($rs_data,$field);
			if(strtoupper($_SESSION["ls_gestor"])==strtoupper("postgre")) $this->fetcharray = @pg_field_type($rs_data,$field);
			return $this->fetcharray;
		}
	}// end function

	function free_result ($result)  
	{
		////////////////////////////////////////////////////////////////////////////////////////////
		// Function:   	free_result
		// Description : Libera de la memoria el resultado de la ejecucion del metodo select
		// Arguments:
		//			     $result->Resulset obtenido del metodo select.
		////////////////////////////////////////////////////////////////////////////////////////////		
			if(strtoupper($_SESSION["ls_gestor"])==strtoupper("mysql"))  @mysql_free_result($result);
			if(strtoupper($_SESSION["ls_gestor"])==strtoupper("postgre"))  @pg_free_result($result);
	}// end function
	
	function close () 
	{
		if(strtoupper($_SESSION["ls_gestor"])==strtoupper("mysql")) $this->closeid = @mysql_close($this->conn);
		if(strtoupper($_SESSION["ls_gestor"])==strtoupper("postgre")) $this->closeid =  @pg_close($this->conn);
		return $this->closeid;
	}// end function
	
	
	function obtener_datos($result)
	{
		////////////////////////////////////////////////////////////////////////////////////////////
		// Function:   	 obtener_datos
		// Description : Metodo que retorna una matriz datastore con los valores obtenidos de la ejecucion del metodo select
		// Arguments:
		//			     $result->Resulset obtenido del metodo select.
		////////////////////////////////////////////////////////////////////////////////////////////		
		switch ($_SESSION["ls_gestor"]) {
		   case 'MYSQL':
		   		$this->datastore->reset_ds();//Blanqueo la matriz $data.
				$numcolumn=@mysql_num_fields($result);//Obtengo el numero de columnas del resultado
				@mysql_data_seek( $result,0);//Devuelvo el puntero al comienzo
				while($row=@mysql_fetch_array($result))//Recorro los datos obtenidos
				{
					for ($i=0;$i<$numcolumn;$i++)//Tomo el valor por columna y lo inserto a la matriz $data
					{
						 $nombre = @mysql_field_name($result,$i);
						 $valor  = $row[$nombre];
						 $this->datastore->insertRow($nombre,$valor);					   
					}									   	 
				}
				break;
			case 'POSTGRE':
		   		$this->datastore->reset_ds();//Blanqueo la matriz $data.
				$filas = @pg_num_rows($result);
				$numcolumn = @pg_num_fields($result);
				$this->datastore->resetds(@pg_field_name($result,0));//Blanqueo la matriz $data.
				for ($j=0; $j < $filas; $j++) 
			   {
				   	for($i=0;$i<$numcolumn;$i++)
					{						
						$nombre = @pg_field_name($result,$i);
						$valor  = @pg_fetch_result($result, $j,$i);
						$this->datastore->insertRow($nombre,$valor);
					}				    
				}
			break;
			}
	return $this->datastore->data;	
	}// end function
	function obtener_new_datos($result,$column_ant)
	{
		////////////////////////////////////////////////////////////////////////////////////////////
		// Function:   	 obtener_new_datos
		// Description : Metodo que retorna una matriz datastore con los valores obtenidos de la ejecucion del metodo select y blanque un datastore llenado anteriormente
		// Arguments:
		//			     $result->    Resulset obtenido del metodo select.
		//               $column_ant->Columna de referencia del datastore creado anteriormente.  
		////////////////////////////////////////////////////////////////////////////////////////////		
		switch ($_SESSION["ls_gestor"]) {
		   case 'MYSQL':
		   		$this->datastore->reset_ds();//Blanqueo la matriz $data.
				$numcolumn=@mysql_num_fields($result);//Obtengo el numero de columnas del resultado
				@mysql_data_seek( $result,0);//Devuelvo el puntero al comienzo
				while($row=@mysql_fetch_array($result))//Recorro los datos obtenidos
				{
					for ($i=0;$i<$numcolumn;$i++)//Tomo el valor por columna y lo inserto a la matriz $data
					{
						 $nombre = @mysql_field_name($result,$i);
						 $valor  = $row[$nombre];
						 $this->datastore->insertRow($nombre,$valor);					   
					}									   	 
				}
				break;
			case 'POSTGRE':
				$filas = @pg_num_rows($result);
				$numcolumn = @pg_num_fields($result);
				$this->datastore->reset_ds();//Blanqueo la matriz $data.
				for ($j=0; $j < $filas; $j++) 
			   {
				   	for($i=0;$i<$numcolumn;$i++)
					{						
						$nombre = @pg_field_name($result,$i);
						$valor  = @pg_fetch_result($result, $j,$i);
						$this->datastore->insertRow($nombre,$valor);
					}				    
				}
			break;
		}
		return $this->datastore->data;	
	}// end function
		
	function begin_transaction()
	{
		////////////////////////////////////////////////////////////////////////////////////////////
		// Function:   	begin_transaction
		// Description : Metodo que inicia una transaccion SQL
		////////////////////////////////////////////////////////////////////////////////////////////		
		switch ($_SESSION["ls_gestor"]) {
		   case 'MYSQL':
				@mysql_query("BEGIN", $this->conn);
				break;
 		   case 'POSTGRE':
		   		@pg_query($this->conn, "begin");
		   		break;
		}		
	}// end function
	
	function commit()
	{
		////////////////////////////////////////////////////////////////////////////////////////////
		// Function:   	commit
		// Description : Realiza el cierre satisfactorio de la transaccion.Depende de la ejecucion anterior del begin_transaction
		////////////////////////////////////////////////////////////////////////////////////////////				
		switch ($_SESSION["ls_gestor"]) {
		   case 'MYSQL':
				@mysql_query("COMMIT", $this->conn);
				break;
 		   case 'POSTGRE':
		   		@pg_query($this->conn, "COMMIT");
		   		break;
		}		
	}// end function
	
	function rollback()
	{
		////////////////////////////////////////////////////////////////////////////////////////////
		// Function:   	commit
		// Description : Realiza el aborto o reverso de la transaccion ejecutada(INSERT,UPDATE,DELETE).Depende de la ejecucion anterior del begin_transaction
		////////////////////////////////////////////////////////////////////////////////////////////				
		switch ($_SESSION["ls_gestor"]) {
		   case 'MYSQL':
				@mysql_query("ROLLBACK", $this->conn);
				break;
 		   case 'POSTGRE':
		   		@pg_query($this->conn, "ROLLBACK");
		   		break;
		}		
	}	// end function
} // end class
?>