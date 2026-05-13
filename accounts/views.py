from django.shortcuts import render, redirect
from django.contrib.auth.forms import UserCreationForm
from django.contrib.auth import login
from django.contrib import messages

def register(request):
    if request.method == 'POST':
        form = UserCreationForm(request.POST)
        if form.is_valid():
            # commit=False artinya kita tahan dulu datanya, jangan langsung masuk database
            user = form.save(commit=False)
            
            # PAKSA status menjadi bukan staf dan bukan superuser
            user.is_staff = False
            user.is_superuser = False
            
            # Baru kemudian simpan permanen ke database
            user.save()
            
            login(request, user) 
            messages.success(request, "Akses Akun Berhasil Diaktivasi! Selamat datang di LMG Ecosystem.")
            return redirect('shop:product_list')
        else:
            for error in form.errors.values():
                messages.error(request, error)
            return redirect('shop:product_list')
            
    return redirect('shop:product_list')