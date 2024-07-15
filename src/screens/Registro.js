import React, { useState } from 'react';
import { StyleSheet, Text, TextInput, View, TouchableOpacity, ScrollView, Image, ImageBackground, Alert } from 'react-native';
import AwesomeAlert from 'react-native-awesome-alerts';
import * as Constantes from '../../src/utils/Constantes';

// Componente principal de registro.
export default function Registro({ navigation }) {
    // Obtención de la IP del servidor desde el archivo de constantes.
    const ip = Constantes.IP;

    // Estados para almacenar los datos del formulario.
    const [nombre, setNombre] = useState('');
    const [apellido, setApellido] = useState('');
    const [direccion, setDireccion] = useState('');
    const [telefono, setTelefono] = useState('');
    const [email, setEmail] = useState('');
    const [clave, setClave] = useState('');
    const [confirmarClave, setConfirmarClave] = useState('');
    const [showAlert, setShowAlert] = useState(false);// Estado para manejar la visibilidad de la alerta.
    const [alertMessage, setAlertMessage] = useState('');// Estado para manejar el mensaje de la alerta.
    const [isRegistering, setIsRegistering] = useState(false);// Estado para indicar si se está registrando.

    // Expresión regular para validar el formato del teléfono (####-####).
    const telefonoRegex = /^\d{4}-\d{4}$/;

    // Función para manejar el cambio de texto en el campo de teléfono.
    const handleTextChange = (text) => {
        let formatted = text.replace(/[^\d]/g, ''); // Elimina todos los caracteres no numéricos.
        if (formatted.length > 8) {
            formatted = formatted.slice(0, 8); // Limita a 8 dígitos.
        }
        if (formatted.length > 4) {
            formatted = formatted.slice(0, 4) + '-' + formatted.slice(4);
        }
        setTelefono(formatted); // Actualiza el estado con el valor formateado.
    };
    // Función para mostrar una alerta con un mensaje específico.
    const showAlertWithMessage = (message) => {
        setAlertMessage(message);  // Establece el mensaje de la alerta.
        setShowAlert(true); // Muestra la alerta.
    };

    // Función asíncrona para manejar la creación de una cuenta.
    const handleCreate = async () => {
        // Validaciones de los campos del formulario.
        if (!nombre.trim() || !direccion.trim() || !telefono.trim() ||
            !email.trim() || !clave.trim() || !confirmarClave.trim()) {
            showAlertWithMessage("Debes llenar todos los campos");
            return;
        } else if (!telefonoRegex.test(telefono)) {
            showAlertWithMessage("El teléfono debe tener el formato correcto (####-####)");
            return;
        }

        setIsRegistering(true);

        try {
            // Creación de un objeto FormData para enviar los datos del formulario.
            const formData = new FormData();
            formData.append('nombreCliente', nombre);
            formData.append('apellidoCliente', apellido);
            formData.append('direccionCliente', direccion);
            formData.append('telefonoCliente', telefono);
            formData.append('correoCliente', email);
            formData.append('claveCliente', clave);
            formData.append('confirmarClave', confirmarClave);
            // Envío de los datos del formulario al servidor.
            const response = await fetch(`${ip}/services/public/clientes.php?action=signUp`, {
                method: 'POST',
                body: formData
            });
            const data = await response.json();
            if (data.status) {
                Alert('Cuenta registrada correctamente');
                setTimeout(() => {
                    Alert(false);
                    navigation.navigate('Login'); // Redirección a la pantalla de inicio de sesión después de 2 segundos.
                }, 2000);
            } else {
                showAlertWithMessage(data.error);
            }
        } catch (error) {
            Alert('Ocurrió un problema al registrar la cuenta');
        } finally {
            setIsRegistering(false); // Indica que se ha terminado el registro.
        }

        return (
            <ScrollView contentContainerStyle={styles.container}>
                <Text style={styles.title}>Bienvenido</Text>
                <Text style={styles.subtitle}>Registra tu cuenta para ver nuestros productos</Text>

                <TextInput
                    style={styles.input}
                    placeholder="Nombre"
                    value={nombre}
                    onChangeText={setNombre}
                />
                <TextInput
                    style={styles.input}
                    placeholder="Apellido"
                    value={apellido}
                    onChangeText={setApellido}
                />-
                <TextInput
                    style={styles.input}
                    placeholder="Teléfono"
                    value={telefono}
                    onChangeText={setTelefono}
                />
                <TextInput
                    style={styles.input}
                    placeholder="Dirección"
                    value={direccion}
                    onChangeText={setDireccion}
                />
                <TextInput
                    style={styles.input}
                    placeholder="Correo electrónico"
                    value={email}
                    onChangeText={setEmail}
                    keyboardType="email-address"
                />
                <TextInput
                    style={styles.input}
                    placeholder="Escribe una contraseña"
                    value={clave}
                    onChangeText={setClave}
                    secureTextEntry
                />
                <TextInput
                    style={styles.input}
                    placeholder="Confirma tu contraseña"
                    value={confirmarClave}
                    onChangeText={setConfirmPassword}
                    secureTextEntry
                />
                <View
                    style={styles.buttonContainer}
                >
                    <TouchableOpacity style={styles.button}>
                        <Text style={styles.buttonText}>REGISTRARSE</Text>
                    </TouchableOpacity>
                    <AwesomeAlert
                        show={showAlert}
                        showProgress={isRegistering}
                        title={isRegistering ? "Registrando" : "Mensaje"}
                        message={alertMessage}
                        closeOnTouchOutside={!isRegistering}
                        closeOnHardwareBackPress={!isRegistering}
                        showCancelButton={false}
                        showConfirmButton={!isRegistering}
                        confirmText="OK"
                        confirmButtonColor="gray"
                        onConfirmPressed={() => {
                            setShowAlert(false);
                        }}
                        contentContainerStyle={styles.alertContentContainer}
                        titleStyle={styles.alertTitle}
                        messageStyle={styles.alertMessage}
                        confirmButtonStyle={styles.alertConfirmButton}
                        confirmButtonTextStyle={styles.alertConfirmButtonText}
                    />
                </View>



            </ScrollView>
        );
    }

    const styles = StyleSheet.create({
        container: {
            flexGrow: 1,
            padding: 20,
            justifyContent: 'center',
            backgroundColor: '#fff',
        },
        title: {
            fontSize: 32,
            fontWeight: 'bold',
            marginBottom: 10,
        },
        button: {
            width: '100%',
            height: 50,
            backgroundColor: '#000',
            alignItems: 'center',
            justifyContent: 'center',
            marginTop: 10,
            borderRadius: 10,
        },
        buttonText: {
            color: '#fff',
            fontSize: 18,
            fontWeight: 'bold',
        },
        subtitle: {
            fontSize: 16,
            marginBottom: 20,
        },
        input: {
            height: 40,
            borderColor: '#000',
            borderWidth: 1,
            marginBottom: 10,
            paddingHorizontal: 10,
        },
        buttonContainer: {
            justifyContent: 'center',
            alignItems: 'center',
        }
    });
};