<?php
/**
 * Validasi hasil build:
 *  - semua file .html bebas dari token Twig yang belum ter-render
 *  - semua referensi href/src lokal resolve ke file yang ada
 *  - tag <div> seimbang (kecuali pengecualian template asli)
 */
$root = dirname(__DIR__);
$knownUnbalanced = ['cetak-ruang.html' => -1]; // </div> berlebih dari ruang.twig asli

$files = glob($root . '/*.html');
$errors = [];
$refs   = [];
$twigLeft = [];

foreach ($files as $path) {
    $name = basename($path);
    $html = file_get_contents($path);

    if (preg_match('/\{\{|\{%|\{#/', $html)) {
        $errors[] = "$name: masih ada token Twig";
    }

    preg_match_all('/(?:href|src)\s*=\s*"([^"#][^"]*)"/i', $html, $m);
    foreach ($m[1] as $ref) {
        $ref = preg_replace('/\?.*$/', '', $ref);
        if ($ref === '' || preg_match('#^(https?:|javascript:|data:|mailto:|//)#i', $ref)) continue;
        $refs[$ref][] = $name;
        if (!file_exists($root . '/' . $ref)) {
            $errors[] = "$name: referensi hilang -> $ref";
        }
    }

    $opens  = preg_match_all('/<div\b/i', $html);
    $closes = substr_count(strtolower($html), '</div>');
    $diff   = $opens - $closes;
    if ($diff !== 0 && ($knownUnbalanced[$name] ?? null) !== $diff) {
        $errors[] = "$name: tag <div> tidak seimbang (buka=$opens tutup=$closes)";
    }
}

echo sprintf("  %d halaman, %d referensi lokal unik, %d error\n",
    count($files), count($refs), count($errors));

if ($errors) {
    foreach ($errors as $e) echo "  FAIL  $e\n";
    exit(1);
}
echo "  OK  semua halaman & aset valid\n";