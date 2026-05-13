from .forms import OrderCreateForm
from .models import OrderItem

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