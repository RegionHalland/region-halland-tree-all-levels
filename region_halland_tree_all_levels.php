<?php

	/**
	 * @package Region Halland Tree All Levels
	 */
	/*
	Plugin Name: Region Halland Tree All Levels
	Description: Front-end-plugin för hela siten som tree-menu
	Version: 1.0.0
	Author: Roland Hydén
	License: MIT
	Text Domain: regionhalland
	*/

	// Return all page childs to a page
	function get_region_halland_tree_all_levels()
	{
		
		// Databs-variabler
		$servername = env('DB_HOST');
		$username = env('DB_USER');
		$password = env('DB_PASSWORD');
		$dbname = env('DB_NAME');
		
		// Create connection
	 	$conn = mysqli_connect($servername, $username, $password, $dbname);

	 	// Hämta från databasen
	 	$sql = "SELECT ";
		$sql .= "ID, ";
		$sql .= "post_title, ";
		$sql .= "post_parent ";
		$sql .= "FROM wp_posts ";
		$sql .= "WHERE ";
		$sql .= "post_status = 'publish' ";
		$sql .= "AND ";
		$sql .= "post_type = 'page'";
		$result = mysqli_query($conn, $sql);
		$data = array();
		while($row = mysqli_fetch_assoc($result)) {
		    $data[] = $row;
		}
		
		$myData = region_halland_tree_all_levels_buildtree($data);

		return $myData;
	}

	function region_halland_tree_all_levels_buildtree($data, $post_parent = 0, $tree = array())
	{
	    foreach($data as $idx => $row)
	    {
	        if($row['post_parent'] == $post_parent)
	        {
	            foreach($row as $k => $v)
	                $tree[$row['ID']][$k] = $v;
	            unset($data[$idx]);
	            $tree[$row['ID']]['children'] = region_halland_tree_all_levels_buildtree($data, $row['ID']);
	        }
	    }
	    ksort($tree);
	    return $tree;
	}

	// Metod som anropas när pluginen aktiveras
	function region_halland_tree_all_levels_activate() {
		// Ingenting just nu...
	}

	// Metod som anropas när pluginen avaktiveras
	function region_halland_tree_all_levels_deactivate() {
		// Ingenting just nu...
	}
	
	// Vilken metod som ska anropas när pluginen aktiveras
	register_activation_hook( __FILE__, 'region_halland_tree_all_levels_activate');
	
	// Vilken metod som ska anropas när pluginen avaktiveras
	register_deactivation_hook( __FILE__, 'region_halland_tree_all_levels_deactivate');

?>