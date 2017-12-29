<?php

namespace vendor\modules;


class SiteMapGenerator
{
    /**
     * Base url - musy have slash on the end
     *
     * @access private
     */
    private $_base_url = NULL;

    /**
     * There are storage all items
     *
     * @access private
     */
    private $_items = array();

    /**
     * There is generated sitemap
     *
     * @access private
     */
    private $_contain;

    /**
     * Urlset - encapsulates the file and references the current protocol standard.
     *
     * @access private
     */
    private $_urlset = 'http://www.sitemaps.org/schemas/sitemap/0.9';

    /**
     * Encoding
     *
     * @access private
     */
    private $_encoding = 'UTF-8';

    /**
     * XML version
     *
     * @access private
     */
    private $_xml_version = '1.0';

    /**
     * Tab is equal for spaces
     *
     * @access private
     */
    private $_tab = 4;

    /**
     * Last mod static
     *
     * @access private
     */
    private $_last_mod_static = FALSE;

    /**
     * In constructor is configured sitemap
     *
     * @access public
     * @param array with config
     */
    public function __construct($config = array())
    {
        isset($config['urlset']) ? $this -> _urlset = $config['urlset'] : NULL;
        isset($config['encoding']) ? $this -> _encoding = $config['encoding'] : NULL;
        isset($config['xml_version']) ? $this -> _xml_version = $config['xml_version'] : NULL;

        $this -> _contain = NULL; //set empty string
    }

    private function _space($hm = 1)
    {
        $space = NULL;

        for($i = 0; $i < $hm; $i++)
        {
            for($n = 0; $n < $this -> _tab; $n++)
            {
                $space .= ' ';
            }
        }

        return $space;
    }

    /**
     * Set base url
     *
     * @access public
     * @param $url - base url
     */
    public function set_base_url($url)
    {
        $this -> _base_url = $url;
    }

    /**
     * Set last mod static
     *
     * @access public
     * @param date
     */
    public function set_last_mod_static($date)
    {
        $this -> _last_mod_static = $date;
    }

    /**
     * Add items
     *
     * @access public
     * @param  $loc - URL of the page. This URL must begin with the protocol (such as http) and end with a trailing slash, if your web server requires it. This value must be less than 2,048 characters
     * @param $optional - optional tags
     */
    public function addSite($loc, $optional = array())
    {
        $array['loc'] = $this -> _base_url . $loc;
        isset($optional['lastmod']) ? $array['lastmod'] = $optional['lastmod'] : NULL;
        isset($optional['changefreq']) ? $array['changefreq'] = $optional['changefreq'] : NULL;
        isset($optional['priority']) ? $array['priority'] = $optional['priority'] : NULL;

        if($this -> _last_mod_static)
        {
            $array['lastmod'] =  $this -> _last_mod_static;
        }

        //set item
        $this -> _items[] = $array;
    }

    /**
     * Generate sitemap
     */
    public function generate()
    {
        //set begin
        $this -> _contain .= '<?xml version="' . $this -> _xml_version . '" encoding="' . $this -> _encoding . '"?>' . PHP_EOL;
        $this -> _contain .= '<urlset xmlns="' . $this -> _urlset . '">' . PHP_EOL;

        //set items
        foreach($this -> _items as $item)
        {
            $this -> _contain .= $this -> _space() . '<url>' . PHP_EOL;

            foreach($item as $key => $value)
            {
                $this -> _contain .= $this -> _space(2) . '<' . $key . '>' . $value . '</' . $key . '>' . PHP_EOL;
            }

            $this -> _contain .= $this -> _space() . '</url>' . PHP_EOL;
        }

        //set end
        $this -> _contain .= '</urlset>';

        return $this -> _contain;
    }

    /**
     * Download sitemap
     *
     * @access public
     */
    public function download()
    {
        header("Content-type: text/xml");
        header('Content-Disposition: attachment; filename="sitemap-genereted-' . time() . '.xml"');

        echo $this -> generate();
    }
}