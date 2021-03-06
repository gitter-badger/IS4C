<?php
/*******************************************************************************

    Copyright 2001, 2004 Wedge Community Co-op

    This file is part of IT CORE.

    IT CORE is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    IT CORE is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    in the file license.txt along with IT CORE; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*********************************************************************************/

use COREPOS\pos\parser\parse\UPC;

include_once(dirname(__FILE__).'/../lib/AutoLoader.php');

class productlist extends NoInputCorePage 
{

    private $boxSize;
    private $search_results = array();
    private $quantity = 0;

    private function adjustUPC($entered)
    {
        // expand UPC-E to UPC-A
        if (substr($entered, 0, 1) == 0 && strlen($entered) == 7) {
            $parser = new UPC();
            $entered = $parser->expandUPCE($entered);
        }

        // UPCs should be length 13 w/ at least one leading zero
        if (strlen($entered) == 13 && substr($entered, 0, 1) != 0) 
            $entered = "0".substr($entered, 0, 12);
        else 
            $entered = substr("0000000000000".$entered, -13);

        // zero out the price field of scale UPCs
        if (substr($entered, 0, 3) == "002")
            $entered = substr($entered, 0, 8)."00000";

        return $entered;
    }

    private function getQuantity($entered)
    {
        if (strstr($entered, '*')) {
            list($qty,$rest) = explode('*', $entered, 2);
            $qty = is_numeric($qty) ? $qty : 1;
        } elseif (isset($_REQUEST['qty'])) {
            $qty = is_numeric($_REQUEST['qty']) ? $_REQUEST['qty'] : 0;
        } else {
            $qty = 0;
        }

        return array($qty, $entered);
    }

    function preprocess()
    {
        $entered = "";
        if (isset($_REQUEST["search"])) {
            $entered = strtoupper(trim($_REQUEST["search"]));
        } else {
            return True;
        }

        // canceled
        if (empty($entered)) {
            $this->change_page($this->page_url."gui-modules/pos2.php");
            return False;
        }

        list($qty, $entered) = $this->getQuantity($entered);
        $this->quantity = $qty;

        // picked an item from the list
        if (is_numeric($entered) && strlen($entered) == 13) {
            $inp = $qty ? $qty . '*' . $entered : $entered;
            $this->change_page(
                $this->page_url
                . "gui-modules/pos2.php"
                . '?reginput=' . urlencode($inp)
                . '&repeat=1');
            return false;
        }

        if (is_numeric($entered)) {
            $entered = $this->adjustUPC($entered);
        }

        $this->search_results = $this->runSearch($entered);

        return true;
    } // END preprocess() FUNCTION

    private function runSearch($entered)
    {
        /* Get all enabled plugins and standard modules of the base. */
        $modules = AutoLoader::ListModules('ProductSearch');
        $results = array();
        $this->boxSize = 1;
        /* Search first with the plugins
         *  and then with standard modules.
         * Keep only the first instance of each upc.
         * Increase the depth of the list from module parameters.
         */
        foreach($modules as $mod_name){
            $mod = new $mod_name();
            $mod_results = $mod->search($entered);
            foreach($mod_results as $upc => $record){
                if (!isset($results[$upc]))
                    $results[$upc] = $record;
            }
            if ($mod->result_size > $this->boxSize)
                $this->boxSize = $mod->result_size;
            if (isset($mod->this_mod_only) && $mod->this_mod_only) {
                break;
            }
        }

        return $results;
    }

    function head_content()
    {
        // Javascript is only really needed if there are results
        if (count($this->search_results) > 0) {
            ?>
            <script type="text/javascript" src="../js/selectSubmit.js"></script>
            <?php
        }
    } // END head() FUNCTION

    function body_content()
    {
        if (count($this->search_results) == 0) {
            $this->productsearchbox(_("no match found")."<br />"._("next search or enter upc"));
        } else {
            $this->add_onload_command("selectSubmit('#search', '#selectform', '#filter-span')\n");

            // originally 390
            if (CoreLocal::get('touchscreen')) {
                $maxSelectWidth = 470;
            } else {
                $maxSelectWidth = 530;
            }
            echo "<div class=\"baseHeight\">"
                ."<div class=\"listbox\">"
                ."<form name=\"selectform\" method=\"post\" action=\""
                . filter_input(INPUT_SERVER, 'PHP_SELF') . "\""
                ." id=\"selectform\">"
                ."<select name=\"search\" id=\"search\" "
                .' style="min-height: 200px; min-width: 220px;'
                ." max-width: {$maxSelectWidth}px;\""
                ."size=".$this->boxSize." onblur=\"\$('#search').focus();\" "
                ."ondblclick=\"document.forms['selectform'].submit();\">";

            $selected = "selected";
            foreach ($this->search_results as $row){
                $price = $row["normal_price"];    

                if ($row["scale"] != 0) $Scale = "S";
                else $Scale = " ";

                $price = MiscLib::truncate2($price);

                echo "<option value='".$row["upc"]."' ".$selected.">".$row["upc"]." - ".$row["description"]
                    ." -- [".$price."] ".$Scale."\n";
                    
                $selected = "";
            }
            echo "</select>"
                . '<div id="filter-span"></div>'
                ."</div>";
            if (CoreLocal::get('touchscreen')) {
                echo '<div class="listbox listboxText">'
                    . DisplayLib::touchScreenScrollButtons()
                    . '</div>';
            }
            echo "<div class=\"listboxText coloredText centerOffset\">"
                . _("use arrow keys") . '<br />' . _("to navigate") . '<br />' . _("the list")
                . '<p><button type="submit" class="pos-button wide-button coloredArea">
                    OK <span class="smaller">[enter]</span>
                    </button></p>'
                . '<p><button type="submit" class="pos-button wide-button errorColoredArea"
                    onclick="$(\'#search\').append($(\'<option>\').val(\'\'));$(\'#search\').val(\'\');">
                    Cancel <span class="smaller">[clear]</span>
                    </button></p>'
                ."</div><!-- /.listboxText coloredText .centerOffset -->"
                .'<input type="hidden" name="qty" value="' . $this->quantity . '" />'
                ."</form>"
                ."<div class=\"clear\"></div>";
            echo "</div>";
        }

        $this->add_onload_command("\$('#search').focus();\n");
    } // END body_content() FUNCTION

    function productsearchbox($strmsg) {
        ?>
        <div class="baseHeight">
            <div class="colored centeredDisplay rounded">
            <span class="larger">
            <?php echo $strmsg;?>
            </span>
            <form action="<?php echo filter_input(INPUT_SERVER, 'PHP_SELF'); ?>" method="post" autocomplete="off">
            <p>
            <input type="text" name="search" size="15" id="search"
                onblur="$('#search').focus();" />
            <input type="hidden" name="qty" value="<?php echo $this->quantity; ?>" />
            </p>
            <button class="pos-button" type="button"
                onclick="$('#search').val('');$(this).closest('form').submit();">
                Cancel [enter]
            </button>
            </form>
            </div>
        </div>
        <?php
    }

    public function unitTest($phpunit)
    {
        $res = $this->runSearch('BANA');
        $phpunit->assertInternalType('array', $res);
        $phpunit->assertNotEquals(0, count($res));
        $one = array_pop($res);
        $this->search_results = array($one); // no need to loop whole list
        ob_start();
        $this->body_content();
        $phpunit->assertNotEquals(0, ob_get_clean());
    }

}

AutoLoader::dispatch();

