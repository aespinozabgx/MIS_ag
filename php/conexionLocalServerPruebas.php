
    <?php

    $serverName = "WIN-UDOPKRI9RC1\MSSQLSERVER_SMBK"; //serverName\instanceName


      $connectionInfo = array(
          "Database" => "ADMINISTRACION_TI",
               "UID" => "sa",
               "PWD" => "C09%1_e4_",
      "CharacterSet" => "UTF-8");

      $conn = sqlsrv_connect( $serverName, $connectionInfo );


      if( $conn === false )
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
          echo "Conectado a PRUEBAS";
      }


    ?>
