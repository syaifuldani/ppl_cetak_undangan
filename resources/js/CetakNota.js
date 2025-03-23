async function generateNota(orderId) {
    try {
        // console.log("Generating nota for order:", orderId);

        const response = await fetch(
            `../config/getOrderDetailForPrintNote.php?order_id=${orderId}`
        );
        const data = await response.json();
        // console.log("ini data :", data);

        if (!data.success) {
            throw new Error(data.message || "Gagal memuat data pesanan");
        }

        const order = data.order;
        // console.log(order);

        const notaTemplate = `
            <div class="nota" style="padding: 20px; font-family: Arial, sans-serif;">
                <div style="display: flex; align-items: center; justify-content: flex-start; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 20px;">
                    <div style="width: 150px; margin-right: 20px;">
                        <img src="../resources/img/logo.png" style="width: 100%; height: auto;" alt="Plee Art Logo">
                    </div>
                    
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
                        ${
                            order.items && order.items.length > 0
                                ? order.items
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
                                      .join("")
                                : '<tr><td colspan="4" style="padding: 12px; border: 1px solid #ddd; text-align: center;">Tidak ada item</td></tr>'
                        }
                    </tbody>
                    <tfoot>
                        <tr style="background-color: #f5f5f5;">
                            <td colspan="4" style="text-align: right; padding: 8px;">
                                <div style="display: inline-block;">
                                    <div style="font-size: 12px; margin-bottom: 8px;">
                                        <span style="display: inline-block; text-align: right; margin-right: 10px;">Biaya Ongkir:</span>
                                        <span style="display: inline-block; width: 100px; text-align: right;">
                                            Rp ${formatNumber(
                                                order.biaya_ongkir || 0
                                            )}
                                        </span>
                                    </div>
                                    <div style="white-space: nowrap;">
                                        <strong style="display: inline-block; text-align: right; margin-right: 10px;">Total Pembayaran:</strong>
                                        <strong style="display: inline-block; width: 100px; text-align: right; color: #4CAF50;">
                                            Rp ${formatNumber(
                                                parseInt(order.total_harga)
                                            )}
                                        </strong>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
 
                <div style="position: relative; margin-top: 40px; padding: 20px; border-top: 2px solid #4CAF50;">
                    <div style="page-break-inside: avoid;">
                        <p style="text-align: center; margin: 5px 0; color: #666; font-size: 14px; font-weight: bold;">
                            Terima kasih telah berbelanja di Plee Art
                        </p>
                        <p style="text-align: center; margin: 10px 0; color: #888; font-size: 12px; font-style: italic;">
                            Simpan nota ini sebagai bukti pembelian yang sah
                        </p>
                        
                        <div style="text-align: center; margin-top: 20px; position: relative;">
                            <img src="../resources/img/logo.png" 
                                style="width: 200px; opacity: 0.2; position: relative; z-index: 1;" 
                                alt="Watermark">
                        </div>
                    </div>
                </div>
            </div>
        `;

        const opt = {
            margin: 1,
            filename: `nota-${order.order_id}.pdf`,
            html2canvas: { scale: 2 },
            jsPDF: { unit: "in", format: "letter", orientation: "portrait" },
        };

        html2pdf().set(opt).from(notaTemplate).save();
    } catch (error) {
        console.error("Error fetching order details:", error);
        alert("Gagal membuat nota: " + error.message);
    }
}

// Helper function untuk format angka dan tanggal
function formatNumber(number) {
    return new Intl.NumberFormat("id-ID").format(number);
}

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString("id-ID", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
}
