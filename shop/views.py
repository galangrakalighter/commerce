from django.shortcuts import render, redirect, get_object_or_404
from django.contrib.auth.decorators import login_required
from django.contrib.admin.views.decorators import staff_member_required
from django.contrib.auth.forms import UserCreationForm
from django.contrib.auth import login
from django.contrib import messages
from django.http import JsonResponse
from .models import Product, OrderItem, Order, Wishlist, ProductLike, Comment, Category
from .forms import OrderCreateForm, ProductForm
from .cart import Cart

def product_list(request, category_slug=None):
    # 1. Ambil semua kategori untuk navigasi Tab
    categories = Category.objects.all()
    
    # 2. Ambil semua produk yang aktif
    products = Product.objects.all() # Tambahkan .filter(is_active=True) jika ada fieldnya
    
    # 3. Filter berdasarkan kategori jika ada slug di URL
    category = None
    if category_slug:
        category = get_object_or_404(Category, slug=category_slug)
        products = products.filter(category=category)
    
    # 4. Ambil data Like & Wishlist user (tetap sama seperti logika Bos)
    user_like_ids = []
    user_wishlist_ids = []
    
    if request.user.is_authenticated:
        user_like_ids = ProductLike.objects.filter(user=request.user).values_list('product_id', flat=True)
        user_wishlist_ids = Wishlist.objects.filter(user=request.user).values_list('product_id', flat=True)
    
    # 5. Kirim semua ke template
    return render(request, 'shop/list.html', {
        'products': products,
        'categories': categories, # List semua kategori
        'current_category': category, # Kategori yang sedang dibuka (buat nandain tab aktif)
        'user_like_ids': list(user_like_ids),
        'user_wishlist_ids': list(user_wishlist_ids)
    })

def cart_add(request, product_id):
    cart = Cart(request)
    product = get_object_or_404(Product, id=product_id)
    
    quantity = int(request.GET.get('quantity', 1))
    
    cart.add(product=product, quantity=quantity)
    
    next_url = request.GET.get('next')
    if next_url:
        return redirect(next_url)
        
    return JsonResponse({'status': 'ok', 'cart_count': len(cart)})

def cart_detail(request):
    cart = Cart(request)
    return render(request, 'shop/cart_detail.html', {'cart': cart})

def save(self):
    # Tandai session sebagai "modified" agar disimpan ke database/file
    self.session.modified = True

def clear(self):
    # Hapus cart dari session
    del self.session[settings.CART_SESSION_ID]
    self.save()


def cart_remove(request, product_id):
    cart = Cart(request)
    product = get_object_or_404(Product, id=product_id)
    cart.remove(product)
    return redirect('shop:cart_detail')

def order_create(request):
    cart = Cart(request)
    if request.method == 'POST':
        form = OrderCreateForm(request.POST)
        if form.is_valid():
            order = form.save(commit=False)
            if request.user.is_authenticated:
                order.user = request.user
            order.save()
            for item in cart:
                OrderItem.objects.create(
                    order=order,
                    product=item['product'],
                    price=item['price'],
                    quantity=item['quantity']
                )
            # Bersihkan keranjang setelah checkout
            cart.clear()
            return render(request, 'shop/order_created.html', {'order': order})
    else:
        form = OrderCreateForm()
    return render(request, 'shop/checkout.html', {'cart': cart, 'form': form})

def product_detail(request, id, slug):
    product = get_object_or_404(Product, id=id, slug=slug)
    
    # 1. Logic untuk menangani POST Comment via AJAX
    if request.method == 'POST' and request.headers.get('x-requested-with') == 'XMLHttpRequest':
        if not request.user.is_authenticated:
            return JsonResponse({'status': 'error', 'message': 'Login required'}, status=403)
            
        content = request.POST.get('content')
        if content:
            # Simpan komentar ke database
            comment = Comment.objects.create(
                product=product,
                name=request.user.username,
                email=request.user.email,
                content=content
            )
            
            # Balas dengan JSON agar JavaScript bisa langsung update UI
            return JsonResponse({
                'status': 'success',
                'name': comment.name,
                'content': comment.content,
                'created_at': comment.created_at.strftime("%b %d, %Y")
            })
        
        return JsonResponse({'status': 'error', 'message': 'Empty content'}, status=400)

    # 2. Logic untuk tampilan halaman biasa (GET)
    recommendations = Product.objects.filter(category=product.category).exclude(id=product.id)[:6]
    
    # Ambil daftar komentar untuk produk ini
    comments = product.comments.all().order_by('-created_at') # Asumsi ada related_name='comments'
    
    return render(request, 'shop/detail.html', {
        'product': product,
        'recommendations': recommendations,
        'comments': comments
    })

@login_required
def order_history(request):
    orders = Order.objects.filter(user=request.user).prefetch_related('items__product')
    return render(request, 'shop/order_history.html', {'orders': orders})


@staff_member_required
def staff_dashboard(request):
    products = Product.objects.all().order_by('-created')
    return render(request, 'shop/staff/dashboard.html', {'products': products})

@staff_member_required
def product_manage(request, id=None):
    if id:
        product = get_object_or_404(Product, id=id)
    else:
        product = None

    if request.method == 'POST':
        form = ProductForm(request.POST, request.FILES, instance=product)
        if form.is_valid():
            form.save()
            return redirect('shop:staff_dashboard')
    else:
        form = ProductForm(instance=product)
    
    return render(request, 'shop/staff/product_form.html', {'form': form, 'product': product})

@staff_member_required
def product_delete(request, id):
    product = get_object_or_404(Product, id=id)
    if request.method == 'POST':
        product.delete()
        return redirect('shop:staff_dashboard')
    return redirect('shop:staff_dashboard') # Jika diakses via GET, lempar balik

@login_required
def toggle_wishlist(request, product_id):
    product = get_object_or_404(Product, id=product_id)
    if product.wishlist.filter(id=request.user.id).exists():
        product.wishlist.remove(request.user)
        status = 'removed'
    else:
        product.wishlist.add(request.user)
        status = 'added'
    
    return JsonResponse({'status': status})

@login_required
def wishlist_detail(request):
    wishlist_items = request.user.wishlist.all() 
    
    return render(request, 'shop/wishlist.html', {
        'wishlist_items': wishlist_items
    })

@login_required
def toggle_like(request, product_id):
    product = get_object_or_404(Product, id=product_id)
    like_item, created = ProductLike.objects.get_or_create(user=request.user, product=product)
    if not created:
        like_item.delete()
        product.total_likes -= 1
        status = 'unliked'
    else:
        product.total_likes += 1
        status = 'liked'
    product.save()
    return JsonResponse({'status': status, 'total_likes': product.total_likes})

def register(request):
    if request.method == 'POST':
        form = UserCreationForm(request.POST)
        if form.is_valid():
            # commit=False untuk kustomisasi tambahan
            user = form.save(commit=False)
            
            # Memastikan user baru adalah customer biasa
            user.is_staff = False
            user.is_superuser = False
            
            user.save()
            
            # Langsung login setelah daftar
            login(request, user) 
            
            messages.success(request, f"Identity Created! Welcome to LMG Ecosystem, {user.username}.")
            return redirect('shop:product_list')
        else:
            # Mengambil pesan error agar lebih spesifik (misal: password terlalu pendek)
            for field, errors in form.errors.items():
                for error in errors:
                    messages.error(request, f"{field.capitalize()}: {error}")
            
            # Kembali ke halaman sebelumnya agar modal bisa muncul lagi (jika diatur)
            return redirect(request.META.get('HTTP_REFERER', 'shop:product_list'))
            
    return redirect('shop:product_list')

@login_required
def liked_products(request):
    # Asumsi: Bos punya related_name='likes' di model Like atau User
    # Jika menggunakan model Like:
    liked_items = request.user.likes.all() 
    return render(request, 'shop/liked_list.html', {'liked_items': liked_items})