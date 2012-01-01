<?php
    /*
     *      OpenSourceClassifieds – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2011 OpenSourceClassifieds
     *
     *       This program is free software: you can redistribute it and/or
     *     modify it under the terms of the GNU Affero General Public License
     *     as published by the Free Software Foundation, either version 3 of
     *            the License, or (at your option) any later version.
     *
     *     This program is distributed in the hope that it will be useful, but
     *         WITHOUT ANY WARRANTY; without even the implied warranty of
     *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     *             GNU Affero General Public License for more details.
     *
     *      You should have received a copy of the GNU Affero General Public
     * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
     */

    define( 'ABS_PATH', dirname(dirname(dirname(__FILE__))) . '/' );

    require_once  ABS_PATH . '/config.php';
    require_once 'osc/db.php';
    require_once 'osc/classes/DAO.php';
    require_once 'osc/model/Admin.php';
    require_once 'osc/helpers/hDatabaseInfo.php';
    require_once 'osc/core/Params.php';

    $old_passwd   = Params::getParam('old_password');
    $id_admin     = Params::getParam('id');
    $new_username = Params::getParam('new_username');
    $new_passwd   = Params::getParam('new_password');
    $response     = array('error' => 'Operation fail');

    $mAdmin = Admin::newInstance();
    $admin = $mAdmin->findByIdPassword(1, sha1($old_passwd) );

    if($admin){
        $result = -1;
        if( $new_username != '' ){
            $result = $mAdmin->update( array('s_username' => $new_username ), array('pk_i_id' => '1') ) ;
        } elseif ( $new_passwd != '' ) {
            $result = $mAdmin->update( array('s_password' => sha1($new_passwd) ), array('pk_i_id' => '1') ) ;
        }

        switch ($result) {
            case(1): $response = array('ok' => 'Updated sucessfully');
                     break;
            case(0): $response = array('ok' => 'No changes');
                     break;
        }
    }

    echo json_encode($response);

