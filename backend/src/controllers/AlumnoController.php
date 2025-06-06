<?php
// Controlador Alumno: Gestiona las peticiones HTTP para la entidad alumnos

require_once __DIR__ . '/../models/Alumno.php';
require_once __DIR__ . '/../../vendor/autoload.php';
use Dompdf\Dompdf;

class AlumnoController
{

    // Maneja GET /alumnos y GET /alumnos/{id}
    public function handleGet($id = null)
    {
        $mensajes = require __DIR__ . '/../../vistas/mensajes.php';
        session_start();
        error_log(print_r($_SESSION, true));
        if (!isset($_SESSION['usuario_id'])) {
        VistaJson::error($mensajes['no_autenticado'], 401);
        return;
         }
        
        $mensajes = require __DIR__ . '/../../vistas/mensajes.php';
        header('Content-Type: application/json');
        if ($id) {
            $alumno = Alumno::getById($id);
            if ($alumno) {
                VistaJson::respuesta($alumno);
            } else {
                VistaJson::error($mensajes['alumno_no_encontrado'], 404);
            }
        } else {
            $alumnos = Alumno::getAll();
            VistaJson::respuesta($alumnos);
        }
    }

    // Maneja POST /alumnos
    public function handlePost()
    {
        $mensajes = require __DIR__ . '/../../vistas/mensajes.php';
        session_start();
        error_log(print_r($_SESSION, true));
        if (!isset($_SESSION['usuario_id'])) {
        VistaJson::error($mensajes['no_autenticado'], 401);
        return;
         }
        $data = json_decode(file_get_contents('php://input'), true);
        if (Alumno::create($data)) {
            VistaJson::exito($mensajes['alumno_creado']);
        } else {
            VistaJson::error($mensajes['alumno_no_creado'], 400);
        }
    }

    // Maneja PUT /alumnos/{id}
    public function handlePut($id)
    {
        $mensajes = require __DIR__ . '/../../vistas/mensajes.php';
        session_start();
        error_log(print_r($_SESSION, true));
        if (!isset($_SESSION['usuario_id'])) {
        VistaJson::error($mensajes['no_autenticado'], 401);
        return;
         }
        $data = json_decode(file_get_contents('php://input'), true);
        if (Alumno::update($id, $data)) {
            VistaJson::exito($mensajes['alumno_actualizado']);
        } else {
            VistaJson::error($mensajes['alumno_no_actualizado'], 400);
        }
    }

    // Maneja DELETE /alumnos/{id}
    public function handleDelete($id)
    {
        $mensajes = require __DIR__ . '/../../vistas/mensajes.php';
        session_start();
        error_log(print_r($_SESSION, true));
        if (!isset($_SESSION['usuario_id'])) {
        VistaJson::error($mensajes['no_autenticado'], 401);
        return;
         }
        if (Alumno::delete($id)) {
            VistaJson::exito($mensajes['alumno_eliminado']);
        } else {
            VistaJson::error($mensajes['alumno_no_eliminado'], 400);
        }
    }

/*     // Reporte de  alumnos dados de alta en el sistema
    public function handleReportePDF()
    {
        session_start();
        $mensajes = require __DIR__ . '/../../vistas/mensajes.php';
        if (!isset($_SESSION['usuario_id'])) {
            VistaJson::error($mensajes['no_autenticado'], 401);
            return;
        }

        $reporte = Alumno::getReporte();

        // Generar HTML para el reporte
        $html = '<h2>Reporte de Alumnos</h2>';
        $html .= '<table border="1" cellpadding="5" cellspacing="0">';
        $html .= '<thead><tr>
            <th>ID</th>
            <th>Alumno</th>
            <th>Fecha de nacimiento</th>
            <th>Email</th>
            <th>Telefono</th>
            <th>Grupo</th>
        </tr></thead><tbody>';
        foreach ($reporte as $fila) {
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($fila['id']) . '</td>';
            $html .= '<td>' . htmlspecialchars($fila['nombre_alumno'] . ' ' . $fila['apellido_alumno']) . '</td>';
            $html .= '<td>' . htmlspecialchars($fila['fecha_nacimiento_alumno']) . '</td>';
            $html .= '<td>' . htmlspecialchars($fila['email_alumno']) . '</td>';
            $html .= '<td>' . htmlspecialchars($fila['telefono_alumno']) . '</td>';
            $html .= '<td>' . htmlspecialchars($fila['nombre_grupo']) . '</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody></table>';

        // Generar PDF
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        // Descargar el PDF
        $dompdf->stream('reporte_calificaciones.pdf', ['Attachment' => true]);
    } */

        public function handleReportePDF()
    {
        session_start();
        $mensajes = require __DIR__ . '/../../vistas/mensajes.php';
        if (!isset($_SESSION['usuario_id'])) {
            VistaJson::error($mensajes['no_autenticado'], 401);
            return;
        }

        $reporte = Alumno::getReporte();
        $fechaActual = date('d/m/Y');
        
        // Estilos CSS para el reporte
        $css = '
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 20px;
                color: #333;
            }
            .header {
                text-align: center;
                margin-bottom: 30px;
                border-bottom: 2px solid #4a86e8;
                padding-bottom: 10px;
            }
            .header h1 {
                color: #4a86e8;
                margin: 0;
                font-size: 24px;
            }
            .header p {
                margin: 5px 0;
                color: #666;
                font-size: 14px;
            }
            .logo {
                text-align: center;
                margin-bottom: 10px;
            }
            .info {
                margin-bottom: 20px;
                font-size: 14px;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
                font-size: 12px;
            }
            th {
                background-color: #4a86e8;
                color: white;
                font-weight: bold;
                text-align: left;
                padding: 10px;
                border: 1px solid #ddd;
            }
            td {
                padding: 8px;
                border: 1px solid #ddd;
            }
            tr:nth-child(even) {
                background-color: #f2f2f2;
            }
            .footer {
                margin-top: 30px;
                text-align: center;
                font-size: 12px;
                color: #666;
                border-top: 1px solid #ddd;
                padding-top: 10px;
            }
            .page-number {
                text-align: right;
                font-size: 12px;
                color: #666;
            }
            .calificacion {
                font-weight: bold;
                text-align: center;
            }
        </style>';

        // Generar HTML para el reporte
        $html = '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Reporte de Calificaciones</title>
            ' . $css . '
        </head>
        <body>
            <div class="header">
                <div class="logo">
                    <div style="font-size: 40px; font-weight: bold; color: #4a86e8;">ESCUELA</div>
                </div>
                <h1>REPORTE DE ALUMNOS</h1>
                <p>Sistema de Gestión Académica</p>
            </div>
            
            <div class="info">
                <p><strong>Fecha de generación:</strong> ' . $fechaActual . '</p>
                <p><strong>Generado por:</strong> ' . (isset($_SESSION['usuario_nombre']) ? htmlspecialchars($_SESSION['usuario_nombre']) : 'Usuario del Sistema') . '</p>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Fecha de nacimiento</th>
                        <th>Grupo</th>
                        <th>Email</th>
                        <th>Telefono</th>
                    </tr>
                </thead>
                <tbody>';
        
        foreach ($reporte as $fila) {
            
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($fila['id']) . '</td>';
            $html .= '<td>' . htmlspecialchars($fila['nombre_alumno']) . '</td>';
            $html .= '<td>' . htmlspecialchars($fila['apellido_alumno']) . '</td>';
            $html .= '<td>' . htmlspecialchars($fila['fecha_nacimiento_alumno']) . '</td>';
            $html .= '<td>' . htmlspecialchars($fila['nombre_grupo']) . '</td>';
            $html .= '<td>' . htmlspecialchars($fila['email_alumno']) . '</td>';
            $html .= '<td>' . htmlspecialchars($fila['telefono_alumno']) . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody>
            </table>
            
            <div class="footer">
                <p>Este documento es un reporte oficial del Sistema de Gestión Académica.</p>
                <p>© ' . date('Y') . ' - Todos los derechos reservados</p>
            </div>
            
            <div class="page-number">
                Página 1
            </div>
        </body>
        </html>';

        // Generar PDF
        $dompdf = new Dompdf([
            'enable_remote' => true,
            'enable_css_float' => true,
            'enable_html5_parser' => true
        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        // Descargar el PDF
        $dompdf->stream('reporte_alumnos.pdf', ['Attachment' => true]);

    }
}