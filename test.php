<?php

  $total = 123.00;
  $total += 1;
  echo "TOTAL: " . $total . "<br>";

die;


require 'vendor/autoload.php';
require 'php/funciones.php';
require 'php/conexion.php';

$indice = 0;

$colateralSQL[$indice]['NOM_PROYECTO'] = 'PROYECTO NAME';
$colateralSQL[$indice]['FECH_COLATERAL'] = '2021-04-01 00:00:00.000';
$colateralSQL[$indice]['FECH_FIN_CONTRATO'] = '2021-04-01 00:00:00.000';
$colateralSQL[$indice]['AO_VIV_ACTIVAS'] = 88.34;
$colateralSQL[$indice]['VIV_LIB_CORTE_ANTERIOR'] = 12;
$colateralSQL[$indice]['VIV_LIB_PERIODO'] = 76;
$colateralSQL[$indice]['ACUM_VIV_LIB_FIN_P_ANTERIOR'] = 26;
$colateralSQL[$indice]['MONTO_MIN_ACUM_P_ANTERIOR'] = 42384.234;
$colateralSQL[$indice]['MONTO_MIN_EN_EL_PERIODO'] = 23482349.23;
$colateralSQL[$indice]['MONTO_MIN_ACUM_FIN_P_N'] = 542.23;
$colateralSQL[$indice]['MONTO_POR_DISPONER_N'] = 234556.33;
$colateralSQL[$indice]['MONTO_AMORT_ACUM_FIN_P_ANTERIOR'] = 23489025.45;
$colateralSQL[$indice]['MONTO_AMORT_EN_EL_PERIODO'] = 23408234.56;
$colateralSQL[$indice]['MONTO_AMORT_ACUM_FIN_P'] = 9128395.34;
$colateralSQL[$indice]['SALDO_INS_CARTERA_FIN_P'] = 82348.45;
$colateralSQL[$indice]['SALDO_INS_CARTERA_FIN_P_N'] = 23423501.32;
$colateralSQL[$indice]['INTERESES_COBRADOS_PERIODO'] = 0;
$colateralSQL[$indice]['COMISIONES_COBRADAS_PERIODO'] = 0;
$colateralSQL[$indice]['NUM_MESES_MOROSOS'] = 0;
$colateralSQL[$indice]['INTERESES_DEV_NO_CUBIERTOS'] = 2312445.23;




//$colateralSQL[$indice]['NUM_FIDEICOMISO'] = "111";
//$colateralSQL[$indice]['CVE_CRE_IF'] = "as8d465";
//$colateralSQL[$indice]['CVE_CRE_ID_OFERTA'] = "as4d5";
//$colateralSQL[$indice]['NUM_REF_SHF'] = "asd-asd-asd";
//$colateralSQL[$indice]['NOM_PROMOTOR'] = "PROMOTOR NAME";
//$colateralSQL[$indice]['TIPO_CREDITO'] = "Revolvente";
//$colateralSQL[$indice]['UBICACIÓN_EDO'] = "Estado de méxico";
//$colateralSQL[$indice]['UBICACIÓN_MUN'] = "nezahualcoyotl";
//$colateralSQL[$indice]['FECH_INI_CONTRATO'] = "2021-04-01 00.00.00.000";
//$colateralSQL[$indice]['LINEA_DE_CRE_POR_PROYECTO'] = 5656565.45;
//$colateralSQL[$indice]['VALOR_PROYECTO'] = 684686.45;
//$colateralSQL[$indice]['TASA_INTERES'] = "TIIE + 9.5";
//$colateralSQL[$indice]['VIV_TOTALES_PROYECTO'] = "3";















    $tsql = "EXEC MIS_insertaColateral ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?";

    $params = Array(
      $colateralSQL[$indice]['NOM_PROYECTO'],
      $colateralSQL[$indice]['FECH_COLATERAL'],
      $colateralSQL[$indice]['FECH_FIN_CONTRATO'],
      $colateralSQL[$indice]['AO_VIV_ACTIVAS'],
      $colateralSQL[$indice]['VIV_LIB_CORTE_ANTERIOR'],
      $colateralSQL[$indice]['VIV_LIB_PERIODO'],
      $colateralSQL[$indice]['ACUM_VIV_LIB_FIN_P_ANTERIOR'],
      $colateralSQL[$indice]['MONTO_MIN_ACUM_P_ANTERIOR'],
      $colateralSQL[$indice]['MONTO_MIN_EN_EL_PERIODO'],
      $colateralSQL[$indice]['MONTO_MIN_ACUM_FIN_P_N'],
      $colateralSQL[$indice]['MONTO_POR_DISPONER_N'],
      $colateralSQL[$indice]['MONTO_AMORT_ACUM_FIN_P_ANTERIOR'],
      $colateralSQL[$indice]['MONTO_AMORT_EN_EL_PERIODO'],
      $colateralSQL[$indice]['MONTO_AMORT_ACUM_FIN_P'],
      $colateralSQL[$indice]['SALDO_INS_CARTERA_FIN_P'],
      $colateralSQL[$indice]['SALDO_INS_CARTERA_FIN_P_N'],
      $colateralSQL[$indice]['INTERESES_COBRADOS_PERIODO'],
      $colateralSQL[$indice]['COMISIONES_COBRADAS_PERIODO'],
      $colateralSQL[$indice]['NUM_MESES_MOROSOS'],
      $colateralSQL[$indice]['INTERESES_DEV_NO_CUBIERTOS']
    );


    // $params = Array($colateralSQL[$indice][0],  $colateralSQL[$indice][1],  $colateralSQL[$indice][2],  $colateralSQL[$indice][3],  $colateralSQL[$indice][4],  $colateralSQL[$indice][5],
    //                 $colateralSQL[$indice][6],  $colateralSQL[$indice][7],  $colateralSQL[$indice][8],  $colateralSQL[$indice][9],  $colateralSQL[$indice][10], $colateralSQL[$indice][11],
    //                 $colateralSQL[$indice][12], $colateralSQL[$indice][13], $colateralSQL[$indice][14], $colateralSQL[$indice][15], $colateralSQL[$indice][19], $colateralSQL[$indice][17],
    //                 $colateralSQL[$indice][18], $colateralSQL[$indice][19]);

    $stmt = sqlsrv_query($conn, $tsql, $params);

    if( !$stmt)
    {
        if(($errors = sqlsrv_errors()) != null)
        {
            foreach($errors as $error)
            {
                echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
                echo "code: ".$error[ 'code']."<br />";
                echo "message: ".$error[ 'message']."<br />";
            }
        }
    }
    else
    {
        echo "Insertado. <br>";
    }


?>
