<div >
    <li><a href="#" wire:click="openEstadisticas" ><i class="far fa-circle"></i>
        <div wire:loading wire:target="openEstadisticas">
            <div class="spinner-grow spinner-grow-sm my-1" role="status" >
            </div>
        </div>
        <span wire:loading.remove wire:target="openEstadisticas">
            {{ __('Estadisticas') }}
        </span>
       
    </a></li>
    <li>
        <a href="#" wire:click="openReporte"><i class="far fa-circle"></i>
            <div wire:loading wire:target="openReporte">
                <div class="spinner-grow spinner-grow-sm my-1" role="status" >
                </div>
            </div>
            <span wire:loading.remove wire:target="openReporte">
                {{ __('Reporte') }}
            </span>
        
        </a>
    </li>   
</div>
