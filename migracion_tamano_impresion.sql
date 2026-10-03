-- Tamano de impresion del ticket por sucursal (72 mm o 58 mm).
--
-- Aplicar ANTES de subir el codigo del punto de venta y del panel de
-- administracion: ambos leen esta columna al imprimir.
--
-- Todas las sucursales existentes quedan en 72 mm, que es como imprimen hoy,
-- asi que aplicar esta migracion no cambia ningun ticket hasta que se configure
-- 58 mm desde Sucursales en el panel de administracion.

ALTER TABLE tsucursales
	ADD COLUMN ticket_tamanoimpresion TINYINT UNSIGNED NOT NULL DEFAULT 72 AFTER ticket_nombreimpresora;
