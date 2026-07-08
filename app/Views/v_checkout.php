<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-6">
        <?= form_open('buy', 'class="row g-3"') ?>

        <?= form_hidden('username', session()->get('username')) ?>
        <input type="hidden" name="subtotal_harga" id="subtotal_harga" value="<?= $total ?>">
        <input type="hidden" name="total_harga" id="total_harga" value="">

        <div class="col-12">
            <?= form_label('Nama', 'nama', ['class' => 'form-label']) ?>
            <?= form_input([
                'name'     => 'nama',
                'id'       => 'nama',
                'class'    => 'form-control',
                'value'    => session()->get('username'),
                'readonly' => true]) ?>
        </div>
        <div class="col-12">
            <?= form_label('Alamat', 'alamat', ['class' => 'form-label']) ?>
            <?= form_input([
                'name'  => 'alamat',
                'id'    => 'alamat',
                'class' => 'form-control',
                'placeholder' => 'Masukkan alamat lengkap']) ?>
        </div> 
        <div class="col-12"> 
            <?= form_label('Kelurahan', 'kelurahan', ['class' => 'form-label']) ?>
            <?= form_dropdown('kelurahan', [], '', ['id' => 'kelurahan', 'class' => 'form-control']) ?>
        </div>
        <div class="col-12"> 
            <?= form_label('Layanan', 'layanan', ['class' => 'form-label']) ?> 
            <?= form_dropdown('layanan', [], '', ['id' => 'layanan', 'class' => 'form-control']) ?>
        </div>
        <div class="col-12">
            <?= form_label('Ongkir', 'ongkir', ['class' => 'form-label']) ?>
            <input type="text" id="ongkir_tampilan" class="form-control" readonly value="Rp 0">
            <input type="hidden" name="ongkir" id="ongkir" value="0">
        </div>
        
        <div class="col-12">
            <?= form_label('Kode Voucher', 'voucher_code', ['class' => 'form-label']) ?>
            <input type="text" name="voucher_code" id="voucher_code" class="form-control" placeholder="Contoh: PROMO2026">
            <small class="text-muted">Tersedia: PROMO2025 (10%), PROMO2026 (15%), AKHIRTAHUN (25%)</small>
        </div>

        <div class="col-12">
            <?= form_submit(
                'submit',
                'Buat Pesanan',
                ['class' => 'btn btn-primary']) ?>
        </div>

        <?= form_close() ?> 
    </div>
    
    <div class="col-lg-6">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Nama</th>
                    <th scope="col">Harga</th>
                    <th scope="col">Jumlah</th>
                    <th scope="col">Sub Total</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if (!empty($items)) :
                    foreach ($items as $index => $item) :
                ?>
                        <tr>
                            <td><?= $item['name'] ?></td>
                            <td><?= number_to_currency($item['price'], 'IDR') ?></td>
                            <td><?= $item['qty'] ?></td>
                            <td><?= number_to_currency($item['price'] * $item['qty'], 'IDR') ?></td>
                        </tr>
                <?php
                    endforeach;
                endif;
                ?>
                <tr>
                    <td colspan="2"></td>
                    <td>Subtotal</td>
                    <td>IDR <?= number_format($total, 0, ',', '.') ?></td>
                </tr>
                
                <tr>
                    <td colspan="2"></td>
                    <td class="text-danger">Diskon Voucher</td>
                    <td class="text-danger">
                        -IDR <span id="tampil_diskon_voucher">0</span> (<span id="tampil_persen_voucher">0</span>%)
                    </td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td>Biaya Jasa</td>
                    <td>IDR <span id="tampil_biaya_jasa">0</span></td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td class="text-warning">Free Mouse</td>
                    <td class="text-warning">-IDR <span id="tampil_free_mouse">0</span></td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td>Subtotal (+Jasa -Voucher -Free Mouse)</td>
                    <td><strong>IDR <span id="subtotal_promo_tampilan">0</span></strong></td>
                </tr>
                <tr class="table-primary">
                    <td colspan="2"></td>
                    <td><strong>Grand Total (incl. Ongkir)</strong></td>
                    <td><strong>IDR <span id="total">0</span></strong></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
$(document).ready(function() {
    let ongkir = 0;
    let subtotal = <?= $total ?>;
    
    // Variabel state promo global
    let biayaJasa = 0;
    let diskonVoucher = 0;
    let persenVoucher = 0;
    let freeMouse = 0;

    // Hitung inisialisasi awal saat halaman dibuka
    cekPromoDanHitung();

    function hitungTotal() {
        // Rumus total kombinasi promo dan ongkir
        let subtotalPromo = subtotal + biayaJasa - diskonVoucher - freeMouse;
        let grandTotal = subtotalPromo + ongkir;

        // Tembakkan hasil angka terformat ke interface ringkasan kanan
        $("#tampil_biaya_jasa").text(biayaJasa.toLocaleString('id-ID'));
        $("#tampil_diskon_voucher").text(diskonVoucher.toLocaleString('id-ID'));
        $("#tampil_persen_voucher").text(persenVoucher);
        $("#tampil_free_mouse").text(freeMouse.toLocaleString('id-ID'));
        $("#subtotal_promo_tampilan").text(subtotalPromo.toLocaleString('id-ID'));
        
        // Atur display box input ongkir kiri
        $("#ongkir").val(ongkir);
        $("#ongkir_tampilan").val(`Rp ${ongkir.toLocaleString('id-ID')}`);

        // Kunci nilai akhir ke element grand_total & input hidden form
        $("#total").text(grandTotal.toLocaleString('id-ID'));
        $("#total_harga").val(grandTotal);
    }

    function cekPromoDanHitung() {
        let kode = $("#voucher_code").val();
        
        $.ajax({
            url: "<?= site_url('transaksicontroller/hitung_promo_ajax') ?>",
            type: "GET",
            dataType: "json",
            data: {
                subtotal: subtotal,
                voucher_code: kode
            },
            success: function(res) {
                biayaJasa = res.biaya_jasa;
                diskonVoucher = res.voucher_diskon;
                persenVoucher = res.voucher_persen;
                freeMouse = res.free_mouse;
                hitungTotal();
            }
        });
    }

    // Listener interaksi dinamis ketikan voucher promo
    $("#voucher_code").on('keyup change', function() {
        cekPromoDanHitung();
    });

    $('#kelurahan').select2({
        placeholder: 'Cari daerah tujuan',
        minimumInputLength: 3,
        ajax: {
            url: '<?= site_url('ajax/destinations') ?>',
            dataType: 'json',
            delay: 300,
            data: function(params) {
                return {
                    q: params.term
                };
            },
            processResults: function(data) {
                return data;
            },
            cache: true
        }
    });

    $("#kelurahan").on('change', function () {
        let id_kelurahan = $(this).val();

        $("#layanan").empty().append('<option value="">-- Memuat Layanan... --</option>');
        ongkir = 0;
        $("#ongkir").val(0);
        hitungTotal(); 

        $.ajax({
            url: "<?= site_url('ajax/costs') ?>", 
            dataType: "json",
            data: {
                destination: id_kelurahan,
            },
            success: function (response) { 
                $("#layanan").empty().append('<option value="">-- Pilih Layanan --</option>');
                
                if (response && response.length > 0) {
                    response.forEach(function (item) {
                        $("#layanan").append(
                            $('<option>', {
                                value: item.cost,
                                text: `${item.service} : Rp ${item.cost.toLocaleString('id-ID')} (Estimasi ${item.etd} Hari)`
                            })
                        );
                    });
                } else {
                    $("#layanan").append('<option value="">Layanan tidak tersedia</option>');
                }
            },
            error: function() {
                $("#layanan").empty().append('<option value="">Gagal memuat layanan</option>');
            }
        });
    });

    $("#layanan").on('change', function() {
        ongkir = parseInt($(this).val()) || 0;
        hitungTotal();
    }); 
});
</script>
<?= $this->endSection() ?>