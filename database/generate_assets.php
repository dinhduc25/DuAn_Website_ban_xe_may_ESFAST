<?php

// Asset Generator for ESFAST Luxury Electric Bikes
$dir = __DIR__ . '/../public/assets/images/';
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}

$bikes = [
    'bike_theon_s.png'        => ['name' => 'ESFAST THEON S', 'color' => '#0052cc', 'power' => '7100W', 'speed' => '99 km/h'],
    'bike_vento_s.png'        => ['name' => 'ESFAST VENTO S', 'color' => '#00d2ff', 'power' => '5200W', 'speed' => '89 km/h'],
    'bike_klara_s2.png'       => ['name' => 'ESFAST KLARA S2', 'color' => '#405d4b', 'power' => '3000W', 'speed' => '78 km/h'],
    'bike_feliz_s.png'        => ['name' => 'ESFAST FELIZ S', 'color' => '#dc2626', 'power' => '3000W', 'speed' => '78 km/h'],
    'bike_evo_200.png'        => ['name' => 'ESFAST EVO 200', 'color' => '#0284c7', 'power' => '2500W', 'speed' => '70 km/h'],
    'bike_evo_lite.png'       => ['name' => 'ESFAST EVO LITE', 'color' => '#fbbf24', 'power' => '1500W', 'speed' => '49 km/h'],
    'bike_impes.png'          => ['name' => 'ESFAST IMPES SPORT', 'color' => '#10b981', 'power' => '1200W', 'speed' => '50 km/h'],
    'bike_apex_x.png'         => ['name' => 'ESFAST APEX X', 'color' => '#6366f1', 'power' => '8000W', 'speed' => '110 km/h'],
    'bike_fallback.png'       => ['name' => 'ESFAST BIKE', 'color' => '#0052cc', 'power' => '3000W', 'speed' => '80 km/h'],
    'hero_bike_fallback.png'  => ['name' => 'ESFAST SMART BIKE', 'color' => '#0052cc', 'power' => '7100W', 'speed' => '99 km/h'],
];

foreach ($bikes as $filename => $info) {
    $w = 600;
    $h = 420;
    $img = imagecreatetruecolor($w, $h);
    imagesavealpha($img, true);
    $transparent = imagecolorallocatealpha($img, 0, 0, 0, 127);
    imagefill($img, 0, 0, $transparent);

    // Convert hex to rgb
    list($r, $g, $b) = sscanf($info['color'], "#%02x%02x%02x");

    // Colors
    $colorMain = imagecolorallocate($img, $r, $g, $b);
    $colorDark = imagecolorallocate($img, 24, 30, 42);
    $colorGray = imagecolorallocate($img, 71, 85, 105);
    $colorLight = imagecolorallocate($img, 241, 245, 249);
    $colorCyan = imagecolorallocate($img, 0, 210, 255);
    $colorWhite = imagecolorallocate($img, 255, 255, 255);

    // Draw wheels (Shadows and Rims)
    imagefilledellipse($img, 150, 310, 150, 150, $colorDark);
    imagefilledellipse($img, 150, 310, 120, 120, $colorGray);
    imagefilledellipse($img, 150, 310, 70, 70, $colorDark);
    imagefilledellipse($img, 150, 310, 30, 30, $colorCyan);

    imagefilledellipse($img, 450, 310, 150, 150, $colorDark);
    imagefilledellipse($img, 450, 310, 120, 120, $colorGray);
    imagefilledellipse($img, 450, 310, 70, 70, $colorDark);
    imagefilledellipse($img, 450, 310, 30, 30, $colorCyan);

    // Bike Frame and Body
    $bodyPoints = [
        150, 310,
        220, 230,
        270, 240,
        340, 180,
        410, 170,
        450, 310,
        330, 310,
        260, 270,
    ];
    imagefilledpolygon($img, $bodyPoints, $colorDark);

    // Main Fairing / Color Accent
    $fairingPoints = [
        220, 220,
        360, 160,
        430, 170,
        460, 210,
        380, 260,
        280, 260,
    ];
    imagefilledpolygon($img, $fairingPoints, $colorMain);

    // Handlebar & Headlight
    imagesetthickness($img, 8);
    imageline($img, 360, 160, 330, 110, $colorDark);
    imageline($img, 310, 110, 350, 110, $colorCyan); // Headlight LED line

    // Seat
    imagefilledellipse($img, 270, 190, 120, 30, $colorDark);

    // Text Badge on vehicle
    imagestring($img, 5, 230, 30, $info['name'], $colorDark);
    imagestring($img, 4, 250, 60, "MOTOR: " . $info['power'] . " | " . $info['speed'], $colorMain);

    imagepng($img, $dir . $filename);
    imagedestroy($img);
}

// Banners & Post Images
$banners = [
    'banner_theon_s.jpg'      => ['bg' => [11, 34, 101], 'text' => 'ESFAST THEON S - SIEU XE MAY DIEN'],
    'banner_evo200.jpg'       => ['bg' => [2, 132, 199], 'text' => 'ESFAST EVO 200 - DI CHUYEN 203KM/SAC'],
    'banner_green_summer.jpg' => ['bg' => [16, 185, 129], 'text' => 'DOI XANG LAY DIEN - TRO GIA 3 TRIEU'],
    'post_compare_cost.jpg'   => ['bg' => [30, 41, 59], 'text' => 'TIET KIEM 80% CHI PHI NUOI XE'],
    'post_lfp_battery.jpg'    => ['bg' => [15, 23, 42], 'text' => 'CONG NGHE PIN LFP AN TOAN'],
    'post_maintenance.jpg'    => ['bg' => [51, 65, 85], 'text' => 'CAM NANG BAO DUONG XE DIEN'],
    'about_hero.jpg'          => ['bg' => [0, 82, 204], 'text' => 'ESFAST FACTORY & SHOWROOM'],
];

foreach ($banners as $filename => $info) {
    $w = 900;
    $h = 500;
    $img = imagecreatetruecolor($w, $h);
    $bg = imagecolorallocate($img, $info['bg'][0], $info['bg'][1], $info['bg'][2]);
    imagefill($img, 0, 0, $bg);

    $white = imagecolorallocate($img, 255, 255, 255);
    $cyan = imagecolorallocate($img, 0, 210, 255);

    // Grid pattern
    $gridColor = imagecolorallocatealpha($img, 255, 255, 255, 115);
    for ($x = 0; $x < $w; $x += 40) {
        imageline($img, $x, 0, $x, $h, $gridColor);
    }
    for ($y = 0; $y < $h; $y += 40) {
        imageline($img, 0, $y, $w, $y, $gridColor);
    }

    // Logo & text
    imagestring($img, 5, 50, 50, "ESFAST ELECTRIC VEHICLE VIETNAM", $cyan);
    imagestring($img, 5, 50, 230, $info['text'], $white);
    imagestring($img, 4, 50, 270, "Cong nghe pin LFP - IP67 Waterproof - Smart App", $cyan);

    imagejpeg($img, $dir . $filename, 90);
    imagedestroy($img);
}

echo "Created all realistic bike & banner graphics in $dir" . PHP_EOL;
