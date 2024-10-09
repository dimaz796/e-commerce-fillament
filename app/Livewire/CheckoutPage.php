<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Models\Order;
use App\Models\Address;
use App\Models\Cart;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Title;
use Livewire\Component;
use Midtrans\Config;
use Midtrans\Snap;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;
use Stripe\Webhook;

#[Title("Checkout")]
class CheckoutPage extends Component
{
    public $first_name;
    public $last_name;
    public $phone;
    public $street_address;
    public $city;
    public $state;
    public $zip_code;
    public $payment_method;

    public function mount()
    {
        $cart_items = Cart::where('user_id', auth()->id())->get();
        if (empty($cart_items)) {
            return redirect('/products');
        }
    }

    public function placeOrder()
    {
        $this->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'street_address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip_code' => 'required',
            'payment_method' => 'required|in:stripe,midtrans,cod',
        ]);

        $cart_items = Cart::where('user_id', auth()->id())->get();
        $grand_total = CartManagement::calculateGrandTotal($cart_items);

        switch ($this->payment_method) {
            case 'stripe':
                return $this->processStripePayment($cart_items, $grand_total);
            case 'midtrans':
                return $this->processMidtransPayment($cart_items, $grand_total);
            case 'cod':
                return $this->processCodPayment();
            default:
                return redirect()->back()->with('error', 'Invalid payment method');
        }
    }

    // Start Proses Payment COD
    private function processCodPayment()
    {
        $order_id = $this->saveOrder('cod', 'pending');
        return redirect()->route('success');
    }

    // Proses Midtrans Payment
    private function processMidtransPayment($cart_items, $grand_total)
{
    Config::$serverKey = env('MIDTRANS_SERVER_KEY');
    Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
    Config::$isSanitized = true;
    Config::$is3ds = true;

    try {
        // Simpan data customer ke session
        session([
            'midtrans_customer' => [
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'phone' => $this->phone,
                'street_address' => $this->street_address,
                'city' => $this->city,
                'state' => $this->state,
                'zip_code' => $this->zip_code,
            ],
            'midtrans_cart_items' => $cart_items->toArray(), // Konversi ke array
        ]);

        $params = [
            'transaction_details' => [
                'order_id' => 'order-temp-' . time(),
                'gross_amount' => $grand_total,
            ],
            'customer_details' => [
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => auth()->user()->email,
                'phone' => $this->phone,
            ],
            'item_details' => $cart_items->map(function ($item) {
                return [
                    'id' => $item->product_id,
                    'price' => $item->productVariant->price, // Ambil harga dari relasi productVariant
                    'quantity' => $item->quantity,
                    'name' => $item->product->name, // Ambil nama dari relasi product
                ];
            })->toArray(), // Konversi hasil map ke array
            'callbacks' => [
                'finish' => url('/midtrans/callback'),
            ]
        ];

        
        $snapToken = Snap::getSnapToken($params);

        return redirect()->to("https://app.sandbox.midtrans.com/snap/v2/vtweb/" . $snapToken);
    } catch (\Exception $e) {
        Log::error('Midtrans payment initiation failed: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Unable to process Midtrans payment. Please try again.');
    }
}

    
    

    public function handleMidtransCallback(Request $request)
{
    // Ambil data customer dan cart items dari session
    $cart_items = session('midtrans_cart_items');
    $customer_details = session('midtrans_customer');

    if (!$cart_items || !$customer_details) {
        return redirect()->route('checkout.failed')->with('error', 'Session expired.');
    }

    // Proses pembayaran Midtrans, cek status pembayaran dari Midtrans callback
    $transaction_status = $request->input('transaction_status'); // Ambil status dari Midtrans

    if ($transaction_status == 'capture' || $transaction_status == 'settlement') {
        // Pembayaran sukses, buat order di database
        $order_id = $this->saveOrderWithAddress(
            'midtrans', 
            'paid', 
            $customer_details['first_name'], 
            $customer_details['last_name'], 
            $customer_details['phone'], 
            $customer_details['street_address'], 
            $customer_details['city'], 
            $customer_details['state'], 
            $customer_details['zip_code'], 
            auth()->user()->id
        );

        // Hapus data session setelah order dibuat
        session()->forget(['midtrans_customer', 'midtrans_cart_items']);

        // Redirect ke halaman sukses
        return redirect()->route('success');
    } else {
        // Jika pembayaran gagal
        return redirect()->route('checkout.failed')->with('error', 'Payment failed.');
    }
}



    //Fungsi Untuk Menyimpan Datanya
    public function saveOrder($payment_method, $payment_status = 'pending')
    {
        $cart_items = Cart::where('user_id', auth()->id())->get();

        $order = Order::create([
            'user_id' => auth()->id(),
            'grand_total' => CartManagement::calculateGrandTotal($cart_items),
            'payment_method' => $payment_method,
            'payment_status' => $payment_status,
            'status' => "new",
            'currency' => "idr",
            'shipping_amount' => 0,
            'shipping_method' => ($payment_method == 'cod') ? 'cod' : 'none',
            'notes' => "Order placed by " . auth()->user()->name,
        ]);

        Address::create([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'street_address' => $this->street_address,
            'city' => $this->city,
            'state' => $this->state,
            'zip_code' => $this->zip_code,
            'order_id' => $order->id,
        ]);

        foreach ($cart_items as $item) {
            // Simpan item pesanan
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_amount' => $item->productVariant->price,
                'total_amount' => $item->productVariant->price * $item['quantity'],
            ]);
    
            // Kurangi stok product variant
            $product_variant = $item->productVariant;
            $product_variant->stock -= $item['quantity'];
            $product_variant->save();
    
            // Update stok product berdasarkan total stok dari semua variant
            $product = $product_variant->product;
            $product->stock = $product->product_variants()->sum('stock');
            $product->save();
        }

        Cart::where('user_id', auth()->id())->delete();

        return $order->id;
    }

    //Start Proses Stripe
    private function processStripePayment($cart_items, $grand_total)
    {
        Stripe::setApiKey(env("STRIPE_SECRET"));
        
        try {
            $session = StripeSession::create([
                "payment_method_types" => ["card"],
                "customer_email" => auth()->user()->email,
                "line_items" => $this->prepareStripeLineItems($cart_items),
                "mode" => "payment",
                "success_url" => route('stripe.success') . "?session_id={CHECKOUT_SESSION_ID}",
                "cancel_url" => route('cancel'),
                "metadata" => [
                'first_name' => $this->first_name,
                    'last_name' => $this->last_name,
                    'phone' => $this->phone,
                    'street_address' => $this->street_address,
                    'city' => $this->city,
                    'state' => $this->state,
                    'zip_code' => $this->zip_code,
                    'user_id' => auth()->user()->id,
                ],
            ]);
    
            return redirect($session->url);
        } catch (\Exception $e) {
            Log::error('Stripe session creation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to process Stripe payment. Please try again.');
        }
    }

    private function prepareStripeLineItems($cart_items)
    {
        return $cart_items->map(function ($item) {
            return [
                "price_data" => [
                    'currency' => 'idr',
                    'unit_amount' => $item->productVariant->price * 100, // Mengambil harga dari relasi ProductVariant
                    "product_data" => [
                        "name" => $item->product->name, // Mengambil nama produk dari relasi Product
                    ],
                ],
                "quantity" => $item->quantity, // Mengambil kuantitas dari item di cart
            ];
        })->toArray(); // Mengonversi hasil map ke array
    }
    
   
    public function saveOrderWithAddress(
        $payment_method,
        $payment_status,
        $first_name,
        $last_name,
        $phone,
        $street_address,
        $city,
        $state,
        $zip_code,
        $user_id
    ) {
        // Ambil item dari keranjang
        $cart_items = Cart::where('user_id', auth()->id())->get();

        // Simpan order ke database
        $order = Order::create([
            'user_id' => $user_id,
            'grand_total' => CartManagement::calculateGrandTotal($cart_items),
            'payment_method' => $payment_method,
            'payment_status' => $payment_status,
            'status' => "new",
            'currency' => "idr",
            'shipping_amount' => 0,
            'shipping_method' => ($payment_method == 'cod') ? 'cod' : 'none',
            'notes' => "Order placed by " . auth()->user()->name,
        ]);

        // Simpan alamat pengiriman
        Address::create([
            'first_name' => $first_name,
            'last_name' => $last_name,
            'phone' => $phone,
            'street_address' => $street_address,
            'city' => $city,
            'state' => $state,
            'zip_code' => $zip_code,
            'order_id' => $order->id,
        ]);

        // Simpan item pesanan
        foreach ($cart_items as $item) {
            // Simpan item pesanan
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_amount' => $item->productVariant->price,
                'total_amount' => $item->productVariant->price * $item['quantity'],
            ]);
    
            // Kurangi stok product variant
            $product_variant = $item->productVariant;
            $product_variant->stock -= $item['quantity'];
            $product_variant->save();
    
            // Update stok product berdasarkan total stok dari semua variant
            $product = $product_variant->product;
            $product->stock = $product->product_variants()->sum('stock');
            $product->save();
        }

        // Hapus keranjang setelah order disimpan
        Cart::where('user_id', auth()->id())->delete();

        // Kembalikan ID order
        return $order->id;
    }

    public function handleStripeSuccess(Request $request)
    {
        $session_id = $request->get('session_id');
        
        // Set API Key Stripe
        Stripe::setApiKey(env('STRIPE_SECRET'));
    
        try {
            Log::info("Retrieving Stripe session: " . $session_id);
            $session = StripeSession::retrieve($session_id); // Ambil detail session dari Stripe
            
            Log::info("Session retrieved. Payment status: " . $session->payment_status);
            // Cek apakah pembayaran sukses
            if ($session->payment_status == 'paid') {
                // Ambil metadata dari session untuk data order
                $first_name = $session->metadata['first_name'];
                $last_name = $session->metadata['last_name'];
                $phone = $session->metadata['phone'];
                $street_address = $session->metadata['street_address'];
                $city = $session->metadata['city'];
                $state = $session->metadata['state'];
                $zip_code = $session->metadata['zip_code'];
                $user_id = $session->metadata['user_id'];
    
                // Simpan order ke database jika belum ada
                $order_id = $session->metadata['order_id'] ?? null;
                if (!$order_id) {
                    Log::warning("Order ID not found in session metadata. Creating new order.");
                    $order_id = $this->saveOrderWithAddress(
                        'stripe',
                        'paid',
                        $first_name,
                        $last_name,
                        $phone,
                        $street_address,
                        $city,
                        $state,
                        $zip_code,
                        $user_id
                    );
                }
    
                // Redirect ke halaman sukses dengan order_id
                return redirect()->route('success');
            } else {
                Log::warning("Payment status is not 'paid'. Current status: " . $session->payment_status);
                return redirect()->route('checkout.pending')->with('message', 'Pembayaran sedang diproses. Status: ' . $session->payment_status);
            }
        } catch (\Exception $e) {
            Log::error("Stripe session verification failed: " . $e->getMessage());
            Log::error("Exception trace: " . $e->getTraceAsString());
            return redirect()->route('cancel')->with('error', 'Gagal memverifikasi pembayaran: ' . $e->getMessage());
        }
    }
    
    

     public function render()
    {
        $cart_items = Cart::where('user_id', auth()->id())
                        ->with('productVariant','product')
                        ->get();
        $grand_total = CartManagement::calculateGrandTotal($cart_items);
        return view('livewire.checkout-page', [
            'cart_items' => $cart_items,
            'grand_total' => $grand_total,
        ]);
    }
}
