
<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <style type="text/css">

      body
      {
        background: rgb(237, 237, 237, 1);
      }

      h2
      {

      }

      .contenedor
      {
        margin: 50px auto;
      }

    </style>
  </head>
  <body>
    <?php
    $serverName = "AXELEA7CFE\MSSQLSERVER02";
    //$serverName = "WIN-UDOPKRI9RC1\MSSQLSERVER_SMBK"; //serverName\instanceName

    // Since UID and PWD are not specified in the $connectionInfo array,
    // The connection will be attempted using Windows Authentication.
    $connectionInfo = array(
    "Database"=>"DESARROLLO",
    "CharacterSet" => "UTF-8");
    $conn = sqlsrv_connect( $serverName, $connectionInfo);

    if( !$conn )
    {
        if( ($errors = sqlsrv_errors() ) != null)
        {
            foreach( $errors as $error )
            {
                echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
                echo "code: ".$error[ 'code']."<br />";
                echo "message: ".$error[ 'message']."<br />";
            }
        }
    }
    else
    {
        echo "Conectado a PRUEBAS/SMBK";
    }


      //$serverNa
