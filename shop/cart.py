from decimal import Decimal
from django.conf import settings
from .models import Product

class Cart:
    def __init__(self, request):
        self.session = request.session
        cart = self.session.get(settings.CART_SESSION_ID)
        if not cart:
            cart = self.session[settings.CART_SESSION_ID] = {}
        self.cart = cart

    def add(self, product, quantity=1, override_quantity=False):
        product_id = str(product.id)
        if product_id not in self.cart:
            self.cart[product_id] = {'quantity': 0, 'price': str(product.price)}
        
        if override_quantity:
            self.cart[product_id]['quantity'] = quantity
        else:
            self.cart[product_id]['quantity'] += quantity
        self.save()

    def save(self):
        # Tandai session sebagai "modified" agar disimpan ke database/file
        self.session.modified = True

    def clear(self):
        # Hapus cart dari session
        del self.session[settings.CART_SESSION_ID]
        self.save()

    def remove(self, product):
        product_id = str(product.id)
        if product_id in self.cart:
            del self.cart[product_id]
            self.save()

    # --- TAMBAHAN PENTING ---
    def __len__(self):
        """
        Menghitung total item di keranjang (untuk angka di icon keranjang).
        """
        return sum(item['quantity'] for item in self.cart.values())

    def __iter__(self):
        # Ambil SEMUA keys ke dalam list (agar ukurannya tetap/statis saat di-loop)
        product_ids = list(self.cart.keys())
        
        products = Product.objects.filter(id__in=product_ids)
        
        cart = self.cart.copy()
        valid_product_ids = []

        for product in products:
            cart[str(product.id)]['product'] = product
            valid_product_ids.append(str(product.id))

        # Iterasi menggunakan list product_ids yang sudah kita buat di awal tadi
        for product_id in product_ids:
            if product_id not in valid_product_ids:
                # Menghapus dari self.cart asli sekarang AMAN 
                # karena kita tidak sedang me-looping self.cart secara langsung
                if product_id in self.cart:
                    del self.cart[product_id]
                    self.save()
                continue 

            item = cart[product_id]
            item['price'] = Decimal(item['price'])
            item['total_price'] = item['price'] * item['quantity']
            yield item
    # ------------------------

    def get_total_price(self):
        return sum(Decimal(item['price']) * item['quantity'] for item in self.cart.values())