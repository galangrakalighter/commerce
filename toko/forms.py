from django import forms
from .models import BannerPromo

class BannerPromoForm(forms.ModelForm):
    class Meta:
        model = BannerPromo
        fields = ['judul', 'gambar', 'url_tujuan', 'is_aktif']
        widgets = {
            'judul': forms.TextInput(attrs={
                'class': 'w-full px-4 py-2.5 rounded-xl border border-[#EAE3D2] focus:outline-none focus:border-[#8C7454] text-sm',
                'placeholder': 'Contoh: Concert Season Deal'
            }),
            'gambar': forms.FileInput(attrs={
                'class': 'w-full text-sm text-stone-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#F4F1EA] file:text-[#2C2A29] hover:file:bg-[#EAE3D2] file:cursor-pointer'
            }),
            'url_tujuan': forms.TextInput(attrs={
                'class': 'w-full px-4 py-2.5 rounded-xl border border-[#EAE3D2] focus:outline-none focus:border-[#8C7454] text-sm',
                'placeholder': 'Contoh: /kategori/serum/ (Bisa dikosongkan)'
            }),
            'is_aktif': forms.CheckboxInput(attrs={
                'class': 'w-4 h-4 text-[#8C7454] border-[#EAE3D2] rounded focus:ring-[#8C7454]'
            }),
        }