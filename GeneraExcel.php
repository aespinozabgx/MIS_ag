<?php

    $titulos = Array("CVE_CRED_IF", "CVE_CRED_ID_OFERTA", "NUM_REF_SHF", "NOM_CONJUNTO", "NOM_PROMOTOR", "TIPO_CREDITO", "UBICACION_ESTADO", "UBICACION_MUNICIPIO", "FECH_INI_CONTRATO", "LINEA_DE_CREDITO_POR_PROYECTO", "VALOR_PROYECTO", "TASA_INTERES", "VIVIENDAS_TOTALES_DEL_PROYECTO", "FECH_FIN_CONTRATO", "AO_VIV_ACTIVAS", "VIV_LIB_PERIODO", "MONTO_MIN_EN_EL_PERIODO", "MONTO_AMORT_EN_EL_PERIODO", "PROYECTO_MAY", "PROYECTO_MIN", "REP_MOR_INTERESES", "REP_INTPROV_INTERESES");
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->fromArray($matriz, NULL, 'A2', true);
    $sheet->fromArray($titulos, NULL, 'A1', true);


    $letras =  Array("A","B","C","D","E","F","G","H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "V", "W");
    for ($i = 0; $i < sizeof($titulos); $i++)
    {
      $spreadsheet->getActiveSheet()->getColumnDimension($letras[$i])->setWidth(25);
    }

    $spreadsheet->getDefaultStyle()->getFont()->setName('Helvetica');
    $spreadsheet->getDefaultStyle()->getFont()->setSize(13);



    $estiloColumnasEspecificas = [
                  'font' => [
                      'color' => array('rgb' => '000000'),
                      'size'  => 13,
                  ],
                  'alignment' => [
                      'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                  ],

              ];
    $spreadsheet->getActiveSheet()->getStyle('A:V')->applyFromArray($estiloColumnasEspecificas);

    $styleArray = [
                  'font' => [
                      'bold' => true,
                      'color' => array('rgb' => 'FFFFFF'),
                      'size'  => 15,
                  ],
                  'alignment' => [
                      'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                  ],
                  'borders' => [
                      'outline' => [
                          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                          'color' => array('argb' => 'FFFFFF'),
                      ],
                  ],
                  'fill' => [
                      'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                      'color' => [
                          'argb' => '2A7BD6',
                      ],
                  ],
              ];

    $spreadsheet->getActiveSheet()->getStyle('A1:V1')->applyFromArray($styleArray);


    $writer = new Xlsx($spreadsheet);
    //$writer->save('helloworld.xlsx');

    if ($writer->save('reportes/colateral_' . date("d-m-Y_h.i.s_00") . '.xlsx'))
    {
        echo "<br>Error reporte";
        header('Location: autorizaAccesos.php?msg=reporteError');
    }
    else
    {
      $linkRep = 'reportes/colateral_' . date("d-m-Y_h.i.s_00") . '.xlsx';
      echo "<br>Reporte generado.";
      //echo "<script>window.location.href = 'reportesAccesos/REP_ACCESOS_' . $fechaActual . '.xlsx'';</script>";
      header('Location: reportes/colateral_' . date("d-m-Y_h.i.s_00") . '.xlsx');
    }

 ?>
