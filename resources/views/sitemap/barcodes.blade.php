<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($barcodes as $value)
        <url>
            <loc>{{ route('seo-barcode', ['slug' => str_slug($value->name, "-") . '-' . $value->barcode]) }}</loc>
            <lastmod>{{ $value->updated_at->toAtomString() }}</lastmod>
        </url>
    @endforeach
</urlset> 