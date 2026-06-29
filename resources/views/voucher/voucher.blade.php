@extends('app')

@section('content')
<div class="bg-gray-50 min-h-screen pb-12 antialiased">

    <div class="bg-[#24420A] py-4 px-4 md:px-6 shadow-sm">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex items-center space-x-3 w-full sm:w-auto text-white">
                <span class="text-2xl">🎟️</span>
                <div>
                    <h1 class="text-md md:text-lg font-bold uppercase tracking-wide">Kelola Voucher</h1>
                    <p class="text-xs text-white/80 font-medium">Atur kode promo dan potongan harga untuk pelanggan</p>
                </div>
            </div>
            
            <button onclick="openVoucherModal()" class="w-full sm:w-auto bg-[#F2B705] text-white px-5 py-2 rounded font-bold text-xs md:text-sm hover:bg-opacity-90 transition shadow-md">
                Tambah Voucher Baru
            </button>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 mt-6">
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 font-bold uppercase tracking-wider border-b border-gray-200">
                        <th class="py-3.5 px-4">Kode Voucher</th>
                        <th class="py-3.5 px-4">Potongan (Rp)</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="voucher-table-body" class="divide-y divide-gray-100 font-medium text-gray-700">
                    @include('voucher.table_rows', ['vouchers' => $vouchers])
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="voucher-modal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="fixed inset-0 bg-black bg-opacity-50" onclick="closeVoucherModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative bg-white rounded-lg shadow-xl w-full sm:max-w-md text-xs">
            <div class="bg-[#24420A] px-4 py-3.5 text-white flex justify-between">
                <h3 class="font-bold uppercase tracking-wider" id="modal-title">Formulir Voucher</h3>
                <button onclick="closeVoucherModal()" class="text-lg font-bold">&times;</button>
            </div>

            <form id="voucher-form" onsubmit="handleVoucherSubmit(event)" class="p-5 space-y-4">
                @csrf
                <input type="hidden" id="form-method" name="_method" value="POST">
                
                <div>
                    <label class="block font-bold mb-1">Kode Voucher *</label>
                    <input type="text" id="input-kode" name="kode" required placeholder="Contoh: PROMO2026" class="w-full px-3 py-2 border rounded uppercase">
                </div>

                <div>
                    <label class="block font-bold mb-1">Nilai Potongan (Rp) *</label>
                    <input type="number" id="input-potongan" name="potongan" required placeholder="Contoh: 10000" class="w-full px-3 py-2 border rounded">
                </div>

                <div class="pt-3 border-t flex justify-end space-x-2">
                    <button type="button" onclick="closeVoucherModal()" class="bg-gray-100 px-4 py-2 rounded font-bold">Batal</button>
                    <button type="submit" class="bg-[#24420A] text-white px-5 py-2 rounded font-bold">Simpan Voucher</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let currentEditId = null;

    function openVoucherModal(data = null) {
        document.getElementById('voucher-modal').classList.remove('hidden');
        const form = document.getElementById('voucher-form');
        
        if (data) {
            currentEditId = data.id;
            document.getElementById('form-method').value = 'PUT';
            document.getElementById('modal-title').innerText = "Ubah Data Voucher";
            document.getElementById('input-kode').value = data.kode;
            document.getElementById('input-potongan').value = data.potongan;
        } else {
            currentEditId = null;
            document.getElementById('form-method').value = 'POST';
            document.getElementById('modal-title').innerText = "Tambah Voucher Baru";
            form.reset();
        }
    }

    function closeVoucherModal() {
        document.getElementById('voucher-modal').classList.add('hidden');
    }

    function handleVoucherSubmit(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        const url = currentEditId ? `/voucher/${currentEditId}` : '/voucher';

        fetch(url, {
            method: 'POST',
            body: formData,
            headers: { 
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json' 
            }
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message || "Data berhasil disimpan");
            closeVoucherModal();
            location.reload();
        })
        .catch(err => alert("Terjadi kesalahan saat menyimpan"));
    }

    function deleteVoucher(id) {
        if(!confirm('Hapus voucher ini?')) return;
        
        fetch(`/voucher/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        }).then(() => location.reload());
    }
    function prepareEdit(id) {
        const jsonElement = document.getElementById(`voucher-json-${id}`);
        openVoucherModal(JSON.parse(jsonElement.textContent));
    }
</script>
@endsection