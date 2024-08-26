<?php
namespace Core\Database;
use Exception;
class QueryBuilder
{

    protected $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }
    public function selectAll($table)
{
    $conn = $this->pdo;
    $conn->beginTransaction();

    try {
        $query = $conn->prepare("SELECT e.id_e, e.cedula, e.name, e.ape, e.email,e.id_rol, r.id_r, r.nombre, e.sexo, e.celu, e.fecha, e.dire FROM {$table} e JOIN rol r ON e.id_rol = r.id_r where e.estado=1");
        $query->execute();
        $result = $query->fetchAll(\PDO::FETCH_ASSOC);

        $conn->commit();
        return $result;
    } catch (Exception $e) {
        $conn->rollback();
        throw $e;
    }
}

public function selectAll2($table)
{
    $conn = $this->pdo;
    $conn->beginTransaction();

    try {
        $query = $conn->prepare("SELECT e.id_e,e.cedula, e.name,e.ape,e.email,e.id_rol, r.id_r,r.nombre,e.sexo,e.celu,e.fecha,e.dire FROM {$table} e JOIN rol r on e.id_rol=r.id_r where e.estado=0");
        $query->execute();
        $result = $query->fetchAll(\PDO::FETCH_ASSOC);

        $conn->commit();
        return $result;
    } catch (Exception $e) {
        $conn->rollback();
        throw $e;
    }
}

public function selectAll3($table)
{
    $conn = $this->pdo;
    $conn->beginTransaction();

    try {
        $query = $conn->prepare("SELECT e.id_e,e.cedula, e.name,e.ape,e.email,e.id_rol,r.id_r, r.nombre,e.sexo,e.celu,e.fecha,e.dire FROM {$table} e JOIN rol r on e.id_rol=r.id_r where e.estado = 1 
        AND r.id_r NOT IN (1, 2)");
        $query->execute();
        $result = $query->fetchAll(\PDO::FETCH_ASSOC);

        $conn->commit();
        return $result;
    } catch (Exception $e) {
        $conn->rollback();
        throw $e;
    }
}
public function selectAll4($table)
{
    $conn = $this->pdo;
    $conn->beginTransaction();
    try {
        $query = $conn->prepare("SELECT e.id_e, e.cedula, e.name, e.ape, e.email, e.id_rol, r.id_r, r.nombre, e.sexo, e.celu, e.fecha, e.dire 
                                  FROM {$table} e 
                                  JOIN rol r ON e.id_rol = r.id_r 
                                  WHERE e.estado = 0 AND r.id_r NOT IN (1, 2)");
    $query->execute();
    $result=$query->fetchAll(\PDO::FETCH_ASSOC);
    $conn->commit();
    return $result;
    } catch (Exception $e) {
        $conn->rollback();
        throw $e;
    }
}
public function selectAll5($table2)
{
    $conn = $this->pdo;
    $conn->beginTransaction();
    try {
        $query = $this->pdo->prepare("SELECT * FROM {$table2}");
        $query->execute();
        $result=$query->fetchAll(\PDO::FETCH_ASSOC);
       
        $conn->commit();
        return $result;
    } catch (Exception $e) {
        $conn->rollback();
        throw $e;
    }
}
public function selectAll6($table2)
{
    $conn = $this->pdo;
    $conn->beginTransaction();
    try {
        $query = $conn->prepare("SELECT * FROM {$table2} WHERE id_r NOT IN (1, 2)");
        $query->execute();
        $conn->commit();
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $conn->rollback();
        throw $e;
    }



    // $query = $this->pdo->prepare("SELECT * FROM {$table2} WHERE id_r NOT IN (1, 2)");
    // $query->execute();
    // return $query->fetchAll(\PDO::FETCH_ASSOC);
}
public function selectAll7($table)
{


    $conn = $this->pdo;
    $conn->beginTransaction();
    try {
        $query = $conn->prepare("SELECT e.id_e, e.cedula, e.name, e.ape, e.email, e.id_rol, r.id_r, r.nombre, e.sexo, e.celu, e.fecha, e.dire 
                                  FROM {$table} e 
                                  JOIN rol r ON e.id_rol = r.id_r 
                                  WHERE e.estado = 1");
    $query->execute();
    $conn->commit();
    return $query->fetchAll(\PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $conn->rollback();
        throw $e;
    }
    // $query = $this->pdo->prepare("SELECT e.id_e, e.cedula, e.name, e.ape, e.email, e.id_rol, r.id_r, r.nombre, e.sexo, e.celu, e.fecha, e.dire 
    //                               FROM {$table} e 
    //                               JOIN rol r ON e.id_rol = r.id_r 
    //                               WHERE e.estado = 1");
    // $query->execute();
    // return $query->fetchAll(\PDO::FETCH_ASSOC);
}
public function selectrole()
{
    $conn=$this->pdo;
    $conn->beginTransaction();
    try {
        $sql = "SELECT id_r, nombre FROM rol";
    $query = $this->pdo->prepare($sql);
    $query->execute();
    $conn->commit();
    return $query->fetchAll(\PDO::FETCH_OBJ);
    } catch (Exception $e) {
        $conn->rollback();
        throw $e;
    }
    // $sql = "SELECT id_r, nombre FROM rol";
    // $query = $this->pdo->prepare($sql);
    // $query->execute();
    // return $query->fetchAll(\PDO::FETCH_OBJ);
}
public function find($table, $id)
{
    $comm=$this->pdo;
    $comm->beginTransaction();
    try {
        $query = $this->pdo->prepare("SELECT * FROM {$table} WHERE id_e = :id LIMIT 1");
        $query->bindParam(':id', $id, \PDO::PARAM_INT);
        $query->execute();
        $comm->commit();
        return $query->fetch(\PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $comm->rollback();
        throw $e;
    }

    // $query = $this->pdo->prepare("SELECT * FROM {$table} WHERE id_e = :id LIMIT 1");
    // $query->bindParam(':id', $id, \PDO::PARAM_INT);
    // $query->execute();
    // return $query->fetch(\PDO::FETCH_ASSOC);
}


// Ejemplo para el método findBy
public function findBy($table, $params)
{
    $conn = $this->pdo;
    $conn->beginTransaction();

    try {
        $cols = array_keys($params);
        $cols = implode(' AND ', array_map(function ($col) {
            return "{$col}=:{$col}";
        }, $cols));
        $query = $conn->prepare("SELECT * FROM {$table} WHERE {$cols}");
        $query->execute($params);
        $result = $query->fetchAll(\PDO::FETCH_ASSOC);

        $conn->commit();
        return $result;
    } catch (Exception $e) {
        $conn->rollback();
        throw $e;
    }
}

public function create($table, $params)
{
    $conn = $this->pdo;
    $conn->beginTransaction();

    try {
        $cols = implode(', ', array_keys($params));
        $placeholders = ':' . implode(', :', array_keys($params));
        $sql = "INSERT INTO {$table} ({$cols}) VALUES ({$placeholders})";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        
        $conn->commit();
    } catch (\PDOException $ERROR) {
        $conn->rollback();
        die($ERROR->getMessage());
    }
}

public function update($table, $id, $params)
{
    $conn = $this->pdo;
    $conn->beginTransaction();

    try {
        if (isset($params['cedula'])) {
            $cedula = $params['cedula'];
            $checkSql = "SELECT COUNT(*) FROM {$table} WHERE cedula = '{$cedula}' AND id_e != {$id}";
            $result = $conn->query($checkSql);
            $count = $result->fetchColumn();
            
            if ($count > 0) {
                $mensaje = "Cédula {$cedula} ya registrada.";
                header("Location: index.php?url=tables&mensaje=" . urlencode($mensaje));
                exit();
            }
        }

        $cols = implode(', ', array_map(function($key, $value) {
            return "{$key}='{$value}'";
        }, array_keys($params), $params));

        $sql = "UPDATE {$table} SET {$cols} WHERE id_e={$id}";
        $conn->query($sql);

        $conn->commit();
    } catch (\PDOException $ERROR) {
        $conn->rollback();
        die($ERROR->getMessage());
    }
}

public function delete($table, $id)
{
    $conn = $this->pdo;
    $conn->beginTransaction();

    try {
        $sql = "UPDATE {$table} SET estado= 0 WHERE id_e=:id";
        $query = $conn->prepare($sql);
        $query->execute(['id' => $id]);

        $conn->commit();
    } catch (\PDOException $ERROR) {
        $conn->rollback();
        die($ERROR->getMessage());
    }
}

public function delete2($table, $id)
{
    $conn = $this->pdo;
    $conn->beginTransaction();

    try {
        $sql = "UPDATE {$table} SET estado= 1 WHERE id_e=:id";
        $query = $conn->prepare($sql);
        $query->execute(['id' => $id]);

        $conn->commit();
    } catch (\PDOException $ERROR) {
        $conn->rollback();
        die($ERROR->getMessage());
    }
}
public function delete3($table, $id)
{
    $conn = $this->pdo;
    $conn->beginTransaction();

    try {
        $sql = "UPDATE {$table} SET estado = 0 WHERE id_e = :id";
        $query = $conn->prepare($sql);
        $query->execute(['id' => $id]);

        $conn->commit();
    } catch (\PDOException $ERROR) {
        $conn->rollback();
        die($ERROR->getMessage());
    }
}

public function delete4($table, $id)
{
    $conn = $this->pdo;
    $conn->beginTransaction();

    try {
        $sql = "UPDATE {$table} SET estado = 1 WHERE id_e = :id";
        $query = $conn->prepare($sql);
        $query->execute(['id' => $id]);

        $conn->commit();
    } catch (\PDOException $ERROR) {
        $conn->rollback();
        die($ERROR->getMessage());
    }
}
}