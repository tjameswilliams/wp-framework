<?
/**
 * dbHelper class
 * 
 * This is a helper class for writing native SQL queries when creating very custom functionality
 * @package WordPress::Custom Theme Framework
 * @author Tim Williams
 */
class dbHelper {
	
	// --> optionally, while testing, set this to true to log queries
	public $log_query = false;
	public $log_errors = false;
	
	/**
	 * __call function
	 *
	 * Simply runs the SQL of the 'method' called, simply add a new query to create a new method
	 * any method with 'get' will return database results
	 * @return bool if method is NOT get, array if it is
	 * @author Tim Williams
	 */
	public function __call($method, $args)
	{
		global $wpdb;
		
		if( method_exists($this,$method) ) // -> real methods override ephemeral methods
			return call_user_func_array(array($this, $method), $args);

		if( !in_array($method,array_keys($this->SQL)) )
			throw( new Exception(__CLASS__." :: Method does not exist: ".$method));

		// --> deflate json
		if( !empty($args) )
		{
			array_walk($args, function(&$value,$key) {
				if( is_array($value) || is_object($value) )
					$value = json_encode($value);
			});
		}

		$get_results = strpos($method,'get') !== false ? true : false;

		if( property_exists( $this, 'SQL_PREPS' ) )
		{
			$SQL = $this->_prepSql($method);
		}
		else
		{
			$SQL = $this->SQL[$method];
		}
		
		$wpdb->query($wpdb->prepare($SQL,$args), ARRAY_N);
		
		$this->last_query = $wpdb->last_query;
		
		if( $this->log_query ) {
			$log = new MyLogPHP($_SERVER['DOCUMENT_ROOT'].'/storage/db.log.csv');
			$log->info($this->last_query,'db_query');
		}
		
		if( !empty($wpdb->last_error) ) {
			$this->error = $wpdb->last_error;
			if( $this->log_errors ) {
				$log = new MyLogPHP($_SERVER['DOCUMENT_ROOT'].'/storage/db.log.csv');
				$log->error($this->error,'db_error');
			}
		}
		
		$result = $wpdb->last_result;
		
		// --> inflate json
		if( $result !== true && strpos($method,'get') !== false )
		{
			$result =  $this->_inflateJson($result);
			if( !empty($result) && strpos(strtolower($method),'single') !== false )
				$result = $result[0];
		}
		else
		{
			$this->insert_id = $wpdb->insert_id;
		}

		return $result;
	}

	function _inflateJson($results)
	{
		if( !empty($results) )
		{
			$inflated = array();
			foreach($results as $result)
			{
				array_walk($result, function(&$value,$key) {
					if( strpos($key,'json') !== false )
					{
						$value = json_decode($value);
					}
				});
				$inflated[] = $result;
			}
		}
		return isset($inflated) ? $inflated : $results;
	}

	/**
	 * prepSql function
	 *
	 * @param  string  $sql_stmt  the SQL to prep
	 *
	 * @return string, prepped SQL statement
	 * @author Tim Williams
	 */
	function _prepSql($sql_stmt)
	{
		return str_replace( array_keys($this->SQL_PREPS), array_values($this->SQL_PREPS), $this->SQL[$sql_stmt] );
	}
	
	
}