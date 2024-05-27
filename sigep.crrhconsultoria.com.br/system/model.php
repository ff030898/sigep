<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of model
 *
 * @author drc
 */
class Model {

    protected $db;
    public $_tabela;

    public function __construct() {

        $this->db = new PDO('mysql:host=localhost;dbname=diego970_crrh', 'diego970_crrh', 'RK+!mk)-dFaEHC)#0d', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

        $q = $this->db->query("SET NAMES 'utf8'");
        $q->setFetchMode(PDO::FETCH_ASSOC);
    }

    protected function insert(Array $dados, $lasId = null) {
        $valores = null;
        foreach (array_values($dados) as $key => $value) {
            $valores .= ($value != "" ? ", '" . $value . "'" : ", NULL");
        }
        $valores = substr($valores, 1);
        $campos = implode(", ", array_keys($dados));
        if (isset($lasId)) {
            $this->db->exec("INSERT INTO {$this->_tabela} ({$campos}) VALUES ({$valores})");
            $result = $this->db->lastInsertId($lasId);
            return $result;
        } else {
            $result = $this->db->exec("INSERT INTO {$this->_tabela} ({$campos}) VALUES ({$valores})");
            return $result;
        }
    }

    protected function read($where = null, $limit = null, $offset = null, $orderby = null, $campos = "*") {
        $where = ($where != null ? "WHERE {$where}" : null);
        $limit = ($limit != null ? "LIMIT {$limit}" : "");
        $offset = ($offset != null ? "OFFSET {$offset} " : "");
        $orderby = ($orderby != null ? "ORDER BY {$orderby} " : "");
//        print_r("SELECT {$campos} FROM {$this->_tabela} {$where} {$orderby} {$limit} {$offset}");
        $q = $this->db->query("SELECT {$campos} FROM {$this->_tabela} {$where} {$orderby} {$limit} {$offset}");
        $q->setFetchMode(PDO::FETCH_ASSOC);
        return $q->fetchAll();
    }

    protected function total($where = null) {
        $where = ($where != null ? "WHERE {$where}" : null);
        $q = $this->db->query("SELECT COUNT(*) AS count FROM {$this->_tabela} {$where}");
        $q->setFetchMode(PDO::FETCH_ASSOC);
        $result = $q->fetchAll();
        return $result[0]['count'];
    }

    protected function query($query) {
        try {
            $q = $this->db->query($query);
            $q->setFetchMode(PDO::FETCH_ASSOC);
            $result = $q->fetchAll();
            return $result;
        } catch (PDOException $err) {
            print_r($query . "\n<br />" . $err);
        }
    }

    protected function query2($sql) {
        try {
            $result = $this->db->exec($sql);
            return $result;
        } catch (PDOException $exc) {
            die($exc->getMessage() . "\n<br />" . $sql);
        }
    }

    protected function update(Array $dados, $where) {
        foreach ($dados as $inds => $vals) {
            $campos[] = ($vals != "" ? "{$inds} = '{$vals}'" : "{$inds} = NULL");
        }
        $campos = implode(", ", $campos);

        try {
            $result = $this->db->exec("UPDATE {$this->_tabela} SET {$campos} WHERE {$where}");
            return $result;
        } catch (PDOException $exc) {
            die($exc->getMessage() . "UPDATE {$this->_tabela} SET {$campos} WHERE {$where}");
        }
    }

    protected function delete($where) {
        return $this->db->exec("DELETE FROM {$this->_tabela} WHERE {$where}");
    }

    protected function log($tipo, $operacao) {
        $ll = new Log_Model();
        $ll->gravarLog($this->_tabela, $tipo, $operacao);
    }

    protected function search_restricao($search) {
        $result = $this->read("id LIKE '%{$search}%' OR nome LIKE '%{$search}%'");
        return $result;
    }

    protected function beginTransaction() {
        try {
            return $this->db->beginTransaction();
        } catch (PDOException $err) {
            print_r($err);
        }
    }

    protected function commit() {
        try {
            return $this->db->commit();
        } catch (PDOException $err) {
            print_r($err);
        }
    }

    protected function rollBack() {
        try {
            return $this->db->rollBack();
        } catch (PDOException $err) {
            print_r($err);
        }
    }

}
