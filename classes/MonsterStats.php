<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MonsterStats
 *
 * @author matanasio
 */
class MonsterStats {

    private $db;
    private $stats = [];

    function __construct($db) {
        $this->db = $db;
    }
    
    function getMonsterStat($type,$subtype){
        $sql = "select * from mr_stat where type1 = '$type' and type2 = '$subtype'";
        $this->stats = [];
        foreach($this->db->query($sql) as $row){
            $this->stats[] = $row;
        }
        return $this->stats;
    }
            

}
