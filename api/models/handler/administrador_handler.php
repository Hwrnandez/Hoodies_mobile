<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');
/*
 *  Clase para manejar el comportamiento de los datos de la tabla empleado.
 */
class AdministradorHandler
{
    /*
     *  Declaración de atributos para el manejo de datos.
     */
    protected $id = null;
    protected $nombre = null;
    protected $apellido = null;
    protected $clave = null;
    protected $correo = null;
    protected $imagen = null;  

 
    /*
     *  Métodos para gestionar la cuenta del empleado.
     */
    public function checkUser($username, $password)
{
    $sql = 'SELECT id_administrador, correo_administrador, clave_administrador
            FROM administrador
            WHERE  correo_administrador = ?';
    $params = array($username);
    $data = Database::getRow($sql, $params);
    if ($data && password_verify($password, $data['clave_administrador'])) {
        $_SESSION['idAdministrador'] = $data['id_administrador'];
        $_SESSION['usuarioAdmin'] = $data['correo_administrador'];
        return true;
    } else {
        return false;
    }
}
 
 
    public function checkPassword($password)
    {
        $sql = 'SELECT clave_administrador
                FROM administrador
                WHERE id_administrador = ?';
        $params = array($_SESSION['idAdministrador']);
        $data = Database::getRow($sql, $params);
        // Se verifica si la contraseña coincide con el hash almacenado en la base de datos.
        if (password_verify($password, $data['clave_administrador'])) {
            return true;
        } else {
            return false;
        }
    }
 
 
    public function changePassword()
    {
        $sql = 'UPDATE empleado
                SET clave_empleado = ?
                WHERE id_administrador = ?';
        $params = array($this->clave, $_SESSION['idEmpleado']);
        return Database::executeRow($sql, $params);
    }
 
    public function readProfile()
    {
        $sql = 'SELECT id_administrador, nombre_empleado, apellido_empleado, telefono_empleado, dui_empleado, correo_empleado, clave_empleado
                FROM administrador
                WHERE id_administrador = ?';
        $params = array($_SESSION['idEmpleado']);
        return Database::getRow($sql, $params);
    }
 
 
 
    public function editProfile()
    {
        $sql = 'UPDATE administrador
                SET nombre_empleado = ?, apellido_empleado = ?, telefono_empleado = ?, dui_empleado = ?, correo_empleado = ?, clave_empleado = ?
                WHERE id_administrador = ?';
        $params = array($this->nombre, $this->apellido, $this->clave, $this->correo, $_SESSION['idEmpleado']);
        return Database::executeRow($sql, $params);
    }
 
    /*
     *  Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_administrador, nombre_administrador, apellido_administrador, correo_administrador, clave_administrador
                FROM administrador
                WHERE apellido_empleado LIKE ? OR nombre_empleado LIKE ?
                ORDER BY apellido_empleado';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }
 
    public function createRow()
    {
        $sql = 'INSERT INTO administrador(nombre_administrador, apellido_administrador, correo_administrador, clave_administrador)
                VALUES(?, ?, ?, ?)';
        $params = array($this->nombre, $this->apellido, $this->correo, $this->clave);
        return Database::executeRow($sql, $params);
    }
   
 
    public function readAll()
    {
        $sql = 'SELECT id_administrador, nombre_administrador, apellido_administrador, correo_administrador, clave_administrador
                FROM administrador
                ORDER BY apellido_administrador';
        return Database::getRows($sql);
    }
 
    public function readOne()
    {
        $sql = 'SELECT id_administrador, nombre_administrador, apellido_administrador, correo_administrador, clave_administrador
                FROM administrador
                WHERE id_administrador = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }
 
    public function updateRow()
    {
        $sql = 'UPDATE administrador
                SET nombre_administrador = ?, apellido_administrador = ?, correo_administrador = ?
                WHERE id_administrador = ?';
        $params = array($this->nombre, $this->apellido, $this->correo, $this->id);
        return Database::executeRow($sql, $params); 
    }
 
    public function deleteRow()
    {
        $sql = 'DELETE FROM administrador
                WHERE id_administrador = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
 
    public function checkDuplicate($value)
    {
        $sql = 'SELECT id_administrador
                FROM administrador
                WHERE dui_empleado = ? OR correo_empleado = ?';
        $params = array($value, $value);
        return Database::getRow($sql, $params);
    }
}