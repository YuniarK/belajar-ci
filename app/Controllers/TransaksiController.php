<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\RajaOngkirService;

use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;

class TransaksiController extends BaseController
{
    protected $cart;
    protected $transactionModel;
    protected $transactionDetailModel;

    public function __construct()
    {
        helper(['number', 'form']);
        $this->cart = service('cart');
        $this->transactionModel = new TransactionModel();
        $this->transactionDetailModel = new TransactionDetailModel(); 
    }

    public function index()
    {  
        $data = [
            'items' => $this->cart->contents(),
            'total' => $this->cart->total() 
        ];

        return view('v_keranjang', $data);
    }

    public function cart_add()
    {
	$this->cart->insert([
	    'id'      => $this->request->getPost('id'),
	    'qty'     => 1,
	    'price'   => $this->request->getPost('harga'),
	    'name'    => $this->request->getPost('nama'),
	    'options' => [
	        'foto' => $this->request->getPost('foto')
	    ]
	]);
	
	session()->setFlashdata(
	    'success',
	    'Produk berhasil ditambahkan ke keranjang. 
	    <a href="' . base_url('keranjang') . '">Lihat</a>'
	);
	
	return redirect()->to(base_url('/'));
    } 

    public function cart_edit()
    {
    $i = 1;
    foreach ($this->cart->contents() as $item) {
        $qty = $this->request->getPost('qty' . $i++);

        $this->cart->update([
            'rowid' => $item['rowid'],
            'qty'   => $qty
        ]);
    }

    session()->setFlashdata(
        'success',
        'Keranjang berhasil diperbarui'
    );

    return redirect()->to(base_url('keranjang'));
    }

    public function cart_delete($rowid)
    {
    $this->cart->remove($rowid);

    session()->setFlashdata(
        'success',
        'Produk berhasil dihapus dari keranjang'
    );

    return redirect()->to(base_url('keranjang'));
    }

    public function cart_clear()
    {
    $this->cart->destroy();

    session()->setFlashdata(
        'success',
        'Keranjang berhasil dikosongkan'
    );

    return redirect()->to(base_url('keranjang'));
    }
    
    public function checkout()
    {  
    $data = [
        'items' => $this->cart->contents(),
        'total' => $this->cart->total() 
    ];

    return view('v_checkout', $data);
    }

    public function destinations()
    {
        $search = $this->request->getGet('q'); 

        $service = new RajaOngkirService();
        $response = $service->getDestination($search);

        $results = [];
        $data = $response['data'] ?? [];

        foreach ($data as $item) {
            $results[] = [
                'id'   => $item['id'],
                'text' => $item['label']
            ];
        }

        return $this->response->setJSON([
            'results' => $results
        ]);
    }

    public function costs()
    {
    $origin = '64999';
    $destination = $this->request->getGet('destination');
    $weight = '1000';
    $courier = 'jne'; 

    $service = new RajaOngkirService();
    $response = $service->getCost($origin, $destination, $weight, $courier);

    $results = [];
    $data = $response['data'] ?? [];

    foreach ($data as $item) {
        $results[] = [
            'service'     => $item['service'],
            'description' => $item['description'],
            'cost'        => $item['cost'],
            'etd'         => $item['etd']
        ];
    }

    return $this->response->setJSON($results);
    }

    public function hitung_promo_ajax()
    {
        helper('promo');
        
        $subtotal = (float) $this->request->getGet('subtotal');
        $voucher_code = $this->request->getGet('voucher_code') ?? '';

        // Hitung menggunakan fungsi-fungsi dari promo_helper
        $biaya_jasa = hitung_biaya_jasa($subtotal);
        $data_voucher = hitung_diskon_voucher($subtotal, $voucher_code);
        $free_mouse = hitung_free_mouse($subtotal);

        return $this->response->setJSON([
            'biaya_jasa'     => $biaya_jasa,
            'voucher_persen' => $data_voucher['persen'],
            'voucher_diskon' => $data_voucher['nominal'],
            'free_mouse'     => $free_mouse
        ]);
    }

    public function buy()
    { 
        $cartItems = $this->cart->contents();

        if (empty($cartItems)) {
            return redirect()->back();
        }

        // 1. LOAD HELPER PROMO UAS (sebelumnya helper diskon kuis kemarin)
        helper('promo');

        $db = \Config\Database::connect();
        $db->transStart(); 

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item['qty'] * $item['price'];
        }

        // 2. TANGKAP INPUTAN KODE VOUCHER DARI CHECKOUT
        $voucher_code = $this->request->getVar('voucher_code') ?? '';

        // 3. HITUNG ULANG LOGIKA BISNIS DI SERVER DEMI KEAMANAN DATA DB 
        $biaya_jasa = hitung_biaya_jasa($subtotal);
        $data_voucher = hitung_diskon_voucher($subtotal, $voucher_code);
        $nominal_voucher = $data_voucher['nominal'];
        $free_mouse = hitung_free_mouse($subtotal);

        // 4. AMANKAN DATA ONGKIR
        $input_ongkir = $this->request->getVar('ongkir');
        $ongkir = (!empty($input_ongkir)) ? (float) $input_ongkir : 0.0;

        // 5. RUMUS GRAND TOTAL SESUAI LEMBAR SOAL UAS
        // Subtotal + Jasa - Voucher - Free Mouse + Ongkir
        $total_akhir = $subtotal + $biaya_jasa - $nominal_voucher - $free_mouse + $ongkir;

        $transaction = [
            'username'       => $this->request->getPost('username'),
            'alamat'         => $this->request->getPost('alamat'),
            'ongkir'         => $ongkir,
            'biaya_jasa'     => $biaya_jasa,     // Field Baru UAS 
            'voucher_code'   => !empty($voucher_code) ? strtoupper(trim($voucher_code)) : null, // Field Baru UAS 
            'diskon_voucher' => $nominal_voucher, // Field Baru UAS 
            'free_mouse'     => $free_mouse,     // Field Baru UAS 
            'total_harga'    => (int) round($total_akhir), 
            'status'         => 0, 
        ];

        // insert transaction
        if (!$this->transactionModel->insert($transaction)) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Gagal membuat transaksi');
        }

        $transactionId = $this->transactionModel->getInsertID();

        // insert transaction detail
        foreach ($cartItems as $item) {
            $this->transactionDetailModel->insert([
                'transaction_id' => $transactionId,
                'product_id'     => $item['id'],
                'jumlah'         => $item['qty'],
                'diskon'         => 0,
                'subtotal_harga' => $item['qty'] * $item['price'] 
            ]);
        }

        $db->transComplete();

        if (!$db->transStatus()) {
            return redirect()->back()->with('error', 'Gagal membuat transaksi');
        }

        // hapus session keranjang belanja 
        $this->cart->destroy();
        return redirect()->to(base_url())->with('success', 'Transaksi Berhasil Dibuat!');
    }

    public function history()
    {
        $username = session()->get('username'); 
    
        $transactions = $this->transactionModel->where('username', $username)->findAll();
        $transactionIds = array_column($transactions, 'id');

        $products = $this->transactionDetailModel->getProductsByTransactionIds($transactionIds);

        $data = [
            'username'      => $username,
            'transactions'  => $transactions,
            'products'      => $products
        ]; 

        return view('v_history', $data);
    }
}
