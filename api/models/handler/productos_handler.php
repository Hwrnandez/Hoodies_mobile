<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');
/*
*	Clase para manejar el comportamiento de los datos de la tabla PRODUCTO.
*/
class ProductoHandler
{
    /*
    *   Declaración de atributos para el manejo de datos.
    */
    protected $id = null;
    protected $nombre = null;
    protected $descripcion = null;
    protected $precio = null;
    protected $existencias = null;
    protected $imagen = null;
    protected $marca = null;
    protected $categoria = null;
    protected $estado = null;

    // Constante para establecer la ruta de las imágenes.
    const RUTA_IMAGEN = '../../images/productos/';

    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
    */
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_producto, imagen_producto, nombre_producto, descripcion_producto, precio_producto, nombre_categoria, nombre_marca, estado_producto
                FROM producto
                INNER JOIN categoria USING(id_categoria_hoodie)
                INNER JOIN marca USING(id_marca)
                WHERE nombre_producto LIKE ? OR descripcion_producto LIKE ?
                ORDER BY nombre_producto';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO producto(nombre_producto, descripcion_producto, precio_producto, existencia_producto, imagen_producto, estado_producto, id_categoria_hoodie, id_marca, id_administrador)
                VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $params = array($this->nombre, $this->descripcion, $this->precio, $this->existencias, $this->imagen, $this->estado, $this->categoria, $this->marca, $_SESSION['idAdministrador']);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_producto, nombre_producto, descripcion_producto, precio_producto, existencia_producto, imagen_producto, estado_producto, nombre_categoria, nombre_marca
                FROM producto
                INNER JOIN categoria USING(id_categoria_hoodie)
                INNER JOIN marca USING(id_marca)
                ORDER BY nombre_producto';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = 'SELECT id_producto, nombre_categoria, nombre_marca, nombre_producto, descripcion_producto, precio_producto,  estado_producto, imagen_producto, existencia_producto
                FROM producto
                INNER JOIN categoria USING(id_categoria_hoodie) 
                INNER JOIN marca USING(id_marca)
                WHERE id_producto = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function readFilename()
    {
        $sql = 'SELECT imagen_producto
                FROM producto
                WHERE id_producto = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE producto
                SET imagen_producto = ?, nombre_producto = ?, descripcion_producto = ?, precio_producto = ?, existencia_producto = ?, estado_producto = ?, id_categoria_hoodie = ?, id_marca = ?
                WHERE id_producto = ?';
        $params = array($this->imagen, $this->nombre, $this->descripcion, $this->precio, $this->existencias, $this->estado, $this->categoria, $this->marca, $this->id);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM producto
                WHERE id_producto = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    public function readProductosCategoria()
    {
        $sql = 'SELECT id_producto, imagen_producto, nombre_producto, descripcion_producto, precio_producto, existencia_producto
        FROM producto
        INNER JOIN categoria USING(id_categoria_hoodie)
        WHERE id_categoria_hoodie = ? AND estado_producto = true
        ORDER BY nombre_producto';
        $params = array($this->categoria);
        return Database::getRows($sql, $params);
    }

    public function readProductosMarca()
    {
        $sql = 'SELECT id_producto, imagen_producto, nombre_producto, descripcion_producto, precio_producto, existencias_producto
                FROM producto
                INNER JOIN marca USING(id_marca)
                WHERE id_marca = ? AND estado_producto = true
                ORDER BY nombre_producto';
        $params = array($this->marca);
        return Database::getRows($sql, $params);
    }

    /*
    *   Métodos para generar gráficos.
    */
    public function cantidadProductosCategoria()
    {
        $sql = 'SELECT nombre_categoria, COUNT(id_producto) cantidad
                FROM producto
                INNER JOIN categoria USING(id_categoria)
                GROUP BY nombre_categoria ORDER BY cantidad DESC LIMIT 5';
        return Database::getRows($sql);
    }

    public function porcentajeProductosCategoria()
    {
        $sql = 'SELECT nombre_categoria, ROUND((COUNT(id_producto) * 100.0 / (SELECT COUNT(id_producto) FROM productos)), 2) porcentaje
                FROM producto
                INNER JOIN categoria USING(id_categoria)
                GROUP BY nombre_categoria ORDER BY porcentaje DESC';
        return Database::getRows($sql);
    }

    /*
    *   Métodos para generar reportes.
    */
    public function productosCategoria()
    {
        $sql = 'SELECT nombre_producto, precio_producto, estado_producto
                FROM product
                INNER JOIN categoria USING(id_categoria)
                WHERE id_categoria = ?
                ORDER BY nombre_producto';
        $params = array($this->categoria);
        return Database::getRows($sql, $params);
    }
}