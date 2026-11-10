#!/bin/sh
# Render ulang seluruh halaman statis SIUDIN UBK.
# Jalankan dari folder mana pun: sh /root/siudin-ubk/_build/rebuild.sh
set -e
cd "$(dirname "$0")/.."

echo "[1/3] Render template Twig -> HTML ..."
php _build/build.php

echo "[2/3] Buat halaman galeri index.html ..."
php _build/build-index.php

echo "[3/3] Cek referensi aset lokal ..."
php _build/verify.php

echo "Selesai."