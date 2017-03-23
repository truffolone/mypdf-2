<?php 

namespace lib;

class Genpdf {

    private $_debug = true; //application debug
    private $_debugLog = array(); //logger
    private $_debugLevels = array(
        0 =>  '#000000',
        1 =>  '#FFD700',
        2 =>  '#FF6347',
        99 => '#008000'
    ); //Debug levels with associated colors

    protected $mpdf; //mPDF library

    protected $title; //document title (meta)
    protected $author; //document author (meta)
    protected $creator; //document creator (meta)
    protected $subject; //document subject (meta)
    protected $keywords; //document keywords (meta)
    protected $password = null; //document password (if null no password will be set)

    protected $css; //css for the html file

    private $_overwrite = 0; //how to handle setters for existing properties
    private $_overwriteOverride = false; //keep overwrite past first setter (true) or reset it back to 0 (false)

    public function __construct(\mPDF $mpdf) {
        $this->mpdf = $mpdf;
    }

    /*
     * MAGIC setter;
     * @params (string) $property, (multi) $value;
     * @returns $this;
     * if $this->$property is missing will set it as $value
     * else will check $this->_overwrite: 0 => ignore; 1 => append; 2 => replace
     * if $this->_overwriteOverride is true, $this->overwrite is kept as is, otherwise is set back to 0;
     */
    public function __set($property, $value) {
        $this->_debug("Setting " . $property . " as " . $value, 99);
        //check if the property has already been set
        if(property_exists($this, $property)) {
            $this->_debug("\$this->\$property already exists as " . $this->$property, 0);
            if($this->_overwrite === 2) {
                $this->$property = $value;
                $this->_debug("\$this->\$property overwritten as " . $value, 0);
            } elseif($this->_overwrite === 1) {
                $this->_debug("\$this->\$property got " . $value . " appended", 0);
                $this->$property .= $value;
            }
        } else {
            $this->_debug("\$this->\$property got " . $value . " as value", 0);
            //we are gonna create a new variable
            $this->$property = $value;
        }

        if($this->_overwriteOverride === false) {
            $this->_debug("overwrite has been set to 0", 0);
            $this->_overwrite = 0;
        }

        return $this;
    }

    /*
     * @params (int) $val;
     * @returns $this;
     * Define the overwrite mode
     */
    public function overwrite(int $val) {
        if($val >= 2) {
            $this->_overwrite = 2;
        } else {
            $this->_overwrite = $val;
        }

        return $this;
    }

    /*
     * @params (bool) $val;
     * @returns $this;
     * Define if we are in override mode
     */
    public function override(bool $val) {
        $this->_overwriteOverride = !!$val;

        return $this;
    }

    /*
     * @params void;
     * @returns (array) $this->_debugLog;
     * Define if we are in override mode
     */
    public function getDebug() {
        return $this->_debugLog;
    }

    /*
     * @params (string) $str, (int) $lvl;
     * @returns void;
     * Send String to debug with warning level
     */
    private function _debug(string $str, int $lvl = 0) {
        if($this->_debug === true) $this->_debugLog[] = array('color' => $this->_debugLevels[(array_key_exists($lvl, $this->_debugLevels) ? $lvl : 0)], 'text' => $str);
    }
}