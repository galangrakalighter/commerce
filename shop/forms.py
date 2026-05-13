from django import forms
from .models import Order, Product
from django.forms.widgets import ClearableFileInput

class OrderCreateForm(forms.ModelForm):
    class Meta:
        model = Order
        fields = ['first_name', 'last_name', 'email', 'address', 'city']

class CustomImageWidget(ClearableFileInput):
    template_name = 'shop/widgets/custom_image_input.html'

class ProductForm(forms.ModelForm):
    class Meta:
        model = Product
        fields = ['category', 'name', 'description', 'price', 'stock', 'image', 'slug']
        widgets = {
            'description': forms.Textarea(attrs={'rows': 3, 'placeholder': 'Describe the essence...'}),
            'image': CustomImageWidget(),
            'name': forms.TextInput(attrs={'placeholder': 'PRODUCT NAME'}),
            'price': forms.NumberInput(attrs={'placeholder': '0.00'}),
            'stock': forms.NumberInput(attrs={'placeholder': '100'}),
            'slug': forms.TextInput(attrs={'placeholder': 'product-url-slug'}),
        }