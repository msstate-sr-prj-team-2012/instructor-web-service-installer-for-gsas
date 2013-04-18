<?php

// createdb.php
// 
// script used to create the necessary databases to run OnPar
// it won't overwrite any existent database

// For importing a SQL file
// Function from http://mcamady.blogspot.com/2012/07/import-sql-file-in-to-mysql-database.html
/* Accepts a filename and imports the SQL script in it.

   Returns: true if all is well
            false if something is wrong
            (error message is embedded in $errmsg)

   One can also use mysql_error() if this function
   returns an error.

*/

function mysql_import_file($filename, &$errmsg)
{
   /* Read the file */
   $lines = file($filename);

   if(!$lines)
   {
      $errmsg = "cannot open file $filename";
      return false;
   }

   $scriptfile = false;

   /* Get rid of the comments and form one jumbo line */
   foreach($lines as $line)
   {
      $line = trim($line);

      if(!ereg('^--', $line))
      {
         $scriptfile.=" ".$line;
      }
   }

   if(!$scriptfile)
   {
      $errmsg = "no text found in $filename";
      return false;
   }

   /* Split the jumbo line into smaller lines */

   $queries = explode(';', $scriptfile);

   /* Run each line as a query */

   foreach($queries as $query)
   {
      $query = trim($query);
      if($query == "") { continue; }
      if(!mysql_query($query.';'))
      {
         $errmsg = "query ".$query." failed";
         return false;
      }
   }

   /* All is well */
   return true;
}

/* Installs a DB with a given name with the help of a given
   .sql file

   Returns: true if all is well
       false if something is wrong
            (error message is embedded in $errmsg)

   One can also use mysql_error() if this function
   returns an error.

*/

function mysql_install_db($dbname, $dbsqlfile, &$errmsg)
{
   $result = true;

   if(!mysql_select_db($dbname))
   {
     $result = mysql_query("CREATE DATABASE $dbname");
     if(!$result)
     {
        $errmsg = "could not create [$dbname] db in mysql";
        return false;
     }
     $result = mysql_select_db($dbname);
   }

   if(!$result)
   {
      $errmsg = "could not select [$dbname] database in mysql";
      return false;
   }

   $result = mysql_import_file($dbsqlfile, $errmsg);

   return $result;
}

if ( $db = mysql_connect('@@BITNAMI_DATABASE_HOST@@:@@BITNAMI_DATABASE_PORT@@', 'root', '@@BITNAMI_DATABASE_ROOT_PASSWORD@@') )
{

	if( mysql_install_db("@@BITNAMI_DATABASE_NAME@@", "@@BITNAMI_DATABASE_SCHEMA@@", $errmsg) )
	{
		 echo "Success!!";
		 mysql_query("GRANT ALL PRIVILEGES ON @@BITNAMI_DATABASE_NAME@@.* TO '@@BITNAMI_DATABASE_USER@@'@'@@BITNAMI_DATABASE_HOST@@' IDENTIFIED BY '@@BITNAMI_DATABASE_PASSWORD@@';");
		 mysql_query("FLUSH PRIVILEGES;");
	}
	else
	{
		echo "failure: ".$errmsg."<br/>".mysql_error(); 
	}

}
else
{
  die("There was an error creating the database or setting the appropriate privileges");
}

