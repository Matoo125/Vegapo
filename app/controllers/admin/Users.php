<?php 

namespace app\controllers\admin;

use app\controllers\api\Users as UsersApiController;

class Users extends UsersApiController
{
  public function index()
  {
    $this->data['data'] = $this->model->getAll();

  }

  public function change_role($id, $role)
  {
    // only superadmin and developer can change role
    // nobody can add role higher than 74
    if(!self::check_premission(74) || $role > 75) return;
    // only developer can add superadmins
    if($role > 70 && !self::check_premission(75) ) return;

    $this->model->update(
      ['role'     =>  $role],
      ['user_id'  =>  $id]
    );

    redirect('/admin/users');
  }
}