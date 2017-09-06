<?php

namespace app\model;

use app\core\Model;
use app\model\Edit;
use m4\m4mvc\helper\Session;

use mrkovec\sdiff\SDiff;


class Locale extends Model
{
  private $edit;

  function __construct()
  {
    $this->edit = new Edit();
  }

  // load localization texts from json file
  public static function loadLang($lang)
  {
    return json_decode(
      file_get_contents(APP . DS . 'string/lang/'.$lang.'.json'),
      True
    );
  }

  // save localization data
  public static function saveLang($lang, $data)
  {
    ksort($data);
    file_put_contents(
      APP . DS . 'string/lang/'.$lang.'.json', 
      json_encode(
        $data, 
        JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT
      )
    );
  }

  // merges localization data into one
  public function compareLocale()
  {
    $sk = self::loadLang("sk");
    $cz = self::loadLang("cz");
    array_walk($sk, function(&$item, $key) {
      $item = ['sk' => $item];
    });
    array_walk($cz, function(&$item, $key) {
      $item = ['cz' => $item];
    });
    $a = array_merge_recursive($sk, $cz);
    ksort($a);
    return $a;
  }

  // processeslocale changes from POST request
  public function processChange($post)
  {
    $sk = [];
    $cz = [];
    // divide POST keys by language preffixes
    foreach ($post as $key => $value) {
      if (substr($key, 0, 2 ) === "sk") {
         $sk[substr($key, 2, strlen($key))] = $value;
       }elseif (substr($key, 0, 2 ) === "cz") {
         $cz[substr($key, 2, strlen($key))] = $value;
       }
    }

    // compute diffs from old and new localization data
    $diff_sk = SDiff::getObjectDiff(self::loadLang("sk"),$sk, False);
    $diff_cz = SDiff::getObjectDiff(self::loadLang("cz"),$cz, False);
    $diff = "";
    if($diff_sk) $diff .= "SK: ".$diff_sk;
    if($diff_cz) {
      if($diff) $diff .="<br>";
      $diff .= "CZ: ".$diff_cz;
    }

    //save new locale
    self::saveLang("sk", $sk);
    self::saveLang("cz", $cz);

    $this->closeEdit(null, $diff);
  }

  // checks if localization data is updated by another user
  public function isLocked()
  {
    return !empty($this->edit->getEditsByObject("locale", null, "opened")) && 
           !$this->haveLock();
  }

  // checks if current user has lock on localization
  public function haveLock()
  {
    return !empty($this->edit->getUserEditsByObject("locale", null, "opened"));
  }

  // creates new edit along with locking localisation for current user
  public function startEdit()
  {
    $data['type'] = "update";
    $data['object_type'] = "locale";
    $this->edit->newEdit($data);
  }

  // closes edit and frees lock
  public function closeEdit($comment = null, $diff = null)
  {
    if($edit = $this->edit->getUserEditsByObject("locale", null, "opened")) {
      $this->edit->closeEdit($edit[0]['id'], $comment, $diff);
    }
  }

}
