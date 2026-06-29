@extends('app')
@section('content')

<div class="container mx-auto px-4 py-8">
    <div class="hidden md:flex justify-between bg-white p-4 rounded-t-lg border border-gray-200 font-semibold text-gray-700">
        <div class="flex items-center gap-4">
            <input type="checkbox" id="selectAll" class="w-5 h-5 accent-[#24420A]">
            <span>Produk</span>
        </div>
        <span>Kuantitas</span>
    </div>

    <div class="space-y-4 mb-6" id="cartList">
        @forelse($keranjangItems as $item)
            <div class="cart-item bg-white p-5 rounded-xl border border-gray-100 shadow-sm transition-all duration-200 hover:border-[#24420A]/20" data-id="{{ $item->product_id }}">
                <div class="flex items-center gap-6">
                    <input type="checkbox" class="item-checkbox w-5 h-5 accent-[#24420A] cursor-pointer" 
                        data-name="{{ $item->product->nama_produk }}" 
                        data-varian="{{ $item->varian }}" 
                        data-qty="1">
                    
                    <img src="{{ !empty($item->product->gambar) ? asset('storage/' . $item->product->gambar[0]) : asset('images/default.jpg') }}" 
                        class="w-20 h-20 object-cover rounded-lg border border-gray-200">
                    
                    <div class="flex-grow min-w-0">
                        <h3 class="font-bold text-gray-800 truncate pr-4">{{ $item->product->nama_produk }}</h3>
                    </div>

                    <div class="flex items-center gap-6">
                        <div class="w-32 flex flex-col justify-end">
                            <select class="variant-select text-sm border border-gray-200 rounded-lg p-2 outline-none cursor-pointer w-full focus:ring-1 focus:ring-[#24420A] focus:border-[#24420A]">
                                @foreach(['1KG', '2KG', '3KG', '5KG', 'BUNDLE'] as $v)
                                    <option value="{{ $v }}" {{ ($item->varian == $v) ? 'selected' : '' }}>{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center gap-6">
                            <div class="flex items-center border border-gray-200 rounded-lg bg-gray-50 overflow-hidden">
                                <button class="btn-minus px-4 py-2 hover:bg-gray-200 transition-colors border-r border-gray-200">-</button>
                                <input type="number" value="1" class="qty-input w-12 bg-transparent text-center text-sm font-semibold outline-none" readonly>
                                <button class="btn-plus px-4 py-2 hover:bg-gray-200 transition-colors border-l border-gray-200">+</button>
                            </div>
                            
                            <button onclick="deleteFromCart('{{ $item->product_id }}', '{{ $item->id }}')" 
                                    class="text-gray-400 hover:text-red-600 transition-colors text-sm font-medium w-16 text-right">
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12 text-gray-400">
                <i class="fas fa-shopping-cart text-4xl mb-4 opacity-50"></i>
                <p>Keranjang Anda masih kosong.</p>
            </div>
        @endforelse
    </div>

    <div class="sticky bottom-0 bg-white p-4 rounded-lg border border-gray-200 shadow-lg flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <label class="flex items-center gap-2 cursor-pointer text-sm font-semibold text-[#24420A]">
                <input type="checkbox" id="selectBottomAll" class="w-5 h-5 accent-[#24420A]"> Pilih Semua
            </label>
            <button onclick="deleteSelected()" class="text-sm font-semibold text-red-600 hover:text-red-800 ml-4">
                Hapus Terpilih
            </button>
        </div>
        <button onclick="checkoutWhatsApp()" class="w-full md:w-auto bg-[#24420A] text-white px-10 py-3 rounded-full font-bold hover:bg-opacity-90">
            <i class="fab fa-whatsapp"></i> Check Out
        </button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Logic Tambah/Kurang Qty
    document.querySelectorAll('.btn-plus').forEach(btn => {
        btn.addEventListener('click', function() {
            let container = this.closest('.cart-item');
            let input = container.querySelector('.qty-input');
            let checkbox = container.querySelector('.item-checkbox');
            input.value = parseInt(input.value) + 1;
            checkbox.dataset.qty = input.value;
        });
    });

    document.querySelectorAll('.btn-minus').forEach(btn => {
        btn.addEventListener('click', function() {
            let container = this.closest('.cart-item');
            let input = container.querySelector('.qty-input');
            let checkbox = container.querySelector('.item-checkbox');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
                checkbox.dataset.qty = input.value;
            }
        });
    });

    // 2. Select All Logic
    const toggleAll = (status) => {
        document.querySelectorAll('.item-checkbox').forEach(cb => cb.checked = status);
        document.getElementById('selectAll').checked = status;
        document.getElementById('selectBottomAll').checked = status;
    };
    document.getElementById('selectAll').addEventListener('change', (e) => toggleAll(e.target.checked));
    document.getElementById('selectBottomAll').addEventListener('change', (e) => toggleAll(e.target.checked));
});

async function deleteSelected() {
    let selected = document.querySelectorAll('.item-checkbox:checked');
    
    if (selected.length === 0) {
        alert('Pilih produk yang ingin dihapus!');
        return;
    }

    if (!confirm('Yakin ingin menghapus produk terpilih?')) return;

    // Ambil semua ID dari item yang dicentang
    let ids = Array.from(selected).map(cb => cb.closest('.cart-item').dataset.id);
    console.log(ids);

    try {
        // Kita kirim request delete ke endpoint yang bisa menangani array ID
        // Pastikan Anda memiliki route/controller untuk menghapus banyak item
        const response = await fetch('/keranjang/delete-batch', {
            method: 'POST', // Menggunakan POST untuk kirim array ID
            headers: { 
                'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                'Content-Type': 'application/json' 
            },
            body: JSON.stringify({ ids: ids })
        });

        if ((await response.json()).success) {
            // Hapus elemen dari tampilan setelah sukses di DB
            selected.forEach(cb => cb.closest('.cart-item').remove());
            if(document.querySelectorAll('.cart-item').length === 0) location.reload();
        }
    } catch (err) {
        alert('Terjadi kesalahan saat menghapus.');
    }
}

document.getElementById('selectBottomAll').addEventListener('change', function() {
    let isChecked = this.checked;
    document.querySelectorAll('.item-checkbox').forEach(cb => cb.checked = isChecked);
})

// 3. Hapus via API
async function deleteFromCart(cartId, id) {
    const btn = document.getElementById(`btn-delete-${id}`);
    const textSpan = document.getElementById(`text-delete-${id}`);
    const cartItem = document.querySelector(`.cart-item[data-id="${id}"]`);

    btn.disabled = true;
    textSpan.innerText = "Menghapus...";

    try {
        const response = await fetch(`/keranjang/${cartId}/delete`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
        });

        if ((await response.json()).success) {
            cartItem.style.opacity = "0";
            setTimeout(() => { 
                cartItem.remove(); 
                if(document.querySelectorAll('.cart-item').length === 0) location.reload(); 
            }, 300);
        } else {
            throw new Error();
        }
    } catch (err) { 
        alert('Gagal menghapus'); 
        btn.disabled = false; 
        textSpan.innerText = "Hapus"; 
    }
}

// 4. Checkout WA dengan style
function checkoutWhatsApp() {
    let selected = document.querySelectorAll('.item-checkbox:checked');
    
    if (selected.length === 0) {
        alert('Pilih produk terlebih dahulu!');
        return;
    }

    let orderList = [];

    selected.forEach(cb => {
        let container = cb.closest('.cart-item');
        let qty = container.querySelector('.qty-input').value;
        let varian = container.querySelector('.variant-select').value;
        let nama = cb.dataset.name;

        orderList.push(`- *${nama}* (${varian}) | Qty: ${qty}`);
    });

    // Pesan yang menegaskan asal pesanan
    let greeting = "Halo admin! Saya ingin memesan produk berikut (Pesanan via Website):\n\n";
    let footer = "\n\nMohon dicek ketersediaannya ya. Terima kasih!";
    
    let pesan = greeting + orderList.join("\n") + footer;

    window.open(`https://wa.me/62895428171038?text=${encodeURIComponent(pesan)}`, '_blank');
}
</script>

@endsection