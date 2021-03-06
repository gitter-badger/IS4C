<?php
/*******************************************************************************

    Copyright 2016 Whole Foods Co-op

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

namespace COREPOS\pos\lib;
use COREPOS\pos\lib\Database;

/**
  @class Drawer
*/
class Drawers extends \LibraryClass 
{
    static public function kick() 
    {
        $pin = self::current();
        if ($pin == 1) {
            \ReceiptLib::writeLine(chr(27).chr(112).chr(0).chr(48)."0");
        } elseif ($pin == 2) {
            \ReceiptLib::writeLine(chr(27).chr(112).chr(1).chr(48)."0");
        }
    }

    /**
      Which drawer is currently in use
      @return
        1 - Use the first drawer
        2 - Use the second drawer
        0 - Current cashier has no drawer

      This always returns 1 when dual drawer mode
      is enabled. Assignments in the table aren't
      relevant.
    */
    static public function current()
    {
        if (\CoreLocal::get('dualDrawerMode') !== 1) {
            return 1;
        }

        $dbc = Database::pDataConnect();
        $chkQ = 'SELECT drawer_no FROM drawerowner WHERE emp_no=' . \CoreLocal::get('CashierNo');
        $chkR = $dbc->query($chkQ);
        if ($dbc->numRows($chkR) == 0) {
            return 0;
        } else {
            $chkW = $dbc->fetchRow($chkR);
            return $chkW['drawer_no'];
        }
    }

    /**
      Assign drawer to cashier
      @param $emp the employee number
      @param $num the drawer number
      @return success True/False
    */
    static public function assign($emp,$num)
    {
        $dbc = Database::pDataConnect();
        $upQ = sprintf('UPDATE drawerowner SET emp_no=%d WHERE drawer_no=%d',$emp,$num);
        $upR = $dbc->query($upQ);

        return ($upR !== false) ? true : false;
    }

    /**
      Unassign drawer
      @param $num the drawer number
      @return success True/False
    */
    static public function free($num)
    {
        $dbc = Database::pDataConnect();
        $upQ = sprintf('UPDATE drawerowner SET emp_no=NULL WHERE drawer_no=%d',$num);
        $upR = $dbc->query($upQ);

        return ($upR !== false) ? true : false;
    }

    /**
      Get list of available drawers
      @return array of drawer numbers
    */
    static public function available()
    {
        $dbc = Database::pDataConnect();
        $query = 'SELECT drawer_no FROM drawerowner WHERE emp_no IS NULL ORDER BY drawer_no';
        $res = $dbc->query($query);
        $ret = array();
        while ($row = $dbc->fetchRow($res)) {
            $ret[] = $row['drawer_no'];
        }

        return $ret;
    }
}

