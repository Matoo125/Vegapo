<?php

namespace app\helper;

// Stupid text diff
class SDiff {

  // returns ["text similiarity", "text diff"] for two strings
  public static function getDiff($a, $b, $spliter = null)
  {
    if (!is_string($a) || !is_string($a)) throw new \Exception('not a string');
    if ($a === $b) return ["eq" => 1, "diff" => False];

    if($spliter) {
      $text1 = explode($spliter, $a);
      $text2 = explode($spliter, $b);
    } else {
      // $text1 = str_split($a);
      // $text2 = str_split($b);

      $text1 = preg_split('//u', $a, -1, PREG_SPLIT_NO_EMPTY);
      $text2 = preg_split('//u', $b, -1, PREG_SPLIT_NO_EMPTY);
    }

    $res = [];

    $numChar = count($text1);
    $numEqual = 0;
    while (True) {

      if (count($text1) === 0) {
        foreach($text2 as $value) $res[] = "<ins>".$value."</ins>";
        break;
      }

      $val = $text1[0];
      $j = array_search($val, $text2);

      if($j === False) {
        $res[] = "<del>".$val."</del>";
      } else {
        if($j===0) {
          $res[] = $val;
          array_splice($text2, 0, 1);
          $numEqual +=1;
        } else {
          foreach(array_slice($text2, 0, $j) as $value) $res[] = "<ins>".$value."</ins>";
          $res[] = $text2[$j];
          array_splice($text2, 0, $j+1);
        }
      }
      array_splice($text1, 0, 1);
    }
    return ["eq" => $numEqual/$numChar, "diff" => implode($res,$spliter)];
  }

  public static function getCharDiff($a, $b)
  {
    return  self::getDiff($a, $b);
  }

  public static function getWordDiff($a, $b)
  {
    return  self::getDiff($a, $b, " ");
  }

  // returns "text diff" for two multiline strings - compares one line of text a time
  public static function getTextDiff($a, $b, $spliter = " ", $retEqual = False)
  {
    if (!is_string($a) || !is_string($a)) throw new \Exception('not a string');
    if ($a === $b) return False;
    $text1 = explode($spliter, $a);
    $text2 = explode($spliter, $b);
    $res = [];

    while(1) {
      if (count($text1) === 0) {
        foreach($text2 as $value) $res[] = "<ins>".$value."</ins>";
        break;
      }

      $maxEq = -1;
      $maxEqJ = -1;
      $maxEqDiff = [];
      for($j = 0; $j < count($text2); $j++) {
        $diff = self::getCharDiff($text1[0], $text2[$j]);

        if($diff["eq"] === 1) {
          $maxEq = 1;
          $maxEqJ = $j;
          $maxEqDiff = $text1[0];
          break;
        }

        if($diff["eq"] > 0.5 && $diff["eq"] > $maxEq) {
          $maxEq = $diff["eq"];
          $maxEqJ = $j;
          $maxEqDiff = $diff["diff"];
        }
      }

      // if ($maxEq <= 0) {
      //   $res[] = "<del>".$text1[0]."</del>";
      // } else {
      //   if ($maxEq === 1) {
      //     if($retEqual) $res[] = $maxEqDiff;
      //   } else $res[] = $maxEqDiff;
      //   array_splice($text2, $maxEqJ, 1);
      // }
      // echo ".............res ";
      // print_r($res);
      // echo "<br>";
      array_splice($text1, 0, 1);
    }
    return implode($res, $spliter);
  }

  // compute diff for arbytrary object
  public static function getObjectDiff($a, $b, $spliter = "\n", $retEqual = False)
  {
    $a = json_encode($a, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    $b = json_encode($b, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); //JSON_UNESCAPED_UNICODE|
    return mb_convert_encoding(self::getTextDiff($a, $b, $spliter, $retEqual), "UTF-8");
  }
  public static function formHtml($str)
  {
    return self::formatJson($str, True);
  }
  /**
   * Formats a JSON string for pretty printing
   *
   * @param string $json The JSON to make pretty
   * @param bool $html Insert nonbreaking spaces and <br />s for tabs and linebreaks
   * @return string The prettified output
   * @author Jay Roberts
   */
  private static function formatJson($json, $html = false) {
      $tabcount = 0;
      $result = '';
      $inquote = false;
      $ignorenext = false;
      if ($html) {
          $tab = "&nbsp;&nbsp;&nbsp;";
          $newline = "<br/>";
      } else {
          $tab = "\t";
          $newline = "\n";
      }
      for($i = 0; $i < strlen($json); $i++) {
          $char = $json[$i];
          if ($ignorenext) {
              $result .= $char;
              $ignorenext = false;
          } else {
              switch($char) {
                  case '{':
                      $tabcount++;
                      $result .= $char . $newline . str_repeat($tab, $tabcount);
                      break;
                  case '}':
                      $tabcount--;
                      $result = trim($result) . $newline . str_repeat($tab, $tabcount) . $char;
                      break;
                  case ',':
                      $result .= $char . $newline . str_repeat($tab, $tabcount);
                      break;
                  case '"':
                      $inquote = !$inquote;
                      $result .= $char;
                      break;
                  case '\\':
                      if ($inquote) $ignorenext = true;
                      $result .= $char;
                      break;
                  default:
                      $result .= $char;
              }
          }
      }
      return $result;
    }
}
