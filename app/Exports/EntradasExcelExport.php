<?php

namespace App\Exports;

use App\Models\Ticket;
use App\Models\OrderChild;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EntradasExcelExport implements  FromArray, ShouldAutoSize, WithStyles
{
    use Exportable;
    protected $data;

    public function __construct($data)
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
        
        $array[] =  ['Entrada',  'Cliente', 'Mesa', 'Asiento', 'Ticket Number', 'Ticket Number2', 'Ticket Codigo Base64', 'Ticket Codigo2 Base64', 'Estado', 'Consecutivo', 'Salto', 'Identificador', 'Concatenado'];
        foreach($this->data as $entrada){
            $ticket_digital = Ticket::find($entrada['id'])->forma_generar;

            $entrada = OrderChild::where('ticket_id', $entrada['id'])->get();
            foreach ($entrada as $item) {
                if ($item->status == '0') {
                    $item->status = 'No leida';
                } elseif ($item->status == '1') {
                    $item->status = 'Leida';
                }

                if ($item->mesas == '0' || $item->mesas == '') {
                    $mesa = 'No';
                } else {
                    $mesa = $item->mesas;
                }

                if ($item->asiento == '0' || $item->asiento == '') {
                    $asiento = 'No';
                } else {
                    $asiento = $item->asiento;
                }

                if($ticket_digital == 1){
                    $concat = $item->identificador . '-'. Str::slug('ticket-'.$item->evento->name) . '-' . $item->consecutivo;
                }else{
                    $concat = $item->identificador . '-'. Str::slug('ticket-'.$item->evento->name) . '-' . 'P'.$mesa.'A'.$asiento;
                }

                $array[] = array(
                    'Entrada' => $item->evento->name,
                    'Cliente' => $item->cliente->name . ' ' . $item->cliente->last_name,
                    'Mesa' => $mesa,
                    'Asiento' =>  $asiento,
                    'Ticket Number' => $item->ticket_number,
                    'Ticket Number2' => $item->ticket_number2,
                    'Ticket Codigo Base64' => $item->ticket_hash3,
                    'Ticket Codigo2 Base64' => $item->ticket_hash4,
                    'Estado' => $item->status,
                    'Consecutivo' => $item->consecutivo,
                    'Salto' => $item->salto,
                    'Identificador' => $item->identificador,
                    'Concatenado' => $concat
                );
            }
        }
       
        return [$array];
    }
}
