<?php
/*******************************************************************************

    Copyright 2014 Whole Foods Co-op

    This file is part of Fannie.

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

/**
  @class ScaleItemsModel
*/
class ScaleItemsModel extends BasicModel
{

    protected $name = "scaleItems";

    protected $columns = array(
    'plu' => array('type'=>'VARCHAR(13)', 'primary_key'=>true),
    'price' => array('type'=>'MONEY'),
    'itemdesc' => array('type'=>'VARCHAR(100)'),
    'exceptionprice' => array('type'=>'MONEY'),
    'weight' => array('type'=>'TINYINT', 'default'=>0),
    'bycount' => array('type'=>'TINYINT', 'default'=>0),
    'tare' => array('type'=>'FLOAT', 'default'=>0),
    'shelflife' => array('type'=>'SMALLINT', 'default'=>0),
    'netWeight' => array('type'=>'SMALLINT', 'default'=>0),
    'text' => array('type'=>'TEXT'),
    'reportingClass' => array('type'=>'VARCHAR(6)'),
    'label' => array('type'=>'INT'),
    'graphics' => array('type'=>'INT'),
	);

    protected $preferred_db = 'op';

    /* START ACCESSOR FUNCTIONS */

    public function plu()
    {
        if(func_num_args() == 0) {
            if(isset($this->instance["plu"])) {
                return $this->instance["plu"];
            } else if (isset($this->columns["plu"]["default"])) {
                return $this->columns["plu"]["default"];
            } else {
                return null;
            }
        } else {
            if (!isset($this->instance["plu"]) || $this->instance["plu"] != func_get_args(0)) {
                if (!isset($this->columns["plu"]["ignore_updates"]) || $this->columns["plu"]["ignore_updates"] == false) {
                    $this->record_changed = true;
                }
            }
            $this->instance["plu"] = func_get_arg(0);
        }
    }

    public function price()
    {
        if(func_num_args() == 0) {
            if(isset($this->instance["price"])) {
                return $this->instance["price"];
            } else if (isset($this->columns["price"]["default"])) {
                return $this->columns["price"]["default"];
            } else {
                return null;
            }
        } else {
            if (!isset($this->instance["price"]) || $this->instance["price"] != func_get_args(0)) {
                if (!isset($this->columns["price"]["ignore_updates"]) || $this->columns["price"]["ignore_updates"] == false) {
                    $this->record_changed = true;
                }
            }
            $this->instance["price"] = func_get_arg(0);
        }
    }

    public function itemdesc()
    {
        if(func_num_args() == 0) {
            if(isset($this->instance["itemdesc"])) {
                return $this->instance["itemdesc"];
            } else if (isset($this->columns["itemdesc"]["default"])) {
                return $this->columns["itemdesc"]["default"];
            } else {
                return null;
            }
        } else {
            if (!isset($this->instance["itemdesc"]) || $this->instance["itemdesc"] != func_get_args(0)) {
                if (!isset($this->columns["itemdesc"]["ignore_updates"]) || $this->columns["itemdesc"]["ignore_updates"] == false) {
                    $this->record_changed = true;
                }
            }
            $this->instance["itemdesc"] = func_get_arg(0);
        }
    }

    public function exceptionprice()
    {
        if(func_num_args() == 0) {
            if(isset($this->instance["exceptionprice"])) {
                return $this->instance["exceptionprice"];
            } else if (isset($this->columns["exceptionprice"]["default"])) {
                return $this->columns["exceptionprice"]["default"];
            } else {
                return null;
            }
        } else {
            if (!isset($this->instance["exceptionprice"]) || $this->instance["exceptionprice"] != func_get_args(0)) {
                if (!isset($this->columns["exceptionprice"]["ignore_updates"]) || $this->columns["exceptionprice"]["ignore_updates"] == false) {
                    $this->record_changed = true;
                }
            }
            $this->instance["exceptionprice"] = func_get_arg(0);
        }
    }

    public function weight()
    {
        if(func_num_args() == 0) {
            if(isset($this->instance["weight"])) {
                return $this->instance["weight"];
            } else if (isset($this->columns["weight"]["default"])) {
                return $this->columns["weight"]["default"];
            } else {
                return null;
            }
        } else {
            if (!isset($this->instance["weight"]) || $this->instance["weight"] != func_get_args(0)) {
                if (!isset($this->columns["weight"]["ignore_updates"]) || $this->columns["weight"]["ignore_updates"] == false) {
                    $this->record_changed = true;
                }
            }
            $this->instance["weight"] = func_get_arg(0);
        }
    }

    public function bycount()
    {
        if(func_num_args() == 0) {
            if(isset($this->instance["bycount"])) {
                return $this->instance["bycount"];
            } else if (isset($this->columns["bycount"]["default"])) {
                return $this->columns["bycount"]["default"];
            } else {
                return null;
            }
        } else {
            if (!isset($this->instance["bycount"]) || $this->instance["bycount"] != func_get_args(0)) {
                if (!isset($this->columns["bycount"]["ignore_updates"]) || $this->columns["bycount"]["ignore_updates"] == false) {
                    $this->record_changed = true;
                }
            }
            $this->instance["bycount"] = func_get_arg(0);
        }
    }

    public function tare()
    {
        if(func_num_args() == 0) {
            if(isset($this->instance["tare"])) {
                return $this->instance["tare"];
            } else if (isset($this->columns["tare"]["default"])) {
                return $this->columns["tare"]["default"];
            } else {
                return null;
            }
        } else {
            if (!isset($this->instance["tare"]) || $this->instance["tare"] != func_get_args(0)) {
                if (!isset($this->columns["tare"]["ignore_updates"]) || $this->columns["tare"]["ignore_updates"] == false) {
                    $this->record_changed = true;
                }
            }
            $this->instance["tare"] = func_get_arg(0);
        }
    }

    public function shelflife()
    {
        if(func_num_args() == 0) {
            if(isset($this->instance["shelflife"])) {
                return $this->instance["shelflife"];
            } else if (isset($this->columns["shelflife"]["default"])) {
                return $this->columns["shelflife"]["default"];
            } else {
                return null;
            }
        } else {
            if (!isset($this->instance["shelflife"]) || $this->instance["shelflife"] != func_get_args(0)) {
                if (!isset($this->columns["shelflife"]["ignore_updates"]) || $this->columns["shelflife"]["ignore_updates"] == false) {
                    $this->record_changed = true;
                }
            }
            $this->instance["shelflife"] = func_get_arg(0);
        }
    }

    public function netWeight()
    {
        if(func_num_args() == 0) {
            if(isset($this->instance["netWeight"])) {
                return $this->instance["netWeight"];
            } else if (isset($this->columns["netWeight"]["default"])) {
                return $this->columns["netWeight"]["default"];
            } else {
                return null;
            }
        } else {
            if (!isset($this->instance["netWeight"]) || $this->instance["netWeight"] != func_get_args(0)) {
                if (!isset($this->columns["netWeight"]["ignore_updates"]) || $this->columns["netWeight"]["ignore_updates"] == false) {
                    $this->record_changed = true;
                }
            }
            $this->instance["netWeight"] = func_get_arg(0);
        }
    }

    public function text()
    {
        if(func_num_args() == 0) {
            if(isset($this->instance["text"])) {
                return $this->instance["text"];
            } else if (isset($this->columns["text"]["default"])) {
                return $this->columns["text"]["default"];
            } else {
                return null;
            }
        } else {
            if (!isset($this->instance["text"]) || $this->instance["text"] != func_get_args(0)) {
                if (!isset($this->columns["text"]["ignore_updates"]) || $this->columns["text"]["ignore_updates"] == false) {
                    $this->record_changed = true;
                }
            }
            $this->instance["text"] = func_get_arg(0);
        }
    }

    public function reportingClass()
    {
        if(func_num_args() == 0) {
            if(isset($this->instance["reportingClass"])) {
                return $this->instance["reportingClass"];
            } else if (isset($this->columns["reportingClass"]["default"])) {
                return $this->columns["reportingClass"]["default"];
            } else {
                return null;
            }
        } else {
            if (!isset($this->instance["reportingClass"]) || $this->instance["reportingClass"] != func_get_args(0)) {
                if (!isset($this->columns["reportingClass"]["ignore_updates"]) || $this->columns["reportingClass"]["ignore_updates"] == false) {
                    $this->record_changed = true;
                }
            }
            $this->instance["reportingClass"] = func_get_arg(0);
        }
    }

    public function label()
    {
        if(func_num_args() == 0) {
            if(isset($this->instance["label"])) {
                return $this->instance["label"];
            } else if (isset($this->columns["label"]["default"])) {
                return $this->columns["label"]["default"];
            } else {
                return null;
            }
        } else {
            if (!isset($this->instance["label"]) || $this->instance["label"] != func_get_args(0)) {
                if (!isset($this->columns["label"]["ignore_updates"]) || $this->columns["label"]["ignore_updates"] == false) {
                    $this->record_changed = true;
                }
            }
            $this->instance["label"] = func_get_arg(0);
        }
    }

    public function graphics()
    {
        if(func_num_args() == 0) {
            if(isset($this->instance["graphics"])) {
                return $this->instance["graphics"];
            } else if (isset($this->columns["graphics"]["default"])) {
                return $this->columns["graphics"]["default"];
            } else {
                return null;
            }
        } else {
            if (!isset($this->instance["graphics"]) || $this->instance["graphics"] != func_get_args(0)) {
                if (!isset($this->columns["graphics"]["ignore_updates"]) || $this->columns["graphics"]["ignore_updates"] == false) {
                    $this->record_changed = true;
                }
            }
            $this->instance["graphics"] = func_get_arg(0);
        }
    }
    /* END ACCESSOR FUNCTIONS */
}

