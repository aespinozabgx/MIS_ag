<!DOCTYPE html>
<html>
<head>
<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
</style>
</head>
<body>

<?php

require 'vendor/autoload.php';
require 'php/funciones.php';
require 'php/conexion.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


    if (isset($_POST['btnFiltra']))
    {
      header('Location: index.php?date=' . $_POST['fechaReporte']);
    }

    if (isset($_POST['cargaDatos']))
    {
        $mesActualziar = $_POST['toUpdate'];
        $mesActualziar = date("Y-m-d",strtotime($mesActualziar));

        if (validaCargaColateral($conn, $mesActualziar))
        {
          die("Ya existen datos cargados");
        }

        $fechaPorciones = explode(' ', $mesActualziar);
        $onlyDate = $fechaPorciones[0];
        $onlyDate = explode('-', $mesActualziar);
        $mes = $onlyDate[1];
        $mes -= 1;


        $monthNum = $mes;
        $dateObj = DateTime::createFromFormat('!m', $monthNum);
        $monthName = $dateObj->format('m');
        $preMonth = $onlyDate[0] . "-" . $monthName. "-01";

        echo "<br>Month to update: " .  $mesActualziar;
        echo "<br>Prev month: " . $preMonth . "<br>";




        // STAR OF REPORT MATRIZ

        $T_proyectos     = Array();  // Matriz de proyectos base (DB)
        $T_repIntProv    = Array();  // Matriz de reporte de intereses y provisiones
        $T_repMorosidad  = Array();  // Matriz de reporte de morosidad
        $T_SHF           = Array();  // Matriz de datos SHF
        $T_intermedia    = Array();  // Matriz de tabla intermedia

        // END OF REPORT MATRIX


        readReporteSHF($conn);
        readReporteMorosidad($conn);
        readReporteIntProv($conn);


        //$T_proyectos     = readProyectosDB($conn);
        //$T_intermedia    = readTablaIntermedia($conn);

        //echo "<br><br>";

        // $tsql = "SELECT
        // MIS_CAT_proyectos.COLATERAL,
        // MIS_CAT_proyectos.CVE_CRE_IF,
        // MIS_CAT_proyectos.CVE_CRE_ID_OFERTA,
        // MIS_CAT_proyectos.NUM_REF_SHF,
        // MIS_CAT_proyectos.NOM_PROYECTO,
        // MIS_CAT_proyectos.NOM_PROMOTOR,
        // MIS_CAT_proyectos.TIPO_CREDITO,
        // MIS_CAT_proyectos.UBICACIÓN_EDO,
        // MIS_CAT_proyectos.UBICACIÓN_MUN,
        // MIS_CAT_proyectos.FECH_INI_CONTRATO,
        // MIS_CAT_proyectos.LINEA_DE_CRE_POR_PROYECTO,
        // MIS_CAT_proyectos.VALOR_PROYECTO,
        // MIS_CAT_proyectos.TASA_INTERES,
        // MIS_CAT_proyectos.VIV_TOTALES_PROYECTO,
        // MIS_temp_shf.FECH_FIN_CONTRATO,
        // MIS_temp_shf.AO_VIV_ACTIVAS,
        // MIS_temp_shf.VIV_LIB_PERIODO,
        // MIS_temp_shf.MONTO_MIN_EN_EL_PERIODO,
        // MIS_temp_shf.MONTO_AMORT_EN_EL_PERIODO,
        // /* monto amort acum fin periodo */
        // /* acum viv lib a fin periodo */
        // /* monto min acum fin periodo */
        // /* monto por disponer */
        // /* saldo ins cartera fin periodo */
        //
        // /* Viviendas Liberadas al Corte Anterior  */
        // /* monto amort acum periodo ant */
        // /* saldo ins periodo ant */
        // /* monto min acum periodo ant */
        // /* comisiones cobradas */
        // MIS_temp_intprov.INTERESES AS INTERESES_COBRADOS_PERIODO,
        // /* meses morosos */
        // MIS_temp_morosidad.INT_DEVENGADO AS INTERESES_DEV_NO_CUBIERTOS
        // FROM MIS_temp_morosidad
        // INNER JOIN MIS_TABLA_INTERMEDIA ON MIS_TABLA_INTERMEDIA.DOS = MIS_temp_morosidad.PROYECTO
        // INNER JOIN MIS_temp_intprov ON MIS_temp_intprov.PROYECTO = MIS_TABLA_INTERMEDIA.DOS
        // INNER JOIN MIS_CAT_proyectos ON MIS_CAT_proyectos.NOM_PROYECTO = MIS_TABLA_INTERMEDIA.UNO
        // INNER JOIN MIS_temp_shf ON MIS_temp_shf.NOM_CONJUNTO = MIS_TABLA_INTERMEDIA.UNO";

        $tsql = "EXEC MIS_generaColateral";
        $stmt = sqlsrv_query($conn, $tsql);
        if( $stmt === false)
        {
             echo "Error in query preparation/execution.\n";
             die( print_r( sqlsrv_errors(), true));
        }


        $contador = 0;

        while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_BOTH))
        {

              $matriz[$contador]['COLATERAL']                      = $row['COLATERAL'];
              $matriz[$contador]['CVE_CRE_IF']                     = $row['CVE_CRE_IF'];
              $matriz[$contador]['CVE_CRE_ID_OFERTA']              = $row['CVE_CRE_ID_OFERTA'];
              $matriz[$contador]['NUM_REF_SHF']                    = $row['NUM_REF_SHF'];
              $matriz[$contador]['NOM_PROYECTO']                   = $row['NOM_PROYECTO'];
              $matriz[$contador]['NOM_PROMOTOR']                   = $row['NOM_PROMOTOR'];
              $matriz[$contador]['TIPO_CREDITO']                   = $row['TIPO_CREDITO'];
              $matriz[$contador]['UBICACIÓN_EDO']                  = $row['UBICACIÓN_EDO'];
              $matriz[$contador]['UBICACIÓN_MUN']                  = $row['UBICACIÓN_MUN'];
              $matriz[$contador]['FECH_INI_CONTRATO']              = $row['FECH_INI_CONTRATO'];
              $matriz[$contador]['LINEA_DE_CRE_POR_PROYECTO']      = $row['LINEA_DE_CRE_POR_PROYECTO'];
              $matriz[$contador]['VALOR_PROYECTO']                 = $row['VALOR_PROYECTO'];
              $matriz[$contador]['TASA_INTERES']                   = $row['TASA_INTERES'];
              $matriz[$contador]['VIV_TOTALES_PROYECTO']           = $row['VIV_TOTALES_PROYECTO'];
              $matriz[$contador]['FECH_FIN_CONTRATO']              = $row['FECH_FIN_CONTRATO'];
              $matriz[$contador]['AO_VIV_ACTIVAS']                 = $row['AO_VIV_ACTIVAS'];
              $matriz[$contador]['VIV_LIB_PERIODO']                = $row['VIV_LIB_PERIODO'];

              /* shf */
              $matriz[$contador]['MONTO_MIN_EN_EL_PERIODO']        = $row['MONTO_MIN_EN_EL_PERIODO'];
              $matriz[$contador]['MONTO_AMORT_EN_EL_PERIODO']      = $row['MONTO_AMORT_EN_EL_PERIODO'];
              $matriz[$contador]['INTERESES_COBRADOS_PERIODO']     = $row['INTERESES_COBRADOS_PERIODO'];
              $matriz[$contador]['INTERESES_DEV_NO_CUBIERTOS']     = $row['INTERESES_DEV_NO_CUBIERTOS'];

              $contador++;

        }



        /* Elimino tablas temporales */
        $tsql = "DELETE FROM MIS_temp_intprov; DELETE FROM MIS_temp_morosidad; DELETE FROM MIS_temp_shf";

        /* Set parameter values. */
        $params = array();

        /* Prepare and execute the query. */
        $stmt = sqlsrv_query($conn, $tsql, $params);
        if ($stmt)
        {
            echo "<br><br>Tablas temporales vacías.<br>";
        }
        else
        {
            echo "<br><br>Error al vaciar tablas temporales.<br>";
            die(print_r(sqlsrv_errors(), true));
        }


        /*UNO LA INFORAMCIÓN */

        $tsql = "EXEC MIS_obtieneMesPrevio ?";
        $params = Array($preMonth);
        $stmt = sqlsrv_query($conn, $tsql, $params);
        if( $stmt === false)
        {
             echo "Error in query execution.<br>";
             die( print_r( sqlsrv_errors(), true));
        }

        /* Retrieve each row as an associative array and display the results.*/
        $c = 0;
        $datosMesPrevio = Array();
        while( $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
        {
            //echo $row['NOM_PROYECTO'] . "<br>";
            $datosMesPrevio[$c]['NOM_PROYECTO']                   = $row['NOM_PROYECTO'];
            $datosMesPrevio[$c]['FECH_COLATERAL']                 = date_format($row['FECH_COLATERAL'], "d-m-Y");
            $datosMesPrevio[$c]['COLATERAL']                      = $row['COLATERAL'];
            $datosMesPrevio[$c]['VIV_LIB_CORTE_ANTERIOR']         = $row['VIV_LIB_CORTE_ANTERIOR'];
            $datosMesPrevio[$c]['ACUM_VIV_LIB_FIN_P']             = $row['ACUM_VIV_LIB_FIN_P'];
            $datosMesPrevio[$c]['MONTO_MIN_ACUM_P_ANTERIOR']      = $row['MONTO_MIN_ACUM_P_ANTERIOR'];
            $datosMesPrevio[$c]['MONTO_MIN_ACUM_FIN_P']           = $row['MONTO_MIN_ACUM_FIN_P'];
            $datosMesPrevio[$c]['MONTO_POR_DISPONER']             = $row['MONTO_POR_DISPONER'];
            $datosMesPrevio[$c]['MONTO_AMORT_ACUM_P_ANTERIOR']    = $row['MONTO_AMORT_ACUM_P_ANTERIOR'];
            $datosMesPrevio[$c]['MONTO_AMORT_ACUM_FIN_P']         = $row['MONTO_AMORT_ACUM_FIN_P'];
            $datosMesPrevio[$c]['SALDO_INS_P_ANTERIOR']           = $row['SALDO_INS_P_ANTERIOR'];
            $datosMesPrevio[$c]['SALDO_INS_CARTERA_FIN_P']        = $row['SALDO_INS_CARTERA_FIN_P'];
            $datosMesPrevio[$c]['COMISIONES_COBRADAS_PERIODO']    = $row['COMISIONES_COBRADAS_PERIODO'];
            $datosMesPrevio[$c]['NUM_MESES_MOROSOS']              = $row['NUM_MESES_MOROSOS'];
            $c++;
        }

        // echo "<pre>";
        // var_dump($datosMesPrevio);
        // echo "</pre>";
        // die("Terminated");

        /* FIN UNO LA INFORMACIÓN */
        $matriz_row = count($matriz);
        $matriz_col = max(array_map('count', $matriz));

        echo "<br>Matriz row: " . $matriz_row;
        echo "<br>Matriz col: " . $matriz_col;

        $datosMesPrevio_row = count($datosMesPrevio);
        $datosMesPrevio_col = max(array_map('count', $datosMesPrevio));

        echo "<br>Datos MP row: " . $matriz_row;
        echo "<br>Datos MP col: " . $matriz_col;
        echo "<br><br>";

        $colateralSQL = Array();
        for ($cont=0; $cont < $matriz_row; $cont++)
        {

            for ($i=0; $i < $datosMesPrevio_row; $i++)
            {

                if ($matriz[$cont]['NOM_PROYECTO'] == $datosMesPrevio[$i]['NOM_PROYECTO'])
                {
                    //echo "<hr><br>";
                    echo $cont . ") Encontrado -> " . $matriz[$cont]['NOM_PROYECTO'] . " <b>::</b> " . $datosMesPrevio[$i]['NOM_PROYECTO'] . "<br><br>";

                    $colateralSQL[$cont]['FECH_COLATERAL']    = date('Y-m-01', strtotime($mesActualziar));
                    $colateralSQL[$cont]['NUM_FIDEICOMISO']   = $matriz[$cont]['COLATERAL'];
                    $colateralSQL[$cont]['CVE_CRE_IF']        = $matriz[$cont]['CVE_CRE_IF'];
                    $colateralSQL[$cont]['CVE_CRE_ID_OFERTA'] = $matriz[$cont]['CVE_CRE_ID_OFERTA'];
                    $colateralSQL[$cont]['NUM_REF_SHF']       = $matriz[$cont]['NUM_REF_SHF'];
                    $colateralSQL[$cont]['NOM_PROYECTO']      = $matriz[$cont]['NOM_PROYECTO'];
                    $colateralSQL[$cont]['NOM_PROMOTOR']      = $matriz[$cont]['NOM_PROMOTOR'];
                    $colateralSQL[$cont]['TIPO_CREDITO']      = $matriz[$cont]['TIPO_CREDITO'];
                    $colateralSQL[$cont]['UBICACIÓN_EDO']     = $matriz[$cont]['UBICACIÓN_EDO'];
                    $colateralSQL[$cont]['UBICACIÓN_MUN']     = $matriz[$cont]['UBICACIÓN_MUN'];
                    $colateralSQL[$cont]['FECH_INI_CONTRATO']          = date_format($matriz[$cont]['FECH_INI_CONTRATO'], "Y-m-d");
                    $colateralSQL[$cont]['LINEA_DE_CRE_POR_PROYECTO']  = number_format($matriz[$cont]['LINEA_DE_CRE_POR_PROYECTO'], 2);
                    $colateralSQL[$cont]['VALOR_PROYECTO']             = number_format($matriz[$cont]['VALOR_PROYECTO'], 2);
                    $colateralSQL[$cont]['TASA_INTERES']               = $matriz[$cont]['TASA_INTERES'];
                    $colateralSQL[$cont]['VIV_TOTALES_PROYECTO']       = $matriz[$cont]['VIV_TOTALES_PROYECTO'];
                    $colateralSQL[$cont]['FECH_FIN_CONTRATO']          = date_format($matriz[$cont]['FECH_FIN_CONTRATO'], "Y-m-d");
                    $colateralSQL[$cont]['AO_VIV_ACTIVAS']             = $matriz[$cont]['AO_VIV_ACTIVAS'];
                    $colateralSQL[$cont]['VIV_LIB_PERIODO']            = $matriz[$cont]['VIV_LIB_PERIODO'];
                    $colateralSQL[$cont]['MONTO_MIN_EN_EL_PERIODO']    = number_format($matriz[$cont]['MONTO_MIN_EN_EL_PERIODO'], 2);
                    $colateralSQL[$cont]['MONTO_AMORT_EN_EL_PERIODO']  = number_format($matriz[$cont]['MONTO_AMORT_EN_EL_PERIODO'], 2);

                    /* SUMA DE COLUMNAS */
                    $colateralSQL[$cont]['MONTO_AMORT_ACUM_FIN_P_ANTERIOR']  = number_format(($datosMesPrevio[$i]['MONTO_AMORT_ACUM_P_ANTERIOR'] + $matriz[$cont]['MONTO_AMORT_EN_EL_PERIODO']), 2);
                    $colateralSQL[$cont]['ACUM_VIV_LIB_FIN_P_ANTERIOR']      = ($datosMesPrevio[$i]['VIV_LIB_CORTE_ANTERIOR']                    + $matriz[$cont]['VIV_LIB_PERIODO']);
                    $colateralSQL[$cont]['MONTO_MIN_ACUM_FIN_P_N']           = number_format(($datosMesPrevio[$i]['MONTO_MIN_ACUM_P_ANTERIOR']   + $matriz[$cont]['MONTO_MIN_EN_EL_PERIODO']), 2);
                    $colateralSQL[$cont]['MONTO_POR_DISPONER_N']             = number_format(($datosMesPrevio[$i]['MONTO_MIN_ACUM_FIN_P']        + $matriz[$cont]['MONTO_MIN_EN_EL_PERIODO']), 2);
                    $colateralSQL[$cont]['SALDO_INS_CARTERA_FIN_P_N']        = number_format((($matriz[$cont]['MONTO_MIN_EN_EL_PERIODO']         - $matriz[$cont]['MONTO_AMORT_EN_EL_PERIODO']) + $datosMesPrevio[$i]['SALDO_INS_P_ANTERIOR']), 2);
                    /* FIN SUMA DE COLUMNAS */

                    $colateralSQL[$cont]['VIV_LIB_CORTE_ANTERIOR']           = $datosMesPrevio[$i]['VIV_LIB_CORTE_ANTERIOR'];
                    $colateralSQL[$cont]['MONTO_AMORT_ACUM_FIN_P']           = number_format($datosMesPrevio[$i]['MONTO_AMORT_ACUM_FIN_P'], 2);
                    $colateralSQL[$cont]['SALDO_INS_CARTERA_FIN_P']          = number_format($datosMesPrevio[$i]['SALDO_INS_CARTERA_FIN_P'], 2);
                    $colateralSQL[$cont]['MONTO_MIN_ACUM_P_ANTERIOR']        = number_format($datosMesPrevio[$i]['MONTO_MIN_ACUM_P_ANTERIOR'], 2);
                    $colateralSQL[$cont]['COMISIONES_COBRADAS_PERIODO']      = number_format(0,2);
                    $colateralSQL[$cont]['INTERESES_COBRADOS_PERIODO']       = number_format($matriz[$cont]['INTERESES_COBRADOS_PERIODO'], 2);
                    $colateralSQL[$cont]['NUM_MESES_MOROSOS']                = $datosMesPrevio[$i]['NUM_MESES_MOROSOS'];
                    $colateralSQL[$cont]['INTERESES_DEV_NO_CUBIERTOS']       = number_format($matriz[$cont]['INTERESES_DEV_NO_CUBIERTOS'], 2) ;

                    // echo "<br><b>NOM_PROYECTO:</b> " . $datosMesPrevio[$i]['NOM_PROYECTO'];
                    // echo "<br><b>FECH_COLATERAL:</b> " . "Fecha: " . $datosMesPrevio[$i]['FECH_COLATERAL'];
                    // echo "<br><b>MONTO_POR_DISPONER:</b> " . $datosMesPrevio[$i]['MONTO_POR_DISPONER'];
                    // echo "<br><b>ACUM_VIV_LIB_FIN_P:</b> " . $datosMesPrevio[$i]['ACUM_VIV_LIB_FIN_P'];
                    // echo "<br><b>COMISIONES_COBRADAS_PERIODO:</b> " . $datosMesPrevio[$i]['COMISIONES_COBRADAS_PERIODO'];
                    // echo " <br><br> ";

                }
            }
        }


        // echo "<pre>";
        // var_dump($colateralSQL);
        // echo "</pre>";
        // die;

        $b  = 1;
        echo "<br>";
        //Obtengo indices de la matriz
        $keys = array_keys($colateralSQL);

        //Itero casa posicion con el indice ya que es un pedo re indexar la matriz
        foreach ($keys as $indice)
        {

           // $colateralSQL[$indice]['FECH_COLATERAL'] .= " 00:00:00.000";
           // $colateralSQL[$indice]['FECH_FIN_CONTRATO'] .= " 00:00:00.000";

          echo "<table >";
          echo "<tr>";
            echo "<td> NOM_PROYECTO </td>";
            echo "<td> FECH_COLATERAL </td>";
            echo "<td> FECH_FIN_CONTRATO </td>";
            echo "<td> AO_VIV_ACTIVAS </td>";
            echo "<td> VIV_LIB_CORTE_ANTERIOR </td>";
            echo "<td> VIV_LIB_PERIODO </td>";
            echo "<td> ACUM_VIV_LIB_FIN_P_ANTERIOR </td>";
            echo "<td> MONTO_MIN_ACUM_P_ANTERIOR </td>";
            echo "<td> MONTO_MIN_EN_EL_PERIODO </td>";
            echo "<td> MONTO_MIN_ACUM_FIN_P_N </td>";
            echo "<td> MONTO_POR_DISPONER_N </td>";
            echo "<td> MONTO_AMORT_ACUM_FIN_P_ANTERIOR </td>";
            echo "<td> MONTO_AMORT_EN_EL_PERIODO </td>";
            echo "<td> MONTO_AMORT_ACUM_FIN_P </td>";
            echo "<td> SALDO_INS_CARTERA_FIN_P </td>";
            echo "<td> SALDO_INS_CARTERA_FIN_P_N </td>";
            echo "<td> INTERESES_COBRADOS_PERIODO </td>";
            echo "<td> COMISIONES_COBRADAS_PERIODO </td>";
            echo "<td> NUM_MESES_MOROSOS </td>";
            echo "<td> INTERESES_DEV_NO_CUBIERTOS </td>";
          echo "</tr>";


          echo "<tr>";
            echo "<td>" . $colateralSQL[$indice]['NOM_PROYECTO'] . "</td>";
            echo "<td>" . $colateralSQL[$indice]['FECH_COLATERAL'] . "</td>";
            echo "<td>" . $colateralSQL[$indice]['FECH_FIN_CONTRATO'] . "</td>";
            echo "<td>" . $colateralSQL[$indice]['AO_VIV_ACTIVAS'] . "</td>";
            echo "<td>" . $colateralSQL[$indice]['VIV_LIB_CORTE_ANTERIOR'] . "</td>";
            echo "<td>" . $colateralSQL[$indice]['VIV_LIB_PERIODO'] . "</td>";
            echo "<td>" . $colateralSQL[$indice]['ACUM_VIV_LIB_FIN_P_ANTERIOR'] . "</td>";
            echo "<td>" . $colateralSQL[$indice]['MONTO_MIN_ACUM_P_ANTERIOR'] . "</td>";
            echo "<td>" . $colateralSQL[$indice]['MONTO_MIN_EN_EL_PERIODO'] . "</td>";
            echo "<td>" . $colateralSQL[$indice]['MONTO_MIN_ACUM_FIN_P_N'] . "</td>";
            echo "<td>" . $colateralSQL[$indice]['MONTO_POR_DISPONER_N'] . "</td>";
            echo "<td>" . $colateralSQL[$indice]['MONTO_AMORT_ACUM_FIN_P_ANTERIOR'] . "</td>";
            echo "<td>" . $colateralSQL[$indice]['MONTO_AMORT_EN_EL_PERIODO'] . "</td>";
            echo "<td>" . $colateralSQL[$indice]['MONTO_AMORT_ACUM_FIN_P'] . "</td>";
            echo "<td>" . $colateralSQL[$indice]['SALDO_INS_CARTERA_FIN_P'] . "</td>";
            echo "<td>" . $colateralSQL[$indice]['SALDO_INS_CARTERA_FIN_P_N'] . "</td>";
            echo "<td>" . $colateralSQL[$indice]['INTERESES_COBRADOS_PERIODO'] . "</td>";
            echo "<td>" . $colateralSQL[$indice]['COMISIONES_COBRADAS_PERIODO'] . "</td>";
            echo "<td>" . $colateralSQL[$indice]['NUM_MESES_MOROSOS'] . "</td>";
            echo "<td>" . $colateralSQL[$indice]['INTERESES_DEV_NO_CUBIERTOS'] . "</td>";
          echo "</tr>";

          echo "</table>"; 


            // $tsql = "EXEC MIS_insertaColateral ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?";
            //
            // $params = Array(
            //     $colateralSQL[$indice]['NOM_PROYECTO'],
            //     $colateralSQL[$indice]['FECH_COLATERAL'],
            //     $colateralSQL[$indice]['FECH_FIN_CONTRATO'],
            //     $colateralSQL[$indice]['AO_VIV_ACTIVAS'],
            //     $colateralSQL[$indice]['VIV_LIB_CORTE_ANTERIOR'],
            //     $colateralSQL[$indice]['VIV_LIB_PERIODO'],
            //     $colateralSQL[$indice]['ACUM_VIV_LIB_FIN_P_ANTERIOR'],
            //     0,
            //     0,
            //     0,
            //     0,
            //     0,
            //     0,
            //     0,
            //     0,
            //     0,
            //     0,
            //     0,
            //     0,
            //     0,
            //     // $colateralSQL[$indice]['MONTO_MIN_ACUM_P_ANTERIOR'],
            //     // $colateralSQL[$indice]['MONTO_MIN_EN_EL_PERIODO'],
            //     // $colateralSQL[$indice]['MONTO_MIN_ACUM_FIN_P_N'],
            //     // $colateralSQL[$indice]['MONTO_POR_DISPONER_N'],
            //     // $colateralSQL[$indice]['MONTO_AMORT_ACUM_FIN_P_ANTERIOR'],
            //     // $colateralSQL[$indice]['MONTO_AMORT_EN_EL_PERIODO'],
            //     // $colateralSQL[$indice]['MONTO_AMORT_ACUM_FIN_P'],
            //     // $colateralSQL[$indice]['SALDO_INS_CARTERA_FIN_P'],
            //     // $colateralSQL[$indice]['SALDO_INS_CARTERA_FIN_P_N'],
            //     // $colateralSQL[$indice]['INTERESES_COBRADOS_PERIODO'],
            //     // $colateralSQL[$indice]['COMISIONES_COBRADAS_PERIODO'],
            //     // $colateralSQL[$indice]['NUM_MESES_MOROSOS'],
            //     // $colateralSQL[$indice]['INTERESES_DEV_NO_CUBIERTOS']
            // );


            // $params = Array($colateralSQL[$indice][0],  $colateralSQL[$indice][1],  $colateralSQL[$indice][2],  $colateralSQL[$indice][3],  $colateralSQL[$indice][4],  $colateralSQL[$indice][5],
            //                 $colateralSQL[$indice][6],  $colateralSQL[$indice][7],  $colateralSQL[$indice][8],  $colateralSQL[$indice][9],  $colateralSQL[$indice][10], $colateralSQL[$indice][11],
            //                 $colateralSQL[$indice][12], $colateralSQL[$indice][13], $colateralSQL[$indice][14], $colateralSQL[$indice][15], $colateralSQL[$indice][19], $colateralSQL[$indice][17],
            //                 $colateralSQL[$indice][18], $colateralSQL[$indice][19]);

            // $stmt = sqlsrv_query($conn, $tsql, $params);
            //
            // if( !$stmt)
            // {
            //     if(($errors = sqlsrv_errors()) != null)
            //     {
            //         foreach($errors as $error)
            //         {
            //             echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
            //             echo "code: ".$error[ 'code']."<br />";
            //             echo "message: ".$error[ 'message']."<br />";
            //         }
            //     }
            // }
            // else
            // {
            //     echo $b . ", insertado. <br>";
            // }
            //
            // $b++;

        }


        die("<br><br>Terminated");

    } // END OF CARGA DE DATOS

?>

</body>
</html>
