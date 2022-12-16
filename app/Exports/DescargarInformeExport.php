<?php

namespace App\Exports;

use App\Models\DigitalOrdenCompra;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DescargarInformeExport implements FromArray, ShouldAutoSize, WithStyles
{
    use Exportable;
    protected $id;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1   => ['font' => ['bold' => true]],
        ];
    }

    public function array(): array
    {
        $array[] =  ['Identificador de venta',  'Nombre entrada', 'Identificador', 'Consecutivo', 'Palco', 'Asiento', 'Comprador', 'Endosado', 'Estado', 'Fecha vendido'];
        foreach($this->data as $ent){
            $array[] = array(
               'Identificador de venta' => $ent['orden_compra_identificador'],
               'Nombre entrada' => $ent['nombre_entrada'],
               'Identificador' => $ent['Identificador'],
               'Consecutivo' => $ent['Consecutivo'],
               'Palco' => $ent['Palco'],
               'Asiento' => $ent['Asiento'],
               'Comprador' => $ent['comprador'],
               'Endosado' => $ent['endosado'],
               'Estado' => $ent['estado'] == 0 ? 'Pendiente' : 'Leida',
               'Fecha vendido' => $ent['fecha_vendido']
            );
        }
       
        return [$array];
    }
}
