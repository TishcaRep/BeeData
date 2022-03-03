<?php

/**********************************************************************
*  Author: Juergen Bouché (jbouche@nurfuerspam.de). Modified by Sunaryo Hadi
*  Web...: http://www.juergenbouche.de
*  Name..: ezSQL_mysqli
*  Desc..: mySQLi component (part of ezSQL database abstraction library)
*
*/

/**********************************************************************
*  ezSQL error strings - mySQLi
*/
  
global $ezsql_mysqli_str;

$ezsql_mysqli_str = array
(
	1 => 'Require $dbuser and $dbpassword to connect to a database server',
	2 => 'Error establishing mySQLi database connection. Correct user/password? Correct hostname? Database server running?',
	3 => 'Require $dbname to select a database',
	4 => 'mySQLi database connection is not active',
	5 => 'Unexpected error while trying to select database'
);

/**********************************************************************
*  ezSQL Database specific class - mySQLi
*/

if ( ! function_exists ('mysqli_connect') ) die('<b>Fatal Error:</b> ezSQL_mysql requires mySQLi Lib to be compiled and or linked in to the PHP engine');
if ( ! class_exists ('ezSQLcore') ) die('<b>Fatal Error:</b> ezSQL_mysql requires ezSQLcore (ez_sql_core.php) to be included/loaded before it can be used');

class ezSQL_mysqli extends ezSQLcore
{

	var $dbuser = false;
	var $dbpassword = false;
	var $dbname = false;
	var $dbhost = false;
	var $dbport = false;
	var $encoding = false;
	var $rows_affected = false;

	/**
	 * Format specifiers for DB columns. Columns not listed here default to %s. Initialized during WP load.
	 *
	 * Keys are column names, values are format types: 'ID' => '%d'
	 *
	 * @since 2.8.0
	 * @see wpdb::prepare()
	 * @see wpdb::insert()
	 * @see wpdb::update()
	 * @see wpdb::delete()
	 * @see wp_set_wpdb_vars()
	 * @access public
	 * @var array
	 */
	public $field_types = array();

	/**********************************************************************
	*  Constructor - allow the user to perform a quick connect at the
	*  same time as initialising the ezSQL_mysqli class
	*/

	function __construct($dbuser='', $dbpassword='', $dbname='', $dbhost='localhost', $encoding='')
	{
		$this->dbuser = $dbuser;
		$this->dbpassword = $dbpassword;
		$this->dbname = $dbname;
		list( $this->dbhost, $this->dbport ) = $this->get_host_port( $dbhost, 3306 );
		$this->encoding = $encoding;
	}

	/**********************************************************************
	*  Short hand way to connect to mySQL database server
	*  and select a mySQL database at the same time
	*/

	function quick_connect($dbuser='', $dbpassword='', $dbname='', $dbhost='localhost', $dbport='3306', $encoding='')
	{
		$return_val = false;
		if ( ! $this->connect($dbuser, $dbpassword, $dbhost, $dbport) ) ;
		else if ( ! $this->select($dbname,$encoding) ) ;
		else $return_val = true;
		return $return_val;
	}

	/**********************************************************************
	*  Try to connect to mySQL database server
	*/

	function connect($dbuser='', $dbpassword='', $dbhost='localhost', $dbport=false)
	{
		global $ezsql_mysqli_str; $return_val = false;
		
		// Keep track of how long the DB takes to connect
		$this->timer_start('db_connect_time');
		
		// If port not specified (new connection issued), get it
		if( ! $dbport ) {
			list( $dbhost, $dbport ) = $this->get_host_port( $dbhost, 3306 );
		}
		
		// Must have a user and a password
		if ( ! $dbuser )
		{
			$this->register_error($ezsql_mysqli_str[1].' in '.__FILE__.' on line '.__LINE__);
			$this->show_errors ? trigger_error($ezsql_mysqli_str[1],E_USER_WARNING) : null;
		}
		// Try to establish the server database handle
		else
		{
			$this->dbh = new mysqli($dbhost,$dbuser,$dbpassword, '', $dbport);
			// Check for connection problem
			if( $this->dbh->connect_errno )
			{
				$this->register_error($ezsql_mysqli_str[2].' in '.__FILE__.' on line '.__LINE__);
				$this->show_errors ? trigger_error($ezsql_mysqli_str[2],E_USER_WARNING) : null;
			}
			else
			{
				$this->dbuser = $dbuser;
				$this->dbpassword = $dbpassword;
				$this->dbhost = $dbhost;
				$this->dbport = $dbport;
				$return_val = true;

				$this->conn_queries = 0;
			}
		}

		return $return_val;
	}

	/**********************************************************************
	*  Try to select a mySQL database
	*/

	function select($dbname='', $encoding='')
	{
		global $ezsql_mysqli_str; $return_val = false;

		// Must have a database name
		if ( ! $dbname )
		{
			$this->register_error($ezsql_mysqli_str[3].' in '.__FILE__.' on line '.__LINE__);
			$this->show_errors ? trigger_error($ezsql_mysqli_str[3],E_USER_WARNING) : null;
		}

		// Must have an active database connection
		else if ( ! $this->dbh )
		{
			$this->register_error($ezsql_mysqli_str[4].' in '.__FILE__.' on line '.__LINE__);
			$this->show_errors ? trigger_error($ezsql_mysqli_str[4],E_USER_WARNING) : null;
		}

		// Try to connect to the database
		else if ( !@$this->dbh->select_db($dbname) )
		{
			// Try to get error supplied by mysql if not use our own
			if ( !$str = @$this->dbh->error)
				  $str = $ezsql_mysqli_str[5];

			$this->register_error($str.' in '.__FILE__.' on line '.__LINE__);
			$this->show_errors ? trigger_error($str,E_USER_WARNING) : null;
		}
		else
		{
			$this->dbname = $dbname;
			if($encoding!='')
			{
				$encoding = strtolower(str_replace("-","",$encoding));
				$charsets = array();
				$result = $this->dbh->query("SHOW CHARACTER SET");
				while($row = $result->fetch_array(MYSQLI_ASSOC))
				{
					$charsets[] = $row["Charset"];
				}
				if(in_array($encoding,$charsets)){
					$this->dbh->query("SET NAMES '".$encoding."'");						
				}
			}
			
			$return_val = true;
		}

		return $return_val;
	}

	/**********************************************************************
	*  Format a mySQL string correctly for safe mySQL insert
	*  (no mater if magic quotes are on or not)
	*/

	function escape($str)
	{
		// If there is no existing database connection then try to connect
		if ( ! isset($this->dbh) || ! $this->dbh )
		{
			$this->connect($this->dbuser, $this->dbpassword, $this->dbhost, $this->dbport);
			$this->select($this->dbname, $this->encoding);
		}

		return $this->dbh->escape_string(stripslashes($str));
	}

	/**********************************************************************
	*  Return mySQL specific system date syntax
	*  i.e. Oracle: SYSDATE Mysql: NOW()
	*/

	function sysdate()
	{
		return 'NOW()';
	}

	/**********************************************************************
	*  Perform mySQL query and try to determine result value
	*/

	function query($query)
	{

		// This keeps the connection alive for very long running scripts
		if ( $this->num_queries >= 500 )
		{
			$this->disconnect();
			$this->quick_connect($this->dbuser,$this->dbpassword,$this->dbname,$this->dbhost,$this->dbport,$this->encoding);
		}

		// Initialise return
		$return_val = 0;

		// Flush cached values..
		$this->flush();

		// For reg expressions
		$query = trim($query);

		// Log how the function was called
		$this->func_call = "\$db->query(\"$query\")";

		// Keep track of the last query for debug..
		$this->last_query = $query;

		// Count how many queries there have been
		$this->count(true, true);

		// Start timer
		$this->timer_start($this->num_queries);

		// Use core file cache function
		if ( $cache = $this->get_cache($query) )
		{
			// Keep tack of how long all queries have taken
			$this->timer_update_global($this->num_queries);

			// Trace all queries
			if ( $this->use_trace_log )
			{
				$this->trace_log[] = $this->debug(false);
			}
			
			return $cache;
		}

		// If there is no existing database connection then try to connect
		if ( ! isset($this->dbh) || ! $this->dbh )
		{
			$this->connect($this->dbuser, $this->dbpassword, $this->dbhost, $this->dbport);
			$this->select($this->dbname,$this->encoding);
			// No existing connection at this point means the server is unreachable
			if ( ! isset($this->dbh) || ! $this->dbh || $this->dbh->connect_errno )
				return false;
		}

		// Perform the query via std mysql_query function..
		$this->result = @$this->dbh->query($query);

		// If there is an error then take note of it..
		if ( $str = @$this->dbh->error )
		{
			$this->register_error($str);
			$this->show_errors ? trigger_error($str,E_USER_WARNING) : null;
			return false;
		}

		// Query was an insert, delete, update, replace
		if ( preg_match("/^(insert|delete|update|replace|truncate|drop|create|alter|begin|commit|rollback|set|lock|unlock|call)/i",$query) )
		{
			$is_insert = true;
			$this->rows_affected = @$this->dbh->affected_rows;

			// Take note of the insert_id
			if ( preg_match("/^(insert|replace)\s+/i",$query) )
			{
				$this->insert_id = @$this->dbh->insert_id;
			}

			// Return number fo rows affected
			$return_val = $this->rows_affected;
		}
		// Query was a select
		else
		{
			$is_insert = false;

			// Take note of column info
			$i=0;
			while ($i < @$this->result->field_count)
			{
				$this->col_info[$i] = @$this->result->fetch_field();
				$i++;
			}

			// Store Query Results
			$num_rows=0;
			while ( $row = @$this->result->fetch_object() )
			{
				// Store relults as an objects within main array
				$this->last_result[$num_rows] = $row;
				$num_rows++;
			}

			@$this->result->free_result();

			// Log number of rows the query returned
			$this->num_rows = $num_rows;

			// Return number of rows selected
			$return_val = $this->num_rows;
		}

		// disk caching of queries
		$this->store_cache($query,$is_insert);

		// If debug ALL queries
		$this->trace || $this->debug_all ? $this->debug() : null ;

		// Keep tack of how long all queries have taken
		$this->timer_update_global($this->num_queries);

		// Trace all queries
		if ( $this->use_trace_log )
		{
			$this->trace_log[] = $this->debug(false);
		}

		return $return_val;

	}
	
	/**********************************************************************
	*  Close the active mySQLi connection
	*/

	function disconnect()
	{
		$this->conn_queries = 0;
		@$this->dbh->close();
	}

	/**
	 * Prepares a SQL query for safe execution. Uses sprintf()-like syntax.
	 *
	 * The following directives can be used in the query format string:
	 *   %d (integer)
	 *   %f (float)
	 *   %s (string)
	 *   %% (literal percentage sign - no argument needed)
	 *
	 * All of %d, %f, and %s are to be left unquoted in the query string and they need an argument passed for them.
	 * Literals (%) as parts of the query must be properly written as %%.
	 *
	 * This function only supports a small subset of the sprintf syntax; it only supports %d (integer), %f (float), and %s (string).
	 * Does not support sign, padding, alignment, width or precision specifiers.
	 * Does not support argument numbering/swapping.
	 *
	 * May be called like {@link http://php.net/sprintf sprintf()} or like {@link http://php.net/vsprintf vsprintf()}.
	 *
	 * Both %d and %s should be left unquoted in the query string.
	 *
	 *     wpdb::prepare( "SELECT * FROM `table` WHERE `column` = %s AND `field` = %d", 'foo', 1337 )
	 *     wpdb::prepare( "SELECT DATE_FORMAT(`field`, '%%c') FROM `table` WHERE `column` = %s", 'foo' );
	 *
	 * @link http://php.net/sprintf Description of syntax.
	 * @since 2.3.0
	 *
	 * @param string      $query    Query statement with sprintf()-like placeholders
	 * @param array|mixed $args     The array of variables to substitute into the query's placeholders if being called like
	 *                              {@link http://php.net/vsprintf vsprintf()}, or the first variable to substitute into the query's placeholders if
	 *                              being called like {@link http://php.net/sprintf sprintf()}.
	 * @param mixed       $args,... further variables to substitute into the query's placeholders if being called like
	 *                              {@link http://php.net/sprintf sprintf()}.
	 * @return string|void Sanitized query string, if there is a query to prepare.
	 */
	public function prepare( $strquery, $args ) {
		if ( is_null( $strquery ) )
			return;

		// This is not meant to be foolproof -- but it will catch obviously incorrect usage.
		if ( strpos( $strquery, '%' ) === false ) {
			$this->show_errors ? trigger_error("The query argument of db::prepare() must have a placeholder.",E_USER_WARNING) : null;

		}

		$args = func_get_args();
		array_shift( $args );
		// If args were passed as an array (as in vsprintf), move them up
		if ( isset( $args[0] ) && is_array($args[0]) )
			$args = $args[0];
		$strquery = str_replace( "'%s'", '%s', $strquery ); // in case someone mistakenly already singlequoted it
		$strquery = str_replace( '"%s"', '%s', $strquery ); // doublequote unquoting
		$strquery = preg_replace( '|(?<!%)%f|' , '%F', $strquery ); // Force floats to be locale unaware
		$strquery = preg_replace( '|(?<!%)%s|', "'%s'", $strquery ); // quote the strings, avoiding escaped strings like %%s
		array_walk( $args, array( $this, 'escape_by_ref' ) ); // this has been escaped by ezmysqli
		return @vsprintf( $strquery, $args );
	}

	/**
	 * Escapes content by reference for insertion into the database, for security
	 *
	 * @uses wpdb::_real_escape()
	 *
	 * @since 2.3.0
	 *
	 * @param string $string to escape
	 */
	public function escape_by_ref( &$string ) {
		if ( ! is_float( $string ) )
			$string = $this->escape($string);
			// $string = mysqli_real_escape_string( $this->dbh, $string );
	}

	/* Funciones tomadas del wpdb -> (insert, update, delete) */

	/**
	 * Insert a row into a table.
	 *
	 *     wpdb::insert( 'table', array( 'column' => 'foo', 'field' => 'bar' ) )
	 *     wpdb::insert( 'table', array( 'column' => 'foo', 'field' => 1337 ), array( '%s', '%d' ) )
	 *
	 * @since 2.5.0
	 * @see wpdb::prepare()
	 * @see wpdb::$field_types
	 * @see wp_set_wpdb_vars()
	 *
	 * @param string       $table  Table name
	 * @param array        $data   Data to insert (in column => value pairs).
	 *                             Both $data columns and $data values should be "raw" (neither should be SQL escaped).
	 *                             Sending a null value will cause the column to be set to NULL - the corresponding format is ignored in this case.
	 * @param array|string $format Optional. An array of formats to be mapped to each of the value in $data.
	 *                             If string, that format will be used for all of the values in $data.
	 *                             A format is one of '%d', '%f', '%s' (integer, float, string).
	 *                             If omitted, all values in $data will be treated as strings unless otherwise specified in wpdb::$field_types.
	 * @return int|false The number of rows inserted, or false on error.
	 */
	public function insert( $table, $data, $format = null ) {
		return $this->_insert_replace_helper( $table, $data, $format, 'INSERT' );
	}

	/**
	 * Replace a row into a table.
	 *
	 *     wpdb::replace( 'table', array( 'column' => 'foo', 'field' => 'bar' ) )
	 *     wpdb::replace( 'table', array( 'column' => 'foo', 'field' => 1337 ), array( '%s', '%d' ) )
	 *
	 * @since 3.0.0
	 * @see wpdb::prepare()
	 * @see wpdb::$field_types
	 * @see wp_set_wpdb_vars()
	 *
	 * @param string       $table  Table name
	 * @param array        $data   Data to insert (in column => value pairs).
	 *                             Both $data columns and $data values should be "raw" (neither should be SQL escaped).
	 *                             Sending a null value will cause the column to be set to NULL - the corresponding format is ignored in this case.
	 * @param array|string $format Optional. An array of formats to be mapped to each of the value in $data.
	 *                             If string, that format will be used for all of the values in $data.
	 *                             A format is one of '%d', '%f', '%s' (integer, float, string).
	 *                             If omitted, all values in $data will be treated as strings unless otherwise specified in wpdb::$field_types.
	 * @return int|false The number of rows affected, or false on error.
	 */
	public function replace( $table, $data, $format = null ) {
		return $this->_insert_replace_helper( $table, $data, $format, 'REPLACE' );
	}

	/**
	 * Helper function for insert and replace.
	 *
	 * Runs an insert or replace query based on $type argument.
	 *
	 * @access private
	 * @since 3.0.0
	 * @see wpdb::prepare()
	 * @see wpdb::$field_types
	 * @see wp_set_wpdb_vars()
	 *
	 * @param string       $table  Table name
	 * @param array        $data   Data to insert (in column => value pairs).
	 *                             Both $data columns and $data values should be "raw" (neither should be SQL escaped).
	 *                             Sending a null value will cause the column to be set to NULL - the corresponding format is ignored in this case.
	 * @param array|string $format Optional. An array of formats to be mapped to each of the value in $data.
	 *                             If string, that format will be used for all of the values in $data.
	 *                             A format is one of '%d', '%f', '%s' (integer, float, string).
	 *                             If omitted, all values in $data will be treated as strings unless otherwise specified in wpdb::$field_types.
	 * @param string $type         Optional. What type of operation is this? INSERT or REPLACE. Defaults to INSERT.
	 * @return int|false The number of rows affected, or false on error.
	 */
	function _insert_replace_helper( $table, $data, $format = null, $type = 'INSERT' ) {
		$this->insert_id = 0;

		if ( ! in_array( strtoupper( $type ), array( 'REPLACE', 'INSERT' ) ) ) {
			return false;
		}

		$data = $this->process_fields( $table, $data, $format );
		if ( false === $data ) {
			return false;
		}

		$formats = $values = array();
		foreach ( $data as $value ) {
			if ( is_null( $value['value'] ) ) {
				$formats[] = 'NULL';
				continue;
			}

			$formats[] = $value['format'];
			$values[]  = $value['value'];
		}

		$fields  = '`' . implode( '`, `', array_keys( $data ) ) . '`';
		$formats = implode( ', ', $formats );

		$sql = "$type INTO `$table` ($fields) VALUES ($formats)";

		$this->check_current_query = false;
		return $this->query( $this->prepare( $sql, $values ) );
	}

	/**
	 * Update a row in the table
	 *
	 *     wpdb::update( 'table', array( 'column' => 'foo', 'field' => 'bar' ), array( 'ID' => 1 ) )
	 *     wpdb::update( 'table', array( 'column' => 'foo', 'field' => 1337 ), array( 'ID' => 1 ), array( '%s', '%d' ), array( '%d' ) )
	 *
	 * @since 2.5.0
	 * @see wpdb::prepare()
	 * @see wpdb::$field_types
	 * @see wp_set_wpdb_vars()
	 *
	 * @param string       $table        Table name
	 * @param array        $data         Data to update (in column => value pairs).
	 *                                   Both $data columns and $data values should be "raw" (neither should be SQL escaped).
	 *                                   Sending a null value will cause the column to be set to NULL - the corresponding
	 *                                   format is ignored in this case.
	 * @param array        $where        A named array of WHERE clauses (in column => value pairs).
	 *                                   Multiple clauses will be joined with ANDs.
	 *                                   Both $where columns and $where values should be "raw".
	 *                                   Sending a null value will create an IS NULL comparison - the corresponding format will be ignored in this case.
	 * @param array|string $format       Optional. An array of formats to be mapped to each of the values in $data.
	 *                                   If string, that format will be used for all of the values in $data.
	 *                                   A format is one of '%d', '%f', '%s' (integer, float, string).
	 *                                   If omitted, all values in $data will be treated as strings unless otherwise specified in wpdb::$field_types.
	 * @param array|string $where_format Optional. An array of formats to be mapped to each of the values in $where.
	 *                                   If string, that format will be used for all of the items in $where.
	 *                                   A format is one of '%d', '%f', '%s' (integer, float, string).
	 *                                   If omitted, all values in $where will be treated as strings.
	 * @return int|false The number of rows updated, or false on error.
	 */
	public function update( $table, $data, $where, $format = null, $where_format = null ) {
		if ( ! is_array( $data ) || ! is_array( $where ) ) {
			return false;
		}

		$data = $this->process_fields( $table, $data, $format );
		if ( false === $data ) {
			return false;
		}
		$where = $this->process_fields( $table, $where, $where_format );
		if ( false === $where ) {
			return false;
		}

		$fields = $conditions = $values = array();
		foreach ( $data as $field => $value ) {
			if ( is_null( $value['value'] ) ) {
				$fields[] = "`$field` = NULL";
				continue;
			}

			$fields[] = "`$field` = " . $value['format'];
			$values[] = $value['value'];
		}
		foreach ( $where as $field => $value ) {
			if ( is_null( $value['value'] ) ) {
				$conditions[] = "`$field` IS NULL";
				continue;
			}

			$conditions[] = "`$field` = " . $value['format'];
			$values[] = $value['value'];
		}

		$fields = implode( ', ', $fields );
		$conditions = implode( ' AND ', $conditions );

		$sql = "UPDATE `$table` SET $fields WHERE $conditions";

		$this->check_current_query = false;
		return $this->query( $this->prepare( $sql, $values ) );
	}

	/**
	 * Delete a row in the table
	 *
	 *     wpdb::delete( 'table', array( 'ID' => 1 ) )
	 *     wpdb::delete( 'table', array( 'ID' => 1 ), array( '%d' ) )
	 *
	 * @since 3.4.0
	 * @see wpdb::prepare()
	 * @see wpdb::$field_types
	 * @see wp_set_wpdb_vars()
	 *
	 * @param string       $table        Table name
	 * @param array        $where        A named array of WHERE clauses (in column => value pairs).
	 *                                   Multiple clauses will be joined with ANDs.
	 *                                   Both $where columns and $where values should be "raw".
	 *                                   Sending a null value will create an IS NULL comparison - the corresponding format will be ignored in this case.
	 * @param array|string $where_format Optional. An array of formats to be mapped to each of the values in $where.
	 *                                   If string, that format will be used for all of the items in $where.
	 *                                   A format is one of '%d', '%f', '%s' (integer, float, string).
	 *                                   If omitted, all values in $where will be treated as strings unless otherwise specified in wpdb::$field_types.
	 * @return int|false The number of rows updated, or false on error.
	 */
	public function delete( $table, $where, $where_format = null ) {
		if ( ! is_array( $where ) ) {
			return false;
		}

		$where = $this->process_fields( $table, $where, $where_format );
		if ( false === $where ) {
			return false;
		}

		$conditions = $values = array();
		foreach ( $where as $field => $value ) {
			if ( is_null( $value['value'] ) ) {
				$conditions[] = "`$field` IS NULL";
				continue;
			}

			$conditions[] = "`$field` = " . $value['format'];
			$values[] = $value['value'];
		}

		$conditions = implode( ' AND ', $conditions );

		$sql = "DELETE FROM `$table` WHERE $conditions";

		$this->check_current_query = false;
		return $this->query( $this->prepare( $sql, $values ) );
	}

	/**
	 * Processes arrays of field/value pairs and field formats.
	 *
	 * This is a helper method for wpdb's CRUD methods, which take field/value
	 * pairs for inserts, updates, and where clauses. This method first pairs
	 * each value with a format. Then it determines the charset of that field,
	 * using that to determine if any invalid text would be stripped. If text is
	 * stripped, then field processing is rejected and the query fails.
	 *
	 * @since 4.2.0
	 * @access protected
	 *
	 * @param string $table  Table name.
	 * @param array  $data   Field/value pair.
	 * @param mixed  $format Format for each field.
	 * @return array|false Returns an array of fields that contain paired values
	 *                    and formats. Returns false for invalid values.
	 */
	protected function process_fields( $table, $data, $format ) {
		$data = $this->process_field_formats( $data, $format );
		if ( false === $data ) {
			return false;
		}

		$data = $this->process_field_charsets( $data, $table );
		if ( false === $data ) {
			return false;
		}

		$data = $this->process_field_lengths( $data, $table );
		if ( false === $data ) {
			return false;
		}

		$converted_data = $this->strip_invalid_text( $data );

		if ( $data !== $converted_data ) {
			return false;
		}

		return $data;
	}

	/**
	 * Prepares arrays of value/format pairs as passed to wpdb CRUD methods.
	 *
	 * @since 4.2.0
	 * @access protected
	 *
	 * @param array $data   Array of fields to values.
	 * @param mixed $format Formats to be mapped to the values in $data.
	 * @return array Array, keyed by field names with values being an array
	 *               of 'value' and 'format' keys.
	 */
	protected function process_field_formats( $data, $format ) {
		$formats = $original_formats = (array) $format;

		foreach ( $data as $field => $value ) {
			$value = array(
				'value'  => $value,
				'format' => '%s',
			);

			if ( ! empty( $format ) ) {
				$value['format'] = array_shift( $formats );
				if ( ! $value['format'] ) {
					$value['format'] = reset( $original_formats );
				}
			} elseif ( isset( $this->field_types[ $field ] ) ) {
				$value['format'] = $this->field_types[ $field ];
			}

			$data[ $field ] = $value;
		}

		return $data;
	}

	/**
	 * Adds field charsets to field/value/format arrays generated by
	 * the wpdb::process_field_formats() method.
	 *
	 * @since 4.2.0
	 * @access protected
	 *
	 * @param array  $data  As it comes from the wpdb::process_field_formats() method.
	 * @param string $table Table name.
	 * @return array|false The same array as $data with additional 'charset' keys.
	 */
	protected function process_field_charsets( $data, $table ) {
		foreach ( $data as $field => $value ) {
			if ( '%d' === $value['format'] || '%f' === $value['format'] ) {
				/*
				 * We can skip this field if we know it isn't a string.
				 * This checks %d/%f versus ! %s because its sprintf() could take more.
				 */
				$value['charset'] = false;
			} else {
				$value['charset'] = $this->get_col_charset( $table, $field );
			}

			$data[ $field ] = $value;
		}

		return $data;
	}

	/**
	 * For string fields, record the maximum string length that field can safely save.
	 *
	 * @since 4.2.1
	 * @access protected
	 *
	 * @param array  $data  As it comes from the wpdb::process_field_charsets() method.
	 * @param string $table Table name.
	 * @return array|false The same array as $data with additional 'length' keys, or false if
	 *                     any of the values were too long for their corresponding field.
	 */
	protected function process_field_lengths( $data, $table ) {
		foreach ( $data as $field => $value ) {
			if ( '%d' === $value['format'] || '%f' === $value['format'] ) {
				/*
				 * We can skip this field if we know it isn't a string.
				 * This checks %d/%f versus ! %s because its sprintf() could take more.
				 */
				$value['length'] = false;
			} else {
				$value['length'] = $this->get_col_length( $table, $field );
			}

			$data[ $field ] = $value;
		}

		return $data;
	}

	/**
	 * Retrieves the character set for the given table.
	 *
	 * @since 4.2.0
	 * @access protected
	 *
	 * @param string $table Table name.
	 * @return string|WP_Error Table character set, WP_Error object if it couldn't be found.
	 */
	protected function get_table_charset( $table ) {
		$tablekey = strtolower( $table );

		/**
		 * Filters the table charset value before the DB is checked.
		 *
		 * Passing a non-null value to the filter will effectively short-circuit
		 * checking the DB for the charset, returning that value instead.
		 *
		 * @since 4.2.0
		 *
		 * @param string $charset The character set to use. Default null.
		 * @param string $table   The name of the table being checked.
		 */
		$charset = null;
		if ( null !== $charset ) {
			return $charset;
		}

		if ( isset( $this->table_charset[ $tablekey ] ) ) {
			return $this->table_charset[ $tablekey ];
		}

		$charsets = $columns = array();

		$table_parts = explode( '.', $table );
		$table = '`' . implode( '`.`', $table_parts ) . '`';
		$results = $this->get_results( "SHOW FULL COLUMNS FROM $table" );
		if ( ! $results ) {
			return new WP_Error( 'wpdb_get_table_charset_failure' );
		}

		foreach ( $results as $column ) {
			$columns[ strtolower( $column->Field ) ] = $column;
		}

		$this->col_meta[ $tablekey ] = $columns;

		foreach ( $columns as $column ) {
			if ( ! empty( $column->Collation ) ) {
				list( $charset ) = explode( '_', $column->Collation );

				// If the current connection can't support utf8mb4 characters, let's only send 3-byte utf8 characters.
				if ( 'utf8mb4' === $charset && ! $this->has_cap( 'utf8mb4' ) ) {
					$charset = 'utf8';
				}

				$charsets[ strtolower( $charset ) ] = true;
			}

			list( $type ) = explode( '(', $column->Type );

			// A binary/blob means the whole query gets treated like this.
			if ( in_array( strtoupper( $type ), array( 'BINARY', 'VARBINARY', 'TINYBLOB', 'MEDIUMBLOB', 'BLOB', 'LONGBLOB' ) ) ) {
				$this->table_charset[ $tablekey ] = 'binary';
				return 'binary';
			}
		}

		// utf8mb3 is an alias for utf8.
		if ( isset( $charsets['utf8mb3'] ) ) {
			$charsets['utf8'] = true;
			unset( $charsets['utf8mb3'] );
		}

		// Check if we have more than one charset in play.
		$count = count( $charsets );
		if ( 1 === $count ) {
			$charset = key( $charsets );
		} elseif ( 0 === $count ) {
			// No charsets, assume this table can store whatever.
			$charset = false;
		} else {
			// More than one charset. Remove latin1 if present and recalculate.
			unset( $charsets['latin1'] );
			$count = count( $charsets );
			if ( 1 === $count ) {
				// Only one charset (besides latin1).
				$charset = key( $charsets );
			} elseif ( 2 === $count && isset( $charsets['utf8'], $charsets['utf8mb4'] ) ) {
				// Two charsets, but they're utf8 and utf8mb4, use utf8.
				$charset = 'utf8';
			} else {
				// Two mixed character sets. ascii.
				$charset = 'ascii';
			}
		}

		$this->table_charset[ $tablekey ] = $charset;
		return $charset;
	}

	/**
	 * Retrieves the character set for the given column.
	 *
	 * @since 4.2.0
	 * @access public
	 *
	 * @param string $table  Table name.
	 * @param string $column Column name.
	 * @return string|false|WP_Error Column character set as a string. False if the column has no
	 *                               character set. WP_Error object if there was an error.
	 */
	public function get_col_charset( $table, $column ) {
		$tablekey = strtolower( $table );
		$columnkey = strtolower( $column );

		/**
		 * Filters the column charset value before the DB is checked.
		 *
		 * Passing a non-null value to the filter will short-circuit
		 * checking the DB for the charset, returning that value instead.
		 *
		 * @since 4.2.0
		 *
		 * @param string $charset The character set to use. Default null.
		 * @param string $table   The name of the table being checked.
		 * @param string $column  The name of the column being checked.
		 */
		$charset = null;
		if ( null !== $charset ) {
			return $charset;
		}

		// Skip this entirely if this isn't a MySQL database.
		if ( empty( $this->is_mysql ) ) {
			return false;
		}

		if ( empty( $this->table_charset[ $tablekey ] ) ) {
			// This primes column information for us.
			$table_charset = $this->get_table_charset( $table );
		}

		// If still no column information, return the table charset.
		if ( empty( $this->col_meta[ $tablekey ] ) ) {
			return $this->table_charset[ $tablekey ];
		}

		// If this column doesn't exist, return the table charset.
		if ( empty( $this->col_meta[ $tablekey ][ $columnkey ] ) ) {
			return $this->table_charset[ $tablekey ];
		}

		// Return false when it's not a string column.
		if ( empty( $this->col_meta[ $tablekey ][ $columnkey ]->Collation ) ) {
			return false;
		}

		list( $charset ) = explode( '_', $this->col_meta[ $tablekey ][ $columnkey ]->Collation );
		return $charset;
	}

	/**
	 * Retrieve the maximum string length allowed in a given column.
	 * The length may either be specified as a byte length or a character length.
	 *
	 * @since 4.2.1
	 * @access public
	 *
	 * @param string $table  Table name.
	 * @param string $column Column name.
	 * @return array|false|WP_Error array( 'length' => (int), 'type' => 'byte' | 'char' )
	 *                              false if the column has no length (for example, numeric column)
	 *                              WP_Error object if there was an error.
	 */
	public function get_col_length( $table, $column ) {
		$tablekey = strtolower( $table );
		$columnkey = strtolower( $column );

		// Skip this entirely if this isn't a MySQL database.
		if ( empty( $this->is_mysql ) ) {
			return false;
		}

		if ( empty( $this->col_meta[ $tablekey ] ) ) {
			// This primes column information for us.
			$table_charset = $this->get_table_charset( $table );
		}

		if ( empty( $this->col_meta[ $tablekey ][ $columnkey ] ) ) {
			return false;
		}

		$typeinfo = explode( '(', $this->col_meta[ $tablekey ][ $columnkey ]->Type );

		$type = strtolower( $typeinfo[0] );
		if ( ! empty( $typeinfo[1] ) ) {
			$length = trim( $typeinfo[1], ')' );
		} else {
			$length = false;
		}

		switch( $type ) {
			case 'char':
			case 'varchar':
				return array(
					'type'   => 'char',
					'length' => (int) $length,
				);

			case 'binary':
			case 'varbinary':
				return array(
					'type'   => 'byte',
					'length' => (int) $length,
				);

			case 'tinyblob':
			case 'tinytext':
				return array(
					'type'   => 'byte',
					'length' => 255,        // 2^8 - 1
				);

			case 'blob':
			case 'text':
				return array(
					'type'   => 'byte',
					'length' => 65535,      // 2^16 - 1
				);

			case 'mediumblob':
			case 'mediumtext':
				return array(
					'type'   => 'byte',
					'length' => 16777215,   // 2^24 - 1
				);

			case 'longblob':
			case 'longtext':
				return array(
					'type'   => 'byte',
					'length' => 4294967295, // 2^32 - 1
				);

			default:
				return false;
		}
	}

	/**
	 * Check if a string is ASCII.
	 *
	 * The negative regex is faster for non-ASCII strings, as it allows
	 * the search to finish as soon as it encounters a non-ASCII character.
	 *
	 * @since 4.2.0
	 * @access protected
	 *
	 * @param string $string String to check.
	 * @return bool True if ASCII, false if not.
	 */
	protected function check_ascii( $string ) {
		if ( function_exists( 'mb_check_encoding' ) ) {
			if ( mb_check_encoding( $string, 'ASCII' ) ) {
				return true;
			}
		} elseif ( ! preg_match( '/[^\x00-\x7F]/', $string ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Check if the query is accessing a collation considered safe on the current version of MySQL.
	 *
	 * @since 4.2.0
	 * @access protected
	 *
	 * @param string $query The query to check.
	 * @return bool True if the collation is safe, false if it isn't.
	 */
	protected function check_safe_collation( $query ) {
		if ( $this->checking_collation ) {
			return true;
		}

		// We don't need to check the collation for queries that don't read data.
		$query = ltrim( $query, "\r\n\t (" );
		if ( preg_match( '/^(?:SHOW|DESCRIBE|DESC|EXPLAIN|CREATE)\s/i', $query ) ) {
			return true;
		}

		// All-ASCII queries don't need extra checking.
		if ( $this->check_ascii( $query ) ) {
			return true;
		}

		$table = $this->get_table_from_query( $query );
		if ( ! $table ) {
			return false;
		}

		$this->checking_collation = true;
		$collation = $this->get_table_charset( $table );
		$this->checking_collation = false;

		// Tables with no collation, or latin1 only, don't need extra checking.
		if ( false === $collation || 'latin1' === $collation ) {
			return true;
		}

		$table = strtolower( $table );
		if ( empty( $this->col_meta[ $table ] ) ) {
			return false;
		}

		// If any of the columns don't have one of these collations, it needs more sanity checking.
		foreach ( $this->col_meta[ $table ] as $col ) {
			if ( empty( $col->Collation ) ) {
				continue;
			}

			if ( ! in_array( $col->Collation, array( 'utf8_general_ci', 'utf8_bin', 'utf8mb4_general_ci', 'utf8mb4_bin' ), true ) ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Strips any invalid characters based on value/charset pairs.
	 *
	 * @since 4.2.0
	 * @access protected
	 *
	 * @param array $data Array of value arrays. Each value array has the keys
	 *                    'value' and 'charset'. An optional 'ascii' key can be
	 *                    set to false to avoid redundant ASCII checks.
	 * @return array|WP_Error The $data parameter, with invalid characters removed from
	 *                        each value. This works as a passthrough: any additional keys
	 *                        such as 'field' are retained in each value array. If we cannot
	 *                        remove invalid characters, a WP_Error object is returned.
	 */
	protected function strip_invalid_text( $data ) {
		$db_check_string = false;

		foreach ( $data as &$value ) {
			$charset = $value['charset'];

			if ( is_array( $value['length'] ) ) {
				$length = $value['length']['length'];
				$truncate_by_byte_length = 'byte' === $value['length']['type'];
			} else {
				$length = false;
				// Since we have no length, we'll never truncate.
				// Initialize the variable to false. true would take us
				// through an unnecessary (for this case) codepath below.
				$truncate_by_byte_length = false;
			}

			// There's no charset to work with.
			if ( false === $charset ) {
				continue;
			}

			// Column isn't a string.
			if ( ! is_string( $value['value'] ) ) {
				continue;
			}

			$needs_validation = true;
			if (
				// latin1 can store any byte sequence
				'latin1' === $charset
			||
				// ASCII is always OK.
				( ! isset( $value['ascii'] ) && $this->check_ascii( $value['value'] ) )
			) {
				$truncate_by_byte_length = true;
				$needs_validation = false;
			}

			if ( $truncate_by_byte_length ) {
				mbstring_binary_safe_encoding();
				if ( false !== $length && strlen( $value['value'] ) > $length ) {
					$value['value'] = substr( $value['value'], 0, $length );
				}
				reset_mbstring_encoding();

				if ( ! $needs_validation ) {
					continue;
				}
			}

			// utf8 can be handled by regex, which is a bunch faster than a DB lookup.
			if ( ( 'utf8' === $charset || 'utf8mb3' === $charset || 'utf8mb4' === $charset ) && function_exists( 'mb_strlen' ) ) {
				$regex = '/
					(
						(?: [\x00-\x7F]                  # single-byte sequences   0xxxxxxx
						|   [\xC2-\xDF][\x80-\xBF]       # double-byte sequences   110xxxxx 10xxxxxx
						|   \xE0[\xA0-\xBF][\x80-\xBF]   # triple-byte sequences   1110xxxx 10xxxxxx * 2
						|   [\xE1-\xEC][\x80-\xBF]{2}
						|   \xED[\x80-\x9F][\x80-\xBF]
						|   [\xEE-\xEF][\x80-\xBF]{2}';

				if ( 'utf8mb4' === $charset ) {
					$regex .= '
						|    \xF0[\x90-\xBF][\x80-\xBF]{2} # four-byte sequences   11110xxx 10xxxxxx * 3
						|    [\xF1-\xF3][\x80-\xBF]{3}
						|    \xF4[\x80-\x8F][\x80-\xBF]{2}
					';
				}

				$regex .= '){1,40}                          # ...one or more times
					)
					| .                                  # anything else
					/x';
				$value['value'] = preg_replace( $regex, '$1', $value['value'] );


				if ( false !== $length && mb_strlen( $value['value'], 'UTF-8' ) > $length ) {
					$value['value'] = mb_substr( $value['value'], 0, $length, 'UTF-8' );
				}
				continue;
			}

			// We couldn't use any local conversions, send it to the DB.
			$value['db'] = $db_check_string = true;
		}
		unset( $value ); // Remove by reference.

		if ( $db_check_string ) {
			$queries = array();
			foreach ( $data as $col => $value ) {
				if ( ! empty( $value['db'] ) ) {
					// We're going to need to truncate by characters or bytes, depending on the length value we have.
					if ( 'byte' === $value['length']['type'] ) {
						// Using binary causes LEFT() to truncate by bytes.
						$charset = 'binary';
					} else {
						$charset = $value['charset'];
					}

					if ( $this->charset ) {
						$connection_charset = $this->charset;
					} else {
						if ( $this->use_mysqli ) {
							$connection_charset = mysqli_character_set_name( $this->dbh );
						} else {
							$connection_charset = mysql_client_encoding();
						}
					}

					if ( is_array( $value['length'] ) ) {
						$queries[ $col ] = $this->prepare( "CONVERT( LEFT( CONVERT( %s USING $charset ), %.0f ) USING $connection_charset )", $value['value'], $value['length']['length'] );
					} else if ( 'binary' !== $charset ) {
						// If we don't have a length, there's no need to convert binary - it will always return the same result.
						$queries[ $col ] = $this->prepare( "CONVERT( CONVERT( %s USING $charset ) USING $connection_charset )", $value['value'] );
					}

					unset( $data[ $col ]['db'] );
				}
			}

			$sql = array();
			foreach ( $queries as $column => $query ) {
				if ( ! $query ) {
					continue;
				}

				$sql[] = $query . " AS x_$column";
			}

			$this->check_current_query = false;
			$row = $this->get_row( "SELECT " . implode( ', ', $sql ), ARRAY_A );
			if ( ! $row ) {
				return new WP_Error( 'wpdb_strip_invalid_text_failure' );
			}

			foreach ( array_keys( $data ) as $column ) {
				if ( isset( $row["x_$column"] ) ) {
					$data[ $column ]['value'] = $row["x_$column"];
				}
			}
		}

		return $data;
	}

	/**
	 * Strips any invalid characters from the query.
	 *
	 * @since 4.2.0
	 * @access protected
	 *
	 * @param string $query Query to convert.
	 * @return string|WP_Error The converted query, or a WP_Error object if the conversion fails.
	 */
	protected function strip_invalid_text_from_query( $query ) {
		// We don't need to check the collation for queries that don't read data.
		$trimmed_query = ltrim( $query, "\r\n\t (" );
		if ( preg_match( '/^(?:SHOW|DESCRIBE|DESC|EXPLAIN|CREATE)\s/i', $trimmed_query ) ) {
			return $query;
		}

		$table = $this->get_table_from_query( $query );
		if ( $table ) {
			$charset = $this->get_table_charset( $table );

			// We can't reliably strip text from tables containing binary/blob columns
			if ( 'binary' === $charset ) {
				return $query;
			}
		} else {
			$charset = $this->charset;
		}

		$data = array(
			'value'   => $query,
			'charset' => $charset,
			'ascii'   => false,
			'length'  => false,
		);

		$data = $this->strip_invalid_text( array( $data ) );

		return $data[0]['value'];
	}

	/**
	 * Strips any invalid characters from the string for a given table and column.
	 *
	 * @since 4.2.0
	 * @access public
	 *
	 * @param string $table  Table name.
	 * @param string $column Column name.
	 * @param string $value  The text to check.
	 * @return string|WP_Error The converted string, or a WP_Error object if the conversion fails.
	 */
	public function strip_invalid_text_for_column( $table, $column, $value ) {
		if ( ! is_string( $value ) ) {
			return $value;
		}

		$charset = $this->get_col_charset( $table, $column );
		if ( ! $charset ) {
			// Not a string column.
			return $value;
		}

		$data = array(
			$column => array(
				'value'   => $value,
				'charset' => $charset,
				'length'  => $this->get_col_length( $table, $column ),
			)
		);

		$data = $this->strip_invalid_text( $data );

		return $data[ $column ]['value'];
	}

	/**
	 * Find the first table name referenced in a query.
	 *
	 * @since 4.2.0
	 * @access protected
	 *
	 * @param string $query The query to search.
	 * @return string|false $table The table name found, or false if a table couldn't be found.
	 */
	protected function get_table_from_query( $query ) {
		// Remove characters that can legally trail the table name.
		$query = rtrim( $query, ';/-#' );

		// Allow (select...) union [...] style queries. Use the first query's table name.
		$query = ltrim( $query, "\r\n\t (" );

		// Strip everything between parentheses except nested selects.
		$query = preg_replace( '/\((?!\s*select)[^(]*?\)/is', '()', $query );

		// Quickly match most common queries.
		if ( preg_match( '/^\s*(?:'
				. 'SELECT.*?\s+FROM'
				. '|INSERT(?:\s+LOW_PRIORITY|\s+DELAYED|\s+HIGH_PRIORITY)?(?:\s+IGNORE)?(?:\s+INTO)?'
				. '|REPLACE(?:\s+LOW_PRIORITY|\s+DELAYED)?(?:\s+INTO)?'
				. '|UPDATE(?:\s+LOW_PRIORITY)?(?:\s+IGNORE)?'
				. '|DELETE(?:\s+LOW_PRIORITY|\s+QUICK|\s+IGNORE)*(?:\s+FROM)?'
				. ')\s+((?:[0-9a-zA-Z$_.`-]|[\xC2-\xDF][\x80-\xBF])+)/is', $query, $maybe ) ) {
			return str_replace( '`', '', $maybe[1] );
		}

		// SHOW TABLE STATUS and SHOW TABLES
		if ( preg_match( '/^\s*(?:'
				. 'SHOW\s+TABLE\s+STATUS.+(?:LIKE\s+|WHERE\s+Name\s*=\s*)'
				. '|SHOW\s+(?:FULL\s+)?TABLES.+(?:LIKE\s+|WHERE\s+Name\s*=\s*)'
				. ')\W((?:[0-9a-zA-Z$_.`-]|[\xC2-\xDF][\x80-\xBF])+)\W/is', $query, $maybe ) ) {
			return str_replace( '`', '', $maybe[1] );
		}

		// Big pattern for the rest of the table-related queries.
		if ( preg_match( '/^\s*(?:'
				. '(?:EXPLAIN\s+(?:EXTENDED\s+)?)?SELECT.*?\s+FROM'
				. '|DESCRIBE|DESC|EXPLAIN|HANDLER'
				. '|(?:LOCK|UNLOCK)\s+TABLE(?:S)?'
				. '|(?:RENAME|OPTIMIZE|BACKUP|RESTORE|CHECK|CHECKSUM|ANALYZE|REPAIR).*\s+TABLE'
				. '|TRUNCATE(?:\s+TABLE)?'
				. '|CREATE(?:\s+TEMPORARY)?\s+TABLE(?:\s+IF\s+NOT\s+EXISTS)?'
				. '|ALTER(?:\s+IGNORE)?\s+TABLE'
				. '|DROP\s+TABLE(?:\s+IF\s+EXISTS)?'
				. '|CREATE(?:\s+\w+)?\s+INDEX.*\s+ON'
				. '|DROP\s+INDEX.*\s+ON'
				. '|LOAD\s+DATA.*INFILE.*INTO\s+TABLE'
				. '|(?:GRANT|REVOKE).*ON\s+TABLE'
				. '|SHOW\s+(?:.*FROM|.*TABLE)'
				. ')\s+\(*\s*((?:[0-9a-zA-Z$_.`-]|[\xC2-\xDF][\x80-\xBF])+)\s*\)*/is', $query, $maybe ) ) {
			return str_replace( '`', '', $maybe[1] );
		}

		return false;
	}

	/**
	 * Load the column metadata from the last query.
	 *
	 * @since 3.5.0
	 *
	 * @access protected
	 */
	protected function load_col_info() {
		if ( $this->col_info )
			return;

		if ( $this->use_mysqli ) {
			$num_fields = mysqli_num_fields( $this->result );
			for ( $i = 0; $i < $num_fields; $i++ ) {
				$this->col_info[ $i ] = mysqli_fetch_field( $this->result );
			}
		} else {
			$num_fields = mysql_num_fields( $this->result );
			for ( $i = 0; $i < $num_fields; $i++ ) {
				$this->col_info[ $i ] = mysql_fetch_field( $this->result, $i );
			}
		}
	}

	/**
	 * Retrieve column metadata from the last query.
	 *
	 * @since 0.71
	 *
	 * @param string $info_type  Optional. Type one of name, table, def, max_length, not_null, primary_key, multiple_key, unique_key, numeric, blob, type, unsigned, zerofill
	 * @param int    $col_offset Optional. 0: col name. 1: which table the col's in. 2: col's max length. 3: if the col is numeric. 4: col's type
	 * @return mixed Column Results
	 */
	public function get_col_info( $info_type = 'name', $col_offset = -1 ) {
		$this->load_col_info();

		if ( $this->col_info ) {
			if ( $col_offset == -1 ) {
				$i = 0;
				$new_array = array();
				foreach ( (array) $this->col_info as $col ) {
					$new_array[$i] = $col->{$info_type};
					$i++;
				}
				return $new_array;
			} else {
				return $this->col_info[$col_offset]->{$info_type};
			}
		}
	}
}