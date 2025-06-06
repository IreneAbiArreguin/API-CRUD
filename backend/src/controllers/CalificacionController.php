<?php
// Controlador Calificacion: Gestiona las peticiones HTTP para la entidad calificaciones

require_once __DIR__ . '/../models/Calificacion.php';
require_once __DIR__ . '/../../vendor/autoload.php';
use Dompdf\Dompdf;

class CalificacionController
{
    // Maneja GET /calificaciones y GET /calificaciones/{id}
    public function handleGet($id = null)
    {
        session_start();
        $mensajes = require __DIR__ . '/../../vistas/mensajes.php';
        if (!isset($_SESSION['usuario_id'])) {
            VistaJson::error($mensajes['no_autenticado'], 401);
            return;
        }
        
     //   header('Content-Type: application/json');
        if ($id) {
            $calificacion = Calificacion::getById($id);
            if ($calificacion) {
                VistaJson::respuesta($calificacion);
            } else {
                VistaJson::error($mensajes['calificacion_no_encontrada'], 404);
            }
        } else {
            $calificaciones = Calificacion::getAll();
            VistaJson::respuesta($calificaciones);
        }
    }

    // Maneja POST /calificaciones
    public function handlePost()
    {
        session_start();
        $mensajes = require __DIR__ . '/../../vistas/mensajes.php';
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'profesor') {
            VistaJson::error($mensajes['no_autenticado'], 403);
            return;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        if (
        isset($data['id_alumno']) &&
        isset($data['id_materia']) &&
        isset($data['calificacion']) &&
        isset($data['fecha'])
        ) {
            // Usa el id del profesor de la sesión
            $data['id_profesor'] = $_SESSION['datos_usuario']['id'];
            if (Calificacion::create($data)) {
                VistaJson::exito($mensajes['calificacion_creada'], [], 201);
            } else {
                VistaJson::error($mensajes['calificacion_no_creada']);
            }
        } else {
            VistaJson::error($mensajes['Datos_incompletos'], 400);
        }
    }

    // Maneja PUT /calificaciones/{id}
    public function handlePut($id)
    {
        session_start();
        $mensajes = require __DIR__ . '/../../vistas/mensajes.php';
        if (!isset($_SESSION['usuario_id'])) {
            VistaJson::error($mensajes['no_autenticado'], 401);
            return;
        }
        $data = json_decode(file_get_contents('php://input'), true);
        if (Calificacion::update($id, $data)) {
            VistaJson::exito($mensajes['calificacion_actualizada']);
        } else {
            VistaJson::error($mensajes['calificacion_no_actualizada']);
        }
    }

    // Maneja DELETE /calificaciones/{id}
    public function handleDelete($id)
    {
        session_start();
        $mensajes = require __DIR__ . '/../../vistas/mensajes.php';
        if (!isset($_SESSION['usuario_id'])) {
            VistaJson::error($mensajes['no_autenticado'], 401);
            return;
        }
        if (Calificacion::delete($id)) {
            VistaJson::exito($mensajes['calificacion_eliminada']);
        } else {
            VistaJson::error($mensajes['calificacion_no_eliminada']);
        }
    }

    // Maneja GET /calificaciones/reporte/pdf
/*     public function handleReportePDF()
    {
        session_start();
        $mensajes = require __DIR__ . '/../../vistas/mensajes.php';
        if (!isset($_SESSION['usuario_id'])) {
            VistaJson::error($mensajes['no_autenticado'], 401);
            return;
        }

        $reporte = Calificacion::getReporte();

        // Generar HTML para el reporte
        $html = '<h2>Reporte de Calificaciones</h2>';
        $html .= '<table border="1" cellpadding="5" cellspacing="0">';
        $html .= '<thead><tr>
            <th>ID</th>
            <th>Alumno</th>
            <th>Materia</th>
            <th>Profesor</th>
            <th>Calificación</th>
            <th>Fecha</th>
        </tr></thead><tbody>';
        foreach ($reporte as $fila) {
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($fila['id']) . '</td>';
            $html .= '<td>' . htmlspecialchars($fila['nombre_alumno']) . '</td>';
            $html .= '<td>' . htmlspecialchars($fila['nombre_materia']) . '</td>';
            $html .= '<td>' . htmlspecialchars($fila['nombre_profesor']) . '</td>';
            $html .= '<td>' . htmlspecialchars($fila['calificacion']) . '</td>';
            $html .= '<td>' . htmlspecialchars($fila['fecha']) . '</td>';
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

        $reporte = Calificacion::getReporte();
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
                <h1>REPORTE DE CALIFICACIONES</h1>
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
                        <th>Alumno</th>
                        <th>Materia</th>
                        <th>Profesor</th>
                        <th>Calificación</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>';
        
        foreach ($reporte as $fila) {
            // Determinar color según calificación
            $calificacionColor = '';
            if ($fila['calificacion'] < 6) {
                $calificacionColor = 'color: red;';
            } elseif ($fila['calificacion'] >= 9) {
                $calificacionColor = 'color: green;';
            }
            
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($fila['id']) . '</td>';
            $html .= '<td>' . htmlspecialchars($fila['nombre_alumno']) . '</td>';
            $html .= '<td>' . htmlspecialchars($fila['nombre_materia']) . '</td>';
            $html .= '<td>' . htmlspecialchars($fila['nombre_profesor']) . '</td>';
            $html .= '<td class="calificacion" style="' . $calificacionColor . '">' . htmlspecialchars($fila['calificacion']) . '</td>';
            $html .= '<td>' . htmlspecialchars($fila['fecha']) . '</td>';
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
        $dompdf->stream('reporte_calificaciones.pdf', ['Attachment' => true]);

    }
}