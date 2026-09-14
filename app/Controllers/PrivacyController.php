<?php
namespace App\Controllers;

use App\Core\Controller;

class PrivacyController extends Controller
{
    public function index(): void
    {
        $canonical = $this->request->getScheme() . '://' . $this->request->getHost() . $this->request->getBaseDir() . '/privacy';

        $this->render('privacy/index', [
            'pageTitle' => 'Kebijakan Privasi — Desadroid IT Consultant & Studio',
            'metaDescription' => 'Kebijakan privasi dan perlindungan data pengunjung serta mitra kerja di Desadroid.',
            'metaKeywords' => 'kebijakan privasi desadroid, privacy policy desadroid, keamanan data desadroid',
            'canonical' => $canonical
        ]);
    }
}
