1. cara menambahkan middleware agar bisa dibaca adalah di AppServiceProvider.php.
2. cara membuat SMTP tinggal masukin di .env buat email dll nya, terus di controller ditambahin mail::try{} lalu diatur data apa aja yang dimasukkan
3. kalau mau buat jwt jangan lupa di aut config dikasih jwtnya, terus di model use jwt juga