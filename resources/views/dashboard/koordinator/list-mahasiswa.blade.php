@extends('layouts.app')

@section('content')
<div class="container">
<div class="sidebar">
    <a href="{{ route('koordinator.dashboard') }}"class="active">List Mahasiswa</a>
    <a href="{{ route('koordinator.ploting') }}">Ploting Pembimbing</a>
    <a href="{{ route('koordinator.sidang') }}">Sidang</a>
</div>


    <div class="main">
        <div class="header">
            <div class="logo">
                <img src="{{ asset("public/img/LOGO.png") }}" alt="UBPLogo">
            </div>
            <div class="profile">
                <strong>{{ Auth::user()->name }}</strong>
                <small>{{ Auth::user()->email }}</small>
                <img src="{{ asset('public/img/R1_.jpg') }}" alt="Profile">
            </div>
        </div>

        <div class="content">
            <h2>List Mahasiswa</h2>
            <div class="search-bar">
                <input type="text" id="search" placeholder="Search Bar..." onkeyup="searchTable()">
            </div>

            <table id="mahasiswaTable">
                <thead>
                    <tr>
                        <th>Nama Mahasiswa</th>
                        <th>NIM Mahasiswa</th>
                        <th>Periode</th>
                        <th>Status</th>
                        <th>SKS</th>
                        <th>IPK Semester 1-2</th>
                        <th>IPK Semester 3-4</th>
                        <th>IPK Semester 5-6</th>
                        <th>IPK Semester 7-8</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mahasiswa as $mhs)
                    <tr>
                        <td>{{ $mhs->nama }}</td>
                        <td>{{ $mhs->nim }}</td>
                        <td>{{ $mhs->periode }}</td>
                        <td>{{ $mhs->status }}</td>
                        <td>{{ $mhs->sks }}</td>
                        <td>{{ $mhs->ipk_1_2 }}</td>
                        <td>{{ $mhs->ipk_3_4 }}</td>
                        <td>{{ $mhs->ipk_5_6 }}</td>
                        <td>{{ $mhs->ipk_7_8 }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function searchTable() {
    let input = document.getElementById("search").value.toLowerCase();
    let rows = document.querySelectorAll("#mahasiswaTable tbody tr");
    rows.forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(input) ? "" : "none";
    });
}
</script>
@endsection