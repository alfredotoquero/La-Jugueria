# Instalador de QZ Tray para La Jugueria

Aqui esta lo necesario para que las computadoras de las sucursales impriman **sin que salga
el dialogo de permiso**, sin configurar nada en cada maquina.

## Por que hace falta esto

El sistema firma digitalmente cada peticion de impresion (ver `assets/php/otros/qz-firma.php`).
Eso es indispensable, pero no basta: QZ Tray **no permite recordar la decision de "Allow" en una
conexion que no considera de confianza** — al marcar "Remember this decision" se apaga el boton.
Es una restriccion deliberada de QZ Tray, no un error de configuracion.

Para que la conexion sea de confianza, el certificado de La Jugueria tiene que estar instalado
como raiz confiable dentro de QZ Tray. La forma de lograrlo sin tocar cada computadora es
compilar QZ Tray con el certificado ya incluido: las sucursales instalan **ese** instalador en
lugar del oficial y no configuran nada.

## Que hay en cada carpeta

**`deploy/qz-tray/` (esta): lo que se necesita para compilar.** Va en git, pesa unos KB.

| Archivo | Que es |
|---|---|
| `lajugueria.crt` | Certificado publico. NO es secreto (la llave privada vive en `config/qz-llaves.php`, solo en el servidor). |
| `provision.json` | Le indica al instalador que confie en ese certificado. |

Los dos deben quedar **en la misma carpeta**: la ruta de `data` es relativa a `provision.json`.

**`descargas/` (raiz del proyecto): los instaladores ya compilados**, que es de donde los baja
la gente desde `instalar-qz.php`. Estan en `.gitignore` por su tamano y se suben por FTP.

Aqui no se guardan instaladores: el resultado de cada compilacion se copia directo a
`descargas/`, para no tener el mismo archivo de ~100 MB duplicado.

## Instaladores ya compilados

Los tres llevan el certificado incluido. No se versionan en git por su tamano (estan en
`.gitignore`); se suben por FTP a la carpeta `descargas/` del servidor, que es de donde los
ofrece `instalar-qz.php`.

| Archivo | Para | Peso | SHA-256 (inicio) |
|---|---|---|---|
| `qz-tray-2.2.6-x86_64.exe` | Windows | 98 MB | `e62dae2bd7e941e6...` |
| `qz-tray-2.2.6-arm64.pkg` | Mac con chip Apple | 95 MB | `0479a75f91607388...` |
| `qz-tray-2.2.6-x86_64.pkg` | Mac Intel | 99 MB | `8ef85ef3c38caf90...` |

**Los nombres importan**: `instalar-qz.php` los busca por nombre exacto. Si cambian, hay que
actualizar el arreglo `$instaladores` de esa pagina.

**Nunca mandar a la gente a descargar de qz.io.** El instalador oficial no lleva el certificado
y con el vuelve a salir el dialogo de permiso en cada impresion.

## Volver a compilarlo

Se hace desde macOS; NSIS genera el instalador de Windows.

```bash
# 1. Dependencias (una sola vez)
brew install git ant makeself nsis
#    Ademas un JDK 11 o superior (probado con openjdk@11 de Homebrew)
export JAVA_HOME="/opt/homebrew/opt/openjdk@11"
export PATH="$JAVA_HOME/bin:$PATH"

# 2. Codigo de QZ Tray, en la misma version que usa el sistema (assets/js/qz-tray.js)
git clone https://github.com/qzind/tray.git
cd tray
git checkout v2.2.6

# 3. Compilar con el certificado incluido (ruta ABSOLUTA al provision.json)
PROV=/ruta/completa/a/deploy/qz-tray/provision.json

ant -Dtarget.arch=x86_64  -Dprovision.file=$PROV nsis      # Windows
ant -Dtarget.arch=aarch64 -Dprovision.file=$PROV pkgbuild  # Mac con chip Apple
ant -Dtarget.arch=x86_64  -Dprovision.file=$PROV pkgbuild  # Mac Intel
```

**`-Dtarget.arch` no es opcional.** Sin ese parametro, el build toma la arquitectura de la Mac
donde se compila: en una Mac con chip M1/M2/M3 genera un instalador para Windows ARM64
(`qz-tray-2.2.6-arm64.exe`), que NO sirve en las computadoras normales de las sucursales.

**Cada build limpia `out/` antes de empezar**, asi que borra el instalador anterior. Hay que
copiar cada uno a `descargas/` antes de lanzar el siguiente.

Cada instalador queda en `out/` y tarda unos 2-4 minutos.

## Antes de repartirlo

Instalar en una maquina de prueba, entrar al sistema e imprimir un ticket: no debe salir ningun
dialogo de permiso, ni la primera vez.

**El sistema operativo va a mostrar una advertencia al instalar.** Es normal y no se puede
evitar sin comprar un certificado de firma de codigo: el instalador oficial de QZ esta firmado
por ellos, y estos, al compilarlos nosotros, quedan firmados con un certificado propio que el
sistema no conoce.

- Windows: "Windows protegio su PC" > Mas informacion > Ejecutar de todas formas.
- macOS: es mas estricto; hay que abrir el .pkg con clic derecho > Abrir, o autorizarlo en
  Ajustes del Sistema > Privacidad y seguridad. Evitarlo requiere notarizar el paquete con una
  cuenta de Apple Developer (99 USD al ano).

`instalar-qz.php` ya le muestra al usuario la advertencia que corresponde a su sistema.

## Alternativa rapida: instalaciones que ya existen

Para las computadoras que ya tienen QZ Tray instalado y no quieras reinstalar:

1. Crear la carpeta `C:\Program Files\QZ Tray\provision` (pide permisos de administrador).
2. Copiar ahi `provision.json` y `lajugueria.crt`.
3. Cerrar QZ Tray por completo (icono junto al reloj > Exit) y volver a abrirlo.

Usa los mismos dos archivos. Sirve para no esperar al instalador, aunque hay que hacerlo en
cada maquina.

## Mantenimiento

- El certificado **vence el 5 de agosto de 2036**. Al renovarlo hay que regenerar el par,
  actualizar `config/qz-llaves.php` en el servidor y recompilar el instalador.
- Para actualizar QZ Tray a una version nueva hay que repetir la compilacion; el instalador
  oficial no sirve, porque no lleva el certificado.
- Si la llave privada se filtra, regenerar el par de inmediato: cualquiera con esa llave puede
  mandar impresiones a las sucursales.

Comando para regenerar el par (10 anios de validez):

```bash
openssl req -x509 -newkey rsa:2048 -keyout private-key.pem -out lajugueria.crt \
  -days 3650 -nodes -subj "/CN=La Jugueria/O=La Jugueria/C=MX"
```

La llave privada va a `config/qz-llaves.php` (dentro de la variable `$QZ_LLAVE_PRIVADA`) y el
certificado reemplaza a `lajugueria.crt` y a `$QZ_CERTIFICADO`.
