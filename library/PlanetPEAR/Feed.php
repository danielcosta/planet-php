<?php
/**
 * @category Feed
 * @package  Planet-PHP
 * @author   Till Klampaeckel <till@php.net>
 */
class PlanetPEAR_Feed
{
    /**
     * @var array $entries
     */
    protected $entries = array();

    /**
     * @var array $feed;
     */
    protected $feed;

    /**
     * @var string $type
     */
    protected $type;

    /**
     * @param string $type
     *
     * @return PlanetPEAR_Feed
     *
     * @uses self::setType()
     * @uses self::reset()
     */
    public function __construct($type)
    {
        $this->setType($type);
        $this->reset();
    }

    /**
     * Create XML (RSS or ATOM) from assembled data.
     *
     * @return string
     *
     * @uses self::$feed
     * @uses self::$entries
     * @uses self::$type
     * @uses Zend_Feed_Builder::__construct()
     * @uses Zend_Feed::importBuilder()
     * @uses Zend_Feed::saveXml()
     */
    public function __toString()
    {
        $feed            = $this->feed;
        $feed['entries'] = $this->entries;

        $builder = new Zend_Feed_Builder($feed);
        $feedObj = Zend_Feed::importBuilder($builder, $this->type);

        return $feedObj->saveXml();
    }

    /**
     * Autoloader for Zend Framework
     *
     * @return boolean
     */
    public static function autoload($className)
    {
        $file  = dirname(__FILE__);
        $file .= str_replace('_', '/', $className);
        $file .= '.php';

        return require $file;
    }

    /**
     * Returns an array for Zend_Feed_Builder
     *
     * @return array
     */
    protected function getFeedDefinition()
    {
        $feed = array(

            //required
            'title' => PROJECT_NAME_HR,
            'link'  => PROJECT_URL . '/feed.php?type=' . $this->type,

            // optional
            'lastUpdate' => 'timestamp of the update date',
            'published'  => 'timestamp of the publication date',

            // required
            'charset' => 'utf-8',

            // optional
            'description' => 'Combined feed of ' . PROJECT_NAME_HR,
            'author'      => PROJECT_ADMIN_NAME,
            'email'       => PROJECT_ADMIN_EMAIL,

            // optional, ignored if atom is used
            'webmaster' => PROJECT_ADMIN_EMAIL,

            // optional
            'copyright' => 'All entries are copyright of their authors.',

            'generator' => MAGPIE_USER_AGENT,
            'language'  => 'en',

            // optional, ignored if atom is used
            'ttl' => 3600,

            // entries
            'entries' => array(),
        );

        return $feed;
    }

    /**
     * Add another entry to this feed.
     *
     * @param PlanetPEAR_Feed_Entry $entry
     *
     * return PlanetPEAR_Feed
     */
    public function addEntry(PlanetPEAR_Feed_Entry $entry)
    {
        $arrEntry        = $entry->toArray();
        $this->entries[] = $arrEntry;

        return $this;
    }

    /**
     * @return PlanetPEAR_Feed_Entry
     */
    public function createEntry()
    {
        $entry = new PlanetPEAR_Feed_Entry();
        return;
    }

    /**
     * Recycle the object.
     *
     * @return $this
     */
    public function reset()
    {
        $this->feed    = $this->getFeedDefinition();
        $this->entries = array();

        return $this;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public functions setType($type)
    {
        $type = strtolower(trim($type));

        if (!in_array($type, array('rss', 'atom')) {
            throw new InvalidArgumentException("Type: '{$type}' is not supported.");
        }
        $this->type = $type;

        return $this;
    }
}

spl_autoload_register(arrat('PlanetPEAR_Feed', 'autoload'));
