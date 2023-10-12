<?php

namespace App\Http\Livewire\Sidebar;

use App\Exports\ReporteVentaAnuladosExport;
use Carbon\Carbon;
use Livewire\Component;
use App\Models\EventPuntoVenta;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReporteVentaExport;

class DescargarReporteLivewire extends Component
{
    public $eventoId;
    public $dataPuntosVentas = [];
    public $readyToLoad2 = false;
    protected $listeners = ['downloadReportesAll', 'downloadReportesPuntoVenta', 'downloadReportesAllAnulados', 'downloadReportesAllAnulados'];

    public function mount($eventoId)
    {
        $this->eventoId = $eventoId;
    }

    public function render()
    {
        return view('livewire.sidebar.descargar-reporte-livewire');
    }

    public function loadDatos()
    {

        foreach ($this->Puntosventas as $item) {
            if ($item->puntoventa != null) {
                $this->dataPuntosVentas[] = [
                    'id' => $item->punto_id,
                    'name' => $item->puntoventa->first_name . ' ' . $item->puntoventa->last_name
                ];
            }
        }

        $this->dataPuntosVentas = array_reduce($this->dataPuntosVentas, function ($carry, $promoter) {
            $carry[$promoter['id']] = $promoter['name'];
            return $carry;
        }, []);
        $this->readyToLoad2 = true;
    }

    public function downloadReportesAll()
    {
        $data = [
            'all' => true,
            'puntoVentaId' => null,
            'eventoId' => $this->eventoId,
        ];
        return Excel::download(new ReporteVentaExport($data), 'informe-' . Carbon::now()->isoFormat('D-M-YY') . '-kf' . rand(1, 999) . '.xlsx');
    }

    public function downloadReportesPuntoVenta($id)
    {
        $data = [
            'all' => false,
            'puntoVentaId' => $id,
            'eventoId' => $this->eventoId,
        ];
        return Excel::download(new ReporteVentaExport($data), 'informe-' . Carbon::now()->isoFormat('D-M-YY') . '-kf' . rand(1, 999) . '.xlsx');
    }

    public function downloadReportesAllAnulados()
    {
        $data = [
            'all' => true,
            'puntoVentaId' => null,
            'eventoId' => $this->eventoId,
        ];
        return Excel::download(new ReporteVentaAnuladosExport($data), 'informe-anulados-' . Carbon::now()->isoFormat('D-M-YY') . '-kf' . rand(1, 999) . '.xlsx');
    }

    public function downloadReportesPuntoVentaAnulados($id)
    {
        $data = [
            'all' => false,
            'puntoVentaId' => $id,
            'eventoId' => $this->eventoId,
        ];
        return Excel::download(new ReporteVentaAnuladosExport($data), 'informe-anulados-' . Carbon::now()->isoFormat('D-M-YY') . '-kf' . rand(1, 999) . '.xlsx');
    }

    public function getPuntosventasProperty()
    {
        return EventPuntoVenta::where([
            ['event_id', $this->eventoId]
        ])->get();
    }
}
