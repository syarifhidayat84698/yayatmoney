<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
            font-family: 'Inter', sans-serif;
            color: #334155;
        }
        .nota {
            width: 108mm;
            background-color: white;
            padding: 30px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            font-size: 14px;
            line-height: 1.6;
            position: relative;
            border-bottom: 2px solid #f0f0f0;
        }
        .nota::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 8px;
            background: linear-gradient(90deg, #007bff, #00c6ff);
            border-radius: 12px 12px 0 0;
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100px;
            opacity: 0.03;
            pointer-events: none;
            white-space: nowrap;
            color: #000;
            font-weight: 900;
        }
        .header {
            display: flex;
            align-items: flex-start;
            justify-content: flex-start;
            flex-direction: column;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }
        .header-top {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 10px;
        }
        .header-top img {
            width: 80px;
            height: 80px;
            object-fit: contain;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header-text-group {
            display: flex;
            flex-direction: column;
        }
        h2 {
            color: #2d3748;
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            background: linear-gradient(90deg, #007bff, #00c6ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.5px;
            margin-bottom: 5px;
        }
        .address-line {
            font-size: 10px;
            color: #334155;
            margin: 0;
            line-height: 1.5;
        }
        .contact-details {
            color: #334155;
        }
        .bank-info {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px dashed #e2e8f0;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 10px;
        }
        .bank-info p {
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 10px;
        }
        .bank-info .bank-item {
            flex-basis: calc(50% - 5px);
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
        .bank-info svg {
            width: 12px;
            height: 12px;
        }
        .invoice-details {
            background-color: #f8fafc;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 25px;
            border: 1px solid #e2e8f0;
        }
        .invoice-details table {
            width: 100%;
            border: none;
        }
        .invoice-details th {
            color: #64748b;
            font-weight: 500;
            width: 140px;
            padding: 8px 0;
        }
        .invoice-details td {
            color: #334155;
            font-weight: 500;
            padding-left: 5px;
        }
        .items-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 25px;
        }
        .items-table th {
            background-color: #f1f5f9;
            color: #64748b;
            font-weight: 600;
            text-align: left;
            padding: 12px;
            border-bottom: 2px solid #e2e8f0;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
            color: #334155;
            font-size: 13px;
        }
        .items-table tr:last-child td {
            border-bottom: none;
        }
        .items-table tr:hover td {
            background-color: #f8fafc;
        }
        .total-section {
            background-color: #f8fafc;
            border-radius: 8px;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            border: 1px solid #e2e8f0;
        }
        .total-label {
            font-size: 16px;
            font-weight: 600;
            color: #64748b;
        }
        .total-amount {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            background: linear-gradient(90deg, #007bff, #00c6ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #f0f0f0;
            text-align: center;
            font-size: 12px;
            color: #64748b;
        }
        .footer p {
            margin: 5px 0;
        }
        .qr-section {
            margin-top: 20px;
            text-align: center;
        }
        .qr-code {
            width: 100px;
            height: 100px;
            margin: 0 auto;
            background-color: #f8fafc;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }
        .download-btn {
            margin-top: 20px;
            padding: 12px 24px;
            background: linear-gradient(90deg, #007bff, #00c6ff);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .download-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.2);
        }
        .download-btn svg {
            width: 20px;
            height: 20px;
        }
        @media print {
            body {
                background: none;
                padding: 0;
            }
            .nota {
                box-shadow: none;
                padding: 20px;
            }
            .download-btn {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="nota" id="nota">
        <div class="watermark">PAID</div>
        <div class="header">
            <div class="header-top">
                <img src="{{ asset('assets/images/yayat.png') }}" alt="logo">
                <div class="header-text-group">
                    <h2>Hidayat Collection</h2>
                    <p class="address-line">Kios 1 Pasar Sandang Tegal Gubuk Blok G193</p>
                    <p class="address-line">Kios 2 Tembok Kidul RT 12/RW 02, Adiwerna, Tegal</p>
                  
                    <p class="address-line">WA: 082333305520</p>
                </div>
            </div>
            <div class="contact-details">
                <div class="bank-info">
                    <div class="bank-item">
                        <p>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            BCA: 0990885745
                        </p>
                        <p style="font-size: 9px; color: #64748b;">a.n. Muhamad Syarif Hidayatullah</p>
                    </div>
                    <div class="bank-item">
                        <p>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            BRI: 054501003553505
                        </p>
                        <p style="font-size: 9px; color: #64748b;">a.n. Akhmad Zaeni</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="invoice-details">
            <table>
                <tr>
                    <th>Nomor Tagihan</th>
                    <td>: {{ $transaction->nomor_tagihan}}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td>: {{ date('d F Y', strtotime($transaction->due_date)) }}</td>
                </tr>
                <tr>
                    <th>Nama Customer</th>
                    <td>: {{$transaction->nama_customer }}</td>
                </tr>
                <tr>
                    <th>Nomor WhatsApp</th>
                    <td>: {{ $transaction->nomor_whatsapp }}</td>
                </tr>
            </table>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 40px; text-align: center;">No</th>
                    <th style="width: 80px; text-align: center;">Qty</th>
                    <th>Nama Barang</th>
                    <th style="text-align: right;">Harga</th>
                    <th style="text-align: right;">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaction->details as $index => $detail)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td style="text-align: center;">{{ $detail->banyaknya }}</td>
                    <td>{{ $detail->nama_barang }}</td>
                    <td style="text-align: right;">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                    <td style="text-align: right;">Rp {{ number_format($detail->jumlah, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-section">
            <span class="total-label">Total Pembayaran</span>
            <span class="total-amount">Rp {{ number_format($total, 0, ',', '.') }}</span>
        </div>

        <div class="footer">
            <p>Terima kasih atas kepercayaan Anda berbelanja dengan kami.</p>
            <p>Simpan nota ini sebagai bukti pembayaran yang sah.</p>
            <div class="qr-section">
                <div class="qr-code">
                    @php
                        $qrData = url('/input_nota/' . $transaction->id);
                    @endphp
                    {!! QrCode::size(100)
                        ->backgroundColor(248, 250, 252)
                        ->generate($qrData) !!}
                </div>
                <p style="margin-top: 8px; font-size: 11px; color: #64748b;">
                    Scan untuk verifikasi detail transaksi
                </p>
            </div>
        </div>
    </div>

    <button class="download-btn" onclick="downloadNota()">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
        </svg>
        Download Nota
    </button>

    <script>
        function downloadNota() {
            html2canvas(document.getElementById("nota"), { 
                scale: 2,
                backgroundColor: '#ffffff',
                logging: false,
                useCORS: true,
                onclone: function(clonedDoc) {
                    clonedDoc.querySelector('.watermark').style.opacity = '0.05';
                }
            }).then(canvas => {
                let link = document.createElement("a");
                link.download = "nota-" + "{{ $transaction->nomor_tagihan }}" + ".jpg";
                link.href = canvas.toDataURL("image/jpeg", 0.92);
                link.click();
            });
        }
    </script>
</body>
</html>
