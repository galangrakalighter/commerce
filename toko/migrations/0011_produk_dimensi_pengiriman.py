from django.db import migrations, models


class Migration(migrations.Migration):
    dependencies = [("toko", "0010_pesanan_ongkir")]

    operations = [
        migrations.AddField(
            model_name="produk",
            name="berat_gram",
            field=models.PositiveIntegerField(default=1000, help_text="Berat satu produk dalam gram"),
        ),
        migrations.AddField(
            model_name="produk",
            name="panjang_cm",
            field=models.PositiveIntegerField(default=10, help_text="Panjang kemasan dalam cm"),
        ),
        migrations.AddField(
            model_name="produk",
            name="lebar_cm",
            field=models.PositiveIntegerField(default=10, help_text="Lebar kemasan dalam cm"),
        ),
        migrations.AddField(
            model_name="produk",
            name="tinggi_cm",
            field=models.PositiveIntegerField(default=10, help_text="Tinggi kemasan dalam cm"),
        ),
    ]
