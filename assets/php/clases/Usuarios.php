<?php
class Usuarios
{
    private $con;
    private $fondoInicial = 1000;

    function __construct()
    {
        include($_SERVER["DOCUMENT_ROOT"] . "/config/environment.php");
        include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/otros/con.php");
        $this->con = $con;
    }

    /**
     * Iniciar Sesión.
     *
     * @access public
     * @param array $post
     * @return array
     */
    public function iniciarSesion($post)
    {
        $usuario  = mysqli_real_escape_string($this->con, $post["txtUsuario"]);
        $password = mysqli_real_escape_string($this->con, $post["txtPassword"]);

        try {
            $query = "
            select
                *
            from
                tusuarios
            where
                usuario = '" . $usuario . "'
                and password = AES_ENCRYPT('" . $password . "', '" . SEED_CAJEROS . "')
                and status = 'A'
            limit 1
            ";

            $usuario = mysqli_fetch_assoc(mysqli_query($this->con, $query));

            if ($usuario["idusuario"] > 0) {
                $idusuario = $usuario["idusuario"];

                $query = "
                select
                    *
                from
                    tcortes
                where
                    status = 0
                limit 1
                ";

                $validaCorte = mysqli_num_rows(mysqli_query($this->con, $query));

                if ($validaCorte == 0) {
                    $fecha = date("Y-m-d");
                    $hora  = date("H:i:s");

                    $query = "
                    insert into
                        tcortes (idcorte, idusuario, fechainicio, horainicio, fondoinicial)
                    values
                        (null, '" . $idusuario . "', '" . $fecha . "', '" . $hora . "', '" . $this->fondoInicial . "')
                    ";

                    mysqli_query($this->con, $query);

                    $respuesta = array("success" => true, "tipo" => "href", "idusuario" => $idusuario);
                } else {
                    $query = "
                    select
                        *
                    from
                        tcortes
                    where
                        idusuario = '" . $idusuario . "'
                        and status = 0
                    limit 1
                    ";

                    $validaCajero = mysqli_num_rows(mysqli_query($this->con, $query));

                    if ($validaCajero == 1) {
                        $respuesta = array("success" => true, "tipo" => "href", "idusuario" => $idusuario);
                    } else {
                        $respuesta = array("success" => false, "message" => "Existe un corte iniciado por otro cajero.");
                    }
                }
            } else {
                $respuesta = array("success" => false, "message" => "Usuario o contraseña incorrectos.");
            }
        } catch (Exception $e) {
            $respuesta = array("success" => false, "message" => "Ocurrió un error inesperado. Intenta de nuevo.");
        } finally {
            return $respuesta;
        }
    }
}
