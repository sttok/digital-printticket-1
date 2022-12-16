<?php

namespace App\Http\Livewire\Misentradas\Nuevo;

use Livewire\Component;

class ReporteLivewire extends Component
{    
    public function render()
    {
        return view('livewire.misentradas.nuevo.reporte-livewire');
    }

    public function openReporte(){
        $this->dispatchBrowserEvent('openReporte1');
    }

    public function openEstadisticas(){
        $this->dispatchBrowserEvent('openEstadisticas1');
    }
}
