from django.urls import path, include
from . import views

app_name = 'shop'

urlpatterns = [
    path('', views.product_list, name='product_list'),
    path('accounts/', include('django.contrib.auth.urls')),
    path('cart/', views.cart_detail, name='cart_detail'),
    path('add/<int:product_id>/', views.cart_add, name='cart_add'),
    path('remove/<int:product_id>/', views.cart_remove, name='cart_remove'),
    path('checkout/', views.order_create, name='order_create'),
    path('history/', views.order_history, name='order_history'),
    path('<int:id>/<slug:slug>/', views.product_detail, name='product_detail'),
    path('manage/', views.staff_dashboard, name='staff_dashboard'),
    path('manage/add/', views.product_manage, name='product_create'),
    path('manage/edit/<int:id>/', views.product_manage, name='product_edit'),
    path('manage/delete/<int:id>/', views.product_delete, name='product_delete'),
    path('like/toggle/<int:product_id>/', views.toggle_like, name='toggle_like'),
    path('wishlist/', views.wishlist_detail, name='wishlist_detail'),
    path('wishlist/toggle/<int:product_id>/', views.toggle_wishlist, name='toggle_wishlist'),
    path('register/', views.register, name='register'),
    path('category/<slug:category_slug>/', views.product_list, name='product_list_by_category'),
    path('liked/', views.liked_products, name='liked_products'),
]