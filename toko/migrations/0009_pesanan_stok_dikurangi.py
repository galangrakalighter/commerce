from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('toko', '0008_pesanan_doku_invoice_number_pesanan_doku_payment_url'),
    ]

    operations = [
        migrations.AddField(
            model_name='pesanan',
            name='stok_dikurangi',
            field=models.BooleanField(default=False),
        ),
    ]
