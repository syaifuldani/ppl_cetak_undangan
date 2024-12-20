document.addEventListener("DOMContentLoaded", function () {
    const checkShippingBtn = document.getElementById("check-shipping");
    const shippingResults = document.getElementById("shipping-results");
    const courierSelect = document.getElementById("courier");
    const provinsiSelect = document.getElementById("provinsi");
    const kotaSelect = document.getElementById("kota");

    // Load provinsi saat halaman dimuat
    loadProvinsi();

    // Event listener untuk perubahan provinsi
    provinsiSelect.addEventListener("change", function () {
        const provinsiId = this.value;
        if (provinsiId) {
            loadKota(provinsiId);
        } else {
            resetKotaSelect();
        }
    });

    // Fungsi untuk memuat data provinsi
    async function loadProvinsi() {
        try {
            provinsiSelect.classList.add("loading");

            const response = await fetch(
                "../config/get_location.php?action=provinces"
            );
            const data = await response.json();

            if (data.rajaongkir?.results) {
                data.rajaongkir.results.forEach((province) => {
                    const option = document.createElement("option");
                    option.value = province.province_id;
                    option.textContent = province.province;
                    provinsiSelect.appendChild(option);
                });
            }
        } catch (error) {
            console.error("Error loading provinces:", error);
            showError("Gagal memuat data provinsi");
        } finally {
            provinsiSelect.classList.remove("loading");
        }
    }

    // Fungsi untuk memuat data kota berdasarkan provinsi
    async function loadKota(provinsiId) {
        try {
            resetKotaSelect();
            kotaSelect.classList.add("loading");
            kotaSelect.disabled = true;

            const response = await fetch(
                `../config/get_location.php?action=cities&province_id=${provinsiId}`
            );
            const data = await response.json();

            if (data.rajaongkir?.results) {
                data.rajaongkir.results.forEach((city) => {
                    const option = document.createElement("option");
                    option.value = city.city_id;
                    option.textContent = `${city.type} ${city.city_name}`;
                    kotaSelect.appendChild(option);
                });
                kotaSelect.disabled = false;
            }
        } catch (error) {
            console.error("Error loading cities:", error);
            showError("Gagal memuat data kota");
        } finally {
            kotaSelect.classList.remove("loading");
        }
    }

    // Reset dropdown kota
    function resetKotaSelect() {
        kotaSelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
        kotaSelect.disabled = true;
    }

    checkShippingBtn.addEventListener("click", async function () {
        // Validasi input
        if (!validateShippingInputs()) {
            return;
        }

        showLoading();

        // Ambil data pesanan dari cart atau order details
        // const orderItems = await getOrderItems(); // Fungsi untuk mengambil data pesanan
        // console.log("Order Items :", orderItems);

        // Kirim data ke backend untuk kalkulasi
        const formData = new FormData();

        formData.append("provinsi", provinsiSelect.value);
        formData.append("kota", kotaSelect.value);
        formData.append("courier", courierSelect.value);

        // console.log("Sending data:", {
        //     provinsi: provinsiSelect.value,
        //     kota: kotaSelect.value,
        //     courier: courierSelect.value,
        // });

        const response = await fetch("../config/calculate_shipping.php", {
            method: "POST",
            body: formData,
        });

        const data = await response.json();
        // console.log("Shipping response:", data);

        // Di bagian pemrosesan response
        if (data[0]?.rajaongkir?.results?.[0]?.costs) {
            displayShippingOptions(data[0].rajaongkir.results[0].costs);
        } else {
            throw new Error("Data ongkir tidak ditemukan");
        }
    });

    // Fungsi untuk mengambil data pesanan
    async function getOrderItems() {
        // Jika untuk order yang sudah ada
        const orderId = document.querySelector('[name="order_id"]').value;
        const response = await fetch(
            `../config/get_cart_items.php?order_id=${orderId}`
        );
        const data = await response.json();
        // console.log(data);

        if (data.status === "success") {
            return data.items;
        } else {
            throw new Error("Gagal mengambil data pesanan");
        }
    }

    function validateShippingInputs() {
        if (!kotaSelect.value) {
            showError("Pilih kota tujuan terlebih dahulu");
            return false;
        }

        if (!courierSelect.value) {
            showError("Pilih kurir pengiriman");
            return false;
        }

        return true;
    }

    // Modifikasi fungsi displayShippingOptions
    function displayShippingOptions(costs) {
        if (!Array.isArray(costs)) {
            console.error("Invalid costs data:", costs);
            showError("Format data ongkir tidak valid");
            return;
        }

        shippingResults.style.display = "block";
        shippingResults.innerHTML = costs
            .map(
                (service) => `
            <div class="shipping-option">
                <label>
                    <input type="radio" 
                           name="shipping_service" 
                           value="${service.service}"
                           data-cost="${service.cost[0].value}"
                           data-eta="${service.cost[0].etd}"
                           data-courier="${courierSelect.value.toUpperCase()}"
                           data-service="${service.service}"
                           onchange="updateTotalWithShipping(${
                               service.cost[0].value
                           })">
                    <div class="shipping-info">
                        <div>${courierSelect.value.toUpperCase()} ${
                    service.service
                }</div>
                        <div class="shipping-price">
                            Rp ${service.cost[0].value.toLocaleString()}
                        </div>
                        <div class="shipping-eta">
                            Estimasi ${service.cost[0].etd} hari
                        </div>
                    </div>
                </label>
            </div>
        `
            )
            .join("");

        // Event listener untuk radio buttons
        document
            .querySelectorAll('input[name="shipping_service"]')
            .forEach((radio) => {
                radio.addEventListener("change", function () {
                    // Tambah hidden inputs untuk data pengiriman
                    addShippingDataToForm({
                        cost: this.dataset.cost,
                        eta: this.dataset.eta,
                        courier: this.dataset.courier,
                        service: this.dataset.service,
                    });
                    updateTotal(parseInt(this.dataset.cost));
                });
            });

        function updateTotalWithShipping(shippingCost) {
            // Ambil total harga produk
            const subtotal = parseFloat(
                document.getElementById("subtotal").dataset.value
            );

            // Hitung total dengan ongkir
            const total = subtotal + shippingCost;

            // Update tampilan total
            document.getElementById(
                "total"
            ).textContent = `Rp ${total.toLocaleString()}`;

            // Simpan data untuk dikirim ke server
            const form = document.getElementById("payment-form");

            // Update hidden inputs
            let totalInput = form.querySelector('input[name="total_final"]');
            if (!totalInput) {
                totalInput = document.createElement("input");
                totalInput.type = "hidden";
                totalInput.name = "total_final";
                form.appendChild(totalInput);
            }
            totalInput.value = total;

            let shippingInput = form.querySelector(
                'input[name="shipping_cost"]'
            );
            if (!shippingInput) {
                shippingInput = document.createElement("input");
                shippingInput.type = "hidden";
                shippingInput.name = "shipping_cost";
                form.appendChild(shippingInput);
            }
            shippingInput.value = shippingCost;
        }
    }

    // Fungsi untuk menambah data pengiriman ke form
    function addShippingDataToForm(data) {
        const form = document.getElementById("payment-form");

        // Data yang perlu disimpan
        const shippingData = {
            shipping_cost: data.cost,
            shipping_courier: data.courier,
            shipping_service: data.service,
            shipping_eta: data.eta,
        };

        // Update atau tambah hidden inputs
        for (const [key, value] of Object.entries(shippingData)) {
            let input = form.querySelector(`input[name="${key}"]`);
            if (!input) {
                input = document.createElement("input");
                input.type = "hidden";
                input.name = key;
                form.appendChild(input);
            }
            input.value = value;
        }
    }

    function updateShippingData(shippingData) {
        // Update atau buat hidden inputs untuk data pengiriman
        const form = document.getElementById("payment-form");

        ["cost", "eta", "courier", "service"].forEach((field) => {
            let input = form.querySelector(`input[name="shipping_${field}"]`);
            if (!input) {
                input = document.createElement("input");
                input.type = "hidden";
                input.name = `shipping_${field}`;
                form.appendChild(input);
            }
            input.value = shippingData[field];
        });
    }

    function updateTotal(shippingCost) {
        const subtotal = parseInt(
            document.getElementById("subtotal")?.dataset?.value || 0
        );
        const total = subtotal + shippingCost;

        // Update tampilan total
        const totalElement = document.getElementById("total");
        if (totalElement) {
            totalElement.textContent = `Rp ${total.toLocaleString()}`;
        }

        // Simpan shipping cost ke hidden input untuk disubmit
        const shippingCostInput = document.createElement("input");
        shippingCostInput.type = "hidden";
        shippingCostInput.name = "shipping_cost";
        shippingCostInput.value = shippingCost;

        // Replace existing shipping cost input if exists
        const existingInput = document.querySelector(
            'input[name="shipping_cost"]'
        );
        if (existingInput) {
            existingInput.remove();
        }
        document.getElementById("payment-form").appendChild(shippingCostInput);
    }

    function showLoading() {
        checkShippingBtn.disabled = true;
        checkShippingBtn.textContent = "Mengecek...";
        shippingResults.style.display = "none";

        const loadingDiv = document.createElement("div");
        loadingDiv.className = "loading-indicator";
        loadingDiv.style.display = "block";
        shippingResults.parentNode.insertBefore(loadingDiv, shippingResults);
    }

    function hideLoading() {
        checkShippingBtn.disabled = false;
        checkShippingBtn.textContent = "Cek Ongkir";

        const loadingDiv = document.querySelector(".loading-indicator");
        if (loadingDiv) {
            loadingDiv.remove();
        }
    }

    function showError(message) {
        const errorDiv = document.createElement("div");
        errorDiv.className = "error-message";
        errorDiv.textContent = message;
        errorDiv.style.display = "block";

        shippingResults.parentNode.insertBefore(errorDiv, shippingResults);

        setTimeout(() => {
            errorDiv.remove();
        }, 3000);
    }
});
