<div class="form-group">
  @if(Session::has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{Session::get('success')}}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>

    </div>
  @endif
</div>
<div class="form-group">
  @if(Session::has('danger'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      {{Session::get('danger')}}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>

    </div>
  @endif
</div>
<div class="form-group">
    @if(Session::has('warning'))
      <div class="alert alert-warning alert-dismissible fade show" role="alert" style="color: #4f4f4f; background-color: #ffee01; border-color: #ffee01; border-radius: 1rem;">
        <i class="fas fa-exclamation-triangle"></i>&nbsp;&nbsp;{{Session::get('warning')}}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>

      </div>
    @endif
</div>