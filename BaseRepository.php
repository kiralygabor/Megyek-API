<?php

namespace App\Repositories;

use App\Database\DB;

class BaseRepository extends DB
{

    protected $tableName;

    public function create(array $data): ?int
    {
        $sql = 'INSERT INTO stores (%s) VALUES (%s)';
        $fields = '';
        $values = '';
        foreach ($data as $field => $value) {
            if ($fields > '') {
                $fields .= ',' . $field;
            } else
                $fields .= $field;

            if ($values > '') {
                $values .= ',' . "'$value'";
            } else
                $values .= "'$value'";
        }
        $sql = sprintf($sql, $fields, $values);
        $this->mysqli->query($sql);

        $lastInserted = $this->mysqli->query("SELECT LAST_INSERT_ID() id;")->fetch_assoc();

        return $lastInserted['id'];
    }

    public function update(int $id, array $data)
    {
        $set = '';
        foreach ($data as $field => $value){
            if($set > ''){
                $set .= ", $field = '$value'";
            } else
                $set .= "$field = '$value'";
        }
        $query = "UPDATE `{$this->tableName}` SET %s WHERE id = $id;";
        $query = sprintf($query, $set);
        $this->mysqli->query($query);

        return $this->find($id);
    }

    public function getAll(): array
    {
        $query = $this->select() . "ORDER BY name";

        return $this->mysqli->query($query)->fetch_all(MYSQLI_ASSOC);
    }

    public function select(): string
    {
        return "SELECT * FROM `{$this->tableName}` ";
    }

    public function find($id): array
    {
        $query = "SELECT * FROM `{$this->tableName}` WHERE id = {$id}";
        return $this->mysqli->query($query)->fetch_all(MYSQLI_ASSOC);
    }

    public function delete(int $id)
    {
        $query = "DELETE * FROM `{$this->tableName}` WHERE id = $id";
        return $this->mysqli->query($query);
    }


}


?>
