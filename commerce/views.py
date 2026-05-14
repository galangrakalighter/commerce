from django.shortcuts import render

def halaman_utama(request):
    return render(request, 'index.html')