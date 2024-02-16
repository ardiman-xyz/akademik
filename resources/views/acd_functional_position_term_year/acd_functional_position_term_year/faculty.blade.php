{!! Form::label('', 'Departemen', ['class' => 'col-md-4 col-xs-12']) !!}
<div class="col-md-12">
  <select  class="form-control form-control-sm" name="Faculty_Id">
    <option value="">Pilih Departemen</option>
    @foreach ( $faculty as $data )
      <option value="{{ $data->Faculty_Id }}">{{ $data->Faculty_Name }}</option>
    @endforeach
  </select>
</div>
