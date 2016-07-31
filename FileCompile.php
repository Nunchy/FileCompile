<?php

	/************************************************************
	 * FileCompile.php - Written by M. Nealon, 2016.
	 */

	define("FILE_COMPILE_BASE", "resources");	// Default base directory
	define("FILE_COMPILE_ERR_PREFIX", "ERR: ");	// Error message prefix string.
	
	/**
	 * Loads the specified file if it can be found.
	 *
	 * If the $strPath is NULL or an empty string, FileLoad() will look for
	 * $strBase/$strFile, if found the data is loaded and returned. If
	 * it is not found an error message is returned.
	 *
	 * If the $strPath is not NULL then FileLoad() first looks for 
	 * $strBase/$strPath/$strFile, if this is found the data is loaded
	 * and returned - however, if it is not found then FileLoad()
	 * will attempt to load $strBase/$strFile.
	 *
	 * The idea here is that we can create a set of standard resources which
	 * are stored in the base directory. And we can then extend that standard
	 * set of resources by creating sub-directries within strBase and keeping
	 * cutsom versions of some (or all) of the default, standard files.
	 *
	 * Think of it as a fallback option - we can fall back on $strBase/$strFile
	 * if $strBase/$strPath/$strFile can't be found.
	 */
	function FileLoad(
		$strBase,
		$strPath,
		$strFile
	) {
		$filePath = "";
		
		// Firstly - initialise $strBase with the default
		// FILE_COMPILE_BASE ("resources" by default). Also,
		// ensure $strPath is set to "" if NULL...
		if ($strBase == NULL || $strBase == "") $strBase = FILE_COMPILE_BASE;
		if ($strPath == NULL) $strPath = "";
		else {
			// Look first for $strBase/$strPath/$strFile.
			$filePath = $strBase . "/" . $strPath . "/" . $strFile;
			echo "Attempting to load file " . $filePath . "<br />";
			if (is_file($filePath))
				return file_get_contents($filePath);
			echo "Couldn't load file " . $filePath . "<br />";
		}
		
		// Look for $strBase/$strPath
		$filePath = $strBase . "/" . $strFile;
		echo "Attempting to load file " . $filePath . "<br />";
		if (is_file($filePath))
			return file_get_contents($filePath);
			
		// File wasn't found in either $strBase/$strPath or
		// directly within $strBase - return error message.
		return FILE_COMPILE_ERR_PREFIX . "Error loading file " . $strFile . "!";
	}
	
	/**
	 * This is just a loop that loads each file in the list via calls to
	 * FileLoad().
	 *
	 * Will immediately bail with any errors returned by FileLoad(), or will
	 * return the compiled data loaded from the listed files.
	 *
	 * Passing the return string to FileCompileError() will tell you whether
	 * there was an error or not - example:
	 *
	 *	$cssArray = array(
	 *		0 => "common.css",
	 *		1 => "header.css",
	 *		2 => "nav.css"
	 *	);
	 *
	 *	$compiledData = Filecompile("css", "default", $cssArray);
	 *	if (($strError = FileCompileError($compiledData)) != "") {
	 *		echo "FileCompile error: " . $strError;
	 *		die();
	 *	}
	 */
	function FileCompile(
		$strBase,
		$strPath,
		$fileList
	) {
		$compiledData = "";	// Compiled data is stored here.
		$returnedData = "";	// Each individual file is temporarily stored here.
		
		for ($f = 0; $f < count($fileList); $f++) {
			// Load the current file - check here for
			// and reuurn any errors...
			$returnedData = FileLoad($strBase, $strPath, $fileList[$f]);
			// FilecompileError() will return an empty string if there
			// are no errors to report.
			if (FileCompileError($returnedData) != "")
				return $returnedData;
			$compiledData = $compiledData . $returnedData;
		}
		
		// Success - return compiled data.
		return $compiledData;
	}
	
	/**
	 * If the specified string ($strOutput) if prefixed with the error
	 * prefix (FILE_COMPILE_ERR_PREFIX, "ERR: " by default) then the
	 * error message portion of the string (everything after the prefix)
	 * is returned.
	 *
	 * If this isn't an error message an empty string is returned.
	 */
	function FileCompileError(
		$strOutput
	) {
		$strPrefix = FILE_COMPILE_ERR_PREFIX;
		
		// Is strOutput an error message? If so the message is returned minus
		// the prefix string.
		if (substr($strOutput, 0, strlen($strPrefix)) == $strPrefix)
			return substr($strOutput, strlen($strPrefix), (strlen($strOutput) - strlen($strPrefix)));
		else
			// Othrwise an empty string is returned to indicate no error.
			return "";
	}
	
