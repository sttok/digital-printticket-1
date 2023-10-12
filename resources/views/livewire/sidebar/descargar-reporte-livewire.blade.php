<div wire:init="loadDatos">
    @if ($readyToLoad2)
        <script>
            window.addEventListener('downloadReport', event => {
                Swal.fire({
                    title: '¿Que reporte desea descargar?',
                    icon: 'question',
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: 'Todos',
                    denyButtonText: `Filtrar por puntos de ventas`,
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        window.livewire.emit('downloadReportesAll')
                        let timerInterval
                        Swal.fire({
                            icon: 'success',
                            title: '¡Procesando! ',
                            text: 'Espera un momento, pronto estará disponible',
                            timer: 1500,
                            timerProgressBar: true,
                            didOpen: () => {
                                Swal.showLoading()
                            },
                            willClose: () => {
                                clearInterval(timerInterval)
                            }
                        })
                    } else if (result.isDenied) {
                        Swal.fire({
                            title: 'Selecciona un punto de venta',
                            icon: 'info',
                            input: 'select',
                            inputOptions: @json($dataPuntosVentas),
                            inputPlaceholder: 'Selecciona un punto de venta',
                            showCancelButton: true,
                            confirmButtonText: 'Seleccionar',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                const selectedPromoterId = result.value;
                                window.livewire.emit('downloadReportesPuntoVenta', selectedPromoterId);
                                let timerInterval
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Procesando! ',
                                    text: 'Espera un momento, pronto estará disponible',
                                    timer: 1500,
                                    timerProgressBar: true,
                                    didOpen: () => {
                                        Swal.showLoading()
                                    },
                                    willClose: () => {
                                        clearInterval(timerInterval)
                                    }
                                });
                            }
                        });
                    }
                })
            })
        </script>
    @endif

    <!--<script>
        const downloadReport = document.getElementById("downloadReport");
        downloadReport.addEventListener('click', event => {
            Swal.fire({
                title: '¿Que reporte desea descargar?',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Todos',
                denyButtonText: `Activos`,
                cancelButtonText: 'Desactivados'
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    window.livewire.emit('downloadReportes', 1)
                    let timerInterval
                    Swal.fire({
                        icon: 'success',
                        title: '¡Procesando! ',
                        text: 'Espera un momento, pronto estará disponible',
                        timer: 1500,
                        timerProgressBar: true,
                        didOpen: () => {
                            Swal.showLoading()
                        },
                        willClose: () => {
                            clearInterval(timerInterval)
                        }
                    })
                } else if (result.isDenied) {
                    window.livewire.emit('downloadReportes', 2)
                    let timerInterval
                    Swal.fire({
                        icon: 'success',
                        title: '¡Procesando! ',
                        text: 'Espera un momento, pronto estará disponible',
                        timer: 1500,
                        timerProgressBar: true,
                        didOpen: () => {
                            Swal.showLoading()
                        },
                        willClose: () => {
                            clearInterval(timerInterval)
                        }
                    })
                } else if (result.isDismissed) {
                    window.livewire.emit('downloadReportes', 3)
                    let timerInterval
                    Swal.fire({
                        icon: 'success',
                        title: '¡Procesando! ',
                        text: 'Espera un momento, pronto estará disponible',
                        timer: 1500,
                        timerProgressBar: true,
                        didOpen: () => {
                            Swal.showLoading()
                        },
                        willClose: () => {
                            clearInterval(timerInterval)
                        }
                    })
                }
            })
        })
    </script>-->

</div>
