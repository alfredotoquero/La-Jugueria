-- Migración multisucursal: folios, carrito, fondo inicial
-- Ver plan completo en /Users/alfredotoquero/.claude/plans/transient-moseying-candy.md
--
-- Estado al 2026-08-06: los pasos 1-4 ya se ejecutaron correctamente en producción
-- (verificado leyendo el esquema real). Se dejan aquí comentados solo como registro/
-- referencia — NO volver a correrlos (fallarían por columnas/tabla ya existentes).
-- El paso 5 (tsucursales.fondoinicial) es el único pendiente de ejecutar.

-- ============================================================
-- YA APLICADO — no volver a ejecutar
-- ============================================================

-- -- 1. Carrito temporal: aislar por sucursal
-- ALTER TABLE trcuentaproductostmp
--   ADD COLUMN idsucursal INT UNSIGNED NOT NULL DEFAULT 1 AFTER idtmp,
--   ADD INDEX idx_sucursal (idsucursal);
-- ALTER TABLE trcuentaproductostmp ALTER COLUMN idsucursal DROP DEFAULT;

-- -- 2. Contador de folios por sucursal
-- CREATE TABLE tfolios (
--   idsucursal INT UNSIGNED NOT NULL,
--   ultimofolio INT NOT NULL DEFAULT 0,
--   PRIMARY KEY (idsucursal),
--   CONSTRAINT fk_tfolios_sucursal FOREIGN KEY (idsucursal) REFERENCES tsucursales(idsucursal)
-- ) ENGINE=InnoDB;
-- INSERT INTO tfolios (idsucursal, ultimofolio) SELECT idsucursal, 0 FROM tsucursales;

-- -- 3. tcuentas: folio por sucursal + idsucursal denormalizado (con backfill)
-- ALTER TABLE tcuentas
--   ADD COLUMN idsucursal INT UNSIGNED NULL AFTER idcorte,
--   ADD COLUMN folio INT NULL AFTER idsucursal;
-- UPDATE tcuentas tc
-- JOIN tcortes tco ON tco.idcorte = tc.idcorte
-- SET tc.idsucursal = tco.idsucursal, tc.folio = tc.idcuenta;
-- ALTER TABLE tcuentas
--   MODIFY idsucursal INT UNSIGNED NOT NULL,
--   MODIFY folio INT NOT NULL,
--   ADD UNIQUE KEY uq_sucursal_folio (idsucursal, folio);
-- UPDATE tfolios SET ultimofolio = (SELECT MAX(folio) FROM tcuentas WHERE idsucursal = 1) WHERE idsucursal = 1;

-- ============================================================
-- OMITIDO A PROPÓSITO — decisión tomada el 2026-08-06
-- ============================================================
-- El blindaje anti-doble-corte-abierto (columna virtual + índice único en tcortes)
-- no se puede aplicar porque tcortes usa el motor MyISAM, que no soporta índices
-- sobre columnas generadas virtuales. Se decidió NO convertir tcortes a InnoDB por
-- ahora y dejar la protección solo a nivel de código (como ya funcionaba antes de
-- esta migración). No se requiere ninguna acción aquí.
--
-- ALTER TABLE tcortes
--   ADD COLUMN idsucursal_abierto INT UNSIGNED
--     GENERATED ALWAYS AS (IF(status = 0, idsucursal, NULL)) VIRTUAL,
--   ADD UNIQUE KEY uq_sucursal_abierta (idsucursal_abierto);

-- ============================================================
-- PENDIENTE — ejecutar esto
-- ============================================================

-- 5. tsucursales: fondo inicial de caja configurable por sucursal
ALTER TABLE tsucursales
  ADD COLUMN fondoinicial DECIMAL(10,2) NOT NULL DEFAULT 1000.00 AFTER nombre;
