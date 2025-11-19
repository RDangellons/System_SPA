-- =====================================================
--  BASE DE DATOS SPA MAMÁ
--  Estructura inicial
-- =====================================================

-- (Opcional) Eliminar BD si existe
-- DROP DATABASE IF EXISTS spa_mama;

CREATE DATABASE IF NOT EXISTS spa_mama
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE spa_mama;

-- =====================================================
--  TABLA: usuarios
--  Cuentas de acceso (admin + clientas con perfil)
-- =====================================================

CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  telefono VARCHAR(20) DEFAULT NULL,
  password_hash VARCHAR(255) NOT NULL,
  rol ENUM('admin','cliente') NOT NULL DEFAULT 'cliente',
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  ultimo_acceso TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
--  TABLA: clientes
--  Toda persona que agenda cita (con o sin usuario)
-- =====================================================

CREATE TABLE clientes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT DEFAULT NULL,
  nombre VARCHAR(150) NOT NULL,
  telefono VARCHAR(20) NOT NULL,
  email VARCHAR(150) DEFAULT NULL,
  fecha_nacimiento DATE DEFAULT NULL,
  como_se_entero VARCHAR(100) DEFAULT NULL,   -- redes, recomendación, etc.
  primera_visita DATE DEFAULT NULL,
  activo TINYINT(1) DEFAULT 1,

  CONSTRAINT fk_clientes_usuario
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
    ON DELETE SET NULL
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
--  TABLA: servicios
--  Catálogo de servicios del spa
-- =====================================================

CREATE TABLE servicios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(120) NOT NULL,
  descripcion TEXT,
  duracion_min INT NOT NULL,             -- duración en minutos
  precio DECIMAL(10,2) NOT NULL,
  imagen VARCHAR(255) DEFAULT NULL,      -- ruta de la imagen
  es_destacado TINYINT(1) DEFAULT 0,
  activo TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
--  TABLA: cuestionarios_salud
--  Cuestionarios médicos de cada clienta
-- =====================================================

CREATE TABLE cuestionarios_salud (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cliente_id INT NOT NULL,
  fecha_registro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

  enfermedad_cardiaca TINYINT(1) DEFAULT 0,
  presion_alta_baja TINYINT(1) DEFAULT 0,
  trombosis_embolia TINYINT(1) DEFAULT 0,
  embarazo TINYINT(1) DEFAULT 0,
  problemas_columna TINYINT(1) DEFAULT 0,
  cirugia_reciente TINYINT(1) DEFAULT 0,
  cirugia_detalle VARCHAR(255) DEFAULT NULL,
  problemas_piel TINYINT(1) DEFAULT 0,
  varices_severas TINYINT(1) DEFAULT 0,
  diabetes TINYINT(1) DEFAULT 0,
  anticoagulantes TINYINT(1) DEFAULT 0,
  alergias_productos TINYINT(1) DEFAULT 0,
  alergias_detalle VARCHAR(255) DEFAULT NULL,
  mareos_convulsiones TINYINT(1) DEFAULT 0,
  otra_condicion TINYINT(1) DEFAULT 0,
  otra_detalle VARCHAR(255) DEFAULT NULL,

  apto ENUM('apto','con_cuidados','no_apto') DEFAULT 'apto',
  notas_internas TEXT NULL,

  CONSTRAINT fk_cuestionarios_cliente
    FOREIGN KEY (cliente_id) REFERENCES clientes(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Índice útil para consultar el último cuestionario de una clienta
CREATE INDEX idx_cuestionarios_cliente_fecha
  ON cuestionarios_salud (cliente_id, fecha_registro);

-- =====================================================
--  TABLA: promociones (opcional, pero ya lista)
-- =====================================================

CREATE TABLE promociones (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(150) NOT NULL,
  descripcion TEXT,
  imagen VARCHAR(255) DEFAULT NULL,
  descuento_porcentaje INT DEFAULT NULL,   -- ej: 20 = 20%
  precio_especial DECIMAL(10,2) DEFAULT NULL,
  fecha_inicio DATE DEFAULT NULL,
  fecha_fin DATE DEFAULT NULL,
  activo TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
--  TABLA: ajustes
--  Configuración general del sistema
-- =====================================================

CREATE TABLE ajustes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  clave VARCHAR(100) NOT NULL UNIQUE,
  valor TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Ejemplos que luego puedes insertar:
-- INSERT INTO ajustes (clave, valor) VALUES
-- ('spa_nombre', 'Spa Mamá'),
-- ('spa_telefono', '55 0000 0000'),
-- ('spa_whatsapp_url', 'https://wa.me/52XXXXXXXXXX'),
-- ('spa_direccion', 'Dirección del spa'),
-- ('banco_nombre', 'Banco X'),
-- ('banco_titular', 'Nombre Mamá'),
-- ('banco_clabe', '000000000000000000'),
-- ('banco_cuenta', '0000000000');

-- =====================================================
--  TABLA: galeria (fotos del spa, antes/después, etc.)
-- =====================================================

CREATE TABLE galeria (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(150) DEFAULT NULL,
  descripcion TEXT,
  imagen VARCHAR(255) NOT NULL,          -- ruta al archivo
  tipo ENUM('spa','antes_despues','promo','otro')
       DEFAULT 'spa',
  activo TINYINT(1) DEFAULT 1,
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
--  TABLA: citas
--  Citas de servicios del spa
-- =====================================================

CREATE TABLE citas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cliente_id INT NOT NULL,
  servicio_id INT NOT NULL,
  cuestionario_id INT DEFAULT NULL,    -- cuestionario usado para esta cita

  fecha DATE NOT NULL,
  hora TIME NOT NULL,

  estado ENUM(
      'pendiente',
      'confirmada',
      'en_cabina',
      'realizada',
      'no_asistio',
      'cancelada'
  ) NOT NULL DEFAULT 'pendiente',

  origen ENUM('invitado','usuario','admin')
         NOT NULL DEFAULT 'invitado',

  total_cita DECIMAL(10,2) DEFAULT NULL,
  saldo_pendiente DECIMAL(10,2) DEFAULT NULL,

  notas_internas TEXT NULL,
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  CONSTRAINT fk_citas_cliente
    FOREIGN KEY (cliente_id) REFERENCES clientes(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,

  CONSTRAINT fk_citas_servicio
    FOREIGN KEY (servicio_id) REFERENCES servicios(id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,

  CONSTRAINT fk_citas_cuestionario
    FOREIGN KEY (cuestionario_id) REFERENCES cuestionarios_salud(id)
    ON DELETE SET NULL
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Índices útiles para el panel
CREATE INDEX idx_citas_fecha_estado
  ON citas (fecha, estado);

CREATE INDEX idx_citas_cliente
  ON citas (cliente_id);

-- =====================================================
--  TABLA: pagos
--  Control de pagos por cita (efectivo / transferencia)
-- =====================================================

CREATE TABLE pagos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cita_id INT NOT NULL,

  monto DECIMAL(10,2) NOT NULL,
  metodo ENUM('efectivo','transferencia') NOT NULL,

  estado ENUM('pendiente','pendiente_validacion','pagado','rechazado')
         NOT NULL DEFAULT 'pendiente',

  comprobante_url VARCHAR(255) DEFAULT NULL,     -- ruta del archivo subido
  referencia_cliente VARCHAR(100) DEFAULT NULL,  -- ej. últimos 4 dígitos, folio, etc.
  fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  fecha_confirmacion TIMESTAMP NULL,
  notas TEXT NULL,

  CONSTRAINT fk_pagos_cita
    FOREIGN KEY (cita_id) REFERENCES citas(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE INDEX idx_pagos_cita
  ON pagos (cita_id);

-- =====================================================
--  TABLA: config_horarios (horario base del spa)
--  Opcional pero ya listo
-- =====================================================

CREATE TABLE config_horarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  dia_semana ENUM('lunes','martes','miercoles','jueves','viernes','sabado','domingo')
             NOT NULL,
  hora_inicio TIME NOT NULL,
  hora_fin TIME NOT NULL,
  activo TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
--  TABLA: bloqueos_horarios
--  Días/horas especiales donde no se atiende
-- =====================================================

CREATE TABLE bloqueos_horarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  fecha DATE NOT NULL,
  hora_inicio TIME DEFAULT NULL,
  hora_fin TIME DEFAULT NULL,
  motivo VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
--  FIN DE LA ESTRUCTURA INICIAL
-- =====================================================
