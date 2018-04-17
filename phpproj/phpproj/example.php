<?php

/*

Database Access Library
Object Oriented

*/

require_once('config.php');

if ($config_debug)
  {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
  }


require_once('error.php');



interface database_access
  {
    // Transaction Processing
    public function trans_open();
    public function trans_commit();
    public function trans_rollback();

    // User
    public function query_user($keyvalue);
    public function query_user_all();
    public function update_user_login($userid, $salt, $passwd, $digest, $pwchg);
    public function update_user($uid, $empid, $act, $salt, $passwd, $digest, $pwchg);
    public function insert_user($uid, $empid, $act, $salt, $passwd, $digest, $pwchg);
    public function delete_user($userid);

    // Position
    public function query_position($pos_id);
    public function query_position_all();
    public function insert_position($pos_id, $pos_type);
    public function update_position($pos_id, $pos_type);
    public function delete_position($pos_id);

    // Employee
    public function query_employee($emp_id);
    public function update_employee($empid, $name, $addr, $cph, $hph, $email, $posid);
    public function update_employee_status($empid, $status, $sdate, $edate);
    public function insert_employee($empid, $name, $userid, $addr, $cph, $hph, $email, $posid);
    public function delete_employee($empid);

    // Module
    public function query_module_common();
    public function query_module_access($pos_id);
    public function query_module($mod_id);
    public function query_module_all();
    public function query_module_hasaccess($mod_id, $pos_id);
    public function insert_module_access($mod_id, $pos_id);
    public function delete_module_access($pos_id);

    // Tables
    public function query_table_all();
    public function update_table($tabid, $name, $size, $json);
    public function update_table_status($tabname, $status);
    public function insert_table($tabid, $name, $size, $status, $json);
    public function delete_table($tabid);
    public function delete_table_all();

    // Menus
    public function query_menu($mid);
    public function query_menu_all();
    public function update_menu($mid, $name, $desc, $mfact);
    public function insert_menu($mid, $name, $desc, $mfact);
    public function delete_menu($mid);

    // Menu Items
    public function query_menuitem($mid);
    public function insert_menuitem($mid, $iid);
    public function delete_menuitem($mid, $iid);

    public function query_item($iid);
    public function query_item_all();
    public function update_item($iid, $name, $desc, $cata, $file, $price);
    public function insert_item($iid, $name, $desc, $cata, $file, $price);
    public function delete_item($iid);

    // Item Categories
    public function query_category($cid);
    public function query_category_all();
    public function update_category($cid, $name, $desc);
    public function insert_category($cid, $name, $desc);
    public function delete_category($cid);

    // Item Ingredients
    public function query_itemingred($iid);
    public function insert_itemingred($iid, $nid);
    public function delete_itemingred($iid);

    // Ingredients
    public function query_ingred($nid);
    public function query_ingred_all();
    public function update_ingred($nid, $name);
    public function insert_ingred($nid, $name);
    public function delete_ingred($nid);

    // Time Card
    public function query_timecard($emp_id, $date);
    public function query_timecard_emp($emp_id);
    public function query_timecard_date($date);
    public function update_timecard($emp_id, $date, $field, $value);
    public function insert_timecard($emp_id, $date);
    public function delete_timecard($emp_id, $date);
  }

class database implements database_access
  {
    const PTINT = PDO::PARAM_INT;
    const PTSTR = PDO::PARAM_STR;

    private $sqlconn;

    // Object Constructor
    function __construct()
      {
	global $dbase_type;
	global $dbase_host;
	global $dbase_db;
	global $dbase_uid;
	global $dbase_passwd;
	global $dbase_charset;

	switch ($dbase_type)
	  {
	    case 'mysql':
	      $dsn = $dbase_type . ":";
	      if (isset($dbase_host))
		  {
		    if (isset($dbase_socket))
		      return($this->handle_error("CONFLICT: Can't use sockets and host:port at the same time."));
		    $dsn .= "host=" . $dbase_host;
		    if (isset($dbase_port)) $dsn .= ";port=" . $dbase_port;
		  }
		else
		  {
		    $dsn .= "unix_socket=" . $dbase_socket;
		  }
	      $dsn .= ";dbname=" . $dbase_db;
	      if (isset($dbase_charset)) $dsn .= ";charset=" . $dbase_charset;
	      break;
	    default:
	      return($this->handle_error("Database type must be defined and valid."));
	      break;
	  }

	try
	  {
	    $this->sqlconn = new PDO($dsn, $dbase_uid, $dbase_passwd);
	    $this->sqlconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	  }
	catch (PDOException $e)
	  {
	    echo "Failed to connect to database: " . $e->getMessage();
	    exit(1);
	  }
      }


    // Puts error messages into the error message buffer and returns false.
    private function handle_error($mixvar)
      {
        global $handerr;
	if (is_object($mixvar))
	    {
	      $error = $mixvar->errorInfo();
	      if ($error[1] != 0)
		{
		  $str = "SQL Failue: ($error[1]:$error[0]) $error[2]";
		  $handerr->puterrmsg($str);
		  if (is_a($mixvar, 'PDOStatement', false))
		    {
		      $mixvar->closeCursor;
		    }
		}
	    }
	  else if (is_string($mixvar))
	    {
	      $handerr->puterrmsg($mixvar);
	    }

	// Always return false
        return false;
      }


    // Collates results into a 3D associative array.
    // This only makes sense for queries which result in
    // multiple rows.  Not to be called directly.
    private function result_multi($stmt)
      {
	$rxa = array();
	$flag = false;
	while (true)
	  {
	    $row = $stmt->fetch(PDO::FETCH_ASSOC);
	    if ($row == false)
	      {
		if ($flag == false) return($this->handle_error($stmt));
		  else break;
	      }
	    array_push($rxa, $row);
	    $flag = true;
	  }
	return $rxa;
      }

    // Launches a single query for a single table.
    // Returns a single record in associative array format.
    // $qxa is a nested array.  There is one inside array for
    // each query parameter.  The keys are field, type, and value.
    private function query_launch_single($tab, $col, $qxa)
      {
        if (!is_array($qxa)) return($this->handle_error("Query data not an array."));
	$request = "SELECT $col FROM $tab WHERE ";
	$flag = false;
	foreach($qxa as $fx => $vx)
	  {
	    if ($flag) $request .= " AND ";
	    $request .= $vx['field'] . " = ?";
	    $flag = true;
	  }
	$request .= " LIMIT 1";
	$stmt = $this->sqlconn->prepare($request);
	if ($stmt == false) return($this->handle_error($this->sqlconn));
	$count = 1;
	foreach($qxa as $fx => $vx)
	  {
	    $result = $stmt->bindParam($count, $vx['value'], $vx['type']);
	    if ($result == false) return($this->handle_error($stmt));
	    $count++;
	  }
        $result = $stmt->execute();
	if ($result == false) return($this->handle_error($stmt));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	if ($row == false) return($this->handle_error($stmt));
	$stmt->closeCursor();
	return $row;
      }


    // Same as query_launch above, but returns data in a 3D
    // associative array.
    private function query_launch_multi($tab, $col, $qxa)
      {
        if (!is_array($qxa)) return($this->handle_error("Query data not an array."));
	$request = "SELECT $col FROM $tab WHERE ";
	$flag = false;
	foreach($qxa as $fx => $vx)
	  {
	    if ($flag) $request .= " AND ";
	    $request .= $vx['field'] . " = ?";
	    $flag = true;
	  }
	$stmt = $this->sqlconn->prepare($request);
	if ($stmt == false) return($this->handle_error($this->sqlconn));
	$count = 1;
	foreach($qxa as $fx => $vx)
	  {
	    $result = $stmt->bindParam($count, $vx['value'], $vx['type']);
	    if ($result == false) return($this->handle_error($stmt));
	    $count++;
	  }
        $result = $stmt->execute();
	if ($result == false) return($this->handle_error($stmt));
	$rxa = $this->result_multi($stmt);
	$stmt->closeCursor();
	return $rxa;
      }


    // This dumps the entire table and returns the data in a 3D
    // associative array.
    private function query_launch_dumptab($tab, $col)
      {
        $request = "SELECT $col FROM $tab";
	$stmt = $this->sqlconn->query($request);
	if ($stmt == false) return($this->handle_error($this->sqlconn));
	$rxa = $this->result_multi($stmt);
	$stmt->closeCursor();
	return $rxa;
      }


    // Special queries that expect multiple results.
    private function query_launch_multispec($request)
      {
	$stmt = $this->sqlconn->query($request);
	if ($stmt == false) return($this->handle_error($this->sqlconn));
	$rxa = $this->result_multi($stmt);
	return $rxa;
      }


    // Updates a database record. This is multi-field enabled with single
    // key.
    private function update_launch($tab, $kfld, $kval, $ktyp, $dxa)
      {
	$str = "";
        if (!is_array($dxa))
	  return($this->handle_error("Program error, 5th param is not array."));

	$flag = false;
	foreach($dxa as $fx => $vx)
	  {
	    if ($flag)
	      {
	        $str .= ",";
	      }
	    $str .= $vx['field'] . "=?";
	    $flag = true;
	  }
	$request = "UPDATE $tab SET $str WHERE $kfld = ? LIMIT 1";
	$stmt = $this->sqlconn->prepare($request);
	if ($stmt == false) return($this->handle_error($this->sqlconn));
	$count = 1;
	foreach($dxa as $fx => $vx)
	  {
	    $result = $stmt->bindParam($count, $vx['value'], $vx['type']);
	    if ($result == false) return($this->handle_error($stmt));
	    $count++;
	  }
	$result = $stmt->bindParam($count, $kval, $ktyp);
	if ($result == false) return($this->handle_error($stmt));
        $result = $stmt->execute();
	if ($result == false) return($this->handle_error($stmt));
	return true;
      }


    // Updates a database record. This is multi-field enabled with multiple
    // keys.
    private function update_launch_multi($tab, $dxk, $dxa)
      {
	$strdat = "";
	$strkey = "";
	if (!is_array($dxk))
	  return($this->handle_error("Program error, 2nd param is not array."));
        if (!is_array($dxa))
	  return($this->handle_error("Program error, 3th param is not array."));

	$flag = false;
	foreach($dxa as $fx => $vx)
	  {
	    if ($flag)
	      {
	        $strdat .= ",";
	      }
	    $strdat .= $vx['field'] . "=?";
	    $flag = true;
	  }

	$flag = false;
	foreach($dxk as $fx => $vx)
	  {
	    if ($flag)
	      {
	        $strkey .= " AND ";
	      }
	    $strkey .= $vx['field'] . "=?";
	    $flag = true;
	  }

	$request = "UPDATE $tab SET $strdat WHERE $strkey LIMIT 1";
	$stmt = $this->sqlconn->prepare($request);
	if ($stmt == false) return($this->handle_error($this->sqlconn));
	$count = 1;
	foreach($dxa as $fx => $vx)
	  {
	    $result = $stmt->bindParam($count, $vx['value'], $vx['type']);
	    if ($result == false) return($this->handle_error($stmt));
	    $count++;
	  }
	foreach($dxk as $fx => $vx)
	  {
	    $result = $stmt->bindParam($count, $vx['value'], $vx['type']);
	    if ($result == false) return($this->handle_error($stmt));
	    $count++;
	  }
        $result = $stmt->execute();
	if ($result == false) return($this->handle_error($stmt));
	return true;
      }


    // Inserts a record into the database.  Multifield enabled.
    private function insert_launch($tab, $qxa)
      {
        $str1 = "";
	$str2 = "";
	$flag = false;
	foreach($qxa as $fx => $vx)
	  {
	    if ($flag)
	      {
	        $str1 .= ",";
	        $str2 .= ",";
	      }
	    $str1 .= $vx['field'];
	    $str2 .= "?";
	    $flag = true;
	  }
	$request = "INSERT INTO $tab ($str1) VALUES ($str2)";
	$stmt = $this->sqlconn->prepare($request);
	if ($stmt == false) return($this->handle_error($this->sqlconn));
	$count = 1;
	foreach($qxa as $fx => $vx)
	  {
	    $result = $stmt->bindParam($count, $vx['value'], $vx['type']);
	    if ($result == false) return($this->handle_error($stmt));
	    $count++;
	  }
        $result = $stmt->execute();
	if ($result == false) return($this->handle_error($stmt));
	return true;
      }


    // Deletes a record from the database.
    private function delete_launch($tab, $kfld, $kval, $ktyp)
      {
        $request = "DELETE FROM $tab WHERE $kfld=?";
	$stmt = $this->sqlconn->prepare($request);
	if ($stmt == false) return($this->handle_error($this->sqlconn));
	$result = $stmt->bindParam(1, $kval, $ktyp);
	if ($result == false) return($this->handle_error($stmt));
        $result = $stmt->execute();
	if ($result == false) return($this->handle_error($stmt));
	return true;
      }

    // Deletes a record with multiple keys from the database.
    private function delete_launch_multi($tab, $qxa)
      {
	$flag = false;
	$str = "";
	foreach($qxa as $fx => $vx)
	  {
	    if ($flag) $str .= ",";
	      else $flag = true;
	    $str .= $vx['field'] . "=?";
	  }
        $request = "DELETE FROM $tab WHERE $str";
	$stmt = $this->sqlconn->prepare($request);
	if ($stmt == false) return($this->handle_error($this->sqlconn));
	$count = 1;
	foreach($qxa as $fx => $vx)
	  {
	    $result = $stmt->bindParam($count, $vx['value'], $vx['type']);
	    if ($result == false) return($this->handle_error($stmt));
	    $count++;
	  }
        $result = $stmt->execute();
	if ($result == false) return($this->handle_error($stmt));
	return true;
      }


    // Sends a preformatted command to the database.  Returns false
    // on error, true on success.  Do not use for queries.
    private function request_launch($request)
      {
	$stmt = $this->sqlconn->query($request);
	if ($stmt == false) return($this->handle_error($this->sqlconn));
	return true;
      }




    /* ******** PUBLIC FUNCTIONS ******** */


    /* **** TRANSACTION PROCESSING **** */


    // Turns off autocommit mode and begins a transaction.
    // Returns true if successful, false on error.
    public function trans_open()
      {
        return($this->sqlconn->beginTransaction());
      }

    // Commits an open transaction and turns autocommit mode on.
    // Returns true if successful, false on error.
    public function trans_commit()
      {
        return($this->sqlconn->commit());
      }

    // Cancels a transaction and turns autocommit mode on.
    // Returns true if successful, false on error.
    public function trans_rollback()
      {
        return($this->sqlconn->rollBack());
      }


    /* **** POSITION TABLE **** */


    // Queries a position ID in the position table.
    public function query_position($pos_id)
      {
	$qxa = array();
        $table = "position";
	$columns = "*";
	array_push($qxa, array('field' => 'position_id', 'value' => $pos_id, 'type' => self::PTINT));
	return($this->query_launch_single($table, $columns, $qxa));
      }

    // Returns all positions in the database.
    public function query_position_all()
      {
        $table = "position";
	$columns = "*";
	return($this->query_launch_dumptab($table, $columns));
      }

    // Queries a position ID in the position table.
    public function update_position($pos_id, $pos_type)
      {
	$qxa = array();
        $table = "position";
	$field = "position_id";
	array_push($qxa, array('field' => 'position_type', 'value' => $pos_type, 'type' => self::PTSTR));
	return($this->update_launch($table, $field, $pos_id, self::PTINT, $qxa));
      }

    public function insert_position($pos_id, $pos_type)
      {
        $qxa = array();
	$table = "position";
	array_push($qxa, array('field' => 'position_id', 'value' => $pos_id, 'type' => self::PTINT));
	array_push($qxa, array('field' => 'position_type', 'value' => $pos_type, 'type' => self::PTSTR));
	return($this->insert_launch($table, $qxa));
      }

    public function delete_position($pos_id)
      {
        $table = "position";
	$field = "position_id";
	return($this->delete_launch($table, $field, $pos_id, self::PTINT));
      }


    /* **** USERS TABLE **** */


    // Queries a user in the users table.
    public function query_user($user_id)
      {
	$qxa = array();
        $table = "users";
	$columns = "*";
	$types = self::PTSTR;
	array_push($qxa, array('field' => 'user_id', 'value' => $user_id, 'type' => self::PTSTR));
	return($this->query_launch_single($table, $columns, $qxa));
      }

    // Returns information about all users in the database.
    public function query_user_all()
      {
        $table = "users";
	$columns = "user_id,employee_id";
	return($this->query_launch_dumptab($table, $columns));
      }

    // Updates the user login information.
    public function update_user_login($userid, $salt, $passwd, $digest, $pwchg)
      {
        $qxa = array();
	$table = "users";
	$field = "user_id";
	array_push($qxa, array('field' => 'passchg','value' => $pwchg,  'type' => self::PTINT));
	array_push($qxa, array('field' => 'digest', 'value' => $digest, 'type' => self::PTSTR));
	array_push($qxa, array('field' => 'salt',   'value' => $salt,	'type' => self::PTSTR));
	array_push($qxa, array('field' => 'passwd', 'value' => $passwd, 'type' => self::PTSTR));
	return($this->update_launch($table, $field, $userid, self::PTSTR, $qxa));
      }

    // Updates all user fields.
    public function update_user($uid, $empid, $act, $salt, $passwd, $digest, $pwchg)
      {
        $qxa = array();
	$table = "users";
	$field = "user_id";
	array_push($qxa, array('field' => 'employee_id', 'value' => $empid, 'type' => self::PTINT));
	array_push($qxa, array('field' => 'passchg','value' => $pwchg,  'type' => self::PTINT));
	array_push($qxa, array('field' => 'active', 'value' => $act,    'type' => self::PTINT));
	array_push($qxa, array('field' => 'digest', 'value' => $digest, 'type' => self::PTSTR));
	array_push($qxa, array('field' => 'salt',   'value' => $salt,	'type' => self::PTSTR));
	array_push($qxa, array('field' => 'passwd', 'value' => $passwd, 'type' => self::PTSTR));
	return($this->update_launch($table, $field, $uid, self::PTSTR, $qxa));
      }

    // Inserts user into users table.
    public function insert_user($uid, $empid, $act, $salt, $passwd, $digest, $pwchg)
      {
        $qxa = array();
	$table = "users";
	array_push($qxa, array('field' => 'user_id',     'value' => $uid,   'type' => self::PTSTR));
	array_push($qxa, array('field' => 'employee_id', 'value' => $empid, 'type' => self::PTINT));
	array_push($qxa, array('field' => 'passchg','value' => $pwchg,  'type' => self::PTINT));
	array_push($qxa, array('field' => 'active', 'value' => $act,    'type' => self::PTINT));
	array_push($qxa, array('field' => 'digest', 'value' => $digest, 'type' => self::PTSTR));
	array_push($qxa, array('field' => 'salt',   'value' => $salt,	'type' => self::PTSTR));
	array_push($qxa, array('field' => 'passwd', 'value' => $passwd, 'type' => self::PTSTR));
	return($this->insert_launch($table, $qxa));
      }

    // Delete user
    public function delete_user($userid)
      {
        $table = "users";
	$field = "user_id";
	return($this->delete_launch($table, $field, $userid, self::PTSTR));
      }


    /* **** EMPLOYEE TABLE **** */


    // Queries information about a specific employee number.
    public function query_employee($emp_id)
      {
	$qxa = array();
        $table = "employee";
	$columns = "*";
	array_push($qxa, array('field' => 'employee_id', 'value' => $emp_id, 'type' => self::PTINT));
	return($this->query_launch_single($table, $columns, $qxa));
      }

    public function query_employee_pos($pos_id)
      {
	$qxa = array();
        $table = "employee";
	$columns = "*";
	array_push($qxa, array('field' => 'position_id', 'value' => $pos_id, 'type' => self::PTINT));
	return($this->query_launch_multi($table, $columns, $qxa));
      }

    public function update_employee($empid, $name, $addr, $cph, $hph, $email, $posid)
      {
	$qxa = array();
        $table = "employee";
	$field = "employee_id";
	array_push($qxa, array('field' => 'name',       'value' => $name,  'type' => self::PTSTR));
	array_push($qxa, array('field' => 'address',    'value' => $addr,  'type' => self::PTSTR));
	array_push($qxa, array('field' => 'cell_phone', 'value' => $cph,   'type' => self::PTSTR));
	array_push($qxa, array('field' => 'home_phone', 'value' => $hph,   'type' => self::PTSTR));
	array_push($qxa, array('field' => 'emailId',    'value' => $email, 'type' => self::PTSTR));
	array_push($qxa, array('field' => 'position_id','value' => $posid,'type' => self::PTINT));
	return($this->update_launch($table, $field, $empid, self::PTINT, $qxa));
      }

    public function update_employee_status($empid, $status, $sdate, $edate)
      {
	$qxa = array();
        $table = "employee";
	$field = "employee_id";
	array_push($qxa, array('field' => 'status', 'value' => $status, 'type' => self::PTSTR));
	array_push($qxa, array('field' => 'emp_start', 'value' => $sdate, 'type' => self::PTINT));
	array_push($qxa, array('field' => 'emp_end', 'value' => $edate, 'type' => self::PTINT));
	return($this->update_launch($table, $field, $empid, self::PTINT, $qxa));
      }

    // Inserts a new record into the employee table.
    public function insert_employee($empid, $name, $userid, $addr, $cph, $hph, $email, $posid)
      {
	$qxa = array();
        $table = "employee";
	array_push($qxa, array('field' => 'employee_id','value' => $empid, 'type' => self::PTINT));
	array_push($qxa, array('field' => 'user_id',    'value' => $userid,'type' => self::PTSTR));
	array_push($qxa, array('field' => 'name',       'value' => $name, 'type' => self::PTSTR));
	array_push($qxa, array('field' => 'address',    'value' => $addr, 'type' => self::PTSTR));
	array_push($qxa, array('field' => 'cell_phone', 'value' => $cph,  'type' => self::PTSTR));
	array_push($qxa, array('field' => 'home_phone', 'value' => $hph,  'type' => self::PTSTR));
	array_push($qxa, array('field' => 'emailId',    'value' => $email,'type' => self::PTSTR));
	array_push($qxa, array('field' => 'position_id','value' => $posid,'type' => self::PTINT));
	return($this->insert_launch($table, $qxa));
      }

    // Deletes the specified employee by employee Id.
    // Note: This will cascade to all tables that uses employee_id as a
    // foreign key.
    public function delete_employee($empid)
      {
        $table = "employee";
	$field = "employee_id";
	return($this->delete_launch($table, $field, $empid, self::PTINT));
      }


    /* **** MODULES **** */


    // Returns information about all common modules that
    // are in the database.
    public function query_module_common()
      {
	$request = "SELECT * FROM modules WHERE module_id BETWEEN -2147483648 AND 0";
	return($this->query_launch_multispec($request));
      }

    // Returns information from the database about all
    // modules the employee has access to.
    public function query_module_access($pos_id)
      {
	$qxa = array();
        $table = "module_access";
	$columns = "module_id";
	array_push($qxa, array('field' => 'position_id', 'value' => $pos_id, 'type' => self::PTINT));
	return($this->query_launch_multi($table, $columns, $qxa));
      }

    // Queries information about all modules.
    public function query_module_all()
      {
        $table = "modules";
	$columns = "*";
	return($this->query_launch_dumptab($table, $columns));
      }

    // Queries information about a specific module.
    public function query_module($mod_id)
      {
	$qxa = array();
        $table = "modules";
	$columns = "*";
	array_push($qxa, array('field' => 'module_id', 'value' => $mod_id, 'type' => self::PTINT));
	return($this->query_launch_single($table, $columns, $qxa));
      }

    // Checks if the user has access to the specific module.
    // Note: All users have access to common modules.
    // Returns true if the employee has access, false if not.
    public function query_module_hasaccess($mod_id, $pos_id)
      {
	if ($mod_id < 0) return true;
	if ($pos_id < 0) return true;
	$qxa = array();
        $table = "module_access";
	$columns = "*";
	array_push($qxa, array('field' => 'module_id', 'value' => $mod_id, 'type' => self::PTINT));
	array_push($qxa, array('field' => 'position_id', 'value' => $pos_id, 'type' => self::PTINT));
	$row = $this->query_launch_single($table, $columns, $qxa);
	if ($row != false) return true;
	return false;
      }

    // Inserts module access records.
    public function insert_module_access($mod_id, $pos_id)
      {
	$qxa = array();
        $table = "module_access";
	array_push($qxa, array('field' => 'module_id', 'value' => $mod_id, 'type' => self::PTINT));
	array_push($qxa, array('field' => 'position_id', 'value' => $pos_id, 'type' => self::PTINT));
	return($this->insert_launch($table, $qxa));
      }

    // Deletes all module access records for a given position ID.
    public function delete_module_access($pos_id)
      {
        $table = "module_access";
	$field = "position_id";
	return($this->delete_launch($table, $field, $pos_id, self::PTINT));
      }

    /* **** TALBLE_DESC **** */


    // Returns all records in the table_details table.
    public function query_table_all()
      {
        $table = "table_details";
	$columns = "*";
	return($this->query_launch_dumptab($table, $columns));
      }

    // Updates a record in table_desc.
    public function update_table($tabid, $name, $size, $json)
      {
	$qxa = array();
        $table = "table_details";
	$field = "table_id";
	array_push($qxa, array('field' => 'table_name', 'value' => $name, 'type' => self::PTSTR));
	array_push($qxa, array('field' => 'table_size', 'value' => $size, 'type' => self::PTINT));
	array_push($qxa, array('field' => 'json_obj',   'value' => $json, 'type' => self::PTSTR));
	return($this->update_launch($table, $field, $tabid, self::PTINT, $qxa));
      }

    // Updates only the status of a table
    public function update_table_status($tabname, $status)
      {
	$qxa = array();
        $table = "table_details";
	$field = "table_name";
	array_push($qxa, array('field' => 'status', 'value' => $status, 'type' => self::PTINT));
	return($this->update_launch($table, $field, $tabname, self::PTSTR, $qxa));
      }

    // Inserts a new table
    public function insert_table($tabid, $name, $size, $status, $json)
      {
	$qxa = array();
        $table = "table_details";
	array_push($qxa, array('field' => 'table_id',   'value' => $tabid,  'type' => self::PTINT));
	array_push($qxa, array('field' => 'table_name', 'value' => $name,   'type' => self::PTSTR));
	array_push($qxa, array('field' => 'table_size', 'value' => $size,   'type' => self::PTINT));
	array_push($qxa, array('field' => 'status',     'value' => $status, 'type' => self::PTINT));
	array_push($qxa, array('field' => 'json_obj',   'value' => $json,   'type' => self::PTSTR));
	return($this->insert_launch($table, $qxa));
      }

    // Deletes a table
    public function delete_table($tabid)
      {
        $table = "table_details";
	$field = "table_id";
	return($this->delete_launch($table, $field, $tabid, self::PTINT));
      }

    // Deletes all data from table table_details
    public function delete_table_all()
      {
        $req = "DELETE FROM table_details";
	return($this->request_launch($req));
      }


    /* **** MENU **** */


    public function query_menu($mid)
      {
	$qxa = array();
        $table = "menu";
	$columns = "*";
	array_push($qxa, array('field' => 'menu_id', 'value' => $mid, 'type' => self::PTSTR));
	return($this->query_launch_single($table, $columns, $qxa));
      }

    public function query_menu_all()
      {
        $table = "menu";
	$columns = "*";
	return($this->query_launch_dumptab($table, $columns));
      }

    public function update_menu($mid, $name, $desc, $mfact)
      {
        $qxa = array();
	$table = "menu";
	$field = "menu_id";
	$strmfact = strvar($mfact);
	array_push($qxa, array('field' => 'menu_name', 'value' => $name, 'type' => self::PTSTR));
	array_push($qxa, array('field' => 'description', 'value' => $desc, 'type' => self::PTSTR));
	array_push($qxa, array('field' => 'multifactor', 'value' => $strmfact, 'type' => self::PTSTR));
	return($this->update_launch($table, $field, $mid, self::PTSTR, $qxa));
      }

    public function insert_menu($mid, $name, $desc, $mfact)
      {
        $qxa = array();
	$table = "menu";
	$strmfact = strvar($mfact);
	array_push($qxa, array('field' => 'menu_id', 'value' => $mid, 'type' => self::PTSTR));
	array_push($qxa, array('field' => 'menu_name', 'value' => $name, 'type' => self::PTSTR));
	array_push($qxa, array('field' => 'description', 'value' => $desc, 'type' => self::PTSTR));
	array_push($qxa, array('field' => 'multifactor', 'value' => $strmfact, 'type' => self::PTSTR));
	return($this->insert_launch($table, $qxa));
      }

    public function delete_menu($mid)
      {
        $table = "menu";
	$field = "menu_id";
	return($this->delete_launch($table, $field, $mid, self::PTSTR));
      }


    /* **** MENU_ITEM **** */


    public function query_menuitem($mid)
      {
	$qxa = array();
        $table = "menu_item";
	$columns = "*";
	array_push($qxa, array('field' => 'menu_id', 'value' => $mid, 'type' => self::PTSTR));
	return($this->query_launch_multi($table, $columns, $qxa));
      }

    public function insert_menuitem($mid, $iid)
      {
        $qxa = array();
	$table = "menu_item";
	array_push($qxa, array('field' => 'menu_id', 'value' => $mid, 'type' => self::PTSTR));
	array_push($qxa, array('field' => 'item_id', 'value' => $iid, 'type' => self::PTINT));
	return($this->insert_launch($table, $qxa));
      }

    public function delete_menuitem($mid, $iid)
      {
	$qxa = array();
        $table = "menu_item";
	array_push($qxa, array('field' => 'menu_id', 'value' => $mid, 'type' => self::PTSTR));
	array_push($qxa, array('field' => 'item_id', 'value' => $iid, 'type' => self::PTINT));
	return($this->delete_launch_multi($table, $qxa));
      }


    /* **** ITEM **** */


    public function query_item($iid)
      {
	$qxa = array();
        $table = "item";
	$columns = "*";
	array_push($qxa, array('field' => 'item_id', 'value' => $iid, 'type' => self::PTINT));
	return($this->query_launch_single($table, $columns, $qxa));
      }

    public function query_item_all()
      {
        $table = "item";
	$columns = "*";
	return($this->query_launch_dumptab($table, $columns));
      }

    public function update_item($iid, $name, $desc, $cata, $file, $price)
      {
        $qxa = array();
	$table = "item";
	$field = "item_id";
	$strprice = strval($price);
	array_push($qxa, array('field' => 'item_name', 'value' => $name, 'type' => self::PTSTR));
	array_push($qxa, array('field' => 'description', 'value' => $desc, 'type' => self::PTSTR));
	array_push($qxa, array('field' => 'category_id', 'value' => $cata, 'type' => self::PTSTR));
	array_push($qxa, array('field' => 'image_file', 'value' => $file, 'type' => self::PTSTR));
	array_push($qxa, array('field' => 'price', 'value' => $strprice, 'type' => self::PTSTR));
	return($this->update_launch($table, $field, $iid, self::PTINT, $qxa));
      }

    public function insert_item($iid, $name, $desc, $cata, $file, $price)
      {
        $qxa = array();
	$table = "item";
	$strprice = strval($price);
	array_push($qxa, array('field' => 'item_id', 'value' => $iid, 'type' => self::PTINT));
	array_push($qxa, array('field' => 'item_name', 'value' => $name, 'type' => self::PTSTR));
	array_push($qxa, array('field' => 'description', 'value' => $desc, 'type' => self::PTSTR));
	array_push($qxa, array('field' => 'category_id', 'value' => $cata, 'type' => self::PTSTR));
	array_push($qxa, array('field' => 'image_file', 'value' => $file, 'type' => self::PTSTR));
	array_push($qxa, array('field' => 'price', 'value' => $strprice, 'type' => self::PTSTR));
	return($this->insert_launch($table, $qxa));
      }

    public function delete_item($iid)
      {
        $table = "item";
	$field = "item_id";
	return($this->delete_launch($table, $field, $iid, self::PTINT));
      }


    /* **** ITEM_CATEGORY **** */


    public function query_category($cid)
      {
	$qxa = array();
        $table = "item_category";
	$columns = "*";
	array_push($qxa, array('field' => 'category_id', 'value' => $cid, 'type' => self::PTSTR));
	return($this->query_launch_single($table, $columns, $qxa));
      }

    public function query_category_all()
      {
        $table = "item_category";
	$columns = "*";
	return($this->query_launch_dumptab($table, $columns));
      }

    public function update_category($cid, $name, $desc)
      {
        $qxa = array();
        $table = "item_category";
	$field = "category_id";
	array_push($qxa, array('field' => 'category_name', 'value' => $name, 'type' => self::PTSTR));
	array_push($qxa, array('field' => 'description', 'value' => $desc, 'type' => self::PTSTR));
	return($this->update_launch($table, $field, $cid, self::PTSTR, $qxa));
      }

    public function insert_category($cid, $name, $desc)
      {
        $qxa = array();
        $table = "item_category";
	array_push($qxa, array('field' => 'category_id', 'value' => $cid, 'type' => self::PTSTR));
	array_push($qxa, array('field' => 'category_name', 'value' => $name, 'type' => self::PTSTR));
	array_push($qxa, array('field' => 'description', 'value' => $desc, 'type' => self::PTSTR));
	return($this->insert_launch($table, $qxa));
      }

    public function delete_category($cid)
      {
        $table = "item_category";
	$field = "category_id";
	return($this->delete_launch($table, $field, $cid, self::PTSTR));
      }


    /* **** ITEM_INGREDIENT **** */


    // Queries all ingredients for an item.
    public function query_itemingred($iid)
      {
	$qxa = array();
        $table = "item_ingredient";
	$columns = "*";
	array_push($qxa, array('field' => 'item_id', 'value' => $iid, 'type' => self::PTINT));
	return($this->query_launch_multi($table, $columns, $qxa));
      }

    // Inserts an ingredient for an item.
    public function insert_itemingred($iid, $nid)
      {
        $qxa = array();
        $table = "item_ingredient";
	array_push($qxa, array('field' => 'ingredient_id', 'value' => $nid, 'type' => self::PTINT));
	array_push($qxa, array('field' => 'item_id', 'value' => $iid, 'type' => self::PTINT));
	return($this->insert_launch($table, $qxa));
      }

    // Deletes all ingredients from an item.
    public function delete_itemingred($iid)
      {
	$qxa = array();
        $table = "item_ingredient";
	array_push($qxa, array('field' => 'item_id', 'value' => $iid, 'type' => self::PTINT));
	return($this->delete_launch_multi($table, $qxa));
      }


    /* **** INGREDIENTS **** */


    // Queries a specific ingredient.
    public function query_ingred($nid)
      {
	$qxa = array();
        $table = "ingredients";
	$columns = "*";
	array_push($qxa, array('field' => 'ingredient_id', 'value' => $nid, 'type' => self::PTINT));
	return($this->query_launch_single($table, $columns, $qxa));
      }

    // Returns all ingredients.
    public function query_ingred_all()
      {
        $table = "ingredients";
	$columns = "*";
	return($this->query_launch_dumptab($table, $columns));
      }

    // Updates an ingredient.
    public function update_ingred($nid, $name)
      {
        $qxa = array();
        $table = "ingredients";
	$field = "ingredient_id";
	array_push($qxa, array('field' => 'ingredient_name', 'value' => $name, 'type' => self::PTSTR));
	return($this->update_launch($table, $field, $nid, self::PTINT, $qxa));
      }

    // Inserts a new ingredient.
    public function insert_ingred($nid, $name)
      {
        $qxa = array();
        $table = "ingredients";
	array_push($qxa, array('field' => 'ingredient_id', 'value' => $nid, 'type' => self::PTINT));
	array_push($qxa, array('field' => 'ingredient_name', 'value' => $name, 'type' => self::PTSTR));
	return($this->insert_launch($table, $qxa));
      }

    // Deletes an ingredient.
    public function delete_ingred($nid)
      {
        $table = "ingredients";
	$field = "ingredient_id";
	return($this->delete_launch($table, $field, $nid, self::PTSTR));
      }

    /* **** TIME CARD **** */

    public function query_timecard($emp_id, $date)
      {
	$qxa = array();
        $table = "time_card";
	$columns = "*";
	array_push($qxa, array('field' => 'employee_id', 'value' => $emp_id, 'type' => self::PTINT));
	array_push($qxa, array('field' => 'entry_date', 'value' => $date, 'type' => self::PTSTR));
	return($this->query_launch_single($table, $columns, $qxa));
      }

    public function query_timecard_emp($emp_id)
      {
	$qxa = array();
        $table = "time_card";
	$columns = "*";
	array_push($qxa, array('field' => 'employee_id', 'value' => $emp_id, 'type' => self::PTINT));
	return($this->query_launch_single($table, $columns, $qxa));
      }

    public function query_timecard_date($date)
      {
	$qxa = array();
        $table = "time_card";
	$columns = "*";
	array_push($qxa, array('field' => 'entry_date', 'value' => $date, 'type' => self::PTSTR));
	return($this->query_launch_single($table, $columns, $qxa));
      }

    public function update_timecard($emp_id, $date, $field, $value)
      {
        $qxa = array();
	$qxk = array();
        $table = "time_card";
	array_push($qxk, array('field' => 'entry_date', 'value' => $date, 'type' => self::PTSTR));
	array_push($qxk, array('field' => 'employee_id', 'value' => $emp_id, 'type' => self::PTINT));
	array_push($qxa, array('field' => $field, 'value' => $value, 'type' => self::PTINT));
	return($this->update_launch_multi($table, $qxk, $qxa));
      }

    public function insert_timecard($emp_id, $date)
      {
        $qxa = array();
        $table = "time_card";
	array_push($qxa, array('field' => 'entry_date', 'value' => $date, 'type' => self::PTSTR));
	array_push($qxa, array('field' => 'employee_id', 'value' => $emp_id, 'type' => self::PTINT));
	return($this->insert_launch($table, $qxa));
      }

    public function delete_timecard($emp_id, $date)
      {
        $qxa = array();
        $table = "time_card";
	array_push($qxa, array('field' => 'entry_date', 'value' => $date, 'type' => self::PTSTR));
	array_push($qxa, array('field' => 'employee_id', 'value' => $emp_id, 'type' => self::PTINT));
        return($this->delete_launch_multi($table, $qxa));
      }


	// Array building template
	//array_push($qxa, array('field' => '', 'value' => , 'type' => ));
  }


// Auto instanciate the class.
$dbase = new database();



?>
