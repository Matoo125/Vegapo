<?php

namespace app\model;

use app\core\Model;
use app\model\Edit;
use app\core\Session;

// use app\helper\SDiff;


class Locale extends Model
{
    private $edit;

    function __construct()
    {
      $this->edit = new Edit();
    }

    public static function loadLang($lang)
    {
      return json_decode(file_get_contents(APP . DS . 'string/lang/'.$lang.'.json'), True);
    }

    public function saveLang($lang, $data)
    {
      file_put_contents(APP . DS . 'string/lang/'.$lang.'.json', json_encode($data, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
    }

    public function compareLocale()
    {
      $sk = self::loadLang("sk");
      $cz = self::loadLang("cz");
      array_walk($sk, function(&$item1, $key) {
        $item1 = ['sk' => $item1];
      });
      array_walk($cz, function(&$item1, $key) {
        $item1 = ['cz' => $item1];
      });
      $a = array_merge_recursive($sk, $cz);
      ksort($a);
      return $a;
    }

    public function treatChange($post)
    {
      $sk = [];
      $cz = [];
      foreach ($_POST as $key => $value) {
        if (substr( $key, 0, 2 ) === "sk") {
           $sk[substr( $key, 2, strlen($key))] = $value;
         }elseif (substr( $key, 0, 2 ) === "cz") {
           $cz[substr( $key, 2, strlen($key))] = $value;
         }
      }

      // $from_text_utf8 = json_encode(self::loadLang("sk"), JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
      // $to_text_utf8 = json_encode($sk, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
      // $diff_opcodes = \FineDiff::getDiffOpcodes($from_text_utf8, $to_text_utf8);
      // $diff = _format_json(html_entity_decode(\FineDiff::renderDiffToHTMLFromOpcodes($from_text_utf8, $diff_opcodes)), True);

      var_dump($diff);
      exit;
      $this->saveLang("sk", $sk);
      $this->saveLang("cz", $cz);

      $this->closeEdit($diff);
    }


    public function isLocked()
    {
       return !empty($this->edit->getEditsByObject("locale", null, "opened")) && !$this->haveLock();
      // return !empty($this->runQuery("select * from edits where type = 'lang' and state = 'opened' and user_id != :user_id",
      // [":user_id" => Session::get("user_id")], "get"));
    }

    public function haveLock()
    {
      return !empty($this->edit->getUserEditsByObject("locale", null, "opened"));
      // return !empty($this->runQuery("select * from edits where type = 'lang' and state = 'opened' and user_id = :user_id",
      // [":user_id" => Session::get("user_id")], "get"));
    }

    public function startEdit()
    {
      $this->edit->newLocaleEdit();
    }

    public function closeEdit($comment = null, $diff = null)
    {
      if($edit = $this->edit->getUserEditsByObject("locale", null, "opened")) {
        $this->edit->closeEdit($e[0]['id'], $comment, $diff);
      }
    }

}
