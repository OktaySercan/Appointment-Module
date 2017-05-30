<?php
require_once("DataLayer/DB.php");
require_once("SubBranch.php");

class SubBranchManager
{
    public static function getSubBranches(){
        $db=new DB();
        $result = $db->getDataTable("select name,ID,generalID from subbranch order by name");
        $subBranches = array();
        while($row=$result->fetch_assoc()){
            $subBranchObj = new SubBranch($row["generalID"],$row["name"], $row["ID"]);
            array_push($subBranches, $subBranchObj);
        }
        return $subBranches;
    }
    public static function getSubBranchID($name){
        $db=new DB();
        $result = $db->getDataTable("select name,ID,generalID from subbranch where name='$name'");
        $subBranches = array();
        while($row=$result->fetch_assoc()){
            $subBranchObj = new SubBranch($row["generalID"],$row["name"], $row["ID"]);
            array_push($subBranches, $subBranchObj);
        }
        return $subBranches;
    }
    public static function insertSubBranch($generalBranchName,$subBranchName){
        $db = new DB();
        $result = $db->executeQuery("INSERT INTO `subbranch`(`generalID`, `name`) VALUES ((SELECT ID FROM generalbranch WHERE name='$generalBranchName'),'$subBranchName')");
        return $result;
    }
    public static function getSubBranchNameWithID($subId){
        $db=new DB();
        $result = $db->getDataTable("select name,ID,generalID from subbranch where ID='$subId'");
        $subBranches = array();
        while($row=$result->fetch_assoc()){
            $subBranchObj = new SubBranch($row["generalID"],$row["name"], $row["ID"]);
            array_push($subBranches, $subBranchObj);
        }
        return $subBranches;
    }
}
?>