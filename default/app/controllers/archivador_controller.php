<?php 

class ArchivadorController extends ApplicationController
{
    /**
     * Accion para subir archivo
     *
     */
    public function subir()
    {
        $archivador = Load::model('archivador');
        $archivador->guardar();
    }
}