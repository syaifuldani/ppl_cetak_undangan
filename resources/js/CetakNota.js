// Tambahkan fungsi generateNota
function generateNota(orderId) {
    fetch(`../config/getOrderDetailForPrintNote.php?order_id=${orderId}`)
        .then((response) => response.json())
        .then((order) => {
            const nota = `
                <div class="nota" style="padding: 20px; font-family: Arial, sans-serif;">
                    <div style="display: flex; align-items: center; justify-content: flex-start; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 20px;">
                        <!-- Logo di sebelah kiri -->
                        <div style="width: 150px; margin-right: 20px;">
                            <img src="../resources/img/logo.png" style="width: 100%; height: auto;" alt="Plee Art Logo">
                        </div>
                        
                        <!-- Informasi toko di sebelah kanan logo -->
                        <div style="flex-grow: 1; text-align: center;">
                            <h2 style="margin: 0; font-size: 24px; color: #333; text-transform: uppercase; font-weight: bold;">NOTA PEMBELIAN</h2>
                            <h3 style="margin: 5px 0; font-size: 20px; color: #4CAF50;">Plee Art</h3>
                            <p style="margin: 5px 0; font-size: 12px; color: #666; line-height: 1.4;">
                                Jl. A Yani Dsn.Sumberjo Ds.Sumbertanggul Kec. Mojosari<br>
                                Kab. Mojokerto, 41382, Jawa Timur Indonesia
                            </p>
                            <p style="margin: 5px 0; font-size: 14px; color: #444;">
                                <strong>Telp:</strong> +62 878-5333-8254
                            </p>
                        </div>
                    </div>
                    
                    <!-- Informasi Order dengan styling yang lebih baik -->
                    <div style="margin-bottom: 20px; background-color: #f9f9f9; padding: 15px; border-radius: 5px;">
                        <table style="width: 100%; margin-bottom: 10px; border-collapse: collapse;">
                            <tr>
                                <td style="width: 50%; padding: 8px;">
                                    <strong style="color: #4CAF50;">Order ID:</strong> 
                                    <span style="color: #666;">${
                                        order.order_id
                                    }</span><br>
                                    <strong style="color: #4CAF50;">Tanggal:</strong> 
                                    <span style="color: #666;">${formatDate(
                                        order.created_at
                                    )}</span><br>
                                    <strong style="color: #4CAF50;">Status:</strong> 
                                    <span style="color: #666;">${
                                        order.transaction_status || "-"
                                    }</span>
                                </td>
                                <td style="width: 50%; padding: 8px; border-left: 2px solid #eee;">
                                    <strong style="color: #4CAF50;">Informasi Penerima:</strong><br>
                                    <span style="color: #666; font-size: 14px;">
                                        ${order.nama_penerima}<br>
                                        ${order.nomor_penerima}<br>
                                        ${order.alamat_penerima}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- Tabel Produk dengan styling yang lebih menarik -->
                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                        <thead>
                            <tr style="background-color: #4CAF50; color: white;">
                                <th style="padding: 12px; border: 1px solid #ddd; text-align: left;">Produk</th>
                                <th style="padding: 12px; border: 1px solid #ddd; text-align: right;">Harga</th>
                                <th style="padding: 12px; border: 1px solid #ddd; text-align: center;">Jumlah</th>
                                <th style="padding: 12px; border: 1px solid #ddd; text-align: right;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${order.items
                                .map(
                                    (item) => `
                                <tr>
                                    <td style="padding: 12px; border: 1px solid #ddd;">
                                        <div style="font-weight: 500; color: #333;">${
                                            item.nama_produk
                                        }</div>
                                    </td>
                                    <td style="padding: 12px; border: 1px solid #ddd; text-align: right; color: #666;">
                                        Rp ${formatNumber(item.harga_order)}
                                    </td>
                                    <td style="padding: 12px; border: 1px solid #ddd; text-align: center; color: #666;">
                                        ${item.jumlah_order}
                                    </td>
                                    <td style="padding: 12px; border: 1px solid #ddd; text-align: right; color: #333; font-weight: 500;">
                                        Rp ${formatNumber(
                                            item.harga_order * item.jumlah_order
                                        )}
                                    </td>
                                </tr>
                            `
                                )
                                .join("")}
                        </tbody>
                        <tfoot>
                            <tr style="background-color: #f5f5f5;">
                                <td colspan="3" style="padding: 12px; border: 1px solid #ddd; text-align: right;">
                                    <strong style="color: #333; font-size: 16px;">Total Pembayaran:</strong>
                                </td>
                                <td style="padding: 12px; border: 1px solid #ddd; text-align: right;">
                                    <strong style="color: #4CAF50; font-size: 16px;">Rp ${formatNumber(
                                        order.total_harga
                                    )}</strong>
                                </td>
                            </tr>
                        </tfoot>
                    </table>

                    <!-- Footer dengan position relative dan margin yang cukup -->
                    <div style="position: relative; margin-top: 40px; padding: 20px; border-top: 2px solid #4CAF50;">
                        <div style="page-break-inside: avoid;">
                            <p style="text-align: center; margin: 5px 0; color: #666; font-size: 14px; font-weight: bold;">
                                Terima kasih telah berbelanja di Plee Art
                            </p>
                            <p style="text-align: center; margin: 10px 0; color: #888; font-size: 12px; font-style: italic;">
                                Simpan nota ini sebagai bukti pembelian yang sah
                            </p>
                            
                            <!-- Watermark dengan position relative -->
                            <div style="text-align: center; margin-top: 20px; position: relative;">
                                <img src="../resources/img/logo.png" 
                                    style="width: 120px; opacity: 0.2; position: relative; z-index: 1;" 
                                    alt="Watermark">
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Konfigurasi PDF yang lebih baik
            const opt = {
                margin: [8, 8, 8, 8],
                filename: `nota-${orderId}.pdf`,
                image: { type: "jpeg", quality: 0.98 },
                html2canvas: {
                    scale: 2,
                    logging: true,
                    useCORS: true,
                },
                jsPDF: {
                    unit: "mm",
                    format: "a4",
                    orientation: "portrait",
                    compress: true,
                    putOnlyUsedFonts: true,
                },
            };

            // Generate PDF
            // Tambahkan event handler untuk memastikan gambar dimuat
            const element = document.createElement("div");
            element.innerHTML = nota;

            // Tunggu semua gambar dimuat sebelum generate PDF
            Promise.all(
                Array.from(element.querySelectorAll("img")).map((img) => {
                    if (img.complete) return Promise.resolve();
                    return new Promise((resolve) => {
                        img.onload = resolve;
                        img.onerror = resolve;
                    });
                })
            ).then(() => {
                html2pdf()
                    .from(element)
                    .set(opt)
                    .save()
                    .catch((err) => {
                        console.error("Error generating PDF:", err);
                        alert("Gagal mengunduh nota. Silakan coba lagi.");
                    });
            });
        })
        .catch((error) => {
            console.error("Error fetching order details:", error);
            alert("Gagal mengambil data order");
        });
}
