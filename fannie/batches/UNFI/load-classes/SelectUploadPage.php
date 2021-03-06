<?php
/*******************************************************************************

    Copyright 2013 Whole Foods Co-op

    This file is part of CORE-POS.

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

include(dirname(__FILE__) . '/../../../config.php');
if (!class_exists('FannieAPI')) {
    include_once($FANNIE_ROOT.'classlib2.0/FannieAPI.php');
}
if (!class_exists('UnfiUploadPage')) {
    include(dirname(__FILE__) . '/UnfiUploadPage.php');
}

class SelectUploadPage extends UnfiUploadPage 
{
    public $title = "Fannie - Select Nutrition Prices";
    public $header = "Upload Select Nutrition price file";

    public $description = '[Select Nutrition Catalog Import] specialized vendor import tool. Column choices
    default to Select price file layout.';
    protected $vendor_name = 'SELECT';
}

FannieDispatch::conditionalExec();

