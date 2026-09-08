from django.db import migrations, models


class Migration(migrations.Migration):
    dependencies = [("toko", "0011_produk_dimensi_pengiriman")]

    operations = [
        migrations.AddField(
            model_name="pesanan",
            name="biteship_order_id",
            field=models.CharField(blank=True, max_length=100, null=True),
        ),
        migrations.AddField(
            model_name="pesanan",
            name="biteship_tracking_id",
            field=models.CharField(blank=True, max_length=100, null=True),
        ),
    ]
