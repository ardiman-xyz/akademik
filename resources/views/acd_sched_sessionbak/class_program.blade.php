<option value="">Pilih Program Kelas</option>
@foreach ( $data as $class )
  <option value="{{ $class->Class_Prog_Id }}">{{ $class->Class_Program_Name }}</option>
@endforeach
