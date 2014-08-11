<?php

class sitemap_Generator {

    private $_priority = '0.5';
    private $_freqArray = array('always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never');
    private $_xmlns = 'http://www.sitemaps.org/schemas/sitemap/0.9';
    private $_encoding = 'UTF-8';
    private $_xmlVersion = '1.0';
    private $_xmlns_xsi = 'http://www.w3.org/2001/XMLSchema-instance';
    private $_xsi_schemaLocation = 'http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd';
    private $_output;
    private $_allItems = array();
    private $_allMaps;

    /**
     * Add element to the final output
     * @param mixed $loc
     * @param int $lastmod
     * @param string $changefreq
     * @param int $priority
     * @return \sitemap_Generator
     */
    public function addItem($loc = array(), $lastmod = NULL, $changefreq = NULL, $priority = NULL) {
        if (is_array($loc)) {
            call_user_func_array(array($this, 'addItem'), $loc);
            return;
        }

        $this->_allItems[] = array(
            'loc' => rawurldecode(htmlentities($loc)),
            'lastmod' => date('r', (isset($lastmod) && strlen($lastmod) > 0) ? $lastmod : time() ),
            'changefreq' => (isset($changefreq) && in_array($changefreq, $this->_freqArray)) ? $changefreq : $this->_freqArray[0],
            'priority' => (isset($priority) < 1) ? $priority : $this->_priority
        );

        return $this;
    }

    /**
     * Add sitemap index to final output
     * @param array $loc
     * @param int $lastmod
     * @return \sitemap_Generator
     */
    public function addSitemap($loc = array(), $lastmod = NULL) {
        if (is_array($loc)) {
            call_user_func_array(array($this, 'addSitemap'), $loc);
            return;
        }

        $this->_allMaps[] = array(
            'loc' => $loc,
            'lastmod' => date('r', (isset($lastmod) && strlen($lastmod) > 0) ? $lastmod : time())
        );
        return $this;
    }

    /**
     * Generate the sitemap.xml
     * @return string final output
     * @throws Exception
     */
    public function generate() {
        $output = '';
        $output .= '<?xml verison="' . $this->_xmlVersion . '" encoding="' . $this->_encoding . '"?>' . "\n";

        if (count($this->_allItems) > 0 && count($this->_allMaps) == 0) {
            $output .= '<urlset xmlns:xsi="' . $this->_xmlns_xsi . '" ' . "\n\t" .
                    ' xsi:schemaLocation="' . $this->_xsi_schemaLocation . '" ' . "\n\t" .
                    ' xmlns="' . $this->_xmlns . '">' . "\n";
            foreach ($this->_allItems as $item) {
                $output .= "\t<url>\n";
                $output .= "\t\t<loc>$item[loc]</loc>\n";
                $output .= "\t\t<lastmod>$item[lastmod]</lastmod>\n";
                $output .= "\t\t<changefreq>$item[changefreq]</changefreq>\n";
                $output .= "\t\t<priority>$item[priority]</priority>\n";
                $output .= "\t</url>\n\n";
            }

            $output .="</urlset>";
        } else if (count($this->_allItems) == 0 && count($this->_allMaps) > 0) {
            $output .= '<sitemapindex xmlns:xsi="' . $this->_xmlns_xsi . '" ' . "\n\t" .
                    ' xsi:schemaLocation="' . $this->_xsi_schemaLocation . '" ' . "\n\t" .
                    ' xmlns="' . $this->_xmlns . '">' . "\n";

            foreach ($this->_allMaps as $item) {
                $output .= "\t<url>\n";
                $output .= "\t\t<loc>$item[loc]</loc>\n";
                $output .= "\t\t<lastmod>$item[lastmod]</lastmod>\n";
                $output .= "\t</url>\n\n";
            }
            $output .="</sitemapindex>";
        } else {
            throw new Exception('you must generate either a site map, or sitemap index.');
        }

        $this->_output = $output;
        return $output;
    }

    /**
     * Save output as file
     * @param string $filename
     */
    public function saveAs($filename) {
        file_put_contents($filename, $this->_output);
    }

}

/**
 * End of file sitemap_generator.php
 */
