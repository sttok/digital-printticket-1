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
                    denyButtonText: `Por puntos de ventas`,
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
        <script>
            window.addEventListener('downloadReportAnulados', event => {
                Swal.fire({
                    title: '¿Que reporte desea descargar?',
                    icon: 'question',
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: 'Todos',
                    denyButtonText: `Por puntos de ventas`,
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        window.livewire.emit('downloadReportesAllAnulados')
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
                                window.livewire.emit('downloadReportesPuntoVentaAnulados',
                                    selectedPromoterId);
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
</div>
