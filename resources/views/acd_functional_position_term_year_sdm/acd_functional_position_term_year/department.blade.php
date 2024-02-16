{!! Form::label('', 'Program Studi', ['class' => 'col-md-4 col-xs-12']) !!}
<div class="col-md-12">
  <select  class="form-control form-control-sm" name="Department_Id">
    <option value="">Pilih Department</option>
    @foreach ( $department as $data )
      <option value="{{ $data->Department_Id }}">{{ $data->Department_Name }}</option>
    @endforeach
  </select>
</div>
