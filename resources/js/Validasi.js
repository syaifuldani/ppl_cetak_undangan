function validateForm() {
    // Validasi tanggal
    const tanggalAcara = document.getElementsByName("tanggalacara")[0];
    const today = new Date().toISOString().split("T")[0];
    if (tanggalAcara.value < today) {
        alert("Tanggal acara tidak boleh kurang dari hari ini");
        return false;
    }

    // Validasi lokasi
    const lokasiAcara = document.getElementsByName("lokasiacara")[0];
    if (!/^(?=.*[a-zA-Z])[a-zA-Z0-9\s,./-]{5,100}$/.test(lokasiAcara.value)) {
        alert("Lokasi harus minimal 5 karakter dan tidak boleh hanya angka");
        return false;
    }

    // Validasi keterangan
    const keterangan = document.getElementsByName("keterangan_order")[0];
    if (keterangan.value.length < 10) {
        alert("Keterangan minimal 10 karakter");
        return false;
    }

    // Validasi nama penerima
    const namaPenerima = document.getElementsByName("namapenerima")[0];
    if (!/^[a-zA-Z\s]{3,50}$/.test(namaPenerima.value)) {
        alert(
            "Nama penerima hanya boleh mengandung huruf dan spasi, minimal 3 karakter"
        );
        return false;
    }

    // Validasi email (jika diisi)
    const email = document.getElementsByName("email")[0];
    if (
        email.value &&
        !/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/.test(email.value)
    ) {
        alert("Format email tidak valid");
        return false;
    }

    // Validasi nomor telepon
    const noTelp = document.getElementsByName("notelppenerima")[0];
    if (!/^\+62[0-9]{8,12}$/.test(noTelp.value)) {
        alert("Nomor telepon harus diawali dengan +62 diikuti 8-12 angka");
        return false;
    }

    // Validasi alamat
    const alamat = document.getElementsByName("alamatpenerima")[0];
    if (alamat.value.length < 10) {
        alert("Alamat minimal 10 karakter");
        return false;
    }

    // Validasi kota
    const kota = document.getElementsByName("kota")[0];
    if (!/^[a-zA-Z\s]{2,50}$/.test(kota.value)) {
        alert("Nama kota hanya boleh mengandung huruf dan spasi");
        return false;
    }

    // Validasi kode pos
    const kodePos = document.getElementsByName("kodepos")[0];
    if (!/^[0-9]{5}$/.test(kodePos.value)) {
        alert("Kode pos harus terdiri dari 5 angka");
        return false;
    }

    return true;
}

// Real-time validation untuk nomor telepon
document
    .getElementsByName("notelppenerima")[0]
    .addEventListener("input", function (e) {
        let value = e.target.value;

        // Otomatis menambahkan +62 jika belum ada
        if (!value.startsWith("+62")) {
            if (value.startsWith("0")) {
                value = "+62" + value.slice(1);
            } else if (!value.startsWith("+")) {
                value = "+62" + value;
            }
            e.target.value = value;
        }
    });
