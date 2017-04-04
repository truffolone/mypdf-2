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

    protected $title = false; //document title (meta)
    protected $author = false; //document author (meta)
    protected $creator = false; //document creator (meta)
    protected $subject = false; //document subject (meta)
    protected $keywords = false; //document keywords (meta)
    protected $password = null; //document password (if null no password will be set)
    protected $css = false;

    private $_values = array(); //values for the file
    protected $html = null; //html raw data
    protected $template; //generated template (html format)

    private $_overwrite = 0; //how to handle setters for existing properties
    private $_overwriteOverride = false; //keep overwrite past first setter (true) or reset it back to 0 (false)

    public function __construct(\mPDF $mpdf) {
        $this->mpdf = $mpdf;
    }

    /*
     * binder;
     * @params (string) $property, (multi) $value;
     * @returns $this;
     * if $this->_values[$property] is missing will set it as $value
     * else will check $this->_overwrite: 0 => ignore; 1 => append; 2 => replace
     * if $this->_overwriteOverride is true, $this->overwrite is kept as is, otherwise is set back to 0;
     */
    public function bind($property, $value) {
        $this->_debug("Setting " . $property . " as " . $value, 99);
        //check if the property has already been set
        if(array_key_exists($property, $this->_values)) {
            $this->_debug("\$this->_values[$property] already exists as " . $this->_values[$property], 0);
            if($this->_overwrite === 2) {
                $this->_values[$property] = $value;
                $this->_debug("\$this->_values[$property] overwritten as " . $value, 0);
            } elseif($this->_overwrite === 1) {
                $this->_debug("\$this->_values[$property] got " . $value . " appended", 0);
                $this->_values[$property] .= $value;
            }
        } else {
            $this->_debug("\$this->_values[$property] got " . $value . " as value", 0);
            //we are gonna create a new variable
            $this->_values[$property] = $value;
        }

        if($this->_overwriteOverride === false) {
            $this->_debug("overwrite has been set to 0", 0);
            $this->_overwrite = 0;
        }

        return $this;
    }

    /*
     * @params (string) $filename, (bool) $show;
     * @returns $this;
     * generte the pdf file, if $show then loads the generated pdf into the page;
     */
    public function generate(string $filename, bool $show) {
        if($this->title !== null)    $this->mpdf->SetTitle($this->title);
        if($this->author !== null)   $this->mpdf->SetAuthor($this->author);
        if($this->creator !== null)  $this->mpdf->SetCreator($this->creator);
        if($this->subject !== null)  $this->mpdf->SetSubject($this->subject);
        if($this->keywords !== null) $this->mpdf->SetKeywords($this->keywords);
        if($this->password !== null) $this->mpdf->SetProtection(array(), $this->password);
        if($this->css !== null)      $this->mpdf->WriteHTML($this->css, 1);

        $fullpath = "./pdf/" . $filename;
        $this->_debug("writing to " . $fullpath);
        if(file_exists($fullpath)) {
            $this->_debug($fullpath . " already exists, overwriting...", 1);
        }

        $this->mpdf->WriteHTML($this->template, 2);
        $this->mpdf->Output($fullpath, 'F');

        if($show === true) {
            $this->mpdf->Output();
        }

        return $this;
    }

    /*
     * @params void;
     * returns $this;
     * binds template to variables
     */
    public function replaceAll() {
        $this->_debug("Starting html templating replace", 99);
        if($this->html !== null) {
            //finding all the bracket occurrences
            $template = $this->html;
            if (preg_match_all("/{{(.*?)}}/", $template, $m)) {
                foreach ($m[1] as $i => $varname) {
                    if(array_key_exists($varname, $this->_values)) {
                        $this->_debug("setting " . $m[0][$i] . " as " . $this->_values[$varname]);
                        $template = str_replace($m[0][$i], $this->_values[$varname], $template);
                    } 
                }
            }

            //saving the result
            $this->template = $template;
        } else {
            $this->_debug("It appears like the template is empty", 2);
        }

        return $this;
    }

    /*
     * @params (string) $footer;
     * returns $this;
     * create footer for each page
     */
     public function setFooter(string $footer) {
         $this->mpdf->setFooter($footer);
         return $this;
     }

    /*
     * @params (string) $html;
     * returns $this;
     * binds html data to the protected $this->html
     */
    public function saveHtml($html) {
        $this->html = $html;

        return $this;
    }

    /*
     * @params (string) $name, (string) $val;
     * resurns $this;
     * A simple setter...
     */
    public function set($name, $val) {
        $this->$name = $val;

        return $his;
    }

    /*
     * @params (string) $val;
     * returns $this->$val on success, false on faliure
     * A simple getter...
     */
    public function get($val) {
        if(property_exists($this, $val)) {
            return $this->$val;
        } else {
            $this->_debug("\$this->" . $val . " doesn't exists", 2);
            return false;
        }
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
     * @params void;
     * @returns void;
     * Shows debug
     */
    public function showDebug() {
        echo "<hr><h3>Debug</h3>";
        if(count($this->_debugLog) > 0) {
            foreach($this->_debugLog as $k => $v) {
                echo "<p style='color:" . $v['color'] . "'>" . $v['text'] . "</p>";
            }
        } else {
            echo "<p style='color:#00ff00'>Everything seems to be fine...</p>";
        }
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