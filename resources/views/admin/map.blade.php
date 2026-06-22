<x-layouts.dashboard title="Peta Laporan" :pageTitle="'Peta Laporan'">
    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css">
    <style> #map { height: calc(100vh - 160px); border-radius: 12px; } </style>
    @endpush

    <div id="map" class="w-full border border-stone-200"></div>

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
    <script>
        const map = L.map('map').setView([-7.2575, 112.7521], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        const markers = L.markerClusterGroup();

        fetch('/peta/data')
            .then(r => r.json())
            .then(data => {
                data.forEach(r => {
                    const marker = L.circleMarker([r.lat, r.lng], {
                        radius: 8,
                        fillColor: r.status_color,
                        color: '#fff',
                        weight: 2,
                        opacity: 1,
                        fillOpacity: 0.9,
                    });
                    marker.bindPopup(`
                        <div style="min-width:180px">
                            <div style="font-weight:600;margin-bottom:4px">${r.title}</div>
                            <div style="font-size:12px;color:#64748b">
                                <span style="color:${r.status_color};font-weight:600">${r.status}</span>
                                · ${r.category}
                            </div>
                            <a href="${r.url}" style="font-size:12px;color:#1a73e8;display:inline-block;margin-top:6px">
                                Lihat Detail →
                            </a>
                        </div>
                    `);
                    markers.addLayer(marker);
                });
                map.addLayer(markers);
            });
    </script>
    @endpush
</x-layouts.dashboard>