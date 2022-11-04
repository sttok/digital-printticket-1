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

            // $ticket_digital = Ticket::find($entrada['id'])->forma_generar;

            // $entrada = OrderChild::where('ticket_id', $entrada['id'])->get();
            // foreach ($entrada as $item) {
            //     if ($item->status == '0') {
            //         $item->status = 'No leida';
            //     } elseif ($item->status == '1') {
            //         $item->status = 'Leida';
            //     }

            //     if ($item->mesas == '0' || $item->mesas == '') {
            //         $mesa = 'No';
            //     } else {
            //         $mesa = $item->mesas;
            //     }

            //     if ($item->asiento == '0' || $item->asiento == '') {
            //         $asiento = 'No';
            //     } else {
            //         $asiento = $item->asiento;
            //     }

            //     if($ticket_digital == 1){
            //         $concat = $item->identificador . '-'. Str::slug('ticket-'.$item->evento->name) . '-' . $item->consecutivo;
            //     }else{
            //         $concat = $item->identificador . '-'. Str::slug('ticket-'.$item->evento->name) . '-' . 'P'.$mesa.'A'.$asiento;
            //     }

            //     $array[] = array(
            //         'Entrada' => $item->evento->name,
            //         'Cliente' => $item->cliente->name . ' ' . $item->cliente->last_name,
            //         'Mesa' => $mesa,
            //         'Asiento' =>  $asiento,
            //         'Ticket Number' => $item->ticket_number,
            //         'Ticket Number2' => $item->ticket_number2,
            //         'Ticket Codigo Base64' => $item->ticket_hash3,
            //         'Ticket Codigo2 Base64' => $item->ticket_hash4,
            //         'Estado' => $item->status,
            //         'Consecutivo' => $item->consecutivo,
            //         'Salto' => $item->salto,
            //         'Identificador' => $item->identificador,
            //         'Concatenado' => $concat
            //     );
            // }
        }
       
        return [$array];
    }
}
