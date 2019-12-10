<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<?php
	$dt = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d 00:00:00'));
?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <sitemap>
        <loc>{{ url('/').'/barcode-sitemap.xml' }}</loc>
        <lastmod>{{ $dt->toAtomString() }}</lastmod>
    </sitemap>
    <sitemap>
        <loc>{{ url('/').'/page-sitemap.xml' }}</loc>
        <lastmod>{{ $dt->toAtomString() }}</lastmod>
    </sitemap>
</sitemapindex>