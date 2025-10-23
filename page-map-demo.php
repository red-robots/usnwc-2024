<?php
/**
 * Template Name: Interactive Map DEMO
 */
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Custom Image Map with Add-a-Pin - Leaflet</title>

  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

  <style>
    html, body {
      height: 100%;
      margin: 0;
      font-family: Arial, sans-serif;
    }

    #container {
      display: flex;
      height: 100vh;
      overflow: hidden;
    }

    #map {
      flex: 3;
      background: #f5f5f5;
      cursor: crosshair;
    }

    #sidebar {
      flex: 1;
      padding: 20px;
      background: #fff;
      border-left: 2px solid #ccc;
      overflow-y: auto;
      transition: all 0.3s ease;
    }

    #sidebar h2 {
      margin-top: 0;
      color: #333;
    }

    #sidebar p {
      color: #555;
      line-height: 1.5;
    }

    .highlighted {
      fill-opacity: 0.5 !important;
    }

    #addPinToggle {
      display: inline-block;
      background: #0078ff;
      color: #fff;
      border: none;
      padding: 10px 15px;
      border-radius: 6px;
      cursor: pointer;
      margin-top: 15px;
    }

    #addPinToggle.active {
      background: #ff4c4c;
    }
  </style>
</head>
<body>
  <div id="container">
    <div id="map"></div>
    <div id="sidebar">
      <h2>Map Info</h2>
      <p>Click a region or marker to see details here.</p>
      <button id="addPinToggle">➕ Add Pin</button>
    </div>
  </div>

  <!-- Leaflet JS -->
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

  <script>
    // --- Initialize Map ---
    const map = L.map('map', {
      crs: L.CRS.Simple,
      minZoom: -1,
      maxZoom: 4
    });

    const imageWidth = 2000;
    const imageHeight = 1500;
    const bounds = [[0, 0], [imageHeight, imageWidth]];
    L.imageOverlay('../wp-content/themes/usnwc-2024/images/map-demo.png', bounds).addTo(map);
    map.fitBounds(bounds);

    const sidebar = document.getElementById('sidebar');
    const addPinButton = document.getElementById('addPinToggle');
    let addPinMode = false;

    // --- Add Markers ---
    const marker1 = L.marker([400, 600]).addTo(map).bindTooltip('Point A');
    const marker2 = L.marker([1000, 1200]).addTo(map).bindTooltip('Point B');

    marker1.on('click', () => showInfo('Point A', 'This is the first location with custom information.'));
    marker2.on('click', () => showInfo('Point B', 'This area represents another region of interest.'));

    // --- Add Highlightable Regions ---
    const regionA = L.polygon(
      [
        [200, 400],
        [400, 400],
        [400, 600],
        [200, 600]
      ],
      { color: 'blue', weight: 2, fillColor: 'blue', fillOpacity: 0.2 }
    ).addTo(map);

    const regionB = L.polygon(
      [
        [800, 1000],
        [1000, 1000],
        [1000, 1200],
        [800, 1200]
      ],
      { color: 'green', weight: 2, fillColor: 'green', fillOpacity: 0.2 }
    ).addTo(map);

    regionA.on('click', () => showInfo('Region A', 'This blue-highlighted zone represents Area A.'));
    regionB.on('click', () => showInfo('Region B', 'This green-highlighted zone represents Area B.'));

    function addHighlight(layer) {
      layer.on('mouseover', function() {
        this.setStyle({ fillOpacity: 0.5 });
      });
      layer.on('mouseout', function() {
        this.setStyle({ fillOpacity: 0.2 });
      });
    }
    addHighlight(regionA);
    addHighlight(regionB);

    // --- Sidebar Update Function ---
    function showInfo(title, content) {
      sidebar.innerHTML = `
        <h2>${title}</h2>
        <p>${content}</p>
        <button id="addPinToggle" class="${addPinMode ? 'active' : ''}">${addPinMode ? '❌ Cancel Add Pin' : '➕ Add Pin'}</button>
      `;
      document.getElementById('addPinToggle').onclick = toggleAddPinMode;
    }

    // --- Add-a-Pin Mode Toggle ---
    function toggleAddPinMode() {
      addPinMode = !addPinMode;
      addPinButton.classList.toggle('active', addPinMode);
      addPinButton.textContent = addPinMode ? '❌ Cancel Add Pin' : '➕ Add Pin';
    }

    addPinButton.addEventListener('click', toggleAddPinMode);

    // --- Map Click (Add Pin) ---
    map.on('click', (e) => {
      if (!addPinMode) return;
      const { lat, lng } = e.latlng;

      const title = prompt('Enter a title for this pin:');
      if (!title) return;

      const newMarker = L.marker([lat, lng])
        .addTo(map)
        .bindTooltip(title)
        .on('click', () => showInfo(title, `Custom user-added pin at [${lat.toFixed(1)}, ${lng.toFixed(1)}].`));

      showInfo(title, `Added a new pin at [${lat.toFixed(1)}, ${lng.toFixed(1)}].`);
    });
  </script>
</body>
</html>

