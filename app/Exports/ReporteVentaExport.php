<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\DigitalOrdenCompra;
use App\Models\OrderChildsDigital;
use App\Models\DigitalOrdenCompraDetalle;


class ReporteVentaExport implements FromArray, ShouldAutoSize, WithStyles
{
    use Exportable;
    protected $id;
    protected $data;

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
        $array[] =  ['Vendedor', 'Identificador de venta',  'Nombre entrada', 'Identificador entrada', 'Consecutivo', 'Palco', 'Asiento', 'Comprador', 'Endosado', 'Fecha vendido'];

        $data = $this->data;

        $dataVenta = DigitalOrdenCompra::where('evento_id', $this->data['eventoId'])
            ->when(!$this->data['all'], function ($query) use ($data) {
                return $query->where('vendedor_id', $this->data['puntoVentaId']);
            })
            ->orderBy('created_at', 'DESC')
            ->orderBy('identificador', 'DESC')
            ->with(['vendedor', 'cliente'])->get();

        foreach ($dataVenta as $venta) {
            $detalles = DigitalOrdenCompraDetalle::where('digital_orden_compra_id', $venta->id)->with(['entrada'])->get();

            foreach ($detalles as $ent) {
                $array[] = array(
                    'Vendedor' => $venta->vendedor != null ? $venta->vendedor->first_name . ' ' . $venta->vendedor->last_name : 'No encontrado',
                    'Identificador de venta' => $venta['identificador'],
                    'Nombre entrada' => $ent->entrada->evento->name,
                    'Identificador' =>  $ent->entrada->identificador,
                    'Consecutivo' => $ent->entrada->consecutivo,
                    'Palco' => $ent->entrada->mesas,
                    'Asiento' => $ent->entrada->asiento,
                    'Comprador' => $venta->cliente->name . ' ' . $venta->cliente->last_name,
                    'Endosado' => $ent->endosado != null ? $ent->endosado->name . ' ' . $ent->endosado->last_name : 'No',
                    'Fecha vendido' => Carbon::create($venta['created_at'])->isoFormat('LLLL')
                );
            }
        }

        return [$array];
    }
}
