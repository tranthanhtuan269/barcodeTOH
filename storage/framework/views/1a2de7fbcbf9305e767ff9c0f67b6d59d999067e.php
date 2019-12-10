<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <?php foreach($barcodes as $value): ?>
        <url>
            <loc><?php echo e(route('seo-barcode', ['slug' => str_slug($value->name, "-") . '-' . $value->barcode])); ?></loc>
            <lastmod><?php echo e($value->updated_at->toAtomString()); ?></lastmod>
        </url>
    <?php endforeach; ?>
</urlset> 