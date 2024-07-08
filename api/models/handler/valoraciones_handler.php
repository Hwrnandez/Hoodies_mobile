<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');
/*
*	Clase para manejar el comportamiento de los datos de la tabla CLIENTE.
*/
class ValoracionHandler
{
    /*
    *   Declaración de atributos para el manejo de datos.
    */
    protected $id = null;
    protected $cliente = null;
    protected $producto = null;
    protected $descripcion = null;
    protected $fecha = null; 
    protected $estado = null;

    public function changeStatus()
    {
        $sql = 'UPDATE valoracion
                SET estado_comentario = ?
                WHERE id_comentario = ?';
        $params = array($this->estado, $this->id);
        return Database::executeRow($sql, $params);
    }

    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
    */
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_comentario, descripcion_comentario, puntuacion, fecha_comentario, nombre_cliente, nombre_producto
                FROM valoracion
                INNER JOIN cliente USING(id_cliente)
                INNER JOIN producto USING(id_producto)
                WHERE nombre_cliente LIKE ?
                ORDER BY descripcion_comentario';
        $params = array($value);
        return Database::getRows($sql, $params);
    }


    public function readAll()
    {
        $sql = 'SELECT id_comentario, descripcion_comentario, puntuacion, fecha_comentario, estado_comentario, nombre_cliente, nombre_producto
                FROM valoracion
                INNER JOIN cliente USING(id_cliente)
                INNER JOIN producto USING(id_producto)
                ORDER BY descripcion_comentario';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = 'SELECT id_comentario, descripcion_comentario, puntuacion, fecha_comentario, estado_comentario, id_cliente, id_producto
                FROM valoracion
                WHERE id_comentario = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE valoracion
                SET estado_comentario = ?
                WHERE id_comentario = ?';
        $params = array($this->estado, $this->id);
        return Database::executeRow($sql, $params);
    }

    public function readvaloracionCliente()
    {
        $sql = 'SELECT id_comentario, descripcion_comentario, fecha_comentario
                FROM valoracion
                INNER JOIN cliente USING(id_cliente)
                WHERE id_cliente = ? AND estado_comentario = true
                ORDER BY fecha_comentario';
        $params = array($this->cliente);
        return Database::getRows($sql, $params);
    }

    public function readvaloracionProducto()
    {
        $sql = 'SELECT id_comentario, descripcion_comentario, fecha_comentario
                FROM valoracion
                INNER JOIN producto USING(id_producto)
                WHERE id_producto = ? AND estado_comentario = true
                ORDER BY fecha_comentario';
        $params = array($this->producto);
        return Database::getRows($sql, $params);
    }
}