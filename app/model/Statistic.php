<?php

namespace app\model;

use app\core\Model;

class Statistic extends Model
{
  public function list() {
    // products statistic
    $products = $this->fetch("select sum(1) 'all',
      sum(if(country='sk',1,0)) sk,
      sum(if(country='cz',1,0)) cz  from products", []);
    $products_time = $this->fetchAll("select
      date_format(created_at, '%Y-%m') month,
      sum(if(country='sk',1,0)) sk,
      sum(if(country='cz',1,0)) cz
      from products group by date_format(created_at, '%Y-%m')
      order by date_format(created_at, '%Y-%m') limit 12");

    $stats['products_all'] = $products['all'];
    $stats['products_datasets_data_x'] = "['SK', 'CZ']";
    $stats['products_datasets_data_y'] = "[".$products['sk'].",".$products['cz']."]";

    $data = array_reduce($products_time, function ($c, $i) {
      $c['x'] = $c['x']."'".$i['month']."',";
      $c['sk_y'] = $c['sk_y'].$i['sk'].",";
      $c['cz_y'] = $c['cz_y'].$i['cz'].",";
      return $c;
    },['x'=>'','sk_y'=>'','cz_y'=>'']);

    $stats['products_time_datasets_data_x'] = "[".rtrim($data['x'],',')."]";
    $stats['products_time_datasets_data_sk_y'] = "[".rtrim($data['sk_y'],',')."]";
    $stats['products_time_datasets_data_cz_y'] = "[".rtrim($data['cz_y'],',')."]";

    // products tags
    $products_tags = $this->fetchAll("
      select x.name, sum(x.number) number from (
        select
          e.name, sum(1) number
        from
          products q, matching_tags w, tags e
        where
          q.id = w.product_id and w.tag_id = e.id and e.value is null
        group by e.name
        union
        select
          (select a.name from tags a where a.value = e.value and country=:cc) name,
          sum(1) number
        from
          products q, matching_tags w, tags e
        where
          q.id = w.product_id and w.tag_id = e.id
          and e.value is not null group by e.value
      ) x group by x.name order by number",
    ['cc'=>COUNTRY_CODE]);

    $data = array_reduce($products_tags, function ($c, $i) {
      $c['x'] = $c['x']."'".$i['name']."',";
      $c['y'] = $c['y'].$i['number'].",";
      return $c;
    },['x'=>'','y'=>'']);

    $stats['products_tag_datasets_data_x'] = "[".rtrim($data['x'],',')."]";
    $stats['products_tag_datasets_data_y'] = "[".rtrim($data['y'],',')."]";

    // products categories
    $categories_categories = $this->fetchAll("
      select x.name, sum(x.number) number from (
        select
          w.name, sum(1) number
        from
          products q, categories w
        where
          q.category_id = w.id and w.value is null group by w.name
        union
        select
          (select a.name from categories a where a.value = w.value and country=:cc) name,
          sum(1) number
        from
          products q, categories w
        where
          q.category_id = w.id and w.value is not null
        group by w.value
      ) x group by x.name order by number",
    ['cc'=>COUNTRY_CODE]);

    $data = array_reduce($categories_categories, function ($c, $i) {
      $c['x'] = $c['x']."'".$i['name']."',";
      $c['y'] = $c['y'].$i['number'].",";
      return $c;
    },['x'=>'','y'=>'']);

    $stats['products_categories_datasets_data_x'] = "[".rtrim($data['x'],',')."]";
    $stats['products_categories_datasets_data_y'] = "[".rtrim($data['y'],',')."]";

    // products supermarkets
    $products_supermarkets = $this->fetchAll("
      select x.name, sum(x.number) number from (
        select
          e.name, sum(1) number
        from
          products q, matching_supermarkets w, supermarkets e
        where
          q.id = w.product_id and w.supermarket_id = e.id and e.value is null
        group by e.name
        union
        select
          (select a.name from supermarkets a where a.value = e.value and country=:cc) name,
          sum(1) number
        from
          products q, matching_supermarkets w, supermarkets e
        where
          q.id = w.product_id and w.supermarket_id = e.id and e.value is not null
        group by e.value
      ) x group by x.name order by number",
    ['cc'=>COUNTRY_CODE]);

    $data = array_reduce($products_supermarkets, function ($c, $i) {
      $c['x'] = $c['x']."'".$i['name']."',";
      $c['y'] = $c['y'].$i['number'].",";
      return $c;
    },['x'=>'','y'=>'']);

    $stats['products_supermarkets_datasets_data_x'] = "[".rtrim($data['x'],',')."]";
    $stats['products_supermarkets_datasets_data_y'] = "[".rtrim($data['y'],',')."]";

    // users
    $users = $this->fetch("select sum(1) 'all',
      sum(if(country='sk',1,0)) sk,
      sum(if(country='cz',1,0)) cz  from users", []);
    $users_time = $this->fetchAll("select
      date_format(created_at, '%Y-%m') month,
      sum(if(country='sk',1,0)) sk,
      sum(if(country='cz',1,0)) cz
      from users group by date_format(created_at, '%Y-%m')
      order by date_format(created_at, '%Y-%m') limit 12");

    $stats['users_all'] = $users['all'];
    $stats['users_datasets_data_x'] = "['SK', 'CZ']";
    $stats['users_datasets_data_y'] = "[".$users['sk'].",".$users['cz']."]";

    $data = array_reduce($users_time, function ($c, $i) {
      $c['x'] = $c['x']."'".$i['month']."',";
      $c['sk_y'] = $c['sk_y'].$i['sk'].",";
      $c['cz_y'] = $c['cz_y'].$i['cz'].",";
      return $c;
    },['x'=>'','sk_y'=>'','cz_y'=>'']);

    $stats['users_time_datasets_data_x'] = "[".rtrim($data['x'],',')."]";
    $stats['users_time_datasets_data_sk_y'] = "[".rtrim($data['sk_y'],',')."]";
    $stats['users_time_datasets_data_cz_y'] = "[".rtrim($data['cz_y'],',')."]";

    // suggestions
    $suggestions = $this->fetch("select sum(1) 'all',
      sum(if(country='sk',1,0)) sk,
      sum(if(country='cz',1,0)) cz  from suggestions", []);
    $suggestions_time = $this->fetchAll("select
      date_format(created_at, '%Y-%m') month,
      sum(if(country='sk',1,0)) sk,
      sum(if(country='cz',1,0)) cz
      from suggestions group by date_format(created_at, '%Y-%m')
      order by date_format(created_at, '%Y-%m') limit 12");

    $stats['suggestions_all'] = $suggestions['all'];
    $stats['suggestions_datasets_data_x'] = "['SK', 'CZ']";
    $stats['suggestions_datasets_data_y'] = "[".$suggestions['sk'].",".$suggestions['cz']."]";

    $data = array_reduce($suggestions_time, function ($c, $i) {
      $c['x'] = $c['x']."'".$i['month']."',";
      $c['sk_y'] = $c['sk_y'].$i['sk'].",";
      $c['cz_y'] = $c['cz_y'].$i['cz'].",";
      return $c;
    },['x'=>'','sk_y'=>'','cz_y'=>'']);

    $stats['suggestions_time_datasets_data_x'] = "[".rtrim($data['x'],',')."]";
    $stats['suggestions_time_datasets_data_sk_y'] = "[".rtrim($data['sk_y'],',')."]";
    $stats['suggestions_time_datasets_data_cz_y'] = "[".rtrim($data['cz_y'],',')."]";

    return $stats;
  }
}
