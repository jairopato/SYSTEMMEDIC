<?php
class AESStable {
    private $key;
    private $method;
    private $iv;

    public function __construct($key, $method = 'AES-256-CBC') {
        // Configura la clave y el método de cifrado
        $this->key = substr(hash('sha256', $key, true), 0, 32); // Deriva una clave válida
        $this->method = $method;
        $this->iv = str_repeat("\0", openssl_cipher_iv_length($this->method)); // IV fijo de ceros
    }

    public function encrypt($data) {
        // Cifra los datos y los devuelve en Base64
        $encrypted = openssl_encrypt($data, $this->method, $this->key, OPENSSL_RAW_DATA, $this->iv);
        return base64_encode($encrypted);
    }

    public function decrypt($encryptedData) {
        // Descifra los datos cifrados en Base64
        $encryptedData = base64_decode($encryptedData);
        return openssl_decrypt($encryptedData, $this->method, $this->key, OPENSSL_RAW_DATA, $this->iv);
    }
}

// Ejemplo de uso
$key = "clave_de_32_caracteres_para_aes1";
//$key = "jairopatriciohuaracasamaniego202";

// Crear una instancia de la clase
$aes = new AESStable($key);

$texto = "1987541258";

// Medir el tiempo de cifrado
$startEncrypt = microtime(true);
$cifrado = $aes->encrypt($texto);
$endEncrypt = microtime(true);

$encryptTime = $endEncrypt - $startEncrypt;

// Mostrar el resultado del cifrado y el tiempo
echo "Texto cifrado: " . $cifrado . PHP_EOL;
echo "<br>";
echo "Tiempo de cifrado: " . $encryptTime . " segundos" . PHP_EOL;
echo "<br>";

// Medir el tiempo de descifrado
$startDecrypt = microtime(true);
$descifrado = $aes->decrypt($cifrado);
$endDecrypt = microtime(true);

$decryptTime = $endDecrypt - $startDecrypt;

// Mostrar el resultado del descifrado y el tiempo
echo "Texto descifrado: " . $descifrado . PHP_EOL;
echo "<br>";
echo "Tiempo de descifrado: " . $decryptTime . " segundos" . PHP_EOL;


/*/ Cifrar el texto
$cifrado = $aes->encrypt($texto);
echo "Texto cifrado: " . $cifrado . PHP_EOL;
echo "<br>";
// Descifrar el texto
$descifrado = $aes->decrypt($cifrado);
echo "Texto descifrado: " . $descifrado . PHP_EOL;
*/
?>