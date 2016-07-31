FileCompile.php

Script provides a set of functions that allow you to compile a number of
files into a single output string.


Purpose:

The object is to provide a means by which .html, .php, .css and .js files
can be loaded and compiled together to allow for modular structuring of
resources.

For example, one might wish to separate their .css code over several files
but avoid multiple HTTP requests for each individual file.


Usage:

	FileCompile($strBase, $strPath, $fileList[]);

The $strBase is a string that points to the base or root directory where
the files reside. This can be set to NULL and the default "resources" is
used.

The $strPath parameter allows us to specify a style or theme directory
which resides within $strBase.

For example, we may store everything in the base directory "resources"
which contains a directory named "default".

The point is to provide a fallback option - IF the $strPath parameter is
NULL or an empty string - then the FileCompile() function will only look
for files in $strBase.

However, if the $strPath string is NOT empty, FileCompile() will first
look for a file in $strBase/$strPath, if this file isn't found it will
look for the file directly within $strBase, instead.

This allows us to build our own 'themes' using a combination of custom and
standard resource files.


Return:

FileCompile() will return a string on both success and error.

The FileCompileError() function will handle any errors - simply put, the
FileCompile() function will return a string with the prefix

	"ERR: "

Followed by a specific error message if there was indeed an error loading
a specific file in the list.

If the "ERR: " prefix is not returned, all files in the list were found,
loaded and compiled - the output string (compiled file data) is returned.


Example: 

This script could be used to compile the .css files into a single string
which can then be output:

	<?php
		$cssFiles = array(
			0 => "common.css",
			1 => "header.css",
			2 => "footer.css"
		);

		$cssData = FileCompile("resources", "default", $cssFiles);

		// The FileCompileError() function will return an error
		// message if an error occured, or it will return an empty
		// string.
		//
		if (($strError = FileCompileError($cssData)) != "") {
			echo "FileCompile() error: " . $strError;
			die();
		}

		// Success, output the css data.
		echo "<style type=\"text/css\">";
		echo $cssData;
		echo "</style>";


Written by M. Nealon (Nunchy) 2016.

OA
