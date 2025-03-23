document.addEventListener("DOMContentLoaded", function () {
    // Fungsi untuk mengatur status tombol bayar
    function togglePayButton(isValid) {
        const payButton = document.getElementById("pay-btn");
        if (payButton) {
            payButton.disabled = !isValid;
            payButton.style.opacity = isValid ? "1" : "0.5";
            payButton.style.cursor = isValid ? "pointer" : "not-allowed";
        }
    }

    // Fungsi untuk mengecek validasi semua field
    function validateAllFields() {
        const form = document.getElementById("payment-form");
        let isValid = true;

        isValid = validateTanggalAcara(form.tanggalacara) && isValid;
        isValid = validateLokasiAcara(form.lokasiacara) && isValid;
        isValid = validateKeterangan(form.keterangan_order) && isValid;
        isValid = validateNamaPenerima(form.namapenerima) && isValid;
        isValid = validateEmail(form.email) && isValid;
        isValid = validateKelurahan(form.kelurahan) && isValid;
        isValid = validateKecamatan(form.kecamatan) && isValid;
        isValid = validatePhone(form.notelppenerima) && isValid;
        isValid = validateSelect(form.provinsi, "Provinsi") && isValid;
        isValid = validateSelect(form.kota, "Kota") && isValid;
        isValid = validateAlamat(form.alamatpenerima) && isValid;
        isValid = validateKodePos(form.kodepos) && isValid;
        isValid = validateSelect(form.courier, "Kurir") && isValid;

        // Cek shipping cost
        const shippingCost = form.querySelector('input[name="shipping_cost"]');
        if (!shippingCost.value) {
            isValid = false;
        }

        togglePayButton(isValid);
        return isValid;
    }

    const form = document.getElementById("payment-form");

    // Fungsi untuk menampilkan pesan error
    function showError(input, message) {
        const formGroup = input.closest(".form-group");
        const existingError = formGroup.querySelector(".error-message");

        if (!existingError) {
            const errorDiv = document.createElement("div");
            errorDiv.className = "error-message";
            errorDiv.style.color = "red";
            errorDiv.style.fontSize = "12px";
            errorDiv.style.marginTop = "5px";
            errorDiv.textContent = message;
            formGroup.appendChild(errorDiv);
        }
        input.style.borderColor = "red";
    }

    // Fungsi untuk menghapus pesan error
    function removeError(input) {
        const formGroup = input.closest(".form-group");
        const errorDiv = formGroup.querySelector(".error-message");
        if (errorDiv) {
            errorDiv.remove();
        }
        input.style.borderColor = "";
    }

    // Validasi kelurahan
    function validateKelurahan(input) {
        if (!input.value.trim()) {
            showError(input, "Kelurahan harus diisi");
            return false;
        }
        if (input.value.length < 3) {
            showError(input, "Kelurahan minimal 3 karakter");
            return false;
        }
        if (/\d/.test(input.value)) {
            showError(input, "Kelurahan tidak boleh mengandung angka");
            return false;
        }
        removeError(input);
        return true;
    }

    // Validasi kecamatan
    function validateKecamatan(input) {
        if (!input.value.trim()) {
            showError(input, "Kecamatan harus diisi");
            return false;
        }
        if (input.value.length < 3) {
            showError(input, "Kecamatan minimal 3 karakter");
            return false;
        }
        if (/\d/.test(input.value)) {
            showError(input, "Kecamatan tidak boleh mengandung angka");
            return false;
        }
        removeError(input);
        return true;
    }

    // Validasi tanggal acara
    function validateTanggalAcara(input) {
        const today = new Date();
        today.setHours(0, 0, 0, 0); // Reset waktu ke 00:00:00
        const selectedDate = new Date(input.value);

        if (!input.value) {
            showError(input, "Tanggal acara harus diisi");
            return false;
        }

        if (selectedDate < today) {
            showError(input, "Tanggal acara tidak boleh kurang dari hari ini");
            return false;
        }

        removeError(input);
        return true;
    }

    // Validasi lokasi acara
    function validateLokasiAcara(input) {
        if (!input.value.trim()) {
            showError(input, "Lokasi acara harus diisi");
            return false;
        }
        if (input.value.length < 3) {
            showError(input, "Lokasi acara minimal 3 karakter");
            return false;
        }
        removeError(input);
        return true;
    }

    // Validasi keterangan order
    function validateKeterangan(input) {
        if (!input.value.trim()) {
            showError(input, "Keterangan tambahan harus diisi");
            return false;
        }
        if (input.value.length < 10) {
            showError(input, "Keterangan minimal 10 karakter");
            return false;
        }
        removeError(input);
        return true;
    }

    // Validasi nama penerima
    function validateNamaPenerima(input) {
        if (!input.value.trim()) {
            showError(input, "Nama penerima harus diisi");
            return false;
        }
        if (input.value.length < 3) {
            showError(input, "Nama penerima minimal 3 karakter");
            return false;
        }
        if (/\d/.test(input.value)) {
            showError(input, "Nama tidak boleh mengandung angka");
            return false;
        }
        removeError(input);
        return true;
    }

    // Validasi email (opsional)
    function validateEmail(input) {
        if (!input.value.trim()) {
            showError(input, "Email harus diisi");
            return false;
        }

        // Jika ada isi, validasi format
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(input.value)) {
            showError(input, "Format email tidak valid");
            return false;
        }
        removeError(input);
        return true;
    }

    // Validasi nomor telepon
    function validatePhone(input) {
        if (!input.value.trim()) {
            showError(input, "Nomor telepon harus diisi");
            return false;
        }

        const phoneRegex = /^\+62[0-9]{9,12}$/;
        if (!phoneRegex.test(input.value)) {
            showError(
                input,
                "Format nomor telepon tidak valid (contoh: +628123456789)"
            );
            return false;
        }
        removeError(input);
        return true;
    }

    // Validasi provinsi dan kota
    function validateSelect(input, fieldName) {
        if (!input.value) {
            showError(input, `${fieldName} harus dipilih`);
            return false;
        }
        removeError(input);
        return true;
    }

    // Validasi alamat
    function validateAlamat(input) {
        if (!input.value.trim()) {
            showError(input, "Alamat lengkap harus diisi");
            return false;
        }
        if (input.value.length < 10) {
            showError(input, "Alamat lengkap minimal 10 karakter");
            return false;
        }
        removeError(input);
        return true;
    }

    // Validasi kode pos
    function validateKodePos(input) {
        const kodeposRegex = /^[0-9]{5}$/;
        if (!input.value.trim()) {
            showError(input, "Kode pos harus diisi");
            return false;
        }
        if (!kodeposRegex.test(input.value)) {
            showError(input, "Kode pos harus 5 digit angka");
            return false;
        }
        removeError(input);
        return true;
    }

    // Nonaktifkan tombol bayar di awal
    togglePayButton(false);

    // Event listener untuk validasi real-time
    form.querySelectorAll("input, textarea, select").forEach((input) => {
        input.addEventListener("input", function () {
            // Validasi field saat ini
            let fieldValid = true;
            switch (this.name) {
                case "tanggalacara":
                    fieldValid = validateTanggalAcara(this);
                    break;
                case "lokasiacara":
                    fieldValid = validateLokasiAcara(this);
                    break;
                case "keterangan_order":
                    fieldValid = validateKeterangan(this);
                    break;
                case "namapenerima":
                    fieldValid = validateNamaPenerima(this);
                    break;
                case "kelurahan":
                    fieldValid = validateKelurahan(this);
                    break;
                case "kecamatan":
                    fieldValid = validateKecamatan(this);
                    break;
                case "email":
                    fieldValid = validateEmail(this);
                    break;
                case "notelppenerima":
                    fieldValid = validatePhone(this);
                    break;
                case "provinsi":
                    fieldValid = validateSelect(this, "Provinsi");
                    break;
                case "kota":
                    fieldValid = validateSelect(this, "Kota");
                    break;
                case "alamatpenerima":
                    fieldValid = validateAlamat(this);
                    break;
                case "kodepos":
                    fieldValid = validateKodePos(this);
                    break;
                case "courier":
                    fieldValid = validateSelect(this, "Kurir");
                    break;
            }

            // Validasi semua field hanya jika field saat ini valid
            if (fieldValid) {
                validateAllFields();
            } else {
                togglePayButton(false);
            }
        });
    });

    // Event listener untuk submit form
    form.addEventListener("submit", function (e) {
        e.preventDefault();

        const isValid = validateAllFields();

        if (isValid) {
            // Lanjutkan dengan proses pembayaran
            const payBtn = document.getElementById("pay-btn");
            if (payBtn) {
                payBtn.click();
            }
        } else {
            // Scroll ke error pertama
            const firstError = document.querySelector(".error-message");
            if (firstError) {
                firstError.scrollIntoView({
                    behavior: "smooth",
                    block: "center",
                });
            }
        }
    });

    // Observer untuk shipping cost
    const shippingCostInput = form.querySelector('input[name="shipping_cost"]');
    const observer = new MutationObserver(function (mutations) {
        mutations.forEach(function (mutation) {
            if (
                mutation.type === "attributes" &&
                mutation.attributeName === "value"
            ) {
                validateAllFields();
            }
        });
    });

    observer.observe(shippingCostInput, {
        attributes: true,
    });

    // Event listener untuk radio button ongkir jika ada
    form.querySelectorAll('input[name="shipping_option"]').forEach((radio) => {
        radio.addEventListener("change", validateAllFields);
    });
});
