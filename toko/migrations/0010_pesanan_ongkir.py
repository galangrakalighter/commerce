from django.db import migrations, models


def isi_subtotal_lama(apps, schema_editor):
    Pesanan = apps.get_model("toko", "Pesanan")
    for pesanan in Pesanan.objects.filter(subtotal_harga=0).iterator():
        pesanan.subtotal_harga = pesanan.total_harga
        pesanan.save(update_fields=["subtotal_harga"])


class Migration(migrations.Migration):
    dependencies = [("toko", "0009_pesanan_stok_dikurangi")]

    operations = [
        migrations.AddField(
            model_name="pesanan",
            name="subtotal_harga",
            field=models.IntegerField(default=0),
        ),
        migrations.AddField(
            model_name="pesanan",
            name="biaya_ongkir",
            field=models.IntegerField(default=0),
        ),
        migrations.AddField(
            model_name="pesanan",
            name="kurir_layanan",
            field=models.CharField(blank=True, max_length=50, null=True),
        ),
        migrations.AddField(
            model_name="pesanan",
            name="destination_area_id",
            field=models.CharField(blank=True, max_length=100, null=True),
        ),
        migrations.RunPython(isi_subtotal_lama, migrations.RunPython.noop),
    ]
