@extends('layouts.app')

@section('title', 'Buat Kegiatan Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-plus-circle text-primary me-2"></i>Buat Kegiatan Baru
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.kegiatan.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="nama_kegiatan" class="form-label">
                            Nama Kegiatan <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('nama_kegiatan') is-invalid @enderror" 
                               id="nama_kegiatan" 
                               name="nama_kegiatan" 
                               value="{{ old('nama_kegiatan') }}"
                               placeholder="Contoh: Rapat Koordinasi Bulanan"
                               required>
                        @error('nama_kegiatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                  id="deskripsi" 
                                  name="deskripsi" 
                                  rows="3"
                                  placeholder="Deskripsi kegiatan (opsional)">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="mb-3">
                                <label for="tanggal" class="form-label">
                                    Tanggal Kegiatan <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       class="form-control @error('tanggal') is-invalid @enderror" 
                                       id="tanggal" 
                                       name="tanggal" 
                                       value="{{ old('tanggal', date('Y-m-d')) }}"
                                       required>
                                @error('tanggal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="mb-3">
                                <label for="lokasi" class="form-label">Lokasi</label>
                                <input type="text" 
                                       class="form-control @error('lokasi') is-invalid @enderror" 
                                       id="lokasi" 
                                       name="lokasi" 
                                       value="{{ old('lokasi') }}"
                                       placeholder="Contoh: Ruang Rapat Utama">
                                @error('lokasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-check-lg me-2"></i>Simpan Kegiatan
                        </button>
                        <a href="{{ route('admin.kegiatan.index') }}" class="btn btn-secondary">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
