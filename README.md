Simple Sitemaps Generator
================

Simple sitemaps generator class, easy way to generate sitemap.xml files for your php projects, with easy inherited api

### Example of generating sitemap.xml
```php
<?php
// connecting to database
  $mysql = new MYSQLI('localhost', 'root', '', 'short-swe3t');
  $query = $mysql->query('SELECT * FROM `sh-users`');
  
// class the class 
$generator = new Sitemap_Generator();

// database loop
while ($row = $query->fetch_array(MYSQLI_ASSOC)) {
    $loc = 'http://localhost/users/' . $row['id'] . '-' . $row['username'] . '.html';
    $lastmod = $row['last_active'];
    $priority = '0.5';
    $changefreq = 'always';
    $generator->addItem(array($loc, $lastmod, $changefreq, $priority));
}

// add items to sitemap and generate it
echo $generator->generate();

// you can save the results as xml files, or just use mode_rewrite apache module to change page name to sitemap.xml
$generator->saveAs($filename);
```

#### Results
```xml
<?xml verison="1.0" encoding="UTF-8"?>
<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
	 xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" 
	 xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
	<url>
		<loc>http://localhost/users/2-MHMD.html</loc>
		<lastmod>Tue, 15 Jul 2014 02:10:12 +0200</lastmod>
		<changefreq>always</changefreq>
		<priority>0.5</priority>
	</url>

	<url>
		<loc>http://localhost/users/3-seraj.html</loc>
		<lastmod>Mon, 11 Aug 2014 14:12:04 +0200</lastmod>
		<changefreq>always</changefreq>
		<priority>0.5</priority>
	</url>

	<url>
		<loc>http://localhost/users/4-ali.html</loc>
		<lastmod>Mon, 11 Aug 2014 14:12:04 +0200</lastmod>
		<changefreq>always</changefreq>
		<priority>0.5</priority>
	</url>

</urlset>
```

### Example of generating sitemap index files
```php
<?php
$generator = new Sitemap_Generator();

// use addSitemap method to add index files
$generator->addSitemap(array(
    'http://google.com/example_sitemap.xml',
    time() // time of last modification
));
echo $generator->generate();
```
#### Results
```xml
<?xml verison="1.0" encoding="UTF-8"?>
<sitemapindex xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
	 xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" 
	 xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
	<url>
		<loc>http://google.com/example_sitemap.xml</loc>
		<lastmod>Mon, 11 Aug 2014 14:15:25 +0200</lastmod>
	</url>

</sitemapindex>
```
More info about sitemaps visit http://www.sitemaps.org/
