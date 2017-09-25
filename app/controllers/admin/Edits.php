<?php

namespace app\controllers\admin;

use app\controllers\api\Edits as EditsApiController;
use app\model\Suggestion;
use app\core\Functions;
use app\string\Url;

class Edits extends EditsApiController
{
  public function index($limit = null, $state = null)
  {
    $params['edit_state'] = $state;
    $params['edit_user_id'] = $_GET['edit_user_id'] ?? null;
    $params['object_type'] = $_GET['object_type'] ?? null;
    $params['object_id'] = $_GET['object_id'] ?? null;

    $limit = $limit ?? 20;
    $this->data['limit'] = $limit;
    $this->data['query_str'] = http_build_query($params);

    if($edits = $this->model->list($limit, $params)) $this->data['edits'] = array_map('self::repl', $edits);
  }

  public function edit($id = null)
  {
    if($id) {
      $this->data['data'] = self::repl($this->model->getEditDetailsById($id));
      $edit = $this->model->getById($id);

      if($edit['type'] === "suggestion") {
        $this->data['suggestion_data'] = (new Suggestion)->getById($edit['object_id']);
      }
      $this->data['object_edits'] = $this->model->getObjectEdits($this->data['data']['object_type'], $this->data['data']['object_id']);
    }
  }

  // humanize edit data
  private static function repl($edit) {
    switch($edit['edit_type']) {
      case "suggestion":
        $edit['lang_edit_type'] = getString('SUGGESTION')." - ";
        switch($edit['edit_sub_type']) {
          case 0:
           $edit['lang_edit_sub_type'] = getString('SUPERMARKETS');
           break;
          case 1:
           $edit['lang_edit_sub_type'] = getString('CATEGORY');
           break;
          case 2:
           $edit['lang_edit_sub_type'] = getString('TAGS');
           break;
          case 3:
           $edit['lang_edit_sub_type'] = getString('IMAGE');
           break;
          case 4:
           $edit['lang_edit_sub_type'] = getString('IMAGE_INGREDIENTS');
           break;
          case 5:
           $edit['lang_edit_sub_type'] = getString('IMAGE_OTHER');
           break;
          case 6:
           $edit['lang_edit_sub_type'] = getString('NOTE');
           break;
          case 7:
           $edit['lang_edit_sub_type'] = getString('INGREDIENTS');
           break;
          case 8:
           $edit['lang_edit_sub_type'] = getString('BARCODE');
           break;
          case 9:
           $edit['lang_edit_sub_type'] = getString('REPORT');
           break;
          default:
            $edit['lang_edit_sub_type'] = getString('OTHER');
        }
        break;
      // product
      case "update":
        $edit['lang_edit_type'] = getString('UPDATE');
        break;
      case "accept":
        $edit['lang_edit_type'] = getString('ACCEPTED');
        break;
      case "deny":
        $edit['lang_edit_type'] = getString('DENIED');
        break;
      case "recover":
        $edit['lang_edit_type'] = getString('RECOVERED');
        break;
      case "delete":
        $edit['lang_edit_type'] = getString('DELETED');
        break;
      case "move":
        $edit['lang_edit_type'] = getString('DELETED');
        break;
      case "moveskcz":
        $edit['lang_edit_type'] = getString('MOVESKCZ');
        break;
      case "moveczsk":
        $edit['lang_edit_type'] = getString('MOVECZSK');
        break;
      // user role
      case "role4":
        $edit['lang_edit_type'] = getString('UPDATE').' - '.' na user';
        break;
      case "role34":
        $edit['lang_edit_type'] = getString('UPDATE').' - '.'na admin';
        break;
      case "role74":
        $edit['lang_edit_type'] = getString('UPDATE').' - '.'na superadmin';
        break;
      // tag
      case "new":
        $edit['lang_edit_type'] = getString('NEW');
        break;
      default:
        $edit['lang_edit_type'] = $edit['edit_type'];
    }

    switch($edit['object_type']) {
      case "product":
        $edit['lang_object_type'] = getString('PRODUCT')." - ";
        $edit['object_url'] = Url::get('PRODUCT_ADMIN_EDIT').$edit['object_id'];
        break;
      case "user":
        $edit['lang_object_type'] = getString('USER')." - ";
        $edit['object_url'] = Url::get('ADMIN_USERS');
        break;
      case "locale":
        $edit['lang_object_type'] = getString('LOCALE');
        $edit['object_url'] = Url::get('ADMIN_LOCALE');
        break;
      case "info":
        $edit['lang_object_type'] = "Info stránka - ";
        $edit['object_url'] = '/admin/info/edit/'.$edit['object_id'];
        break;
      case "tag":
        $edit['lang_object_type'] = "Tag - ";
        $edit['object_url'] = Url::get('TAG_ADMIN_EDIT').$edit['object_id'];
        break;
      case "store":
        $edit['lang_object_type'] = getString('SUPERMARKET')." - ";
        $edit['object_url'] = Url::get('SUPERMARKET_ADMIN_EDIT').$edit['object_id'];
        break;
      case "category":
        $edit['lang_object_type'] = getString('CATEGORY')." - ";
        $edit['object_url'] = Url::get('ADMIN_CATEGORIES_EDIT').$edit['object_id'];
        break;
      case "newsletter":
        $edit['lang_object_type'] = "Newsletter - ";
        $edit['object_url'] = Url::get('ADMIN_NEWSLETTER');
        break;
      default:
    }

    return $edit;
  }

}
