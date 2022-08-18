<div class="mb-0" style="margin-bottom: 5px">         
    @if (session()->has('msj'))
     <div class="alert alert-success" role="alert" style="border-radius: 1rem;">
        <button type="button" class="close pull-right" style="background-color: transparent" data-dismiss="alert">&times;</button>
            {{'Los datos han  sidos enviados con exito'}}
      </div>
    @else                         
        @if ($errors->any())
            @foreach($errors->all() as $error)
                <div class="alert alert-danger alert-dismissable" style="border-radius: 1rem;">
                    <strong style="color: #e0317a">¡Error!</strong> {{ $error }}.
                </div>  
            @endforeach
        @endif 
    @endif
</div>