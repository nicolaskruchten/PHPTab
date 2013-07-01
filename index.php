<?php
/*

PHPTab 2.0 Copyright Nicolas Kruchten 2004

    This file is part of PHPTab.

    PHPTab is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    PHPTab is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with PHPTab; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

session_start();
include("dbinfo.php"); # create db connection, set table prefix
include("dbcreation.php"); # create tables if not already there
include("helpers.php"); # functions to handle all form submissions
include("handlers.php"); # detect form submission, pass off to right helper, set message

$mode = isset($_GET['mode']) ? $_GET['mode'] : "mainpage";
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>PHPTab</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
    <link href="css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="index.php">PHPTab</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li <?php if($mode == "mainpage") echo 'class="active"'; ?>><a href="index.php">Main</a></li>
              <li <?php if($mode == "edit") echo 'class="active"'; ?>><a href="index.php?mode=edit">Edit Targets</a></li>
              <li <?php if($mode == "macros") echo 'class="active"'; ?>><a href="index.php?mode=macros">Show Macros</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">

<?php if(isset($_SESSION["message_type"])) { ?>

<div class="alert alert-<?php echo $_SESSION["message_type"]?>">
    <?php echo $_SESSION["message"]?>
</div>

<?php 
unset($_SESSION["message_type"]);
unset($_SESSION["message"]);
} ?>

<?php
if($mode == "edit")
{
	include("edittargets.php");
}
elseif($mode == "macros")
{
	include("showmacros.php");
}
elseif($mode == "mainpage")
{ 
	include("mainpage.php");
} 
?>
    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>

  </body>
</html>