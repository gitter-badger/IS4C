<?php

namespace COREPOS\pos\lib\PrintHandlers;
use COREPOS\pos\lib\Bitmap;
use \ReceiptLib;

/**
 @class PrintHandler
 Generic print module

 This is working backwards from the
 existing ESCPOS class. 
 Work-in-progress.
*/

class PrintHandler {

    public static function factory($class)
    {
        if ($class != '' && class_exists($class)) {
            return new $class();
        } elseif ($class != '' && class_exists('COREPOS\\pos\\lib\\PrintHandlers\\' . $class)) {
            $class = 'COREPOS\\pos\\lib\PrintHandlers\\' . $class;
            return new $class();
        }

        return new COREPOS\pos\lib\PrintHandlers\ESCPOSPrintHandler();
    }
    
    /**
     Get printer tab
     @return string printer command
    */
    function Tab() {
        return "\t";
    }
    
    /**
      Get newlines
      @param $lines number of lines
      @return string printer command
    */
    function LineFeed($lines=1) {
        $ret = "\n";
        for($i=1;$i<$lines;$i++)
            $ret .= "\n";
        return $ret;
    }
    
    function PageFeed($reset=true) {
        return '';
    }
    
    /**
      Get carriage return
      @return string printer command
    */
    function CarriageReturn() {
        return "\r";
    }
    
    function ClearPage() {
        return '';
    }
    
    function CharacterSpacing($dots=0) {
        return '';
    }

    /**
     Center a string of text
     @param $text the text
     @param $big boolean using larger font
     @return a printable string

     Replaces old center(), centerString(),
     and centerBig() functions. 
    */
    function centerString($text,$big=false)
    {
        $width = ($big) ? 30 : 59;

        $blank = str_repeat(" ", $width);
        $text = trim($text);
        $lead = (int) (($width - strlen($text)) / 2);
        $newline = substr($blank, 0, $lead).$text;
        return $newline;
    }
    
    /**
      Add font style command
        @param $altFont use alternate font
      @param $bold use bold font
      @param $tall use tall characters
      @param $wide use wide characters
      @param $underline use undlerined font      
      @return A printer command string

      Replaces several old printLib.php functions
      TextStyle(true) is equivalent to normalFont()
      TextStyle(true,true) is equivalent to boldFont()
      TextStyle(true,false,true) is equivalent to
        biggerFont().
    */
    function TextStyle($altFont=false, $bold=false, $tall=false, $wide=false, $underline=false) {
        return '';
    }
    
    function GotoX($dots=0) {
        return '';
    } // GotoX()
    
    /*
    ESC/POS requires graphical bitmap data in columns, but BMP is rasterized in rows,
    so this function transposes the latter to the former.  The source BMP data must
    be 1 bpp (black/white image only), and $width is in pixels == bits (not bytes).
    The return will be an array of binary strings; if the source data fits in one
    inline stripe (8 pixels tall with $tallDots, 24 otherwise) the array will have
    only one element.
    */
    function TransposeBitmapData($data, $width, $tallDots=false) 
    {
        $oldRowBytes = (int)(($width + 7) / 8);
        $newColBytes = $tallDots ? 1 : 3;
        $oldStripeSize = (int)($oldRowBytes * ($tallDots ? 8 : 24));
        $stripes = array();

        // str_split function doesn't exist in PHP4
        $str_split = array();
        $val = "";
        for($i=0;$i<strlen($data);$i++){
            if ($i % $oldStripeSize==0){
                if (strlen($val) > 0)
                    $str_split[] = $val;
                $val = "";
            }
            $val .= $data[$i];
        }
        if (strlen($val) > 0)
            $str_split[] = $val;
        // end manual split

        foreach ($str_split as $oldData) {
            $oldData = str_pad($oldData, $oldStripeSize, "\x00", STR_PAD_RIGHT);
            $newData = str_repeat("\x00", $width * $newColBytes);
            for ($c = 0;  $c < $width;  $c++) {
                $oldMask = 1 << (7 - ($c % 8));
                $newByte = ($tallDots ? $c : ($c + $c + $c));
                // top or only byte
                $b = $this->bitMagic(0, array($oldData, $oldMask, $oldRowBytes), $c);
                $newData[$newByte + 0] = chr($b);
                if (!$tallDots) {
                    // middle byte
                    $b = $this->bitMagic(8, array($oldData, $oldMask, $oldRowBytes), $c);
                    $newData[$newByte + 1] = chr($b);
                    // bottom byte
                    $b = $this->bitMagic(16, array($oldData, $oldMask, $oldRowBytes), $c);
                    $newData[$newByte + 2] = chr($b);
                }
            }
            $stripes[] = $newData;
        }
        return $stripes;
    } // TransposeBitmapData()

    /**
      No idea what this really does but it was repeated
      three times above
    */
    private function bitMagic($base, $oldInfo, $c)
    {
        $byte = 0;
        list($oldData, $oldMask, $oldRowBytes) = $oldInfo;
        for ($r = $base;  $r < $base+8;  $r++) {
            $oldByte = ($r * $oldRowBytes) + ($c >> 3); // (int)($c / 8)
            if (ord($oldData[$oldByte]) & $oldMask)
                $byte |= (1 << (7 - ($r % 8)));
        }

        return $byte;
    }
    
    function InlineBitmap($data, $width, $tallDots=false, $wideDots=false) {
        return '';
    } // InlineBitmap()
    
    function Underline($dots=1) {
        return '';
    }
    
    function ResetLineSpacing() {
        return '';
    }
    
    function LineSpacing($space=64) {
        return '';
    }
    
    function Reset() {
        return '';
    }
    
    function SetTabs($tabs=null) {
        return '';
    }
    
    /**
     Enable or disable bold font
     @param $on boolean enable
     @return string printer command
    */
    function Bold($on=true) {
        return "";
    }
    
    function DoublePrint($on=true) {
        return "";
    }
    
    function PaperFeed($space) {
        return "";
    }
    
    function PaperFeedBack($space) {
        return "";
    }
    
    function PageMode() {
        return "";
    }
    
    function Font($font=0) {
        return "";
    }
    
    /*
    international character sets:
        0: America
        1: France
        2: Germany
        3: UK
        4: Denmark I
        5: Sweden
        6: Italy
        7: Spain I
        8: Japan
        9: Norway
        10: Denmark II
        11: Spain II
        12: Latin America
        13: Korea
    */
    function CharacterSet($set=0) {
        return '';
    }
    
    function LineMode() {
        return '';
    }
    
    function PageOrient($orient=0) {
        return '';
    }
    
    // TODO: unidirectional printing;  ESC(\x1B) "U"(\x55) bit
    
    function Rotate($on=true) {
        return '';
    }
    
    function PageRegion($x=0, $y=0, $dx=65535, $dy=65535) {
        return '';
    }
    
    function MoveX($dots) {
        return '';
    }
    
    function AlignLeft() {
        return '';
    }
    
    function AlignCenter() {
        return '';
    }
    
    function AlignRight() {
        return '';
    }
    
    function PaperRoll($receipt=true, $journal=false, $endorse=false, $validation=false) {
        return '';
    } // PaperRoll()
    
    function PanelButtons($on=true) {
        return '';
    }
    
    function LineFeedBack() {
        return '';
    }
    
    function DrawerKick($pin=2, $on=100, $off=100) {
        return '';
    }
    
    /*
    code tables:
        0: PC437 (USA: Standard Europe)
        1: Katakana
        2: PC850 (Multilingual)
        3: PC860 (Portugese)
        4: PC863 (Canadian-French)
        5: PC865 (Nordic)
        16: WPC1252
        17: PC866 (Cryllic #2)
        18: PC852 (Latin2)
        19: PC858
        255: blank
    */
    function CodeTable($table=0) {
        return '';
    }
    
    function UpsideDown($on=true) {
        return '';
    }
    
    function CharacterZoom($horiz=1, $vert=1) {
        return '';
    }
    
    function GotoY($dots=0) {
        return '';
    }
    
    function Test($type=3, $paper=0) {
        return '';
    }
    
    function Density($factor=1.0) {
        return '';
    }
    
    function ColorBlack() {
        return '';
    }
    
    function ColorRed() {
        return '';
    }
    
    function Invert($on=true) {
        return '';
    }
    
    function SpeedHigh() {
        return '';
    }
    
    function SpeedMedium() {
        return '';
    }
    
    function SpeedLow() {
        return '';
    }
    
    function BarcodeHRI($below=true, $above=false) {
        return '';
    }
    
    function LeftMargin($dots=0) {
        return '';
    }
    
    function DotPitch($primary=0, $secondary=0) {
        return '';
    }
    
    function DiscardLine() {
        return '';
    }
    
    function PreCutPaper($full=false) {
        return '';
    }
    
    function CutPaper($full=false, $feed=0) {
        return '';
    }
    
    function PrintableWidth($dots=65535) {
        return '';
    }
    
    function MoveY($dots) {
        return '';
    }
    
    function Smooth($on=true) {
        return '';
    }
    
    function BarcodeHRIFont($font=0) {
        return '';
    }
    
    function BarcodeHeight($dots=162) {
        return '';
    }
    
    function BarcodeUPC($data, $upcE=false) {
        return '';
    }
    
    function BarcodeEAN($data, $ean8=false) {
        return '';
    }
    
    function BarcodeCODE39($data) {
        return '';
    }
    
    function BarcodeITF($data) {
        return '';
    }
    
    function BarcodeCODEABAR($data) {
        return '';
    }
    
    function BarcodeCODE93($data) {
        return '';
    }
    
    function BarcodeCODE128($data) {
        return '';
    }
    
    /*
    Raster bitmaps may be up to 524280 pixels wide ((255+255*256)*8), and up to 2303 pixels tall (255+8*256).
    Tall dots are 2 pixels tall, and wide dots are 2 pixels wide.
    This function takes $width and $height as pixels, but the printer takes width as bytes (= 8 pixels).
    Data must have (($width * $height) / 8) bytes.
    The bits in each data byte specify dots from left-to-right, and bytes are left-to-right, top-to-bottom.
        \xC0 = \b11000000 = pixels in the left two columns
        \x81 = \b10000001 = pixels in the left and right columns
    */
    function RasterBitmap($data, $width, $height, $tallDots=false, $wideDots=false) {
        return '';
    }
    
    function BarcodeWidth($scale=3) {
        return '';
    }

    /**
      Write output to device
      @param the output string
    */
    function writeLine($text){
        ReceiptLib::writeLine($text);
    }

    /**
      Draw bitmap from file
      @param $fn a bitmap file
      @return printer command string
    */
    function RenderBitmapFromFile($fn, $align='C')
    {
        return $this->RenderBitmap($fn, $align);
    }

    /**
      Turn bitmap into receipt string
      @param $arg string filename OR Bitmap obj
      @return receipt-formatted string
    */
    function RenderBitmap($arg, $align='C'){
        $slip = "";

        $bmp = null;
        if (is_object($arg) && is_a($arg, 'Bitmap')){
            $bmp = $arg;
        }
        else if (file_exists($arg)){
            $bmp = new Bitmap();
            $bmp->load($arg);
        }

        // argument was invalid
        if ($bmp === null)
            return "";

        $bmpData = $bmp->getRawData();
        $bmpWidth = $bmp->getWidth();
        $bmpHeight = $bmp->getHeight();
        $bmpRawBytes = (int)(($bmpWidth + 7)/8);

        $stripes = $this->TransposeBitmapData($bmpData, $bmpWidth);
        for($i=0; $i<count($stripes); $i++)
            $stripes[$i] = $this->InlineBitmap($stripes[$i], $bmpWidth);

        $slip .= $this->AlignCenter();
        if (count($stripes) > 1)
            $slip .= $this->LineSpacing(0);
        $slip .= implode("\n",$stripes);
        if (count($stripes) > 1)
            $slip .= $this->ResetLineSpacing()."\n";
        $slip .= $this->AlignLeft();

        return $slip;
    }

    /**
      Wrapper for raw ESC byte strings so 
      subclass handlers can decide whether they're
      compatible
      @param $command [string] command bytes
      @return [string] receipt text
    */
    public function rawEscCommand($command)
    {
        return '';
    }

    /**
      Show bitmap stored on the printer device itself
      @param $image_id [int] storage location ID
      @return [string] receipt text
    */
    public function renderBitmapFromRam($image_id)
    {
        return '';
    }

    /**
      Insert a chunk of information into the
      receipt that writeLine() will later use
      during rendering. By default adds nothing.
    */
    public function addRenderingSpacer($str)
    {
        return '';
    }
} 

