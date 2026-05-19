@extends('layouts.app')

@section('title', 'Buat Kegiatan Baru')

@push('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #mapCreate {
        height: 400px;
        border-radius: 12px;
        border: 2px solid #e9ecef;
        z-index: 1;
    }
    .map-section {
        margin-top: 0.5rem;
    }
    .map-info {
        background: linear-gradient(135deg, #e8f5e9, #f1f8e9);
        border-left: 4px solid #4caf50;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        margin-bottom: 1rem;
        font-size: 13px;
        color: #2e7d32;
    }
    .map-info i {
        color: #4caf50;
    }
    .gps-toggle-card {
        background: linear-gradient(135deg, #f0f4ff, #ffffff);
        border: 2px solid #e3e8f0;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }
    .gps-toggle-card.active {
        border-color: #4caf50;
        background: linear-gradient(135deg, #e8f5e9, #ffffff);
    }
    .preview-address {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
        font-size: 13px;
        color: #6c757d;
        margin-top: 0.5rem;
        min-height: 36px;
        display: flex;
        align-items: center;
    }
    .preview-address .spinner-border {
        width: 14px;
        height: 14px;
        border-width: 2px;
    }
</style>
@endpush

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

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="mb-3">
                                <label for="jam_mulai" class="form-label">
                                    Jam Mulai <span class="text-danger">*</span>
                                </label>
                                <input type="time" 
                                       class="form-control @error('jam_mulai') is-invalid @enderror" 
                                       id="jam_mulai" 
                                       name="jam_mulai" 
                                       value="{{ old('jam_mulai') }}"
                                       required>
                                @error('jam_mulai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="mb-3">
                                <label for="jam_selesai" class="form-label">
                                    Jam Selesai <span class="text-danger">*</span>
                                </label>
                                <input type="time" 
                                       class="form-control @error('jam_selesai') is-invalid @enderror" 
                                       id="jam_selesai" 
                                       name="jam_selesai" 
                                       value="{{ old('jam_selesai') }}"
                                       required>
                                @error('jam_selesai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- GPS Location Section -->
                    <hr class="my-4">
                    <div class="gps-toggle-card" id="gpsToggleCard">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="enableGps" 
                                   {{ old('latitude') ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="enableGps">
                                <i class="bi bi-geo-alt-fill text-success me-1"></i>
                                Aktifkan Validasi GPS Radius
                            </label>
                        </div>
                        <small class="text-muted d-block mt-1">
                            Peserta hanya dapat absen jika berada dalam radius lokasi yang ditentukan
                        </small>
                    </div>

                    <!-- Selfie Toggle -->
                    <div class="gps-toggle-card" id="selfieToggleCard" style="margin-top: -0.5rem;">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="enableSelfie"
                                   name="is_selfie_required" value="1"
                                   {{ old('is_selfie_required') ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="enableSelfie">
                                <i class="bi bi-camera-fill text-primary me-1"></i>
                                Wajib Selfie Saat Absensi
                            </label>
                        </div>
                        <small class="text-muted d-block mt-1">
                            Peserta wajib mengambil foto selfie dari kamera sebagai bukti kehadiran
                        </small>
                    </div>

                    <div id="gpsSection" style="display: none;">
                        <div class="map-info">
                            <i class="bi bi-info-circle me-1"></i>
                            <strong>Klik pada peta</strong> untuk menentukan titik lokasi kegiatan, atau gunakan tombol "Lokasi Saya" untuk mengambil posisi Anda saat ini.
                        </div>

                        <div class="row mb-3">
                            <div class="col-12 col-md-4">
                                <label for="latitude" class="form-label">
                                    Latitude <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('latitude') is-invalid @enderror" 
                                       id="latitude" 
                                       name="latitude" 
                                       value="{{ old('latitude') }}"
                                       placeholder="-7.xxxxxx"
                                       readonly>
                                @error('latitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="longitude" class="form-label">
                                    Longitude <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('longitude') is-invalid @enderror" 
                                       id="longitude" 
                                       name="longitude" 
                                       value="{{ old('longitude') }}"
                                       placeholder="111.xxxxxx"
                                       readonly>
                                @error('longitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="radius_meter" class="form-label">
                                    Radius (meter) <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       class="form-control @error('radius_meter') is-invalid @enderror" 
                                       id="radius_meter" 
                                       name="radius_meter" 
                                       value="{{ old('radius_meter', 100) }}"
                                       min="10" max="5000" step="10"
                                       placeholder="100">
                                @error('radius_meter')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Preview Address -->
                        <div class="preview-address" id="previewAddress">
                            <i class="bi bi-geo me-1"></i> Klik peta untuk melihat alamat
                        </div>

                        <div class="map-section mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted fw-semibold">
                                    <i class="bi bi-map me-1"></i>Pilih Lokasi di Peta
                                </small>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="btnMyLocation">
                                    <i class="bi bi-crosshair me-1"></i>Lokasi Saya
                                </button>
                            </div>
                            <div id="mapCreate"></div>
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

@push('scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const enableGps = document.getElementById('enableGps');
    const gpsSection = document.getElementById('gpsSection');
    const gpsToggleCard = document.getElementById('gpsToggleCard');
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');
    const radiusInput = document.getElementById('radius_meter');
    const previewAddress = document.getElementById('previewAddress');
    
    let map = null;
    let marker = null;
    let circle = null;

    // Default: Nganjuk, Jawa Timur
    const defaultLat = -7.6051;
    const defaultLng = 111.8975;

    function toggleGps() {
        if (enableGps.checked) {
            gpsSection.style.display = 'block';
            gpsToggleCard.classList.add('active');
            
            if (!map) {
                initMap();
            }
            
            setTimeout(() => {
                map.invalidateSize();
            }, 200);
        } else {
            gpsSection.style.display = 'none';
            gpsToggleCard.classList.remove('active');
            latInput.value = '';
            lngInput.value = '';
        }
    }

    enableGps.addEventListener('change', toggleGps);
    
    // Check on page load
    if (enableGps.checked) {
        toggleGps();
    }

    function initMap() {
        const initLat = latInput.value || defaultLat;
        const initLng = lngInput.value || defaultLng;
        
        map = L.map('mapCreate').setView([initLat, initLng], 16);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            maxZoom: 19
        }).addTo(map);

        // If already has coordinates, place marker
        if (latInput.value && lngInput.value) {
            placeMarker(parseFloat(latInput.value), parseFloat(lngInput.value));
        }

        // Click on map to place marker
        map.on('click', function(e) {
            placeMarker(e.latlng.lat, e.latlng.lng);
        });

        // Update circle on radius change
        radiusInput.addEventListener('input', function() {
            if (marker) {
                updateCircle(marker.getLatLng().lat, marker.getLatLng().lng);
            }
        });
    }

    function placeMarker(lat, lng) {
        latInput.value = lat.toFixed(8);
        lngInput.value = lng.toFixed(8);

        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng], {
                draggable: true,
                icon: L.icon({
                    iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
                    iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
                    shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41]
                })
            }).addTo(map);

            marker.on('dragend', function(e) {
                const pos = e.target.getLatLng();
                placeMarker(pos.lat, pos.lng);
            });
        }

        updateCircle(lat, lng);
        reverseGeocode(lat, lng);
        
        marker.bindPopup(`<b>Lokasi Kegiatan</b><br>Lat: ${lat.toFixed(6)}<br>Lng: ${lng.toFixed(6)}`).openPopup();
    }

    function updateCircle(lat, lng) {
        const radius = parseInt(radiusInput.value) || 100;
        
        if (circle) {
            circle.setLatLng([lat, lng]);
            circle.setRadius(radius);
        } else {
            circle = L.circle([lat, lng], {
                radius: radius,
                color: '#4caf50',
                fillColor: '#4caf50',
                fillOpacity: 0.15,
                weight: 2,
                dashArray: '5, 10'
            }).addTo(map);
        }
    }

    function reverseGeocode(lat, lng) {
        previewAddress.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengambil alamat...';
        
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`)
            .then(res => res.json())
            .then(data => {
                if (data.display_name) {
                    previewAddress.innerHTML = `<i class="bi bi-pin-map-fill text-success me-1"></i> ${data.display_name}`;
                } else {
                    previewAddress.innerHTML = '<i class="bi bi-geo me-1"></i> Alamat tidak ditemukan';
                }
            })
            .catch(() => {
                previewAddress.innerHTML = '<i class="bi bi-geo me-1"></i> Gagal mengambil alamat';
            });
    }

    // My Location button
    document.getElementById('btnMyLocation').addEventListener('click', function() {
        if (!navigator.geolocation) {
            alert('Browser Anda tidak mendukung geolocation');
            return;
        }

        this.disabled = true;
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Mengambil lokasi...';

        navigator.geolocation.getCurrentPosition(
            (position) => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                map.setView([lat, lng], 17);
                placeMarker(lat, lng);
                
                this.disabled = false;
                this.innerHTML = '<i class="bi bi-crosshair me-1"></i>Lokasi Saya';
            },
            (error) => {
                let msg = 'Gagal mengambil lokasi';
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        msg = 'Akses lokasi ditolak. Aktifkan GPS Anda.';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        msg = 'Informasi lokasi tidak tersedia.';
                        break;
                    case error.TIMEOUT:
                        msg = 'Timeout saat mengambil lokasi.';
                        break;
                }
                alert(msg);
                this.disabled = false;
                this.innerHTML = '<i class="bi bi-crosshair me-1"></i>Lokasi Saya';
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    });
});
</script>
@endpush
