<?php

namespace DataTables\Adapters;

abstract class AdapterInterface {

  protected $parser = null;
  protected $columns = null;
  protected $options = [];

  public function __construct($options) {
    $this->options = $options;
  }

  abstract public function getResponse();

  public function setParser($parser) {
    $this->parser = $parser;
  }

  public function setColumns($columns) {
    $this->columns = $columns;
  }

  public function columnExists($column) {
    return in_array($column, $this->columns);
  }

  public function formResponse($options) {
    $defaults = [
      'total'     => 0,
      'filtered'  => 0,
      'data'      => []
    ];
    $options += $defaults;

    $response = [];
    $response['draw'] = $this->parser->getDraw();
    $response['recordsTotal'] = $options['total'];
    $response['recordsFiltered'] = $options['filtered'];
    foreach($options['data'] as $item) {
      if (isset($item['id'])) {
        $item['DT_RowId'] = $item['id'];
      }

      $response['data'][] = $item;
    }

    return $response;
  }

  public function sanitaze($string) {
    return mb_substr($string, 0, $this->options['length']);
  }

  public function bind($case, $closure) {
    switch($case) {
      case "global_search":
        $search = $this->parser->getSearchValue();
        if (!mb_strlen($search)) return;

        foreach($this->parser->getSearchableColumns() as $column) {
          if (!$this->columnExists($column)) continue;
          $closure($column, $this->sanitaze($search));
        }
        break;
      case "column_search":
        $columnSearch = $this->parser->getColumnsSearch();
        if (!$columnSearch) return;

        foreach($columnSearch as $key => $column) {
          if (!$this->columnExists($column['data'])) continue;
          $closure($column['data'], $this->sanitaze($column['search']['value']));
        }
        break;
      case "order":
        $order = $this->parser->getOrder();
        if (!$order) return;

        $orderArray = [];

        foreach($order as $columnId=>$orderBy) {
          $orderDir = $orderBy['dir'];

          $column = $this->parser->getColumnById($columnId);
          if (is_null($column) || !$this->columnExists($column)) continue;

          $orderArray[] = "{$column} {$orderDir}";
        }

        $closure($orderArray);
        break;
      default:
        throw new \Exception('Unknown bind type');
        break;
    }

  }

}
