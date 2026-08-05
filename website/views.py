import requests

from django.shortcuts import render

# Create your views here.
def halaman_web(request):
    return render(request, 'home_dashboard.html')

def halaman_produk(request):
    return render(request, 'home_produk.html')

def halaman_artikel(request):
    page = request.GET.get('page', 1)

    url = (
        "https://laflyderm.com/cms_artikel123123/index.php"
        f"?rest_route=/wp/v2/posts&_embed&per_page=6&page={page}"
    )

    response = requests.get(url)

    posts = response.json()

    # Jumlah halaman dari WordPress
    total_pages = int(response.headers.get('X-WP-TotalPages', 1))

    # Tambahkan image_url agar mudah dipakai di template
    for post in posts:
        post["image_url"] = None

        if (
            "_embedded" in post
            and "wp:featuredmedia" in post["_embedded"]
            and len(post["_embedded"]["wp:featuredmedia"]) > 0
        ):
            post["image_url"] = (
                post["_embedded"]["wp:featuredmedia"][0]["source_url"]
            )

    return render(
        request,
        "article.html",
        {
            "posts": posts,
            "total_pages": range(1, total_pages + 1),
            "current_page": int(page),
        },
    )

def detail_artikel(request, slug):
    # Artikel utama
    url = (
        "https://laflyderm.com/cms_artikel123123/index.php"
        f"?rest_route=/wp/v2/posts&slug={slug}&_embed"
    )

    response = requests.get(url)
    posts = response.json()

    if not posts:
        return render(request, "404.html")

    post = posts[0]

    image_url = None

    if (
        "_embedded" in post
        and "wp:featuredmedia" in post["_embedded"]
        and len(post["_embedded"]["wp:featuredmedia"]) > 0
    ):
        image_url = post["_embedded"]["wp:featuredmedia"][0]["source_url"]

    post["image_url"] = image_url

    # Artikel lainnya
    related_url = (
        "https://laflyderm.com/cms_artikel123123/index.php"
        "?rest_route=/wp/v2/posts&_embed&per_page=3"
    )

    related_response = requests.get(related_url)
    related_posts = related_response.json()

    artikel_lainnya = []

    for item in related_posts:
        # jangan tampilkan artikel yang sedang dibuka
        if item["slug"] == slug:
            continue

        image = None

        if (
            "_embedded" in item
            and "wp:featuredmedia" in item["_embedded"]
            and len(item["_embedded"]["wp:featuredmedia"]) > 0
        ):
            image = item["_embedded"]["wp:featuredmedia"][0]["source_url"]

        item["image_url"] = image

        artikel_lainnya.append(item)

    return render(
        request,
        "detail_article.html",
        {
            "post": post,
            "artikel_lainnya": artikel_lainnya
        }
    )   