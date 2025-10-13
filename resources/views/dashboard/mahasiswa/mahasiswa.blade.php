@extends('layouts.app')

@section('content')
<div class="container mt-4">
  <h3>Daftar Mahasiswa</h3>

  <form id="approveForm" method="POST" action="{{ route('mahasiswa.approve') }}">
    @csrf
    <table class="table table-bordered mt-3">
      <thead class="table-primary">
        <tr>
          <th><input type="checkbox" id="checkAll"></th>
          <th>Nama</th>
          <th>NIM</th>
          <th>Judul TA</th>
          <th>Deskripsi</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($mahasiswa as $mhs)
          <tr>
            <td><input type="checkbox" name="selected[]" value="{{ $mhs->id }}"></td>
            <td>{{ $mhs->nama }}</td>
            <td>{{ $mhs->nim }}</td>
            <td>{{ $mhs->judul_ta }}</td>
            <td>{{ $mhs->deskripsi_ta }}</td>
            <td>{{ $mhs->status ?? 'Belum Disetujui' }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <button type="submit" class="btn btn-success">Approve</button>
  </form>
</div>

<script>
  // Checkbox “select all”
  document.getElementById('checkAll').addEventListener('change', function(e) {
    const checkboxes = document.querySelectorAll('input[name="selected[]"]');
    checkboxes.forEach(cb => cb.checked = e.target.checked);
  });
</script>
@endsection
